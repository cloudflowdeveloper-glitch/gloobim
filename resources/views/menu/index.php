<?php $activeTab = 'menu'; $title = 'Menu - Globiim'; $hideTopNav = true; ?>
<?php
$currentUser = \Core\Auth::user();
$wallet = null;
if ($currentUser) {
    try {
        $wallets = \Core\Database::query("SELECT balance, currency FROM wallets WHERE user_id = ? LIMIT 1", [$currentUser['id']]);
        $wallet = $wallets[0] ?? null;
    } catch (\Exception $e) {}
}
$name = $currentUser['name'] ?? 'Guest';
$username = $currentUser['username'] ?? 'guest';
$avatar = $currentUser['avatar'] ?? '/uploads/profiles/admin.jpg';
$isVerified = !empty($currentUser['is_verified']);
$balance = $wallet ? number_format((float)$wallet['balance'], 0) : '0';
$currency = $wallet['currency'] ?? 'KES';
$level = $currentUser['creator_level'] ?? 1;
?>
<?php ob_start(); ?>
<style>
    .menu-item { transition: all 0.2s ease; }
    .menu-item:hover { background: rgba(255,255,255,0.04); }
    .menu-item:active { transform: scale(0.98); }
    .quick-action-card { transition: all 0.25s ease; }
    .quick-action-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.3); }
    .quick-action-card:active { transform: scale(0.95); }
    .creator-studio-card { transition: all 0.25s ease; }
    .creator-studio-card:hover { border-color: rgba(131,74,229,0.3); background: rgba(131,74,229,0.05); }
    .trending-product { transition: all 0.25s ease; }
    .trending-product:hover { transform: translateY(-2px); }
</style>

