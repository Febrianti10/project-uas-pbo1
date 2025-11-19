<?php
$pageTitle  = 'Dashboard';
$activeMenu = 'dashboard';
include __DIR__ . '/template/header.php';
?>

<h2 class="mb-3">Penitipan Hewan</h2>

<div class="row g-3">
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-2">Total Hewan Dititipkan</h5>
                <p class="display-6 mb-0"><?= $totalHewan ?? 0 ?></p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-2">Total Hewan Dititipkan</h5>
                <p class="display-6 mb-0"><?= $totalHewan ?? 0 ?></p>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-2">Total Hewan Dititipkan</h5>
                <p class="display-6 mb-0"><?= $totalHewan ?? 0 ?></p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/template/footer.php'; ?>
