<?php
function cda_send_certificate_email($to, $pdf_path) {
    $subject = 'Your Certificate';
    $message = 'Congratulations! Attached is your certificate.';
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    $attachments = [$pdf_path];

    wp_mail($to, $subject, $message, $headers, $attachments);
}