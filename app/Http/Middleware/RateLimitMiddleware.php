<?php

namespace App\Http\Middleware;

use Core\Request;
use Core\Response;
use Core\Session;

class RateLimitMiddleware
{
    protected int $maxAttempts = 60;
    protected int $decayMinutes = 1;

    public function handle(Request $request): ?Response
    {
        $key = 'rate_limit_' . md5($request->ip());
        $attempts = (int) Session::get($key . '_attempts', 0);
        $lastAttempt = (int) Session::get($key . '_last', 0);

        if (time() - $lastAttempt > $this->decayMinutes * 60) {
            $attempts = 0;
        }

        if ($attempts >= $this->maxAttempts) {
            return Response::json(['error' => 'Too many requests'], 429);
        }

        Session::set($key . '_attempts', $attempts + 1);
        Session::set($key . '_last', time());

        return null;
    }
}
