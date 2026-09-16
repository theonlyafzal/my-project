<?php
require_once('../../../wp-load.php');
header('Content-Type: text/plain');

// Test 1: Check product-manager.php loads without errors
echo "=== Test 1: Plugin loaded ===\n";
if (function_exists('eproduct_manage_products_page')) {
    echo "OK: eproduct_manage_products_page exists\n";
} else {
    echo "FAIL: eproduct_manage_products_page NOT found\n";
}

if (function_exists('eproduct_get_visibility')) {
    echo "OK: eproduct_get_visibility exists\n";
} else {
    echo "FAIL: eproduct_get_visibility NOT found\n";
}

if (function_exists('eproduct_enhanced_meta_cb')) {
    echo "OK: eproduct_enhanced_meta_cb exists\n";
} else {
    echo "FAIL: eproduct_enhanced_meta_cb NOT found\n";
}

// Test 2: Check meta box hooks
echo "\n=== Test 2: Meta box hooks ===\n";
global $wp_filter;
if (isset($wp_filter['add_meta_boxes'])) {
    foreach ($wp_filter['add_meta_boxes']->callbacks as $p => $hooks) {
        foreach ($hooks as $k => $h) {
            if (is_array($h['function'])) {
                echo "Priority $p: " . (is_string($h['function'][0]) ? $h['function'][0] . '::' . $h['function'][1] : 'closure') . "\n";
            } elseif (is_string($h['function'])) {
                echo "Priority $p: " . $h['function'] . "\n";
            }
        }
    }
}

// Test 3: Check save_post hooks
echo "\n=== Test 3: save_post_eproduct hooks ===\n";
if (isset($wp_filter['save_post_eproduct'])) {
    foreach ($wp_filter['save_post_eproduct']->callbacks as $p => $hooks) {
        foreach ($hooks as $k => $h) {
            if (is_array($h['function'])) {
                echo "Priority $p: " . (is_string($h['function'][0]) ? $h['function'][0] . '::' . $h['function'][1] : 'closure') . "\n";
            } elseif (is_string($h['function'])) {
                echo "Priority $p: " . $h['function'] . "\n";
            }
        }
    }
}

// Test 4: Check products have visibility meta
echo "\n=== Test 4: Product visibility meta ===\n";
$products = get_posts(array('post_type' => 'eproduct', 'post_status' => 'publish', 'posts_per_page' => -1));
foreach ($products as $p) {
    $vis = get_post_meta($p->ID, '_eproduct_visibility', true);
    echo "ID {$p->ID}: '{$p->post_title}' => visibility='" . ($vis ?: '(empty)') . "'\n";
}

// Test 5: Test nonce generation
echo "\n=== Test 5: Nonce test ===\n";
$test_url = wp_nonce_url(admin_url('admin.php?page=eproduct-manage&ep_toggle=100'), 'ep_toggle_100');
echo "Generated URL: $test_url\n";
$nonce = wp_create_nonce('ep_toggle_100');
echo "Generated nonce: $nonce\n";
echo "Verify nonce: " . (wp_verify_nonce($nonce, 'ep_toggle_100') ? 'VALID' : 'INVALID') . "\n";
