<?php $hideTopNav = true; $title = ($post['creator_name'] ?? 'Post') . ' - GLOOBIM'; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto">

    <div class="flex items-center justify-between px-3 py-3 border-b border-surface-400/20">
        <div class="flex items-center gap-3">
            <a href="javascript:history.back()" class="text-zinc-400 hover:text-white transition-colors">
                <span class="material-icons-round text-2xl">arrow_back</span>
            </a>
            <span class="text-white text-sm font-semibold">Post</span>
        </div>
        <button onclick="reportPost()" class="text-zinc-500 hover:text-zinc-300 transition-colors">
            <span class="material-icons-round text-xl">more_horiz</span>
        </button>
    </div>

    <div class="bg-surface-100/30 rounded-2xl mx-3 mt-3 overflow-hidden border border-surface-400/10">
        <div class="flex items-center justify-between px-4 pt-4 pb-2">
            <a href="/creator/<?= $post['username'] ?>" class="flex items-center gap-3">
                <img src="<?= $post['creator_avatar'] ?>" alt="<?= $post['creator_name'] ?>" class="w-10 h-10 rounded-full">
                <div>
                    <div class="flex items-center gap-1">
                        <span class="text-white text-sm font-semibold"><?= $post['creator_name'] ?></span>
                        <?php if (!empty($post['is_verified'])): ?>
                        <span class="material-icons-round text-brand-400 text-[14px]">verified</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-zinc-500 text-[11px]"><?= $post['username'] ?> · <?= timeAgo($post['created_at']) ?></span>
                </div>
            </a>
            <button onclick="followUser(<?= $post['user_id'] ?? 0 ?>)" class="px-3 py-1.5 rounded-full gradient-brand text-white text-[10px] font-bold hover:opacity-90 transition-opacity" id="followBtn">Follow</button>
        </div>

        <div class="px-4 py-2">
            <p class="text-zinc-200 text-[13px] leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($post['content']) ?></p>
        </div>

        <?php if (!empty($post['image_url'])): ?>
        <div class="relative">
            <img src="<?= $post['image_url'] ?>" alt="Post image" class="w-full max-h-[500px] object-cover">
        </div>
        <?php endif; ?>

        <div class="px-4 py-3 flex items-center justify-between border-t border-surface-400/10">
            <button onclick="likePost(<?= $post['id'] ?>)" class="flex items-center gap-1.5 text-zinc-400 hover:text-red-400 transition-colors group" id="likeBtn">
                <span class="material-icons-round text-xl group-hover:scale-110 transition-transform" id="postLikeIcon">favorite_border</span>
                <span class="text-[12px]" id="postLikeCount"><?= formatCount($post['likes'] ?? 0) ?></span>
            </button>
            <button onclick="focusComment()" class="flex items-center gap-1.5 text-zinc-400 hover:text-brand-400 transition-colors group">
                <span class="material-icons-round text-xl group-hover:scale-110 transition-transform">chat_bubble_outline</span>
                <span class="text-[12px]"><?= formatCount($post['comments_count'] ?? 0) ?></span>
            </button>
            <button onclick="sharePost(<?= $post['id'] ?>)" class="flex items-center gap-1.5 text-zinc-400 hover:text-green-400 transition-colors group">
                <span class="material-icons-round text-xl group-hover:scale-110 transition-transform">ios_share</span>
                <span class="text-[12px]"><?= formatCount($post['shares'] ?? 0) ?></span>
            </button>
            <button onclick="savePost(<?= $post['id'] ?>)" class="flex items-center gap-1.5 text-zinc-400 hover:text-amber-400 transition-colors group">
                <span class="material-icons-round text-xl group-hover:scale-110 transition-transform">bookmark_border</span>
                <span class="text-[12px]">Save</span>
            </button>
        </div>
    </div>

    <div class="flex items-center gap-2 px-3 py-4">
        <span class="material-icons-round text-zinc-500 text-lg">chat</span>
        <h3 class="text-white text-sm font-bold">Comments</h3>
        <span class="px-1.5 py-0.5 rounded bg-surface-300 text-zinc-400 text-[9px] font-medium"><?= count($comments) ?></span>
    </div>

    <div class="px-3 pb-3">
        <div class="flex items-start gap-3 p-3 rounded-2xl bg-surface-100/60 border border-surface-400/15">
            <div class="w-8 h-8 rounded-full gradient-brand flex-shrink-0 flex items-center justify-center text-white text-[10px] font-bold">
                Y
            </div>
            <div class="flex-1 min-w-0">
                <textarea id="commentInput" placeholder="Write a comment..." rows="2" class="w-full bg-transparent text-white text-xs placeholder:text-zinc-500 focus:outline-none resize-none leading-relaxed"></textarea>
                <div class="flex items-center justify-between mt-1">
                    <div class="flex items-center gap-2">
                        <button class="text-zinc-500 hover:text-zinc-300 transition-colors">
                            <span class="material-icons-round text-lg">emoji_emotions</span>
                        </button>
                        <button class="text-zinc-500 hover:text-zinc-300 transition-colors">
                            <span class="material-icons-round text-lg">add_photo_alternate</span>
                        </button>
                    </div>
                    <button onclick="postComment(<?= $post['id'] ?>)" class="px-4 py-1.5 rounded-full gradient-brand text-white text-[10px] font-bold hover:opacity-90 transition-opacity disabled:opacity-50" id="commentBtn" disabled>Post</button>
                </div>
            </div>
        </div>
    </div>

    <div class="px-3 pb-6 space-y-3" id="commentsSection">
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
            <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-surface-100/40 border border-surface-400/10">
                <img src="<?= $comment['commenter_avatar'] ?? '/uploads/profiles/admin.jpg' ?>" alt="" class="w-8 h-8 rounded-full flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 mb-0.5">
                        <span class="text-white text-xs font-semibold"><?= $comment['commenter_name'] ?? 'User' ?></span>
                        <?php if (!empty($comment['is_verified'])): ?>
                        <span class="material-icons-round text-brand-400 text-[10px]">verified</span>
                        <?php endif; ?>
                        <span class="text-zinc-600 text-[10px]">· <?= timeAgo($comment['created_at'] ?? null) ?></span>
                    </div>
                    <p class="text-zinc-300 text-[12px] leading-relaxed"><?= htmlspecialchars($comment['body'] ?? '') ?></p>
                    <div class="flex items-center gap-3 mt-1.5">
                        <button onclick="likeComment(<?= $comment['id'] ?>)" class="text-zinc-600 hover:text-red-400 transition-colors flex items-center gap-0.5">
                            <span class="material-icons-round text-[14px]">favorite_border</span>
                            <span class="text-[9px]"><?= formatCount($comment['likes'] ?? 0) ?></span>
                        </button>
                        <button onclick="replyComment(<?= $comment['id'] ?>)" class="text-zinc-600 hover:text-brand-400 transition-colors text-[10px] font-medium">Reply</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-8">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-surface-200/80 flex items-center justify-center">
                    <span class="material-icons-round text-zinc-500 text-2xl">chat_bubble_outline</span>
                </div>
                <p class="text-zinc-500 text-xs">No comments yet. Be the first to comment!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
