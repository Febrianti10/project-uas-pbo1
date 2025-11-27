<?php
require_once __DIR__ . '/../models/Transaksi.php';
require_once __DIR__ . '/../models/Pelanggan.php';
require_once __DIR__ . '/../models/Hewan.php';
require_once __DIR__ . '/../models/Kandang.php';

class TransaksiController {
    private $transaksiModel;
    private $pelangganModel;
    private $hewanModel;
    private $kandangModel;

    public function __construct() {
        $this->transaksiModel = new Transaksi();
        $this->pelangganModel = new Pelanggan();
        $this->hewanModel = new Hewan();
        $this->kandangModel = new Kandang();
    }

    // Menampilkan halaman transaksi (index)
    public function index() {
        // Ambil data yang diperlukan untuk dropdown di form
        $daftarPelanggan = $this->pelangganModel->getForDropdown();
        $daftarLayanan = (new Layanan())->getAll(); // Butuh require model layanan di atas jika belum
        $daftarKandang = $this->kandangModel->getAll(); // Ambil semua kandang (nanti difilter di view/js)
        
        // Ambil data list transaksi hari ini/aktif
        $transaksiAktif = $this->transaksiModel->getActiveTransactions();

        require_once __DIR__ . '/../views/transaksi.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validasi data required
                if (empty($_POST['id_layanan']) || empty($_POST['id_kandang']) || empty($_POST['nama_hewan'])) {
                    throw new Exception("Data required tidak lengkap");
                }

                // 1. Handle Pelanggan
                $id_pelanggan = $this->handlePelanggan($_POST);
                
                // 2. Handle Hewan 
                $id_hewan = $this->handleHewan($_POST, $id_pelanggan);
                
                // 3. Prepare transaksi data
                $transaksiData = [
                    'id_pelanggan' => $id_pelanggan,
                    'id_hewan' => $id_hewan,
                    'id_kandang' => $_POST['id_kandang'],
                    'id_layanan' => $_POST['id_layanan'],
                    'tanggal_masuk' => $_POST['tanggal_masuk'] ?? date('Y-m-d'),
                    'durasi' => $_POST['durasi'] ?? 1,
                    'total_biaya' => $_POST['total_biaya'] ?? 0
                ];

                // 4. Create transaksi
                if ($this->transaksiModel->create($transaksiData)) {
                    // Update status kandang
                    $this->kandangModel->updateStatus($transaksiData['id_kandang'], 'terpakai');
                    
                    // Update status hewan
                    $this->hewanModel->updateStatus($id_hewan, 'sedang_dititipkan');
                    
                    header('Location: index.php?page=transaksi&status=success');
                    exit;
                } else {
                    throw new Exception("Gagal insert database transaksi");
                }

            } catch (Exception $e) {
                error_log("Error create transaksi: " . $e->getMessage());
                header('Location: index.php?page=transaksi&status=error&msg=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    private function handlePelanggan($data) {
        // Jika ID Pelanggan sudah dipilih dari dropdown/search
        if (!empty($data['id_pelanggan'])) {
            return $data['id_pelanggan'];
        }
        
        // Jika buat baru
        $pelangganData = [
            'nama_pelanggan' => $data['search_pemilik'] ?? 'Tanpa Nama',
            'no_hp' => $data['no_hp'] ?? '-',
            'alamat' => $data['alamat'] ?? '-'
        ];

        // Create dan return ID barunya
        return $this->pelangganModel->create($pelangganData);
    }

    private function handleHewan($data, $id_pelanggan) {
        $hewanData = [
            'id_pelanggan' => $id_pelanggan,
            'nama_hewan' => $data['nama_hewan'],
            'jenis' => $data['jenis'],
            'ras' => $data['ras'],
            'ukuran' => $data['ukuran'],
            'warna' => $data['warna'],
            'catatan' => $data['catatan'] ?? '',
            'status' => 'tersedia'
        ];

        // Create hewan
        $this->hewanModel->create($hewanData);
        
        // PERBAIKAN: Gunakan helper method dari model, jangan getDB() langsung
        return $this->hewanModel->getLastInsertId();
    }

    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_transaksi'];
            $data = [
                'tanggal_keluar_aktual' => date('Y-m-d'),
                'jam_keluar_aktual' => date('H:i:s'),
                'durasi_hari' => $_POST['durasi_aktual'], // Hitung di JS atau PHP
                'total_biaya' => $_POST['total_bayar'],
                'metode_pembayaran' => $_POST['metode_pembayaran']
            ];

            if ($this->transaksiModel->updateCheckout($id, $data)) {
                // Jangan lupa set kandang jadi tersedia lagi!
                // Kita butuh ID kandang dari transaksi ini dulu
                $trx = $this->transaksiModel->getById($id);
                if($trx) {
                    $this->kandangModel->updateStatus($trx['id_kandang'], 'tersedia');
                }

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal checkout']);
            }
            exit;
        }
    }
}