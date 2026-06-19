<?php

return [
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
        'redirect_uri' => env('GOOGLE_REDIRECT_URI', 'http://localhost:8000/auth/google/callback'),
        'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
        'token_url' => 'https://oauth2.googleapis.com/token',
        'userinfo_url' => 'https://www.googleapis.com/oauth2/v3/userinfo',
        'scopes' => ['openid', 'email', 'profile'],
    ],

    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID', ''),
        'client_secret' => env('APPLE_CLIENT_SECRET', ''),
        'redirect_uri' => env('APPLE_REDIRECT_URI', 'http://localhost:8000/auth/apple/callback'),
        'auth_url' => 'https://appleid.apple.com/auth/authorize',
        'token_url' => 'https://appleid.apple.com/auth/token',
        'scopes' => ['name', 'email'],
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID', ''),
        'client_secret' => env('FACEBOOK_APP_SECRET', ''),
        'redirect_uri' => env('FACEBOOK_REDIRECT_URI', 'http://localhost:8000/auth/facebook/callback'),
        'auth_url' => 'https://www.facebook.com/v18.0/dialog/oauth',
        'token_url' => 'https://graph.facebook.com/v18.0/oauth/access_token',
        'userinfo_url' => 'https://graph.facebook.com/me?fields=id,name,email,picture',
        'scopes' => ['email', 'public_profile'],
    ],

    'x' => [
        'client_id' => env('X_CLIENT_ID', ''),
        'client_secret' => env('X_CLIENT_SECRET', ''),
        'redirect_uri' => env('X_REDIRECT_URI', 'http://localhost:8000/auth/x/callback'),
        'auth_url' => 'https://twitter.com/i/oauth2/authorize',
        'token_url' => 'https://api.twitter.com/2/oauth2/token',
        'userinfo_url' => 'https://api.twitter.com/2/users/me',
        'scopes' => ['tweet.read', 'users.read'],
    ],
];
