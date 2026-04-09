<?php
namespace App\Presentation\Controllers;

use App\BLL\CategoryService;
use Exception;

class CategoryController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $categoryService = new CategoryService();
        $categories = $categoryService->getCategories();
        include __DIR__ . '/../Views/category/list.php'; // Optional generic fall-through
    }
}
?>
