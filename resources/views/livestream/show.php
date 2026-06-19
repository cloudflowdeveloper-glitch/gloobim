<?php $activeTab = 'stream'; $title = $stream['title'] . ' - DTTube Live'; ?>
<?php ob_start(); ?>
<style>
    @keyframes float-up {
        0% { transform: translateY(0) scale(0.3); opacity: 1; }
        100% { transform: translateY(-220px) scale(1.3) translateX(50px); opacity: 0; }
    }
    @keyframes float-up-2 {
        0% { transform: translateY(0) scale(0.3) rotate(-10deg); opacity: 1; }
        100% { transform: translateY(-260px) scale(1.5) translateX(-40px); opacity: 0; }
    }
    .heart-float { animation: float-up 1.8s ease-out forwards; pointer-events: none; }
    .heart-float-2 { animation: float-up-2 2.2s ease-out forwards; pointer-events: none; }
    .gift-bounce { animation: gift-bounce 0.5s ease-out; }
    @keyframes gift-bounce { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.2); } 100% { transform: scale(1); opacity: 1; } }
    .slide-up { animation: slide-up 0.3s ease-out forwards; }
    @keyframes slide-up { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .fade-in { animation: fade-in 0.3s ease-out forwards; }
    @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* TikTok floating comment */
    @keyframes tiktok-comment {
        0% { transform: translateX(100%) scale(0.8); opacity: 0; }
        10% { transform: translateX(0) scale(1); opacity: 1; }
        85% { transform: translateX(0) scale(1); opacity: 1; }
        100% { transform: translateX(-20px) scale(0.95); opacity: 0; }
    }
    .tiktok-comment {
        animation: tiktok-comment 5s ease-out forwards;
        pointer-events: none;
    }

    /* TikTok-like full screen */
    main:has(.live-full) { padding: 0 !important; max-width: 100% !important; width: 100% !important; margin: 0 !important; }
    .live-full {
        position: fixed !important;
        top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important;
        width: 100vw !important; height: 100dvh !important;
        max-width: none !important; margin: 0 !important;
        background: #000; overflow: hidden; z-index: 999;
        border-radius: 0 !important;
    }
    .live-full video { width: 100%; height: 100%; object-fit: cover; }
    .remote-video { transform: scaleX(-1); }
    .connection-status { transition: all 0.3s ease; }

    /* Side action buttons TikTok-style */
    .tiktok-actions { display: flex; flex-direction: column; align-items: center; gap: 0.75rem; }
    .tiktok-actions button { display: flex; flex-direction: column; align-items: center; gap: 0.125rem; }
    .tiktok-actions .action-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 50%;
        background: rgba(0,0,0,0.4); backdrop-filter: blur(8px);
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s ease; border: 1px solid rgba(255,255,255,0.08);
    }
    .tiktok-actions .action-icon:hover { transform: scale(1.1); }
    .tiktok-actions .action-label { color: rgba(255,255,255,0.85); font-size: 9px; font-weight: 600; }

    /* Bottom gradient overlay for readability */
    .tiktok-bottom-gradient {
        background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.05) 30%, rgba(0,0,0,0.55) 70%, rgba(0,0,0,0.8) 100%);
        pointer-events: none;
    }
    .tiktok-top-gradient {
        background: linear-gradient(180deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.2) 50%, transparent 100%);
        pointer-events: none;
    }

    /* Double-tap heart burst */
    @keyframes double-tap-burst {
        0% { transform: scale(0) rotate(-15deg); opacity: 1; }
        30% { transform: scale(1.3) rotate(5deg); opacity: 1; }
        60% { transform: scale(0.9) rotate(-3deg); opacity: 1; }
        100% { transform: scale(1) rotate(0deg); opacity: 1; }
    }
    .double-tap-heart {
        animation: double-tap-burst 0.6s ease-out forwards;
        font-size: 80px !important; position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%); pointer-events: none; z-index: 50;
    }
    @keyframes burst-particles {
        0% { transform: translate(-50%, -50%) scale(0.5); opacity: 1; }
        100% { transform: translate(var(--tx), var(--ty)) scale(0); opacity: 0; }
    }

    /* Gift sent animation */
    @keyframes gift-sent-float {
        0% { transform: translateY(0) scale(0.5); opacity: 0; }
        15% { transform: translateY(-20px) scale(1.2); opacity: 1; }
        30% { transform: translateY(-40px) scale(1); opacity: 1; }
        100% { transform: translateY(-180px) scale(1.5); opacity: 0; }
    }
    .gift-sent-icon { animation: gift-sent-float 3s ease-out forwards; pointer-events: none; }
    @keyframes gift-coin-fall {
        0% { transform: translateY(-60px) rotate(0deg); opacity: 1; }
        100% { transform: translateY(60px) rotate(360deg); opacity: 0; }
    }
    .gift-coin { animation: gift-coin-fall 1.2s ease-in forwards; pointer-events: none; }

    /* Viewer avatar bubbles */
    @keyframes viewer-bubble-in {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .viewer-bubble { animation: viewer-bubble-in 0.4s ease-out forwards; }
    @keyframes viewer-bubble-out {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(0); opacity: 0; }
    }
    .viewer-bubble.leaving { animation: viewer-bubble-out 0.3s ease-in forwards; }

    /* Share modal */
    .share-option { transition: all 0.2s ease; cursor: pointer; }
    .share-option:hover { transform: scale(1.08); }
    .share-option:active { transform: scale(0.95); }

    /* Gift history */
    @keyframes gift-history-in {
        0% { transform: translateX(100px); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }
    .gift-history-item { animation: gift-history-in 0.4s ease-out forwards; }

    /* Viewers count on stream */
    .viewer-avatar-strip { display: flex; align-items: center; }
    .viewer-avatar-strip img {
        width: 28px; height: 28px; border-radius: 50%; border: 2px solid rgba(0,0,0,0.5);
        margin-left: -8px; object-fit: cover;
    }
    .viewer-avatar-strip img:first-child { margin-left: 0; }

</style>

<div class="live-full" id="streamContainer">
    <?php
    $currentUser = \Core\Auth::user();
    $isOwner = $currentUser && isset($currentUser['id']) && isset($stream['user_id']) && $currentUser['id'] == $stream['user_id'];
    ?>

    <div id="remoteFeedContainer" class="absolute inset-0 bg-black <?= $isOwner ? 'hidden' : '' ?>">
        <video id="remoteFeed" autoplay playsinline class="absolute inset-0 w-full h-full object-cover"></video>
        <div id="viewerConnecting" class="absolute inset-0 flex items-center justify-center bg-gradient-to-b from-indigo-950/80 via-purple-950/60 to-black">
            <img src="<?= $stream['thumbnail'] ?>" alt="" class="absolute inset-0 w-full h-full object-cover opacity-40 scale-110 blur-sm">
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/70"></div>
            <div class="relative flex flex-col items-center">
                <div class="w-16 h-16 rounded-full border-4 border-brand-500/30 flex items-center justify-center">
                    <span class="material-icons-round text-white/80 text-4xl">sensors</span>
                </div>
                <span class="text-white/60 text-xs mt-3 font-medium">Connecting to stream...</span>
                <div class="flex items-center gap-1 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse" style="animation-delay:0.2s"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse" style="animation-delay:0.4s"></span>
                </div>
            </div>
        </div>
    </div>

    <video id="webcamFeed" autoplay muted playsinline class="absolute inset-0 w-full h-full object-cover remote-video <?= $isOwner ? '' : 'hidden' ?>"></video>

    <div id="streamPlaceholder" class="absolute inset-0 flex items-center justify-center bg-gradient-to-b from-indigo-950/80 via-purple-950/60 to-black <?= $isOwner ? 'hidden' : '' ?>">
        <img src="<?= $stream['thumbnail'] ?>" alt="" class="absolute inset-0 w-full h-full object-cover opacity-40 scale-110 blur-sm">
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/70"></div>
        <div class="relative flex flex-col items-center">
            <div class="w-20 h-20 rounded-full border-4 border-red-500/30 flex items-center justify-center">
                <span class="material-icons-round text-white/80 text-5xl">sensors</span>
            </div>
            <span class="text-white/60 text-xs mt-3 font-medium">Starting your stream...</span>
        </div>
    </div>

    <div id="hostControls" class="absolute inset-0 z-10 flex flex-col justify-between pointer-events-none <?= $isOwner ? '' : 'hidden' ?>">
        <div class="p-3 flex items-start justify-between pointer-events-auto">
            <div class="flex items-center gap-2">
                <a href="/livestream" class="w-8 h-8 rounded-full bg-black/50 backdrop-blur-sm flex items-center justify-center hover:bg-black/70 transition-colors">
                    <span class="material-icons-round text-white text-lg">chevron_left</span>
                </a>
                <div class="flex items-center gap-2 px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-sm">
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-red-500 rounded-full live-dot"></span>
                        <span class="text-red-400 text-[10px] font-bold">LIVE</span>
                    </span>
                    <span class="w-px h-3 bg-white/20"></span>
                    <span class="flex items-center gap-1 text-white/90 text-xs viewer-live">
                        <span class="material-icons-round text-[14px]">visibility</span>
                        <span id="viewerCount" class="font-semibold tabular-nums"><?= number_format((int)($stream['viewers'] ?? 0)) ?></span>
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="toggleCamera()" class="px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-sm text-white text-[10px] font-medium flex items-center gap-1 hover:bg-black/80 transition-colors connection-status">
                    <span class="material-icons-round text-[16px]">videocam</span>
                    <span id="camBtnText">Start Camera</span>
                </button>
                <button onclick="openManagePanel()" class="px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-sm text-white text-[10px] font-medium flex items-center gap-1 hover:bg-amber-500/40 transition-colors">
                    <span class="material-icons-round text-[16px]">tune</span>
                    Manage
                </button>
                <button onclick="endStream(<?= $stream['id'] ?>)" class="px-3 py-1.5 rounded-full bg-red-600/80 text-white text-[10px] font-medium flex items-center gap-1 hover:bg-red-700 transition-colors">
                    <span class="material-icons-round text-[16px]">stop</span>
                    End
                </button>
            </div>
        </div>

        <!-- TikTok bottom gradient + creator info -->
        <div class="absolute bottom-0 left-0 right-0 h-44 tiktok-bottom-gradient pointer-events-none z-10"></div>

        <!-- Creator info bottom left -->
        <div class="absolute bottom-20 left-3 z-20 pointer-events-none">
            <div class="flex items-center gap-2 mb-1.5">
                <div class="flex-shrink-0 relative">
                    <img src="<?= $stream['creator_avatar'] ?? 'https://placehold.co/80x80/6d28d9/ffffff?text=U' ?>" alt="" class="w-10 h-10 rounded-full border-2 border-white/50">
                    <?php if (!empty($stream['is_verified'])): ?>
                    <span class="absolute -bottom-0.5 -right-0.5 material-icons-round text-brand-400 text-sm bg-black rounded-full text-[14px]">verified</span>
                    <?php endif; ?>
                </div>
                <div class="pointer-events-auto">
                    <div class="flex items-center gap-1">
                        <span class="text-white text-sm font-bold drop-shadow-lg"><?= $stream['creator_name'] ?></span>
                        <?php if (!empty($stream['is_verified'])): ?>
                        <span class="material-icons-round text-brand-400 text-[14px] drop-shadow-lg">verified</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-white/80 text-[11px] drop-shadow-lg"><?= $stream['title'] ?></span>
                </div>
            </div>
            <!-- Viewer avatars strip -->
            <div class="flex items-center gap-1 mt-1">
                <div class="viewer-avatar-strip" id="viewerBubbles"></div>
                <span class="text-white/60 text-[10px] ml-1" id="viewerCountLabel"><?= number_format((int)($stream['viewers'] ?? 0)) ?> watching</span>
            </div>
        </div>

        <!-- TikTok right-side action buttons -->
        <div class="absolute right-3 bottom-28 z-20 tiktok-actions pointer-events-auto">
            <button onclick="likeStream(<?= $stream['id'] ?>)">
                <div class="action-icon">
                    <span class="material-icons-round text-white text-2xl transition-colors" id="likeIcon">favorite_border</span>
                </div>
                <span class="action-label" id="likeCount"><?= formatCount($stream['total_likes'] ?? 0) ?></span>
            </button>
            <button onclick="openGiftPanel()">
                <div class="action-icon">
                    <span class="material-icons-round text-white text-2xl">card_giftcard</span>
                </div>
                <span class="action-label">Gift</span>
            </button>
            <button onclick="openViewersList()">
                <div class="action-icon">
                    <span class="material-icons-round text-white text-2xl">people</span>
                </div>
                <span class="action-label">Viewers</span>
            </button>
            <button onclick="shareStream()">
                <div class="action-icon">
                    <span class="material-icons-round text-white text-2xl">share</span>
                </div>
                <span class="action-label">Share</span>
            </button>
        </div>

        <!-- TikTok floating comments area -->
        <div class="absolute right-16 bottom-36 left-3 z-20 pointer-events-none" id="tiktokComments"></div>

        <!-- Chat input bar -->
        <div class="absolute bottom-2 left-2 right-16 z-20 pointer-events-auto">
            <div class="flex items-center gap-1.5 bg-black/50 backdrop-blur-sm rounded-full px-3 py-2 border border-white/10">
                <input type="text" id="chatInput" placeholder="Send a message..." class="flex-1 bg-transparent text-white text-xs placeholder:text-zinc-400 focus:outline-none min-w-0" onkeydown="if(event.key==='Enter')sendMessage(<?= $stream['id'] ?>)">
                <button onclick="openGiftPanel()" class="flex-shrink-0">
                    <span class="material-icons-round text-amber-400 text-lg">card_giftcard</span>
                </button>
                <button onclick="sendMessage(<?= $stream['id'] ?>)" class="flex-shrink-0">
                    <span class="material-icons-round text-brand-400 text-lg">send</span>
                </button>
            </div>
        </div>
    </div>

    <div id="viewerOverlay" class="absolute inset-0 z-10 flex flex-col justify-between pointer-events-none <?= $isOwner ? 'hidden' : '' ?>">
        <div class="p-3 flex items-start justify-between pointer-events-auto">
            <div class="flex items-center gap-2">
                <a href="/livestream" class="w-9 h-9 rounded-full bg-black/40 backdrop-blur-sm flex items-center justify-center hover:bg-black/60 transition-colors shadow-lg">
                    <span class="material-icons-round text-white text-xl">chevron_left</span>
                </a>
                <span class="flex items-center gap-1 px-2.5 py-1 rounded-full bg-black/50 backdrop-blur-sm">
                    <span class="w-2 h-2 bg-red-500 rounded-full live-dot"></span>
                    <span class="text-red-400 text-[10px] font-bold">LIVE</span>
                    <span class="w-px h-3 bg-white/20 ml-1"></span>
                    <span class="flex items-center gap-1 text-white/90 text-xs ml-1">
                        <span class="material-icons-round text-[14px]">visibility</span>
                        <span id="viewerCountV" class="font-semibold tabular-nums"><?= number_format((int)($stream['viewers'] ?? 0)) ?></span>
                    </span>
                </span>
            </div>
            <div class="flex items-center gap-1.5">
                <button onclick="openReportModal()" class="w-9 h-9 rounded-full bg-black/40 backdrop-blur-sm flex items-center justify-center hover:bg-red-500/40 transition-colors shadow-lg" title="Report">
                    <span class="material-icons-round text-white text-xl">more_vert</span>
                </button>
            </div>
        </div>

        <!-- TikTok bottom gradient + creator info -->
        <div class="absolute bottom-0 left-0 right-0 h-44 tiktok-bottom-gradient pointer-events-none z-10"></div>

        <!-- Creator info bottom left -->
        <div class="absolute bottom-20 left-3 z-20 pointer-events-none">
            <div class="flex items-center gap-2">
                <div class="flex-shrink-0 relative">
                    <img src="<?= $stream['creator_avatar'] ?? 'https://placehold.co/80x80/6d28d9/ffffff?text=U' ?>" alt="" class="w-10 h-10 rounded-full border-2 border-white/50">
                    <?php if (!empty($stream['is_verified'])): ?>
                    <span class="absolute -bottom-0.5 -right-0.5 material-icons-round text-brand-400 text-sm bg-black rounded-full text-[14px]">verified</span>
                    <?php endif; ?>
                </div>
                <div class="pointer-events-auto">
                    <div class="flex items-center gap-1">
                        <span class="text-white text-sm font-bold drop-shadow-lg"><?= $stream['creator_name'] ?></span>
                        <?php if (!empty($stream['is_verified'])): ?>
                        <span class="material-icons-round text-brand-400 text-[14px] drop-shadow-lg">verified</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-white/80 text-[11px] drop-shadow-lg"><?= $stream['title'] ?></span>
                </div>
            </div>
            <!-- Viewer avatars strip -->
            <div class="flex items-center gap-1 mt-1">
                <div class="viewer-avatar-strip" id="viewerBubblesV"></div>
                <span class="text-white/60 text-[10px] ml-1" id="viewerCountLabelV"><?= number_format((int)($stream['viewers'] ?? 0)) ?> watching</span>
            </div>
        </div>

        <!-- TikTok right-side action buttons -->
        <div class="absolute right-3 bottom-28 z-20 tiktok-actions pointer-events-auto">
            <button onclick="likeStream(<?= $stream['id'] ?>)">
                <div class="action-icon">
                    <span class="material-icons-round text-white text-2xl transition-colors" id="likeIconV">favorite_border</span>
                </div>
                <span class="action-label" id="likeCountV"><?= formatCount($stream['total_likes'] ?? 0) ?></span>
            </button>
            <button onclick="openGiftPanel()">
                <div class="action-icon">
                    <span class="material-icons-round text-white text-2xl">card_giftcard</span>
                </div>
                <span class="action-label">Gift</span>
            </button>
            <button onclick="openViewersList()">
                <div class="action-icon">
                    <span class="material-icons-round text-white text-2xl">people</span>
                </div>
                <span class="action-label">Viewers</span>
            </button>
            <button onclick="shareStream()">
                <div class="action-icon">
                    <span class="material-icons-round text-white text-2xl">share</span>
                </div>
                <span class="action-label">Share</span>
            </button>
        </div>

        <!-- TikTok floating comments area -->
        <div class="absolute right-16 bottom-36 left-3 z-20 pointer-events-none" id="tiktokCommentsV"></div>

        <!-- Chat input bar -->
        <div class="absolute bottom-2 left-2 right-16 z-20 pointer-events-auto">
            <div class="flex items-center gap-1.5 bg-black/50 backdrop-blur-sm rounded-full px-3 py-2 border border-white/10">
                <input type="text" id="chatInput" placeholder="Send a message..." class="flex-1 bg-transparent text-white text-xs placeholder:text-zinc-400 focus:outline-none min-w-0" onkeydown="if(event.key==='Enter')sendMessage(<?= $stream['id'] ?>)">
                <button onclick="openGiftPanel()" class="flex-shrink-0">
                    <span class="material-icons-round text-amber-400 text-lg">card_giftcard</span>
                </button>
                <button onclick="sendMessage(<?= $stream['id'] ?>)" class="flex-shrink-0">
                    <span class="material-icons-round text-brand-400 text-lg">send</span>
                </button>
            </div>
        </div>
    </div>

    <div class="absolute right-16 bottom-36 left-3 z-20 pointer-events-none" id="tiktokComments"></div>

    <!-- Floating hearts container -->
    <div id="floatingHearts" class="absolute inset-0 pointer-events-none z-30"></div>
</div>

<?php if ($isOwner): ?>
<!-- HOST MANAGEMENT PANEL -->
<div id="managePanel" class="fixed inset-0 z-50 hidden" onclick="closeManagePanel(event)">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-surface-50 rounded-t-3xl slide-up max-w-lg mx-auto max-h-[80vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-4 border-b border-surface-400/20 sticky top-0 bg-surface-50 z-10">
            <h3 class="font-display text-white font-bold text-lg">Stream Management</h3>
            <button onclick="closeManagePanel()" class="w-8 h-8 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">close</span>
            </button>
        </div>
        <div class="p-4 space-y-4">
            <div class="flex gap-2">
                <button onclick="switchManageTab('viewers')" id="mTabViewers" class="flex-1 py-2 rounded-xl bg-brand-500/20 border border-brand-500/30 text-brand-300 text-xs font-semibold">Viewers</button>
                <button onclick="switchManageTab('banned')" id="mTabBanned" class="flex-1 py-2 rounded-xl bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white">Banned</button>
                <button onclick="switchManageTab('settings')" id="mTabSettings" class="flex-1 py-2 rounded-xl bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white">Settings</button>
            </div>

            <div id="manageViewersTab">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-white text-xs font-bold">Active Viewers <span id="viewerListCount" class="text-zinc-500 font-normal">(0)</span></h4>
                    <button onclick="refreshViewerList()" class="text-zinc-500 hover:text-white transition-colors">
                        <span class="material-icons-round text-lg">refresh</span>
                    </button>
                </div>
                <div id="viewerListContainer" class="space-y-1.5 max-h-60 overflow-y-auto">
                    <div class="text-center py-6 text-zinc-500 text-xs">Loading viewers...</div>
                </div>
            </div>

            <div id="manageBannedTab" class="hidden">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-white text-xs font-bold">Banned Users</h4>
                </div>
                <div id="bannedListContainer" class="space-y-1.5 max-h-60 overflow-y-auto">
                    <div class="text-center py-6 text-zinc-500 text-xs">Click refresh to load</div>
                </div>
            </div>

            <div id="manageSettingsTab" class="hidden space-y-3">
                <div class="bg-surface-100/40 rounded-xl border border-surface-400/10 p-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-round text-zinc-400">pause_circle</span>
                        <span class="text-white text-xs font-medium">Pause Stream</span>
                    </div>
                    <button onclick="togglePause()" id="pauseBtn" class="px-3 py-1.5 rounded-lg bg-amber-600/20 text-amber-400 text-[10px] font-semibold hover:bg-amber-600/30 transition-colors">Pause</button>
                </div>
                <div class="bg-surface-100/40 rounded-xl border border-surface-400/10 p-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-round text-zinc-400">group_add</span>
                        <span class="text-white text-xs font-medium">Co-Host</span>
                    </div>
                    <button onclick="openAddCohost()" class="px-3 py-1.5 rounded-lg bg-brand-500/20 text-brand-400 text-[10px] font-semibold hover:bg-brand-500/30 transition-colors">Add</button>
                </div>
                <div class="bg-surface-100/40 rounded-xl border border-surface-400/10 p-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-round text-zinc-400">auto_awesome</span>
                        <span class="text-white text-xs font-medium">Featured</span>
                    </div>
                    <button onclick="toggleFeatured()" id="featuredBtn" class="px-3 py-1.5 rounded-lg bg-surface-200 text-zinc-400 text-[10px] font-semibold hover:bg-surface-300 transition-colors">Promote</button>
                </div>
                <div class="bg-surface-100/40 rounded-xl border border-red-500/20 p-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="material-icons-round text-red-400">stop</span>
                        <span class="text-red-400 text-xs font-medium">End Stream</span>
                    </div>
                    <button onclick="endStream(<?= $stream['id'] ?>)" class="px-3 py-1.5 rounded-lg bg-red-600/20 text-red-400 text-[10px] font-semibold hover:bg-red-600/30 transition-colors">End</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- VIEWERS LIST PANEL (for both host & viewers) -->
<div id="viewersListPanel" class="fixed inset-0 z-50 hidden" onclick="closeViewersList(event)">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-surface-50 rounded-t-3xl slide-up max-w-lg mx-auto max-h-[70vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-4 border-b border-surface-400/20 sticky top-0 bg-surface-50 z-10">
            <h3 class="font-display text-white font-bold text-lg">Viewers <span id="viewersListPanelCount" class="text-zinc-500 text-sm font-normal">(0)</span></h3>
            <button onclick="closeViewersList()" class="w-8 h-8 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">close</span>
            </button>
        </div>
        <div id="viewersPanelList" class="p-4 space-y-2">
            <div class="text-center py-8 text-zinc-500 text-xs">Loading viewers...</div>
        </div>
    </div>
</div>

<!-- REPORT MODAL -->
<div id="reportModal" class="fixed inset-0 z-50 hidden" onclick="closeReportModal(event)">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-surface-50 rounded-t-3xl slide-up max-w-lg mx-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-4 border-b border-surface-400/20">
            <h3 class="font-display text-white font-bold text-lg">Report Stream</h3>
            <button onclick="closeReportModal()" class="w-8 h-8 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">close</span>
            </button>
        </div>
        <div class="p-4 space-y-3">
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Reason</label>
                <select id="reportReason" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-red-500/60 focus:outline-none text-sm">
                    <option value="Inappropriate content">Inappropriate content</option>
                    <option value="Harassment">Harassment or bullying</option>
                    <option value="Hate speech">Hate speech</option>
                    <option value="Violence">Violent or threatening</option>
                    <option value="Copyright">Copyright violation</option>
                    <option value="Spam">Spam or misleading</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Details (optional)</label>
                <textarea id="reportDetails" rows="3" placeholder="Provide more information..." class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-red-500/60 focus:outline-none text-sm resize-none"></textarea>
            </div>
            <button onclick="submitReport(<?= $stream['id'] ?>)" class="w-full py-2.5 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                <span class="material-icons-round text-lg">flag</span>
                Submit Report
            </button>
        </div>
    </div>
</div>

<!-- SHARE MODAL -->
<div id="shareModal" class="fixed inset-0 z-50 hidden" onclick="closeShareModal(event)">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-surface-50 rounded-t-3xl slide-up max-w-lg mx-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-4 border-b border-surface-400/20">
            <h3 class="font-display text-white font-bold text-lg">Share Stream</h3>
            <button onclick="closeShareModal()" class="w-8 h-8 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">close</span>
            </button>
        </div>
        <div class="p-6">
            <p class="text-zinc-400 text-xs mb-4 text-center">Share this livestream with your friends</p>
            <div class="grid grid-cols-4 gap-4 mb-5">
                <button onclick="shareTo('whatsapp')" class="share-option flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-200/60 hover:bg-surface-200 border border-surface-400/20">
                    <div class="w-12 h-12 rounded-full bg-emerald-600 flex items-center justify-center">
                        <span class="material-icons-round text-white text-2xl">chat</span>
                    </div>
                    <span class="text-zinc-400 text-[10px] font-medium">WhatsApp</span>
                </button>
                <button onclick="shareTo('twitter')" class="share-option flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-200/60 hover:bg-surface-200 border border-surface-400/20">
                    <div class="w-12 h-12 rounded-full bg-sky-600 flex items-center justify-center">
                        <span class="material-icons-round text-white text-2xl">alternate_email</span>
                    </div>
                    <span class="text-zinc-400 text-[10px] font-medium">Twitter</span>
                </button>
                <button onclick="shareTo('facebook')" class="share-option flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-200/60 hover:bg-surface-200 border border-surface-400/20">
                    <div class="w-12 h-12 rounded-full bg-blue-700 flex items-center justify-center">
                        <span class="material-icons-round text-white text-2xl">thumb_up</span>
                    </div>
                    <span class="text-zinc-400 text-[10px] font-medium">Facebook</span>
                </button>
                <button onclick="shareTo('copy')" class="share-option flex flex-col items-center gap-2 p-3 rounded-xl bg-surface-200/60 hover:bg-surface-200 border border-surface-400/20">
                    <div class="w-12 h-12 rounded-full bg-brand-600 flex items-center justify-center">
                        <span class="material-icons-round text-white text-2xl">link</span>
                    </div>
                    <span class="text-zinc-400 text-[10px] font-medium">Copy Link</span>
                </button>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-200/60 border border-surface-400/20">
                <span class="material-icons-round text-zinc-400 text-lg flex-shrink-0">share</span>
                <span class="text-zinc-400 text-xs truncate flex-1" id="shareUrlDisplay"><?= addslashes($stream['title']) ?></span>
                <button onclick="shareTo('copy')" class="px-3 py-1.5 rounded-lg gradient-brand text-white text-[10px] font-bold flex-shrink-0">Copy</button>
            </div>
            <div class="mt-4 text-center">
                <span class="text-zinc-500 text-[10px]">Shared <span id="shareCountDisplay"><?= formatCount($stream['total_shares'] ?? 0) ?></span> times</span>
            </div>
        </div>
    </div>
</div>

<!-- ADD CO-HOST MODAL -->
<div id="cohostModal" class="fixed inset-0 z-50 hidden" onclick="closeCohostModal(event)">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-surface-50 rounded-t-3xl slide-up max-w-lg mx-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-4 border-b border-surface-400/20">
            <h3 class="font-display text-white font-bold text-lg">Add Co-Host</h3>
            <button onclick="closeCohostModal()" class="w-8 h-8 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">close</span>
            </button>
        </div>
        <div class="p-4 space-y-3">
            <p class="text-zinc-400 text-xs">Enter the username of the user you want to add as a co-host for this stream.</p>
            <input type="text" id="cohostUsername" placeholder="Username..." class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
            <button onclick="submitCohost(<?= $stream['id'] ?>)" class="w-full py-2.5 rounded-xl gradient-brand text-white text-sm font-bold hover:opacity-90 transition-all">Add Co-Host</button>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION -->
<div id="liveToast" class="fixed top-4 left-1/2 -translate-x-1/2 z-[200] hidden">
    <div class="px-4 py-2 rounded-full bg-emerald-600/90 text-white text-sm font-medium backdrop-blur-sm shadow-lg flex items-center gap-2">
        <span class="material-icons-round text-lg" id="toastIcon">check_circle</span>
        <span id="toastMessage">Done</span>
    </div>
</div>

<div id="giftPanel" class="fixed inset-0 z-50 hidden" onclick="closeGiftPanel(event)">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-surface-50 rounded-t-3xl slide-up max-w-lg mx-auto" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-4 border-b border-surface-400/20">
            <h3 class="font-display text-white font-bold text-lg">Send a Gift</h3>
            <button onclick="closeGiftPanel()" class="w-8 h-8 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">close</span>
            </button>
        </div>
        <div class="p-4">
            <div class="flex items-center gap-2 mb-4">
                <span class="material-icons-round text-amber-400 text-lg">monetization_on</span>
                <span class="text-white text-sm font-semibold">Wallet: <span class="text-brand-400"><?= $currencyInfo['symbol'] ?> <?= $wallet ? number_format((float)$wallet['balance'], 2) : '0.00' ?></span></span>
            </div>
            <div class="grid grid-cols-4 gap-3" id="giftGrid">
                <?php foreach ($gifts as $gift):
                    $priceLocal = round((float)$gift['price_usd'] * (float)$currencyInfo['rate'], 2);
                ?>
                <button onclick="sendGift(<?= $stream['id'] ?>, <?= $gift['id'] ?>)" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-200/60 hover:bg-surface-200 border border-surface-400/20 hover:border-brand-500/40 transition-all group" title="<?= htmlspecialchars($gift['description'] ?? $gift['name']) ?>">
                    <?php if (!empty($gift['image_url'])): ?>
                    <div class="w-9 h-9 rounded-lg overflow-hidden group-hover:scale-110 transition-transform">
                        <img src="<?= $gift['image_url'] ?>" alt="<?= $gift['name'] ?>" class="w-full h-full object-cover">
                    </div>
                    <?php else: ?>
                    <span class="material-icons-round <?= $gift['color_class'] ?> text-2xl group-hover:scale-110 transition-transform"><?= $gift['icon'] ?></span>
                    <?php endif; ?>
                    <span class="text-zinc-300 text-[10px] font-medium"><?= $gift['name'] ?></span>
                    <span class="text-brand-400 text-[9px] font-bold"><?= $currencyInfo['symbol'] ?> <?= number_format($priceLocal, 2) ?></span>
                </button>
                <?php endforeach; ?>
            </div>
            <button class="w-full mt-4 py-2.5 rounded-xl gradient-brand text-white text-sm font-semibold hover:opacity-90 transition-opacity">
                <span class="flex items-center justify-center gap-1">
                    <span class="material-icons-round text-lg">add</span>
                    Top Up Wallet
                </span>
            </button>
            <div id="giftHistoryPanel" class="mt-4 space-y-1.5 hidden">
                <h4 class="text-zinc-400 text-[10px] font-semibold uppercase tracking-wider">Gift History</h4>
                <div id="giftHistoryList" class="space-y-1"></div>
            </div>
        </div>
    </div>
</div>

<script>
const STREAM_ID = <?= $stream['id'] ?>;
const IS_HOST = <?= $isOwner ? 'true' : 'false' ?>;
const ICE_SERVERS = { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }, { urls: 'stun:stun1.l.google.com:19302' }] };

let likeCount = <?= (int)($stream['total_likes'] ?? 0) ?>;
let isLiked = false;
let cameraActive = false;
let localStream = null;
let hostPCs = {};
let viewerPC = null;
let viewerSid = 'v_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
let hostPollInterval = null;
let viewerPollInterval = null;
let lastSignalId = 0;
let lastTap = 0;
let likeBurstCount = 0;

/* ===== TIKTOK FLOATING COMMENTS ===== */
function addFloatingComment(html) {
    const container = IS_HOST ? document.getElementById('tiktokComments') : document.getElementById('tiktokCommentsV');
    if (!container) return;
    const div = document.createElement('div');
    div.className = 'tiktok-comment mb-1.5';
    div.innerHTML = html;
    container.appendChild(div);
    if (container.children.length > 8) container.removeChild(container.children[0]);
    setTimeout(() => { if (div.parentNode) div.remove(); }, 5000);
}

function addComment(html) {
    addFloatingComment(html);
}

/* ===== SIGNALING ===== */
function sendSignal(sender, type, data, vsid) {
    return fetch('/livestream/' + STREAM_ID + '/signal', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ sender: sender, type: type, data: JSON.stringify(data), viewer_sid: vsid || '' })
    }).then(r => r.json());
}

