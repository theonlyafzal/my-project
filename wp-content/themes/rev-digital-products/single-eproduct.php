<?php
/**
 * Single product template for Rev Digital Products.
 *
 * @package Rev Digital Products
 */
get_header();

if (have_posts()) :
    the_post();
    $ep_price = get_post_meta(get_the_ID(), '_eproduct_price', true);
    $ep_file = get_post_meta(get_the_ID(), '_eproduct_file', true);
    $ep_slug = get_post_field('post_name');
    $ep_cats = wp_get_post_terms(get_the_ID(), 'eproduct_category', array('fields' => 'names'));
    $ep_initial = preg_match('/./u', get_the_title(), $m) ? $m[0] : '&#9733;';
    ?>
    <article class="ep-single ep-reveal">
      <a class="ep-single__back" href="<?php echo esc_url(home_url('/products/')); ?>">&larr; All Products</a>
      <div class="ep-single__grid">
        <div class="ep-single__media">
          <?php if (has_post_thumbnail()): ?>
            <?php the_post_thumbnail('large'); ?>
          <?php else: ?>
            <div class="ep-single__placeholder"><?php echo $ep_initial; ?></div>
          <?php endif; ?>
        </div>
        <div class="ep-single__info">
          <h1 class="ep-single__title"><?php the_title(); ?></h1>
          <div class="ep-single__price"><?php echo $ep_price ? '$' . number_format($ep_price, 2) : 'Free'; ?></div>
          <div class="ep-single__desc"><?php the_content(); ?></div>
          <a class="ep-btn" href="<?php echo esc_url(add_query_arg('add_to_cart', get_the_ID(), get_permalink())); ?>">Buy Now</a>
          <?php if ($ep_file): ?><p class="ep-note">Delivered as an instant download after purchase.</p><?php endif; ?>
          <?php if (!empty($ep_cats)): ?><div class="ep-single__meta"><?php foreach ($ep_cats as $c) echo '<span class="ep-badge">' . esc_html($c) . '</span>'; ?></div><?php endif; ?>
        </div>
      </div>
    </article>
    <?php
endif;

get_footer();
