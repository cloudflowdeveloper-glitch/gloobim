<?php

namespace Core;

class Response
{
    protected string $content;
    protected int $statusCode;
    protected array $headers;

    protected static array $statusTexts = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        304 => 'Not Modified',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        422 => 'Unprocessable Entity',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable',
    ];

    public function __construct(string $content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public static function json($data, int $statusCode = 200, array $headers = []): self
    {
        $headers['Content-Type'] = 'application/json';
        return new static(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), $statusCode, $headers);
    }

    public static function view(string $template, array $data = [], int $statusCode = 200): self
    {
        $content = View::render($template, $data);
        return new static($content, $statusCode);
    }

    public static function redirect(string $url, int $statusCode = 302): self
    {
        return new static('', $statusCode, ['Location' => $url]);
    }

    public static function download(string $filePath, string $fileName = null, array $headers = []): self
    {
        $fileName = $fileName ?? basename($filePath);
        $headers['Content-Type'] = 'application/octet-stream';
        $headers['Content-Disposition'] = 'attachment; filename="' . $fileName . '"';
        $headers['Content-Length'] = filesize($filePath);
        return new static(file_get_contents($filePath), 200, $headers);
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        echo $this->content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function withCookie(string $name, string $value, int $expire = 3600, string $path = '/'): self
    {
        setcookie($name, $value, time() + $expire, $path);
        return $this;
    }
}
