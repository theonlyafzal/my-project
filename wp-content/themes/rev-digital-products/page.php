<?php
/**
 * Page template for Rev Digital Products (renders eProduct shortcodes).
 *
 * @package Rev Digital Products
 */
get_header();
?>

<?php
while (have_posts()) :
    the_post();
    the_content();
endwhile;
?>

<?php
get_footer();
