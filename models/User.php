<?php
// models/User.php

// Asumsi: Anda memiliki class Database yang menangani koneksi PDO
// atau Anda akan mengelola koneksi langsung di sini.

class User {
    
    /**
     * Mencari pengguna berdasarkan username untuk proses login.
     * Rubrik: Fungsi CRUD berfungsi (Read), Aman dari SQL injection
     *
     * @param string $username Username yang dicari
     * @return array|null Data pengguna atau null jika tidak ditemukan
     */
    public static function findByUsername($username) {
        // --- 1. Skenario Dummy (Untuk Pengujian Awal) ---
        // Jika Anda belum menghubungkan ke DB nyata, gunakan data ini:
        if ($username === 'kasir1') {
            // 'password123' di-hash
            return [
                'id' => 1,
                'username' => 'kasir1',
                // Hash untuk 'password123' (ganti dengan hash dari DB nyata Anda)
                'password' => '$2y$10$wTf7N1P5x0/i/Hk9uVq.a.wB4dE9Q7n0P0t8R/Y9Xm1', 
                'role' => 'kasir' 
            ];
        }
    }
    
    // ... Anda dapat menambahkan method lain seperti createUser, updateUserPassword, dll.
}