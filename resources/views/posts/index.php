<?php $hideTopNav = true; $activeTab = 'post'; $title = 'GLOOBIM - Posts'; ?>
<?php extract($data ?? []); ?>
<?php ob_start(); ?>
<style>
    @keyframes pulse-live { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
    .pulse-live { animation: pulse-live 2s ease-in-out infinite; }

    .story-ring-new { background: linear-gradient(135deg, #22c55e, #10b981, #34d399); padding: 3px; border-radius: 50%; }
    .story-ring-seen { background: #3f3f46; padding: 3px; border-radius: 50%; }
    .story-ring-purple { background: linear-gradient(135deg, #9333ea, #ec4899); padding: 3px; border-radius: 50%; }
    .story-ring-orange { background: linear-gradient(135deg, #f97316, #eab308); padding: 3px; border-radius: 50%; }

    .scroll-snap-x { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
    .scroll-snap-x > * { scroll-snap-align: start; }

    .action-btn { transition: all 0.2s ease; }
    .action-btn:hover { transform: scale(1.15); }
    .action-btn:active { transform: scale(0.9); }

    .post-card { transition: all 0.2s ease; }
    .post-card:hover { border-color: rgba(115, 29, 252, 0.2); }

    .follow-btn { transition: all 0.25s ease; }
    .follow-btn:hover { opacity: 0.85; transform: scale(1.03); }
    .follow-btn.following { background: #1e1e2a !important; color: #a1a1aa !important; }

    .gradient-animate { background-size: 200% 200%; animation: gradient-shift 4s ease infinite; }
    @keyframes gradient-shift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    .shimmer { background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.06) 50%, transparent 75%); background-size: 200% 100%; animation: shimmer 3s infinite; }

    .create-fab { box-shadow: 0 4px 20px rgba(115, 29, 252, 0.5), 0 0 40px rgba(168, 85, 247, 0.2); }
    .create-fab:hover { box-shadow: 0 4px 30px rgba(115, 29, 252, 0.7), 0 0 60px rgba(168, 85, 247, 0.3); }

    .feature-icon { transition: all 0.25s ease; }
    .feature-icon:hover { transform: translateY(-3px); }

    .suggestion-card { transition: all 0.25s ease; }
    .suggestion-card:hover { border-color: rgba(115, 29, 252, 0.3); }
</style>

<div class="max-w-lg mx-auto pb-4">

    <!-- ===== HEADER ===== -->
    <div class="px-4 pt-3 pb-2">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center gradient-animate" style="background: linear-gradient(135deg, #834ae5, #ec4899, #834ae5);">
                    <span class="material-icons-round text-white text-lg">play_arrow</span>
                </div>
                <h1 class="font-display text-xl font-bold tracking-tight">
                    <span class="text-white">GLO</span><span class="gradient-text">OB</span><span class="text-white">IM</span>
                </h1>
            </div>
            <div class="flex items-center gap-2">
                <!-- Notification Bell - Gradient Purple/Blue/Cyan -->
                <button class="w-9 h-9 rounded-full flex items-center justify-center relative hover:opacity-80 transition-opacity" style="background: linear-gradient(135deg, #731dfc, #3b82f6, #06b6d4);">
                    <span class="material-icons-round text-white text-[18px]">notifications_none</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <!-- Messenger - Gradient Cyan/Pink -->
                <button onclick="window.location.href='/messages'" class="w-9 h-9 rounded-full flex items-center justify-center relative hover:opacity-80 transition-opacity" style="background: linear-gradient(135deg, #06b6d4, #ec4899);">
                    <span class="material-icons-round text-white text-[18px]">chat_bubble_outline</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-[#34b747] rounded-full"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- ===== STORIES ROW ===== -->
    <div class="px-4 py-2">
        <div class="flex gap-3 overflow-x-auto scrollbar-hide py-1 scroll-snap-x">
            <!-- Your Story -->
            <a href="/reels/create" class="flex-shrink-0 flex flex-col items-center gap-1.5 w-[68px]">
                <div class="w-16 h-16 rounded-full bg-[#14141c] border-2 border-dashed border-zinc-700 flex items-center justify-center hover:border-[#834ae5] transition-colors">
                    <span class="material-icons-round text-[#834ae5] text-2xl">add</span>
                </div>
                <span class="text-[10px] text-zinc-500 font-medium truncate w-full text-center">Your Story</span>
            </a>
            <!-- Creator Stories -->
            <?php
            $storyRings = ['story-ring-new', 'story-ring-purple', 'story-ring-new', 'story-ring-orange', 'story-ring-seen', 'story-ring-new', 'story-ring-seen', 'story-ring-purple'];
            foreach ($stories as $i => $story):
                if ($story['is_add'] ?? false) continue;
                $ringClass = $storyRings[$i % count($storyRings)] ?? 'story-ring-new';
            ?>
            <a href="#" class="flex-shrink-0 flex flex-col items-center gap-1.5 w-[68px]" onclick="viewStory(<?= $story['id'] ?>, event)">
                <div class="<?= $story['has_story'] ? $ringClass : 'story-ring-seen' ?>">
                    <div class="w-[58px] h-[58px] rounded-full overflow-hidden bg-[#14141c] border-2 border-[#090c15]">
                        <img src="<?= $story['avatar'] ?>" alt="<?= $story['name'] ?>" class="w-full h-full object-cover">
                    </div>
                </div>
                <span class="text-[10px] text-zinc-400 font-medium truncate w-full text-center"><?= explode(' ', $story['name'])[0] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== CREATE POST AREA ===== -->
    <div class="px-4 py-3">
        <div class="flex items-center gap-3 p-3 rounded-2xl border border-[#1e1e2a]" style="background: rgba(20,20,28,0.6);">
            <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0" style="box-shadow: 0 0 0 2px rgba(131,74,229,0.5);">
                <img src="/uploads/profiles/admin.jpg" alt="You" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <input type="text" placeholder="What's on your mind?" class="w-full bg-transparent text-white text-sm placeholder:text-zinc-600 focus:outline-none" onclick="window.location.href='/posts/create'">
            </div>
            <div class="flex items-center gap-1.5">
                <button class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#1e1e2a] transition-colors" title="Photo">
                    <span class="material-icons-round text-[#ee0f4d] text-[18px]">photo_camera</span>
                </button>
                <button class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#1e1e2a] transition-colors" title="Video">
                    <span class="material-icons-round text-[#06b6d4] text-[18px]">videocam</span>
                </button>
                <button class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#1e1e2a] transition-colors" title="GIF">
                    <span class="material-icons-round text-[#f59e0b] text-[18px]">gif</span>
                </button>
                <button class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#1e1e2a] transition-colors" title="Poll">
                    <span class="material-icons-round text-[#34b747] text-[18px]">poll</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ===== POSTS FEED ===== -->
    <div class="px-4 py-2">
        <div class="space-y-4">
            <?php foreach ($posts as $post): ?>
            <article class="post-card rounded-2xl overflow-hidden border border-[#1e1e2a]" style="background: rgba(20,20,28,0.4);">
                <!-- Post Header -->
                <div class="flex items-center gap-2.5 p-3.5 pb-2">
                    <div class="story-ring-new" style="padding: 2px;">
                        <img src="<?= $post['creator_avatar'] ?>" alt="" class="w-9 h-9 rounded-full object-cover border-2 border-[#090c15]">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1 flex-wrap">
                            <span class="text-[13px] font-semibold" style="color: #ee0f4d;"><?= htmlspecialchars($post['creator_name']) ?></span>
                            <?php if (!empty($post['is_verified'])): ?>
                            <span class="material-icons-round text-[13px]" style="color: #731dfc;">verified</span>
                            <?php endif; ?>
                            <span class="text-zinc-600 text-[11px]">· <?= timeAgo($post['created_at']) ?></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button onclick="followUser(<?= $post['user_id'] ?? 0 ?>, this)" class="follow-btn px-3.5 py-1 rounded-full text-[11px] font-bold text-white" style="background: #34b747;">Follow</button>
                        <button class="p-1 rounded-full hover:bg-[#1e1e2a] transition-colors">
                            <span class="material-icons-round text-zinc-500 text-[18px]">more_horiz</span>
                        </button>
                    </div>
                </div>

                <!-- Post Content -->
                <div class="px-3.5 pb-2">
                    <p class="text-zinc-200 text-[13px] leading-relaxed"><?= htmlspecialchars($post['content']) ?></p>
                </div>

                <!-- Post Image -->
                <?php if (!empty($post['image_url'])): ?>
                <div class="relative">
                    <img src="<?= $post['image_url'] ?>" alt="Post" class="w-full max-h-[400px] object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#090c15]/30 via-transparent to-transparent pointer-events-none"></div>
                </div>
                <?php endif; ?>

                <!-- Post Reactions & Actions -->
                <div class="px-3.5 py-2.5 flex items-center justify-between">
                    <button onclick="likePost(<?= $post['id'] ?>, this)" class="action-btn flex items-center gap-1 text-zinc-500 hover:text-[#9333ea]">
                        <span class="material-icons-round text-[20px]">favorite_border</span>
                        <span class="text-[11px]"><?= formatCount($post['likes'] ?? 0) ?></span>
                    </button>
                    <button onclick="commentOnPost(<?= $post['id'] ?>)" class="action-btn flex items-center gap-1 text-zinc-500 hover:text-[#06b6d4]">
                        <span class="material-icons-round text-[20px]">chat_bubble_outline</span>
                        <span class="text-[11px]"><?= formatCount($post['comments_count'] ?? 0) ?></span>
                    </button>
                    <button onclick="sharePost(<?= $post['id'] ?>)" class="action-btn flex items-center gap-1 text-zinc-500 hover:text-[#ec4899]">
                        <span class="material-icons-round text-[20px]">share</span>
                        <span class="text-[11px]"><?= formatCount($post['shares'] ?? 0) ?></span>
                    </button>
                    <button onclick="bookmarkPost(<?= $post['id'] ?>, this)" class="action-btn text-zinc-500 hover:text-[#eab308]">
                        <span class="material-icons-round text-[20px]">bookmark_border</span>
                    </button>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== FEATURE CATEGORY ICONS ===== -->
    <div class="px-4 py-3 mt-1">
        <div class="flex items-center justify-between gap-3">
            <a href="/music" class="feature-icon flex-1 flex flex-col items-center gap-1.5">
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #9333ea, #6366f1);">
                    <span class="material-icons-round text-white text-xl">music_note</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-medium">Music</span>
            </a>
            <a href="/reels/create" class="feature-icon flex-1 flex flex-col items-center gap-1.5">
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">
                    <span class="material-icons-round text-white text-xl">photo_camera</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-medium">Photo</span>
            </a>
            <a href="/reels" class="feature-icon flex-1 flex flex-col items-center gap-1.5">
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #06b6d4, #3b82f6);">
                    <span class="material-icons-round text-white text-xl">play_circle</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-medium">Reels</span>
            </a>
            <a href="/marketplace" class="feature-icon flex-1 flex flex-col items-center gap-1.5">
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #f97316, #eab308);">
                    <span class="material-icons-round text-white text-xl">shopping_bag</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-medium">Shop</span>
            </a>
            <a href="/livestream" class="feature-icon flex-1 flex flex-col items-center gap-1.5">
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #ef4444, #f97316);">
                    <span class="material-icons-round text-white text-xl">sensors</span>
                </div>
                <span class="text-zinc-400 text-[10px] font-medium">Live</span>
            </a>
        </div>
    </div>

    <!-- ===== SUGGESTED CREATORS ===== -->
    <div class="px-4 py-3">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="material-icons-round text-[18px]" style="color: #834ae5;">group</span>
                <h2 class="text-white text-sm font-bold">Suggested for You</h2>
            </div>
            <button class="text-[11px] font-semibold" style="color: #834ae5;">See All</button>
        </div>
        <div class="grid grid-cols-3 gap-2.5">
            <?php foreach ($suggestedCreators as $creator): ?>
            <div class="suggestion-card rounded-xl p-3 text-center border border-[#1e1e2a] transition-all hover:shadow-lg" style="background: rgba(20,20,28,0.5);">
                <div class="relative w-12 h-12 mx-auto mb-2">
                    <div class="story-ring-new" style="padding: 2px;">
                        <img src="<?= $creator['avatar'] ?>" alt="<?= $creator['name'] ?>" class="w-full h-full rounded-full object-cover border-2 border-[#090c15]">
                    </div>
                    <?php if (!empty($creator['is_verified'])): ?>
                    <span class="absolute -bottom-0.5 -right-0.5 material-icons-round text-sm rounded-full" style="color: #731dfc;">verified</span>
                    <?php endif; ?>
                </div>
                <h3 class="text-white text-[11px] font-semibold truncate"><?= $creator['name'] ?></h3>
                <p class="text-zinc-500 text-[9px]"><?= $creator['followers'] ?> followers</p>
                <button onclick="followUser(<?= $creator['id'] ?>, this)" class="follow-btn mt-1.5 w-full py-1 rounded-full text-white text-[10px] font-bold" style="background: #34b747;">Follow</button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ===== PROMO BANNER ===== -->
    <div class="px-4 py-2">
        <div class="relative rounded-2xl overflow-hidden" style="box-shadow: 0 0 30px rgba(115,29,252,0.15);">
            <img src="/uploads/posts/promo_banner.jpg" alt="Go Live" class="w-full aspect-[2/1] object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent"></div>
            <div class="absolute inset-0 shimmer"></div>
            <div class="absolute inset-0 flex flex-col justify-center p-5">
                <h2 class="font-display text-xl font-bold text-white leading-tight max-w-[60%]">Go Live on GLOOBIM</h2>
                <p class="text-zinc-300 text-xs mt-1 max-w-[55%]">Stream & earn in real-time</p>
                <div class="flex items-center gap-3 mt-3">
                    <a href="/livestream/start" class="px-5 py-2 rounded-full text-white text-xs font-bold shadow-lg hover:opacity-90 transition-opacity" style="background: linear-gradient(135deg, #ec4899, #9333ea); box-shadow: 0 4px 15px rgba(236,72,153,0.4);">
                        Start Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom spacer -->
    <div class="h-4"></div>
</div>

<script>
function formatCount(num) {
    num = parseInt(num) || 0;
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
    return num.toString();
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

function likePost(id, btn) {
    const icon = btn.querySelector('.material-icons-round');
    const countEl = btn.querySelector('span:last-child');
    if (icon.textContent === 'favorite') return;
    icon.textContent = 'favorite';
    icon.classList.add('text-red-400');
    icon.classList.remove('text-zinc-500');
    btn.classList.add('text-red-400');
    btn.classList.remove('text-zinc-500');
    let c = parseInt(countEl.textContent.replace(/[^0-9]/g, '')) || 0;
    countEl.textContent = formatCount(c + 1);
    fetch('/posts/' + id + '/like', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

function commentOnPost(id) {
    const comment = prompt('Write a comment:');
    if (comment && comment.trim()) {
        fetch('/posts/' + id + '/comment', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ body: comment.trim() }) })
        .then(r => r.json()).then(() => showToast('Comment added!')).catch(() => showToast('Comment added!'));
    }
}

function sharePost(id) {
    if (navigator.share) {
        navigator.share({ title: 'GLOOBIM Post', url: '/posts/' + id }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.origin + '/posts/' + id).then(() => showToast('Link copied!')).catch(() => {});
    }
}

function bookmarkPost(id, btn) {
    const icon = btn.querySelector('.material-icons-round');
    if (icon.textContent === 'bookmark') {
        icon.textContent = 'bookmark_border';
        icon.classList.remove('text-yellow-400');
    } else {
        icon.textContent = 'bookmark';
        icon.classList.add('text-yellow-400');
    }
}

function followUser(id, btn) {
    if (btn.classList.contains('following')) {
        btn.classList.remove('following');
        btn.textContent = 'Follow';
        btn.style.background = '#34b747';
    } else {
        btn.classList.add('following');
        btn.textContent = 'Following';
    }
    fetch('/follow/' + id, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

function viewStory(id, e) {
    e.preventDefault();
    showToast('Story viewer coming soon!');
}

function showToast(msg) {
    const existing = document.querySelector('.toast-msg');
    if (existing) existing.remove();
    const div = document.createElement('div');
    div.className = 'toast-msg fixed top-16 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-sm font-medium z-[200]';
    div.style.cssText = 'background: linear-gradient(135deg, #9333ea, #731dfc); box-shadow: 0 4px 20px rgba(115,29,252,0.4);';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(() => div.remove(), 300); }, 2000);
}
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
