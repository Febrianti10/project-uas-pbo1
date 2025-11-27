<?php
$pageTitle  = 'Transaksi Penitipan Hewan';
$activeMenu = 'transaksi';
include __DIR__ . '/template/header.php';

// Pastikan data dikirim dari Controller. 
// Jika file ini diakses langsung tanpa controller (not recommended tapi kita handle), 
// kita inisialisasi model di sini sebagai fallback.
if (!isset($paketList)) {
    require_once __DIR__ . '/../models/Layanan.php';
    require_once __DIR__ . '/../models/Kandang.php';
    require_once __DIR__ . '/../models/Pelanggan.php';
    require_once __DIR__ . '/../models/Transaksi.php';

    $layananModel = new Layanan();
    $kandangModel = new Kandang();
    $pelangganModel = new Pelanggan();
    $transaksiModel = new Transaksi();

    $paketList = $layananModel->getAll();
    $kandangTersedia = $kandangModel->getAll(); // Idealnya difilter yang 'tersedia'
    $pelangganList = $pelangganModel->getAll();
    $hewanMenginap = $transaksiModel->getActiveTransactions();
}

$tab = $_GET['tab'] ?? 'pendaftaran';
?>

<?php if (isset($_GET['status'])): ?>
    <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show m-3">
        <?php if ($_GET['status'] === 'success'): ?>
            <strong>Sukses!</strong> Transaksi berhasil dibuat.
        <?php else: ?>
            <strong>Error!</strong> <?= htmlspecialchars($_GET['message'] ?? 'Terjadi kesalahan') ?>
        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-12 col-xl-12">
        <div class="card shadow-sm">

            <div class="card-header border-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link <?= $tab === 'pendaftaran' ? 'active' : '' ?>"
                           href="index.php?page=transaksi&tab=pendaftaran">
                            <i class="bi bi-box-arrow-in-down me-2"></i>Pendaftaran (Check-In)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $tab === 'pengembalian' ? 'active' : '' ?>"
                           href="index.php?page=transaksi&tab=pengembalian">
                            <i class="bi bi-box-arrow-up me-2"></i>Pengembalian (Check-Out)
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">

                <?php if ($tab === 'pendaftaran'): ?>

                    <h5 class="mb-3">Form Pendaftaran Penitipan</h5>

                    <form method="post" action="index.php?action=createTransaksi" id="formPendaftaran">

                        <div class="row g-4">

                            <div class="col-lg-6">
                                <div class="card p-3 h-100 position-relative">
                                    <h6 class="mb-3 text-primary">Informasi Pemilik</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                        <select name="id_pelanggan" class="form-select" id="selectPelanggan" required>
                                            <option value="">-- Pilih Pemilik --</option>
                                            <?php foreach ($pelangganList as $p): ?>
                                                <option value="<?= $p['id'] ?>" 
                                                        data-hp="<?= $p['hp'] ?>" 
                                                        data-alamat="<?= htmlspecialchars($p['alamat']) ?>">
                                                    <?= htmlspecialchars($p['nama']) ?> (<?= $p['hp'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="new" class="fw-bold text-primary">+ Tambah Pemilik Baru</option>
                                        </select>
                                    </div>

                                    <div class="mb-3" id="newCustomerNameField" style="display: none;">
                                        <label class="form-label">Nama Lengkap Pemilik Baru <span class="text-danger">*</span></label>
                                        <input type="text" name="search_pemilik" class="form-control" 
                                               placeholder="Masukkan nama pemilik baru">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                        <input type="text" name="no_hp" id="p_hp" class="form-control"
                                            placeholder="08xxxxxxxxxx" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea name="alamat" id="p_alamat" class="form-control"
                                            rows="2" placeholder="Alamat lengkap"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card p-3 h-100">
                                    <h6 class="mb-3 text-primary">Informasi Hewan</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Nama Hewan <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_hewan" class="form-control"
                                            placeholder="Contoh: Mochi" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                            <select name="jenis" class="form-select" id="jenisHewanSelect" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="Kucing">Kucing</option>
                                                <option value="Anjing">Anjing</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ukuran</label>
                                            <select name="ukuran" class="form-select" id="ukuranHewanSelect">
                                                <option value="Kecil">Kecil</option>
                                                <option value="Sedang">Sedang</option>
                                                <option value="Besar">Besar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ras</label>
                                            <input type="text" name="ras" class="form-control" placeholder="Persia, Domestik, dll">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Warna</label>
                                            <input type="text" name="warna" class="form-control" placeholder="Putih, Belang">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Catatan Kesehatan/Khusus</label>
                                        <textarea name="catatan" class="form-control" rows="2"
                                            placeholder="Alergi, galak, butuh obat khusus, dll."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card p-3">
                                    <h6 class="mb-3 text-primary">Paket & Layanan</h6>

                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label class="form-label">Pilih Paket <span class="text-danger">*</span></label>
                                            <select name="id_layanan" class="form-select" id="paketSelect" required>
                                                <option value="">-- Pilih Paket Penitipan --</option>
                                                <?php foreach ($paketList as $pk): ?>
                                                    <option value="<?= $pk['id_layanan'] ?>" 
                                                            data-harga="<?= $pk['harga'] ?>">
                                                        <?= htmlspecialchars($pk['nama_layanan']) ?> 
                                                        (Rp <?= number_format($pk['harga'], 0, ',', '.') ?> /hari)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Tanggal Masuk</label>
                                            <input type="date" name="tanggal_masuk" class="form-control" 
                                                   value="<?= date('Y-m-d') ?>" required>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <label class="form-label">Durasi (Hari) <span class="text-danger">*</span></label>
                                            <input type="number" name="durasi" id="lamaInap" class="form-control" 
                                                   min="1" value="1" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Pilih Kandang <span class="text-danger">*</span></label>
                                        <select name="id_kandang" class="form-select" id="id_kandang" required>
                                            <option value="">-- Pilih Kandang Tersedia --</option>
                                            <?php foreach ($kandangTersedia as $k): ?>
                                                <?php if($k['status'] === 'tersedia'): ?>
                                                <option value="<?= $k['id'] ?>" data-tipe="<?= $k['tipe'] ?>">
                                                    <?= htmlspecialchars($k['kode']) ?> - <?= $k['tipe'] ?> 
                                                    (<?= htmlspecialchars($k['catatan'] ?? '') ?>)
                                                </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted">Pastikan ukuran kandang sesuai dengan hewan.</small>
                                    </div>

                                    <div class="alert alert-primary d-flex justify-content-between align-items-center mt-3 mb-0">
                                        <div>
                                            <span class="d-block small">Estimasi Total Biaya</span>
                                            <h3 class="mb-0 fw-bold" id="totalHarga">Rp 0</h3>
                                            <input type="hidden" name="total_biaya" id="totalInput" value="0">
                                        </div>
                                        <button type="submit" class="btn btn-light fw-bold text-primary px-4 py-2">
                                            <i class="bi bi-save me-2"></i>SIMPAN TRANSAKSI
                                        </button>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </form>

                <?php else: ?>

                    <h5 class="mb-3">Daftar Hewan Menginap (Active)</h5>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Pemilik</th>
                                    <th>Hewan</th>
                                    <th>Kandang</th>
                                    <th>Masuk</th>
                                    <th>Durasi</th>
                                    <th>Total Awal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($hewanMenginap)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            Belum ada hewan yang sedang dititipkan.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($hewanMenginap as $row): ?>
                                        <tr>
                                            <td class="fw-bold"><?= htmlspecialchars($row['kode_transaksi']) ?></td>
                                            <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                            <td>
                                                <?= htmlspecialchars($row['nama_hewan']) ?> 
                                                <span class="badge bg-secondary"><?= htmlspecialchars($row['jenis_hewan']) ?></span>
                                            </td>
                                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['kode_kandang']) ?></span></td>
                                            <td><?= date('d/m/Y', strtotime($row['tanggal_masuk'])) ?></td>
                                            <td><?= $row['durasi'] ?> Hari</td>
                                            <td>Rp <?= number_format($row['total_biaya'], 0, ',', '.') ?></td>
                                            <td>
                                                <button class="btn btn-success btn-sm" 
                                                        onclick="showCheckoutModal('<?= $row['id_transaksi'] ?>', '<?= $row['kode_transaksi'] ?>', <?= $row['total_biaya'] ?>, <?= $row['durasi'] ?>)">
                                                    <i class="bi bi-box-arrow-right"></i> Checkout
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCheckout" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="index.php?action=checkoutTransaksi" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proses Checkout & Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_transaksi" id="co_id_transaksi">
                
                <div class="mb-3 text-center">
                    <h6 class="text-muted">Kode Transaksi</h6>
                    <h3 class="fw-bold" id="co_kode_transaksi">TRX-000</h3>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label small">Durasi Aktual (Hari)</label>
                        <input type="number" name="durasi_aktual" id="co_durasi" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label small">Total Tagihan (Rp)</label>
                        <input type="number" name="total_bayar" id="co_total" class="form-control fw-bold" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-select" required>
                        <option value="Cash">Tunai (Cash)</option>
                        <option value="Transfer">Transfer Bank</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-2"></i>Konfirmasi Lunas
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. LOGIC AUTO FILL PELANGGAN ---
    const selectPelanggan = document.getElementById('selectPelanggan');
    const inputHp = document.getElementById('p_hp');
    const inputAlamat = document.getElementById('p_alamat');
    const divNewCustomer = document.getElementById('newCustomerNameField');
    const inputNewName = divNewCustomer ? divNewCustomer.querySelector('input') : null;

    if (selectPelanggan) {
        selectPelanggan.addEventListener('change', function() {
            const val = this.value;
            const opt = this.options[this.selectedIndex];

            if (val === 'new') {
                // Mode Pelanggan Baru
                divNewCustomer.style.display = 'block';
                inputNewName.required = true;
                inputHp.value = '';
                inputAlamat.value = '';
                inputHp.readOnly = false;
                inputAlamat.readOnly = false;
            } else if (val !== '') {
                // Mode Pelanggan Lama (Auto-fill)
                divNewCustomer.style.display = 'none';
                inputNewName.required = false;
                inputHp.value = opt.getAttribute('data-hp');
                inputAlamat.value = opt.getAttribute('data-alamat');
                // Optional: set readonly biar tidak diubah sembarangan
                // inputHp.readOnly = true; 
            } else {
                // Reset
                divNewCustomer.style.display = 'none';
                inputNewName.required = false;
                inputHp.value = '';
                inputAlamat.value = '';
            }
        });
    }

    // --- 2. LOGIC HITUNG TOTAL OTOMATIS ---
    const selectPaket = document.getElementById('paketSelect');
    const inputDurasi = document.getElementById('lamaInap');
    const txtTotal = document.getElementById('totalHarga');
    const inputTotal = document.getElementById('totalInput');

    function hitungTotal() {
        let harga = 0;
        if (selectPaket && selectPaket.value !== '') {
            harga = parseFloat(selectPaket.options[selectPaket.selectedIndex].getAttribute('data-harga')) || 0;
        }
        
        let durasi = parseInt(inputDurasi.value) || 1;
        if (durasi < 1) durasi = 1;

        const total = harga * durasi;

        // Format Rupiah
        txtTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
        inputTotal.value = total;
    }

    if (selectPaket) selectPaket.addEventListener('change', hitungTotal);
    if (inputDurasi) inputDurasi.addEventListener('input', hitungTotal);

});

// --- 3. LOGIC MODAL CHECKOUT ---
function showCheckoutModal(id, kode, total, durasi) {
    document.getElementById('co_id_transaksi').value = id;
    document.getElementById('co_kode_transaksi').innerText = kode;
    document.getElementById('co_durasi').value = durasi;
    document.getElementById('co_total').value = total;
    
    var modal = new bootstrap.Modal(document.getElementById('modalCheckout'));
    modal.show();
}
</script>

<?php include __DIR__ . '/template/footer.php'; ?>