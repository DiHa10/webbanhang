<?php
namespace App\Presentation\Middlewares;

class RoleMiddleware {
    /**
     * Checks if the user is logged in. If not, redirects to login page.
     */
    public static function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['username'])) {
            header('Location: /webbanhang/index.php?url=account/login');
            exit;
        }
    }

    /**
     * @param array $allowedRoles Array of allowed roles (e.g., ['admin', 'employee', 'customer'])
     */
    public static function requireRole($allowedRoles) {
        self::requireLogin();
        $userRole = $_SESSION['role'] ?? 'guest';

        // Lấy lại role đúng format chữ thường
        $userRole = strtolower($userRole);

        if (!in_array($userRole, $allowedRoles)) {
            // Không đủ quyền, chặn lại và hiển thị cảnh báo
            http_response_code(403);
            die('
                <div style="font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ff4d4f; border-radius: 8px; background: #fff2f0; text-align: center;">
                    <h2 style="color: #cf1322;">Truy cập bị từ chối (403 Forbidden)</h2>
                    <p style="color: #820014;">Tài khoản của bạn ("' . htmlspecialchars($userRole) . '") không có quyền truy cập vào chức năng này.</p>
                    <p>Các quyền yêu cầu: ' . implode(", ", $allowedRoles) . '</p>
                    <a href="/webbanhang/index.php" style="display: inline-block; margin-top: 15px; padding: 10px 20px; background: #cf1322; color: #fff; text-decoration: none; border-radius: 5px;">Quay lại Trang Chủ</a>
                </div>
            ');
        }
    }
}
?>
