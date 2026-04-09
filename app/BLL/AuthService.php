<?php
namespace App\BLL;

use App\DAL\UserRepository;

class AuthService {
    private $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function login($username, $password) {
        $account = $this->userRepo->getAccountByUsername($username);
        if ($account && password_verify($password, $account->password)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['username'] = $account->username;
            $_SESSION['role'] = $account->role; 
            return true;
        }
        return false;
    }

    public function register($username, $fullname, $password) {
        // Mặc định đăng ký mới sẽ là quyền 'customer'
        return $this->userRepo->save($username, $fullname, $password, 'customer');
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        session_destroy();
    }

    public function getCurrentRole() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION['role'] ?? 'guest';
    }

    public function isRole($expectedRole) {
        return $this->getCurrentRole() === $expectedRole;
    }
}
?>
