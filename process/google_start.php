<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/google_config.php';

$client = getGoogleClient();
$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit;
