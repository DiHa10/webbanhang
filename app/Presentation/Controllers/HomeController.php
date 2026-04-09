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

        // Chạy qua thư mục View mới
        include __DIR__ . '/../Views/home/index.php';
    }

    public function contact() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include __DIR__ . '/../Views/home/contact.php';
    }
}
?>
