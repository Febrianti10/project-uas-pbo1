<?php
$pageTitle  = 'Transaksi Penitipan Hewan';
$activeMenu = 'transaksi';
include __DIR__ . '/template/header.php';

/*
|======================================================
|  DATA PAKET & LAYANAN (sementara statis)
|======================================================
*/

$paketList = [
    ['kode_paket' => 'P001', 'nama_paket' => 'Paket Daycare (Tanpa Menginap) ≤ 5 kg', 'harga' => 50000],
    ['kode_paket' => 'P002', 'nama_paket' => 'Paket Daycare (Tanpa Menginap) > 5 kg', 'harga' => 60000],
    ['kode_paket' => 'P003', 'nama_paket' => 'Paket Boarding',                       'harga' => 120000],
    ['kode_paket' => 'P004', 'nama_paket' => 'Paket Boarding > 5 kg',                'harga' => 120000],
    ['kode_paket' => 'P005', 'nama_paket' => 'Paket Boarding VIP',                   'harga' => 250000],
];

$layananTambahanList = [
    ['kode' => 'G001', 'nama_layanan' => 'Grooming Dasar',     'harga' => 100000, 'satuan' => '/ sesi'],
    ['kode' => 'G002', 'nama_layanan' => 'Grooming Lengkap',   'harga' => 170000, 'satuan' => '/ sesi'],
    ['kode' => 'L003', 'nama_layanan' => 'Vitamin / Suplemen', 'harga' => 50000,  'satuan' => '/ pemberian'],
    ['kode' => 'L004', 'nama_layanan' => 'Vaksin',             'harga' => 260000, 'satuan' => '/ dosis'],
];

$tab = $_GET['tab'] ?? 'pendaftaran';
?>

<div class="row justify-content-center">
    <div class="col-12 col-xl-12">
        <div class="card shadow-sm">

            <div class="card-header border-0 pb-0">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link <?= $tab === 'pendaftaran' ? 'active' : '' ?>"
                           href="index.php?page=transaksi&tab=pendaftaran">
                            Pendaftaran (Check-In)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $tab === 'pengembalian' ? 'active' : '' ?>"
                           href="index.php?page=transaksi&tab=pengembalian">
                            Pengembalian (Check-Out)
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">

                <?php if ($tab === 'pendaftaran'): ?>

                    <h5 class="mb-3">Form Pendaftaran Penitipan</h5>

                    <form method="post" action="index.php?page=transaksi&action=save_checkin">
                        <div class="row g-4">

                            <!-- INFORMASI PEMILIK -->
                            <div class="col-lg-6">
                                <div class="card p-3 h-100 position-relative">
                                    <h6 class="mb-3 text-primary">Informasi Pemilik</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Nama Pemilik</label>
                                        <input type="text" id="search_pemilik" class="form-control"
                                               autocomplete="off" placeholder="Ketik nama pemilik...">
                                        <div id="suggest_pemilik"
                                             class="border rounded bg-white position-absolute w-100 shadow-sm d-none"
                                             style="z-index: 9999;"></div>
                                        <input type="hidden" name="pemilik_id" id="pemilik_id">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nomor HP</label>
                                        <input type="text" id="p_hp" class="form-control" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Alamat</label>
                                        <textarea id="p_alamat" class="form-control" rows="2" readonly></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- INFORMASI HEWAN -->
                            <div class="col-lg-6">
                                <div class="card p-3 h-100">
                                    <h6 class="mb-3 text-primary">Informasi Hewan</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Nama Hewan</label>
                                        <input type="text" name="nama_hewan" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jenis Hewan</label>
                                        <select name="jenis_hewan" class="form-select" required>
                                            <option value="">-- Pilih Hewan --</option>
                                            <option value="Kucing">Kucing</option>
                                            <option value="Anjing">Anjing</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ras</label>
                                        <input type="text" name="ras" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Ukuran</label>
                                        <select name="ukuran" class="form-select">
                                            <option value="">-- Pilih Ukuran --</option>
                                            <option value="Kecil">Kecil</option>
                                            <option value="Sedang">Sedang</option>
                                            <option value="Besar">Besar</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Warna</label>
                                        <input type="text" name="warna" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Catatan Khusus</label>
                                        <textarea name="catatan" class="form-control" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- LAYANAN -->
                            <div class="col-12">
                                <div class="card p-3">
                                    <h6 class="mb-3 text-primary">Layanan</h6>

                                    <div class="row g-3">

                                        <!-- Paket Utama -->
                                        <div class="col-lg-4">
                                            <label class="form-label">Paket Utama</label>
                                            <select name="kode_paket" class="form-select" id="paketSelect" required>
                                                <option value="">-- Pilih Paket --</option>
                                                <?php foreach ($paketList as $pk): ?>
                                                    <option value="<?= $pk['kode_paket']; ?>"
                                                            data-harga="<?= $pk['harga']; ?>">
                                                        <?= $pk['nama_paket']; ?>
                                                        - Rp <?= number_format($pk['harga'],0,',','.'); ?>/hari
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- Layanan Tambahan (panel custom, bukan dropdown Bootstrap) -->
                                        <div class="col-lg-8">
                                            <label class="form-label d-block">Layanan Tambahan</label>

                                            <!-- Tombol "pseudo-dropdown" -->
                                            <button type="button"
                                                    class="form-select text-start d-flex justify-content-between align-items-center"
                                                    id="btnLayananTambahan">
                                                <span id="ltLabel">Pilih layanan tambahan (opsional)</span>
                                                <i class="bi bi-chevron-down ms-2 small"></i>
                                            </button>

                                            <!-- Panel yang dibuka/tutup manual (tidak pakai .dropdown-menu) -->
                                            <div id="panelLayananTambahan"
                                                 class="border rounded p-2 mt-1 d-none"
                                                 style="max-height:260px; overflow-y:auto;">

                                                <?php foreach ($layananTambahanList as $lt): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input lt-checkbox"
                                                               type="checkbox"
                                                               name="layanan_tambahan[]"
                                                               value="<?= $lt['kode']; ?>"
                                                               data-harga="<?= $lt['harga']; ?>"
                                                               id="lt_<?= $lt['kode']; ?>">
                                                        <label class="form-check-label small" for="lt_<?= $lt['kode']; ?>">
                                                            <?= $lt['nama_layanan']; ?>
                                                            - Rp <?= number_format($lt['harga'],0,',','.'); ?>
                                                            <?= $lt['satuan']; ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>

                                            </div>

                                            <small class="text-muted d-block mt-1">
                                                Bisa pilih lebih dari satu layanan tambahan.
                                            </small>
                                        </div>

                                    </div>

                                    <!-- TOTAL -->
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small text-muted">Total Estimasi Biaya</div>
                                                <h3 id="totalHarga" class="fw-bold text-primary mb-0">Rp 0</h3>
                                            </div>
                                        </div>
                                        <input type="hidden" name="total" id="totalInput">
                                        <div class="small text-muted mt-1">
                                            Total = (harga paket × lama inap) + jumlah layanan tambahan.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- DETAIL PENITIPAN -->
                            <div class="col-12">
                                <div class="card p-3">
                                    <h6 class="mb-3 text-primary">Detail Penitipan</h6>

                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <label class="form-label">Tanggal Masuk</label>
                                            <input type="date" name="tgl_masuk" class="form-control"
                                                   value="<?= date('Y-m-d'); ?>" required>
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="form-label">Lama Inap (hari)</label>
                                            <input type="number" name="lama_inap" id="lamaInap"
                                                   class="form-control" min="1" value="1" required>
                                        </div>

                                        <div class="col-lg-4">
                                            <label class="form-label">Nomor Kandang</label>
                                            <input type="text" name="no_kandang" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- /.row g-4 -->

                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-primary">Simpan &amp; Cetak Bukti</button>
                        </div>
                    </form>

                <?php else: ?>

                    <h5 class="mb-3">Proses Pengembalian Hewan &amp; Pembayaran</h5>

                    <p class="text-muted">Bagian check-out bisa kamu lanjutkan nanti (logic backend).</p>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- ============================
     SCRIPT HALAMAN INI
