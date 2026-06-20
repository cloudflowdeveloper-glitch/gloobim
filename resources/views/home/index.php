<?php $hideTopNav = true; $activeTab = 'home'; $title = 'Globiim - Home'; ?>
<?php extract($data ?? []); ?>
<?php ob_start(); ?>
<style>
    @keyframes pulse-live { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
    .pulse-live { animation: pulse-live 2s ease-in-out infinite; }

    .story-ring { background: linear-gradient(135deg, #9333ea, #ec4899, #f59e0b); padding: 3.5px; border-radius: 50%; }
    .story-ring-seen { background: #3f3f46; padding: 3.5px; border-radius: 50%; }
    .scroll-snap-x { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
    .scroll-snap-x > * { scroll-snap-align: start; }

    .hover-scale { transition: transform 0.25s ease, box-shadow 0.25s ease; }
    .hover-scale:hover { transform: scale(1.03); box-shadow: 0 8px 25px rgba(0,0,0,0.4); }

    .quick-action { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
    .quick-action:hover { transform: translateY(-4px); }
    .quick-action:hover .qa-icon { box-shadow: 0 4px 20px rgba(131,74,229,0.4); }
    .quick-action:active { transform: scale(0.92); }

    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    .shimmer { background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.06) 50%, transparent 75%); background-size: 200% 100%; animation: shimmer 3s infinite; }

    .category-chip { transition: all 0.25s ease; }
    .category-chip:hover { background: rgba(131,74,229,0.15); color: #c084fc; }
    .category-chip.active { background: linear-gradient(135deg, #834ae5, #6b21a8); color: white; box-shadow: 0 4px 15px rgba(131,74,229,0.3); }

    @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    .float-anim { animation: float 3s ease-in-out infinite; }

    .post-card { transition: all 0.2s ease; }
    .post-card:hover { border-color: rgba(131,74,229,0.2); }

    .action-btn { transition: all 0.2s ease; }
    .action-btn:hover { transform: scale(1.15); }
    .action-btn:active { transform: scale(0.9); }

    .follow-post-btn { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
    .follow-post-btn:active { transform: scale(0.92); }

    .live-badge { animation: pulse-live 1.5s ease-in-out infinite; }

    @keyframes gradient-shift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    .gradient-animate { background-size: 200% 200%; animation: gradient-shift 4s ease infinite; }

    /* ===== SPOTLIGHT CAROUSEL ===== */
    .spotlight-track {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        padding: 4px 0 8px 0;
        scrollbar-width: none;
    }
    .spotlight-track::-webkit-scrollbar { display: none; }

    .spotlight-card {
        flex: 0 0 calc(100% - 48px);
        min-width: calc(100% - 48px);
        scroll-snap-align: center;
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        transition: transform 0.35s cubic-bezier(0.25,0.8,0.25,1);
    }
    .spotlight-card:first-child { margin-left: 16px; }

    .spotlight-card img {
        width: 100%;
        aspect-ratio: 16/9;
        object-fit: cover;
        display: block;
    }

    .spotlight-card .card-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(9,12,21,0.95) 0%, rgba(9,12,21,0.3) 50%, transparent 100%);
    }

    .spotlight-card .card-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px;
    }

    .spotlight-card .card-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        color: white;
        margin-bottom: 8px;
    }

    .spotlight-card .card-shimmer {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.04) 50%, transparent 75%);
        background-size: 200% 100%;
        animation: shimmer 3s infinite;
        pointer-events: none;
    }

    .spotlight-card .card-cta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        padding: 8px 18px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        color: white;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .spotlight-card .card-cta:hover { transform: translateY(-2px); filter: brightness(1.15); }

    /* Progress dots */
    .spotlight-dots {
        display: flex;
        justify-content: center;
        gap: 6px;
        padding: 8px 0 4px;
    }
    .spotlight-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #3f3f46;
        transition: all 0.35s ease;
        cursor: pointer;
    }
    .spotlight-dot.active {
        width: 24px;
        border-radius: 4px;
        background: linear-gradient(135deg, #834ae5, #c084fc);
        box-shadow: 0 2px 8px rgba(131,74,229,0.4);
    }

    /* Featured content card special styling */
    .spotlight-card.featured-card .card-cta {
        background: linear-gradient(135deg, #834ae5, #4f09f5);
        box-shadow: 0 4px 15px rgba(131,74,229,0.4);
    }
</style>

<div class="max-w-lg mx-auto pb-4">

    <!-- ===== HEADER ===== -->
    <div class="px-4 pt-3 pb-2">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <img src="/logo.jpeg" alt="Globiim" class="h-10 w-auto rounded-xl object-contain">
                <h1 class="font-display text-2xl font-bold tracking-tight">
                    <span class="text-white">Glo</span><span class="gradient-text">biim</span>
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <a href="/messages" class="w-10 h-10 rounded-full bg-[#14141c] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors relative border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-[20px]">chat_bubble_outline</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#834ae5] rounded-full"></span>
                </a>
                <button onclick="showToast('No new notifications')" class="w-10 h-10 rounded-full bg-[#14141c] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors relative border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-[20px]">notifications_none</span>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="w-9 h-9 rounded-full overflow-hidden" style="box-shadow: 0 0 0 2px rgba(131,74,229,0.5);">
                    <img src="/uploads/profiles/admin.jpg" alt="Profile" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- ===== SEARCH BAR ===== -->
        <div class="relative mb-3">
            <span class="material-icons-round absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-600 text-lg">search</span>
            <input type="text" placeholder="Search creators, reels, music..." class="w-full bg-[#14141c] text-white pl-10 pr-4 py-2.5 rounded-xl border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-sm placeholder:text-zinc-600 transition-all">
            <button class="absolute right-2 top-1/2 -translate-y-1/2 p-1 rounded-lg hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-500 text-lg">tune</span>
            </button>
        </div>

        <!-- ===== CATEGORY CHIPS ===== -->
        <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-1 scroll-snap-x">
            <?php foreach ($categories as $idx => $cat): ?>
            <button class="category-chip <?= $idx === 0 ? 'active' : '' ?> flex-shrink-0 px-4 py-1.5 rounded-full bg-[#14141c] text-zinc-400 text-xs font-medium border border-[#1e1e2a]" onclick="selectCategory(this)">
                <span class="flex items-center gap-1">
                    <span class="material-icons-round text-[14px]"><?= $cat['icon'] ?></span>
                    <?= $cat['name'] ?>
                </span>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== STORIES ROW ===== -->
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-white text-sm font-bold">Stories</h2>
            <?php if (!empty($storyGroups)): ?>
            <a href="/stories" class="text-[11px] font-semibold flex items-center gap-0.5" style="color: #834ae5;">
                View all <span class="material-icons-round text-sm">chevron_right</span>
            </a>
            <?php endif; ?>
        </div>
        <div class="flex gap-5 overflow-x-auto scrollbar-hide py-1 scroll-snap-x">
            <!-- Your Story -->
            <a href="/stories/create" class="flex-shrink-0 flex flex-col items-center gap-2.5 w-[100px]">
                <div class="w-[88px] h-[88px] rounded-full bg-[#14141c] border-2 border-dashed border-zinc-700 flex items-center justify-center hover:border-[#834ae5] transition-colors">
                    <span class="material-icons-round text-[#834ae5] text-4xl">add</span>
                </div>
                <span class="text-[11px] text-zinc-500 font-medium truncate w-full text-center">Your Story</span>
            </a>
            <!-- Dynamic Stories from DB -->
            <?php foreach ($storyGroups as $group): ?>
            <a href="#" onclick="openStoryViewer(event, <?= $group['stories'][0]['id'] ?? 0 ?>, <?= $group['user_id'] ?? 0 ?>); return false;"
               class="flex-shrink-0 flex flex-col items-center gap-2.5 w-[100px]">
                <div class="<?= $group['has_unseen'] ? 'story-ring' : 'story-ring-seen' ?>" style="padding: 3.5px;">
                    <div class="w-[80px] h-[80px] rounded-full overflow-hidden bg-[#14141c]">
                        <img src="<?= $group['avatar'] ?>" alt="<?= $group['name'] ?>"
                             class="w-full h-full object-cover">
                    </div>
                </div>
                <span class="text-[11px] text-zinc-400 font-medium truncate w-full text-center">
                    <?= htmlspecialchars(explode(' ', $group['name'])[0]) ?>
                </span>
                <?php if (count($group['stories']) > 1): ?>
                <span class="text-[9px] text-zinc-600 -mt-2">+<?= count($group['stories']) - 1 ?></span>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
            <!-- Fallback: show creators if no stories exist -->
            <?php if (empty($storyGroups)): ?>
            <?php foreach ($topCreators as $i => $creator): ?>
            <a href="/creator/<?= $creator['username'] ?>" class="flex-shrink-0 flex flex-col items-center gap-2.5 w-[100px]">
                <div class="<?= $i < 4 ? 'story-ring' : 'story-ring-seen' ?>" style="padding: 3.5px;">
                    <div class="w-[80px] h-[80px] rounded-full overflow-hidden bg-[#14141c]">
                        <img src="<?= $creator['avatar'] ?>" alt="<?= $creator['name'] ?>" class="w-full h-full object-cover">
                    </div>
                </div>
                <span class="text-[11px] text-zinc-400 font-medium truncate w-full text-center"><?= explode(' ', $creator['name'])[0] ?></span>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===== QUICK ACTION ICONS ===== -->
    <div class="px-4 py-4 border-t border-[#14141c]/60">
        <div class="grid grid-cols-4 gap-6">
            <?php foreach ($quickActions as $action): ?>
            <a href="<?= $action['url'] ?>" class="quick-action flex flex-col items-center gap-2">
                <div class="qa-icon w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg" style="background: <?= $action['color'] ?>;">
                    <span class="material-icons-round text-white text-2xl"><?= $action['icon'] ?></span>
                </div>
                <span class="text-zinc-300 text-[10px] font-semibold"><?= $action['name'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== FEATURED BANNER ===== -->
    <?php $feat = $featuredContent ?? ['title' => 'Discover, Create & Share', 'subtitle' => 'Your creative universe awaits.', 'cover_url' => '/uploads/home/featured_banner.jpg', 'creators_online' => '12.4K+', 'daily_views' => '5.6M+']; ?>
    <div class="px-4 py-2 border-t border-[#14141c]/60">
        <div class="relative rounded-2xl overflow-hidden hover-scale" style="box-shadow: 0 0 30px rgba(131,74,229,0.1);">
            <img src="<?= $feat['cover_url'] ?>" alt="Featured" class="w-full aspect-[2/1] object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
            <div class="absolute inset-0 shimmer"></div>
            <div class="absolute inset-0 flex flex-col justify-center p-5">
                <h2 class="font-display text-xl font-bold text-white leading-tight max-w-[60%]"><?= $feat['title'] ?></h2>
                <p class="text-zinc-300 text-xs mt-1 max-w-[55%]"><?= $feat['subtitle'] ?></p>
                <div class="flex items-center gap-3 mt-3">
                    <button class="px-5 py-2 rounded-full text-white text-xs font-bold shadow-lg hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #834ae5, #4f09f5); box-shadow: 0 4px 15px rgba(131,74,229,0.4);">
                        Explore Now
                    </button>
                </div>
            </div>
            <div class="absolute bottom-3 right-4 flex flex-col items-end gap-1">
                <div class="bg-black/40 backdrop-blur-sm rounded-lg px-3 py-1.5">
                    <span class="text-white text-sm font-bold"><?= $feat['creators_online'] ?></span>
                    <span class="text-zinc-400 text-[9px] block">Creators Online</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SMART SPOTLIGHT CAROUSEL ===== -->
    <div class="py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between px-4 mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-[18px]" style="color: #834ae5;">auto_awesome</span>
                <h2 class="text-white text-sm font-bold">Spotlight</h2>
            </div>
            <a href="/reels" class="text-[11px] font-semibold flex items-center gap-0.5" style="color: #834ae5;">
                See all <span class="material-icons-round text-sm">chevron_right</span>
            </a>
        </div>

        <div class="spotlight-track" id="spotlightTrack">
            <?php
            // First card: Featured content (top reel)
            $featuredReel = !empty($discoverGrid) ? $discoverGrid[0] : null;
            if ($featuredReel):
            ?>
            <!-- Card 1: Featured Content -->
            <a href="/reels/<?= $featuredReel['id'] ?>" class="spotlight-card featured-card">
                <img src="<?= $featuredReel['cover_url'] ?>" alt="<?= $featuredReel['title'] ?>">
                <div class="card-shimmer"></div>
                <div class="card-gradient"></div>
                <div class="absolute top-3 left-4">
                    <span class="card-badge" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
                        <span class="material-icons-round text-[12px]">local_fire_department</span>
                        Trending
                    </span>
                </div>
                <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm rounded-lg px-2.5 py-1 flex items-center gap-1">
                    <span class="material-icons-round text-white text-[14px]">visibility</span>
                    <span class="text-white text-[11px] font-semibold"><?= formatCount($featuredReel['views'] ?? 0) ?></span>
                </div>
                <div class="card-content">
                    <h3 class="text-white text-base font-bold leading-tight mb-1"><?= $featuredReel['title'] ?></h3>
                    <span class="card-cta">
                        <span class="material-icons-round text-[14px]">play_arrow</span>
                        Watch Now
                    </span>
                </div>
            </a>
            <?php endif; ?>

            <?php if (empty($featuredReel)): ?>
            <!-- Fallback Card 1 if no content -->
            <a href="/reels" class="spotlight-card featured-card">
                <img src="/uploads/home/featured_banner.jpg" alt="Featured">
                <div class="card-shimmer"></div>
                <div class="card-gradient"></div>
                <div class="absolute top-3 left-4">
                    <span class="card-badge" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
                        <span class="material-icons-round text-[12px]">local_fire_department</span>
                        Trending
                    </span>
                </div>
                <div class="card-content">
                    <h3 class="text-white text-base font-bold leading-tight mb-1">Discover Trending Clips</h3>
                    <span class="card-cta">
                        <span class="material-icons-round text-[14px]">play_arrow</span>
                        Watch Now
                    </span>
                </div>
            </a>
            <?php endif; ?>

            <!-- Cards 2-4: Admin Spotlight Ads -->
            <?php foreach ($spotlightAds as $ad): ?>
            <a href="<?= $ad['link_url'] ?? '#' ?>" class="spotlight-card">
                <img src="<?= $ad['image_url'] ?>" alt="<?= $ad['title'] ?>">
                <div class="card-shimmer"></div>
                <div class="card-gradient"></div>
                <div class="absolute top-3 left-4">
                    <span class="card-badge" style="background: <?= $ad['badge_color'] ?? '#834ae5' ?>;">
                        <span class="material-icons-round text-[12px]">campaign</span>
                        <?= htmlspecialchars($ad['badge'] ?? 'Ad') ?>
                    </span>
                </div>
                <div class="card-content">
                    <h3 class="text-white text-base font-bold leading-tight mb-0.5"><?= htmlspecialchars($ad['title']) ?></h3>
                    <?php if (!empty($ad['subtitle'])): ?>
                    <p class="text-zinc-300 text-xs"><?= htmlspecialchars($ad['subtitle']) ?></p>
                    <?php endif; ?>
                    <span class="card-cta" style="background: <?= $ad['badge_color'] ?? '#834ae5' ?>; box-shadow: 0 4px 15px <?= $ad['badge_color'] ?? '#834ae5' ?>33;">
                        Learn More
                        <span class="material-icons-round text-[14px]">arrow_forward</span>
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Progress Dots -->
        <div class="spotlight-dots" id="spotlightDots">
            <?php
            $totalCards = 1 + count($spotlightAds);
            for ($d = 0; $d < $totalCards; $d++):
            ?>
            <div class="spotlight-dot <?= $d === 0 ? 'active' : '' ?>" data-index="<?= $d ?>" onclick="goToSlide(<?= $d ?>)"></div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- ===== LIVE NOW ===== -->
    <?php if (!empty($liveNow)): ?>
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-red-500 rounded-full live-badge"></span>
                <h2 class="text-white text-sm font-bold">Live Now</h2>
            </div>
            <a href="/livestream" class="text-[11px] font-semibold" style="color: #834ae5;">See all</a>
        </div>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide scroll-snap-x">
            <?php foreach ($liveNow as $stream): ?>
            <a href="/livestream/<?= $stream['id'] ?>" class="flex-shrink-0 w-[220px] group">
                <div class="relative rounded-xl overflow-hidden hover-scale">
                    <img src="<?= $stream['thumbnail'] ?>" alt="<?= $stream['title'] ?>" class="w-full aspect-video object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute top-2 left-2 flex items-center gap-1 px-2 py-0.5 rounded-lg bg-red-600 text-white text-[10px] font-bold">
                        <span class="w-1.5 h-1.5 bg-white rounded-full live-badge"></span>
                        LIVE
                    </div>
                    <div class="absolute top-2 right-2 flex items-center gap-0.5 px-1.5 py-0.5 rounded-lg bg-black/60 backdrop-blur-sm text-white text-[10px]">
                        <span class="material-icons-round text-[12px]">visibility</span>
                        <?= formatCount($stream['viewers']) ?>
                    </div>
                    <div class="absolute bottom-2 left-2 right-2 flex items-center gap-2">
                        <img src="<?= $stream['creator_avatar'] ?>" alt="" class="w-6 h-6 rounded-full border border-white/30">
                        <div class="min-w-0">
                            <p class="text-white text-[11px] font-semibold truncate"><?= $stream['title'] ?></p>
                            <p class="text-white/50 text-[9px]"><?= $stream['creator_name'] ?? '' ?></p>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== TRENDING REELS ===== -->
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-[18px]" style="color: #834ae5;">movie</span>
                <h2 class="text-white text-sm font-bold">Trending Clips</h2>
            </div>
            <a href="/reels" class="text-[11px] font-semibold flex items-center gap-0.5" style="color: #834ae5;">
                See all <span class="material-icons-round text-sm">chevron_right</span>
            </a>
        </div>
        <div class="flex gap-2.5 overflow-x-auto scrollbar-hide scroll-snap-x">
            <?php foreach ($trendingReels as $reel): ?>
            <a href="/reels/<?= $reel['id'] ?>" class="flex-shrink-0 w-[130px] group">
                <div class="relative rounded-xl overflow-hidden mb-1.5 hover-scale">
                    <img src="<?= $reel['thumbnail'] ?>" alt="<?= $reel['title'] ?>" class="w-full aspect-[9/16] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-2 left-2 right-2">
                        <p class="text-white text-[11px] font-semibold leading-tight line-clamp-2"><?= $reel['title'] ?></p>
                    </div>
                    <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 rounded-lg bg-black/70 backdrop-blur-sm text-white text-[9px] font-medium">
                        0:<?= str_pad($reel['duration'] ?? 30, 2, '0', STR_PAD_LEFT) ?>
                    </div>
                    <div class="absolute bottom-2 right-2 flex items-center gap-0.5">
                        <span class="material-icons-round text-white/80 text-[10px]">play_arrow</span>
                        <span class="text-white/80 text-[9px]"><?= formatCount($reel['views'] ?? 0) ?></span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 px-0.5">
                    <img src="<?= $reel['creator_avatar'] ?? '/uploads/home/story_1.jpg' ?>" class="w-4 h-4 rounded-full">
                    <span class="text-zinc-400 text-[10px] truncate"><?= $reel['creator_name'] ?? '' ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== POSTS FEED ===== -->
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-[18px]" style="color: #834ae5;">article</span>
                <h2 class="text-white text-sm font-bold">Feed</h2>
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-1 rounded-full text-white text-[11px] font-semibold" style="background: linear-gradient(135deg, #834ae5, #6b21a8);">For You</button>
                <button class="px-3 py-1 rounded-full bg-[#14141c] text-zinc-400 text-[11px] font-medium border border-[#1e1e2a]">Following</button>
            </div>
        </div>
        <div class="space-y-4">
            <?php foreach ($posts as $post): ?>
            <article class="post-card bg-[#14141c] rounded-2xl border border-[#1e1e2a] overflow-hidden">
                <div class="p-3.5">
                    <!-- Post Header -->
                    <div class="flex items-center gap-2.5 mb-2.5">
                        <div class="story-ring" style="padding: 1.5px;">
                            <img src="<?= $post['creator_avatar'] ?? '/uploads/home/story_1.jpg' ?>" alt="" class="w-9 h-9 rounded-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1">
                                <span class="text-white text-[13px] font-semibold"><?= $post['creator_name'] ?? '' ?></span>
                                <?php if (!empty($post['is_verified'])): ?>
                                <span class="material-icons-round text-[13px]" style="color: #834ae5;">verified</span>
                                <?php endif; ?>
                                <span class="text-zinc-600 text-[11px]">¡¤ <?= timeAgo($post['created_at']) ?></span>
                            </div>
                        </div>
                        <?php $postIsFollowing = !empty($post['is_following']); ?>
                        <button onclick="followPostCreator(<?= $post['user_id'] ?? 0 ?>, this)" class="follow-post-btn flex-shrink-0 px-3.5 py-1 rounded-full text-[11px] font-semibold hover:opacity-90 transition-all" style="<?= $postIsFollowing ? 'background: #1e1e2a; color: #a1a1aa;' : 'background: linear-gradient(135deg, #834ae5, #6b21a8); color: #ffffff;' ?>" data-following="<?= $postIsFollowing ? '1' : '0' ?>">
                            <span class="follow-label"><?= $postIsFollowing ? 'Following' : 'Follow' ?></span>
                        </button>
                        <button class="p-1 rounded-full hover:bg-[#1e1e2a] transition-colors">
                            <span class="material-icons-round text-zinc-500 text-[18px]">more_horiz</span>
                        </button>
                    </div>
                    <!-- Post Content -->
                    <p class="text-zinc-200 text-[13px] leading-relaxed"><?= htmlspecialchars($post['content']) ?></p>
                </div>
                <!-- Post Image -->
                <?php if (!empty($post['image_url'])): ?>
                <img src="<?= $post['image_url'] ?>" alt="Post" class="w-full max-h-[400px] object-cover">
                <?php endif; ?>
                <!-- Post Actions -->
                <div class="px-3.5 py-2.5 flex items-center justify-between">
                    <button onclick="likePost(<?= $post['id'] ?>, this)" class="action-btn flex items-center gap-1 text-zinc-400 hover:text-red-400">
                        <span class="material-icons-round text-[20px]">favorite_border</span>
                        <span class="text-[11px]"><?= formatCount($post['likes']) ?></span>
                    </button>
                    <button onclick="commentOnPost(<?= $post['id'] ?>)" class="action-btn flex items-center gap-1 text-zinc-400 hover:text-purple-400">
                        <span class="material-icons-round text-[20px]">chat_bubble_outline</span>
                        <span class="text-[11px]"><?= formatCount($post['comments_count']) ?></span>
                    </button>
                    <button onclick="sharePost(<?= $post['id'] ?>)" class="action-btn flex items-center gap-1 text-zinc-400 hover:text-green-400">
                        <span class="material-icons-round text-[20px]">share</span>
                        <span class="text-[11px]"><?= formatCount($post['shares']) ?></span>
                    </button>
                    <button onclick="tipPost(<?= $post['id'] ?>)" class="action-btn flex items-center gap-1 text-zinc-400 hover:text-amber-400">
                        <span class="material-icons-round text-[20px]">monetization_on</span>
                    </button>
                    <button onclick="bookmarkPost(<?= $post['id'] ?>, this)" class="action-btn text-zinc-400 hover:text-purple-400">
                        <span class="material-icons-round text-[20px]">bookmark_border</span>
                    </button>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== TRENDING VIDEOS ===== -->
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-[18px]" style="color: #834ae5;">play_circle</span>
                <h2 class="text-white text-sm font-bold">Trending Videos</h2>
            </div>
            <a href="/videos" class="text-[11px] font-semibold flex items-center gap-0.5" style="color: #834ae5;">
                See all <span class="material-icons-round text-sm">chevron_right</span>
            </a>
        </div>
        <div class="space-y-3">
            <?php foreach ($trendingVideos as $video): ?>
            <a href="/videos/<?= $video['id'] ?>" class="flex gap-3 group">
                <div class="relative flex-shrink-0 w-[140px] rounded-xl overflow-hidden">
                    <img src="<?= $video['thumbnail'] ?>" alt="<?= $video['title'] ?>" class="w-full aspect-video object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black/30">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #834ae5, #4f09f5);">
                            <span class="material-icons-round text-white text-sm">play_arrow</span>
                        </div>
                    </div>
                    <div class="absolute bottom-1.5 right-1.5 px-1.5 py-0.5 rounded bg-black/80 text-white text-[9px] font-medium">
                        <?= formatDuration($video['duration'] ?? 0) ?>
                    </div>
                </div>
                <div class="flex-1 min-w-0 py-0.5">
                    <h3 class="text-white text-[13px] font-semibold line-clamp-2 leading-snug"><?= $video['title'] ?></h3>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-zinc-500 text-[11px]"><?= $video['creator_name'] ?? '' ?></span>
                        <?php if (!empty($video['is_verified'])): ?>
                        <span class="material-icons-round text-[11px]" style="color: #834ae5;">verified</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-zinc-600 text-[11px]"><?= formatCount($video['views']) ?> views ¡¤ <?= timeAgo($video['created_at'] ?? '') ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== CREATORS TO FOLLOW ===== -->
    <div class="px-4 py-3 border-t border-[#14141c]/60">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-[18px]" style="color: #834ae5;">group</span>
                <h2 class="text-white text-sm font-bold">Suggested Creators</h2>
            </div>
            <a href="/creators" class="text-[11px] font-semibold" style="color: #834ae5;">See all</a>
        </div>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide scroll-snap-x">
            <?php foreach ($topCreators as $creator): ?>
            <div class="flex-shrink-0 w-[140px] bg-[#14141c] rounded-xl p-3 text-center border border-[#1e1e2a] hover:border-[#834ae5]/30 transition-all hover:shadow-lg hover:shadow-purple-900/10">
                <div class="relative w-14 h-14 mx-auto mb-2">
                    <div class="story-ring" style="padding: 2px;">
                        <img src="<?= $creator['avatar'] ?>" alt="<?= $creator['name'] ?>" class="w-full h-full rounded-full object-cover">
                    </div>
                    <?php if (!empty($creator['is_verified'])): ?>
                    <span class="absolute -bottom-0.5 -right-0.5 material-icons-round text-sm rounded-full" style="color: #834ae5;">verified</span>
                    <?php endif; ?>
                </div>
                <h3 class="text-white text-xs font-semibold truncate"><?= $creator['name'] ?></h3>
                <p class="text-zinc-500 text-[10px]"><?= formatCount($creator['follower_count'] ?? 0) ?> followers</p>
                <?php $isFollowing = !empty($creator['is_following']); ?>
                <button onclick="followCreator(<?= $creator['id'] ?? 0 ?>, this)"
                        class="mt-2 w-full py-1.5 rounded-lg text-[11px] font-semibold hover:opacity-90 transition-all"
                        style="<?= $isFollowing ? 'background: #1e1e2a; color: #a1a1aa;' : 'background: linear-gradient(135deg, #834ae5, #6b21a8); color: #ffffff;' ?>"
                        data-following="<?= $isFollowing ? '1' : '0' ?>"><?= $isFollowing ? 'Following' : 'Follow' ?></button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom spacer -->
    <div class="h-4"></div>
</div>

<script>
/* ==================================================================
   UTILITIES
   ================================================================== */
function formatCount(num) {
    num = parseInt(num) || 0;
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
    return num.toString();
}

function formatDuration(secs) {
    secs = parseInt(secs) || 0;
    const h = Math.floor(secs / 3600);
    const m = Math.floor((secs % 3600) / 60);
    const s = secs % 60;
    if (h > 0) return h + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    return m + ':' + String(s).padStart(2, '0');
}

function timeAgo(dateStr) {
    if (!dateStr) return '';
    const now = new Date();
    const date = new Date(dateStr.replace(/-/g, '/'));
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return 'just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h';
    if (diff < 604800) return Math.floor(diff / 86400) + 'd';
    if (diff < 2592000) return Math.floor(diff / 604800) + 'w';
    return Math.floor(diff / 2592000) + 'mo';
}

/**
 * Reusable AJAX helper – POSTs JSON and returns parsed response.
 * Shows toast on auth errors.
 */
async function apiPost(url, body = {}) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(body),
    });
    const data = await res.json();
    if (res.status === 401) showToast('Please login first');
    return data;
}

/* ==================================================================
   TOAST NOTIFICATIONS
   ================================================================== */
function showToast(msg, isError = false) {
    const existing = document.querySelector('.toast-msg');
    if (existing) existing.remove();
    const div = document.createElement('div');
    div.className = 'toast-msg fixed top-16 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-sm font-medium z-[200]';
    div.style.cssText = isError
        ? 'background: linear-gradient(135deg, #ef4444, #b91c1c); box-shadow: 0 4px 20px rgba(239,68,68,0.4);'
        : 'background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 20px rgba(131,74,229,0.4);';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(() => div.remove(), 300); }, 2000);
}

