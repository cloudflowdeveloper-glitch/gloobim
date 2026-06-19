<?php

namespace App\Services;

use Core\Config;
use Core\Database;
use Core\Session;

class OAuthService
{
    protected string $provider;

    public function __construct(string $provider)
    {
        $this->provider = $provider;
    }

    public function getConfig(): array
    {
        return Config::get("oauth.{$this->provider}", []);
    }

    public function getAuthorizationUrl(): string
    {
        $config = $this->getConfig();

        $params = [
            'client_id' => $config['client_id'],
            'redirect_uri' => $config['redirect_uri'],
            'response_type' => 'code',
            'scope' => implode(' ', $config['scopes'] ?? []),
            'state' => $this->generateState(),
        ];

        if ($this->provider === 'x') {
            $params['code_challenge'] = $this->generateCodeChallenge();
            $params['code_challenge_method'] = 'S256';
        }

        return $config['auth_url'] . '?' . http_build_query($params);
    }

    public function handleCallback(string $code): ?array
    {
        $config = $this->getConfig();
        $tokens = $this->exchangeCodeForToken($code, $config);

        if (!$tokens) {
            return null;
        }

        $userInfo = $this->fetchUserInfo($tokens['access_token'], $config);

        if (!$userInfo) {
            return null;
        }

        $normalizedUser = $this->normalizeUserInfo($userInfo);

        $user = $this->findOrCreateUser($normalizedUser);

        return $user;
    }

    protected function exchangeCodeForToken(string $code, array $config): ?array
    {
        $postFields = [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect_uri' => $config['redirect_uri'],
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];

        $ch = curl_init($config['token_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postFields),
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            error_log("OAuth token exchange failed for {$this->provider}: HTTP {$httpCode} - {$response}");
            return null;
        }

        return json_decode($response, true);
    }

    protected function fetchUserInfo(string $accessToken, array $config): ?array
    {
        $url = $config['userinfo_url'] ?? '';
        if (!$url) {
            return null;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Accept: application/json',
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            error_log("OAuth userinfo fetch failed for {$this->provider}: HTTP {$httpCode}");
            return null;
        }

        return json_decode($response, true);
    }

    protected function normalizeUserInfo(array $info): array
    {
        return match ($this->provider) {
            'google' => [
                'provider' => 'google',
                'provider_id' => $info['sub'] ?? '',
                'name' => $info['name'] ?? '',
                'email' => $info['email'] ?? '',
                'avatar' => $info['picture'] ?? '',
            ],
            'facebook' => [
                'provider' => 'facebook',
                'provider_id' => $info['id'] ?? '',
                'name' => $info['name'] ?? '',
                'email' => $info['email'] ?? '',
                'avatar' => $info['picture']['data']['url'] ?? '',
            ],
            'x' => [
                'provider' => 'x',
                'provider_id' => $info['data']['id'] ?? '',
                'name' => $info['data']['name'] ?? '',
                'email' => $info['data']['username'] ?? '' . '@x.com',
                'avatar' => $info['data']['profile_image_url'] ?? '',
            ],
            'apple' => [
                'provider' => 'apple',
                'provider_id' => $info['sub'] ?? '',
                'name' => $info['name'] ?? 'Apple User',
                'email' => $info['email'] ?? '',
                'avatar' => '',
            ],
            default => [
                'provider' => $this->provider,
                'provider_id' => '',
                'name' => '',
                'email' => '',
                'avatar' => '',
            ],
        };
    }

    protected function findOrCreateUser(array $socialUser): array
    {
        $existing = Database::queryOne(
            "SELECT * FROM users WHERE provider = ? AND provider_id = ? LIMIT 1",
            [$socialUser['provider'], $socialUser['provider_id']]
        );

        if ($existing) {
            return $existing;
        }

        if ($socialUser['email']) {
            $existingByEmail = Database::queryOne(
                "SELECT * FROM users WHERE email = ? LIMIT 1",
                [$socialUser['email']]
            );

            if ($existingByEmail) {
                Database::update('users', [
                    'provider' => $socialUser['provider'],
                    'provider_id' => $socialUser['provider_id'],
                    'avatar' => $socialUser['avatar'] ?: $existingByEmail['avatar'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ], 'id = ?', [$existingByEmail['id']]);

                return Database::queryOne("SELECT * FROM users WHERE id = ? LIMIT 1", [$existingByEmail['id']]);
            }
        }

        $username = $this->generateUsername($socialUser['name']);
        $userId = Database::insert('users', [
            'name' => $socialUser['name'],
            'username' => $username,
            'email' => $socialUser['email'],
            'password' => password_hash(bin2hex(random_bytes(32)), PASSWORD_BCRYPT),
            'provider' => $socialUser['provider'],
            'provider_id' => $socialUser['provider_id'],
            'avatar' => $socialUser['avatar'],
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return ['id' => $userId, 'name' => $socialUser['name'], 'username' => $username, 'email' => $socialUser['email']];
    }

    protected function generateUsername(string $name): string
    {
        $base = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($name));
        $username = $base . rand(100, 999);

        $exists = Database::queryOne("SELECT id FROM users WHERE username = ? LIMIT 1", [$username]);
        while ($exists) {
            $username = $base . rand(1000, 99999);
            $exists = Database::queryOne("SELECT id FROM users WHERE username = ? LIMIT 1", [$username]);
        }

        return $username;
    }

    protected function generateState(): string
    {
        $state = bin2hex(random_bytes(32));
        Session::set('oauth_state', $state);
        Session::set('oauth_provider', $this->provider);
        return $state;
    }

    protected function generateCodeChallenge(): string
    {
        $verifier = bin2hex(random_bytes(32));
        Session::set('oauth_code_verifier', $verifier);
        return rtrim(strtr(base64_encode(hash('sha256', $verifier, true)), '+/', '-_'), '=');
    }

    public function isConfigured(): bool
    {
        $config = $this->getConfig();
        return !empty($config['client_id']) && !empty($config['client_secret']);
    }
}
