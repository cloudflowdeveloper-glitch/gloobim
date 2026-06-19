<?php

use Core\Router;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\LivestreamController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\StoryController;

use App\Http\Controllers\Admin\AdminGiftController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\AdminPaymentController;

Router::get('/', [HomeController::class, 'index']);
Router::get('/menu', [HomeController::class, 'menu']);
Router::get('/notifications', [HomeController::class, 'notifications']);
Router::get('/creators', [HomeController::class, 'creators']);

Router::get('/login', [LoginController::class, 'showLoginForm']);
Router::post('/login', [LoginController::class, 'login']);

Router::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider']);
Router::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);
Router::post('/logout', [LoginController::class, 'logout']);

Router::get('/register', [RegisterController::class, 'showRegistrationForm']);
Router::post('/register', [RegisterController::class, 'register']);

Router::get('/livestream', [LivestreamController::class, 'index']);

// ===== MUSIC ROUTES =====
Router::get('/music', [MusicController::class, 'index']);
Router::get('/music/search', [MusicController::class, 'search']);
Router::get('/music/trending', [MusicController::class, 'trendingJson']);
Router::get('/music/genre/{slug}', [MusicController::class, 'genre']);
Router::get('/music/track/{id}', [MusicController::class, 'show']);
Router::post('/music/{id}/play', [MusicController::class, 'play']);
Router::post('/music/{id}/share', [MusicController::class, 'share']);

// ===== SUPPORT ROUTES =====
Router::get('/support', [SupportController::class, 'index']);
Router::get('/support/tickets', [SupportController::class, 'tickets']);
Router::post('/support/create', [SupportController::class, 'createTicket']);
Router::get('/support/{id}', [SupportController::class, 'showTicket']);
Router::post('/support/{id}/reply', [SupportController::class, 'reply']);
Router::post('/support/{id}/close', [SupportController::class, 'closeTicket']);

// ===== INTERACTION ROUTES (outside auth group – controller handles auth check) =====
Router::post('/follow/{id}', [InteractionController::class, 'toggleFollow']);
Router::get('/follow/check/{id}', [InteractionController::class, 'checkFollow']);
Router::post('/bookmark/{type}/{id}', [InteractionController::class, 'toggleBookmark']);
Router::post('/tip/{type}/{id}', [InteractionController::class, 'sendTip']);

