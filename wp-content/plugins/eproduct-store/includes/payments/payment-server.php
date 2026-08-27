<?php
define('WP_USE_THEMES', false);
require_once '../../../../../wp-load.php';
require_once __DIR__ . '/../orders.php';
require_once __DIR__ . '/PaymentGateway.php';
require_once __DIR__ . '/StripeGateway.php';
require_once __DIR__ . '/PayPalGateway.php';
require_once __DIR__ . '/RazorpayGateway.php';

define('EPRODUCT_URL', plugin_dir_url(__FILE__));

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

if ($action === 'create') {
    if (!is_user_logged_in()) { auth_redirect(); exit; }
    $products = array_filter(array_map('intval', explode(',', $_POST['products'])));
    $amount = floatval($_POST['amount']);
    $gateway = sanitize_key($_POST['gateway']);
    $order_id = eproduct_create_order(array(
        'product_id' => intval($products[0]),
        'gateway' => $gateway,
        'amount' => $amount,
        'currency' => 'USD',
    ));
    update_post_meta($order_id, '_eproduct_items', $products);
    $order = eproduct_get_order($order_id);
    $gw = eproduct_gateway_instance($gateway);
    $res = $gw->create_payment($order);
    if (!empty($res['error'])) { wp_redirect(home_url('/checkout/?notice=' . urlencode($res['error']))); exit; }
    wp_redirect($res['url']); exit;
}

if ($action === 'return') {
    $order_id = intval($_GET['order_id']);
    $order = eproduct_get_order($order_id);
    if ($order) {
        $gw = eproduct_gateway_instance($order->gateway);
        if ($gw && $gw->verify_return($order, $_GET)) {
            eproduct_update_order($order_id, 'completed', isset($_GET['payment_id']) ? $_GET['payment_id'] : '');
        }
    }
    wp_redirect(home_url('/account/?status=' . ($order ? $order->status : 'unknown'))); exit;
}

if ($action === 'webhook') {
    $order_id = intval($_POST['order_id']);
    eproduct_update_order($order_id, 'completed', isset($_POST['payment_id']) ? $_POST['payment_id'] : '');
    http_response_code(200);
    exit;
}

wp_redirect(home_url('/'));
exit;