/* ==================================================================
   LIKE POST  –  toggle via /posts/{id}/like
   Uses the likes table to prevent double-likes.
   ================================================================== */
function likePost(id, btn) {
    const icon = btn.querySelector('.material-icons-round');
    const countEl = btn.querySelector('span:last-child');

    // Optimistic UI
    const wasLiked = icon.textContent === 'favorite';
    let currentCount = parseInt(countEl.textContent.replace(/[^0-9]/g, '')) || 0;

    if (wasLiked) {
        icon.textContent = 'favorite_border';
        icon.classList.remove('text-red-400');
        icon.classList.add('text-zinc-400');
        btn.classList.remove('text-red-400');
        btn.classList.add('text-zinc-400');
        countEl.textContent = formatCount(currentCount - 1);
    } else {
        icon.textContent = 'favorite';
        icon.classList.add('text-red-400');
        icon.classList.remove('text-zinc-400');
        btn.classList.add('text-red-400');
        btn.classList.remove('text-zinc-400');
        countEl.textContent = formatCount(currentCount + 1);
    }

    apiPost('/posts/' + id + '/like').then(data => {
        if (data.error) {
            // Revert on error
            icon.textContent = wasLiked ? 'favorite' : 'favorite_border';
            if (wasLiked) {
                icon.classList.add('text-red-400');
                icon.classList.remove('text-zinc-400');
            } else {
                icon.classList.remove('text-red-400');
                icon.classList.add('text-zinc-400');
            }
            countEl.textContent = formatCount(currentCount);
            showToast(data.error, true);
            return;
        }
        // Sync real count from server
        if (data.likes !== undefined) {
            countEl.textContent = formatCount(data.likes);
        }
    }).catch(() => showToast('Network error', true));
}

