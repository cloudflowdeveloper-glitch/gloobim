<?php
$title = 'My Tickets — Support';
$activeTab = 'menu';
$hideTopNav = true;
$tickets = $data['tickets'] ?? [];
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .mt-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .mt-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 16px; }
    .mt-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .mt-title { font-size: 20px; font-weight: 700; flex: 1; }
    .mt-tabs { display: flex; gap: 4px; margin-bottom: 14px; background: var(--bg-surface); border-radius: 12px; padding: 4px; }
    .mt-tab { flex: 1; text-align: center; padding: 8px 6px; border-radius: 10px; font-size: 11px; font-weight: 600; cursor: pointer; color: #94A3B8; border: none; background: none; transition: all 0.2s; }
    .mt-tab.active { background: var(--purple); color: white; }
    .mt-card { background: var(--bg-card); border-radius: 16px; padding: 16px; margin-bottom: 10px; border: 1px solid rgba(255,255,255,0.06); }
    .mt-card-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 10px; }
    .mt-card-num { font-size: 11px; color: #6B7280; font-weight: 600; }
    .mt-card-subject { font-size: 15px; font-weight: 700; margin: 4px 0 8px; }
    .mt-card-meta { display: flex; gap: 16px; margin-bottom: 12px; }
    .mt-meta-item { display: flex; align-items: center; gap: 4px; font-size: 11px; color: #94A3B8; }
    .mt-meta-item span { font-size: 14px; }
    .mt-badge { display: inline-block; padding: 3px 12px; border-radius: 8px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; }
    .mt-badge.open{background:rgba(59,130,246,0.15);color:#3B82F6;}
    .mt-badge.in_progress{background:rgba(139,92,246,0.15);color:#8B5CF6;}
    .mt-badge.waiting{background:rgba(245,158,11,0.15);color:#F59E0B;}
    .mt-badge.resolved{background:rgba(34,197,94,0.15);color:#22C55E;}
    .mt-badge.closed{background:rgba(239,68,68,0.1);color:#EF4444;}
    .mt-badge.low{background:rgba(107,114,128,0.1);color:#9CA3AF;}
    .mt-badge.medium{background:rgba(245,158,11,0.1);color:#F59E0B;}
    .mt-badge.high{background:rgba(239,68,68,0.1);color:#EF4444;}
    .mt-badge.urgent{background:rgba(220,38,38,0.15);color:#DC2626;}
    .mt-actions { display: flex; gap: 8px; margin-top: 12px; }
    .mt-reply-btn { flex: 1; padding: 10px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; background: var(--purple); color: white; border: none; }
    .mt-close-btn { padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; background: rgba(239,68,68,0.1); color: #EF4444; border: none; white-space: nowrap; }
    .mt-empty { text-align: center; padding: 80px 20px; background: var(--bg-card); border-radius: 20px; border: 1px solid rgba(255,255,255,0.06); }
    .mt-empty-icon { font-size: 64px; color: #374151; margin-bottom: 16px; }
    .mt-empty h2 { font-size: 18px; font-weight: 600; margin-bottom: 8px; }
    .mt-empty p { color: #94A3B8; font-size: 13px; margin-bottom: 20px; }
    .mt-empty a { background: var(--purple); color: white; padding: 12px 32px; border-radius: 50px; font-weight: 600; text-decoration: none; display: inline-block; }
    .toast { position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
</style>

<div class="mt-page">
    <div class="mt-header">
        <button class="mt-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="mt-title">Support Tickets</span>
        <a href="/support" style="width:36px;height:36px;border-radius:10px;background:var(--purple);display:flex;align-items:center;justify-content:center;text-decoration:none;">
            <span class="material-icons-round" style="color:white;font-size:20px;">add</span>
        </a>
    </div>

    <?php if (empty($tickets)): ?>
    <div class="mt-empty">
        <div class="mt-empty-icon"><span class="material-icons-round" style="font-size:64px;">confirmation_number</span></div>
        <h2>No tickets yet</h2>
        <p>Submit a ticket for help with any issue</p>
        <a href="/support">Submit a Ticket</a>
    </div>
    <?php else: ?>
    <?php
    $statuses = ['open','in_progress','waiting','resolved','closed'];
    $activeStatus = $_GET['status'] ?? 'all';
    $filtered = $tickets;
    if ($activeStatus !== 'all') {
        $filtered = array_filter($tickets, fn($t) => $t['status'] === $activeStatus);
    }
    ?>

    <!-- Status filter tabs -->
    <div class="mt-tabs">
        <button class="mt-tab <?= $activeStatus === 'all' ? 'active' : '' ?>" onclick="window.location='?status=all'">All</button>
        <button class="mt-tab <?= $activeStatus === 'open' ? 'active' : '' ?>" onclick="window.location='?status=open'">Open</button>
        <button class="mt-tab <?= $activeStatus === 'in_progress' ? 'active' : '' ?>" onclick="window.location='?status=in_progress'">In Progress</button>
        <button class="mt-tab <?= $activeStatus === 'resolved' ? 'active' : '' ?>" onclick="window.location='?status=resolved'">Resolved</button>
    </div>

    <?php foreach ($filtered as $t): ?>
    <div class="mt-card">
        <div class="mt-card-header">
            <span class="mt-card-num">#<?= htmlspecialchars($t['ticket_number']) ?></span>
            <div style="display:flex;gap:6px;">
                <span class="mt-badge <?= $t['status'] ?>"><?= str_replace('_',' ',$t['status']) ?></span>
                <span class="mt-badge <?= $t['priority'] ?>"><?= $t['priority'] ?></span>
            </div>
        </div>
        <div class="mt-card-subject"><?= htmlspecialchars($t['subject']) ?></div>
        <div class="mt-card-meta">
            <div class="mt-meta-item"><span class="material-icons-round">category</span><?= ucfirst($t['category']) ?></div>
            <div class="mt-meta-item"><span class="material-icons-round">chat_bubble_outline</span><?= $t['msg_count'] ?? 0 ?> replies</div>
            <div class="mt-meta-item"><span class="material-icons-round">schedule</span><?= date('M d', strtotime($t['created_at'])) ?></div>
        </div>
        <?php if ($t['last_reply_at']): ?>
        <div class="mt-meta-item" style="font-size:10px;color:#6B7280;margin-bottom:8px;">
            <span class="material-icons-round" style="font-size:12px;">history</span>Last reply <?= date('M d g:i A', strtotime($t['last_reply_at'])) ?>
        </div>
        <?php endif; ?>
        <div class="mt-actions">
            <a href="/support/<?= $t['id'] ?>" class="mt-reply-btn">
                <span class="material-icons-round" style="font-size:18px;">reply</span> Reply
            </a>
            <?php if (!in_array($t['status'], ['resolved','closed'])): ?>
            <button class="mt-close-btn" onclick="closeTicket(<?= $t['id'] ?>)">
                <span class="material-icons-round" style="font-size:16px;">check_circle</span> Close
            </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function closeTicket(id) {
    if (!confirm('Close this ticket?')) return;
    fetch('/support/' + id + '/close', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(function() { location.reload(); });
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
