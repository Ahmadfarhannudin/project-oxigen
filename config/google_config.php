<?php
// config/google_config.php
require_once __DIR__ . '/../vendor/autoload.php';

function getGoogleClient() {
    $client = new Google_Client();
    // Ganti dengan credentials mu:
    $client->setClientId('442536324380-6abj66f2o19kbgmfg5l2a7fmdvheg5nj.apps.googleusercontent.com');
    $client->setClientSecret('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T');
    $client->setRedirectUri('http://localhost/peoject-oxigen/public/auth/google_callback.php');
    $client->addScope('email');
    $client->addScope('profile');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    return $client;
}
