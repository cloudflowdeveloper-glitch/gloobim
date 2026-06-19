<?php $hideTopNav = false; $hideBottomNav = true; $title = 'Stories - Globiim'; ?>
<?php extract($data ?? []); ?>
<?php ob_start(); ?>

<style>
    .story-page { background: #090c15; min-height: 100vh; }
    .story-header { position: sticky; top: 0; z-index: 50; background: rgba(9,12,21,0.95); backdrop-filter: blur(20px); }

    .story-avatar-ring {
        background: linear-gradient(135deg, #9333ea, #ec4899, #f59e0b);
        padding: 3px;
        border-radius: 50%;
    }
    .story-avatar-ring.seen {
        background: #3f3f46;
    }

    .story-card {
        background: linear-gradient(145deg, #14141c 0%, #0f1117 100%);
        border: 1px solid #1e1e2a;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .story-card:hover {
        border-color: rgba(131,74,229,0.3);
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(131,74,229,0.08);
    }

    .story-image-wrapper {
        position: relative;
        aspect-ratio: 9/16;
        overflow: hidden;
        background: #090c15;
    }
    .story-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .story-card:hover .story-image-wrapper img {
        transform: scale(1.05);
    }

    .story-overlay-text {
        position: absolute;
        left: 16px;
        right: 16px;
        text-align: center;
        text-shadow: 0 2px 8px rgba(0,0,0,0.7), 0 0 20px rgba(0,0,0,0.4);
        pointer-events: none;
        line-height: 1.3;
        padding: 0 8px;
    }
    .story-overlay-text.top { top: 48px; }
    .story-overlay-text.center { top: 50%; transform: translateY(-50%); }
    .story-overlay-text.bottom { bottom: 48px; }

    .time-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: rgba(239,68,68,0.15);
        color: #f87171;
    }
    .time-badge.expiring { background: rgba(245,158,11,0.15); color: #fbbf24; }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 60vh;
        text-align: center;
        padding: 40px 20px;
    }

    .pulse-ring {
        animation: pulse-ring 2s ease-in-out infinite;
    }
    @keyframes pulse-ring {
        0%, 100% { box-shadow: 0 0 0 0 rgba(131,74,229,0.4); }
        50% { box-shadow: 0 0 0 12px rgba(131,74,229,0); }
    }
</style>

<div class="story-page max-w-lg mx-auto">

    <!-- Header -->
    <div class="story-header px-4 py-3 flex items-center justify-between border-b border-[#14141c]">
        <div class="flex items-center gap-2">
            <a href="/" class="p-1 -ml-1 rounded-full hover:bg-[#14141c] transition-colors">
                <span class="material-icons-round text-zinc-400 text-[22px]">arrow_back</span>
            </a>
            <h1 class="text-white font-display text-lg font-bold">Stories</h1>
        </div>
        <a href="/stories/create"
           class="flex items-center gap-2 px-4 py-2 rounded-full text-white text-[13px] font-semibold"
           style="background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 15px rgba(131,74,229,0.3);">
            <span class="material-icons-round text-[18px]">add</span>
            My Story
        </a>
    </div>

    <?php if (empty($stories)): ?>
    <!-- Empty State -->
    <div class="empty-state">
        <div class="w-24 h-24 rounded-full bg-[#14141c] border-2 border-dashed border-zinc-700 flex items-center justify-center mb-5">
            <span class="material-icons-round text-zinc-500 text-5xl">auto_stories</span>
        </div>
        <h2 class="text-white text-lg font-bold mb-1">No Stories Yet</h2>
        <p class="text-zinc-500 text-sm max-w-xs">Be the first to share a moment! Stories disappear after 24 hours.</p>
        <a href="/stories/create"
           class="mt-6 px-8 py-3 rounded-full text-white text-sm font-bold pulse-ring"
           style="background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 20px rgba(131,74,229,0.4);">
            Create Your First Story
        </a>
    </div>

    <?php else: ?>
    <!-- Stories Grid (grouped by user) -->
    <div class="px-4 py-4 space-y-5">
        <?php foreach ($stories as $group): ?>
        <div class="story-card">
            <!-- User Header -->
            <div class="flex items-center justify-between p-3.5">
                <a href="/creator/<?= $group['username'] ?>" class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="<?= $group['has_unseen'] ? 'story-avatar-ring' : 'story-avatar-ring seen' ?>">
                        <img src="<?= $group['avatar'] ?>" alt="<?= $group['name'] ?>"
                             class="w-10 h-10 rounded-full object-cover bg-[#14141c]">
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1">
                            <span class="text-white text-[14px] font-semibold truncate"><?= $group['name'] ?></span>
                            <?php if (!empty($group['is_verified'])): ?>
                            <span class="material-icons-round text-[14px] flex-shrink-0" style="color: #834ae5;">verified</span>
                            <?php endif; ?>
                        </div>
                        <span class="text-zinc-500 text-[11px]"><?= count($group['stories']) ?> story<?= count($group['stories']) > 1 ? 'ies' : '' ?></span>
                    </div>
                </a>
                <?php if ($group['has_unseen']): ?>
                <span class="w-2.5 h-2.5 rounded-full bg-gradient-to-br from-purple-500 to-pink-500"></span>
                <?php endif; ?>
            </div>

            <!-- Story Thumbnails -->
            <div class="flex gap-2 px-3.5 pb-3.5 overflow-x-auto scrollbar-hide">
                <?php foreach ($group['stories'] as $story): ?>
                <a href="/stories/<?= $story['id'] ?>"
                   class="flex-shrink-0 story-image-wrapper rounded-xl w-[110px] relative group cursor-pointer">
                    <img src="<?= $story['image_url'] ?>" alt="Story" class="w-full h-full object-cover">

                    <?php if (!empty($story['text_content'])): ?>
                    <div class="story-overlay-text <?= $story['text_position'] ?>"
                         style="color: <?= $story['text_color'] ?>; font-size: <?= max(10, (int)$story['text_size'] * 0.45) ?>px; font-weight: <?= $story['font_style'] === 'bold' ? '700' : ($story['font_style'] === 'italic' ? '400' : '500') ?>; font-style: <?= $story['font_style'] === 'italic' ? 'italic' : 'normal' ?>;">
                        <?= htmlspecialchars($story['text_content']) ?>
                    </div>
                    <?php endif; ?>

                    <!-- Viewed indicator + View count -->
                    <div class="absolute bottom-2 left-2 right-2 flex items-center justify-between">
                        <div class="flex items-center gap-1">
                            <span class="material-icons-round text-white/70 text-[12px]">visibility</span>
                            <span class="text-white/70 text-[10px]"><?= $story['views_count'] ?></span>
                        </div>
                        <?php
                        $remaining = strtotime($story['expires_at']) - time();
                        $hoursLeft = max(0, ceil($remaining / 3600));
                        ?>
                        <span class="time-badge <?= $hoursLeft < 4 ? 'expiring' : '' ?>">
                            <span class="material-icons-round text-[10px]">schedule</span>
                            <?= $hoursLeft ?>h
                        </span>
                    </div>

                    <!-- Unseen glow -->
                    <?php if (!$story['is_viewed']): ?>
                    <div class="absolute inset-0 rounded-xl ring-2 ring-purple-500/60 ring-inset pointer-events-none"></div>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Bottom spacer for nav-free layout -->
    <div class="h-8"></div>
</div>

<script>
// Auto-refresh to clear expired stories (every 5 min)
setTimeout(() => { if (document.visibilityState === 'visible') location.reload(); }, 300000);
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
