<?php
/**
 * The footer for Rev Digital Products (eproduct.org).
 * Matching glexmedia.in layout: clean minimal footer.
 *
 * @package Rev Digital Products
 */
$ep_company = get_option('eproduct_company_name', 'eProduct');
$ep_year = date('Y');
$store_url = 'https://store.eproduct.org/';
    $ep_icon = get_site_icon_url(36);
    if (!$ep_icon) $ep_icon = esc_url(home_url('/wp-content/uploads/2026/08/cropped-product-32x32.png'));
    ?>
    </main><!-- .ep-main -->

    <style>
    .ep-footer{background:#f9fafb;border-top:1px solid #e5e7eb}
    .ep-footer__inner{max-width:1200px;margin:0 auto;padding:48px 24px 32px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:40px}
    .ep-footer__brand{max-width:320px}
    .ep-footer__logo{display:inline-flex;align-items:center;gap:8px;margin-bottom:12px;text-decoration:none}
    .ep-footer__logo .ep-brand__name{font-size:1.2rem;font-weight:700;color:#111827}
    .ep-footer__logo img{border-radius:6px}
    .ep-footer__brand p{font-size:.9rem;color:#6b7280;line-height:1.6;margin:0}
    .ep-footer__links{display:flex;gap:48px}
    .ep-footer__col h4{font-size:.85rem;font-weight:600;color:#111827;text-transform:uppercase;letter-spacing:.5px;margin:0 0 16px}
    .ep-footer__col a{display:block;font-size:.9rem;color:#6b7280;text-decoration:none;padding:4px 0;transition:color .15s}
    .ep-footer__col a:hover{color:#111827}
    .ep-footer__bar{border-top:1px solid #e5e7eb}
    .ep-footer__bar-inner{max-width:1200px;margin:0 auto;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px}
    .ep-footer__bar span{font-size:.85rem;color:#6b7280}
    @media(max-width:768px){.ep-footer__inner{flex-direction:column;gap:32px}.ep-footer__links{flex-direction:column;gap:32px}.ep-footer__bar-inner{flex-direction:column;text-align:center}}
    </style>

    <footer class="ep-footer">
      <div class="ep-footer__inner">
        <div class="ep-footer__brand">
          <a href="<?php echo esc_url(home_url('/')); ?>" class="ep-footer__logo">
            <img src="<?php echo esc_url($ep_icon); ?>" alt="" width="28" height="28">
            <span class="ep-brand__name">eProduct</span>
          </a>
          <p>A premium digital products store — courses, templates, ebooks, software and creative assets, all in one place.</p>
        </div>

        <div class="ep-footer__links">
          <div class="ep-footer__col">
            <h4>Explore</h4>
            <a href="#who">Who It's For</a>
            <a href="#what">What You Get</a>
            <a href="#how">How It Works</a>
            <a href="#faq">FAQ</a>
          </div>
          <div class="ep-footer__col">
            <h4>Store</h4>
            <a href="<?php echo esc_url($store_url); ?>">Visit Store</a>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About Us</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy Policy</a>
            <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms &amp; Conditions</a>
          </div>
        </div>
      </div>

      <div class="ep-footer__bar">
        <div class="ep-footer__bar-inner">
          <span>&copy; <?php echo $ep_year; ?> <?php echo esc_html($ep_company); ?>. All rights reserved.</span>
          <span>Made for creators, students &amp; entrepreneurs.</span>
        </div>
      </div>
    </footer>

    <?php wp_footer(); ?>
</div><!-- #page -->
</body>
</html>
