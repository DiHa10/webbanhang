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
}
?>
