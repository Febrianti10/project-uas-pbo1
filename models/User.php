<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Model User
 * Untuk autentikasi kasir & admin
 */
class User {
    private $db;
    
    public function __construct() {
        // PERBAIKAN: Gunakan getInstance()
        $this->db = Database::getInstance();
    }
    
    /**
     * LOGIN - Autentikasi user
     */
    public function login($username, $password) {
        $sql = "SELECT * FROM user WHERE username = :username LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        
        return false;
    }
    
    /**
     * GET BY ID
     */
    public function getById($id) {
        $sql = "SELECT id_user, username, nama_lengkap, role, created_at 
                FROM user 
                WHERE id_user = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * GET ALL
     */
    public function getAll() {
        $sql = "SELECT id_user, username, nama_lengkap, role, created_at 
                FROM user 
                ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * CREATE
     */
    public function create($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO user (username, password, nama_lengkap, role) 
                VALUES (:username, :password, :nama_lengkap, :role)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'username' => $data['username'],
            'password' => $hashedPassword,
            'nama_lengkap' => $data['nama_lengkap'],
            'role' => $data['role'] ?? 'kasir'
        ]);
    }
    
    /**
     * UPDATE
     */
    public function update($id, $data) {
        $sql = "UPDATE user 
                SET username = :username,
                    nama_lengkap = :nama_lengkap,
                    role = :role
                WHERE id_user = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'username' => $data['username'],
            'nama_lengkap' => $data['nama_lengkap'],
            'role' => $data['role']
        ]);
    }
    
    /**
     * UPDATE PASSWORD
     */
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE user SET password = :password WHERE id_user = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'password' => $hashedPassword
        ]);
    }
    
    /**
     * DELETE
     */
    public function delete($id) {
        $sql = "DELETE FROM user WHERE id_user = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    
    /**
     * CHECK USERNAME
     */
    public function isUsernameExists($username, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) as total FROM user 
                    WHERE username = :username AND id_user != :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['username' => $username, 'id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) as total FROM user WHERE username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['username' => $username]);
        }
        
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}