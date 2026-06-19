<?php $title = 'Wallet - DTTube'; ?>
<?php
$wallet = $data['wallet'] ?? null;
$transactions = $data['transactions'] ?? [];
$balance = $wallet ? (float)$wallet['balance'] : 0;
$currency = $wallet['currency'] ?? 'KES';
?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4 pb-24 lg:pb-6">
    <div class="flex items-center gap-3 mb-6">
        <a href="/menu" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">Wallet</h1>
    </div>

    <div class="bg-gradient-to-br from-amber-600/30 to-yellow-700/20 rounded-2xl border border-amber-500/20 p-5 mb-4">
        <p class="text-zinc-400 text-xs mb-1">Available Balance</p>
        <h2 class="font-display text-4xl font-bold text-white mb-4"><?= $currency ?> <?= number_format($balance, 2) ?></h2>
        <div class="flex gap-3">
            <a href="/wallet/deposit" class="flex-1 gradient-brand py-3 rounded-xl text-white text-sm font-semibold hover:opacity-90 transition-opacity text-center" style="text-decoration:none;">Deposit</a>
            <a href="/wallet/withdraw" class="flex-1 bg-surface-200 py-3 rounded-xl text-white text-sm font-semibold hover:bg-surface-300 transition-colors text-center" style="text-decoration:none;">Withdraw</a>
        </div>
    </div>

    <div class="mb-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-white text-xs font-bold">Payment Methods</h3>
        </div>
        <div class="space-y-2">
            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-surface-100/60 border border-surface-400/15">
                <div class="w-10 h-10 rounded-xl bg-green-600/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-green-400">phone_android</span>
                </div>
                <div class="flex-1">
                    <h4 class="text-white text-sm font-semibold">M-Pesa</h4>
                    <p class="text-zinc-500 text-[10px]">Instant deposits and withdrawals</p>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-medium">Connected</span>
            </div>
            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-surface-100/60 border border-surface-400/15 opacity-50">
                <div class="w-10 h-10 rounded-xl bg-blue-600/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-blue-400">credit_card</span>
                </div>
                <div class="flex-1">
                    <h4 class="text-white text-sm font-semibold">Bank Transfer</h4>
                    <p class="text-zinc-500 text-[10px]">1-3 business days</p>
                </div>
                <button class="px-3 py-1 rounded-full bg-surface-200 text-zinc-400 text-[10px] font-medium">Connect</button>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-3">
        <h3 class="text-white text-xs font-bold">Recent Transactions</h3>
        <a href="/wallet/transactions" class="text-brand-400 text-[10px] font-semibold">View All</a>
    </div>

    <?php if (!empty($transactions)): ?>
    <div class="space-y-2">
        <?php foreach ($transactions as $tx): ?>
        <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-100/30 border border-surface-400/10">
            <div class="w-9 h-9 rounded-full <?= $tx['type'] === 'deposit' ? 'bg-emerald-500/20' : 'bg-red-500/20' ?> flex items-center justify-center flex-shrink-0">
                <span class="material-icons-round <?= $tx['type'] === 'deposit' ? 'text-emerald-400' : 'text-red-400' ?> text-lg">
                    <?= $tx['type'] === 'deposit' ? 'arrow_downward' : 'arrow_upward' ?>
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold"><?= htmlspecialchars(ucfirst($tx['type'])) ?></p>
                <p class="text-zinc-500 text-[10px] truncate"><?= htmlspecialchars($tx['description'] ?? '') ?></p>
            </div>
            <div class="text-right">
                <span class="block <?= $tx['type'] === 'deposit' ? 'text-emerald-400' : 'text-red-400' ?> text-xs font-bold">
                    <?= $tx['type'] === 'deposit' ? '+' : '-' ?><?= $currency ?> <?= number_format((float)$tx['amount'], 2) ?>
                </span>
                <span class="text-zinc-600 text-[9px]"><?= timeAgo($tx['created_at']) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-8 bg-surface-100/30 rounded-xl border border-surface-400/10">
        <span class="material-icons-round text-zinc-500 text-3xl">receipt_long</span>
        <p class="text-zinc-500 text-xs mt-1">No transactions yet</p>
    </div>
    <?php endif; ?>
</div>