/* ==================================================================
   COMMENT ON POST  –  POST /posts/{id}/comment
   Uses a custom inline modal instead of browser prompt().
   ================================================================== */
function commentOnPost(id) {
    // Remove existing modal
    const old = document.getElementById('commentModal');
    if (old) { old.remove(); return; }

    const modal = document.createElement('div');
    modal.id = 'commentModal';
    modal.style.cssText = 'position:fixed;inset:0;z-index:300;display:flex;align-items:flex-end;justify-content:center;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);';
    modal.innerHTML = `
        <div style="background:#14141c;border:1px solid #1e1e2a;border-radius:20px 20px 0 0;width:100%;max-width:500px;padding:20px 20px 28px;animation:slideUp .25s ease;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                <h3 style="color:#fff;font-size:15px;font-weight:700;">Comment</h3>
                <button onclick="document.getElementById('commentModal').remove()" style="color:#71717a;background:none;border:none;cursor:pointer;font-size:22px;">&times;</button>
            </div>
            <textarea id="commentInput" rows="3" placeholder="Write a comment..." style="width:100%;background:#09090f;border:1px solid #1e1e2a;border-radius:12px;padding:12px;color:#fff;font-size:14px;resize:none;outline:none;font-family:inherit;"></textarea>
            <button id="commentSubmitBtn" onclick="submitComment(${id})" style="margin-top:10px;width:100%;padding:11px;border-radius:12px;border:none;background:linear-gradient(135deg,#834ae5,#6b21a8);color:#fff;font-size:14px;font-weight:700;cursor:pointer;">Post Comment</button>
        </div>
        <style>@keyframes slideUp{from{transform:translateY(100%)}to{transform:translateY(0)}}</style>
    `;
    document.body.appendChild(modal);
    modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });
    setTimeout(() => document.getElementById('commentInput')?.focus(), 100);
}

