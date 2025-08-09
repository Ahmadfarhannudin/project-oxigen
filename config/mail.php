<?php
// config/mail.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

// Konfigurasi SMTP
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USERNAME', 'chisatoowaif@gmail.com'); // ganti dengan email kamu
define('MAIL_PASSWORD', 'ydfv eeqq sofu fycq');    // ganti dengan app password Gmail kamu
define('MAIL_FROM', 'chisatoowaif@gmail.com');
define('MAIL_FROM_NAME', 'B+C Mailer');

function sendEmail($to, $subject, $htmlBody, $plainBody = '') {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $plainBody ?: strip_tags($htmlBody);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mail error: " . $mail->ErrorInfo);
        return false;
    }
}

function sendOtpRegister($to, $namaUser, $kodeOtp) {
    $subject = "✨ Aktivasi Akun Kamu di Gym ZULLxZALL — Kode OTP Kamu";

    $htmlBody = "
    <div style='font-family: Arial, sans-serif; color: #333;'>
        <h2 style='color: #6a1b9a;'>Halo, {$namaUser}!</h2>
        <p>Selamat datang di <strong>Gym ZULLxZALL</strong> ✨</p>
        <p>Untuk menyelesaikan proses pendaftaran, silakan masukkan kode OTP berikut:</p>
        <h1 style='color: #d81b60;'>{$kodeOtp}</h1>
        <p style='font-size: 0.9em; color: #666;'>Kode ini berlaku selama 10 menit. Jangan bagikan kode ini kepada siapapun ya!</p>
        <p>Kalau kamu tidak melakukan pendaftaran ini, abaikan email ini.</p>
        <br>
        <p>Terima kasih telah bergabung dan selamat menikmati layanan kami!<br>
        <strong>Tim B+C Mailer</strong></p>
    </div>
    ";

    $plainBody = "Halo, {$namaUser}!\n\n"
        . "Selamat datang di Gym ZULLxZALL ✨\n"
        . "Kode OTP kamu: {$kodeOtp}\n"
        . "Kode ini berlaku selama 10 menit. Jangan bagikan kode ini kepada siapapun.\n\n"
        . "Kalau kamu tidak melakukan pendaftaran ini, abaikan email ini.\n\n"
        . "Terima kasih,\nTim B+C Mailer";

    return sendEmail($to, $subject, $htmlBody, $plainBody);
}

function sendOtpResetPassword($to, $namaUser, $kodeOtp) {
    $subject = "🔐 Reset Password di Gym ZULLxZALL — Kode OTP Verifikasi";

    $htmlBody = "
    <div style='font-family: Arial, sans-serif; color: #333;'>
        <h2 style='color: #283593;'>Hai, {$namaUser}!</h2>
        <p>Kami menerima permintaan untuk mereset password akunmu di <strong>B+C Mailer</strong>.</p>
        <p>Gunakan kode OTP berikut untuk mengonfirmasi perubahan:</p>
        <h1 style='color: #1e88e5;'>{$kodeOtp}</h1>
        <p style='font-size: 0.9em; color: #666;'>Kode ini hanya berlaku selama 10 menit dan hanya boleh digunakan oleh kamu.</p>
        <p>Jika kamu tidak mengajukan permintaan ini, segera abaikan email ini atau hubungi kami.</p>
        <br>
        <p>Tetap aman dan semoga hari kamu menyenangkan!<br>
        <strong>Tim Keamanan B+C Mailer</strong></p>
    </div>
    ";

    $plainBody = "Hai, {$namaUser}!\n\n"
        . "Kami menerima permintaan untuk mereset password akunmu di B+C Mailer.\n"
        . "Kode OTP kamu: {$kodeOtp}\n"
        . "Kode ini berlaku selama 10 menit dan hanya boleh digunakan oleh kamu.\n\n"
        . "Jika kamu tidak mengajukan permintaan ini, abaikan email ini.\n\n"
        . "Tetap aman,\nTim Keamanan B+C Mailer";

    return sendEmail($to, $subject, $htmlBody, $plainBody);
}
