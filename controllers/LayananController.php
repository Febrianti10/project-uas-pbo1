<?php
require_once __DIR__ . '/../models/Layanan.php';

class LayananController
{
    private $layananModel;

    public function __construct()
    {
        // PERBAIKAN: Jangan passing $db di sini. Model urus koneksinya sendiri.
        $this->layananModel = new Layanan();
    }

    public function index()
    {
        $daftarLayanan = $this->layananModel->getAll();
        // Pastikan variabel $daftarLayanan dikirim ke view
        require_once __DIR__ . '/../views/layanan.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_layanan' => $_POST['nama_layanan'],
                'harga' => $_POST['harga'],
                'deskripsi' => $_POST['deskripsi']
            ];

            if ($this->layananModel->create($data)) {
                header('Location: index.php?page=layanan&status=success');
            } else {
                header('Location: index.php?page=layanan&status=error');
            }
            exit;
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_layanan'];
            $data = [
                'nama_layanan' => $_POST['nama_layanan'],
                'harga' => $_POST['harga'],
                'deskripsi' => $_POST['deskripsi']
            ];

            if ($this->layananModel->update($id, $data)) {
                header('Location: index.php?page=layanan&status=updated');
            } else {
                header('Location: index.php?page=layanan&status=error');
            }
            exit;
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->layananModel->delete($id);
        }
        header('Location: index.php?page=layanan&status=deleted');
        exit;
    }
}