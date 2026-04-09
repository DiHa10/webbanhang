<?php
namespace App\BLL;

use App\DAL\Database;
use App\DAL\OrderRepository;
use PDO;
use Exception;

class AdminService {
    private $db;
    private $orderRepo;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->orderRepo = new OrderRepository();
    }

    public function verifyAndCreateTables() {
        // Tự động tạo bảng discounts nếu chưa có
        $this->db->exec("CREATE TABLE IF NOT EXISTS discounts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(50) NOT NULL UNIQUE,
            discount_amount DECIMAL(10,2) NOT NULL,
            is_percentage TINYINT(1) DEFAULT 0,
            status TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        try { $this->db->exec("ALTER TABLE discounts ADD COLUMN product_id INT NULL"); } catch (\PDOException $e) {}
        try { $this->db->exec("ALTER TABLE product ADD COLUMN features TEXT NULL"); } catch (\PDOException $e) {}
        try { $this->db->exec("ALTER TABLE orders ADD COLUMN username VARCHAR(100) NULL DEFAULT NULL AFTER customer_name"); } catch (\PDOException $e) {}
        try { $this->db->exec("ALTER TABLE orders ADD COLUMN products_json TEXT NULL DEFAULT NULL"); } catch (\PDOException $e) {}
        try { $this->db->exec("ALTER TABLE orders ADD COLUMN status TINYINT(1) DEFAULT 0"); } catch (\PDOException $e) {}
    }

    public function getDashboardRevenue() {
        $stmt = $this->db->prepare("SELECT * FROM orders ORDER BY id DESC");
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmtItems = $this->db->prepare("
            SELECT od.order_id, od.quantity, od.price, p.name as product_name
            FROM order_details od JOIN product p ON od.product_id = p.id
            ORDER BY od.order_id DESC
        ");
        $stmtItems->execute();
        $allOrderItems = $stmtItems->fetchAll(PDO::FETCH_OBJ);
        
        $orderItemsMap = [];
        foreach ($allOrderItems as $item) {
            $orderItemsMap[$item->order_id][] = $item;
        }

        $totalRevenue = 0;
        foreach ($orders as $order) {
            if (isset($order->status) && ($order->status === 'cancelled' || $order->status == 2)) continue;
            $totalRevenue += (float)$order->total_price;
        }

        $stmtMonth = $this->db->prepare("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as monthly_total 
            FROM orders 
            WHERE status != 'cancelled' OR status IS NULL
            GROUP BY month ORDER BY month DESC
        ");
        $stmtMonth->execute();
        $monthlyRevenue = $stmtMonth->fetchAll(PDO::FETCH_OBJ);

        $stmtStock = $this->db->prepare("SELECT SUM(stock) as total_stock FROM product");
        $stmtStock->execute();
        $totalStockRaw = $stmtStock->fetch(PDO::FETCH_OBJ);
        $totalStock = $totalStockRaw ? (int)$totalStockRaw->total_stock : 0;

        $stmtProducts = $this->db->prepare("SELECT id, name, price, stock, image FROM product ORDER BY stock DESC");
        $stmtProducts->execute();
        $productsInStock = $stmtProducts->fetchAll(PDO::FETCH_OBJ);

        return [
            'orders' => $orders,
            'orderItemsMap' => $orderItemsMap,
            'totalRevenue' => $totalRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'totalStock' => $totalStock,
            'productsInStock' => $productsInStock
        ];
    }

    public function updateOrderStatus($id, $statusCode) {
        $statusEnum = 'pending';
        if ($statusCode == 1) $statusEnum = 'confirmed';
        elseif ($statusCode == 2) $statusEnum = 'cancelled';

        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$statusEnum, $id]);
    }

    public function updateProductStock($id, $action, $value = 0) {
        if ($action === 'set') {
            $value = max(0, (int)$value);
            $stmt = $this->db->prepare("UPDATE product SET stock = ? WHERE id = ?");
            $stmt->execute([$value, $id]);
        } else {
            $modifier = ($action === 'increase') ? '+ 1' : '- 1';
            $stmt = $this->db->prepare("UPDATE product SET stock = GREATEST(0, stock $modifier) WHERE id = ?");
            $stmt->execute([$id]);
        }
        
        $stmtCheck = $this->db->prepare("SELECT stock FROM product WHERE id = ?");
        $stmtCheck->execute([$id]);
        return $stmtCheck->fetchColumn();
    }
}
?>
