<?php
$title = 'Payment Settings — Admin';
$methods = $data['methods'] ?? [];
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --purple: #8B5CF6; --green: #22C55E; --red: #EF4444; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .ap-page { max-width: 600px; margin: 0 auto; padding: 0 16px 80px; }
    .ap-header { padding: 48px 0 20px; display: flex; align-items: center; gap: 12px; }
    .ap-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .ap-title { font-size: 20px; font-weight: 700; flex: 1; }
    .ap-card { background: var(--bg-card); border-radius: 16px; padding: 16px; margin-bottom: 10px; border: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 14px; }
    .ap-icon { font-size: 28px; color: var(--purple); width: 48px; height: 48px; border-radius: 12px; background: rgba(139,92,246,0.1); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .ap-info { flex: 1; }
    .ap-name { font-size: 15px; font-weight: 600; margin-bottom: 2px; }
    .ap-desc { font-size: 12px; color: #94A3B8; }
    .ap-toggle { width: 52px; height: 28px; border-radius: 14px; background: rgba(255,255,255,0.1); position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
    .ap-toggle.enabled { background: var(--green); }
    .ap-toggle::after { content: ''; width: 22px; height: 22px; background: white; border-radius: 50%; position: absolute; top: 3px; left: 3px; transition: transform 0.2s; }
    .ap-toggle.enabled::after { transform: translateX(24px); }
    .ap-status { font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 10px; display: inline-block; }
    .ap-status.enabled { background: rgba(34,197,94,0.15); color: var(--green); }
    .ap-status.disabled { background: rgba(239,68,68,0.15); color: var(--red); }
    .toast { position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; animation: fadeInUp 0.3s ease; }
    @keyframes fadeInUp { from { opacity: 0; transform: translate(-50%,10px); } to { opacity: 1; transform: translate(-50%,0); } }
</style>

<div class="ap-page">
    <div class="ap-header">
        <button class="ap-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="ap-title">Payment Settings</span>
    </div>
    <p style="color:#94A3B8;font-size:13px;margin-bottom:16px;">Enable or disable payment methods for customer checkout.</p>

    <?php foreach ($methods as $m): ?>
    <div class="ap-card" id="pm-<?= $m['id'] ?>">
        <div class="ap-icon">
            <span class="material-icons-round"><?= htmlspecialchars($m['icon'] ?? 'payments') ?></span>
        </div>
        <div class="ap-info">
            <div class="ap-name"><?= htmlspecialchars($m['display_name']) ?></div>
            <div class="ap-desc"><?= htmlspecialchars($m['description'] ?? '') ?></div>
            <span class="ap-status <?= $m['is_enabled'] ? 'enabled' : 'disabled' ?>" id="status-<?= $m['id'] ?>">
                <?= $m['is_enabled'] ? 'Enabled' : 'Disabled' ?>
            </span>
        </div>
        <div class="ap-toggle <?= $m['is_enabled'] ? 'enabled' : '' ?>" id="toggle-<?= $m['id'] ?>" onclick="toggleMethod(<?= $m['id'] ?>)"></div>
    </div>
    <?php endforeach; ?>
</div>

<script>
function toggleMethod(id) {
    var toggle = document.getElementById('toggle-' + id);
    var status = document.getElementById('status-' + id);
    toggle.style.pointerEvents = 'none';

    fetch('/admin/payments/toggle/' + id, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.is_enabled) {
            toggle.classList.add('enabled');
            status.textContent = 'Enabled';
            status.className = 'ap-status enabled';
        } else {
            toggle.classList.remove('enabled');
            status.textContent = 'Disabled';
            status.className = 'ap-status disabled';
        }
        showToast(d.message);
        toggle.style.pointerEvents = '';
    })
    .catch(function() {
        showToast('Error toggling method', true);
        toggle.style.pointerEvents = '';
    });
}

function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 2000);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
