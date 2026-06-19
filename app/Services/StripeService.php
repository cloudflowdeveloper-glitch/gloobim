<?php

namespace App\Services;

class StripeService
{
    private string $secretKey;
    private string $publishableKey;

    public function __construct()
    {
        $this->secretKey = getenv('STRIPE_SECRET_KEY') ?: '';
        $this->publishableKey = getenv('STRIPE_PUBLISHABLE_KEY') ?: '';
    }

    public function isConfigured(): bool
    {
        return !empty($this->secretKey) && str_starts_with($this->secretKey, 'sk_');
    }

    /**
     * Create a Stripe Checkout Session
     */
    public function createCheckoutSession(array $items, string $orderNumber, string $successUrl, string $cancelUrl): array
    {
        if (!$this->isConfigured()) {
            throw new \Exception('Stripe not configured. Set STRIPE_SECRET_KEY in .env');
        }

        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $item['title']],
                    'unit_amount' => (int) round(((float)$item['price']) * 100),
                ],
                'quantity' => (int)$item['quantity'],
            ];
        }

        $body = [
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => $orderNumber,
            'metadata' => ['order_number' => $orderNumber],
        ];

        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($body),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->secretKey,
                'Content-Type: application/x-www-form-urlencoded',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200 || !empty($data['error'])) {
            throw new \Exception('Stripe error: ' . ($data['error']['message'] ?? 'Unknown error'));
        }

        return [
            'success' => true,
            'session_id' => $data['id'],
            'checkout_url' => $data['url'],
        ];
    }

    /**
     * Verify a Stripe session by ID
     */
    public function retrieveSession(string $sessionId): array
    {
        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions/' . urlencode($sessionId));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $this->secretKey],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return [
            'success' => ($data['payment_status'] ?? '') === 'paid',
            'payment_status' => $data['payment_status'] ?? 'unpaid',
            'amount_total' => ($data['amount_total'] ?? 0) / 100,
            'order_number' => $data['metadata']['order_number'] ?? ($data['client_reference_id'] ?? ''),
        ];
    }
}
