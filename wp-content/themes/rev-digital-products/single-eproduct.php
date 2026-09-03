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
      <a class="ep-single__back" href="<?php echo esc_url(home_url('/products/')); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Products
      </a>
      <div class="ep-single__grid">
        <div class="ep-single__media">
          <?php if (has_post_thumbnail()): ?>
            <?php the_post_thumbnail('large'); ?>
          <?php else: ?>
            <div class="ep-single__placeholder"><?php echo $ep_initial; ?></div>
          <?php endif; ?>
        </div>
        <div class="ep-single__info">
          <?php if (!empty($ep_cats)): ?>
            <div class="ep-single__meta" style="margin-bottom:12px;">
              <?php foreach ($ep_cats as $c) echo '<span class="ep-badge">' . esc_html($c) . '</span>'; ?>
            </div>
          <?php endif; ?>
          <h1 class="ep-single__title"><?php the_title(); ?></h1>
          <div class="ep-single__price"><?php echo $ep_price ? '$' . number_format($ep_price, 2) : 'Free'; ?></div>
          <div class="ep-single__desc"><?php the_content(); ?></div>
          <div class="ep-single__actions">
            <a class="ep-btn ep-btn--lg" href="<?php echo esc_url(add_query_arg('add_to_cart', get_the_ID(), get_permalink())); ?>">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
              Buy Now
            </a>
            <a class="ep-btn ep-btn--outline ep-btn--lg" href="<?php echo esc_url(home_url('/products/')); ?>">Browse More</a>
          </div>
          <?php if ($ep_file): ?>
            <p class="ep-note">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14" style="vertical-align:-2px;margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Instant download after purchase
            </p>
          <?php endif; ?>
        </div>
      </div>
    </article>
    <?php
endif;

get_footer();
