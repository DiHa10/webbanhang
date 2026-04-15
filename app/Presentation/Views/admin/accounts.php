<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; }

    .accounts-page {
        max-width: 1200px;
        margin: 2.5rem auto;
        padding: 0 1.5rem;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .page-title {
        font-family: 'Lora', serif;
        font-size: 2rem;
        font-weight: 600;
        color: #1a1a1a;
    }
    .page-subtitle { color: #9ca3af; font-size: 0.9rem; margin-top: 4px; }

    /* Stats */
    .acc-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .acc-stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #f0ebe3;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        text-align: center;
        transition: transform 0.3s;
    }
    .acc-stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .acc-stat-card .stat-emoji { font-size: 1.8rem; margin-bottom: 0.5rem; }
    .acc-stat-card .stat-number { font-family: 'Lora', serif; font-size: 2rem; font-weight: 700; color: #1a1a1a; }
    .acc-stat-card .stat-title { font-size: 0.78rem; color: #9ca3af; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-top: 0.3rem; }

    /* Search */
    .search-bar {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #f0ebe3;
        border-radius: 14px;
        padding: 0 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .search-bar i { color: #9ca3af; font-size: 0.95rem; }
    .search-bar input {
        flex: 1;
        border: none;
        padding: 0.9rem 0.8rem;
        font-size: 0.95rem;
        color: #1a1a1a;
        background: transparent;
        outline: none;
    }

    /* Table */
    .accounts-table-wrap {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #f0ebe3;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .acc-table {
        width: 100%;
        border-collapse: collapse;
    }
    .acc-table th {
        background: #faf9f8;
        color: #8c7b6c;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1rem 1.2rem;
        text-align: left;
        border-bottom: 1px solid #f0ebe3;
    }
    .acc-table td {
        padding: 1rem 1.2rem;
        border-bottom: 1px solid #faf9f8;
        vertical-align: middle;
        font-size: 0.92rem;
        color: #1a1a1a;
    }
    .acc-table tbody tr { transition: background 0.2s; }
    .acc-table tbody tr:hover { background: #faf9f8; }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .user-avatar-sm {
        width: 42px; height: 42px;
        border-radius: 12px;
        background: #f0ebe3;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; color: #8c7b6c;
        flex-shrink: 0;
    }
    .user-name { font-weight: 600; color: #1a1a1a; }
    .user-email { font-size: 0.8rem; color: #9ca3af; }

    /* Role badges */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .role-admin { background: #fee2e2; color: #dc2626; }
    .role-staff { background: #dbeafe; color: #2563eb; }
    .role-customer { background: #f0ebe3; color: #8c7b6c; }

    /* Role select */
    .role-select {
        padding: 6px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #1a1a1a;
        background: #faf9f8;
        cursor: pointer;
        outline: none;
        transition: border 0.3s;
    }
    .role-select:focus { border-color: #8c7b6c; }

    /* Actions */
    .btn-action {
        width: 34px; height: 34px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.85rem;
    }
    .btn-action.delete:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }
    .btn-action.save:hover { background: #d1fae5; color: #059669; border-color: #6ee7b7; }

    .cell-meta { font-size: 0.82rem; color: #9ca3af; }
    .cell-money { font-weight: 600; color: #1a1a1a; }

    .loading-row td { text-align: center; padding: 3rem; color: #d1d5db; font-size: 1rem; }

    @media (max-width: 768px) {
        .acc-stats { grid-template-columns: repeat(2, 1fr); }
        .acc-table { font-size: 0.85rem; }
    }
</style>

<div class="accounts-page">
    <div class="page-header">
        <div>
            <h1 class="page-title"><i class="fas fa-users-cog" style="color: #8c7b6c; margin-right: 10px;"></i>Quản Lý Tài Khoản</h1>
            <p class="page-subtitle">Phân quyền và quản lý tất cả tài khoản trong hệ thống</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="acc-stats">
        <div class="acc-stat-card">
            <div class="stat-emoji">👥</div>
            <div class="stat-number" id="stat-total">-</div>
            <div class="stat-title">Tổng tài khoản</div>
        </div>
        <div class="acc-stat-card">
            <div class="stat-emoji">🛡️</div>
            <div class="stat-number" id="stat-admin">-</div>
            <div class="stat-title">Admin</div>
        </div>
        <div class="acc-stat-card">
            <div class="stat-emoji">🧑‍💼</div>
            <div class="stat-number" id="stat-staff">-</div>
            <div class="stat-title">Nhân viên</div>
        </div>
        <div class="acc-stat-card">
            <div class="stat-emoji">🛒</div>
            <div class="stat-number" id="stat-customer">-</div>
            <div class="stat-title">Khách hàng</div>
        </div>
    </div>

    <!-- Search -->
    <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên, username, email...">
    </div>

    <!-- Table -->
    <div class="accounts-table-wrap">
        <table class="acc-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tài khoản</th>
                    <th>Liên hệ</th>
                    <th>Vai trò</th>
                    <th>Đơn hàng</th>
                    <th>Chi tiêu</th>
                    <th>Ngày tạo</th>
                    <th style="text-align: center;">Hành động</th>
                </tr>
            </thead>
            <tbody id="accountsBody">
                <tr class="loading-row"><td colspan="8"><i class="fas fa-spinner fa-spin me-2"></i>Đang tải dữ liệu...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
const currentUser = "<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>";
let allAccounts = [];

function loadAccounts() {
    $.getJSON('/webbanhang/index.php?url=admin/listAccounts', function(data) {
        allAccounts = data;
        renderAccounts(data);
        updateStats(data);
    });
}

function updateStats(data) {
    $('#stat-total').text(data.length);
    $('#stat-admin').text(data.filter(a => a.role === 'admin').length);
    $('#stat-staff').text(data.filter(a => a.role === 'staff').length);
    $('#stat-customer').text(data.filter(a => a.role !== 'admin' && a.role !== 'staff').length);
}

function getRoleBadge(role) {
    if (role === 'admin') return '<span class="role-badge role-admin"><i class="fas fa-shield-alt"></i>Admin</span>';
    if (role === 'staff') return '<span class="role-badge role-staff"><i class="fas fa-id-badge"></i>Nhân viên</span>';
    return '<span class="role-badge role-customer"><i class="fas fa-user"></i>Khách hàng</span>';
}

function getRoleIcon(role) {
    if (role === 'admin') return 'fa-shield-alt';
    if (role === 'staff') return 'fa-id-badge';
    return 'fa-user';
}

function renderAccounts(data) {
    const tbody = $('#accountsBody');
    tbody.empty();

    if (data.length === 0) {
        tbody.html('<tr class="loading-row"><td colspan="8">Không tìm thấy tài khoản nào.</td></tr>');
        return;
    }

    data.forEach(acc => {
        const isMe = acc.username === currentUser;
        const date = acc.created_at ? new Date(acc.created_at).toLocaleDateString('vi-VN') : 'N/A';
        const spent = new Intl.NumberFormat('vi-VN').format(acc.total_spent || 0);
        const email = acc.email || '<span style="color:#d1d5db;">—</span>';
        const phone = acc.phone || '<span style="color:#d1d5db;">—</span>';

        const tr = `
            <tr data-id="${acc.id}">
                <td><span style="font-weight:700; color:#8c7b6c;">#${acc.id}</span></td>
                <td>
                    <div class="user-cell">
                        <div class="user-avatar-sm"><i class="fas ${getRoleIcon(acc.role)}"></i></div>
                        <div>
                            <div class="user-name">${acc.fullname || acc.username} ${isMe ? '<span style="font-size:0.7rem; background:#d1fae5; color:#059669; padding:2px 8px; border-radius:20px; margin-left:6px;">Bạn</span>' : ''}</div>
                            <div class="user-email">@${acc.username}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-size:0.85rem;">${email}</div>
                    <div class="cell-meta">${phone}</div>
                </td>
                <td>
                    <select class="role-select" data-id="${acc.id}" ${isMe ? 'disabled title="Không thể thay đổi vai trò chính mình"' : ''}>
                        <option value="admin" ${acc.role === 'admin' ? 'selected' : ''}>🛡️ Admin</option>
                        <option value="staff" ${acc.role === 'staff' ? 'selected' : ''}>🧑‍💼 Nhân viên</option>
                        <option value="customer" ${acc.role !== 'admin' && acc.role !== 'staff' ? 'selected' : ''}>👤 Khách hàng</option>
                    </select>
                </td>
                <td><span style="font-weight:600;">${acc.order_count || 0}</span> <span class="cell-meta">đơn</span></td>
                <td><span class="cell-money">${spent}đ</span></td>
                <td class="cell-meta">${date}</td>
                <td style="text-align:center;">
                    <button class="btn-action save" onclick="saveRole(${acc.id})" title="Lưu vai trò" ${isMe ? 'disabled' : ''}>
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="btn-action delete" onclick="deleteAcc(${acc.id}, '${acc.username}')" title="Xóa tài khoản" ${isMe ? 'disabled' : ''}>
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(tr);
    });
}

function saveRole(id) {
    const select = $(`.role-select[data-id="${id}"]`);
    const role = select.val();
    const btn = select.closest('tr').find('.btn-action.save');
    btn.html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: '/webbanhang/index.php?url=admin/updateRole',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id, role }),
        success: function(res) {
            if (res.success) {
                btn.html('<i class="fas fa-check"></i>').css({'background': '#d1fae5', 'color': '#059669', 'border-color': '#6ee7b7'});
                setTimeout(() => { btn.css({'background': '', 'color': '', 'border-color': ''}); loadAccounts(); }, 1200);
            } else {
                alert(res.message || 'Lỗi!');
                btn.html('<i class="fas fa-check"></i>');
            }
        },
        error: function() { alert('Lỗi kết nối!'); btn.html('<i class="fas fa-check"></i>'); }
    });
}

function deleteAcc(id, username) {
    if (!confirm(`Xóa tài khoản @${username}? Thao tác này không thể hoàn tác!`)) return;

    $.ajax({
        url: '/webbanhang/index.php?url=admin/deleteAccount/' + id,
        type: 'POST',
        success: function(res) {
            if (res.success) loadAccounts();
            else alert(res.message || 'Lỗi!');
        },
        error: function() { alert('Lỗi kết nối!'); }
    });
}

// Search
$('#searchInput').on('input', function() {
    const q = this.value.toLowerCase();
    const filtered = allAccounts.filter(a =>
        (a.username || '').toLowerCase().includes(q) ||
        (a.fullname || '').toLowerCase().includes(q) ||
        (a.email || '').toLowerCase().includes(q) ||
        (a.phone || '').includes(q)
    );
    renderAccounts(filtered);
});

$(document).ready(loadAccounts);
</script>

<?php include __DIR__ . '/../shaders/footer.php'; ?>
