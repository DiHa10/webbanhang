<?php
session_start();

// Standard Autoloader for 3-tier App namespace
spl_autoload_register(function ($class) {
    // Prefix: App\
    // $class = App\BLL\AuthService
    // Replace prefix with 'app/' and \ with /
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Does not use this autoloader
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// We only transitioned ProductController to Presentation Layer now.
// For others, fall back to old controllers folder temporarily if Presentation doesn't have it.
$presentationControllerClass = "App\\Presentation\\Controllers\\" . $controllerName;
$presentationFile = __DIR__ . "/app/Presentation/Controllers/" . $controllerName . ".php";

if (file_exists($presentationFile)) {
    $controller = new $presentationControllerClass();
} else {
    http_response_code(404);
    die('Controller not found');
}

// Giả lập thẻ ẩn \_method từ Form HTML
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

// Thực thi action 
if (method_exists($controller, $action)) {
    call_user_func_array([$controller, $action], array_slice($url, 2));
} else {
    die('Action not found');
}
?>