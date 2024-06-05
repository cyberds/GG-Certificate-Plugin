<?php
/*
Plugin Name: GGA Certificate Plugin
Description: A plugin to generate and email certificates for Green Growth Africa programmes and workshop. To use, simply add '[certificate_form]' shortcode on any page you want the form to be. You can view all created certificates on your dashboard.
Version: 1.0
Author: <a href="https://cyberds.pythonanywhere.com" style="cursor: pointer;">Doosu Bere</a>
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/form-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/certificate-generator.php';
require_once plugin_dir_path(__FILE__) . 'includes/email-sender.php';
require_once plugin_dir_path(__FILE__) . 'includes/registry.php';
require_once plugin_dir_path(__FILE__) . 'includes/page-creator.php';

function cda_activate_plugin() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'certificate_registry';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        certificate_number varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Create the pages
    cda_create_success_page();
    cda_create_response_page();
}
register_activation_hook(__FILE__, 'cda_activate_plugin');
?>
