<?php $hideTopNav = true; $hideBottomNav = true; $title = 'Story - Globiim'; ?>
<?php extract($data ?? []); ?>
<?php ob_start(); ?>

<?php
// Default to first story if none selected
$allStories = $userStories ?? [$story];
$story = $story ?? ($allStories[0] ?? null);

if (!$story):
?>
<script>window.location.href = '/stories';</script>
<?php return; endif;

// Helper: time ago
function timeAgoStory($dateStr): string {
    if (empty($dateStr)) return '';
    $now = time();
    $date = strtotime(str_replace('-', '/', $dateStr));
    $diff = $now - $date;
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    return floor($diff / 86400) . 'd ago';
}
?>

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    .story-viewer {
        position: fixed;
        inset: 0;
        background: #000;
        z-index: 100;
        overflow: hidden;
        touch-action: pan-y;
    }

    .story-stage {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .story-stage img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .story-stage .story-text {
        position: absolute;
        left: 32px;
        right: 32px;
        text-align: center;
        text-shadow: 0 2px 12px rgba(0,0,0,0.9), 0 0 30px rgba(0,0,0,0.6);
        pointer-events: none;
        line-height: 1.35;
        z-index: 2;
    }
    .story-stage .story-text.top { top: 100px; }
    .story-stage .story-text.center { top: 50%; transform: translateY(-50%); }
    .story-stage .story-text.bottom { bottom: 100px; }

    /* Progress Bars */
    .progress-bars {
        position: absolute;
        top: 12px;
        left: 12px;
        right: 12px;
        display: flex;
        gap: 4px;
        z-index: 10;
    }
    .progress-bar-track {
        flex: 1;
        height: 3px;
        background: rgba(255,255,255,0.25);
        border-radius: 2px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        background: #ffffff;
        border-radius: 2px;
        width: 0%;
        transition: width 0.1s linear;
    }
    .progress-bar-fill.done {
        width: 100%;
        transition: none;
    }

    /* Header */
    .story-top-bar {
        position: absolute;
        top: 24px;
        left: 16px;
        right: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 10;
    }
    .story-top-bar .avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.4);
        object-fit: cover;
    }
    .story-top-bar .info {
        flex: 1;
        min-width: 0;
    }
    .story-top-bar .info .name {
        color: white;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .story-top-bar .info .time {
        color: rgba(255,255,255,0.6);
        font-size: 11px;
    }
    .story-close {
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
    }
    .story-close:hover { background: rgba(255,255,255,0.25); }

    /* Bottom actions */
    .story-bottom-bar {
        position: absolute;
        bottom: 32px;
        left: 16px;
        right: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        z-index: 10;
    }
    .story-reply-input {
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
    .story-reply-input::placeholder {
        color: rgba(255,255,255,0.4);
    }
    .story-views-badge {
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
    }

    /* Nav zones */
    .nav-left, .nav-right {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 50%;
        z-index: 5;
    }
    .nav-left { left: 0; cursor: pointer; }
    .nav-right { right: 0; cursor: pointer; }

    /* Swipe hint */
    .swipe-hint {
        position: absolute;
        bottom: 120px;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(255,255,255,0.5);
        font-size: 11px;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.6s;
    }
    .swipe-hint.show { opacity: 1; }
</style>

<div class="story-viewer" id="storyViewer">
    <!-- Progress Bars -->
    <div class="progress-bars" id="progressBars">
        <?php foreach ($allStories as $i => $s): ?>
        <div class="progress-bar-track">
            <div class="progress-bar-fill" data-index="<?= $i ?>"></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Top Bar -->
    <div class="story-top-bar">
        <img src="<?= $story['avatar'] ?>" alt="" class="avatar">
        <div class="info">
            <div class="name">
                <?= $story['name'] ?>
                <?php if (!empty($story['is_verified'])): ?>
                <span class="material-icons-round text-[14px]" style="color: #834ae5;">verified</span>
                <?php endif; ?>
            </div>
            <div class="time"><?= timeAgoStory($story['created_at']) ?></div>
        </div>
        <div class="story-views-badge">
            <span class="material-icons-round text-[14px]">visibility</span>
            <span id="viewCount"><?= $story['views_count'] ?></span>
        </div>
        <button class="story-close" onclick="closeStory()" title="Close">
            <span class="material-icons-round">close</span>
        </button>
    </div>

    <!-- Story Stage -->
    <div class="story-stage" id="storyStage">
        <!-- Image -->
        <img id="storyImage" src="<?= $story['image_url'] ?>" alt="Story">

        <!-- Text Overlay -->
        <?php if (!empty($story['text_content'])): ?>
        <div id="storyOverlayText" class="story-text <?= $story['text_position'] ?>"
             style="color: <?= $story['text_color'] ?>;
                    font-size: <?= $story['text_size'] ?>px;
                    font-weight: <?= $story['font_style'] === 'bold' ? '700' : ($story['font_style'] === 'italic' ? '400' : '500') ?>;
                    font-style: <?= $story['font_style'] === 'italic' ? 'italic' : 'normal' ?>;">
            <?= htmlspecialchars($story['text_content']) ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Navigation Zones -->
    <div class="nav-left" onclick="prevStory()"></div>
    <div class="nav-right" onclick="nextStory()"></div>

    <!-- Bottom Bar -->
    <div class="story-bottom-bar">
        <input type="text" class="story-reply-input" placeholder="Send message..." id="replyInput">
        <button class="story-close" style="width:40px;height:40px;flex-shrink:0;" onclick="sendReply()" title="Send">
            <span class="material-icons-round text-[20px]">send</span>
        </button>
    </div>

    <!-- Swipe hint -->
    <div class="swipe-hint" id="swipeHint">← swipe to navigate →</div>
</div>

<script>
const stories = <?= json_encode(array_map(function($s) {
    return [
        'id' => $s['id'],
        'image_url' => $s['image_url'],
        'text_content' => $s['text_content'] ?? '',
        'text_position' => $s['text_position'] ?? 'center',
        'text_color' => $s['text_color'] ?? '#ffffff',
        'text_size' => $s['text_size'] ?? '24',
        'font_style' => $s['font_style'] ?? 'normal',
        'views_count' => $s['views_count'] ?? 0,
        'name' => $s['name'],
        'username' => $s['username'],
        'avatar' => $s['avatar'],
        'is_verified' => $s['is_verified'] ?? false,
        'created_at' => $s['created_at'],
    ];
}, $allStories)) ?>;

let currentIndex = 0;
let progressInterval = null;
let progress = 0;
const STORY_DURATION = 5000; // 5 seconds per story
const TICK_MS = 50;

const storyImage = document.getElementById('storyImage');
const viewCount = document.getElementById('viewCount');
const progressFills = document.querySelectorAll('.progress-bar-fill');
const swipeHint = document.getElementById('swipeHint');
const storyOverlayText = document.getElementById('storyOverlayText');

// Show hint briefly
setTimeout(() => swipeHint.classList.add('show'), 300);
setTimeout(() => swipeHint.classList.remove('show'), 3000);

function loadStory(index) {
    if (index < 0 || index >= stories.length) {
        closeStory();
        return;
    }

    currentIndex = index;
    const story = stories[index];

    // Update image
    storyImage.src = story.image_url;

    // Update text overlay
    if (story.text_content) {
        if (!storyOverlayText) {
            const div = document.createElement('div');
            div.id = 'storyOverlayText';
            div.className = 'story-text ' + story.text_position;
            document.getElementById('storyStage').appendChild(div);
        }
        const textEl = document.getElementById('storyOverlayText');
        if (textEl) {
            textEl.textContent = story.text_content;
            textEl.className = 'story-text ' + story.text_position;
            textEl.style.color = story.text_color;
            textEl.style.fontSize = story.text_size + 'px';
            textEl.style.fontWeight = story.font_style === 'bold' ? '700' : (story.font_style === 'italic' ? '400' : '500');
            textEl.style.fontStyle = story.font_style === 'italic' ? 'italic' : 'normal';
            textEl.style.display = '';
        }
    } else if (storyOverlayText) {
        storyOverlayText.style.display = 'none';
    }

    // Update top bar info
    document.querySelector('.story-top-bar .avatar').src = story.avatar;
    document.querySelector('.story-top-bar .info .name').innerHTML =
        story.name + (story.is_verified ? ' <span class="material-icons-round text-[14px]" style="color:#834ae5;">verified</span>' : '');
    document.querySelector('.story-top-bar .info .time').textContent = story.created_at ? timeAgo(story.created_at) : '';

    viewCount.textContent = story.views_count;

    // Update progress bars
    progressFills.forEach(f => f.classList.remove('done'));
    progressFills.forEach((f, i) => {
        if (i < currentIndex) f.classList.add('done');
    });

    // Mark as viewed via API
    fetch('/stories/' + story.id, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).catch(() => {});

    // Start progress for current
    startProgress();
}

function startProgress() {
    clearInterval(progressInterval);
    progress = 0;
    const activeFill = progressFills[currentIndex];
    if (activeFill) {
        activeFill.classList.remove('done');
        activeFill.style.width = '0%';
    }

    progressInterval = setInterval(() => {
        progress += TICK_MS;
        const pct = Math.min((progress / STORY_DURATION) * 100, 100);
        if (activeFill) activeFill.style.width = pct + '%';

        if (progress >= STORY_DURATION) {
            clearInterval(progressInterval);
            if (activeFill) activeFill.classList.add('done');
            setTimeout(() => nextStory(), 200);
        }
    }, TICK_MS);
}

function nextStory() {
    const activeFill = progressFills[currentIndex];
    if (activeFill) activeFill.classList.add('done');
    loadStory(currentIndex + 1);
}

function prevStory() {
    if (currentIndex > 0) {
        // Reset previous bar
        const prevFill = progressFills[currentIndex - 1];
        if (prevFill) prevFill.classList.remove('done');
        loadStory(currentIndex - 1);
    }
}

function closeStory() {
    clearInterval(progressInterval);
    window.location.href = '/stories';
}

function sendReply() {
    const input = document.getElementById('replyInput');
    const msg = input.value.trim();
    if (!msg) return;

    const story = stories[currentIndex];
    // Redirect to messages with pre-filled recipient
    window.location.href = '/messages/create?to=' + story.username + '&text=' + encodeURIComponent('Replied to your story: ' + msg);
}

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') prevStory();
    if (e.key === 'ArrowRight') nextStory();
    if (e.key === 'Escape') closeStory();
});

// Touch swipe navigation
let touchStartX = 0;
let touchStartY = 0;
const viewer = document.getElementById('storyViewer');
viewer.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
    touchStartY = e.changedTouches[0].screenY;
    clearInterval(progressInterval); // Pause
}, { passive: true });

viewer.addEventListener('touchend', (e) => {
    const diffX = e.changedTouches[0].screenX - touchStartX;
    const diffY = e.changedTouches[0].screenY - touchStartY;

    if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 60) {
        if (diffX > 0) prevStory();
        else nextStory();
    } else {
        // Resume progress
        startProgress();
    }
}, { passive: true });

function timeAgo(dateStr) {
    if (!dateStr) return '';
    const now = new Date();
    const date = new Date(dateStr.replace(/-/g, '/'));
    const diff = Math.floor((now - date) / 1000);
    if (diff < 60) return 'just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
}

// Start
loadStory(0);
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
