<?php
require_once __DIR__ . '/../models/Hewan.php';
require_once __DIR__ . '/../models/Pelanggan.php';

class HewanController
{
    private $hewanModel;
    private $pelangganModel;

    public function __construct()
    {
        $this->hewanModel = new Hewan();
        $this->pelangganModel = new Pelanggan();
    }

    public function index()
    {
        // Ambil data untuk ditampilkan di tabel
        $dataHewan = $this->hewanModel->getAll();
        
        // Load view
        require_once __DIR__ . '/../views/hewan.php';
    }

    public function create()
    {
        // Logika untuk menampilkan form tambah (jika dipisah)
        // atau menghandle proses simpan
    }

    public function store()
    {
        // Proses simpan data hewan (jika ada form khusus hewan)
    }
    
    // Method updateStatus dipanggil via AJAX/Redirect
    public function updateStatus() 
    {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($id && $status) {
            if ($this->hewanModel->updateStatus($id, $status)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal update status']);
            }
        }
        exit;
    }
}