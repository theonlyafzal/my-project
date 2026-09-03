<?php
add_action('init', function() {
    global $wp_post_types;
    if (isset($wp_post_types['eproduct'])) {
        unset($wp_post_types['eproduct']);
    }
    register_post_type('eproduct', array(
        'labels' => array(
            'name' => 'Products',
            'singular_name' => 'Product',
            'add_new' => 'Add Product',
            'add_new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'product'),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-download',
        'show_in_rest' => true,
        'taxonomies' => array('eproduct_category'),
    ));
}, 1);

add_action('init', function() {
    global $wp_filter;
    if (isset($wp_filter['pre_get_posts'])) {
        foreach ($wp_filter['pre_get_posts']->callbacks as $priority => $hooks) {
            foreach ($hooks as $key => $hook) {
                if (is_string($hook['function'][0]) && $hook['function'][0] === 'eproduct_hide_from_queries') {
                    remove_action('pre_get_posts', 'eproduct_hide_from_queries', $priority);
                }
            }
        }
    }
}, 0);