function pollSignals(sender, vsid) {
    let url = '/livestream/' + STREAM_ID + '/signal?sender=' + sender;
    if (vsid) url += '&viewer_sid=' + vsid;
    return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json());
}

/* ===== HOST: BROADCAST CAMERA TO ALL VIEWERS ===== */
async function startHostBroadcast() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        const video = document.getElementById('webcamFeed');
        video.srcObject = localStream;
        video.classList.remove('hidden');
        document.getElementById('streamPlaceholder').classList.add('hidden');
        cameraActive = true;
        document.getElementById('camBtnText').textContent = 'On Air';

        await sendSignal('host', 'offer', { type: 'broadcast', ready: true });
        startHostPolling();
    } catch (err) {
        alert('Camera access denied. Please allow camera permissions.');
    }
}

async function startHostPolling() {
    const seen = new Set();
    hostPollInterval = setInterval(async () => {
        const res = await pollSignals('viewer', '');
        if (res.signals) {
            for (const s of res.signals) {
                if (seen.has(s.id)) continue;
                seen.add(s.id);
                if (s.type === 'answer' && s.viewer_sid && !hostPCs[s.viewer_sid]) {
                    connectToViewer(s.viewer_sid, JSON.parse(s.data));
                } else if (s.type === 'ice' && s.viewer_sid && hostPCs[s.viewer_sid]) {
                    try {
                        await hostPCs[s.viewer_sid].addIceCandidate(new RTCIceCandidate(JSON.parse(s.data)));
                    } catch(e) {}
                }
            }
        }
    }, 1000);
}

