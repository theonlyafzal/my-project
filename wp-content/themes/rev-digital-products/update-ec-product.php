<?php
require_once('../../../wp-load.php');
header('Content-Type: text/plain');
$pid = 237;
wp_update_post(array(
    'ID'           => $pid,
    'post_title'   => 'Ultimate E-Commerce Seller Toolkit',
    'post_content' => 'All-in-one e-commerce seller toolkit for starting, running, automating, and scaling an e-commerce business. 24 Powerful Modules. 362+ Premium Tools. Lifetime Access. Rs. 299/-.',
    'post_excerpt' => '24 modules and 362+ premium tools for product research, marketing, finance, inventory, operations, analytics and business growth.',
));
update_post_meta($pid, '_eproduct_price', 999);
update_post_meta($pid, '_eproduct_sale_price', 299);
update_post_meta($pid, '_eproduct_link', '/store_ecomtoolkit.php');
echo "Updated product ID: $pid\nTitle: Ultimate E-Commerce Seller Toolkit\n";
