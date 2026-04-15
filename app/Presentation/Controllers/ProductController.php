<?php
namespace App\Presentation\Controllers;

use App\BLL\ProductService;
use App\BLL\CategoryService;
use App\BLL\OrderService;
use App\DAL\Database;
use App\Presentation\Middlewares\RoleMiddleware;
use Exception;
use PDO;

class ProductController {
    private $productService;
    private $categoryService;
    private $orderService;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
        $this->orderService = new OrderService();
    }

    public function index() {
        $categories = $this->categoryService->getCategories();
        $category_id = $_GET['category_id'] ?? null;
        $keyword = $_GET['search'] ?? ($_GET['keyword'] ?? '');

        try {
            $products = $this->productService->getProductList($category_id, $keyword);
        } catch (Exception $e) {
            die("Lỗi truy vấn: " . $e->getMessage());
        }

        include __DIR__ . '/../Views/product/list.php';
    }

    public function show($id) {
        $product = $this->productService->getProductDetail($id);
        if ($product) include __DIR__ . '/../Views/product/show.php';
        else echo "Không thấy sản phẩm.";
    }

    public function add() {
        // Chỉ admin hoặc employee mới được thêm
        RoleMiddleware::requireRole(['admin', 'employee']);
        $categories = $this->categoryService->getCategories();
        include __DIR__ . '/../Views/product/add.php';
    }

    public function save() {
        RoleMiddleware::requireRole(['admin', 'employee']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $category_id = $_POST['category_id'] ?? null;
            $imageFile = $_FILES['image'] ?? null;

            try {
                $this->productService->addProduct($name, $description, $price, $category_id, $imageFile);
                header('Location: /webbanhang/index.php?url=product');
            } catch (Exception $e) {
                // Errors rendering logic
                $errors = [$e->getMessage()];
                $categories = $this->categoryService->getCategories();
                include __DIR__ . '/../Views/product/add.php';
            }
        }
    }

    public function edit($id) {
        RoleMiddleware::requireRole(['admin', 'employee']);
        $product = $this->productService->getProductDetail($id);
        $categories = $this->categoryService->getCategories();
        if ($product) include __DIR__ . '/../Views/product/edit.php';
        else echo "Không thấy sản phẩm.";
    }

    public function update() {
        RoleMiddleware::requireRole(['admin', 'employee']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $imageFile = $_FILES['image'] ?? null;
            $existing_image = $_POST['existing_image'] ?? '';
            
            try {
                $this->productService->updateProduct($id, $name, $description, $price, $category_id, $imageFile, $existing_image);
                header('Location: /webbanhang/index.php?url=product');
            } catch (Exception $e) {
                echo "Lỗi: " . $e->getMessage();
            }
        }
    }

    public function delete($id) {
        // Chỉ Admin mới được xóa sản phẩm, Employee không được
        RoleMiddleware::requireRole(['admin']);
        try {
            $this->productService->deleteProduct($id);
            header('Location: /webbanhang/index.php?url=product');
        } catch (Exception $e) {
            echo "Đã xảy ra lỗi khi xóa: " . $e->getMessage();
        }
    }

    // ==========================================
    // DISCOUNT HELPERS
    // ==========================================

    private function getDiscountByCode(string $code) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT code, discount_amount, is_percentage FROM discounts WHERE UPPER(code) = UPPER(?) AND status = 1 LIMIT 1");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function calculateAutoDiscount(float $amount): float {
        if ($amount >= 20000000) {
            return $amount * 0.10;
        }
        if ($amount >= 10000000) {
            return $amount * 0.05;
        }
        return 0;
    }

    private function getCartTotal(): float {
        $cart = $this->orderService->getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }
        return $total;
    }

    // ==========================================
    // GIỎ HÀNG VÀ THANH TOÁN
    // ==========================================

    public function cart() {
        // Ai cũng xem giỏ hàng được
        $cart = $this->orderService->getCart();
        include __DIR__ . '/../Views/product/cart.php';
    }

    public function addToCart($id = null) {
        $product_id = $id ?? ($_REQUEST['id'] ?? null);
        $qty = isset($_REQUEST['quantity']) ? (int)$_REQUEST['quantity'] : 1;

        if (!$product_id) {
            header('Location: /webbanhang/index.php?url=product&error=missing_id');
            exit;
        }

        try {
            $this->orderService->addToCart($product_id, $qty);
        } catch (Exception $e) {
            header('Location: /webbanhang/index.php?url=product&error=notfound');
            exit;
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $cart = $this->orderService->getCart();
            $total_items = array_sum(array_column($cart, 'quantity'));
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'total_items' => $total_items]);
            exit;
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: /webbanhang/index.php?url=product');
        }
        exit;
    }

    public function updateCart() {
        $id = $_GET['id'] ?? null;
        $action = $_GET['action'] ?? null;
        if ($id) {
            $this->orderService->updateCart($id, $action);
        }
        header('Location: /webbanhang/index.php?url=product/cart');
        exit;
    }

    public function removeFromCart() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->orderService->removeFromCart($id);
        }
        header('Location: /webbanhang/index.php?url=product/cart');
        exit;
    }

    public function checkout() {
        // Customer, Employee, Admin đều có thể checkout
        RoleMiddleware::requireRole(['customer', 'employee', 'admin']);
        $cart = $this->orderService->getCart();
        if (empty($cart)) {
            header('Location: /webbanhang/index.php?url=product');
            return;
        }
        include __DIR__ . '/../Views/product/checkout.php';
    }

    public function processCheckout() {
        RoleMiddleware::requireRole(['customer', 'employee', 'admin']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer_name = $_POST['customer_name'] ?? '';
            $customer_email = $_POST['customer_email'] ?? '';
            $customer_phone = $_POST['customer_phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $discount_code = trim($_POST['discount_code'] ?? '');

            $discount_amount_sub = 0;
            $cartTotal = $this->getCartTotal();
            $discount_amount_sub += $this->calculateAutoDiscount($cartTotal);

            if ($discount_code !== '') {
                $discount = $this->getDiscountByCode($discount_code);
                if (!$discount) {
                    throw new Exception(" Mã giảm giá không hợp lệ hoặc đã hết hạn.");
                }
                if ((int)$discount['is_percentage'] === 1) {
                    $discount_amount_sub += $cartTotal * ((float)$discount['discount_amount'] / 100);
                } else {
                    $discount_amount_sub += (float)$discount['discount_amount'];
                }
            }
            
            try {
                $order_id = $this->orderService->processCheckout($customer_name, $customer_email, $customer_phone, $address, $discount_amount_sub);
                header('Location: /webbanhang/index.php?url=product/orderSuccess&id=' . $order_id);
                exit;
            } catch (Exception $e) {
                echo "Lỗi khi đặt hàng: " . $e->getMessage();
            }
        }
    }

    public function applyDiscount() {
        header('Content-Type: application/json');
        $code = trim($_POST['code'] ?? '');
        if ($code === '') {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá.']);
            return;
        }

        $discount = $this->getDiscountByCode($code);
        if (!$discount) {
            echo json_encode(['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.']);
            return;
        }

        echo json_encode([
            'success' => true,
            'discount' => [
                'code'          => $discount['code'],
                'amount'        => (float)$discount['discount_amount'],
                'is_percentage' => (int)$discount['is_percentage']
            ]
        ]);
    }

    public function orderSuccess() {
        $order_id = $_GET['id'] ?? null;
        if (!$order_id) { header('Location: /webbanhang/index.php'); exit; }
        
        $data = $this->orderService->getOrderDetails($order_id);
        if (!$data['order']) { header('Location: /webbanhang/index.php'); exit; }

        $order = $data['order'];
        $orderDetails = $data['details'];

        include __DIR__ . '/../Views/product/order_success.php';
    }
}
?>
