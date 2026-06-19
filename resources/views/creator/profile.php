<?php
$hideTopNav = true;
$activeTab = 'home';
$title = 'Creator Dashboard - Globiim';

$profileUser = $data['profileUser'] ?? null;
$posts = $data['posts'] ?? [];
$videos = $data['videos'] ?? [];
$isOwnProfile = $data['isOwnProfile'] ?? false;

$name = $profileUser['name'] ?? 'User';
$username = $profileUser['username'] ?? 'user';
$avatar = $profileUser['avatar'] ?? 'https://placehold.co/160x160/6d28d9/ffffff?text=' . urlencode(substr($name, 0, 2));
$bio = $profileUser['bio'] ?? '';
$isVerified = !empty($profileUser['is_verified']);
$profileType = $profileUser['profile_type'] ?? 'personal';
$profileTypeBadges = [
    'personal' => ['label' => 'Personal', 'color' => 'brand', 'icon' => 'person'],
    'creator' => ['label' => 'Verified Creator', 'color' => 'blue', 'icon' => 'auto_awesome'],
    'business' => ['label' => 'Verified Business', 'color' => 'amber', 'icon' => 'store'],
    'government' => ['label' => 'Verified Government', 'color' => 'emerald', 'icon' => 'account_balance'],
];

$initials = '';
if ($name) {
    $parts = explode(' ', $name);
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
}
?>
<?php ob_start(); ?>

<style>
    /* Scrollbar hide for horizontal scroll areas */
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* Custom header - same glass as layout */
    .dash-header {
        background: rgba(9, 12, 21, 0.92);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(20, 20, 28, 0.8);
    }

    /* Card base */
    .card {
        background: #14141c;
        border: 1px solid rgba(42, 42, 62, 0.5);
        border-radius: 1rem;
        padding: 1rem;
    }

    /* XP Progress bar glow */
    .xp-bar {
        background: linear-gradient(90deg, #9333ea 0%, #6d28d9 50%, #4f46e5 100%);
        box-shadow: 0 0 8px rgba(147, 51, 234, 0.4);
    }

    /* Pulse animation for LIVE badge */
    @keyframes livePulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }
    .live-dot { animation: livePulse 1.5s ease-in-out infinite; }

    /* Fade-in animations for sections */
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-section {
        opacity: 0;
        animation: fadeSlideUp 0.5s ease-out forwards;
    }
    .fade-section:nth-child(1) { animation-delay: 0.05s; }
    .fade-section:nth-child(2) { animation-delay: 0.1s; }
    .fade-section:nth-child(3) { animation-delay: 0.15s; }
    .fade-section:nth-child(4) { animation-delay: 0.2s; }
    .fade-section:nth-child(5) { animation-delay: 0.25s; }
    .fade-section:nth-child(6) { animation-delay: 0.3s; }
    .fade-section:nth-child(7) { animation-delay: 0.35s; }
    .fade-section:nth-child(8) { animation-delay: 0.4s; }

    /* Metric card hover */
    .metric-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    /* Revenue bar animation */
    @keyframes barFill {
        from { width: 0; }
    }
    .rev-bar {
        animation: barFill 0.8s ease-out forwards;
    }

    /* Gauge rotation */
    @keyframes gaugeRotate {
        from { stroke-dasharray: 0 251; }
        to { stroke-dasharray: 218 251; }
    }
    .gauge-arc {
        animation: gaugeRotate 1.2s ease-out 0.4s forwards;
        stroke-dasharray: 0 251;
    }

    /* Sparkline animation */
    @keyframes drawLine {
        from { stroke-dashoffset: 300; }
        to { stroke-dashoffset: 0; }
    }
    .sparkline-anim {
        stroke-dasharray: 300;
        stroke-dashoffset: 300;
        animation: drawLine 1s ease-out 0.3s forwards;
    }
</style>

<!-- ═══════════════════════════════════════════════════════ -->
<!-- CUSTOM STICKY HEADER                                    -->
<!-- ═══════════════════════════════════════════════════════ -->
<header class="dash-header fixed top-0 left-0 right-0 z-50 h-14">
    <div class="max-w-lg mx-auto px-4 h-full flex items-center justify-between">
        <!-- Left: Globiim Logo -->
        <a href="/" class="flex items-center gap-2 flex-shrink-0">
            <img src="/logo.jpeg" alt="Globiim" class="h-8 w-auto rounded-lg object-contain">
            <span class="font-display font-bold text-lg tracking-tight"><span class="text-white">Glo</span><span class="gradient-text">biim</span></span>
        </a>

        <!-- Right: Search, Notifications, Avatar -->
        <div class="flex items-center gap-1">
            <button class="w-9 h-9 rounded-full hover:bg-surface-200 transition-colors flex items-center justify-center">
                <span class="material-icons-round text-zinc-300 text-[20px]">search</span>
            </button>
            <a href="/notifications" class="w-9 h-9 rounded-full hover:bg-surface-200 transition-colors flex items-center justify-center relative">
                <span class="material-icons-round text-zinc-300 text-[20px]">notifications</span>
                <span class="absolute top-1 right-1 min-w-[14px] h-3.5 px-1 rounded-full bg-red-500 text-white text-[8px] font-bold flex items-center justify-center">1</span>
            </a>
            <a href="/profile" class="ml-0.5">
                <div class="w-7 h-7 rounded-full overflow-hidden border-2 border-brand-600/50">
                    <?php if ($avatar): ?>
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Profile" class="w-full h-full object-cover">
                    <?php else: ?>
                    <div class="w-full h-full gradient-brand flex items-center justify-center text-white text-[10px] font-bold"><?= $initials ?: 'U' ?></div>
                    <?php endif; ?>
                </div>
            </a>
        </div>
    </div>