async function connectToViewer(vsid, answerData) {
    const pc = new RTCPeerConnection(ICE_SERVERS);
    hostPCs[vsid] = pc;

    localStream.getTracks().forEach(track => pc.addTrack(track, localStream));

    pc.onicecandidate = async (e) => {
        if (e.candidate) {
            await sendSignal('host', 'ice', { candidate: e.candidate }, vsid);
        }
    };

    pc.onconnectionstatechange = () => {
        if (pc.connectionState === 'disconnected' || pc.connectionState === 'failed') {
            delete hostPCs[vsid];
        }
    };

    try {
        await pc.setRemoteDescription(new RTCSessionDescription(answerData));
    } catch(e) {}
}

/* ===== VIEWER: RECEIVE HOST STREAM ===== */
async function startViewerConnection() {
    fetch('/livestream/' + STREAM_ID + '/join', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});

    viewerPollInterval = setInterval(async () => {
        const res = await pollSignals('host', viewerSid);
        if (res.signals && !viewerPC) {
            for (const s of res.signals) {
                if (s.type === 'offer') {
                    tryConnectAsViewer();
                    break;
                }
            }
        } else if (res.signals && viewerPC) {
            for (const s of res.signals) {
                if (s.type === 'ice' && s.id > lastSignalId) {
                    lastSignalId = s.id;
                    try {
                        await viewerPC.addIceCandidate(new RTCIceCandidate(JSON.parse(JSON.parse(s.data)).candidate));
                    } catch(e) {}
                }
            }
        }
    }, 1000);

    setTimeout(() => {
        if (!viewerPC) tryConnectAsViewer();
    }, 2000);
}

