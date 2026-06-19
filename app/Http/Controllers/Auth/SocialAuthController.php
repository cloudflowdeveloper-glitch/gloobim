<?php

namespace App\Http\Controllers\Auth;

use Core\Controller;
use Core\Response;
use Core\Session;
use Core\Auth;
use App\Services\OAuthService;

class SocialAuthController extends Controller
{
    protected array $validProviders = ['google', 'apple', 'facebook', 'x'];

    protected function validateProvider(string $provider): bool
    {
        return in_array($provider, $this->validProviders);
    }

    public function redirectToProvider(string $provider): Response
    {
        if (!$this->validateProvider($provider)) {
            Session::flash('error', 'Invalid login provider.');
            return $this->redirect('/login');
        }

        $oauth = new OAuthService($provider);

        if (!$oauth->isConfigured()) {
            Session::flash('error', ucfirst($provider) . ' login is not configured yet. Please use email/password.');
            return $this->redirect('/login');
        }

        $url = $oauth->getAuthorizationUrl();
        return Response::redirect($url);
    }

    public function handleProviderCallback(string $provider): Response
    {
        if (!$this->validateProvider($provider)) {
            Session::flash('error', 'Invalid login provider.');
            return $this->redirect('/login');
        }

        $state = $this->request->input('state');
        $savedState = Session::get('oauth_state');
        $savedProvider = Session::get('oauth_provider');

        if (!$state || $state !== $savedState || $savedProvider !== $provider) {
            Session::flash('error', 'Invalid OAuth state. Please try again.');
            return $this->redirect('/login');
        }

        $code = $this->request->input('code');
        $error = $this->request->input('error');

        if ($error) {
            Session::flash('error', ucfirst($provider) . ' login was cancelled or failed.');
            return $this->redirect('/login');
        }

        if (!$code) {
            Session::flash('error', 'No authorization code received.');
            return $this->redirect('/login');
        }

        $oauth = new OAuthService($provider);
        $user = $oauth->handleCallback($code);

        if (!$user) {
            Session::flash('error', 'Failed to authenticate with ' . ucfirst($provider) . '. Please try again.');
            return $this->redirect('/login');
        }

        Auth::login($user);
        Session::remove('oauth_state');
        Session::remove('oauth_provider');
        Session::flash('success', 'Welcome to DTTube! Signed in with ' . ucfirst($provider) . '.');

        return $this->redirect('/feed');
    }

    public function apiRedirectToProvider(string $provider): Response
    {
        if (!$this->validateProvider($provider)) {
            return $this->json(['error' => 'Invalid OAuth provider'], 400);
        }

        $oauth = new OAuthService($provider);

        if (!$oauth->isConfigured()) {
            return $this->json(['error' => ucfirst($provider) . ' OAuth not configured'], 501);
        }

        $url = $oauth->getAuthorizationUrl();
        return $this->json(['authorization_url' => $url]);
    }

    public function apiHandleProviderCallback(string $provider): Response
    {
        if (!$this->validateProvider($provider)) {
            return $this->json(['error' => 'Invalid OAuth provider'], 400);
        }

        $code = $this->request->input('code');
        if (!$code) {
            return $this->json(['error' => 'Authorization code required'], 400);
        }

        $oauth = new OAuthService($provider);
        $user = $oauth->handleCallback($code);

        if (!$user) {
            return $this->json(['error' => 'Authentication failed'], 401);
        }

        $token = bin2hex(random_bytes(32));
        return $this->json([
            'token' => $token,
            'user' => $user,
            'provider' => $provider,
        ]);
    }
}
