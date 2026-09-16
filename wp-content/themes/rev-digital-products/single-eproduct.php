<?php
/**
 * Single product template for Rev Digital Products — glexmedia-style e-commerce layout.
 *
 * @package Rev Digital Products
 */
get_header();

if (have_posts()) :
    the_post();
    $ep_price = floatval(get_post_meta(get_the_ID(), '_eproduct_price', true));
    $ep_sale = get_post_meta(get_the_ID(), '_eproduct_sale_price', true);
    $ep_sale = ($ep_sale !== '' && floatval($ep_sale) > 0) ? floatval($ep_sale) : '';
    $ep_on_sale = ($ep_sale !== '' && $ep_sale < $ep_price);
    $ep_file = get_post_meta(get_the_ID(), '_eproduct_file', true);
    $ep_cats = wp_get_post_terms(get_the_ID(), 'eproduct_category', array('fields' => 'names'));
    $ep_initial = preg_match('/./u', get_the_title(), $m) ? $m[0] : '&#9733;';
    $ep_pct = $ep_on_sale ? round((1 - $ep_sale / $ep_price) * 100) : 0;

    $related_args = array(
        'post_type' => 'eproduct', 'posts_per_page' => 4, 'post_status' => 'publish',
        'post__not_in' => array(get_the_ID()), 'orderby' => 'rand',
        'meta_query' => array(
            'relation' => 'OR',
            array('key' => '_eproduct_visibility', 'value' => 'active', 'compare' => '='),
            array('key' => '_eproduct_visibility', 'compare' => 'NOT EXISTS'),
        ),
    );
    if (!empty($ep_cats)) {
        $related_args['tax_query'] = array(array(
            'taxonomy' => 'eproduct_category', 'field' => 'name', 'terms' => $ep_cats
        ));
    }
    $related = new WP_Query($related_args);
    ?>
    <article class="ep-single">
      <a class="ep-single__back" href="<?php echo esc_url(home_url('/products/')); ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        All Products
      </a>

      <?php if ($ep_on_sale): ?>
        <div class="ep-single__sale-badge"><?php echo $ep_pct; ?>% OFF</div>
      <?php endif; ?>

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
            <div class="ep-single__meta">
              <?php foreach ($ep_cats as $c) echo '<span class="ep-badge">' . esc_html($c) . '</span>'; ?>
            </div>
          <?php endif; ?>
          <h1 class="ep-single__title"><?php the_title(); ?></h1>

          <div class="ep-single__pricing">
            <?php if ($ep_on_sale): ?>
              <span class="ep-single__price ep-single__price--sale">$<?php echo number_format($ep_sale, 2); ?></span>
              <span class="ep-single__price ep-single__price--orig">$<?php echo number_format($ep_price, 2); ?></span>
            <?php else: ?>
              <span class="ep-single__price"><?php echo $ep_price > 0 ? '$' . number_format($ep_price, 2) : 'Free'; ?></span>
            <?php endif; ?>
          </div>

          <?php if (has_excerpt()): ?>
            <div class="ep-single__excerpt"><?php echo wpautop(get_the_excerpt()); ?></div>
          <?php endif; ?>

          <div class="ep-single__desc"><?php the_content(); ?></div>

          <div class="ep-single__actions">
            <button class="ep-btn ep-btn--lg" data-add-to-cart="<?php echo get_the_ID(); ?>">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
              Add to Cart
            </button>
          </div>

          <div class="ep-single__features">
            <div class="ep-single__feature">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              Instant download after purchase
            </div>
            <div class="ep-single__feature">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              Secure payment processing
            </div>
            <div class="ep-single__feature">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
              Lifetime access &amp; updates
            </div>
          </div>
        </div>
      </div>

      <?php if ($related->have_posts()): ?>
      <section class="ep-single__related">
        <h2>You May Also Like</h2>
        <div class="ep-grid">
          <?php while ($related->have_posts()) { $related->the_post(); echo eproduct_product_item_html($related->post); } wp_reset_postdata(); ?>
        </div>
      </section>
      <?php endif; ?>
    </article>
    <?php
endif;

get_footer();
