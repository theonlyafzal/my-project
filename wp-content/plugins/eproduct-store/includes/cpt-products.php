<?php
defined('ABSPATH') || exit;

function eproduct_register_product_cpt() {
    register_post_type('eproduct', array(
        'labels' => array(
            'name' => 'Products',
            'singular_name' => 'Product',
            'add_new' => 'Add Product',
            'add_new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
        ),
        'public' => true,
        'has_archive' => 'products',
        'rewrite' => array('slug' => 'product'),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-download',
        'show_in_rest' => true,
    ));
}

function eproduct_register_category_tax() {
    register_taxonomy('eproduct_category', 'eproduct', array(
        'labels' => array(
            'name' => 'Product Categories',
            'singular_name' => 'Category',
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'product-category'),
        'show_in_rest' => true,
    ));
}

function eproduct_product_meta_box() {
    add_meta_box('eproduct_meta', 'Product Details', 'eproduct_product_meta_cb', 'eproduct', 'normal', 'high');
}

function eproduct_product_meta_cb($post) {
    wp_nonce_field('eproduct_meta_save', 'eproduct_meta_nonce');
    $price = get_post_meta($post->ID, '_eproduct_price', true);
    $file = get_post_meta($post->ID, '_eproduct_file', true);
    echo '<p><label>Price (USD)</label><br>';
    echo '<input type="number" step="0.01" name="eproduct_price" value="' . esc_attr($price) . '" style="width:200px"></p>';
    echo '<p><label>Download File URL</label><br>';
    echo '<input type="url" name="eproduct_file" value="' . esc_url($file) . '" style="width:100%"></p>';
}

function eproduct_product_meta_save($post_id) {
    if (!isset($_POST['eproduct_meta_nonce']) || !wp_verify_nonce($_POST['eproduct_meta_nonce'], 'eproduct_meta_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (isset($_POST['eproduct_price'])) {
        update_post_meta($post_id, '_eproduct_price', floatval($_POST['eproduct_price']));
    }
    if (isset($_POST['eproduct_file'])) {
        update_post_meta($post_id, '_eproduct_file', esc_url_raw($_POST['eproduct_file']));
    }
}

add_action('init', 'eproduct_register_product_cpt');
add_action('init', 'eproduct_register_category_tax');
add_action('add_meta_boxes', 'eproduct_product_meta_box');
add_action('save_post_eproduct', 'eproduct_product_meta_save');
