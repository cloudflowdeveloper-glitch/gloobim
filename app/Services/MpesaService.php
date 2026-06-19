<?php

namespace App\Services;

class MpesaService
{
    private string $consumerKey;
    private string $consumerSecret;
    private string $passkey;
    private string $shortcode;
    private string $callbackUrl;
    private string $environment; // sandbox | production

    public function __construct()
    {
        $this->consumerKey    = getenv('MPESA_CONSUMER_KEY') ?: '';
        $this->consumerSecret = getenv('MPESA_CONSUMER_SECRET') ?: '';
        $this->passkey        = getenv('MPESA_PASSKEY') ?: '';
        $this->shortcode      = getenv('MPESA_SHORTCODE') ?: '174379';
        $this->callbackUrl    = getenv('MPESA_CALLBACK_URL') ?: url('/marketplace/checkout/payment-callback');
        $this->environment    = getenv('MPESA_ENV') ?: 'sandbox';
    }

    private function baseUrl(): string
    {
        return $this->environment === 'production'
            ? 'https://api.safaricom.co.ke'
            : 'https://sandbox.safaricom.co.ke';
    }

    /**
     * Validate Kenyan phone number format
     * Accepts: 2547XXXXXXXX, 07XXXXXXXX, +2547XXXXXXXX, 7XXXXXXXX
     * Returns normalized 2547XXXXXXXX or throws
     */
    public static function validatePhone(string $phone): string
    {
        // Strip all non-digits
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Must be at least 9 digits (7XX XXX XXX)
        if (strlen($phone) < 9) {
            throw new \Exception('Phone number too short. Must be a valid Kenyan number.');
        }

        // Handle 254-prefixed numbers
        if (str_starts_with($phone, '254')) {
            if (strlen($phone) !== 12) {
                throw new \Exception('Invalid 254 number. Must be 12 digits (2547XXXXXXXX).');
            }
            $local = substr($phone, 3); // 7XXXXXXXX
        }
        // Handle 0-prefixed
        elseif (str_starts_with($phone, '0')) {
            $local = substr($phone, 1); // 7XXXXXXXX
            $phone = '254' . $local;
        }
        // Handle 7-prefixed (no country code)
        elseif (str_starts_with($phone, '7')) {
            $local = $phone;
            $phone = '254' . $local;
        }
        // Handle +254
        elseif (strlen($phone) === 12 && str_starts_with($phone, '254')) {
            $local = substr($phone, 3);
        }
        else {
            throw new \Exception('Invalid phone format. Must be a Kenyan number (07XX, 2547XX, or +2547XX).');
        }

        // Validate the local number starts with 7 (Safaricom ranges)
        if (!str_starts_with($local, '7')) {
            throw new \Exception('Not a valid Kenyan mobile number. Must start with 07 or +2547.');
        }

        // Validate length (9 digits after prefix = 7XX XXX XXX)
        if (strlen($local) !== 9) {
            throw new \Exception('Phone number must be 9 digits after country code.');
        }

        // Validate Safaricom prefixes (M-Pesa is Safaricom only)
        $safaricomPrefixes = ['070', '071', '072', '074', '075', '076', '077', '078', '079',
                              '010', '011', '012', '014', '015', '016', '017', '018', '019'];
        $prefix = substr($phone, 0, 5); // 2547X

        $isSafaricom = false;
        foreach ($safaricomPrefixes as $sp) {
            $spFull = '254' . substr($sp, 1);
            if (str_starts_with($phone, $spFull)) {
                $isSafaricom = true;
                break;
            }
        }

        if (!$isSafaricom) {
            throw new \Exception('This number may not be on Safaricom. M-Pesa requires a Safaricom line.');
        }

        return $phone; // Returns normalized 2547XXXXXXXX
    }

    /**
     * Generate M-Pesa API access token
     */
    public function getAccessToken(): string
    {
        if (empty($this->consumerKey) || empty($this->consumerSecret)) {
            throw new \Exception('M-Pesa API credentials not configured. Set MPESA_CONSUMER_KEY and MPESA_CONSUMER_SECRET in .env');
        }

        $url = $this->baseUrl() . '/oauth/v1/generate?grant_type=client_credentials';
        $credentials = base64_encode($this->consumerKey . ':' . $this->consumerSecret);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Basic ' . $credentials],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception('M-Pesa auth failed (HTTP ' . $httpCode . '): ' . $response);
        }

        $data = json_decode($response, true);
        if (empty($data['access_token'])) {
            throw new \Exception('M-Pesa auth failed: no access token returned');
        }

        return $data['access_token'];
    }

    /**
     * Initiate STK Push to customer's phone
     */
    public function stkPush(string $phone, float $amount, string $accountReference, string $description = 'DTTube Order'): array
    {
        $phone = self::validatePhone($phone);
        $token = $this->getAccessToken();

        $timestamp = date('YmdHis');
        $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $body = [
            'BusinessShortCode' => $this->shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => (int) round($amount),
            'PartyA'            => $phone,
            'PartyB'            => $this->shortcode,
            'PhoneNumber'       => $phone,
            'CallBackURL'       => $this->callbackUrl,
            'AccountReference'  => substr($accountReference, 0, 12),
            'TransactionDesc'   => substr($description, 0, 13),
        ];

        $url = $this->baseUrl() . '/mpesa/stkpush/v1/processrequest';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200 || !empty($data['errorCode'])) {
            $errMsg = $data['errorMessage'] ?? ($data['ResponseDescription'] ?? 'Unknown error');
            throw new \Exception('M-Pesa STK Push failed: ' . $errMsg);
        }

        return [
            'success' => true,
            'merchant_request_id' => $data['MerchantRequestID'] ?? '',
            'checkout_request_id' => $data['CheckoutRequestID'] ?? '',
            'response_code' => $data['ResponseCode'] ?? '',
            'response_description' => $data['ResponseDescription'] ?? '',
            'customer_message' => $data['CustomerMessage'] ?? 'Check your phone for the M-Pesa PIN prompt',
        ];
    }

    /**
     * Query STK Push status
     */
    public function stkQuery(string $checkoutRequestId): array
    {
        $token = $this->getAccessToken();
        $timestamp = date('YmdHis');
        $password  = base64_encode($this->shortcode . $this->passkey . $timestamp);

        $body = [
            'BusinessShortCode' => $this->shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ];

        $url = $this->baseUrl() . '/mpesa/stkpushquery/v1/query';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true) ?: [];
    }
}
