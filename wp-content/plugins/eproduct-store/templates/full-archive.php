<?php
defined('ABSPATH') || exit;
require_once EPRODUCT_PATH . 'templates/shell.php';
$cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => false));
ob_start();
?>
<section class="ep-section ep-reveal" style="margin-top:30px">
  <div class="ep-section__head">
    <h2>All Products</h2>
    <p>Explore our full catalog of premium digital products.</p>
  </div>
  <div class="ep-toolbar">
    <div class="ep-search">
      <?php echo eproduct_icon('search'); ?>
      <input type="text" id="ep-search" placeholder="Search products...">
    </div>
    <div class="ep-cats">
      <button class="ep-cat active" data-cat="">All</button>
      <?php foreach ($cats as $c): ?>
        <button class="ep-cat" data-cat="<?php echo esc_attr($c->slug); ?>"><?php echo esc_html($c->name); ?></button>
      <?php endforeach; ?>
    </div>
  </div>
  <div id="ep-grid" class="ep-grid">
    <?php while (have_posts()) : the_post(); echo eproduct_product_item_html(get_post()); endwhile; ?>
  </div>
</section>
<?php
$ep_content = ob_get_clean();
eproduct_site_shell($ep_content, 'All Products');
