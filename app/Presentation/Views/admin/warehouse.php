<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; }
    .wh-page { max-width: 1200px; margin: 2.5rem auto; padding: 0 1.5rem; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .page-title { font-family: 'Lora', serif; font-size: 2rem; font-weight: 600; color: #1a1a1a; }
    .page-subtitle { color: #9ca3af; font-size: 0.9rem; margin-top: 4px; }

    .wh-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .wh-stat { background: #fff; border-radius: 16px; padding: 1.5rem; border: 1px solid #f0ebe3; text-align: center; transition: transform 0.3s; }
    .wh-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .wh-stat .s-emoji { font-size: 1.8rem; margin-bottom: 0.5rem; }
    .wh-stat .s-num { font-family: 'Lora', serif; font-size: 2rem; font-weight: 700; color: #1a1a1a; }
    .wh-stat .s-txt { font-size: 0.78rem; color: #9ca3af; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-top: 0.3rem; }

    .search-bar { display: flex; align-items: center; background: #fff; border: 1px solid #f0ebe3; border-radius: 14px; padding: 0 1rem; margin-bottom: 1.5rem; }
    .search-bar i { color: #9ca3af; }
    .search-bar input { flex: 1; border: none; padding: 0.9rem 0.8rem; font-size: 0.95rem; background: transparent; outline: none; }

    .wh-table-wrap { background: #fff; border-radius: 20px; border: 1px solid #f0ebe3; box-shadow: 0 10px 30px rgba(0,0,0,0.03); overflow: hidden; }
    .wh-table { width: 100%; border-collapse: collapse; }
    .wh-table th { background: #faf9f8; color: #8c7b6c; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; padding: 1rem 1.2rem; text-align: left; border-bottom: 1px solid #f0ebe3; }
    .wh-table td { padding: 0.9rem 1.2rem; border-bottom: 1px solid #faf9f8; vertical-align: middle; font-size: 0.92rem; }
    .wh-table tbody tr { transition: background 0.2s; }
    .wh-table tbody tr:hover { background: #faf9f8; }

    .prod-cell { display: flex; align-items: center; gap: 12px; }
    .prod-thumb { width: 50px; height: 50px; border-radius: 10px; object-fit: cover; border: 1px solid #f0ebe3; background: #faf9f8; }
    .prod-n { font-weight: 600; color: #1a1a1a; }
    .prod-cat { font-size: 0.8rem; color: #9ca3af; }

    .stock-input { width: 80px; padding: 6px 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.9rem; font-weight: 600; text-align: center; outline: none; transition: border 0.3s; }
    .stock-input:focus { border-color: #8c7b6c; }

    .stock-status { padding: 4px 12px; border-radius: 30px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .stock-ok { background: #d1fae5; color: #059669; }
    .stock-low { background: #fef3c7; color: #d97706; }
    .stock-out { background: #fee2e2; color: #dc2626; }

    .btn-save-stock { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: 1px solid #e5e7eb; background: #fff; color: #6b7280; cursor: pointer; transition: all 0.2s; font-size: 0.85rem; }
    .btn-save-stock:hover { background: #d1fae5; color: #059669; border-color: #6ee7b7; }

    @media (max-width: 768px) { .wh-stats { grid-template-columns: repeat(2, 1fr); } }
</style>

<div class="wh-page">
    <div class="page-header">
        <div>
            <h1 class="page-title"><i class="fas fa-warehouse" style="color: #8c7b6c; margin-right: 10px;"></i>Quản Lý Kho</h1>
            <p class="page-subtitle">Theo dõi tồn kho, cập nhật số lượng sản phẩm</p>
        </div>
    </div>

    <div class="wh-stats">
        <div class="wh-stat"><div class="s-emoji">📦</div><div class="s-num" id="st-total">-</div><div class="s-txt">Tổng sản phẩm</div></div>
        <div class="wh-stat"><div class="s-emoji">✅</div><div class="s-num" id="st-instock">-</div><div class="s-txt">Còn hàng</div></div>
        <div class="wh-stat"><div class="s-emoji">⚠️</div><div class="s-num" id="st-low">-</div><div class="s-txt">Sắp hết (≤10)</div></div>
        <div class="wh-stat"><div class="s-emoji">🚫</div><div class="s-num" id="st-out">-</div><div class="s-txt">Hết hàng</div></div>
    </div>

    <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" id="whSearch" placeholder="Tìm kiếm sản phẩm...">
    </div>

    <div class="wh-table-wrap">
        <table class="wh-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Đã bán</th>
                    <th>Trạng thái</th>
                    <th style="text-align:center;">Lưu</th>
                </tr>
            </thead>
            <tbody id="whBody">
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:#d1d5db;"><i class="fas fa-spinner fa-spin me-2"></i>Đang tải...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let allProducts = [];

function loadWarehouse() {
    $.getJSON('/webbanhang/index.php?url=admin/listWarehouse', function(data) {
        allProducts = data;
        renderTable(data);
        updateStats(data);
    });
}

function updateStats(data) {
    $('#st-total').text(data.length);
    $('#st-instock').text(data.filter(p => parseInt(p.stock) > 10).length);
    $('#st-low').text(data.filter(p => parseInt(p.stock) > 0 && parseInt(p.stock) <= 10).length);
    $('#st-out').text(data.filter(p => parseInt(p.stock) <= 0).length);
}

function getStockBadge(stock) {
    stock = parseInt(stock);
    if (stock <= 0) return '<span class="stock-status stock-out">Hết hàng</span>';
    if (stock <= 10) return '<span class="stock-status stock-low">Sắp hết</span>';
    return '<span class="stock-status stock-ok">Còn hàng</span>';
}

function renderTable(data) {
    const tbody = $('#whBody');
    tbody.empty();
    if (!data.length) {
        tbody.html('<tr><td colspan="7" style="text-align:center;padding:3rem;color:#d1d5db;">Không có sản phẩm</td></tr>');
        return;
    }
    data.forEach(p => {
        const imgSrc = p.image ? (p.image.startsWith('http') ? p.image : '/webbanhang/' + p.image) : 'https://via.placeholder.com/50x50/faf9f8/9ca3af?text=N';
        tbody.append(`
            <tr data-id="${p.id}">
                <td><span style="font-weight:700;color:#8c7b6c;">#${p.id}</span></td>
                <td>
                    <div class="prod-cell">
                        <img src="${imgSrc}" class="prod-thumb" alt="">
                        <div>
                            <div class="prod-n">${p.name}</div>
                            <div class="prod-cat">${p.category_name || '—'}</div>
                        </div>
                    </div>
                </td>
                <td style="font-weight:600;">${new Intl.NumberFormat('vi-VN').format(p.price)}đ</td>
                <td><input type="number" class="stock-input" value="${p.stock}" min="0" data-id="${p.id}"></td>
                <td><span style="font-weight:600;">${p.total_sold}</span> <span style="color:#9ca3af;">sp</span></td>
                <td>${getStockBadge(p.stock)}</td>
                <td style="text-align:center;">
                    <button class="btn-save-stock" onclick="saveStock(${p.id})" title="Lưu tồn kho"><i class="fas fa-check"></i></button>
                </td>
            </tr>
        `);
    });
}

function saveStock(id) {
    const input = $(`.stock-input[data-id="${id}"]`);
    const stock = parseInt(input.val());
    const btn = input.closest('tr').find('.btn-save-stock');
    btn.html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: '/webbanhang/index.php?url=admin/setStock',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id, stock }),
        success: function(res) {
            if (res.success) {
                btn.html('<i class="fas fa-check"></i>').css({'background':'#d1fae5','color':'#059669','border-color':'#6ee7b7'});
                setTimeout(() => { btn.css({'background':'','color':'','border-color':''}); loadWarehouse(); }, 1000);
            } else {
                alert(res.message || 'Lỗi!');
                btn.html('<i class="fas fa-check"></i>');
            }
        }
    });
}

$('#whSearch').on('input', function() {
    const q = this.value.toLowerCase();
    renderTable(allProducts.filter(p => p.name.toLowerCase().includes(q) || (p.category_name || '').toLowerCase().includes(q)));
});

$(document).ready(loadWarehouse);
</script>

<?php include __DIR__ . '/../shaders/footer.php'; ?>
