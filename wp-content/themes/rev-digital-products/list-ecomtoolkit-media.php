<?php
require_once('../../../wp-load.php');
header('Content-Type: text/plain');
$args = array(
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'post_status' => 'inherit',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
);
$query = new WP_Query($args);
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $id = get_the_ID();
        echo $id . '|' . get_the_title() . '|' . wp_get_attachment_url($id) . '|' . basename(get_attached_file($id)) . "\n";
    }
    wp_reset_postdata();
}
