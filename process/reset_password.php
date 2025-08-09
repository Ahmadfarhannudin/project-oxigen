<?php
require_once __DIR__ . '/../config/config.php';

$token    = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';

if (!$token || !$password || !$confirm) {
    die('Data tidak lengkap');
}
if ($password !== $confirm) {
    die('Password tidak cocok');
}

$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expired_at > NOW() LIMIT 1");
$stmt->execute([$token]);
$r = $stmt->fetch();

if (!$r) {
    die('Token tidak valid atau kadaluarsa');
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$pdo->prepare("UPDATE users SET password = ? WHERE id = ?")->execute([$hash, $r['user_id']]);
$pdo->prepare("DELETE FROM password_resets WHERE user_id = ?")->execute([$r['user_id']]);

header('Location: ../public/auth/login.php?reset=1');
exit;
