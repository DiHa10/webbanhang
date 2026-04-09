<?php
namespace App\DAL;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $conn;

    private $host = "localhost";
    private $db_name = "my_store";
    private $username = "root";
    private $password = "";

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            die("Database connection error: " . $exception->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>
