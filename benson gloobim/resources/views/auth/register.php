<?php
$title = 'Register - Globiim';
$hideTopNav = true;
$hideBottomNav = true;
ob_start();
?>

<style>
    .register-page { max-width: 480px; margin: 0 auto; padding: 24px 20px 40px; min-height: 100vh; }
    .account-card { background: #1a1a2e; border-radius: 16px; padding: 16px; border: 1px solid #2a2a3e; cursor: pointer; transition: all 0.25s ease; position: relative; }
    .account-card:hover { border-color: rgba(147,51,234,0.3); background: #1e1e35; }
    .account-card.selected { border-color: rgba(147,51,234,0.5); background: rgba(147,51,234,0.08); }
    .account-card .check-badge { display: none; }
    .account-card.selected .check-badge { display: flex; }
    .benefits-panel { max-height: 0; overflow: hidden; transition: max-height 0.4s ease, opacity 0.3s ease; opacity: 0; }
    .benefits-panel.active { max-height: 200px; opacity: 1; }
    .form-group { position: relative; margin-bottom: 16px; }
    .form-group label { display: block; font-size: 12px; color: #a1a1aa; font-weight: 500; margin-bottom: 6px; }
    .form-input-wrap { position: relative; }
    .form-input-wrap .icon-left { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9333ea; font-size: 20px; pointer-events: none; }
    .form-input { width: 100%; background: rgba(39,39,42,0.8); border: 1px solid rgba(63,63,70,0.3); border-radius: 12px; color: #fff; font-size: 14px; padding: 12px 40px 12px 44px; outline: none; transition: border-color 0.2s ease; }
    .form-input:focus { border-color: rgba(147,51,234,0.6); }
    .form-input::placeholder { color: #52525b; }
    .form-input.valid { border-color: rgba(16,185,129,0.4); }
    .form-input-wrap .icon-check { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); opacity: 0; transition: opacity 0.2s ease; }
    .form-input.valid ~ .icon-check { opacity: 1; }
    .btn-continue { width: 100%; padding: 14px 0; border-radius: 12px; background: linear-gradient(135deg, #ec4899, #9333ea); color: #fff; font-size: 15px; font-weight: 700; border: none; cursor: pointer; transition: opacity 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-continue:hover { opacity: 0.9; }
    .btn-continue:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-back { flex: 1; padding: 12px 0; border-radius: 12px; background: #27272a; border: 1px solid rgba(63,63,70,0.3); color: #d4d4d8; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.2s ease; }
    .btn-back:hover { background: #2d2d32; }
    .step-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; transition: all 0.3s ease; flex-shrink: 0; }
    .step-dot.active { background: linear-gradient(135deg, #ec4899, #9333ea); color: #fff; box-shadow: 0 0 20px rgba(147,51,234,0.3); }
    .step-dot.completed { background: #10b981; color: #fff; }
    .step-dot.inactive { background: #27272a; color: #52525b; border: 1px solid #3f3f46; }
    .step-line { height: 2px; flex: 1; transition: background 0.3s ease; }
    .step-line.active { background: linear-gradient(90deg, #10b981, #9333ea); }
    .step-line.inactive { background: #3f3f46; }
    .interest-tag { display: inline-flex; align-items: center; padding: 8px 16px; border-radius: 999px; background: #27272a; border: 1px solid #3f3f46; color: #a1a1aa; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; user-select: none; }
    .interest-tag:hover { border-color: rgba(147,51,234,0.4); color: #d8b4fe; }
    .interest-tag.active { background: rgba(147,51,234,0.15); border-color: rgba(147,51,234,0.5); color: #d8b4fe; }
    .pwd-req { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #71717a; transition: color 0.2s ease; }
    .pwd-req.met { color: #34d399; }
    .pwd-req .req-icon { width: 16px; height: 16px; border-radius: 50%; border: 1.5px solid #52525b; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.2s ease; }
    .pwd-req.met .req-icon { background: #10b981; border-color: #10b981; }
    .eye-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #71717a; cursor: pointer; padding: 4px; display: flex; align-items: center; transition: color 0.2s ease; }
    .eye-toggle:hover { color: #a1a1aa; }
    .eye-toggle ~ .icon-check { right: 40px; }
    .step-section { display: none; animation: fadeIn 0.35s ease; }
    .step-section.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .avatar-upload { position: relative; display: inline-flex; }
    .avatar-circle { width: 96px; height: 96px; border-radius: 50%; background: linear-gradient(135deg, #9333ea, #ec4899); display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .avatar-circle img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-btn { position: absolute; bottom: -2px; right: -2px; width: 32px; height: 32px; border-radius: 50%; background: #27272a; border: 2px solid #1a1a2e; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #d4d4d8; transition: background 0.2s ease; }
    .avatar-btn:hover { background: #3f3f46; }
    .header-row { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
    .back-btn { width: 40px; height: 40px; border-radius: 50%; background: #27272a; border: 1px solid #3f3f46; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #fff; flex-shrink: 0; transition: background 0.2s ease; }
    .back-btn:hover { background: #3f3f46; }
    .error-toast { position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 12px 20px; border-radius: 12px; font-size: 13px; font-weight: 500; z-index: 100; display: none; align-items: center; gap: 8px; backdrop-filter: blur(10px); }
    .error-toast.show { display: flex; animation: fadeIn 0.3s ease; }
    .loading-spinner { width: 20px; height: 20px; border: 2px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.6s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="register-page">

    <!-- Error Toast -->
    <div class="error-toast" id="errorToast">
        <span class="material-icons-round" style="font-size:18px; color:#f87171;">error_outline</span>
        <span id="errorToastText"></span>
    </div>

    <!-- Step 1: Choose Account Type -->
    <div class="step-section active" id="step1Section">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 mb-4">
                <img src="/logo.jpeg" alt="Globiim" class="h-10 w-auto rounded-xl object-contain">
                <span class="font-display font-bold text-xl tracking-tight">
                    <span class="text-white">Glo</span><span class="gradient-text">biim</span>
                </span>
            </div>
            <h1 class="text-white font-bold text-2xl mb-1">Create Your Account</h1>
            <p class="text-zinc-400 text-sm">Join the global creator ecosystem</p>
        </div>

        <!-- Progress Indicator -->
        <div class="flex items-center justify-center gap-0 mb-8 px-4" id="progressBar1">
            <div class="step-dot active" data-step="1">1</div>
            <div class="step-line inactive" data-line="1"></div>
            <div class="step-dot inactive" data-step="2">2</div>
            <div class="step-line inactive" data-line="2"></div>
            <div class="step-dot inactive" data-step="3">3</div>
        </div>
        <p class="text-center text-zinc-500 text-xs font-medium mb-6" id="stepLabel">Step 1 of 3</p>

        <!-- Section Title -->
        <h2 class="text-white font-bold text-lg mb-4">Choose Account Type</h2>

        <!-- Account Type Grid -->
        <div class="grid grid-cols-2 gap-3 mb-4" id="accountGrid">
            <!-- Personal -->
            <div class="account-card selected" data-type="personal" onclick="selectAccountType(this)">
                <div class="check-badge absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full flex items-center justify-center z-10" style="background: linear-gradient(135deg, #ec4899, #9333ea);">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6L5 8.5L9.5 3.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: rgba(147,51,234,0.15);">
                        <span class="material-icons-round" style="color:#9333ea; font-size:22px;">person</span>
                    </div>
                </div>
                <p class="text-white text-sm font-semibold">Personal</p>
                <p class="text-zinc-500 text-xs mt-0.5">For individuals</p>
            </div>
            <!-- Creator -->
            <div class="account-card" data-type="creator" onclick="selectAccountType(this)">
                <div class="check-badge absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full flex items-center justify-center z-10" style="background: linear-gradient(135deg, #ec4899, #9333ea);">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6L5 8.5L9.5 3.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: rgba(239,68,68,0.15);">
                        <span class="material-icons-round" style="color:#ef4444; font-size:22px;">videocam</span>
                    </div>
                </div>
                <p class="text-white text-sm font-semibold">Creator</p>
                <p class="text-zinc-500 text-xs mt-0.5">For content creators</p>
            </div>
            <!-- Business -->
            <div class="account-card" data-type="business" onclick="selectAccountType(this)">
                <div class="check-badge absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full flex items-center justify-center z-10" style="background: linear-gradient(135deg, #ec4899, #9333ea);">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6L5 8.5L9.5 3.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: rgba(245,158,11,0.15);">
                        <span class="material-icons-round" style="color:#f59e0b; font-size:22px;">briefcase</span>
                    </div>
                </div>
                <p class="text-white text-sm font-semibold">Business</p>
                <p class="text-zinc-500 text-xs mt-0.5">For businesses</p>
            </div>
            <!-- NGO -->
            <div class="account-card" data-type="ngo" onclick="selectAccountType(this)">
                <div class="check-badge absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full flex items-center justify-center z-10" style="background: linear-gradient(135deg, #ec4899, #9333ea);">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6L5 8.5L9.5 3.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: rgba(16,185,129,0.15);">
                        <span class="material-icons-round" style="color:#10b981; font-size:22px;">eco</span>
                    </div>
                </div>
                <p class="text-white text-sm font-semibold">NGO</p>
                <p class="text-zinc-500 text-xs mt-0.5">For organizations</p>
            </div>
            <!-- Government -->
            <div class="account-card" data-type="government" onclick="selectAccountType(this)">
                <div class="check-badge absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full flex items-center justify-center z-10" style="background: linear-gradient(135deg, #ec4899, #9333ea);">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6L5 8.5L9.5 3.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: rgba(59,130,246,0.15);">
                        <span class="material-icons-round" style="color:#3b82f6; font-size:22px;">account_balance</span>
                    </div>
                </div>
                <p class="text-white text-sm font-semibold">Government</p>
                <p class="text-zinc-500 text-xs mt-0.5">For institutions</p>
            </div>
            <!-- Other -->
            <div class="account-card" data-type="other" onclick="selectAccountType(this)">
                <div class="check-badge absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full flex items-center justify-center z-10" style="background: linear-gradient(135deg, #ec4899, #9333ea);">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2.5 6L5 8.5L9.5 3.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: rgba(113,113,122,0.15);">
                        <span class="material-icons-round" style="color:#71717a; font-size:22px;">more_horiz</span>
                    </div>
                </div>
                <p class="text-white text-sm font-semibold">Other</p>
                <p class="text-zinc-500 text-xs mt-0.5">Other account type</p>
            </div>
        </div>

        <!-- Benefits Panel -->
        <div class="benefits-panel active" id="benefitsPanel">
            <div class="rounded-xl p-4 mt-1" style="background: rgba(147,51,234,0.06); border: 1px solid rgba(147,51,234,0.15);">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons-round text-brand-400" style="font-size:18px;">info</span>
                    <span class="text-brand-300 text-sm font-semibold" id="benefitsTitle">Personal Account Benefits</span>
                </div>
                <div id="benefitsList">
                    <div class="flex items-center gap-2.5 mb-2">
                        <span class="material-icons-round" style="color:#f59e0b; font-size:16px;">star</span>
                        <span class="text-zinc-300 text-xs">Start as Level 1 Creator</span>
                    </div>
                    <div class="flex items-center gap-2.5 mb-2">
                        <span class="material-icons-round" style="color:#ef4444; font-size:16px;">local_fire_department</span>
                        <span class="text-zinc-300 text-xs">Earn XP from uploads, watchtime, shares</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="material-icons-round" style="color:#f59e0b; font-size:16px;">emoji_events</span>
                        <span class="text-zinc-300 text-xs">Unlock badges & monetization</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Continue Button -->
        <div class="mt-6">
            <button class="btn-continue" onclick="goToStep2()">
                Continue
                <span class="material-icons-round" style="font-size:20px;">arrow_forward</span>
            </button>
        </div>

        <!-- Login Link -->
        <p class="text-center mt-6 text-zinc-500 text-sm">
            Already have an account? <a href="/login" class="text-brand-400 font-semibold hover:text-brand-300 transition-colors">Log In</a>
        </p>
    </div>

    <!-- Step 2: Create Account Form -->
    <div class="step-section" id="step2Section">
        <!-- Header with Back -->
        <div class="header-row">
            <button class="back-btn" onclick="goToStep1()">
                <span class="material-icons-round" style="font-size:20px;">arrow_back</span>
            </button>
            <div class="text-center flex-1">
                <h1 class="text-white font-bold text-lg">Create Your Account</h1>
                <p class="text-zinc-500 text-xs mt-0.5">Step 2 of 3</p>
            </div>
            <div style="width:40px;"></div>
        </div>

        <!-- Progress Indicator -->
        <div class="flex items-center justify-center gap-0 mb-8 px-4" id="progressBar2">
            <div class="step-dot completed" data-step="1">
                <span class="material-icons-round" style="font-size:16px;">check</span>
            </div>
            <div class="step-line active" data-line="1"></div>
            <div class="step-dot active" data-step="2">2</div>
            <div class="step-line inactive" data-line="2"></div>
            <div class="step-dot inactive" data-step="3">3</div>
        </div>

        <!-- Avatar Upload -->
        <div class="flex flex-col items-center mb-6">
            <div class="avatar-upload">
                <div class="avatar-circle" id="avatarPreview">
                    <span class="material-icons-round text-white" style="font-size:40px;">person</span>
                </div>
                <button type="button" class="avatar-btn" onclick="document.getElementById('avatarInput').click()">
                    <span class="material-icons-round" style="font-size:16px;">camera_alt</span>
                </button>
                <input type="file" id="avatarInput" accept="image/*" style="display:none;" onchange="previewAvatar(event)">
            </div>
            <p class="text-zinc-500 text-xs mt-3">Upload profile photo (optional)</p>
        </div>

        <!-- Form Fields -->
        <form id="step2Form" onsubmit="return false;" autocomplete="off">
            <!-- Full Name -->
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <div class="form-input-wrap">
                    <span class="material-icons-round icon-left">person</span>
                    <input type="text" id="fullName" class="form-input" placeholder="John Doe" autocomplete="name">
                    <span class="icon-check">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <circle cx="9" cy="9" r="9" fill="#10b981"/>
                            <path d="M5.5 9L7.8 11.5L12.5 6.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label>
                <div class="form-input-wrap">
                    <span class="material-icons-round icon-left">alternate_email</span>
                    <input type="text" id="username" class="form-input" placeholder="@username" autocomplete="username" style="padding-left: 44px;">
                    <span class="icon-check">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <circle cx="9" cy="9" r="9" fill="#10b981"/>
                            <path d="M5.5 9L7.8 11.5L12.5 6.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <div class="form-input-wrap">
                    <span class="material-icons-round icon-left">email</span>
                    <input type="email" id="email" class="form-input" placeholder="email@example.com" autocomplete="email">
                    <span class="icon-check">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <circle cx="9" cy="9" r="9" fill="#10b981"/>
                            <path d="M5.5 9L7.8 11.5L12.5 6.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <div class="form-input-wrap">
                    <span class="material-icons-round icon-left">lock</span>
                    <input type="password" id="password" class="form-input" placeholder="Create a strong password" autocomplete="new-password" style="padding-right: 80px;">
                    <button type="button" class="eye-toggle" onclick="togglePasswordVisibility('password', 'passEyeIcon')">
                        <span class="material-icons-round" id="passEyeIcon" style="font-size:20px;">visibility_off</span>
                    </button>
                    <span class="icon-check">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <circle cx="9" cy="9" r="9" fill="#10b981"/>
                            <path d="M5.5 9L7.8 11.5L12.5 6.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <div class="form-input-wrap">
                    <span class="material-icons-round icon-left">lock</span>
                    <input type="password" id="confirmPassword" class="form-input" placeholder="Confirm your password" autocomplete="new-password" style="padding-right: 80px;">
                    <button type="button" class="eye-toggle" onclick="togglePasswordVisibility('confirmPassword', 'confirmPassEyeIcon')">
                        <span class="material-icons-round" id="confirmPassEyeIcon" style="font-size:20px;">visibility_off</span>
                    </button>
                    <span class="icon-check">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <circle cx="9" cy="9" r="9" fill="#10b981"/>
                            <path d="M5.5 9L7.8 11.5L12.5 6.5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="rounded-xl p-4 mb-5" style="background: rgba(39,39,42,0.5); border: 1px solid rgba(63,63,70,0.2);">
                <p class="text-zinc-400 text-xs font-semibold mb-3">Password Requirements</p>
                <div class="space-y-2">
                    <div class="pwd-req" id="reqLength">
                        <span class="req-icon">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4 7L8 3" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span>At least 8 characters</span>
                    </div>
                    <div class="pwd-req" id="reqCombo">
                        <span class="req-icon">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4 7L8 3" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span>Include number and letter</span>
                    </div>
                    <div class="pwd-req" id="reqMatch">
                        <span class="req-icon">
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M2 5L4 7L8 3" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span>Password match</span>
                    </div>
                </div>
            </div>

            <!-- Continue Button -->
            <button type="button" class="btn-continue" onclick="validateAndGoToStep3()">
                Continue
                <span class="material-icons-round" style="font-size:20px;">arrow_forward</span>
            </button>
        </form>
    </div>

    <!-- Step 3: Profile Setup -->
    <div class="step-section" id="step3Section">
        <!-- Header with Back -->
        <div class="header-row">
            <button class="back-btn" onclick="goToStep2FromStep3()">
                <span class="material-icons-round" style="font-size:20px;">arrow_back</span>
            </button>
            <div class="text-center flex-1">
                <h1 class="text-white font-bold text-lg">Create Your Account</h1>
                <p class="text-zinc-500 text-xs mt-0.5">Step 3 of 3</p>
            </div>
            <div style="width:40px;"></div>
        </div>

        <!-- Progress Indicator -->
        <div class="flex items-center justify-center gap-0 mb-8 px-4" id="progressBar3">
            <div class="step-dot completed" data-step="1">
                <span class="material-icons-round" style="font-size:16px;">check</span>
            </div>
            <div class="step-line active" data-line="1"></div>
            <div class="step-dot completed" data-step="2">
                <span class="material-icons-round" style="font-size:16px;">check</span>
            </div>
            <div class="step-line active" data-line="2"></div>
            <div class="step-dot active" data-step="3">3</div>
        </div>

        <form id="step3Form" onsubmit="return false;">
            <!-- Hidden Inputs for Data from Previous Steps -->
            <input type="hidden" id="hiddenAccountType" name="account_type">
            <input type="hidden" id="hiddenFullName" name="full_name">
            <input type="hidden" id="hiddenUsername" name="username">
            <input type="hidden" id="hiddenEmail" name="email">
            <input type="hidden" id="hiddenPassword" name="password">
            <input type="hidden" id="hiddenAvatar" name="avatar_data">

            <!-- Bio -->
            <div class="form-group">
                <label for="bio">Bio</label>
                <div class="form-input-wrap" style="position:relative;">
                    <span class="material-icons-round" style="position:absolute; left:14px; top:14px; color:#9333ea; font-size:20px; pointer-events:none;">edit</span>
                    <textarea id="bio" class="form-input" rows="3" placeholder="Tell the world about yourself..." style="padding-top:14px; padding-left:44px; resize:none; min-height:100px;" maxlength="200"></textarea>
                </div>
                <div class="flex justify-end mt-1">
                    <span class="text-zinc-600 text-xs" id="bioCount">0/200</span>
                </div>
            </div>

            <!-- Interest Tags -->
            <div class="form-group">
                <label>Select Your Interests</label>
                <div class="flex flex-wrap gap-2" id="interestTagsContainer">
                    <span class="interest-tag" data-interest="Music" onclick="toggleInterest(this)">Music</span>
                    <span class="interest-tag" data-interest="Dance" onclick="toggleInterest(this)">Dance</span>
                    <span class="interest-tag" data-interest="Gaming" onclick="toggleInterest(this)">Gaming</span>
                    <span class="interest-tag" data-interest="Comedy" onclick="toggleInterest(this)">Comedy</span>
                    <span class="interest-tag" data-interest="Education" onclick="toggleInterest(this)">Education</span>
                    <span class="interest-tag" data-interest="Sports" onclick="toggleInterest(this)">Sports</span>
                    <span class="interest-tag" data-interest="Fashion" onclick="toggleInterest(this)">Fashion</span>
                    <span class="interest-tag" data-interest="Art" onclick="toggleInterest(this)">Art</span>
                    <span class="interest-tag" data-interest="Tech" onclick="toggleInterest(this)">Tech</span>
                    <span class="interest-tag" data-interest="Cooking" onclick="toggleInterest(this)">Cooking</span>
                    <span class="interest-tag" data-interest="Travel" onclick="toggleInterest(this)">Travel</span>
                    <span class="interest-tag" data-interest="News" onclick="toggleInterest(this)">News</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 mt-6">
                <button type="button" class="btn-back" onclick="goToStep2FromStep3()">
                    <span class="material-icons-round" style="font-size:20px;">arrow_back</span>
                    Back
                </button>
                <button type="button" class="btn-continue" id="createAccountBtn" onclick="submitRegistration()" style="flex:2;">
                    <span id="createBtnText">Create Account</span>
                    <span id="createBtnSpinner" class="loading-spinner" style="display:none;"></span>
                    <span class="material-icons-round" style="font-size:20px;" id="createBtnIcon">check_circle</span>
                </button>
            </div>
        </form>
    </div>

</div>

<script>
(function() {
    // State
    let selectedAccountType = 'personal';
    let selectedInterests = [];
    let avatarDataUrl = null;

    // Benefits data per account type
    const benefitsMap = {
        personal: {
            title: 'Personal Account Benefits',
            items: [
                { icon: 'star', color: '#f59e0b', text: 'Start as Level 1 Creator' },
                { icon: 'local_fire_department', color: '#ef4444', text: 'Earn XP from uploads, watchtime, shares' },
                { icon: 'emoji_events', color: '#f59e0b', text: 'Unlock badges & monetization' }
            ]
        },
        creator: {
            title: 'Creator Account Benefits',
            items: [
                { icon: 'star', color: '#f59e0b', text: 'Start as Level 2 Creator' },
                { icon: 'local_fire_department', color: '#ef4444', text: 'Access creator analytics dashboard' },
                { icon: 'emoji_events', color: '#f59e0b', text: 'Early monetization access' }
            ]
        },
        business: {
            title: 'Business Account Benefits',
            items: [
                { icon: 'star', color: '#f59e0b', text: 'Business verification badge' },
                { icon: 'local_fire_department', color: '#ef4444', text: 'Analytics & ad manager tools' },
                { icon: 'emoji_events', color: '#f59e0b', text: 'Priority support & API access' }
            ]
        },
        ngo: {
            title: 'NGO Account Benefits',
            items: [
                { icon: 'star', color: '#f59e0b', text: 'NGO verification badge' },
                { icon: 'local_fire_department', color: '#ef4444', text: 'Fundraising & donation tools' },
                { icon: 'emoji_events', color: '#f59e0b', text: 'Community impact tracking' }
            ]
        },
        government: {
            title: 'Government Account Benefits',
            items: [
                { icon: 'star', color: '#f59e0b', text: 'Government verification badge' },
                { icon: 'local_fire_department', color: '#ef4444', text: 'Official communications channel' },
                { icon: 'emoji_events', color: '#f59e0b', text: 'Public service analytics' }
            ]
        },
        other: {
            title: 'Account Benefits',
            items: [
                { icon: 'star', color: '#f59e0b', text: 'Start as Level 1 Creator' },
                { icon: 'local_fire_department', color: '#ef4444', text: 'Earn XP from uploads, watchtime, shares' },
                { icon: 'emoji_events', color: '#f59e0b', text: 'Unlock badges & monetization' }
            ]
        }
    };

    // Account type selection
    window.selectAccountType = function(card) {
        const cards = document.querySelectorAll('.account-card');
        cards.forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');

        selectedAccountType = card.dataset.type;
        updateBenefits(selectedAccountType);
    };

    function updateBenefits(type) {
        const panel = document.getElementById('benefitsPanel');
        const title = document.getElementById('benefitsTitle');
        const list = document.getElementById('benefitsList');
        const data = benefitsMap[type] || benefitsMap.personal;

        // Animate out
        panel.classList.remove('active');

        setTimeout(() => {
            title.textContent = data.title;
            list.innerHTML = data.items.map(item => `
                <div class="flex items-center gap-2.5 mb-2">
                    <span class="material-icons-round" style="color:${item.color}; font-size:16px;">${item.icon}</span>
                    <span class="text-zinc-300 text-xs">${item.text}</span>
                </div>
            `).join('');
            panel.classList.add('active');
        }, 200);
    }

    // Step Navigation
    window.goToStep2 = function() {
        if (!selectedAccountType) {
            showError('Please select an account type');
            return;
        }
        switchSection('step1Section', 'step2Section');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.goToStep1 = function() {
        switchSection('step2Section', 'step1Section');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.goToStep2FromStep3 = function() {
        switchSection('step3Section', 'step2Section');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.validateAndGoToStep3 = function() {
        const fullName = document.getElementById('fullName').value.trim();
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (!fullName) { showError('Please enter your full name'); return; }
        if (!username || username.length < 3) { showError('Username must be at least 3 characters'); return; }
        if (!email || !isValidEmail(email)) { showError('Please enter a valid email address'); return; }
        if (password.length < 8) { showError('Password must be at least 8 characters'); return; }
        if (!/(?=.*[a-zA-Z])(?=.*\d)/.test(password)) { showError('Password must include both a letter and a number'); return; }
        if (password !== confirmPassword) { showError('Passwords do not match'); return; }

        switchSection('step2Section', 'step3Section');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    function switchSection(from, to) {
        document.getElementById(from).classList.remove('active');
        document.getElementById(to).classList.add('active');
    }

    // Password visibility toggle
    window.togglePasswordVisibility = function(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility_off';
        }
    };

    // Real-time validation
    function setupValidation() {
        const fullNameInput = document.getElementById('fullName');
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const bioInput = document.getElementById('bio');

        // Full name validation
        fullNameInput.addEventListener('input', function() {
            this.classList.toggle('valid', this.value.trim().length >= 2);
        });

        // Username validation
        usernameInput.addEventListener('input', function() {
            const val = this.value.trim();
            this.classList.toggle('valid', val.length >= 3 && /^[a-zA-Z0-9_]+$/.test(val));
        });

        // Email validation
        emailInput.addEventListener('input', function() {
            this.classList.toggle('valid', isValidEmail(this.value.trim()));
        });

        // Password validation
        passwordInput.addEventListener('input', function() {
            updatePasswordRequirements();
        });

        confirmPasswordInput.addEventListener('input', function() {
            updatePasswordRequirements();
        });

        // Bio char count
        bioInput.addEventListener('input', function() {
            document.getElementById('bioCount').textContent = this.value.length + '/200';
        });
    }

    function updatePasswordRequirements() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        const reqLength = document.getElementById('reqLength');
        const reqCombo = document.getElementById('reqCombo');
        const reqMatch = document.getElementById('reqMatch');

        reqLength.classList.toggle('met', password.length >= 8);
        reqCombo.classList.toggle('met', /(?=.*[a-zA-Z])(?=.*\d)/.test(password));
        reqMatch.classList.toggle('met', password.length > 0 && confirmPassword.length > 0 && password === confirmPassword);

        // Green check on password field
        document.getElementById('password').classList.toggle('valid', password.length >= 8 && /(?=.*[a-zA-Z])(?=.*\d)/.test(password));

        // Green check on confirm field
        document.getElementById('confirmPassword').classList.toggle('valid', confirmPassword.length > 0 && password === confirmPassword);
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Avatar upload preview
    window.previewAvatar = function(event) {
        const file = event.target.files[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            showError('Image must be under 5MB');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            avatarDataUrl = e.target.result;
            const preview = document.getElementById('avatarPreview');
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Avatar">';
        };
        reader.readAsDataURL(file);
    };

    // Interest tag toggle
    window.toggleInterest = function(tag) {
        const interest = tag.dataset.interest;
        tag.classList.toggle('active');
        if (selectedInterests.includes(interest)) {
            selectedInterests = selectedInterests.filter(i => i !== interest);
        } else {
            selectedInterests.push(interest);
        }
    };

    // Form submission
    window.submitRegistration = function() {
        const btn = document.getElementById('createAccountBtn');
        const btnText = document.getElementById('createBtnText');
        const btnSpinner = document.getElementById('createBtnSpinner');
        const btnIcon = document.getElementById('createBtnIcon');

        // Populate hidden fields
        document.getElementById('hiddenAccountType').value = selectedAccountType;
        document.getElementById('hiddenFullName').value = document.getElementById('fullName').value.trim();
        document.getElementById('hiddenUsername').value = document.getElementById('username').value.trim();
        document.getElementById('hiddenEmail').value = document.getElementById('email').value.trim();
        document.getElementById('hiddenPassword').value = document.getElementById('password').value;
        document.getElementById('hiddenAvatar').value = avatarDataUrl || '';

        const formData = {
            account_type: selectedAccountType,
            full_name: document.getElementById('fullName').value.trim(),
            username: document.getElementById('username').value.trim(),
            email: document.getElementById('email').value.trim(),
            password: document.getElementById('password').value,
            bio: document.getElementById('bio').value.trim(),
            interests: selectedInterests,
            avatar_data: avatarDataUrl || ''
        };

        // Show loading
        btn.disabled = true;
        btnText.textContent = 'Creating...';
        btnSpinner.style.display = 'block';
        btnIcon.style.display = 'none';

        fetch('/register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                btnText.textContent = 'Success!';
                btnSpinner.style.display = 'none';
                btnIcon.style.display = 'inline';
                btnIcon.textContent = 'check_circle';
                setTimeout(() => {
                    window.location.href = data.redirect || '/login';
                }, 1000);
            } else {
                showError(data.message || 'Registration failed. Please try again.');
                resetCreateBtn();
            }
        })
        .catch(err => {
            // Fallback: submit as traditional form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/register';

            Object.keys(formData).forEach(key => {
                if (key === 'interests') {
                    formData[key].forEach(val => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'interests[]';
                        input.value = val;
                        form.appendChild(input);
                    });
                } else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = formData[key];
                    form.appendChild(input);
                }
            });

            document.body.appendChild(form);
            form.submit();
        });
    };

    function resetCreateBtn() {
        const btn = document.getElementById('createAccountBtn');
        const btnText = document.getElementById('createBtnText');
        const btnSpinner = document.getElementById('createBtnSpinner');
        const btnIcon = document.getElementById('createBtnIcon');
        btn.disabled = false;
        btnText.textContent = 'Create Account';
        btnSpinner.style.display = 'none';
        btnIcon.style.display = 'inline';
    }

    // Error toast
    function showError(message) {
        const toast = document.getElementById('errorToast');
        const text = document.getElementById('errorToastText');
        text.textContent = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3500);
    }
    window.showError = showError;

    // Init
    setupValidation();
})();
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
