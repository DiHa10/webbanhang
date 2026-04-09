<?php
namespace App\BLL;

use App\DAL\OrderRepository;
use App\DAL\ProductRepository;
use Exception;

class OrderService {
    private $orderRepo;
    private $productRepo;

    public function __construct() {
        $this->orderRepo = new OrderRepository();
        $this->productRepo = new ProductRepository();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function getCart() {
        return $_SESSION['cart'] ?? [];
    }

    public function addToCart($productId, $quantity = 1) {
        if ($quantity < 1) $quantity = 1;

        $product = $this->productRepo->getById($productId);
        if (!$product) throw new Exception("Không tìm thấy sản phẩm");

        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image
            ];
        }

        return $product;
    }

    public function updateCart($productId, $action) {
        if (isset($_SESSION['cart'][$productId])) {
            if ($action === 'increase') {
                $_SESSION['cart'][$productId]['quantity']++;
            } elseif ($action === 'decrease') {
                $_SESSION['cart'][$productId]['quantity']--;
                if ($_SESSION['cart'][$productId]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$productId]);
                }
            }
        }
    }

    public function removeFromCart($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    }

    public function processCheckout($customer_name, $customer_email, $customer_phone, $address, $discount_amount_sub) {
        $cart = $this->getCart();
        if (empty($cart)) throw new Exception("Giỏ hàng trống!");

        $total_price = 0;
        foreach ($cart as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        // Trừ mã giảm giá nếu có
        $total_price -= $discount_amount_sub;
        if ($total_price < 0) $total_price = 0;

        $username = $_SESSION['username'] ?? null;

        // Build Snapshot JSON cho orders
        $products_snapshot = [];
        foreach ($cart as $product_id => $item) {
            $products_snapshot[] = [
                'name'     => $item['name'],
                'quantity' => (int)$item['quantity'],
                'price'    => (float)$item['price']
            ];
        }
        $products_json = json_encode($products_snapshot, JSON_UNESCAPED_UNICODE);

        try {
            $this->orderRepo->beginTransaction();

            $order_id = $this->orderRepo->createOrder(
                $customer_name, $username, $customer_email, $customer_phone, $address, $total_price, $products_json
            );

            foreach ($cart as $product_id => $item) {
                // Tạo chi tiết đơn hàng
                $this->orderRepo->createOrderDetail($order_id, $product_id, $item['quantity'], $item['price']);
                
                // Trừ tồn kho
                $success = $this->productRepo->updateStock($product_id, -$item['quantity']);
                if (!$success) {
                    throw new Exception("Sản phẩm {$item['name']} không đủ tồn kho hoặc lỗi trừ kho.");
                }
            }

            $this->orderRepo->commit();
            unset($_SESSION['cart']);
            return $order_id;
        } catch (Exception $e) {
            $this->orderRepo->rollBack();
            throw $e;
        }
    }

    public function getOrderDetails($orderId) {
        return [
            'order' => $this->orderRepo->getOrderById($orderId),
            'details' => $this->orderRepo->getOrderDetails($orderId)
        ];
    }
}
?>
