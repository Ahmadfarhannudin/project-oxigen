<?php
require_once __DIR__ . '/../config/config.php';
$otp = trim($_POST['otp'] ?? '');
$email = $_SESSION['pending_email'] ?? ($_POST['email'] ?? '');

if (!$email || !$otp) { set_flash('error','OTP & email harus diisi'); header('Location: ../public/auth/verify_otp.php'); exit; }

$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]); $user = $stmt->fetch();
if (!$user) { set_flash('error','User tidak ditemukan'); header('Location: ../public/auth/register.php'); exit; }
$userId = $user['id'];

$stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$userId]); $row = $stmt->fetch();
if (!$row) { set_flash('error','OTP tidak ditemukan'); header('Location: ../public/auth/verify_otp.php'); exit; }

if (hash_equals($row['otp_code'], $otp) && strtotime($row['expired_at']) > time()) {
    $pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = ?")->execute([$userId]);
    $pdo->prepare("DELETE FROM otp_codes WHERE user_id = ?")->execute([$userId]);
    unset($_SESSION['pending_email']);
    set_flash('success','Akun terverifikasi. Silakan login.');
    header('Location: ../public/auth/login.php'); exit;
} else {
    set_flash('error','OTP salah atau kadaluarsa'); header('Location: ../public/auth/verify_otp.php'); exit;
}
