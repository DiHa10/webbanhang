<?php
namespace App\DAL;

use PDO;

class CategoryRepository {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getAll() {
        $query = "SELECT * FROM category";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>
