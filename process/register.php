<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/mail.php';

$username   = trim($_POST['username'] ?? '');
$fullname   = trim($_POST['full_name'] ?? '');
$email      = trim($_POST['email'] ?? '');
$password   = trim($_POST['password'] ?? '');
$confirmPwd = trim($_POST['confirm_password'] ?? '');

// Validasi input
if (!$username || !$fullname || !$email || !$password || !$confirmPwd) {
    set_flash('error', 'Semua field wajib diisi.');
    header('Location: ../public/auth/register.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Format email tidak valid.');
    header('Location: ../public/auth/register.php');
    exit;
}

if ($password !== $confirmPwd) {
    set_flash('error', 'Password dan konfirmasi password tidak sama.');
    header('Location: ../public/auth/register.php');
    exit;
}

// Cek email atau username sudah ada
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
$stmt->execute([$email, $username]);
if ($stmt->fetch()) {
    set_flash('error', 'Username atau email sudah terdaftar.');
    header('Location: ../public/auth/register.php');
    exit;
}

// Simpan user baru
$hashedPwd = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, password, is_verified) VALUES (?, ?, ?, ?, 0)");
$stmt->execute([$username, $fullname, $email, $hashedPwd]);
$userId = $pdo->lastInsertId();

// Buat OTP
$otp = rand(100000, 999999);
$expiredAt = date('Y-m-d H:i:s', time() + (5 * 60)); // berlaku 5 menit

$stmt = $pdo->prepare("INSERT INTO otp_codes (user_id, otp_code, expired_at) VALUES (?, ?, ?)");
$stmt->execute([$userId, $otp, $expiredAt]);

// Kirim email OTP
$subject = "Kode OTP Verifikasi Akun";
$htmlBody = "<p>Halo <b>$fullname</b>,</p>
<p>Kode OTP kamu adalah: <b>$otp</b></p>
<p>Berlaku 5 menit.</p>";
$plainBody = "Halo $fullname,\n\nKode OTP kamu adalah: $otp\nBerlaku 5 menit.";

if (!sendEmail($email, $subject, $htmlBody, $plainBody)) {
    set_flash('error', 'Gagal mengirim email OTP.');
    header('Location: ../public/auth/register.php');
    exit;
}

// Simpan email ke session untuk proses verifikasi
$_SESSION['pending_email'] = $email;

// Redirect ke halaman verifikasi OTP
header('Location: ../public/auth/verify_otp.php');
exit;
