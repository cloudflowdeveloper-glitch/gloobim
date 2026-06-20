<?php $hideTopNav = true; $activeTab = 'menu'; $title = 'Account Settings - Globiim'; ?>
<?php
$user = \Core\Auth::user();
$name = $user['name'] ?? 'User';
$username = $user['username'] ?? 'user';
$email = $user['email'] ?? '';
$bio = $user['bio'] ?? '';
$avatar = $user['avatar'] ?? null;
$phone = $user['phone'] ?? '';
$isVerified = !empty($user['is_verified']);
$initials = '';
if ($name) {
    $parts = explode(' ', $name);
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
}
$profileType = $user['profile_type'] ?? 'personal';
$profileTypes = [
    'personal' => ['label' => 'Personal', 'icon' => 'person', 'color' => 'purple', 'desc' => 'Normal User Account — connect, share, and shop on Globiim marketplace.'],
    'creator' => ['label' => 'Creator', 'icon' => 'auto_awesome', 'color' => 'blue', 'desc' => 'Content Creator Account — monetize content, access brand deals, analytics & creator tools.'],
    'business' => ['label' => 'Business', 'icon' => 'store', 'color' => 'amber', 'desc' => 'Business/Brand Account — sell products, manage store, track orders & run campaigns.'],
    'government' => ['label' => 'Government', 'icon' => 'account_balance', 'color' => 'emerald', 'desc' => 'Government/Institution Account — share official updates, services, notices & tenders.'],
];
?>
<?php ob_start(); ?>

