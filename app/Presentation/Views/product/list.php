<?php
$_baseUrl = 'product';
include_once __DIR__ . '/../shaders/header.php';
?>

<style>
    body { background-color: #f7f5f2; }
    .product-page-wrapper {
        display: flex;
        min-height: calc(100vh - 56px);
        background: #f7f5f2;
        padding: 3rem;
        gap: 3rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* ===== LEFT SIDEBAR ===== */
    .category-sidebar {
        width: 260px;
        flex-shrink: 0;
        background: transparent;
    }
    .sidebar-title {
        font-family: 'Lora', serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.5rem;
    }
    .sidebar-title::after {
        content: ''; position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: #8c7b6c; border-radius: 2px;
    }
    
    .cat-list { display: flex; flex-direction: column; gap: 8px; }
    .cat-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.8rem 1rem;
        font-size: 0.95rem;
        font-weight: 500;
        color: #4b5563;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .cat-btn:hover { background: #faf9f8; color: #1a1a1a; transform: translateX(5px); box-shadow: 0 4px 6px rgba(0,0,0,0.04); border-color:#d1d5db; }
    .cat-btn.active { background: #8c7b6c; color: #fff; border-color: #8c7b6c; box-shadow: 0 10px 15px -3px rgba(140, 123, 108, 0.3); }

    /* ===== RIGHT CONTENT ===== */
    .product-content {
        flex: 1;
    }
    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .product-main-title {
        font-family: 'Lora', serif;
        font-size: 2rem;
        font-weight: 500;
        color: #1a1a1a;
        margin: 0;
    }
    .btn-add-product {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.75rem 1.25rem;
        background: #1a1a1a;
        color: #fff;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .btn-add-product:hover { background: #8c7b6c; color: #fff; transform: translateY(-2px); box-shadow: 0 10px 15px rgba(140, 123, 108, 0.3); }

    /* Search bar */
    .search-wrapper { position: relative; max-width: 500px; margin-bottom: 2.5rem; }
    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: none;
        border-radius: 14px;
        font-size: 0.95rem;
        background: #fff;
        color: #1a1a1a;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
        transition: all 0.3s;
        outline: none;
    }
    .search-input:focus { box-shadow: 0 10px 25px -5px rgba(140, 123, 108, 0.15); }
    .search-icon { position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1rem; }

    /* ===== PRODUCT CARD ===== */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 2rem;
    }
    @media (max-width: 900px) {
        .product-page-wrapper { flex-direction: column; padding: 2rem 1.5rem; }
        .category-sidebar { width: 100%; margin-bottom: 1rem; }
    }

    .prod-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        transition: all 0.4s ease;
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.04);
    }
    .prod-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1); }

    .prod-img-wrap {
        position: relative;
        height: 250px;
        overflow: hidden;
        background: #fdfdfc;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .prod-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .prod-card:hover .prod-img { transform: scale(1.05); }

    .cat-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(255,255,255,0.95);
        color: #8c7b6c;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        backdrop-filter: blur(4px);
    }

    .prod-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .prod-name {
        font-family: 'Lora', serif;
        font-weight: 600;
        font-size: 1.15rem;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .prod-desc {
        font-size: 0.85rem;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    .prod-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 1.25rem;
    }
    .btn-add-cart {
        display: block;
        width: 100%;
        text-align: center;
        padding: 0.8rem;
        background: #f9fafb;
        color: #1a1a1a;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    .btn-add-cart:hover { background: #8c7b6c; color: #fff; border-color: #8c7b6c; }

    .prod-btns {
        display: flex;
        gap: 8px;
    }
    .prod-btns .btn-add-cart { flex: 1; }
    .btn-buy-now {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 1;
        padding: 0.75rem 0.5rem;
        background: #1a1a1a;
        color: #fff;
        border: 1px solid #1a1a1a;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-buy-now:hover { background: #ef4444; border-color: #ef4444; color: #fff; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(239,68,68,0.3); }

    .admin-actions {
        position: absolute;
        top: 12px;
        left: 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }
    .prod-card:hover .admin-actions { opacity: 1; transform: translateX(0); }
    .btn-action-sm {
        width: 35px; height: 35px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        background: #fff;
        color: #1a1a1a;
        border: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-action-sm.edit:hover { background: #fbbf24; color: #fff; }
    .btn-action-sm.delete:hover { background: #ef4444; color: #fff; }

    /* Empty state */
    .empty-state { grid-column: 1 / -1; text-align: center; padding: 5rem 2rem; color: #9ca3af; background: #fff; border-radius: 20px; }
    .empty-state i { font-size: 4rem; margin-bottom: 1.5rem; color: #d1d5db; }
    .empty-state h3 { font-family: 'Lora', serif; color: #4b5563; }

    /* Sold count - Shopee style */
    .prod-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .prod-sold {
        font-size: 0.8rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .prod-sold i { font-size: 0.7rem; color: #f59e0b; }
    .prod-sold .sold-count {
        font-weight: 600;
        color: #6b7280;
    }
</style>

<?php
$_isAdmin   = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$_catId     = $_GET['category_id'] ?? null;
$_keyword   = $_GET['search'] ?? '';

$_pageTitle = 'Tất cả Sản Phẩm';
if ($_catId && !empty($categories)) {
    foreach ($categories as $c) {
        if ($c->id == $_catId) { $_pageTitle = htmlspecialchars($c->name); break; }
    }
}
?>

<div class="product-page-wrapper">

    <!-- LEFT SIDEBAR -->
    <aside class="category-sidebar">
        <div class="sidebar-title">Danh mục</div>
        <div class="cat-list">
            <a href="/webbanhang/index.php?url=<?php echo $_baseUrl; ?>" class="cat-btn <?php echo !$_catId ? 'active' : ''; ?>">
                <span>Tất cả sản phẩm</span> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
            </a>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <a href="/webbanhang/index.php?url=<?php echo $_baseUrl; ?>&category_id=<?php echo $cat->id; ?>"
                       class="cat-btn <?php echo ($_catId == $cat->id) ? 'active' : ''; ?>">
                        <span><?php echo htmlspecialchars($cat->name); ?></span> <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </aside>

    <!-- RIGHT CONTENT -->
    <main class="product-content">
        <div class="product-header">
            <h1 class="product-main-title"><?php echo $_pageTitle; ?> 
                <?php if($_keyword): ?><span style="font-size:0.45em; color:#9ca3af; font-family:'Inter'; font-weight:400; vertical-align:middle;">(Kết quả cho "<?php echo htmlspecialchars($_keyword); ?>")</span><?php endif; ?>
            </h1>
            <?php if ($_isAdmin): ?>
                <a href="/webbanhang/index.php?url=product/add" class="btn-add-product">
                    <i class="fas fa-plus"></i> Thêm Mới
                </a>
            <?php endif; ?>
        </div>

        <form class="search-wrapper" action="/webbanhang/index.php" method="GET">
            <input type="hidden" name="url" value="<?php echo $_baseUrl; ?>">
            <?php if ($_catId): ?>
                <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($_catId); ?>">
            <?php endif; ?>
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="search" class="search-input" placeholder="Bạn đang tìm kiếm gì hôm nay?..." value="<?php echo htmlspecialchars($_keyword); ?>">
        </form>

        <div class="products-grid">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <?php
                    $imgSrc = !empty($product->image) ? '/webbanhang/' . $product->image : 'https://via.placeholder.com/400x300/f5f0ea/aaa?text=No+Image';
                    $priceFormatted = number_format($product->price, 0, ',', '.') . ' ₫';
                    ?>
                    <div class="prod-card">
                        <a href="/webbanhang/index.php?url=<?php echo $_baseUrl; ?>/show/<?php echo $product->id; ?>" class="prod-img-wrap">
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($product->name); ?>" class="prod-img" loading="lazy">
                            <?php if (!empty($product->category_name)): ?>
                                <span class="cat-badge"><?php echo htmlspecialchars($product->category_name); ?></span>
                            <?php endif; ?>
                        </a>
                        
                        <?php if ($_isAdmin): ?>
                            <div class="admin-actions">
                                <a href="/webbanhang/index.php?url=product/edit/<?php echo $product->id; ?>" class="btn-action-sm edit" title="Sửa sản phẩm">
                                    <i class="fas fa-pen fa-sm"></i>
                                </a>
                                <button class="btn-action-sm delete" onclick="confirmDelete(<?php echo $product->id; ?>)" title="Xóa sản phẩm">
                                    <i class="fas fa-trash fa-sm"></i>
                                </button>
                            </div>
                        <?php endif; ?>

                        <div class="prod-body">
                            <a href="/webbanhang/index.php?url=<?php echo $_baseUrl; ?>/show/<?php echo $product->id; ?>" style="text-decoration:none;"><div class="prod-name"><?php echo htmlspecialchars($product->name); ?></div></a>
                            <div class="prod-desc"><?php echo htmlspecialchars($product->description ?? ''); ?></div>
                            <div class="prod-footer">
                                <div class="prod-price"><?php echo $priceFormatted; ?></div>
                                <div class="prod-sold">
                                    <i class="fas fa-fire"></i>
                                    Đã bán <span class="sold-count"><?php 
                                        $sold = intval($product->sold_count ?? 0);
                                        if ($sold >= 1000) echo number_format($sold/1000, 1, ',', '.') . 'k';
                                        else echo $sold;
                                    ?></span>
                                </div>
                            </div>
                            <div class="prod-btns">
                                <a href="javascript:void(0);" onclick="addToCartGlobal(<?php echo $product->id; ?>, this)" class="btn-add-cart">
                                    <i class="fas fa-shopping-cart" style="margin-right:5px;"></i> Thêm Giỏ
                                </a>
                                <a href="javascript:void(0);" onclick="buyNow(<?php echo $product->id; ?>)" class="btn-buy-now">
                                    <i class="fas fa-bolt" style="margin-right:5px;"></i> Mua Ngay
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>Chưa có sản phẩm nào</h3>
                    <p>Hãy theo dõi để cập nhật những bộ sưu tập mới nhất.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function confirmDelete(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Mọi dữ liệu sẽ bị mất.')) return;
    window.location.href = '/webbanhang/index.php?url=product/delete/' + id;
}

function buyNow(productId) {
    var btn = event.currentTarget;
    $(btn).css('pointer-events','none').html('<i class="fas fa-spinner fa-spin" style="margin-right:5px;"></i> Đang xử lý...');
    
    fetch('/webbanhang/index.php?url=product/addToCart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'id=' + productId + '&quantity=1'
    }).then(res => res.json()).then(data => {
        if (data.success) {
            // Cập nhật badge giỏ hàng
            var badge = $('#cart-badge');
            badge.text(data.total_items).show();
            $(btn).html('<i class="fas fa-check" style="margin-right:5px;"></i> Đang chuyển...').css({'background':'#10b981','border-color':'#10b981'});
            setTimeout(function() {
                window.location.href = '/webbanhang/index.php?url=product/checkout';
            }, 400);
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
            $(btn).css('pointer-events','auto').html('<i class="fas fa-bolt" style="margin-right:5px;"></i> Mua Ngay');
        }
    }).catch(() => {
        window.location.href = '/webbanhang/index.php?url=product/checkout';
    });
}
</script>

<?php include_once __DIR__ . '/../shaders/footer.php'; ?>