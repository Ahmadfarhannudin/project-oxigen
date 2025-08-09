<?php require_once __DIR__ . '/../../config/config.php'; 
$email = $_SESSION['pending_email'] ?? '';
if (!$email) {
    header('Location: register.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Verifikasi OTP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card p-4 text-center">
        <h5>Verifikasi Email</h5>
        <p>Kami telah mengirim kode OTP ke <b><?= htmlspecialchars($email) ?></b></p>
        <form id="otpForm" action="../../process/verify_otp.php" method="post">
          <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
          <div class="mb-3">
            <input name="otp" maxlength="6" class="form-control text-center" placeholder="Masukkan 6 digit OTP" required>
          </div>
          <button class="btn btn-success w-100">Verifikasi</button>
        </form>
        <div class="mt-3 small">
          Belum terima? <a href="register.php">Daftar ulang</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../assets/js/validation.js"></script>
</body>
</html>
