<?php

namespace App\Services;

class PaystackService
{
    private string $secretKey;
    private string $publicKey;
    private string $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = getenv('PAYSTACK_SECRET_KEY') ?: '';
        $this->publicKey = getenv('PAYSTACK_PUBLIC_KEY') ?: '';
    }

    public function isConfigured(): bool
    {
        return !empty($this->secretKey);
    }

    /**
     * Initialize a transaction — returns authorization URL
     */
    public function initializeTransaction(string $email, float $amount, string $reference, string $callbackUrl = ''): array
    {
        if (!$this->isConfigured()) {
            throw new \Exception('Paystack not configured. Set PAYSTACK_SECRET_KEY in .env');
        }

        $body = [
            'email' => $email,
            'amount' => (int) round($amount * 100), // Paystack uses kobo/cents
            'reference' => $reference,
            'callback_url' => $callbackUrl ?: url('/marketplace/checkout/success?order=' . $reference),
            'currency' => 'KES',
        ];

        $ch = curl_init($this->baseUrl . '/transaction/initialize');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->secretKey,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200 || empty($data['status'])) {
            throw new \Exception('Paystack error: ' . ($data['message'] ?? 'Unknown error'));
        }

        return [
            'success' => true,
            'authorization_url' => $data['data']['authorization_url'],
            'access_code' => $data['data']['access_code'],
            'reference' => $data['data']['reference'],
        ];
    }

    /**
     * Verify a transaction
     */
    public function verifyTransaction(string $reference): array
    {
        $ch = curl_init($this->baseUrl . '/transaction/verify/' . urlencode($reference));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $this->secretKey],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $status = $data['data']['status'] ?? '';

        return [
            'success' => $status === 'success',
            'status' => $status,
            'amount' => ($data['data']['amount'] ?? 0) / 100,
            'reference' => $reference,
            'gateway_response' => $data['data']['gateway_response'] ?? '',
        ];
    }
}
