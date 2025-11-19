        </div> <!-- /.app-content -->
    </main>

    <footer class="app-footer border-top small text-muted py-2 px-3">
        <div class="d-flex justify-content-between">
            <span>&copy; <?= date('Y') ?> Sistem Penitipan Hewan</span>
            <span>UAS PBO</span>
        </div>
    </footer>

</div> <!-- /.app-wrapper -->

<!-- AdminLTE v4 JS -->
<script src="public/dist/js/adminlte.js"></script>

<?php
if (!empty($extraScripts)) {
    echo $extraScripts;
}
?>

</body>
</html>
