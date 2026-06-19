<?php $activeTab = 'menu'; $title = 'Edit Listing - DTTube'; ?>
<?php $listing = $data['listing'] ?? []; ?>
<?php $ci = $data['currencyInfo'] ?? ['code' => 'KES', 'symbol' => 'KES', 'rate' => 129.50]; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4">
    <div class="flex items-center gap-3 mb-6">
        <a href="/marketplace/<?= $listing['id'] ?? '' ?>" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">Edit Listing</h1>
    </div>

    <form id="editForm" class="space-y-4" enctype="multipart/form-data" onsubmit="updateListing(event)">
        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Title *</label>
            <input type="text" id="title" required value="<?= htmlspecialchars($listing['title'] ?? '') ?>" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-emerald-500/60 focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Description</label>
            <textarea id="description" rows="4" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-emerald-500/60 focus:outline-none text-sm resize-none"><?= htmlspecialchars($listing['description'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Price *</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-500 text-sm font-medium"><?= $ci['symbol'] ?></span>
                    <input type="number" id="price" required min="1" step="0.01" value="<?= htmlspecialchars((string)($listing['price'] ?? 0)) ?>" class="w-full bg-surface-200/80 text-white pl-12 pr-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-emerald-500/60 focus:outline-none text-sm">
                </div>
                <p class="text-zinc-600 text-[9px] mt-1">Currency: <?= $ci['code'] ?> (auto-detected from your country)</p>
                <input type="hidden" id="currency" value="<?= $ci['code'] ?>">
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Category</label>
                <select id="category" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-emerald-500/60 focus:outline-none text-sm">
                    <?php foreach (['Electronics', 'Fashion', 'Home', 'Collectibles', 'Sports', 'Music', 'Art', 'Books', 'Other'] as $cat): ?>
                    <option value="<?= $cat ?>" <?= ($listing['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Condition</label>
                <select id="condition" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-emerald-500/60 focus:outline-none text-sm">
                    <?php $labels = ['new' => 'Brand New', 'like_new' => 'Like New', 'good' => 'Good', 'fair' => 'Fair', 'used' => 'Used']; ?>
                    <?php foreach ($labels as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($listing['condition'] ?? 'good') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Image</label>
            <div class="flex items-center gap-3">
                <?php if (!empty($listing['image_url']) && !str_contains($listing['image_url'] ?? '', 'placehold.co')): ?>
                <div class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden bg-surface-200 border border-surface-400/30">
                    <img src="<?= $listing['image_url'] ?>" alt="" class="w-full h-full object-cover">
                </div>
                <?php endif; ?>
                <div class="flex-1">
                    <input type="file" id="imageFile" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full bg-surface-200/80 text-zinc-400 px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-emerald-500/60 focus:outline-none text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:gradient-brand file:text-white file:text-xs file:font-medium file:cursor-pointer">
                </div>
            </div>
            <?php if (!empty($listing['image_url'])): ?>
            <label class="inline-flex items-center gap-1 mt-2 cursor-pointer">
                <input type="checkbox" name="remove_image" value="1" class="w-3 h-3 rounded bg-surface-200 border-surface-400 text-red-500">
                <span class="text-zinc-500 text-xs">Remove current image</span>
            </label>
            <?php endif; ?>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Location</label>
            <input type="text" id="location" value="<?= htmlspecialchars($listing['location'] ?? '') ?>" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-emerald-500/60 focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Phone Number</label>
            <input type="tel" id="phone" value="<?= htmlspecialchars($listing['phone'] ?? '') ?>" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-emerald-500/60 focus:outline-none text-sm">
        </div>

        <button type="submit" id="submitBtn" class="w-full py-2.5 rounded-xl gradient-brand text-white text-sm font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2">
            <span class="material-icons-round text-lg">save</span>
            Save Changes
        </button>
    </form>
</div>

<script>
function updateListing(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round animate-spin text-lg">refresh</span> Saving...';

    const formData = new FormData();
    formData.append('title', document.getElementById('title').value.trim());
    formData.append('description', document.getElementById('description').value.trim());
    formData.append('price', document.getElementById('price').value);
    formData.append('category', document.getElementById('category').value);
    formData.append('condition', document.getElementById('condition').value);
    formData.append('location', document.getElementById('location').value.trim());
    formData.append('phone', document.getElementById('phone').value.trim());

    const fileInput = document.getElementById('imageFile');
    if (fileInput && fileInput.files.length > 0) {
        formData.append('image', fileInput.files[0]);
    }

    fetch('/marketplace/<?= $listing['id'] ?>/update', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    }).then(r => r.json()).then(d => {
        if (d.listing_id) {
            alert('Listing updated!');
            window.location.href = '/marketplace/' + d.listing_id;
        } else {
            alert(d.error || 'Error updating listing');
            btn.disabled = false;
            btn.innerHTML = '<span class="material-icons-round text-lg">save</span> Save Changes';
        }
    }).catch(() => {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-icons-round text-lg">save</span> Save Changes';
    });
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
