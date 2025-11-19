<?php
if (!isset($activeMenu)) {
    $activeMenu = '';
}
?>
<!-- SIDEBAR -->
<aside class="app-sidebar bg-dark text-white shadow" data-bs-theme="dark">

    <!-- Brand besar di sidebar -->
    <div class="sidebar-brand">
        <a href="index.php?page=dashboard" class="brand-link text-decoration-none">
            <span class="brand-text fw-light">Admin Penitipan</span>
        </a>
    </div>

    <!-- Isi sidebar -->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">

                <li class="nav-item">
                    <a href="index.php?page=dashboard"
                       class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=transaksi"
                       class="nav-link <?= $activeMenu === 'transaksi' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-arrow-left-right"></i>
                        <p>Transaksi Penitipan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=layanan"
                       class="nav-link <?= $activeMenu === 'layanan' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-box"></i>
                        <p>Data Paket / Layanan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=hewan"
                       class="nav-link <?= $activeMenu === 'hewan' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-paw"></i>
                        <p>Data Hewan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="index.php?page=laporan"
                       class="nav-link <?= $activeMenu === 'laporan' ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>Laporan</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
<!-- /SIDEBAR -->
