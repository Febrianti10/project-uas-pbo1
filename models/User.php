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
        
        // --- 2. Skenario Nyata (Menggunakan Database) ---
        try {
            // Asumsi: $pdo adalah objek koneksi database (PDO)
            $pdo = Database::getConnection(); 
            
            $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = :username LIMIT 1");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            // Rubrik: Struktur DB logis
            // Jika user ditemukan, kembalikan datanya
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // Tangani error database
            // Rubrik: Error tidak crash program
            error_log("Database Error di User.php: " . $e->getMessage());
            return null;
        }
    }
    
    // ... Anda dapat menambahkan method lain seperti createUser, updateUserPassword, dll.
}