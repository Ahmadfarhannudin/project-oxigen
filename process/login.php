<?php
require_once __DIR__ . '/../config/config.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) { 
    set_flash('error', 'Email & password wajib diisi'); 
    header('Location: ../public/auth/login.php'); 
    exit; 
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) { 
    set_flash('error', 'Akun tidak ditemukan'); 
    header('Location: ../public/auth/login.php'); 
    exit; 
}

if (!$user['is_verified']) { 
    set_flash('error', 'Akun belum diverifikasi'); 
    header('Location: ../public/auth/login.php'); 
    exit; 
}

if (!password_verify($password, $user['password'])) { 
    set_flash('error', 'Password salah'); 
    header('Location: ../public/auth/login.php'); 
    exit; 
}

// ✅ Login sukses
$_SESSION['user_id'] = $user['id']; 
$_SESSION['username'] = $user['username'];

set_flash('success', 'Login berhasil! Selamat datang, ' . $user['username']);
header('Location: ../index.php'); 
exit;
