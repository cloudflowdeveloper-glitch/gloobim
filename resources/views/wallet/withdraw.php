<?php
$title = 'Withdraw — Wallet';
$activeTab = 'menu';
$hideTopNav = true;
$wallet = $data['wallet'] ?? null;
$balance = $wallet ? (float)$wallet['balance'] : 0;
$currency = $wallet['currency'] ?? 'KES';
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; --red: #EF4444; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .ww-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .ww-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 20px; }
    .ww-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .ww-title { font-size: 20px; font-weight: 700; flex: 1; }
    .ww-balance { background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(167,139,250,0.05)); border: 1px solid rgba(139,92,246,0.2); border-radius: 16px; padding: 18px; text-align: center; margin-bottom: 16px; }
    .ww-balance-label { font-size: 11px; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
    .ww-balance-amount { font-size: 32px; font-weight: 800; }
    .ww-section { background: var(--bg-card); border-radius: 16px; padding: 18px; margin-bottom: 14px; border: 1px solid rgba(255,255,255,0.06); }
    .ww-section h3 { font-size: 14px; font-weight: 700; margin: 0 0 12px; display: flex; align-items: center; gap: 8px; }
    .ww-field { margin-bottom: 12px; }
    .ww-field label { display: block; font-size: 12px; color: #94A3B8; margin-bottom: 4px; font-weight: 500; }
    .ww-field input, .ww-field select { width: 100%; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 10px 14px; color: white; font-size: 14px; font-family: 'Inter', sans-serif; outline: none; box-sizing: border-box; }
    .ww-field input:focus { border-color: var(--purple); }
    .ww-preset-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 12px; }
    .ww-preset { padding: 10px; text-align: center; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #94A3B8; transition: all 0.2s; }
    .ww-preset:hover, .ww-preset.active { border-color: var(--purple); color: white; background: rgba(139,92,246,0.1); }
    .ww-custom-input { width: 100%; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 12px 16px; color: white; font-size: 20px; font-weight: 700; outline: none; box-sizing: border-box; text-align: center; }
    .ww-custom-input:focus { border-color: var(--purple); }
    .ww-fee-note { font-size: 11px; color: #94A3B8; text-align: center; margin-top: 6px; }
    .ww-submit { width: 100%; padding: 14px; border-radius: 14px; font-size: 16px; font-weight: 700; cursor: pointer; border: none; display: flex; align-items: center; justify-content: center; gap: 8px; background: var(--purple); color: white; }
    .ww-submit:disabled { opacity: 0.5; }
    .toast { position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
</style>

<div class="ww-page">
    <div class="ww-header">
        <button class="ww-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="ww-title">Withdraw Funds</span>
    </div>

    <div class="ww-balance">
        <div class="ww-balance-label">Available Balance</div>
        <div class="ww-balance-amount"><?= $currency ?> <?= number_format($balance, 0) ?></div>
    </div>

    <div class="ww-section">
        <h3><span class="material-icons-round" style="color:var(--purple);">payments</span> Amount</h3>
        <div class="ww-preset-grid">
            <div class="ww-preset" onclick="setAmount(500)">KES 500</div>
            <div class="ww-preset" onclick="setAmount(1000)">KES 1,000</div>
            <div class="ww-preset" onclick="setAmount(2500)">KES 2,500</div>
            <div class="ww-preset" onclick="setAmount(5000)">KES 5,000</div>
            <div class="ww-preset" onclick="setAmount(10000)">KES 10,000</div>
            <div class="ww-preset" onclick="setAmount(<?= (int)$balance ?>)">All</div>
        </div>
        <input type="number" class="ww-custom-input" id="withdrawAmount" placeholder="Enter amount" min="10" max="<?= (int)$balance ?>" oninput="clearPresets()">
        <div class="ww-fee-note">Withdrawal fee: KES 0 — Free</div>
    </div>

    <div class="ww-section">
        <h3><span class="material-icons-round" style="color:var(--purple);">phone_android</span> M-Pesa Details</h3>
        <div class="ww-field">
            <label>Phone Number (M-Pesa) <span style="color:#EF4444;">*</span></label>
            <input type="tel" id="withdrawPhone" placeholder="254712345678" value="">
        </div>
        <div class="ww-field">
            <label>Account Name</label>
            <input type="text" id="withdrawName" placeholder="Your full name">
        </div>
    </div>

    <button class="ww-submit" id="withdrawBtn" onclick="submitWithdraw()">
        <span class="material-icons-round">sync_alt</span> Withdraw
    </button>
</div>

<!-- Withdrawal Confirmation Modal -->
<div class="ww-modal-overlay" id="confirmModal">
    <div class="ww-modal">
        <div style="width:52px;height:52px;border-radius:50%;background:rgba(139,92,246,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
            <span class="material-icons-round" style="font-size:26px;color:#8B5CF6;">sync_alt</span>
        </div>
        <h3 style="font-size:17px;font-weight:700;margin-bottom:4px;">Confirm Withdrawal</h3>
        <div id="confirmDetails" style="background:var(--bg-surface);border-radius:12px;padding:14px;margin:14px 0;text-align:left;"></div>
        <div style="display:flex;gap:10px;">
            <button onclick="closeConfirmModal()" style="flex:1;padding:12px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-radius:12px;color:#94A3B8;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="doWithdraw()" style="flex:1;padding:12px;background:var(--purple);border:none;border-radius:12px;color:white;font-size:14px;font-weight:600;cursor:pointer;">Confirm</button>
        </div>
    </div>
</div>

<style>
    .ww-modal-overlay { position:fixed;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(4px);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity 0.25s; }
    .ww-modal-overlay.active { opacity:1;pointer-events:all; }
    .ww-modal { background:#151D2E;border-radius:20px;padding:24px 22px;max-width:360px;width:100%;text-align:center;border:1px solid rgba(255,255,255,0.06);animation:wwSlide 0.25s ease; }
    @keyframes wwSlide { from{transform:translateY(20px);opacity:0;} to{transform:translateY(0);opacity:1;} }
</style>

<script>
function setAmount(val) {
    document.getElementById('withdrawAmount').value = val;
    document.querySelectorAll('.ww-preset').forEach(function(p) { p.classList.remove('active'); });
    event.target.classList.add('active');
}
function clearPresets() { document.querySelectorAll('.ww-preset').forEach(function(p) { p.classList.remove('active'); }); }

var _wAmount = 0, _wPhone = '', _wName = '';

function submitWithdraw() {
    _wAmount = parseInt(document.getElementById('withdrawAmount').value) || 0;
    _wPhone = document.getElementById('withdrawPhone').value.trim();
    _wName = document.getElementById('withdrawName').value.trim();

    if (_wAmount < 10) { showToast('Minimum withdrawal is KES 10', true); return; }
    if (_wAmount > <?= (int)$balance ?>) { showToast('Insufficient balance', true); return; }
    if (!_wPhone) { showToast('Enter M-Pesa phone number', true); return; }
    if (!/^(0?7|2547|\+2547)/.test(_wPhone.replace(/[^0-9]/g,''))) { showToast('Must be a valid Kenyan M-Pesa number', true); return; }

    document.getElementById('confirmDetails').innerHTML =
        '<div style="display:flex;justify-content:space-between;padding:5px 0;font-size:13px;"><span style="color:#94A3B8;">Amount</span><span style="font-weight:700;">KES ' + _wAmount.toLocaleString() + '</span></div>' +
        '<div style="display:flex;justify-content:space-between;padding:5px 0;font-size:13px;"><span style="color:#94A3B8;">To</span><span style="font-weight:600;">' + (_wName || _wPhone) + '</span></div>' +
        '<div style="display:flex;justify-content:space-between;padding:5px 0;font-size:13px;"><span style="color:#94A3B8;">Phone</span><span style="font-weight:600;">' + _wPhone + '</span></div>' +
        '<div style="display:flex;justify-content:space-between;padding:5px 0;font-size:13px;color:#22C55E;"><span>Fee</span><span>Free</span></div>';

    document.getElementById('confirmModal').classList.add('active');
}

function closeConfirmModal() { document.getElementById('confirmModal').classList.remove('active'); }

function doWithdraw() {
    closeConfirmModal();
    var btn = document.getElementById('withdrawBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round" style="animation:spin 0.8s linear infinite;">sync</span> Processing...';

    fetch('/wallet/withdraw', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ amount: _wAmount, phone: _wPhone, name: _wName })
    }).then(r => r.json()).then(function(d) {
        if (d.error) { showToast(d.error, true); btn.disabled = false; btn.innerHTML = '<span class="material-icons-round">sync_alt</span> Withdraw'; return; }
        showToast(d.message + ' — KES ' + _wAmount.toLocaleString() + ' sent to ' + _wPhone, false);
        setTimeout(function() { window.location.href = '/wallet'; }, 2000);
    }).catch(function() { showToast('Error', true); btn.disabled = false; btn.innerHTML = '<span class="material-icons-round">sync_alt</span> Withdraw'; });
}

function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 3000);
}
</script>
<style>@keyframes spin { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }</style>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
