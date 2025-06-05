<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
// include("forgot-password.php");

function sendResetEmail($toEmail, $resetLink) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';         
        $mail->SMTPAuth = true;
        $mail->Username = 'chiemenagodswill97@gmail.com'; 
        $mail->Password = 'fzde posb eira kclg';    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;                   
        $mail->Port = 465;

        //Recipients
        $mail->setFrom('chiemenagodswill97@gmail.com', 'Online Course Platform');
        $mail->addAddress($toEmail);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "
            <p>Hi,</p>
            <p>You requested a password reset. Click the link below to reset your password:</p>
            <p><a href='$resetLink'>$resetLink</a></p>
            <p>If you didn't request this, please ignore this email.</p>
            <p>Thanks,<br>YourAppName Team</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

?>
