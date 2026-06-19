<?php

namespace App\Http\Controllers;

use Core\Response;
use Core\Database;
use Core\Controller;
use Core\Auth;
use App\Services\MpesaService;
use App\Services\PaymentDispatcher;

class CheckoutController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        if (!$user) return $this->redirect('/login');

        // Get cart items
        $items = Database::query(
            "SELECT ci.id AS cart_id, ci.quantity, ml.*, u.username, u.name AS seller_name
             FROM cart_items ci
             INNER JOIN marketplace_listings ml ON ci.listing_id = ml.id
             INNER JOIN users u ON ml.user_id = u.id
             WHERE ci.user_id = ? AND ml.status = 'active'
             ORDER BY ci.created_at DESC",
            [$user['id']]
        );

        if (empty($items)) return $this->redirect('/marketplace/cart');

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float)$item['price'] * (int)$item['quantity'];
        }

        $shippingCost = $subtotal > 100 ? 0 : 10.00;
        $tax = round($subtotal * 0.16, 2);
        $total = $subtotal + $shippingCost + $tax;

        // Get enabled payment methods
        $paymentMethods = Database::query(
            "SELECT * FROM payment_methods WHERE is_enabled = 1 ORDER BY sort_order ASC"
        );

        return $this->view('marketplace.checkout', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'tax' => $tax,
            'total' => $total,
            'paymentMethods' => $paymentMethods,
            'user' => $user,
        ]);
    }

    public function placeOrder(): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            $data = $_POST;
        }

        $paymentMethod = $data['payment_method'] ?? '';
        $sameAsBilling = !empty($data['same_as_billing']);

        // Get cart items
        $items = Database::query(
            "SELECT ci.id AS cart_id, ci.quantity, ml.*
             FROM cart_items ci
             INNER JOIN marketplace_listings ml ON ci.listing_id = ml.id
             WHERE ci.user_id = ? AND ml.status = 'active'",
            [$user['id']]
        );

        if (empty($items)) {
            return $this->json(['error' => 'Your cart is empty'], 400);
        }

        // Verify payment method is enabled
        $pm = Database::queryOne(
            "SELECT * FROM payment_methods WHERE slug = ? AND is_enabled = 1",
            [$paymentMethod]
        );
        if (!$pm) {
            return $this->json(['error' => 'Selected payment method is not available'], 400);
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float)$item['price'] * (int)$item['quantity'];
        }
        $shippingCost = $subtotal > 100 ? 0 : 10.00;
        $tax = round($subtotal * 0.16, 2);
        $total = $subtotal + $shippingCost + $tax;

        $orderNumber = 'ORD-' . strtoupper(substr(md5(uniqid()), 0, 10));

        try {
            Database::beginTransaction();

            $orderId = Database::insert('orders', [
                'order_number' => $orderNumber,
                'user_id' => $user['id'],
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax' => $tax,
                'total' => $total,
                'currency' => $data['currency'] ?? 'USD',
                'billing_name' => $data['billing_name'] ?? $user['name'] ?? '',
                'billing_email' => $data['billing_email'] ?? $user['email'] ?? '',
                'billing_phone' => $data['billing_phone'] ?? '',
                'billing_address' => $data['billing_address'] ?? '',
                'billing_city' => $data['billing_city'] ?? '',
                'billing_state' => $data['billing_state'] ?? '',
                'billing_zip' => $data['billing_zip'] ?? '',
                'billing_country' => $data['billing_country'] ?? '',
                'delivery_name' => $sameAsBilling ? ($data['billing_name'] ?? '') : ($data['delivery_name'] ?? ''),
                'delivery_phone' => $sameAsBilling ? ($data['billing_phone'] ?? '') : ($data['delivery_phone'] ?? ''),
                'delivery_address' => $sameAsBilling ? ($data['billing_address'] ?? '') : ($data['delivery_address'] ?? ''),
                'delivery_city' => $sameAsBilling ? ($data['billing_city'] ?? '') : ($data['delivery_city'] ?? ''),
                'delivery_state' => $sameAsBilling ? ($data['billing_state'] ?? '') : ($data['delivery_state'] ?? ''),
                'delivery_zip' => $sameAsBilling ? ($data['billing_zip'] ?? '') : ($data['delivery_zip'] ?? ''),
                'delivery_country' => $sameAsBilling ? ($data['billing_country'] ?? '') : ($data['delivery_country'] ?? ''),
                'delivery_instructions' => $data['delivery_instructions'] ?? '',
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
            ]);

            foreach ($items as $item) {
                Database::insert('order_items', [
                    'order_id' => $orderId,
                    'listing_id' => $item['id'],
                    'title' => $item['title'],
                    'price' => (float)$item['price'],
                    'quantity' => (int)$item['quantity'],
                    'image_url' => $item['image_url'] ?? '',
                    'seller_id' => $item['user_id'] ?? null,
                ]);
            }

            // Clear cart
            Database::execute("DELETE FROM cart_items WHERE user_id = ?", [$user['id']]);

            Database::commit();

            return $this->json([
                'success' => true,
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'message' => 'Order placed successfully',
                'redirect' => '/marketplace/checkout/success?order=' . $orderNumber,
            ]);
        } catch (\Exception $e) {
            Database::rollback();
            return $this->json(['error' => 'Failed to place order: ' . $e->getMessage()], 500);
        }
    }

    public function success(): Response
    {
        $orderNumber = $_GET['order'] ?? '';
        $order = null;

        if ($orderNumber) {
            $order = Database::queryOne(
                "SELECT o.*, pm.display_name AS payment_display, pm.icon AS payment_icon
                 FROM orders o
                 LEFT JOIN payment_methods pm ON o.payment_method = pm.slug
                 WHERE o.order_number = ?",
                [$orderNumber]
            );
        }

        return $this->view('marketplace.checkout-success', ['order' => $order]);
    }

    public function orders(): Response
    {
        $user = Auth::user();
        if (!$user) return $this->redirect('/login');

        $orders = Database::query(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 50",
            [$user['id']]
        );

        return $this->view('marketplace.orders', ['orders' => $orders]);
    }

    public function paymentCallback(): Response
    {
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

        // Handle M-Pesa callback format
        $mpesaCallback = $input['Body']['stkCallback'] ?? null;
        if ($mpesaCallback) {
            $resultCode = (int)($mpesaCallback['ResultCode'] ?? 1);
            $checkoutRequestId = $mpesaCallback['CheckoutRequestID'] ?? '';

            // Find order by checkout request ID
            $order = Database::queryOne(
                "SELECT * FROM orders WHERE payment_reference = ?",
                [$checkoutRequestId]
            );

            if ($order && $resultCode === 0) {
                // Extract receipt from metadata
                $receipt = '';
                $items = $mpesaCallback['CallbackMetadata']['Item'] ?? [];
                foreach ($items as $item) {
                    if ($item['Name'] === 'MpesaReceiptNumber') $receipt = $item['Value'] ?? '';
                }

                Database::execute(
                    "UPDATE orders SET payment_status = 'paid', status = 'confirmed', payment_reference = ?, updated_at = NOW() WHERE id = ?",
                    [$receipt ?: $checkoutRequestId, $order['id']]
                );

                try {
                    PaymentDispatcher::creditSellers($order['order_number']);
                } catch (\Exception $e) {
                    error_log('Seller payout failed for ' . $order['order_number'] . ': ' . $e->getMessage());
                }

                return $this->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
            }

            return $this->json(['ResultCode' => 1, 'ResultDesc' => 'Failed']);
        }

        // Generic callback handler for other gateways
        $reference = $input['reference'] ?? $input['tx_ref'] ?? $input['order_number'] ?? '';
        $status = strtolower($input['status'] ?? '');
        $transactionId = $input['transaction_id'] ?? $input['id'] ?? '';

        if (!$reference) {
            return $this->json(['error' => 'Missing reference'], 400);
        }

        try {
            if (in_array($status, ['success', 'successful', 'completed', 'paid'])) {
                Database::execute(
                    "UPDATE orders SET payment_status = 'paid', status = 'confirmed', payment_reference = ?, updated_at = NOW() WHERE order_number = ?",
                    [$transactionId ?: $reference, $reference]
                );

                try { PaymentDispatcher::creditSellers($reference); }
                catch (\Exception $e) { error_log('Payout failed: ' . $e->getMessage()); }

                return $this->json(['success' => true, 'message' => 'Payment confirmed']);
            } elseif (in_array($status, ['failed', 'cancelled', 'abandoned'])) {
                Database::execute(
                    "UPDATE orders SET payment_status = 'failed', payment_reference = ?, updated_at = NOW() WHERE order_number = ?",
                    [$input['transaction_id'] ?? '', $reference]
                );
                return $this->json(['success' => false, 'message' => 'Payment failed']);
            }

            return $this->json(['success' => true, 'message' => 'Callback received']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    public function mpesaInitiate(): Response
    {
        $user = Auth::user();
        if (!$user) return $this->json(['error' => 'Login required'], 401);

        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $phone = $input['phone'] ?? '';
        $orderNumber = $input['order_number'] ?? '';
        $amount = (float)($input['amount'] ?? 0);

        if (!$phone || !$orderNumber || $amount <= 0) {
            return $this->json(['error' => 'Missing phone, order number, or amount'], 400);
        }

        try {
            $mpesa = new MpesaService();
            $normalizedPhone = MpesaService::validatePhone($phone);
            $result = $mpesa->stkPush($normalizedPhone, $amount, $orderNumber);

            Database::execute(
                "UPDATE orders SET payment_reference = ? WHERE order_number = ? AND user_id = ?",
                [$result['checkout_request_id'], $orderNumber, $user['id']]
            );

            return $this->json([
                'success' => true,
                'message' => 'STK Push sent to ' . $normalizedPhone . '. Check your phone and enter PIN.',
                'checkout_request_id' => $result['checkout_request_id'],
                'phone' => $normalizedPhone,
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
