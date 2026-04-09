<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; color: #1a1a1a; font-family: 'Inter', system-ui, sans-serif; }
    
    .admin-wrapper {
        max-width: 1300px;
        margin: 3rem auto;
        padding: 0 1.5rem;
        min-height: calc(100vh - 100px);
    }
    
    .admin-header {
        font-family: 'Lora', serif;
        font-size: 2.2rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 2rem;
        border-bottom: 1px solid #f0ebe3;
        padding-bottom: 1.5rem;
    }

    .glass-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.04);
        padding: 2.5rem;
        border: 1px solid #f0ebe3;
        height: 100%;
    }
    
    .card-title-custom {
        font-family: 'Lora', serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 0.8rem;
    }
    .card-title-custom::after { content: ''; position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: #8c7b6c; border-radius: 2px; }

    /* Form Styles */
    .form-floating { position: relative; margin-bottom: 1.5rem; }
    .form-floating input {
        width: 100%; height: 58px;
        padding: 1.625rem 1rem 0.625rem;
        border: 1px solid #e5e7eb; border-radius: 12px;
        font-size: 1rem; background: #fff; color: #111827;
        transition: all 0.2s ease-in-out; outline: none;
        box-sizing: border-box; line-height: 1.25;
    }
    .form-floating input:focus { border-color: #8c7b6c; background: #fff; box-shadow: 0 0 0 4px rgba(140, 123, 108, 0.08); }
    .form-floating label {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        padding: 1rem; color: #9ca3af; pointer-events: none;
        transition: transform .2s ease-out, color .2s ease-out;
        transform-origin: 0 0; box-sizing: border-box;
    }
    .form-floating input:focus + label, .form-floating input:not(:placeholder-shown) + label {
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem); color: #8c7b6c;
    }

    .file-input-label { display: block; font-size: 0.85rem; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; }
    .file-input { width: 100%; padding: 0.8rem; border: 1px dashed #d1d5db; border-radius: 12px; background: #faf9f8; color: #4b5563; font-size: 0.95rem; margin-bottom: 1.5rem; }

    .btn-submit {
        width: 100%; background: #8c7b6c; color: #fff; border: none; padding: 1.1rem; border-radius: 12px; font-size: 1rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; transition: all 0.3s;
    }
    .btn-submit:hover { background: #6b5c50; transform: translateY(-3px); box-shadow: 0 10px 15px rgba(140, 123, 108, 0.3); }

    /* Slider List Grid */
    .slider-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
    .slider-item-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #f0ebe3;
        transition: all 0.4s ease;
        position: relative;
    }
    .slider-item-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.06); }
    .slider-img-wrap { width: 100%; height: 180px; overflow: hidden; background: #fdfdfc; }
    .slider-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
    
    .slider-info { padding: 1.25rem; }
    .slider-item-title { font-size: 1.05rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.4rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-family: 'Lora', serif;}
    .slider-item-subtitle { font-size: 0.85rem; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 0.5rem; }
    .slider-item-link { font-size: 0.75rem; color: #8c7b6c; font-style: italic; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .btn-delete-slide {
        position: absolute;
        top: 10px; right: 10px;
        width: 35px; height: 35px;
        background: #fff; color: #ef4444; border: none; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer; transition: 0.3s; opacity: 0; transform: scale(0.8);
    }
    .slider-item-card:hover .btn-delete-slide { opacity: 1; transform: scale(1); }
    .btn-delete-slide:hover { background: #ef4444; color: #fff; }

    .alert-error { background: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 12px; font-size: 0.95rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px; border: 1px solid #fecaca; }
    .alert-warning { background: #fef3c7; color: #b45309; padding: 1rem; border-radius: 12px; font-size: 0.9rem; margin-bottom: 1.5rem; border: 1px solid #fde68a; }
</style>

<div class="admin-wrapper">
    <div class="admin-header">Quản Trị Visual Chắp Cánh</div>

    <?php if (isset($_SESSION['slider_error'])): ?>
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div><?php echo htmlspecialchars($_SESSION['slider_error']); unset($_SESSION['slider_error']); ?></div>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Add New Form -->
        <div class="col-lg-4">
            <div class="glass-card">
                <h3 class="card-title-custom">Thêm Bức Tranh Mới</h3>
                
                <?php if (count($sliders ?? []) >= 5): ?>
                    <div class="alert-warning"><i class="fas fa-info-circle me-2"></i> Khung tranh đã đầy (5/5). Vui lòng gỡ bớt tác phẩm cũ trước khi đưa tác phẩm mới lên.</div>
                <?php else: ?>
                    <form action="/webbanhang/index.php?url=admin/addSlider" method="POST" enctype="multipart/form-data">
                        <label class="file-input-label">Ảnh Slide (Tỉ lệ ngang 16:9)</label>
                        <input type="file" name="image" class="file-input" accept="image/*" required>
                        
                        <div class="form-floating">
                            <input type="text" name="title" id="sl_title" placeholder=" ">
                            <label for="sl_title">Dòng Chữ Lớn (Tiêu đề)</label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="text" name="subtitle" id="sl_subtitle" placeholder=" ">
                            <label for="sl_subtitle">Dòng Chữ Nhỏ (Mô tả phụ)</label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="text" name="link_url" id="sl_link" placeholder=" ">
                            <label for="sl_link">Đường dẫn khi ấn (Để trống mđ: /product)</label>
                        </div>
                        
                        <button type="submit" class="btn-submit">Đăng Tải Tác Phẩm</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Slider List -->
        <div class="col-lg-8">
            <div class="glass-card">
                <h3 class="card-title-custom">Tác Phẩm Đang Trưng Bày (<?php echo count($sliders ?? []); ?>/5)</h3>
                
                <?php if (empty($sliders)): ?>
                    <div style="text-align: center; padding: 4rem 1rem; color: #9ca3af;">
                        <i class="fas fa-image fa-3x mb-3" style="color: #e5e7eb;"></i>
                        <p>Khu vực này hiện đang trống. Cửa hàng sẽ dùng ảnh minh hoạ ngẫu nhiên.</p>
                    </div>
                <?php else: ?>
                    <div class="slider-grid">
                        <?php foreach($sliders as $slide): ?>
                            <div class="slider-item-card">
                                <div class="slider-img-wrap">
                                    <img src="/webbanhang/<?php echo htmlspecialchars($slide->image_path); ?>" alt="Slide">
                                </div>
                                <div class="slider-info">
                                    <div class="slider-item-title"><?php echo htmlspecialchars($slide->title ?: "Không Mới: Tiêu Điểm"); ?></div>
                                    <div class="slider-item-subtitle"><?php echo htmlspecialchars($slide->subtitle ?: "Hãy chọn mua sắm cùng chúng tôi"); ?></div>
                                    <div class="slider-item-link" title="<?php echo htmlspecialchars($slide->link_url); ?>">
                                        <i class="fas fa-link me-1"></i> <?php echo htmlspecialchars($slide->link_url ?: "Mặc định (Chuyển trang sp)"); ?>
                                    </div>
                                </div>
                                <a href="/webbanhang/index.php?url=admin/deleteSlider/<?php echo $slide->id; ?>" class="btn-delete-slide" onclick="return confirm('Gỡ bỏ bức ảnh này khỏi trang chủ?');" title="Gỡ hình">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>
