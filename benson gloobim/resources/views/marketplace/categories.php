<?php
$title = 'Categories — Marketplace';
$activeTab = 'marketplace';
$hideTopNav = true;
$user = \Core\Auth::user();

$physicalCategories = $data['physicalCategories'] ?? [];
$digitalCategories = $data['digitalCategories'] ?? [];
$creatorCategories = $data['creatorCategories'] ?? [];
$cartCount = $data['cartCount'] ?? 0;
?>
<?php ob_start(); ?>
<style>
    :root {
        --bg-deep: #0B1120;
        --bg-card: #151D2E;
        --bg-surface: #1E293B;
        --text-primary: #FFFFFF;
        --text-secondary: #94A3B8;
        --text-muted: #64748B;
        --purple: #8B5CF6;
        --purple-light: #A78BFA;
        --purple-bg: rgba(139, 92, 246, 0.15);
        --blue: #3B82F6;
        --blue-light: #60A5FA;
        --blue-bg: rgba(59, 130, 246, 0.15);
        --green: #22C55E;
        --green-light: #4ADE80;
        --green-bg: rgba(34, 197, 94, 0.15);
        --red: #EF4444;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        background: var(--bg-deep);
        font-family: 'Inter', -apple-system, sans-serif;
        color: var(--text-primary);
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
    }

    .categories-page {
        max-width: 480px;
        margin: 0 auto;
        padding: 0 16px 100px;
    }

    /* Header */
    .cat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 48px 0 20px;
        position: sticky;
        top: 0;
        background: var(--bg-deep);
        z-index: 50;
    }
    .cat-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .cat-back-btn {
        width: 40px; height: 40px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 12px;
        background: rgba(255,255,255,0.05);
        color: var(--text-secondary);
        font-size: 22px;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    .cat-back-btn:active { background: rgba(255,255,255,0.1); transform: scale(0.95); }
    .cat-brand {
        display: flex; align-items: center; gap: 10px;
    }
    .cat-brand-icon {
        width: 32px; height: 32px;
        background: var(--purple); border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
    }
    .cat-brand-icon .dots { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; }
    .cat-brand-icon .dots span {
        width: 6px; height: 6px; background: white; border-radius: 2px;
    }
    .cat-brand-text { font-size: 20px; font-weight: 700; letter-spacing: -0.3px; }
    .cat-header-right { display: flex; align-items: center; gap: 8px; }
    .cat-icon-btn {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,0.05);
        color: var(--text-secondary);
        font-size: 22px; cursor: pointer; border: none;
        position: relative; transition: all 0.2s;
    }
    .cat-icon-btn:active { background: rgba(255,255,255,0.1); transform: scale(0.95); }
    .cat-icon-btn .badge-dot {
        position: absolute; top: 6px; right: 6px;
        width: 8px; height: 8px; background: var(--red); border-radius: 50%;
        border: 2px solid var(--bg-deep);
    }
    .cat-icon-btn .badge-count {
        position: absolute; top: -4px; right: -6px;
        min-width: 20px; height: 20px; border-radius: 10px;
        background: var(--purple); color: white; font-size: 11px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        padding: 0 5px; border: 2px solid var(--bg-deep);
    }

    /* Search */
    .cat-search {
        background: var(--bg-surface);
        border-radius: 14px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
    }
    .cat-search .material-icons-round { color: var(--text-muted); font-size: 22px; }
    .cat-search input {
        flex: 1; background: transparent; border: none; outline: none;
        color: var(--text-primary); font-size: 15px; font-weight: 400;
    }
    .cat-search input::placeholder { color: var(--text-secondary); }

    /* Section Header */
    .cat-section-header {
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 16px;
    }
    .cat-section-accent {
        width: 3px; height: 24px; background: var(--purple); border-radius: 2px;
    }
    .cat-section-accent.blue { background: var(--blue); }
    .cat-section-accent.green { background: var(--green); }
    .cat-section-title { font-size: 17px; font-weight: 600; }

    /* Category Grid */
    .cat-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 28px;
    }
    .cat-card {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 16px;
        cursor: pointer;
        position: relative;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        text-decoration: none;
        display: block;
    }
    .cat-card:active { transform: scale(0.97); background: #1a2338; }
    .cat-card.featured { border-color: var(--blue); }
    .cat-card .card-badge {
        position: absolute; top: 10px; right: 10px;
        background: var(--blue); color: white; font-size: 10px; font-weight: 700;
        padding: 3px 8px; border-radius: 8px; text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .cat-card-icon {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; color: white; margin-bottom: 12px;
    }
    .cat-card-icon.purple { background: linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%); }
    .cat-card-icon.blue { background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%); }
    .cat-card-icon.green { background: linear-gradient(135deg, #16A34A 0%, #15803D 100%); }
    .cat-card-title { font-size: 15px; font-weight: 600; margin-bottom: 4px; }
    .cat-card-subtitle {
        font-size: 12px; color: var(--text-secondary); line-height: 1.45;
        margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .cat-card-footer { display: flex; align-items: center; justify-content: space-between; }
    .cat-card-count {
        font-size: 11px; font-weight: 600; padding: 4px 10px;
        border-radius: 20px;
    }
    .cat-card-count.purple { background: var(--purple-bg); color: var(--purple-light); }
    .cat-card-count.blue { background: var(--blue-bg); color: var(--blue-light); }
    .cat-card-count.green { background: var(--green-bg); color: var(--green-light); }
    .cat-card-chevron { color: var(--text-muted); font-size: 20px; }

    /* Full-width cards */
    .cat-card-full {
        background: var(--bg-card); border-radius: 16px;
        padding: 16px 18px; cursor: pointer;
        display: flex; align-items: center; gap: 14px;
        text-decoration: none; position: relative;
        transition: all 0.25s; margin-bottom: 12px;
        border: 2px solid transparent;
    }
    .cat-card-full:active { transform: scale(0.98); }
    .cat-card-full .cat-card-icon { margin-bottom: 0; flex-shrink: 0; }
    .cat-card-full .card-content { flex: 1; }
    .cat-card-full .cat-card-title { margin-bottom: 2px; }
    .cat-card-full .cat-card-subtitle { margin-bottom: 0; }
    .cat-card-full .cat-card-chevron { flex-shrink: 0; }

    /* Bottom CTA */
    .cat-cta {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, rgba(59, 130, 246, 0.08) 100%);
        border: 1px solid rgba(139, 92, 246, 0.2);
        border-radius: 20px; padding: 20px;
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 24px;
    }
    .cat-cta-icon {
        width: 48px; height: 48px; border-radius: 50%;
        background: var(--purple);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .cat-cta-icon .material-icons-round { color: white; font-size: 24px; }
    .cat-cta-content { flex: 1; }
    .cat-cta-title { font-size: 15px; font-weight: 700; margin-bottom: 2px; }
    .cat-cta-subtitle { font-size: 12px; color: var(--text-secondary); }
    .cat-cta-btn {
        background: var(--purple); color: white;
        font-size: 13px; font-weight: 600; padding: 10px 18px;
        border-radius: 50px; white-space: nowrap;
        display: flex; align-items: center; gap: 6px;
        cursor: pointer; border: none;
        transition: all 0.2s;
    }
    .cat-cta-btn:active { background: #7C3AED; transform: scale(0.96); }
    .cat-cta-btn .material-icons-round { font-size: 18px; }

    /* Bottom Navigation */
    .cat-bottom-nav {
        position: fixed; bottom: 0; left: 50%; transform: translateX(-50%);
        width: 100%; max-width: 480px;
        background: rgba(11, 17, 32, 0.96);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border-top: 1px solid rgba(255,255,255,0.06);
        padding: 8px 16px 12px;
        display: flex; justify-content: space-around; align-items: flex-start;
        z-index: 100;
    }
    .cat-nav-item {
        display: flex; flex-direction: column; align-items: center;
        gap: 3px; text-decoration: none; padding: 6px 12px;
        min-width: 52px; cursor: pointer;
    }
    .cat-nav-item .nav-icon { font-size: 24px; color: #6B7280; }
    .cat-nav-item .nav-label { font-size: 10px; color: #6B7280; font-weight: 500; }
    .cat-nav-item.active .nav-icon { color: var(--purple-light); }
    .cat-nav-item.active .nav-label { color: var(--purple-light); }
    .cat-nav-center {
        width: 48px; height: 48px; border-radius: 50%;
        background: linear-gradient(180deg, #FDFDFD 0%, #FCE3FF 15%, #641FF4 60%, #572C97 100%);
        display: flex; align-items: center; justify-content: center;
        margin-top: -20px; box-shadow: 0 4px 20px rgba(101,37,248,0.5);
    }
    .cat-nav-center .material-icons-round { color: white; font-size: 26px; }

    /* Pulse animation for notification dot */
    @keyframes pulse { 0%,100% { opacity:1 } 50% { opacity:0.5 } }
    .pulse { animation: pulse 2s infinite; }
</style>

<div class="categories-page">

    <!-- Header -->
    <div class="cat-header">
        <div class="cat-header-left">
            <button class="cat-back-btn" onclick="history.back()">
                <span class="material-icons-round">arrow_back</span>
            </button>
            <div class="cat-brand">
                <div class="cat-brand-icon">
                    <div class="dots">
                        <span></span><span></span>
                        <span></span><span></span>
                    </div>
                </div>
                <span class="cat-brand-text">Categories</span>
            </div>
        </div>
        <div class="cat-header-right">
            <button class="cat-icon-btn" onclick="location.href='/notifications'">
                <span class="material-icons-round">notifications</span>
                <span class="badge-dot pulse"></span>
            </button>
            <?php if ($cartCount > 0): ?>
            <button class="cat-icon-btn" onclick="location.href='/marketplace'">
                <span class="material-icons-round">shopping_cart</span>
                <span class="badge-count"><?= $cartCount ?></span>
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Search -->
    <div class="cat-search">
        <span class="material-icons-round">search</span>
        <input type="text" placeholder="Search categories..." id="categorySearch">
    </div>

    <!-- Browse by Category -->
    <div class="cat-section-header">
        <div class="cat-section-accent"></div>
        <h2 class="cat-section-title">Browse by Category</h2>
    </div>

    <div class="cat-grid" id="physicalGrid">
        <?php foreach ($physicalCategories as $cat): ?>
        <a href="/marketplace?category=<?= urlencode($cat['name']) ?>" class="cat-card <?= ($cat['badge'] ?? '') === 'New' ? 'featured' : '' ?>">
            <?php if (isset($cat['badge'])): ?>
            <span class="card-badge"><?= $cat['badge'] ?></span>
            <?php endif; ?>
            <div class="cat-card-icon <?= $cat['color'] ?>">
                <span class="material-icons-round"><?= $cat['icon'] ?></span>
            </div>
            <div class="cat-card-title"><?= $cat['name'] ?></div>
            <div class="cat-card-subtitle"><?= $cat['subtitle'] ?></div>
            <div class="cat-card-footer">
                <span class="cat-card-count <?= $cat['color'] ?>"><?= $cat['count'] ?> items</span>
                <span class="cat-card-chevron material-icons-round">chevron_right</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Digital Products -->
    <div class="cat-section-header">
        <div class="cat-section-accent blue"></div>
        <h2 class="cat-section-title">Digital Products</h2>
    </div>

    <div class="cat-grid" id="digitalGrid">
        <?php foreach ($digitalCategories as $cat): ?>
        <a href="/market?category=<?= urlencode($cat['name']) ?>" class="cat-card">
            <div class="cat-card-icon <?= $cat['color'] ?>">
                <span class="material-icons-round"><?= $cat['icon'] ?></span>
            </div>
            <div class="cat-card-title"><?= $cat['name'] ?></div>
            <div class="cat-card-subtitle"><?= $cat['subtitle'] ?></div>
            <div class="cat-card-footer">
                <span class="cat-card-count <?= $cat['color'] ?>"><?= $cat['count'] ?> items</span>
                <span class="cat-card-chevron material-icons-round">chevron_right</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Creator & Brand -->
    <div class="cat-section-header">
        <div class="cat-section-accent green"></div>
        <h2 class="cat-section-title">Creator &amp; Brand</h2>
    </div>

    <div class="cat-grid" id="creatorGrid">
        <?php foreach ($creatorCategories as $cat): ?>
        <a href="/market?category=<?= urlencode($cat['name']) ?>" class="cat-card">
            <div class="cat-card-icon <?= $cat['color'] ?>">
                <span class="material-icons-round"><?= $cat['icon'] ?></span>
            </div>
            <div class="cat-card-title"><?= $cat['name'] ?></div>
            <div class="cat-card-subtitle"><?= $cat['subtitle'] ?></div>
            <div class="cat-card-footer">
                <span class="cat-card-count <?= $cat['color'] ?>"><?= $cat['count'] ?> items</span>
                <span class="cat-card-chevron material-icons-round">chevron_right</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Bottom CTA -->
    <div class="cat-cta">
        <div class="cat-cta-icon">
            <span class="material-icons-round">crown</span>
        </div>
        <div class="cat-cta-content">
            <div class="cat-cta-title">List Your Product or Service</div>
            <div class="cat-cta-subtitle">Reach millions of users on Globlim Marketplace</div>
        </div>
        <button class="cat-cta-btn" onclick="location.href='/marketplace/create'">
            Get Started
            <span class="material-icons-round">arrow_forward</span>
        </button>
    </div>

</div>

<!-- Bottom Navigation -->
<nav class="cat-bottom-nav">
    <a href="/feed" class="cat-nav-item">
        <span class="nav-icon material-icons-round">home</span>
        <span class="nav-label">Home</span>
    </a>
    <a href="/marketplace" class="cat-nav-item">
        <span class="nav-icon material-icons-round">storefront</span>
        <span class="nav-label">Market</span>
    </a>
    <div class="cat-nav-center" onclick="location.href='/marketplace/create'">
        <span class="material-icons-round">add</span>
    </div>
    <a href="/marketplace/categories" class="cat-nav-item active">
        <span class="nav-icon material-icons-round">category</span>
        <span class="nav-label">Categories</span>
    </a>
    <a href="/profile" class="cat-nav-item">
        <span class="nav-icon material-icons-round">person</span>
        <span class="nav-label">Profile</span>
    </a>
</nav>

<!-- Search Filter Script -->
<script>
document.getElementById('categorySearch').addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    document.querySelectorAll('.cat-card, .cat-card-full').forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(query) ? '' : 'none';
    });
});
</script>

</body>
</html>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
