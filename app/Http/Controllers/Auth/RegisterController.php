<?php

namespace App\Http\Controllers\Auth;

use Core\Controller;
use Core\Response;
use Core\Auth;
use Core\Session;

class RegisterController extends Controller
{
    public function showRegistrationForm(): Response
    {
        return $this->view('auth.register');
    }

    public function register(): Response
    {
        $data = $this->request->only(['name', 'username', 'email', 'password']);
        $errors = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            return $this->redirect('/register');
        }

        $userId = Auth::createUser($data);
        $user = ['id' => $userId, ...$data];
        Auth::login($user);

        Session::flash('success', 'Welcome to DTTube!');
        return $this->redirect('/feed');
    }
}
