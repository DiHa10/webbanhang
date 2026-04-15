<?php include_once __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; }

    .profile-page {
        max-width: 1000px;
        margin: 2.5rem auto;
        padding: 0 1.5rem;
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 2rem;
    }

    /* === LEFT: USER CARD === */
    .user-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        border: 1px solid #f0ebe3;
        overflow: hidden;
        position: sticky;
        top: 80px;
        height: fit-content;
    }
    .user-card-header {
        background: <?php echo $tierColor; ?>;
        padding: 2rem 1.5rem;
        text-align: center;
        color: #fff;
        position: relative;
    }
    .user-avatar {
        width: 90px; height: 90px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.4);
        background: rgba(255,255,255,0.15);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 0.8rem;
        overflow: hidden;
    }
    .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .user-display-name {
        font-family: 'Lora', serif;
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
    }
    .user-role-badge {
        display: inline-block;
        padding: 3px 14px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.5rem;
        background: rgba(255,255,255,0.25);
        backdrop-filter: blur(4px);
    }
    .user-tier-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 0.6rem;
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .user-info-section {
        padding: 1.5rem;
    }
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 0.7rem 0;
        border-bottom: 1px solid #f5f0ea;
    }
    .info-item:last-child { border: none; }
    .info-icon {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: #faf9f8;
        display: flex; align-items: center; justify-content: center;
        color: #8c7b6c;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .info-label { font-size: 0.72rem; color: #9ca3af; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
    .info-value { font-size: 0.92rem; color: #1a1a1a; font-weight: 500; word-break: break-word; }
    .info-value.empty { color: #d1d5db; font-style: italic; font-weight: 400; }

    .btn-edit-profile {
        display: block;
        width: calc(100% - 3rem);
        margin: 0 1.5rem 1.5rem;
        padding: 0.7rem;
        background: #1a1a1a;
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }
    .btn-edit-profile:hover { background: #8c7b6c; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(140,123,108,0.3); }

    /* === RIGHT: STATS + ORDERS === */
    .right-col { display: flex; flex-direction: column; gap: 1.5rem; }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #f0ebe3;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        text-align: center;
        transition: transform 0.3s;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.06); }
    .stat-card .stat-icon { font-size: 1.5rem; margin-bottom: 0.6rem; }
    .stat-card .stat-num { font-family: 'Lora', serif; font-size: 1.6rem; font-weight: 700; color: #1a1a1a; }
    .stat-card .stat-txt { font-size: 0.78rem; color: #9ca3af; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; margin-top: 0.3rem; }

    /* Progress */
    .tier-progress-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #f0ebe3;
    }
    .tier-progress-title { font-family: 'Lora', serif; font-size: 1.1rem; font-weight: 600; color: #1a1a1a; margin-bottom: 1rem; }
    .tier-bar-bg { height: 10px; background: #f0ebe3; border-radius: 5px; overflow: hidden; }
    .tier-bar-fill { height: 100%; background: <?php echo $tierColor; ?>; border-radius: 5px; transition: width 1.5s ease; }
    .tier-labels { display: flex; justify-content: space-between; font-size: 0.8rem; color: #9ca3af; margin-top: 6px; }

    /* Orders */
    .orders-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #f0ebe3;
    }
    .orders-card-title {
        font-family: 'Lora', serif;
        font-size: 1.2rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #f0ebe3;
        display: flex; align-items: center; gap: 8px;
    }
    .order-row {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #faf9f8;
        gap: 1rem;
        transition: background 0.2s;
    }
    .order-row:hover { background: #faf9f8; margin: 0 -1.5rem; padding-left: 1.5rem; padding-right: 1.5rem; border-radius: 12px; }
    .order-row:last-child { border: none; }
    .order-id { font-weight: 700; color: #8c7b6c; font-size: 0.85rem; white-space: nowrap; }
    .order-products { flex: 1; font-size: 0.88rem; color: #4b5563; }
    .order-products span { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 300px; }
    .order-total { font-weight: 700; color: #1a1a1a; font-size: 0.95rem; white-space: nowrap; }
    .order-status {
        padding: 4px 12px; border-radius: 30px; font-size: 0.7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;
    }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-confirmed { background: #d1fae5; color: #059669; }
    .status-cancelled { background: #fee2e2; color: #dc2626; }

    .empty-orders {
        text-align: center;
        padding: 3rem 1rem;
        color: #d1d5db;
    }
    .empty-orders i { font-size: 3rem; margin-bottom: 1rem; }

    /* Edit Modal */
    .edit-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 1000; backdrop-filter: blur(4px); }
    .edit-overlay.active { display: flex; align-items: center; justify-content: center; }
    .edit-modal {
        background: #fff; border-radius: 20px; padding: 2rem; width: 90%; max-width: 500px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15); animation: slideUp 0.3s ease;
    }
    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .edit-modal h3 { font-family: 'Lora', serif; font-size: 1.3rem; margin-bottom: 1.5rem; }
    .edit-modal .form-group { margin-bottom: 1rem; }
    .edit-modal label { font-size: 0.78rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block; }
    .edit-modal input, .edit-modal textarea {
        width: 100%; padding: 0.7rem 1rem; border: 1px solid #e5e7eb; border-radius: 10px;
        font-size: 0.95rem; color: #1a1a1a; background: #faf9f8; outline: none; transition: border 0.3s;
    }
    .edit-modal input:focus, .edit-modal textarea:focus { border-color: #8c7b6c; background: #fff; }
    .edit-modal-actions { display: flex; gap: 10px; margin-top: 1.5rem; }
    .edit-modal-actions button { flex: 1; padding: 0.7rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 0.9rem; }
    .btn-cancel-edit { background: #f3f4f6; color: #6b7280; }
    .btn-save-edit { background: #1a1a1a; color: #fff; }
    .btn-save-edit:hover { background: #8c7b6c; }

    @media (max-width: 768px) {
        .profile-page { grid-template-columns: 1fr; }
        .user-card { position: static; }
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<?php
$_role = $user->role ?? 'customer';
$_roleLabel = 'Khách hàng';
$_roleIcon = 'fa-user';
if ($_role === 'admin') { $_roleLabel = 'Quản trị viên'; $_roleIcon = 'fa-shield-alt'; }
elseif ($_role === 'staff' || $_role === 'employee') { $_roleLabel = 'Nhân viên'; $_roleIcon = 'fa-id-badge'; }

$_email = $user->email ?? '';
$_phone = $user->phone ?? '';
$_address = $user->address ?? '';
$_avatar = $user->avatar ?? '';
$_createdAt = $user->created_at ?? '';
$_orderCount = count($orders ?? []);
$_confirmedOrders = 0;
$_pendingOrders = 0;
foreach ($orders as $o) {
    $st = $o->status ?? 'pending';
    if ($st === 'confirmed' || $st == 1) $_confirmedOrders++;
    elseif ($st === 'pending' || $st == 0) $_pendingOrders++;
}
?>

<div class="profile-page">
    <!-- LEFT: USER CARD -->
    <div class="user-card">
        <div class="user-card-header">
            <div class="user-avatar">
                <?php if (!empty($_avatar)): ?>
                    <img src="/webbanhang/<?php echo htmlspecialchars($_avatar); ?>" alt="Avatar">
                <?php else: ?>
                    <i class="fas <?php echo $_roleIcon; ?>"></i>
                <?php endif; ?>
            </div>
            <h2 class="user-display-name"><?php echo htmlspecialchars($user->fullname ?? $username); ?></h2>
            <div class="user-role-badge"><i class="fas <?php echo $_roleIcon; ?> me-1"></i><?php echo $_roleLabel; ?></div>
            <div class="user-tier-badge"><i class="fas <?php echo htmlspecialchars($icon ?? 'fa-user'); ?>"></i> <?php echo htmlspecialchars($tier ?? 'Thành viên mới'); ?></div>
        </div>

        <div class="user-info-section">
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-at"></i></div>
                <div>
                    <div class="info-label">Tên đăng nhập</div>
                    <div class="info-value"><?php echo htmlspecialchars($username); ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="info-label">Email</div>
                    <div class="info-value <?php echo empty($_email) ? 'empty' : ''; ?>"><?php echo !empty($_email) ? htmlspecialchars($_email) : 'Chưa cập nhật'; ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <div class="info-label">Số điện thoại</div>
                    <div class="info-value <?php echo empty($_phone) ? 'empty' : ''; ?>"><?php echo !empty($_phone) ? htmlspecialchars($_phone) : 'Chưa cập nhật'; ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div class="info-label">Địa chỉ</div>
                    <div class="info-value <?php echo empty($_address) ? 'empty' : ''; ?>"><?php echo !empty($_address) ? htmlspecialchars($_address) : 'Chưa cập nhật'; ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <div class="info-label">Ngày tham gia</div>
                    <div class="info-value"><?php echo !empty($_createdAt) ? date('d/m/Y', strtotime($_createdAt)) : 'N/A'; ?></div>
                </div>
            </div>
        </div>

        <button class="btn-edit-profile" onclick="document.getElementById('editOverlay').classList.add('active')">
            <i class="fas fa-pen me-2"></i>Chỉnh sửa thông tin
        </button>
    </div>

    <!-- RIGHT: STATS + ORDERS -->
    <div class="right-col">
        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon" style="color: #8c7b6c;">💰</div>
                <div class="stat-num"><?php echo number_format($totalSpent ?? 0, 0, ',', '.'); ?>đ</div>
                <div class="stat-txt">Tổng chi tiêu</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #10b981;">📦</div>
                <div class="stat-num"><?php echo $_orderCount; ?></div>
                <div class="stat-txt">Đơn hàng</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="color: #f59e0b;">✅</div>
                <div class="stat-num"><?php echo $_confirmedOrders; ?></div>
                <div class="stat-txt">Đã hoàn thành</div>
            </div>
        </div>

        <!-- Tier Progress -->
        <?php if (($nextThreshold ?? 0) > 0): ?>
        <div class="tier-progress-card">
            <div class="tier-progress-title">
                <i class="fas <?php echo $icon; ?>" style="color: <?php echo $tierColor; ?>; margin-right: 6px;"></i>
                Hạng <?php echo htmlspecialchars($tier); ?> → <?php echo htmlspecialchars($nextTier); ?>
            </div>
            <div class="tier-bar-bg">
                <div class="tier-bar-fill" style="width: <?php echo min(100, $progress ?? 0); ?>%"></div>
            </div>
            <div class="tier-labels">
                <span><?php echo number_format($totalSpent ?? 0, 0, ',', '.'); ?>đ</span>
                <span>Cần thêm <strong><?php echo number_format(($nextThreshold ?? 0) - ($totalSpent ?? 0), 0, ',', '.'); ?>đ</strong></span>
            </div>
        </div>
        <?php else: ?>
        <div class="tier-progress-card" style="text-align: center; background: linear-gradient(135deg, <?php echo $tierColor; ?>11, <?php echo $tierColor; ?>22);">
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">🏆</div>
            <div style="font-weight: 700; color: <?php echo $tierColor; ?>; font-size: 1.1rem;">Chúc mừng! Bạn đã đạt hạng cao nhất!</div>
        </div>
        <?php endif; ?>

        <!-- Order History -->
        <div class="orders-card">
            <div class="orders-card-title"><i class="fas fa-history" style="color: #8c7b6c;"></i> Lịch Sử Mua Hàng</div>

            <?php if (empty($orders)): ?>
                <div class="empty-orders">
                    <i class="fas fa-box-open"></i>
                    <p>Bạn chưa có đơn hàng nào.</p>
                    <a href="/webbanhang/index.php?url=product" style="color: #8c7b6c; font-weight: 600; text-decoration: none;">Khám phá sản phẩm →</a>
                </div>
            <?php else: ?>
                <?php foreach ($orders as $order): 
                    $st = $order->status ?? 'pending';
                    $stClass = 'status-pending'; $stText = 'Chờ xử lý';
                    if ($st === 'confirmed' || $st == 1) { $stClass = 'status-confirmed'; $stText = 'Đã duyệt'; }
                    elseif ($st === 'cancelled' || $st == 2) { $stClass = 'status-cancelled'; $stText = 'Đã hủy'; }

                    $itemNames = [];
                    if (!empty($order->items)) {
                        foreach ($order->items as $item) $itemNames[] = htmlspecialchars($item->name) . ' (x' . $item->quantity . ')';
                    }
                ?>
                <div class="order-row">
                    <div class="order-id">#<?php echo $order->id; ?></div>
                    <div class="order-products">
                        <span><?php echo !empty($itemNames) ? implode(', ', $itemNames) : '<em>Gói hàng ẩn</em>'; ?></span>
                        <small style="color: #9ca3af;"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></small>
                    </div>
                    <div class="order-total"><?php echo number_format($order->total_price, 0, ',', '.'); ?>đ</div>
                    <span class="order-status <?php echo $stClass; ?>"><?php echo $stText; ?></span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="edit-overlay" id="editOverlay" onclick="if(event.target===this) this.classList.remove('active')">
    <div class="edit-modal">
        <h3><i class="fas fa-user-edit me-2" style="color: #8c7b6c;"></i>Chỉnh sửa thông tin</h3>
        <form id="editProfileForm">
            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($user->fullname ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_email); ?>" placeholder="example@email.com">
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($_phone); ?>" placeholder="0901 234 567">
            </div>
            <div class="form-group">
                <label>Địa chỉ</label>
                <textarea name="address" rows="2" placeholder="Số nhà, đường, quận/huyện, thành phố"><?php echo htmlspecialchars($_address); ?></textarea>
            </div>
            <div class="edit-modal-actions">
                <button type="button" class="btn-cancel-edit" onclick="document.getElementById('editOverlay').classList.remove('active')">Hủy</button>
                <button type="submit" class="btn-save-edit"><i class="fas fa-check me-1"></i>Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
$('#editProfileForm').on('submit', function(e) {
    e.preventDefault();
    const data = {
        fullname: this.fullname.value,
        email: this.email.value,
        phone: this.phone.value,
        address: this.address.value
    };
    $.post('/webbanhang/index.php?url=account/updateProfile', data, function(res) {
        if (res.success) {
            location.reload();
        } else {
            alert(res.message || 'Lỗi cập nhật!');
        }
    }, 'json').fail(function() { alert('Lỗi kết nối'); });
});
</script>

<?php include_once __DIR__ . '/../shaders/footer.php'; ?>
