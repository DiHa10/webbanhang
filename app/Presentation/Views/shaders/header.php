<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_isAdmin   = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$_isLoggedIn = isset($_SESSION['username']);
$_username  = htmlspecialchars($_SESSION['username'] ?? '');

// Logic lấy dữ liệu Mega Menu (Danh mục + Top 5 Sản Phẩm mỗi Danh Mục)
$_megaCats = [];
try {
    $_megaDb = \App\DAL\Database::getInstance();
    $_megaCats = $_megaDb->query("SELECT * FROM category ORDER BY id ASC LIMIT 4")->fetchAll(PDO::FETCH_OBJ);
    foreach ($_megaCats as $_mc) {
        $_stmt = $_megaDb->prepare("SELECT id, name FROM product WHERE category_id = ? ORDER BY id DESC LIMIT 5");
        $_stmt->execute([$_mc->id]);
        $_mc->products = $_stmt->fetchAll(PDO::FETCH_OBJ);
    }
} catch(Exception $e) {}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nội Thất Hiện Đại</title>
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%238c7b6c%22/><text x=%2250%22 y=%2270%22 font-size=%2265%22 font-family=%22Georgia, serif%22 font-weight=%22bold%22 text-anchor=%22middle%22 fill=%22%23ffffff%22>N</text></svg>" type="image/svg+xml">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: #faf9f7;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        /* ===== TOP NAVBAR ===== */
        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e8e3dc;
            padding: 0 2rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Logo */
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-family: 'Lora', serif;
            font-weight: 500;
            font-size: 1.05rem;
            color: #1a1a1a;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        .navbar-logo:hover { text-decoration: none; color: #1a1a1a; }
        .navbar-logo-icon {
            width: 28px;
            height: 28px;
            background: #f0ebe3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8c7b6c;
            font-size: 0.85rem;
        }

        /* Center Nav Links */
        .navbar-links {
            display: flex;
            align-items: center;
            gap: 0;
            list-style: none;
            margin: 0;
            padding: 0;
            height: 100%;
        }
        .navbar-links li > a,
        .navbar-links li > .nav-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 4px;
            height: 56px;
            padding: 0 0.85rem;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #555;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: color 0.2s, border-color 0.2s;
            cursor: pointer;
            background: none;
            border-top: none;
            border-left: none;
            border-right: none;
            white-space: nowrap;
        }
        .navbar-links li > a:hover,
        .navbar-links li > .nav-dropdown-toggle:hover {
            color: #1a1a1a;
            border-bottom-color: #1a1a1a;
            text-decoration: none;
        }
        .navbar-links li > a.active {
            color: #1a1a1a;
            border-bottom-color: #1a1a1a;
        }

        /* Admin Dropdown */
        .admin-dropdown {
            position: relative;
        }
        .admin-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
            height: 56px;
            padding: 0 0.85rem;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #fff;
            background: #8c7b6c;
            border-radius: 6px;
            margin: 0 0.3rem;
            height: 34px;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .admin-dropdown-toggle:hover { background: #6b5c50; }
        .admin-dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 220px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.12);
            border: 1px solid #f0ebe3;
            z-index: 1000;
            padding: 0.5rem 0;
            margin-top: 0;
        }
        .admin-dropdown-menu::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 0;
            right: 0;
            height: 10px;
        }
        .admin-dropdown:hover .admin-dropdown-menu { display: block; }
        .admin-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.7rem 1.2rem;
            font-size: 0.85rem;
            color: #4b5563;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            font-weight: 500;
        }
        .admin-dropdown-menu a:hover {
            background: #faf9f8;
            color: #1a1a1a;
        }
        .admin-dropdown-menu a i {
            width: 18px;
            text-align: center;
            color: #8c7b6c;
            font-size: 0.9rem;
        }
        .admin-dropdown-menu .dropdown-divider {
            height: 1px;
            background: #f0ebe3;
            margin: 0.3rem 0;
        }

        /* Admin pill button */
        .btn-api-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.3rem 0.8rem;
            background: #1a1a1a;
            color: #fff !important;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            text-decoration: none !important;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-api-pill:hover { background: #333; }

        /* Right controls */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .navbar-search {
            width: 180px;
            padding: 0.3rem 0.75rem;
            border: 1px solid #e8e3dc;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #555;
            background: #faf9f7;
            outline: none;
            transition: border-color 0.2s;
        }
        .navbar-search:focus { border-color: #aaa; background: #fff; }

        .nav-icon-link {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #555;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-icon-link:hover { color: #1a1a1a; text-decoration: none; }
        .nav-icon-link i { font-size: 1rem; }

        .cart-icon-wrap { position: relative; }
        .cart-badge {
            position: absolute;
            top: -7px;
            right: -8px;
            background: #1a1a1a;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* product image utility (old compat) */
        .product-image { max-width: 100px; height: auto; }

        /* ===== BELL / NOTIFICATION ===== */
        .bell-wrap {
            position: relative;
        }
        .bell-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #fff;
            border: 1px solid #e8e3dc;
            border-radius: 14px;
            min-width: 260px;
            box-shadow: 0 12px 32px rgba(0,0,0,0.12);
            z-index: 9999;
            padding: 16px;
            text-align: left;
        }
        .bell-wrap.open .bell-dropdown { display: block; }
        .bell-dropdown-title {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #8c7b6c;
            margin-bottom: 10px;
        }
        .bell-noti-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: #f5f0ea;
            border-radius: 10px;
            font-size: 0.85rem;
            color: #1a1a1a;
            font-weight: 500;
        }
        .bell-noti-item i { color: #10b981; font-size: 1.1rem; flex-shrink: 0; }
        .bell-noti-empty { font-size: 0.85rem; color: #aaa; text-align: center; padding: 8px 0; }

        /* ===== TOAST NOTIFICATION ===== */
        #order-toast {
            position: fixed;
            top: 72px;
            right: 24px;
            z-index: 99999;
            display: none;
            align-items: center;
            gap: 14px;
            background: #fff;
            border: 1px solid #e8e3dc;
            border-left: 4px solid #10b981;
            border-radius: 14px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.13);
            padding: 16px 22px 16px 18px;
            min-width: 300px;
            max-width: 380px;
            animation: toastSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(60px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes toastSlideOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(60px); }
        }
        #order-toast .toast-icon {
            width: 42px; height: 42px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 1.35rem;
            color: #10b981;
        }
        #order-toast .toast-body { flex: 1; }
        #order-toast .toast-title {
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #10b981;
            margin-bottom: 2px;
        }
        #order-toast .toast-msg {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1a1a1a;
        }
        #order-toast .toast-close {
            background: none; border: none; cursor: pointer;
            color: #aaa; font-size: 1.1rem; padding: 0;
            transition: color 0.2s;
        }
        #order-toast .toast-close:hover { color: #555; }
    </style>
