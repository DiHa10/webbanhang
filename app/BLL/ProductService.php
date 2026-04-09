<?php
namespace App\BLL;

use App\DAL\ProductRepository;
use Exception;

class ProductService {
    private $productRepo;

    public function __construct() {
        $this->productRepo = new ProductRepository();
    }

    public function getProductList($category_id = null, $keyword = '') {
        return $this->productRepo->getAll($category_id, $keyword);
    }

    public function getProductDetail($id) {
        if (!$id) return null;
        return $this->productRepo->getById($id);
    }

    public function addProduct($name, $description, $price, $category_id, $imageFile) {
        // Validation
        if (empty($name) || $price < 0) {
            throw new Exception("Tên sản phẩm rỗng hoặc giá không hợp lệ");
        }
        
        $imagePath = $this->uploadImage($imageFile);
        return $this->productRepo->save($name, $description, $price, $category_id, $imagePath);
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $imageFile, $existing_image) {
        if ($price < 0) {
            throw new Exception("Giá không hợp lệ");
        }

        $imagePath = (isset($imageFile) && $imageFile['error'] == 0) ? $this->uploadImage($imageFile) : $existing_image;
        return $this->productRepo->update($id, $name, $description, $price, $category_id, $imagePath);
    }

    public function deleteProduct($id) {
        return $this->productRepo->delete($id);
    }

    private function uploadImage($file) {
        if (!$file || $file['error'] !== 0) return "";
        $target_dir = __DIR__ . "/../../uploads/"; 
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $filename = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return "uploads/" . $filename;
        }
        throw new Exception("Lỗi khi tải file lên.");
    }
}
?>
