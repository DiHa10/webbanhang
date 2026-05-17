<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    :root {
        --bg-color: #f7f5f2;
        --card-bg: #ffffff;
        --text-pure: #1a1a1a;
        --text-mutated: #6b7280;
        --border-soft: #f0ebe3;
        --primary-accent: #8c7b6c;
        --success-accent: #10b981;
        --danger-accent: #ef4444;
        --warning-accent: #f59e0b;
        --shadow-sm: 0 10px 20px rgba(0,0,0,0.02);
        --shadow-md: 0 15px 35px rgba(0,0,0,0.04);
        --radius-md: 12px;
        --radius-lg: 24px;
    }

    body {
        background-color: var(--bg-color);
        color: var(--text-pure);
    }

    .admin-container {
        max-width: 1300px;
        margin: 3rem auto;
        padding: 0 1.5rem;
    }

    .page-title {
        font-family: 'Lora', serif;
        font-size: 2rem;
        font-weight: 600;
        color: var(--text-pure);
        margin-bottom: 2rem;
    }

    .modern-card {
        background: var(--card-bg);
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 2rem;
        margin-bottom: 2rem;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .modern-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }

    .card-header-title {
        font-family: 'Lora', serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-pure);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid var(--border-soft);
        padding-bottom: 1rem;
    }

    /* Summary Stats */
    .stat-box { display: flex; flex-direction: column; }
    .stat-label {
        font-size: 0.85rem; font-weight: 700; color: var(--primary-accent);
        text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem;
    }
    .stat-value { font-size: 2.2rem; font-weight: 700; color: var(--text-pure); line-height: 1.2; font-family: 'Lora', serif; }
    .stat-value.revenue { color: var(--text-pure); }
    .stat-value.stock { color: var(--primary-accent); }

    /* Tables */
    .modern-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .modern-table th {
        background: #faf9f8; color: var(--primary-accent); font-weight: 700; font-size: 0.8rem;
        text-transform: uppercase; letter-spacing: 1px; padding: 1rem; text-align: left;
        border-top: 1px solid var(--border-soft); border-bottom: 1px solid var(--border-soft);
    }
    .modern-table th:first-child { border-top-left-radius: var(--radius-md); border-left: 1px solid var(--border-soft); }
    .modern-table th:last-child { border-top-right-radius: var(--radius-md); border-right: 1px solid var(--border-soft); }
    .modern-table td {
        padding: 1.25rem 1rem; border-bottom: 1px solid var(--border-soft);
        color: var(--text-pure); vertical-align: middle;
    }
    .modern-table tbody tr:hover { background-color: #faf9f8; }

    .badge-soft { padding: 0.4rem 0.8rem; border-radius: 30px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; }
    .badge-soft.month { background: #f0ebe3; color: var(--primary-accent); }
    .badge-soft.order-id { background: #1a1a1a; color: #fff; }
    .badge-soft.info { background: #dbeafe; color: #1d4ed8; }
    .badge-soft.primary { background: #e0e7ff; color: #4338ca; }
    .badge-soft.warning { background: #fef3c7; color: #d97706; }
    .badge-soft.success { background: #d1fae5; color: #059669; }
    .badge-soft.danger { background: #fee2e2; color: #dc2626; }

    /* Product Tags inline */
    .inline-product-tag {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: #faf9f8; border: 1px solid var(--border-soft); border-radius: 8px;
        padding: 0.3rem 0.6rem; font-size: 0.8rem; margin: 0.2rem 0.2rem 0 0; color: var(--text-pure);
    }
    .inline-product-tag .qty { font-weight: 700; color: var(--primary-accent); }

    /* Editable Stock List */
    .stock-list { max-height: 400px; overflow-y: auto; padding-right: 0.5rem; }
    .stock-list::-webkit-scrollbar { width: 6px; }
    .stock-list::-webkit-scrollbar-track { background: transparent; }
    .stock-list::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }

    .stock-item {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem; border: 1px solid var(--border-soft);
        border-radius: var(--radius-md); margin-bottom: 0.75rem;
        background: #fff; transition: all 0.3s ease;
    }
    .stock-item:hover { border-color: var(--primary-accent); box-shadow: 0 4px 12px rgba(140, 123, 108, 0.08); }

    .stock-info { flex: 1; }
    .stock-name { font-weight: 600; font-size: 0.95rem; margin-bottom: 0.2rem; color: var(--text-pure); }
    .stock-price { font-size: 0.85rem; color: var(--text-mutated); }

    .stock-controls { display: flex; align-items: center; gap: 0.5rem; }
    .stock-btn {
        width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-soft); background: #faf9f8; border-radius: 8px;
        cursor: pointer; color: var(--primary-accent); transition: all 0.2s; font-weight: bold;
    }
    .stock-btn:hover { border-color: var(--primary-accent); background: var(--primary-accent); color: #fff; }
    
    .stock-input {
        width: 50px; text-align: center; border: 1px solid var(--border-soft); border-radius: 8px;
        padding: 0.4rem; font-size: 0.9rem; font-weight: 600; color: var(--text-pure); outline: none;
    }
    .stock-input:focus { border-color: var(--primary-accent); }
    .stock-input.low-stock { color: var(--danger-accent); border-color: #fca5a5; background: #fef2f2; }

    /* Chart card */
    .chart-card {
        background: var(--card-bg); border: 1px solid var(--border-soft);
        border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
        padding: 2rem; margin-bottom: 2rem; transition: transform 0.3s ease;
    }
    .chart-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }

    /* Buttons */
    .btn-primary { background: var(--text-pure); border: none; padding: 0.6rem 1.5rem; border-radius: 8px; font-weight: 600; }
    .btn-primary:hover { background: var(--primary-accent); }

    /* Modals */
    .modal-content { border-radius: var(--radius-lg); border: none; box-shadow: var(--shadow-md); }
    .modal-header { border-bottom: 1px solid var(--border-soft); padding: 1.5rem; }
    .modal-body { padding: 1.5rem; }
    .modal-footer { border-top: 1px solid var(--border-soft); padding: 1.5rem; }
    .form-control { padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: none !important; }
    .form-control:focus { border-color: var(--primary-accent); }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="admin-container">
    <div class="page-title"><i class="fas fa-chart-pie mr-2" style="color: var(--primary-accent);"></i>Tổng quan Doanh thu & Kho</div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger shadow-sm rounded"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Summary Cards -->
    <div class="row mb-2">
        <div class="col-md-6 mb-3">
            <div class="modern-card">
                <div class="stat-box">
                    <div class="stat-label">Tổng Doanh Thu Của Hệ Thống</div>
                    <div class="stat-value revenue"><?php echo number_format($totalRevenue ?? 0, 0, ',', '.'); ?> đ</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="modern-card">
                <div class="stat-box">
                    <div class="stat-label">Số Lượng Hàng Trong Kho</div>
                    <div class="stat-value stock"><?php echo number_format($totalStock ?? 0, 0, ',', '.'); ?> <span style="font-size:1rem;font-weight:500;color:var(--text-mutated);">sản phẩm</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="chart-card mb-2">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="card-header-title border-0 mb-0 pb-0" style="font-size:0.95rem;">
                <i class="fas fa-chart-line" style="color: var(--primary-accent);"></i> Xu hướng doanh thu / Mua sắm
            </div>
            <select class="form-control form-control-sm w-auto border-0 bg-light" style="border-radius:20px;font-size:0.8rem;">
                <option>Tất cả (All time)</option>
                <option>Năm nay</option>
            </select>
        </div>
        <div style="height:260px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Monthly Revenue & Stock Management -->
    <div class="row mb-2">
        <div class="col-lg-6 mb-3">
            <div class="modern-card">
                <div class="card-header-title"><i class="far fa-calendar-alt" style="color: var(--primary-accent);"></i> Doanh thu theo tháng</div>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Tháng</th>
                                <th class="text-right">Doanh Thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (!empty($monthlyRevenue)): 
                                $reversedMonthlyRevenue = array_reverse($monthlyRevenue);
                                foreach ($reversedMonthlyRevenue as $rev): 
                            ?>
                                <tr>
                                    <td><span class="badge-soft month"><?php echo htmlspecialchars($rev->month); ?></span></td>
                                    <td class="text-right font-weight-bold" style="color:var(--success-accent);">
                                        <?php echo number_format($rev->monthly_total, 0, ',', '.'); ?> đ
                                    </td>
                                </tr>
                            <?php 
                                endforeach; 
                            else: 
                            ?>
                                <tr><td colspan="2" class="text-center text-muted py-3">Chưa có dữ liệu</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="modern-card">
                <div class="card-header-title"><i class="fas fa-boxes" style="color: var(--primary-accent);"></i> Sản phẩm đang bán</div>
                <div class="stock-list">
                    <?php if (!empty($productsInStock)): ?>
                        <?php foreach ($productsInStock as $product): ?>
                            <div class="stock-item">
                                <div class="stock-info">
                                    <div class="stock-name"><?php echo htmlspecialchars($product->name); ?></div>
                                    <div class="stock-price"><?php echo number_format($product->price, 0, ',', '.'); ?> đ</div>
                                </div>
                                <div class="stock-controls">
                                    <button class="stock-btn stock-decrease" data-id="<?php echo $product->id; ?>"><i class="fas fa-minus fa-xs"></i></button>
                                    <input type="number" class="stock-input <?php echo ($product->stock <= 10) ? 'low-stock' : ''; ?>" id="stock-val-<?php echo $product->id; ?>" data-id="<?php echo $product->id; ?>" value="<?php echo (int)$product->stock; ?>" min="0">
                                    <button class="stock-btn stock-increase" data-id="<?php echo $product->id; ?>"><i class="fas fa-plus fa-xs"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">Không có sản phẩm nào.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Top Selling Products by Month -->
    <div class="row mb-2">
        <div class="col-12 mb-3">
            <div class="modern-card">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2 flex-wrap gap-2">
                    <div class="card-header-title border-0 mb-0 pb-0"><i class="fas fa-trophy" style="color: var(--warning-accent);"></i> Top Sản Phẩm Bán Chạy</div>
                    <div class="d-flex align-items-center gap-2">
                        <select id="topSellingMonth" class="form-control form-control-sm" style="width:auto; border-radius:8px; border:1px solid var(--border-soft); font-size:0.85rem; padding:6px 12px;">
                            <?php
                            if (empty($availableMonths)) $availableMonths = [date('Y-m')];
                            foreach ($availableMonths as $m):
                                $label = date('m/Y', strtotime($m . '-01'));
                                $selected = ($m === date('Y-m')) ? 'selected' : '';
                            ?>
                                <option value="<?php echo $m; ?>" <?php echo $selected; ?>>Tháng <?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div id="topSellingContent" style="min-height:100px;">
                    <div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Đang tải...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Discount Codes -->
    <div class="row mb-2">
        <div class="col-12 mb-3">
            <div class="modern-card">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <div class="card-header-title border-0 mb-0 pb-0"><i class="fas fa-ticket-alt" style="color: var(--primary-accent);"></i> Quản lý Mã Giảm Giá</div>
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addDiscountModal" data-bs-toggle="modal" data-bs-target="#addDiscountModal"><i class="fas fa-plus mr-1"></i>Thêm Mới</button>
                </div>
                <div class="table-responsive">
                    <table class="modern-table" id="discountTable">
                        <thead>
                            <tr>
                                <th>Mã CODE / Phạm Vi</th>
                                <th>Giá trị giảm</th>
                                <th class="text-center">Loại giảm</th>
                                <th class="text-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <div class="card-header-title border-0 mb-0 pb-0"><i class="fas fa-history" style="color: var(--primary-accent);"></i> Lịch Sử Đơn Hàng</div>
                    <span class="text-muted small">Hiển thị tất cả <?php echo count($orders); ?> đơn</span>
                </div>
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="modern-table">
                        <thead style="position: sticky; top: 0; z-index: 10;">
                            <tr>
                                <th style="width:100px;">Mã ĐH</th>
                                <th style="width:200px;">Khách hàng</th>
                                <th>Sản phẩm đã mua</th>
                                <th style="width:150px;">Liên hệ</th>
                                <th style="width:120px;">Trạng Thái</th>
                                <th style="width:120px;" class="text-right">Tổng Tiền</th>
                                <th style="width:100px;" class="text-right">Xử lý</th>
                            </tr>
                        </thead>
                        <tbody id="admin-orders-tbody">
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <?php
                                        $st = $order->status ?? 'pending';
                                        $statusConfig = [
                                            'pending'   => ['class' => 'badge-soft warning', 'text' => 'Chờ xác nhận', 'next' => 'confirmed', 'nextLabel' => 'Xác nhận'],
                                            'confirmed' => ['class' => 'badge-soft info',    'text' => 'Đã xác nhận',  'next' => 'preparing', 'nextLabel' => 'Chuẩn bị hàng'],
                                            'preparing' => ['class' => 'badge-soft primary', 'text' => 'Đang chuẩn bị','next' => 'shipping',  'nextLabel' => 'Giao hàng'],
                                            'shipping'  => ['class' => 'badge-soft info',    'text' => 'Đang giao',    'next' => 'completed', 'nextLabel' => 'Hoàn thành'],
                                            'completed' => ['class' => 'badge-soft success', 'text' => 'Hoàn thành',   'next' => null,        'nextLabel' => null],
                                            'cancelled' => ['class' => 'badge-soft danger',  'text' => 'Đã hủy',       'next' => null,        'nextLabel' => null],
                                        ];
                                        $cfg = $statusConfig[$st] ?? $statusConfig['pending'];
                                        $isFinished = ($st === 'completed' || $st === 'cancelled');
                                    ?>
                                    <tr class="admin-order-row">
                                        <td>
                                            <span class="badge-soft order-id">#<?php echo $order->id; ?></span>
                                            <div class="small text-muted mt-1" style="font-size:0.75rem;"><?php echo !empty($order->created_at) ? date('d/m/Y', strtotime($order->created_at)) : ''; ?></div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark"><?php echo htmlspecialchars($order->customer_name); ?></div>
                                            <?php if (!empty($order->username)): ?>
                                                <div class="small text-primary mt-1" style="font-size:0.75rem;"><i class="fas fa-user mr-1"></i><?php echo htmlspecialchars($order->username); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $items = $orderItemsMap[$order->id] ?? [];
                                            if (!empty($items)):
                                                foreach ($items as $item):
                                            ?>
                                                <div class="inline-product-tag">
                                                    <span class="qty"><?php echo (int)$item->quantity; ?>x</span>
                                                    <span><?php echo htmlspecialchars($item->product_name); ?></span>
                                                </div>
                                            <?php
                                                endforeach;
                                            elseif (!empty($order->products_json)):
                                                $pjson = json_decode($order->products_json, true) ?? [];
                                                foreach ($pjson as $pitem):
                                            ?>
                                                <div class="inline-product-tag">
                                                    <span class="qty"><?php echo (int)$pitem['quantity']; ?>x</span>
                                                    <span><?php echo htmlspecialchars($pitem['name']); ?></span>
                                                </div>
                                            <?php
                                                endforeach;
                                            else:
                                            ?>
                                                <span class="text-muted small">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="small text-dark mb-1"><i class="fas fa-phone mr-1 text-muted"></i><?php echo htmlspecialchars($order->customer_phone); ?></div>
                                            <div class="small text-muted" style="font-size:0.75rem;"><i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($order->customer_email); ?></div>
                                        </td>
                                        <td style="white-space: nowrap; font-size: 0.85rem;">
                                            <span class="<?php echo $cfg['class']; ?>" id="ostatus-<?php echo $order->id; ?>"><?php echo $cfg['text']; ?></span>
                                        </td>
                                        <td class="text-right font-weight-bold" style="white-space: nowrap; font-size: 0.9rem; color:var(--text-pure);">
                                            <?php echo number_format($order->total_price, 0, ',', '.'); ?> đ
                                        </td>
                                        <td class="text-right" id="action-cell-<?php echo $order->id; ?>" style="white-space:nowrap;">
                                            <?php if (!$isFinished): ?>
                                                <?php if ($cfg['next']): ?>
                                                <button class="btn btn-sm btn-outline-success border-0 px-1" style="font-size:0.7rem;" title="<?php echo $cfg['nextLabel']; ?>" onclick="updateOrder(<?php echo $order->id; ?>, '<?php echo $cfg['next']; ?>')">
                                                    <i class="fas fa-arrow-right"></i> <?php echo $cfg['nextLabel']; ?>
                                                </button>
                                                <?php endif; ?>
                                                <button class="btn btn-sm btn-outline-danger border-0 px-1" style="font-size:0.7rem;" title="Hủy" onclick="updateOrder(<?php echo $order->id; ?>, 'cancelled')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted" style="font-size:0.7rem;"><i class="fas fa-lock fa-sm"></i> Khóa</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted border-0">Chưa có đơn hàng nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="admin-order-pagination" class="mt-3 px-3 pb-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add Discount Modal -->
<div class="modal fade" id="addDiscountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg);">
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title font-weight-bold text-dark"><i class="fas fa-ticket-alt mr-2" style="color: var(--primary-accent);"></i>Thêm Mã Giảm Giá</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
            </div>
            <form id="add-discount-form">
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase">Mã Code</label>
                        <input type="text" id="discount_code" class="form-control" placeholder="SALE50" required style="text-transform: uppercase;">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase">Mức Giảm Giá</label>
                        <input type="number" id="discount_amount" class="form-control" required step="0.01">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted text-uppercase">Loại Giảm</label>
                        <select id="discount_is_percentage" class="form-control" style="font-size: 0.95rem; color: #1a1a1a; height: auto; padding: 0.6rem 0.75rem;">
                            <option value="0">Giảm tiền trực tiếp (VNĐ)</option>
                            <option value="1">Giảm theo phần trăm (%)</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold text-muted text-uppercase">Áp dụng cho Sản Phẩm</label>
                        <select id="discount_product_id" class="form-control" style="font-size: 0.95rem; color: #1a1a1a; height: auto; padding: 0.6rem 0.75rem;">
                            <option value="">Toàn bộ hệ thống</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-dismiss="modal" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary px-4">Lưu Mã</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // ==== STOCK UPDATE LOGIC ====
    function updateStockUI(id, newStock) {
        const input = document.getElementById('stock-val-' + id);
        if (!input) return;
        input.value = newStock;
        if (newStock <= 10) {
            input.classList.add('low-stock');
        } else {
            input.classList.remove('low-stock');
        }
    }

    function sendStockUpdate(id, action, value) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('action', action);
        if (action === 'set') formData.append('value', value);

        return fetch('/webbanhang/index.php?url=admin/updateStock', {
            method: 'POST',
            body: formData
        }).then(res => res.json());
    }

    // + / - buttons
    document.querySelectorAll('.stock-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            this.disabled = true;
            sendStockUpdate(id, 'decrease', null).then(data => {
                this.disabled = false;
                if (data.success) updateStockUI(id, data.new_stock);
                else alert('Lỗi: ' + data.message);
            }).catch(() => this.disabled = false);
        });
    });

    document.querySelectorAll('.stock-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            this.disabled = true;
            sendStockUpdate(id, 'increase', null).then(data => {
                this.disabled = false;
                if (data.success) updateStockUI(id, data.new_stock);
                else alert('Lỗi: ' + data.message);
            }).catch(() => this.disabled = false);
        });
    });

    // Direct number input
    document.querySelectorAll('.stock-input').forEach(input => {
        function commitValue() {
            const id = input.getAttribute('data-id');
            const val = parseInt(input.value);
            if (isNaN(val) || val < 0) { input.value = 0; }
            sendStockUpdate(id, 'set', val < 0 ? 0 : val)
            .then(data => {
                if (data.success) updateStockUI(id, data.new_stock);
                else alert('Lỗi: ' + data.message);
            });
        }
        input.addEventListener('blur', commitValue);
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); this.blur(); }
        });
    });

    // ==== DISCOUNT LOGIC ====
    if (typeof $ !== 'undefined') {
        $.getJSON('/webbanhang/index.php?url=admin/listProducts', function(data) {
            const productSelect = $('#discount_product_id');
            $.each(data, function(index, product) {
                productSelect.append($('<option></option>').val(product.id).text(product.name));
            });
        });

        loadDiscounts();

        function loadDiscounts() {
            $.getJSON('/webbanhang/index.php?url=admin/listDiscounts', function(data) {
                const tbody = $('#discountTable tbody');
                tbody.empty();
                if (data.length === 0) {
                    tbody.append('<tr><td colspan="4" class="text-center py-4 text-muted border-0">Chưa có mã giảm giá nào.</td></tr>');
                    return;
                }
                $.each(data, function(index, discount) {
                    const typeText = parseInt(discount.is_percentage) === 1 ? 'Phần trăm (%)' : 'Cố định (VNĐ)';
                    const badgeClass = parseInt(discount.is_percentage) === 1 ? 'badge-soft info' : 'badge-soft primary';
                    const formattedAmount = parseInt(discount.is_percentage) === 1 
                                            ? discount.discount_amount + '%' 
                                            : new Intl.NumberFormat('vi-VN').format(discount.discount_amount) + 'đ';
                    
                    const scopeHtml = discount.product_name 
                        ? `<div class="small text-muted mt-1"><i class="fas fa-box text-secondary mr-1"></i> ${discount.product_name}</div>`
                        : `<div class="small text-muted mt-1"><i class="fas fa-globe text-primary mr-1"></i> Toàn hệ thống</div>`;
                    
                    const tr = `
                        <tr>
                            <td>
                                <div class="font-weight-bold" style="color: var(--success-accent);">${discount.code}</div>
                                ${scopeHtml}
                            </td>
                            <td class="font-weight-bold text-dark">${formattedAmount}</td>
                            <td class="text-center"><span class="${badgeClass}">${typeText}</span></td>
                            <td class="text-right">
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteDiscount(${discount.id})"><i class="fas fa-trash fa-sm"></i></button>
                            </td>
                        </tr>
                    `;
                    tbody.append(tr);
                });
            });
        }

        window.deleteDiscount = function(id) {
            if(confirm('Xóa mã giảm giá này?')) {
                $.ajax({
                    url: '/webbanhang/index.php?url=admin/deleteDiscount/' + id,
                    type: 'DELETE',
                    success: function(res) {
                        if(res.success) loadDiscounts();
                        else alert('Lỗi xóa: ' + res.message);
                    }
                });
            }
        };

        $('#add-discount-form').on('submit', function(e) {
            e.preventDefault();
            const codeData = {
                code: $('#discount_code').val(),
                discount_amount: $('#discount_amount').val(),
                is_percentage: parseInt($('#discount_is_percentage').val()),
                product_id: $('#discount_product_id').val()
            };
            $.ajax({
                url: '/webbanhang/index.php?url=admin/addDiscount',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(codeData),
                success: function(res) {
                    if(res.success) {
                        $('#addDiscountModal').modal('hide');
                        if (window.bootstrap) {
                            const modalEl = document.getElementById('addDiscountModal');
                            const bsModal = bootstrap.Modal.getInstance(modalEl);
                            if (bsModal) bsModal.hide();
                        }
                        $('#add-discount-form')[0].reset();
                        loadDiscounts();
                    } else {
                        alert(res.message);
                    }
                }
            });
        });
    }

    // ==== ORDER STATUS UPDATE ====
    var statusLabels = {
        'pending': {cls: 'badge-soft warning', text: 'Chờ xác nhận'},
        'confirmed': {cls: 'badge-soft info', text: 'Đã xác nhận'},
        'preparing': {cls: 'badge-soft primary', text: 'Đang chuẩn bị'},
        'shipping': {cls: 'badge-soft info', text: 'Đang giao'},
        'completed': {cls: 'badge-soft success', text: 'Hoàn thành'},
        'cancelled': {cls: 'badge-soft danger', text: 'Đã hủy'}
    };

    window.updateOrder = function(id, status) {
        var actionText = status === 'cancelled' ? 'HỦY đơn hàng' : 'chuyển trạng thái đơn hàng';
        if (!confirm('Xác nhận ' + actionText + ' #' + id + '?')) return;
        $.ajax({
            url: '/webbanhang/index.php?url=admin/updateOrderStatus',
            type: 'POST',
            data: { id: id, status: status },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    location.reload();
                } else {
                    alert('Lỗi: ' + res.message);
                }
            }
        });
    };

    // ==== ORDER PAGINATION ====
    const orderRows = Array.from(document.querySelectorAll('.admin-order-row'));
    if (orderRows.length > 10) {
        const rowsPerPage = 10;
        let currentPage = 1;
        const totalPages = Math.ceil(orderRows.length / rowsPerPage);

        function renderOrderPage(page) {
            currentPage = page;
            orderRows.forEach((r, idx) => {
                r.style.display = (idx >= (page - 1) * rowsPerPage && idx < page * rowsPerPage) ? '' : 'none';
            });
            document.getElementById('admin-page-display').innerText = page + ' / ' + totalPages;
        }

        const controls = `
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">Hiển thị Trang <span id="admin-page-display" class="font-weight-bold">1</span></span>
                <div>
                    <button class="btn btn-sm btn-light border" onclick="prevAdminOrderPage()"><i class="fas fa-chevron-left mr-1"></i> Trước</button>
                    <button class="btn btn-sm btn-light border ml-1" onclick="nextAdminOrderPage()">Tiếp <i class="fas fa-chevron-right ml-1"></i></button>
                </div>
            </div>
        `;
        document.getElementById('admin-order-pagination').innerHTML = controls;
        renderOrderPage(1);

        window.prevAdminOrderPage = function() { if(currentPage > 1) renderOrderPage(currentPage - 1); };
        window.nextAdminOrderPage = function() { if(currentPage < totalPages) renderOrderPage(currentPage + 1); };
    }
});
</script>