<div class="max-w-lg mx-auto pb-20">

    <!-- ===== HEADER BAR ===== -->
    <div class="px-4 pt-3 pb-3 flex items-center gap-3">
        <a href="/" class="flex items-center gap-2 flex-shrink-0">
            <img src="/logo.jpeg" alt="Globiim" class="h-8 w-auto rounded-lg object-contain">
            <span class="font-display font-bold text-lg tracking-tight"><span class="text-white">Glo</span><span class="gradient-text">biim</span></span>
        </a>
        <!-- Spacer pushes icons to the right -->
        <div class="flex-1"></div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="/messages" class="relative w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-300 text-[20px]">chat_bubble_outline</span>
                <span class="absolute top-1 right-1 w-2 h-2 bg-[#834ae5] rounded-full"></span>
            </a>
            <a href="/notifications" class="relative w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-300 text-[20px]">notifications_none</span>
                <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center">3</span>
            </a>
            <a href="/profile" class="w-9 h-9 rounded-full overflow-hidden border-2 border-[#834ae5]/50 flex-shrink-0">
                <img src="<?= $avatar ?>" alt="Profile" class="w-full h-full object-cover">
            </a>
        </div>
    </div>

    <!-- ===== PROFILE CARD ===== -->
    <div class="px-4 mb-3">
        <a href="/profile" class="block relative rounded-2xl overflow-hidden p-4" style="background: linear-gradient(135deg, rgba(131,74,229,0.15) 0%, rgba(20,20,28,0.9) 100%); border: 1px solid rgba(131,74,229,0.2);">
            <!-- Subtle shimmer -->
            <div class="absolute inset-0 opacity-20" style="background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.03) 50%, transparent 75%); background-size: 200% 100%; animation: shimmer 3s infinite;"></div>
            <div class="relative flex items-center gap-3 mb-3">
                <div class="w-14 h-14 rounded-full overflow-hidden flex-shrink-0" style="box-shadow: 0 0 0 2.5px rgba(131,74,229,0.6), 0 0 15px rgba(131,74,229,0.2);">
                    <img src="<?= $avatar ?>" alt="<?= $name ?>" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-white font-bold text-sm truncate"><?= htmlspecialchars($name) ?></h3>
                        <?php if ($isVerified): ?>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#834ae5"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                        <?php endif; ?>
                    </div>
                    <p class="text-zinc-400 text-xs">@<?= htmlspecialchars($username) ?></p>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold" style="background: rgba(131,74,229,0.2); color: #c084fc;">
                            <span class="material-icons-round text-[10px]">star</span>
                            Level <?= $level ?> Creator
                        </span>
                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-[9px] font-bold" style="background: rgba(251,146,60,0.15); color: #fb923c;">
                            <span class="material-icons-round text-[10px]">local_fire_department</span>
                            Top Creator
                        </span>
                    </div>
                </div>
            </div>
            <!-- Stats Row -->
            <div class="relative grid grid-cols-3 gap-2 pt-3" style="border-top: 1px solid rgba(255,255,255,0.06);">
                <div class="text-center">
                    <p class="text-white text-sm font-bold">12.5K</p>
                    <p class="text-zinc-500 text-[10px]">Followers</p>
                </div>
                <div class="text-center" style="border-left: 1px solid rgba(255,255,255,0.06); border-right: 1px solid rgba(255,255,255,0.06);">
                    <p class="text-white text-sm font-bold">342</p>
                    <p class="text-zinc-500 text-[10px]">Following</p>
                </div>
                <div class="text-center">
                    <p class="text-white text-sm font-bold"><?= $currency ?> <?= $balance ?></p>
                    <p class="text-zinc-500 text-[10px]">Total Earnings</p>
                </div>
            </div>
        </a>
    </div>

    <!-- ===== WALLET BALANCE ===== -->
    <div class="px-4 mb-3">
        <a href="/wallet" class="flex items-center gap-3 p-3.5 rounded-xl transition-all hover:opacity-90" style="background: linear-gradient(135deg, rgba(180,120,40,0.2) 0%, rgba(20,20,28,0.9) 100%); border: 1px solid rgba(251,146,60,0.15);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(251,146,60,0.15);">
                <span class="material-icons-round text-amber-400 text-xl">account_balance_wallet</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-bold"><?= $currency ?> <?= $balance ?></p>
                <p class="text-zinc-400 text-[11px]">Wallet Balance</p>
            </div>
            <span class="material-icons-round text-zinc-500 text-lg">chevron_right</span>
        </a>
    </div>

    <!-- ===== QUICK ACTIONS ===== -->
    <div class="px-4 mb-4">
        <div class="grid grid-cols-4 gap-2.5">
            <a href="/wallet/withdraw" class="quick-action-card flex flex-col items-center gap-1.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-xl">sync_alt</span>
                </div>
                <span class="text-zinc-300 text-[10px] font-semibold">Withdraw</span>
            </a>
            <a href="/wallet/deposit" class="quick-action-card flex flex-col items-center gap-1.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.15);">
                    <span class="material-icons-round text-emerald-400 text-xl">south_west</span>
                </div>
                <span class="text-zinc-300 text-[10px] font-semibold">Deposit</span>
            </a>
            <a href="/creator/analytics" class="quick-action-card flex flex-col items-center gap-1.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.15);">
                    <span class="material-icons-round text-blue-400 text-xl">bar_chart</span>
                </div>
                <span class="text-zinc-300 text-[10px] font-semibold">Analytics</span>
            </a>
            <a href="/creator/dashboard" class="quick-action-card flex flex-col items-center gap-1.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.15);">
                    <span class="material-icons-round text-red-400 text-xl">auto_awesome</span>
                </div>
                <span class="text-zinc-300 text-[10px] font-semibold">Creator Hub</span>
            </a>
        </div>
    </div>

    <!-- ===== CONTENT SECTION (2-column grid) ===== -->
    <div class="px-4 mb-4">
        <p class="text-[10px] text-zinc-500 uppercase tracking-wider font-bold mb-2.5">Content</p>
        <div class="grid grid-cols-2 gap-2.5">
            <!-- Explore -->
            <a href="/feed" class="menu-item flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-lg">explore</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Explore</p>
                    <p class="text-zinc-500 text-[9px] truncate">Discover new content</p>
                </div>
            </a>
            <!-- Reels -->
            <a href="/reels" class="menu-item flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-lg">movie</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Reels</p>
                    <p class="text-zinc-500 text-[9px] truncate">Short creative videos</p>
                </div>
            </a>
            <!-- Videos -->
            <a href="/videos" class="menu-item flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-lg">play_circle</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Videos</p>
                    <p class="text-zinc-500 text-[9px] truncate">Watch and enjoy</p>
                </div>
            </a>
            <!-- Music -->
            <a href="/music" class="menu-item flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-lg">music_note</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Music</p>
                    <p class="text-zinc-500 text-[9px] truncate">Trending music</p>
                </div>
            </a>
            <!-- Upload Music -->
            <a href="/music/upload" class="menu-item flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-lg">upload_file</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Upload Music</p>
                    <p class="text-zinc-500 text-[9px] truncate">Share your sound</p>
                </div>
            </a>
            <!-- Posts -->
            <a href="/posts" class="menu-item flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-lg">article</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Posts</p>
                    <p class="text-zinc-500 text-[9px] truncate">Read and share posts</p>
                </div>
            </a>
            <!-- Live Streams -->
            <a href="/livestream" class="menu-item flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a] relative">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(239,68,68,0.15);">
                    <span class="material-icons-round text-red-500 text-lg">sensors</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Live Streams</p>
                    <p class="text-zinc-500 text-[9px] truncate">1,240 live now</p>
                </div>
                <span class="absolute top-2 right-2 px-1.5 py-0.5 rounded bg-red-500 text-white text-[8px] font-bold" style="animation: pulse-live 1.5s ease-in-out infinite;">LIVE</span>
            </a>
        </div>
    </div>

    <!-- ===== MARKETPLACE SECTION ===== -->
    <div class="px-4 mb-4">
        <p class="text-[10px] text-zinc-500 uppercase tracking-wider font-bold mb-2.5">Marketplace</p>

        <!-- Trending Product Card -->
        <div class="trending-product relative rounded-xl overflow-hidden p-3.5 mb-2.5" style="background: linear-gradient(135deg, rgba(131,74,229,0.12) 0%, rgba(20,20,28,0.95) 100%); border: 1px solid rgba(131,74,229,0.15);">
            <div class="absolute top-2.5 right-2.5">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold" style="background: rgba(251,146,60,0.2); color: #fb923c;">
                    <span class="material-icons-round text-[10px]">trending_up</span>
                    Trending
                </span>
            </div>
            <div class="flex gap-3">
                <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-[#834ae5]/20 flex items-center justify-center">
                    <span class="material-icons-round text-[#834ae5] text-3xl">menu_book</span>
                </div>
                <div class="flex-1 min-w-0 pt-1">
                    <p class="text-white text-xs font-bold">Growing on Social Media</p>
                    <p class="text-zinc-500 text-[10px] mt-0.5">E-book · Digital Product</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-[#834ae5] text-xs font-bold"><?= $currency ?> 499</span>
                        <a href="/marketplace" class="px-3 py-1 rounded-full text-[10px] font-bold border transition-colors" style="border-color: rgba(131,74,229,0.4); color: #c084fc;">View Product</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Marketplace Menu Items -->
        <div class="space-y-1">
            <a href="/marketplace" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.15);">
                    <span class="material-icons-round text-[#8B5CF6] text-lg">storefront</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold">Marketplace</p>
                    <p class="text-zinc-500 text-[9px]">Buy & sell products</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>
            <a href="/marketplace/my" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.15);">
                    <span class="material-icons-round text-[#8B5CF6] text-lg">list_alt</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold">View Products</p>
                    <p class="text-zinc-500 text-[9px]">Browse all listings</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>
            <a href="/marketplace/create" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.15);">
                    <span class="material-icons-round text-[#8B5CF6] text-lg">add_circle</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold">Add Products</p>
                    <p class="text-zinc-500 text-[9px]">Sell your items</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>
            <a href="/marketplace/orders" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.15);">
                    <span class="material-icons-round text-[#8B5CF6] text-lg">receipt_long</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold">View Orders</p>
                    <p class="text-zinc-500 text-[9px]">Track your purchases</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>
        </div>
    </div>

    <!-- ===== CREATOR STUDIO SECTION ===== -->
    <div class="px-4 mb-4">
        <p class="text-[10px] text-zinc-500 uppercase tracking-wider font-bold mb-2.5">Creator Studio</p>
        <div class="grid grid-cols-2 gap-2.5">
            <a href="/creator/analytics" class="creator-studio-card flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(59,130,246,0.15);">
                    <span class="material-icons-round text-blue-400 text-lg">bar_chart</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Analytics</p>
                    <p class="text-zinc-500 text-[9px] truncate">Track performance</p>
                </div>
            </a>
            <a href="/wallet" class="creator-studio-card flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(16,185,129,0.15);">
                    <span class="material-icons-round text-emerald-400 text-lg">payments</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Monetization</p>
                    <p class="text-zinc-500 text-[9px] truncate">Earnings & payouts</p>
                </div>
            </a>
            <a href="/creator/dashboard" class="creator-studio-card flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-lg">group</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Subscribers</p>
                    <p class="text-zinc-500 text-[9px] truncate">Manage followers</p>
                </div>
            </a>
            <a href="/creator/dashboard" class="creator-studio-card flex items-center gap-2.5 p-3 rounded-xl bg-[#14141c] border border-[#1e1e2a]">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(251,146,60,0.15);">
                    <span class="material-icons-round text-amber-400 text-lg">folder_special</span>
                </div>
                <div class="min-w-0">
                    <p class="text-white text-xs font-semibold">Content Manager</p>
                    <p class="text-zinc-500 text-[9px] truncate">Manage your content</p>
                </div>
            </a>
        </div>
    </div>

    <!-- ===== ACCOUNT SECTION ===== -->
    <div class="px-4 mb-4">
        <p class="text-[10px] text-zinc-500 uppercase tracking-wider font-bold mb-2.5">Account</p>
        <div class="space-y-1">
            <a href="/profile" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 bg-[#14141c] border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-400 text-lg">person</span>
                </div>
                <div class="flex-1">
                    <p class="text-white text-xs font-semibold">Profile</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>
            <a href="/settings" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 bg-[#14141c] border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-400 text-lg">settings</span>
                </div>
                <div class="flex-1">
                    <p class="text-white text-xs font-semibold">Settings</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>
            <a href="/support" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 bg-[#14141c] border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-400 text-lg">help_outline</span>
                </div>
                <div class="flex-1">
                    <p class="text-white text-xs font-semibold">Help & Support</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>
            <a href="/support/tickets" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(139,92,246,0.12);">
                    <span class="material-icons-round text-[#8B5CF6] text-lg">confirmation_number</span>
                </div>
                <div class="flex-1">
                    <p class="text-white text-xs font-semibold">Support Tickets</p>
                    <p class="text-zinc-500 text-[9px]">View & reply to your tickets</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>
            <?php if ($currentUser): ?>
            <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(239,68,68,0.1);">
                    <span class="material-icons-round text-red-400 text-lg">logout</span>
                </div>
                <div class="flex-1">
                    <p class="text-red-400 text-xs font-semibold">Sign Out</p>
                </div>
            </a>
            <form id="logout-form" method="POST" action="/logout" style="display:none;"></form>
            <?php else: ?>
            <a href="/login" class="menu-item flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-lg">login</span>
                </div>
                <div class="flex-1">
                    <p class="text-[#834ae5] text-xs font-semibold">Sign In</p>
                </div>
            </a>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
function showToast(msg) {
    var existing = document.querySelector('.menu-toast');
    if (existing) existing.remove();
    var div = document.createElement('div');
    div.className = 'menu-toast';
    div.style.cssText = 'position:fixed;top:16px;left:50%;transform:translateX(-50%);padding:10px 24px;border-radius:24px;color:white;font-size:14px;font-weight:500;z-index:200;background:linear-gradient(135deg,#834ae5,#6b21a8);box-shadow:0 4px 20px rgba(131,74,229,0.4);';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(function() { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(function() { div.remove(); }, 300); }, 2000);
}
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
