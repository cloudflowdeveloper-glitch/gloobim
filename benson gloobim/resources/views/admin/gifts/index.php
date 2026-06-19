<?php $gifts = $data['gifts'] ?? []; $title = 'Manage Gifts - DTTube Admin'; ?>
<?php ob_start(); ?>
<div class="max-w-2xl mx-auto px-3 py-4 pb-24">
    <div class="flex items-center gap-3 mb-5">
        <a href="/menu" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <div class="flex-1">
            <h1 class="font-display text-lg font-bold text-white">Manage Gifts</h1>
            <p class="text-zinc-500 text-xs">Create and manage livestream gifts</p>
        </div>
        <a href="/admin/gifts/create" class="flex items-center gap-1.5 gradient-brand px-4 py-2 rounded-xl text-white text-xs font-bold hover:opacity-90 transition-opacity">
            <span class="material-icons-round text-lg">add</span>
            New Gift
        </a>
    </div>

    <?php if (empty($gifts)): ?>
    <div class="text-center py-16">
        <span class="material-icons-round text-5xl text-surface-400 mb-3">card_giftcard</span>
        <p class="text-zinc-500 text-sm">No gifts yet</p>
        <a href="/admin/gifts/create" class="inline-block mt-3 gradient-brand px-5 py-2.5 rounded-xl text-white text-sm font-semibold">
            Create Your First Gift
        </a>
    </div>
    <?php else: ?>
    <div class="space-y-2">
        <?php foreach ($gifts as $g): ?>
        <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-100 border border-surface-400/20 hover:border-brand-500/30 transition-all">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-surface-200 flex items-center justify-center overflow-hidden">
                <?php if (!empty($g['image_url'])): ?>
                <img src="<?= $g['image_url'] ?>" alt="<?= $g['name'] ?>" class="w-full h-full object-cover">
                <?php else: ?>
                <span class="material-icons-round <?= $g['color_class'] ?? 'text-amber-400' ?> text-2xl"><?= $g['icon'] ?? 'card_giftcard' ?></span>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                    <span class="text-white text-sm font-semibold"><?= $g['name'] ?></span>
                    <?php if (!empty($g['is_animated'])): ?>
                    <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-purple-500/20 text-purple-400 font-medium">Animated</span>
                    <?php endif; ?>
                    <?php if ($g['is_active']): ?>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pulse-live"></span>
                    <?php else: ?>
                    <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-zinc-400 text-[11px]">$<?= number_format((float)$g['price_usd'], 2) ?> USD</span>
                    <span class="text-zinc-600 text-[11px]">|</span>
                    <span class="text-zinc-500 text-[11px]">Sort: <?= (int)$g['sort_order'] ?></span>
                    <?php if (!empty($g['description'])): ?>
                    <span class="text-zinc-600 text-[11px]">|</span>
                    <span class="text-zinc-500 text-[11px] truncate"><?= $g['description'] ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <a href="/admin/gifts/<?= $g['id'] ?>/edit" class="p-2 rounded-lg bg-surface-200 hover:bg-surface-300 transition-colors" title="Edit">
                    <span class="material-icons-round text-zinc-400 text-lg">edit</span>
                </a>
                <button onclick="deleteGift(<?= $g['id'] ?>, '<?= addslashes($g['name']) ?>')" class="p-2 rounded-lg bg-surface-200 hover:bg-red-500/20 transition-colors" title="Delete">
                    <span class="material-icons-round text-zinc-400 text-lg">delete</span>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function deleteGift(id, name) {
    if (!confirm('Delete "' + name + '"? This cannot be undone.')) return;

    fetch('/admin/gifts/' + id, {
        method: 'DELETE',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(function(r) { return r.json(); }).then(function(d) {
        if (d.success) {
            location.reload();
        } else {
            alert(d.error || 'Error deleting gift');
        }
    }).catch(function() { location.reload(); });
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../../layouts/app.php'; ?>
