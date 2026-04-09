<?php include_once __DIR__ . '/../shaders/header.php'; ?>

<style>
    .profile-wrapper {
        min-height: calc(100vh - 56px);
        background: #f8f9fa;
        padding: 3rem 1rem;
    }
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .tier-header {
        background: <?php echo $tierColor; ?>;
        color: white;
        padding: 2.5rem;
        text-align: center;
        position: relative;
    }
    .tier-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    .tier-name {
        font-family: 'Lora', serif;
        font-size: 2rem;
        font-weight: bold;
        margin: 0;
    }
    .user-stats {
        padding: 2rem;
    }
    .stat-box {
        text-align: center;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .stat-label {
        font-size: 0.85rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #0f172a;
    }
    
    /* Progress Bar */
    .progress-container {
        margin-top: 2rem;
    }
    .progress-bar-bg {
        height: 12px;
        background: #e2e8f0;
        border-radius: 6px;
        overflow: hidden;
        margin: 0.5rem 0;
    }
    .progress-bar-fill {
        height: 100%;
        background: <?php echo $tierColor; ?>;
        border-radius: 6px;
        transition: width 1s ease-in-out;
    }
    .progress-labels {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        margin-top: 4px;
    }
    
    /* Order History */
    .orders-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }
    .orders-title {
        font-family: 'Lora', serif;
        font-size: 1.5rem;
        font-weight: bold;
        color: #0f172a;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 0.75rem;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
    .label-status {
        padding: 0.4em 0.8em;
        font-size: 0.75em;
        font-weight: 600;
    }
    .product-list {
        margin: 0;
        padding-left: 1rem;
        font-size: 0.9rem;
        color: #475569;
    }
</style>

<div class="profile-wrapper">
    <div class="container container-md" style="max-width: 900px;">
        <!-- Membership Card -->
        <div class="profile-card">
            <div class="tier-header">
                <div class="tier-icon">
                    <i class="fas <?php echo htmlspecialchars($icon ?? 'fa-user'); ?>"></i>
                </div>
                <h2 class="tier-name">Thành viên <?php echo htmlspecialchars($tier ?? ''); ?></h2>
                <div class="mt-2" style="font-size: 1.1rem; opacity: 0.9;">Xin chào, <?php echo htmlspecialchars($user->fullName ?? $username); ?>!</div>
            </div>
            
            <div class="user-stats">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-box h-100">
                            <div class="stat-label">Tổng Chi Tiêu</div>
                            <div class="stat-value"><?php echo number_format($totalSpent ?? 0, 0, ',', '.'); ?>đ</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box h-100">
                            <div class="stat-label">Số Đơn Hàng</div>
                            <div class="stat-value"><?php echo count($orders ?? []); ?> đơn</div>
                        </div>
                    </div>
                </div>

                <?php if (($nextThreshold ?? 0) > 0): ?>
                <div class="progress-container">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold" style="color: <?php echo $tierColor; ?>; font-size: 0.95rem;"><?php echo htmlspecialchars($tier); ?></span>
                        <span class="fw-bold text-muted" style="font-size: 0.95rem;"><?php echo htmlspecialchars($nextTier); ?></span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: <?php echo min(100, $progress ?? 0); ?>%"></div>
                    </div>
                    <div class="progress-labels">
                        <span><?php echo number_format($totalSpent ?? 0, 0, ',', '.'); ?>đ</span>
                        <span>Mục tiêu: <?php echo number_format($nextThreshold ?? 0, 0, ',', '.'); ?>đ</span>
                    </div>
                    <p class="text-center mt-3 mb-0" style="font-size: 0.9rem; color: #475569;">
                        Hãy mua sắm thêm <strong><?php echo number_format(($nextThreshold ?? 0) - ($totalSpent ?? 0), 0, ',', '.'); ?>đ</strong> để thăng hạng <strong style="color: <?php echo $tierColor; ?>;"><?php echo htmlspecialchars($nextTier); ?></strong> nhé!
                    </p>
                </div>
                <?php else: ?>
                <div class="progress-container text-center mt-4 p-3 bg-light rounded">
                    <p class="fw-bold mb-0" style="color: <?php echo $tierColor; ?>; font-size: 1.1rem;">
                        <i class="fas fa-trophy me-2"></i> Chúc mừng! Bạn đã đạt hạng cao nhất của hệ thống.
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order History -->
        <div class="orders-section">
            <h3 class="orders-title"><i class="fas fa-history me-2" style="color: #94a3b8;"></i> Lịch Sử Mua Hàng</h3>
            
            <?php if (empty($orders)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-box-open mb-3" style="font-size: 3rem; opacity: 0.5;"></i>
                    <p style="font-size: 1.1rem;">Bạn chưa có đơn hàng nào.</p>
                    <a href="/webbanhang/index.php" class="btn btn-dark mt-2 px-4 py-2" style="border-radius: 8px;">Khám phá sản phẩm ngay</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-muted" style="font-weight: 600;">Mã Đơn</th>
                                <th class="text-muted" style="font-weight: 600;">Ngày Đặt</th>
                                <th class="text-muted" style="font-weight: 600; min-width: 250px;">Sản Phẩm</th>
                                <th class="text-muted" style="font-weight: 600;">Trạng Thái</th>
                                <th class="text-end text-muted" style="font-weight: 600;">Thành Tiền</th>
                            </tr>
                        </thead>
                        <tbody id="profile-orders-tbody">
                            <?php foreach ($orders as $order): ?>
                            <tr class="profile-order-row">
                                <td><span class="fw-bold text-primary">#<?php echo $order->id; ?></span></td>
                                <td style="font-size: 0.9rem; color: #64748b;"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                                <td>
                                    <ul class="product-list">
                                        <?php if (!empty($order->items)): ?>
                                            <?php foreach ($order->items as $item): ?>
                                                <li><?php echo htmlspecialchars($item->name); ?> (x<?php echo $item->quantity; ?>)</li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li><em class="text-muted">Gói hàng ẩn</em></li>
                                        <?php endif; ?>
                                    </ul>
                                </td>
                                <td>
                                    <?php 
                                        $st = $order->status ?? 'pending';
                                        $statusClass = 'bg-warning text-dark';
                                        $statusIcon = 'fa-clock';
                                        $statusText = 'Chờ xử lý';
                                        if ($st === 'confirmed' || $st == 1) { 
                                            $statusClass = 'bg-success'; 
                                            $statusText = 'Đã duyệt'; 
                                            $statusIcon = 'fa-check-circle';
                                        } elseif ($st === 'cancelled' || $st == 2) { 
                                            $statusClass = 'bg-danger'; 
                                            $statusText = 'Đã hủy'; 
                                            $statusIcon = 'fa-times-circle';
                                        }
                                    ?>
                                    <span class="badge rounded-pill <?php echo $statusClass; ?> label-status">
                                        <i class="fas <?php echo $statusIcon; ?> me-1"></i><?php echo $statusText; ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    <?php echo number_format($order->total_price, 0, ',', '.'); ?>đ
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="profile-order-pagination"></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderRows = Array.from(document.querySelectorAll('.profile-order-row'));
    if (orderRows.length > 10) {
        const rowsPerPage = 10;
        let currentPage = 1;
        const totalPages = Math.ceil(orderRows.length / rowsPerPage);

        function renderOrderPage(page) {
            currentPage = page;
            orderRows.forEach((r, idx) => {
                r.style.display = (idx >= (page - 1) * rowsPerPage && idx < page * rowsPerPage) ? '' : 'none';
            });
            document.getElementById('profile-page-display').innerHTML = `<b>${page}</b> / ${totalPages}`;
        }

        const controls = `
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <span class="text-muted small" style="font-size: 0.95rem;">Trang <span id="profile-page-display">1</span></span>
                <div>
                    <button class="btn btn-sm btn-outline-secondary me-1 px-3" onclick="prevProfileOrderPage()"><i class="fas fa-chevron-left me-1"></i> Trước</button>
                    <button class="btn btn-sm btn-outline-secondary px-3" onclick="nextProfileOrderPage()">Tiếp <i class="fas fa-chevron-right ms-1"></i></button>
                </div>
            </div>
        `;
        document.getElementById('profile-order-pagination').innerHTML = controls;
        renderOrderPage(1);

        window.prevProfileOrderPage = function() { if(currentPage > 1) renderOrderPage(currentPage - 1); };
        window.nextProfileOrderPage = function() { if(currentPage < totalPages) renderOrderPage(currentPage + 1); };
    }
});
</script>

<?php include_once __DIR__ . '/../shaders/footer.php'; ?>
