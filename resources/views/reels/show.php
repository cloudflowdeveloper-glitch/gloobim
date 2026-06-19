<?php $activeTab = 'clips'; $title = ($data['reel']['title'] ?? 'Reel') . ' - Globiim'; $hideTopNav = true; $hideBottomNav = false; ?>
<?php
$reel = $data['reel'] ?? [];
$comments = $data['comments'] ?? [];
$videoUrl = $reel['video_url'] ?? '';
$thumbnail = $reel['thumbnail'] ?? '';
?>
<?php ob_start(); ?>

<style>
    * { box-sizing: border-box; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    /* Video */
    .clip-video { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 0; }
    .video-loader { z-index: 5; transition: opacity 0.3s ease; }
    .video-loader.hidden-loader { opacity: 0; pointer-events: none; }

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

    /* Progress bar */
    .progress-bar-track { position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: rgba(255,255,255,0.15); z-index: 25; }
    .progress-bar-fill { height: 100%; background: linear-gradient(90deg, #3b82f6, #6366f1); width: 0%; transition: width 0.25s linear; }

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
    .spin-disc { animation: spin 4s linear infinite; }
</style>

<div id="reelShowContainer" class="relative w-full overflow-hidden bg-black" style="height: 100vh; padding-bottom: 56px;" data-reel-id="<?= $reel['id'] ?? 0 ?>" data-playing="false">

    <!-- VIDEO -->
    <?php if (!empty($videoUrl)): ?>
    <video id="reelVideo" class="clip-video" src="<?= htmlspecialchars($videoUrl) ?>" poster="<?= htmlspecialchars($thumbnail) ?>" loop playsinline preload="metadata"></video>
    <?php else: ?>
    <img src="<?= htmlspecialchars($thumbnail) ?>" alt="<?= htmlspecialchars($reel['title'] ?? '') ?>" class="clip-video" style="object-fit: cover;">
    <?php endif; ?>

    <!-- LOADING -->
    <?php if (!empty($videoUrl)): ?>
    <div id="videoLoader" class="video-loader absolute inset-0 flex items-center justify-center bg-black/80">
        <div class="w-10 h-10 border-[3px] border-white/30 border-t-white rounded-full" style="animation: spin 0.8s linear infinite;"></div>
    </div>
    <?php endif; ?>

    <!-- GRADIENTS -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40 pointer-events-none z-[1]"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-transparent to-transparent pointer-events-none z-[1]" style="height: 160px;"></div>

    <!-- TAP AREA -->
    <div class="absolute inset-0 z-[2]" id="tapArea" ondblclick="doubleTapLike(event)" onclick="togglePlay()"></div>

    <!-- ===== TOP NAV BAR ===== -->
    <div class="absolute top-0 left-0 right-0 z-[15] pointer-events-auto" style="background: linear-gradient(to bottom, rgba(0,0,0,0.8) 0%, transparent 100%); padding: 10px 16px 24px;">
        <div class="flex items-center justify-between mb-3">
            <!-- Back button -->
            <a href="/reels" class="w-9 h-9 rounded-full bg-black/40 backdrop-blur-sm flex items-center justify-center hover:bg-black/60 transition-colors">
                <span class="material-icons-round text-white text-xl">chevron_left</span>
            </a>
            <!-- Icons -->
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
        <button onclick="event.stopPropagation(); openComments()" class="action-icon flex flex-col items-center gap-0.5">
            <div class="action-circle">
                <span class="material-icons-round text-white text-[26px]">chat_bubble</span>
            </div>
            <span class="text-white text-[11px] font-semibold"><?= formatCount($reel['comments_count'] ?? 0) ?></span>
        </button>

        <!-- Share -->
        <button onclick="event.stopPropagation(); shareClip(<?= $reel['id'] ?? 0 ?>)" class="action-icon flex flex-col items-center gap-0.5">
            <div class="action-circle">
                <span class="material-icons-round text-white text-[26px]">send</span>
            </div>
            <span class="text-white text-[11px] font-semibold"><?= formatCount($reel['shares'] ?? 0) ?></span>
        </button>

        <!-- Repost -->
        <button onclick="event.stopPropagation(); repostClip(<?= $reel['id'] ?? 0 ?>, this)" class="action-icon flex flex-col items-center gap-0.5">
            <div class="action-circle">
                <span class="material-icons-round text-white text-[26px]">autorenew</span>
            </div>
            <span class="text-white text-[11px] font-semibold repost-count"><?= formatCount($reel['reposts'] ?? 0) ?></span>
        </button>

        <!-- Gift -->
        <button onclick="event.stopPropagation(); openReelGiftPanel(<?= $reel['id'] ?? 0 ?>)" class="action-icon flex flex-col items-center gap-0.5">
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
            <img src="<?= $reel['creator_avatar'] ?? 'https://placehold.co/40/40/3f3f46/ffffff?text=♪' ?>" alt="Music" class="w-full h-full object-cover">
        </div>
    </div>

    <!-- ===== BOTTOM: Creator Info + Description + Music ===== -->
    <div class="absolute bottom-[120px] left-3 right-20 z-[10] pointer-events-none">
        <!-- Creator Info -->
        <div class="flex items-center gap-2.5 mb-2 pointer-events-auto">
            <div class="creator-ring flex-shrink-0">
                <img src="<?= $reel['creator_avatar'] ?? 'https://placehold.co/44/44/3f3f46/ffffff?text=U' ?>" alt="<?= $reel['creator_name'] ?>" class="w-9 h-9 rounded-full border-2 border-black object-cover">
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1.5">
                    <span class="text-white text-[14px] font-bold truncate"><?= htmlspecialchars($reel['creator_name'] ?? $reel['username'] ?? '') ?></span>
                    <?php if (!empty($reel['is_verified'])): ?>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#3b82f6"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                    <?php endif; ?>
<?php $reelShowFollowing = !empty($reel['is_following']); ?>
                    <button onclick="event.stopPropagation(); toggleFollowBtn(this, <?= $reel['user_id'] ?? 0 ?>)" class="follow-btn px-4 py-1 rounded-full text-[11px] font-semibold ml-1 flex-shrink-0 <?= $reelShowFollowing ? 'following' : '' ?>"><?= $reelShowFollowing ? 'Following' : 'Follow' ?></button>
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
    <div class="progress-bar-track">
        <div class="progress-bar-fill" id="progressFill"></div>
    </div>
    <?php endif; ?>

</div>

<!-- ===== MORE MENU ===== -->
<div id="reelMenu" class="hidden fixed top-1/2 right-14 -translate-y-1/2 z-[40] bg-black/80 backdrop-blur-xl rounded-2xl border border-white/10 overflow-hidden shadow-2xl min-w-[180px] fade-in">
    <button onclick="showToast('Saved! 📌'); closeReelMenu()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
        <span class="material-icons-round text-zinc-300 text-lg">bookmark_border</span>
        <span class="text-zinc-200 text-sm font-medium">Save</span>
    </button>
    <?php if (!empty($videoUrl)): ?>
    <button onclick="showToast('Download started ⬇️'); closeReelMenu()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
        <span class="material-icons-round text-zinc-300 text-lg">download</span>
        <span class="text-zinc-200 text-sm font-medium">Download</span>
    </button>
    <?php endif; ?>
    <button onclick="shareClip(<?= $reel['id'] ?? 0 ?>); closeReelMenu()" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
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
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
            <div class="flex gap-2.5">
                <img src="<?= $comment['commenter_avatar'] ?? 'https://placehold.co/32/32/3f3f46/ffffff?text=U' ?>" class="w-8 h-8 rounded-full flex-shrink-0">
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-white text-xs font-semibold"><?= htmlspecialchars($comment['commenter_name'] ?? $comment['username'] ?? '') ?></span>
                        <span class="text-zinc-600 text-[10px]"><?= timeAgo($comment['created_at'] ?? '') ?></span>
                    </div>
                    <p class="text-zinc-200 text-[13px]"><?= htmlspecialchars($comment['body'] ?? '') ?></p>
                    <div class="flex items-center gap-3 mt-0.5">
                        <button class="text-zinc-600 text-[10px] font-medium hover:text-zinc-400">Reply</button>
                        <button class="flex items-center gap-0.5 text-zinc-600 text-[10px] hover:text-zinc-400"><span class="material-icons-round text-[10px]">favorite_border</span> <?= rand(5, 200) ?></button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-8">
                <span class="material-icons-round text-zinc-500 text-3xl">chat_bubble_outline</span>
                <p class="text-zinc-500 text-xs mt-2">No comments yet. Be the first!</p>
            </div>
        <?php endif; ?>
    </div>
    <div class="px-4 py-2.5 border-t border-white/10 flex items-center gap-2">
        <img src="https://placehold.co/32x32/6d28d9/ffffff?text=U" class="w-7 h-7 rounded-full flex-shrink-0">
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
                $clipGifts = $data['gifts'] ?? [];
                if (empty($clipGifts)) {
                    $clipGifts = [
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
                <?php foreach ($clipGifts as $g): ?>
                <button onclick="sendClipGift(<?= $reel['id'] ?? 0 ?>, <?= $g['id'] ?>)" class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-blue-500/40 transition-all group" data-gift-id="<?= $g['id'] ?>">
                    <?php if (!empty($g['image_url'])): ?>
                    <div class="w-9 h-9 rounded-lg overflow-hidden group-hover:scale-110 transition-transform">
                        <img src="<?= $g['image_url'] ?>" alt="<?= $g['name'] ?>" class="w-full h-full object-cover">
                    </div>
                    <?php else: ?>
                    <span class="material-icons-round <?= $g['color_class'] ?> text-2xl group-hover:scale-110 transition-transform"><?= $g['icon'] ?></span>
                    <?php endif; ?>
                    <span class="text-zinc-300 text-[10px] font-medium"><?= $g['name'] ?></span>
                    <span class="text-blue-400 text-[9px] font-bold">$<?= number_format((float)($g['price_usd'] ?? 0), 2) ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
const reelId = <?= $reel['id'] ?? 0 ?>;
const video = document.getElementById('reelVideo');
const loader = document.getElementById('videoLoader');
const progressFill = document.getElementById('progressFill');
const container = document.getElementById('reelShowContainer');

// ===================== VIDEO PLAYBACK =====================
function togglePlay() {
    if (!video || video.tagName !== 'VIDEO') return;
    if (video.paused) playVideo(); else pauseVideo();
}

function playVideo() {
    if (!video || video.tagName !== 'VIDEO') return;
    container.dataset.playing = 'true';
    const p = video.play();
    if (p) p.catch(() => pauseVideo());
}

function pauseVideo() {
    if (!video || video.tagName !== 'VIDEO') return;
    video.pause();
    container.dataset.playing = 'false';
}

if (video && video.tagName === 'VIDEO') {
    video.addEventListener('canplay', () => { if (loader) loader.classList.add('hidden-loader'); });
    video.addEventListener('waiting', () => { if (loader) loader.classList.remove('hidden-loader'); });
    video.addEventListener('playing', () => { if (loader) loader.classList.add('hidden-loader'); });
    video.addEventListener('timeupdate', () => {
        if (video.duration && progressFill) {
            progressFill.style.width = (video.currentTime / video.duration * 100) + '%';
        }
    });
    video.addEventListener('ended', () => { video.currentTime = 0; video.play(); });
    video.addEventListener('error', () => { if (loader) loader.classList.add('hidden-loader'); });
    // Auto-play on load
    window.addEventListener('load', () => setTimeout(() => playVideo(), 300));
}

// ===================== UI INTERACTIONS =====================
function toggleLike(btn) {
    const icon = btn.querySelector('.like-icon');
    const countEl = btn.querySelector('.like-count');
    if (icon.textContent === 'favorite_border') {
        icon.textContent = 'favorite'; icon.style.color = '#ef4444';
        let c = parseInt(countEl.textContent.replace(/[^0-9]/g, '')) || 0;
        countEl.textContent = formatCount(c + 1);
        fetch('/reels/' + reelId + '/like', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
    } else {
        icon.textContent = 'favorite_border'; icon.style.color = '#ffffff';
        let c = parseInt(countEl.textContent.replace(/[^0-9]/g, '')) || 0;
        countEl.textContent = formatCount(Math.max(0, c - 1));
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
            if (wasFollowing) { btn.classList.add('following'); btn.textContent = 'Following'; }
            else { btn.classList.remove('following'); btn.textContent = 'Follow'; }
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

function doubleTapLike(event) {
    event.stopPropagation();
    const heart = document.createElement('div');
    heart.className = 'double-tap-heart';
    heart.innerHTML = '<span class="material-icons-round text-red-500" style="font-size: 80px; filter: drop-shadow(0 2px 10px rgba(239,68,68,0.5));">favorite</span>';
    container.appendChild(heart);
    setTimeout(() => heart.remove(), 900);
    // Increment like
    const likeBtn = container.querySelector('.action-icon');
    if (likeBtn) { const icon = likeBtn.querySelector('.like-icon'); if (icon && icon.textContent === 'favorite_border') toggleLike(likeBtn); }
}

function toggleReelMenu() { document.getElementById('reelMenu').classList.toggle('hidden'); }
function closeReelMenu() { document.getElementById('reelMenu').classList.add('hidden'); }

function toggleSearch() { showToast('Search'); }

function openComments() { document.getElementById('commentsPanel').classList.remove('hidden'); }
function closeComments() { document.getElementById('commentsPanel').classList.add('hidden'); }
function sendComment() {
    const input = document.getElementById('commentInput');
    const msg = input.value.trim(); if (!msg) return;
    const scroll = document.getElementById('commentsScroll');
    const row = document.createElement('div');
    row.className = 'flex gap-2.5 fade-in';
    row.innerHTML = '<img src="https://placehold.co/32x32/6d28d9/ffffff?text=U" class="w-8 h-8 rounded-full flex-shrink-0"><div><span class="text-white text-xs font-semibold">You</span><p class="text-zinc-200 text-[13px]">' + escapeHtml(msg) + '</p><div class="flex items-center gap-3 mt-0.5"><span class="text-zinc-600 text-[10px]">Just now</span></div></div>';
    scroll.appendChild(row); scroll.scrollTop = scroll.scrollHeight;
    input.value = '';
    fetch('/reels/' + reelId + '/comment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ body: msg })
    }).catch(() => {});
}

function shareClip(id) {
    if (navigator.share) navigator.share({ title: 'Globiim Clip', url: '/reels/' + id }).catch(() => {});
    else navigator.clipboard.writeText(window.location.origin + '/reels/' + id).then(() => showToast('Link copied!')).catch(() => {});
    fetch('/reels/' + id + '/share', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

function openReelGiftPanel(id) { document.getElementById('reelGiftPanel').classList.remove('hidden'); }
function closeReelGiftPanel(e) { if (!e || e.target === e.currentTarget) document.getElementById('reelGiftPanel').classList.add('hidden'); }
function sendClipGift(rid, giftId) {
    closeReelGiftPanel();
    const grid = document.getElementById('reelGiftGrid');
    const btn = grid.querySelector('button[data-gift-id="'+giftId+'"]');
    const name = btn ? btn.querySelector('.text-zinc-300').textContent : 'Gift';
    showToast('Sent ' + name + '! 🎁');
    fetch('/reels/' + rid + '/gift', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ gift_id: giftId }) }).catch(() => {});
}

function formatCount(num) { num = parseInt(num) || 0; if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M'; if (num >= 1000) return (num / 1000).toFixed(1) + 'K'; return num.toString(); }
function escapeHtml(str) { const d = document.createElement('div'); d.textContent = str; return d.innerHTML; }

function showToast(msg) {
    const existing = document.querySelector('.clip-toast');
    if (existing) existing.remove();
    const div = document.createElement('div');
    div.className = 'clip-toast fixed top-16 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-sm font-medium z-[200]';
    div.style.cssText = 'background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 20px rgba(131,74,229,0.4);';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(() => div.remove(), 300); }, 2000);
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
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
