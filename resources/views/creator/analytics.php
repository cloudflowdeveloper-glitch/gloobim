<?php $activeTab = 'menu'; $title = 'Analytics - DTTube'; ?>
<?php $d = $data['data'] ?? []; ?>
<?php ob_start(); ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
<style>
    .material-icons-round { font-family: 'Material Icons Round'; font-weight: normal; font-style: normal; font-size: 24px; line-height: 1; letter-spacing: normal; text-transform: none; display: inline-block; white-space: nowrap; word-wrap: normal; direction: ltr; -webkit-font-smoothing: antialiased; }
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; --green: #22C55E; --amber: #F59E0B; --red: #EF4444; --blue: #3B82F6; --pink: #EC4899; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .an-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .an-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 16px; }
    .an-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .an-title { font-size: 20px; font-weight: 700; flex: 1; display: flex; align-items: center; gap: 8px; }
    .an-stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 14px; }
    .an-stat-card { background: var(--bg-card); border-radius: 14px; padding: 14px; border: 1px solid rgba(255,255,255,0.06); animation: anFadeUp 0.5s ease-out forwards; opacity: 0; }
    .an-stat-card:nth-child(1) { animation-delay: 0.05s; } .an-stat-card:nth-child(2) { animation-delay: 0.1s; } .an-stat-card:nth-child(3) { animation-delay: 0.15s; } .an-stat-card:nth-child(4) { animation-delay: 0.2s; }
    @keyframes anFadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .an-stat-label { font-size: 10px; color: #94A3B8; display: flex; align-items: center; gap: 4px; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
    .an-stat-value { font-size: 24px; font-weight: 800; }
    .an-stat-growth { font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 3px; margin-top: 4px; }
    .an-stat-growth.up { background: rgba(34,197,94,0.12); color: var(--green); }
    .an-sub-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 14px; }
    .an-sub-card { background: var(--bg-card); border-radius: 14px; padding: 14px; text-align: center; border: 1px solid rgba(255,255,255,0.06); animation: anFadeUp 0.5s ease-out forwards; opacity: 0; }
    .an-sub-card:nth-child(1) { animation-delay: 0.25s; } .an-sub-card:nth-child(2) { animation-delay: 0.3s; } .an-sub-card:nth-child(3) { animation-delay: 0.35s; }
    .an-sub-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; }
    .an-sub-value { font-size: 18px; font-weight: 700; }
    .an-sub-label { font-size: 10px; color: #94A3B8; margin-top: 2px; }
    .an-chart-card { background: var(--bg-card); border-radius: 16px; padding: 18px; margin-bottom: 14px; border: 1px solid rgba(255,255,255,0.06); }
    .an-chart-card h3 { font-size: 13px; font-weight: 700; margin: 0 0 16px; display: flex; align-items: center; gap: 8px; }
    .an-bars { display: flex; align-items: flex-end; gap: 8px; height: 120px; }
    .an-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; height: 100%; }
    .an-bar-fill { width: 100%; border-radius: 6px 6px 0 0; transition: height 0.6s ease; position: relative; }
    .an-bar-label { font-size: 9px; color: #6B7280; }
    .an-bar-value { font-size: 8px; color: #94A3B8; font-weight: 600; opacity: 0; transition: opacity 0.3s; }
    .an-bar-wrap:hover .an-bar-value { opacity: 1; }
    .an-top-card { background: var(--bg-card); border-radius: 14px; padding: 14px; margin-bottom: 8px; border: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 12px; animation: anFadeUp 0.4s ease-out forwards; opacity: 0; }
    .an-top-card:nth-child(1) { animation-delay: 0.5s; } .an-top-card:nth-child(2) { animation-delay: 0.55s; } .an-top-card:nth-child(3) { animation-delay: 0.6s; } .an-top-card:nth-child(4) { animation-delay: 0.65s; } .an-top-card:nth-child(5) { animation-delay: 0.7s; }
    .an-rank { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0; }
    .an-rank-1 { background: rgba(245,158,11,0.2); color: #F59E0B; }
    .an-rank-2 { background: rgba(148,163,184,0.15); color: #94A3B8; }
    .an-rank-3 { background: rgba(180,83,38,0.15); color: #D97742; }
    .an-rank-def { background: rgba(255,255,255,0.06); color: #6B7280; }
    .an-top-info { flex: 1; min-width: 0; }
    .an-top-title { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .an-top-meta { display: flex; gap: 12px; margin-top: 4px; font-size: 10px; color: #94A3B8; }
    .an-top-meta span { display: flex; align-items: center; gap: 3px; }
    .an-top-time { font-size: 10px; color: #6B7280; flex-shrink: 0; }
    .an-empty { text-align: center; padding: 40px; background: var(--bg-card); border-radius: 16px; border: 1px solid rgba(255,255,255,0.06); }
    .an-empty-icon { font-size: 48px; color: #374151; margin-bottom: 12px; }
    .an-empty h3 { font-size: 15px; font-weight: 600; margin-bottom: 4px; }
    .an-empty p { color: #94A3B8; font-size: 13px; }
</style>

<div class="an-page">
    <div class="an-header">
        <button class="an-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="an-title"><span class="material-icons-round" style="color:var(--purple);">bar_chart</span> Analytics</span>
    </div>

    <!-- Main Stats -->
    <div class="an-stat-grid">
        <div class="an-stat-card">
            <div class="an-stat-label"><span class="material-icons-round" style="font-size:14px;color:var(--purple);">visibility</span> Total Views</div>
            <div class="an-stat-value"><?= number_format((int)($d['total_views'] ?? 0)) ?></div>
            <div class="an-stat-growth up"><span class="material-icons-round" style="font-size:12px;">trending_up</span> <?= $d['views_growth'] ?? 0 ?>%</div>
        </div>
        <div class="an-stat-card">
            <div class="an-stat-label"><span class="material-icons-round" style="font-size:14px;color:var(--pink);">favorite</span> Total Likes</div>
            <div class="an-stat-value"><?= number_format((int)($d['total_likes'] ?? 0)) ?></div>
            <div class="an-stat-growth up"><span class="material-icons-round" style="font-size:12px;">trending_up</span> +<?= $d['engagement_rate'] ?? 0 ?>%</div>
        </div>
        <div class="an-stat-card">
            <div class="an-stat-label"><span class="material-icons-round" style="font-size:14px;color:var(--green);">people</span> Followers</div>
            <div class="an-stat-value"><?= number_format((int)($d['total_followers'] ?? 0)) ?></div>
            <div class="an-stat-growth up"><span class="material-icons-round" style="font-size:12px;">trending_up</span> <?= $d['followers_growth'] ?? 0 ?>%</div>
        </div>
        <div class="an-stat-card">
            <div class="an-stat-label"><span class="material-icons-round" style="font-size:14px;color:var(--amber);">account_balance_wallet</span> Earnings</div>
            <div class="an-stat-value">KES <?= number_format((float)($d['total_earnings'] ?? 0)) ?></div>
        </div>
    </div>

    <!-- Sub Stats -->
    <div class="an-sub-grid">
        <div class="an-sub-card">
            <div class="an-sub-icon" style="background:rgba(59,130,246,0.12);"><span class="material-icons-round" style="color:var(--blue);">chat_bubble_outline</span></div>
            <div class="an-sub-value"><?= number_format((int)($d['total_comments'] ?? 0)) ?></div>
            <div class="an-sub-label">Comments</div>
        </div>
        <div class="an-sub-card">
            <div class="an-sub-icon" style="background:rgba(34,197,94,0.12);"><span class="material-icons-round" style="color:var(--green);">ios_share</span></div>
            <div class="an-sub-value"><?= number_format((int)($d['total_shares'] ?? 0)) ?></div>
            <div class="an-sub-label">Shares</div>
        </div>
        <div class="an-sub-card">
            <div class="an-sub-icon" style="background:rgba(139,92,246,0.12);"><span class="material-icons-round" style="color:var(--purple);">auto_graph</span></div>
            <div class="an-sub-value"><?= $d['engagement_rate'] ?? 0 ?>%</div>
            <div class="an-sub-label">Engagement</div>
        </div>
    </div>

    <!-- Bar Chart -->
    <div class="an-chart-card">
        <h3><span class="material-icons-round" style="color:var(--purple);">bar_chart</span> Daily Views (Last 7 Days)</h3>
        <?php $daily = $d['daily_views'] ?? []; $views = array_column($daily, 'views'); $max = !empty($views) ? max($views) : 1; ?>
        <div class="an-bars">
            <?php foreach ($daily as $day): $pct = max(8, ((int)$day['views'] / $max) * 100); ?>
            <div class="an-bar-wrap">
                <div class="an-bar-value"><?= number_format((int)$day['views']) ?></div>
                <div class="an-bar-fill" style="height:<?= $pct ?>%;background:linear-gradient(180deg,rgba(139,92,246,0.7),rgba(139,92,246,0.2));"></div>
                <div class="an-bar-label"><?= $day['date'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Top Content -->
    <?php if (!empty($d['top_content'])): ?>
    <div class="an-chart-card">
        <h3><span class="material-icons-round" style="color:var(--amber);">emoji_events</span> Top Performing Content</h3>
        <?php foreach ($d['top_content'] as $i => $item): ?>
        <div class="an-top-card">
            <div class="an-rank <?= $i === 0 ? 'an-rank-1' : ($i === 1 ? 'an-rank-2' : ($i === 2 ? 'an-rank-3' : 'an-rank-def')) ?>">#<?= $i + 1 ?></div>
            <div class="an-top-info">
                <div class="an-top-title"><?= htmlspecialchars(mb_substr($item['content'] ?? '', 0, 50) . (mb_strlen($item['content'] ?? '') > 50 ? '...' : '')) ?></div>
                <div class="an-top-meta">
                    <span><span class="material-icons-round" style="font-size:12px;color:var(--pink);">favorite</span> <?= formatCount($item['likes'] ?? 0) ?></span>
                    <span><span class="material-icons-round" style="font-size:12px;color:var(--blue);">chat_bubble</span> <?= formatCount($item['comments_count'] ?? 0) ?></span>
                    <span><span class="material-icons-round" style="font-size:12px;color:var(--green);">ios_share</span> <?= formatCount($item['shares'] ?? 0) ?></span>
                </div>
            </div>
            <span class="an-top-time"><?= timeAgo($item['created_at']) ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="an-empty">
        <div class="an-empty-icon"><span class="material-icons-round">analytics</span></div>
        <h3>No data yet</h3>
        <p>Publish content to see your top performers</p>
    </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
