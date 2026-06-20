<?php
$title = 'Checkout — Marketplace';
$activeTab = 'marketplace';
$hideTopNav = true;
$items = $data['items'] ?? [];
$subtotal = $data['subtotal'] ?? 0;
$shippingCost = $data['shippingCost'] ?? 0;
$tax = $data['tax'] ?? 0;
$total = $data['total'] ?? 0;
$paymentMethods = $data['paymentMethods'] ?? [];
$user = $data['user'] ?? [];
?>
<?php ob_start(); ?>
<style>
    :root { --bg-deep: #0B1120; --bg-card: #151D2E; --bg-surface: #1E293B; --purple: #8B5CF6; --text-primary: #FFFFFF; --text-secondary: #94A3B8; --border: rgba(255,255,255,0.06); }
    body { background: var(--bg-deep); font-family: 'Inter', sans-serif; color: var(--text-primary); }
    .co-page { max-width: 480px; margin: 0 auto; padding: 0 16px 120px; }
    .co-header { display: flex; align-items: center; gap: 12px; padding: 48px 0 20px; position: sticky; top: 0; background: var(--bg-deep); z-index: 50; }
    .co-back { width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,0.05); border: none; color: #94A3B8; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .co-title { font-size: 20px; font-weight: 700; flex: 1; }
    .co-step { font-size: 11px; color: var(--purple); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
    .co-section { background: var(--bg-card); border-radius: 16px; padding: 18px; margin-bottom: 12px; border: 1px solid var(--border); }
    .co-section h3 { font-size: 15px; font-weight: 700; margin: 0 0 14px; display: flex; align-items: center; gap: 8px; }
    .co-field { margin-bottom: 12px; }
    .co-field label { display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 4px; font-weight: 500; }
    .co-field input, .co-field textarea, .co-field select { width: 100%; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 10px 12px; color: white; font-size: 14px; font-family: 'Inter', sans-serif; outline: none; box-sizing: border-box; transition: border-color 0.2s; }
    .co-field input:focus, .co-field textarea:focus { border-color: var(--purple); }
    .co-field textarea { resize: vertical; min-height: 60px; }
    .co-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .co-toggle { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; cursor: pointer; }
    .co-toggle-switch { width: 44px; height: 24px; background: rgba(255,255,255,0.15); border-radius: 12px; position: relative; transition: background 0.2s; }
    .co-toggle-switch.active { background: var(--purple); }
    .co-toggle-switch::after { content: ''; width: 20px; height: 20px; background: white; border-radius: 50%; position: absolute; top: 2px; left: 2px; transition: transform 0.2s; }
    .co-toggle-switch.active::after { transform: translateX(20px); }
    .co-toggle-label { font-size: 13px; color: var(--text-secondary); }
    .co-pm-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 2px solid rgba(255,255,255,0.06); border-radius: 12px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s; }
    .co-pm-item:hover { border-color: rgba(139,92,246,0.3); }
    .co-pm-item.selected { border-color: var(--purple); background: rgba(139,92,246,0.08); }
    .co-pm-radio { width: 20px; height: 20px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .co-pm-item.selected .co-pm-radio { border-color: var(--purple); }
    .co-pm-item.selected .co-pm-radio::after { content: ''; width: 10px; height: 10px; background: var(--purple); border-radius: 50%; }
    .co-pm-icon { font-size: 24px; color: var(--purple); }
    .co-pm-name { font-size: 14px; font-weight: 600; flex: 1; }
    .co-pm-desc { font-size: 11px; color: var(--text-secondary); }
    .co-summary-item { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid var(--border); }
    .co-summary-item:last-child { border-bottom: none; }
    .co-summary-img { width: 44px; height: 44px; border-radius: 8px; object-fit: cover; flex-shrink: 0; }
    .co-summary-info { flex: 1; min-width: 0; }
    .co-summary-title { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .co-summary-qty { font-size: 11px; color: var(--text-secondary); }
    .co-summary-price { font-size: 14px; font-weight: 700; color: var(--purple); }
    .co-total-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; color: var(--text-secondary); }
    .co-total-row.grand { border-top: 1px solid var(--border); padding-top: 14px; margin-top: 6px; font-size: 18px; font-weight: 700; color: white; }
    .co-place-btn { background: linear-gradient(135deg, #8B5CF6, #A78BFA); color: white; border: none; border-radius: 14px; padding: 16px; font-size: 16px; font-weight: 700; width: 100%; cursor: pointer; margin-top: 12px; display: flex; align-items: center; justify-content: center; gap: 8px; transition: opacity 0.2s; }
    .co-place-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .co-place-btn:hover:not(:disabled) { opacity: 0.9; }
    .toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%); padding: 10px 24px; border-radius: 24px; font-weight: 600; z-index: 999; font-size: 14px; animation: fadeInUp 0.3s ease; }
    @keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, 10px); } to { opacity: 1; transform: translate(-50%, 0); } }
    .co-required { color: #EF4444; }

    /* Payment Modal */
    .pm-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.75); backdrop-filter: blur(6px); z-index: 999; display: flex; align-items: center; justify-content: center; padding: 20px; opacity: 0; pointer-events: none; transition: opacity 0.3s; }
    .pm-overlay.active { opacity: 1; pointer-events: all; }
    .pm-modal { background: var(--bg-card); border-radius: 20px; padding: 28px 24px 24px; max-width: 400px; width: 100%; text-align: center; position: relative; border: 1px solid rgba(139,92,246,0.2); box-shadow: 0 20px 60px rgba(0,0,0,0.5); animation: pmSlideUp 0.3s ease; }
    @keyframes pmSlideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .pm-close { position: absolute; top: 12px; right: 12px; width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.06); border: none; color: #94A3B8; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; }
    .pm-close:hover { background: rgba(255,255,255,0.12); }
    .pm-icon-wrap { width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, rgba(139,92,246,0.2), rgba(167,139,250,0.1)); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
    .pm-icon-wrap span { font-size: 30px; color: var(--purple); }
    .pm-title { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
    .pm-subtitle { font-size: 13px; color: var(--text-secondary); margin-bottom: 18px; line-height: 1.5; }
    .pm-details { background: var(--bg-surface); border-radius: 12px; padding: 14px 16px; margin-bottom: 18px; text-align: left; }
    .pm-detail-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; border-bottom: 1px solid rgba(255,255,255,0.04); }
    .pm-detail-row:last-child { border-bottom: none; }
    .pm-detail-row span:first-child { color: var(--text-secondary); }
    .pm-detail-row span:last-child { font-weight: 600; }
    .pm-phone-group { text-align: left; }
    .pm-phone-group label { display: block; font-size: 12px; color: var(--text-secondary); margin-bottom: 6px; font-weight: 500; }
    .pm-phone-input-wrap { display: flex; align-items: center; background: var(--bg-surface); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden; margin-bottom: 12px; }
    .pm-country-code { padding: 12px 14px; color: var(--text-secondary); font-size: 14px; font-weight: 600; border-right: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03); }
    .pm-phone-input-wrap input { flex: 1; background: transparent; border: none; outline: none; color: white; font-size: 16px; padding: 12px 14px; font-family: 'Inter', sans-serif; }
    .pm-phone-input-wrap input:focus { border: none; }
    .pm-submit-btn { background: linear-gradient(135deg, #22C55E, #16A34A); color: white; border: none; border-radius: 12px; padding: 13px; font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 12px; }
    .pm-redirect-btn { background: linear-gradient(135deg, #8B5CF6, #A78BFA); color: white; border: none; border-radius: 12px; padding: 13px; font-size: 14px; font-weight: 700; width: 100%; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 10px; }
    .pm-cancel-btn { background: transparent; border: 1px solid rgba(255,255,255,0.1); color: var(--text-secondary); border-radius: 10px; padding: 10px; font-size: 13px; font-weight: 600; width: 100%; cursor: pointer; }
    .pm-cancel-btn:hover { background: rgba(255,255,255,0.04); }
    .pm-status { text-align: center; padding: 20px 0; display: flex; flex-direction: column; align-items: center; gap: 10px; }
    .pm-status p { color: white; font-size: 14px; font-weight: 600; margin: 0; }
</style>

<div class="co-page">
    <div class="co-header">
        <button class="co-back" onclick="history.back()"><span class="material-icons-round">arrow_back</span></button>
        <span class="co-title">Checkout</span>
        <span style="font-size:12px;color:var(--text-secondary);"><?= count($items) ?> items</span>
    </div>

    <form id="checkout-form" onsubmit="return false;">
        <!-- Billing Address -->
        <div class="co-section">
            <div class="co-step">Step 1</div>
            <h3><span class="material-icons-round" style="color:var(--purple);">receipt_long</span> Billing Address</h3>
            <div class="co-field">
                <label>Full Name <span class="co-required">*</span></label>
                <input type="text" name="billing_name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" placeholder="John Doe" required>
            </div>
            <div class="co-field">
                <label>Email <span class="co-required">*</span></label>
                <input type="email" name="billing_email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="john@example.com" required>
            </div>
            <div class="co-field">
                <label>Phone <span class="co-required">*</span></label>
                <input type="tel" name="billing_phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+254 712 345 678" required>
            </div>
            <div class="co-field">
                <label>Address <span class="co-required">*</span></label>
                <textarea name="billing_address" placeholder="Street address, apartment, suite" required></textarea>
            </div>
            <div class="co-row">
                <div class="co-field">
                    <label>City <span class="co-required">*</span></label>
                    <input type="text" name="billing_city" placeholder="Nairobi" required>
                </div>
                <div class="co-field">
                    <label>State/Region</label>
                    <input type="text" name="billing_state" placeholder="Nairobi">
                </div>
            </div>
            <div class="co-row">
                <div class="co-field">
                    <label>ZIP Code</label>
                    <input type="text" name="billing_zip" placeholder="00100">
                </div>
                <div class="co-field">
                    <label>Country <span class="co-required">*</span></label>
                    <select name="billing_country" required>
                        <option value="Kenya">Kenya</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Ghana">Ghana</option>
                        <option value="South Africa">South Africa</option>
                        <option value="United States">United States</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Delivery Address -->
        <div class="co-section">
            <div class="co-step">Step 2</div>
            <h3><span class="material-icons-round" style="color:var(--purple);">local_shipping</span> Delivery Address</h3>
            <div class="co-toggle" onclick="toggleDelivery()">
                <div class="co-toggle-switch active" id="same-toggle"></div>
                <span class="co-toggle-label">Same as billing address</span>
            </div>
            <div id="delivery-fields" style="display: none;">
                <div class="co-field">
                    <label>Recipient Name</label>
                    <input type="text" name="delivery_name" placeholder="Jane Doe">
                </div>
                <div class="co-field">
                    <label>Phone</label>
                    <input type="tel" name="delivery_phone" placeholder="+254 712 345 678">
                </div>
                <div class="co-field">
                    <label>Address</label>
                    <textarea name="delivery_address" placeholder="Street address, apartment, suite"></textarea>
                </div>
                <div class="co-row">
                    <div class="co-field">
                        <label>City</label>
                        <input type="text" name="delivery_city" placeholder="Nairobi">
                    </div>
                    <div class="co-field">
                        <label>State</label>
                        <input type="text" name="delivery_state" placeholder="Nairobi">
                    </div>
                </div>
                <div class="co-row">
                    <div class="co-field">
                        <label>ZIP</label>
                        <input type="text" name="delivery_zip" placeholder="00100">
                    </div>
                    <div class="co-field">
                        <label>Country</label>
                        <select name="delivery_country">
                            <option value="">Same as billing</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Uganda">Uganda</option>
                            <option value="Tanzania">Tanzania</option>
                            <option value="Rwanda">Rwanda</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Ghana">Ghana</option>
                            <option value="South Africa">South Africa</option>
                            <option value="United States">United States</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="co-field">
                    <label>Delivery Instructions</label>
                    <textarea name="delivery_instructions" placeholder="Gate code, floor number, landmarks..."></textarea>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="co-section">
            <div class="co-step">Step 3</div>
            <h3><span class="material-icons-round" style="color:var(--purple);">payments</span> Payment Method</h3>
            <?php if (empty($paymentMethods)): ?>
            <p style="color:var(--text-secondary);font-size:13px;text-align:center;padding:16px;">No payment methods available. Please contact support.</p>
            <?php else: ?>
            <input type="hidden" name="payment_method" id="selected-payment" value="<?= $paymentMethods[0]['slug'] ?? '' ?>">
            <?php foreach ($paymentMethods as $pm): ?>
            <div class="co-pm-item <?= $pm === reset($paymentMethods) ? 'selected' : '' ?>" data-slug="<?= $pm['slug'] ?>" onclick="selectPayment(this, '<?= $pm['slug'] ?>')">
                <div class="co-pm-radio"></div>
                <span class="material-icons-round co-pm-icon"><?= $pm['icon'] ?? 'payments' ?></span>
                <div>
                    <div class="co-pm-name"><?= htmlspecialchars($pm['display_name']) ?></div>
                    <div class="co-pm-desc"><?= htmlspecialchars($pm['description'] ?? '') ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <div class="co-section">
            <h3><span class="material-icons-round" style="color:var(--purple);">shopping_bag</span> Order Summary</h3>
            <?php foreach ($items as $item): ?>
            <div class="co-summary-item">
                <img src="<?= $item['image_url'] ?? '/uploads/profiles/admin.jpg' ?>" alt="" class="co-summary-img">
                <div class="co-summary-info">
                    <div class="co-summary-title"><?= htmlspecialchars($item['title']) ?></div>
                    <div class="co-summary-qty">Qty: <?= $item['quantity'] ?></div>
                </div>
                <div class="co-summary-price">$<?= number_format((float)$item['price'] * (int)$item['quantity'], 0) ?></div>
            </div>
            <?php endforeach; ?>
            <div style="margin-top:12px;">
                <div class="co-total-row"><span>Subtotal</span><span>$<?= number_format($subtotal, 0) ?></span></div>
                <div class="co-total-row"><span>Shipping</span><span><?= $shippingCost > 0 ? '$' . number_format($shippingCost, 0) : 'Free' ?></span></div>
                <div class="co-total-row"><span>Tax (VAT 16%)</span><span>$<?= number_format($tax, 0) ?></span></div>
                <div class="co-total-row grand"><span>Total</span><span>$<?= number_format($total, 0) ?></span></div>
            </div>
        </div>

        <button type="submit" class="co-place-btn" id="place-order-btn" onclick="placeOrder()">
            <span class="material-icons-round">lock</span>
            Place Order — $<?= number_format($total, 0) ?>
        </button>
    </form>

    <!-- Payment Modal -->
    <div class="pm-overlay" id="payment-modal">
        <div class="pm-modal">
            <button class="pm-close" onclick="closePaymentModal()"><span class="material-icons-round">close</span></button>
            <div class="pm-icon-wrap">
                <span class="material-icons-round">lock</span>
            </div>
            <h2 class="pm-title">Confirm Payment</h2>
            <p class="pm-subtitle" id="pm-message">Complete your payment to finalize the order.</p>

            <div class="pm-details">
                <div class="pm-detail-row"><span>Order</span><span id="pm-order-number">—</span></div>
                <div class="pm-detail-row"><span>Gateway</span><span id="pm-gateway-name">—</span></div>
                <div class="pm-detail-row"><span>Total</span><span id="pm-total">—</span></div>
            </div>

            <!-- M-Pesa specific -->
            <div id="pm-mpesa-block" style="display:none;">
                <div class="pm-phone-group">
                    <label>M-Pesa Phone Number</label>
                    <div class="pm-phone-input-wrap">
                        <span class="pm-country-code">+254</span>
                        <input type="tel" id="pm-phone-input" placeholder="712 345 678" value="<?= htmlspecialchars(substr($user['phone'] ?? '', -9)) ?>">
                    </div>
                    <button class="pm-submit-btn" onclick="submitMpesaPayment()">
                        <span class="material-icons-round">send</span> Send STK Push
                    </button>
                </div>
                <div class="pm-status" id="pm-mpesa-status" style="display:none;"></div>
            </div>

            <!-- Redirect button for other gateways -->
            <button class="pm-redirect-btn" id="pm-redirect-btn" onclick="proceedToGateway()">
                <span class="material-icons-round">open_in_new</span> Proceed to Payment
            </button>

            <button class="pm-cancel-btn" onclick="closePaymentModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
var sameAsBilling = true;
function toggleDelivery() {
    sameAsBilling = !sameAsBilling;
    var t = document.getElementById('same-toggle');
    var f = document.getElementById('delivery-fields');
    if (sameAsBilling) { t.classList.add('active'); f.style.display = 'none'; }
    else { t.classList.remove('active'); f.style.display = 'block'; }
}

function selectPayment(el, slug) {
    document.querySelectorAll('.co-pm-item').forEach(function(i) { i.classList.remove('selected'); });
    el.classList.add('selected');
    document.getElementById('selected-payment').value = slug;
}

function placeOrder() {
    var btn = document.getElementById('place-order-btn');
    if (btn.disabled) return;
    btn.disabled = true;
    btn.innerHTML = '<span class="material-icons-round" style="animation:spin 0.8s linear infinite;">sync</span> Processing...';

    var form = document.getElementById('checkout-form');
    var fd = new FormData(form);
    fd.append('same_as_billing', sameAsBilling ? '1' : '0');

    var data = {};
    fd.forEach(function(v, k) { data[k] = v; });

    fetch('/marketplace/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(data)
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.error) { showToast(d.error, true); btn.disabled = false; btn.innerHTML = '<span class="material-icons-round">lock</span> Place Order — $<?= number_format($total, 0) ?>'; return; }
        if (d.success) {
            showToast(d.message, false);
            setTimeout(function() {
                showPaymentModal(d);
            }, 800);
        }
    })
    .catch(function(err) {
        showToast('Network error. Please try again.', true);
        btn.disabled = false;
        btn.innerHTML = '<span class="material-icons-round">lock</span> Place Order — $<?= number_format($total, 0) ?>';
    });
}

// Payment modal handlers
function showPaymentModal(d) {
    var modal = document.getElementById('payment-modal');
    var gateway = d.payment_method;
    document.getElementById('pm-gateway-name').textContent = gateway.charAt(0).toUpperCase() + gateway.slice(1);
    document.getElementById('pm-order-number').textContent = d.order_number;
    document.getElementById('pm-total').textContent = '$' + Number(d.total).toLocaleString();

    var mpesaBlock = document.getElementById('pm-mpesa-block');
    mpesaBlock.style.display = gateway === 'mpesa' ? 'block' : 'none';

    if (gateway !== 'mpesa') {
        document.getElementById('pm-message').textContent = 'You will be redirected to ' + gateway.charAt(0).toUpperCase() + gateway.slice(1) + ' to complete your payment.';
        document.getElementById('pm-redirect-btn').style.display = 'flex';
    } else {
        document.getElementById('pm-message').textContent = 'Enter your M-Pesa phone number to receive the STK push.';
        document.getElementById('pm-redirect-btn').style.display = 'none';
    }

    modal.classList.add('active');
    // Store order data
    modal._orderData = d;
}

function closePaymentModal() {
    document.getElementById('payment-modal').classList.remove('active');
}

function submitMpesaPayment() {
    var phone = document.getElementById('pm-phone-input').value.replace(/[^0-9]/g, '');
    // Quick local validation
    if (!phone || phone.length < 9) { showToast('Enter a valid Kenyan phone number', true); return; }
    if (!/^(0?7|2547|\+2547)/.test(phone) && !/^7/.test(phone)) { showToast('Must be a Kenyan number starting with 07 or 2547', true); return; }

    var statusEl = document.getElementById('pm-mpesa-status');
    var phoneGroup = document.getElementById('pm-mpesa-block').querySelector('.pm-phone-group');
    phoneGroup.style.display = 'none';
    statusEl.style.display = 'flex';
    statusEl.innerHTML = '<span class="material-icons-round" style="font-size:40px;animation:spin 1s linear infinite;">sync</span><p>Sending STK Push...</p>';

    var d = document.getElementById('payment-modal')._orderData;
    fetch('/marketplace/checkout/mpesa', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ phone: phone, order_number: d.order_number, amount: d.total })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.error) {
            statusEl.innerHTML = '<span class="material-icons-round" style="font-size:40px;color:#EF4444;">error</span><p style="color:#EF4444;">' + res.error + '</p>';
            phoneGroup.style.display = 'block';
            return;
        }
        statusEl.innerHTML = '<span class="material-icons-round" style="font-size:48px;color:#22C55E;">check_circle</span><p>STK Push sent!</p><p style="font-size:12px;color:#94A3B8;">Check <strong>' + (res.phone || phone) + '</strong> and enter your PIN</p>';
        setTimeout(function() {
            window.location.href = '/marketplace/checkout/success?order=' + d.order_number;
        }, 6000);
    })
    .catch(function(err) {
        statusEl.innerHTML = '<span class="material-icons-round" style="font-size:40px;color:#EF4444;">error</span><p style="color:#EF4444;">Network error. Try again.</p>';
        phoneGroup.style.display = 'block';
    });
}

function proceedToGateway() {
    var d = document.getElementById('payment-modal')._orderData;
    window.location.href = d.redirect || '/marketplace/checkout/success?order=' + d.order_number;
}

function showToast(msg, isError) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.style.background = isError ? '#EF4444' : '#22C55E';
    t.style.color = 'white';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function() { t.remove(); }, 3000);
}
</script>
<style>@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }</style>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/app.php'; ?>
