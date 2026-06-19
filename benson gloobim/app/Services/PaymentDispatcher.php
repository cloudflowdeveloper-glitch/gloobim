<?php

namespace App\Services;

use Core\Database;

class PaymentDispatcher
{
    /**
     * Process seller payouts when an order payment is confirmed.
     * Credits each seller's wallet with their share of the order.
     */
    public static function creditSellers(string $orderNumber, ?float $platformFeePercent = null): array
    {
        $platformFeePercent = $platformFeePercent ?? 5.0; // 5% platform fee

        // Get order with items including seller info
        $order = Database::queryOne(
            "SELECT * FROM orders WHERE order_number = ?",
            [$orderNumber]
        );

        if (!$order) {
            return ['error' => 'Order not found'];
        }

        if ($order['payment_status'] !== 'paid') {
            return ['error' => 'Order payment not yet confirmed'];
        }

        // Check if already disbursed (avoid double-pay)
        $alreadyPaid = Database::queryOne(
            "SELECT id FROM wallet_transactions WHERE reference = ? AND type = 'earnings'",
            ['order_' . $orderNumber]
        );
        if ($alreadyPaid) {
            return ['already_disbursed' => true, 'message' => 'Sellers already credited for this order'];
        }

        // Get order items with seller IDs
        $items = Database::query(
            "SELECT oi.*, ml.user_id AS seller_id
             FROM order_items oi
             LEFT JOIN marketplace_listings ml ON oi.listing_id = ml.id
             WHERE oi.order_id = ?",
            [$order['id']]
        );

        $results = [];
        $totalCredited = 0;

        foreach ($items as $item) {
            $sellerId = $item['seller_id'] ?? null;
            if (!$sellerId) continue;

            $itemTotal = (float)$item['price'] * (int)$item['quantity'];
            $fee = round($itemTotal * ($platformFeePercent / 100), 2);
            $sellerEarnings = $itemTotal - $fee;

            // Get or create wallet
            $wallet = Database::queryOne(
                "SELECT * FROM wallets WHERE user_id = ?",
                [$sellerId]
            );

            if (!$wallet) {
                Database::insert('wallets', [
                    'user_id' => $sellerId,
                    'balance' => 0,
                    'currency' => $order['currency'] ?? 'KES',
                ]);
                $walletId = Database::queryOne("SELECT id FROM wallets WHERE user_id = ?", [$sellerId])['id'];
            } else {
                $walletId = $wallet['id'];
            }

            // Credit wallet
            Database::execute(
                "UPDATE wallets SET balance = balance + ?, updated_at = NOW() WHERE id = ?",
                [$sellerEarnings, $walletId]
            );

            // Record transaction
            Database::insert('wallet_transactions', [
                'wallet_id' => $walletId,
                'type' => 'earnings',
                'amount' => $sellerEarnings,
                'fee' => $fee,
                'status' => 'completed',
                'reference' => 'order_' . $orderNumber,
                'description' => 'Sale: ' . ($item['title'] ?? 'Product') . ' (Order #' . $orderNumber . ')',
                'recipient_id' => $sellerId,
            ]);

            $results[] = [
                'seller_id' => $sellerId,
                'item' => $item['title'],
                'item_total' => $itemTotal,
                'platform_fee' => $fee,
                'seller_earnings' => $sellerEarnings,
            ];

            $totalCredited += $sellerEarnings;
        }

        return [
            'success' => true,
            'order_number' => $orderNumber,
            'total_credited' => $totalCredited,
            'platform_fee_percent' => $platformFeePercent,
            'sellers_paid' => count($results),
            'details' => $results,
        ];
    }
}
