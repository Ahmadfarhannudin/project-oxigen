<?php
// config/mail.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

// Konfigurasi SMTP: sesuaikan dengan email & password (gunakan App Password untuk Gmail)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USERNAME', 'chisatoowaif@gmail.com'); // ganti
define('MAIL_PASSWORD', 'rxne tklp mjjx xhmc');    // ganti (App Password)
define('MAIL_FROM', 'chisatoowaif@gmail.com');
define('MAIL_FROM_NAME', 'PHP Mailer');

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
        // untuk debugging, bisa menulis ke error_log($mail->ErrorInfo)
        return false;
    }
}