</head>
<body>

<nav class="top-navbar">
    <!-- Logo -->
    <a href="/webbanhang/index.php" class="navbar-logo">
        <span class="navbar-logo-icon"><i class="fas fa-leaf"></i></span>
        NỘI THẤT HIỆN ĐẠI
    </a>

    <!-- Center Links -->
    <ul class="navbar-links">
        <li>
            <a href="/webbanhang/index.php?url=product" id="nav-link-product">Danh Sách Sản Phẩm</a>
        </li>
        <li><a href="/webbanhang/index.php?url=product/ranking">Top Bán Chạy</a></li>
        <li><a href="/webbanhang/index.php?url=blog">Blog</a></li>
        <?php $_isStaff = isset($_SESSION['role']) && $_SESSION['role'] === 'staff'; ?>
        <?php if (!$_isStaff && !$_isAdmin): ?>
        <li><a href="/webbanhang/index.php?url=home/contact">Liên Hệ</a></li>
        <?php endif; ?>

        <?php if ($_isStaff || $_isAdmin): ?>
        <li class="admin-dropdown">
            <button class="admin-dropdown-toggle">
                <i class="fas fa-cogs"></i> Quản Trị <i class="fas fa-chevron-down" style="font-size:0.5rem;"></i>
            </button>
            <div class="admin-dropdown-menu">
                <?php if ($_isAdmin): ?>
                <a href="/webbanhang/index.php?url=product/add"><i class="fas fa-plus-circle"></i> Thêm Sản Phẩm</a>
                <a href="/webbanhang/index.php?url=admin/revenue"><i class="fas fa-chart-line"></i> Quản Lý Doanh Thu</a>
                <a href="/webbanhang/index.php?url=admin/sliders"><i class="fas fa-images"></i> Quản Lý Slide</a>
                <a href="/webbanhang/index.php?url=admin/accounts"><i class="fas fa-users-cog"></i> Quản Lý Tài Khoản</a>
                <div class="dropdown-divider"></div>
                <?php endif; ?>
                <a href="/webbanhang/index.php?url=admin/warehouse"><i class="fas fa-warehouse"></i> Quản Lý Kho</a>
                <a href="/webbanhang/index.php?url=admin/contacts"><i class="fas fa-envelope-open-text"></i> Trả Lời Liên Hệ</a>
            </div>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Right Section -->
    <div class="navbar-right">
        <form action="/webbanhang/index.php" method="GET" style="margin:0;">
            <input type="hidden" name="url" value="product">
            <input type="text" name="search" class="navbar-search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        </form>

        <?php if ($_isLoggedIn): ?>
            <a href="/webbanhang/index.php?url=account/profile" class="nav-icon-link" title="Trang Cá Nhân & Thành Viên">
                <i class="fas fa-user-circle" style="color: #3b82f6;"></i>
                <span style="font-weight: 500;"><?php echo htmlspecialchars($_username); ?></span>
            </a>
            <a href="/webbanhang/index.php?url=account/logout" class="nav-icon-link" title="Đăng xuất">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        <?php else: ?>
            <a href="/webbanhang/account/login" class="nav-icon-link">
                <i class="fas fa-user"></i>
                <span>Đăng nhập</span>
            </a>
        <?php endif; ?>

        <a href="/webbanhang/index.php?url=product/cart" class="nav-icon-link cart-icon-wrap" style="margin-left:8px;">
            <i class="fas fa-shopping-bag"></i>
            <span class="cart-badge" id="cart-badge">0</span>
        </a>

        <?php if ($_isLoggedIn): ?>
        <div class="bell-wrap nav-icon-link cart-icon-wrap" style="margin-left:8px;" id="bell-wrap" title="Thông báo đơn hàng">
            <i class="fas fa-bell" style="color:#555; font-size:1rem; cursor:pointer;"></i>
            <span class="cart-badge" id="noti-badge" style="background:#ef4444;display:none;">0</span>
            <div class="bell-dropdown" id="bell-dropdown">
                <div class="bell-dropdown-title"><i class="fas fa-bell" style="margin-right:5px;"></i>Thông Báo</div>
                <div id="bell-noti-content">
                    <div class="bell-noti-empty">Chưa có thông báo.</div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</nav>

