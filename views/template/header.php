<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($pageTitle)) {
    $pageTitle = 'Sistem Penitipan Hewan';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE v4 CSS -->
    <link rel="stylesheet" href="public/dist/css/adminlte.css">

    <!-- Bootstrap Icons (ikon di sidebar/nav) -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">

    <!-- HEADER / NAVBAR -->
    <nav class="app-header navbar navbar-expand bg-body border-bottom shadow-sm">
        <div class="container-fluid">

            <!-- Tombol toggle sidebar -->
            <button class="navbar-toggler" type="button" data-lte-toggle="sidebar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Brand kecil di navbar -->
            <a href="index.php?page=dashboard" class="navbar-brand ms-2">
                <span class="brand-text fw-semibold">Admin Penitipan</span>
            </a>

            <!-- Menu kanan -->
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (!empty($_SESSION['user'])): ?>
                    <li class="nav-item me-3">
                        <span class="nav-link">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= htmlspecialchars($_SESSION['user']['username']) ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="index.php?page=logout" class="nav-link text-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <!-- /HEADER -->

    <!-- SIDEBAR -->
    <?php include __DIR__ . '/sidebar.php'; ?>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="app-main">
        <div class="app-content p-3">
