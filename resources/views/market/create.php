<?php $activeTab = 'menu'; $title = 'Sell on Market - DTTube'; ?>
<?php $ci = $data['currencyInfo'] ?? ['code' => 'KES', 'symbol' => 'KES', 'rate' => 129.50]; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4">
    <div class="flex items-center gap-3 mb-6">
        <a href="/market" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <h1 class="font-display text-lg font-bold text-white">Sell on Market</h1>
    </div>

    <div class="mb-5">
        <div class="flex gap-2 mb-4">
            <button type="button" onclick="selectType('digital')" id="typeDigital" class="flex-1 py-3 rounded-xl bg-purple-500/20 border border-purple-500/30 text-purple-300 text-sm font-semibold flex items-center justify-center gap-2 hover:bg-purple-500/30 transition-colors">
                <span class="material-icons-round text-lg">download</span>
                Digital Product
            </button>
            <button type="button" onclick="selectType('service')" id="typeService" class="flex-1 py-3 rounded-xl bg-surface-200 text-zinc-400 text-sm font-semibold flex items-center justify-center gap-2 hover:bg-surface-300 transition-colors">
                <span class="material-icons-round text-lg">handyman</span>
                Service
            </button>
        </div>
        <p id="typeHelp" class="text-zinc-500 text-[10px] text-center">Select what you want to sell</p>
    </div>

    <form id="marketForm" class="space-y-4" enctype="multipart/form-data" onsubmit="submitItem(event)">
        <input type="hidden" id="type" name="type" value="digital">

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Title *</label>
            <input type="text" id="title" required placeholder="e.g. Lo-fi Beat Pack, Video Editing Gig, etc." class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Description</label>
            <textarea id="description" rows="4" placeholder="Describe your item or service in detail..." class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm resize-none"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Price *</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-500 text-sm font-medium" id="pricePrefix"><?= $ci['symbol'] ?></span>
                    <input type="number" id="price" required min="1" step="0.01" placeholder="0.00" class="w-full bg-surface-200/80 text-white pl-12 pr-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
                </div>
                <p class="text-zinc-600 text-[9px] mt-1">Auto-detected from your country. <span id="currencyName"><?= $ci['code'] ?></span></p>
                <input type="hidden" id="currency" value="<?= $ci['code'] ?>">
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Category</label>
                <select id="category" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
                    <optgroup label="Digital Products">
                        <option value="Beats">Beats</option>
                        <option value="Presets">Presets</option>
                        <option value="Templates">Templates</option>
                        <option value="Courses">Courses</option>
                        <option value="Ebooks">Ebooks</option>
                        <option value="Fonts">Fonts</option>
                        <option value="Graphics">Graphics</option>
                        <option value="Software">Software</option>
                    </optgroup>
                    <optgroup label="Services">
                        <option value="Editing">Editing</option>
                        <option value="Design">Design</option>
                        <option value="Consulting">Consulting</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Writing">Writing</option>
                        <option value="Music Production">Music Production</option>
                        <option value="Voice Over">Voice Over</option>
                    </optgroup>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div id="deliveryField">
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Delivery Time</label>
                <select id="delivery_time" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
                    <option value="Instant">Instant</option>
                    <option value="1-2 days">1-2 days</option>
                    <option value="3-5 days">3-5 days</option>
                    <option value="5-7 days">5-7 days</option>
                    <option value="1-2 weeks">1-2 weeks</option>
                    <option value="Monthly">Monthly</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Thumbnail Image (Recommended)</label>
            <input type="file" id="thumbnailFile" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full bg-surface-200/80 text-zinc-400 px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:gradient-brand file:text-white file:text-xs file:font-medium file:cursor-pointer"
                   onchange="document.getElementById('thumbnailUrlField').classList.add('hidden')">
            <p class="text-zinc-600 text-[9px] mt-1">Upload image (JPG, PNG, GIF, WebP — max 5MB)</p>
        </div>

        <div id="thumbnailUrlField">
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Or Thumbnail URL</label>
            <input type="url" id="thumbnailUrl" placeholder="https://placehold.co/400x300/..." class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
        </div>

        <div id="fileUploadField">
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">File Upload <span id="fileLabel" class="text-zinc-600">(for digital products)</span></label>
            <input type="file" id="fileUpload" class="w-full bg-surface-200/80 text-zinc-400 px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:gradient-brand file:text-white file:text-xs file:font-medium file:cursor-pointer"
                   onchange="document.getElementById('fileUrlField').classList.add('hidden')">
            <div id="fileUrlField" class="mt-2">
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Or File URL</label>
                <input type="url" id="file_url" placeholder="https://drive.google.com/..." class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
            </div>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Preview URL</label>
            <input type="url" id="preview_url" placeholder="https://youtube.com/watch?v=..." class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
        </div>

        <div id="requirementsField">
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Requirements <span id="reqLabel" class="text-zinc-600">(what you need from buyers)</span></label>
            <textarea id="requirements" rows="3" placeholder="e.g. Provide your video files, brand colors, preferred style..." class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm resize-none"></textarea>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Tags <span class="text-zinc-600">(comma separated)</span></label>
            <input type="text" id="tags" placeholder="e.g. lo-fi, beats, royalty-free, music" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-amber-500/60 focus:outline-none text-sm">
        </div>

        <button type="submit" id="submitBtn" class="w-full py-2.5 rounded-xl gradient-brand text-white text-sm font-bold hover:opacity-90 transition-all flex items-center justify-center gap-2">
            <span class="material-icons-round text-lg">sell</span>
            Publish to Market
        </button>
    </form>
