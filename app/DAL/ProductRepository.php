<?php
namespace App\DAL;

use PDO;
use Exception;

class ProductRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getAll($category_id = null, $keyword = '') {
        $query = "SELECT p.*, c.name AS category_name, COALESCE((SELECT SUM(quantity) FROM order_details od WHERE od.product_id = p.id), 0) AS sold_count"
               . " FROM product p"
               . " LEFT JOIN category c ON p.category_id = c.id"
               . " WHERE 1=1";
        
        if ($category_id) {
            $query .= " AND p.category_id = :category_id";
        }
        
        if (!empty($keyword)) {
            $query .= " AND p.name LIKE :keyword";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if ($category_id) {
            $stmt->bindParam(":category_id", $category_id);
        }
        if (!empty($keyword)) {
            $keyword_param = "%" . $keyword . "%";
            $stmt->bindParam(":keyword", $keyword_param);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id) {
        $query = "SELECT * FROM product WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($name, $description, $price, $category_id, $image) {
        $query = "INSERT INTO product (name, description, price, category_id, image) VALUES (:name, :description, :price, :category_id, :image)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':category_id' => $category_id,
            ':image' => $image
        ]);
    }

    public function update($id, $name, $description, $price, $category_id, $image) {
        $query = "UPDATE product SET name = :name, description = :description, price = :price, category_id = :category_id, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':category_id' => $category_id,
            ':image' => $image,
            ':id' => $id
        ]);
    }

    public function updateStock($id, $quantity_change) {
        // quantity_change can be negative (decrease stock)
        $query = "UPDATE product SET stock = stock + :qty WHERE id = :id AND (stock + :qty) >= 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':qty' => $quantity_change, ':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function delete($id) {
        $query = "DELETE FROM product WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>
