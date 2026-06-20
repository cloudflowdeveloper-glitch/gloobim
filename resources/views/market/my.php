<?php $activeTab = 'menu'; $title = 'My Items - DTTube Market'; ?>
<?php $items = $data['items'] ?? []; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4">
    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <a href="/market" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
                <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
            </a>
            <h1 class="font-display text-lg font-bold text-white">My Items</h1>
        </div>
        <a href="/market/create" class="w-9 h-9 rounded-full gradient-brand flex items-center justify-center hover:opacity-90 transition-opacity">
            <span class="material-icons-round text-white text-lg">add</span>
        </a>
    </div>

    <div class="flex gap-2 mb-4">
        <a href="/market/my" class="px-3 py-1.5 rounded-full gradient-brand text-white text-xs font-semibold">All</a>
        <a href="/market/my?filter=digital" class="px-3 py-1.5 rounded-full bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white transition-colors">Digital</a>
        <a href="/market/my?filter=service" class="px-3 py-1.5 rounded-full bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white transition-colors">Services</a>
        <a href="/market/my?filter=inactive" class="px-3 py-1.5 rounded-full bg-surface-200 text-zinc-400 text-xs font-semibold hover:text-white transition-colors">Inactive</a>
    </div>

    <?php if (!empty($items)): ?>
    <div class="space-y-2">
        <?php foreach ($items as $item): ?>
        <a href="/market/<?= $item['id'] ?>" class="flex items-center gap-3 p-3.5 rounded-xl bg-surface-100/30 border border-surface-400/10 hover:border-amber-500/30 hover:-translate-y-0.5 transition-all">
            <img src="<?= $item['thumbnail'] ?? '/uploads/profiles/admin.jpg' ?>" alt="" class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($item['title'] ?? '') ?></p>
                    <?php if ($item['type'] === 'digital'): ?>
                    <span class="px-1.5 py-0.5 rounded bg-purple-500/20 text-purple-400 text-[8px] font-medium flex-shrink-0">DIGITAL</span>
                    <?php else: ?>
                    <span class="px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-400 text-[8px] font-medium flex-shrink-0">SERVICE</span>
                    <?php endif; ?>
                    <?php if (($item['status'] ?? 'active') !== 'active'): ?>
                    <span class="px-1.5 py-0.5 rounded bg-zinc-500/20 text-zinc-400 text-[8px] font-medium flex-shrink-0">INACTIVE</span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-amber-400 text-sm font-bold"><?= htmlspecialchars($item['currency'] ?? 'KES') ?> <?= number_format((float)($item['price'] ?? 0)) ?></span>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-500 text-[10px]"><?= formatCount($item['views'] ?? 0) ?> views</span>
                    <?php if ($item['type'] === 'digital'): ?>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-500 text-[10px]"><?= formatCount($item['downloads'] ?? 0) ?> downloads</span>
                    <?php else: ?>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-500 text-[10px]"><?= formatCount($item['orders_count'] ?? 0) ?> orders</span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-zinc-600 text-[9px]"><?= htmlspecialchars($item['category'] ?? '') ?></span>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-600 text-[9px]"><?= isset($item['created_at']) ? date('M d, Y', strtotime($item['created_at'])) : '' ?></span>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <a href="/market/<?= $item['id'] ?>/edit" class="w-7 h-7 rounded-lg bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
                    <span class="material-icons-round text-zinc-400 text-sm">edit</span>
                </a>
                <button onclick="event.preventDefault(); event.stopPropagation(); deleteItem(<?= $item['id'] ?>)" class="w-7 h-7 rounded-lg bg-red-500/20 flex items-center justify-center hover:bg-red-500/30 transition-colors">
                    <span class="material-icons-round text-red-400 text-sm">delete</span>
                </button>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16 bg-surface-100/30 rounded-2xl border border-surface-400/10">
        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-surface-200/80 flex items-center justify-center">
            <span class="material-icons-round text-surface-400 text-3xl">sell</span>
        </div>
        <h3 class="text-white font-semibold text-sm mb-1">No items yet</h3>
        <p class="text-zinc-500 text-xs mb-4">Start selling your digital products or services on the Market!</p>
        <a href="/market/create" class="inline-flex items-center gap-1.5 gradient-brand px-5 py-2 rounded-full text-white text-xs font-semibold">
            <span class="material-icons-round text-lg">add</span>
            Sell on Market
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
function deleteItem(id) {
    if (!confirm('Delete this item permanently?')) return;
    fetch('/market/' + id, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(d => { location.reload(); })
        .catch(() => alert('Error deleting item'));
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