<div id="depositModal" class="fixed inset-0 z-50 hidden" onclick="closeModal(event, 'depositModal')">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-surface-50 rounded-t-3xl p-5 max-w-lg mx-auto slide-up" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-white font-bold text-lg">Deposit Funds</h3>
            <button onclick="closeModal(event, 'depositModal')" class="w-8 h-8 rounded-full bg-surface-200 flex items-center justify-center"><span class="material-icons-round text-zinc-400">close</span></button>
        </div>
        <div class="flex gap-2 mb-4">
            <?php foreach ([100, 500, 1000, 5000] as $amt): ?>
            <button onclick="setAmount(<?= $amt ?>)" class="flex-1 py-2.5 rounded-xl bg-surface-200 text-zinc-300 text-sm font-semibold hover:bg-brand-600/30 hover:text-brand-300 border border-surface-400/20 hover:border-brand-500/50 transition-all"><?= $currency ?> <?= $amt ?></button>
            <?php endforeach; ?>
        </div>
        <input type="number" id="depositAmount" placeholder="Enter amount" class="w-full bg-surface-200 text-white px-4 py-3 rounded-xl border border-surface-400/30 focus:border-brand-500 focus:outline-none text-sm mb-4" min="10">
        <button onclick="processDeposit()" class="w-full gradient-brand py-3 rounded-xl text-white font-semibold">Deposit via M-Pesa</button>
    </div>
</div>

<div id="withdrawModal" class="fixed inset-0 z-50 hidden" onclick="closeModal(event, 'withdrawModal')">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-surface-50 rounded-t-3xl p-5 max-w-lg mx-auto slide-up" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-white font-bold text-lg">Withdraw Funds</h3>
            <button onclick="closeModal(event, 'withdrawModal')" class="w-8 h-8 rounded-full bg-surface-200 flex items-center justify-center"><span class="material-icons-round text-zinc-400">close</span></button>
        </div>
        <div class="mb-3">
            <label class="text-zinc-400 text-xs mb-1 block">Amount</label>
            <input type="number" id="withdrawAmount" placeholder="Enter amount to withdraw" class="w-full bg-surface-200 text-white px-4 py-3 rounded-xl border border-surface-400/30 focus:border-brand-500 focus:outline-none text-sm" min="50">
        </div>
        <div class="mb-4">
            <label class="text-zinc-400 text-xs mb-1 block">M-Pesa Phone Number</label>
            <input type="tel" id="withdrawPhone" placeholder="e.g. 0712345678" class="w-full bg-surface-200 text-white px-4 py-3 rounded-xl border border-surface-400/30 focus:border-brand-500 focus:outline-none text-sm">
        </div>
        <button onclick="processWithdraw()" class="w-full bg-red-500 py-3 rounded-xl text-white font-semibold hover:bg-red-600 transition-colors">Withdraw to M-Pesa</button>
    </div>
</div>

<style>
.slide-up { animation: slideUp 0.3s ease-out forwards; }
@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
</style>

<script>
function showDeposit() { document.getElementById('depositModal').classList.remove('hidden'); }
function showWithdraw() { document.getElementById('withdrawModal').classList.remove('hidden'); }
function closeModal(e, id) { if (e.target === e.currentTarget || e.type === 'click') document.getElementById(id).classList.add('hidden'); }
function setAmount(amt) { document.getElementById('depositAmount').value = amt; }

function processDeposit() {
    const amount = document.getElementById('depositAmount').value;
    if (!amount || parseFloat(amount) < 10) { alert('Please enter a valid amount (minimum <?= $currency ?> 10)'); return; }
    fetch('/wallet/deposit', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ amount: parseFloat(amount), method: 'mpesa' })
    }).then(r => r.json()).then(d => { alert('Deposit successful! <?= $currency ?> ' + d.amount + ' added.'); location.reload(); }).catch(() => { alert('Deposit failed. Please try again.'); });
}

function processWithdraw() {
    const amount = document.getElementById('withdrawAmount').value;
    const phone = document.getElementById('withdrawPhone').value;
    if (!amount || parseFloat(amount) < 50) { alert('Please enter a valid amount (minimum <?= $currency ?> 50)'); return; }
    if (!phone) { alert('Please enter your M-Pesa phone number'); return; }
    fetch('/wallet/withdraw', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ amount: parseFloat(amount), phone: phone })
    }).then(r => r.json()).then(d => { alert('Withdrawal initiated! <?= $currency ?> ' + d.amount + ' will be sent to ' + phone); location.reload(); }).catch(() => { alert('Withdrawal failed. Please try again.'); });
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
