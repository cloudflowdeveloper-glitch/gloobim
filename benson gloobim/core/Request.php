<?php

namespace Core;

class Request
{
    protected array $query = [];
    protected array $body = [];
    protected array $files = [];
    protected array $server = [];
    protected array $headers = [];

    public function __construct()
    {
        $this->query = $_GET;
        $this->body = $_POST;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        $this->headers = $this->extractHeaders();
    }

    protected function extractHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }

    public function method(): string
    {
        $method = $this->server['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'POST' && $this->has('_method')) {
            return strtoupper($this->input('_method'));
        }
        return $method;
    }

    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $position = strpos($uri, '?');
        if ($position !== false) {
            $uri = substr($uri, 0, $position);
        }
        return $uri;
    }

    public function fullUrl(): string
    {
        $scheme = (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') ? 'https' : 'http';
        return $scheme . '://' . ($this->server['HTTP_HOST'] ?? 'localhost') . ($this->server['REQUEST_URI'] ?? '/');
    }

    public function input(string $key, $default = null)
    {
        if (isset($this->body[$key])) {
            return $this->body[$key];
        }
        if (isset($this->query[$key])) {
            return $this->query[$key];
        }
        return $default;
    }

    public function query(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->body[$key]) || isset($this->query[$key]);
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function only(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            if ($this->has($key)) {
                $result[$key] = $this->input($key);
            }
        }
        return $result;
    }

    public function file(string $key)
    {
        return $this->files[$key] ?? null;
    }

    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    public function ip(): string
    {
        return $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public function userAgent(): string
    {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }

    public function isAjax(): bool
    {
        return $this->header('X-Requested-With') === 'XMLHttpRequest'
            || strpos($this->header('Accept', ''), 'application/json') !== false;
    }

    public function bearerToken(): ?string
    {
        $header = $this->header('Authorization', '');
        if (strpos($header, 'Bearer ') === 0) {
            return substr($header, 7);
        }
        return null;
    }

    public function validate(array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $fieldRules) {
            $value = $this->input($field);
            foreach ((array) $fieldRules as $rule) {
                $error = $this->applyRule($field, $value, $rule);
                if ($error) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }
        return $errors;
    }

    protected function applyRule(string $field, $value, string $rule): ?string
    {
        return match ($rule) {
            'required' => empty($value) ? "{$field} is required" : null,
            'email' => (!filter_var($value, FILTER_VALIDATE_EMAIL)) ? "{$field} must be a valid email" : null,
            'numeric' => !is_numeric($value) ? "{$field} must be numeric" : null,
            default => null,
        };
    }
}
