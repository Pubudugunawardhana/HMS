<?php
require_once __DIR__ . '/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/phpmailer/src/SMTP.php';
require_once __DIR__ . '/phpmailer/src/Exception.php';
require_once __DIR__ . '/../admin/inc/essentials.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->Host       = getenv('SMTP_HOST') ?: 'localhost';
        $this->mail->SMTPAuth   = (string)getenv('SMTP_USER') !== '';
        $this->mail->Username   = getenv('SMTP_USER') ?: '';
        $this->mail->Password   = getenv('SMTP_PASS') ?: '';
        $this->mail->SMTPSecure = getenv('SMTP_SECURE') ?: 'tls';
        $this->mail->Port       = (int)(getenv('SMTP_PORT') ?: 587);
        $this->mail->Timeout    = 15;

        $this->mail->setFrom(getenv('MAIL_FROM') ?: 'no-reply@example.com', getenv('MAIL_FROM_NAME') ?: 'HMS Team');
        $this->mail->isHTML(true);
    }

    public function sendRegistrationMail($to, $name) {
        try {
            $this->mail->clearAllRecipients(); 
            $this->mail->addAddress($to);
            $this->mail->Subject = 'Registration Successful - HMS';
            $this->mail->Body    = "Hello $name,<br><br>Thank you for registering on our platform. Your account has been created successfully.<br><br>Regards,<br>HMS Team";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }

    public function sendPasswordResetMail($to, $token) {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->addAddress($to);
            $this->mail->Subject = 'Password Reset Request - HMS';

            $resetLink = SITE_URL . "index.php?account_recovery&email=" . urlencode($to) . "&token=" . urlencode($token);
            $body = "
                Hello,<br><br>
                We received a request to reset your password. Please click the link below to reset it:<br>
                <a href='$resetLink'>$resetLink</a><br><br>
                If you didn't request this, you can safely ignore this email.<br><br>
                Regards,<br>HMS Team
            ";

            $this->mail->Body = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }

    public function sendContactMail($to, $name) {
        try {
            $this->mail->clearAllRecipients(); 
            $this->mail->addAddress($to);
            $this->mail->Subject = 'Contact Form Submission - HMS';
            $this->mail->Body    = "Hello $name,<br><br>Thank you for reaching out to us. We have received your message and will get back to you shortly.<br><br>";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }

    public function sendInquiryMail($to, $name, $weddingId) {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->addAddress($to);
            $this->mail->Subject = 'Wedding Inquiry - HMS';
            $this->mail->Body    = "Hello $name,<br><br>Thank you for your inquiry regarding wedding ID: $weddingId. We will get back to you shortly.<br><br>";
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mailer Error: ' . $this->mail->ErrorInfo);
            return false;
        }
    }

}
