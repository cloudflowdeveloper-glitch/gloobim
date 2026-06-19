<?php

namespace App\Services;

class BinancePayService
{
    private string $apiKey;
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = getenv('BINANCE_API_KEY') ?: '';
        $this->secretKey = getenv('BINANCE_SECRET_KEY') ?: '';
        $this->baseUrl = 'https://bpay.binanceapi.com';
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->secretKey);
    }

    /**
     * Create a Binance Pay order
     */
    public function createOrder(string $orderId, float $amount, string $currency = 'USDT'): array
    {
        if (!$this->isConfigured()) {
            throw new \Exception('Binance Pay not configured. Set BINANCE_API_KEY and BINANCE_SECRET_KEY in .env');
        }

        $timestamp = (string)(time() * 1000);
        $nonce = bin2hex(random_bytes(16));

        $body = [
            'merchantTradeNo' => $orderId,
            'totalFee' => (string)$amount,
            'currency' => $currency,
            'productType' => 'Marketplace Order',
            'productName' => 'DTTube Order #' . $orderId,
        ];

        $payload = $timestamp . "\n" . $nonce . "\n" . json_encode($body) . "\n";
        $signature = strtoupper(hash_hmac('sha512', $payload, $this->secretKey));

        $ch = curl_init($this->baseUrl . '/binancepay/openapi/v2/order');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'BinancePay-Timestamp: ' . $timestamp,
                'BinancePay-Nonce: ' . $nonce,
                'BinancePay-Certificate-SN: ' . $this->apiKey,
                'BinancePay-Signature: ' . $signature,
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200 || $data['status'] !== 'SUCCESS') {
            throw new \Exception('Binance Pay error: ' . ($data['errorMessage'] ?? 'Unknown error'));
        }

        return [
            'success' => true,
            'prepay_id' => $data['data']['prepayId'] ?? '',
            'checkout_url' => $data['data']['checkoutUrl'] ?? '',
            'qrcode_link' => $data['data']['qrcodeLink'] ?? '',
        ];
    }
}
