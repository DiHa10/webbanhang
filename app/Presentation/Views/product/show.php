<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; color: #1a1a1a; font-family: 'Inter', system-ui, sans-serif; }
    
    .product-showoff-wrapper {
        min-height: 80vh;
        max-width: 1300px;
        margin: 4rem auto;
        padding: 0 1.5rem;
    }
    
    .breadcrumb-custom { background: transparent; padding: 0; margin-bottom: 2rem; font-size: 0.9rem; }
    .breadcrumb-custom a { color: #6b7280; text-decoration: none; transition: 0.2s; }
    .breadcrumb-custom a:hover { color: #8c7b6c; text-decoration: underline; }
    .breadcrumb-custom .active { color: #1a1a1a; font-weight: 600; }

    .show-glass-panel {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.04);
        padding: 4rem;
        display: flex;
        flex-wrap: wrap;
        gap: 4rem;
    }

    /* LEFT SIDE: Image */
    .show-left { flex: 1; min-width: 350px; display: flex; align-items: center; justify-content: center; background: #fdfdfc; border-radius: 16px; padding: 2rem; border: 1px solid #f0ebe3; position: relative;}
    .show-left img { width: 100%; max-height: 500px; object-fit: contain; transition: transform 0.6s ease; }
    .show-left:hover img { transform: scale(1.05); }

    /* RIGHT SIDE: Details */
    .show-right { flex: 1.2; min-width: 350px; display: flex; flex-direction: column; }
    
    .show-cat-badge { display: inline-block; padding: 5px 15px; border: 1px solid #e5e7eb; border-radius: 30px; font-size: 0.75rem; font-weight: 700; color: #8c7b6c; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 1.5rem; }
    .show-title { font-family: 'Lora', serif; font-size: 2.8rem; font-weight: 600; color: #1a1a1a; line-height: 1.2; margin-bottom: 1.5rem; }
    
    .show-price-row { display: flex; align-items: baseline; gap: 1rem; margin-bottom: 1rem; border-bottom: 1px solid #f0ebe3; padding-bottom: 1.5rem; }
    .show-price { font-size: 2.2rem; font-weight: 700; color: #1a1a1a; }
    .show-original-price { font-size: 1.3rem; color: #9ca3af; text-decoration: line-through; }
    
    .show-description { font-size: 1.05rem; color: #6b7280; line-height: 1.8; margin-bottom: 2.5rem; font-weight: 300; }

    .qty-wrapper { display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2.5rem; }
    .qty-label { font-size: 0.85rem; font-weight: 700; color: #4b5563; letter-spacing: 1px; }
    .qty-box { display: flex; align-items: center; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: #fff; }
    .qty-btn { width: 40px; height: 45px; display: flex; align-items: center; justify-content: center; background: transparent; border: none; font-size: 1.2rem; cursor: pointer; color: #6b7280; transition: 0.2s;}
    .qty-btn:hover { background: #f9fafb; color: #1a1a1a;}
    .qty-input { width: 50px; height: 45px; text-align: center; border: none; font-weight: 600; font-size: 1.1rem; pointer-events: none;}
    
    .show-action-row { display: flex; gap: 1rem; }
    .btn-buy { flex: 1; background: #1a1a1a; color: #fff; border: none; padding: 1.2rem; border-radius: 12px; font-size: 1rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; transition: all 0.3s ease; display:flex; justify-content:center; align-items:center; gap:8px;}
    .btn-buy:hover { background: #8c7b6c; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(140, 123, 108, 0.3); }

    .show-perks { margin-top: 3rem; display: flex; gap: 2rem; border-top: 1px solid #f0ebe3; padding-top: 2rem; }
    .perk-item { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; color: #4b5563; font-weight: 500;}
    .perk-item i { color: #8c7b6c; font-size: 1.2rem;}

    @media (max-width: 900px) {
        .show-glass-panel { padding: 2rem; gap: 2rem; flex-direction: column; }
        .show-title { font-size: 2.2rem; }
    }

    /* Features Highlight Area */
    .features-panel { max-width: 1300px; margin: 0 auto 5rem; padding: 4rem; background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
    .features-title { font-family: 'Lora', serif; font-size: 2rem; color: #1a1a1a; padding-bottom: 2rem; margin-bottom: 2rem; border-bottom: 1px solid #f0ebe3;}
    .features-list { font-size: 1.05rem; color: #4b5563; line-height: 2; list-style: none; padding: 0; display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;}
    .features-list li { display: flex; gap: 15px; }
    .features-list i { color: #8c7b6c; margin-top: 5px;}
</style>

<div class="product-showoff-wrapper">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-custom">
            <?php $baseRoute = '/webbanhang/index.php?url=product'; ?>
            <li class="breadcrumb-item"><a href="<?php echo $baseRoute; ?>">Trang chủ / Sản Phẩm</a></li>
            <li class="breadcrumb-item active"><?php echo htmlspecialchars($product->name); ?></li>
        </ol>
    </nav>

    <div class="show-glass-panel mb-5">
        <div class="show-left">
            <?php $imgSrc = !empty($product->image) ? "/webbanhang/" . $product->image : "https://via.placeholder.com/600x600/fdfdfc/6b7280?text=No+Image"; ?>
            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($product->name); ?>">
        </div>

        <div class="show-right">
            <div>
                <span class="show-cat-badge"><?php echo htmlspecialchars($product->category_name ?? 'Tuyển chọn'); ?></span>
                <h1 class="show-title"><?php echo htmlspecialchars($product->name); ?></h1>
                
                <div class="show-price-row">
                    <span class="show-price"><?php echo number_format($product->price, 0, ',', '.'); ?> ₫</span>
                    <?php if (isset($product->original_price) && $product->original_price > $product->price): ?>
                        <span class="show-original-price"><?php echo number_format($product->original_price, 0, ',', '.'); ?> ₫</span>
                    <?php endif; ?>
                </div>

                <div class="show-description">
                    <?php echo nl2br(htmlspecialchars($product->description)); ?>
                </div>
            </div>

            <form action="/webbanhang/index.php?url=product/addToCart" method="POST" class="mt-auto">
                <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                
                <div class="qty-wrapper">
                    <span class="qty-label">SỐ LƯỢNG:</span>
                    <div class="qty-box">
                        <button type="button" class="qty-btn" onclick="var el=document.getElementById('qty-input'); if(el.value>1) el.value--;"><i class="fas fa-minus"></i></button>
                        <input type="number" id="qty-input" name="quantity" value="1" min="1" max="99" class="qty-input" readonly>
                        <button type="button" class="qty-btn" onclick="var el=document.getElementById('qty-input'); el.value++;"><i class="fas fa-plus"></i></button>
                    </div>
                </div>

                <div class="show-action-row">
                    <button type="submit" name="action" value="add" class="btn-buy" style="background:#fff; color:#1a1a1a; border: 1px solid #1a1a1a;">
                        <i class="fas fa-cart-plus"></i> Thêm Vào Giỏ Hàng
                    </button>
                    <button type="submit" name="action" value="buy_now" class="btn-buy">
                        Mua Ngay <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>

            <div class="show-perks">
                <div class="perk-item"><i class="fas fa-truck-moving"></i> Giao hàng miễn phí toàn quốc</div>
                <div class="perk-item"><i class="fas fa-shield-alt"></i> Bảo hành gỗ 5 năm</div>
            </div>
        </div>
    </div>

    <?php if (!empty($product->features)): ?>
    <div class="features-panel">
        <h2 class="features-title">Điểm Nhấn Nghệ Thuật</h2>
        <ul class="features-list">
            <?php 
            $featureLines = explode("\n", trim($product->features));
            foreach($featureLines as $line): 
                if(trim($line) === '') continue;
            ?>
                <li><i class="fas fa-check"></i> <span><?php echo htmlspecialchars(trim($line)); ?></span></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>