async function tryConnectAsViewer() {
    if (viewerPC) return;
    const res = await pollSignals('host', viewerSid);
    if (!res.signals || res.signals.length === 0) return;

    viewerPC = new RTCPeerConnection(ICE_SERVERS);

    viewerPC.ontrack = (event) => {
        const video = document.getElementById('remoteFeed');
        if (event.streams[0]) {
            video.srcObject = event.streams[0];
            document.getElementById('viewerConnecting').classList.add('hidden');
            document.getElementById('remoteFeedContainer').classList.remove('hidden');
        }
    };

    viewerPC.onicecandidate = async (e) => {
        if (e.candidate) {
            await sendSignal('viewer', 'ice', { candidate: e.candidate }, viewerSid);
        }
    };

    viewerPC.onconnectionstatechange = () => {
        if (viewerPC.connectionState === 'connected') {
            document.getElementById('viewerConnecting').classList.add('hidden');
        }
    };

    try {
        for (const s of res.signals) {
            if (s.type === 'offer') {
                const offerData = JSON.parse(s.data);
                if (offerData.type === 'broadcast') {
                    const dummyOffer = { type: 'offer', sdp: '' };
                    await viewerPC.setRemoteDescription(new RTCSessionDescription(dummyOffer));
                    const answer = await viewerPC.createAnswer({ offerToReceiveVideo: true, offerToReceiveAudio: true });
                    await viewerPC.setLocalDescription(answer);
                    await sendSignal('viewer', 'answer', answer, viewerSid);
                    return;
                }
            }
        }
    } catch(e) {}
}

