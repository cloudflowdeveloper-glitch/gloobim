<?php $activeTab = 'stream'; $title = 'Past Streams - DTTube'; ?>
<?php
$streams = $data['streams'] ?? [];
$activeCategory = $data['activeCategory'] ?? '';
$search = $data['search'] ?? '';
?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4 pb-24 lg:pb-6">
    <div class="flex items-center gap-3 mb-5">
        <a href="/livestream" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">Past Streams</h1>
    </div>

    <form method="GET" action="/livestream/ended" class="relative mb-3">
        <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-lg">search</span>
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search past streams..." class="w-full bg-surface-200/80 text-white pl-9 pr-4 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm placeholder:text-zinc-600">
    </form>

    <?php if (!empty($streams)): ?>
    <div class="space-y-2">
        <?php foreach ($streams as $s): ?>
        <a href="/livestream/<?= $s['id'] ?>" class="flex items-center gap-3 p-3.5 rounded-xl bg-surface-100/40 border border-surface-400/10 hover:border-surface-400/30 hover:-translate-y-0.5 transition-all">
            <div class="w-16 h-16 rounded-xl bg-surface-200 overflow-hidden flex-shrink-0 relative">
                <img src="<?= $s['thumbnail'] ?? '/uploads/profiles/admin.jpg' ?>" alt="" class="w-full h-full object-cover">
                <span class="absolute bottom-1 right-1 px-1 py-0.5 rounded bg-black/70 text-white text-[7px] font-medium"><?= isset($s['duration_seconds']) && $s['duration_seconds'] ? gmdate('H:i:s', (int)$s['duration_seconds']) : '' ?></span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5 mb-0.5">
                    <p class="text-white text-xs font-semibold truncate"><?= htmlspecialchars($s['title'] ?? '') ?></p>
                </div>
                <div class="flex items-center gap-1">
                    <img src="<?= $s['creator_avatar'] ?? '/uploads/profiles/admin.jpg' ?>" alt="" class="w-4 h-4 rounded-full">
                    <span class="text-zinc-500 text-[9px]">@<?= htmlspecialchars($s['username'] ?? '') ?></span>
                    <?php if (!empty($s['is_verified'])): ?><span class="material-icons-round text-brand-400 text-[10px]">verified</span><?php endif; ?>
                </div>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-zinc-500 text-[9px]"><?= (int)($s['total_likes'] ?? 0) ?> likes</span>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-500 text-[9px]"><?= (int)($s['peak_viewers'] ?? 0) ?> peak viewers</span>
                    <span class="text-zinc-600">·</span>
                    <span class="text-zinc-500 text-[9px]"><?= isset($s['ended_at']) ? timeAgo($s['ended_at']) : '' ?></span>
                </div>
            </div>
            <span class="material-icons-round text-zinc-500">play_circle</span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16 bg-surface-100/30 rounded-2xl border border-surface-400/10">
        <span class="material-icons-round text-zinc-500 text-5xl">history</span>
        <h3 class="text-white font-semibold text-sm mt-3">No past streams</h3>
        <p class="text-zinc-500 text-xs mt-1">Ended streams will appear here</p>
        <a href="/livestream" class="inline-block mt-4 gradient-brand px-5 py-2 rounded-full text-white text-xs font-semibold">Browse Live</a>
    </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
