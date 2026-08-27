<?php
defined('ABSPATH') || exit;

function eproduct_ajax_filter() {
    $cat = isset($_POST['cat']) ? sanitize_key($_POST['cat']) : '';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $args = array(
        'post_type' => 'eproduct',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        's' => $search,
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
