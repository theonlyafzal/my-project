<?php
require_once('../../../wp-load.php');
header('Content-Type: text/plain');

// Find the existing product
$existing = get_posts(array(
    'post_type' => 'eproduct',
    'post_status' => 'publish',
    's' => 'Ultimate E-Commerce Seller Toolkit',
    'posts_per_page' => 1
));

if (empty($existing)) {
    // Try by ID 237
    $pid = 237;
    if (get_post_type($pid) !== 'eproduct') {
        echo "Product not found. Creating new...\n";
        $pid = wp_insert_post(array(
            'post_title'   => '100+ Million Digital Resources',
            'post_content' => 'An all-in-one premium digital collection containing a massive library of digital resources for creators, designers, marketers, entrepreneurs, businesses, students, and professionals.',
            'post_excerpt' => '100+ million digital resources including design templates, AI prompts, graphics, videos, eBooks, business resources, and creative assets. One powerful collection.',
            'post_type'    => 'eproduct',
            'post_status'  => 'publish',
        ));
    }
} else {
    $pid = $existing[0]->ID;
}

echo "Updating product ID: $pid\n";

// Update post data
wp_update_post(array(
    'ID'           => $pid,
    'post_title'   => '100+ Million Digital Resources',
    'post_content' => 'An all-in-one premium digital collection containing a massive library of digital resources for creators, designers, marketers, entrepreneurs, businesses, students, and professionals. One Powerful Collection. Endless Possibilities.',
    'post_excerpt' => '100+ million digital resources including design templates, AI prompts, graphics, videos, eBooks, business resources, and creative assets.',
));

// Update meta
update_post_meta($pid, '_eproduct_price', 999);
update_post_meta($pid, '_eproduct_sale_price', 299);
update_post_meta($pid, '_eproduct_link', '/store_ecomtoolkit.php');

// Ensure Toolkit category exists and assign
$term = term_exists('Toolkit', 'eproduct_category');
if (!$term) {
    $term = wp_insert_term('Toolkit', 'eproduct_category', array('slug' => 'toolkit'));
    echo "Created category: Toolkit\n";
}
if (!is_wp_error($term)) {
    wp_set_object_terms($pid, array(intval($term['term_id'])), 'eproduct_category');
    echo "Assigned category: Toolkit\n";
}

echo "Product URL: https://store.eproduct.org/store_ecomtoolkit.php\n";
echo "Done!\n";