============================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // -------- Toggle panel layanan tambahan (tanpa dropdown Bootstrap) --------
    const btnLT   = document.getElementById('btnLayananTambahan');
    const panelLT = document.getElementById('panelLayananTambahan');
    const ltLabel = document.getElementById('ltLabel');
    const checkboxes = document.querySelectorAll('.lt-checkbox');
    const paketSelect = document.getElementById('paketSelect');
    const lamaInap = document.getElementById('lamaInap');
    const totalHarga = document.getElementById('totalHarga');
    const totalInput = document.getElementById('totalInput');

    if (btnLT && panelLT) {
        btnLT.addEventListener('click', function () {
            panelLT.classList.toggle('d-none');
        });
    }

    function formatRupiah(num) {
        return num.toLocaleString('id-ID');
    }

    function updateLabelTambahan() {
        if (!ltLabel) return;
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        if (selected.length === 0) {
            ltLabel.textContent = 'Pilih layanan tambahan (opsional)';
        } else if (selected.length === 1) {
            const lbl = selected[0].nextElementSibling;
            ltLabel.textContent = lbl ? lbl.textContent.trim() : '1 layanan dipilih';
        } else {
            ltLabel.textContent = selected.length + ' layanan dipilih';
        }
    }

    function hitungTotal() {
        let total = 0;
        const hari = parseInt(lamaInap?.value || '1', 10);

        // Paket utama
        if (paketSelect && paketSelect.value) {
            const opt = paketSelect.options[paketSelect.selectedIndex];
            const hargaPaket = parseInt(opt.getAttribute('data-harga') || '0', 10);
            if (!isNaN(hargaPaket) && hari > 0) {
                total += hargaPaket * hari;
            }
        }

        // Layanan tambahan
        checkboxes.forEach(cb => {
            if (cb.checked) {
                const h = parseInt(cb.dataset.harga || '0', 10);
                if (!isNaN(h)) total += h;
            }
        });

        if (totalHarga) totalHarga.textContent = 'Rp ' + formatRupiah(isNaN(total) ? 0 : total);
        if (totalInput) totalInput.value = isNaN(total) ? 0 : total;
    }

    // Event listeners
    if (paketSelect) paketSelect.addEventListener('change', hitungTotal);
    if (lamaInap) lamaInap.addEventListener('input', hitungTotal);
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            updateLabelTambahan();
            hitungTotal();
        });
    });

    // Init
    updateLabelTambahan();
    hitungTotal();
});
</script>

<?php include __DIR__ . '/template/footer.php'; ?>
