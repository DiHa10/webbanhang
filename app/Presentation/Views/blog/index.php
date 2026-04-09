<?php include_once __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; color: #1a1a1a; font-family: 'Inter', sans-serif; }
    
    /* ===== BLOG HERO ===== */
    .blog-hero {
        position: relative;
        height: 60vh;
        min-height: 400px;
        background: linear-gradient(rgba(26,26,26,0.6), rgba(26,26,26,0.8)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
        margin-top: -1.5rem;
    }
    .blog-hero-content {
        max-width: 800px;
        padding: 0 20px;
    }
    .blog-hero-title {
        font-family: 'Lora', serif;
        font-size: 3.5rem;
        font-weight: 500;
        margin-bottom: 1rem;
        animation: fadeInUp 1s ease;
    }
    .blog-hero-subtitle {
        font-weight: 300;
        font-size: 1.15rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        animation: fadeInUp 1.3s ease;
        opacity: 0.9;
    }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    /* ===== BLOG CONTENT ===== */
    .blog-section {
        padding: 5rem 0;
    }
    
    /* FEATURED POST */
    .featured-post {
        display: flex;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        margin-bottom: 5rem;
        transition: transform 0.4s ease;
    }
    .featured-post:hover { transform: translateY(-5px); box-shadow: 0 25px 50px rgba(0,0,0,0.08); }
    .fp-img-wrap {
        flex: 1.2;
        overflow: hidden;
    }
    .fp-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }
    .featured-post:hover .fp-img-wrap img { transform: scale(1.05); }
    .fp-content {
        flex: 1;
        padding: 4rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #fff;
    }
    @media (max-width: 992px) {
        .featured-post { flex-direction: column; }
        .fp-img-wrap { height: 350px; }
        .fp-content { padding: 3rem 2rem; }
    }
    .post-tag {
        font-size: 0.8rem;
        font-weight: 700;
        color: #8c7b6c;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 1rem;
    }
    .fp-title {
        font-family: 'Lora', serif;
        font-size: 2.2rem;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        line-height: 1.3;
        text-decoration: none;
        transition: color 0.3s;
    }
    .fp-title:hover { color: #8c7b6c; text-decoration: none; }
    .post-excerpt {
        color: #6b7280;
        font-size: 1.05rem;
        line-height: 1.8;
        margin-bottom: 2rem;
    }

    /* GRID POSTS */
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2.5rem;
    }
    @media (max-width: 900px) { .blog-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .blog-grid { grid-template-columns: 1fr; } }

    .blog-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.03);
        transition: all 0.4s ease;
        display: flex;
        flex-direction: column;
        border: 1px solid #f0ebe3;
    }
    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08);
        border-color: #e5e7eb;
    }
    .bc-img-wrap {
        height: 250px;
        overflow: hidden;
    }
    .bc-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .blog-card:hover .bc-img-wrap img { transform: scale(1.05); }
    .bc-body {
        padding: 2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .bc-title {
        font-family: 'Lora', serif;
        font-size: 1.25rem;
        color: #1a1a1a;
        margin-bottom: 1rem;
        font-weight: 600;
        text-decoration: none;
        line-height: 1.4;
    }
    .bc-title:hover { color: #8c7b6c; text-decoration: none; }
    .bc-excerpt {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    /* COMMON ASSETS */
    .post-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 0.85rem;
        color: #9ca3af;
        margin-bottom: 1rem;
    }
    .btn-readmore {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1a1a1a;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        padding-bottom: 3px;
        transition: color 0.3s;
        align-self: flex-start;
    }
    .btn-readmore::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0;
        width: 100%; height: 1.5px;
        background: #1a1a1a;
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.4s ease;
    }
    .btn-readmore:hover { color: #8c7b6c; text-decoration: none;}
    .btn-readmore:hover::after { transform: scaleX(1); transform-origin: left; background: #8c7b6c; }
</style>

<div class="blog-hero">
    <div class="blog-hero-content">
        <h1 class="blog-hero-title">Câu Chuyện Nội Thất</h1>
        <div class="blog-hero-subtitle">Cảm hứng nghệ thuật cho không gian sống của bạn</div>
    </div>
</div>

<div class="container blog-section">
    <!-- Featured Post -->
    <article class="featured-post">
        <div class="fp-img-wrap">
            <img src="https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?q=80&w=1200&auto=format&fit=crop" alt="Featured Blog">
        </div>
        <div class="fp-content">
            <div class="post-tag">Xu hướng thiết kế</div>
            <a href="javascript:alert('Tính năng đang phát triển!');" class="fp-title">
                Mang phong cách Wabi-Sabi vào không gian sống hiện đại
            </a>
            <div class="post-meta">
                <span><i class="far fa-calendar-alt mr-2"></i> 05/04/2026</span>
                <span><i class="far fa-user mr-2"></i> Kiến trúc sư Tuấn Anh</span>
            </div>
            <p class="post-excerpt">
                Wabi-Sabi không chỉ là một phong cách nội thất, nó là triết lý trân trọng những vẻ đẹp không hoàn hảo và thuận theo tự nhiên. Khám phá cách kết hợp vật liệu thô mộc như gỗ nhám, linen và đất nung để mang lại cảm giác bình yên đến lạ kì giữa lòng thành thị ồn ào.
            </p>
            <a href="javascript:alert('Tính năng đang phát triển!');" class="btn-readmore">Đọc Toàn Bộ Câu Chuyện <i class="fas fa-arrow-right"></i></a>
        </div>
    </article>

    <!-- Grid Posts -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h3 style="font-family: 'Lora', serif; font-size: 2rem; color: #1a1a1a;">Góc Chia Sẻ</h3>
    </div>

    <div class="blog-grid">
        <!-- Card 1 -->
        <article class="blog-card">
            <a href="javascript:alert('Tính năng đang phát triển!');" class="bc-img-wrap">
                <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?q=80&w=800&auto=format&fit=crop" alt="Post 1">
            </a>
            <div class="bc-body">
                <div class="post-meta">
                    <span><i class="far fa-calendar-alt mr-1"></i> 01/04/2026</span>
                </div>
                <a href="javascript:alert('Tính năng đang phát triển!');" class="bc-title">
                    Cách phối màu Sofa nâu bò trong phòng khách ngập nắng
                </a>
                <p class="bc-excerpt">
                    Màu nâu bò (Cognac) liên tục làm mưa làm gió trong làng nội thất Âu Châu. Hãy cùng tìm hiểu công thức phối màu nền trắng kem và thảm đan...
                </p>
                <a href="javascript:alert('Tính năng đang phát triển!');" class="btn-readmore">Đọc thêm</a>
            </div>
        </article>

        <!-- Card 2 -->
        <article class="blog-card">
            <a href="javascript:alert('Tính năng đang phát triển!');" class="bc-img-wrap">
                <img src="https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?q=80&w=800&auto=format&fit=crop" alt="Post 2">
            </a>
            <div class="bc-body">
                <div class="post-meta">
                    <span><i class="far fa-calendar-alt mr-1"></i> 28/03/2026</span>
                </div>
                <a href="javascript:alert('Tính năng đang phát triển!');" class="bc-title">
                    Nghệ thuật chiếu sáng: Linh hồn thực sự của góc làm việc
                </a>
                <p class="bc-excerpt">
                    Đừng để thiết kế của bạn bị phá hỏng bởi một bóng đèn tuýp. Hướng dẫn chọn ánh sáng Vàng/Trắng và đèn thả trần (pendant light) phù hợp.
                </p>
                <a href="javascript:alert('Tính năng đang phát triển!');" class="btn-readmore">Đọc thêm</a>
            </div>
        </article>

        <!-- Card 3 -->
        <article class="blog-card">
            <a href="javascript:alert('Tính năng đang phát triển!');" class="bc-img-wrap">
                <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?q=80&w=800&auto=format&fit=crop" alt="Post 3">
            </a>
            <div class="bc-body">
                <div class="post-meta">
                    <span><i class="far fa-calendar-alt mr-1"></i> 20/03/2026</span>
                </div>
                <a href="javascript:alert('Tính năng đang phát triển!');" class="bc-title">
                    Bảo quản bàn ăn gỗ sồi chuẩn chuyên gia
                </a>
                <p class="bc-excerpt">
                    Gỗ sồi (Oak) cứng chắc nhưng độ nhạy cảm với nhiệt và nước lại rất cao. Bộ bí kíp 5 bước giúp mặt bàn nhà bạn luôn bóng loáng như ngày đầu.
                </p>
                <a href="javascript:alert('Tính năng đang phát triển!');" class="btn-readmore">Đọc thêm</a>
            </div>
        </article>
    </div>
</div>

<?php include_once __DIR__ . '/../shaders/footer.php'; ?>
