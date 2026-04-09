<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; }

    .success-wrapper {
        max-width: 750px;
        margin: 4rem auto 5rem;
        padding: 0 1.5rem;
    }

    /* Animation */
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes confetti {
        0% { background-position: 0% 0%; }
        100% { background-position: 100% 100%; }
    }

    .success-card {
        background: #fff;
        border-radius: 24px;
        padding: 4rem 3.5rem;
        text-align: center;
        border: 1px solid #f0ebe3;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
        animation: fadeInUp 0.6s ease;
    }

    .check-circle {
        width: 90px; height: 90px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 2rem;
        animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.25);
    }
    .check-circle i { color: #fff; font-size: 2.5rem; }

    .success-title { font-family: 'Lora', serif; font-size: 2.2rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.75rem; }
    .success-subtitle { font-size: 1.05rem; color: #6b7280; line-height: 1.7; margin-bottom: 2.5rem; }

    /* Receipt Card */
    .receipt-box {
        background: #faf9f8;
        border-radius: 16px;
        padding: 2.5rem;
        text-align: left;
        border: 1px solid #f0ebe3;
        margin-bottom: 2rem;
    }
    .receipt-header {
        display: flex; justify-content: space-between; align-items: center;
        padding-bottom: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px dashed #e5e7eb;
    }
    .order-id { font-family: 'Lora', serif; font-size: 1.25rem; font-weight: 600; color: #1a1a1a; }
    .order-id span { color: #8c7b6c; }
    .order-date { font-size: 0.85rem; color: #9ca3af; }

    /* Customer Info */
    .customer-info {
        background: #fff; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #f0ebe3;
    }
    .customer-info-title { font-size: 0.8rem; font-weight: 700; color: #8c7b6c; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1rem; }
    .customer-info p { margin: 0.3rem 0; font-size: 0.95rem; color: #4b5563; }
    .customer-info .name { font-weight: 600; color: #1a1a1a; font-size: 1.05rem; }

    /* Order Items */
    .items-title { font-size: 0.8rem; font-weight: 700; color: #8c7b6c; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.25rem; }
    .receipt-item {
        display: flex; justify-content: space-between; align-items: flex-start;
        padding: 1rem 0; border-bottom: 1px solid #f0ebe3;
    }
    .receipt-item:last-of-type { border-bottom: none; }
    .receipt-item-name { font-weight: 500; color: #1a1a1a; margin-bottom: 0.25rem; }
    .receipt-item-qty { font-size: 0.85rem; color: #9ca3af; }
    .receipt-item-price { font-weight: 600; color: #1a1a1a; white-space: nowrap; }

    /* Totals */
    .receipt-subtotal {
        display: flex; justify-content: space-between; padding-top: 1.25rem; margin-top: 0.5rem;
        border-top: 1px dashed #e5e7eb; font-size: 0.95rem; color: #6b7280;
    }
    .receipt-discount {
        display: flex; justify-content: space-between; padding: 0.75rem 0; font-size: 0.95rem; color: #10b981;
    }
    .receipt-total {
        display: flex; justify-content: space-between; align-items: center;
        padding-top: 1.5rem; margin-top: 1rem; border-top: 2px solid #1a1a1a;
    }
    .receipt-total .label { font-family: 'Lora', serif; font-size: 1.15rem; font-weight: 600; color: #1a1a1a; }
    .receipt-total .val { font-size: 2rem; font-weight: 700; color: #1a1a1a; }

    .payment-method { text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: #9ca3af; }
    .payment-method i { color: #8c7b6c; margin-right: 5px; }

    .btn-continue {
        display: inline-flex; align-items: center; gap: 10px;
        background: #8c7b6c; color: #fff; padding: 1.1rem 2.5rem;
        border-radius: 30px; text-decoration: none; font-weight: 600;
        text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;
        transition: all 0.3s; margin-top: 2rem;
        box-shadow: 0 4px 10px rgba(140, 123, 108, 0.3);
    }
    .btn-continue:hover { background: #6b5c50; color: #fff; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(140, 123, 108, 0.4); text-decoration: none; }

    @media (max-width: 576px) {
        .success-card { padding: 3rem 1.5rem; }
        .receipt-box { padding: 1.5rem; }
    }
</style>

<div class="success-wrapper">
    <div class="success-card">
        <div class="check-circle">
            <i class="fas fa-check"></i>
        </div>
        <h1 class="success-title">Đặt Hàng Thành Công!</h1>
        <p class="success-subtitle">Cảm ơn bạn đã tin tưởng Nội Thất Hiện Đại. Đơn hàng đang được chuẩn bị và sẽ sớm đến tay bạn.</p>

        <div class="receipt-box">
            <div class="receipt-header">
                <div class="order-id">Đơn hàng <span>#<?php echo str_pad($order->id, 5, '0', STR_PAD_LEFT); ?></span></div>
                <div class="order-date"><i class="far fa-calendar-alt me-1"></i> <?php echo date('d/m/Y H:i', strtotime($order->created_at ?? date('Y-m-d H:i:s'))); ?></div>
            </div>

            <div class="customer-info">
                <div class="customer-info-title"><i class="fas fa-user me-1"></i> Người nhận</div>
                <p class="name"><?php echo htmlspecialchars($order->customer_name); ?></p>
                <p><i class="fas fa-phone-alt me-1" style="color:#8c7b6c; font-size:0.8rem;"></i> <?php echo htmlspecialchars($order->customer_phone); ?></p>
                <p><i class="fas fa-map-marker-alt me-1" style="color:#8c7b6c; font-size:0.8rem;"></i> <?php echo htmlspecialchars($order->address); ?></p>
            </div>

            <div class="items-title"><i class="fas fa-box-open me-1"></i> Chi tiết sản phẩm</div>
            
            <?php 
            $subTotal = 0;
            foreach ($orderDetails as $item): 
                $subTotal += $item->price * $item->quantity;
            ?>
            <div class="receipt-item">
                <div>
                    <div class="receipt-item-name"><?php echo htmlspecialchars($item->name); ?></div>
                    <div class="receipt-item-qty">SL: <?php echo $item->quantity; ?> × <?php echo number_format($item->price, 0, ',', '.'); ?> ₫</div>
                </div>
                <div class="receipt-item-price"><?php echo number_format($item->price * $item->quantity, 0, ',', '.'); ?> ₫</div>
            </div>
            <?php endforeach; ?>

            <div class="receipt-subtotal">
                <span>Tạm tính</span>
                <span style="font-weight:600; color:#1a1a1a;"><?php echo number_format($subTotal, 0, ',', '.'); ?> ₫</span>
            </div>
            
            <?php if ($subTotal > $order->total_price): ?>
            <div class="receipt-discount">
                <span><i class="fas fa-tags me-1"></i> Khuyến mãi áp dụng</span>
                <span style="font-weight:600;">−<?php echo number_format($subTotal - $order->total_price, 0, ',', '.'); ?> ₫</span>
            </div>
            <?php endif; ?>

            <div class="receipt-total">
                <span class="label">Tổng thanh toán</span>
                <span class="val"><?php echo number_format($order->total_price, 0, ',', '.'); ?> ₫</span>
            </div>

            <div class="payment-method">
                <i class="fas fa-money-bill-wave"></i> Thanh toán khi nhận hàng (COD)
            </div>
        </div>

        <a href="/webbanhang/index.php?url=product" class="btn-continue">
            <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
        </a>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>