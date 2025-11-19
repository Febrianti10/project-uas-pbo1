<?php
session_start();

// ambil parameter ?page=...
$page = $_GET['page'] ?? 'dashboard';

switch ($page) {
    case 'dashboard':
        include 'views/dashboard.php';
        break;

    case 'transaksi':
        include 'views/transaksi.php';
        break;

    case 'layanan':
        include 'views/layanan.php';   // nanti kamu buat views/layanan.php
        break;

    case 'hewan':
        include 'views/hewan.php';     // nanti kamu buat views/hewan.php
        break;

    case 'laporan':
        include 'views/laporan.php';
        break;

    case 'login':
        include 'views/login.php';
        break;

    case 'logout':
        // hapus session lalu balik ke halaman login
        session_destroy();
        header('Location: index.php?page=login');
        exit;

    default:
        // kalau page tidak dikenali, tampilkan 404 sederhana
        include 'views/404.php';       // boleh kamu buat file 404 sendiri
        break;
}
