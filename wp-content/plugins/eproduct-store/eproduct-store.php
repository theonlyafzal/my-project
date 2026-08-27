<?php
defined('ABSPATH') || exit;

define('EPRODUCT_PATH', plugin_dir_path(__FILE__));
define('EPRODUCT_URL', plugin_dir_url(__FILE__));
define('EPRODUCT_VERSION', '1.0.0');

if (file_exists(EPRODUCT_PATH . 'vendor/autoload.php')) {
    require_once EPRODUCT_PATH . 'vendor/autoload.php';
}

register_activation_hook(__FILE__, 'eproduct_activate');
register_deactivation_hook(__FILE__, 'eproduct_deactivate');

function eproduct_activate() {
    eproduct_register_product_cpt();
    eproduct_register_category_tax();
    eproduct_create_orders_table();
    eproduct_create_pages();
    flush_rewrite_rules();
}

function eproduct_deactivate() {
    flush_rewrite_rules();
}

require_once EPRODUCT_PATH . 'includes/cpt-products.php';
require_once EPRODUCT_PATH . 'includes/orders.php';
require_once EPRODUCT_PATH . 'includes/settings.php';
require_once EPRODUCT_PATH . 'includes/privacy.php';
require_once EPRODUCT_PATH . 'includes/templates.php';
require_once EPRODUCT_PATH . 'includes/ajax.php';
