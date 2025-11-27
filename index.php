<?php
// index.php - Entry Point Gabungan (Frontend + Backend)

// Autoload untuk load class otomatis
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/models/' . $className . '.php',
        __DIR__ . '/controllers/' . $className . '.php',
        __DIR__ . '/core/' . $className . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// 1. BACKEND ROUTING (ACTION)
// ==========================================
$action = $_GET['action'] ?? $_POST['action'] ?? null;

if ($action) {
    switch ($action) {
        // --- AUTH ---
        case 'login':
            $controller = new AuthController();
            $controller->login();
            break;
        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;

        // --- TRANSAKSI ---
        case 'createTransaksi':
            $controller = new TransaksiController();
            $controller->create();
            break;
        case 'checkoutTransaksi':
            $controller = new TransaksiController();
            $controller->checkout();
            break;
        
        // --- LAYANAN ---
        case 'storeLayanan':
            $controller = new LayananController();
            $controller->store();
            break;
        case 'updateLayanan':
            $controller = new LayananController();
            $controller->update();
            break;
        case 'deleteLayanan':
            $controller = new LayananController();
            $controller->delete();
            break;

        // --- AJAX / API ---
        case 'searchPelanggan':
            // Idealnya ini di PelangganController, tapi kita taruh sini biar cepat
            require_once __DIR__ . '/models/Pelanggan.php';
            $pelangganModel = new Pelanggan();
            $keyword = $_GET['q'] ?? '';
            $results = $pelangganModel->searchForAutocomplete($keyword);
            header('Content-Type: application/json');
            echo json_encode($results);
            break;

        case 'getKandangTersedia':
            // Idealnya ini di KandangController
            require_once __DIR__ . '/models/Kandang.php';
            $kandangModel = new Kandang();
            $jenis = $_GET['jenis'] ?? '';
            $ukuran = $_GET['ukuran'] ?? '';
            $kandangTersedia = $kandangModel->getAvailableKandang($jenis, $ukuran);
            header('Content-Type: application/json');
            echo json_encode($kandangTersedia);
            break;
        
        case 'updateStatusHewan':
            $controller = new HewanController();
            $controller->updateStatus();
            break;

        default:
            echo json_encode(['error' => 'Action not found']);
            break;
    }
    exit; // Stop script here for actions
}

// ==========================================
// 2. FRONTEND ROUTING (PAGE)
// ==========================================
$page = $_GET['page'] ?? 'dashboard';

// Cek Login (Kecuali halaman login)
if ($page !== 'login' && !isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

switch ($page) {
    case 'login':
        // Jika sudah login, lempar ke dashboard
        if (isset($_SESSION['user'])) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        include 'views/login.php';
        break;

    case 'dashboard':
        include 'views/dashboard.php';
        break;

    case 'transaksi':
        // Panggil Controller untuk menyiapkan data
        $controller = new TransaksiController();
        $controller->index(); 
        // Note: Controller akan memanggil view 'views/transaksi.php'
        break;

    case 'layanan':
        $controller = new LayananController();
        $controller->index();
        break;

    case 'hewan':
        $controller = new HewanController();
        $controller->index();
        break;

    case 'pelanggan': // Bisa pakai 'pelanggan' atau 'pemilik'
    case 'pemilik':
        // Jika kamu belum punya PelangganController, kita include view manual
        // Tapi idealnya buat PelangganController juga.
        // Untuk sementara kita include view saja dan biarkan view memanggil model (meski tidak ideal)
        include 'views/pelanggan.php';
        break;

    case 'laporan':
        include 'views/laporan.php';
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    default:
        include 'views/404.php';
        break;
}