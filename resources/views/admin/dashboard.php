<?php $title = 'Admin Dashboard'; ?>
<?php $data = $data ?? []; ?>
<?php extract($data); ?>
<?php ob_start(); ?>
<style>
    .dash-page { max-width: 42rem; margin: 0 auto; padding: 0 16px 100px; }
    .dash-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 20px; }
    .dash-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #a1a1aa; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; text-decoration: none; }
    .dash-back:hover { background: rgba(255,255,255,0.08); }
    .dash-title { font-size: 20px; font-weight: 700; color: white; flex: 1; }

    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 24px; }
    @media (min-width: 640px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
    .stat-card { background: #14141c; border: 1px solid #1e1e2a; border-radius: 14px; padding: 14px; display: flex; align-items: flex-start; gap: 10px; transition: border-color 0.2s; }
    .stat-card:hover { border-color: rgba(255,255,255,0.1); }
    .stat-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .stat-icon span { font-size: 20px; }
    .stat-icon.purple { background: rgba(131,74,229,0.15); color: #834ae5; }
    .stat-icon.green { background: rgba(34,197,94,0.15); color: #22c55e; }
    .stat-icon.amber { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .stat-icon.pink { background: rgba(236,72,153,0.15); color: #ec4899; }
    .stat-icon.cyan { background: rgba(6,182,212,0.15); color: #06b6d4; }
    .stat-icon.red { background: rgba(239,68,68,0.15); color: #ef4444; }
    .stat-value { font-size: 18px; font-weight: 800; color: white; line-height: 1.2; }
    .stat-label { font-size: 11px; color: #71717a; font-weight: 500; margin-top: 2px; }

    /* Pulse for pending/live */
    .stat-pulse { position: relative; }
    .stat-pulse.active::before { content: ''; position: absolute; top: -1px; right: -1px; width: 10px; height: 10px; background: #ef4444; border-radius: 50%; animation: dash-pulse 1.5s ease-in-out infinite; }
    .stat-pulse.active::after { content: ''; position: absolute; top: -1px; right: -1px; width: 10px; height: 10px; background: #ef4444; border-radius: 50%; animation: dash-pulse-ring 1.5s ease-in-out infinite; }
    @keyframes dash-pulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.7; transform: scale(1.2); } }
    @keyframes dash-pulse-ring { 0% { transform: scale(1); opacity: 0.6; } 100% { transform: scale(2.2); opacity: 0; } }

    /* Quick Actions */
    .dash-section-title { font-size: 15px; font-weight: 700; color: white; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
    .dash-section-title span.material-icons-round { font-size: 18px; color: #834ae5; }
    .actions-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 28px; }
    .action-btn { background: #14141c; border: 1px solid #1e1e2a; border-radius: 12px; padding: 14px 8px; display: flex; flex-direction: column; align-items: center; gap: 8px; cursor: pointer; text-decoration: none; transition: all 0.2s; color: white; }
    .action-btn:hover { border-color: rgba(131,74,229,0.4); background: rgba(131,74,229,0.05); }
    .action-btn .action-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .action-btn .action-icon span { font-size: 20px; }
    .action-btn .action-label { font-size: 11px; font-weight: 600; color: #a1a1aa; text-align: center; line-height: 1.3; }

    /* Recent Users */
    .users-scroll { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 8px; margin-bottom: 28px; -webkit-overflow-scrolling: touch; }
    .users-scroll::-webkit-scrollbar { display: none; }
    .users-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    .user-card { min-width: 140px; max-width: 140px; background: #14141c; border: 1px solid #1e1e2a; border-radius: 14px; padding: 14px; flex-shrink: 0; text-align: center; }
    .user-card img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; margin: 0 auto 8px; border: 2px solid #1e1e2a; }
    .user-card .user-name { font-size: 13px; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .user-card .user-username { font-size: 11px; color: #71717a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .user-card .user-role { display: inline-block; font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 6px; margin-top: 6px; }
    .user-card .user-role.creator { background: rgba(131,74,229,0.15); color: #834ae5; }
    .user-card .user-role.user { background: rgba(161,161,170,0.15); color: #a1a1aa; }
    .user-card .user-time { font-size: 10px; color: #52525b; margin-top: 4px; }
    .verified-badge { display: inline-flex; align-items: center; justify-content: center; width: 14px; height: 14px; background: #834ae5; border-radius: 50%; margin-left: 2px; vertical-align: middle; }
    .verified-badge span { font-size: 9px; color: white; }

    /* Recent Reports */
    .report-item { background: #14141c; border: 1px solid #1e1e2a; border-radius: 12px; padding: 12px 14px; margin-bottom: 8px; display: flex; align-items: center; gap: 12px; }
    .report-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .report-info { flex: 1; min-width: 0; }
    .report-reason { font-size: 13px; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .report-meta { font-size: 11px; color: #71717a; margin-top: 2px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .report-status { font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.3px; flex-shrink: 0; }
    .report-status.pending { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .report-status.resolved { background: rgba(34,197,94,0.15); color: #22c55e; }
    .report-status.dismissed { background: rgba(113,113,122,0.15); color: #71717a; }
    .report-status.reviewing { background: rgba(131,74,229,0.15); color: #834ae5; }

    /* Recent Content */
    .content-item { background: #14141c; border: 1px solid #1e1e2a; border-radius: 12px; padding: 12px 14px; margin-bottom: 8px; display: flex; align-items: center; gap: 12px; }
    .content-type-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .content-type-icon.post { background: rgba(236,72,153,0.15); color: #ec4899; }
    .content-type-icon.reel { background: rgba(6,182,212,0.15); color: #06b6d4; }
    .content-type-icon.video { background: rgba(34,197,94,0.15); color: #22c55e; }
    .content-info { flex: 1; min-width: 0; }
    .content-title { font-size: 13px; font-weight: 600; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .content-meta { font-size: 11px; color: #71717a; margin-top: 2px; display: flex; align-items: center; gap: 6px; }
    .content-status { font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.3px; flex-shrink: 0; }
    .content-status.published { background: rgba(34,197,94,0.15); color: #22c55e; }
    .content-status.pending { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .content-status.flagged { background: rgba(239,68,68,0.15); color: #ef4444; }
    .content-status.draft { background: rgba(113,113,122,0.15); color: #71717a; }

    .empty-state { text-align: center; padding: 24px; color: #52525b; font-size: 13px; }
    .empty-state span { display: block; font-size: 28px; margin-bottom: 6px; }

    .toast { position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; animation: dash-toast-in 0.3s ease; }
    @keyframes dash-toast-in { from { opacity: 0; transform: translate(-50%, 10px); } to { opacity: 1; transform: translate(-50%, 0); } }
</style>

<div class="dash-page">
    <!-- Header -->
    <div class="dash-header">
        <a href="/menu" class="dash-back"><span class="material-icons-round">arrow_back</span></a>
        <span class="dash-title">Admin Dashboard</span>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <?php
        $statCards = [
            ['key' => 'total_users',      'label' => 'Total Users',        'icon' => 'group',          'color' => 'purple'],
            ['key' => 'active_users',     'label' => 'Active Users',       'icon' => 'how_to_reg',     'color' => 'green'],
            ['key' => 'total_creators',   'label' => 'Creators',           'icon' => 'brush',          'color' => 'amber'],
            ['key' => 'total_posts',      'label' => 'Posts',              'icon' => 'article',        'color' => 'pink'],
            ['key' => 'total_reels',      'label' => 'Reels',              'icon' => 'movie',          'color' => 'cyan'],
            ['key' => 'total_videos',     'label' => 'Videos',             'icon' => 'play_circle',    'color' => 'green'],
            ['key' => 'pending_reports',  'label' => 'Pending Reports',    'icon' => 'flag',           'color' => 'red',   'pulse' => true],
            ['key' => 'live_now',         'label' => 'Live Now',           'icon' => 'sensors',        'color' => 'red',   'pulse' => true],
            ['key' => 'total_views',      'label' => 'Total Views',        'icon' => 'visibility',     'color' => 'purple'],
            ['key' => 'total_likes',      'label' => 'Total Likes',        'icon' => 'favorite',       'color' => 'red'],
            ['key' => 'total_listings',   'label' => 'Marketplace',        'icon' => 'storefront',     'color' => 'green'],
            ['key' => 'total_orders',     'label' => 'Total Orders',       'icon' => 'shopping_bag',   'color' => 'amber'],
        ];
        foreach ($statCards as $s):
            $val = $stats[$s['key']] ?? 0;
            $pulseClass = (!empty($s['pulse']) && $val > 0) ? ' active' : '';
        ?>
        <div class="stat-card stat-pulse<?= $pulseClass ?>">
            <div class="stat-icon <?= $s['color'] ?>">
                <span class="material-icons-round"><?= $s['icon'] ?></span>
            </div>
            <div>
                <div class="stat-value"><?= formatCount($val) ?></div>
                <div class="stat-label"><?= $s['label'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Quick Actions -->
    <div class="dash-section-title">
        <span class="material-icons-round">bolt</span>
        Quick Actions
    </div>
    <div class="actions-grid">
        <a href="/admin/users" class="action-btn">
            <div class="action-icon" style="background:rgba(131,74,229,0.15);color:#834ae5;">
                <span class="material-icons-round">people</span>
            </div>
            <span class="action-label">Manage Users</span>
        </a>
        <a href="/admin/content" class="action-btn">
            <div class="action-icon" style="background:rgba(6,182,212,0.15);color:#06b6d4;">
                <span class="material-icons-round">content_paste</span>
            </div>
            <span class="action-label">Content Moderation</span>
        </a>
        <a href="/admin/reports" class="action-btn">
            <div class="action-icon" style="background:rgba(239,68,68,0.15);color:#ef4444;">
                <span class="material-icons-round">flag</span>
            </div>
            <span class="action-label">View Reports</span>
        </a>
        <a href="/admin/gifts" class="action-btn">
            <div class="action-icon" style="background:rgba(245,158,11,0.15);color:#f59e0b;">
                <span class="material-icons-round">card_giftcard</span>
            </div>
            <span class="action-label">Gifts</span>
        </a>
        <a href="/admin/payments" class="action-btn">
            <div class="action-icon" style="background:rgba(34,197,94,0.15);color:#22c55e;">
                <span class="material-icons-round">payments</span>
            </div>
            <span class="action-label">Payments</span>
        </a>
        <a href="/admin/support" class="action-btn">
            <div class="action-icon" style="background:rgba(236,72,153,0.15);color:#ec4899;">
                <span class="material-icons-round">support_agent</span>
            </div>
            <span class="action-label">Support</span>
        </a>
    </div>

    <!-- Recent Users -->
    <div class="dash-section-title">
        <span class="material-icons-round">person_add</span>
        Recent Users
    </div>
    <?php if (!empty($recentUsers)): ?>
    <div class="users-scroll">
        <?php foreach (array_slice($recentUsers, 0, 5) as $u): ?>
        <a href="/admin/users/<?= $u['id'] ?>" class="user-card" style="text-decoration:none;">
            <img src="<?= htmlspecialchars($u['avatar'] ?? '/uploads/profiles/default.png') ?>" alt="<?= htmlspecialchars($u['name'] ?? '') ?>">
            <div class="user-name">
                <?= htmlspecialchars($u['name'] ?? 'Unknown') ?>
                <?php if (!empty($u['is_verified'])): ?>
                <span class="verified-badge"><span class="material-icons-round">check</span></span>
                <?php endif; ?>
            </div>
            <div class="user-username">@<?= htmlspecialchars($u['username'] ?? '') ?></div>
            <span class="user-role <?= ($u['role'] ?? '') === 'creator' ? 'creator' : 'user' ?>">
                <?= htmlspecialchars(ucfirst($u['role'] ?? 'user')) ?>
            </span>
            <div class="user-time"><?= timeAgo($u['created_at'] ?? '') ?></div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <span class="material-icons-round">group_off</span>
        No recent users
    </div>
    <?php endif; ?>

    <!-- Recent Reports -->
    <div class="dash-section-title" style="margin-top:24px;">
        <span class="material-icons-round">report</span>
        Recent Reports
    </div>
    <?php if (!empty($recentReports)): ?>
    <?php foreach (array_slice($recentReports, 0, 5) as $r):
        $typeShort = str_replace('App\\Models\\', '', $r['reportable_type'] ?? 'Unknown');
        $statusClass = strtolower($r['status'] ?? 'pending');
        $iconBg = $statusClass === 'resolved' ? 'rgba(34,197,94,0.15)' : ($statusClass === 'dismissed' ? 'rgba(113,113,122,0.15)' : 'rgba(239,68,68,0.15)');
        $iconColor = $statusClass === 'resolved' ? '#22c55e' : ($statusClass === 'dismissed' ? '#71717a' : '#ef4444');
    ?>
    <a href="/admin/reports/<?= $r['id'] ?>" class="report-item" style="text-decoration:none;">
        <div class="report-icon" style="background:<?= $iconBg ?>;color:<?= $iconColor ?>;">
            <span class="material-icons-round">flag</span>
        </div>
        <div class="report-info">
            <div class="report-reason"><?= htmlspecialchars($r['reason'] ?? 'No reason') ?></div>
            <div class="report-meta">
                <span><?= htmlspecialchars($typeShort) ?></span>
                <span>&middot;</span>
                <span>by @<?= htmlspecialchars($r['reporter_username'] ?? 'anonymous') ?></span>
                <span>&middot;</span>
                <span><?= timeAgo($r['created_at'] ?? '') ?></span>
            </div>
        </div>
        <span class="report-status <?= $statusClass ?>"><?= htmlspecialchars(ucfirst($r['status'] ?? 'pending')) ?></span>
    </a>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="empty-state">
        <span class="material-icons-round">shield</span>
        No reports — all clear!
    </div>
    <?php endif; ?>

    <!-- Recent Content -->
    <div class="dash-section-title" style="margin-top:24px;">
        <span class="material-icons-round">auto_awesome</span>
        Recent Content
    </div>
    <?php if (!empty($recentContent)): ?>
    <?php foreach (array_slice($recentContent, 0, 5) as $c):
        $contentType = strtolower($c['type'] ?? 'post');
        $contentStatus = strtolower($c['status'] ?? 'published');
        $typeIcon = $contentType === 'reel' ? 'movie' : ($contentType === 'video' ? 'play_circle' : 'article');
    ?>
    <a href="/admin/content/<?= $contentType ?>s/<?= $c['id'] ?>" class="content-item" style="text-decoration:none;">
        <div class="content-type-icon <?= $contentType ?>">
            <span class="material-icons-round"><?= $typeIcon ?></span>
        </div>
        <div class="content-info">
            <div class="content-title"><?= htmlspecialchars($c['title'] ?? mb_substr($c['content'] ?? '', 0, 40)) ?></div>
            <div class="content-meta">
                <span><?= ucfirst($contentType) ?></span>
                <span>&middot;</span>
                <span>by ID <?= $c['user_id'] ?? '?' ?></span>
                <span>&middot;</span>
                <span><?= timeAgo($c['created_at'] ?? '') ?></span>
            </div>
        </div>
        <span class="content-status <?= $contentStatus ?>"><?= htmlspecialchars(ucfirst($contentStatus)) ?></span>
    </a>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="empty-state">
        <span class="material-icons-round">post_add</span>
        No recent content
    </div>
    <?php endif; ?>
</div>

<script>
function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.style.background = isError ? '#EF4444' : '#834ae5';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 2000);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>