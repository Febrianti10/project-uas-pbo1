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

    public function index() {
        $daftarPelanggan = $this->pelangganModel->getForDropdown();
        $paketList = (new Layanan())->getAll(); 
        $kandangTersedia = $this->kandangModel->getAll(); 
        $hewanMenginap = $this->transaksiModel->getActiveTransactions();

        // Variabel harus dikirim ke view via include
        require_once __DIR__ . '/../views/transaksi.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['id_layanan']) || empty($_POST['id_kandang']) || empty($_POST['nama_hewan'])) {
                    throw new Exception("Data wajib tidak lengkap!");
                }

                // 1. Handle Pelanggan (FIX BUG DISINI)
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
                    // Update status kandang & hewan
                    $this->kandangModel->updateStatus($transaksiData['id_kandang'], 'terpakai');
                    $this->hewanModel->updateStatus($id_hewan, 'sedang_dititipkan');
                    
                    header('Location: index.php?page=transaksi&status=success');
                    exit;
                } else {
                    throw new Exception("Gagal menyimpan ke database.");
                }

            } catch (Exception $e) {
                // Tampilkan pesan error spesifik di URL agar tahu salahnya dimana
                header('Location: index.php?page=transaksi&status=error&message=' . urlencode($e->getMessage()));
                exit;
            }
        }
    }

    private function handlePelanggan($data) {
        $id = $data['id_pelanggan'] ?? null;

        // PERBAIKAN: Cek jika ID valid DAN bukan string "new"
        if (!empty($id) && $id !== 'new') {
            return $id;
        }
        
        // Jika "new", buat baru
        $pelangganData = [
            'nama_pelanggan' => $data['search_pemilik'] ?? 'Tanpa Nama', // Ambil dari input hidden search_pemilik
            'no_hp' => $data['no_hp'] ?? '-',
            'alamat' => $data['alamat'] ?? '-'
        ];

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

        // Pakai create yang me-return ID langsung
        return $this->hewanModel->create($hewanData);
    }

    public function checkout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_transaksi'];
            $data = [
                'tanggal_keluar_aktual' => date('Y-m-d')
            ];

            if ($this->transaksiModel->updateCheckout($id, $data)) {
                $trx = $this->transaksiModel->getById($id);
                if($trx) {
                    $this->kandangModel->updateStatus($trx['id_kandang'], 'tersedia');
                }
                header('Location: index.php?page=transaksi&tab=pengembalian&status=success');
            } else {
                header('Location: index.php?page=transaksi&tab=pengembalian&status=error&message=Gagal Checkout');
            }
            exit;
        }
    }
}