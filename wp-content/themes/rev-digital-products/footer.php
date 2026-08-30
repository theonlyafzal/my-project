<?php
/**
 * The footer for Rev Digital Products (eProduct storefront).
 *
 * @package Rev Digital Products
 */
$ep_company = get_option('eproduct_company_name', 'eProduct');
$ep_year = date('Y');
?>
    </main><!-- .ep-main -->

    <footer class="ep-footer">
      <div class="ep-footer__inner">
        <div class="ep-footer__brand">
          <span class="ep-brand__mark">&#9670;</span> <?php echo esc_html($ep_company); ?>
          <p>Premium digital products, delivered instantly to your inbox.</p>
        </div>
        <div class="ep-footer__cols">
          <div>
            <h4>Store</h4>
            <a href="https://store.eproduct.org/" target="_blank" rel="noopener">Visit Store</a>
          </div>
          <div>
            <h4>Company</h4>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy Policy</a>
            <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms &amp; Conditions</a>
          </div>
        </div>
      </div>
      <div class="ep-footer__bar">&copy; <?php echo $ep_year; ?> <?php echo esc_html($ep_company); ?>. All rights reserved.</div>
    </footer>

    <?php wp_footer(); ?>
</div><!-- #page -->
</body>
</html>
