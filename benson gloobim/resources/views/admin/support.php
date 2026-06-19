<?php
$title = 'Support Settings — Admin';
$categories = $data['categories'] ?? [];
$contacts = $data['contacts'] ?? [];
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; --green: #22C55E; --red: #EF4444; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .as-page { max-width: 600px; margin: 0 auto; padding: 0 16px 100px; }
    .as-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 16px; }
    .as-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .as-title { font-size: 20px; font-weight: 700; flex: 1; }
    .as-tabs { display: flex; gap: 4px; margin-bottom: 16px; background: var(--bg-surface); border-radius: 12px; padding: 4px; }
    .as-tab { flex: 1; text-align: center; padding: 8px; border-radius: 10px; font-size: 12px; font-weight: 600; cursor: pointer; color: #94A3B8; border: none; background: none; transition: all 0.2s; }
    .as-tab.active { background: var(--purple); color: white; }
    .as-tab-content { display: none; }
    .as-tab-content.active { display: block; }
    .as-card { background: var(--bg-card); border-radius: 14px; padding: 14px; margin-bottom: 8px; border: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 12px; }
    .as-card-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(139,92,246,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .as-card-icon span { font-size: 20px; color: var(--purple); }
    .as-card-info { flex: 1; min-width: 0; }
    .as-card-name { font-size: 14px; font-weight: 600; }
    .as-card-meta { font-size: 11px; color: #94A3B8; }
    .as-toggle { width: 48px; height: 26px; border-radius: 13px; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; background: rgba(255,255,255,0.1); }
    .as-toggle.on { background: var(--green); }
    .as-toggle::after { content: ''; width: 20px; height: 20px; background: white; border-radius: 50%; position: absolute; top: 3px; left: 3px; transition: transform 0.2s; }
    .as-toggle.on::after { transform: translateX(22px); }
    .as-delete { background: none; border: none; color: #EF4444; cursor: pointer; padding: 4px; font-size: 18px; flex-shrink: 0; }
    .as-delete:hover { opacity: 0.7; }
    .as-add-section { background: var(--bg-card); border-radius: 14px; padding: 16px; margin-top: 12px; border: 1px solid rgba(255,255,255,0.06); }
    .as-add-section h4 { font-size: 13px; font-weight: 700; margin: 0 0 12px; }
    .as-field-row { display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap; }
    .as-field-row input, .as-field-row select { background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 8px 10px; color: white; font-size: 13px; outline: none; font-family: 'Inter', sans-serif; }
    .as-field-row input:focus { border-color: var(--purple); }
    .as-field-row input { flex: 1; min-width: 100px; }
    .as-field-row select { width: auto; }
    .as-add-btn { background: var(--purple); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 12px; font-weight: 600; cursor: pointer; }
    .toast { position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
</style>

<div class="as-page">
    <div class="as-header">
        <button class="as-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="as-title">Support Settings</span>
    </div>

    <!-- Tabs -->
    <div class="as-tabs">
        <button class="as-tab active" onclick="switchTab('categories')">Categories</button>
        <button class="as-tab" onclick="switchTab('contacts')">Contacts</button>
    </div>

    <!-- ===== CATEGORIES TAB ===== -->
    <div class="as-tab-content active" id="tab-categories">
        <?php foreach ($categories as $cat): ?>
        <div class="as-card" id="cat-<?= $cat['id'] ?>">
            <div class="as-card-icon"><span class="material-icons-round"><?= htmlspecialchars($cat['icon'] ?? 'help') ?></span></div>
            <div class="as-card-info">
                <div class="as-card-name"><?= htmlspecialchars($cat['name']) ?></div>
                <div class="as-card-meta"><?= $cat['slug'] ?></div>
            </div>
            <div class="as-toggle <?= $cat['is_active'] ? 'on' : '' ?>" onclick="toggleCategory(<?= $cat['id'] ?>)"></div>
            <button class="as-delete" onclick="deleteCategory(<?= $cat['id'] ?>)"><span class="material-icons-round">close</span></button>
        </div>
        <?php endforeach; ?>

        <div class="as-add-section">
            <h4>Add Category</h4>
            <div class="as-field-row">
                <input type="text" id="catName" placeholder="Category name" style="max-width:140px;">
                <input type="text" id="catSlug" placeholder="slug-name" style="max-width:120px;">
                <input type="text" id="catIcon" placeholder="Icon (material)" value="help" style="max-width:100px;">
                <button class="as-add-btn" onclick="addCategory()">Add</button>
            </div>
        </div>
    </div>

    <!-- ===== CONTACTS TAB ===== -->
    <div class="as-tab-content" id="tab-contacts">
        <?php foreach ($contacts as $c): ?>
        <div class="as-card" id="contact-<?= $c['id'] ?>">
            <div class="as-card-icon"><span class="material-icons-round"><?= htmlspecialchars($c['icon'] ?? 'info') ?></span></div>
            <div class="as-card-info">
                <div class="as-card-name"><?= htmlspecialchars($c['label']) ?></div>
                <div class="as-card-meta"><?= htmlspecialchars($c['value']) ?> · <?= $c['type'] ?></div>
            </div>
            <div class="as-toggle <?= $c['is_active'] ? 'on' : '' ?>" onclick="toggleContact(<?= $c['id'] ?>)"></div>
            <button class="as-delete" onclick="deleteContact(<?= $c['id'] ?>)"><span class="material-icons-round">close</span></button>
        </div>
        <?php endforeach; ?>

        <div class="as-add-section">
            <h4>Add Contact</h4>
            <div class="as-field-row">
                <select id="contactType">
                    <option value="email">Email</option>
                    <option value="phone">Phone</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="address">Address</option>
                    <option value="social">Social</option>
                </select>
                <input type="text" id="contactLabel" placeholder="Label" style="max-width:130px;">
                <input type="text" id="contactValue" placeholder="Value" style="max-width:150px;">
                <input type="text" id="contactIcon" placeholder="Icon" value="info" style="max-width:80px;">
                <button class="as-add-btn" onclick="addContact()">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.querySelectorAll('.as-tab').forEach(function(t) { t.classList.remove('active'); });
    document.querySelectorAll('.as-tab-content').forEach(function(c) { c.classList.remove('active'); });
    event.target.classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

// Categories
function addCategory() {
    fetch('/admin/support/category', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ name: document.getElementById('catName').value, slug: document.getElementById('catSlug').value, icon: document.getElementById('catIcon').value })
    }).then(r => r.json()).then(function(d) { if (d.success) location.reload(); else showToast(d.error, true); });
}
function toggleCategory(id) {
    fetch('/admin/support/category/' + id + '/toggle', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(function(d) { location.reload(); });
}
function deleteCategory(id) {
    if (!confirm('Delete this category?')) return;
    fetch('/admin/support/category/' + id, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(function() { location.reload(); });
}

// Contacts
function addContact() {
    fetch('/admin/support/contact', {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ type: document.getElementById('contactType').value, label: document.getElementById('contactLabel').value, value: document.getElementById('contactValue').value, icon: document.getElementById('contactIcon').value })
    }).then(r => r.json()).then(function(d) { if (d.success) location.reload(); else showToast(d.error, true); });
}
function toggleContact(id) {
    fetch('/admin/support/contact/' + id + '/toggle', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json()).then(function(d) { location.reload(); });
}
function deleteContact(id) {
    if (!confirm('Delete this contact?')) return;
    fetch('/admin/support/contact/' + id, { method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function() { location.reload(); });
}

function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function(){ t.remove(); }, 2000);
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
