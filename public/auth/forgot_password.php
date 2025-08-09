<?php
require_once __DIR__ . '/../../config/config.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Lupa Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <?php if ($msg = get_flash('success')): ?>
                <div class="alert alert-success"><?= $msg ?></div>
            <?php elseif ($msg = get_flash('error')): ?>
                <div class="alert alert-danger"><?= $msg ?></div>
            <?php endif; ?>
            <div class="card p-4 shadow">
                <h5 class="mb-3">Lupa Password</h5>
                <form action="../../process/forgot_password.php" method="post">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Kirim Link Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
