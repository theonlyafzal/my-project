<?php
defined('ABSPATH') || exit;

/* ── Product filter (unchanged) ── */
function eproduct_ajax_filter() {
    $cat = isset($_POST['cat']) ? sanitize_key($_POST['cat']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $args = array(
        'post_type' => 'eproduct',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        's' => $search,
        'meta_query' => array(
            'relation' => 'OR',
            array('key' => '_eproduct_visibility', 'value' => 'active', 'compare' => '='),
            array('key' => '_eproduct_visibility', 'compare' => 'NOT EXISTS'),
        ),
    );
    if ($cat) {
        $args['tax_query'] = array(array('taxonomy' => 'eproduct_category', 'field' => 'slug', 'terms' => $cat));
    }
    $q = new WP_Query($args);
    if ($q->have_posts()) {
        while ($q->have_posts()) { $q->the_post(); echo eproduct_product_item_html($q->post); }
    } else {
        echo '<p class="ep-empty">No products found.</p>';
    }
    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_eproduct_filter', 'eproduct_ajax_filter');
add_action('wp_ajax_nopriv_eproduct_filter', 'eproduct_ajax_filter');

/* ── Add to cart (AJAX) ── */
function eproduct_ajax_add_to_cart() {
    if (!session_id()) session_start();
    $id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if (!$id || get_post_type($id) !== 'eproduct') {
        wp_send_json_error(array('message' => 'Invalid product.'));
    }
    if (!isset($_SESSION['eproduct_cart'])) $_SESSION['eproduct_cart'] = array();
    if (!in_array($id, $_SESSION['eproduct_cart'])) {
        $_SESSION['eproduct_cart'][] = $id;
    }
    eproduct_send_cart_json();
}
add_action('wp_ajax_eproduct_add_to_cart', 'eproduct_ajax_add_to_cart');
add_action('wp_ajax_nopriv_eproduct_add_to_cart', 'eproduct_ajax_add_to_cart');

/* ── Remove from cart (AJAX) ── */
function eproduct_ajax_remove_from_cart() {
    if (!session_id()) session_start();
    $id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    if (!empty($_SESSION['eproduct_cart'])) {
        $_SESSION['eproduct_cart'] = array_values(array_diff($_SESSION['eproduct_cart'], array($id)));
    }
    eproduct_send_cart_json();
}
add_action('wp_ajax_eproduct_remove_from_cart', 'eproduct_ajax_remove_from_cart');
add_action('wp_ajax_nopriv_eproduct_remove_from_cart', 'eproduct_ajax_remove_from_cart');

/* ── Get cart (AJAX) ── */
function eproduct_ajax_get_cart() {
    if (!session_id()) session_start();
    eproduct_send_cart_json();
}
add_action('wp_ajax_eproduct_get_cart', 'eproduct_ajax_get_cart');
add_action('wp_ajax_nopriv_eproduct_get_cart', 'eproduct_ajax_get_cart');

/* ── Helper: send cart JSON ── */
function eproduct_send_cart_json() {
    $cart = isset($_SESSION['eproduct_cart']) ? array_values(array_unique(array_map('intval', $_SESSION['eproduct_cart']))) : array();
    $items = array();
    $total = 0.0;
    foreach ($cart as $id) {
        $price    = floatval(get_post_meta($id, '_eproduct_price', true));
        $sale     = get_post_meta($id, '_eproduct_sale_price', true);
        $sale     = ($sale !== '' && floatval($sale) > 0) ? floatval($sale) : '';
        $display  = ($sale !== '' && $sale < $price) ? $sale : $price;
        $img      = get_the_post_thumbnail_url($id, 'thumbnail');
        $total   += $display;
        $items[]  = array(
            'id'    => $id,
            'title' => get_the_title($id),
            'price' => $display,
            'img'   => $img ? $img : '',
            'url'   => get_permalink($id),
        );
    }
    wp_send_json_success(array('items' => $items, 'total' => $total, 'count' => count($items)));
}
