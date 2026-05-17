<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; }
    .checkout-wrapper {
        display: flex;
        flex-wrap: wrap;
        max-width: 1300px;
        margin: 3rem auto;
        padding: 0 1.5rem;
        gap: 3rem;
        min-height: 70vh;
    }

    /* LEFT: Form */
    .checkout-left { flex: 1.4; min-width: 400px; }
    .checkout-logo {
        font-family: 'Lora', serif; font-size: 1.6rem; font-weight: 600; color: #1a1a1a;
        text-decoration: none; display: block; margin-bottom: 2.5rem;
    }
    .checkout-logo:hover { color: #8c7b6c; text-decoration: none; }

    .section-heading {
        font-family: 'Lora', serif; font-size: 1.3rem; font-weight: 600; color: #1a1a1a;
        margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;
        padding-bottom: 0.75rem; border-bottom: 1px solid #f0ebe3;
    }
    .section-heading .login-link { font-size: 0.85rem; color: #8c7b6c; text-decoration: none; font-weight: 500; }
    .section-heading .login-link:hover { text-decoration: underline; }

    .form-floating { position: relative; margin-bottom: 1.25rem; }
    .form-floating input, .form-floating select {
        width: 100%; height: 58px;
        padding: 1.625rem 1rem 0.625rem;
        border: 1px solid #e5e7eb; border-radius: 12px;
        font-size: 1rem; background: #fff; color: #111827;
        transition: all 0.2s ease-in-out; outline: none;
        box-sizing: border-box; line-height: 1.25;
    }
    .form-floating input:focus, .form-floating select:focus { border-color: #8c7b6c; box-shadow: 0 0 0 4px rgba(140, 123, 108, 0.08); }
    .form-floating label {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        padding: 1rem; color: #9ca3af; pointer-events: none;
        transition: transform .2s ease-out, color .2s ease-out;
        transform-origin: 0 0; box-sizing: border-box;
    }
    .form-floating input:focus + label, .form-floating input:not(:placeholder-shown) + label,
    .form-floating select:focus + label, .form-floating select:not(:placeholder-shown) + label {
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem); color: #8c7b6c;
    }

    /* Ship method */
    .ship-option {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.1rem 1.25rem; border: 1px solid #e5e7eb; border-radius: 12px;
        margin-bottom: 0.75rem; cursor: pointer; transition: all 0.3s; background: #fff;
    }
    .ship-option:hover { border-color: #d1d5db; }
    .ship-option.selected { border-color: #8c7b6c; background: #faf9f8; box-shadow: 0 0 0 2px rgba(140, 123, 108, 0.15); }
    .ship-option i { color: #8c7b6c; margin-right: 10px; }

    .btn-pay {
        width: 100%; background: #1a1a1a; color: #fff; border: none; padding: 1.2rem; border-radius: 12px;
        font-size: 1.05rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; cursor: pointer;
        transition: all 0.3s; margin-top: 2rem;
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-pay:hover { background: #8c7b6c; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(140, 123, 108, 0.3); }

    .news-check { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem; }
    .news-check input[type="checkbox"] { accent-color: #8c7b6c; }

    /* RIGHT: Order Summary */
    .checkout-right {
        flex: 0 0 420px; min-width: 320px;
        background: #fff; border-radius: 20px; padding: 2.5rem; border: 1px solid #f0ebe3;
        position: sticky; top: 100px; align-self: flex-start;
        box-shadow: 0 20px 40px rgba(0,0,0,0.03);
    }
    .sidebar-title { font-family: 'Lora', serif; font-size: 1.2rem; font-weight: 600; color: #1a1a1a; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #f0ebe3; }

    .order-item { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem; }
    .order-item-img { width: 64px; height: 64px; border-radius: 10px; overflow: hidden; border: 1px solid #f0ebe3; position: relative; flex-shrink: 0; background: #fdfdfc; }
    .order-item-img img { width: 100%; height: 100%; object-fit: contain; }
    .order-item-badge {
        position: absolute; top: -8px; right: -8px;
        background: #8c7b6c; color: #fff; width: 22px; height: 22px; border-radius: 50%;
        font-size: 0.7rem; font-weight: 700; display: flex; align-items: center; justify-content: center;
    }
    .order-item-name { flex: 1; font-size: 0.9rem; font-weight: 500; color: #1a1a1a; line-height: 1.4; }
    .order-item-price { font-size: 0.95rem; font-weight: 600; color: #1a1a1a; white-space: nowrap; }

    .voucher-row { display: flex; gap: 10px; padding: 1.5rem 0; border-top: 1px solid #f0ebe3; border-bottom: 1px solid #f0ebe3; margin: 1.5rem 0; }
    .voucher-input { flex: 1; padding: 0.8rem 1rem; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem; outline: none; background: #fdfdfc; }
    .voucher-input:focus { border-color: #8c7b6c; }
    .voucher-btn { padding: 0 1.25rem; background: #f0ebe3; border: none; border-radius: 10px; font-weight: 600; color: #8c7b6c; cursor: pointer; transition: 0.2s; }
    .voucher-btn:hover { background: #8c7b6c; color: #fff; }

    .summary-row { display: flex; justify-content: space-between; margin-bottom: 0.85rem; font-size: 0.95rem; color: #6b7280; }
    .summary-row .val { color: #1a1a1a; font-weight: 600; }
    .total-row { display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; margin-top: 1rem; border-top: 2px dashed #f0ebe3; }
    .total-row .label { font-size: 1rem; color: #4b5563; }
    .total-row .val { font-size: 1.8rem; font-weight: 700; color: #1a1a1a; }
    .tax-note { font-size: 0.75rem; color: #9ca3af; text-align: right; margin-top: 0.5rem; }

    @media (max-width: 992px) {
        .checkout-wrapper { flex-direction: column; }
        .checkout-right { order: -1; position: static; flex: none; width: 100%; }
        .checkout-left { min-width: auto; }
    }
</style>

<form action="/webbanhang/index.php?url=product/processCheckout" method="POST">
    <input type="hidden" name="discount_code" id="hidden_discount_code">

    <div class="checkout-wrapper">
        <div class="checkout-left">
            <a href="/webbanhang/index.php?url=product" class="checkout-logo">Nội Thất Hiện Đại</a>

            <div class="section-heading">
                <span>Thông tin liên hệ</span>
                <a href="/webbanhang/index.php?url=account/login" class="login-link"></a>
            </div>
            <div class="form-floating">
                <input type="email" name="customer_email" id="ck_email" placeholder=" " required value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>">
                <label for="ck_email">Địa chỉ email</label>
            </div>
            <div class="news-check">
                <input type="checkbox" id="news" checked> <label for="news">Gửi cho tôi tin tức và ưu đãi qua email</label>
            </div>

            <div class="section-heading mt-4">Địa chỉ giao hàng</div>
            
            <label class="ship-option selected" onclick="this.querySelector('input').checked=true; document.querySelectorAll('.ship-option').forEach(e=>e.classList.remove('selected')); this.classList.add('selected');">
                <span><i class="fas fa-shipping-fast"></i> Giao hàng tận nơi</span>
                <input type="radio" name="shipping_type" value="delivery" checked style="accent-color:#8c7b6c;">
            </label>
            <label class="ship-option" onclick="this.querySelector('input').checked=true; document.querySelectorAll('.ship-option').forEach(e=>e.classList.remove('selected')); this.classList.add('selected');">
                <span><i class="fas fa-store"></i> Nhận tại showroom</span>
                <input type="radio" name="shipping_type" value="pickup" style="accent-color:#8c7b6c;">
            </label>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="form-floating">
                        <select name="country" id="ck_country">
                            <option value="Việt Nam" selected>Việt Nam</option>
                        </select>
                        <label for="ck_country" style="transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem); color: #8c7b6c;">Quốc gia</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="first_name" id="ck_fn" placeholder=" " required>
                        <label for="ck_fn">Tên</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="last_name" id="ck_ln" placeholder=" " required>
                        <label for="ck_ln">Họ</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input type="text" name="address" id="ck_addr" placeholder=" " required>
                        <label for="ck_addr">Địa chỉ chi tiết</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="city" id="ck_city" placeholder=" " required>
                        <label for="ck_city">Thành phố / Tỉnh</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" name="postal_code" id="ck_zip" placeholder=" ">
                        <label for="ck_zip">Mã bưu chính (tùy chọn)</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input type="tel" name="customer_phone" id="ck_phone" placeholder=" " required>
                        <label for="ck_phone">Số điện thoại</label>
                    </div>
                </div>
            </div>

            <div class="section-heading mt-4">Phương thức thanh toán</div>
            
            <label class="ship-option selected" onclick="selectPayment('cod', this)">
                <span><i class="fas fa-money-bill-wave"></i> Thanh toán khi nhận hàng (COD)</span>
                <input type="radio" name="payment_method" value="cod" checked style="accent-color:#8c7b6c;">
            </label>
            <label class="ship-option" onclick="selectPayment('qr', this)">
                <span><i class="fas fa-qrcode"></i> Chuyển khoản ngân hàng (QR Pay)</span>
                <input type="radio" name="payment_method" value="qr" style="accent-color:#8c7b6c;">
            </label>

            <!-- QR Payment Panel -->
            <div id="qr-payment-panel" style="display:none; margin-top:1.5rem; background:#faf9f8; border-radius:16px; padding:2rem; border:1px solid #f0ebe3; text-align:center;">
                <div style="margin-bottom:1rem;">
                    <span style="display:inline-flex; align-items:center; gap:6px; background:#dbeafe; color:#1e40af; padding:6px 14px; border-radius:20px; font-size:0.8rem; font-weight:600;">
                        <i class="fas fa-info-circle"></i> Quét mã QR để thanh toán
                    </span>
                </div>
                <div id="qr-code-wrap" style="background:#fff; display:inline-block; padding:1rem; border-radius:12px; border:1px solid #e5e7eb; margin-bottom:1rem;">
                    <img id="qr-code-img" src="" alt="QR Code" style="width:220px; height:220px;">
                </div>
                <div style="font-size:0.85rem; color:#6b7280; line-height:1.7;">
                    <div><strong>Ngân hàng:</strong> <span style="color:#1a1a1a;">MB Bank</span></div>
                    <div><strong>Số TK:</strong> <span style="color:#1a1a1a;">0394867215</span></div>
                    <div><strong>Chủ TK:</strong> <span style="color:#1a1a1a;">NỘI THẤT HIỆN ĐẠI</span></div>
                    <div style="margin-top:0.5rem;"><strong>Nội dung CK:</strong> <span style="color:#ef4444; font-weight:700;" id="qr-transfer-content">DH_<?php echo time(); ?></span></div>
                </div>
                <div style="margin-top:1rem; font-size:0.8rem; color:#d97706; background:#fef3c7; padding:8px 14px; border-radius:8px; display:inline-block;">
                    <i class="fas fa-exclamation-triangle"></i> Vui lòng ghi đúng nội dung chuyển khoản để đơn được xử lý nhanh nhất!
                </div>
            </div>

            <button type="submit" class="btn-pay" id="btn-submit-order">
                Xác Nhận Đặt Hàng <i class="fas fa-lock"></i>
            </button>
        </div>

        <div class="checkout-right">
            <div class="sidebar-title">Đơn hàng của bạn</div>
            
            <?php 
                $total_price = 0;
                $cart = $_SESSION['cart'] ?? [];
                foreach ($cart as $id => $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_price += $subtotal;
                    $imgSrc = !empty($item['image']) ? "/webbanhang/" . $item['image'] : "https://via.placeholder.com/64x64/fdfdfc/9ca3af?text=Img";
            ?>
                <div class="order-item">
                    <div class="order-item-img">
                        <img src="<?php echo $imgSrc; ?>">
                        <span class="order-item-badge"><?php echo $item['quantity']; ?></span>
                    </div>
                    <div class="order-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                    <div class="order-item-price"><?php echo number_format($subtotal, 0, ',', '.'); ?> ₫</div>
                </div>
            <?php endforeach; ?>

            <div class="voucher-row">
                <input type="text" id="discount_input" class="voucher-input" placeholder="Mã giảm giá hoặc thẻ quà tặng">
                <button type="button" class="voucher-btn" id="btn_apply_discount">Áp dụng</button>
            </div>

            <div class="summary-row"><span>Tổng phụ</span><span class="val"><?php echo number_format($total_price, 0, ',', '.'); ?> ₫</span></div>
            <div class="summary-row"><span>Vận chuyển</span><span class="val" style="color:#10b981;">Miễn phí</span></div>

            <div class="total-row">
                <span class="label">Tổng cộng</span>
                <span class="val" id="total_price_display"><?php echo number_format($total_price, 0, ',', '.'); ?> ₫</span>
            </div>
            <div class="tax-note">Bao gồm <?php echo number_format($total_price * 0.08, 0, ',', '.'); ?> ₫ thuế VAT</div>
        </div>
    </div>
</form>

<?php include __DIR__ . '/../shaders/footer.php'; ?>
<script>
// Payment method config
const BANK_ID = 'MB'; // Mã ngân hàng (MB Bank)
const ACCOUNT_NO = '0394867215';
const ACCOUNT_NAME = 'NOI THAT HIEN DAI';

function selectPayment(method, el) {
    el.querySelector('input').checked = true;
    document.querySelectorAll('.ship-option').forEach(function(e) {
        if (e.querySelector('input[name="payment_method"]')) {
            e.classList.remove('selected');
        }
    });
    el.classList.add('selected');

    var qrPanel = document.getElementById('qr-payment-panel');
    var btnSubmit = document.getElementById('btn-submit-order');
    
    if (method === 'qr') {
        qrPanel.style.display = 'block';
        btnSubmit.innerHTML = 'Đã Chuyển Khoản - Xác Nhận <i class="fas fa-check-circle"></i>';
        generateQR();
    } else {
        qrPanel.style.display = 'none';
        btnSubmit.innerHTML = 'Xác Nhận Đặt Hàng <i class="fas fa-lock"></i>';
    }
}

function generateQR() {
    var totalText = document.getElementById('total_price_display').textContent;
    var amount = parseInt(totalText.replace(/[^\d]/g, '')) || 0;
    var transferContent = document.getElementById('qr-transfer-content').textContent;
    
    // VietQR API: https://api.vietqr.io
    var qrUrl = 'https://img.vietqr.io/image/' + BANK_ID + '-' + ACCOUNT_NO + '-compact2.jpg'
              + '?amount=' + amount 
              + '&addInfo=' + encodeURIComponent(transferContent)
              + '&accountName=' + encodeURIComponent(ACCOUNT_NAME);
    
    document.getElementById('qr-code-img').src = qrUrl;
}

$(document).ready(function() {
    let baseTotal = <?php echo (int)$total_price; ?>;
    
    $('#btn_apply_discount').click(function() {
        const code = $('#discount_input').val().trim();
        if (!code) return;
        
        $.post('/webbanhang/index.php?url=product/applyDiscount', {code: code}, function(res) {
            if (res.success) {
                $('#hidden_discount_code').val(res.discount.code);
                let discountVal = res.discount.is_percentage == 1 
                                  ? baseTotal * (res.discount.amount / 100) 
                                  : parseFloat(res.discount.amount);
                let newTotal = Math.max(0, baseTotal - discountVal);
                
                $('#total_price_display').text(new Intl.NumberFormat('vi-VN').format(newTotal) + ' ₫');
                $('.voucher-row').after('<div class="text-success small fw-bold mb-3" style="color:#10b981;"><i class="fas fa-check-circle"></i> Đã áp dụng! Giảm ' + new Intl.NumberFormat('vi-VN').format(discountVal) + ' ₫</div>');
                $('#btn_apply_discount').prop('disabled', true).css('opacity', 0.5);
                
                // Re-generate QR if QR panel is visible
                if ($('#qr-payment-panel').is(':visible')) generateQR();
            } else {
                alert(res.message);
            }
        }, 'json');
    });
});
</script>