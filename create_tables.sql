-- ==================================================
-- 1. TABEL USER (Kasir/Admin)
-- ==================================================
CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Harus disimpan sebagai hash
    nama_lengkap VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==================================================
-- 2. TABEL PELANGGAN (Pemilik Hewan)
-- ==================================================
CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_hp VARCHAR(15) NOT NULL,
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==================================================
-- 3. TABEL HEWAN (Relasi ke PELANGGAN)
-- ==================================================
CREATE TABLE hewan (
    id_hewan INT AUTO_INCREMENT PRIMARY KEY,
    id_pelanggan INT NOT NULL,
    nama_hewan VARCHAR(50) NOT NULL,
    jenis ENUM('anjing', 'kucing') NOT NULL,
    ras VARCHAR(50),
    ukuran ENUM('kecil', 'sedang', 'besar') NOT NULL,
    warna VARCHAR(30),
    catatan_khusus TEXT,
    status ENUM('tersedia', 'sedang_dititipkan', 'sudah_diambil') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Foreign Key
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON DELETE CASCADE
);


-- ==================================================
-- 4. TABEL LAYANAN (Tarif & Deskripsi)
-- ==================================================
CREATE TABLE layanan (
    id_layanan INT AUTO_INCREMENT PRIMARY KEY,
    kode_layanan VARCHAR(10) UNIQUE, -- Untuk P001, G001, dll.
    nama_layanan VARCHAR(100) NOT NULL,
    kategori_layanan ENUM('penitipan', 'tambahan') NOT NULL,
    satuan VARCHAR(20) NOT NULL,
    detail TEXT, -- Kolom penting untuk deskripsi yang dibutuhkan di view
    harga_kecil DECIMAL(10,2) DEFAULT 0,
    harga_sedang DECIMAL(10,2) DEFAULT 0,
    harga_besar DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==================================================
-- 5. TABEL TRANSAKSI (Relasi ke PELANGGAN, HEWAN, USER)
-- ==================================================
CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    nomor_transaksi VARCHAR(20) NOT NULL UNIQUE,
    id_pelanggan INT NOT NULL,
    id_hewan INT NOT NULL,
    id_user INT NOT NULL,
    tanggal_masuk DATE NOT NULL,
    jam_masuk TIME NOT NULL,
    estimasi_tanggal_keluar DATE,
    estimasi_jam_keluar TIME,
    tanggal_keluar_aktual DATE,
    jam_keluar_aktual TIME,
    durasi_hari INT DEFAULT 0,
    status ENUM('sedang_dititipkan', 'selesai', 'dibatalkan') DEFAULT 'sedang_dititipkan',
    subtotal DECIMAL(10,2) DEFAULT 0,
    diskon DECIMAL(10,2) DEFAULT 0,
    total_biaya DECIMAL(10,2) DEFAULT 0,
    metode_pembayaran ENUM('cash', 'transfer', 'qris'),
    status_pembayaran ENUM('belum_lunas', 'lunas') DEFAULT 'belum_lunas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    -- Foreign Keys
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON DELETE CASCADE,
    FOREIGN KEY (id_hewan) REFERENCES hewan(id_hewan) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
);


-- ==================================================
-- 6. TABEL DETAIL_TRANSAKSI (Relasi ke TRANSAKSI, LAYANAN)
-- ==================================================
CREATE TABLE detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NOT NULL,
    id_layanan INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    harga_satuan DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Foreign Keys
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE,
    FOREIGN KEY (id_layanan) REFERENCES layanan(id_layanan) ON DELETE CASCADE
);


-- ==================================================
-- DATA DUMMY
-- ==================================================

-- Data Dummy User (Password Hash untuk 'password')
INSERT INTO user (username, password, nama_lengkap) VALUES
('kasir01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Nurhaliza'),
('admin01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso');

-- Data Dummy Pelanggan
INSERT INTO pelanggan (nama_pelanggan, no_hp, alamat) VALUES
('Ahmad Rizki', '081234567890', 'Jl. Merdeka No. 123, Jakarta'),
('Dewi Lestari', '081298765432', 'Jl. Sudirman No. 45, Bandung');

-- Data Dummy Hewan
INSERT INTO hewan (id_pelanggan, nama_hewan, jenis, ras, ukuran, warna, status) VALUES
(1, 'Bobby', 'anjing', 'Golden Retriever', 'besar', 'Kuning Keemasan', 'tersedia'),
(2, 'Luna', 'kucing', 'Anggora', 'kecil', 'Abu-abu', 'tersedia');

-- Data Dummy Layanan (Penting: Sesuai dengan array statis yang akan dihapus)
INSERT INTO layanan (kode_layanan, nama_layanan, kategori_layanan, satuan, harga_kecil, harga_sedang, harga_besar, detail) VALUES
-- Penitipan (layananUtama)
('P001', 'Paket Daycare (Tanpa Menginap) ≤ 5 kg', 'penitipan', '/ hari', 50000.00, 60000.00, 75000.00, 'Makan 2x\nMinum\nKandang & pasir\nTidak menginap'),
('P002', 'Paket Daycare (Tanpa Menginap) > 5 kg', 'penitipan', '/ hari', 60000.00, 70000.00, 85000.00, 'Makan 2x\nMinum\nKandang & pasir\nTidak menginap'),
('P003', 'Paket Boarding', 'penitipan', '/ hari', 120000.00, 140000.00, 160000.00, 'Makan\nMinum\nKandang & pasir\nMenginap 24 jam'),
('P005', 'Paket Boarding VIP', 'penitipan', '/ hari', 250000.00, 250000.00, 300000.00, 'Makan\nMinum\nKandang & pasir\nMenginap 24 jam\nGrooming lengkap'),
-- Layanan Tambahan (layananTambahan)
('G001', 'Grooming Dasar', 'tambahan', '/ sesi', 100000.00, 120000.00, 150000.00, 'Pemotongan kuku\nPerapihan bulu\nPembersihan telinga\nMandi & pengeringan'),
('G002', 'Grooming Lengkap', 'tambahan', '/ sesi', 170000.00, 200000.00, 250000.00, 'Termasuk grooming dasar\nTrimming / bentuk bulu'),
('L003', 'Vitamin / Suplemen', 'tambahan', '/ sekali pemberian', 50000.00, 50000.00, 50000.00, 'Pemberian vitamin / suplemen sesuai kebutuhan hewan'),
('L004', 'Vaksin', 'tambahan', '/ dosis', 260000.00, 260000.00, 260000.00, 'Kucing: Tricat Trio\nAnjing: DHPPi / setara');
