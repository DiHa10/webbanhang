<?php
namespace App\BLL;

use App\DAL\SliderRepository;
use Exception;

class SliderService {
    private $sliderRepo;

    public function __construct() {
        $this->sliderRepo = new SliderRepository();
    }

    public function getSliders() {
        return $this->sliderRepo->getSliders();
    }

    public function addSlider($title, $subtitle, $link_url, $imageFile) {
        if ($this->sliderRepo->getSliderCount() >= 5) {
            throw new Exception("Chỉ được upload tối đa 5 slide.");
        }

        if (!isset($imageFile) || $imageFile['error'] != 0) {
            throw new Exception("Vui lòng chọn ảnh hợp lệ.");
        }

        $target_dir = __DIR__ . "/../../uploads/sliders/"; 
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $filename = time() . "_" . basename($imageFile["name"]);
        $target_file = $target_dir . $filename;
        
        if (move_uploaded_file($imageFile["tmp_name"], $target_file)) {
            $image_path = "uploads/sliders/" . $filename;
            return $this->sliderRepo->addSlider($image_path, $title, $subtitle, $link_url);
        } else {
            throw new Exception("Lỗi upload ảnh.");
        }
    }

    public function deleteSlider($id) {
        $slider = $this->sliderRepo->getSliderById($id);
        if ($slider && file_exists(__DIR__ . '/../../' . $slider->image_path)) {
            unlink(__DIR__ . '/../../' . $slider->image_path);
        }
        return $this->sliderRepo->deleteSlider($id);
    }
}
?>
