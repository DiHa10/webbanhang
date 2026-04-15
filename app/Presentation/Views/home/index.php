<?php include __DIR__ . '/../shaders/header.php'; ?>
<style>
    body { background-color: #f7f5f2; color: #1a1a1a; font-family: 'Inter', system-ui, sans-serif; }
    
    /* ===== BANNER ===== */
    .hero-banner { margin-top: -1.5rem; position: relative; }
    .carousel-item-custom { height: 85vh; min-height: 500px; position: relative; overflow: hidden; }
    .carousel-item-custom img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0.7) contrast(1.1); transition: transform 6s ease; }
    .carousel-item.active .carousel-item-custom img { transform: scale(1.05); }
    .hero-caption {
        position: absolute; top: 50%; left: 0; right: 0; transform: translateY(-50%);
        text-align: center; color: #fff; padding: 0 20px;
    }
    .hero-title { font-family: 'Lora', serif; font-size: 4rem; font-weight: 500; text-shadow: 0 4px 15px rgba(0,0,0,0.4); margin-bottom: 1rem; animation: fadeInUp 1s ease; }
    .hero-subtitle { font-size: 1.15rem; font-weight: 300; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 2.5rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3); animation: fadeInUp 1.2s ease; }
    .btn-hero { background: #8c7b6c; color: #fff; padding: 1rem 2.5rem; border-radius: 30px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; border: none; text-decoration: none; animation: fadeInUp 1.4s ease;}
    .btn-hero:hover { background: #6b5c50; color: #fff; box-shadow: 0 10px 20px rgba(140, 123, 108, 0.4); transform: translateY(-3px); }
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    /* ===== SECTION HEADERS ===== */
    .section-title-wrap { text-align: center; margin-bottom: 3.5rem; }
    .section-title { font-family: 'Lora', serif; font-size: 2.25rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.5rem; }
    .section-subtitle { font-size: 0.9rem; color: #8c7b6c; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; }

    /* ===== CATEGORIES ===== */
    .home-cat-card {
        background: #fff; padding: 2.5rem 1.5rem; text-align: center; border-radius: 20px; transition: all 0.4s ease; cursor: pointer; border: 1px solid transparent; text-decoration: none; display: block;
    }
    .home-cat-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.06); border-color: #eae5de; }
    .home-cat-icon { width: 70px; height: 70px; background: #faf9f8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #8c7b6c; transition: all 0.4s; }
    .home-cat-card:hover .home-cat-icon { background: #8c7b6c; color: #fff; transform: scale(1.1); }
    .home-cat-name { font-family: 'Lora', serif; font-size: 1.15rem; color: #1a1a1a; font-weight: 600; margin: 0; }

    /* ===== STORY (INTERVIEW) ===== */
    .story-section { padding: 6rem 0; background: #fff; }
    .story-left { padding-right: 4rem; display: flex; flex-direction: column; justify-content: center; }
    .story-quote { font-family: 'Lora', serif; font-size: 2.5rem; line-height: 1.4; color: #1a1a1a; margin-bottom: 2rem; position: relative; }
    .story-quote::before { content: '"'; font-family: 'Lora', serif; font-size: 5rem; color: #f0ebe3; position: absolute; left: -30px; top: -30px; z-index: 0; }
    .story-desc { font-size: 1.05rem; color: #6b7280; line-height: 1.8; margin-bottom: 2rem; font-weight: 300; }
    .story-author { font-weight: 600; color: #8c7b6c; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem; }
    .story-img-wrap { position: relative; border-radius: 20px; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.1); }
    .story-img-wrap img { width: 100%; transition: transform 1s ease; }
    .story-img-wrap:hover img { transform: scale(1.03); }

    /* ===== PRODUCT CARD (Synced with list.php) ===== */
    .prod-card { background: #fff; border: none; border-radius: 16px; display: flex; flex-direction: column; transition: all 0.4s ease; overflow: hidden; position: relative; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.04); text-decoration: none;}
    .prod-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1); text-decoration: none; }
    .prod-img-wrap { position: relative; height: 280px; overflow: hidden; background: #fdfdfc; display: flex; align-items: center; justify-content: center; }
    .prod-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .prod-card:hover .prod-img { transform: scale(1.05); }
    .cat-badge { position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,0.95); color: #8c7b6c; font-size: 0.7rem; font-weight: 600; padding: 5px 12px; border-radius: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); z-index:5;}
    .prod-body { padding: 1.5rem; display: flex; flex-direction: column; flex: 1; }
    .prod-name { font-family: 'Lora', serif; font-weight: 600; font-size: 1.15rem; color: #1a1a1a; margin-bottom: 0.5rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-decoration: none;}
    .prod-desc { font-size: 0.85rem; color: #6b7280; line-height: 1.6; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex: 1; }
    .prod-price { font-size: 1.25rem; font-weight: 700; color: #1a1a1a; margin-bottom: 1.25rem; }
    .btn-add-cart { display: block; width: 100%; text-align: center; padding: 0.8rem; background: #f9fafb; color: #1a1a1a; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 0.9rem; font-weight: 600; text-decoration: none; transition: all 0.3s; z-index:5; position:relative;}
    .btn-add-cart:hover { background: #8c7b6c; color: #fff; border-color: #8c7b6c; }
    .view-more-banner { background: #8c7b6c; color: #fff; padding: 3rem 0; text-align: center; margin-top: 4rem; }
    
    @media (max-width: 992px) {
        .hero-title { font-size: 2.8rem; }
        .story-left { padding-right: 15px; margin-bottom: 3rem; }
        .story-quote { font-size: 2rem; }
    }
</style>

<!-- HERO BANNER -->
<div class="hero-banner">
    <div id="mainBannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-indicators">
            <?php if (!empty($sliders)): ?>
                <?php foreach ($sliders as $index => $slide): ?>
                    <button type="button" data-bs-target="#mainBannerCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></button>
                <?php endforeach; ?>
            <?php else: ?>
                <button type="button" data-bs-target="#mainBannerCarousel" data-bs-slide-to="0" class="active"></button>
            <?php endif; ?>
        </div>
        <div class="carousel-inner">
            <?php if (!empty($sliders)): ?>
                <?php foreach ($sliders as $index => $slide): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="carousel-item-custom">
                            <img src="/webbanhang/<?php echo htmlspecialchars($slide->image_path); ?>" alt="Banner <?php echo $index + 1; ?>">
                            <div class="hero-caption">
                                <h1 class="hero-title"><?php echo htmlspecialchars($slide->title ?? 'Nội thất tinh tế'); ?></h1>
                                <p class="hero-subtitle"><?php echo htmlspecialchars($slide->subtitle ?? 'Sang trọng - Đẳng cấp - Tiện nghi'); ?></p>
                                <a href="<?php echo !empty($slide->link_url) ? htmlspecialchars($slide->link_url) : '/webbanhang/index.php?url=product'; ?>" class="btn-hero border">Khám Phá Ngay</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="carousel-item active">
                    <div class="carousel-item-custom">
                        <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=2000&auto=format&fit=crop" alt="Default Banner">
                        <div class="hero-caption">
                            <h1 class="hero-title">Nghệ thuật không gian sống</h1>
                            <p class="hero-subtitle">Bộ sưu tập mùa thu mới nhất 2026</p>
                            <a href="/webbanhang/index.php?url=product" class="btn-hero">Xem Bộ Sưu Tập</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- CATEGORY SECTION -->
<div class="container" style="padding-top: 6rem; padding-bottom: 2rem;">
    <div class="section-title-wrap">
        <div class="section-subtitle">Dành riêng cho bạn</div>
        <h2 class="section-title">Danh Mục Nổi Bật</h2>
    </div>
    <div class="row g-4 justify-content-center" id="home-category-list">
        <!-- Tải render qua JS -->
    </div>
</div>

<!-- BRAND STORY -->
<div class="story-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 story-left mb-4 mb-lg-0">
                <div class="section-subtitle mb-3" style="text-align: left;">Câu chuyện nhãn hiệu</div>
                <h2 class="story-quote">"Mang tâm hồn của nghệ nhân vào từng góc nhỏ của ngôi nhà."</h2>
                <p class="story-desc">
                    Chúng tôi tin rằng nội thất không chỉ là những món đồ để lấp khoảng trống. Mỗi chiếc bàn, chiếc ghế, hay ngọn đèn đều mang trong mình một câu chuyện riêng, dệt nên mạch cảm xúc tổ ấm riêng biệt của bạn.
                </p>
                <div class="story-author">— Kiến trúc sư Trần Hoàng</div>
                <div class="mt-4">
                    <a href="javascript:alert('Tính năng đang phát triển');" class="btn-hero" style="padding: 0.8rem 2rem; display:inline-block;">Tìm hiểu thêm</a>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="story-img-wrap">
                    <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=1200&auto=format&fit=crop" alt="Kiến trúc hiện đại">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PRODUCTS SECTION -->
<div class="container" style="padding-top: 5rem;">
    <div class="section-title-wrap">
        <div class="section-subtitle">Sản phẩm tuyển chọn</div>
        <h2 class="section-title">Xu Hướng Nội Thất</h2>
    </div>
    
    <div class="row g-4" id="home-product-list">
        <!-- Render qua JS -->
    </div>
</div>

<!-- BOTTOM BANNER -->
<div class="view-more-banner">
    <div class="container">
        <h2 style="font-family: 'Lora', serif; font-size: 2.5rem; margin-bottom: 1rem;">Bạn cần thêm không gian cảm hứng?</h2>
        <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 2rem;">Khám phá toàn bộ danh mục sản phẩm từ Nội Thất Hiện Đại để tìm được kiệt tác của riêng mình.</p>
        <a href="/webbanhang/index.php?url=product" class="btn-hero" style="background: #fff; color: #1a1a1a;">Đến Trưng Bày</a>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>

<script>
$(document).ready(function() {
    var initialCategories = <?php echo isset($categoriesJson) ? $categoriesJson : '[]'; ?>;
    var initialProducts = <?php echo isset($productsJson) ? $productsJson : '[]'; ?>;

    loadHomeCategories();
    loadHomeProducts();

    function loadHomeCategories() {
        const cats = initialCategories;
        if(!cats || !cats.length) return;
        const list = $('#home-category-list');
        list.empty();
        const icons = ['fa-couch', 'fa-bed', 'fa-chair', 'fa-door-open', 'fa-table', 'fa-lamp'];
        $.each(cats.slice(0, 6), function(index, c) {
            const icon = icons[index % icons.length];
            const item = `
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="/webbanhang/index.php?url=product&category_id=${c.id}" class="home-cat-card">
                        <div class="home-cat-icon"><i class="fas ${icon} fa-2x"></i></div>
                        <h5 class="home-cat-name">${c.name}</h5>
                    </a>
                </div>
            `;
            list.append(item);
        });
    }

    function loadHomeProducts() {
        const data = initialProducts;
        const productList = $('#home-product-list');
        productList.empty();
        if(!data || !data.length) return;
        
        const sliceData = data.slice(0, 8); // TOP 8 TỐT NHẤT
        
        $.each(sliceData, function(index, product) {
            const imgUrl = product.image ? `/webbanhang/${product.image}` : 'https://via.placeholder.com/300x250/f8fafc/64748b?text=No+Image';
            const priceFormatted = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(product.price);
            const baseRoute = "/webbanhang/index.php?url=product";

            const productItem = `
                <div class="col-md-4 col-sm-6 col-lg-3 d-flex">
                    <div class="prod-card w-100">
                        <a href="${baseRoute}/show/${product.id}" class="prod-img-wrap">
                            <span class="cat-badge">${product.category_name || 'Độc Quyền'}</span>
                            <img src="${imgUrl}" class="prod-img" alt="${product.name}">
                        </a>
                        <div class="prod-body">
                            <a href="${baseRoute}/show/${product.id}" style="text-decoration:none;"><h3 class="prod-name">${product.name}</h3></a>
                            <div class="prod-desc">${product.description || ''}</div>
                            <div class="prod-price">${priceFormatted}</div>
                            <a href="javascript:void(0);" onclick="addToCartGlobal(${product.id}, this)" class="btn-add-cart mt-auto">
                                <i class="fas fa-shopping-cart" style="margin-right:6px;"></i> Thêm Vào Giỏ
                            </a>
                        </div>
                    </div>
                </div>
            `;
            productList.append(productItem);
        });
    }
});
</script>
