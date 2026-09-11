<?php
defined('ABSPATH') || exit;
require_once EPRODUCT_PATH . 'templates/shell.php';

if (!have_posts()) {
    eproduct_site_shell('<p>Product not found.</p>', 'Not found');
    return;
}
the_post();
$price = get_post_meta(get_the_ID(), '_eproduct_price', true);
$file = get_post_meta(get_the_ID(), '_eproduct_file', true);
$slug = get_post_field('post_name');
$cats = wp_get_post_terms(get_the_ID(), 'eproduct_category', array('fields' => 'names'));
$initial = preg_match('/./u', get_the_title(), $m) ? $m[0] : '&#9733;';

ob_start();
?>
<article class="ep-single ep-reveal">
  <a class="ep-single__back" href="<?php echo esc_url(home_url('/products/')); ?>">&larr; All Products</a>
  <div class="ep-single__grid">
    <div class="ep-single__media">
      <?php if (has_post_thumbnail()): ?>
        <?php the_post_thumbnail('large'); ?>
      <?php else: ?>
        <div class="ep-single__placeholder"><?php echo $initial; ?></div>
      <?php endif; ?>
    </div>
    <div class="ep-single__info">
      <h1 class="ep-single__title"><?php the_title(); ?></h1>
      <div class="ep-single__price"><?php echo $price ? '$' . number_format($price, 2) : 'Free'; ?></div>
      <div class="ep-single__desc"><?php the_content(); ?></div>
      <button class="ep-btn" data-add-to-cart="<?php echo get_the_ID(); ?>">Add to Cart</button>
      <?php if ($file): ?><p class="ep-note">Delivered as an instant download after purchase.</p><?php endif; ?>
      <?php if (!empty($cats)): ?><div class="ep-single__meta"><?php foreach ($cats as $c) echo '<span class="ep-badge">' . esc_html($c) . '</span>'; ?></div><?php endif; ?>
    </div>
  </div>
</article>
<?php
$ep_content = ob_get_clean();
eproduct_site_shell($ep_content, get_the_title());
