<?php
defined('ABSPATH') || exit;
get_header();
?>
<div class="eproduct">
    <section class="ep-section" style="margin-top:30px">
        <div class="ep-section__head">
            <h2>All Products</h2>
            <p>Explore our full catalog of digital products.</p>
        </div>
        <div class="ep-grid">
            <?php while (have_posts()) : the_post(); echo eproduct_product_item_html(get_post()); endwhile; ?>
        </div>
    </section>
</div>
<?php get_footer(); ?>