async function submitComment(postId) {
    const input = document.getElementById('commentInput');
    const btn = document.getElementById('commentSubmitBtn');
    const body = input.value.trim();
    if (!body) { showToast('Write something first', true); return; }

    btn.textContent = 'Posting...';
    btn.disabled = true;

    try {
        const data = await apiPost('/posts/' + postId + '/comment', { body });
        if (data.error) {
            showToast(data.error, true);
            btn.textContent = 'Post Comment';
            btn.disabled = false;
            return;
        }
        document.getElementById('commentModal').remove();

        // Update comment count in the UI
        const postCard = document.querySelector(`[onclick="commentOnPost(${postId})"]`)?.closest('article')?.closest('div')?.closest('div')?.closest('div');
        if (postCard) {
            const commentBtn = postCard.querySelector('[onclick*="commentOnPost"]');
            if (commentBtn) {
                const countEl = commentBtn.querySelector('span:last-child');
                if (countEl && data.comments_count !== undefined) {
                    countEl.textContent = formatCount(data.comments_count);
                }
            }
        }
        showToast(data.message || 'Comment added!');
    } catch {
        showToast('Failed to comment', true);
        btn.textContent = 'Post Comment';
        btn.disabled = false;
    }
}

/* ==================================================================
   SHARE POST  –  POST /posts/{id}/share  +  native share / clipboard
   ================================================================== */
