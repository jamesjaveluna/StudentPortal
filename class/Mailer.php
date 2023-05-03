<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Load configuration settings
require_once 'config/config.php';

/**
 * Sends an email using PHPMailer
 *
 * @param string $to The recipient's email address
 * @param string $subject The subject of the email
 * @param string $message The message body of the email
 * @return bool Whether the email was sent successfully or not
 */
function send_email($to, $subject, $message) {
    // Create a new instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = MAILER_DEBUG;
        $mail->isSMTP();
        $mail->Host       = MAILER_HOST;
        $mail->SMTPAuth   = MAILER_AUTH;
        $mail->Username   = MAILER_USERNAME;
        $mail->Password   = MAILER_PASSWORD;
        $mail->SMTPSecure = MAILER_SECURE;
        $mail->Port       = MAILER_PORT;

        // Recipients
        $mail->setFrom(MAILER_FROM_EMAIL, MAILER_FROM_NAME);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        // Send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error sending email: " . $mail->ErrorInfo);
        return false;
    }
}
