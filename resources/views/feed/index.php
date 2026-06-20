<?php $activeTab = 'feed'; $title = 'Feed - DTTube'; ?>
<?php extract($data ?? []); ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4">
    <div class="flex items-center gap-2 mb-6">
        <span class="material-icons-round text-brand-400 text-2xl">explore</span>
        <h1 class="font-display text-2xl font-bold text-white">Your Feed</h1>
    </div>

    <div class="flex gap-2 mb-6 overflow-x-auto scrollbar-hide">
        <a href="/feed" class="flex-shrink-0 px-4 py-2 rounded-xl <?= empty($trending) && empty($subscriptions) ? 'gradient-brand text-white' : 'bg-surface-200 text-zinc-400 hover:text-white' ?> text-sm font-medium transition-colors">For You</a>
        <a href="/feed/trending" class="flex-shrink-0 px-4 py-2 rounded-xl <?= !empty($trending) ? 'gradient-brand text-white' : 'bg-surface-200 text-zinc-400 hover:text-white' ?> text-sm font-medium transition-colors">Trending</a>
        <a href="/feed/subscriptions" class="flex-shrink-0 px-4 py-2 rounded-xl <?= !empty($subscriptions) ? 'gradient-brand text-white' : 'bg-surface-200 text-zinc-400 hover:text-white' ?> text-sm font-medium transition-colors">Subscriptions</a>
    </div>

    <?php if (!empty($reels)): ?>
    <section class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-display text-sm font-bold text-white flex items-center gap-1.5">
                <span class="material-icons-round text-brand-400 text-lg">movie</span>
                Reels
            </h2>
            <a href="/reels" class="text-brand-400 text-xs font-semibold hover:text-brand-300">See all</a>
        </div>
        <div class="flex gap-2.5 overflow-x-auto scrollbar-hide">
            <?php foreach ($reels as $reel): ?>
            <a href="/reels/<?= $reel['id'] ?>" class="flex-shrink-0 w-[120px] group">
                <div class="relative rounded-xl overflow-hidden mb-1.5">
                    <img src="<?= $reel['thumbnail'] ?>" alt="<?= $reel['title'] ?>" class="w-full aspect-[9/16] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-2 right-2">
                        <p class="text-white text-[10px] font-semibold leading-tight line-clamp-2"><?= $reel['title'] ?></p>
                    </div>
                    <div class="absolute top-1.5 right-1.5 px-1 py-0.5 rounded bg-black/70 text-white text-[9px] font-medium">
                        0:<?= str_pad($reel['duration'] ?? 30, 2, '0', STR_PAD_LEFT) ?>
                    </div>
                </div>
                <div class="flex items-center gap-1">
                    <span class="text-zinc-500 text-[10px] truncate"><?= $reel['creator_name'] ?? $reel['username'] ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section>
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-display text-sm font-bold text-white flex items-center gap-1.5">
                <span class="material-icons-round text-brand-400 text-lg">article</span>
                Posts
            </h2>
        </div>

        <?php if (!empty($posts)): ?>
        <div class="space-y-4">
            <?php foreach ($posts as $post): ?>
            <a href="/posts/<?= $post['id'] ?>" class="block bg-surface-100/30 rounded-2xl overflow-hidden border border-surface-400/10 hover:border-surface-400/30 transition-all">
                <div class="flex items-center justify-between px-4 pt-4 pb-2">
                    <div class="flex items-center gap-2.5">
                        <img src="<?= $post['creator_avatar'] ?? '/uploads/profiles/admin.jpg' ?>" alt="" class="w-9 h-9 rounded-full">
                        <div>
                            <div class="flex items-center gap-1">
                                <span class="text-white text-xs font-semibold"><?= $post['creator_name'] ?></span>
                                <?php if (!empty($post['is_verified'])): ?>
                                <span class="material-icons-round text-brand-400 text-[12px]">verified</span>
                                <?php endif; ?>
                            </div>
                            <span class="text-zinc-600 text-[10px]"><?= $post['username'] ?> · <?= timeAgo($post['created_at']) ?></span>
                        </div>
                    </div>
                    <span class="material-icons-round text-zinc-600 text-lg">more_horiz</span>
                </div>

                <?php if (!empty($post['image_url'])): ?>
                <div>
                    <img src="<?= $post['image_url'] ?>" alt="" class="w-full max-h-[400px] object-cover">
                </div>
                <?php endif; ?>

                <div class="px-4 pb-3">
                    <p class="text-zinc-200 text-[13px] leading-relaxed mt-2 line-clamp-3"><?= htmlspecialchars($post['content']) ?></p>
                </div>

                <div class="flex items-center gap-4 px-4 py-2.5 border-t border-surface-400/10">
                    <div class="flex items-center gap-1 text-zinc-500">
                        <span class="material-icons-round text-[16px]">favorite_border</span>
                        <span class="text-[10px]"><?= formatCount($post['likes'] ?? 0) ?></span>
                    </div>
                    <div class="flex items-center gap-1 text-zinc-500">
                        <span class="material-icons-round text-[16px]">chat_bubble_outline</span>
                        <span class="text-[10px]"><?= formatCount($post['comments_count'] ?? 0) ?></span>
                    </div>
                    <div class="flex items-center gap-1 text-zinc-500">
                        <span class="material-icons-round text-[16px]">ios_share</span>
                        <span class="text-[10px]"><?= formatCount($post['shares'] ?? 0) ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-surface-200/80 flex items-center justify-center">
                <span class="material-icons-round text-surface-400 text-3xl">explore</span>
            </div>
            <h3 class="text-white font-semibold text-sm mb-1">No content yet</h3>
            <p class="text-zinc-500 text-xs mb-4"><?= $user ? 'Follow creators to see their posts in your feed' : 'Sign in to get personalized recommendations' ?></p>
            <?php if (!$user): ?>
            <a href="/login" class="inline-flex items-center gap-1.5 gradient-brand px-5 py-2 rounded-full text-white text-sm font-semibold hover:opacity-90 transition-opacity">
                Sign In
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </section>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
