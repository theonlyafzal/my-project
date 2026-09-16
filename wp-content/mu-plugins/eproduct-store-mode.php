<?php
/*
 * Store-mode mu-plugin for store.eproduct.org
 * Makes the eproduct CPT public and front-end visible.
 */

// 1. Fix CPT after it's registered
add_action('registered_post_type', function($post_type, $args) {
    if ($post_type !== 'eproduct') return;
    global $wp_post_types;
    $wp_post_types['eproduct']->public = true;
    $wp_post_types['eproduct']->publicly_queryable = true;
    $wp_post_types['eproduct']->has_archive = true;
    $wp_post_types['eproduct']->show_in_rest = true;
    $wp_post_types['eproduct']->rewrite = array('slug' => 'product', 'with_front' => false);
    if (!isset($wp_post_types['eproduct']->taxonomies) || !in_array('eproduct_category', $wp_post_types['eproduct']->taxonomies)) {
        $wp_post_types['eproduct']->taxonomies[] = 'eproduct_category';
    }
}, 99, 2);

// 2. Force correct permalink for eproduct posts
add_filter('post_type_link', function($post_link, $post) {
    if ($post->post_type === 'eproduct') {
        return home_url('/product/' . $post->post_name . '/');
    }
    return $post_link;
}, 10, 2);

// 3. Add rewrite rules for product CPT
add_action('init', function() {
    add_rewrite_rule('product/([^/]+)/?$', 'index.php?post_type=eproduct&name=$matches[1]', 'top');
    add_rewrite_rule('product-category/([^/]+)/?$', 'index.php?eproduct_category=$matches[1]', 'top');
    add_rewrite_rule('product-category/([^/]+)/page/([0-9]+)/?$', 'index.php?eproduct_category=$matches[1]&paged=$matches[2]', 'top');
}, 1);

// 4. Force single-eproduct.php template
add_filter('template_include', function($template) {
    if (is_singular('eproduct')) {
        $t = locate_template('single-eproduct.php');
        if ($t) return $t;
    }
    if (is_post_type_archive('eproduct')) {
        $t = locate_template('archive-eproduct.php');
        if ($t) return $t;
    }
    return $template;
}, 99);

// 5. Remove the plugin's hide-from-frontend filter
add_action('init', function() {
    global $wp_filter;
    if (isset($wp_filter['pre_get_posts'])) {
        foreach ($wp_filter['pre_get_posts']->callbacks as $priority => $hooks) {
            foreach ($hooks as $key => $hook) {
                if (is_array($hook['function']) && is_string($hook['function'][0]) && $hook['function'][0] === 'eproduct_hide_from_frontend') {
                    remove_action('pre_get_posts', 'eproduct_hide_from_frontend', $priority);
                }
            }
        }
    }
}, 1);

// 6. Filter hidden products from frontend (meta-based visibility)
add_action('pre_get_posts', function($query) {
    if (is_admin()) return;
    if (!$query->is_main_query()) return;

    $post_type = $query->get('post_type');
    $is_eproduct = false;
    if (is_string($post_type) && $post_type === 'eproduct') $is_eproduct = true;
    if (is_array($post_type) && in_array('eproduct', $post_type)) $is_eproduct = true;
    if (!$is_eproduct) return;

    // Don't override if meta_query already set by product-manager
    $existing = $query->get('meta_query');
    if (is_array($existing) && !empty($existing)) return;

    $query->set('meta_query', array(
        'relation' => 'OR',
        array(
            'key'     => '_eproduct_visibility',
            'value'   => 'active',
            'compare' => '=',
        ),
        array(
            'key'     => '_eproduct_visibility',
            'compare' => 'NOT EXISTS',
        ),
    ));
}, 3);