Router::middleware('auth')->group([], function () {
    Router::get('/feed', [FeedController::class, 'index']);
    Router::get('/feed/trending', [FeedController::class, 'trending']);
    Router::get('/feed/subscriptions', [FeedController::class, 'subscriptions']);

    Router::resource('reels', ReelController::class);
    Router::post('/reels/{id}/like', [ReelController::class, 'like']);
    Router::post('/reels/{id}/comment', [ReelController::class, 'comment']);
    Router::post('/reels/{id}/share', [ReelController::class, 'share']);
    Router::post('/reels/{id}/gift', [ReelController::class, 'sendGift']);

    Router::resource('videos', VideoController::class);
    Router::get('/videos/{id}', [VideoController::class, 'show']); // Override for direct match

    // ===== MUSIC AUTH ROUTES =====
    Router::post('/music/{id}/like', [MusicController::class, 'like']);
    Router::get('/music/upload', [MusicController::class, 'upload']);
    Router::post('/music/upload', [MusicController::class, 'store']);
    Router::get('/music/playlist/create', [MusicController::class, 'createPlaylist']);
    Router::post('/music/playlist/create', [MusicController::class, 'storePlaylist']);

    Router::post('/videos/{id}/like', [VideoController::class, 'like']);
    Router::post('/videos/{id}/comment', [VideoController::class, 'comment']);
    Router::post('/videos/{id}/share', [VideoController::class, 'share']);

    Router::resource('posts', PostController::class);
    Router::post('/posts/{id}/like', [PostController::class, 'like']);
    Router::post('/posts/{id}/comment', [PostController::class, 'comment']);
    Router::post('/posts/{id}/share', [PostController::class, 'share']);
    Router::post('/posts/upload-image', [PostController::class, 'uploadImage']);

    // ===== FOLLOW / BOOKMARK (inside auth for convenience) =====
    Router::post('/follow/{id}', [InteractionController::class, 'toggleFollow']);
    Router::post('/bookmark/{type}/{id}', [InteractionController::class, 'toggleBookmark']);
    Router::post('/tip/{type}/{id}', [InteractionController::class, 'sendTip']);

    Router::get('/messages', [MessageController::class, 'index']);
    Router::get('/messages/{id}', [MessageController::class, 'show']);
    Router::get('/messages/{id}/poll', [MessageController::class, 'poll']);
    Router::post('/messages/{id}', [MessageController::class, 'send']);
    Router::post('/messages/{id}/send', [MessageController::class, 'send']);
    Router::post('/messages/create', [MessageController::class, 'create']);

    Router::get('/wallet', [WalletController::class, 'index']);
    Router::get('/wallet/deposit', [WalletController::class, 'depositPage']);
    Router::get('/wallet/withdraw', [WalletController::class, 'withdrawPage']);
    Router::post('/wallet/deposit', [WalletController::class, 'deposit']);
    Router::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
    Router::get('/wallet/transactions', [WalletController::class, 'transactions']);
    Router::post('/wallet/mpesa/stk', [WalletController::class, 'mpesaSTKPush']);
    Router::post('/wallet/mpesa/callback', [WalletController::class, 'mpesaCallback']);

    Router::get('/creator/dashboard', [CreatorController::class, 'dashboard']);
    Router::get('/creator/analytics', [CreatorController::class, 'analytics']);
    Router::post('/creator/monetize', [CreatorController::class, 'monetize']);
    Router::get('/creator/{username}', [CreatorController::class, 'profile']);

    Router::get('/profile', [CreatorController::class, 'myProfile']);
    Router::get('/settings', [CreatorController::class, 'settings']);
    Router::post('/profile/update', [CreatorController::class, 'updateProfile']);
    Router::post('/profile/update-type', [CreatorController::class, 'updateProfileType']);

    // ===== USER SEARCH =====
    Router::get('/users/search', [LivestreamController::class, 'searchUsers']);

    // ===== LIVESTREAM ROUTES =====
    // STATIC routes FIRST (before dynamic {id})
    Router::get('/livestream/start', [LivestreamController::class, 'start']);
    Router::post('/livestream/start', [LivestreamController::class, 'createStream']);
    Router::get('/livestream/my', [LivestreamController::class, 'my']);
    Router::get('/livestream/ended', [LivestreamController::class, 'ended']);
    Router::get('/livestream/schedule', [LivestreamController::class, 'schedule']);
    Router::post('/livestream/schedule', [LivestreamController::class, 'createSchedule']);
    Router::get('/livestream/search/json', [LivestreamController::class, 'search']);

    // DYNAMIC routes with {id}
    Router::get('/livestream/{id}', [LivestreamController::class, 'show']);
    Router::put('/livestream/{id}', [LivestreamController::class, 'updateStream']);
    Router::delete('/livestream/{id}', [LivestreamController::class, 'deleteStream']);
    Router::post('/livestream/{id}/end', [LivestreamController::class, 'end']);
    Router::post('/livestream/{id}/like', [LivestreamController::class, 'like']);
    Router::post('/livestream/{id}/share', [LivestreamController::class, 'share']);
    Router::post('/livestream/{id}/comment', [LivestreamController::class, 'comment']);
    Router::delete('/livestream/comments/{cid}', [LivestreamController::class, 'deleteComment']);
    Router::get('/livestream/{id}/comments', [LivestreamController::class, 'getLiveComments']);
    Router::post('/livestream/{id}/gift', [LivestreamController::class, 'sendGift']);
    Router::post('/livestream/{id}/signal', [LivestreamController::class, 'sendSignal']);
    Router::get('/livestream/{id}/signal', [LivestreamController::class, 'pollSignals']);
    Router::post('/livestream/{id}/join', [LivestreamController::class, 'joinStream']);
    Router::post('/livestream/{id}/leave', [LivestreamController::class, 'leaveStream']);
    Router::get('/livestream/{id}/heartbeat', [LivestreamController::class, 'heartbeat']);
    Router::get('/livestream/{id}/stats', [LivestreamController::class, 'getStreamStats']);
    Router::get('/livestream/{id}/viewers', [LivestreamController::class, 'getViewers']);
    Router::post('/livestream/{id}/save', [LivestreamController::class, 'saveStream']);
    Router::post('/livestream/{id}/unsave', [LivestreamController::class, 'unsaveStream']);
    Router::post('/livestream/{id}/report', [LivestreamController::class, 'report']);
    Router::post('/livestream/{id}/mute/{vid}', [LivestreamController::class, 'muteViewer']);
    Router::post('/livestream/{id}/unmute/{vid}', [LivestreamController::class, 'unmuteViewer']);
    Router::post('/livestream/{id}/ban/{vid}', [LivestreamController::class, 'banViewer']);
    Router::post('/livestream/{id}/unban/{vid}', [LivestreamController::class, 'unbanViewer']);
    Router::post('/livestream/{id}/pause', [LivestreamController::class, 'pauseStream']);
    Router::post('/livestream/{id}/unpause', [LivestreamController::class, 'unpauseStream']);
    Router::get('/livestream/{id}/banned', [LivestreamController::class, 'getBannedViewers']);
    Router::get('/livestream/{id}/viewer-count', [LivestreamController::class, 'getViewerCount']);
    Router::post('/livestream/{id}/featured', [LivestreamController::class, 'setFeatured']);
    Router::post('/livestream/{id}/cohost', [LivestreamController::class, 'addCoHost']);
    Router::delete('/livestream/{id}/cohost', [LivestreamController::class, 'removeCoHost']);
    Router::post('/livestream/{id}/raid', [LivestreamController::class, 'raid']);

    // ===== MARKETPLACE ROUTES =====
    // STATIC routes MUST come before wildcard {id}
    Router::get('/marketplace', [MarketplaceController::class, 'index']);
    Router::get('/marketplace/categories', [MarketplaceController::class, 'categories']);
    Router::get('/marketplace/my', [MarketplaceController::class, 'my']);
    Router::get('/marketplace/create', [MarketplaceController::class, 'create']);
    Router::get('/marketplace/cart', [MarketplaceController::class, 'cart']);
    Router::get('/marketplace/cart-count', [MarketplaceController::class, 'cartCount']);
    Router::get('/marketplace/wishlist', [MarketplaceController::class, 'wishlist']);
    Router::get('/marketplace/checkout', [CheckoutController::class, 'index']);
    Router::post('/marketplace/checkout', [CheckoutController::class, 'placeOrder']);
    Router::get('/marketplace/checkout/success', [CheckoutController::class, 'success']);
    Router::get('/marketplace/orders', [CheckoutController::class, 'orders']);
    Router::post('/marketplace/checkout/payment-callback', [CheckoutController::class, 'paymentCallback']);
    Router::post('/marketplace/checkout/mpesa', [CheckoutController::class, 'mpesaInitiate']);
    // POST static
    Router::post('/marketplace', [MarketplaceController::class, 'store']);
    // Cart mutations
    Router::post('/marketplace/cart/{id}', [MarketplaceController::class, 'updateCart']);
    Router::delete('/marketplace/cart/{id}', [MarketplaceController::class, 'removeFromCart']);
    // Dynamic wildcard routes
    Router::get('/marketplace/{id}', [MarketplaceController::class, 'show']);
    Router::get('/marketplace/{id}/edit', [MarketplaceController::class, 'edit']);
    Router::put('/marketplace/{id}', [MarketplaceController::class, 'update']);
    Router::post('/marketplace/{id}/update', [MarketplaceController::class, 'update']);
    Router::delete('/marketplace/{id}', [MarketplaceController::class, 'destroy']);
    Router::post('/marketplace/{id}/sold', [MarketplaceController::class, 'markSold']);
    Router::post('/marketplace/{id}/wishlist', [MarketplaceController::class, 'toggleWishlist']);
    Router::post('/marketplace/{id}/cart', [MarketplaceController::class, 'addToCart']);

    // ===== MARKET (DIGITAL PRODUCTS & SERVICES) ROUTES =====
    Router::get('/market', [MarketController::class, 'index']);
    Router::get('/market/my', [MarketController::class, 'my']);
    Router::get('/market/create', [MarketController::class, 'create']);
    Router::post('/market', [MarketController::class, 'store']);
    Router::get('/market/{id}', [MarketController::class, 'show']);
    Router::get('/market/{id}/edit', [MarketController::class, 'edit']);
    Router::put('/market/{id}', [MarketController::class, 'update']);
    Router::post('/market/{id}/update', [MarketController::class, 'update']);
    Router::delete('/market/{id}', [MarketController::class, 'destroy']);
    Router::post('/market/{id}/toggle', [MarketController::class, 'toggleStatus']);

    // ===== STORIES ROUTES =====
    // STATIC routes first, then wildcard {id} LAST
    Router::get('/stories', [StoryController::class, 'index']);
    Router::get('/stories/create', [StoryController::class, 'create']);
    Router::post('/stories', [StoryController::class, 'store']);
    Router::get('/stories/user/{username}', [StoryController::class, 'userStories']);
    // Dynamic wildcard routes (MUST be last)
    Router::get('/stories/{id}', [StoryController::class, 'show']);
    Router::delete('/stories/{id}', [StoryController::class, 'destroy']);

    // ===== ADMIN ROUTES =====
    Router::group(['middleware' => ['admin']], function () {
        Router::get('/admin/gifts', [AdminGiftController::class, 'index']);
        Router::get('/admin/gifts/create', [AdminGiftController::class, 'create']);
        Router::post('/admin/gifts', [AdminGiftController::class, 'store']);
        Router::get('/admin/gifts/{id}/edit', [AdminGiftController::class, 'edit']);
        Router::post('/admin/gifts/{id}', [AdminGiftController::class, 'update']);
        Router::delete('/admin/gifts/{id}', [AdminGiftController::class, 'destroy']);

        // Payment settings
        Router::get('/admin/payments', [AdminPaymentController::class, 'index']);
        Router::post('/admin/payments/toggle/{id}', [AdminPaymentController::class, 'toggle']);

        // Support settings
        Router::get('/admin/support', [AdminSupportController::class, 'index']);
        Router::post('/admin/support/category', [AdminSupportController::class, 'addCategory']);
        Router::post('/admin/support/category/{id}/toggle', [AdminSupportController::class, 'toggleCategory']);
        Router::delete('/admin/support/category/{id}', [AdminSupportController::class, 'deleteCategory']);
        Router::post('/admin/support/contact', [AdminSupportController::class, 'addContact']);
        Router::post('/admin/support/contact/{id}/toggle', [AdminSupportController::class, 'toggleContact']);
        Router::delete('/admin/support/contact/{id}', [AdminSupportController::class, 'deleteContact']);
    });
    });
