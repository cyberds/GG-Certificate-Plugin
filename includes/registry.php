<?php
function cda_certificate_registry_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'certificate_registry';
    
    // Check if delete request is submitted
    if (isset($_GET['delete_certificate'])) {
        $certificate_id = intval($_GET['delete_certificate']);
        $wpdb->delete($table_name, ['id' => $certificate_id]);
    }
    
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    ?>
    <div class="wrap">
        <h1>Certificate Registry</h1>
        <p>To use, simply add '[certificate_form]' shortcode on any page you want the form to be. You can view all created certificates on your dashboard.</p>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Certificate Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row) : ?>
                    <tr>
                        <td><?php echo esc_html($row->name); ?></td>
                        <td><?php echo esc_html($row->email); ?></td>
                        <td><?php echo esc_html($row->certificate_number); ?></td>
                        <td>
                            <a href="?page=certificate-registry&delete_certificate=<?php echo esc_attr($row->id); ?>" class="button" onclick="return confirm('Are you sure you want to delete this certificate? This action cannot be undone.');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function cda_register_admin_menu() {
    add_menu_page('Certificate Registry', 'Certificate Registry', 'manage_options', 'certificate-registry', 'cda_certificate_registry_page');
}
add_action('admin_menu', 'cda_register_admin_menu');
