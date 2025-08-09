<?php
require_once __DIR__ . '/../../config/config.php';

$token = $_GET['token'] ?? '';
if (!$token) {
    die('Token tidak valid.');
}

$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expired_at > NOW() LIMIT 1");
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    die('Token tidak ditemukan atau kadaluarsa.');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Reset Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow p-4">
        <h5 class="mb-3">Reset Password</h5>
        <form action="../../process/reset_password.php" method="post">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
          <div class="mb-3">
            <label>Password baru</label>
            <input name="password" type="password" class="form-control" required minlength="6">
          </div>
          <div class="mb-3">
            <label>Konfirmasi password</label>
            <input name="confirm_password" type="password" class="form-control" required minlength="6">
          </div>
          <button class="btn btn-success w-100">Ganti Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
