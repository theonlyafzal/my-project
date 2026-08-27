<?php
defined('ABSPATH') || exit;
get_header();
?>
<section class="ep-archive">
    <h1>Products</h1>
    <div class="ep-grid">
        <?php while (have_posts()) : the_post(); echo eproduct_product_item_html(get_post()); endwhile; ?>
    </div>
</section>
<?php get_footer(); ?>
