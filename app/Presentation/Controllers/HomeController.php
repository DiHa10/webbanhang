<?php
namespace App\Presentation\Controllers;

use App\BLL\SliderService;
use App\BLL\CategoryService;
use App\BLL\ProductService;

class HomeController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $sliderService = new SliderService();
        $sliders = $sliderService->getSliders();

        $categoryService = new CategoryService();
        $categories = $categoryService->getCategories();
        $categoriesJson = json_encode($categories);

        $productService = new ProductService();
        // Assume getProductList returns all products if no params
        $products = $productService->getProductList();
        $productsJson = json_encode($products);

        // Lấy sản phẩm bán chạy trong tháng
        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->query("
                SELECT p.id, p.name, p.price, p.image, c.name as category_name, SUM(od.quantity) as total_sold
                FROM product p
                LEFT JOIN category c ON p.category_id = c.id
                JOIN order_details od ON p.id = od.product_id
                JOIN orders o ON od.order_id = o.id
                WHERE MONTH(o.created_at) = MONTH(CURRENT_DATE()) 
                  AND YEAR(o.created_at) = YEAR(CURRENT_DATE()) 
                  AND o.status != 'cancelled' 
                GROUP BY p.id
                ORDER BY total_sold DESC
                LIMIT 4
            ");
            $topProducts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            $topProducts = [];
        }
        $topProductsJson = json_encode($topProducts);

        // Chạy qua thư mục View mới
        include __DIR__ . '/../Views/home/index.php';
    }

    public function contact() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include __DIR__ . '/../Views/home/contact.php';
    }

    public function saveContact() {
        header('Content-Type: application/json');
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tên và nội dung!']);
            return;
        }

        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->prepare("INSERT INTO contacts (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $phone, $email, $subject, $message]);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
?>