/* ===== CAMERA TOGGLE (HOST) ===== */
async function toggleCamera() {
    if (!IS_HOST) return;
    if (cameraActive) {
        if (localStream) localStream.getTracks().forEach(t => t.stop());
        cameraActive = false;
        document.getElementById('camBtnText').textContent = 'Start Camera';
        document.getElementById('webcamFeed').classList.add('hidden');
        document.getElementById('streamPlaceholder').classList.remove('hidden');
        if (hostPollInterval) clearInterval(hostPollInterval);
    } else {
        await startHostBroadcast();
    }
}

/* ===== LIKE (TikTok-style double-tap) ===== */
function showHeartBurst() {
    const container = document.getElementById('floatingHearts');
    if (!container) return;
    likeBurstCount++;
    const bigHeart = document.createElement('span');
    bigHeart.className = 'material-icons-round text-red-400 double-tap-heart';
    bigHeart.textContent = 'favorite';
    container.appendChild(bigHeart);
    setTimeout(() => bigHeart.remove(), 600);

    for (let i = 0; i < 6; i++) {
        const particle = document.createElement('span');
        particle.className = 'material-icons-round text-red-300 absolute';
        particle.textContent = 'favorite';
        particle.style.fontSize = (12 + Math.random() * 18) + 'px';
        particle.style.left = '50%';
        particle.style.top = '50%';
        particle.style.setProperty('--tx', (Math.random() * 200 - 100) + 'px');
        particle.style.setProperty('--ty', (Math.random() * 200 - 100) + 'px');
        particle.style.animation = 'burst-particles 0.8s ease-out forwards';
        particle.style.pointerEvents = 'none';
        particle.style.zIndex = '50';
        container.appendChild(particle);
        setTimeout(() => particle.remove(), 800);
    }
}

function likeStream(id) {
    const iconEl = IS_HOST ? document.getElementById('likeIcon') : document.getElementById('likeIconV');
    const countEl = IS_HOST ? document.getElementById('likeCount') : document.getElementById('likeCountV');
    if (!isLiked) { iconEl.textContent = 'favorite'; iconEl.classList.add('text-red-400'); isLiked = true; }
    likeCount++; countEl.textContent = formatCount(likeCount);
    showHeartBurst();
    fetch('/livestream/' + id + '/like', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

function handleDoubleTap(e) {
    const now = Date.now();
    if (now - lastTap < 400) {
        e.preventDefault();
        likeStream(STREAM_ID);
        lastTap = 0;
    } else {
        lastTap = now;
    }
}

/* ===== CHAT ===== */
function sendMessage(id) {
    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if (!msg) return;
    addComment('<div class="flex items-center gap-1.5"><div class="w-6 h-6 rounded-full bg-brand-600 flex-shrink-0 flex items-center justify-center"><span class="material-icons-round text-white text-xs">person</span></div><div class="bg-black/50 backdrop-blur-sm rounded-full px-3 py-1.5"><span class="text-brand-300 text-xs font-bold">You </span><span class="text-white/90 text-xs">' + escapeHtml(msg) + '</span></div></div>');
    input.value = '';
    fetch('/livestream/' + id + '/comment', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ message: msg }) }).catch(() => {});
}