<!-- ===== ORDER SUCCESS TOAST ===== -->
<div id="order-toast">
    <div class="toast-icon"><i class="fas fa-check"></i></div>
    <div class="toast-body">
        <div class="toast-title">Đặt hàng thành công!</div>
        <div class="toast-msg">Bạn đã mua hàng thành công 🎉</div>
    </div>
    <button class="toast-close" id="toast-close-btn" aria-label="Đóng"><i class="fas fa-times"></i></button>
</div>

<script>
// Global Add To Cart AJAX function to prevent double-submit and GET prefetch issues
window.addToCartGlobal = function(id, btnElement) {
    if (btnElement) {
        var btn = $(btnElement);
        var orig = btn.html();
        btn.css('pointer-events','none').html('<i class="fas fa-spinner fa-spin mr-1"></i>...');
    }
    $.ajax({
        url: '/webbanhang/index.php?url=product/addToCart', 
        type: 'POST', 
        data: { id: id, quantity: 1 },
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        success: function(res) {
            if (res.out_of_stock) {
                if(btnElement) btn.html(orig).css('pointer-events','auto');
                showOutOfStockPopup(res.message || 'Sản phẩm đã hết hàng!');
                return;
            }
            if (res.success && btnElement) {
                var badge = $('#cart-badge');
                badge.text(res.total_items).show();
                btn.html('<i class="fas fa-check mr-1"></i> OK').css({'background':'#10b981', 'color':'#fff', 'border-color':'#10b981'});
                setTimeout(function() {
                    btn.html(orig).css({'background':'', 'color':'', 'border-color':''}).css('pointer-events','auto');
                }, 1500);
            }
            if (typeof window.refreshBellNotification === 'function') {
                window.refreshBellNotification();
            }
        },
        error: function() { 
            if(btnElement) btn.html(orig).css('pointer-events','auto'); 
            alert('Lỗi mạng, vui lòng thử lại!'); 
        }
    });
};

window.showOutOfStockPopup = function(msg) {
    var existing = document.getElementById('oos-popup-overlay');
    if (existing) existing.remove();

    var overlay = document.createElement('div');
    overlay.id = 'oos-popup-overlay';
    overlay.style.cssText = 'position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;display:flex;align-items:center;justify-content:center;animation:fadeIn 0.2s;';
    
    overlay.innerHTML = `
        <div style="background:#fff;border-radius:20px;padding:3rem 2.5rem;max-width:420px;width:90%;text-align:center;box-shadow:0 20px 40px rgba(0,0,0,0.15);animation:popIn 0.3s;">
            <div style="width:80px;height:80px;background:#fef2f2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
                <i class="fas fa-box-open" style="font-size:2rem;color:#ef4444;"></i>
            </div>
            <h3 style="font-family:'Lora',serif;font-size:1.4rem;color:#1a1a1a;margin-bottom:0.8rem;">Hết hàng rồi!</h3>
            <p style="color:#6b7280;font-size:1rem;line-height:1.6;margin-bottom:2rem;">${msg}</p>
            <button onclick="this.closest('#oos-popup-overlay').remove();" style="background:#8c7b6c;color:#fff;border:none;padding:0.8rem 2.5rem;border-radius:10px;font-size:1rem;font-weight:600;cursor:pointer;transition:background 0.2s;">
                Đã hiểu
            </button>
        </div>
    `;
    
    overlay.addEventListener('click', function(e) { if(e.target === overlay) overlay.remove(); });
    document.body.appendChild(overlay);
};

