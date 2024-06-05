<?php

function cda_create_success_page() {
    $page = get_page_by_path('certificate-success');
    if (!$page) {
        $page_data = array(
            'post_title'    => 'Certificate Success',
            'post_content'  => '[certificate_success]',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
            'post_name'     => 'certificate-success'
        );

        $page_id = wp_insert_post($page_data);
        if (!is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', 'templates/success-page.php');
        }
    }
}

function cda_create_response_page() {
    $page = get_page_by_path('response-page');
    if (!$page) {
        $page_data = array(
            'post_title'    => 'Response Page',
            'post_content'  => '[response_page]', // Placeholder for shortcode or content
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
            'post_name'     => 'response-page'
        );

        $page_id = wp_insert_post($page_data);
        if (!is_wp_error($page_id)) {
            update_post_meta($page_id, '_wp_page_template', 'templates/response-page.php');
        }
    }
}

function cda_load_custom_template($template) {
    if (is_page('certificate-success')) {
        $custom_template = plugin_dir_path(__FILE__) . '../templates/success-page.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    if (is_page('response-page')) {
        $custom_template = plugin_dir_path(__FILE__) . '../templates/response-page.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'cda_load_custom_template', 99);
?>
