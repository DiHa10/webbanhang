<?php
namespace App\Presentation\Controllers;

use App\BLL\AuthService;
use App\DAL\UserRepository;
use App\DAL\OrderRepository;

class AccountController {
    private $authService;
    private $userRepo;
    private $orderRepo;

    public function __construct() {
        $this->authService = new AuthService();
        $this->userRepo = new UserRepository();
        $this->orderRepo = new OrderRepository();
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function register() {
        include_once __DIR__ . '/../Views/account/register.php';
    }

    public function login() {
        include_once __DIR__ . '/../Views/account/login.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $errors = [];

            if (empty($username)) $errors['username'] = "Vui lòng nhập UserName!";
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập FullName!";
            if (empty($password)) $errors['password'] = "Vui lòng nhập Password!";
            if ($password != $confirmPassword) $errors['confirmPass'] = "Mật khẩu xác nhận không khớp";
            
            // Validation
            if ($this->userRepo->getAccountByUsername($username)) {
                $errors['account'] = "Tài khoản này đã tồn tại!";
            }

            if (count($errors) > 0) {
                include_once __DIR__ . '/../Views/account/register.php';
            } else {
                $result = $this->authService->register($username, $fullName, $password);
                if ($result) header('Location: /webbanhang/index.php?url=account/login');
            }
        }
    }

    public function logout() {
        $this->authService->logout();
        header('Location: /webbanhang/index.php?url=product');
    }

    public function checkLogin() {
        // Demo API Login inside Main Controller
        header('Content-Type: application/json');
        
        // Either fetch JSON or POST formulation
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data && isset($_POST['username'])) { 
            // Also accept normal form post natively via API path if necessary
            $data = $_POST;
        }

        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if ($this->authService->login($username, $password)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials', 'success' => false]);
        }
    }

    public function profile() {
        if (!isset($_SESSION['username'])) {
            header('Location: /webbanhang/index.php?url=account/login');
            exit;
        }
        
        $username = $_SESSION['username'];
        $user = $this->userRepo->getAccountByUsername($username);
        
        if (!$user) {
            $this->logout();
            exit;
        }

        $orders = $this->orderRepo->getOrdersByUsername($username, 100);
        foreach ($orders as &$order) {
            $order->items = $this->orderRepo->getOrderDetails($order->id);
        }

        // Tier Logic
        $totalSpent = 0;
        foreach ($orders as $o) {
            if (isset($o->status) && ($o->status === 'cancelled' || $o->status == 2)) continue;
            $totalSpent += (float)$o->total_price;
        }
        
        $tier = 'Thành viên mới';
        $nextTier = 'Đồng';
        $nextThreshold = 20000000;
        $tierColor = '#64748b'; 
        
        if ($totalSpent >= 150000000) {
            $tier = 'Kim Cương'; $nextTier = 'Tối đa'; $nextThreshold = 0; $progress = 100; $tierColor = '#06b6d4'; $icon = 'fa-gem';
        } elseif ($totalSpent >= 100000000) {
            $tier = 'Vàng'; $nextTier = 'Kim Cương'; $nextThreshold = 150000000; $progress = ($totalSpent / $nextThreshold) * 100; $tierColor = '#eab308'; $icon = 'fa-crown';
        } elseif ($totalSpent >= 50000000) {
            $tier = 'Bạc'; $nextTier = 'Vàng'; $nextThreshold = 100000000; $progress = ($totalSpent / $nextThreshold) * 100; $tierColor = '#94a3b8'; $icon = 'fa-star';
        } elseif ($totalSpent >= 20000000) {
            $tier = 'Đồng'; $nextTier = 'Bạc'; $nextThreshold = 50000000; $progress = ($totalSpent / $nextThreshold) * 100; $tierColor = '#b45309'; $icon = 'fa-medal';
        } else {
            $progress = ($totalSpent / $nextThreshold) * 100; $icon = 'fa-user';
        }

        include_once __DIR__ . '/../Views/account/profile.php';
    }

    public function updateProfile() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['username'])) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            return;
        }

        $username = $_SESSION['username'];
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        try {
            $db = \App\DAL\Database::getInstance();
            $stmt = $db->prepare("UPDATE account SET fullname = ?, email = ?, phone = ?, address = ? WHERE username = ?");
            $stmt->execute([$fullname, $email, $phone, $address, $username]);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
?>
