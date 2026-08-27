# AGENTS.md — eproduct (digital products store)

## What this repo is
WordPress site for **eproduct**, a store selling digital products (live at eproduct.org).
- Stack: WordPress (PHP) + **Easy Digital Downloads (EDD)** for the store + **Mayosis** theme (digital marketplace UI) + **Elementor** for page building.
- There is **no build step, bundler, or test runner**. Work is PHP / theme / template edits committed directly.
- Database: MySQL `eproduct` (user `eproduct`, host `127.0.0.1:3306`). Credentials live only in `wp-config.php` on the server.

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

## Gotchas an agent will otherwise miss
- **Never commit `wp-config.php`** — it is git-ignored and holds the DB password; it exists only on the server.
- **Per-product pages already exist** via EDD (every product/"download" gets its own page automatically). Do **not** build a custom "auto page generator" — extend EDD/Mayosis templates. The requested auto-building behavior is EDD's built-in function.
- **Products page (cards, category filter, search):** largely provided by the Mayosis theme + EDD. Extend those, don't rebuild from scratch.
- **Payments:** EDD handles checkout. Stripe & PayPal are EDD gateway add-ons (Stripe is a separate EDD Stripe add-on; PayPal is in core). **Razorpay requires an additional gateway plugin** — verify availability before promising it.
- **Privacy / Meta (Facebook) ads:** a compliant **Privacy Policy + cookie consent** (for the FB Pixel) is required *before* running social ads, especially for EU users. Route this to the security/safety model (Nemotron 3.5 Lightning).
- **DB snapshot is static:** `database/eproduct.sql.gz` is a one-time dump, not live data. Refresh with `mysqldump -u eproduct -p eproduct | gzip > database/eproduct.sql.gz` on the server when needed.
- **No secrets in repo:** SFTP password, DB password, and GitHub token are not stored here — do not add them.

## Where to look
- Store logic: `wp-content/plugins/easy-digital-downloads/`
- Theme / marketplace UI: `wp-content/themes/mayosis/`
- Page builder: `wp-content/plugins/elementor/`
- Custom code / child theme: prefer a child theme or MU plugin over editing core/plugin files.
