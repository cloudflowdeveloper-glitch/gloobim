<?php $title = 'Transactions - DTTube'; ?>
<?php $transactions = $data['transactions'] ?? []; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4">
    <div class="flex items-center gap-3 mb-6">
        <a href="/wallet" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">Transaction History</h1>
    </div>

    <?php if (!empty($transactions)): ?>
    <div class="space-y-2">
        <?php foreach ($transactions as $tx): ?>
        <div class="flex items-center gap-3 p-3.5 rounded-xl bg-surface-100/30 border border-surface-400/10 hover:border-surface-400/30 transition-all">
            <div class="w-10 h-10 rounded-full <?= $tx['type'] === 'deposit' ? 'bg-emerald-500/20' : ($tx['type'] === 'withdrawal' ? 'bg-red-500/20' : 'bg-brand-500/20') ?> flex items-center justify-center flex-shrink-0">
                <span class="material-icons-round <?= $tx['type'] === 'deposit' ? 'text-emerald-400' : ($tx['type'] === 'withdrawal' ? 'text-red-400' : 'text-brand-400') ?> text-xl">
                    <?= $tx['type'] === 'deposit' ? 'arrow_circle_down' : ($tx['type'] === 'withdrawal' ? 'arrow_circle_up' : 'receipt') ?>
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold"><?= htmlspecialchars(ucfirst($tx['type'])) ?></p>
                <p class="text-zinc-500 text-[10px] truncate"><?= htmlspecialchars($tx['description'] ?? '') ?></p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-zinc-600 text-[9px]"><?= $tx['reference'] ?? '' ?></span>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-600 text-[9px]"><?= isset($tx['created_at']) ? date('M d, H:i', strtotime($tx['created_at'])) : '' ?></span>
                </div>
            </div>
            <div class="text-right">
                <span class="block <?= $tx['type'] === 'deposit' ? 'text-emerald-400' : ($tx['type'] === 'withdrawal' ? 'text-red-400' : 'text-brand-400') ?> text-sm font-bold">
                    <?= $tx['type'] === 'deposit' ? '+' : '-' ?><?= $tx['currency'] ?? 'KES' ?> <?= number_format((float)$tx['amount'], 2) ?>
                </span>
                <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[9px] font-medium <?= $tx['status'] === 'completed' ? 'bg-emerald-500/20 text-emerald-400' : ($tx['status'] === 'pending' ? 'bg-amber-500/20 text-amber-400' : 'bg-red-500/20 text-red-400') ?>">
                    <?= ucfirst($tx['status'] ?? 'completed') ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16 bg-surface-100/30 rounded-2xl border border-surface-400/10">
        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-surface-200/80 flex items-center justify-center">
            <span class="material-icons-round text-surface-400 text-3xl">receipt_long</span>
        </div>
        <h3 class="text-white font-semibold text-sm mb-1">No transactions</h3>
        <p class="text-zinc-500 text-xs">Your deposit and withdrawal history will appear here</p>
        <a href="/wallet" class="inline-block mt-4 gradient-brand px-5 py-2 rounded-full text-white text-xs font-semibold">Go to Wallet</a>
    </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
