<?php include __DIR__ . '/../shaders/header.php'; ?>
<style>
    body { background-color: #f7f5f2; }
    .form-wrapper {
        min-height: calc(100vh - 56px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
    }
    .form-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
        padding: 3.5rem;
        max-width: 800px;
        width: 100%;
        border: 1px solid #f0ebe3;
    }
    
    .form-title {
        font-family: 'Lora', serif;
        font-size: 2rem;
        color: #1a1a1a;
        font-weight: 600;
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .form-floating { position: relative; margin-bottom: 1.5rem; }
    .form-floating input, .form-floating textarea, .form-floating select {
        width: 100%; height: 58px;
        padding: 1.625rem 1rem 0.625rem;
        border: 1px solid #e5e7eb; border-radius: 12px;
        font-size: 1rem; background: #fff; color: #111827;
        transition: all 0.2s ease-in-out; outline: none;
        box-sizing: border-box; line-height: 1.25;
    }
    .form-floating textarea { min-height: 120px; padding-top: 1.625rem; }
    
    .form-floating input:focus, .form-floating textarea:focus, .form-floating select:focus { 
        border-color: #8c7b6c; background: #fff; box-shadow: 0 0 0 4px rgba(140, 123, 108, 0.08); 
    }
    
    .form-floating label {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        padding: 1rem; color: #9ca3af; pointer-events: none;
        transition: transform .2s ease-out, color .2s ease-out;
        transform-origin: 0 0; box-sizing: border-box;
    }
    .form-floating textarea + label { height: auto; }
    .form-floating input:focus + label, .form-floating input:not(:placeholder-shown) + label,
    .form-floating textarea:focus + label, .form-floating textarea:not(:placeholder-shown) + label,
    .form-floating select:focus + label, .form-floating select:not(:placeholder-shown) + label {
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem); color: #8c7b6c;
    }

    /* Custom File Input */
    .file-input-wrapper { margin-bottom: 2rem; }
    .file-input-label { display: block; font-size: 0.85rem; font-weight: 600; color: #6b7280; margin-bottom: 0.5rem; }
    .file-input {
        width: 100%; padding: 0.8rem; border: 1px dashed #d1d5db; border-radius: 12px; background: #faf9f8; color: #4b5563; font-size: 0.9rem;
    }

    .btn-submit {
        width: 100%;
        background: #8c7b6c;
        color: #fff;
        border: none;
        padding: 1.2rem;
        border-radius: 12px;
        font-size: 1.05rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(140, 123, 108, 0.3);
    }
    .btn-submit:hover { background: #6b5c50; transform: translateY(-3px); box-shadow: 0 10px 15px rgba(140, 123, 108, 0.4); }
    
    .btn-back { display: inline-block; text-align: center; width: 100%; margin-top: 1.5rem; color: #6b7280; font-size: 0.95rem; text-decoration: none; transition: color 0.2s; }
    .btn-back:hover { color: #1a1a1a; text-decoration: none; }
</style>

<div class="form-wrapper">
    <div class="form-card">
        <h1 class="form-title">THÊM SẢN PHẨM MỚI</h1>
        
        <form id="add-product-form">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-floating">
                        <input type="text" id="name" name="name" placeholder=" " required>
                        <label for="name">Tên sản phẩm</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-floating">
                        <input type="number" id="price" name="price" placeholder=" " step="0.01" required>
                        <label for="price">Giá (VND)</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating">
                        <select id="category_id" name="category_id" required>
                            <?php if (!empty($categories)): ?>
                                <option value="" disabled selected>-- Chọn danh mục --</option>
                                <?php foreach($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>"><?php echo htmlspecialchars($category->name); ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Không có danh mục</option>
                            <?php endif; ?>
                        </select>
                        <label for="category_id">Danh mục</label>
                    </div>
                </div>
                <div class="col-md-6 border-start ps-md-4 mb-4">
                    <label class="file-input-label">Hình ảnh sản phẩm</label>
                    <input type="file" id="image" name="image" class="file-input" accept="image/*">
                </div>
            </div>

            <div class="form-floating">
                <textarea id="description" name="description" placeholder=" " required></textarea>
                <label for="description">Mô tả tổng vật</label>
            </div>
            
            <div class="form-floating">
                <textarea id="features" name="features" placeholder=" " style="min-height:100px;"></textarea>
                <label for="features">Đặc điểm nổi bật (Mỗi dòng 1 ý)</label>
            </div>
            
            <button type="submit" class="btn-submit" id="addBtn">Lưu Trữ Tác Phẩm <i class="fas fa-arrow-right ms-2"></i></button>
        </form>

        <?php $baseRoute = isset($apiMode) && $apiMode ? '/webbanhang/api/product' : '/webbanhang/index.php?url=product'; ?>
        <a href="<?php echo $baseRoute; ?>" class="btn-back"><i class="fas fa-arrow-left me-2"></i> Trở về danh sách</a>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>
<script>
$(document).ready(function() {
    // Categories are rendered via PHP

    $('#add-product-form').on('submit', function(event) {
        event.preventDefault();
        const btn = $('#addBtn');
        const origText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Đang tải lên...').css('pointer-events','none');

        let formData = new FormData();
        formData.append('name', $('#name').val());
        formData.append('description', $('#description').val());
        formData.append('features', $('#features').val());
        formData.append('price', $('#price').val());
        formData.append('category_id', $('#category_id').val());
        
        const imageFile = $('#image')[0].files[0];
        if (imageFile) formData.append('image', imageFile);

        $.ajax({
            url: '/webbanhang/api/product',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.message === 'Product created successfully') {
                    btn.html('<i class="fas fa-check"></i> Hoàn Thành').css('background','#10b981');
                    setTimeout(() => window.location.href = '/webbanhang/index.php?url=product', 500);
                } else {
                    alert('Lỗi: Thêm sản phẩm thất bại');
                    btn.html(origText).css('pointer-events','auto');
                }
            },
            error: function(xhr, status, error) {
                alert('Có lỗi xảy ra: ' + (xhr.responseJSON?.message || error));
                btn.html(origText).css('pointer-events','auto');
            }
        });
    });
});
</script>