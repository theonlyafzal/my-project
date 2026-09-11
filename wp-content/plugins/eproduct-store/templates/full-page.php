<?php
defined('ABSPATH') || exit;
require_once EPRODUCT_PATH . 'templates/shell.php';

$ep_title = is_front_page() ? 'Home' : (wp_title('', false) ?: get_the_title());
ob_start();
if (have_posts()) {
    the_post();
    the_content();
}
$ep_content = ob_get_clean();
eproduct_site_shell($ep_content, $ep_title);
