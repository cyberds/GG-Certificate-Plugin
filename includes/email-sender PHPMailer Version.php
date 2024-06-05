<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once plugin_dir_path(__FILE__) . '../vendor/autoload.php';

function cda_send_certificate_email($to, $pdf_path) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'dfadagrafix@gmail.com'; // SMTP username
        $mail->Password = 'vcfcsreyglaojrej'; // SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('noreply@greengrowthafrica.com', 'Green Growth Africa');
        $mail->addAddress($to);

        // Attachments
        $pdf_server_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $pdf_path);
        $mail->addAttachment($pdf_server_path);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Certificate';
        $mail->Body = 'Congratulations! Attached is your certificate.';

        $mail->send();
        return 'Email sent successfully.';
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
