<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/mail.php';

$email = trim($_POST['email'] ?? '');
if (!$email) {
    set_flash('error', 'Email wajib diisi');
    header('Location: ../public/auth/forgot_password.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    // Biar keamanan, tetap kasih respon sukses
    set_flash('success', 'Jika email terdaftar, link reset dikirim');
    header('Location: ../public/auth/forgot_password.php');
    exit;
}

// Hapus token lama untuk user ini
$pdo->prepare("DELETE FROM password_resets WHERE user_id = ?")->execute([$user['id']]);

// Buat token baru
$token = bin2hex(random_bytes(32));
$expired = date('Y-m-d H:i:s', time() + 3600); // 1 jam dari sekarang

$stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expired_at) VALUES (?, ?, ?)");
$stmt->execute([$user['id'], $token, $expired]);

// Buat link reset
$link = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/../public/auth/reset_password.php?token=' . urlencode($token);

// Kirim email
$subject = "Reset Password";
$html = "<p>Halo {$user['full_name']},</p>
<p>Klik link berikut untuk reset password Anda (berlaku 1 jam):</p>
<p><a href=\"$link\">$link</a></p>";

sendEmail($email, $subject, $html);

set_flash('success', 'Jika email terdaftar, link reset dikirim');
header('Location: ../public/auth/forgot_password.php');
exit;
