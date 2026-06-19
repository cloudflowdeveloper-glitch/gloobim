<?php

use Core\Router;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\FeedApiController;
use App\Http\Controllers\Api\ReelApiController;
use App\Http\Controllers\Api\VideoApiController;
use App\Http\Controllers\Api\WalletApiController;
use App\Http\Controllers\Api\CreatorApiController;
use App\Http\Controllers\Api\LivestreamApiController;
use App\Http\Controllers\Api\MessageApiController;
use App\Http\Controllers\Auth\SocialAuthController;

Router::group(['prefix' => 'api'], function () {
    Router::post('/auth/login', [AuthApiController::class, 'login']);
    Router::post('/auth/register', [AuthApiController::class, 'register']);
    Router::get('/auth/{provider}', [SocialAuthController::class, 'apiRedirectToProvider']);
    Router::post('/auth/{provider}/callback', [SocialAuthController::class, 'apiHandleProviderCallback']);
    Router::post('/auth/refresh', [AuthApiController::class, 'refresh']);
    Router::post('/auth/forgot-password', [AuthApiController::class, 'forgotPassword']);

    Router::middleware('auth')->group([], function () {
        Router::get('/auth/me', [AuthApiController::class, 'me']);
        Router::post('/auth/logout', [AuthApiController::class, 'logout']);

        Router::get('/feed', [FeedApiController::class, 'index']);
        Router::get('/feed/trending', [FeedApiController::class, 'trending']);
        Router::get('/feed/for-you', [FeedApiController::class, 'forYou']);
        Router::get('/feed/subscriptions', [FeedApiController::class, 'subscriptions']);

        Router::apiResource('reels', ReelApiController::class);
        Router::post('/reels/{id}/like', [ReelApiController::class, 'like']);
        Router::post('/reels/{id}/comment', [ReelApiController::class, 'comment']);
        Router::post('/reels/{id}/share', [ReelApiController::class, 'share']);
        Router::get('/reels/{id}/comments', [ReelApiController::class, 'comments']);

        Router::apiResource('videos', VideoApiController::class);
        Router::post('/videos/{id}/like', [VideoApiController::class, 'like']);
        Router::post('/videos/{id}/comment', [VideoApiController::class, 'comment']);
        Router::post('/videos/{id}/share', [VideoApiController::class, 'share']);
        Router::get('/videos/{id}/comments', [VideoApiController::class, 'comments']);

        Router::get('/wallet', [WalletApiController::class, 'index']);
        Router::post('/wallet/deposit', [WalletApiController::class, 'deposit']);
        Router::post('/wallet/withdraw', [WalletApiController::class, 'withdraw']);
        Router::get('/wallet/transactions', [WalletApiController::class, 'transactions']);
        Router::post('/wallet/mpesa/stk', [WalletApiController::class, 'mpesaSTKPush']);
        Router::post('/wallet/gift', [WalletApiController::class, 'sendGift']);

        Router::get('/creator/dashboard', [CreatorApiController::class, 'dashboard']);
        Router::get('/creator/analytics', [CreatorApiController::class, 'analytics']);
        Router::get('/creator/{username}', [CreatorApiController::class, 'profile']);

        Router::get('/livestreams', [LivestreamApiController::class, 'index']);
        Router::get('/livestreams/live', [LivestreamApiController::class, 'live']);
        Router::get('/livestreams/ended', [LivestreamApiController::class, 'ended']);
        Router::get('/livestreams/scheduled', [LivestreamApiController::class, 'scheduled']);
        Router::get('/livestreams/featured', [LivestreamApiController::class, 'featured']);
        Router::get('/livestreams/search', [LivestreamApiController::class, 'search']);
        Router::get('/livestreams/{id}', [LivestreamApiController::class, 'show']);
        Router::post('/livestreams/start', [LivestreamApiController::class, 'start']);
        Router::post('/livestreams/schedule', [LivestreamApiController::class, 'createSchedule']);
        Router::put('/livestreams/{id}', [LivestreamApiController::class, 'update']);
        Router::delete('/livestreams/{id}', [LivestreamApiController::class, 'destroy']);
        Router::post('/livestreams/{id}/end', [LivestreamApiController::class, 'end']);
        Router::post('/livestreams/{id}/like', [LivestreamApiController::class, 'like']);
        Router::post('/livestreams/{id}/share', [LivestreamApiController::class, 'share']);
        Router::post('/livestreams/{id}/comment', [LivestreamApiController::class, 'comment']);
        Router::delete('/livestreams/comments/{cid}', [LivestreamApiController::class, 'deleteComment']);
        Router::get('/livestreams/{id}/comments', [LivestreamApiController::class, 'getComments']);
        Router::post('/livestreams/{id}/gift', [LivestreamApiController::class, 'sendGift']);
        Router::post('/livestreams/{id}/save', [LivestreamApiController::class, 'save']);
        Router::post('/livestreams/{id}/unsave', [LivestreamApiController::class, 'unsave']);
        Router::post('/livestreams/{id}/report', [LivestreamApiController::class, 'report']);
        Router::get('/livestreams/{id}/stats', [LivestreamApiController::class, 'stats']);
        Router::get('/livestreams/{id}/viewers', [LivestreamApiController::class, 'getViewers']);
        Router::post('/livestreams/{id}/join', [LivestreamApiController::class, 'join']);
        Router::post('/livestreams/{id}/leave', [LivestreamApiController::class, 'leave']);
        Router::get('/livestreams/{id}/heartbeat', [LivestreamApiController::class, 'heartbeat']);
        Router::post('/livestreams/{id}/mute/{vid}', [LivestreamApiController::class, 'muteViewer']);
        Router::post('/livestreams/{id}/unmute/{vid}', [LivestreamApiController::class, 'unmuteViewer']);
        Router::post('/livestreams/{id}/ban/{vid}', [LivestreamApiController::class, 'banViewer']);
        Router::post('/livestreams/{id}/unban/{vid}', [LivestreamApiController::class, 'unbanViewer']);
        Router::post('/livestreams/{id}/cohost', [LivestreamApiController::class, 'addCoHost']);
        Router::delete('/livestreams/{id}/cohost', [LivestreamApiController::class, 'removeCoHost']);
        Router::post('/livestreams/{id}/raid', [LivestreamApiController::class, 'raid']);
        Router::get('/livestreams/{id}/signal', [LivestreamApiController::class, 'pollSignals']);
        Router::post('/livestreams/{id}/signal', [LivestreamApiController::class, 'sendSignal']);

        Router::get('/messages', [MessageApiController::class, 'index']);
        Router::get('/messages/{id}', [MessageApiController::class, 'show']);
        Router::post('/messages/send', [MessageApiController::class, 'send']);
    });
});
