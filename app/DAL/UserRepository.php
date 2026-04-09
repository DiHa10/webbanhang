<?php
namespace App\DAL;

use PDO;

class UserRepository {
    private $conn;
    private $table_name = "account";

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getAccountByUsername($username) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($username, $fullName, $password, $role = 'customer') {
        if ($this->getAccountByUsername($username)) {
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . " (username, fullname, password, role) VALUES (:username, :fullname, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $password = password_hash($password, PASSWORD_BCRYPT);
        // roles can be 'admin', 'employee', or 'customer'
        $role = htmlspecialchars(strip_tags($role));
        
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);
        return $stmt->execute();
    }
}
?>
