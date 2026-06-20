<?php
$ticket = $data['ticket'] ?? null;
$messages = $data['messages'] ?? [];
$title = 'Ticket #' . ($ticket['ticket_number'] ?? '') . ' - Support';
$activeTab = 'menu';
$hideTopNav = true;
if (!$ticket) { header('Location: /support'); exit; }
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .st-page { max-width: 480px; margin: 0 auto; padding: 0 16px 120px; }
    .st-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 16px; }
    .st-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .st-title { font-size: 18px; font-weight: 700; flex: 1; }
    .st-meta { background: var(--bg-card); border-radius: 16px; padding: 14px 16px; margin-bottom: 14px; border: 1px solid rgba(255,255,255,0.06); }
    .st-meta-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px; }
    .st-meta-label { color: #94A3B8; }
    .st-meta-value { font-weight: 600; }
    .st-badge { display: inline-block; padding: 2px 10px; border-radius: 8px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
    .st-badge.open{background:rgba(59,130,246,0.15);color:#3B82F6}
    .st-badge.in_progress{background:rgba(139,92,246,0.15);color:#8B5CF6}
    .st-badge.waiting{background:rgba(245,158,11,0.15);color:#F59E0B}
    .st-badge.resolved{background:rgba(34,197,94,0.15);color:#22C55E}
    .st-badge.closed{background:rgba(239,68,68,0.1);color:#EF4444}
    .st-msg { margin-bottom: 12px; }
    .st-msg-header { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
    .st-msg-avatar { width: 28px; height: 28px; border-radius: 50%; object-fit: cover; background: var(--bg-surface); }
    .st-msg-name { font-size: 12px; font-weight: 600; }
    .st-msg-role { font-size: 10px; padding: 1px 6px; border-radius: 6px; }
    .st-msg-role.user { background: rgba(139,92,246,0.15); color: var(--purple); }
    .st-msg-role.admin { background: rgba(245,158,11,0.15); color: #F59E0B; }
    .st-msg-time { font-size: 10px; color: #6B7280; margin-left: auto; }
    .st-msg-body { background: var(--bg-card); border-radius: 12px; padding: 12px 14px; font-size: 13px; line-height: 1.6; border: 1px solid rgba(255,255,255,0.04); color: #E2E8F0; }
    .st-reply-bar { position: fixed; bottom: 0; left: 0; right: 0; max-width: 480px; margin: 0 auto; background: rgba(11,17,32,0.98); backdrop-filter: blur(12px); border-top: 1px solid rgba(255,255,255,0.06); padding: 10px 16px 20px; z-index: 50; }
    .st-reply-wrap { display: flex; gap: 8px; align-items: center; }
    .st-reply-input { flex: 1; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.08); border-radius: 24px; padding: 10px 16px; color: white; font-size: 13px; outline: none; font-family: 'Inter', sans-serif; }
    .st-reply-input:focus { border-color: var(--purple); }
    .st-reply-send { width: 40px; height: 40px; border-radius: 50%; background: var(--purple); border: none; color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
    .st-reply-send:disabled { opacity: 0.5; }
    .st-close-btn { background: rgba(239,68,68,0.12); color: #EF4444; border: none; border-radius: 10px; padding: 8px 16px; font-size: 12px; font-weight: 600; cursor: pointer; }
    .toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
</style>

<div class="st-page">
    <div class="st-header">
        <button class="st-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="st-title">#<?= htmlspecialchars($ticket['ticket_number']) ?></span>
        <?php if (!in_array($ticket['status'], ['resolved','closed'])): ?>
        <button class="st-close-btn" onclick="closeTicket(<?= $ticket['id'] ?>)">Close</button>
        <?php endif; ?>
    </div>

    <div class="st-meta">
        <h3 style="font-size:15px;font-weight:700;margin:0 0 10px;"><?= htmlspecialchars($ticket['subject']) ?></h3>
        <div class="st-meta-row"><span class="st-meta-label">Status</span><span class="st-badge <?= $ticket['status'] ?>"><?= str_replace('_',' ',$ticket['status']) ?></span></div>
        <div class="st-meta-row"><span class="st-meta-label">Priority</span><span class="st-meta-value"><?= ucfirst($ticket['priority']) ?></span></div>
        <div class="st-meta-row"><span class="st-meta-label">Category</span><span class="st-meta-value"><?= ucfirst($ticket['category']) ?></span></div>
        <div class="st-meta-row"><span class="st-meta-label">Created</span><span class="st-meta-value"><?= date('M j, Y g:i A', strtotime($ticket['created_at'])) ?></span></div>
    </div>

    <?php foreach ($messages as $msg): ?>
    <div class="st-msg">
        <div class="st-msg-header">
            <img src="<?= $msg['avatar'] ?? '/uploads/profiles/admin.jpg' ?>" class="st-msg-avatar" alt="">
            <span class="st-msg-name"><?= htmlspecialchars($msg['name'] ?? $msg['username'] ?? 'User') ?></span>
            <span class="st-msg-role <?= $msg['is_admin'] ? 'admin' : 'user' ?>"><?= $msg['is_admin'] ? 'Support' : 'You' ?></span>
            <span class="st-msg-time"><?= date('M d g:i A', strtotime($msg['created_at'])) ?></span>
        </div>
        <div class="st-msg-body"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
    </div>
    <?php endforeach; ?>

    <?php if (!in_array($ticket['status'], ['resolved','closed'])): ?>
    <p style="text-align:center;color:#94A3B8;font-size:11px;padding:8px 0;">Waiting for response from our support team</p>
    <?php else: ?>
    <p style="text-align:center;color:#6B7280;font-size:11px;padding:8px 0;">This ticket is <?= $ticket['status'] ?></p>
    <?php endif; ?>
</div>

<?php if (!in_array($ticket['status'], ['resolved','closed'])): ?>
<div class="st-reply-bar">
    <div class="st-reply-wrap">
        <input type="text" class="st-reply-input" id="replyInput" placeholder="Type your reply..." onkeydown="if(event.key==='Enter')sendReply()">
        <button class="st-reply-send" onclick="sendReply()"><span class="material-icons-round">send</span></button>
    </div>
</div>
<?php endif; ?>

<script>
function sendReply() {
    var input = document.getElementById('replyInput');
    var msg = input.value.trim();
    if (!msg) return;

    var btn = document.querySelector('.st-reply-send');
    btn.disabled = true;

    fetch('/support/<?= $ticket['id'] ?>/reply', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ message: msg })
    })
    .then(r => r.json())
    .then(d => {
        if (d.error) { showToast(d.error, true); btn.disabled = false; return; }
        location.reload();
    })
    .catch(function() { showToast('Error sending reply', true); btn.disabled = false; });
}

function closeTicket(id) {
    if (!confirm('Close this ticket?')) return;
    fetch('/support/' + id + '/close', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json()).then(function() { location.reload(); });
}

function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function(){ t.remove(); }, 2500);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
