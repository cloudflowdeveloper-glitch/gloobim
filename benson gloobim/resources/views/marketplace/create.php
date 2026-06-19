<?php $activeTab = 'menu'; $title = 'Sell an Item - DTTube'; ?>
<?php
$ci = $data['currencyInfo'] ?? ['code' => 'KES', 'symbol' => 'KES', 'rate' => 129.50];
$categories = $data['categories'] ?? [];
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: white; }
    .mc-page { max-width: 480px; margin: 0 auto; padding: 0 16px 100px; }
    .mc-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 20px; }
    .mc-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .mc-title { font-size: 20px; font-weight: 700; flex: 1; }
    .mc-field { margin-bottom: 14px; }
    .mc-field label { display: block; font-size: 12px; color: #94A3B8; margin-bottom: 5px; font-weight: 500; }
    .mc-field input, .mc-field textarea, .mc-field select { width: 100%; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 11px 14px; color: white; font-size: 14px; font-family: 'Inter', sans-serif; outline: none; box-sizing: border-box; }
    .mc-field input:focus, .mc-field textarea:focus, .mc-field select:focus { border-color: var(--purple); }
    .mc-field textarea { resize: vertical; min-height: 80px; }
    .mc-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .mc-upload-box { background: var(--bg-surface); border: 2px dashed rgba(255,255,255,0.1); border-radius: 14px; padding: 24px; text-align: center; cursor: pointer; transition: border-color 0.2s; }
    .mc-upload-box:hover { border-color: var(--purple); }
    .mc-upload-box.active { border-color: var(--purple); background: rgba(139,92,246,0.05); }
    .mc-upload-box input[type=file] { display: none; }
    .mc-upload-icon { font-size: 40px; color: #4B5563; margin-bottom: 8px; }
    .mc-upload-text { font-size: 13px; color: #94A3B8; }
    .mc-upload-hint { font-size: 10px; color: #6B7280; margin-top: 4px; }
    .mc-preview { width: 100%; max-height: 200px; border-radius: 12px; object-fit: cover; margin-top: 12px; display: none; }
    .mc-submit-btn { background: linear-gradient(135deg, #8B5CF6, #A78BFA); color: white; border: none; border-radius: 14px; padding: 14px; font-size: 15px; font-weight: 700; width: 100%; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: opacity 0.2s; }
    .mc-submit-btn:disabled { opacity: 0.5; }
    .mc-price-wrap { position: relative; }
    .mc-price-wrap span { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 14px; font-weight: 600; }
    .mc-price-wrap input { padding-left: 48px; }
    .toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; }
</style>

<div class="mc-page">
    <div class="mc-header">
        <button class="mc-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="mc-title">Sell an Item</span>
        <span class="material-icons-round" style="color:var(--purple);">add_business</span>
    </div>

    <form id="listingForm" enctype="multipart/form-data" onsubmit="submitListing(event)">
        <div class="mc-field">
            <label>Title <span style="color:#EF4444;">*</span></label>
            <input type="text" id="title" required placeholder="e.g. iPhone 15 Pro Max 256GB">
        </div>

        <div class="mc-field">
            <label>Description</label>
            <textarea id="description" placeholder="Describe your item in detail..."></textarea>
        </div>

        <div class="mc-row">
            <div class="mc-field">
                <label>Price <span style="color:#EF4444;">*</span></label>
                <div class="mc-price-wrap">
                    <span><?= str_starts_with($ci['code'], 'KE') ? 'KES' : $ci['symbol'] ?></span>
                    <input type="number" id="price" required min="1" step="0.01" placeholder="0.00">
                </div>
                <p style="font-size:10px;color:#6B7280;margin-top:4px;"><?= $ci['code'] ?></p>
                <input type="hidden" id="currency" value="<?= $ci['code'] ?>">
            </div>
            <div class="mc-field">
                <label>Category</label>
                <select id="category">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['name']) ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="Electronics">Electronics</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Home">Home & Garden</option>
                        <option value="Sports">Sports</option>
                        <option value="Books">Books</option>
                        <option value="Other">Other</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="mc-row">
            <div class="mc-field">
                <label>Condition</label>
                <select id="condition">
                    <option value="new">Brand New</option>
                    <option value="like_new">Like New</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
                    <option value="used">Used</option>
                </select>
            </div>
            <div class="mc-field">
                <label>Location</label>
                <input type="text" id="location" placeholder="e.g. Nairobi, Kenya">
            </div>
        </div>

        <div class="mc-field">
            <label>Phone Number</label>
            <input type="tel" id="phone" placeholder="e.g. +254 712 345 678">
        </div>

        <div class="mc-field">
            <label>Product Image <span style="color:#EF4444;">*</span></label>
            <div class="mc-upload-box" id="uploadBox" onclick="document.getElementById('imageFile').click()">
                <span class="material-icons-round mc-upload-icon" id="uploadIcon">add_photo_alternate</span>
                <p class="mc-upload-text" id="uploadText">Tap to upload a photo</p>
                <p class="mc-upload-hint">JPG, PNG, GIF, WebP — max 5MB</p>
                <input type="file" id="imageFile" name="image" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(event)">
            </div>
            <img id="imagePreview" class="mc-preview" alt="Preview">
        </div>

        <button type="submit" id="submitBtn" class="mc-submit-btn">
            <span class="material-icons-round">add_business</span>
            Post Listing
        </button>
    </form>
</div>

<script>
function previewImage(e) {
    var file = e.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(ev) {
        var img = document.getElementById('imagePreview');
        img.src = ev.target.result;
        img.style.display = 'block';
        document.getElementById('uploadBox').classList.add('active');
        document.getElementById('uploadIcon').textContent = 'check_circle';
        document.getElementById('uploadIcon').style.color = '#22C55E';
        document.getElementById('uploadText').textContent = file.name;
    };
    reader.readAsDataURL(file);
}

function submitListing(e) {
    e.preventDefault();
    var btn = document.getElementById('submitBtn');
    var orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round" style="animation:spin 0.8s linear infinite;">sync</span> Posting...';

    var fd = new FormData();
    fd.append('title', document.getElementById('title').value.trim());
    fd.append('description', document.getElementById('description').value.trim());
    fd.append('price', document.getElementById('price').value);
    fd.append('currency', document.getElementById('currency').value);
    fd.append('category', document.getElementById('category').value);
    fd.append('condition', document.getElementById('condition').value);
    fd.append('location', document.getElementById('location').value.trim());
    fd.append('phone', document.getElementById('phone').value.trim());

    var fileInput = document.getElementById('imageFile');
    if (fileInput.files.length > 0) {
        fd.append('image', fileInput.files[0]);
    }

    fetch('/marketplace', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: fd
    }).then(r => r.json()).then(d => {
        if (d.listing_id) {
            showToast('Listing posted!', false);
            setTimeout(function() { window.location.href = '/marketplace/' + d.listing_id; }, 800);
        } else {
            showToast(d.error || 'Error creating listing', true);
            btn.disabled = false;
            btn.innerHTML = orig;
        }
    }).catch(function() {
        showToast('Network error. Try again.', true);
        btn.disabled = false;
        btn.innerHTML = orig;
    });
}

function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function(){ t.remove(); }, 2500);
}
</script>
<style>@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }</style>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
