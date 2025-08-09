<?php
session_start();

// Set timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Koneksi ke database
$host = 'localhost';
$db   = 'gym';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Pastikan MySQL timezone sama
$pdo->exec("SET time_zone = '+07:00'");

// Flash message helper
function set_flash($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function get_flash($type) {
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}
