<?php
// config/auth.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/auth/login.php");
    exit;
}
