<?php
$pageTitle  = 'Data Pelanggan';
$activeMenu = 'pemilik';
include __DIR__ . '/template/header.php';
?>

<h2 class="mb-3">Data Pelanggan</h2>

<div class="card shadow-sm mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h5 class="card-title mb-0">Daftar Pelanggan</h5>
            <small class="text-muted">Data pemilik hewan yang sudah terdaftar.</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Pelanggan
        </button>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 90px;">Kode</th>
                        <th>Nama Pelanggan</th>
                        <th style="width: 150px;">No. HP</th>
                        <th>Alamat</th>
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Nanti baris ini diganti loop dari database -->
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada data pelanggan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>
