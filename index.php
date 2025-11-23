
// session_start();

// $page = $_GET['page'] ?? 'dashboard';

// switch ($page) {
//     case 'dashboard':
//         include 'views/dashboard.php';
//         break;

//     case 'transaksi':
//         include 'views/transaksi.php';
//         break;

//     case 'layanan':
//         include 'views/layanan.php';
//         break;

//     case 'hewan':
//         include 'views/hewan.php';
//         break;

//     case 'pemilik':          // <-- TAMBAHAN: Data Pelanggan
//         include 'views/pelanggan.php';
//         break;

//     case 'laporan':
//         include 'views/laporan.php';
//         break;

//     case 'login':
//         include 'views/login.php';
//         break;

//     case 'logout':
//         session_destroy();
//         header('Location: index.php?page=login');
//         exit;

//     default:
//         include 'views/404.php';   // pastikan file ini ada (langkah 3)
//         break;
// }

<?php
// index.php - Entry Point dan Router Sederhana MVC

session_start();

// 1. Autoloader Sederhana
spl_autoload_register(function ($class_name) {
    // Rubrik: Gunakan require_once autoload untuk load semua class otomatis
    $dirs = ['models/', 'controllers/', 'helper/', 'config/']; // Tambahkan config jika ada
    foreach ($dirs as $dir) {
        $file = __DIR__ . '/' . $dir . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// 2. Router Sederhana
$request_uri = $_SERVER['REQUEST_URI'];
$project_root = '/project-uas-pbo'; // Sesuaikan dengan path project Anda

// Membersihkan URI
$route = str_replace($project_root, '', parse_url($request_uri, PHP_URL_PATH));
$route = trim($route, '/');

// Tentukan Controller dan Method
$parts = explode('/', $route);

// Default Route (Jika URL kosong)
if (empty($route)) {
    if (isset($_SESSION['user_id'])) {
        $controllerName = 'TransaksiController';
        $actionName = 'dashboard';
    } else {
        $controllerName = 'AuthController';
        $actionName = 'showLoginForm';
    }
} else {
    $controllerName = ucfirst(array_shift($parts)) . 'Controller'; 
    $actionName = array_shift($parts) ?: 'index'; 
    $actionName = str_replace('-', '', lcfirst(ucwords(str_replace('-', ' ', $actionName))));
}

// 3. Dispatch (Memanggil Controller)
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        http_response_code(404);
        include 'views/404.php'; // Pastikan file 404 ini ada
    }
} else {
    http_response_code(404);
    include 'views/404.php'; // Pastikan file 404 ini ada
}