async function sharePost(id) {
    // Record the share in the backend
    apiPost('/posts/' + id + '/share').catch(() => {});

    // Native share or clipboard
    if (navigator.share) {
        try { await navigator.share({ title: 'Globiim Post', url: '/posts/' + id }); } catch {}
    } else {
        try {
            await navigator.clipboard.writeText(window.location.origin + '/posts/' + id);
            showToast('Link copied to clipboard!');
        } catch {
            showToast('Could not copy link', true);
        }
    }
}

/* ==================================================================
   TIP POST  –  POST /tip/post/{id}
   Opens a custom amount picker instead of browser prompt().
   ================================================================== */
function tipPost(id) {
    const old = document.getElementById('tipModal');
    if (old) { old.remove(); return; }

    const modal = document.createElement('div');
    modal.id = 'tipModal';
    modal.style.cssText = 'position:fixed;inset:0;z-index:300;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);';
    modal.innerHTML = `
        <div style="background:#14141c;border:1px solid #1e1e2a;border-radius:20px;width:90%;max-width:360px;padding:24px;animation:scaleIn .2s ease;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                <h3 style="color:#fff;font-size:15px;font-weight:700;">Send a Tip</h3>
                <button onclick="document.getElementById('tipModal').remove()" style="color:#71717a;background:none;border:none;cursor:pointer;font-size:22px;">&times;</button>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px;" id="tipAmounts">
                <button onclick="setTipAmount(50)" class="tip-amt" style="flex:1;min-width:60px;padding:10px;border-radius:12px;border:1px solid #1e1e2a;background:#09090f;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">KES 50</button>
                <button onclick="setTipAmount(100)" class="tip-amt" style="flex:1;min-width:60px;padding:10px;border-radius:12px;border:1px solid #1e1e2a;background:#09090f;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">KES 100</button>
                <button onclick="setTipAmount(500)" class="tip-amt" style="flex:1;min-width:60px;padding:10px;border-radius:12px;border:1px solid #1e1e2a;background:#09090f;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">KES 500</button>
                <button onclick="setTipAmount(1000)" class="tip-amt" style="flex:1;min-width:60px;padding:10px;border-radius:12px;border:1px solid #1e1e2a;background:#09090f;color:#fff;font-size:14px;font-weight:600;cursor:pointer;">KES 1K</button>
            </div>
            <input type="number" id="tipCustomAmount" placeholder="Or enter custom amount" min="1" style="width:100%;background:#09090f;border:1px solid #1e1e2a;border-radius:12px;padding:11px 14px;color:#fff;font-size:14px;outline:none;margin-bottom:12px;font-family:inherit;">
            <button id="tipSubmitBtn" onclick="submitTip(${id})" style="width:100%;padding:12px;border-radius:12px;border:none;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;font-size:14px;font-weight:700;cursor:pointer;">Send Tip</button>
            <style>@keyframes scaleIn{from{transform:scale(0.9);opacity:0}to{transform:scale(1);opacity:1}}</style>
        </div>
    `;
    document.body.appendChild(modal);
    modal.addEventListener('click', e => { if (e.target === modal) modal.remove(); });
}

