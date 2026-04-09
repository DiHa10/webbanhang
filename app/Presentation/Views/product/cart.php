<?php 
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
include __DIR__ . '/../shaders/header.php'; 
?>

<style>
    body { background-color: #f7f5f2; }
    .cart-wrapper { max-width: 1300px; margin: 3rem auto; padding: 0 1.5rem; min-height: 70vh; }
    
    .cart-header {
        margin-bottom: 3rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f0ebe3;
    }
    .cart-title { font-family: 'Lora', serif; font-size: 2.5rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.5rem; }
    .cart-count { font-size: 0.95rem; color: #6b7280; }

    /* CART ITEM */
    .cart-items-list { display: flex; flex-direction: column; gap: 1.5rem; }
    .cart-item-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
        border: 1px solid #f0ebe3;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .cart-item-card:hover { box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-color: #e5e7eb; }
    
    .cart-item-img {
        width: 110px; height: 110px; flex-shrink: 0;
        border-radius: 12px; overflow: hidden; background: #fdfdfc; border: 1px solid #f0ebe3;
    }
    .cart-item-img img { width: 100%; height: 100%; object-fit: contain; }

    .cart-item-info { flex: 1; }
    .cart-item-name { font-family: 'Lora', serif; font-size: 1.15rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.3rem; }
    .cart-item-unit { font-size: 0.9rem; color: #9ca3af; }

    .qty-box { display: flex; align-items: center; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; background: #fff; }
    .qty-btn { width: 40px; height: 42px; display: flex; align-items: center; justify-content: center; background: transparent; border: none; font-size: 1.1rem; cursor: pointer; color: #6b7280; text-decoration: none; transition: 0.2s; }
    .qty-btn:hover { background: #f9fafb; color: #1a1a1a; text-decoration: none; }
    .qty-val { width: 50px; height: 42px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; border-left: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; }

    .cart-item-subtotal { font-size: 1.25rem; font-weight: 700; color: #1a1a1a; min-width: 140px; text-align: right; }
    
    .btn-remove { background: none; border: none; color: #d1d5db; font-size: 1.1rem; cursor: pointer; transition: 0.2s; padding: 8px; border-radius: 50%; }
    .btn-remove:hover { color: #ef4444; background: #fef2f2; }

    /* SIDEBAR */
    .cart-sidebar {
        background: #fff;
        border-radius: 20px;
        padding: 2.5rem;
        border: 1px solid #f0ebe3;
        position: sticky;
        top: 100px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.03);
    }
    .sidebar-title { font-family: 'Lora', serif; font-size: 1.35rem; font-weight: 600; color: #1a1a1a; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #f0ebe3; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.95rem; color: #6b7280; }
    .summary-row .val { color: #1a1a1a; font-weight: 600; }
    .summary-total { display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; margin-top: 1.5rem; border-top: 2px dashed #f0ebe3; }
    .summary-total .label { font-size: 1rem; font-weight: 600; color: #4b5563; }
    .summary-total .val { font-size: 1.8rem; font-weight: 700; color: #1a1a1a; }

    .note-area { width: 100%; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 12px; font-size: 0.9rem; background: #fdfdfc; resize: none; height: 80px; margin: 1.5rem 0; outline: none; transition: 0.3s; }
    .note-area:focus { border-color: #8c7b6c; box-shadow: 0 0 0 4px rgba(140, 123, 108, 0.08); }

    .btn-checkout {
        width: 100%; background: #1a1a1a; color: #fff; border: none; padding: 1.15rem; border-radius: 12px;
        font-size: 1rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;
        display: flex; align-items: center; justify-content: center; gap: 10px;
        transition: all 0.3s; text-decoration: none;
    }
    .btn-checkout:hover { background: #8c7b6c; color: #fff; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(140, 123, 108, 0.3); text-decoration: none; }

    .payment-icons { display: flex; justify-content: center; gap: 1rem; margin-top: 1.5rem; }
    .payment-icons i { font-size: 1.5rem; color: #d1d5db; }

    /* EMPTY */
    .empty-cart { text-align: center; padding: 5rem 2rem; background: #fff; border-radius: 20px; border: 1px solid #f0ebe3; max-width: 600px; margin: 0 auto; }
    .empty-cart i { font-size: 4rem; color: #e5e7eb; margin-bottom: 1.5rem; }
    .empty-cart h3 { font-family: 'Lora', serif; color: #1a1a1a; margin-bottom: 0.5rem; }
    .empty-cart p { color: #6b7280; margin-bottom: 2rem; }
    .btn-continue { display: inline-block; background: #8c7b6c; color: #fff; padding: 0.85rem 2rem; border-radius: 30px; font-weight: 600; text-decoration: none; transition: 0.3s; }
    .btn-continue:hover { background: #6b5c50; color: #fff; transform: translateY(-2px); }

    @media (max-width: 992px) {
        .cart-item-card { flex-wrap: wrap; gap: 1rem; }
        .cart-item-subtotal { min-width: auto; text-align: left; }
    }
</style>

<div class="cart-wrapper">
    <div class="cart-header">
        <h1 class="cart-title">Giỏ Hàng</h1>
        <?php if (!empty($cart)): ?>
            <span class="cart-count"><?php echo array_sum(array_column($cart, 'quantity')); ?> sản phẩm · Miễn phí vận chuyển toàn quốc</span>
        <?php endif; ?>
    </div>

    <?php if (empty($cart)): ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-bag"></i>
            <h3>Giỏ hàng trống</h3>
            <p>Hãy khám phá bộ sưu tập và tìm cho mình những tác phẩm yêu thích.</p>
            <a href="/webbanhang/index.php?url=product" class="btn-continue"><i class="fas fa-arrow-left me-2"></i> Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="cart-items-list">
                    <?php 
                    $total = 0; 
                    foreach ($cart as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        $imgSrc = (!empty($item['image']) && strpos($item['image'], 'http') === false) 
                                  ? "/webbanhang/" . $item['image'] 
                                  : $item['image'];
                        if (empty($imgSrc)) $imgSrc = "https://via.placeholder.com/110x110/fdfdfc/9ca3af?text=No+Image";
                    ?>
                        <div class="cart-item-card">
                            <div class="cart-item-img"><img src="<?php echo $imgSrc; ?>" alt=""></div>
                            <div class="cart-item-info">
                                <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="cart-item-unit"><?php echo number_format($item['price'], 0, ',', '.'); ?> ₫ / sản phẩm</div>
                            </div>
                            <div class="qty-box">
                                <a href="/webbanhang/index.php?url=Product/updateCart&id=<?php echo $id; ?>&action=decrease" class="qty-btn">−</a>
                                <div class="qty-val"><?php echo $item['quantity']; ?></div>
                                <a href="/webbanhang/index.php?url=Product/updateCart&id=<?php echo $id; ?>&action=increase" class="qty-btn">+</a>
                            </div>
                            <div class="cart-item-subtotal"><?php echo number_format($subtotal, 0, ',', '.'); ?> ₫</div>
                            <a href="/webbanhang/index.php?url=Product/removeFromCart&id=<?php echo $id; ?>" class="btn-remove" onclick="return confirm('Bạn muốn bỏ sản phẩm này khỏi giỏ?')">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="cart-sidebar">
                    <div class="sidebar-title">Tóm tắt đơn hàng</div>
                    <div class="summary-row"><span>Tạm tính</span><span class="val"><?php echo number_format($total, 0, ',', '.'); ?> ₫</span></div>
                    <div class="summary-row"><span>Vận chuyển</span><span class="val" style="color:#10b981;">Miễn phí</span></div>
                    <div class="summary-total">
                        <span class="label">Tổng cộng</span>
                        <span class="val"><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                    </div>
                    <textarea class="note-area" placeholder="Ghi chú cho đơn hàng (tùy chọn)..."></textarea>
                    <a href="/webbanhang/index.php?url=Product/checkout" class="btn-checkout">
                        Tiến hành thanh toán <i class="fas fa-arrow-right"></i>
                    </a>
                    <div class="payment-icons">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="far fa-credit-card"></i>
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>