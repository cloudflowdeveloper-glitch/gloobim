<?php $title = 'Login - DTTube'; ?>
<?php ob_start(); ?>
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <img src="/logo.jpeg" alt="Globiim" class="h-14 w-auto rounded-2xl object-contain mx-auto mb-4">
            <h1 class="font-display text-2xl font-bold text-white">Welcome Back</h1>
            <p class="text-zinc-400 mt-1">Sign in to your DTTube account</p>
        </div>
        <div class="glass rounded-2xl p-8">
            <div class="space-y-3 mb-6">
                <a href="/auth/google" class="w-full flex items-center justify-center gap-3 py-3 rounded-xl bg-surface-200 border border-surface-400/50 text-white text-sm font-medium hover:bg-surface-300 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Continue with Google
                </a>
                <a href="/auth/apple" class="w-full flex items-center justify-center gap-3 py-3 rounded-xl bg-surface-200 border border-surface-400/50 text-white text-sm font-medium hover:bg-surface-300 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="white"><path d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/></svg>
                    Continue with Apple
                </a>
                <a href="/auth/facebook" class="w-full flex items-center justify-center gap-3 py-3 rounded-xl bg-[#1877F2] text-white text-sm font-medium hover:bg-[#1565C0] transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    Continue with Facebook
                </a>
                <a href="/auth/x" class="w-full flex items-center justify-center gap-3 py-3 rounded-xl bg-surface-200 border border-surface-400/50 text-white text-sm font-medium hover:bg-surface-300 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="white"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    Continue with X
                </a>
            </div>

            <div class="flex items-center gap-3 mb-6">
                <div class="flex-1 h-px bg-surface-400/50"></div>
                <span class="text-zinc-500 text-xs font-medium">OR</span>
                <div class="flex-1 h-px bg-surface-400/50"></div>
            </div>

            <div class="mb-6">
                <p class="text-zinc-500 text-xs font-medium text-center mb-2">Demo Accounts</p>
                <div class="space-y-2">
                    <button type="button" onclick="fillDemo('admin@dttube.com', 'password')" class="w-full flex items-center gap-3 p-3 rounded-xl bg-surface-200/80 border border-surface-400/30 hover:border-brand-500/50 transition-all text-left">
                        <div class="w-9 h-9 rounded-full gradient-brand flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-round text-white text-sm">admin_panel_settings</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-semibold">Admin Account</p>
                            <p class="text-zinc-400 text-xs truncate">admin@dttube.com</p>
                        </div>
                        <span class="text-[10px] text-zinc-500 bg-surface-300/50 px-2 py-1 rounded-md font-mono">password</span>
                    </button>
                    <button type="button" onclick="fillDemo('zarake@dttube.com', 'password')" class="w-full flex items-center gap-3 p-3 rounded-xl bg-surface-200/80 border border-surface-400/30 hover:border-brand-500/50 transition-all text-left">
                        <div class="w-9 h-9 rounded-full bg-purple-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-icons-round text-white text-sm">person</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-semibold">Creator Account</p>
                            <p class="text-zinc-400 text-xs truncate">zarake@dttube.com</p>
                        </div>
                        <span class="text-[10px] text-zinc-500 bg-surface-300/50 px-2 py-1 rounded-md font-mono">password</span>
                    </button>
                </div>
            </div>

            <form method="POST" action="/login" class="space-y-5">
                <?php
                $error = \Core\Session::getFlash('error');
                $success = \Core\Session::getFlash('success');
                $errors = \Core\Session::getFlash('errors');
                ?>
                <?php if ($error): ?>
                <div class="p-3 rounded-xl bg-red-500/15 border border-red-500/30 flex items-center gap-2.5">
                    <span class="material-icons-round text-red-400 text-lg">error_outline</span>
                    <span class="text-red-300 text-xs font-medium"><?= $error ?></span>
                </div>
                <?php endif; ?>
                <?php if ($success): ?>
                <div class="p-3 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center gap-2.5">
                    <span class="material-icons-round text-emerald-400 text-lg">check_circle</span>
                    <span class="text-emerald-300 text-xs font-medium"><?= $success ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                <div class="p-3 rounded-xl bg-red-500/15 border border-red-500/30">
                    <?php foreach ((array)$errors as $field => $msg): ?>
                    <div class="flex items-center gap-2 text-red-300 text-xs">
                        <span class="material-icons-round text-red-400 text-[14px]">circle</span>
                        <?= $msg ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <div>
                    <label class="block text-sm font-medium text-zinc-300 mb-1.5">Email</label>
                    <div class="relative">
                        <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-surface-500">email</span>
                        <input type="email" name="email" required class="w-full bg-surface-200 text-white pl-10 pr-4 py-3 rounded-xl border border-surface-400/50 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500/50 text-sm placeholder:text-surface-500" placeholder="you@example.com">
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-zinc-300">Password</label>
                        <a href="/forgot-password" class="text-brand-400 text-xs font-medium hover:text-brand-300">Forgot?</a>
                    </div>
                    <div class="relative">
                        <span class="material-icons-round absolute left-3 top-1/2 -translate-y-1/2 text-surface-500">lock</span>
                        <input type="password" name="password" required class="w-full bg-surface-200 text-white pl-10 pr-4 py-3 rounded-xl border border-surface-400/50 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500/50 text-sm placeholder:text-surface-500" placeholder="Enter your password">
                    </div>
                </div>
                <button type="submit" class="w-full gradient-brand py-3 rounded-xl text-white font-semibold hover:opacity-90 transition-opacity">
                    Sign In
                </button>
            </form>
            <div class="mt-6 text-center">
                <p class="text-zinc-400 text-sm">Don't have an account? <a href="/register" class="text-brand-400 font-medium hover:text-brand-300">Sign Up</a></p>
            </div>
        </div>
    </div>
</div>
<script>
function fillDemo(email, password) {
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');
    const form = document.querySelector('form[action="/login"]');

    if (emailInput && passwordInput && form) {
        emailInput.value = email;
        passwordInput.value = password;
        form.submit();
    }
}
</script>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