/* ===== GIFTS (TikTok-style) ===== */
function sendGift(id, giftId) {
    document.getElementById('giftPanel').classList.add('hidden');

    const giftGrid = document.getElementById('giftGrid');
    const btn = giftGrid.querySelector('button[onclick*="' + giftId + '"]');
    const giftName = btn ? btn.querySelector('.text-zinc-300').textContent : 'Gift';
    const giftImg = btn ? btn.querySelector('img') : null;
    const giftIconEl = btn ? btn.querySelector('.material-icons-round') : null;
    const giftColor = btn ? (btn.querySelector('[class*="text-"]')?.className.match(/text-\w+-\d+/)?.[0] || 'text-amber-400') : 'text-amber-400';
    const hasImage = !!giftImg;

    /* Show gift sent overlay on video */
    showGiftAnimation(hasImage ? giftImg.src : null, hasImage ? null : (giftIconEl ? giftIconEl.textContent : 'card_giftcard'), hasImage ? null : giftColor, giftName);

    /* Floating comment */
    addComment('<div class="flex items-center gap-1 bg-amber-500/20 backdrop-blur-sm rounded-full px-3 py-1.5 border border-amber-500/30"><span class="material-icons-round text-amber-400 text-sm">card_giftcard</span><span class="text-amber-300 text-xs font-semibold">You </span><span class="text-white/80 text-xs">sent </span><span class="text-amber-300 text-xs font-bold">' + giftName + '</span></div>');

    fetch('/livestream/' + id + '/gift', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ gift_id: giftId }) })
    .then(r => r.json()).then(d => {
        if (d.error) {
            showLiveToast(d.error);
        } else {
            showGiftSentToast(giftName, d.price_local || 0);
            refreshWalletBalance();
            addGiftHistory(giftName, 'card_giftcard', 'text-amber-400', d.price_local || 0);
        }
    }).catch(() => {});
}

function showGiftAnimation(imgSrc, icon, colorClass, name) {
    const container = document.getElementById('streamContainer');
    if (!container) return;

    const el = document.createElement('div');
    el.className = 'gift-sent-icon absolute top-1/3 left-1/2 -translate-x-1/2 z-30 flex flex-col items-center gap-2';

    if (imgSrc) {
        el.innerHTML = '<img src="' + imgSrc + '" style="width:72px;height:72px;object-fit:contain;filter:drop-shadow(0 4px 12px rgba(0,0,0,0.5))"><span class="text-white text-sm font-bold drop-shadow-lg">' + name + '</span>';
    } else {
        el.innerHTML = '<span class="material-icons-round ' + (colorClass || 'text-amber-400') + '" style="font-size:72px">' + (icon || 'card_giftcard') + '</span><span class="text-white text-sm font-bold drop-shadow-lg">' + name + '</span>';
    }
    container.appendChild(el);
    setTimeout(function() { el.remove(); }, 3000);

    /* Falling coins */
    for (let i = 0; i < 8; i++) {
        const coin = document.createElement('span');
        coin.className = 'gift-coin material-icons-round text-amber-400 absolute z-30';
        coin.textContent = 'monetization_on';
        coin.style.fontSize = (14 + Math.random() * 16) + 'px';
        coin.style.left = (20 + Math.random() * 60) + '%';
        coin.style.top = (30 + Math.random() * 20) + '%';
        coin.style.animationDelay = (Math.random() * 0.5) + 's';
        coin.style.animationDuration = (0.8 + Math.random() * 0.6) + 's';
        container.appendChild(coin);
        setTimeout(function() { coin.remove(); }, 2000);
    }
}

function showGiftSentToast(name, price) {
    const toast = document.getElementById('liveToast');
    const messageEl = document.getElementById('toastMessage');
    const iconEl = document.getElementById('toastIcon');
    if (!toast) return;
    messageEl.textContent = 'Sent ' + name + '!';
    iconEl.textContent = 'card_giftcard';
    toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-[200]';
    toast.querySelector('div').className = 'px-4 py-2 rounded-full bg-amber-600/90 text-white text-sm font-medium backdrop-blur-sm shadow-lg flex items-center gap-2';
    toast.classList.remove('hidden');
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(-20px)';
        setTimeout(() => toast.classList.add('hidden'), 300);
    }, 3000);
}

let giftHistory = [];

function addGiftHistory(name, icon, colorClass, price) {
    giftHistory.unshift({ name: name, icon: icon, color: colorClass, price: price, time: new Date() });
    if (giftHistory.length > 10) giftHistory.pop();

    const panel = document.getElementById('giftHistoryPanel');
    const list = document.getElementById('giftHistoryList');
    if (!panel || !list) return;
    panel.classList.remove('hidden');
    list.innerHTML = giftHistory.map((g, i) =>
        '<div class="gift-history-item flex items-center gap-2 p-2 rounded-lg bg-surface-200/50" style="animation-delay:' + (i * 0.05) + 's">' +
            '<span class="material-icons-round ' + g.color + ' text-lg">' + g.icon + '</span>' +
            '<span class="text-white text-xs flex-1">' + g.name + '</span>' +
            '<span class="text-zinc-500 text-[10px]">' + g.time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) + '</span>' +
        '</div>'
    ).join('');
}

function refreshWalletBalance() {
    fetch('/wallet', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.text()).then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const balanceEl = doc.querySelector('[data-wallet-balance]');
        if (balanceEl) {
            const panelBalance = document.querySelector('#giftPanel .text-brand-400');
            if (panelBalance) panelBalance.textContent = balanceEl.textContent;
        }
    }).catch(() => {});
}

function openGiftPanel() {
    document.getElementById('giftPanel').classList.remove('hidden');
    /* Auto-refresh gift history on open */
    const panel = document.getElementById('giftHistoryPanel');
    if (panel && giftHistory.length > 0) {
        panel.classList.remove('hidden');
    }
}
function closeGiftPanel(e) {
    if (!e || e.target === e.currentTarget) {
        document.getElementById('giftPanel').classList.add('hidden');
    }
}

