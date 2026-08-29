# AGENTS.md — eproduct (digital products store)

## What this repo is
WordPress site for **eproduct**, a store selling digital products (live at eproduct.org).
- **The real storefront is a custom plugin: `wp-content/plugins/eproduct-store/`** — it provides the product CPT, pages, cart, checkout, and payment server.
- Mayosis theme and Elementor were removed from the repo and live site. The **active theme is `Rev Digital Products`** (`wp-content/themes/rev-digital-products/`, a classic Underscores-based theme). The storefront **UI lives in this theme** (templates + `style.css` + `js/`), while `eproduct-store` provides the backend (CPT, cart, checkout, payments, orders, privacy, shortcodes). EDD is also present but **not** the active storefront. Do **not** assume store logic lives in them; extend `eproduct-store` for backend and the `Rev Digital Products` theme for storefront UI.
- **No bundler, test runner, or linter.** Work is PHP / template edits committed directly. Exception: **Stripe & Razorpay payment gateways require `composer install`** in `wp-content/plugins/eproduct-store/` (SDKs load from `vendor/`). PayPal uses raw curl and needs no SDK.

## Model routing (pick the model by task domain)
When delegating to a model/subagent, route by domain:
- Frontend (theme / UI / HTML / CSS / JS): **Hy3**
- Backend & database / SQL: **MiMo V2.5**
- React code (only if used): **Muse Spark 1.2**
- Security, privacy & regulatory / compliance (incl. ad + privacy policy): **Nemotron 3.5 Lightning**

## Three locations — GitHub is the source of truth
- **GitHub (canonical):** `github.com/theonlyafzal/my-project` (private).
- **Local desktop:** `C:\Users\afzal\Desktop\my-project`.
- **Live server:** `169.58.90.8`, SSH/SFTP user `eproduct` (port 22), doc root `/home/eproduct/htdocs/eproduct.org`.
- Deploy by syncing repo content into the live doc root. The live doc root is not itself a git checkout.

## Architecture (verified from code)
- **Products:** custom post type `eproduct` (`includes/cpt-products.php`) with taxonomy `eproduct_category` and meta `_eproduct_price`, `_eproduct_file`.
- **Pages:** auto-created on plugin activation via `eproduct_create_pages()` in `includes/templates.php` — each is a WordPress Page whose content is a shortcode (`[eproduct_home]`, `[eproduct_products]`, `[eproduct_about]`, `[eproduct_login]`, `[eproduct_register]`, `[eproduct_checkout]`, `[eproduct_account]`, `[eproduct_privacy]`).
- **Product detail pages auto-build:** adding a product (a new `eproduct` post) automatically gets its own URL (`/product/<slug>/`) rendered by `templates/single-eproduct.php` via the `eproduct_template_include` filter. The products archive/category filter is `templates/archive-eproduct.php`. **No separate "page generator" is needed** — it is built-in CPT behavior.
- **Products grid filter/search:** `[eproduct_products]` renders cards + category buttons + search box; filtering is AJAX `wp_ajax_eproduct_filter` (`includes/ajax.php`).
- **Cart:** PHP `$_SESSION`-based (`session_start()` on `init` in `includes/templates.php`), not a WooCommerce/EDD cart.
- **Payments:** standalone script `includes/payments/payment-server.php` (bootstraps `wp-load.php` itself, not a WP REST route). Actions: `?action=create|return|webhook`. Gateways Stripe / PayPal / Razorpay are classes under `includes/payments/` selected by `eproduct_gateway_instance()`. **Stripe & Razorpay need their SDKs present (`composer install` in the plugin dir); otherwise they return an "SDK not installed" error.**
- **Orders DB:** custom table `{prefix}eproduct_orders` created on activation via `dbDelta` in `includes/orders.php`.
- **Settings / secrets:** Stripe, PayPal, Razorpay keys and the FB Pixel ID are stored as **WordPress options** (admin page "eProduct" → `includes/settings.php`), not in files.
- **Privacy / ads:** `includes/privacy.php` injects the Meta (FB) Pixel only after consent; a cookie banner sets `eproduct_consent=granted`. GDPR/EU consent must stay before running FB ads — route to Nemotron 3.5 Lightning.
- **Storefront UI/UX (theme-based):** the storefront is **rendered by the active theme `Rev Digital Products`** — the theme owns `style.css` (design system), `header.php` (nav), `footer.php`, `page.php` (renders the `eproduct_*` shortcode pages), `single-eproduct.php` (two-column product detail), `archive-eproduct.php` + `taxonomy-eproduct_category.php` (products grid + filters), and `js/store.js` + `js/eproduct-ui.js` (enqueued in `functions.php` via `eproduct_theme_storefront_assets`). The plugin `eproduct-store` provides the data/functions used by those templates: shortcodes (`[eproduct_home]`, `[eproduct_products]`, etc.), `eproduct_product_item_html()`, `eproduct_icon()`, cart/checkout/payments. Edit the theme's `style.css` and template files for storefront look; edit `eproduct-store` for backend. Home = animated gradient hero + feature cards + featured products + CTA; products grid uses `.ep-card`; single product uses `.ep-single`. JS (`eproduct-ui.js`) adds sticky-header shadow, mobile burger menu, and IntersectionObserver scroll-reveal (`.ep-reveal`). Login/Register are **modal overlays** opened from the nav (the `/login/` and `/register/` pages remain as no-JS fallback); auth is submitted via AJAX — `eproduct_ajax_login` and `eproduct_ajax_register` in `includes/templates.php`, secured with the `eproduct_auth` nonce localized as `EP.authNonce`. Note: a single `eproduct` URL canonicalizes to `/blog/product/<slug>/` (WP permalink front).

