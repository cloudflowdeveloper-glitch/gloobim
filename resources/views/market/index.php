<?php $activeTab = 'menu'; $title = 'Market - Digital Products & Services - DTTube'; ?>
<?php
$items = $data['items'] ?? [];
$categories = $data['categories'] ?? [];
$userItems = $data['userItems'] ?? [];
$activeType = $data['activeType'] ?? '';
$activeCategory = $data['activeCategory'] ?? '';
$search = $data['search'] ?? '';
$user = \Core\Auth::user();
?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4 pb-24 lg:pb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <span class="material-icons-round text-amber-400 text-2xl">sell</span>
            <h1 class="font-display text-xl font-bold text-white">Market</h1>
        </div>
        <div class="flex items-center gap-1.5">
            <a href="/market" class="px-2.5 py-1.5 rounded-lg bg-surface-200 text-zinc-400 text-[10px] font-semibold hover:text-white transition-colors flex items-center gap-1">
                <span class="material-icons-round text-sm">grid_view</span>
                All
            </a>
            <a href="/market/create" class="w-9 h-9 rounded-full gradient-brand flex items-center justify-center hover:opacity-90 transition-opacity">
                <span class="material-icons-round text-white text-lg">add</span>
            </a>
        </div>
    </div>

    <form method="GET" action="/market" class="relative mb-4">
        <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-lg">search</span>
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search digital products & services..." class="w-full bg-surface-200/80 text-white pl-9 pr-4 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm placeholder:text-zinc-600 transition-all">
        <?php if ($activeType): ?>
        <input type="hidden" name="type" value="<?= htmlspecialchars($activeType) ?>">
        <?php endif; ?>
    </form>

    <div class="flex gap-2 mb-4 overflow-x-auto scrollbar-hide pb-1">
        <a href="/market<?= $search ? '?search='.urlencode($search) : '' ?>" class="flex-shrink-0 px-3.5 py-1.5 rounded-full <?= empty($activeType) ? 'gradient-brand text-white' : 'bg-surface-200 text-zinc-400 hover:text-white' ?> text-xs font-semibold transition-all">
            All Items
        </a>
        <a href="/market?type=digital<?= $search ? '&search='.urlencode($search) : '' ?>" class="flex-shrink-0 px-3.5 py-1.5 rounded-full <?= $activeType === 'digital' ? 'gradient-brand text-white' : 'bg-surface-200 text-zinc-400 hover:text-white' ?> text-xs font-semibold transition-all flex items-center gap-1">
            <span class="material-icons-round text-sm">download</span>
            Digital
        </a>
        <a href="/market?type=service<?= $search ? '&search='.urlencode($search) : '' ?>" class="flex-shrink-0 px-3.5 py-1.5 rounded-full <?= $activeType === 'service' ? 'gradient-brand text-white' : 'bg-surface-200 text-zinc-400 hover:text-white' ?> text-xs font-semibold transition-all flex items-center gap-1">
            <span class="material-icons-round text-sm">handyman</span>
            Services
        </a>
    </div>

    <?php if (!empty($categories)): ?>
    <div class="flex gap-2 mb-5 overflow-x-auto scrollbar-hide pb-1">
        <?php foreach ($categories as $cat): ?>
        <a href="/market?<?= $activeType ? 'type='.urlencode($activeType).'&' : '' ?>category=<?= urlencode($cat['category']) ?><?= $search ? '&search='.urlencode($search) : '' ?>" class="flex-shrink-0 px-3 py-1.5 rounded-lg <?= $activeCategory === $cat['category'] ? 'bg-brand-500/20 text-brand-300 border border-brand-500/30' : 'bg-surface-200/60 text-zinc-400 border border-surface-400/10 hover:text-white' ?> text-[10px] font-semibold transition-all">
            <?= htmlspecialchars($cat['category']) ?> (<?= $cat['count'] ?>)
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($items)): ?>
    <div class="space-y-3">
        <?php foreach ($items as $item): ?>
        <a href="/market/<?= $item['id'] ?>" class="flex gap-3 p-3 rounded-2xl bg-surface-100/40 border border-surface-400/10 hover:border-amber-500/30 hover:-translate-y-0.5 transition-all group">
            <div class="w-20 h-20 rounded-xl overflow-hidden bg-surface-200 flex-shrink-0">
                <img src="<?= $item['thumbnail'] ?? ($item['type'] === 'digital' ? '/uploads/profiles/admin.jpg' : '/uploads/profiles/admin.jpg') ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <?php if ($item['type'] === 'digital'): ?>
                            <span class="px-1.5 py-0.5 rounded bg-purple-500/20 text-purple-400 text-[8px] font-semibold">DIGITAL</span>
                            <?php else: ?>
                            <span class="px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-400 text-[8px] font-semibold">SERVICE</span>
                            <?php endif; ?>
                            <?php if (!empty($item['is_featured'])): ?>
                            <span class="px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-400 text-[8px] font-semibold">FEATURED</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-white text-sm font-semibold truncate"><?= htmlspecialchars($item['title'] ?? '') ?></h3>
                    </div>
                    <span class="text-amber-400 text-sm font-bold flex-shrink-0"><?= htmlspecialchars($item['currency'] ?? 'KES') ?> <?= number_format((float)($item['price'] ?? 0)) ?></span>
                </div>
                <p class="text-zinc-500 text-[10px] leading-relaxed line-clamp-2 mt-0.5"><?= htmlspecialchars(substr($item['description'] ?? '', 0, 100)) ?><?= strlen($item['description'] ?? '') > 100 ? '...' : '' ?></p>
                <div class="flex items-center gap-2 mt-1.5">
                    <div class="flex items-center gap-1">
                        <img src="<?= $item['seller_avatar'] ?? 'https://picsum.photos/id/64/16/16' ?>" alt="" class="w-4 h-4 rounded-full">
                        <span class="text-zinc-500 text-[9px]">@<?= htmlspecialchars($item['username'] ?? '') ?></span>
                        <?php if (!empty($item['is_verified'])): ?>
                        <span class="material-icons-round text-brand-400 text-[10px]">verified</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($item['type'] === 'digital'): ?>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-500 text-[9px]"><?= formatCount($item['downloads'] ?? 0) ?> downloads</span>
                    <?php else: ?>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-500 text-[9px]"><?= formatCount($item['orders_count'] ?? 0) ?> orders</span>
                    <?php endif; ?>
                    <?php if (!empty($item['delivery_time'])): ?>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-500 text-[9px]"><?= htmlspecialchars($item['delivery_time']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-12 bg-surface-100/30 rounded-2xl border border-surface-400/10">
        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-surface-200/80 flex items-center justify-center">
            <span class="material-icons-round text-surface-400 text-3xl">sell</span>
        </div>
        <h3 class="text-white font-semibold text-sm mb-1">No items found</h3>
        <p class="text-zinc-500 text-xs mb-4"><?= $search ? 'No results for "' . htmlspecialchars($search) . '"' : ($user ? 'Start selling your digital products or services!' : 'Sign in to browse and sell items') ?></p>
        <?php if ($user): ?>
        <a href="/market/create" class="inline-flex items-center gap-1.5 gradient-brand px-5 py-2 rounded-full text-white text-xs font-semibold">Sell on Market</a>
        <?php else: ?>
        <a href="/login" class="inline-flex items-center gap-1.5 gradient-brand px-5 py-2 rounded-full text-white text-xs font-semibold">Sign In</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($user && !empty($userItems)): ?>
    <div class="mt-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-white text-xs font-bold">Your Items</h3>
            <a href="/market/my" class="text-amber-400 text-[10px] font-semibold">Manage</a>
        </div>
        <div class="space-y-2">
            <?php foreach ($userItems as $item): ?>
            <a href="/market/<?= $item['id'] ?>" class="flex items-center gap-3 p-3 rounded-xl bg-surface-100/30 border border-surface-400/10 hover:border-surface-400/30 transition-all">
                <img src="<?= $item['thumbnail'] ?? '/uploads/profiles/admin.jpg' ?>" alt="" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5">
                        <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($item['title'] ?? '') ?></p>
                        <?php if ($item['type'] === 'digital'): ?>
                        <span class="px-1 py-0.5 rounded bg-purple-500/20 text-purple-400 text-[7px] font-semibold">DIGITAL</span>
                        <?php else: ?>
                        <span class="px-1 py-0.5 rounded bg-blue-500/20 text-blue-400 text-[7px] font-semibold">SERVICE</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-amber-400 text-[10px] font-bold"><?= htmlspecialchars($item['currency'] ?? 'KES') ?> <?= number_format((float)($item['price'] ?? 0)) ?></span>
                        <span class="text-zinc-600">·</span>
                        <span class="text-zinc-500 text-[10px]"><?= formatCount($item['views'] ?? 0) ?> views</span>
                    </div>
                </div>
                <span class="material-icons-round text-zinc-500">chevron_right</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
