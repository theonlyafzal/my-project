<?php
defined('ABSPATH') || exit;

function eproduct_register_settings() {
    $fields = array(
        'eproduct_stripe_publishable',
        'eproduct_stripe_secret',
        'eproduct_paypal_client',
        'eproduct_paypal_secret',
        'eproduct_razorpay_key',
        'eproduct_razorpay_secret',
        'eproduct_fb_pixel',
        'eproduct_company_name',
        'eproduct_contact_email',
    );
    foreach ($fields as $f) {
        register_setting('eproduct_settings', $f);
    }
}

function eproduct_settings_page() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1>eProduct Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('eproduct_settings'); ?>
            <table class="form-table">
                <tr><th>Company Name</th><td><input type="text" name="eproduct_company_name" value="<?php echo esc_attr(get_option('eproduct_company_name')); ?>" class="regular-text"></td></tr>
                <tr><th>Contact Email</th><td><input type="email" name="eproduct_contact_email" value="<?php echo esc_attr(get_option('eproduct_contact_email')); ?>" class="regular-text"></td></tr>
                <tr><th>Stripe Publishable Key</th><td><input type="text" name="eproduct_stripe_publishable" value="<?php echo esc_attr(get_option('eproduct_stripe_publishable')); ?>" class="regular-text"></td></tr>
                <tr><th>Stripe Secret Key</th><td><input type="password" name="eproduct_stripe_secret" value="<?php echo esc_attr(get_option('eproduct_stripe_secret')); ?>" class="regular-text"></td></tr>
                <tr><th>PayPal Client ID</th><td><input type="text" name="eproduct_paypal_client" value="<?php echo esc_attr(get_option('eproduct_paypal_client')); ?>" class="regular-text"></td></tr>
                <tr><th>PayPal Secret</th><td><input type="password" name="eproduct_paypal_secret" value="<?php echo esc_attr(get_option('eproduct_paypal_secret')); ?>" class="regular-text"></td></tr>
                <tr><th>Razorpay Key ID</th><td><input type="text" name="eproduct_razorpay_key" value="<?php echo esc_attr(get_option('eproduct_razorpay_key')); ?>" class="regular-text"></td></tr>
                <tr><th>Razorpay Key Secret</th><td><input type="password" name="eproduct_razorpay_secret" value="<?php echo esc_attr(get_option('eproduct_razorpay_secret')); ?>" class="regular-text"></td></tr>
                <tr><th>Facebook Pixel ID</th><td><input type="text" name="eproduct_fb_pixel" value="<?php echo esc_attr(get_option('eproduct_fb_pixel')); ?>" class="regular-text"></td></tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function eproduct_settings_menu() {
    add_menu_page('eProduct', 'eProduct', 'manage_options', 'eproduct-settings', 'eproduct_settings_page', 'dashicons-cart');
}

add_action('admin_init', 'eproduct_register_settings');
add_action('admin_menu', 'eproduct_settings_menu');
