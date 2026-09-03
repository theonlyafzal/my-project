<?php
defined('ABSPATH') || exit;
get_header();
while (have_posts()) : the_post();
    $price = get_post_meta(get_the_ID(), '_eproduct_price', true);
    $file = get_post_meta(get_the_ID(), '_eproduct_file', true);
    $slug = get_post_field('post_name');
    $cats = wp_get_post_terms(get_the_ID(), 'eproduct_category', array('fields' => 'names'));
    $initial = preg_match('/./u', get_the_title(), $m) ? $m[0] : '★';
?>
<div class="eproduct">
    <article class="ep-single">
        <a class="ep-single__back" href="<?php echo esc_url(home_url('/products/')); ?>">&larr; All Products</a>
        <div class="ep-single__grid">
            <div class="ep-single__media">
                <?php if (has_post_thumbnail()): ?>
                    <?php the_post_thumbnail('large'); ?>
                <?php else: ?>
                    <div style="aspect-ratio:4/3;display:grid;place-items:center;background:linear-gradient(135deg,#eef2ff,#fce7f3);color:var(--ep-primary);font-size:64px;font-weight:800"><?php echo esc_html($initial); ?></div>
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
</div>
<?php endwhile; get_footer(); ?>
