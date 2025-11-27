<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/PaymentMethod.php'; 

class Transaksi
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // --- FUNGSI CREATE (PENTING DIPERBAIKI) ---
    public function create($data)
    {
        try {
            // Generate nomor transaksi (sesuai kolom database 'no_transaksi')
            $noTransaksi = $this->generateNoTransaksi();
            
            // PERBAIKAN: 
            // 1. Gunakan 'no_transaksi' (bukan kode_transaksi)
            // 2. Gunakan status 'Proses' (sesuai ENUM database)
            $sql = "INSERT INTO transaksi 
                    (no_transaksi, id_pelanggan, id_hewan, id_kandang, id_layanan, 
                     tanggal_masuk, durasi, total_biaya, status)
                    VALUES 
                    (:no_transaksi, :id_pelanggan, :id_hewan, :id_kandang, :id_layanan,
                     :tanggal_masuk, :durasi, :total_biaya, 'Proses')";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                "no_transaksi" => $noTransaksi,
                "id_pelanggan" => $data["id_pelanggan"],
                "id_hewan" => $data["id_hewan"], 
                "id_kandang" => $data["id_kandang"],
                "id_layanan" => $data["id_layanan"],
                "tanggal_masuk" => $data["tanggal_masuk"],
                "durasi" => $data["durasi"],
                "total_biaya" => $data["total_biaya"]
            ]);
            
        } catch (Exception $e) {
            error_log("Error create transaksi: " . $e->getMessage());
            return false;
        }
    }

    // --- FUNGSI AMBIL DATA AKTIF (PENTING DIPERBAIKI) ---
    public function getActiveTransactions()
    {
        // PERBAIKAN: 
        // 1. Select t.id (nama asli di DB) lalu alias-kan jadi id_transaksi (biar view tidak error)
        // 2. Select t.no_transaksi alias kode_transaksi
        // 3. WHERE status = 'Proses'
        $sql = "SELECT 
                    t.id as id_transaksi,
                    t.no_transaksi as kode_transaksi,
                    p.nama_pelanggan,
                    h.nama_hewan,
                    h.jenis as jenis_hewan,
                    k.kode_kandang,
                    t.tanggal_masuk,
                    t.durasi,
                    t.total_biaya
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN hewan h ON t.id_hewan = h.id_hewan
                LEFT JOIN kandang k ON t.id_kandang = k.id_kandang
                WHERE t.status = 'Proses' 
                ORDER BY t.tanggal_masuk DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll()
    {
        $sql = "SELECT 
                    t.id as id_transaksi,
                    t.no_transaksi as kode_transaksi,
                    t.*,
                    p.nama_pelanggan,
                    h.nama_hewan,
                    l.nama_layanan,
                    k.kode_kandang
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN hewan h ON t.id_hewan = h.id_hewan
                LEFT JOIN layanan l ON t.id_layanan = l.id_layanan
                LEFT JOIN kandang k ON t.id_kandang = k.id_kandang
                ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        // PERBAIKAN: WHERE t.id (bukan id_transaksi)
        $sql = "SELECT 
                    t.id as id_transaksi,
                    t.no_transaksi as kode_transaksi,
                    t.*,
                    p.nama_pelanggan,
                    p.no_hp,
                    p.alamat,
                    h.nama_hewan,
                    h.jenis,
                    h.ras,
                    h.ukuran,
                    h.warna,
                    l.nama_layanan,
                    l.harga as harga_layanan,
                    k.kode_kandang,
                    k.tipe as tipe_kandang
                FROM transaksi t
                LEFT JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
                LEFT JOIN hewan h ON t.id_hewan = h.id_hewan
                LEFT JOIN layanan l ON t.id_layanan = l.id_layanan
                LEFT JOIN kandang k ON t.id_kandang = k.id_kandang
                WHERE t.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateCheckout($id, $data) {
        try {
            $this->db->beginTransaction();

            $transaksi = $this->getById($id);

            // PERBAIKAN: WHERE id_transaksi = :id diganti jadi WHERE id = :id
            // Status jadi 'Selesai'
            $sql = "UPDATE transaksi 
                    SET tanggal_keluar = :tanggal_keluar, 
                        status = 'Selesai'
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'tanggal_keluar' => $data['tanggal_keluar_aktual']
            ]);
            
            $sqlHewan = "UPDATE hewan SET status = 'tersedia' WHERE id = :id";
            $stmtHewan = $this->db->prepare($sqlHewan);
            $stmtHewan->execute(['id' => $transaksi['id_hewan']]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error checkout: " . $e->getMessage());
            return false;
        }
    }

    // Helper untuk generate TRX-001, TRX-002
    private function generateNoTransaksi()
    {
        // PERBAIKAN: Gunakan nama kolom 'no_transaksi'
        $sql = "SELECT MAX(CAST(SUBSTRING(no_transaksi, 5) AS UNSIGNED)) as max_number 
                FROM transaksi 
                WHERE no_transaksi LIKE 'TRX-%'";
        
        // Fallback jika query di atas ribet/error di TiDB, pakai hitung ID saja
        // $sql = "SELECT MAX(id) as max_id FROM transaksi";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        
        $nextNum = ($result['max_number'] ?? 0) + 1;
        return 'TRX-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }
}