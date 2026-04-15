<?php include __DIR__ . '/../shaders/header.php'; ?>
<style>
    body { background-color: #f7f5f2; }
    .edit-wrapper {
        max-width: 700px;
        margin: 3rem auto;
        padding: 0 1rem;
    }
    .edit-card {
        background: #fff;
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #f0ebe3;
    }
    .edit-title {
        font-family: 'Lora', serif;
        font-size: 1.8rem;
        font-weight: 600;
        color: #1a1a1a;
        text-align: center;
        margin-bottom: 2rem;
    }
    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.4rem;
    }
    .form-control {
        background: #faf9f8;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        color: #1a1a1a;
        transition: all 0.3s;
    }
    .form-control:focus {
        background: #fff;
        border-color: #8c7b6c;
        box-shadow: 0 0 0 3px rgba(140, 123, 108, 0.1);
        outline: none;
    }
    .btn-save {
        display: block;
        width: 100%;
        padding: 0.9rem;
        background: #1a1a1a;
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 1.5rem;
    }
    .btn-save:hover { background: #8c7b6c; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(140, 123, 108, 0.3); }
    .btn-back {
        display: block;
        text-align: center;
        margin-top: 1.5rem;
        color: #9ca3af;
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.3s;
    }
    .btn-back:hover { color: #8c7b6c; }
    .current-img {
        max-width: 200px;
        border-radius: 12px;
        margin-top: 0.5rem;
        border: 1px solid #f0ebe3;
    }
</style>

<div class="edit-wrapper">
    <div class="edit-card">
        <h1 class="edit-title">Chỉnh Sửa Sản Phẩm</h1>
        <form action="/webbanhang/index.php?url=product/update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product->id); ?>">
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->image ?? ''); ?>">

            <div class="mb-3">
                <label for="name" class="form-label">Tên sản phẩm</label>
                <input type="text" id="name" name="name" class="form-control" required
                       value="<?php echo htmlspecialchars($product->name); ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả chi tiết</label>
                <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product->description ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Giá (VND)</label>
                <input type="number" id="price" name="price" class="form-control" step="1" required
                       value="<?php echo htmlspecialchars($product->price); ?>">
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Danh mục</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat->id; ?>" <?php echo ($product->category_id == $cat->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat->name); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Thay đổi hình ảnh (Tùy chọn)</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                <?php if (!empty($product->image)): ?>
                    <img src="/webbanhang/<?php echo htmlspecialchars($product->image); ?>" alt="Ảnh hiện tại" class="current-img">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-save">Lưu Cập Nhật <i class="fas fa-check ms-2"></i></button>
        </form>
        <a href="/webbanhang/index.php?url=product" class="btn-back"><i class="fas fa-arrow-left me-2"></i>Trở về danh sách</a>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>