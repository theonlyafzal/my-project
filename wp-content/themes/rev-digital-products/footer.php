<?php
/**
 * The footer for Rev Digital Products (eProduct storefront).
 * Matching special.glexmedia.in layout: newsletter + multi-column + copyright.
 *
 * @package Rev Digital Products
 */
$ep_company = get_option('eproduct_company_name', 'eProduct');
$ep_icon = get_site_icon_url(36);
if (!$ep_icon) $ep_icon = esc_url(home_url('/wp-content/uploads/2026/08/cropped-product-32x32.png'));
$ep_year = date('Y');
$store_url = 'https://store.eproduct.org/';
$cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => true));
?>
    </main><!-- .ep-main -->

    <footer class="ep-footer">
      <!-- Newsletter row -->
      <div class="ep-footer__newsletter">
        <div class="ep-footer__newsletter-inner">
          <div class="ep-footer__newsletter-text">
            <h2>Subscribe &amp; Get 10% Discount</h2>
            <p>Get E-mail updates about our latest shop and special offers.</p>
          </div>
          <form class="ep-footer__newsletter-form" data-newsletter>
            <input type="email" placeholder="Enter your email address" required>
            <button type="submit" class="ep-btn">Subscribe</button>
          </form>
        </div>
      </div>

      <!-- Main footer columns -->
      <div class="ep-footer__main">
        <div class="ep-footer__main-inner">
          <!-- Column 1: Logo + Address + Social -->
          <div class="ep-footer__col ep-footer__col--brand">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="ep-footer__logo">
              <img class="ep-brand__mark" src="<?php echo esc_url($ep_icon); ?>" alt="<?php echo esc_attr($ep_company); ?>" width="40" height="40">
              <span class="ep-brand__name" style="-webkit-text-fill-color:#fff;color:#fff;"><?php echo esc_html($ep_company); ?></span>
            </a>
            <p class="ep-footer__address">Premium digital products, delivered instantly. Templates, courses, ebooks, software and creative assets.</p>
            <div class="ep-footer__social">
              <a href="#" class="ep-footer__social-link" title="Instagram">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
              </a>
              <a href="#" class="ep-footer__social-link" title="Facebook">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
              <a href="#" class="ep-footer__social-link" title="Twitter / X">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              </a>
              <a href="#" class="ep-footer__social-link" title="WhatsApp">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              </a>
            </div>
          </div>

          <!-- Column 2: Need Help -->
          <div class="ep-footer__col">
            <h4>Need Help?</h4>
            <p>Monday – Friday: 9:00 – 20:00</p>
            <p>Saturday: 11:00 – 15:00</p>
            <a href="mailto:support@eproduct.org">support@eproduct.org</a>
          </div>

          <!-- Column 3: Our Products -->
          <div class="ep-footer__col">
            <h4>Our Products</h4>
            <?php if (!empty($cats) && !is_wp_error($cats)): ?>
              <?php foreach ($cats as $c): ?>
                <a href="<?php echo esc_url(get_term_link($c)); ?>" data-cat-slug="<?php echo esc_attr($c->slug); ?>"><?php echo esc_html($c->name); ?></a>
              <?php endforeach; ?>
            <?php endif; ?>
            <a href="<?php echo esc_url($store_url); ?>" target="_blank" rel="noopener">Visit Store</a>
          </div>

          <!-- Column 4: Support -->
          <div class="ep-footer__col">
            <h4>Support</h4>
            <a href="mailto:support@eproduct.org">Help Center</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy Policy</a>
            <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms &amp; Conditions</a>
            <a href="<?php echo esc_url(home_url('/account/')); ?>">My Account</a>
          </div>

          <!-- Column 5: Customer Service -->
          <div class="ep-footer__col">
            <h4>Customer Service</h4>
            <a href="mailto:support@eproduct.org">Contact Us</a>
            <a href="<?php echo esc_url(home_url('/checkout/')); ?>">Checkout</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Refund &amp; Returns</a>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About Us</a>
          </div>
        </div>
      </div>

      <!-- Copyright bar -->
      <div class="ep-footer__bar">
        <div class="ep-footer__bar-inner">
          <span>&copy; <?php echo $ep_year; ?> <?php echo esc_html($ep_company); ?>. All rights reserved.</span>
          <div class="ep-footer__bar-links">
            <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms of Use</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Security</a>
          </div>
        </div>
      </div>
    </footer>

    <!-- Login Modal -->
    <div class="ep-modal" id="epLoginModal" aria-hidden="true">
      <div class="ep-modal__overlay" data-ep-modal-close></div>
      <div class="ep-modal__dialog">
        <button class="ep-modal__close" data-ep-modal-close>&times;</button>
        <div class="ep-auth__aside">
          <div class="ep-auth__brand">
            <img class="ep-brand__mark" src="<?php echo esc_url($ep_icon); ?>" alt="" width="32" height="32">
            <span class="ep-brand__name"><?php echo esc_html($ep_company); ?></span>
          </div>
          <h2>Welcome Back!</h2>
          <p>Sign in to access your downloads, order history and account settings.</p>
          <ul class="ep-auth__points">
            <li>Access your purchased products</li>
            <li>View order history</li>
            <li>Download files instantly</li>
            <li>Get updates on new products</li>
          </ul>
        </div>
        <div class="ep-modal__body">
          <h2 class="ep-auth__title">Sign In</h2>
          <p class="ep-auth__sub">Don't have an account? <a href="#" data-ep-auth data-tab="register">Create one</a></p>
          <form class="ep-auth__form" id="epLoginForm" data-mode="login">
            <label>Username or Email</label>
            <input type="text" name="log" placeholder="Enter your username or email" required>
            <label>Password</label>
            <input type="password" name="pwd" placeholder="Enter your password" required>
            <div class="ep-auth__submit">
              <button type="submit" class="ep-btn">Sign In</button>
            </div>
          </form>
          <div class="ep-auth__msg"></div>
        </div>
      </div>
    </div>

    <!-- Register Modal -->
    <div class="ep-modal" id="epRegisterModal" aria-hidden="true">
      <div class="ep-modal__overlay" data-ep-modal-close></div>
      <div class="ep-modal__dialog">
        <button class="ep-modal__close" data-ep-modal-close>&times;</button>
        <div class="ep-auth__aside">
          <div class="ep-auth__brand">
            <img class="ep-brand__mark" src="<?php echo esc_url($ep_icon); ?>" alt="" width="32" height="32">
            <span class="ep-brand__name"><?php echo esc_html($ep_company); ?></span>
          </div>
          <h2>Create Account</h2>
          <p>Join us to get instant access to premium digital products.</p>
          <ul class="ep-auth__points">
            <li>Instant download after purchase</li>
            <li>Track all your orders</li>
            <li>Get exclusive deals &amp; updates</li>
            <li>Lifetime access to products</li>
          </ul>
        </div>
        <div class="ep-modal__body">
          <h2 class="ep-auth__title">Create Account</h2>
          <p class="ep-auth__sub">Already have an account? <a href="#" data-ep-auth data-tab="login">Sign in</a></p>
          <form class="ep-auth__form" id="epRegisterForm" data-mode="register">
            <label>Username</label>
            <input type="text" name="user_login" placeholder="Choose a username" required>
            <label>Email</label>
            <input type="email" name="user_email" placeholder="Enter your email" required>
            <div class="ep-auth__submit">
              <button type="submit" class="ep-btn">Create Account</button>
            </div>
          </form>
          <div class="ep-auth__msg"></div>
        </div>
      </div>
    </div>

    <?php wp_footer(); ?>
</div><!-- #page -->
</body>
</html>
