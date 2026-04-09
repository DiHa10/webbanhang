<?php
namespace App\Presentation\Controllers;

class BlogController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        include __DIR__ . '/../Views/blog/index.php';
    }
}
?>