</header>

<!-- ═══════════════════════════════════════════════════════ -->
<!-- MAIN CONTENT                                            -->
<!-- ═══════════════════════════════════════════════════════ -->
<div class="px-4 pt-16 pb-20">

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- SECTION 1: CREATOR PROFILE CARD                       -->
    <!-- ═══════════════════════════════════════════════════ -->
    <section class="card mb-3 fade-section" style="background: #14141c; border-color: #2a2a3e;">
        <div class="flex items-start gap-4">
            <!-- Left: Avatar + Info -->
            <div class="flex-shrink-0">
                <?php if ($avatar): ?>
                <img src="<?= htmlspecialchars($avatar) ?>" alt="<?= htmlspecialchars($name) ?>" class="w-16 h-16 rounded-full object-cover <?= $isVerified ? 'ring-2 ring-brand-500/60' : 'rounded-full' ?>">
                <?php else: ?>
                <div class="w-16 h-16 rounded-full gradient-brand flex items-center justify-center text-white text-2xl font-bold <?= $isVerified ? 'ring-2 ring-brand-500/60' : '' ?>">
                    <?= $initials ?: 'U' ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Center: Name, Level, XP, Earnings -->
            <div class="flex-1 min-w-0">
                <!-- Name + Verified Badge -->
                <div class="flex items-center gap-1.5 mb-0.5">
                    <h2 class="text-white font-bold text-base truncate"><?= htmlspecialchars($name) ?></h2>
                    <?php if ($isVerified): ?>
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 16 16" fill="none">
                        <circle cx="8" cy="8" r="8" fill="#3b82f6"/>
                        <path d="M4.5 8.5L6.5 10.5L11.5 5.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php endif; ?>
                </div>

                <!-- Level & Role -->
                <p class="text-zinc-400 text-xs mb-1.5">Level 48 &bull; Creator</p>

                <!-- Profile Type Badge -->
                <?php $ptBadge = $profileTypeBadges[$profileType] ?? $profileTypeBadges['personal']; ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full 
                    bg-<?= $ptBadge['color'] ?>-500/20 text-<?= $ptBadge['color'] ?>-400 
                    text-[10px] font-bold mb-2">
                    <span class="material-icons-round text-xs"><?= $ptBadge['icon'] ?></span>
                    <?= $ptBadge['label'] ?>
                </span>

                <!-- XP Progress Bar -->
                <div class="mb-1">
                    <div class="w-full h-1.5 rounded-full bg-surface-200 overflow-hidden">
                        <div class="h-full rounded-full xp-bar" style="width: 82.3%;"></div>
                    </div>
                </div>
                <p class="text-zinc-500 text-[10px]">24,680 / 30,000 XP</p>
            </div>

            <!-- Right: Wallet Button -->
            <a href="/wallet" class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #9333ea 0%, #6d28d9 50%, #4f46e5 100%);">
                <span class="material-icons-round text-white text-lg">account_balance_wallet</span>
            </a>
        </div>

        <!-- Today's Earnings -->
        <div class="mt-4 pt-3 border-t border-[#2a2a3e]/50">
            <p class="text-zinc-400 text-xs mb-0.5">Today's Earnings</p>
            <div class="flex items-end gap-2">
                <span class="text-white text-xl font-bold leading-none">$156.80</span>
                <span class="flex items-center gap-0.5 text-emerald-400 text-[10px] font-semibold mb-0.5">
                    <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                    +18.6% vs yesterday
                </span>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- SECTION 2: KEY METRICS ROW                           -->
    <!-- ═══════════════════════════════════════════════════ -->
    <section class="mb-3 fade-section">
        <div class="flex gap-2 overflow-x-auto scrollbar-hide -mx-4 px-4 pb-1">

            <!-- Views -->
            <div class="metric-card bg-[#14141c] rounded-xl p-3 min-w-[110px] flex-shrink-0 border border-[#2a2a3e]/50">
                <div class="w-8 h-8 rounded-lg bg-teal-500/15 flex items-center justify-center mb-2">
                    <span class="material-icons-round text-teal-400 text-base">play_circle</span>
                </div>
                <p class="text-white font-bold text-lg leading-none mb-0.5">2.4M</p>
                <p class="text-zinc-500 text-[10px] font-medium mb-1">Views</p>
                <span class="flex items-center gap-0.5 text-emerald-400 text-[10px] font-semibold">
                    <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                    +28.5%
                </span>
            </div>

            <!-- Watchtime -->
            <div class="metric-card bg-[#14141c] rounded-xl p-3 min-w-[110px] flex-shrink-0 border border-[#2a2a3e]/50">
                <div class="w-8 h-8 rounded-lg bg-purple-500/15 flex items-center justify-center mb-2">
                    <span class="material-icons-round text-purple-400 text-base">schedule</span>
                </div>
                <p class="text-white font-bold text-lg leading-none mb-0.5">156.7K</p>
                <p class="text-zinc-500 text-[10px] font-medium mb-1">Watchtime</p>
                <span class="flex items-center gap-0.5 text-emerald-400 text-[10px] font-semibold">
                    <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                    +34.2%
                </span>
            </div>

            <!-- Followers -->
            <div class="metric-card bg-[#14141c] rounded-xl p-3 min-w-[110px] flex-shrink-0 border border-[#2a2a3e]/50">
                <div class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center mb-2">
                    <span class="material-icons-round text-blue-400 text-base">group</span>
                </div>
                <p class="text-white font-bold text-lg leading-none mb-0.5"><?= formatCount($followerCount ?? 0) ?></p>
                <p class="text-zinc-500 text-[10px] font-medium mb-1">Followers</p>
                <span class="flex items-center gap-0.5 text-emerald-400 text-[10px] font-semibold">
                    <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                    +18.9%
                </span>
            </div>

            <!-- Revenue -->
            <div class="metric-card bg-[#14141c] rounded-xl p-3 min-w-[110px] flex-shrink-0 border border-[#2a2a3e]/50">
                <div class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center mb-2">
                    <span class="material-icons-round text-amber-400 text-base">attach_money</span>
                </div>
                <p class="text-white font-bold text-lg leading-none mb-0.5">$18,540</p>
                <p class="text-zinc-500 text-[10px] font-medium mb-1">Revenue</p>
                <span class="flex items-center gap-0.5 text-emerald-400 text-[10px] font-semibold">
                    <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                    +26.7%
                </span>
            </div>

            <!-- Engagement Rate -->
            <div class="metric-card bg-[#14141c] rounded-xl p-3 min-w-[110px] flex-shrink-0 border border-[#2a2a3e]/50">
                <div class="w-8 h-8 rounded-lg bg-red-500/15 flex items-center justify-center mb-2">
                    <span class="material-icons-round text-red-400 text-base">favorite</span>
                </div>
                <p class="text-white font-bold text-lg leading-none mb-0.5">12.8%</p>
                <p class="text-zinc-500 text-[10px] font-medium mb-1">Eng. Rate</p>
                <span class="flex items-center gap-0.5 text-emerald-400 text-[10px] font-semibold">
                    <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                    +8.4%
                </span>
            </div>

            <!-- More Button -->
            <div class="flex-shrink-0 flex items-center pl-1">
                <button class="w-8 h-8 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
                    <span class="material-icons-round text-zinc-400 text-lg">more_horiz</span>
                </button>
            </div>

        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- SECTION 3: PERFORMANCE OVER TIME + VIRAL SCORE       -->
    <!-- ═══════════════════════════════════════════════════ -->
    <section class="mb-3 fade-section">
        <div class="grid grid-cols-5 gap-3">

            <!-- Performance Over Time (3 cols) -->
            <div class="col-span-3 bg-[#14141c] rounded-2xl border border-[#2a2a3e]/50 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white text-sm font-bold">Performance Over Time</h3>
                    <span class="bg-surface-200 text-white text-[10px] rounded-full px-2.5 py-1 font-medium">7D</span>
                </div>

                <!-- SVG Line Chart -->
                <svg viewBox="0 0 200 80" class="w-full" style="height: 100px;" preserveAspectRatio="none">
                    <!-- Grid lines (subtle) -->
                    <line x1="0" y1="20" x2="200" y2="20" stroke="#2a2a3e" stroke-width="0.5" stroke-dasharray="2 2"/>
                    <line x1="0" y1="40" x2="200" y2="40" stroke="#2a2a3e" stroke-width="0.5" stroke-dasharray="2 2"/>
                    <line x1="0" y1="60" x2="200" y2="60" stroke="#2a2a3e" stroke-width="0.5" stroke-dasharray="2 2"/>

                    <!-- Views line (Blue) -->
                    <polyline points="10,60 40,50 70,55 100,30 130,35 160,20 190,15" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sparkline-anim"/>

                    <!-- Watchtime line (Purple) -->
                    <polyline points="10,65 40,58 70,50 100,45 130,40 160,35 190,30" fill="none" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sparkline-anim" style="animation-delay: 0.45s;"/>

                    <!-- Revenue line (Green) -->
                    <polyline points="10,70 40,65 70,60 100,55 130,48 160,42 190,38" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sparkline-anim" style="animation-delay: 0.6s;"/>

                    <!-- Data points - Views -->
                    <circle cx="10" cy="60" r="2" fill="#3b82f6"/>
                    <circle cx="40" cy="50" r="2" fill="#3b82f6"/>
                    <circle cx="70" cy="55" r="2" fill="#3b82f6"/>
                    <circle cx="100" cy="30" r="2" fill="#3b82f6"/>
                    <circle cx="130" cy="35" r="2" fill="#3b82f6"/>
                    <circle cx="160" cy="20" r="2" fill="#3b82f6"/>
                    <circle cx="190" cy="15" r="2.5" fill="#3b82f6"/>

                    <!-- X-axis labels -->
                    <text x="10" y="78" fill="#52525b" font-size="8" text-anchor="middle">Mon</text>
                    <text x="40" y="78" fill="#52525b" font-size="8" text-anchor="middle">Tue</text>
                    <text x="70" y="78" fill="#52525b" font-size="8" text-anchor="middle">Wed</text>
                    <text x="100" y="78" fill="#52525b" font-size="8" text-anchor="middle">Thu</text>
                    <text x="130" y="78" fill="#52525b" font-size="8" text-anchor="middle">Fri</text>
                    <text x="160" y="78" fill="#52525b" font-size="8" text-anchor="middle">Sat</text>
                    <text x="190" y="78" fill="#52525b" font-size="8" text-anchor="middle">Sun</text>
                </svg>

                <!-- Legend -->
                <div class="flex items-center gap-4 mt-2">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                        <span class="text-zinc-500 text-[10px]">Views</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-purple-600"></div>
                        <span class="text-zinc-500 text-[10px]">Watchtime (hrs)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        <span class="text-zinc-500 text-[10px]">Revenue</span>
                    </div>
                </div>
            </div>

            <!-- Viral Score (2 cols) -->
            <div class="col-span-2 bg-[#14141c] rounded-2xl border border-[#2a2a3e]/50 p-4 flex flex-col items-center">
                <div class="flex items-center gap-1.5 self-start mb-3">
                    <h3 class="text-white text-sm font-bold">Viral Score</h3>
                    <span class="material-icons-round text-zinc-500 text-sm">info_outline</span>
                </div>

                <!-- Circular Gauge SVG -->
                <div class="relative mb-2" style="width: 100px; height: 100px;">
                    <svg viewBox="0 0 100 100" class="w-full h-full" style="transform: rotate(-90deg);">
                        <!-- Background circle -->
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#1e1e22" stroke-width="8"/>
                        <!-- Gauge arc (87%) - gradient from purple to green -->
                        <defs>
                            <linearGradient id="gaugeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" stop-color="#9333ea"/>
                                <stop offset="100%" stop-color="#10b981"/>
                            </linearGradient>
                        </defs>
                        <circle cx="50" cy="50" r="40" fill="none" stroke="url(#gaugeGrad)" stroke-width="8" stroke-linecap="round" class="gauge-arc"/>
                    </svg>
                    <!-- Score text centered -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <span class="text-white text-2xl font-bold leading-none">87</span>
                            <span class="text-zinc-500 text-xs">/100</span>
                        </div>
                    </div>
                </div>

                <!-- Badge -->
                <span class="bg-emerald-500/15 text-emerald-400 rounded-full px-2.5 py-0.5 text-[10px] font-semibold mb-1">Excellent</span>

                <!-- Weekly change -->
                <span class="flex items-center gap-0.5 text-emerald-400 text-xs font-medium">
                    <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                    +23 this week
                </span>
            </div>

        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- SECTION 4: TOP PERFORMING VIDEO + AI COACH            -->
    <!-- ═══════════════════════════════════════════════════ -->
    <section class="mb-3 fade-section">
        <div class="grid grid-cols-5 gap-3">

            <!-- Top Performing Video (3 cols) -->
            <div class="col-span-3 bg-[#14141c] rounded-2xl border border-[#2a2a3e]/50 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white text-sm font-bold">Top Performing Video</h3>
                    <a href="/videos" class="text-purple-400 text-[10px] font-semibold">View all</a>
                </div>

                <!-- Video Thumbnail -->
                <div class="relative rounded-xl overflow-hidden mb-3 bg-surface-200 aspect-video">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="material-icons-round text-white/60 text-5xl">play_circle</span>
                    </div>
                    <!-- Duration badge -->
                    <div class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded bg-black/80 text-white text-[10px] font-medium">0:58</div>
                </div>

                <!-- Video Title -->
                <p class="text-white text-sm font-bold mb-2">Never Give Up &ndash; Motivational Clip</p>

                <!-- Metric Badges -->
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 bg-teal-500/15 text-teal-400 rounded-full px-2.5 py-1 text-[10px] font-semibold">
                        <span class="material-icons-round text-xs">play_circle</span>
                        1.2M
                    </span>
                    <span class="flex items-center gap-1 bg-red-500/15 text-red-400 rounded-full px-2.5 py-1 text-[10px] font-semibold">
                        <span class="material-icons-round text-xs">favorite</span>
                        98.7K
                    </span>
                    <span class="flex items-center gap-1 bg-amber-500/15 text-amber-400 rounded-full px-2.5 py-1 text-[10px] font-semibold">
                        <span class="material-icons-round text-xs">attach_money</span>
                        $3,456
                    </span>
                </div>
            </div>

            <!-- AI Coach (2 cols) -->
            <div class="col-span-2 bg-[#14141c] rounded-2xl border border-[#2a2a3e]/50 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white text-sm font-bold">AI Coach</h3>
                    <span class="bg-brand-500/20 text-brand-400 text-[9px] rounded-full px-2 py-0.5 font-bold">NEW</span>
                </div>

                <!-- Robot avatar -->
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-9 h-9 rounded-full bg-blue-500/15 flex items-center justify-center flex-shrink-0">
                        <span class="material-icons-round text-blue-400 text-xl">smart_toy</span>
                    </div>
                    <p class="text-zinc-400 text-xs leading-snug">You're doing great! Here's what you should focus on.</p>
                </div>

                <!-- Recommendations -->
                <div class="space-y-2 mb-3">
                    <div class="flex items-start gap-2">
                        <svg width="14" height="14" viewBox="0 0 14 14" class="flex-shrink-0 mt-0.5">
                            <circle cx="7" cy="7" r="7" fill="rgba(16,185,129,0.15)"/>
                            <path d="M4 7.5L6.5 10L10 4.5" stroke="#10b981" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <p class="text-white text-[11px] leading-snug">Motivational clips performing 42% better</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg width="14" height="14" viewBox="0 0 14 14" class="flex-shrink-0 mt-0.5">
                            <circle cx="7" cy="7" r="7" fill="rgba(245,158,11,0.15)"/>
                            <path d="M7 4.5V7.5M7 10H7.01" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <p class="text-white text-[11px] leading-snug">Improve your hook in the first 3 seconds</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <svg width="14" height="14" viewBox="0 0 14 14" class="flex-shrink-0 mt-0.5">
                            <circle cx="7" cy="7" r="7" fill="rgba(59,130,246,0.15)"/>
                            <path d="M7 4.5V7.5M7 10H7.01" stroke="#3b82f6" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <p class="text-white text-[11px] leading-snug">Best time to post today is 7PM&ndash;9PM</p>
                    </div>
                </div>

                <!-- CTA Button -->
                <a href="/creator/analytics" class="block w-full text-center py-2 rounded-lg text-white text-xs font-semibold" style="background: linear-gradient(135deg, #9333ea 0%, #6d28d9 50%, #4f46e5 100%);">
                    View All Recommendations
                </a>
            </div>

        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- SECTION 5: AUDIENCE OVERVIEW                          -->
    <!-- ═══════════════════════════════════════════════════ -->
    <section class="mb-3 fade-section">
        <div class="bg-[#14141c] rounded-2xl border border-[#2a2a3e]/50 p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-white text-sm font-bold">Audience Overview</h3>
                <a href="/creator/analytics" class="text-purple-400 text-[10px] font-semibold">View all</a>
            </div>

            <!-- Top metrics -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <p class="text-zinc-500 text-[10px] mb-0.5">Total Followers</p>
                    <div class="flex items-end gap-1.5">
                        <span class="text-white font-bold text-base leading-none"><?= formatCount($followerCount ?? 0) ?></span>
                        <span class="flex items-center gap-0.5 text-emerald-400 text-[10px] font-semibold">
                            <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                            +12.6%
                        </span>
                    </div>
                </div>
                <div>
                    <p class="text-zinc-500 text-[10px] mb-0.5">Active Followers</p>
                    <div class="flex items-end gap-1.5">
                        <span class="text-white font-bold text-base leading-none">89.7K</span>
                        <span class="flex items-center gap-0.5 text-emerald-400 text-[10px] font-semibold">
                            <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                            +18.4%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Donut + Country -->
            <div class="flex items-center gap-5">
                <!-- Donut Chart -->
                <div class="relative flex-shrink-0" style="width: 90px; height: 90px;">
                    <svg viewBox="0 0 100 100" class="w-full h-full" style="transform: rotate(-90deg);">
                        <!-- Purple segment (60%) -->
                        <circle cx="50" cy="50" r="38" fill="none" stroke="#9333ea" stroke-width="12" stroke-dasharray="377 628" stroke-linecap="round"/>
                        <!-- Green segment (25%) -->
                        <circle cx="50" cy="50" r="38" fill="none" stroke="#10b981" stroke-width="12" stroke-dasharray="157 628" stroke-dashoffset="-377"/>
                        <!-- Red segment (15%) -->
                        <circle cx="50" cy="50" r="38" fill="none" stroke="#ef4444" stroke-width="12" stroke-dasharray="94 628" stroke-dashoffset="-534"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-white text-sm font-bold">256K</span>
                    </div>
                </div>

                <!-- Top Country & Legend -->
                <div class="flex-1 space-y-2.5">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-zinc-400 text-[10px]">Top Country</span>
                            <span class="text-white text-[10px] font-semibold">Kenya</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-surface-200 overflow-hidden">
                            <div class="h-full rounded-full xp-bar rev-bar" style="width: 28.6%;"></div>
                        </div>
                        <p class="text-zinc-500 text-[9px] mt-0.5">28.6%</p>
                    </div>
                    <!-- Legend -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-purple-600"></div>
                            <span class="text-zinc-500 text-[9px]">Kenya 60%</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                            <span class="text-zinc-500 text-[9px]">Nigeria 25%</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <span class="text-zinc-500 text-[9px]">Other 15%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- SECTION 5b: REVENUE                                   -->
    <!-- ═══════════════════════════════════════════════════ -->
    <section class="mb-3 fade-section">
        <div class="bg-[#14141c] rounded-2xl border border-[#2a2a3e]/50 p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-white text-sm font-bold">Revenue</h3>
                <a href="/wallet" class="text-purple-400 text-[10px] font-semibold">View all</a>
            </div>

            <!-- Revenue amount -->
            <div class="flex items-end gap-2 mb-2">
                <span class="text-white text-xl font-bold leading-none">$12,540.60</span>
                <span class="flex items-center gap-0.5 text-emerald-400 text-[10px] font-semibold mb-0.5">
                    <svg width="8" height="8" viewBox="0 0 8 8"><path d="M4 1L7 5H1L4 1Z" fill="#34d399"/></svg>
                    +28.6%
                </span>
            </div>

            <!-- Sparkline -->
            <svg viewBox="0 0 200 40" class="w-full mb-4" style="height: 40px;" preserveAspectRatio="none">
                <polyline points="0,35 30,28 60,30 90,18 120,22 150,10 180,12 200,5" fill="none" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sparkline-anim"/>
                <polyline points="0,35 30,28 60,30 90,18 120,22 150,10 180,12 200,5 200,40 0,40" fill="url(#sparkGrad)" stroke="none" opacity="0.2"/>
                <defs>
                    <linearGradient id="sparkGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#9333ea"/>
                        <stop offset="100%" stop-color="#9333ea" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>

            <!-- Revenue Breakdown -->
            <div class="space-y-2.5">
                <!-- Ads -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-purple-600"></div>
                            <span class="text-zinc-300 text-xs">Ads</span>
                        </div>
                        <span class="text-white text-xs font-semibold">57.8%</span>
                    </div>
                    <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                        <div class="h-full rounded-full bg-purple-600 rev-bar" style="width: 57.8%;"></div>
                    </div>
                </div>
                <!-- Gifts -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <span class="text-zinc-300 text-xs">Gifts</span>
                        </div>
                        <span class="text-white text-xs font-semibold">28.0%</span>
                    </div>
                    <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                        <div class="h-full rounded-full bg-red-500 rev-bar" style="width: 28%; animation-delay: 0.1s;"></div>
                    </div>
                </div>
                <!-- Sponsors -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            <span class="text-zinc-300 text-xs">Sponsors</span>
                        </div>
                        <span class="text-white text-xs font-semibold">15.8%</span>
                    </div>
                    <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                        <div class="h-full rounded-full bg-blue-500 rev-bar" style="width: 15.8%; animation-delay: 0.2s;"></div>
                    </div>
                </div>
                <!-- Other -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-zinc-500"></div>
                            <span class="text-zinc-300 text-xs">Other</span>
                        </div>
                        <span class="text-white text-xs font-semibold">13.4%</span>
                    </div>
                    <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                        <div class="h-full rounded-full bg-zinc-500 rev-bar" style="width: 13.4%; animation-delay: 0.3s;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- SECTION 5c: LIVE NOW                                   -->
    <!-- ═══════════════════════════════════════════════════ -->
    <section class="mb-3 fade-section">
        <div class="bg-[#14141c] rounded-2xl border border-red-500/20 p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <h3 class="text-white text-sm font-bold">Live Now</h3>
                    <!-- LIVE Badge -->
                    <span class="flex items-center gap-1 bg-red-500/15 text-red-400 rounded-full px-2 py-0.5 text-[10px] font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 live-dot"></span>
                        LIVE
                    </span>
                </div>
            </div>

            <!-- 2x2 Metrics Grid -->
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="bg-surface-200/50 rounded-xl p-2.5">
                    <p class="text-zinc-500 text-[10px] mb-0.5">Live Viewers</p>
                    <p class="text-white font-bold text-base leading-none">3,254</p>
                </div>
                <div class="bg-surface-200/50 rounded-xl p-2.5">
                    <p class="text-zinc-500 text-[10px] mb-0.5">Peak Viewers</p>
                    <p class="text-white font-bold text-base leading-none">8,921</p>
                </div>
                <div class="bg-surface-200/50 rounded-xl p-2.5">
                    <p class="text-zinc-500 text-[10px] mb-0.5">Watchtime</p>
                    <p class="text-white font-bold text-base leading-none">1,256 hrs</p>
                </div>
                <div class="bg-surface-200/50 rounded-xl p-2.5">
                    <p class="text-zinc-500 text-[10px] mb-0.5">Gifts</p>
                    <p class="text-white font-bold text-base leading-none">$456.80</p>
                </div>
            </div>

            <!-- Viewer Trend -->
            <svg viewBox="0 0 200 30" class="w-full mb-3" style="height: 30px;" preserveAspectRatio="none">
                <polyline points="0,25 25,20 50,22 75,15 100,18 125,10 150,12 175,8 200,5" fill="none" stroke="#9333ea" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="sparkline-anim"/>
            </svg>

            <!-- Go to Live Button -->
            <a href="/livestream" class="block w-full text-center py-2.5 rounded-lg text-white text-xs font-semibold" style="background: linear-gradient(135deg, #9333ea 0%, #6d28d9 50%, #4f46e5 100%);">
                Go to Live
            </a>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- SECTION 6: AUDIENCE RETENTION + TRAFFIC SOURCES       -->
    <!-- ═══════════════════════════════════════════════════ -->
    <section class="mb-3 fade-section">
        <div class="grid grid-cols-5 gap-3">

            <!-- Audience Retention (3 cols) -->
            <div class="col-span-3 bg-[#14141c] rounded-2xl border border-[#2a2a3e]/50 p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-1.5">
                        <h3 class="text-white text-sm font-bold">Audience Retention</h3>
                        <span class="material-icons-round text-zinc-500 text-sm">info_outline</span>
                    </div>
                    <a href="/creator/analytics" class="text-purple-400 text-[10px] font-semibold">View all</a>
                </div>

                <!-- Stats Row -->
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="bg-surface-200/50 rounded-lg p-2">
                        <p class="text-zinc-500 text-[9px]">Avg View Duration</p>
                        <p class="text-white text-xs font-bold">0:37 <span class="text-zinc-500 font-normal">(42%)</span></p>
                    </div>
                    <div class="bg-surface-200/50 rounded-lg p-2">
                        <p class="text-zinc-500 text-[9px]">Completion Rate</p>
                        <p class="text-white text-xs font-bold">38.6%</p>
                    </div>
                    <div class="bg-surface-200/50 rounded-lg p-2">
                        <p class="text-zinc-500 text-[9px]">Replays</p>
                        <p class="text-white text-xs font-bold">2.8M</p>
                    </div>
                    <div class="bg-surface-200/50 rounded-lg p-2">
                        <p class="text-zinc-500 text-[9px]">Retention Score</p>
                        <p class="text-white text-xs font-bold">76/100</p>
                    </div>
                </div>

                <!-- Retention Graph SVG -->
                <svg viewBox="0 0 200 70" class="w-full" style="height: 80px;" preserveAspectRatio="none">
                    <!-- Background fill area -->
                    <defs>
                        <linearGradient id="retentionGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#9333ea" stop-opacity="0.2"/>
                            <stop offset="100%" stop-color="#9333ea" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <polyline points="5,8 60,8 70,50 100,45 120,20 170,15 195,25 195,68 5,68" fill="url(#retentionGrad)" stroke="none"/>
                    <!-- Retention curve -->
                    <polyline points="5,8 60,8 70,50 100,45 120,20 170,15 195,25" fill="none" stroke="#9333ea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sparkline-anim"/>

                    <!-- Annotations -->
                    <!-- Strong Hook (green) -->
                    <circle cx="30" cy="8" r="3.5" fill="#10b981" opacity="0.9"/>
                    <line x1="30" y1="4" x2="30" y2="0" stroke="#10b981" stroke-width="0.8"/>
                    <text x="30" y="0" fill="#10b981" font-size="6" text-anchor="middle" font-weight="600">Strong Hook</text>

                    <!-- Drop-off (red) -->
                    <circle cx="70" cy="50" r="3.5" fill="#ef4444" opacity="0.9"/>
                    <line x1="70" y1="54" x2="70" y2="60" stroke="#ef4444" stroke-width="0.8"/>
                    <text x="70" y="66" fill="#ef4444" font-size="6" text-anchor="middle" font-weight="600">Drop at 0:17</text>

                    <!-- Replay Moment (blue) -->
                    <circle cx="120" cy="20" r="3.5" fill="#3b82f6" opacity="0.9"/>
                    <line x1="120" y1="16" x2="120" y2="10" stroke="#3b82f6" stroke-width="0.8"/>
                    <text x="120" y="8" fill="#3b82f6" font-size="6" text-anchor="middle" font-weight="600">Replay</text>

                    <!-- Spike Moment (orange) -->
                    <circle cx="170" cy="15" r="3.5" fill="#f59e0b" opacity="0.9"/>
                    <line x1="170" y1="11" x2="170" y2="5" stroke="#f59e0b" stroke-width="0.8"/>
                    <text x="170" y="4" fill="#f59e0b" font-size="6" text-anchor="middle" font-weight="600">Spike</text>
                </svg>
            </div>

            <!-- Traffic Sources (2 cols) -->
            <div class="col-span-2 bg-[#14141c] rounded-2xl border border-[#2a2a3e]/50 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-white text-sm font-bold">Traffic Sources</h3>
                    <a href="/creator/analytics" class="text-purple-400 text-[10px] font-semibold">View all</a>
                </div>

                <div class="space-y-3">
                    <!-- Reels Feed -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-purple-600"></div>
                                <span class="text-zinc-300 text-[11px]">Reels Feed</span>
                            </div>
                            <span class="text-white text-[11px] font-semibold">48.2%</span>
                        </div>
                        <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                            <div class="h-full rounded-full bg-purple-600 rev-bar" style="width: 48.2%;"></div>
                        </div>
                    </div>

                    <!-- Shares -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                <span class="text-zinc-300 text-[11px]">Shares</span>
                            </div>
                            <span class="text-white text-[11px] font-semibold">24.8%</span>
                        </div>
                        <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                            <div class="h-full rounded-full bg-red-500 rev-bar" style="width: 24.8%; animation-delay: 0.1s;"></div>
                        </div>
                    </div>

                    <!-- Following Feed -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-zinc-300 text-[11px]">Following Feed</span>
                            </div>
                            <span class="text-white text-[11px] font-semibold">12.8%</span>
                        </div>
                        <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                            <div class="h-full rounded-full bg-emerald-500 rev-bar" style="width: 12.8%; animation-delay: 0.15s;"></div>
                        </div>
                    </div>

                    <!-- Search -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                                <span class="text-zinc-300 text-[11px]">Search</span>
                            </div>
                            <span class="text-white text-[11px] font-semibold">8.4%</span>
                        </div>
                        <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                            <div class="h-full rounded-full bg-orange-500 rev-bar" style="width: 8.4%; animation-delay: 0.2s;"></div>
                        </div>
                    </div>

                    <!-- External -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <span class="text-zinc-300 text-[11px]">External</span>
                            </div>
                            <span class="text-white text-[11px] font-semibold">3.8%</span>
                        </div>
                        <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                            <div class="h-full rounded-full bg-blue-500 rev-bar" style="width: 3.8%; animation-delay: 0.25s;"></div>
                        </div>
                    </div>

                    <!-- Other -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-2 h-2 rounded-full bg-zinc-500"></div>
                                <span class="text-zinc-300 text-[11px]">Other</span>
                            </div>
                            <span class="text-white text-[11px] font-semibold">2.0%</span>
                        </div>
                        <div class="w-full h-1 rounded-full bg-surface-200 overflow-hidden">
                            <div class="h-full rounded-full bg-zinc-500 rev-bar" style="width: 2%; animation-delay: 0.3s;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>

<!-- ═══════════════════════════════════════════════════════ -->
<!-- SCRIPTS                                                  -->
<!-- ═══════════════════════════════════════════════════════ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for fade-in animations
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-section').forEach(function(el) {
        observer.observe(el);
    });

    // Animate metric cards on scroll
    var metricObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                var cards = entry.target.querySelectorAll('.metric-card');
                cards.forEach(function(card, index) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(10px)';
                    card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    setTimeout(function() {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 80);
                });
                metricObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    var metricsSection = document.querySelector('.scrollbar-hide');
    if (metricsSection) {
        metricObserver.observe(metricsSection);
    }
});
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
