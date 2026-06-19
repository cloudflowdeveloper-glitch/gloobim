<?php

namespace App\Http\Middleware;

use Core\Request;
use Core\Response;
use Core\Auth;

class GuestMiddleware
{
    public function handle(Request $request): ?Response
    {
        if (Auth::check()) {
            return Response::redirect('/feed');
        }
        return null;
    }
}
