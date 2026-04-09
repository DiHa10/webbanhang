<?php include __DIR__ . '/../shaders/header.php'; ?>
<style>
    body { background-color: #fcfcfc; color: #1e293b; font-family: 'Inter', system-ui, sans-serif; }
    .form-container {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .form-control {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #1e293b;
    }
    .form-control:focus {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .btn-gradient {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white; border: none; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .btn-gradient:hover {
        transform: translateY(-2px); box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3); color: white;
    }
</style>

<div class="container mt-5 mb-5" style="max-width: 700px;">
    <div class="form-container">
        <h1 class="text-center mb-4 text-uppercase font-weight-bold text-dark" style="letter-spacing: 1px;">Sửa Sản Phẩm</h1>
        <form id="edit-product-form">
            <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($product->id); ?>">
            
            <div class="form-group mb-4">
                <label for="name" class="text-secondary text-uppercase small font-weight-bold">Tên sản phẩm</label>
                <input type="text" id="name" name="name" class="form-control form-control-lg rounded-pill px-4" required>
            </div>
            <div class="form-group mb-4">
                <label for="description" class="text-secondary text-uppercase small font-weight-bold">Mô tả chi tiết</label>
                <textarea id="description" name="description" class="form-control rounded-lg p-3" rows="5" required></textarea>
            </div>
            <div class="form-group mb-4">
                <label for="features" class="text-secondary text-uppercase small font-weight-bold">Đặc điểm nổi bật (Mỗi dòng 1 ý)</label>
                <textarea id="features" name="features" class="form-control rounded-lg p-3" rows="4"></textarea>
            </div>
            <div class="form-group mb-4">
                <label for="price" class="text-secondary text-uppercase small font-weight-bold">Giá (VND)</label>
                <input type="number" id="price" name="price" class="form-control form-control-lg rounded-pill px-4" step="0.01" required>
            </div>
            
            <div class="form-group mb-4">
                <label for="image" class="text-secondary text-uppercase small font-weight-bold">Thay Đổi Hình Ảnh (Tùy chọn)</label>
                <input type="file" id="image" name="image" class="form-control form-control-lg rounded-pill px-4 pt-2 pb-1" accept="image/*">
            </div>

            <div class="form-group mb-5">
                <label for="category_id" class="text-secondary text-uppercase small font-weight-bold">Danh mục</label>
                <select id="category_id" name="category_id" class="form-control form-control-lg rounded-pill px-4" required>
                </select>
            </div>
            <button type="submit" class="btn btn-gradient btn-lg btn-block rounded-pill py-3">Lưu cập nhật qua API</button>
        </form>
        <div class="text-center mt-4">
            <?php $baseRoute = isset($apiMode) && $apiMode ? '/webbanhang/api/product' : '/webbanhang/Product'; ?>
            <a href="<?php echo $baseRoute; ?>" class="text-muted text-decoration-none"><i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>
<script>
$(document).ready(function() {
    const productId = $('#id').val();

    // 1. Fetch categories
    $.getJSON('/webbanhang/api/category', function(categories) {
        const categorySelect = $('#category_id');
        $.each(categories, function(index, category) {
            categorySelect.append($('<option></option>').val(category.id).text(category.name));
        });

        // 2. Fetch specific product to populate fields
        $.getJSON(`/webbanhang/api/product/${productId}`, function(productData) {
            $('#name').val(productData.name);
            $('#description').val(productData.description);
            $('#features').val(productData.features);
            $('#price').val(productData.price);
            $('#category_id').val(productData.category_id);
        });
    });

    // Submitting updates via API with POST to fake PUT due to PHP constraints
    $('#edit-product-form').on('submit', function(event) {
        event.preventDefault();
        
        let formData = new FormData();
        formData.append('id', productId);
        formData.append('name', $('#name').val());
        formData.append('description', $('#description').val());
        formData.append('features', $('#features').val());
        formData.append('price', $('#price').val());
        formData.append('category_id', $('#category_id').val());
        
        const imageFile = $('#image')[0].files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }

        $.ajax({
            url: `/webbanhang/api/product`, // Gửi POST để workaround upload file
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.message === 'Product updated successfully') {
                    alert('Sửa Sản Phẩm thành công');
                    window.location.href = '/webbanhang/Product/index';
                } else {
                    alert('Cập nhật thất bại');
                }
            },
            error: function(xhr, status, error) {
                console.error('Lỗi cập nhật:', xhr.responseText);
                alert('Có lỗi xảy ra qua jQuery API: ' + (xhr.responseJSON?.message || error));
            }
        });
    });
});
</script>