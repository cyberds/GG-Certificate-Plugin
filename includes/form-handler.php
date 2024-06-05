<?php

function cda_display_form() {
    ob_start();
    ?>
    <form id="certificate-form" method="post" action="">
        <?php wp_nonce_field('cda_certificate_form', 'cda_certificate_nonce'); ?>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="username">DigiHub Username:</label>
        <input type="text" id="username" name="username" required>
        <input type="submit" value="Generate Certificate">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('certificate_form', 'cda_display_form');

function cda_handle_form_submission() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cda_certificate_nonce']) && wp_verify_nonce($_POST['cda_certificate_nonce'], 'cda_certificate_form')) {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $username = sanitize_text_field($_POST['username']);

        // Check if the DigiHub username exists
        $response = cda_check_username($username);

        if ($response && isset($response->api_status) && $response->api_status === "400" && isset($response->errors)) {
            $error_id = isset($response->errors->error_id) ? $response->errors->error_id : null;

            if ($error_id === 5) {
                // Username exists but password is incorrect
                // Proceed to generate the certificate
                $certificate_number = cda_generate_certificate_number();
                $pdf_path = cda_generate_certificate($name, $certificate_number);

                // Insert the new certificate into the database
                global $wpdb;
                $table_name = $wpdb->prefix . 'certificate_registry';
                $wpdb->insert($table_name, [
                    'name' => $name,
                    'email' => $email,
                    'certificate_number' => $certificate_number
                ]);

                // Redirect to the certificate success page
                $success_page = get_page_by_path('certificate-success');
                if ($success_page) {
                    $success_page_url = get_permalink($success_page);
                    $redirect_url = add_query_arg([
                        'certificate_number' => urlencode($certificate_number)
                    ], $success_page_url);
                    wp_redirect($redirect_url);
                    exit;
                } else {
                    echo '<p>Success page not found.</p>';
                }
            } elseif ($error_id === 4) {
                // Username not found
                // Redirect to response page
                $response_page = get_page_by_path('response-page');
                if ($response_page) {
                    $response_page_url = get_permalink($response_page);
                    wp_redirect($response_page_url);
                    exit;
                } else {
                    echo '<p>Response page not found.</p>';
                }
            }
        } else {
            // Error occurred or invalid response received
            echo '<p>Error occurred while checking username.</p>';
        }
    }
}
add_action('template_redirect', 'cda_handle_form_submission');

function cda_check_username($username) {
    $data = [
        'server_key' => '3b28702903f325a2680b08327783bae8',
        'username' => $username,
        'password' => 'GreenGrowth' // Default password as per the provided workaround
    ];

    $response = wp_remote_post('https://app.thegreengrowth.com/api/auth', [
        'body' => $data
    ]);

    if (!is_wp_error($response) && isset($response['body'])) {
        return json_decode($response['body']);
    }

    return false;
}

function cda_enqueue_scripts() {
    wp_enqueue_style('cda-style', plugin_dir_url(__FILE__) . '../css/certificate-form.css');
}
add_action('wp_enqueue_scripts', 'cda_enqueue_scripts');
?>
