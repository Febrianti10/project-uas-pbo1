        </div> <!-- /.app-content -->
    </main>

    <footer class="app-footer border-top small text-muted py-2 px-3">
        <div class="d-flex justify-content-between">
            <span>&copy; <?= date('Y') ?> Sistem Penitipan Hewan</span>
            <span>UAS PBO</span>
        </div>
    </footer>

</div> <!-- /.app-wrapper -->

<!-- Bootstrap 5 JS (WAJIB untuk modal, dropdown, dll) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<!-- AdminLTE v4 JS -->
<script src="public/dist/js/adminlte.js"></script>

<?php
// kalau controller mau nambah script lagi
if (!empty($extraScripts)) {
    echo $extraScripts;
}
?>

</body>
</html>