let postLiked = false;
let postLikeCount = <?= (int)($post['likes'] ?? 0) ?>;

function likePost(id) {
    const icon = document.getElementById('postLikeIcon');
    const count = document.getElementById('postLikeCount');
    if (!postLiked) {
        icon.textContent = 'favorite';
        icon.classList.add('text-red-400');
        postLiked = true;
    }
    postLikeCount++;
    count.textContent = formatCount(postLikeCount);
    fetch('/posts/' + id + '/like', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

document.getElementById('commentInput')?.addEventListener('input', function() {
    document.getElementById('commentBtn').disabled = this.value.trim().length === 0;
});

function postComment(id) {
    const input = document.getElementById('commentInput');
    const text = input.value.trim();
    if (!text) return;

    const section = document.getElementById('commentsSection');
    const div = document.createElement('div');
    div.className = 'flex items-start gap-2.5 p-3 rounded-2xl bg-surface-100/40 border border-surface-400/10 fade-in';
    div.innerHTML = '<div class="w-8 h-8 rounded-full gradient-brand flex-shrink-0 flex items-center justify-center text-white text-[10px] font-bold">Y</div><div class="flex-1 min-w-0"><div class="flex items-center gap-1.5 mb-0.5"><span class="text-white text-xs font-semibold">You</span><span class="text-zinc-600 text-[10px]">· just now</span></div><p class="text-zinc-300 text-[12px] leading-relaxed">' + escapeHtml(text) + '</p></div>';
    section.prepend(div);
    input.value = '';
    document.getElementById('commentBtn').disabled = true;

    fetch('/posts/' + id + '/comment', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ body: text }) }).catch(() => {});
}

function sharePost(id) {
    if (navigator.share) {
        navigator.share({ title: 'DTTube Post', url: window.location.href }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 left-1/2 -translate-x-1/2 px-4 py-2 rounded-full bg-surface-200 text-white text-sm font-medium z-50 slide-up';
            toast.textContent = 'Link copied!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }).catch(() => {});
    }
    fetch('/posts/' + id + '/share', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).catch(() => {});
}

function followUser(userId) {
    const btn = document.getElementById('followBtn');
    if (!userId) return;
    const wasText = btn.textContent;
    btn.textContent = 'Following';
    btn.classList.remove('gradient-brand');
    btn.classList.add('bg-surface-300', 'text-zinc-300');
    fetch('/follow/' + userId, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(r => r.json()).then(d => {
        if (d.error) { location.href = '/login'; btn.textContent = wasText; btn.classList.add('gradient-brand'); btn.classList.remove('bg-surface-300','text-zinc-300'); return; }
    }).catch(() => { btn.textContent = wasText; btn.classList.add('gradient-brand'); btn.classList.remove('bg-surface-300','text-zinc-300'); });
}

function savePost(id) { alert('Post saved!'); }
function reportPost() { alert('Report options coming soon!'); }
function focusComment() { document.getElementById('commentInput')?.focus(); }
function likeComment(id) {}
function replyComment(id) { document.getElementById('commentInput')?.focus(); }

function formatCount(num) {
    num = parseInt(num) || 0;
    if (num >= 1000000) return (num / 1000000).toFixed(1) + 'M';
    if (num >= 1000) return (num / 1000).toFixed(1) + 'K';
    return num.toString();
}

function escapeHtml(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