function endStream(id) {
    if (!confirm('End this livestream?')) return;
    if (localStream) localStream.getTracks().forEach(t => t.stop());
    if (hostPollInterval) clearInterval(hostPollInterval);
    if (viewerPollInterval) clearInterval(viewerPollInterval);
    Object.values(hostPCs).forEach(pc => pc.close());
    if (viewerPC) viewerPC.close();
    fetch('/livestream/' + id + '/end', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(() => { window.location.href = '/livestream'; }).catch(() => { window.location.href = '/livestream'; });
}

/* ===== HOST MANAGEMENT PANEL ===== */
function openManagePanel() { document.getElementById('managePanel').classList.remove('hidden'); refreshViewerList(); }
function closeManagePanel(e) { if (!e || e.target === e.currentTarget) document.getElementById('managePanel').classList.add('hidden'); }

function switchManageTab(tab) {
    ['viewers','banned','settings'].forEach(t => {
        document.getElementById('mTab' + t.charAt(0).toUpperCase() + t.slice(1)).className = 'flex-1 py-2 rounded-xl bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white';
        document.getElementById('manage' + t.charAt(0).toUpperCase() + t.slice(1) + 'Tab').classList.add('hidden');
    });
    const activeTab = document.getElementById('mTab' + tab.charAt(0).toUpperCase() + tab.slice(1));
    activeTab.className = 'flex-1 py-2 rounded-xl bg-brand-500/20 border border-brand-500/30 text-brand-300 text-xs font-semibold';
    document.getElementById('manage' + tab.charAt(0).toUpperCase() + tab.slice(1) + 'Tab').classList.remove('hidden');
    if (tab === 'viewers') refreshViewerList();
    if (tab === 'banned') refreshBannedList();
}

function refreshViewerList() {
    const container = document.getElementById('viewerListContainer');
    if (!container) return;
    container.innerHTML = '<div class="text-center py-4"><span class="material-icons-round animate-spin text-zinc-500 text-2xl">refresh</span></div>';
    fetch('/livestream/<?= $stream['id'] ?>/viewers', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => {
        const count = d.count || 0;
        document.getElementById('viewerListCount').textContent = '(' + count + ')';
        if (d.viewers && d.viewers.length > 0) {
            container.innerHTML = d.viewers.map(v => renderViewerRow(v)).join('');
        } else {
            container.innerHTML = '<div class="text-center py-6 text-zinc-500 text-xs">No active viewers</div>';
        }
    }).catch(() => {
        container.innerHTML = '<div class="text-center py-6 text-zinc-500 text-xs">Failed to load viewers</div>';
    });
}

function refreshBannedList() {
    const container = document.getElementById('bannedListContainer');
    if (!container) return;
    container.innerHTML = '<div class="text-center py-4"><span class="material-icons-round animate-spin text-zinc-500 text-2xl">refresh</span></div>';
    fetch('/livestream/<?= $stream['id'] ?>/banned', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => {
        if (d.viewers && d.viewers.length > 0) {
            container.innerHTML = d.viewers.map(v => renderBannedRow(v)).join('');
        } else {
            container.innerHTML = '<div class="text-center py-6 text-zinc-500 text-xs">No banned users</div>';
        }
    }).catch(() => {
        container.innerHTML = '<div class="text-center py-6 text-zinc-500 text-xs">Failed to load</div>';
    });
}

function renderViewerRow(v) {
    const name = v.username || v.viewer_sid || 'Unknown';
    const avatar = v.avatar || 'https://picsum.photos/id/64/32/32';
    return '<div class="flex items-center justify-between p-2 rounded-lg bg-surface-200/50">' +
        '<div class="flex items-center gap-2 min-w-0">' +
            '<img src="' + avatar + '" class="w-7 h-7 rounded-full flex-shrink-0">' +
            '<span class="text-white text-xs truncate">' + escapeHtml(name) + '</span>' +
        '</div>' +
        '<div class="flex items-center gap-1 flex-shrink-0">' +
            '<button onclick="muteViewer(' + v.id + ')" class="w-7 h-7 rounded-lg bg-surface-200 hover:bg-amber-500/20 flex items-center justify-center" title="Mute">' +
                '<span class="material-icons-round text-zinc-400 text-sm">' + (v.is_muted ? 'mic_off' : 'mic') + '</span>' +
            '</button>' +
            '<button onclick="banViewer(' + v.id + ')" class="w-7 h-7 rounded-lg bg-surface-200 hover:bg-red-500/20 flex items-center justify-center" title="Ban">' +
                '<span class="material-icons-round text-red-400 text-sm">block</span>' +
            '</button>' +
        '</div>' +
    '</div>';
}

function renderBannedRow(v) {
    const name = v.username || v.viewer_sid || 'Unknown';
    const avatar = v.avatar || 'https://picsum.photos/id/64/32/32';
    return '<div class="flex items-center justify-between p-2 rounded-lg bg-surface-200/50">' +
        '<div class="flex items-center gap-2 min-w-0">' +
            '<img src="' + avatar + '" class="w-7 h-7 rounded-full flex-shrink-0">' +
            '<span class="text-white text-xs truncate">' + escapeHtml(name) + '</span>' +
        '</div>' +
        '<button onclick="unbanViewer(' + v.id + ')" class="px-2.5 py-1 rounded-lg bg-emerald-600/20 text-emerald-400 text-[10px] font-semibold hover:bg-emerald-600/30 transition-colors">Unban</button>' +
    '</div>';
}

function muteViewer(viewerId) {
    fetch('/livestream/<?= $stream['id'] ?>/mute/' + viewerId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { showToast(d.message || 'Muted'); refreshViewerList(); })
    .catch(() => {});
}

function unmuteViewer(viewerId) {
    fetch('/livestream/<?= $stream['id'] ?>/unmute/' + viewerId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { showToast(d.message || 'Unmuted'); refreshViewerList(); })
    .catch(() => {});
}

function banViewer(viewerId) {
    if (!confirm('Ban this viewer?')) return;
    fetch('/livestream/<?= $stream['id'] ?>/ban/' + viewerId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { showToast(d.message || 'Banned'); refreshViewerList(); refreshBannedList(); })
    .catch(() => {});
}

function unbanViewer(viewerId) {
    fetch('/livestream/<?= $stream['id'] ?>/unban/' + viewerId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => { showToast(d.message || 'Unbanned'); refreshBannedList(); })
    .catch(() => {});
}

/* ===== PAUSE / RESUME ===== */
function togglePause() {
    const btn = document.getElementById('pauseBtn');
    const isPaused = btn.textContent.trim() === 'Resume';
    const url = isPaused ? '/livestream/<?= $stream['id'] ?>/unpause' : '/livestream/<?= $stream['id'] ?>/pause';
    fetch(url, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(d => {
        if (!d.error) {
            btn.textContent = isPaused ? 'Pause' : 'Resume';
            btn.className = isPaused ? 'px-3 py-1.5 rounded-lg bg-amber-600/20 text-amber-400 text-[10px] font-semibold hover:bg-amber-600/30 transition-colors' : 'px-3 py-1.5 rounded-lg bg-emerald-600/20 text-emerald-400 text-[10px] font-semibold hover:bg-emerald-600/30 transition-colors';
            showToast(d.message || (isPaused ? 'Stream resumed' : 'Stream paused'));
        } else {
            showToast(d.error);
        }
    }).catch(() => {});
}

/* ===== FEATURED ===== */
function toggleFeatured() {
    const btn = document.getElementById('featuredBtn');
    const isFeatured = btn.textContent.trim() === 'Unfeature';
    const val = isFeatured ? 0 : 1;
    fetch('/livestream/<?= $stream['id'] ?>/featured', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ is_featured: val })
    }).then(r => r.json()).then(d => {
        if (!d.error) {
            btn.textContent = isFeatured ? 'Promote' : 'Unfeature';
            btn.className = isFeatured ? 'px-3 py-1.5 rounded-lg bg-surface-200 text-zinc-400 text-[10px] font-semibold hover:bg-surface-300 transition-colors' : 'px-3 py-1.5 rounded-lg bg-amber-600/20 text-amber-400 text-[10px] font-semibold hover:bg-amber-600/30 transition-colors';
            showToast(d.message || 'Updated');
        }
    }).catch(() => {});
}

/* ===== CO-HOST ===== */
function openAddCohost() { document.getElementById('cohostModal').classList.remove('hidden'); }
function closeCohostModal(e) { if (!e || e.target === e.currentTarget) document.getElementById('cohostModal').classList.add('hidden'); }
function submitCohost(id) {
    const username = document.getElementById('cohostUsername').value.trim();
    if (!username) { showToast('Enter a username'); return; }
    fetch('/users/search?q=' + encodeURIComponent(username), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(users => {
        if (users && users.length > 0) {
            return fetch('/livestream/' + id + '/cohost', {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ co_host_id: users[0].id })
            }).then(r => r.json());
        } else {
            showToast('User not found');
            return null;
        }
    }).then(d => {
        if (d && !d.error) { showToast('Co-host added!'); document.getElementById('cohostModal').classList.add('hidden'); document.getElementById('cohostUsername').value = ''; }
        else if (d && d.error) showToast(d.error);
    }).catch(() => showToast('Error adding co-host'));
}

/* ===== SHARE (TikTok-style) ===== */
let shareCount = <?= (int)($stream['total_shares'] ?? 0) ?>;

function shareStream() {
    document.getElementById('shareModal').classList.remove('hidden');
    document.getElementById('shareUrlDisplay').textContent = window.location.href;
}

function closeShareModal(e) {
    if (!e || e.target === e.currentTarget) document.getElementById('shareModal').classList.add('hidden');
}

function shareTo(platform) {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('<?= addslashes($stream['title']) ?>');
    let shareUrl = '';

    switch (platform) {
        case 'whatsapp':
            shareUrl = 'https://wa.me/?text=' + title + '%20' + url;
            break;
        case 'twitter':
            shareUrl = 'https://twitter.com/intent/tweet?text=' + title + '&url=' + url;
            break;
        case 'facebook':
            shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + url;
            break;
        case 'copy':
            if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    showToast('Link copied to clipboard!', 'link');
                }).catch(() => {
                    prompt('Copy link:', window.location.href);
                });
            } else {
                prompt('Copy link:', window.location.href);
            }
            break;
        case 'native':
            if (navigator.share) {
                navigator.share({ title: '<?= addslashes($stream['title']) ?>', url: window.location.href }).catch(() => {});
            }
            break;
    }

    if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=500');
    }

    /* Track share count */
    shareCount++;
    const display = document.getElementById('shareCountDisplay');
    if (display) display.textContent = formatCount(shareCount);

    fetch('/livestream/<?= $stream['id'] ?>/share', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});

    if (platform !== 'copy') {
        document.getElementById('shareModal').classList.add('hidden');
        showToast('Shared!', 'share');
    }
}

