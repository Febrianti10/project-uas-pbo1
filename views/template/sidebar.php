<?php
// pastikan variabel halaman ada
$currentPage = $page ?? ($activeMenu ?? 'dashboard');
?>

<aside class="app-sidebar modern-sidebar">
  <!-- Brand -->
  <div class="sidebar-brand d-flex align-items-center">
    <div class="brand-logo-circle me-2">
      <!-- kalau punya logo png, pakai img di sini -->
      <img src="img/Logo.png" class="img-fluid" alt="Logo">
      <i class="bi bi-shield-check"></i>
    </div>
    <div class="d-flex flex-column">
      <span class="brand-name">SIP Hewan</span>
      <span class="brand-subtitle">Admin Panel</span>
    </div>
  </div>

  <!-- Wrapper isi sidebar -->
  <div class="sidebar-wrapper d-flex flex-column">

    <nav class="mt-3 flex-grow-1">
      <ul class="nav flex-column modern-menu">

        <!-- DASHBOARD -->
        <li class="nav-item mb-1">
          <a href="index.php?page=dashboard"
            class="nav-link modern-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <span class="modern-icon">
              <i class="bi bi-grid-3x3-gap"></i>
            </span>
            <span>Dashboard</span>
          </a>
        </li>

        <!-- DATA (sub menu kecil, tanpa treeview AdminLTE) -->
        <li class="nav-item mb-1">
          <span class="modern-section-label">Data Master</span>
        </li>

        <li class="nav-item mb-1">
          <a href="index.php?page=hewan"
            class="nav-link modern-link <?= $currentPage === 'hewan' ? 'active' : '' ?>">
            <span class="modern-icon">
              <i class="bi bi-paw"></i>
            </span>
            <span>Data Hewan</span>
          </a>
        </li>

        <li class="nav-item mb-1">
          <a href="index.php?page=pemilik"
            class="nav-link modern-link <?= $currentPage === 'pelanggan' || $currentPage === 'pemilik' ? 'active' : '' ?>">
            <span class="modern-icon">
              <i class="bi bi-person-vcard"></i>
            </span>
            <span>Data Pelanggan</span>
          </a>
        </li>

        <li class="nav-item mb-1">
          <a href="index.php?page=layanan"
            class="nav-link modern-link <?= $currentPage === 'layanan' ? 'active' : '' ?>">
            <span class="modern-icon">
              <i class="bi bi-list-check"></i>
            </span>
            <span>Jenis Layanan</span>
          </a>
        </li>

        <!-- Pemisah -->
        <li class="nav-item my-3">
          <hr class="modern-divider">
        </li>

        <!-- TRANSAKSI -->
        <li class="nav-item mb-1">
          <a href="index.php?page=transaksi"
            class="nav-link modern-link <?= $currentPage === 'transaksi' ? 'active' : '' ?>">
            <span class="modern-icon">
              <i class="bi bi-journal-text"></i>
            </span>
            <span>Transaksi Penitipan</span>
          </a>
        </li>

        <li class="nav-item mt-3">
          <a href="index.php?page=logout" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
        </li>

      </ul>
    </nav>

  </div>
</aside>