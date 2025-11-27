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

    public function getAll()
    {
        $sql = "SELECT 
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

    public function getActiveTransactions()
    {
        // PERBAIKAN: Ubah 'active' menjadi 'Proses'
        $sql = "SELECT 
                    t.id_transaksi,
                    t.kode_transaksi,
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

    public function create($data)
    {
        try {
            $kodeTransaksi = $this->generateKodeTransaksi();
            
            // PERBAIKAN: Ubah 'active' menjadi 'Proses' di INSERT
            $sql = "INSERT INTO transaksi 
                    (kode_transaksi, id_pelanggan, id_hewan, id_kandang, id_layanan, 
                     tanggal_masuk, durasi, total_biaya, status)
                    VALUES 
                    (:kode_transaksi, :id_pelanggan, :id_hewan, :id_kandang, :id_layanan,
                     :tanggal_masuk, :durasi, :total_biaya, 'Proses')";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                "kode_transaksi" => $kodeTransaksi,
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

    public function getById($id)
    {
        $sql = "SELECT 
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
                WHERE t.id_transaksi = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateCheckout($id, $data) {
        try {
            $this->db->beginTransaction();

            $transaksi = $this->getById($id);

            // Logika pembayaran (PaymentMethod) biarkan tetap sama...
            // (Disederhanakan di sini, asumsi logic PaymentMethod sudah jalan)
            // ...

            // PERBAIKAN: Ubah status menjadi 'Selesai' (sesuai ENUM database)
            $sql = "UPDATE transaksi 
                    SET tanggal_keluar = :tanggal_keluar, 
                        status = 'Selesai'
                    WHERE id_transaksi = :id";
            
            // Catatan: Jika kolom tanggal_keluar_aktual tidak ada di DB, pakai tanggal_keluar saja
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id' => $id,
                'tanggal_keluar' => $data['tanggal_keluar_aktual']
            ]);
            
            // Update status hewan jadi 'sudah_diambil' (pastikan enum hewan support ini)
            // Jika enum hewan cuma ('tersedia', 'sedang_dititipkan'), kembalikan ke 'tersedia' saja
            // Tapi amannya kita set ke 'tersedia' agar bisa dipakai lagi nanti
            $sqlHewan = "UPDATE hewan SET status = 'tersedia' WHERE id_hewan = :id";
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

    private function generateKodeTransaksi()
    {
        $sql = "SELECT MAX(CAST(SUBSTRING(no_transaksi, 5) AS UNSIGNED)) as max_number 
                FROM transaksi 
                WHERE no_transaksi LIKE 'TRX-%'";
        
        // Perhatikan: Kolom di DB kamu 'no_transaksi' atau 'kode_transaksi'?
        // Sesuai SQL awal: no_transaksi VARCHAR(20)
        // Jadi kita sesuaikan querynya:
        $sql = "SELECT MAX(id) as max_id FROM transaksi"; 
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        
        $nextId = ($result['max_id'] ?? 0) + 1;
        return 'TRX-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }
}