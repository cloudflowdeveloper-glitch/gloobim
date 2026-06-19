<?php $activeTab = 'menu'; $title = 'Edit Item - DTTube Market'; ?>
<?php $item = $data['item'] ?? []; ?>
<?php $ci = $data['currencyInfo'] ?? ['code' => 'KES', 'symbol' => 'KES', 'rate' => 129.50]; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4">
    <div class="flex items-center gap-3 mb-6">
        <a href="/market/<?= $item['id'] ?? '' ?>" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">Edit Item</h1>
    </div>

    <form id="editForm" class="space-y-4" enctype="multipart/form-data" onsubmit="updateItem(event)">
        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Title *</label>
            <input type="text" id="title" required value="<?= htmlspecialchars($item['title'] ?? '') ?>" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Description</label>
            <textarea id="description" rows="4" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm resize-none"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Price *</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-500 text-sm font-medium"><?= $ci['symbol'] ?></span>
                    <input type="number" id="price" required min="1" step="0.01" value="<?= htmlspecialchars((string)($item['price'] ?? 0)) ?>" class="w-full bg-surface-200/80 text-white pl-12 pr-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
                </div>
                <p class="text-zinc-600 text-[9px] mt-1">Currency: <?= $ci['code'] ?> (auto-detected)</p>
                <input type="hidden" id="currency" value="<?= $ci['code'] ?>">
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Category</label>
                <select id="category" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
                    <optgroup label="Digital Products">
                        <?php foreach (['Beats', 'Presets', 'Templates', 'Courses', 'Ebooks', 'Fonts', 'Graphics', 'Software'] as $cat): ?>
                        <option value="<?= $cat ?>" <?= ($item['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                    <optgroup label="Services">
                        <?php foreach (['Editing', 'Design', 'Consulting', 'Marketing', 'Writing', 'Music Production', 'Voice Over'] as $cat): ?>
                        <option value="<?= $cat ?>" <?= ($item['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                    <option value="Other" <?= ($item['category'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Delivery Time</label>
                <select id="delivery_time" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
                    <?php foreach (['Instant', '1-2 days', '3-5 days', '5-7 days', '1-2 weeks', 'Monthly'] as $dt): ?>
                    <option value="<?= $dt ?>" <?= ($item['delivery_time'] ?? '') === $dt ? 'selected' : '' ?>><?= $dt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Thumbnail</label>
            <div class="flex items-center gap-3">
                <?php if (!empty($item['thumbnail']) && !str_contains($item['thumbnail'] ?? '', 'placehold.co')): ?>
                <div class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden bg-surface-200 border border-surface-400/30">
                    <img src="<?= $item['thumbnail'] ?>" alt="" class="w-full h-full object-cover">
                </div>
                <?php endif; ?>
                <div class="flex-1">
                    <input type="file" id="thumbnailFile" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full bg-surface-200/80 text-zinc-400 px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:gradient-brand file:text-white file:text-xs file:font-medium file:cursor-pointer">
                </div>
            </div>
            <?php if (!empty($item['thumbnail'])): ?>
            <label class="inline-flex items-center gap-1 mt-2 cursor-pointer">
                <input type="checkbox" name="remove_thumbnail" value="1" class="w-3 h-3 rounded bg-surface-200 border-surface-400 text-red-500">
                <span class="text-zinc-500 text-xs">Remove current thumbnail</span>
            </label>
            <?php endif; ?>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">File Upload</label>
            <?php if (!empty($item['file_url'])): ?>
            <p class="text-zinc-500 text-xs mb-1.5">Current: <?= htmlspecialchars(basename($item['file_url'])) ?></p>
            <?php endif; ?>
            <input type="file" id="fileUpload" class="w-full bg-surface-200/80 text-zinc-400 px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:gradient-brand file:text-white file:text-xs file:font-medium file:cursor-pointer">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Preview URL</label>
            <input type="url" id="preview_url" value="<?= htmlspecialchars($item['preview_url'] ?? '') ?>" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Requirements</label>
            <textarea id="requirements" rows="3" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm resize-none"><?= htmlspecialchars($item['requirements'] ?? '') ?></textarea>
        </div>

        <button type="submit" id="submitBtn" class="w-full py-2.5 rounded-xl gradient-brand text-white text-sm font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2">
            <span class="material-icons-round text-lg">save</span>
            Save Changes
        </button>
    </form>
</div>

<script>
function updateItem(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round animate-spin text-lg">refresh</span> Saving...';

    const formData = new FormData();
    formData.append('title', document.getElementById('title').value.trim());
    formData.append('description', document.getElementById('description').value.trim());
    formData.append('price', document.getElementById('price').value);
    formData.append('category', document.getElementById('category').value);
    formData.append('delivery_time', document.getElementById('delivery_time').value);

    const thumbFile = document.getElementById('thumbnailFile');
    if (thumbFile && thumbFile.files.length > 0) {
        formData.append('thumbnail', thumbFile.files[0]);
    }

    const fileUpload = document.getElementById('fileUpload');
    if (fileUpload && fileUpload.files.length > 0) {
        formData.append('file', fileUpload.files[0]);
    }

    formData.append('preview_url', document.getElementById('preview_url').value.trim());
    formData.append('requirements', document.getElementById('requirements').value.trim());

    fetch('/market/<?= $item['id'] ?>/update', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    }).then(r => r.json()).then(d => {
        if (d.item_id) {
            alert('Item updated!');
            window.location.href = '/market/' + d.item_id;
        } else {
            alert(d.error || 'Error updating item');
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
