<?php
namespace App\BLL;

use App\DAL\CategoryRepository;

class CategoryService {
    private $categoryRepo;

    public function __construct() {
        $this->categoryRepo = new CategoryRepository();
    }

    public function getCategories() {
        return $this->categoryRepo->getAll();
    }
}
?>
