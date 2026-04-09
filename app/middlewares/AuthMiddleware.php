<?php
require_once __DIR__ . '/../utils/JWTHandler.php';

class AuthMiddleware {
    public static function authenticate() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { // Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }

        if (empty($headers) || !preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized: Missing or Invalid Authorization Header']);
            exit;
        }

        $token = $matches[1];
        $jwtHandler = new JWTHandler();
        $response = $jwtHandler->decode($token);

        if (!$response['success']) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized: ' . $response['message']]);
            exit;
        }

        return $response['data'];
    }
}
?>