// Load categories into dropdown
$(document).ready(function() {
    $.getJSON('/webbanhang/api/product', function(data) {}).error(function(){});
    $.ajax({
        url: '/webbanhang/index.php?url=category',
        type: 'GET',
        dataType: 'json',
        success: function(cats) {
            if (!cats || !cats.length) return;
            var list = $('#nav-category-list');
            cats.forEach(function(c) {
                list.append('<a href="/webbanhang/index.php?url=product&category_id=' + c.id + '">' + c.name + '</a>');
            });
        }
    });

    // Update cart badge from session via API or page-load
    var cartCount = <?php 
        $cartCount = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $cartCount += (int)($item['quantity'] ?? 0);
            }
        }
        echo $cartCount;
    ?>;
    if (cartCount > 0) {
        $('#cart-badge').text(cartCount).show();
    } else {
        $('#cart-badge').hide();
    }

    // Update Notification Bell for logged in users
    <?php if ($_isLoggedIn): ?>
    window.refreshBellNotification = function() {
        $.ajax({
            url: '/webbanhang/index.php?url=product/getNotifications',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.unread > 0) {
                    $('#noti-badge').text(res.unread).show();
                } else {
                    $('#noti-badge').hide();
                }
                if (res.data && res.data.length > 0) {
                    var html = '';
                    res.data.forEach(function(item) {
                        var icon = 'fa-info-circle';
                        var iconColor = '#555';
                        if (item.type === 'cart') { icon = 'fa-shopping-cart'; iconColor = '#3b82f6'; }
                        else if (item.type === 'order_success') { icon = 'fa-check-circle'; iconColor = '#10b981'; }
                        else if (item.type === 'order_history') { icon = 'fa-history'; iconColor = '#8c7b6c'; }

                        html += '<div class="bell-noti-item" style="margin-bottom: 6px; align-items: flex-start;">';
                        html += '<i class="fas ' + icon + '" style="color:' + iconColor + '; font-size: 1.1rem; margin-top:2px;"></i>';
                        html += '<div style="flex:1;">';
                        html += '<div style="font-size: 0.8rem; line-height: 1.4; color: #333; font-weight: 600;">' + item.message + '</div>';
                        html += '<div style="font-size: 0.7rem; color: #999; margin-top: 4px;"><i class="far fa-clock"></i> ' + item.time + '</div>';
                        html += '</div></div>';
                    });
                    $('#bell-noti-content').html(html);
                } else {
                    $('#bell-noti-content').html('<div class="bell-noti-empty">Chưa có thông báo.</div>');
                }
            }
        });
    };
    
    // Initial fetch on page load
    window.refreshBellNotification();

    // Mega Menu API đã được vô hiệu hóa, chuyển sang tải Trực tiếp bằng PHP ở phía trên Header để tăng tốc độ.

    // Bell dropdown toggle
    $('#bell-wrap').on('click', function(e) {
        e.stopPropagation();
        var wasOpen = $(this).hasClass('open');
        $(this).toggleClass('open');
        
        // Đoạn này đánh dấu đã đọc khi người dùng mở chuông ra
        if (!wasOpen && $('#noti-badge').is(':visible')) {
            $('#noti-badge').hide();
            $.ajax({ url: '/webbanhang/index.php?url=product/markNotificationsRead', type: 'GET' });
        }
    });
    $(document).on('click', function() {
        $('#bell-wrap').removeClass('open');
    });

    // ===== TOAST: Show if order just placed =====
    <?php if (!empty($_SESSION['order_just_placed'])): ?>
    (function showOrderToast() {
        var $toast = $('#order-toast');
        $toast.css('display', 'flex');
        // Ring the bell icon as animation
        var $bell = $('i.fa-bell');
        $bell.css({'color': '#10b981', 'transform': 'rotate(-20deg)', 'transition': 'transform 0.15s'});
        setTimeout(function() { $bell.css({'transform': 'rotate(20deg)'}); }, 150);
        setTimeout(function() { $bell.css({'transform': 'rotate(-10deg)'}); }, 300);
        setTimeout(function() { $bell.css({'transform': 'rotate(0)'}); }, 450);
        // Auto close after 5s
        var autoClose = setTimeout(function() { dismissToast(); }, 5000);
        $('#toast-close-btn').on('click', function() {
            clearTimeout(autoClose);
            dismissToast();
        });
        function dismissToast() {
            $toast.css('animation', 'toastSlideOut 0.35s ease forwards');
            setTimeout(function() { $toast.hide(); }, 350);
        }
    })();
    <?php
        unset($_SESSION['order_just_placed']);
    ?>
    <?php endif; ?>

    <?php endif; ?>
});
</script>

<div><!-- page content wrapper -->
