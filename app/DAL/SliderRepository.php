<?php
namespace App\DAL;

use PDO;

class SliderRepository {
    private $conn;
    private $table_name = "sliders";

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getSliders() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY sort_order ASC, id DESC LIMIT 5";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getSliderCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row->total ?? 0;
    }

    public function getSliderById($id) {
        $query = "SELECT image_path FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addSlider($image_path, $title, $subtitle, $link_url) {
        $query = "INSERT INTO " . $this->table_name . " (image_path, title, subtitle, link_url) VALUES (:image_path, :title, :subtitle, :link_url)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':subtitle', $subtitle);
        $stmt->bindParam(':link_url', $link_url);
        return $stmt->execute();
    }

    public function deleteSlider($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
