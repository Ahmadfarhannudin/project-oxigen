<?php
require_once __DIR__ . '/../config/config.php';
if (!isset($_SESSION['user_id'])) { header('Location: auth/login.php'); exit; }
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<div class="container py-5">
  <div class="card p-4">
    <h4>Dashboard</h4>
    <p>Halo, <?=htmlspecialchars($_SESSION['username'])?></p>
    <a href="../process/logout.php" class="btn btn-outline-secondary">Logout</a>
  </div>
</div>
</body>
</html>