## Gotchas an agent will otherwise miss
- **Never commit `wp-config.php`** — git-ignored; it holds the DB password and exists only on the server.
- **API keys & Pixel ID live in WP options**, not in code or repo — configure them in the eProduct admin settings, don't hardcode.
- **The payment server is a raw PHP script, not a REST API** — it requires `wp-load.php` relative to its own path and redirects by `home_url()`. Editing its require paths will break it.
- **Cart breaks without sessions** — `eproduct_start_session()` runs only on the frontend `init`; don't disable PHP sessions.
- **Per-product pages already auto-exist** (CPT + `single-eproduct.php`). Do **not** build a custom page generator.
- **DB snapshot `database/eproduct.sql.gz` is a static one-time dump**, not live data. Refresh with `mysqldump -u eproduct -p eproduct | gzip > database/eproduct.sql.gz` on the server when needed.
- **No secrets in repo:** SFTP password, DB password, and GitHub token are not stored here — do not add them.

## Where to look
- Custom store logic (the real app): `wp-content/plugins/eproduct-store/`
  - Storefront/templates/shortcodes: `includes/templates.php`
  - Products CPT + meta box: `includes/cpt-products.php`
  - Orders table + queries: `includes/orders.php`
  - Payments: `includes/payments/` (`payment-server.php`, `PaymentGateway.php`, `StripeGateway.php`, `PayPalGateway.php`, `RazorpayGateway.php`)
  - Privacy + FB pixel + consent: `includes/privacy.php`
   - Product templates (now in the theme): `wp-content/themes/rev-digital-products/single-eproduct.php`, `archive-eproduct.php`, `taxonomy-eproduct_category.php`, `page.php`, `header.php`, `footer.php`, `style.css`, `js/`
- Page builder: Elementor **removed** from the repo and live site (it was not used for the storefront).
- Theme: `wp-content/themes/rev-digital-products/` (classic Underscores-based theme; the active storefront theme — storefront UI lives here).
- Prefer editing the `eproduct-store` plugin over core/plugin/theme files.
