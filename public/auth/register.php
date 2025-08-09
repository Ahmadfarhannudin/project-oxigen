<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/custom.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card p-4">
        <div class="text-center mb-3">
          <div class="logo">Project Oxigen</div>
          <small class="text-muted">Buat akun baru</small>
        </div>
        <?php if($m=get_flash('error')): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($m) ?></div>
        <?php endif; ?>
        <form id="registerForm" action="../../process/register.php" method="post" novalidate>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama lengkap</label>
            <input name="full_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input name="confirm_password" type="password" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100">Daftar</button>
        </form>
        <div class="form-footer mt-3 text-center">
          Sudah punya akun? <a href="login.php">Login</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../assets/js/validation.js"></script>
</body>
</html>
