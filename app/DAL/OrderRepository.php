<?php
namespace App\DAL;

use PDO;

class OrderRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function beginTransaction() {
        $this->conn->beginTransaction();
    }

    public function commit() {
        $this->conn->commit();
    }

    public function rollBack() {
        $this->conn->rollBack();
    }

    public function createOrder($customer_name, $username, $customer_email, $customer_phone, $address, $total_price, $products_json) {
        $stmt = $this->conn->prepare("INSERT INTO orders (customer_name, username, customer_email, customer_phone, address, total_price, products_json) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$customer_name, $username, $customer_email, $customer_phone, $address, $total_price, $products_json]);
        return $this->conn->lastInsertId();
    }

    public function createOrderDetail($order_id, $product_id, $quantity, $price) {
        $stmt = $this->conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$order_id, $product_id, $quantity, $price]);
    }

    public function getOrderById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getOrderDetails($order_id) {
        $stmt = $this->conn->prepare("SELECT d.*, p.name FROM order_details d JOIN product p ON d.product_id = p.id WHERE d.order_id = ?");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getOrdersByUsername($username, $limit = 4) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE username = ? ORDER BY id DESC LIMIT ?");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>
