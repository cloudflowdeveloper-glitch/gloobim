<?php $activeTab = 'clips'; $title = 'Clips - Globiim'; $hideTopNav = true; $hideBottomNav = false; ?>
<?php ob_start(); ?>

<style>
    * { box-sizing: border-box; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .animate-spin-slow { animation: spin 4s linear infinite; }

    /* Video */
    .clip-video { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 0; }
    .video-loader { z-index: 5; transition: opacity 0.3s ease; }
    .video-loader.hidden-loader { opacity: 0; pointer-events: none; }

    /* Scroll */
    #clipsContainer { scroll-snap-type: y mandatory; overflow-y: scroll; -ms-overflow-style: none; scrollbar-width: none; }
    #clipsContainer::-webkit-scrollbar { display: none; }
    .clip-item { scroll-snap-align: start; }

    /* Animations */
    @keyframes fade-in { 0% { opacity: 0; } 100% { opacity: 1; } }
    .fade-in { animation: fade-in 0.25s ease-out forwards; }
    @keyframes slide-up { 0% { transform: translateY(20px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
    .slide-up { animation: slide-up 0.3s ease-out forwards; }

    /* Action buttons */
    .action-icon { transition: all 0.2s ease; }
    .action-icon:active { transform: scale(0.85); }
    .action-icon:hover .action-circle { background: rgba(255,255,255,0.15); }
    .action-circle { width: 44px; height: 44px; border-radius: 50%; background: rgba(0,0,0,0.35); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.08); transition: all 0.2s ease; }

    /* Play button */
    .play-circle { width: 48px; height: 48px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.7); background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; transition: all 0.25s ease; }
    .play-circle:active { transform: scale(0.9); }

    /* Progress bar */
    .progress-bar-track { position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: rgba(255,255,255,0.15); z-index: 25; }
    .progress-bar-fill { height: 100%; background: linear-gradient(90deg, #3b82f6, #6366f1); width: 0%; transition: width 0.25s linear; }

    /* Nav tabs */
    .nav-tab { position: relative; transition: all 0.2s ease; }
    .nav-tab.active::after { content: ''; position: absolute; bottom: -6px; left: 50%; transform: translateX(-50%); width: 24px; height: 3px; border-radius: 2px; background: linear-gradient(90deg, #3b82f6, #6366f1); }

    /* Creator avatar ring */
    .creator-ring { background: linear-gradient(135deg, #e82c3d, #f97316); padding: 2.5px; border-radius: 50%; }

    /* Follow button */
    .follow-btn { border: 1.5px solid rgba(59,130,246,0.6); background: transparent; color: #3b82f6; transition: all 0.2s ease; }
    .follow-btn:hover { background: rgba(59,130,246,0.1); }
    .follow-btn.following { border-color: rgba(255,255,255,0.2); color: #a1a1aa; background: rgba(255,255,255,0.05); }

    /* Double tap heart */
    @keyframes double-tap-burst { 0% { transform: translate(-50%,-50%) scale(0); opacity: 1; } 50% { transform: translate(-50%,-50%) scale(1.4); opacity: 1; } 100% { transform: translate(-50%,-50%) scale(1); opacity: 0; } }
    .double-tap-heart { position: absolute; top: 50%; left: 50%; z-index: 30; pointer-events: none; animation: double-tap-burst 0.8s ease-out forwards; }

    /* Chat input */
    .chat-input:focus { outline: none; border-color: #3b82f6; }

    /* Music marquee */
    .music-scroll { overflow: hidden; white-space: nowrap; }
    .music-scroll-inner { display: inline-block; animation: marquee 8s linear infinite; }
    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

    /* Spinning disc */
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .spin-disc { animation: spin 4s linear infinite; }

    /* Notification dot */
    .notif-dot { position: absolute; top: -2px; right: -2px; min-width: 6px; height: 6px; border-radius: 50%; background: #ef4444; }
</style>

<div id="clipsContainer" class="relative w-full overflow-hidden" style="height: 100vh;">
    <div id="clipsSlider">

        <?php
        if (empty($reels)) {
            $reels = [
                ['id' => 1, 'creator_name' => 'Aisha Rahman', 'username' => 'aisharahman', 'creator_avatar' => '/uploads/profiles/admin.jpg', 'is_verified' => 1, 'title' => 'Studio Session', 'description' => 'Behind the scenes of my new song 🎵 What do you think? 👀 #NewMusic #StudioVibes #Globiim', 'thumbnail' => '/uploads/thumbnails/reel_thumb_1.jpg', 'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4', 'views' => 128000, 'likes' => 128000, 'comments_count' => 1200, 'shares' => 1200, 'reposts' => 3600, 'song_name' => 'Original Sound - Aisha Rahman', 'duration' => 30, 'tags' => ['NewMusic', 'StudioVibes'], 'product' => null, 'level' => 24],
                ['id' => 2, 'creator_name' => 'DJ Khalid', 'username' => 'djkhalid', 'creator_avatar' => '/uploads/profiles/admin.jpg', 'is_verified' => 1, 'title' => 'Night Vibes Mix', 'description' => 'Dropping fire beats tonight 🔥🎧 #DJ #Beats #NightVibes', 'thumbnail' => '/uploads/thumbnails/reel_thumb_1.jpg', 'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4', 'views' => 89000, 'likes' => 89000, 'comments_count' => 2400, 'shares' => 3400, 'reposts' => 5100, 'song_name' => 'Original Sound - DJ Khalid', 'duration' => 45, 'tags' => ['DJ', 'Beats'], 'product' => ['name' => 'Mixtape', 'price' => 'KES 500'], 'level' => 31],
                ['id' => 3, 'creator_name' => 'TechBro', 'username' => 'techbro', 'creator_avatar' => '/uploads/profiles/admin.jpg', 'is_verified' => 1, 'title' => 'AI Art is Crazy', 'description' => 'Watch me create art with AI in 60 seconds! 🤖🎨 #ai #art #tech #Globiim', 'thumbnail' => '/uploads/thumbnails/reel_thumb_1.jpg', 'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4', 'views' => 245000, 'likes' => 62000, 'comments_count' => 4100, 'shares' => 12300, 'reposts' => 8500, 'song_name' => 'Digital Dreams - SynthWave', 'duration' => 58, 'tags' => ['ai', 'art', 'tech'], 'product' => null, 'level' => 27],
                ['id' => 4, 'creator_name' => 'LaughKing', 'username' => 'laughking', 'creator_avatar' => '/uploads/profiles/admin.jpg', 'is_verified' => 1, 'title' => 'African Mom Discovers WiFi', 'description' => 'When mom finds out the WiFi password 😂😂 #comedy #africanmom #Globiim', 'thumbnail' => '/uploads/thumbnails/reel_thumb_1.jpg', 'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4', 'views' => 450000, 'likes' => 95000, 'comments_count' => 7200, 'shares' => 18500, 'reposts' => 12000, 'song_name' => 'Funny Background - LOL Beat', 'duration' => 35, 'tags' => ['comedy', 'africanmom'], 'product' => ['name' => 'Merch Tee', 'price' => 'KES 800'], 'level' => 19],
            ];
        }
        ?>

        <?php foreach ($reels as $index => $reel): ?>
        <?php $videoUrl = $reel['video_url'] ?? ''; $thumbnail = $reel['thumbnail'] ?? ''; ?>
        <div class="clip-item relative w-full bg-black" style="height: 100vh; padding-bottom: 56px;" data-reel-id="<?= $reel['id'] ?>" data-index="<?= $index ?>" data-playing="false">

            <!-- VIDEO -->
            <?php if (!empty($videoUrl)): ?>
            <video class="clip-video" src="<?= htmlspecialchars($videoUrl) ?>" poster="<?= htmlspecialchars($thumbnail) ?>" loop muted playsinline preload="metadata" data-video-index="<?= $index ?>"></video>
            <?php else: ?>
            <img src="<?= htmlspecialchars($thumbnail) ?>" alt="<?= htmlspecialchars($reel['title'] ?? '') ?>" class="clip-video" style="object-fit: cover;">
            <?php endif; ?>

            <!-- LOADING -->
            <?php if (!empty($videoUrl)): ?>
            <div class="video-loader absolute inset-0 flex items-center justify-center bg-black/80" data-loader="<?= $index ?>">
                <div class="w-10 h-10 border-[3px] border-white/30 border-t-white rounded-full" style="animation: spin 0.8s linear infinite;"></div>
            </div>
            <?php endif; ?>

            <!-- GRADIENTS -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40 pointer-events-none z-[1]"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-transparent to-transparent pointer-events-none z-[1]" style="height: 160px;"></div>

            <!-- TAP AREA -->
            <div class="absolute inset-0 z-[2]" id="tapArea<?= $index ?>" ondblclick="doubleTapLike(event, <?= $index ?>)" onclick="togglePlay(<?= $index ?>)"></div>

            <!-- ===== TOP NAV BAR ===== -->
            <div class="absolute top-0 left-0 right-0 z-[15] pointer-events-auto" style="background: linear-gradient(to bottom, rgba(0,0,0,0.8) 0%, transparent 100%); padding: 10px 16px 24px;">
                <!-- Icons Row -->
                <div class="flex items-center justify-end mb-3">
                    <div class="flex items-center gap-3">
                        <button onclick="toggleSearch()" class="hover:opacity-70 transition-opacity">
                            <span class="material-icons-round text-white text-[22px]">search</span>
                        </button>
                        <button onclick="showToast('Bookmarks')" class="hover:opacity-70 transition-opacity">
                            <span class="material-icons-round text-white text-[22px]">bookmark_border</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===== RIGHT-SIDE ACTION BUTTONS ===== -->
            <div class="absolute right-3 z-[10] flex flex-col items-center gap-3 pointer-events-auto" style="bottom: 180px;">

                <!-- Like -->
                <button onclick="event.stopPropagation(); toggleLike(this)" class="action-icon flex flex-col items-center gap-0.5">
                    <div class="action-circle">
                        <span class="material-icons-round text-white text-[26px] like-icon">favorite_border</span>
                    </div>
                    <span class="text-white text-[11px] font-semibold like-count"><?= formatCount($reel['likes'] ?? 0) ?></span>
                </button>

                <!-- Comment -->
                <button onclick="event.stopPropagation(); openComments(<?= $reel['id'] ?>)" class="action-icon flex flex-col items-center gap-0.5">
                    <div class="action-circle">
                        <span class="material-icons-round text-white text-[26px]">chat_bubble</span>
                    </div>
                    <span class="text-white text-[11px] font-semibold"><?= formatCount($reel['comments_count'] ?? 0) ?></span>
                </button>

                <!-- Share -->
                <button onclick="event.stopPropagation(); shareClip(<?= $reel['id'] ?>)" class="action-icon flex flex-col items-center gap-0.5">
                    <div class="action-circle">
                        <span class="material-icons-round text-white text-[26px]">send</span>
                    </div>
                    <span class="text-white text-[11px] font-semibold"><?= formatCount($reel['shares'] ?? 0) ?></span>
                </button>

                <!-- Repost -->
                <button onclick="event.stopPropagation(); repostClip(<?= $reel['id'] ?>, this)" class="action-icon flex flex-col items-center gap-0.5">
                    <div class="action-circle">
                        <span class="material-icons-round text-white text-[26px]">autorenew</span>
                    </div>
                    <span class="text-white text-[11px] font-semibold repost-count"><?= formatCount($reel['reposts'] ?? 0) ?></span>
                </button>

                <!-- Gift -->
                <button onclick="event.stopPropagation(); openReelGiftPanel(<?= $reel['id'] ?>)" class="action-icon flex flex-col items-center gap-0.5">
                    <div class="action-circle">
                        <span class="material-icons-round text-white text-[26px]">card_giftcard</span>
                    </div>
                    <span class="text-white text-[11px] font-semibold">Gift</span>
                </button>

                <!-- More -->
                <button onclick="event.stopPropagation(); toggleReelMenu()" class="action-icon flex flex-col items-center gap-0.5">
                    <div class="action-circle">
                        <span class="material-icons-round text-white text-[26px]">more_horiz</span>
                    </div>
                    <span class="text-white text-[11px] font-semibold">More</span>
                </button>

                <!-- Spinning Music Disc -->
                <div class="w-10 h-10 rounded-full border-2 border-white/30 overflow-hidden spin-disc mt-1">
                    <img src="<?= $reel['creator_avatar'] ?? '/uploads/music/artists/avatar_1.jpg' ?>" alt="Music" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- ===== BOTTOM: Creator Info + Description + Music ===== -->
            <div class="absolute bottom-[120px] left-3 right-20 z-[10] pointer-events-none">
                <!-- Creator Info -->
                <div class="flex items-center gap-2.5 mb-2 pointer-events-auto">
                    <div class="creator-ring flex-shrink-0">
                        <img src="<?= $reel['creator_avatar'] ?? '/uploads/profiles/admin.jpg' ?>" alt="<?= $reel['creator_name'] ?>" class="w-9 h-9 rounded-full border-2 border-black object-cover">
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <span class="text-white text-[14px] font-bold truncate"><?= htmlspecialchars($reel['creator_name'] ?? '') ?></span>
                            <?php if (!empty($reel['is_verified'])): ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="#3b82f6"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                            <?php endif; ?>
<?php $reelIsFollowing = !empty($reel['is_following']); ?>
                            <button onclick="event.stopPropagation(); toggleFollowBtn(this, <?= $reel['user_id'] ?? 0 ?>)" class="follow-btn px-4 py-1 rounded-full text-[11px] font-semibold ml-1 flex-shrink-0 <?= $reelIsFollowing ? 'following' : '' ?>"><?= $reelIsFollowing ? 'Following' : 'Follow' ?></button>
                        </div>
                        <div class="flex items-center gap-1 mt-0.5">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="#a855f7"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span class="text-zinc-400 text-[10px]">Lv. <?= $reel['level'] ?? 1 ?> Creator</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <p class="text-white text-[13px] leading-snug mb-1.5 pointer-events-auto">
                    <?= htmlspecialchars($reel['description'] ?? $reel['title'] ?? '') ?>
                </p>

                <!-- Hashtags (blue) -->
                <?php
                $hashtags = '';
                $desc = $reel['description'] ?? '';
                preg_match_all('/#(\w+)/', $desc, $matches);
                if (!empty($matches[0])) {
                    foreach ($matches[0] as $tag) {
                        $hashtags .= '<span class="text-blue-400 text-[12px] font-medium cursor-pointer hover:underline">' . htmlspecialchars($tag) . '</span> ';
                    }
                }
                $tags = $reel['tags'] ?? [];
                if (is_array($tags)) {
                    foreach ($tags as $tag) {
                        $hashtags .= '<span class="text-blue-400 text-[12px] font-medium cursor-pointer hover:underline">#' . htmlspecialchars($tag) . '</span> ';
                    }
                }
                ?>
                <?php if (!empty($hashtags)): ?>
                <div class="mb-2"><?= $hashtags ?></div>
                <?php endif; ?>

                <!-- Music Marquee -->
                <div class="flex items-center gap-2">
                    <span class="material-icons-round text-white text-[14px]">music_note</span>
                    <div class="music-scroll flex-1">
                        <span class="music-scroll-inner text-zinc-300 text-[12px]"><?= htmlspecialchars($reel['song_name'] ?? 'Original Sound') ?> &nbsp;·&nbsp; <?= htmlspecialchars($reel['song_name'] ?? 'Original Sound') ?> &nbsp;·&nbsp; <?= htmlspecialchars($reel['song_name'] ?? 'Original Sound') ?></span>
                    </div>
                </div>
            </div>

            <!-- PROGRESS BAR -->
            <?php if (!empty($videoUrl)): ?>
            <div class="progress-bar-track" data-progress-track="<?= $index ?>">
                <div class="progress-bar-fill" data-progress-fill="<?= $index ?>"></div>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
    </div>

    <!-- ===== SEARCH PANEL ===== -->
    <div id="searchPanel" class="hidden fixed top-14 left-0 right-0 z-[40] fade-in">
        <div class="max-w-lg mx-auto px-3">
            <div class="relative">
                <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-lg">search</span>
                <input type="text" id="clipSearchInput" placeholder="Search clips, creators, sounds..." class="w-full bg-black/80 backdrop-blur-xl text-white pl-10 pr-10 py-3 rounded-xl border border-white/10 focus:border-[#3b82f6] focus:outline-none text-sm placeholder:text-zinc-500 transition-all" onkeydown="if(event.key==='Enter')searchClips()">
                <button onclick="closeSearch()" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 rounded-full hover:bg-white/10 transition-colors">
                    <span class="material-icons-round text-zinc-400 text-lg">close</span>
                </button>
            </div>
            <div class="mt-2 bg-black/80 backdrop-blur-xl rounded-xl border border-white/10 overflow-hidden">
                <div class="p-2">
                    <div class="px-3 py-2 text-zinc-500 text-[10px] font-semibold uppercase tracking-wider">Trending</div>
                    <?php foreach (['Dance Challenge', 'Comedy Skits', 'Cooking', 'AI Art', 'Music Covers', 'Studio Vibes'] as $term): ?>
                    <button onclick="searchClipsFor('<?= $term ?>')" class="w-full flex items-center gap-3 px-3 py-2.5 hover:bg-white/5 rounded-lg transition-colors">
                        <span class="material-icons-round text-zinc-500 text-[16px]">trending_up</span>
                        <span class="text-white text-sm"><?= $term ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MORE MENU ===== -->
    <div id="reelMenu" class="hidden fixed top-1/2 right-14 -translate-y-1/2 z-[40] bg-black/80 backdrop-blur-xl rounded-2xl border border-white/10 overflow-hidden shadow-2xl min-w-[180px] fade-in">
        <button onclick="showToast('Saved! 📌'); closeReelMenu()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg">bookmark_border</span>
            <span class="text-zinc-200 text-sm font-medium">Save</span>
        </button>
        <button onclick="showToast('Download started ⬇️'); closeReelMenu()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg">download</span>
            <span class="text-zinc-200 text-sm font-medium">Download</span>
        </button>
        <button onclick="shareClip(<?= $reel['id'] ?? 1 ?>); closeReelMenu()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg">share</span>
            <span class="text-zinc-200 text-sm font-medium">Share to...</span>
        </button>
        <button onclick="showToast('Reported'); closeReelMenu()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg">report</span>
            <span class="text-zinc-200 text-sm font-medium">Report</span>
        </button>
        <button onclick="showToast('Not interested'); closeReelMenu()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg">block</span>
            <span class="text-zinc-200 text-sm font-medium">Not Interested</span>
        </button>
    </div>

    <!-- ===== COMMENTS PANEL ===== -->
    <div id="commentsPanel" class="hidden fixed bottom-0 left-0 right-0 z-[35] bg-black/90 backdrop-blur-xl rounded-t-2xl border-t border-white/10 max-h-[55%] flex flex-col slide-up">
        <div class="flex items-center justify-between px-4 py-3 border-b border-white/10">
            <span class="text-white text-sm font-bold"><?= formatCount($reel['comments_count'] ?? 0) ?> Comments</span>
            <button onclick="closeComments()" class="p-1 rounded-full hover:bg-white/10 transition-colors">
                <span class="material-icons-round text-zinc-400 text-xl">close</span>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto px-4 py-3 space-y-3 scrollbar-hide" id="commentsScroll">
            <?php for ($i = 0; $i < 6; $i++):
                $commenters = ['Aisha Rahman', 'DJ Khalid', 'TechBro', 'LaughKing', 'Mercy Mwangi', 'Steve 254'];
                $texts = ['This is 🔥🔥🔥', 'No way!! 😂', 'Tutorial please 👏', 'First! 💪', 'Can\'t stop watching 🔄', 'Top quality 💯'];
                $colors = ['#6d28d9', '#e82c3d', '#2563eb', '#f59e0b', '#ec4899', '#10b981'];
            ?>
            <div class="flex gap-2.5">
                <img src="/uploads/profiles/admin.jpg" class="w-8 h-8 rounded-full flex-shrink-0">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-white text-xs font-semibold"><?= $commenters[$i] ?></span>
                        <span class="text-zinc-600 text-[10px]"><?= $i + 1 ?>h</span>
                    </div>
                    <p class="text-zinc-200 text-[13px]"><?= $texts[$i] ?></p>
                    <div class="flex items-center gap-3 mt-0.5">
                        <button class="text-zinc-600 text-[10px] font-medium hover:text-zinc-400">Reply</button>
                        <button class="flex items-center gap-0.5 text-zinc-600 text-[10px] hover:text-zinc-400"><span class="material-icons-round text-[10px]">favorite_border</span> <?= rand(5, 200) ?></button>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
        </div>
        <div class="px-4 py-2.5 border-t border-white/10 flex items-center gap-2">
            <img src="/uploads/profiles/admin.jpg" class="w-7 h-7 rounded-full flex-shrink-0">
            <input type="text" id="commentInput" placeholder="Add a comment..." class="chat-input flex-1 bg-white/10 text-white px-3 py-2 rounded-full text-[12px] placeholder:text-zinc-500 border border-white/10" onkeydown="if(event.key==='Enter')sendComment()">
            <button onclick="sendComment()" class="text-[12px] font-bold text-blue-400 flex-shrink-0">Post</button>
        </div>
    </div>

    <!-- ===== GIFT PANEL ===== -->
    <div id="reelGiftPanel" class="fixed inset-0 z-50 hidden" onclick="closeReelGiftPanel(event)">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="absolute bottom-0 left-0 right-0 bg-[#0a0a14] rounded-t-3xl max-w-lg mx-auto slide-up" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-4 border-b border-white/10">
                <div class="flex items-center gap-2">
                    <span class="material-icons-round text-amber-400 text-lg">monetization_on</span>
                    <h3 class="text-white font-bold text-lg">Send a Gift</h3>
                </div>
                <button onclick="closeReelGiftPanel()" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                    <span class="material-icons-round text-zinc-400 text-lg">close</span>
                </button>
            </div>
            <div class="p-4">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-zinc-400 text-sm">Balance:</span>
                    <span class="text-white text-sm font-bold">$1,250.00</span>
                </div>
                <div class="grid grid-cols-4 gap-3" id="reelGiftGrid">
                    <?php
                    $clipGifts = [
                        ['id' => 1, 'name' => 'Heart', 'icon' => 'favorite', 'price' => 0.50, 'color' => 'text-red-400'],
                        ['id' => 2, 'name' => 'Fire', 'icon' => 'local_fire_department', 'price' => 1.00, 'color' => 'text-orange-400'],
                        ['id' => 3, 'name' => 'Star', 'icon' => 'star', 'price' => 2.50, 'color' => 'text-yellow-400'],
                        ['id' => 4, 'name' => 'Diamond', 'icon' => 'diamond', 'price' => 5.00, 'color' => 'text-cyan-400'],
                        ['id' => 5, 'name' => 'Crown', 'icon' => 'military_tech', 'price' => 10.00, 'color' => 'text-amber-400'],
                        ['id' => 6, 'name' => 'Rocket', 'icon' => 'rocket_launch', 'price' => 20.00, 'color' => 'text-purple-400'],
                        ['id' => 7, 'name' => 'Party', 'icon' => 'celebration', 'price' => 50.00, 'color' => 'text-pink-400'],
                        ['id' => 8, 'name' => 'Super', 'icon' => 'bolt', 'price' => 100.00, 'color' => 'text-purple-300'],
                    ];
                    ?>
                    <?php foreach ($clipGifts as $g): ?>
                    <button onclick="sendClipGift(<?= $reel['id'] ?? 1 ?>, <?= $g['id'] ?>)" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-blue-500/40 transition-all group" data-gift-id="<?= $g['id'] ?>">
                        <span class="material-icons-round <?= $g['color'] ?> text-2xl group-hover:scale-110 transition-transform"><?= $g['icon'] ?></span>
                        <span class="text-zinc-300 text-[10px] font-medium"><?= $g['name'] ?></span>
                        <span class="text-blue-400 text-[9px] font-bold">$<?= number_format((float)$g['price'], 2) ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
let currentReel = 0;
const totalReels = <?= count($reels) ?>;
const container = document.getElementById('clipsContainer');
const videoElements = document.querySelectorAll('.clip-video');
const clipItems = document.querySelectorAll('.clip-item');

// ===================== SCROLL & NAVIGATION =====================
container.addEventListener('scroll', () => {
    const reelHeight = container.clientHeight;
    currentReel = Math.round(container.scrollTop / reelHeight);
    handleVisibility();
});

function goToReel(index) {
    if (index < 0 || index >= totalReels) return;
    currentReel = index;
    container.scrollTo({ top: currentReel * container.clientHeight, behavior: 'smooth' });
}

function nextReel() {
    if (currentReel < totalReels - 1) goToReel(currentReel + 1);
    else goToReel(0);
}

let touchStartY = 0;
container.addEventListener('touchstart', (e) => { touchStartY = e.touches[0].clientY; });
container.addEventListener('touchend', (e) => {
    const diff = touchStartY - e.changedTouches[0].clientY;
    if (Math.abs(diff) > 60) {
        if (diff > 0) nextReel();
        else if (currentReel > 0) goToReel(currentReel - 1);
    }
});

// ===================== VIDEO PLAYBACK =====================
function getVideo(index) { return document.querySelector('[data-video-index="' + index + '"]'); }

function togglePlay(index) {
    const video = getVideo(index);
    if (!video || video.tagName !== 'VIDEO') return;
    if (video.paused) playVideo(index); else pauseVideo(index);
}

function playVideo(index) {
    const video = getVideo(index);
    if (!video || video.tagName !== 'VIDEO') return;
    const item = clipItems[index]; if (item) item.dataset.playing = 'true';
    const p = video.play(); if (p) p.catch(() => pauseVideo(index));
}

function pauseVideo(index) {
    const video = getVideo(index);
    if (!video || video.tagName !== 'VIDEO') return;
    video.pause();
    const item = clipItems[index]; if (item) item.dataset.playing = 'false';
}

videoElements.forEach((video) => {
    if (video.tagName !== 'VIDEO') return;
    const index = parseInt(video.dataset.videoIndex);
    video.addEventListener('canplay', () => { const l = document.querySelector('[data-loader="'+index+'"]'); if (l) l.classList.add('hidden-loader'); });
    video.addEventListener('waiting', () => { const l = document.querySelector('[data-loader="'+index+'"]'); if (l) l.classList.remove('hidden-loader'); });
    video.addEventListener('playing', () => { const l = document.querySelector('[data-loader="'+index+'"]'); if (l) l.classList.add('hidden-loader'); });
    video.addEventListener('timeupdate', () => { if (video.duration) { const f = document.querySelector('[data-progress-fill="'+index+'"]'); if (f) f.style.width = (video.currentTime/video.duration*100)+'%'; } });
    video.addEventListener('error', () => { const l = document.querySelector('[data-loader="'+index+'"]'); if (l) l.classList.add('hidden-loader'); });
});

function handleVisibility() {
    videoElements.forEach((video) => {
        if (video.tagName !== 'VIDEO') return;
        const index = parseInt(video.dataset.videoIndex);
        if (index === currentReel) playVideo(index); else pauseVideo(index);
    });
}
window.addEventListener('load', () => setTimeout(() => handleVisibility(), 300));

// ===================== UI INTERACTIONS =====================
function switchTab(btn, tab) {
    document.querySelectorAll('.nav-tab').forEach(t => { t.classList.remove('active'); t.classList.remove('text-white','font-bold'); t.classList.add('text-zinc-400','font-medium'); });
    btn.classList.add('active','text-white','font-bold');
    btn.classList.remove('text-zinc-400','font-medium');
    showToast(tab.charAt(0).toUpperCase() + tab.slice(1));
}

function toggleLike(btn) {
    const icon = btn.querySelector('.like-icon');
    const countEl = btn.querySelector('.like-count');
    if (icon.textContent === 'favorite_border') {
        icon.textContent = 'favorite'; icon.style.color = '#ef4444';
        let c = parseInt(countEl.textContent.replace(/[^0-9]/g, '')) || 0;
        countEl.textContent = formatCount(c + 1);
        fetch('/reels/' + clipItems[currentReel].dataset.reelId + '/like', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
    } else {
        icon.textContent = 'favorite_border'; icon.style.color = '#ffffff';
        let c = parseInt(countEl.textContent.replace(/[^0-9]/g, '')) || 0;
        countEl.textContent = formatCount(Math.max(0, c - 1));
    }
}

function toggleFollowBadge(btn) {
    if (btn.dataset.following === 'true') {
        btn.dataset.following = 'false';
        btn.innerHTML = '<span class="material-icons-round text-white text-[14px]">add</span>';
    } else {
        btn.dataset.following = 'true';
        btn.innerHTML = '<span class="material-icons-round text-green-400 text-[14px]">check</span>';
        showToast('Following!');
    }
}

function toggleFollowBtn(btn, userId) {
    const wasFollowing = btn.classList.contains('following');
    if (wasFollowing) {
        btn.classList.remove('following');
        btn.textContent = 'Follow';
    } else {
        btn.classList.add('following');
        btn.textContent = 'Following';
    }
    fetch('/follow/' + userId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json()).then(d => {
        if (d.error) {
            // Revert on error
            if (wasFollowing) {
                btn.classList.add('following');
                btn.textContent = 'Following';
            } else {
                btn.classList.remove('following');
                btn.textContent = 'Follow';
            }
            if (d.error === 'Login required') location.href = '/login';
            else showToast(d.error, true);
            return;
        }
        showToast(d.message || (d.following ? 'Following!' : 'Unfollowed'));
    }).catch(() => {});
}

function repostClip(id, btn) {
    const countEl = btn.querySelector('.repost-count');
    let c = parseInt(countEl.textContent.replace(/[^0-9]/g, '')) || 0;
    countEl.textContent = formatCount(c + 1);
    showToast('Reposted! 🔄');
}

function doubleTapLike(event, index) {
    event.stopPropagation();
    // Create heart burst
    const item = clipItems[index];
    const heart = document.createElement('div');
    heart.className = 'double-tap-heart';
    heart.innerHTML = '<span class="material-icons-round text-red-500" style="font-size: 80px; filter: drop-shadow(0 2px 10px rgba(239,68,68,0.5));">favorite</span>';
    item.appendChild(heart);
    setTimeout(() => heart.remove(), 900);
    // Increment like
    const likeBtn = item.querySelector('.action-icon');
    if (likeBtn) { const icon = likeBtn.querySelector('.like-icon'); if (icon && icon.textContent === 'favorite_border') toggleLike(likeBtn); }
}

function toggleReelMenu() { document.getElementById('reelMenu').classList.toggle('hidden'); }
function closeReelMenu() { document.getElementById('reelMenu').classList.add('hidden'); }

function toggleSearch() {
    document.getElementById('searchPanel').classList.toggle('hidden');
    if (!document.getElementById('searchPanel').classList.contains('hidden')) document.getElementById('clipSearchInput').focus();
}
function closeSearch() { document.getElementById('searchPanel').classList.add('hidden'); }
function searchClips() { const q = document.getElementById('clipSearchInput').value.trim(); if (q) showToast('Searching: ' + q); closeSearch(); }
function searchClipsFor(term) { document.getElementById('clipSearchInput').value = term; showToast('Searching: ' + term); closeSearch(); }

function openComments(id) { document.getElementById('commentsPanel').classList.remove('hidden'); }
function closeComments() { document.getElementById('commentsPanel').classList.add('hidden'); }
function sendComment() {
    const input = document.getElementById('commentInput');
    const msg = input.value.trim(); if (!msg) return;
    const scroll = document.getElementById('commentsScroll');
    const row = document.createElement('div');
    row.className = 'flex gap-2.5 fade-in';
    row.innerHTML = '<img src="/uploads/profiles/admin.jpg" class="w-8 h-8 rounded-full flex-shrink-0"><div><span class="text-white text-xs font-semibold">You</span><p class="text-zinc-200 text-[13px]">' + escapeHtml(msg) + '</p><div class="flex items-center gap-3 mt-0.5"><span class="text-zinc-600 text-[10px]">Just now</span></div></div>';
    scroll.appendChild(row); scroll.scrollTop = scroll.scrollHeight;
    input.value = '';
}

function shareClip(id) {
    if (navigator.share) navigator.share({ title: 'Globiim Clip', url: '/reels/' + id }).catch(() => {});
    else navigator.clipboard.writeText(window.location.origin + '/reels/' + id).then(() => showToast('Link copied!')).catch(() => {});
}

function openReelGiftPanel(id) { document.getElementById('reelGiftPanel').classList.remove('hidden'); }
function closeReelGiftPanel(e) { if (!e || e.target === e.currentTarget) document.getElementById('reelGiftPanel').classList.add('hidden'); }
function sendClipGift(reelId, giftId) {
    closeReelGiftPanel();
    const grid = document.getElementById('reelGiftGrid');
    const btn = grid.querySelector('button[data-gift-id="'+giftId+'"]');
    const name = btn ? btn.querySelector('.text-zinc-300').textContent : 'Gift';
    showToast('Sent ' + name + '! 🎁');
    fetch('/reels/' + reelId + '/gift', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ gift_id: giftId }) }).catch(() => {});
}

function formatCount(num) { num = parseInt(num) || 0; if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M'; if (num >= 1000) return (num / 1000).toFixed(1) + 'K'; return num.toString(); }
function escapeHtml(str) { const d = document.createElement('div'); d.textContent = str; return d.innerHTML; }

function showToast(msg) {
    const existing = document.querySelector('.clip-toast');
    if (existing) existing.remove();
    const div = document.createElement('div');
    div.className = 'clip-toast fixed top-16 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-sm font-medium z-[200]';
    div.style.cssText = 'background: linear-gradient(135deg, #3b82f6, #6366f1); box-shadow: 0 4px 20px rgba(59,130,246,0.4);';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(() => div.remove(), 300); }, 2000);
}

// Close menus
document.addEventListener('click', (e) => {
    const menu = document.getElementById('reelMenu');
    if (!menu.classList.contains('hidden') && !e.target.closest('#reelMenu') && !e.target.closest('[onclick*="toggleReelMenu"]')) menu.classList.add('hidden');
});

// Keyboard
document.addEventListener('keydown', (e) => {
    if (document.activeElement.tagName === 'INPUT') return;
    if (e.key === 'ArrowDown') nextReel();
    if (e.key === 'ArrowUp' && currentReel > 0) goToReel(currentReel - 1);
    if (e.key === ' ' || e.key === 'k') { e.preventDefault(); togglePlay(currentReel); }
    if (e.key === 'm') { const v = getVideo(currentReel); if (v && v.tagName === 'VIDEO') v.muted = !v.muted; }
});

// Auto-scroll
let autoTimer = setInterval(nextReel, 8000);
container.addEventListener('touchstart', () => { clearInterval(autoTimer); autoTimer = setInterval(nextReel, 8000); });
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