function setTipAmount(amt) {
    document.getElementById('tipCustomAmount').value = amt;
    document.querySelectorAll('.tip-amt').forEach(b => {
        b.style.borderColor = b.textContent.trim() === 'KES ' + (amt >= 1000 ? amt/1000 + 'K' : amt) ? '#f59e0b' : '#1e1e2a';
        b.style.background = b.textContent.trim() === 'KES ' + (amt >= 1000 ? amt/1000 + 'K' : amt) ? 'rgba(245,158,11,0.1)' : '#09090f';
    });
}

async function submitTip(id) {
    const input = document.getElementById('tipCustomAmount');
    const btn = document.getElementById('tipSubmitBtn');
    const amount = parseFloat(input.value);

    if (!amount || amount <= 0) { showToast('Enter a valid amount', true); return; }

    btn.textContent = 'Sending...';
    btn.disabled = true;

    try {
        const data = await apiPost('/tip/post/' + id, { amount });
        if (data.error) {
            showToast(data.error, true);
            btn.textContent = 'Send Tip';
            btn.disabled = false;
            return;
        }
        document.getElementById('tipModal').remove();
        showToast(data.message || 'Tip sent! KES ' + amount.toLocaleString());
    } catch {
        showToast('Failed to send tip', true);
        btn.textContent = 'Send Tip';
        btn.disabled = false;
    }
}

/* ==================================================================
   BOOKMARK POST  –  POST /bookmark/post/{id}
   Toggle save/unsave with icon + color change.
   ================================================================== */
async function bookmarkPost(id, btn) {
    const icon = btn.querySelector('.material-icons-round');
    const wasBookmarked = icon.textContent === 'bookmark';

    // Optimistic UI
    if (wasBookmarked) {
        icon.textContent = 'bookmark_border';
        icon.classList.remove('text-purple-400');
    } else {
        icon.textContent = 'bookmark';
        icon.classList.add('text-purple-400');
    }

    try {
        const data = await apiPost('/bookmark/post/' + id);
        if (data.error) {
            // Revert
            icon.textContent = wasBookmarked ? 'bookmark' : 'bookmark_border';
            if (wasBookmarked) icon.classList.add('text-purple-400');
            else icon.classList.remove('text-purple-400');
            showToast(data.error, true);
            return;
        }
        showToast(data.message || (data.bookmarked ? 'Saved!' : 'Removed from saved'));
    } catch {
        // Revert
        icon.textContent = wasBookmarked ? 'bookmark' : 'bookmark_border';
        if (wasBookmarked) icon.classList.add('text-purple-400');
        else icon.classList.remove('text-purple-400');
        showToast('Network error', true);
    }
}

/* ==================================================================
   FOLLOW CREATOR (Suggested Creators section)
   POST /follow/{id}
   ================================================================== */
function followCreator(id, btn) {
    const wasFollowing = btn.dataset.following === '1';

    // Optimistic UI
    if (wasFollowing) {
        btn.textContent = 'Follow';
        btn.style.background = 'linear-gradient(135deg, #834ae5, #6b21a8)';
        btn.style.color = '#ffffff';
        btn.dataset.following = '0';
    } else {
        btn.textContent = 'Following';
        btn.style.background = '#1e1e2a';
        btn.style.color = '#a1a1aa';
        btn.dataset.following = '1';
    }

    apiPost('/follow/' + id).then(data => {
        if (data.error) {
            // Revert
            if (wasFollowing) {
                btn.textContent = 'Following';
                btn.style.background = '#1e1e2a';
                btn.style.color = '#a1a1aa';
                btn.dataset.following = '1';
            } else {
                btn.textContent = 'Follow';
                btn.style.background = 'linear-gradient(135deg, #834ae5, #6b21a8)';
                btn.style.color = '#ffffff';
                btn.dataset.following = '0';
            }
            showToast(data.error, true);
            return;
        }
        showToast(data.message || (data.following ? 'Following!' : 'Unfollowed'));
    }).catch(() => showToast('Network error', true));
}

/* ==================================================================
   FOLLOW POST CREATOR (Post card follow button)
   POST /follow/{id}
   ================================================================== */
function followPostCreator(id, btn) {
    const label = btn.querySelector('.follow-label');
    const wasFollowing = btn.dataset.following === '1';

    // Optimistic UI
    if (wasFollowing) {
        btn.dataset.following = '0';
        label.textContent = 'Follow';
        btn.style.background = 'linear-gradient(135deg, #834ae5, #6b21a8)';
        btn.style.color = '#ffffff';
        btn.style.border = 'none';
    } else {
        btn.dataset.following = '1';
        label.textContent = 'Following';
        btn.style.background = '#1e1e2a';
        btn.style.color = '#a1a1aa';
        btn.style.border = '1px solid #3f3f46';
    }

    apiPost('/follow/' + id).then(data => {
        if (data.error) {
            // Revert
            if (wasFollowing) {
                btn.dataset.following = '1';
                label.textContent = 'Following';
                btn.style.background = '#1e1e2a';
                btn.style.color = '#a1a1aa';
                btn.style.border = '1px solid #3f3f46';
            } else {
                btn.dataset.following = '0';
                label.textContent = 'Follow';
                btn.style.background = 'linear-gradient(135deg, #834ae5, #6b21a8)';
                btn.style.color = '#ffffff';
                btn.style.border = 'none';
            }
            showToast(data.error, true);
            return;
        }
        showToast(data.message || (data.following ? 'Following!' : 'Unfollowed'));
    }).catch(() => showToast('Network error', true));
}

/* ==================================================================
   CATEGORY FILTER
   ================================================================== */
