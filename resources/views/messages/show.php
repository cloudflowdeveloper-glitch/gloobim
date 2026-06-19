<?php $title = 'Chat - Globiim'; $hideTopNav = true; $hideBottomNav = true; ?>
<?php
$messages = $data['messages'] ?? [];
$conversation = $data['conversation'] ?? null;
$userId = (int)($data['userId'] ?? 0);
$otherName = $conversation['other_name'] ?? 'Chat';
$otherAvatar = $conversation['other_avatar'] ?? 'https://placehold.co/48x48/3f3f46/ffffff?text=U';
$otherVerified = !empty($conversation['other_verified']);
$otherOnline = !empty($conversation['other_online']);
?>
<?php ob_start(); ?>
<style>
    * { box-sizing: border-box; }

    /* Chat area */
    .chat-scroll { overflow-y: auto; -ms-overflow-style: none; scrollbar-width: none; }
    .chat-scroll::-webkit-scrollbar { display: none; }

    /* Message bubbles */
    .msg-mine .msg-bubble {
        background: linear-gradient(135deg, #834ae5, #6b21a8);
        border-radius: 18px 18px 4px 18px;
        color: white;
    }
    .msg-other .msg-bubble {
        background: #14141c;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 18px 18px 18px 4px;
        color: white;
    }

    /* Typing indicator */
    .typing-bubble {
        background: #14141c;
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 18px 18px 18px 4px;
    }
    .typing-dots span {
        display: inline-block; width: 7px; height: 7px; border-radius: 50%;
        background: #834ae5; animation: bounce 1.4s infinite;
    }
    .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
    .typing-dots span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes bounce { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-5px); } }

    /* Image message */
    .msg-image { border-radius: 14px; overflow: hidden; max-width: 220px; }
    .msg-image img { width: 100%; display: block; cursor: pointer; transition: opacity 0.2s; }
    .msg-image img:hover { opacity: 0.9; }

    /* Voice message */
    .voice-wave {
        display: flex; align-items: center; gap: 2px; height: 20px;
    }
    .voice-wave span {
        width: 3px; border-radius: 2px; background: #834ae5;
        animation: wave 1.2s ease-in-out infinite;
    }
    .voice-wave span:nth-child(1) { height: 6px; animation-delay: 0s; }
    .voice-wave span:nth-child(2) { height: 12px; animation-delay: 0.15s; }
    .voice-wave span:nth-child(3) { height: 8px; animation-delay: 0.3s; }
    .voice-wave span:nth-child(4) { height: 16px; animation-delay: 0.45s; }
    .voice-wave span:nth-child(5) { height: 10px; animation-delay: 0.6s; }
    .voice-wave span:nth-child(6) { height: 14px; animation-delay: 0.75s; }
    .voice-wave span:nth-child(7) { height: 7px; animation-delay: 0.9s; }
    @keyframes wave { 0%, 100% { transform: scaleY(1); } 50% { transform: scaleY(0.4); } }

    /* Input area */
    .chat-input { background: #14141c; border: 1px solid #1e1e2a; border-radius: 24px; }
    .chat-input:focus { border-color: #834ae5; outline: none; }
    .emoji-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 4px; max-height: 200px; overflow-y: auto; }
    .emoji-btn { padding: 6px; font-size: 20px; border-radius: 8px; cursor: pointer; transition: background 0.15s; text-align: center; }
    .emoji-btn:hover { background: rgba(131,74,229,0.15); }

    /* Reply preview */
    .reply-preview { background: rgba(131,74,229,0.1); border-left: 3px solid #834ae5; border-radius: 0 10px 10px 0; }

    /* Attach menu */
    .attach-item { transition: all 0.2s ease; }
    .attach-item:hover { transform: translateY(-2px); }
    .attach-item:active { transform: scale(0.95); }

    /* Slide up animation */
    @keyframes slideUp { 0% { transform: translateY(20px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
    .slide-up { animation: slideUp 0.25s ease-out forwards; }

    /* Pulse online */
    @keyframes pulse-online { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    .pulse-online { animation: pulse-online 2s ease-in-out infinite; }

    /* Image viewer overlay */
    .image-viewer { transition: opacity 0.3s ease; }
</style>

<div class="max-w-lg mx-auto h-screen flex flex-col" style="background: #090c15;">

    <!-- ===== CHAT HEADER ===== -->
    <div class="flex items-center gap-3 px-3 py-2.5 flex-shrink-0 glass" style="border-bottom: 1px solid rgba(20,20,28,0.8);">
        <!-- Back -->
        <a href="/messages" class="w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors flex-shrink-0">
            <span class="material-icons-round text-zinc-300 text-lg">arrow_back</span>
        </a>

        <?php if ($conversation): ?>
        <!-- User Info -->
        <div class="flex items-center gap-2.5 flex-1 min-w-0 cursor-pointer" onclick="showProfileSheet()">
            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 rounded-full overflow-hidden" style="box-shadow: 0 0 0 2px rgba(131,74,229,0.4);">
                    <img src="<?= $otherAvatar ?>" alt="<?= htmlspecialchars($otherName) ?>" class="w-full h-full object-cover">
                </div>
                <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-[#090c15] <?= $otherOnline ? 'bg-green-500 pulse-online' : 'bg-zinc-600' ?>"></div>
                <?php if ($otherVerified): ?>
                <div class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-[#834ae5] rounded-full flex items-center justify-center">
                    <svg width="8" height="8" viewBox="0 0 24 24" fill="white"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                </div>
                <?php endif; ?>
            </div>
            <div class="min-w-0">
                <h2 class="text-white text-sm font-bold truncate"><?= htmlspecialchars($otherName) ?></h2>
                <span class="text-[10px] <?= $otherOnline ? 'text-green-400' : 'text-zinc-500' ?>" id="onlineStatus"><?= $otherOnline ? 'Online' : 'Last seen recently' ?></span>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="flex items-center gap-1 flex-shrink-0">
            <button onclick="startVoiceCall()" class="w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">phone</span>
            </button>
            <button onclick="startVideoCall()" class="w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">videocam</span>
            </button>
            <button onclick="toggleChatMenu()" class="w-9 h-9 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center hover:bg-[#1e1e2a] transition-colors">
                <span class="material-icons-round text-zinc-400 text-lg">more_vert</span>
            </button>
        </div>
        <?php else: ?>
        <h2 class="text-white text-sm font-bold">Chat</h2>
        <?php endif; ?>
    </div>

    <!-- ===== CHAT MENU (hidden) ===== -->
    <div id="chatMenu" class="hidden fixed top-14 right-3 z-[50] bg-[#14141c] border border-[#1e1e2a] rounded-2xl overflow-hidden shadow-2xl min-w-[200px] slide-up" style="box-shadow: 0 8px 30px rgba(0,0,0,0.5);">
        <button onclick="viewProfile(); closeChatMenu();" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg">person</span>
            <span class="text-zinc-200 text-sm">View Profile</span>
        </button>
        <button onclick="toggleMute(); closeChatMenu();" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg" id="muteIcon">notifications</span>
            <span class="text-zinc-200 text-sm" id="muteText">Mute Notifications</span>
        </button>
        <button onclick="searchInChat(); closeChatMenu();" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-zinc-300 text-lg">search</span>
            <span class="text-zinc-200 text-sm">Search in Chat</span>
        </button>
        <div class="h-px bg-[#1e1e2a]"></div>
        <button onclick="clearChat(); closeChatMenu();" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-zinc-500 text-lg">delete_sweep</span>
            <span class="text-zinc-400 text-sm">Clear Chat</span>
        </button>
        <button onclick="blockUser(); closeChatMenu();" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors">
            <span class="material-icons-round text-red-400 text-lg">block</span>
            <span class="text-red-400 text-sm">Block User</span>
        </button>
    </div>

    <!-- ===== MESSAGES AREA ===== -->
    <div class="flex-1 chat-scroll p-3 space-y-2" id="chatMessages">
        <?php if ($conversation && !empty($messages)): ?>
            <?php $prevDate = null; ?>
            <?php foreach ($messages as $msg): ?>
            <?php
                $isMine = (int)$msg['sender_id'] === $userId;
                $msgDate = date('Y-m-d', strtotime($msg['created_at']));
                $today = date('Y-m-d');
                $yesterday = date('Y-m-d', strtotime('-1 day'));
                $msgType = $msg['message_type'] ?? 'text';
            ?>
            <!-- Date Separator -->
            <?php if ($msgDate !== $prevDate): ?>
            <div class="flex items-center justify-center py-2">
                <span class="px-3 py-1 rounded-full bg-[#14141c] text-zinc-500 text-[10px] font-medium" style="border: 1px solid rgba(255,255,255,0.04);">
                    <?php if ($msgDate === $today): ?>Today
                    <?php elseif ($msgDate === $yesterday): ?>Yesterday
                    <?php else: ?><?= date('M j, Y', strtotime($msg['created_at'])) ?>
                    <?php endif; ?>
                </span>
            </div>
            <?php $prevDate = $msgDate; ?>
            <?php endif; ?>

            <!-- Message -->
            <div class="flex <?= $isMine ? 'justify-end' : 'justify-start' ?> items-end gap-1.5 msg-<?= $isMine ? 'mine' : 'other' ?>" data-msg-id="<?= $msg['id'] ?? '' ?>">
                <?php if (!$isMine): ?>
                <div class="w-7 h-7 rounded-full overflow-hidden flex-shrink-0 mb-5">
                    <img src="<?= $msg['sender_avatar'] ?? $otherAvatar ?>" alt="" class="w-full h-full object-cover">
                </div>
                <?php endif; ?>

                <div class="max-w-[78%]">
                    <?php if ($msgType === 'image' && !empty($msg['image_url'])): ?>
                    <!-- Image Message -->
                    <div class="msg-image <?= $isMine ? 'ml-auto' : '' ?>" onclick="viewImage('<?= htmlspecialchars($msg['image_url']) ?>')">
                        <img src="<?= htmlspecialchars($msg['image_url']) ?>" alt="Image">
                    </div>
                    <?php if (!empty($msg['body'])): ?>
                    <div class="msg-bubble mt-1 px-3 py-2 <?= $isMine ? 'msg-mine' : 'msg-other' ?>">
                        <p class="text-xs leading-relaxed"><?= htmlspecialchars($msg['body']) ?></p>
                    </div>
                    <?php endif; ?>
                    <?php elseif ($msgType === 'voice'): ?>
                    <!-- Voice Message -->
                    <div class="msg-bubble px-3 py-2.5 flex items-center gap-2.5 min-w-[200px]" style="<?= $isMine ? 'background: linear-gradient(135deg, #834ae5, #6b21a8);' : 'background: #14141c; border: 1px solid rgba(255,255,255,0.05);' ?>">
                        <button class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(255,255,255,0.15);">
                            <span class="material-icons-round text-white text-sm">play_arrow</span>
                        </button>
                        <div class="flex-1">
                            <div class="voice-wave"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
                        </div>
                        <span class="text-white/60 text-[10px]">0:<?= str_pad($msg['duration'] ?? 12, 2, '0') ?></span>
                    </div>
                    <?php else: ?>
                    <!-- Text Message -->
                    <div class="msg-bubble px-3.5 py-2.5">
                        <p class="text-[13px] leading-relaxed"><?= htmlspecialchars($msg['body'] ?? '') ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Timestamp & Status -->
                    <div class="flex items-center justify-end gap-1 mt-0.5 pr-1">
                        <span class="text-[9px] <?= $isMine ? 'text-purple-300/50' : 'text-zinc-600' ?>">
                            <?= isset($msg['created_at']) ? date('h:i A', strtotime($msg['created_at'])) : '' ?>
                        </span>
                        <?php if ($isMine): ?>
                        <span class="material-icons-round text-[#834ae5]/60" style="font-size: 11px;" id="status-<?= $msg['id'] ?? '' ?>">
                            <?= (!empty($msg['read_at']) ? 'done_all' : 'check') ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <!-- Empty Chat State -->
        <div class="flex items-center justify-center h-full">
            <div class="text-center px-6">
                <div class="w-16 h-16 mx-auto mb-3 rounded-full overflow-hidden" style="box-shadow: 0 0 0 2.5px rgba(131,74,229,0.4);">
                    <img src="<?= $otherAvatar ?>" alt="" class="w-full h-full object-cover">
                </div>
                <h3 class="text-white font-bold text-sm mb-0.5"><?= htmlspecialchars($otherName) ?></h3>
                <p class="text-zinc-500 text-xs mb-4">No messages yet. Say hello! 👋</p>
                <div class="flex flex-col gap-2 max-w-[200px] mx-auto">
                    <button onclick="quickReply('Hey! 👋')" class="px-4 py-2 rounded-full text-xs font-medium transition-colors" style="background: rgba(131,74,229,0.12); color: #c084fc; border: 1px solid rgba(131,74,229,0.2);">Hey! 👋</button>
                    <button onclick="quickReply('What\'s up?')" class="px-4 py-2 rounded-full text-xs font-medium transition-colors" style="background: rgba(131,74,229,0.12); color: #c084fc; border: 1px solid rgba(131,74,229,0.2);">What's up?</button>
                    <button onclick="quickReply('Love your content! 🔥')" class="px-4 py-2 rounded-full text-xs font-medium transition-colors" style="background: rgba(131,74,229,0.12); color: #c084fc; border: 1px solid rgba(131,74,229,0.2);">Love your content! 🔥</button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Typing Indicator (hidden) -->
        <div id="typingIndicator" class="hidden flex justify-start items-end gap-1.5">
            <div class="w-7 h-7 rounded-full overflow-hidden flex-shrink-0 mb-5">
                <img src="<?= $otherAvatar ?>" alt="" class="w-full h-full object-cover">
            </div>
            <div class="typing-bubble px-4 py-3">
                <div class="typing-dots"><span></span><span></span><span></span></div>
            </div>
        </div>
    </div>

    <!-- ===== REPLY PREVIEW BAR (hidden) ===== -->
    <div id="replyPreview" class="hidden flex-shrink-0 px-3 pt-2">
        <div class="reply-preview flex items-center gap-2 px-3 py-2 rounded-lg">
            <div class="flex-1 min-w-0">
                <p class="text-[10px] text-[#834ae5] font-semibold">Replying to</p>
                <p class="text-zinc-400 text-[11px] truncate" id="replyText"></p>
            </div>
            <button onclick="cancelReply()" class="p-1 rounded-full hover:bg-white/10 transition-colors">
                <span class="material-icons-round text-zinc-500 text-sm">close</span>
            </button>
        </div>
    </div>

    <!-- ===== ATTACHMENT MENU (hidden) ===== -->
    <div id="attachMenu" class="hidden flex-shrink-0 px-3 pt-2 pb-2 slide-up">
        <div class="flex items-center gap-3 justify-around bg-[#14141c] rounded-2xl p-3 border border-[#1e1e2a]">
            <button onclick="attachImage()" class="attach-item flex flex-col items-center gap-1">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: rgba(131,74,229,0.15);">
                    <span class="material-icons-round text-[#834ae5] text-xl">photo</span>
                </div>
                <span class="text-[9px] text-zinc-400 font-medium">Photo</span>
            </button>
            <button onclick="attachVoice()" class="attach-item flex flex-col items-center gap-1">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: rgba(239,68,68,0.12);">
                    <span class="material-icons-round text-red-400 text-xl">mic</span>
                </div>
                <span class="text-[9px] text-zinc-400 font-medium">Voice</span>
            </button>
            <button onclick="attachFile()" class="attach-item flex flex-col items-center gap-1">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: rgba(59,130,246,0.12);">
                    <span class="material-icons-round text-blue-400 text-xl">attach_file</span>
                </div>
                <span class="text-[9px] text-zinc-400 font-medium">File</span>
            </button>
            <button onclick="attachLocation()" class="attach-item flex flex-col items-center gap-1">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: rgba(16,185,129,0.12);">
                    <span class="material-icons-round text-emerald-400 text-xl">location_on</span>
                </div>
                <span class="text-[9px] text-zinc-400 font-medium">Location</span>
            </button>
            <button onclick="attachContact()" class="attach-item flex flex-col items-center gap-1">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: rgba(251,146,60,0.12);">
                    <span class="material-icons-round text-amber-400 text-xl">contact_page</span>
                </div>
                <span class="text-[9px] text-zinc-400 font-medium">Contact</span>
            </button>
        </div>
    </div>

    <!-- ===== EMOJI PICKER (hidden) ===== -->
    <div id="emojiPicker" class="hidden flex-shrink-0 px-3 pt-2 pb-2 slide-up">
        <div class="bg-[#14141c] rounded-2xl p-3 border border-[#1e1e2a]">
            <div class="flex items-center gap-2 mb-2 overflow-x-auto scrollbar-hide">
                <?php foreach (['😀','😍','😂','🔥','❤️','👏','💯','🎉','😍','🤩','😎','✨','🙏','💪','🎵','🌟','💐','🥰','🤯','💀','😏','🥺','😭','🤝','👑'] as $cat): ?>
                <button onclick="insertEmoji('<?= $cat ?>')" class="flex-shrink-0 text-xl p-1 rounded-lg hover:bg-white/10 transition-colors"><?= $cat ?></button>
                <?php endforeach; ?>
            </div>
            <div class="h-px bg-[#1e1e2a] mb-2"></div>
            <div class="emoji-grid">
                <?php foreach (['😀','😃','😄','😁','😆','😅','🤣','😂','🙂','😊','😇','🥰','😍','🤩','😘','😗','😚','😙','🥲','😋','😛','😜','🤪','😝','🤑','🤗','🤭','🫢','🫣','🤫','🤔','🫡','🤐','🤨','😐','😑','😶','🫥','😏','😒','🙄','😬','🤥','😌','😔','😪','🤤','😴','😷','🤒','🤕','🤢','🤮','🥴','😵','🤯','🥵','🥶','🥶','😱','😨','😰','😥','😢','😭','😤','😠','😡','🤬','😈','👿','💀','☠️','💩','🤡','👹','👺','👻','👽','👾','🤖','❤️','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','❤️‍🔥','💕','💞','💓','💗','💖','💝','💘','💟','👋','🤚','🖐️','✋','🖖','🫱','🫲','👌','🤌','🤏','✌️','🤞','🫰','🤟','🤘','🤙','👈','👉','👆','🖕','👇','☝️','🫵','👍','👎','✊','👊','🤛','🤜','👏','🙌','🫶','👐','🤲','🤝','🙏','✍️','💅','🤳','💪','🦾','🔥','⭐','🌟','✨','💥','💫','💦','💨','🕳️','💣','💬','💭','🗯️','🎵','🎶','🎤','🎧','🎸','🎹','🥁','🎺','🎷','🪗','🎻','🎮','🕹️','🎲','♟️','🎯','🎳','🎰','🧩','🚗','🚕','🚙','🚌','🏎️','🚓','🚑','🚒','🚐','🛻','🚚','🚛','✈️','🚀','🛸','🚁','⚓','🛥️','🚤','⛴️','🎁','🎂','🎄','🎃','🧧','🎀','🎉','🎊','🎎','🏮','🎐','🧨','✨','🎈','🎈'] as $em): ?>
                <button onclick="insertEmoji('<?= $em ?>')" class="emoji-btn"><?= $em ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ===== MESSAGE INPUT ===== -->
    <?php if ($conversation): ?>
    <div class="flex items-center gap-2 px-3 py-2.5 flex-shrink-0" style="border-top: 1px solid rgba(20,20,28,0.8); background: rgba(9,12,21,0.95); backdrop-filter: blur(20px);">
        <!-- Attach Button -->
        <button onclick="toggleAttachMenu()" class="w-10 h-10 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center flex-shrink-0 hover:bg-[#1e1e2a] transition-colors" id="attachBtn">
            <span class="material-icons-round text-zinc-400 text-lg">add_circle_outline</span>
        </button>

        <!-- Input -->
        <div class="flex-1 relative">
            <input type="text" id="messageInput" placeholder="Type a message..." class="chat-input w-full text-white px-4 py-2.5 text-sm placeholder:text-zinc-600 transition-all" onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault(); sendMessage();}" oninput="toggleSendBtn()">
        </div>

        <!-- Emoji Button -->
        <button onclick="toggleEmojiPicker()" class="w-10 h-10 rounded-full bg-[#14141c] border border-[#1e1e2a] flex items-center justify-center flex-shrink-0 hover:bg-[#1e1e2a] transition-colors" id="emojiBtn">
            <span class="material-icons-round text-zinc-400 text-lg">sentiment_satisfied_alt</span>
        </button>

        <!-- Send / Voice Button -->
        <button onclick="sendMessage()" id="sendBtn" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 transition-all opacity-50 cursor-not-allowed" style="background: linear-gradient(135deg, #834ae5, #6b21a8);">
            <span class="material-icons-round text-white text-lg" id="sendIcon">mic</span>
        </button>
    </div>
    <?php endif; ?>
</div>

<!-- ===== IMAGE VIEWER OVERLAY ===== -->
<div id="imageViewer" class="hidden fixed inset-0 z-[100] bg-black/95 flex items-center justify-center image-viewer" onclick="closeImageViewer()">
    <button class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors z-10">
        <span class="material-icons-round text-white text-xl">close</span>
    </button>
    <img id="viewerImage" src="" alt="" class="max-w-[95%] max-h-[85vh] object-contain rounded-lg">
</div>

<!-- Hidden file input for image upload -->
<input type="file" id="imageUpload" accept="image/*" class="hidden" onchange="handleImageUpload(this)">

<script>
const CONVERSATION_ID = <?= $data['conversation']['id'] ?? 0 ?>;
const USER_ID = <?= $userId ?>;
let replyTo = null;

// ===================== SEND MESSAGE =====================
async function sendMessage() {
    const input = document.getElementById('messageInput');
    const body = input.value.trim();
    if (!body || !CONVERSATION_ID) return;
    input.value = '';
    toggleSendBtn();

    // Optimistic render
    appendMessage(body, 'text', true);
    scrollToBottom();

    try {
        await fetch('/messages/' + CONVERSATION_ID + '/send', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ body: body })
        });
    } catch (e) {
        showToast('Message not sent. Try again.');
    }

    cancelReply();
}

function appendMessage(body, type, isMine) {
    const chatEl = document.getElementById('chatMessages');
    const typing = document.getElementById('typingIndicator');
    const now = new Date();
    const timeStr = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });

    let bubbleHTML = '';
    if (type === 'image') {
        bubbleHTML = `<div class="msg-image"><img src="${body}" alt="Image" onclick="viewImage('${body}')"></div>`;
    } else if (type === 'voice') {
        bubbleHTML = `
            <div class="msg-bubble px-3 py-2.5 flex items-center gap-2.5 min-w-[200px]" style="background: linear-gradient(135deg, #834ae5, #6b21a8);">
                <button class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(255,255,255,0.15);"><span class="material-icons-round text-white text-sm">play_arrow</span></button>
                <div class="flex-1"><div class="voice-wave"><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div></div>
                <span class="text-white/60 text-[10px]">0:12</span>
            </div>`;
    } else {
        bubbleHTML = `<div class="msg-bubble px-3.5 py-2.5"><p class="text-[13px] leading-relaxed">${escapeHtml(body)}</p></div>`;
    }

    const wrapper = document.createElement('div');
    wrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'} items-end gap-1.5`;
    wrapper.innerHTML = `
        <div class="max-w-[78%]">
            ${bubbleHTML}
            <div class="flex items-center justify-end gap-1 mt-0.5 pr-1">
                <span class="text-[9px] text-purple-300/50">${timeStr}</span>
                <span class="material-icons-round text-[#834ae5]/60" style="font-size: 11px;">check</span>
            </div>
        </div>
    `;
    chatEl.insertBefore(wrapper, typing);
}

function quickReply(text) {
    const input = document.getElementById('messageInput');
    if (input) { input.value = text; toggleSendBtn(); input.focus(); }
}

function toggleSendBtn() {
    const input = document.getElementById('messageInput');
    const btn = document.getElementById('sendBtn');
    const icon = document.getElementById('sendIcon');
    if (!input || !btn || !icon) return;
    const hasText = input.value.trim().length > 0;
    btn.style.opacity = hasText ? '1' : '0.5';
    btn.classList.toggle('cursor-not-allowed', !hasText);
    icon.textContent = hasText ? 'send' : 'mic';
}

// ===================== EMOJI =====================
function toggleEmojiPicker() {
    const picker = document.getElementById('emojiPicker');
    const attach = document.getElementById('attachMenu');
    attach.classList.add('hidden');
    picker.classList.toggle('hidden');
    if (!picker.classList.contains('hidden')) {
        document.getElementById('messageInput').focus();
    }
}

function insertEmoji(emoji) {
    const input = document.getElementById('messageInput');
    input.value += emoji;
    input.focus();
    toggleSendBtn();
}

// ===================== ATTACHMENTS =====================
function toggleAttachMenu() {
    const attach = document.getElementById('attachMenu');
    const emoji = document.getElementById('emojiPicker');
    emoji.classList.add('hidden');
    attach.classList.toggle('hidden');
}

function attachImage() {
    document.getElementById('imageUpload').click();
    toggleAttachMenu();
}

function handleImageUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = async function(e) {
            appendMessage(e.target.result, 'image', true);
            scrollToBottom();
            showToast('Image sent! 📸');
        };
        reader.readAsDataURL(file);
        input.value = '';
    }
}

function attachVoice() {
    toggleAttachMenu();
    showToast('Hold to record voice message 🎙️');
}

function attachFile() { toggleAttachMenu(); showToast('File attachment coming soon 📎'); }
function attachLocation() { toggleAttachMenu(); showToast('Location sharing coming soon 📍'); }
function attachContact() { toggleAttachMenu(); showToast('Contact sharing coming soon 👤'); }

// ===================== REPLY =====================
function setReply(text) {
    replyTo = text;
    document.getElementById('replyPreview').classList.remove('hidden');
    document.getElementById('replyText').textContent = text;
    document.getElementById('messageInput').focus();
}

function cancelReply() {
    replyTo = null;
    document.getElementById('replyPreview').classList.add('hidden');
}

// ===================== IMAGE VIEWER =====================
function viewImage(url) {
    document.getElementById('viewerImage').src = url;
    document.getElementById('imageViewer').classList.remove('hidden');
}

function closeImageViewer() {
    document.getElementById('imageViewer').classList.add('hidden');
}

// ===================== CHAT MENU =====================
function toggleChatMenu() { document.getElementById('chatMenu').classList.toggle('hidden'); }
function closeChatMenu() { document.getElementById('chatMenu').classList.add('hidden'); }

function viewProfile() { showToast('Profile view coming soon'); }
function showProfileSheet() { showToast('Tap for profile details'); }
function toggleMute() { showToast('Notifications muted 🔕'); }
function searchInChat() { showToast('Search in chat coming soon 🔍'); }
function clearChat() { if (confirm('Clear all messages?')) showToast('Chat cleared'); }
function blockUser() { if (confirm('Block this user?')) showToast('User blocked'); }
function startVoiceCall() { showToast('Voice call starting... 📞'); }
function startVideoCall() { showToast('Video call starting... 📹'); }

// ===================== UTILS =====================
function escapeHtml(text) {
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
}

function scrollToBottom() {
    const chatEl = document.getElementById('chatMessages');
    if (chatEl) chatEl.scrollTop = chatEl.scrollHeight;
}

function showToast(msg) {
    const existing = document.querySelector('.chat-toast');
    if (existing) existing.remove();
    const div = document.createElement('div');
    div.className = 'chat-toast fixed top-4 left-1/2 -translate-x-1/2 px-5 py-2.5 rounded-full text-white text-sm font-medium z-[200] max-w-[90%] text-center';
    div.style.cssText = 'background: linear-gradient(135deg, #834ae5, #6b21a8); box-shadow: 0 4px 20px rgba(131,74,229,0.4);';
    div.textContent = msg;
    document.body.appendChild(div);
    setTimeout(() => { div.style.opacity = '0'; div.style.transition = 'opacity 0.3s'; setTimeout(() => div.remove(), 300); }, 2000);
}

// Close menus on outside click
document.addEventListener('click', (e) => {
    if (!e.target.closest('#chatMenu') && !e.target.closest('[onclick*="toggleChatMenu"]')) {
        document.getElementById('chatMenu')?.classList.add('hidden');
    }
});

// ===================== POLLING FOR NEW MESSAGES =====================
let lastMsgCount = <?= count($messages) ?>;
setInterval(async () => {
    if (!CONVERSATION_ID) return;
    try {
        const r = await fetch('/messages/' + CONVERSATION_ID + '/poll', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const d = await r.json();
        if (d.length > lastMsgCount) {
            location.reload();
        }
    } catch(e) {}
}, 5000);

// Show/hide typing indicator simulation
// (In production, this would come from WebSocket/polling)
setInterval(() => {
    const typing = document.getElementById('typingIndicator');
    if (typing) {
        typing.classList.add('hidden');
    }
}, 10000);

// ===================== AUTO-SCROLL ON LOAD =====================
document.addEventListener('DOMContentLoaded', () => {
    scrollToBottom();
});
</script>

<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
