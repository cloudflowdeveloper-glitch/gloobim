<?php $gift = $data['gift'] ?? []; $title = 'Edit Gift - DTTube Admin'; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4 pb-24">
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/gifts" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <div>
            <h1 class="font-display text-lg font-bold text-white">Edit Gift</h1>
            <p class="text-zinc-500 text-xs">Update gift details</p>
        </div>
    </div>

    <form method="POST" action="/admin/gifts/<?= $gift['id'] ?>" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Gift Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="<?= htmlspecialchars($gift['name'] ?? '') ?>" required class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Description</label>
            <input type="text" name="description" value="<?= htmlspecialchars((string)($gift['description'] ?? '')) ?>" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Material Icon</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($gift['icon'] ?? 'card_giftcard') ?>" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm font-mono">
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Color Class</label>
                <select name="color_class" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
                    <option value="text-red-400" <?= ($gift['color_class'] ?? '') === 'text-red-400' ? 'selected' : '' ?>>Red</option>
                    <option value="text-orange-400" <?= ($gift['color_class'] ?? '') === 'text-orange-400' ? 'selected' : '' ?>>Orange</option>
                    <option value="text-amber-400" <?= ($gift['color_class'] ?? 'text-amber-400') === 'text-amber-400' ? 'selected' : '' ?>>Amber</option>
                    <option value="text-yellow-400" <?= ($gift['color_class'] ?? '') === 'text-yellow-400' ? 'selected' : '' ?>>Yellow</option>
                    <option value="text-pink-400" <?= ($gift['color_class'] ?? '') === 'text-pink-400' ? 'selected' : '' ?>>Pink</option>
                    <option value="text-purple-400" <?= ($gift['color_class'] ?? '') === 'text-purple-400' ? 'selected' : '' ?>>Purple</option>
                    <option value="text-cyan-400" <?= ($gift['color_class'] ?? '') === 'text-cyan-400' ? 'selected' : '' ?>>Cyan</option>
                    <option value="text-emerald-400" <?= ($gift['color_class'] ?? '') === 'text-emerald-400' ? 'selected' : '' ?>>Emerald</option>
                    <option value="text-brand-400" <?= ($gift['color_class'] ?? '') === 'text-brand-400' ? 'selected' : '' ?>>Brand</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Price (USD) <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-sm">$</span>
                    <input type="number" step="0.01" min="0.01" name="price_usd" value="<?= htmlspecialchars((string)($gift['price_usd'] ?? '1.00')) ?>" required class="w-full bg-surface-200/80 text-white pl-8 pr-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
                </div>
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Sort Order</label>
                <input type="number" min="0" name="sort_order" value="<?= (int)($gift['sort_order'] ?? 0) ?>" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
            </div>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Gift Image</label>
            <div class="flex items-center gap-3">
                <?php if (!empty($gift['image_url'])): ?>
                <div class="flex-shrink-0 w-14 h-14 rounded-xl overflow-hidden bg-surface-200 border border-surface-400/30">
                    <img src="<?= $gift['image_url'] ?>" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <p class="text-zinc-400 text-xs truncate"><?= $gift['image_url'] ?></p>
                    <label class="inline-flex items-center gap-1 text-zinc-500 text-[10px] cursor-pointer hover:text-red-400 transition-colors mt-1">
                        <input type="checkbox" name="remove_image" value="1" class="w-3 h-3 rounded bg-surface-200 border-surface-400 text-red-500 focus:ring-red-500">
                        Remove current image
                    </label>
                </div>
                <?php endif; ?>
                <div class="<?= !empty($gift['image_url']) ? '' : 'flex-1' ?>">
                    <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full bg-surface-200/80 text-zinc-400 px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:gradient-brand file:text-white file:text-xs file:font-medium file:cursor-pointer">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?= !empty($gift['is_active']) ? 'checked' : '' ?> class="w-4 h-4 rounded bg-surface-200 border-surface-400 text-brand-600 focus:ring-brand-500">
                <span class="text-zinc-300 text-sm">Active</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_animated" value="1" <?= !empty($gift['is_animated']) ? 'checked' : '' ?> class="w-4 h-4 rounded bg-surface-200 border-surface-400 text-brand-600 focus:ring-brand-500">
                <span class="text-zinc-300 text-sm">Animated</span>
            </label>
        </div>

        <div class="pt-2 flex gap-2">
            <a href="/admin/gifts" class="flex-1 py-3 rounded-xl bg-surface-200 text-zinc-300 font-bold text-sm text-center hover:bg-surface-300 transition-colors">Cancel</a>
            <button type="submit" class="flex-1 gradient-brand py-3 rounded-xl text-white font-bold text-sm hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <span class="material-icons-round text-lg">save</span>
                Save Changes
            </button>
        </div>
    </form>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../../layouts/app.php'; ?>
