<?php

namespace Core;

class Controller
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    protected function view(string $template, array $data = []): Response
    {
        return Response::view($template, $data);
    }

    protected function json($data, int $statusCode = 200): Response
    {
        return Response::json($data, $statusCode);
    }

    protected function redirect(string $url, int $statusCode = 302): Response
    {
        return Response::redirect($url, $statusCode);
    }

    protected function back(): Response
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        return Response::redirect($referer);
    }

    protected function validate(array $rules): array
    {
        return $this->request->validate($rules);
    }

    protected function authorize(string $ability, $resource = null): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        return true;
    }
}
