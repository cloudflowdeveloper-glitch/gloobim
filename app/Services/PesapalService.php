<?php

namespace App\Services;

class PesapalService
{
    private string $consumerKey;
    private string $consumerSecret;
    private string $baseUrl;

    public function __construct()
    {
        $env = getenv('PESAPAL_ENV') ?: 'sandbox';
        $this->consumerKey    = getenv('PESAPAL_CONSUMER_KEY') ?: '';
        $this->consumerSecret = getenv('PESAPAL_CONSUMER_SECRET') ?: '';

        $this->baseUrl = $env === 'production'
            ? 'https://pay.pesapal.com/v3'
            : 'https://cybqa.pesapal.com/pesapalv3';
    }

    public function isConfigured(): bool
    {
        return !empty($this->consumerKey) && !empty($this->consumerSecret);
    }

    /**
     * Get OAuth token for PesaPal API v3
     */
    private function getToken(): string
    {
        $ch = curl_init($this->baseUrl . '/api/Auth/RequestToken');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'consumer_key' => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200 || empty($data['token'])) {
            throw new \Exception('PesaPal auth failed: ' . ($data['message'] ?? 'Unknown error'));
        }

        return $data['token'];
    }

    /**
     * Register IPN URL — required before submitting orders
     */
    public function registerIpn(string $ipnUrl): array
    {
        $token = $this->getToken();

        $ch = curl_init($this->baseUrl . '/api/URLSetup/RegisterIPN');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'url' => $ipnUrl,
                'ipn_notification_type' => 'POST',
            ]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true) ?: [];
    }

    /**
     * Submit order request to PesaPal
     * Returns redirect URL for the customer
     */
    public function submitOrder(
        string $orderId,
        float $amount,
        string $currency,
        string $description,
        string $callbackUrl,
        string $customerEmail,
        string $customerPhone,
        string $customerName
    ): array {
        if (!$this->isConfigured()) {
            throw new \Exception('PesaPal not configured. Set PESAPAL_CONSUMER_KEY and PESAPAL_CONSUMER_SECRET in .env');
        }

        $token = $this->getToken();

        $body = [
            'id' => $orderId,
            'currency' => $currency,
            'amount' => (float)$amount,
            'description' => $description,
            'callback_url' => $callbackUrl,
            'notification_id' => $this->getIpnId(),
            'billing_address' => [
                'email_address' => $customerEmail,
                'phone_number' => $customerPhone,
                'first_name' => $customerName,
            ],
        ];

        $ch = curl_init($this->baseUrl . '/api/Transactions/SubmitOrderRequest');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200 || !empty($data['error'])) {
            throw new \Exception('PesaPal order error: ' . ($data['error']['message'] ?? $data['message'] ?? 'Unknown'));
        }

        return [
            'success' => true,
            'order_tracking_id' => $data['order_tracking_id'] ?? '',
            'redirect_url' => $data['redirect_url'] ?? '',
            'merchant_reference' => $data['merchant_reference'] ?? $orderId,
        ];
    }

    /**
     * Get transaction status
     */
    public function getTransactionStatus(string $orderTrackingId): array
    {
        $token = $this->getToken();

        $ch = curl_init($this->baseUrl . '/api/Transactions/GetTransactionStatus?orderTrackingId=' . urlencode($orderTrackingId));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $status = (int)($data['payment_status_description'] ?? 0);

        return [
            'status_code' => $status,
            'paid' => $status === 1, // 1 = COMPLETED
            'payment_method' => $data['payment_method'] ?? '',
            'amount' => $data['amount'] ?? 0,
            'confirmation_code' => $data['confirmation_code'] ?? '',
        ];
    }

    private function getIpnId(): string
    {
        // Returns the registered IPN ID from cached config
        // In production, register IPN once and store the ID
        return getenv('PESAPAL_IPN_ID') ?: '';
    }
}
