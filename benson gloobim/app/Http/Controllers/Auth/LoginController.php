<?php

namespace App\Http\Controllers\Auth;

use Core\Controller;
use Core\Response;
use Core\Auth;
use Core\Session;

class LoginController extends Controller
{
    public function showLoginForm(): Response
    {
        return $this->view('auth.login');
    }

    public function login(): Response
    {
        $credentials = $this->request->only(['email', 'password']);
        $errors = $this->validate(['email' => 'required|email', 'password' => 'required']);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            return $this->redirect('/login');
        }

        if (Auth::attempt($credentials)) {
            Session::flash('success', 'Welcome back!');
            return $this->redirect('/');
        }

        Session::flash('error', 'Invalid credentials');
        return $this->redirect('/login');
    }

    public function logout(): Response
    {
        Auth::logout();
        return $this->redirect('/');
    }
}
