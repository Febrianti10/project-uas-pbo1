<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Login - Sistem Penitipan Hewan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="/penitipan-hewan/assets/css/adminlte.css" />

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
</head>
<body class="login-page bg-body-tertiary">

  <div class="login-box">
    <!-- Logo -->
    <div class="login-logo mb-3">
      <a href="#" class="text-decoration-none">
        <i class="bi bi-shield-check me-1"></i>
        <b>Penitipan</b> Hewan
      </a>
    </div>

    <!-- Card -->
    <div class="card shadow-sm border-0">
      <div class="card-body login-card-body">
        <p class="login-box-msg mb-4">
          Silakan login sebagai admin untuk mengelola penitipan hewan.
        </p>

        <!-- TAMPILKAN ERROR (opsional, nanti dari controller) -->
        <?php if (!empty($errorMessage ?? '')): ?>
          <div class="alert alert-danger py-2">
            <?= htmlspecialchars($errorMessage); ?>
          </div>
        <?php endif; ?>

        <form action="?page=auth&action=login" method="post" novalidate>
          <!-- Username / Email -->
          <div class="mb-3">
            <label for="username" class="form-label">Username atau Email</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-person"></i>
              </span>
              <input
                type="text"
                class="form-control"
                id="username"
                name="username"
                placeholder="Masukkan username atau email"
                required
              />
            </div>
          </div>

          <!-- Password -->
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bi bi-lock"></i>
              </span>
              <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                placeholder="Masukkan password"
                required
              />
            </div>
          </div>

          <!-- Remember + Tombol -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember" />
              <label class="form-check-label" for="remember">
                Ingat saya
              </label>
            </div>
            <a href="#" class="small text-decoration-none">Lupa password?</a>
          </div>

          <div class="d-grid mb-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </button>
          </div>
        </form>

        <p class="mb-0 mt-3 text-center text-muted small">
          &copy; <?= date('Y'); ?> Sistem Penitipan Hewan
        </p>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
  <script src="/penitipan-hewan/assets/js/adminlte.js"></script>
</body>
</html>
