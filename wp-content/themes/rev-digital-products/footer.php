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
            <h4>Shop</h4>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">All Products</a>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">Templates</a>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">Graphics</a>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">Courses</a>
          </div>
          <div>
            <h4>Company</h4>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy Policy</a>
          </div>
        </div>
      </div>
      <div class="ep-footer__bar">&copy; <?php echo $ep_year; ?> <?php echo esc_html($ep_company); ?>. All rights reserved.</div>
    </footer>

    <div class="ep-modal" id="epAuthModal" aria-hidden="true">
      <div class="ep-modal__overlay" data-ep-modal-close></div>
      <div class="ep-modal__dialog" role="dialog" aria-modal="true" aria-label="Account">
        <button class="ep-modal__close" type="button" data-ep-modal-close aria-label="Close">&times;</button>
        <div class="ep-auth__aside">
          <div class="ep-auth__brand"><span class="ep-brand__mark">&#9670;</span> <span class="ep-brand__name"><?php echo esc_html($ep_company); ?></span></div>
          <h2>Welcome</h2>
          <p>Log in or create a free account to buy digital products and download them instantly.</p>
          <ul class="ep-auth__points">
            <li>Instant access to everything you buy</li>
            <li>Re-download purchases anytime, anywhere</li>
            <li>Secure checkout with Stripe, PayPal &amp; Razorpay</li>
          </ul>
        </div>
        <div class="ep-modal__body">
          <div class="ep-modal__tabs">
            <button type="button" class="ep-tab active" data-tab="login">Log In</button>
            <button type="button" class="ep-tab" data-tab="register">Register</button>
          </div>

          <form class="ep-auth__form" id="epLoginForm" data-mode="login">
            <label for="ep-log">Username or email</label>
            <input id="ep-log" type="text" name="log" required autocomplete="username">
            <label for="ep-pwd">Password</label>
            <input id="ep-pwd" type="password" name="pwd" required autocomplete="current-password">
            <button class="ep-btn ep-auth__submit" type="submit">Log In</button>
            <a class="ep-auth__alt" href="<?php echo esc_url(wp_lostpassword_url()); ?>">Forgot your password?</a>
          </form>

          <form class="ep-auth__form" id="epRegisterForm" data-mode="register" hidden>
            <label for="ep-user">Username</label>
            <input id="ep-user" type="text" name="user_login" required autocomplete="username">
            <label for="ep-email">Email</label>
            <input id="ep-email" type="email" name="user_email" required autocomplete="email">
            <button class="ep-btn ep-auth__submit" type="submit">Create Account</button>
            <p class="ep-auth__note">We'll email you a link to set your password.</p>
          </form>

          <div class="ep-auth__msg" id="epAuthMsg" role="status"></div>
        </div>
      </div>
    </div>

    <?php wp_footer(); ?>
</div><!-- #page -->
</body>
</html>
