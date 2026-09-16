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
        'public' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'rewrite' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-download',
        'show_in_rest' => false,
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
        'show_in_rest' => false,
    ));
}

function eproduct_hide_from_frontend($query) {
    if (!is_admin() && $query->is_main_query() && !$query->is_singular()) {
        $types = (array) $query->get('post_type');
        if (in_array('eproduct', $types)) {
            $types = array_diff($types, ['eproduct']);
            $query->set('post_type', $types ? $types : ['post']);
        }
    }
}
add_action('pre_get_posts', 'eproduct_hide_from_frontend');

add_action('init', 'eproduct_register_product_cpt');
add_action('init', 'eproduct_register_category_tax');
