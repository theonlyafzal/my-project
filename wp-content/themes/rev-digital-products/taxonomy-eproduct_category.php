<?php
/**
 * Category (eproduct_category) archive template for Rev Digital Products.
 * Reuses the products grid from archive-eproduct.php.
 *
 * @package Rev Digital Products
 */
get_header();

$ep_cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => false));
$ep_term = get_queried_object();
?>
<section class="ep-section ep-reveal" style="margin-top:30px">
  <div class="ep-section__head">
    <h2><?php echo $ep_term ? esc_html($ep_term->name) : 'Products'; ?></h2>
    <p>Browse digital products<?php echo $ep_term ? ' in ' . esc_html($ep_term->name) : ''; ?>.</p>
  </div>
  <div class="ep-toolbar">
    <div class="ep-search">
      <?php echo eproduct_icon('search'); ?>
      <input type="text" id="ep-search" placeholder="Search products...">
    </div>
    <div class="ep-cats">
      <button class="ep-cat active" data-cat="">All</button>
      <?php foreach ($ep_cats as $c): ?>
        <button class="ep-cat" data-cat="<?php echo esc_attr($c->slug); ?>"><?php echo esc_html($c->name); ?></button>
      <?php endforeach; ?>
    </div>
  </div>
  <div id="ep-grid" class="ep-grid">
    <?php while (have_posts()) : the_post(); echo eproduct_product_item_html(get_post()); endwhile; ?>
  </div>
</section>
<?php
get_footer();