</div>

<script>
let selectedType = 'digital';

function selectType(type) {
    selectedType = type;
    document.getElementById('type').value = type;
    const dBtn = document.getElementById('typeDigital');
    const sBtn = document.getElementById('typeService');
    const help = document.getElementById('typeHelp');

    if (type === 'digital') {
        dBtn.className = 'flex-1 py-3 rounded-xl bg-purple-500/20 border border-purple-500/30 text-purple-300 text-sm font-semibold flex items-center justify-center gap-2';
        sBtn.className = 'flex-1 py-3 rounded-xl bg-surface-200 text-zinc-400 text-sm font-semibold flex items-center justify-center gap-2';
        help.textContent = 'Sell beats, presets, templates, courses, and other digital goods';
        document.getElementById('fileLabel').textContent = '(upload file or provide link)';
        document.getElementById('reqLabel').textContent = '(optional for digital)';
    } else {
        sBtn.className = 'flex-1 py-3 rounded-xl bg-blue-500/20 border border-blue-500/30 text-blue-300 text-sm font-semibold flex items-center justify-center gap-2';
        dBtn.className = 'flex-1 py-3 rounded-xl bg-surface-200 text-zinc-400 text-sm font-semibold flex items-center justify-center gap-2';
        help.textContent = 'Offer editing, design, consulting, and other creator services';
        document.getElementById('fileLabel').textContent = '(optional for services)';
        document.getElementById('reqLabel').textContent = '(what you need from clients to start)';
    }
}

function submitItem(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round animate-spin text-lg">refresh</span> Publishing...';

    const formData = new FormData();
    formData.append('type', selectedType);
    formData.append('title', document.getElementById('title').value.trim());
    formData.append('description', document.getElementById('description').value.trim());
    formData.append('price', document.getElementById('price').value);
    formData.append('category', document.getElementById('category').value);
    formData.append('delivery_time', document.getElementById('delivery_time').value);

    const thumbFile = document.getElementById('thumbnailFile');
    if (thumbFile && thumbFile.files.length > 0) {
        formData.append('thumbnail', thumbFile.files[0]);
    } else {
        formData.append('thumbnail', document.getElementById('thumbnailUrl').value.trim());
    }

    const fileUpload = document.getElementById('fileUpload');
    if (fileUpload && fileUpload.files.length > 0) {
        formData.append('file', fileUpload.files[0]);
    } else {
        formData.append('file_url', document.getElementById('file_url').value.trim());
    }

    formData.append('preview_url', document.getElementById('preview_url').value.trim());
    formData.append('requirements', document.getElementById('requirements').value.trim());

    const tagsInput = document.getElementById('tags').value;
    if (tagsInput) {
        formData.append('tags', tagsInput);
    }

    fetch('/market', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    }).then(r => r.json()).then(d => {
        if (d.item_id) {
            alert(d.message);
            window.location.href = '/market/' + d.item_id;
        } else {
            alert(d.error || 'Error creating item');
            btn.disabled = false;
            btn.innerHTML = '<span class="material-icons-round text-lg">sell</span> Publish to Market';
        }
    }).catch(() => {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-icons-round text-lg">sell</span> Publish to Market';
    });
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