function selectCategory(btn) {
    document.querySelectorAll('.category-chip').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

// ===== SMART SPOTLIGHT CAROUSEL =====
(function() {
    const track = document.getElementById('spotlightTrack');
    const dotsContainer = document.getElementById('spotlightDots');
    if (!track || !dotsContainer) return;

    const dots = dotsContainer.querySelectorAll('.spotlight-dot');
    let currentSlide = 0;
    let totalSlides = dots.length;
    let autoScrollInterval;
    let isPaused = false;
    let touchStartX = 0;
    let touchEndX = 0;

    function getCardWidth() {
        const card = track.querySelector('.spotlight-card');
        if (!card) return 0;
        const style = getComputedStyle(card);
        return card.offsetWidth + parseFloat(style.marginLeft || 0) + 12; // gap
    }

    function updateDots(index) {
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }

    function goToSlide(index) {
        if (index < 0) index = totalSlides - 1;
        if (index >= totalSlides) index = 0;
        currentSlide = index;

        const cardWidth = getCardWidth();
        const scrollPos = cardWidth * index;
        track.scrollTo({ left: scrollPos, behavior: 'smooth' });
        updateDots(index);

        // Reset auto-scroll timer
        resetAutoScroll();
    }

    function nextSlide() {
        goToSlide(currentSlide + 1);
    }

    function startAutoScroll() {
        autoScrollInterval = setInterval(() => {
            if (!isPaused) nextSlide();
        }, 4000);
    }

    function resetAutoScroll() {
        clearInterval(autoScrollInterval);
        startAutoScroll();
    }

    // Detect scroll position to sync dots
    let scrollTimeout;
    track.addEventListener('scroll', () => {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            const cardWidth = getCardWidth();
            if (cardWidth <= 0) return;
            const newIndex = Math.round(track.scrollLeft / cardWidth);
            if (newIndex !== currentSlide && newIndex >= 0 && newIndex < totalSlides) {
                currentSlide = newIndex;
                updateDots(currentSlide);
                resetAutoScroll();
            }
        }, 100);
    });

    // Pause on hover/touch
    track.addEventListener('mouseenter', () => { isPaused = true; });
    track.addEventListener('mouseleave', () => { isPaused = false; });
    track.addEventListener('touchstart', (e) => {
        isPaused = true;
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    track.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) < 10) return;
        // Don't auto-advance if user swiped
        resetAutoScroll();
        setTimeout(() => { isPaused = false; }, 2000);
    }, { passive: true });

    // Make goToSlide available globally for dot clicks
    window.goToSlide = goToSlide;

    // Start auto-scroll
    startAutoScroll();
})();
</script>

