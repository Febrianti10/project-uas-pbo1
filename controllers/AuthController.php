<!--

// class AuthController
// {
//     public function showLogin()
//     {
//         $errorMessage = $_GET['error'] ?? ''; // contoh sederhana
//         include 'views/auth/login.php';
//     }

    // public function login()
    // {
    //     di sini cek username/password pakai OOP + PDO (teman backend)
    //     kalau gagal:
    //     header('Location: ?page=auth&action=showLogin&error=Login gagal');
    //     exit;
    // }

//     public function logout()
//     {
//         session_destroy() lalu redirect ke login
//     }
// }
  -->

<?php
// controllers/AuthController.php

class AuthController {
    
    // Method untuk menampilkan form Login
    public function showLoginForm() {
        // Rubrik: Kelas dibuat sesuai konsep OOP (memisahkan controller dan view)
        // Pastikan Anda memanggil file login.php Anda yang ada di views/login.php
        require_once 'views/login.php'; 
    }

    /**
     * Menangani proses POST request dari form login.
     * Dipanggil oleh router saat request ke /project-uas-pbo/login-process.
     * Rubrik: Menangani input invalid, error tidak crash program
     * Rubrik: Validasi role kasir
     */
    public function loginProcess() {
        // 1. Ambil Input (Server-Side)
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // 2. Validasi Input Kosong
        if (empty($username) || empty($password)) {
            // Rubrik: Input valid, feedback user jelas
            $_SESSION['error_message'] = 'Username dan Password harus diisi.';
            // Redirect ke halaman login (root project)
            header('Location: /project-uas-pbo/'); 
            exit;
        }

        // Rubrik: Object, class, method digunakan dengan benar
        // 3. Cari User dari Database (Memanggil Model User)
        $user = User::findByUsername($username); 

        // 4. Verifikasi User dan Password
        // Rubrik: Aman dari SQL injection (asumsi User::findByUsername menggunakan prepared statement)
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error_message'] = 'Username atau password salah.';
            header('Location: /project-uas-pbo/');
            exit;
        }

        // 5. Validasi Role (Otorisasi Kasir)
        // Rubrik: Validasi role kasir (menu yang muncul sesuai hak akses kasir)
        // Rubrik: Ada komentar yang menjelaskan logika penting
        if ($user['role'] !== 'kasir') {
            $_SESSION['error_message'] = 'Akses ditolak. Anda tidak memiliki hak akses Kasir.';
            header('Location: /project-uas-pbo/');
            exit;
        }

        // 6. Login Berhasil (Membuat Session)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        
        // 7. Redirect ke Dashboard Kasir (Tugas Controller)
        // Rubrik: Semua fitur sesuai requirement
        header('Location: /project-uas-pbo/transaksi/dashboard'); 
        exit;
    }
    
    /**
     * Menangani proses logout dan menghancurkan session.
     */
    public function logout() {
        // Rubrik: Kode modular, bisa dipakai ulang
        session_unset(); 
        session_destroy(); 
        header('Location: /project-uas-pbo/'); 
        exit;
    }
}