<?php
$title = 'Deposit — Wallet';
$activeTab = 'menu';
$hideTopNav = true;
$wallet = $data['wallet'] ?? null;
$paymentMethods = $data['paymentMethods'] ?? [];
$balance = $wallet ? (float)$wallet['balance'] : 0;
$currency = $wallet['currency'] ?? 'KES';
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .wd-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .wd-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 20px; }
    .wd-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .wd-title { font-size: 20px; font-weight: 700; flex: 1; }
    .wd-balance { background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(167,139,250,0.05)); border: 1px solid rgba(139,92,246,0.2); border-radius: 16px; padding: 18px; text-align: center; margin-bottom: 16px; }
    .wd-balance-label { font-size: 11px; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
    .wd-balance-amount { font-size: 32px; font-weight: 800; }
    .wd-amount-section { background: var(--bg-card); border-radius: 16px; padding: 18px; margin-bottom: 14px; border: 1px solid rgba(255,255,255,0.06); }
    .wd-amount-section h3 { font-size: 14px; font-weight: 700; margin: 0 0 12px; display: flex; align-items: center; gap: 8px; }
    .wd-preset-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 12px; }
    .wd-preset { padding: 12px; text-align: center; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 700; color: #94A3B8; transition: all 0.2s; }
    .wd-preset:hover, .wd-preset.active { border-color: var(--purple); color: white; background: rgba(139,92,246,0.1); }
    .wd-custom-input { width: 100%; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 12px 16px; color: white; font-size: 20px; font-weight: 700; outline: none; box-sizing: border-box; text-align: center; }
    .wd-custom-input:focus { border-color: var(--purple); }
    .wd-pm-section { background: var(--bg-card); border-radius: 16px; padding: 18px; margin-bottom: 14px; border: 1px solid rgba(255,255,255,0.06); }
    .wd-pm-section h3 { font-size: 14px; font-weight: 700; margin: 0 0 12px; }
    .wd-pm-item { display: flex; align-items: center; gap: 12px; padding: 12px; border: 2px solid rgba(255,255,255,0.06); border-radius: 12px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s; }
    .wd-pm-item.selected { border-color: var(--purple); background: rgba(139,92,246,0.06); }
    .wd-pm-radio { width: 18px; height: 18px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .wd-pm-item.selected .wd-pm-radio { border-color: var(--purple); }
    .wd-pm-item.selected .wd-pm-radio::after { content: ''; width: 8px; height: 8px; background: var(--purple); border-radius: 50%; }
    .wd-pm-icon { font-size: 22px; color: var(--purple); }
    .wd-pm-name { font-size: 14px; font-weight: 600; }
    .wd-pm-desc { font-size: 11px; color: #94A3B8; }
    .wd-submit { width: 100%; padding: 14px; border-radius: 14px; font-size: 16px; font-weight: 700; cursor: pointer; border: none; display: flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #8B5CF6, #A78BFA); color: white; }
    .wd-submit:disabled { opacity: 0.5; }
    .toast { position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
</style>

<div class="wd-page">
    <div class="wd-header">
        <button class="wd-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="wd-title">Deposit Funds</span>
    </div>

    <div class="wd-balance">
        <div class="wd-balance-label">Current Balance</div>
        <div class="wd-balance-amount"><?= $currency ?> <?= number_format($balance, 0) ?></div>
    </div>

    <!-- Amount -->
    <div class="wd-amount-section">
        <h3><span class="material-icons-round" style="color:var(--purple);">payments</span> Amount</h3>
        <div class="wd-preset-grid">
            <div class="wd-preset" onclick="setAmount(100)">KES 100</div>
            <div class="wd-preset" onclick="setAmount(500)">KES 500</div>
            <div class="wd-preset" onclick="setAmount(1000)">KES 1,000</div>
            <div class="wd-preset" onclick="setAmount(2500)">KES 2,500</div>
            <div class="wd-preset" onclick="setAmount(5000)">KES 5,000</div>
            <div class="wd-preset" onclick="setAmount(10000)">KES 10,000</div>
        </div>
        <input type="number" class="wd-custom-input" id="depositAmount" placeholder="Enter amount" value="100" min="10" oninput="clearPresets()" onfocus="clearPresets()">
    </div>

    <!-- Payment Method -->
    <div class="wd-pm-section">
        <h3>Payment Method</h3>
        <input type="hidden" id="selectedMethod" value="<?= $paymentMethods[0]['slug'] ?? 'mpesa' ?>">
        <?php if (empty($paymentMethods)): ?>
        <p style="color:#94A3B8;font-size:13px;">No methods available</p>
        <?php else: ?>
        <?php foreach ($paymentMethods as $pm): ?>
        <div class="wd-pm-item <?= $pm === reset($paymentMethods) ? 'selected' : '' ?>" data-slug="<?= $pm['slug'] ?>" onclick="selectMethod(this,'<?= $pm['slug'] ?>')">
            <div class="wd-pm-radio"></div>
            <span class="material-icons-round wd-pm-icon"><?= $pm['icon'] ?? 'payments' ?></span>
            <div style="flex:1;">
                <div class="wd-pm-name"><?= htmlspecialchars($pm['display_name']) ?></div>
                <div class="wd-pm-desc"><?= htmlspecialchars($pm['description'] ?? '') ?></div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <button class="wd-submit" id="depositBtn" onclick="submitDeposit()">
        <span class="material-icons-round">south_west</span> Deposit
    </button>
</div>

<!-- M-Pesa Phone Modal -->
<div class="wd-modal-overlay" id="mpesaModal">
    <div class="wd-modal">
        <button class="wd-modal-close" onclick="closeMpesaModal()"><span class="material-icons-round">close</span></button>
        <div style="width:52px;height:52px;border-radius:50%;background:rgba(34,197,94,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
            <span class="material-icons-round" style="font-size:26px;color:#22C55E;">phone_android</span>
        </div>
        <h3 style="font-size:17px;font-weight:700;margin-bottom:4px;">M-Pesa Deposit</h3>
        <p style="color:#94A3B8;font-size:13px;margin-bottom:16px;">Enter your M-Pesa number to receive the STK Push</p>
        <div style="display:flex;align-items:center;background:var(--bg-surface);border:1px solid rgba(255,255,255,0.1);border-radius:12px;overflow:hidden;margin-bottom:14px;">
            <span style="padding:12px 14px;color:#94A3B8;font-size:14px;font-weight:600;border-right:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.03);">+254</span>
            <input type="tel" id="mpesaPhone" placeholder="712 345 678" style="flex:1;background:transparent;border:none;outline:none;color:white;font-size:16px;padding:12px 14px;font-family:'Inter',sans-serif;">
        </div>
        <div style="display:flex;gap:10px;">
            <button onclick="closeMpesaModal()" style="flex:1;padding:12px;background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);border-radius:12px;color:#94A3B8;font-size:14px;font-weight:600;cursor:pointer;">Cancel</button>
            <button onclick="confirmMpesaDeposit()" style="flex:1;padding:12px;background:linear-gradient(135deg,#22C55E,#16A34A);border:none;border-radius:12px;color:white;font-size:14px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;"><span class="material-icons-round" style="font-size:18px;">send</span> Send STK</button>
        </div>
        <div id="mpesaModalStatus" style="text-align:center;padding:16px 0;display:none;"></div>
    </div>
</div>

<style>
    .wd-modal-overlay { position:fixed;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(4px);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity 0.25s; }
    .wd-modal-overlay.active { opacity:1;pointer-events:all; }
    .wd-modal { background:#151D2E;border-radius:20px;padding:24px 22px;max-width:360px;width:100%;text-align:center;border:1px solid rgba(255,255,255,0.06);position:relative;animation:wdSlide 0.25s ease; }
    @keyframes wdSlide { from{transform:translateY(20px);opacity:0;} to{transform:translateY(0);opacity:1;} }
    .wd-modal-close { position:absolute;top:10px;right:10px;width:30px;height:30px;border-radius:50%;background:rgba(255,255,255,0.06);border:none;color:#94A3B8;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px; }
</style>

<script>
var selectedAmount = 100;

function setAmount(val) {
    selectedAmount = val;
    document.getElementById('depositAmount').value = val;
    document.querySelectorAll('.wd-preset').forEach(function(p) { p.classList.remove('active'); });
    event.target.classList.add('active');
}
function clearPresets() {
    document.querySelectorAll('.wd-preset').forEach(function(p) { p.classList.remove('active'); });
    selectedAmount = parseInt(document.getElementById('depositAmount').value) || 0;
}
function selectMethod(el, slug) {
    document.querySelectorAll('.wd-pm-item').forEach(function(i) { i.classList.remove('selected'); });
    el.classList.add('selected');
    document.getElementById('selectedMethod').value = slug;
}

var _depositAmount = 0;
var _depositMethod = '';

function submitDeposit() {
    var amount = parseInt(document.getElementById('depositAmount').value) || 0;
    if (amount < 10) { showToast('Minimum deposit is KES 10', true); return; }
    _depositAmount = amount;
    _depositMethod = document.getElementById('selectedMethod').value;

    if (_depositMethod === 'mpesa') {
        document.getElementById('mpesaModal').classList.add('active');
    } else {
        doDeposit();
    }
}

function closeMpesaModal() {
    document.getElementById('mpesaModal').classList.remove('active');
    document.getElementById('depositBtn').disabled = false;
    document.getElementById('depositBtn').innerHTML = '<span class="material-icons-round">south_west</span> Deposit';
}

function confirmMpesaDeposit() {
    var phone = document.getElementById('mpesaPhone').value.replace(/[^0-9]/g, '');
    if (!phone || phone.length < 9) { showToast('Enter a valid phone number', true); return; }
    if (!/^(0?7|2547|\+2547)/.test('254' + phone.replace(/^0/,''))) { showToast('Must be a Kenyan number', true); return; }

    var statusEl = document.getElementById('mpesaModalStatus');
    statusEl.style.display = 'block';
    statusEl.innerHTML = '<span class="material-icons-round" style="font-size:36px;animation:spin 1s linear infinite;">sync</span><p style="margin-top:8px;font-weight:600;">Sending STK Push...</p>';

    var btn = document.getElementById('depositBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round" style="animation:spin 0.8s linear infinite;">sync</span> Processing...';

    fetch('/wallet/mpesa/stk', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ phone: '254' + phone.replace(/^0/, ''), amount: _depositAmount })
    }).then(r => r.json()).then(function(d) {
        if (d.error) { statusEl.innerHTML = '<span class="material-icons-round" style="font-size:36px;color:#EF4444;">error</span><p style="color:#EF4444;">' + d.error + '</p>'; btn.disabled = false; btn.innerHTML = '<span class="material-icons-round">south_west</span> Deposit'; return; }
        statusEl.innerHTML = '<span class="material-icons-round" style="font-size:40px;color:#22C55E;">check_circle</span><p>STK Push sent!</p><p style="font-size:12px;color:#94A3B8;">Check your phone and enter PIN</p>';
        setTimeout(function() { window.location.href = '/wallet'; }, 4000);
    }).catch(function() { showToast('Error', true); closeMpesaModal(); });
}

function doDeposit() {
    var btn = document.getElementById('depositBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round" style="animation:spin 0.8s linear infinite;">sync</span> Processing...';

    fetch('/wallet/deposit', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ amount: _depositAmount, method: _depositMethod })
    }).then(r => r.json()).then(function(d) {
        if (d.error) { showToast(d.error, true); btn.disabled = false; btn.innerHTML = '<span class="material-icons-round">south_west</span> Deposit'; return; }
        showToast('Deposit successful! ' + d.amount + ' ' + (d.currency || 'KES'), false);
        setTimeout(function() { window.location.href = '/wallet'; }, 1500);
    }).catch(function() { showToast('Error', true); btn.disabled = false; btn.innerHTML = '<span class="material-icons-round">south_west</span> Deposit'; });
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
