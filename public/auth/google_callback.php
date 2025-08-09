<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/google_config.php';
$client = getGoogleClient();

if (!isset($_GET['code'])) {
    set_flash('error','Google login gagal.');
    header('Location: login.php'); exit;
}
$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) {
    set_flash('error','Google token error.');
    header('Location: login.php'); exit;
}
$client->setAccessToken($token['access_token']);
$oauth = new Google_Service_Oauth2($client);
$g = $oauth->userinfo->get();
$email = $g->email; $name = $g->name ?? $g->email;

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch();
if (!$user) {
    $username = explode('@',$email)[0];
    $pw = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, password, is_verified) VALUES (?, ?, ?, ?, 1)");
    $stmt->execute([$username, $name, $email, $pw]);
    $userId = $pdo->lastInsertId();
    $_SESSION['user_id'] = $userId; $_SESSION['username'] = $username;
} else {
    $_SESSION['user_id'] = $user['id']; $_SESSION['username'] = $user['username'];
}
header('Location: ../dashboard.php'); exit;
