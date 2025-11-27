<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        // Jika request via API/AJAX
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $this->jsonResponse(['error' => 'Username dan password harus diisi']);
                return;
            }

            $user = $this->userModel->login($username, $password);
            
            if ($user) {
                // Set session
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['user'] = $user; // Simpan array user lengkap
                
                // Redirect jika bukan AJAX, atau return JSON jika AJAX
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $this->jsonResponse(['success' => 'Login berhasil', 'redirect' => 'index.php?page=dashboard']);
                } else {
                    header('Location: index.php?page=dashboard');
                    exit;
                }
            } else {
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $this->jsonResponse(['error' => 'Username atau password salah']);
                } else {
                    // Redirect balik ke login dengan error
                    header('Location: index.php?page=login&error=invalid_credentials');
                    exit;
                }
            }
        } else {
            // Tampilkan view login
            require_once __DIR__ . '/../views/login.php';
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }

    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}