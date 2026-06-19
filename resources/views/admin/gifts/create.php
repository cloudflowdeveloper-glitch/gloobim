<?php $error = $data['error'] ?? ''; $old = $data['old'] ?? []; $title = 'Create Gift - DTTube Admin'; ?>
<?php ob_start(); ?>
<div class="max-w-lg mx-auto px-3 py-4 pb-24">
    <div class="flex items-center gap-3 mb-6">
        <a href="/admin/gifts" class="w-9 h-9 rounded-full bg-surface-200 flex items-center justify-center hover:bg-surface-300 transition-colors">
            <span class="material-icons-round text-zinc-400 text-xl">chevron_left</span>
        </a>
        <div>
            <h1 class="font-display text-lg font-bold text-white">Create Gift</h1>
            <p class="text-zinc-500 text-xs">Add a new gift for the livestream</p>
        </div>
    </div>

    <?php if ($error): ?>
    <div class="mb-4 p-3 rounded-xl bg-red-500/10 border border-red-500/30 flex items-center gap-2">
        <span class="material-icons-round text-red-400 text-lg">error</span>
        <span class="text-red-300 text-sm"><?= $error ?></span>
    </div>
    <?php endif; ?>

    <form method="POST" action="/admin/gifts" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Gift Name <span class="text-red-400">*</span></label>
            <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required placeholder="e.g. Heart, Fire, Crown" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm placeholder:text-zinc-600">
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Description</label>
            <input type="text" name="description" value="<?= htmlspecialchars($old['description'] ?? '') ?>" placeholder="Short description (optional)" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm placeholder:text-zinc-600">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Material Icon</label>
                <input type="text" name="icon" value="<?= htmlspecialchars($old['icon'] ?? 'card_giftcard') ?>" placeholder="icon_name" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm placeholder:text-zinc-600 font-mono">
                <p class="text-zinc-600 text-[9px] mt-1">Material Icons Round name</p>
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Color Class</label>
                <select name="color_class" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
                    <option value="text-red-400" <?= ($old['color_class'] ?? '') === 'text-red-400' ? 'selected' : '' ?>>Red</option>
                    <option value="text-orange-400" <?= ($old['color_class'] ?? '') === 'text-orange-400' ? 'selected' : '' ?>>Orange</option>
                    <option value="text-amber-400" <?= ($old['color_class'] ?? 'text-amber-400') === 'text-amber-400' ? 'selected' : '' ?>>Amber</option>
                    <option value="text-yellow-400" <?= ($old['color_class'] ?? '') === 'text-yellow-400' ? 'selected' : '' ?>>Yellow</option>
                    <option value="text-pink-400" <?= ($old['color_class'] ?? '') === 'text-pink-400' ? 'selected' : '' ?>>Pink</option>
                    <option value="text-purple-400" <?= ($old['color_class'] ?? '') === 'text-purple-400' ? 'selected' : '' ?>>Purple</option>
                    <option value="text-cyan-400" <?= ($old['color_class'] ?? '') === 'text-cyan-400' ? 'selected' : '' ?>>Cyan</option>
                    <option value="text-emerald-400" <?= ($old['color_class'] ?? '') === 'text-emerald-400' ? 'selected' : '' ?>>Emerald</option>
                    <option value="text-brand-400" <?= ($old['color_class'] ?? '') === 'text-brand-400' ? 'selected' : '' ?>>Brand</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Price (USD) <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-sm">$</span>
                    <input type="number" step="0.01" min="0.01" name="price_usd" value="<?= htmlspecialchars((string)($old['price_usd'] ?? '1.00')) ?>" required class="w-full bg-surface-200/80 text-white pl-8 pr-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
                </div>
            </div>
            <div>
                <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Sort Order</label>
                <input type="number" min="0" name="sort_order" value="<?= (int)($old['sort_order'] ?? 0) ?>" class="w-full bg-surface-200/80 text-white px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm">
            </div>
        </div>

        <div>
            <label class="block text-zinc-400 text-[11px] font-medium mb-1.5">Gift Image (Optional)</label>
            <div class="relative">
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full bg-surface-200/80 text-zinc-400 px-3.5 py-2.5 rounded-xl border border-surface-400/30 focus:border-brand-500/60 focus:outline-none text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:gradient-brand file:text-white file:text-xs file:font-medium file:cursor-pointer">
            </div>
            <p class="text-zinc-600 text-[9px] mt-1">Recommended: 64x64 or 128x128. JPG, PNG, GIF, WEBP (max 2MB)</p>
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" <?= !isset($old['is_active']) || !empty($old['is_active']) ? 'checked' : '' ?> class="w-4 h-4 rounded bg-surface-200 border-surface-400 text-brand-600 focus:ring-brand-500">
                <span class="text-zinc-300 text-sm">Active</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_animated" value="1" <?= !empty($old['is_animated']) ? 'checked' : '' ?> class="w-4 h-4 rounded bg-surface-200 border-surface-400 text-brand-600 focus:ring-brand-500">
                <span class="text-zinc-300 text-sm">Animated</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full gradient-brand py-3 rounded-xl text-white font-bold text-sm hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                <span class="material-icons-round text-lg">add_circle</span>
                Create Gift
            </button>
        </div>
    </form>
</div>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../../layouts/app.php'; ?>
