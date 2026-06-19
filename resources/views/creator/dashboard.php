<?php $activeTab = 'menu'; $title = 'Creator Dashboard - DTTube'; ?>
<?php $stats = $data['stats'] ?? []; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4">
    <div class="flex items-center gap-3 mb-6">
        <a href="/menu" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">Creator Dashboard</h1>
    </div>

    <div class="grid grid-cols-2 gap-3 mb-4">
        <div class="bg-gradient-to-br from-brand-600/30 to-purple-700/30 rounded-2xl border border-brand-500/20 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-brand-500/30 flex items-center justify-center">
                    <span class="material-icons-round text-brand-300 text-lg">visibility</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-medium">Total Views</span>
            </div>
            <span class="text-white text-2xl font-bold"><?= number_format((int)($stats['total_views'] ?? 0)) ?></span>
            <div class="flex items-center gap-1 mt-1">
                <span class="material-icons-round text-emerald-400 text-sm">trending_up</span>
                <span class="text-emerald-400 text-[10px] font-semibold">+12.5%</span>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-600/30 to-green-700/30 rounded-2xl border border-emerald-500/20 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/30 flex items-center justify-center">
                    <span class="material-icons-round text-emerald-300 text-lg">people</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-medium">Followers</span>
            </div>
            <span class="text-white text-2xl font-bold"><?= number_format((int)($stats['total_followers'] ?? 0)) ?></span>
            <div class="flex items-center gap-1 mt-1">
                <span class="material-icons-round text-emerald-400 text-sm">trending_up</span>
                <span class="text-emerald-400 text-[10px] font-semibold">+8.3%</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-4 gap-2 mb-4">
        <div class="bg-surface-100/60 rounded-xl border border-surface-400/15 p-3 text-center">
            <span class="text-white text-lg font-bold block"><?= number_format((int)($stats['total_posts'] ?? 0)) ?></span>
            <span class="text-zinc-500 text-[9px] font-medium">Posts</span>
        </div>
        <div class="bg-surface-100/60 rounded-xl border border-surface-400/15 p-3 text-center">
            <span class="text-white text-lg font-bold block"><?= number_format((int)($stats['total_videos'] ?? 0)) ?></span>
            <span class="text-zinc-500 text-[9px] font-medium">Videos</span>
        </div>
        <div class="bg-surface-100/60 rounded-xl border border-surface-400/15 p-3 text-center">
            <span class="text-white text-lg font-bold block"><?= number_format((int)($stats['total_reels'] ?? 0)) ?></span>
            <span class="text-zinc-500 text-[9px] font-medium">Reels</span>
        </div>
        <div class="bg-surface-100/60 rounded-xl border border-surface-400/15 p-3 text-center">
            <span class="text-white text-lg font-bold block"><?= number_format((int)($stats['total_likes'] ?? 0)) ?></span>
            <span class="text-zinc-500 text-[9px] font-medium">Likes</span>
        </div>
    </div>

    <div class="bg-surface-100/60 rounded-2xl border border-surface-400/15 p-4 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-white text-xs font-bold flex items-center gap-1.5">
                <span class="material-icons-round text-amber-400 text-lg">account_balance_wallet</span>
                Total Earnings
            </h3>
            <a href="/wallet" class="text-brand-400 text-[10px] font-semibold">View Wallet</a>
        </div>
        <span class="text-white text-3xl font-bold">KES <?= number_format((float)($stats['total_earnings'] ?? 0), 2) ?></span>
    </div>

    <div class="mb-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-white text-xs font-bold">Recent Posts</h3>
            <a href="/posts" class="text-brand-400 text-[10px] font-semibold">View All</a>
        </div>
        <?php if (!empty($stats['recent_posts'])): ?>
        <div class="space-y-2">
            <?php foreach ($stats['recent_posts'] as $post): ?>
            <a href="/posts/<?= $post['id'] ?>" class="flex items-center gap-3 p-3 rounded-xl bg-surface-100/40 border border-surface-400/10 hover:border-surface-400/30 transition-all">
                <?php if (!empty($post['image_url'])): ?>
                <img src="<?= $post['image_url'] ?>" alt="" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                <?php else: ?>
                <div class="w-12 h-12 rounded-lg bg-brand-500/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-icons-round text-brand-400">article</span>
                </div>
                <?php endif; ?>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($post['content'] ?? '') ?></p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-zinc-500 text-[10px]"><?= formatCount($post['likes'] ?? 0) ?> likes</span>
                        <span class="text-zinc-600">·</span>
                        <span class="text-zinc-500 text-[10px]"><?= formatCount($post['comments_count'] ?? 0) ?> comments</span>
                    </div>
                </div>
                <span class="text-zinc-600 text-[10px]"><?= timeAgo($post['created_at']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-6 bg-surface-100/30 rounded-xl border border-surface-400/10">
            <span class="material-icons-round text-zinc-500 text-3xl">article</span>
            <p class="text-zinc-500 text-xs mt-1">No posts yet</p>
            <a href="/posts/create" class="inline-block mt-2 text-brand-400 text-[10px] font-semibold">Create your first post</a>
        </div>
        <?php endif; ?>
    </div>

    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-white text-xs font-bold">Recent Videos</h3>
            <a href="/videos" class="text-brand-400 text-[10px] font-semibold">View All</a>
        </div>
        <?php if (!empty($stats['recent_videos'])): ?>
        <div class="space-y-2">
            <?php foreach ($stats['recent_videos'] as $video): ?>
            <a href="/videos/<?= $video['id'] ?>" class="flex items-center gap-3 p-3 rounded-xl bg-surface-100/40 border border-surface-400/10 hover:border-surface-400/30 transition-all">
                <div class="relative w-20 flex-shrink-0">
                    <img src="<?= $video['thumbnail'] ?? 'https://placehold.co/80x45/3f3f46/ffffff?text=Video' ?>" alt="" class="w-full aspect-video rounded-lg object-cover">
                    <div class="absolute bottom-1 right-1 px-1 py-0.5 rounded bg-black/80 text-white text-[8px] font-medium">
                        <?= formatDuration($video['duration'] ?? 0) ?>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($video['title'] ?? '') ?></p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-zinc-500 text-[10px]"><?= formatCount($video['views'] ?? 0) ?> views</span>
                        <span class="text-zinc-600">·</span>
                        <span class="text-zinc-500 text-[10px]"><?= formatCount($video['likes'] ?? 0) ?> likes</span>
                    </div>
                </div>
                <span class="text-zinc-600 text-[10px]"><?= timeAgo($video['created_at'] ?? '') ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-6 bg-surface-100/30 rounded-xl border border-surface-400/10">
            <span class="material-icons-round text-zinc-500 text-3xl">play_circle</span>
            <p class="text-zinc-500 text-xs mt-1">No videos yet</p>
            <a href="/videos/create" class="inline-block mt-2 text-brand-400 text-[10px] font-semibold">Upload your first video</a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
