<?php

namespace App\Http\Controllers\Api;

use Core\Controller;
use Core\Response;
use Core\Auth;
use Core\Session;

class AuthApiController extends Controller
{
    public function login(): Response
    {
        $credentials = $this->request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $token = bin2hex(random_bytes(32));
            return $this->json(['token' => $token, 'user' => Auth::user()]);
        }
        return $this->json(['error' => 'Invalid credentials'], 401);
    }

    public function register(): Response
    {
        $data = $this->request->only(['name', 'username', 'email', 'password']);
        $userId = Auth::createUser($data);
        return $this->json(['user_id' => $userId], 201);
    }

    public function me(): Response
    {
        return $this->json(Auth::user());
    }

    public function logout(): Response
    {
        Auth::logout();
        return $this->json(['message' => 'Logged out']);
    }

    public function refresh(): Response
    {
        $token = bin2hex(random_bytes(32));
        return $this->json(['token' => $token]);
    }

    public function forgotPassword(): Response
    {
        return $this->json(['message' => 'Reset link sent']);
    }
}
