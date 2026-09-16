<?php
require_once('../../../wp-load.php');
header('Content-Type: text/plain');

// Test: simulate what happens when the manage page loads
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Testing product manager page load ===\n";

// Check all required functions exist
$funcs = array(
    'eproduct_get_visibility',
    'eproduct_set_visibility',
    'eproduct_invalidate_cache',
    'eproduct_manage_products_page',
    'eproduct_product_meta_box',
    'eproduct_product_meta_cb',
    'eproduct_product_meta_save',
    'eproduct_sort_link',
);
foreach ($funcs as $f) {
    echo "$f: " . (function_exists($f) ? 'OK' : 'MISSING') . "\n";
}

// Check meta box is registered
echo "\n=== Meta box check ===\n";
global $wp_meta_boxes;
// Force meta box registration
do_action('add_meta_boxes', 'eproduct', null);
do_action('add_meta_boxes_eproduct', null);
if (isset($wp_meta_boxes['eproduct']['normal']['high']['eproduct_meta'])) {
    echo "Meta box 'eproduct_meta' registered: YES\n";
} else {
    echo "Meta box 'eproduct_meta' registered: NO\n";
    echo "Available meta boxes: ";
    if (isset($wp_meta_boxes['eproduct'])) {
        foreach ($wp_meta_boxes['eproduct'] as $context => $priorities) {
            foreach ($priorities as $priority => $boxes) {
                foreach ($boxes as $id => $box) {
                    echo "$id ($context/$priority) ";
                }
            }
        }
    }
    echo "\n";
}

// Check for PHP errors in the page
echo "\n=== Testing page render ===\n";
ob_start();
@include(ABSPATH . 'wp-admin/admin.php');
$output = ob_get_clean();
if (strlen($output) < 100) {
    echo "Page output suspiciously short: " . strlen($output) . " bytes\n";
    echo "Output: " . substr($output, 0, 500) . "\n";
} else {
    echo "Page output: " . strlen($output) . " bytes (looks OK)\n";
}

// Check for fatal errors in product-manager.php specifically
echo "\n=== Syntax check product-manager.php ===\n";
$product_manager_file = dirname(__FILE__) . '/../../../wp-content/plugins/eproduct-store/includes/product-manager.php';
$output = shell_exec("php -l " . escapeshellarg($product_manager_file) . " 2>&1");
echo $output;

echo "\n=== Syntax check cpt-products.php ===\n";
$cpt_file = dirname(__FILE__) . '/../../../wp-content/plugins/eproduct-store/includes/cpt-products.php';
$output = shell_exec("php -l " . escapeshellarg($cpt_file) . " 2>&1");
echo $output;
