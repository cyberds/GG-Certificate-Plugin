<?php
/**
 * Template Name: Certificate Success Page
 */


get_header();

$certificate_number = isset($_GET['certificate_number']) ? sanitize_text_field(urldecode($_GET['certificate_number'])) : null;

if ($certificate_number) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'certificate_registry';
    $certificate = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE certificate_number = %s", $certificate_number));

    if ($certificate) {
        $upload_dir = wp_upload_dir();
        $pdf_url = $upload_dir['baseurl'] . '/certificate-' . $certificate->certificate_number . '.pdf';
        ?>

        <div class="certificate-success">
            <h2>Success!</h2>
            <p>A copy of the certificate has been sent to <?php echo esc_html($certificate->email); ?>.</p>
            <p>You can also download your certificate by clicking the button below.</p>
            <embed src="<?php echo esc_url($pdf_url); ?>" width="600" height="800" type="application/pdf">
            <br>
            <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
            <a href="<?php echo esc_url($pdf_url); ?>" download="certificate.pdf" class="button">Download Certificate</a> <a class="button" style="background-color: orange;" href="https://thegreengrowth.com/"> Flaunt on DigiHub</a>
            </div>
        </div>

        <?php
    } else {
        echo '<p>Invalid certificate number.</p>';
    }
} else {
    echo '<p>Invalid request.</p>';
}

get_footer();


?>