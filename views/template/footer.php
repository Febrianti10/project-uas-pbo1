        </div> <!-- /.app-content -->
    </main>

    <!-- FOOTER -->
    <footer class="app-footer border-top small text-muted py-2 px-3">
        <div class="d-flex justify-content-between">
            <span>&copy; <?= date('Y') ?> Sistem Penitipan Hewan</span>
            <span>UAS PBO</span>
        </div>
    </footer>

</div> <!-- /.app-wrapper -->

<!-- AdminLTE v4 JS -->
<script src="public/dist/js/adminlte.js"></script>

<!-- Sidebar dropdown "Data" (Hewan / Pelanggan / Layanan) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // semua link yang punya submenu
    document.querySelectorAll('.has-dropdown').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const targetSelector = link.getAttribute('data-target');
            const menu = document.querySelector(targetSelector);
            if (!menu) return;

            // tutup submenu lain
            document.querySelectorAll('.submenu.show').forEach(function (sm) {
                if (sm !== menu) sm.classList.remove('show');
            });
            document.querySelectorAll('.has-dropdown.active-dropdown').forEach(function (lnk) {
                if (lnk !== link) lnk.classList.remove('active-dropdown');
            });

            // toggle submenu yang sedang diklik
            menu.classList.toggle('show');
            link.classList.toggle('active-dropdown');
        });
    });
});
</script>

<?php
// jika ada script tambahan dari halaman tertentu, tetap bisa disisipkan
if (!empty($extraScripts)) {
    echo $extraScripts;
}
?>

</body>
</html>
