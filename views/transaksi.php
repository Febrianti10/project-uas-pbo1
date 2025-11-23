<?php
$pageTitle  = 'Transaksi Penitipan Hewan';
$activeMenu = 'transaksi';
include __DIR__ . '/template/header.php';

$tab = $_GET['tab'] ?? 'pendaftaran'; // default tab
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

                <!-- ======================================================== -->
                <!-- ================ TAB 1 : PENDAFTARAN =================== -->
                <!-- ======================================================== -->
                <?php if ($tab === 'pendaftaran'): ?>

                    <h5 class="mb-3">Form Pendaftaran Penitipan</h5>

                    <form method="post" action="index.php?page=transaksi&action=save_checkin">
                        <div class="row g-4">

                            <!-- ======================== -->
                            <!-- BAGIAN 1: DATA PEMILIK  -->
                            <!-- ======================== -->
                            <div class="col-lg-6">
                                <div class="card p-3 h-100">
                                    <h6 class="mb-3 text-primary">Informasi Pemilik</h6>

                                    <label class="form-label">Nama Pemilik</label>
                                    <input type="text" id="search_pemilik" class="form-control" autocomplete="off" placeholder="Ketik nama pemilik...">

                                    <!-- Kotak suggestion -->
                                    <div id="suggest_pemilik" class="border rounded bg-white position-absolute w-100 shadow-sm d-none" style="z-index: 9999;"></div>

                                    <!-- Hidden untuk menyimpan ID pemilik yang dipilih -->
                                    <input type="hidden" name="pemilik_id" id="pemilik_id">

                                    <label class="form-label mt-3">Nomor HP</label>
                                    <input type="text" id="p_hp" class="form-control" readonly>

                                    <label class="form-label mt-3">Alamat</label>
                                    <textarea id="p_alamat" class="form-control" rows="2" readonly></textarea>

                                </div>
                            </div>

                            <!-- Dropdown Pemilik hewan -->
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {

                                    let input = document.getElementById('search_pemilik');
                                    let box = document.getElementById('suggest_pemilik');

                                    // ketika mengetik
                                    input.addEventListener('keyup', function() {
                                        let q = this.value.trim();
                                        if (q.length < 1) {
                                            box.classList.add('d-none');
                                            return;
                                        }

                                        fetch("ajax/get_pemilik.php?q=" + encodeURIComponent(q))
                                            .then(res => res.json())
                                            .then(data => {
                                                let html = "";

                                                if (data.length === 0) {
                                                    html = `<div class="p-2 text-muted small">Tidak ditemukan</div>`;
                                                } else {
                                                    data.forEach(p => {
                                                        html += `
                                                <div class="p-2 hover-bg" 
                                                    style="cursor:pointer"
                                                    data-id="${p.id}"
                                                    data-nama="${p.nama}"
                                                    data-hp="${p.no_telp}"
                                                    data-alamat="${p.alamat}">
                                                    ${p.nama} <span class="text-muted small">(${p.no_telp})</span>
                                                </div>
                                            `;
                                                    });
                                                }

                                                box.innerHTML = html;
                                                box.classList.remove('d-none');

                                                // klik item suggestion
                                                document.querySelectorAll('#suggest_pemilik div[data-id]').forEach(item => {
                                                    item.addEventListener('click', function() {
                                                        // isi input
                                                        input.value = this.dataset.nama;
                                                        document.getElementById('pemilik_id').value = this.dataset.id;

                                                        // auto-fill kolom lain
                                                        document.getElementById('p_hp').value = this.dataset.hp;
                                                        document.getElementById('p_alamat').value = this.dataset.alamat;

                                                        // sembunyikan suggestion
                                                        box.classList.add('d-none');
                                                    });
                                                });
                                            });
                                    });
                                });
                            </script>


                            <!-- ====================== -->
                            <!-- BAGIAN 2: DATA HEWAN  -->
                            <!-- ====================== -->
                            <div class="col-lg-6">
                                <div class="card p-3 h-100">
                                    <h6 class="mb-3 text-primary">Informasi Hewan</h6>

                                    <label class="form-label mt-3">Nama Hewan</label>
                                    <input type="text" id="h_nama" class="form-control" readonly>

                                    <label class="form-label">Jenis Hewan</label>
                                    <select name="hewan_id" class="form-select" id="hewanSelect" required>
                                        <option value="">-- Pilih Hewan --</option>
                                        <option value="Kucing">Kucing</option>
                                        <option value="Anjing">Anjing</option>
                                    </select>

                                    <label class="form-label mt-3">Ras</label>
                                    <input type="text" id="h_ras" class="form-control" readonly>

                                    <label class="form-label mt-3">Ukuran</label>
                                    <input type="text" id="h_ukuran" class="form-control" readonly>

                                    <label class="form-label mt-3">Warna</label>
                                    <input type="text" id="h_warna" class="form-control" readonly>

                                    <label class="form-label mt-3">Catatan Khusus</label>
                                    <textarea id="h_catatan" class="form-control" rows="2" readonly></textarea>
                                </div>
                            </div>

                            <!-- ====================== -->
                            <!-- BAGIAN 3: LAYANAN     -->
                            <!-- ====================== -->

                                        <!-- Layanan Tambahan (boleh lebih dari satu) -->
                                        <div class="col-12">
                                            <div class="card p-3">
                                                <h6 class="mb-3 text-primary">Layanan</h6>

                                                <div class="row g-3">
                                                    <!-- Layanan Utama -->
                                                    <div class="col-lg-4">
                                                        <label class="form-label">Layanan Utama</label>
                                                        <select name="kode_paket" class="form-select" id="paketSelect" required>
                                                            <option value="">-- Pilih Layanan Utama --</option>
                                                            <?php foreach ($paketList as $pk): ?>
                                                                <option value="<?= $pk['kode_paket'] ?>">
                                                                    <?= $pk['nama_paket'] ?> - Rp <?= number_format($pk['harga'], 0, ',', '.') ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <!-- Layanan Tambahan: dropdown dengan checkbox -->
                                                    <div class="col-lg-8">
                                                        <label class="form-label d-block">Layanan Tambahan</label>

                                                        <div class="dropdown">
                                                            <!-- Tombol seperti form-select -->
                                                            <button class="form-select text-start d-flex justify-content-between align-items-center"
                                                                type="button"
                                                                id="dropdownLayananTambahan"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <span id="ltLabel">Pilih layanan tambahan (opsional)</span>
                                                            </button>

                                                            <!-- Isi dropdown: checkbox -->
                                                            <div class="dropdown-menu w-100 p-2"
                                                                aria-labelledby="dropdownLayananTambahan"
                                                                style="max-height: 260px; overflow-y: auto;">

                                                                <?php foreach ($layananTambahanList ?? [] as $lt): ?>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input lt-checkbox"
                                                                            type="checkbox"
                                                                            value="<?= $lt['kode'] ?>"
                                                                            id="lt_<?= $lt['kode'] ?>">
                                                                        <label class="form-check-label small" for="lt_<?= $lt['kode'] ?>">
                                                                            <?= htmlspecialchars($lt['nama_layanan']) ?>
                                                                            - Rp <?= number_format($lt['harga'], 0, ',', '.') ?>
                                                                            <?= $lt['satuan']; ?>
                                                                        </label>
                                                                    </div>
                                                                <?php endforeach; ?>

                                                                <?php if (empty($layananTambahanList ?? [])): ?>
                                                                    <p class="text-muted small mb-0">
                                                                        Data layanan tambahan belum diisi.
                                                                    </p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <!-- hidden input untuk dikirim ke server -->
                                                        <div id="ltHiddenContainer"></div>

                                                        <small class="text-muted">
                                                            Bisa pilih lebih dari satu layanan tambahan (opsional).
                                                        </small>
                                                    </div>


                                                    <!-- ====================== -->
                                                    <!-- BAGIAN 4: DETAIL TITIP -->
                                                    <!-- ====================== -->
                                                    <div class="col-12">
                                                        <div class="card p-3">
                                                            <h6 class="mb-3 text-primary">Detail Penitipan</h6>

                                                            <div class="row g-3">
                                                                <div class="col-lg-4">
                                                                    <label class="form-label">Tanggal Masuk</label>
                                                                    <input type="date" name="tgl_masuk" class="form-control"
                                                                        value="<?= date('Y-m-d') ?>" required>
                                                                </div>

                                                                <div class="col-lg-4">
                                                                    <label class="form-label">Nomor Kandang</label>
                                                                    <input type="text" name="no_kandang" class="form-control"
                                                                        placeholder="K-05" required>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div><!-- /.row -->

                                                <div class="d-flex justify-content-end mt-3">
                                                    <button class="btn btn-primary">
                                                        Simpan & Cetak Bukti
                                                    </button>
                                                </div>
                    </form>

                    <!-- Script kecil untuk autofill data pemilik & hewan -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const pemilikSelect = document.getElementById('pemilikSelect');
                            const hewanSelect = document.getElementById('hewanSelect');

                            if (pemilikSelect) {
                                pemilikSelect.addEventListener('change', function() {
                                    const opt = this.options[this.selectedIndex];
                                    document.getElementById('p_nama').value = opt.dataset.nama || '';
                                    document.getElementById('p_hp').value = opt.dataset.hp || '';
                                    document.getElementById('p_alamat').value = opt.dataset.alamat || '';
                                });
                            }

                            if (hewanSelect) {
                                hewanSelect.addEventListener('change', function() {
                                    const opt = this.options[this.selectedIndex];
                                    document.getElementById('h_nama').value = opt.dataset.nama || '';
                                    document.getElementById('h_jenis').value = opt.dataset.jenis || '';
                                    document.getElementById('h_ras').value = opt.dataset.ras || '';
                                    document.getElementById('h_ukuran').value = opt.dataset.ukuran || '';
                                    document.getElementById('h_warna').value = opt.dataset.warna || '';
                                    document.getElementById('h_catatan').value = opt.dataset.catatan || '';
                                });
                            }
                        });
                    </script>

                    <!-- ======================================================== -->
                    <!-- ================ TAB 2 : PENGEMBALIAN ================== -->
                    <!-- ======================================================== -->
                <?php else: ?>

                    <h5 class="mb-3">Proses Pengembalian Hewan & Pembayaran</h5>

                    <form method="get" class="mb-4">
                        <input type="hidden" name="page" value="transaksi">
                        <input type="hidden" name="tab" value="pengembalian">

                        <div class="row g-2">
                            <div class="col-md-5">
                                <label class="form-label">Nomor Form (no_form)</label>
                                <input type="text" name="no_form" class="form-control"
                                    placeholder="Misal: F-20250101-001"
                                    value="<?= htmlspecialchars($_GET['no_form'] ?? '') ?>">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-outline-primary w-100">Cari</button>
                            </div>
                        </div>
                    </form>

                    <?php if (!empty($transaksi)): ?>

                        <div class="p-3 mb-3 bg-body-secondary rounded">
                            <div><strong>No Form:</strong> <?= $transaksi['no_form'] ?></div>
                            <div><strong>Pemilik:</strong> <?= $transaksi['nama_pemilik'] ?></div>
                            <div><strong>Hewan:</strong> <?= $transaksi['nama_hewan'] ?></div>
                            <div><strong>Paket:</strong> <?= $transaksi['nama_paket'] ?></div>
                            <div><strong>Lama Inap:</strong> <?= $transaksi['lama_inap'] ?> hari</div>
                            <div><strong>Total Tagihan:</strong> Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></div>
                        </div>

                        <form method="post" action="index.php?page=transaksi&action=save_checkout">
                            <input type="hidden" name="no_form" value="<?= $transaksi['no_form'] ?>">

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Diskon (Rp)</label>
                                    <input type="number" min="0" name="disc" class="form-control" value="0">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">DP (Rp)</label>
                                    <input type="number" min="0" name="dp" class="form-control"
                                        value="<?= $transaksi['dp'] ?? 0 ?>">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <select name="metode" class="form-select">
                                        <option value="cash">Cash</option>
                                        <option value="qris">QRIS</option>
                                        <option value="transfer">Transfer Bank</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-success">Simpan & Cetak Struk</button>
                            </div>
                        </form>

                    <?php elseif (isset($_GET['no_form'])): ?>

                        <div class="alert alert-warning">
                            No form <strong><?= htmlspecialchars($_GET['no_form']) ?></strong> tidak ditemukan.
                        </div>

                    <?php else: ?>

                        <p class="text-muted">Masukkan nomor formulir di atas untuk melakukan check-out.</p>

                    <?php endif; ?>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>