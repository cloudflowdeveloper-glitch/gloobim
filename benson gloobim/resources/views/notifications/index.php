<?php $title = 'Notifications - DTTube'; ?>
<?php
$notifications = $data['notifications'] ?? [];
extract($data ?? []);
$unreadCount = 0;
$today = [];
$thisWeek = [];
$earlier = [];
$now = time();
foreach ($notifications as $n) {
    if (!$n['is_read']) $unreadCount++;
    $diff = $now - strtotime($n['created_at']);
    if ($diff < 86400) $today[] = $n;
    elseif ($diff < 604800) $thisWeek[] = $n;
    else $earlier[] = $n;
}
?>
<?php ob_start(); ?>
<style>
    .notif-card { transition: all 0.2s ease; }
    .notif-card:hover { background: rgba(20, 20, 28, 0.6); transform: translateX(2px); }
    .notif-card.unread { background: rgba(14, 3, 35, 0.25); border: 1px solid rgba(147, 51, 234, 0.15); }
    .notif-card.read { background: rgba(20, 20, 28, 0.3); border: 1px solid rgba(30, 30, 42, 0.5); }
    .type-badge-like { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .type-badge-follow { background: linear-gradient(135deg, #9333ea, #7e22ce); }
    .type-badge-comment { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .type-badge-tip { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .type-badge-system { background: linear-gradient(135deg, #10b981, #059669); }
    .type-badge-milestone { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .type-badge-default { background: linear-gradient(135deg, #71717a, #52525b); }
    .avatar-ring-like { box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.5); }
    .avatar-ring-follow { box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.5); }
    .avatar-ring-comment { box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5); }
    .avatar-ring-tip { box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.5); }
</style>

<div class="max-w-lg mx-auto px-3 py-4">
    <!-- Header -->
    <div class="flex items-center justify-between mb-1">
        <div class="flex items-center gap-2">
            <span class="material-icons-round text-brand-400 text-2xl">notifications</span>
            <h1 class="font-display text-xl font-bold text-white">Notifications</h1>
        </div>
        <button onclick="markAllRead()" class="text-brand-400 text-[11px] font-semibold hover:text-brand-300 transition-colors flex items-center gap-1">
            <span class="material-icons-round text-sm">done_all</span>
            Mark all read
        </button>
    </div>
    <p class="text-zinc-500 text-xs mb-4">Stay updated with your activity</p>

    <!-- Filter Chips -->
    <div class="flex gap-2 mb-5 overflow-x-auto scrollbar-hide pb-1">
        <button class="flex-shrink-0 px-4 py-1.5 rounded-full text-white text-[10px] font-bold" style="background: linear-gradient(135deg, #6800d6, #9333ea);">All</button>
        <button class="flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-[10px] font-medium border border-[#1e1e2a] hover:bg-[#1e1e2a] hover:text-zinc-300 transition-colors">Likes</button>
        <button class="flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-[10px] font-medium border border-[#1e1e2a] hover:bg-[#1e1e2a] hover:text-zinc-300 transition-colors">Comments</button>
        <button class="flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-[10px] font-medium border border-[#1e1e2a] hover:bg-[#1e1e2a] hover:text-zinc-300 transition-colors">Follows</button>
        <button class="flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-[10px] font-medium border border-[#1e1e2a] hover:bg-[#1e1e2a] hover:text-zinc-300 transition-colors">Tips</button>
        <button class="flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-[10px] font-medium border border-[#1e1e2a] hover:bg-[#1e1e2a] hover:text-zinc-300 transition-colors">System</button>
    </div>

    <?php if (!empty($notifications)): ?>

    <?php if (!empty($today)): ?>
    <!-- Today Section -->
    <div class="flex items-center gap-2 mb-3">
        <span class="w-1.5 h-1.5 rounded-full" style="background: linear-gradient(135deg, #9333ea, #c084fc);"></span>
        <span class="text-zinc-400 text-[10px] font-semibold uppercase tracking-wider">New</span>
        <?php if ($unreadCount > 0): ?>
        <span class="px-1.5 py-0.5 rounded-full text-white text-[9px] font-bold" style="background: linear-gradient(135deg, #9333ea, #6d28d9);"><?= $unreadCount ?></span>
        <?php endif; ?>
        <span class="flex-1 h-px bg-[#14141c]"></span>
    </div>
    <div class="space-y-2 mb-6">
        <?php foreach ($today as $n): ?>
        <?php
            $typeClass = 'default';
            $typeIcon = 'notifications';
            $avatarRing = '';
            if (($n['type'] ?? '') === 'like') { $typeClass = 'like'; $typeIcon = 'favorite'; $avatarRing = 'avatar-ring-like'; }
            elseif (($n['type'] ?? '') === 'follow') { $typeClass = 'follow'; $typeIcon = 'person_add'; $avatarRing = 'avatar-ring-follow'; }
            elseif (($n['type'] ?? '') === 'comment') { $typeClass = 'comment'; $typeIcon = 'chat_bubble'; $avatarRing = 'avatar-ring-comment'; }
            elseif (($n['type'] ?? '') === 'tip') { $typeClass = 'tip'; $typeIcon = 'monetization_on'; $avatarRing = 'avatar-ring-tip'; }
            elseif (($n['type'] ?? '') === 'verified' || ($n['type'] ?? '') === 'milestone') { $typeClass = 'milestone'; $typeIcon = 'emoji_events'; }
        ?>
        <div class="notif-card <?= $n['is_read'] ? 'read' : 'unread' ?> flex items-start gap-3 p-3 rounded-2xl cursor-pointer" onclick="openNotification(<?= $n['id'] ?>)">
            <!-- Avatar with type badge -->
            <div class="relative flex-shrink-0">
                <div class="w-11 h-11 rounded-full overflow-hidden <?= $avatarRing ?>">
                    <img src="<?= $n['actor_avatar'] ?? 'https://placehold.co/44x44/3f3f46/ffffff?text=?' ?>" alt="" class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full flex items-center justify-center text-white type-badge-<?= $typeClass ?>" style="box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                    <span class="material-icons-round" style="font-size: 10px;"><?= $typeIcon ?></span>
                </div>
                <?php if (!$n['is_read']): ?>
                <div class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full" style="background: linear-gradient(135deg, #9333ea, #c084fc); box-shadow: 0 0 6px rgba(147,51,234,0.5);"></div>
                <?php endif; ?>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <p class="text-[13px] leading-relaxed <?= $n['is_read'] ? 'text-zinc-400' : 'text-zinc-100' ?>"><?= $n['title'] ?></p>
                <?php if (!empty($n['body'])): ?>
                <p class="text-zinc-500 text-[11px] mt-0.5 line-clamp-1"><?= $n['body'] ?></p>
                <?php endif; ?>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-zinc-600 text-[10px]"><?= timeAgo($n['created_at']) ?></span>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center gap-1 flex-shrink-0 self-center">
                <?php if (($n['type'] ?? '') === 'follow'): ?>
                <button onclick="event.stopPropagation();followBack(<?= $n['actor_id'] ?? 0 ?>)" class="px-3 py-1 rounded-full text-white text-[9px] font-bold hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #6800d6, #9333ea);">Follow</button>
                <?php endif; ?>
                <button onclick="event.stopPropagation();moreNotifOptions(<?= $n['id'] ?>)" class="p-1 rounded-full hover:bg-[#1e1e2a] transition-colors">
                    <span class="material-icons-round text-zinc-600 text-base">more_vert</span>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($thisWeek)): ?>
    <!-- This Week Section -->
    <div class="flex items-center gap-2 mb-3">
        <span class="w-1.5 h-1.5 rounded-full bg-zinc-600"></span>
        <span class="text-zinc-500 text-[10px] font-semibold uppercase tracking-wider">This Week</span>
        <span class="flex-1 h-px bg-[#14141c]"></span>
    </div>
    <div class="space-y-2 mb-6">
        <?php foreach ($thisWeek as $n): ?>
        <?php
            $typeClass = 'default';
            $typeIcon = 'notifications';
            $avatarRing = '';
            if (($n['type'] ?? '') === 'like') { $typeClass = 'like'; $typeIcon = 'favorite'; $avatarRing = 'avatar-ring-like'; }
            elseif (($n['type'] ?? '') === 'follow') { $typeClass = 'follow'; $typeIcon = 'person_add'; $avatarRing = 'avatar-ring-follow'; }
            elseif (($n['type'] ?? '') === 'comment') { $typeClass = 'comment'; $typeIcon = 'chat_bubble'; $avatarRing = 'avatar-ring-comment'; }
            elseif (($n['type'] ?? '') === 'tip') { $typeClass = 'tip'; $typeIcon = 'monetization_on'; $avatarRing = 'avatar-ring-tip'; }
            elseif (($n['type'] ?? '') === 'verified' || ($n['type'] ?? '') === 'milestone') { $typeClass = 'milestone'; $typeIcon = 'emoji_events'; }
        ?>
        <div class="notif-card <?= $n['is_read'] ? 'read' : 'unread' ?> flex items-start gap-3 p-3 rounded-2xl cursor-pointer" onclick="openNotification(<?= $n['id'] ?>)">
            <div class="relative flex-shrink-0">
                <div class="w-11 h-11 rounded-full overflow-hidden <?= $avatarRing ?>">
                    <img src="<?= $n['actor_avatar'] ?? 'https://placehold.co/44x44/3f3f46/ffffff?text=?' ?>" alt="" class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full flex items-center justify-center text-white type-badge-<?= $typeClass ?>" style="box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                    <span class="material-icons-round" style="font-size: 10px;"><?= $typeIcon ?></span>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-zinc-300 text-[13px] leading-relaxed"><?= $n['title'] ?></p>
                <?php if (!empty($n['body'])): ?>
                <p class="text-zinc-500 text-[11px] mt-0.5 line-clamp-1"><?= $n['body'] ?></p>
                <?php endif; ?>
                <span class="text-zinc-600 text-[10px] mt-1 block"><?= timeAgo($n['created_at']) ?></span>
            </div>
            <?php if (($n['type'] ?? '') === 'follow'): ?>
            <button onclick="event.stopPropagation();followBack(<?= $n['actor_id'] ?? 0 ?>)" class="px-3 py-1 rounded-full text-zinc-400 text-[9px] font-bold bg-[#14141c] border border-[#1e1e2a] hover:bg-[#1e1e2a] transition-colors flex-shrink-0 self-center">Follow</button>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($earlier)): ?>
    <!-- Earlier Section -->
    <div class="flex items-center gap-2 mb-3">
        <span class="w-1.5 h-1.5 rounded-full bg-zinc-700"></span>
        <span class="text-zinc-600 text-[10px] font-semibold uppercase tracking-wider">Earlier</span>
        <span class="flex-1 h-px bg-[#14141c]"></span>
    </div>
    <div class="space-y-2 mb-6">
        <?php foreach ($earlier as $n): ?>
        <div class="notif-card read flex items-start gap-3 p-3 rounded-2xl cursor-pointer opacity-60 hover:opacity-90 transition-opacity" onclick="openNotification(<?= $n['id'] ?>)">
            <div class="relative flex-shrink-0">
                <div class="w-11 h-11 rounded-full overflow-hidden">
                    <img src="<?= $n['actor_avatar'] ?? 'https://placehold.co/44x44/3f3f46/ffffff?text=?' ?>" alt="" class="w-full h-full object-cover opacity-70">
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-5 h-5 rounded-full flex items-center justify-center text-white type-badge-default" style="box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                    <span class="material-icons-round" style="font-size: 10px;">notifications</span>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-zinc-500 text-[13px] leading-relaxed"><?= $n['title'] ?></p>
                <?php if (!empty($n['body'])): ?>
                <p class="text-zinc-600 text-[11px] mt-0.5 line-clamp-1"><?= $n['body'] ?></p>
                <?php endif; ?>
                <span class="text-zinc-700 text-[10px] mt-1 block"><?= timeAgo($n['created_at']) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <!-- Empty State -->
    <div class="text-center py-16">
        <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, rgba(147,51,234,0.15), rgba(109,40,217,0.08));">
            <span class="material-icons-round text-brand-400 text-4xl">notifications_none</span>
        </div>
        <h3 class="text-white font-semibold text-base mb-1">No notifications yet</h3>
        <p class="text-zinc-500 text-xs mb-4">When you get likes, comments, and follows, they'll show up here.</p>
        <a href="/" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full text-white text-sm font-semibold hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #6800d6, #9333ea, #c084fc);">
            <span class="material-icons-round text-lg">explore</span>
            Explore Content
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
function markAllRead() {
    document.querySelectorAll('.notif-card.unread').forEach(card => {
        card.classList.remove('unread');
        card.classList.add('read');
    });
    document.querySelectorAll('.notif-card .absolute.-top-0\\.5').forEach(dot => {
        dot.remove();
    });
    const badge = document.querySelector('[style*="background: linear-gradient(135deg, #9333ea, #6d28d9)"]');
    if (badge && badge.closest('.flex.items-center.gap-2')) badge.remove();
}

function openNotification(id) {
    const card = event.currentTarget;
    card.classList.remove('unread');
    card.classList.add('read');
    const dot = card.querySelector('.absolute.-top-0\\.5');
    if (dot) dot.remove();
}

function moreNotifOptions(id) {
    const actions = ['Mark as read', 'Mute', 'Delete'];
    const choice = prompt('Choose action:\n1. Mark as read\n2. Mute\n3. Delete\n\nEnter number:');
}

function followBack(userId, e) {
    if (e) e.stopPropagation();
    const btn = e ? e.currentTarget : event.currentTarget;
    const wasText = btn.textContent;
    btn.textContent = 'Following';
    btn.style.background = '#14141c';
    btn.style.border = '1px solid #1e1e2a';
    btn.style.color = '#a1a1aa';
    fetch('/follow/' + userId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json()).then(d => {
        if (d.error) { location.href = '/login'; btn.textContent = wasText; return; }
    }).catch(() => { btn.textContent = wasText; });
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