<style>
    .top-selling-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .top-selling-table th { background: #faf9f7; color: var(--text-mutated); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 14px; border-bottom: 1px solid var(--border-soft); }
    .top-selling-table td { padding: 12px 14px; border-bottom: 1px solid #f5f3f0; vertical-align: middle; font-size: 0.88rem; }
    .top-selling-table tr:hover td { background: #fdfcfb; }
    .top-rank { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; font-weight: 700; font-size: 0.8rem; }
    .top-rank.gold { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #fff; }
    .top-rank.silver { background: linear-gradient(135deg, #d1d5db, #9ca3af); color: #fff; }
    .top-rank.bronze { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .top-rank.normal { background: #f3f4f6; color: #6b7280; }
    .top-product-img { width: 44px; height: 44px; border-radius: 10px; object-fit: cover; border: 1px solid #f0ebe3; }
    .top-product-name { font-weight: 600; color: var(--text-pure); }
    .top-product-cat { font-size: 0.75rem; color: var(--text-mutated); }
    .top-sold-badge { background: #fef3c7; color: #d97706; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; }
    .top-empty { text-align: center; padding: 3rem; color: var(--text-mutated); }
    .top-empty i { font-size: 2.5rem; margin-bottom: 1rem; display: block; opacity: 0.3; }
</style>

<script>
// ==== TOP SELLING PRODUCTS ====
function loadTopSelling(month) {
    var container = document.getElementById('topSellingContent');
    container.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Đang tải...</div>';
    
    $.ajax({
        url: '/webbanhang/index.php?url=admin/topSelling&month=' + month,
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.success && res.data.length > 0) {
                var html = '<div class="table-responsive"><table class="top-selling-table">';
                html += '<thead><tr><th style="width:50px">#</th><th>Sản phẩm</th><th class="text-center">Số lượng bán</th><th class="text-right">Doanh thu</th></tr></thead><tbody>';
                
                res.data.forEach(function(p, i) {
                    var rankClass = i === 0 ? 'gold' : (i === 1 ? 'silver' : (i === 2 ? 'bronze' : 'normal'));
                    var medal = i === 0 ? '🥇' : (i === 1 ? '🥈' : (i === 2 ? '🥉' : ''));
                    var imgSrc = p.image ? '/webbanhang/' + p.image : 'https://via.placeholder.com/44x44/f7f5f2/9ca3af?text=' + (i+1);
                    var price = Number(p.total_revenue).toLocaleString('vi-VN');
                    
                    html += '<tr>';
                    html += '<td><span class="top-rank ' + rankClass + '">' + (medal || (i+1)) + '</span></td>';
                    html += '<td><div class="d-flex align-items-center gap-2">';
                    html += '<img src="' + imgSrc + '" class="top-product-img">';
                    html += '<div><div class="top-product-name">' + p.name + '</div>';
                    html += '<div class="top-product-cat">' + (p.category_name || '') + ' · ' + Number(p.price).toLocaleString('vi-VN') + '₫</div></div></div></td>';
                    html += '<td class="text-center"><span class="top-sold-badge">' + p.total_sold + ' sản phẩm</span></td>';
                    html += '<td class="text-right font-weight-bold" style="color:var(--success-accent);">' + price + ' ₫</td>';
                    html += '</tr>';
                });
                
                html += '</tbody></table></div>';
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="top-empty"><i class="fas fa-box-open"></i>Chưa có dữ liệu bán hàng trong tháng này.</div>';
            }
        },
        error: function() {
            container.innerHTML = '<div class="top-empty"><i class="fas fa-exclamation-triangle"></i>Lỗi khi tải dữ liệu.</div>';
        }
    });
}

// Load on page ready
$(document).ready(function() {
    var sel = document.getElementById('topSellingMonth');
    if (sel) {
        loadTopSelling(sel.value);
        sel.addEventListener('change', function() {
            loadTopSelling(this.value);
        });
    }
});
</script>

<script>
// ===== CHART.JS Revenue Chart =====
(function() {
    var months   = <?php
        $chartMonths   = [];
        $chartRevenues = [];
        if (!empty($monthlyRevenue)) {
            $rev = array_reverse($monthlyRevenue);
            foreach ($rev as $r) {
                $chartMonths[]   = 'Tháng ' . date('n', strtotime($r->month . '-01'));
                $chartRevenues[] = (float)$r->monthly_total;
            }
        }
        echo json_encode($chartMonths);
    ?>;
    var revenues = <?php echo json_encode($chartRevenues); ?>;

    var ctx = document.getElementById('revenueChart');
    if (!ctx || !window.Chart) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Doanh thu (đ)',
                data: revenues,
                borderColor: '#8c7b6c',
                backgroundColor: 'rgba(140, 123, 108, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#8c7b6c',
                pointRadius: 4,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return new Intl.NumberFormat('vi-VN').format(ctx.parsed.y) + 'đ';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(v) {
                            if (v >= 1000000) return (v/1000000).toFixed(0) + 'tr';
                            return v;
                        },
                        font: { size: 11 }
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });
})();
</script>

<?php include __DIR__ . '/../shaders/footer.php'; ?>

