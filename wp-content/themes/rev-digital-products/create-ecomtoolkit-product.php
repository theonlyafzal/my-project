<?php
require_once('../../../wp-load.php');
header('Content-Type: text/plain');

// Check if product already exists
$existing = get_posts(array(
    'post_type' => 'eproduct',
    'post_status' => 'publish',
    's' => 'Ultimate E-Commerce Seller Toolkit',
    'posts_per_page' => 1
));

if (!empty($existing)) {
    echo "Product already exists with ID: " . $existing[0]->ID . "\n";
    echo "Updating meta...\n";
    $pid = $existing[0]->ID;
} else {
    // Create product
    $pid = wp_insert_post(array(
        'post_title'   => 'Ultimate E-Commerce Seller Toolkit',
        'post_content' => 'All-in-one e-commerce seller toolkit for starting, running, automating, and scaling an e-commerce business. 24 Powerful Modules. 362+ Premium Tools. Lifetime Access.',
        'post_excerpt' => '24 modules and 362+ premium tools for product research, store management, marketing, finance, inventory, operations, analytics and business growth.',
        'post_type'    => 'eproduct',
        'post_status'  => 'publish',
    ));
    echo "Created product with ID: $pid\n";
}

// Set meta
update_post_meta($pid, '_eproduct_price', 999);
update_post_meta($pid, '_eproduct_sale_price', 299);
update_post_meta($pid, '_eproduct_link', '/store_ecomtoolkit.php');

// Set category
$term = term_exists('Toolkit', 'eproduct_category');
if (!$term) {
    $term = wp_insert_term('Toolkit', 'eproduct_category', array('slug' => 'toolkit'));
    echo "Created category: Toolkit\n";
}
if (!is_wp_error($term)) {
    wp_set_object_terms($pid, array(intval($term['term_id'])), 'eproduct_category');
    echo "Assigned category: Toolkit\n";
}

echo "\nProduct URL: https://store.eproduct.org/store_ecomtoolkit.php\n";
echo "Product ID: $pid\n";
echo "Done!\n";