<!-- ===== STORY POPUP VIEWER ===== -->
<style>
    .story-popup-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 200;
        background: #000;
        overflow: hidden;
        touch-action: pan-y;
    }
    .story-popup-overlay.active { display: block; }

    .story-popup-overlay .sp-stage {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .story-popup-overlay .sp-stage img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .story-popup-overlay .sp-text {
        position: absolute;
        left: 32px;
        right: 32px;
        text-align: center;
        text-shadow: 0 2px 12px rgba(0,0,0,0.9), 0 0 30px rgba(0,0,0,0.6);
        pointer-events: none;
        line-height: 1.35;
        z-index: 2;
    }
    .story-popup-overlay .sp-text.sp-text-top { top: 100px; }
    .story-popup-overlay .sp-text.sp-text-center { top: 50%; transform: translateY(-50%); }
    .story-popup-overlay .sp-text.sp-text-bottom { bottom: 100px; }

    /* Progress Bars */
    .sp-progress-bars {
        position: absolute;
        top: 12px;
        left: 12px;
        right: 12px;
        display: flex;
        gap: 4px;
        z-index: 10;
    }
    .sp-progress-track {
        flex: 1;
        height: 3px;
        background: rgba(255,255,255,0.25);
        border-radius: 2px;
        overflow: hidden;
    }
    .sp-progress-fill {
        height: 100%;
        background: #ffffff;
        border-radius: 2px;
        width: 0%;
        transition: width 0.1s linear;
    }
    .sp-progress-fill.sp-done {
        width: 100%;
        transition: none;
    }

    /* Header */
    .sp-top-bar {
        position: absolute;
        top: 24px;
        left: 16px;
        right: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 10;
    }
    .sp-top-bar .sp-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.4);
        object-fit: cover;
    }
    .sp-top-bar .sp-info { flex: 1; min-width: 0; }
    .sp-top-bar .sp-info .sp-name {
        color: white;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .sp-top-bar .sp-info .sp-time {
        color: rgba(255,255,255,0.6);
        font-size: 11px;
    }
    .sp-close-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 20px;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    .sp-close-btn:hover { background: rgba(255,255,255,0.25); }

    /* Bottom actions */
    .sp-bottom-bar {
        position: absolute;
        bottom: 32px;
        left: 16px;
        right: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        z-index: 10;
    }
    .sp-reply-input {
        flex: 1;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 24px;
        padding: 10px 16px;
        color: white;
        font-size: 13px;
        outline: none;
    }
    .sp-reply-input::placeholder { color: rgba(255,255,255,0.4); }
    .sp-views-badge {
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 8px 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        color: white;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Nav zones */
    .sp-nav-left, .sp-nav-right {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 50%;
        z-index: 5;
    }
    .sp-nav-left { left: 0; cursor: pointer; }
    .sp-nav-right { right: 0; cursor: pointer; }

    /* Loading spinner */
    .sp-loading {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 15;
    }
    .sp-loading .spinner {
        width: 36px;
        height: 36px;
        border: 3px solid rgba(131,74,229,0.3);
        border-top-color: #834ae5;
        border-radius: 50%;
        animation: sp-spin 0.7s linear infinite;
    }
    @keyframes sp-spin { to { transform: rotate(360deg); } }
</style>

<div class="story-popup-overlay" id="storyPopupOverlay">
    <!-- Loading -->
    <div class="sp-loading" id="spLoading">
        <div class="spinner"></div>
    </div>

    <!-- Progress Bars -->
    <div class="sp-progress-bars" id="spProgressBars"></div>

    <!-- Top Bar -->
    <div class="sp-top-bar" id="spTopBar" style="display:none;">
        <img id="spAvatar" src="" alt="" class="sp-avatar">
        <div class="sp-info">
            <div class="sp-name" id="spName"></div>
            <div class="sp-time" id="spTime"></div>
        </div>
        <div class="sp-views-badge" id="spViewsBadge">
            <span class="material-icons-round" style="font-size:14px;">visibility</span>
            <span id="spViewCount">0</span>
        </div>
        <button class="sp-close-btn" onclick="closeStoryPopup()" title="Close">
            <span class="material-icons-round">close</span>
        </button>
    </div>

    <!-- Story Stage -->
    <div class="sp-stage" id="spStage" style="display:none;">
        <img id="spImage" src="" alt="Story">
        <div id="spText" class="sp-text sp-text-center" style="display:none;"></div>
    </div>

    <!-- Navigation Zones -->
    <div class="sp-nav-left" onclick="spPrevStory()"></div>
    <div class="sp-nav-right" onclick="spNextStory()"></div>

    <!-- Bottom Bar -->
    <div class="sp-bottom-bar" id="spBottomBar" style="display:none;">
        <input type="text" class="sp-reply-input" placeholder="Send message..." id="spReplyInput"
               onkeydown="if(event.key==='Enter')spSendReply();">
        <button class="sp-close-btn" style="width:40px;height:40px;" onclick="spSendReply()" title="Send">
            <span class="material-icons-round" style="font-size:20px;">send</span>
        </button>
    </div>
</div>

<script>
(function() {
    let spStories = [];
    let spCurrentIndex = 0;
    let spInterval = null;
    let spProgress = 0;
    const SP_DURATION = 5000;
    const SP_TICK = 50;
    let spTouchStartX = 0;
    let spTouchStartY = 0;

    const overlay = document.getElementById('storyPopupOverlay');
    const spLoading = document.getElementById('spLoading');
    const spProgressBars = document.getElementById('spProgressBars');
    const spTopBar = document.getElementById('spTopBar');
    const spStage = document.getElementById('spStage');
    const spBottomBar = document.getElementById('spBottomBar');

    window.openStoryViewer = function(event, storyId, userId) {
        event.preventDefault();
        event.stopPropagation();

        // Show overlay with loading
        overlay.classList.add('active');
        spLoading.style.display = 'flex';
        spTopBar.style.display = 'none';
        spStage.style.display = 'none';
        spBottomBar.style.display = 'none';
        document.body.style.overflow = 'hidden';

        fetch('/stories/view/' + storyId)
            .then(r => r.json())
            .then(data => {
                spLoading.style.display = 'none';
                if (data.error) {
                    closeStoryPopup();
                    return;
                }
                spStories = (data.user_stories || []).map(function(s) {
                    return {
                        id: s.id,
                        image_url: s.image_url,
                        text_content: s.text_content || '',
                        text_position: s.text_position || 'center',
                        text_color: s.text_color || '#ffffff',
                        text_size: s.text_size || '24',
                        font_style: s.font_style || 'normal',
                        views_count: s.views_count || 0,
                        name: s.name,
                        username: s.username,
                        avatar: s.avatar,
                        is_verified: s.is_verified || false,
                        created_at: s.created_at,
                    };
                });
                spCurrentIndex = data.current_index || 0;

                // Build progress bars
                spProgressBars.innerHTML = '';
                spStories.forEach(function(_, i) {
                    const track = document.createElement('div');
                    track.className = 'sp-progress-track';
                    const fill = document.createElement('div');
                    fill.className = 'sp-progress-fill';
                    fill.dataset.index = i;
                    track.appendChild(fill);
                    spProgressBars.appendChild(track);
                });

                spTopBar.style.display = 'flex';
                spStage.style.display = 'flex';
                spBottomBar.style.display = 'flex';

                spLoadStory(spCurrentIndex);
            })
            .catch(function() {
                spLoading.style.display = 'none';
                closeStoryPopup();
            });
    };

    function spLoadStory(index) {
        if (index < 0 || index >= spStories.length) {
            closeStoryPopup();
            return;
        }
        spCurrentIndex = index;
        const s = spStories[index];

        // Image
        document.getElementById('spImage').src = s.image_url;

        // Text overlay
        const textEl = document.getElementById('spText');
        if (s.text_content) {
            textEl.textContent = s.text_content;
            textEl.className = 'sp-text sp-text-' + s.text_position;
            textEl.style.color = s.text_color;
            textEl.style.fontSize = s.text_size + 'px';
            textEl.style.fontWeight = s.font_style === 'bold' ? '700' : (s.font_style === 'italic' ? '400' : '500');
            textEl.style.fontStyle = s.font_style === 'italic' ? 'italic' : 'normal';
            textEl.style.display = '';
        } else {
            textEl.style.display = 'none';
        }

        // Header
        document.getElementById('spAvatar').src = s.avatar;
        document.getElementById('spName').innerHTML =
            s.name + (s.is_verified ? ' <span class="material-icons-round" style="font-size:14px;color:#834ae5;">verified</span>' : '');
        document.getElementById('spTime').textContent = spTimeAgo(s.created_at);
        document.getElementById('spViewCount').textContent = s.views_count;

        // Progress bars
        var fills = spProgressBars.querySelectorAll('.sp-progress-fill');
        fills.forEach(function(f, i) {
            f.classList.remove('sp-done');
            f.style.width = '0%';
            if (i < spCurrentIndex) f.classList.add('sp-done');
        });

        spStartProgress();
    }

    function spStartProgress() {
        clearInterval(spInterval);
        spProgress = 0;
        var fills = spProgressBars.querySelectorAll('.sp-progress-fill');
        var activeFill = fills[spCurrentIndex];
        if (activeFill) {
            activeFill.classList.remove('sp-done');
            activeFill.style.width = '0%';
        }

        spInterval = setInterval(function() {
            spProgress += SP_TICK;
            var pct = Math.min((spProgress / SP_DURATION) * 100, 100);
            if (activeFill) activeFill.style.width = pct + '%';

            if (spProgress >= SP_DURATION) {
                clearInterval(spInterval);
                if (activeFill) activeFill.classList.add('sp-done');
                setTimeout(spNextStory, 200);
            }
        }, SP_TICK);
    }

    window.spNextStory = function() {
        var fills = spProgressBars.querySelectorAll('.sp-progress-fill');
        if (fills[spCurrentIndex]) fills[spCurrentIndex].classList.add('sp-done');
        spLoadStory(spCurrentIndex + 1);
    };

    window.spPrevStory = function() {
        if (spCurrentIndex > 0) {
            var fills = spProgressBars.querySelectorAll('.sp-progress-fill');
            if (fills[spCurrentIndex - 1]) fills[spCurrentIndex - 1].classList.remove('sp-done');
            spLoadStory(spCurrentIndex - 1);
        }
    };

    window.closeStoryPopup = function() {
        clearInterval(spInterval);
        spStories = [];
        spCurrentIndex = 0;
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        spProgressBars.innerHTML = '';
    };

    window.spSendReply = function() {
        var input = document.getElementById('spReplyInput');
        var msg = input.value.trim();
        if (!msg || !spStories[spCurrentIndex]) return;
        var s = spStories[spCurrentIndex];
        window.location.href = '/messages/create?to=' + s.username + '&text=' + encodeURIComponent('Replied to your story: ' + msg);
    };

    function spTimeAgo(dateStr) {
        if (!dateStr) return '';
        var now = new Date();
        var date = new Date(dateStr.replace(/-/g, '/'));
        var diff = Math.floor((now - date) / 1000);
        if (diff < 60) return 'just now';
        if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
        return Math.floor(diff / 86400) + 'd ago';
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!overlay.classList.contains('active')) return;
        if (e.key === 'ArrowLeft') spPrevStory();
        if (e.key === 'ArrowRight') spNextStory();
        if (e.key === 'Escape') closeStoryPopup();
    });

    // Touch swipe navigation
    overlay.addEventListener('touchstart', function(e) {
        spTouchStartX = e.changedTouches[0].screenX;
        spTouchStartY = e.changedTouches[0].screenY;
        clearInterval(spInterval); // Pause
    }, { passive: true });

    overlay.addEventListener('touchend', function(e) {
        var diffX = e.changedTouches[0].screenX - spTouchStartX;
        var diffY = e.changedTouches[0].screenY - spTouchStartY;
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 60) {
            if (diffX > 0) spPrevStory();
            else spNextStory();
        } else {
            spStartProgress();
        }
    }, { passive: true });
})();
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
