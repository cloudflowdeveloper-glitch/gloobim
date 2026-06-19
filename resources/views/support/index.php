<?php
$title = 'Help & Support - DTTube';
$activeTab = 'menu';
$hideTopNav = true;
$contacts = $data['contacts'] ?? [];
$categories = $data['categories'] ?? [];
$tickets = $data['tickets'] ?? [];
$user = \Core\Auth::user();
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .sp-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .sp-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 20px; position: sticky; top: 0; background: var(--bg-deep); z-index: 50; }
    .sp-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .sp-title { font-size: 20px; font-weight: 700; flex: 1; }
    .sp-section { background: var(--bg-card); border-radius: 16px; padding: 18px; margin-bottom: 12px; border: 1px solid rgba(255,255,255,0.06); }
    .sp-section h3 { font-size: 14px; font-weight: 700; margin: 0 0 12px; display: flex; align-items: center; gap: 8px; }
    .sp-contact-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.04); }
    .sp-contact-item:last-child { border-bottom: none; }
    .sp-contact-icon { width: 38px; height: 38px; border-radius: 10px; background: rgba(139,92,246,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .sp-contact-icon span { font-size: 18px; color: var(--purple); }
    .sp-contact-label { font-size: 12px; color: #94A3B8; }
    .sp-contact-value { font-size: 14px; font-weight: 600; }
    .sp-field { margin-bottom: 12px; }
    .sp-field label { display: block; font-size: 12px; color: #94A3B8; margin-bottom: 4px; font-weight: 500; }
    .sp-field input, .sp-field textarea, .sp-field select { width: 100%; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 10px 12px; color: white; font-size: 14px; font-family: 'Inter', sans-serif; outline: none; box-sizing: border-box; }
    .sp-field input:focus, .sp-field textarea:focus { border-color: var(--purple); }
    .sp-field textarea { resize: vertical; min-height: 90px; }
    .sp-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .sp-submit { background: var(--purple); color: white; border: none; border-radius: 12px; padding: 12px; font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; }
    .sp-submit:disabled { opacity: 0.5; }
    .sp-ticket { display: flex; align-items: center; gap: 12px; padding: 12px 14px; background: var(--bg-surface); border-radius: 12px; margin-bottom: 8px; text-decoration: none; color: inherit; transition: background 0.2s; }
    .sp-ticket:hover { background: rgba(139,92,246,0.08); }
    .sp-ticket-info { flex: 1; min-width: 0; }
    .sp-ticket-subject { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sp-ticket-meta { font-size: 10px; color: #94A3B8; margin-top: 3px; }
    .sp-badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
    .sp-badge.open { background: rgba(59,130,246,0.15); color: #3B82F6; }
    .sp-badge.in_progress { background: rgba(139,92,246,0.15); color: #8B5CF6; }
    .sp-badge.waiting { background: rgba(245,158,11,0.15); color: #F59E0B; }
    .sp-badge.resolved { background: rgba(34,197,94,0.15); color: #22C55E; }
    .sp-badge.closed { background: rgba(239,68,68,0.1); color: #EF4444; }
    .sp-tab-bar { display: flex; gap: 4px; margin-bottom: 16px; background: var(--bg-surface); border-radius: 12px; padding: 4px; }
    .sp-tab { flex: 1; text-align: center; padding: 8px; border-radius: 10px; font-size: 12px; font-weight: 600; cursor: pointer; color: #94A3B8; border: none; background: none; transition: all 0.2s; }
    .sp-tab.active { background: var(--purple); color: white; }
    .toast { position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
</style>

<div class="sp-page">
    <div class="sp-header">
        <button class="sp-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="sp-title">Help & Support</span>
    </div>

    <!-- Contact Details from DB -->
    <div class="sp-section">
        <h3><span class="material-icons-round" style="color:var(--purple);">contact_support</span> Contact Us</h3>
        <?php if (empty($contacts)): ?>
        <p style="color:#94A3B8;font-size:13px;">Contact details coming soon.</p>
        <?php else: ?>
        <?php foreach ($contacts as $c): ?>
        <div class="sp-contact-item">
            <div class="sp-contact-icon"><span class="material-icons-round"><?= htmlspecialchars($c['icon'] ?? 'info') ?></span></div>
            <div>
                <div class="sp-contact-label"><?= htmlspecialchars($c['label']) ?></div>
                <div class="sp-contact-value"><?= htmlspecialchars($c['value']) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Create Ticket -->
    <div class="sp-section" id="ticket-form-section">
        <h3><span class="material-icons-round" style="color:var(--purple);">support_agent</span> Submit a Ticket</h3>
        <?php if ($user): ?>
        <form id="ticketForm" onsubmit="return false;">
            <div class="sp-field">
                <label>Subject <span style="color:#EF4444;">*</span></label>
                <input type="text" id="ticketSubject" placeholder="Brief description of your issue" required>
            </div>
            <div class="sp-row">
                <div class="sp-field">
                    <label>Category</label>
                    <select id="ticketCategory">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['slug']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="general">General</option>
                            <option value="technical">Technical</option>
                            <option value="billing">Billing</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="sp-field">
                    <label>Priority</label>
                    <select id="ticketPriority">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            <div class="sp-field">
                <label>Message <span style="color:#EF4444;">*</span></label>
                <textarea id="ticketMessage" placeholder="Describe your issue in detail..." required></textarea>
            </div>
            <button type="submit" class="sp-submit" id="ticketSubmitBtn" onclick="submitTicket()">
                <span class="material-icons-round">send</span> Submit Ticket
            </button>
        </form>
        <?php else: ?>
        <p style="color:#94A3B8;font-size:13px;text-align:center;padding:16px;">
            <a href="/login" style="color:var(--purple);">Sign in</a> to submit a support ticket.
        </p>
        <?php endif; ?>
    </div>

    <!-- My Tickets -->
    <?php if ($user && !empty($tickets)): ?>
    <div class="sp-section">
        <h3><span class="material-icons-round" style="color:var(--purple);">receipt_long</span> My Tickets</h3>
        <?php foreach ($tickets as $t): ?>
        <a href="/support/<?= $t['id'] ?>" class="sp-ticket">
            <span class="material-icons-round" style="color:var(--purple);">confirmation_number</span>
            <div class="sp-ticket-info">
                <div class="sp-ticket-subject"><?= htmlspecialchars($t['subject']) ?></div>
                <div class="sp-ticket-meta">#<?= $t['ticket_number'] ?> · <?= date('M d', strtotime($t['created_at'])) ?></div>
            </div>
            <span class="sp-badge <?= $t['status'] ?>"><?= str_replace('_',' ',$t['status']) ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function submitTicket() {
    var btn = document.getElementById('ticketSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round" style="animation:spin 0.8s linear infinite;">sync</span> Submitting...';

    fetch('/support/create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({
            subject: document.getElementById('ticketSubject').value.trim(),
            category: document.getElementById('ticketCategory').value,
            priority: document.getElementById('ticketPriority').value,
            message: document.getElementById('ticketMessage').value.trim()
        })
    })
    .then(r => r.json())
    .then(d => {
        if (d.error) { showToast(d.error, true); btn.disabled = false; btn.innerHTML = '<span class="material-icons-round">send</span> Submit Ticket'; return; }
        showToast('Ticket #' + d.ticket_number + ' created!', false);
        setTimeout(function() { window.location.href = '/support/' + d.ticket_id; }, 1000);
    })
    .catch(function() { showToast('Network error', true); btn.disabled = false; btn.innerHTML = '<span class="material-icons-round">send</span> Submit Ticket'; });
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
<style>@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }</style>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
