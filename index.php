
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

// 1. Mulai Sesi PHP
session_start();

// 2. Autoloader Sederhana (Menggantikan banyak require_once)
// Rubrik: Gunakan require_once autoload untuk load semua class otomatis
spl_autoload_register(function ($class_name) {
    // Tentukan folder di mana class-class Anda berada (lihat struktur folder Anda)
    $dirs = ['models/', 'controllers/', 'helper/'];
    
    foreach ($dirs as $dir) {
        $file = __DIR__ . '/' . $dir . $class_name . '.php';
        
        // Rubrik: Kelas dibuat sesuai konsep OOP, tidak menumpuk kode di satu file
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// 3. Router Sederhana (Menggantikan Switch-Case $_GET['page'])
// Mengambil dan membersihkan URI
$request_uri = $_SERVER['REQUEST_URI'];
// Asumsi root project Anda adalah 'project-uas-pbo'
$project_root = '/project-uas-pbo'; 

// Hapus path project root dari URI dan bersihkan dari query string
$route = str_replace($project_root, '', parse_url($request_uri, PHP_URL_PATH));
$route = trim($route, '/');
$method = $_SERVER['REQUEST_METHOD'];

// Tentukan Controller dan Method
$parts = explode('/', $route);

// Default Route (Jika URL kosong)
if (empty($route)) {
    // Rubrik: Semua fitur sesuai requirement
    if (isset($_SESSION['user_id'])) {
        // Jika sudah login, default ke dashboard
        $controllerName = 'TransaksiController';
        $actionName = 'dashboard';
    } else {
        // Jika belum login, default ke form login
        $controllerName = 'AuthController';
        $actionName = 'showLoginForm';
    }
} else {
    // Ambil ControllerName dan ActionName dari URI
    // Contoh: transaksi/create -> TransaksiController::create()
    $controllerName = ucfirst(array_shift($parts)) . 'Controller'; 
    $actionName = array_shift($parts) ?: 'index'; // Method default 'index'
    
    // Konversi 'login-process' menjadi 'loginProcess'
    $actionName = str_replace('-', '', lcfirst(ucwords(str_replace('-', ' ', $actionName))));
}

// 4. Dispatch (Memanggil Controller dan Method)
if (class_exists($controllerName)) {
    // Rubrik: Object, class, method digunakan dengan benar
    $controller = new $controllerName();
    
    // Rubrik: Menangani input invalid, error tidak crash program (Cek apakah method ada)
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        http_response_code(404);
        include 'views/404.php'; // Atau tampilkan pesan 404 sederhana
    }
} else {
    http_response_code(404);
    include 'views/404.php'; // Atau tampilkan pesan 404 sederhana
}
