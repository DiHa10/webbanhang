<?php
namespace App\Presentation\Controllers;

use App\BLL\AdminService;
use App\BLL\SliderService;
use App\Presentation\Middlewares\RoleMiddleware;
use Exception;

class AdminController {
    private $adminService;
    private $sliderService;

    public function __construct() {
        RoleMiddleware::requireRole(['admin']);
        $this->adminService = new AdminService();
        $this->sliderService = new SliderService();
        $this->adminService->verifyAndCreateTables();
    }

    public function revenue() {
        try {
            $data = $this->adminService->getDashboardRevenue();
            extract($data);
        } catch (Exception $e) {
            $error = "Lỗi xử lý cơ sở dữ liệu: " . $e->getMessage();
            $orders = [];
            $monthlyRevenue = [];
            $totalRevenue = 0;
            $totalStock = 0;
            $productsInStock = [];
            $orderItemsMap = [];
        }

        include __DIR__ . '/../Views/admin/revenue.php';
    }

    public function updateStock() {
        // Có thể được gọi qua JSON (AJAX)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $action = $_POST['action'] ?? null;
            $value = $_POST['value'] ?? 0;

            if ($id && $action) {
                try {
                    $newStock = $this->adminService->updateProductStock($id, $action, $value);
                    echo json_encode(['success' => true, 'new_stock' => $newStock]);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing data']);
            }
        }
    }

    public function updateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $statusCode = $_POST['status'] ?? null;
            
            if ($id && $statusCode !== null) {
                try {
                    $this->adminService->updateOrderStatus($id, $statusCode);
                    echo json_encode(['success' => true]);
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing data']);
            }
        }
    }

    public function sliders() {
        $sliders = $this->sliderService->getSliders();
        include __DIR__ . '/../Views/admin/sliders.php';
    }

    public function addSlider() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'] ?? '';
            $subtitle = $_POST['subtitle'] ?? '';
            $link_url = $_POST['link_url'] ?? '';
            $imageFile = $_FILES['image'] ?? null;

            try {
                $this->sliderService->addSlider($title, $subtitle, $link_url, $imageFile);
            } catch (Exception $e) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['slider_error'] = $e->getMessage();
            }

            header('Location: /webbanhang/index.php?url=admin/sliders');
            exit;
        }
    }

    public function deleteSlider($id) {
        $this->sliderService->deleteSlider($id);
        header('Location: /webbanhang/index.php?url=admin/sliders');
        exit;
    }

    // ==========================================
    // DISCOUNT CODE MANAGEMENT
    // ==========================================

    public function listDiscounts() {
        header('Content-Type: application/json');
        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->prepare("
                SELECT d.*, p.name as product_name 
                FROM discounts d 
                LEFT JOIN product p ON d.product_id = p.id 
                ORDER BY d.id DESC
            ");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }

    public function addDiscount() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            return;
        }

        // Accept both JSON body and form POST
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) $data = $_POST;

        $code = strtoupper(trim($data['code'] ?? ''));
        $amount = floatval($data['discount_amount'] ?? 0);
        $isPct = intval($data['is_percentage'] ?? 0);
        $productId = !empty($data['product_id']) ? intval($data['product_id']) : null;

        if (empty($code) || $amount <= 0) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ mã và mức giảm giá.']);
            return;
        }

        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->prepare("INSERT INTO discounts (code, discount_amount, is_percentage, product_id, status) VALUES (?, ?, ?, ?, 1)");
            $stmt->execute([$code, $amount, $isPct, $productId]);
            echo json_encode(['success' => true]);
        } catch (\PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false) {
                echo json_encode(['success' => false, 'message' => 'Mã giảm giá đã tồn tại!']);
            } else {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function deleteDiscount($id) {
        header('Content-Type: application/json');
        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->prepare("DELETE FROM discounts WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function listProducts() {
        header('Content-Type: application/json');
        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->prepare("SELECT id, name FROM product ORDER BY name");
            $stmt->execute();
            echo json_encode($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }

    // ==========================================
    // ACCOUNT MANAGEMENT
    // ==========================================

    public function accounts() {
        include __DIR__ . '/../Views/admin/accounts.php';
    }

    public function listAccounts() {
        header('Content-Type: application/json');
        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->query("
                SELECT a.*, 
                    COALESCE((SELECT COUNT(*) FROM orders o WHERE o.customer_name = a.username OR o.customer_email = a.email), 0) as order_count,
                    COALESCE((SELECT SUM(o2.total_price) FROM orders o2 WHERE (o2.customer_name = a.username OR o2.customer_email = a.email) AND o2.status != 'cancelled' AND o2.status != 2), 0) as total_spent
                FROM account a
                ORDER BY a.id ASC
            ");
            $accounts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            // Remove passwords from response
            foreach ($accounts as &$acc) unset($acc['password']);
            echo json_encode($accounts);
        } catch (Exception $e) {
            echo json_encode([]);
        }
    }

    public function updateRole() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid method']);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) $data = $_POST;

        $id = intval($data['id'] ?? 0);
        $role = trim($data['role'] ?? '');
        $validRoles = ['admin', 'staff', 'customer'];

        if (!$id || !in_array($role, $validRoles)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            return;
        }

        // Prevent removing own admin role
        $currentUser = $_SESSION['username'] ?? '';
        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->prepare("SELECT username FROM account WHERE id = ?");
            $stmt->execute([$id]);
            $target = $stmt->fetch(\PDO::FETCH_OBJ);
            
            if ($target && $target->username === $currentUser && $role !== 'admin') {
                echo json_encode(['success' => false, 'message' => 'Không thể hạ quyền chính mình!']);
                return;
            }

            $stmt = $db->prepare("UPDATE account SET role = ? WHERE id = ?");
            $stmt->execute([$role, $id]);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteAccount($id = null) {
        header('Content-Type: application/json');
        if (!$id) {
            $data = json_decode(file_get_contents("php://input"), true);
            $id = intval($data['id'] ?? 0);
        }

        $currentUser = $_SESSION['username'] ?? '';
        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->prepare("SELECT username FROM account WHERE id = ?");
            $stmt->execute([$id]);
            $target = $stmt->fetch(\PDO::FETCH_OBJ);

            if (!$target) {
                echo json_encode(['success' => false, 'message' => 'Tài khoản không tồn tại']);
                return;
            }
            if ($target->username === $currentUser) {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa chính mình!']);
                return;
            }

            $stmt = $db->prepare("DELETE FROM account WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
?>
