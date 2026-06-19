<?php

namespace App\Http\Middleware;

use Core\Request;
use Core\Response;
use Core\Auth;

class AdminMiddleware
{
    public function handle(Request $request): ?Response
    {
        $user = Auth::user();
        if (!$user || ($user['role'] ?? '') !== 'admin') {
            if ($request->isAjax()) {
                return Response::json(['error' => 'Forbidden'], 403);
            }
            return Response::redirect('/');
        }
        return null;
    }
}