<style>
    .settings-header {
        background: rgba(9, 12, 21, 0.92);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(20, 20, 28, 0.8);
    }
    .elite-glow {
        box-shadow: 0 0 30px rgba(147, 51, 234, 0.12), 0 0 60px rgba(147, 51, 234, 0.06);
    }
    .hexagon-badge {
        clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    }
    .progress-glow {
        box-shadow: 0 0 8px rgba(147, 51, 234, 0.5);
    }
    .stat-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-item:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    .setting-row {
        transition: background-color 0.15s ease, transform 0.15s ease;
    }
    .setting-row:active {
        transform: scale(0.98);
    }
    .gold-text {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 40%, #f59e0b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>

<!-- Custom Sticky Header -->
<header class="settings-header fixed top-0 left-0 right-0 z-50 h-14">
    <div class="max-w-lg mx-auto px-4 h-full flex items-center justify-between">
        <a href="/menu" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors flex-shrink-0">
            <span class="material-icons-round text-zinc-300 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-base font-bold text-white absolute left-1/2 -translate-x-1/2">Account Settings</h1>
        <a href="/notifications" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors relative flex-shrink-0">
            <span class="material-icons-round text-zinc-300 text-xl">notifications</span>
            <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center">3</span>
        </a>
    </div>
</header>

<div class="max-w-lg mx-auto px-4 pt-16 pb-20">

    <!-- Profile Section -->
    <section class="bg-[#1a1a2e] rounded-2xl border border-[#2a2a3e] p-4 mb-4">
        <div class="flex items-center gap-4">
            <div class="relative flex-shrink-0">
                <?php if ($avatar): ?>
                <img src="<?= htmlspecialchars($avatar) ?>" alt="<?= htmlspecialchars($name) ?>" class="w-20 h-20 rounded-full object-cover ring-2 ring-brand-500/30">
                <?php else: ?>
                <div class="w-20 h-20 rounded-full gradient-brand flex items-center justify-center text-white text-2xl font-bold ring-2 ring-brand-500/30">
                    <?= $initials ?: 'U' ?>
                </div>
                <?php endif; ?>
                <label class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-brand-600 flex items-center justify-center cursor-pointer hover:bg-brand-500 transition-colors border-2 border-[#1a1a2e] shadow-lg" title="Change avatar">
                    <span class="material-icons-round text-white text-sm">camera_alt</span>
                    <input type="file" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden" onchange="uploadAvatar(this)">
                </label>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                    <h2 class="text-white font-bold text-base truncate"><?= htmlspecialchars($name) ?></h2>
                    <?php if ($isVerified): ?>
                    <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 16 16" fill="none">
                        <circle cx="8" cy="8" r="8" fill="#3b82f6"/>
                        <path d="M4.5 8.5L6.5 10.5L11.5 5.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <?php endif; ?>
                </div>
                <p class="text-zinc-500 text-xs">@<?= htmlspecialchars($username) ?></p>
                <div class="mt-1.5">
                    <span class="inline-flex items-center text-[10px] px-2.5 py-0.5 rounded-full bg-brand-500/20 text-brand-400 font-medium">
                        Content Creator
                    </span>
                </div>
            </div>
            <a href="/profile" class="flex-shrink-0 px-3 py-1.5 rounded-lg bg-surface-200 text-white text-xs font-semibold hover:bg-surface-300 transition-colors">
                View Profile
            </a>
        </div>
    </section>

    <!-- Profile Type Selection -->
    <section class="mb-4">
        <h2 class="text-white font-bold text-base mb-3">Switch Account Type</h2>
        <p class="text-zinc-500 text-xs mb-4 -mt-1">Choose the profile type that best fits your needs. Each type unlocks unique features.</p>

        <div class="grid grid-cols-2 gap-3">
            <?php foreach ($profileTypes as $key => $pt): 
                $isActive = $profileType === $key;
                $colorMap = ['purple' => 'brand', 'blue' => 'blue', 'amber' => 'amber', 'emerald' => 'emerald'];
                $c = $colorMap[$pt['color']];
            ?>
            <button onclick="switchProfileType('<?= $key ?>')" 
                    id="profile-card-<?= $key ?>"
                    class="profile-type-card relative text-left p-4 rounded-2xl border-2 transition-all duration-300
                    <?= $isActive ? "border-{$c}-500 bg-{$c}-500/10 shadow-lg shadow-{$c}-500/10" : "border-[#2a2a3e] bg-[#14141c] hover:border-[#3a3a4e]" ?>"
                    style="min-height: 160px;">
                
                <?php if ($isActive): ?>
                <div class="absolute top-3 right-3 w-6 h-6 rounded-full bg-<?= $c ?>-500 flex items-center justify-center">
                    <span class="material-icons-round text-white text-sm">check</span>
                </div>
                <?php endif; ?>

                <!-- Icon -->
                <div class="w-12 h-12 rounded-xl bg-<?= $c ?>-500/20 flex items-center justify-center mb-3
                    <?= $isActive ? 'bg-' . $c . '-500' : '' ?>">
                    <span class="material-icons-round text-<?= $c ?>-400 <?= $isActive ? 'text-white' : '' ?> text-2xl">
                        <?= $pt['icon'] ?>
                    </span>
                </div>

                <!-- Label -->
                <h3 class="text-white font-bold text-sm mb-1"><?= $pt['label'] ?> Profile</h3>

                <!-- Description -->
                <p class="text-zinc-500 text-[11px] leading-tight"><?= $pt['desc'] ?></p>

                <?php if ($isActive): ?>
                <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 rounded-full bg-<?= $c ?>-500/20 text-<?= $c ?>-400 text-[10px] font-semibold">
                    <span class="material-icons-round text-xs">check_circle</span>
                    Active
                </span>
                <?php endif; ?>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Feature comparison -->
        <div class="mt-3 p-4 rounded-2xl bg-[#14141c] border border-[#2a2a3e]/50">
            <h4 class="text-white text-xs font-semibold mb-2.5">Features unlocked for <span id="selected-type-label"><?= $profileTypes[$profileType]['label'] ?></span> Profile</h4>
            <div class="grid grid-cols-2 gap-2" id="feature-list">
                <?php $features = [
                    'personal' => ['Shop Marketplace', 'Post Content', 'Follow Creators', 'Reviews & Ratings'],
                    'creator' => ['Creator Hub', 'Brand Deals', 'Analytics', 'Monetization'],
                    'business' => ['Online Store', 'Order Management', 'Coupons', 'Business Insights'],
                    'government' => ['Public Services', 'Official Notices', 'Tender Portal', 'Citizen FAQ'],
                ]; ?>
                <?php foreach ($features[$profileType] as $f): ?>
                <div class="flex items-center gap-2 text-xs text-zinc-400">
                    <span class="material-icons-round text-<?= $colorMap[$profileTypes[$profileType]['color']] ?>-400 text-sm">check</span>
                    <?= $f ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Elite Creator Card -->
    <section class="bg-[#14141c] rounded-2xl border border-brand-500/20 elite-glow p-4 mb-4 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-brand-600/5 rounded-full blur-2xl"></div>
        <div class="relative">
            <div class="flex items-center gap-3.5 mb-3">
                <!-- Hexagon Badge with Crown -->
                <div class="flex-shrink-0">
                    <div class="w-14 h-14 hexagon-badge gradient-brand flex items-center justify-center relative">
                        <svg class="w-6 h-6 text-amber-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5Z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-0.5">
                        <h3 class="gold-text font-bold text-lg font-display">Elite Creator</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-white font-bold text-sm">Level 42</span>
                        <span class="text-zinc-500 text-xs">·</span>
                        <span class="text-zinc-500 text-xs">1,215,450 XP</span>
                    </div>
                </div>
                <div class="text-right flex-shrink-0">
                    <span class="inline-flex items-center text-[10px] px-2.5 py-1 rounded-full bg-brand-500/15 text-brand-400 font-semibold">
                        <span class="material-icons-round text-xs mr-0.5">trending_up</span>
                        Top 1%
                    </span>
                </div>
            </div>
            <!-- Progress Bar -->
            <div class="mb-1.5">
                <div class="w-full h-2 rounded-full bg-surface-200 overflow-hidden">
                    <div class="h-full rounded-full gradient-brand progress-glow transition-all duration-500" style="width: 76%;"></div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-emerald-400 text-[10px] font-semibold">76% to Level 43</span>
                <span class="text-zinc-600 text-[10px]">922,550 / 1,215,450 XP</span>
            </div>
        </div>
    </section>

    <!-- Stats Row -->
    <section class="mb-4">
        <div class="flex gap-3 overflow-x-auto scrollbar-hide -mx-4 px-4 pb-1">

            <!-- Day Streak -->
            <div class="stat-item bg-[#14141c] rounded-xl p-3 min-w-[100px] flex-shrink-0 border border-[#2a2a3e]/50">
                <div class="flex items-center gap-1.5 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-orange-500/15 flex items-center justify-center">
                        <span class="material-icons-round text-orange-400 text-base">local_fire_department</span>
                    </div>
                </div>
                <p class="text-white font-bold text-xl leading-none mb-0.5">56</p>
                <p class="text-zinc-500 text-[10px] font-medium">Day Streak</p>
            </div>

            <!-- Creator Score -->
            <div class="stat-item bg-[#14141c] rounded-xl p-3 min-w-[100px] flex-shrink-0 border border-[#2a2a3e]/50">
                <div class="flex items-center gap-1.5 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-amber-500/15 flex items-center justify-center">
                        <span class="material-icons-round text-amber-400 text-base">star</span>
                    </div>
                </div>
                <p class="text-white font-bold text-xl leading-none mb-0.5">8.7M</p>
                <p class="text-zinc-500 text-[10px] font-medium">Creator Score</p>
            </div>

            <!-- Global Rank -->
            <div class="stat-item bg-[#14141c] rounded-xl p-3 min-w-[100px] flex-shrink-0 border border-[#2a2a3e]/50">
                <div class="flex items-center gap-1.5 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-500/15 flex items-center justify-center">
                        <span class="material-icons-round text-emerald-400 text-base">trending_up</span>
                    </div>
                </div>
                <p class="text-white font-bold text-xl leading-none mb-0.5">Top 0.8%</p>
                <p class="text-zinc-500 text-[10px] font-medium">Global Rank</p>
            </div>

            <!-- Badges -->
            <div class="stat-item bg-[#14141c] rounded-xl p-3 min-w-[100px] flex-shrink-0 border border-[#2a2a3e]/50">
                <div class="flex items-center gap-1.5 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-brand-500/15 flex items-center justify-center">
                        <span class="material-icons-round text-brand-400 text-base">emoji_events</span>
                    </div>
                </div>
                <p class="text-white font-bold text-xl leading-none mb-0.5">24</p>
                <p class="text-zinc-500 text-[10px] font-medium">Badges</p>
            </div>

        </div>
    </section>

    <!-- View Level Progress & Rewards -->
    <section class="mb-4">
        <a href="/creator/dashboard" class="flex items-center gap-3 p-4 rounded-2xl bg-[#14141c] border border-[#2a2a3e]/50 hover:border-brand-500/20 hover:bg-[#18182a] transition-all group">
            <div class="w-10 h-10 rounded-xl bg-brand-500/15 flex items-center justify-center flex-shrink-0">
                <span class="material-icons-round text-brand-400 text-xl">bar_chart</span>
            </div>
            <div class="flex-1">
                <h3 class="text-white font-semibold text-sm group-hover:text-brand-300 transition-colors">View Level Progress & Rewards</h3>
                <p class="text-zinc-500 text-xs">Track your growth and claim rewards</p>
            </div>
            <span class="material-icons-round text-zinc-500 group-hover:text-brand-400 transition-colors">chevron_right</span>
        </a>
    </section>

    <!-- Settings Menu -->
    <section>
        <div class="bg-[#14141c] rounded-2xl border border-[#2a2a3e]/50 overflow-hidden">

            <!-- Account Information -->
            <a href="/settings#account-info" onclick="scrollToSection(event, 'account-info')" class="setting-row flex items-center gap-3.5 p-4 hover:bg-surface-200/40 cursor-pointer border-b border-[#2a2a3e]/30">
                <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-brand-400 text-xl">person</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white text-sm font-semibold">Account Information</h3>
                    <p class="text-zinc-500 text-xs truncate">Update your personal info</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>

            <!-- Privacy & Security -->
            <a href="/settings/privacy" class="setting-row flex items-center gap-3.5 p-4 hover:bg-surface-200/40 cursor-pointer border-b border-[#2a2a3e]/30">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-blue-400 text-xl">shield</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white text-sm font-semibold">Privacy & Security</h3>
                    <p class="text-zinc-500 text-xs truncate">Manage privacy, password, 2FA</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>

            <!-- Verification -->
            <a href="/settings/verification" class="setting-row flex items-center gap-3.5 p-4 hover:bg-surface-200/40 cursor-pointer border-b border-[#2a2a3e]/30">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-emerald-400 text-xl">verified</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white text-sm font-semibold">Verification</h3>
                    <p class="text-zinc-500 text-xs truncate">Manage your verification badge</p>
                </div>
                <?php if ($isVerified): ?>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 text-[10px] font-semibold mr-1 flex-shrink-0">Verified</span>
                <?php endif; ?>
                <span class="material-icons-round text-zinc-600 text-lg flex-shrink-0">chevron_right</span>
            </a>

            <!-- Wallet -->
            <a href="/wallet" class="setting-row flex items-center gap-3.5 p-4 hover:bg-surface-200/40 cursor-pointer border-b border-[#2a2a3e]/30">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-amber-400 text-xl">account_balance_wallet</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white text-sm font-semibold">Wallet</h3>
                    <p class="text-zinc-500 text-xs truncate">Balance, earnings & transactions</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>

            <!-- Creator Tools -->
            <a href="/creator/dashboard" class="setting-row flex items-center gap-3.5 p-4 hover:bg-surface-200/40 cursor-pointer border-b border-[#2a2a3e]/30">
                <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-pink-400 text-xl">build</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white text-sm font-semibold">Creator Tools</h3>
                    <p class="text-zinc-500 text-xs truncate">Monetization, studio, analytics</p>
                </div>
                <span class="material-icons-round text-zinc-600 text-lg">chevron_right</span>
            </a>

            <!-- Notifications -->
            <a href="/notifications" class="setting-row flex items-center gap-3.5 p-4 hover:bg-surface-200/40 cursor-pointer">
                <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-brand-400 text-xl">notifications</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white text-sm font-semibold">Notifications</h3>
                    <p class="text-zinc-500 text-xs truncate">Manage your notification preferences</p>
                </div>
                <span class="min-w-[20px] h-5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center mr-1 flex-shrink-0">3</span>
                <span class="material-icons-round text-zinc-600 text-lg flex-shrink-0">chevron_right</span>
            </a>

        </div>
    </section>

</div>

<script>
function scrollToSection(event, sectionId) {
    // If we're already on /settings, scroll to the account info section
    if (window.location.pathname === '/settings') {
        event.preventDefault();
        // For now, could scroll or open a modal
        // Future: document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth' });
        return false;
    }
}

// Animate stats on page load
document.addEventListener('DOMContentLoaded', function() {
    const stats = document.querySelectorAll('.stat-item');
    stats.forEach(function(stat, index) {
        stat.style.opacity = '0';
        stat.style.transform = 'translateY(10px)';
        stat.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        setTimeout(function() {
            stat.style.opacity = '1';
            stat.style.transform = 'translateY(0)';
        }, 100 + (index * 80));
    });

    // Animate progress bar
    const progressBar = document.querySelector('.progress-glow');
    if (progressBar) {
        const targetWidth = progressBar.style.width;
        progressBar.style.width = '0%';
        setTimeout(function() {
            progressBar.style.width = targetWidth;
        }, 300);
    }

    // Animate setting rows
    const rows = document.querySelectorAll('.setting-row');
    rows.forEach(function(row, index) {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-10px)';
        row.style.transition = 'opacity 0.3s ease, transform 0.3s ease, background-color 0.15s ease';
        setTimeout(function() {
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, 400 + (index * 60));
    });
});

// Profile type switching
const profileLabels = { personal: 'Personal', creator: 'Creator', business: 'Business', government: 'Government' };
const profileColors = { personal: 'brand', creator: 'blue', business: 'amber', government: 'emerald' };
const profileFeatures = {
    personal: ['Shop Marketplace', 'Post Content', 'Follow Creators', 'Reviews & Ratings'],
    creator: ['Creator Hub', 'Brand Deals', 'Analytics', 'Monetization'],
    business: ['Online Store', 'Order Management', 'Coupons', 'Business Insights'],
    government: ['Public Services', 'Official Notices', 'Tender Portal', 'Citizen FAQ'],
};

function switchProfileType(type) {
    // Update all cards
    document.querySelectorAll('.profile-type-card').forEach(function(card) {
        var cardType = card.id.replace('profile-card-', '');
        var c = profileColors[cardType];

        if (cardType === type) {
            card.className = card.className.replace(/border-\[#2a2a3e\]|bg-\[#14141c\]|hover:border-\[#3a3a4e\]/g, '');
            card.className += ' border-' + c + '-500 bg-' + c + '-500/10 shadow-lg';
            // Add checkmark if not present
            if (!card.querySelector('.check-badge')) {
                var badge = document.createElement('div');
                badge.className = 'check-badge absolute top-3 right-3 w-6 h-6 rounded-full bg-' + c + '-500 flex items-center justify-center';
                badge.innerHTML = '<span class="material-icons-round text-white text-sm">check</span>';
                card.appendChild(badge);
            }
            // Update icon bg
            var icon = card.querySelector('.w-12.h-12');
            if (icon) {
                icon.className = icon.className.replace(/bg-\w+-\d+\/\d+/, 'bg-' + c + '-500');
                var iconSpan = icon.querySelector('.material-icons-round');
                if (iconSpan) iconSpan.className = iconSpan.className.replace(/text-\w+-\d+/, 'text-white');
            }
            // Add active pill
            if (!card.querySelector('.active-pill')) {
                var pill = document.createElement('span');
                pill.className = 'active-pill inline-flex items-center gap-1 mt-2 px-2 py-0.5 rounded-full bg-' + c + '-500/20 text-' + c + '-400 text-[10px] font-semibold';
                pill.innerHTML = '<span class="material-icons-round text-xs">check_circle</span> Active';
                card.appendChild(pill);
            }
        } else {
            card.className = card.className.replace(/border-\w+-\d+|\bbg-\w+-\d+\/\d+\b|shadow-lg/g, '');
            card.className += ' border-[#2a2a3e] bg-[#14141c] hover:border-[#3a3a4e]';
            // Remove check badge
            var badge = card.querySelector('.check-badge');
            if (badge) badge.remove();
            // Reset icon
            var icon = card.querySelector('.w-12.h-12');
            if (icon) {
                icon.className = icon.className.replace(/bg-\w+-\d+/, 'bg-' + c + '-500/20');
                var iconSpan = icon.querySelector('.material-icons-round');
                if (iconSpan) iconSpan.className = iconSpan.className.replace(/text-white/, 'text-' + c + '-400');
            }
            // Remove active pill
            var pill = card.querySelector('.active-pill');
            if (pill) pill.remove();
        }
    });

    // Update feature list
    document.getElementById('selected-type-label').textContent = profileLabels[type];
    var featureList = document.getElementById('feature-list');
    var currentColor = profileColors[type];
    var featuresHTML = profileFeatures[type].map(function(f) {
        return '<div class="flex items-center gap-2 text-xs text-zinc-400"><span class="material-icons-round text-' + currentColor + '-400 text-sm">check</span>' + f + '</div>';
    }).join('');
    featureList.innerHTML = featuresHTML;

    // Send update to server
    fetch('/profile/update-type', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ profile_type: type })
    }).then(function(r) { return r.json(); }).then(function(data) {
        console.log('Profile type updated:', data);
    });
}

function uploadAvatar(input) {
    if (!input.files || !input.files[0]) return;
    const fd = new FormData();
    fd.append('avatar', input.files[0]);
    fetch('/profile/upload-avatar', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.error) { alert(d.error); return; }
            location.reload();
        })
        .catch(() => alert('Upload failed'));
}
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
