<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/custom.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card p-4">
        <h4 class="mb-3">Masuk</h4>
        <?php if($m=get_flash('success')): ?>
          <div class="alert alert-success"><?= htmlspecialchars($m) ?></div>
        <?php endif; ?>
        <?php if($m=get_flash('error')): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($m) ?></div>
        <?php endif; ?>
        <form action="../../process/login.php" method="post">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100">Login</button>
        </form>
        <div class="mt-3 text-center">
          <a href="forgot_password.php">Lupa password?</a> • <a href="register.php">Daftar</a>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
