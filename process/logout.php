<?php
require_once __DIR__ . '/../config/config.php';
session_unset(); session_destroy();
header('Location: ../public/auth/login.php'); exit;