/* ===== REPORT ===== */
function openReportModal() { document.getElementById('reportModal').classList.remove('hidden'); }
function closeReportModal(e) { if (!e || e.target === e.currentTarget) document.getElementById('reportModal').classList.add('hidden'); }
function submitReport(id) {
    const reason = document.getElementById('reportReason').value;
    const details = document.getElementById('reportDetails').value.trim();
    fetch('/livestream/' + id + '/report', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ reason: reason, description: details })
    }).then(r => r.json()).then(d => {
        showToast(d.message || 'Report submitted');
        document.getElementById('reportModal').classList.add('hidden');
        document.getElementById('reportDetails').value = '';
    }).catch(() => {});
}

/* ===== VIEWERS (TikTok-style real-time) ===== */
let viewerBubbles = [];
let viewersCache = [];

function openViewersList() {
    document.getElementById('viewersListPanel').classList.remove('hidden');
    refreshViewersPanel();
}

function closeViewersList(e) {
    if (!e || e.target === e.currentTarget) document.getElementById('viewersListPanel').classList.add('hidden');
}

function refreshViewersPanel() {
    const container = document.getElementById('viewersPanelList');
    if (!container) return;
    container.innerHTML = '<div class="text-center py-4"><span class="material-icons-round animate-spin text-zinc-500 text-2xl">refresh</span></div>';
    fetchViewers(function(d) {
        const count = d.count || 0;
        document.getElementById('viewersListPanelCount').textContent = '(' + count + ')';
        if (d.viewers && d.viewers.length > 0) {
            container.innerHTML = d.viewers.map(function(v) {
                const name = v.username || v.viewer_sid || 'Guest';
                const avatar = v.avatar || 'https://picsum.photos/id/64/32/32';
                const isMod = v.is_moderator ? '<span class="material-icons-round text-brand-400 text-[12px]">verified</span>' : '';
                return '<div class="flex items-center gap-2 p-2.5 rounded-lg bg-surface-200/50 viewer-bubble">' +
                    '<img src="' + avatar + '" class="w-8 h-8 rounded-full">' +
                    '<span class="text-white text-xs font-medium">' + escapeHtml(name) + '</span>' + isMod +
                    '<span class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-500 live-dot"></span>' +
                '</div>';
            }).join('');
        } else {
            container.innerHTML = '<div class="text-center py-8 text-zinc-500 text-xs">No viewers yet</div>';
        }
    });
}

let viewerCountPollInterval = null;

function startViewerPolling() {
    if (viewerCountPollInterval) clearInterval(viewerCountPollInterval);
    viewerCountPollInterval = setInterval(function() {
        fetchViewers(function(d) {
            updateViewerBubbles(d.viewers || []);
            updateViewerCounts(d.count || 0);
        });
    }, 8000);
}

function fetchViewers(callback) {
    fetch('/livestream/<?= $stream['id'] ?>/viewers', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        viewersCache = d.viewers || [];
        callback(d);
    }).catch(function() { callback({ viewers: [], count: 0 }); });
}

function fetchViewerCount(callback) {
    fetch('/livestream/<?= $stream['id'] ?>/viewer-count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function(r) { return r.json(); })
    .then(function(d) { callback(d); })
    .catch(function() { callback({ count: 0 }); });
}

function updateViewerCounts(count) {
    const val = formatCount(count);
    const hostCount = document.getElementById('viewerCount');
    const viewerCount = document.getElementById('viewerCountV');
    const hostLabel = document.getElementById('viewerCountLabel');
    const viewerLabel = document.getElementById('viewerCountLabelV');
    if (hostCount) hostCount.textContent = val;
    if (viewerCount) viewerCount.textContent = val;
    if (hostLabel) hostLabel.textContent = count + ' watching';
    if (viewerLabel) viewerLabel.textContent = count + ' watching';
}

function updateViewerBubbles(viewers) {
    const container = document.getElementById('viewerBubbles');
    const containerV = document.getElementById('viewerBubblesV');
    if (!container && !containerV) return;

    const currentIds = viewers.map(function(v) { return v.id || v.viewer_sid; });
    const existingIds = viewerBubbles.map(function(v) { return v.id || v.viewer_sid; });

    function updateContainer(cont) {
        if (!cont) return;
        cont.querySelectorAll('[data-viewer-id]').forEach(function(el) {
            const id = el.getAttribute('data-viewer-id');
            if (currentIds.indexOf(id) === -1) {
                el.classList.add('leaving');
                setTimeout(function() { if (el.parentNode) el.remove(); }, 300);
            }
        });
        viewers.forEach(function(v, index) {
            const id = v.id || v.viewer_sid;
            if (existingIds.indexOf(id) === -1) {
                const name = v.username || v.viewer_sid || 'Guest';
                const avatar = v.avatar || 'https://placehold.co/28x28/3f3f46/ffffff?text=' + name.charAt(0).toUpperCase();
                const bubble = document.createElement('div');
                bubble.className = 'viewer-bubble';
                bubble.setAttribute('data-viewer-id', id);
                bubble.title = name;
                bubble.innerHTML = '<img src="' + avatar + '" alt="' + escapeHtml(name) + '">';
                bubble.style.cssText = 'position:relative;display:inline-block;margin-left:-8px;';
                bubble.style.animationDelay = (index * 0.1) + 's';
                if (index === 0) bubble.style.marginLeft = '0';
                cont.appendChild(bubble);
            }
        });
    }

    updateContainer(container);
    updateContainer(containerV);
    viewerBubbles = viewers;
}

/* ===== TOAST ===== */
function showToast(msg, icon) {
    const toast = document.getElementById('liveToast');
    const messageEl = document.getElementById('toastMessage');
    const iconEl = document.getElementById('toastIcon');
    if (!toast) return;
    messageEl.textContent = msg;
    if (icon) iconEl.textContent = icon;
    else iconEl.textContent = 'check_circle';
    toast.classList.remove('hidden');
    toast.style.opacity = '1';
    toast.style.transform = 'translateX(-50%) translateY(0)';
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-50%) translateY(-20px)';
        setTimeout(() => toast.classList.add('hidden'), 300);
    }, 3000);
}

function showLiveToast(msg) {
    const existing = document.querySelector('.live-toast');
    if (existing) existing.remove();
    const div = document.createElement('div');
    div.className = 'live-toast fixed top-4 left-1/2 -translate-x-1/2 px-4 py-2 rounded-full bg-red-600/90 text-white text-sm font-medium z-[200] slide-up';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 3000);
}

function formatCount(num) {
    num = parseInt(num) || 0;
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
    return num.toString();
}

function escapeHtml(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

/* ===== INIT ===== */
document.addEventListener('DOMContentLoaded', function() {
    if (IS_HOST) {
        const urlParams = new URLSearchParams(window.location.search);
        const autoStart = urlParams.get('auto') === '1';
        if (autoStart) {
            document.getElementById('camBtnText').textContent = 'Starting...';
            setTimeout(() => toggleCamera(), 500);
        } else {
            document.getElementById('camBtnText').textContent = 'Start Camera';
        }
    } else {
        startViewerConnection();
    }

    fetch('/livestream/<?= $stream['id'] ?>/comments').then(r => r.json()).then(data => {
        if (data.comments && data.comments.length > 0) {
            data.comments.reverse().forEach(function(c) {
                const avatarHtml = c.avatar ? '<img src="' + c.avatar + '" class="w-5 h-5 rounded-full flex-shrink-0">' : '<div class="w-5 h-5 rounded-full bg-brand-600 flex-shrink-0 flex items-center justify-center"><span class="material-icons-round text-white text-[10px]">person</span></div>';
                const verifiedHtml = c.is_verified ? '<span class="material-icons-round text-brand-400 text-[10px]">verified</span>' : '';
                addComment('<div class="flex items-center gap-1.5"><div class="flex-shrink-0">' + avatarHtml + '</div><div class="bg-black/50 backdrop-blur-sm rounded-full px-3 py-1.5"><span class="text-brand-300 text-xs font-bold">' + escapeHtml(c.name) + '</span>' + verifiedHtml + '<span class="text-white/90 text-xs ml-1">' + escapeHtml(c.body) + '</span></div></div>');
            });
        }
    }).catch(() => {});

    /* Real-time viewer count via API */
    startViewerPolling();

    /* Initial viewer data fetch */
    fetchViewers(function(d) {
        updateViewerBubbles(d.viewers || []);
        updateViewerCounts(d.count || 0);
    });

    /* TikTok double-tap */
    document.getElementById('streamContainer').addEventListener('dblclick', handleDoubleTap);
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') document.getElementById('giftPanel').classList.add('hidden');
});
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
