<?php $activeTab = 'videos'; $title = ($data['video']['title'] ?? 'Video') . ' - Globiim'; $hideTopNav = true; $hideBottomNav = true; ?>
<?php
$video = $data['video'] ?? [];
$comments = $data['comments'] ?? [];
$creatorName = $video['creator_name'] ?? $video['username'] ?? 'Creator';
$creatorAvatar = $video['creator_avatar'] ?? '/uploads/profiles/admin.jpg';
$isVerified = !empty($video['is_verified']);
$videoUrl = $video['video_url'] ?? '';
$thumbnail = $video['thumbnail'] ?? '/uploads/profiles/admin.jpg';
$desc = $video['description'] ?? '';
$tags = $video['tags'] ?? [];
if (is_string($tags)) $tags = json_decode($tags, true) ?: [];
?>
<?php ob_start(); ?>
<style>
    * { box-sizing: border-box; }

    /* Video player */
    .video-container { position: relative; width: 100%; aspect-ratio: 16/9; background: #000; overflow: hidden; }
    .video-container video { width: 100%; height: 100%; object-fit: cover; }
    .video-container img { width: 100%; height: 100%; object-fit: cover; }

    /* Play overlay */
    .play-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; z-index: 5; cursor: pointer; }
    .play-overlay:hover .play-btn { transform: scale(1.1); }
    .play-btn { width: 64px; height: 64px; border-radius: 50%; background: rgba(131,74,229,0.85); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; transition: transform 0.2s; }
    .play-btn:active { transform: scale(0.9); }

    /* Progress bar */
    .progress-track { position: absolute; bottom: 0; left: 0; right: 0; height: 4px; background: rgba(255,255,255,0.15); z-index: 10; cursor: pointer; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #834ae5, #c084fc); width: 0%; transition: width 0.1s linear; position: relative; }
    .progress-fill::after { content: ''; position: absolute; right: -6px; top: 50%; transform: translateY(-50%); width: 12px; height: 12px; border-radius: 50%; background: #c084fc; box-shadow: 0 0 6px rgba(131,74,229,0.5); opacity: 0; transition: opacity 0.2s; }
    .progress-track:hover .progress-fill::after { opacity: 1; }

    /* Quality badge */
    .quality-badge { position: absolute; top: 12px; left: 12px; z-index: 8; }

    /* Action button row */
    .action-btn { transition: all 0.2s ease; }
    .action-btn:active { transform: scale(0.92); }

    /* Description expand */
    .desc-text { max-height: 40px; overflow: hidden; transition: max-height 0.3s ease; }
    .desc-text.expanded { max-height: 500px; }

    /* Comments */
    .comment-item { transition: background 0.15s ease; }
    .comment-item:hover { background: rgba(255,255,255,0.02); }

    /* Sort dropdown */
    .sort-dropdown { transition: all 0.2s ease; }

    /* Slide animation */
    @keyframes slideUp { 0% { transform: translateY(20px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
    .slide-up { animation: slideUp 0.25s ease-out forwards; }

    /* Shimmer */
    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    .shimmer { background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.04) 50%, transparent 75%); background-size: 200% 100%; animation: shimmer 3s infinite; }

    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    .reply-input-wrap { margin-top: 8px; margin-left: 32px; display: none; }
    .reply-input-wrap.active { display: flex; align-items: center; gap: 8px; animation: slideUp 0.2s ease-out; }
    .reply-input-wrap input { flex:1; background: #14141c; color: white; padding: 8px 12px; border-radius: 20px; border: 1px solid #1e1e2a; font-size: 12px; outline: none; transition: border-color 0.2s; }
    .reply-input-wrap input:focus { border-color: #834ae5; }
    .reply-input-wrap button { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #834ae5, #6b21a8); border: none; cursor: pointer; flex-shrink: 0; transition: opacity 0.2s; }
    .reply-input-wrap button:hover { opacity: 0.85; }
    .reply-cancel { background: #1e1e2a !important; color: #a1a1aa !important; }
</style>

<div class="max-w-lg mx-auto pb-20" style="background: #090c15;">

    <!-- ===== BACK LINK ===== -->
    <div class="px-4 pt-3 pb-2 flex items-center justify-between">
        <a href="/videos" class="w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg">arrow_back</span>
        </a>
        <h1 class="text-white text-sm font-bold truncate max-w-[60%]"><?= htmlspecialchars(mb_substr($video['title'] ?? 'Video', 0, 40)) ?></h1>
        <button onclick="toggleVideoMenu()" class="w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg">more_vert</span>
        </button>
    </div>

    <!-- ===== VIDEO PLAYER ===== -->
    <div class="video-container rounded-b-2xl overflow-hidden" id="videoPlayerContainer">
        <?php if (!empty($videoUrl)): ?>
        <video id="videoPlayer" src="<?= htmlspecialchars($videoUrl) ?>" poster="<?= htmlspecialchars($thumbnail) ?>" playsinline preload="metadata"></video>
        <?php else: ?>
        <img src="<?= htmlspecialchars($thumbnail) ?>" alt="<?= htmlspecialchars($video['title'] ?? '') ?>">
        <?php endif; ?>

        <!-- Play/Pause Overlay -->
        <div class="play-overlay" id="playOverlay" onclick="toggleVideoPlay()">
            <div class="play-btn" id="playBtn">
                <span class="material-icons-round text-white text-3xl">play_arrow</span>
            </div>
        </div>

        <!-- Big tap indicator -->
        <div id="tapIndicator" class="hidden absolute inset-0 flex items-center justify-center z-[6] pointer-events-none">
            <div class="w-16 h-16 rounded-full bg-black/50 flex items-center justify-center">
                <span class="material-icons-round text-white text-3xl" id="tapIcon">pause</span>
            </div>
        </div>

        <!-- Quality Badge -->
        <div class="quality-badge">
            <button onclick="toggleQuality()" class="flex items-center gap-1 px-2 py-1 rounded-lg bg-black/70 backdrop-blur-sm text-white text-[11px] font-bold hover:bg-black/90 transition-colors">
                <span id="qualityText">1080p</span>
                <span class="material-icons-round text-[10px]">unfold_more</span>
            </button>
        </div>

        <!-- Duration Badge -->
        <?php if (!empty($video['duration'])): ?>
        <div class="absolute bottom-12 right-3 z-[8] px-2 py-0.5 rounded bg-black/80 text-white text-[11px] font-medium" id="durationBadge">
            <?= formatDuration($video['duration']) ?>
        </div>
        <?php endif; ?>

        <!-- Video Controls (shown when playing) -->
        <div class="absolute bottom-0 left-0 right-0 z-[9] p-2 hidden" id="videoControls">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-white text-[11px] font-medium" id="currentTime">0:00</span>
                <div class="flex-1"></div>
                <button onclick="toggleFullscreen()" class="text-white hover:opacity-80 transition-opacity">
                    <span class="material-icons-round text-lg">fullscreen</span>
                </button>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress-track" id="progressTrack" onclick="seekVideo(event)">
            <div class="progress-fill" id="progressFill"></div>
        </div>
    </div>

    <!-- ===== VIDEO INFO ===== -->
    <div class="px-4 pt-3">
        <!-- Title -->
        <h1 class="text-white text-base font-bold leading-snug mb-1.5"><?= htmlspecialchars($video['title'] ?? '') ?></h1>

        <!-- Views �� Time �� Tags -->
        <div class="flex items-center gap-1.5 flex-wrap mb-3">
            <span class="text-zinc-500 text-[11px]"><?= formatCount($video['views'] ?? 0) ?> views</span>
            <span class="text-zinc-600 text-[10px]">�6�1</span>
            <span class="text-zinc-500 text-[11px]"><?= timeAgo($video['created_at'] ?? '') ?></span>
            <?php if (!empty($tags) && is_array($tags)): ?>
            <?php foreach ($tags as $tag): ?>
            <span class="text-[11px] font-medium cursor-pointer hover:underline" style="color: #834ae5;">#<?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
            <?php endif; ?>
            <?php
            // Extract hashtags from description
            preg_match_all('/#(\w+)/', $desc, $hashMatches);
            if (!empty($hashMatches[0])):
                foreach (array_slice($hashMatches[0], 0, 5) as $htag):
            ?>
            <span class="text-[11px] font-medium cursor-pointer hover:underline" style="color: #834ae5;"><?= htmlspecialchars($htag) ?></span>
            <?php endforeach; endif; ?>
        </div>

        <!-- ===== CREATOR INFO ===== -->
        <div class="flex items-center gap-2.5 mb-3 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
            <a href="/creator/<?= htmlspecialchars($video['username'] ?? 'u') ?>" class="flex items-center gap-2.5 flex-1 min-w-0">
                <div class="relative flex-shrink-0">
                    <div class="w-10 h-10 rounded-full overflow-hidden" style="box-shadow: 0 0 0 2px rgba(131,74,229,0.4);">
                        <img src="<?= htmlspecialchars($creatorAvatar) ?>" alt="<?= htmlspecialchars($creatorName) ?>" class="w-full h-full object-cover">
                    </div>
                    <?php if ($isVerified): ?>
                    <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-[#834ae5] rounded-full flex items-center justify-center">
                        <svg width="8" height="8" viewBox="0 0 24 24" fill="white"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1">
                        <span class="text-white text-[13px] font-bold truncate"><?= htmlspecialchars($creatorName) ?></span>
                        <?php if ($isVerified): ?>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="#834ae5"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                        <?php endif; ?>
                    </div>
                    <span class="text-zinc-500 text-[10px]"><?= formatCount($video['subscribers'] ?? 0) ?> followers</span>
                </div>
            </a>
<?php $vidIsFollowing = !empty($video['is_following']); ?>
            <button onclick="toggleFollow(this, <?= $video['user_id'] ?? 0 ?>)" data-following="<?= $vidIsFollowing ? 'true' : 'false' ?>" class="flex items-center gap-1 px-4 py-1.5 rounded-full text-white text-[11px] font-bold flex-shrink-0 transition-all" style="<?= $vidIsFollowing ? 'background:#1e1e2a;color:#a1a1aa;' : 'background:linear-gradient(135deg,#834ae5,#6b21a8);' ?>">
                <?= $vidIsFollowing ? '<span class="material-icons-round text-[14px]">check</span> Following' : 'Follow <span class="material-icons-round text-[14px]">expand_more</span>' ?>
            </button>
        </div>

        <!-- ===== ACTION BUTTONS ROW ===== -->
        <div class="flex items-center justify-between mb-3 py-1">
            <!-- Like -->
            <button onclick="toggleLike()" class="action-btn flex flex-col items-center gap-0.5" id="likeBtn">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#14141c] border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-lg" id="likeIcon">thumb_up</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-semibold" id="likeCount"><?= formatCount($video['likes'] ?? 0) ?></span>
            </button>
            <!-- Dislike -->
            <button onclick="toggleDislike()" class="action-btn flex flex-col items-center gap-0.5" id="dislikeBtn">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#14141c] border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-lg" id="dislikeIcon">thumb_down</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-semibold" id="dislikeCount"><?= formatCount($video['dislikes'] ?? 234) ?></span>
            </button>
            <!-- Gift -->
            <button onclick="openVideoGiftPanel(<?= $video['id'] ?? 0 ?>)" class="action-btn flex flex-col items-center gap-0.5">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: rgba(131,74,229,0.12); border: 1px solid rgba(131,74,229,0.2);">
                    <span class="material-icons-round text-[#834ae5] text-lg">card_giftcard</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-semibold">Gift</span>
            </button>
            <!-- Download -->
            <button onclick="downloadVideo()" class="action-btn flex flex-col items-center gap-0.5">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#14141c] border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-lg">download</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-semibold">Download</span>
            </button>
            <!-- Save -->
            <button onclick="toggleSave(this)" class="action-btn flex flex-col items-center gap-0.5" id="saveBtn">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#14141c] border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-lg save-icon">bookmark_border</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-semibold save-text">Save</span>
            </button>
            <!-- Share -->
            <button onclick="shareVideo(<?= $video['id'] ?? 0 ?>)" class="action-btn flex flex-col items-center gap-0.5">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#14141c] border border-[#1e1e2a]">
                    <span class="material-icons-round text-zinc-300 text-lg">share</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-semibold">Share</span>
            </button>
        </div>

        <!-- ===== DESCRIPTION ===== -->
        <?php if (!empty($desc)): ?>
        <div class="mb-4">
            <div class="bg-[#14141c] rounded-xl p-3 border border-[#1e1e2a]">
                <p class="desc-text text-zinc-300 text-[12px] leading-relaxed" id="descText"><?= htmlspecialchars($desc) ?></p>
                <button onclick="toggleDescription()" class="text-[11px] font-semibold mt-1" id="descToggle" style="color: #834ae5;">...more</button>
            </div>
        </div>
        <?php endif; ?>

        <!-- ===== COMMENTS SECTION ===== -->
        <div class="mb-4">
            <!-- Comments Header -->
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="material-icons-round text-[18px]" style="color: #834ae5;">chat_bubble</span>
                    <h3 class="text-white text-sm font-bold">Comments</h3>
                    <span class="text-zinc-500 text-xs">(<?= count($comments) ?>)</span>
                </div>
                <button onclick="toggleSort()" class="flex items-center gap-1 text-zinc-400 text-[11px] font-medium hover:text-white transition-colors">
                    <span id="sortText">Top</span>
                    <span class="material-icons-round text-[14px]">unfold_more</span>
                </button>
            </div>

            <!-- Comment Input -->
            <div class="flex items-center gap-2.5 mb-4">
                <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0" style="box-shadow: 0 0 0 2px rgba(131,74,229,0.3);">
                    <img src="/uploads/profiles/admin.jpg" alt="You" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 relative">
                    <input type="text" id="videoCommentInput" placeholder="Add a comment..." class="w-full bg-[#14141c] text-white px-4 py-2.5 rounded-full border border-[#1e1e2a] focus:border-[#834ae5] focus:outline-none text-[13px] placeholder:text-zinc-600 transition-all" onkeydown="if(event.key==='Enter')addVideoComment(<?= $video['id'] ?? 0 ?>)">
                </div>
                <button onclick="addVideoComment(<?= $video['id'] ?? 0 ?>)" class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #834ae5, #6b21a8);">
                    <span class="material-icons-round text-white text-lg">send</span>
                </button>
            </div>

            <!-- Comments List -->
            <?php if (!empty($comments)): ?>
            <div class="space-y-3 max-h-[400px] overflow-y-auto scrollbar-hide" id="commentsList">
                <?php foreach ($comments as $idx => $comment): ?>
                <div class="comment-item flex gap-2.5 rounded-xl p-2 -mx-2">
                    <img src="<?= $comment['commenter_avatar'] ?? '/uploads/profiles/admin.jpg' ?>" alt="" class="w-8 h-8 rounded-full flex-shrink-0 mt-0.5">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-white text-[12px] font-semibold"><?= htmlspecialchars($comment['commenter_name'] ?? $comment['username'] ?? 'User') ?></span>
                            <span class="text-zinc-600 text-[9px]"><?= timeAgo($comment['created_at'] ?? '') ?></span>
                        </div>
                        <p class="text-zinc-300 text-[12px] leading-relaxed mt-0.5"><?= htmlspecialchars($comment['body'] ?? '') ?></p>
                        <div class="flex items-center gap-3 mt-1">
                            <button onclick="likeComment(this, <?= $idx ?>)" class="flex items-center gap-0.5 text-zinc-500 hover:text-[#834ae5] transition-colors">
                                <span class="material-icons-round text-[12px] comment-like-icon">favorite_border</span>
                                <span class="text-[10px] comment-like-count"><?= rand(2, 200) ?></span>
                            </button>
                            <button onclick="toggleReplyInput(this, <?= $comment['id'] ?? 0 ?>, '<?= htmlspecialchars(addslashes($comment['commenter_name'] ?? 'User')) ?>')" class="text-[10px] font-medium hover:text-[#834ae5] transition-colors" style="color: #834ae5;">Reply</button>
                            <button class="text-zinc-600 hover:text-zinc-400 transition-colors">
                                <span class="material-icons-round text-[14px]">more_horiz</span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-8 bg-[#14141c] rounded-xl border border-[#1e1e2a]">
                <span class="material-icons-round text-zinc-600 text-3xl">chat_bubble_outline</span>
                <p class="text-zinc-500 text-xs mt-2">No comments yet. Be the first!</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- ===== SUGGESTED VIDEOS ===== -->
        <div class="mb-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="material-icons-round text-[18px]" style="color: #834ae5;">play_circle</span>
                    <h3 class="text-white text-sm font-bold">Up Next</h3>
                </div>
                <a href="/videos" class="text-[11px] font-semibold flex items-center gap-0.5" style="color: #834ae5;">
                    See all <span class="material-icons-round text-sm">chevron_right</span>
                </a>
            </div>
            <div class="space-y-3">
                <?php for ($i = 0; $i < 3; $i++):
                    $suggested = [
                        ['title' => 'Top 10 Places to Visit in Kenya', 'creator' => 'Travel Diaries', 'views' => '98K', 'time' => '2d ago', 'duration' => '12:34', 'color' => '#0891b2'],
                        ['title' => 'How I Built a YouTube Studio', 'creator' => 'Creator Flow', 'views' => '76K', 'time' => '5d ago', 'duration' => '18:22', 'color' => '#7e22ce'],
                        ['title' => 'Best Camera for Content Creators 2024', 'creator' => 'Tech Gear', 'views' => '45K', 'time' => '1w ago', 'duration' => '15:07', 'color' => '#dc2626'],
                    ][$i]; ?>
                <a href="/videos" class="flex gap-3 group">
                    <div class="relative flex-shrink-0 w-[140px] rounded-xl overflow-hidden">
                        <div class="aspect-video flex items-center justify-center" style="background: linear-gradient(135deg, <?= $suggested['color'] ?>33, #14141c);">
                            <span class="material-icons-round text-white/20 text-4xl">play_circle</span>
                        </div>
                        <div class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/80 text-white text-[9px] font-medium"><?= $suggested['duration'] ?></div>
                    </div>
                    <div class="flex-1 min-w-0 py-0.5">
                        <h4 class="text-white text-[12px] font-semibold line-clamp-2 leading-snug"><?= $suggested['title'] ?></h4>
                        <p class="text-zinc-500 text-[10px] mt-1"><?= $suggested['creator'] ?></p>
                        <p class="text-zinc-600 text-[10px]"><?= $suggested['views'] ?> views �� <?= $suggested['time'] ?></p>
                    </div>
                </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<!-- ===== VIDEO MORE MENU ===== -->
<div id="videoMenu" class="hidden fixed top-14 right-3 z-[50] bg-[#14141c] border border-[#1e1e2a] rounded-2xl overflow-hidden shadow-2xl min-w-[200px] slide-up" style="box-shadow: 0 8px 30px rgba(0,0,0,0.5);">
    <button onclick="addToWatchLater(); closeVideoMenu();" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
        <span class="material-icons-round text-zinc-300 text-lg">playlist_add</span>
        <span class="text-zinc-200 text-sm">Add to Watch Later</span>
    </button>
    <button onclick="addToQueue(); closeVideoMenu();" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
        <span class="material-icons-round text-zinc-300 text-lg">queue</span>
        <span class="text-zinc-200 text-sm">Add to Queue</span>
    </button>
    <button onclick="shareVideo(<?= $video['id'] ?? 0 ?>); closeVideoMenu();" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
        <span class="material-icons-round text-zinc-300 text-lg">share</span>
        <span class="text-zinc-200 text-sm">Share</span>
    </button>
    <div class="h-px bg-[#1e1e2a]"></div>
    <button onclick="reportVideo(); closeVideoMenu();" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
        <span class="material-icons-round text-red-400 text-lg">flag</span>
        <span class="text-red-400 text-sm">Report</span>
    </button>
</div>

<!-- ===== QUALITY SELECTOR ===== -->
<div id="qualityPanel" class="hidden fixed top-14 left-3 z-[50] bg-[#14141c] border border-[#1e1e2a] rounded-xl overflow-hidden shadow-2xl slide-up" style="box-shadow: 0 8px 30px rgba(0,0,0,0.5);">
    <?php foreach (['2160p', '1080p', '720p', '480p', '360p', 'Auto'] as $q): ?>
    <button onclick="setQuality('<?= $q ?>')" class="w-full px-4 py-2.5 text-left text-sm hover:bg-white/5 transition-colors <?= $q === '1080p' ? 'text-[#834ae5] font-bold' : 'text-zinc-300' ?>" data-quality="<?= $q ?>"><?= $q ?></button>
    <?php endforeach; ?>
</div>

<!-- ===== GIFT PANEL ===== -->
<div id="videoGiftPanel" class="fixed inset-0 z-50 hidden" onclick="closeVideoGiftPanel(event)">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-[#0a0a14] rounded-t-3xl max-w-lg mx-auto slide-up" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between p-4 border-b border-white/10">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-amber-400 text-lg">monetization_on</span>
                <h3 class="text-white font-bold text-lg">Send a Gift</h3>
            </div>
            <button onclick="closeVideoGiftPanel()" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">close</span>
            </button>
        </div>
        <div class="p-4">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-zinc-400 text-sm">Balance:</span>
                <span class="text-white text-sm font-bold">$1,250.00</span>
            </div>
            <div class="grid grid-cols-4 gap-3" id="videoGiftGrid">
                <?php
                $videoGifts = $data['gifts'] ?? [];
                if (empty($videoGifts)) {
                    $videoGifts = [
                        ['id' => 1, 'name' => 'Heart', 'icon' => 'favorite', 'price_usd' => 0.50, 'color_class' => 'text-red-400'],
                        ['id' => 2, 'name' => 'Fire', 'icon' => 'local_fire_department', 'price_usd' => 1.00, 'color_class' => 'text-orange-400'],
                        ['id' => 3, 'name' => 'Star', 'icon' => 'star', 'price_usd' => 2.50, 'color_class' => 'text-yellow-400'],
                        ['id' => 4, 'name' => 'Diamond', 'icon' => 'diamond', 'price_usd' => 5.00, 'color_class' => 'text-cyan-400'],
                        ['id' => 5, 'name' => 'Crown', 'icon' => 'military_tech', 'price_usd' => 10.00, 'color_class' => 'text-amber-400'],
                        ['id' => 6, 'name' => 'Rocket', 'icon' => 'rocket_launch', 'price_usd' => 20.00, 'color_class' => 'text-purple-400'],
                        ['id' => 7, 'name' => 'Party', 'icon' => 'celebration', 'price_usd' => 50.00, 'color_class' => 'text-pink-400'],
                        ['id' => 8, 'name' => 'Super', 'icon' => 'bolt', 'price_usd' => 100.00, 'color_class' => 'text-purple-300'],
                    ];
                }
                ?>
                <?php foreach ($videoGifts as $g): ?>
                <button onclick="sendVideoGift(<?= $video['id'] ?? 0 ?>, <?= $g['id'] ?>)" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-amber-500/40 transition-all group" data-gift-id="<?= $g['id'] ?>">
                    <?php if (!empty($g['image_url'])): ?>
                    <div class="w-9 h-9 rounded-lg overflow-hidden group-hover:scale-110 transition-transform">
                        <img src="<?= $g['image_url'] ?>" alt="<?= $g['name'] ?>" class="w-full h-full object-cover">
                    </div>
                    <?php else: ?>
                    <span class="material-icons-round <?= $g['color_class'] ?? 'text-amber-400' ?> text-2xl group-hover:scale-110 transition-transform"><?= $g['icon'] ?></span>
                    <?php endif; ?>
                    <span class="text-zinc-300 text-[10px] font-medium"><?= $g['name'] ?></span>
                    <span class="text-amber-400 text-[9px] font-bold">$<?= number_format((float)($g['price_usd'] ?? 0), 2) ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
const videoEl = document.getElementById('videoPlayer');
let isPlaying = false;
let controlsTimeout;

// ===================== VIDEO PLAYER =====================
function toggleVideoPlay() {
    if (!videoEl || videoEl.tagName !== 'VIDEO') return;
    if (isPlaying) {
        videoEl.pause();
        isPlaying = false;
        document.getElementById('playBtn').innerHTML = '<span class="material-icons-round text-white text-3xl">play_arrow</span>';
        document.getElementById('videoControls').classList.add('hidden');
    } else {
        videoEl.play().catch(() => {});
        isPlaying = true;
        document.getElementById('playBtn').innerHTML = '<span class="material-icons-round text-white text-3xl">pause</span>';
        document.getElementById('videoControls').classList.remove('hidden');
        showControls();
    }
}

function showTapIndicator(icon) {
    const el = document.getElementById('tapIndicator');
    document.getElementById('tapIcon').textContent = icon;
    el.classList.remove('hidden');
    el.style.opacity = '1';
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.classList.add('hidden'), 200); }, 300);
}

function showControls() {
    const controls = document.getElementById('videoControls');
    controls.classList.remove('hidden');
    clearTimeout(controlsTimeout);
    controlsTimeout = setTimeout(() => { if (isPlaying) controls.classList.add('hidden'); }, 3000);
}

function toggleFullscreen() {
    const container = document.getElementById('videoPlayerContainer');
    if (!document.fullscreenElement) {
        container.requestFullscreen?.() || container.webkitRequestFullscreen?.();
    } else {
        document.exitFullscreen?.() || document.webkitExitFullscreen?.();
    }
}

function toggleQuality() {
    document.getElementById('qualityPanel').classList.toggle('hidden');
}

function setQuality(q) {
    document.getElementById('qualityText').textContent = q;
    document.getElementById('qualityPanel').classList.add('hidden');
    showToast('Quality set to ' + q);
}

function seekVideo(e) {
    if (!videoEl || !videoEl.duration) return;
    const track = document.getElementById('progressTrack');
    const rect = track.getBoundingClientRect();
    const pct = (e.clientX - rect.left) / rect.width;
    videoEl.currentTime = pct * videoEl.duration;
}

// Video events
if (videoEl && videoEl.tagName === 'VIDEO') {
    videoEl.addEventListener('timeupdate', () => {
        if (!videoEl.duration) return;
        const pct = (videoEl.currentTime / videoEl.duration) * 100;
        document.getElementById('progressFill').style.width = pct + '%';
        document.getElementById('currentTime').textContent = formatTime(videoEl.currentTime);
    });
    videoEl.addEventListener('ended', () => {
        isPlaying = false;
        document.getElementById('playBtn').innerHTML = '<span class="material-icons-round text-white text-3xl">replay</span>';
        document.getElementById('videoControls').classList.add('hidden');
    });
    videoEl.addEventListener('play', () => {
        isPlaying = true;
        document.getElementById('playBtn').innerHTML = '<span class="material-icons-round text-white text-3xl">pause</span>';
        document.getElementById('videoControls').classList.remove('hidden');
    });
    videoEl.addEventListener('pause', () => {
        isPlaying = false;
        document.getElementById('playBtn').innerHTML = '<span class="material-icons-round text-white text-3xl">play_arrow</span>';
    });
}

// Double tap to seek
let lastTap = 0;
document.getElementById('playOverlay')?.addEventListener('touchend', (e) => {
    const now = Date.now();
    if (now - lastTap < 300) {
        // Double tap - toggle play
        toggleVideoPlay();
    }
    lastTap = now;
});

function formatTime(secs) {
    const m = Math.floor(secs / 60);
    const s = Math.floor(secs % 60);
    return m + ':' + String(s).padStart(2, '0');
}

// ===================== ACTIONS =====================
function toggleLike() {
    const icon = document.getElementById('likeIcon');
    const count = document.getElementById('likeCount');
    const isLiked = icon.textContent === 'thumb_up' && icon.style.color === 'rgb(131, 74, 229)';
    if (isLiked) {
        icon.style.color = '#d4d4d8';
        icon.textContent = 'thumb_up';
        let c = parseInt(count.textContent.replace(/[^0-9]/g, '')) || 0;
        count.textContent = formatCount(Math.max(0, c - 1));
    } else {
        icon.style.color = '#834ae5';
        icon.textContent = 'thumb_up';
        let c = parseInt(count.textContent.replace(/[^0-9]/g, '')) || 0;
        count.textContent = formatCount(c + 1);
        // Remove dislike if active
        const dIcon = document.getElementById('dislikeIcon');
        if (dIcon.style.color === 'rgb(131, 74, 229)') {
            dIcon.style.color = '#d4d4d8';
            let dc = parseInt(document.getElementById('dislikeCount').textContent.replace(/[^0-9]/g, '')) || 0;
            document.getElementById('dislikeCount').textContent = formatCount(Math.max(0, dc - 1));
        }
    }
    fetch('/videos/<?= $video['id'] ?? 0 ?>/like', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

function toggleDislike() {
    const icon = document.getElementById('dislikeIcon');
    const count = document.getElementById('dislikeCount');
    const isDisliked = icon.style.color === 'rgb(131, 74, 229)';
    if (isDisliked) {
        icon.style.color = '#d4d4d8';
        let c = parseInt(count.textContent.replace(/[^0-9]/g, '')) || 0;
        count.textContent = formatCount(Math.max(0, c - 1));
    } else {
        icon.style.color = '#834ae5';
        let c = parseInt(count.textContent.replace(/[^0-9]/g, '')) || 0;
        count.textContent = formatCount(c + 1);
        const lIcon = document.getElementById('likeIcon');
        if (lIcon.style.color === 'rgb(131, 74, 229)') {
            lIcon.style.color = '#d4d4d8';
            let lc = parseInt(document.getElementById('likeCount').textContent.replace(/[^0-9]/g, '')) || 0;
            document.getElementById('likeCount').textContent = formatCount(Math.max(0, lc - 1));
        }
    }
    fetch('/videos/<?= $video['id'] ?? 0 ?>/dislike', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

function toggleSave(btn) {
    const icon = btn.querySelector('.save-icon');
    const text = btn.querySelector('.save-text');
    if (icon.textContent === 'bookmark_border') {
        icon.textContent = 'bookmark';
        icon.style.color = '#834ae5';
        text.textContent = 'Saved';
        text.style.color = '#834ae5';
        showToast('Saved to Watch Later');
        fetch('/bookmark/video/<?= $video['id'] ?? 0 ?>', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
    } else {
        icon.textContent = 'bookmark_border';
        icon.style.color = '';
        text.textContent = 'Save';
        text.style.color = '';
        fetch('/bookmark/video/<?= $video['id'] ?? 0 ?>', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
    }
}

function toggleFollow(btn, userId) {
    const wasFollowing = btn.dataset.following === 'true';
    if (wasFollowing) {
        btn.dataset.following = 'false';
        btn.innerHTML = 'Follow <span class="material-icons-round text-[14px]">expand_more</span>';
        btn.style.background = 'linear-gradient(135deg, #834ae5, #6b21a8)';
        btn.style.color = 'white';
    } else {
        btn.dataset.following = 'true';
        btn.innerHTML = '<span class="material-icons-round text-[14px]">check</span> Following';
        btn.style.background = '#1e1e2a';
        btn.style.color = '#a1a1aa';
    }
    fetch('/follow/' + userId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json()).then(d => {
        if (d.error) {
            // Revert on error
            if (wasFollowing) {
                btn.dataset.following = 'true';
                btn.innerHTML = '<span class="material-icons-round text-[14px]">check</span> Following';
                btn.style.background = '#1e1e2a';
                btn.style.color = '#a1a1aa';
            } else {
                btn.dataset.following = 'false';
                btn.innerHTML = 'Follow <span class="material-icons-round text-[14px]">expand_more</span>';
                btn.style.background = 'linear-gradient(135deg, #834ae5, #6b21a8)';
                btn.style.color = 'white';
            }
            if (d.error === 'Login required') location.href = '/login';
            else showToast(d.error, true);
            return;
        }
        showToast(d.message || (d.following ? 'Following!' : 'Unfollowed'));
    }).catch(() => {});
}

function downloadVideo() {
    const videoId = <?= $video['id'] ?? 0 ?>;
    fetch('/videos/' + videoId + '/download', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.video_url) {
            const a = document.createElement('a');
            a.href = data.video_url;
            a.download = (data.title || 'video') + '.mp4';
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            showToast('Download started!');
        } else {
            showToast(data.error || 'Video not available');
        }
    })
    .catch(() => showToast('Download failed'));
}

// ===================== DESCRIPTION =====================
function toggleDescription() {
    const text = document.getElementById('descText');
    const btn = document.getElementById('descToggle');
    if (text.classList.contains('expanded')) {
        text.classList.remove('expanded');
        btn.textContent = '...more';
    } else {
        text.classList.add('expanded');
        btn.textContent = 'Show less';
    }
}

// ===================== COMMENTS =====================
function likeComment(btn, idx) {
    const icon = btn.querySelector('.comment-like-icon');
    const count = btn.querySelector('.comment-like-count');
    if (icon.textContent === 'favorite_border') {
        icon.textContent = 'favorite';
        icon.style.color = '#ef4444';
        let c = parseInt(count.textContent) + 1;
        count.textContent = c;
    } else {
        icon.textContent = 'favorite_border';
        icon.style.color = '';
        let c = parseInt(count.textContent) - 1;
        count.textContent = Math.max(0, c);
    }
}

function toggleSort() {
    const text = document.getElementById('sortText');
    text.textContent = text.textContent === 'Top' ? 'Newest' : 'Top';
    showToast('Sorted by ' + text.textContent);
}

function addVideoComment(id) {
    const input = document.getElementById('videoCommentInput');
    const body = input.value.trim();
    if (!body) return;
    input.value = '';

    // Optimistic render
    const list = document.getElementById('commentsList');
    if (list) {
        const now = new Date();
        const div = document.createElement('div');
        div.className = 'comment-item flex gap-2.5 rounded-xl p-2 -mx-2 slide-up';
        div.innerHTML = `
            <img src="/uploads/profiles/admin.jpg" class="w-8 h-8 rounded-full flex-shrink-0 mt-0.5">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-1.5">
                    <span class="text-white text-[12px] font-semibold">You</span>
                    <span class="text-zinc-600 text-[9px]">now</span>
                </div>
                <p class="text-zinc-300 text-[12px] leading-relaxed mt-0.5">${escapeHtml(body)}</p>
                <div class="flex items-center gap-3 mt-1">
                    <button class="flex items-center gap-0.5 text-zinc-500 hover:text-[#834ae5] transition-colors">
                        <span class="material-icons-round text-[12px]">favorite_border</span>
                        <span class="text-[10px]">0</span>
                    </button>
                    <button onclick="toggleReplyInput(this, 0, 'You')" class="text-[10px] font-medium" style="color: #834ae5;">Reply</button>
                </div>
            </div>
        `;
        list.insertBefore(div, list.firstChild);
    }

    fetch('/videos/' + id + '/comment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ body: body })
    }).catch(() => {});
}

// ===================== GIFT PANEL =====================
function openVideoGiftPanel(id) {
    document.getElementById('videoGiftPanel').classList.remove('hidden');
}

function closeVideoGiftPanel(e) {
    if (!e || e.target === e.currentTarget) {
        document.getElementById('videoGiftPanel').classList.add('hidden');
    }
}

function sendVideoGift(videoId, giftId) {
    closeVideoGiftPanel();
    const grid = document.getElementById('videoGiftGrid');
    const btn = grid.querySelector('button[data-gift-id="' + giftId + '"]');
    const name = btn ? btn.querySelector('.text-zinc-300').textContent : 'Gift';
    showToast('Sent ' + name + '! �9�7');
    fetch('/videos/' + videoId + '/gift', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ gift_id: giftId })
    }).catch(() => {});
}

// ===================== MENUS =====================
function toggleVideoMenu() { document.getElementById('videoMenu').classList.toggle('hidden'); }
function closeVideoMenu() { document.getElementById('videoMenu').classList.add('hidden'); }
function addToWatchLater() { showToast('Added to Watch Later'); }
function addToQueue() { showToast('Added to Queue'); }
function reportVideo() { showToast('Reported �� Thank you'); }
function shareVideo(id) {
    if (navigator.share) {
        navigator.share({ title: 'Check out this video!', url: '/videos/' + id });
    } else {
        navigator.clipboard.writeText(window.location.origin + '/videos/' + id).then(() => showToast('Link copied!')).catch(() => {});
    }
    fetch('/videos/' + id + '/share', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

// Close menus on outside click
document.addEventListener('click', (e) => {
    if (!e.target.closest('#videoMenu') && !e.target.closest('[onclick*="toggleVideoMenu"]')) closeVideoMenu();
    if (!e.target.closest('#qualityPanel') && !e.target.closest('[onclick*="toggleQuality"]')) document.getElementById('qualityPanel')?.classList.add('hidden');
});

// ===================== UTILS =====================
function formatCount(n) {
    n = parseInt(n) || 0;
    if (n >= 1000000) return (n / 1000000).toFixed(1) + 'M';
    if (n >= 1000) return (n / 1000).toFixed(1) + 'K';
    return n.toString();
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

function toggleReplyInput(btn, commentId, commenterName) {
    const commentItem = btn.closest('.comment-item');
    let replyWrap = commentItem.querySelector('.reply-input-wrap');
    
    if (replyWrap && replyWrap.classList.contains('active')) {
        replyWrap.classList.remove('active');
        replyWrap.remove();
        return;
    }
    
    document.querySelectorAll('.reply-input-wrap.active').forEach(el => el.remove());
    
    replyWrap = document.createElement('div');
    replyWrap.className = 'reply-input-wrap active';
    replyWrap.innerHTML = `
        <input type="text" placeholder="Reply to ${escapeHtml(commenterName)}..." onkeydown="if(event.key==='Enter')submitReply(${commentId}, this)" autofocus>
        <button onclick="submitReply(${commentId}, this.previousElementSibling)" title="Send">
            <span class="material-icons-round text-white text-[14px]">send</span>
        </button>
        <button onclick="this.parentElement.classList.remove('active');this.parentElement.remove();" class="reply-cancel" title="Cancel">
            <span class="material-icons-round text-[14px]">close</span>
        </button>
    `;
    commentItem.querySelector('.flex-1.min-w-0').appendChild(replyWrap);
    replyWrap.querySelector('input').focus();
}

function submitReply(commentId, input) {
    const body = input.value.trim();
    if (!body) return;
    input.value = '';
    
    const videoId = <?= $video['id'] ?? 0 ?>;
    
    const replyWrap = input.closest('.reply-input-wrap');
    const replyDiv = document.createElement('div');
    replyDiv.className = 'mt-2 pl-4 border-l-2 border-[#834ae5]/30 slide-up';
    replyDiv.innerHTML = `
        <p class="text-zinc-300 text-[12px] leading-relaxed"><span class="text-[#834ae5] font-semibold">@You</span> ${escapeHtml(body)}</p>
        <p class="text-zinc-600 text-[9px] mt-0.5">just now</p>
    `;
    replyWrap.after(replyDiv);
    replyWrap.classList.remove('active');
    replyWrap.remove();
    
    fetch('/videos/' + videoId + '/comment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ body: '@' + body, parent_id: commentId })
    }).catch(() => {});
    
    showToast('Reply posted!');
}

function escapeHtml(text) {
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
}

function showToast(msg) {
    const existing = document.querySelector('.video-toast');
    if (existing) existing.remove();
    const div = document.createElement('div');
    div.className = 'video-toast fixed top-4 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-sm font-medium z-[200] max-w-[90%] text-center';
    div.style.cssText = 'background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 20px rgba(131,74,229,0.4);';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(() => div.remove(), 300); }, 2000);
}

<?php if (!empty($videoUrl)): ?>
// Auto-show controls on hover
document.getElementById('videoPlayerContainer')?.addEventListener('mousemove', () => { if (isPlaying) showControls(); });
<?php endif; ?>
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
