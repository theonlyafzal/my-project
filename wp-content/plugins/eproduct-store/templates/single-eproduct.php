<?php
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post();
    $price = get_post_meta(get_the_ID(), '_eproduct_price', true);
    $file = get_post_meta(get_the_ID(), '_eproduct_file', true);
    $slug = get_post_field('post_name');
?>
<article class="ep-single">
    <a href="<?php echo esc_url(home_url('/products/')); ?>">&larr; All Products</a>
    <h1><?php the_title(); ?></h1>
    <?php the_post_thumbnail('large'); ?>
    <div class="ep-desc"><?php the_content(); ?></div>
    <p class="ep-price"><?php echo $price ? '$' . number_format($price, 2) : 'Free'; ?></p>
    <a class="ep-btn" href="<?php echo esc_url(home_url('/product/' . $slug . '/?add_to_cart=' . get_the_ID())); ?>">Buy Now</a>
    <?php if ($file): ?><p class="ep-note">Delivered as an instant download after purchase.</p><?php endif; ?>
</article>
<?php endwhile; get_footer(); ?>
