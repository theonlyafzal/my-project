<?php
/**
 * The footer for Rev Digital Products (eProduct storefront).
 *
 * @package Rev Digital Products
 */
$ep_company = get_option('eproduct_company_name', 'eProduct');
$ep_icon = get_site_icon_url(36);
if (!$ep_icon) $ep_icon = esc_url(home_url('/wp-content/uploads/2026/08/cropped-product-32x32.png'));
$ep_year = date('Y');
?>
    </main><!-- .ep-main -->

    <footer class="ep-footer">
      <div class="ep-footer__inner">
        <div class="ep-footer__brand">
          <a href="<?php echo esc_url(home_url('/')); ?>" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;">
            <img class="ep-brand__mark" src="<?php echo esc_url($ep_icon); ?>" alt="<?php echo esc_attr($ep_company); ?>" width="36" height="36">
            <span class="ep-brand__name" style="-webkit-text-fill-color:#fff;color:#fff;"><?php echo esc_html($ep_company); ?></span>
          </a>
          <p>Premium digital products, delivered instantly to your inbox. Templates, courses, ebooks, software and creative assets.</p>
        </div>
        <div class="ep-footer__cols">
          <div>
            <h4>Store</h4>
            <a href="https://store.eproduct.org/" target="_blank" rel="noopener">Visit Store</a>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">All Products</a>
          </div>
          <div>
            <h4>Company</h4>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About Us</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy Policy</a>
            <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms &amp; Conditions</a>
          </div>
          <div>
            <h4>Support</h4>
            <a href="mailto:support@eproduct.org">Contact Support</a>
            <a href="<?php echo esc_url(home_url('/account/')); ?>">My Account</a>
            <a href="<?php echo esc_url(home_url('/checkout/')); ?>">Checkout</a>
          </div>
        </div>
      </div>
      <div class="ep-footer__bar">&copy; <?php echo $ep_year; ?> <?php echo esc_html($ep_company); ?>. All rights reserved.</div>
    </footer>

    <?php wp_footer(); ?>
</div><!-- #page -->
</body>
</html>
