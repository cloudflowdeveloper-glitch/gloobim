<?php $title = ($data['item']['title'] ?? 'Item') . ' - DTTube Market'; ?>
<?php
$item = $data['item'] ?? [];
$moreItems = $data['moreItems'] ?? [];
$user = \Core\Auth::user();
$isOwner = $user && $item && (int)$user['id'] === (int)($item['user_id'] ?? 0);
$price = (float)($item['price'] ?? 0);
$currency = $item['currency'] ?? 'KES';
$rating = (float)($item['rating'] ?? 0);
$ratingFull = floor($rating);
$ratingHalf = ($rating - $ratingFull) >= 0.5;
?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4 pb-24 lg:pb-6">
    <div class="flex items-center gap-3 mb-4">
        <a href="/market" onclick="history.back(); return false;" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white truncate"><?= htmlspecialchars($item['title'] ?? '') ?></h1>
    </div>

    <?php if ($item): ?>
    <div class="rounded-2xl overflow-hidden mb-4 bg-surface-200">
        <div class="aspect-[16/9] relative">
            <img src="<?= $item['thumbnail'] ?? 'https://placehold.co/600x338/3f3f46/ffffff?text=No+Image' ?>" alt="" class="w-full h-full object-cover">
            <div class="absolute top-3 left-3 flex gap-1.5">
                <?php if ($item['type'] === 'digital'): ?>
                <span class="px-2.5 py-1 rounded-full bg-purple-500/80 text-white text-[10px] font-medium">Digital Product</span>
                <?php else: ?>
                <span class="px-2.5 py-1 rounded-full bg-blue-500/80 text-white text-[10px] font-medium">Service</span>
                <?php endif; ?>
                <?php if (!empty($item['category'])): ?>
                <span class="px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-sm text-white text-[10px] font-medium"><?= htmlspecialchars($item['category']) ?></span>
                <?php endif; ?>
            </div>
            <?php if (!empty($item['preview_url'])): ?>
            <div class="absolute bottom-3 right-3">
                <a href="<?= htmlspecialchars($item['preview_url']) ?>" target="_blank" class="px-3 py-1.5 rounded-lg bg-black/70 backdrop-blur-sm text-white text-[10px] font-medium flex items-center gap-1 hover:bg-black/90 transition-colors">
                    <span class="material-icons-round text-sm">play_arrow</span>
                    Preview
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex items-start justify-between mb-3">
        <div>
            <div class="flex items-center gap-2 mb-0.5">
                <h2 class="text-white text-lg font-bold"><?= htmlspecialchars($item['title'] ?? '') ?></h2>
                <?php if (!empty($item['is_featured'])): ?>
                <span class="px-2 py-0.5 rounded bg-amber-500/20 text-amber-400 text-[8px] font-semibold">FEATURED</span>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="material-icons-round text-amber-400 text-sm"><?= $i <= $ratingFull ? 'star' : ($i === $ratingFull + 1 && $ratingHalf ? 'star_half' : 'star_outline') ?></span>
                    <?php endfor; ?>
                </div>
                <span class="text-zinc-400 text-xs font-semibold"><?= number_format($rating, 1) ?></span>
                <span class="text-zinc-600">·</span>
                <span class="text-zinc-500 text-[10px]"><?= formatCount($item['orders_count'] ?? 0) ?> orders</span>
                <span class="text-zinc-600">·</span>
                <span class="text-zinc-500 text-[10px]"><?= formatCount($item['views'] ?? 0) ?> views</span>
            </div>
        </div>
        <span class="text-amber-400 text-xl font-bold flex-shrink-0"><?= htmlspecialchars($currency) ?> <?= number_format($price) ?></span>
    </div>

    <div class="flex items-center gap-3 mb-4 p-3 rounded-xl bg-surface-100/40 border border-surface-400/10">
        <img src="<?= $item['seller_avatar'] ?? 'https://placehold.co/40x40/6d28d9/ffffff?text=S' ?>" alt="" class="w-10 h-10 rounded-full">
        <div class="flex-1">
            <div class="flex items-center gap-1">
                <span class="text-white text-sm font-semibold"><?= htmlspecialchars($item['seller_name'] ?? $item['username'] ?? '') ?></span>
                <?php if (!empty($item['is_verified'])): ?>
                <span class="material-icons-round text-brand-400 text-sm">verified</span>
                <?php endif; ?>
            </div>
            <span class="text-zinc-500 text-[10px]">@<?= htmlspecialchars($item['username'] ?? '') ?></span>
        </div>
        <?php if (!$isOwner && $user): ?>
        <a href="/messages" class="px-3 py-1.5 rounded-lg bg-surface-200 text-zinc-300 text-xs font-semibold hover:bg-surface-300 transition-colors">Message</a>
        <?php endif; ?>
    </div>

    <?php if (!empty($item['description'])): ?>
    <div class="mb-5">
        <h3 class="text-white text-xs font-bold mb-2">Description</h3>
        <p class="text-zinc-300 text-xs leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($item['description']) ?></p>
    </div>
    <?php endif; ?>

    <div class="mb-5">
        <h3 class="text-white text-xs font-bold mb-2">Details</h3>
        <div class="bg-surface-100/30 rounded-xl border border-surface-400/10 overflow-hidden">
            <div class="flex items-center justify-between p-3 border-b border-surface-400/10">
                <span class="text-zinc-500 text-xs">Type</span>
                <span class="text-white text-xs font-semibold"><?= $item['type'] === 'digital' ? 'Digital Product' : 'Service' ?></span>
            </div>
            <div class="flex items-center justify-between p-3 border-b border-surface-400/10">
                <span class="text-zinc-500 text-xs">Category</span>
                <span class="text-white text-xs font-semibold"><?= htmlspecialchars($item['category'] ?? 'N/A') ?></span>
            </div>
            <?php if (!empty($item['delivery_time'])): ?>
            <div class="flex items-center justify-between p-3 border-b border-surface-400/10">
                <span class="text-zinc-500 text-xs">Delivery</span>
                <span class="text-white text-xs font-semibold"><?= htmlspecialchars($item['delivery_time']) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($item['type'] === 'digital'): ?>
            <div class="flex items-center justify-between p-3 border-b border-surface-400/10">
                <span class="text-zinc-500 text-xs">Downloads</span>
                <span class="text-white text-xs font-semibold"><?= formatCount($item['downloads'] ?? 0) ?></span>
            </div>
            <?php endif; ?>
            <div class="flex items-center justify-between p-3">
                <span class="text-zinc-500 text-xs">Listed</span>
                <span class="text-white text-xs font-semibold"><?= isset($item['created_at']) ? timeAgo($item['created_at']) : 'Recently' ?></span>
            </div>
        </div>
    </div>

    <?php if (!empty($item['requirements'])): ?>
    <div class="mb-5">
        <h3 class="text-white text-xs font-bold mb-2">Requirements</h3>
        <div class="bg-surface-100/30 rounded-xl border border-surface-400/10 p-3">
            <p class="text-zinc-300 text-xs leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($item['requirements']) ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($isOwner): ?>
    <div class="flex gap-3">
        <a href="/market/<?= $item['id'] ?>/edit" class="flex-1 py-2.5 rounded-xl bg-surface-200 text-white text-sm font-semibold text-center hover:bg-surface-300 transition-colors">Edit Item</a>
        <button onclick="toggleStatus(<?= $item['id'] ?>)" class="flex-1 py-2.5 rounded-xl bg-amber-600/20 text-amber-400 text-sm font-semibold hover:bg-amber-600/30 transition-colors">
            <?= ($item['status'] ?? 'active') === 'active' ? 'Deactivate' : 'Activate' ?>
        </button>
        <button onclick="deleteItem(<?= $item['id'] ?>)" class="px-4 py-2.5 rounded-xl bg-red-600/20 text-red-400 text-sm font-semibold hover:bg-red-600/30 transition-colors">
            <span class="material-icons-round text-lg">delete</span>
        </button>
    </div>
    <?php elseif ($user): ?>
    <button onclick="purchaseItem(<?= $item['id'] ?>)" class="w-full py-3 rounded-xl gradient-brand text-white text-sm font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2">
        <?php if ($item['type'] === 'digital'): ?>
        <span class="material-icons-round text-lg">shopping_cart</span>
        Buy Now - <?= htmlspecialchars($currency) ?> <?= number_format($price) ?>
        <?php else: ?>
        <span class="material-icons-round text-lg">handyman</span>
        Order Service - <?= htmlspecialchars($currency) ?> <?= number_format($price) ?>
        <?php endif; ?>
    </button>
    <?php else: ?>
    <a href="/login" class="block w-full py-2.5 rounded-xl gradient-brand text-white text-sm font-semibold text-center hover:opacity-90 transition-opacity">Sign in to Purchase</a>
    <?php endif; ?>

    <?php if (!empty($moreItems)): ?>
    <div class="mt-8">
        <h3 class="text-white text-xs font-bold mb-3">More <?= $item['type'] === 'digital' ? 'Digital Products' : 'Services' ?></h3>
        <div class="grid grid-cols-2 gap-3">
            <?php foreach ($moreItems as $mi): ?>
            <a href="/market/<?= $mi['id'] ?>" class="block bg-surface-100/40 rounded-2xl border border-surface-400/10 hover:border-amber-500/30 hover:-translate-y-0.5 transition-all group overflow-hidden">
                <div class="relative aspect-[4/3] bg-surface-200 overflow-hidden">
                    <img src="<?= $mi['thumbnail'] ?? 'https://placehold.co/400x300/3f3f46/ffffff?text=Item' ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-full bg-black/60 backdrop-blur-sm text-white text-[8px] font-medium">
                        <?= htmlspecialchars($mi['category'] ?? '') ?>
                    </span>
                </div>
                <div class="p-2.5">
                    <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($mi['title'] ?? '') ?></p>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-amber-400 text-sm font-bold"><?= htmlspecialchars($mi['currency'] ?? 'KES') ?> <?= number_format((float)($mi['price'] ?? 0)) ?></span>
                        <span class="text-zinc-500 text-[9px]"><?= formatCount($mi['orders_count'] ?? 0) ?> sold</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php else: ?>
    <div class="text-center py-16">
        <span class="material-icons-round text-zinc-500 text-5xl">sell</span>
        <h3 class="text-white font-semibold text-sm mt-3">Item not found</h3>
        <p class="text-zinc-500 text-xs mt-1">This item may have been removed or doesn't exist</p>
        <a href="/market" class="inline-block mt-4 gradient-brand px-5 py-2 rounded-full text-white text-xs font-semibold">Browse Market</a>
    </div>
    <?php endif; ?>
</div>

<script>
function purchaseItem(id) {
    alert('Order placed! The seller will contact you shortly.');
}

function toggleStatus(id) {
    fetch('/market/' + id + '/toggle', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json()).then(d => {
        if (d.status) {
            alert('Item ' + d.status);
            location.reload();
        } else {
            alert(d.error || 'Error toggling status');
        }
    }).catch(() => alert('Network error'));
}

function deleteItem(id) {
    if (!confirm('Delete this item permanently?')) return;
    fetch('/market/' + id, {
        method: 'DELETE',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json()).then(d => {
        alert(d.message);
        window.location.href = '/market/my';
    }).catch(() => alert('Error deleting item'));
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
