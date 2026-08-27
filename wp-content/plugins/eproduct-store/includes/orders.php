<?php
defined('ABSPATH') || exit;

function eproduct_create_orders_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'eproduct_orders';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
        product_id BIGINT(20) UNSIGNED NOT NULL,
        gateway VARCHAR(32) NOT NULL,
        amount DECIMAL(10,2) NOT NULL DEFAULT 0,
        currency VARCHAR(8) NOT NULL DEFAULT 'USD',
        status VARCHAR(32) NOT NULL DEFAULT 'pending',
        transaction_id VARCHAR(255) NOT NULL DEFAULT '',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY user_id (user_id),
        KEY status (status)
    ) $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

function eproduct_create_order($data) {
    global $wpdb;
    $wpdb->insert($wpdb->prefix . 'eproduct_orders', array(
        'user_id' => get_current_user_id(),
        'product_id' => intval($data['product_id']),
        'gateway' => sanitize_key($data['gateway']),
        'amount' => floatval($data['amount']),
        'currency' => sanitize_text_field($data['currency']),
        'status' => 'pending',
    ));
    return $wpdb->insert_id;
}

function eproduct_update_order($order_id, $status, $transaction_id = '') {
    global $wpdb;
    $wpdb->update($wpdb->prefix . 'eproduct_orders',
        array('status' => $status, 'transaction_id' => $transaction_id),
        array('id' => intval($order_id))
    );
}

function eproduct_get_order($order_id) {
    global $wpdb;
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eproduct_orders WHERE id = %d", intval($order_id)));
}
