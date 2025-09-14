<?php
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';
require_once 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);

        $this->mail->isSMTP();
        $this->mail->Host       = 'sandbox.smtp.mailtrap.io';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = '3e42ac3156c84b';
        $this->mail->Password   = '21178f4cd1d656';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port       = 2525;

        $this->mail->setFrom('no-reply@example.com', 'HMS Team');
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

            $resetLink = "http://localhost/hms/index.php?account_recovery&email=$to&token=$token"; // Change domain accordingly
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
