<?php

namespace App\Http\Middleware;

use Core\Request;
use Core\Response;
use Core\Session;
use Core\Auth;

class AuthMiddleware
{
    public function handle(Request $request): ?Response
    {
        if (Auth::guest()) {
            if ($request->isAjax()) {
                return Response::json(['error' => 'Unauthenticated'], 401);
            }
            return Response::redirect('/login');
        }
        return null;
    }
}
