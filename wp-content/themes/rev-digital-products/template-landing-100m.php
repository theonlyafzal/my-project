<?php
/**
 * Template Name: Landing - 100M Digital Resources
 * Description: Premium high-converting landing page for 100+ Million Digital Resources collection.
 *
 * @package Rev Digital Products
 */
get_header();
$ep_company = get_option('eproduct_company_name', 'eProduct Digital');
$ep_icon = get_site_icon_url(36);
if (!$ep_icon) $ep_icon = esc_url(home_url('/wp-content/uploads/2026/08/cropped-product-32x32.png'));
$store_url = 'https://store.eproduct.org/';
$checkout_url = home_url('/checkout/');

$lp_media_args = array(
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'post_status' => 'inherit',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
);
$lp_media_query = new WP_Query($lp_media_args);
$lp_media_images = array();
if ($lp_media_query->have_posts()) {
    while ($lp_media_query->have_posts()) {
        $lp_media_query->the_post();
        $mid = get_the_ID();
        $lp_media_images[] = array(
            'id'     => $mid,
            'url'    => wp_get_attachment_url($mid),
            'title'  => get_the_title(),
            'alt'    => get_post_meta($mid, '_wp_attachment_image_alt', true),
            'file'   => basename(get_attached_file($mid))
        );
    }
    wp_reset_postdata();
}
function lp_find_image($keywords, $lp_media_images) {
    $keywords = strtolower($keywords);
    $words = array_filter(explode(' ', $keywords), function($w) { return strlen($w) > 2; });
    $best_url = '';
    $best_score = -1;
    foreach ($lp_media_images as $img) {
        $title_lower = strtolower($img['title']);
        $haystack = $title_lower . ' ' . strtolower($img['alt']) . ' ' . strtolower($img['file']);
        $score = 0;
        foreach ($words as $word) {
            if (strpos($haystack, $word) !== false) {
                $score++;
            }
        }
        if ($title_lower === $keywords) {
            $score += 100;
        }
        if ($score > $best_score) {
            $best_score = $score;
            $best_url = $img['url'];
        }
    }
    return $best_url;
}
?>
<style>
body.eproduct .ep-header,
body.eproduct .ep-mobile-bar,
body.eproduct .ep-footer,
body.eproduct > .site > .ep-main > .ep-section:first-child {
  display: none !important;
}
</style>

<!-- ===== LANDING PAGE STYLES ===== -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
  --lp-bg: #0a0a0f;
  --lp-bg-alt: #111118;
  --lp-bg-card: rgba(255,255,255,0.04);
  --lp-bg-card-hover: rgba(255,255,255,0.07);
  --lp-gold: #d4a843;
  --lp-gold-light: #f0d078;
  --lp-gold-dark: #b08930;
  --lp-gold-gradient: linear-gradient(135deg, #d4a843, #f0d078, #d4a843);
  --lp-white: #ffffff;
  --lp-gray-100: #f0f0f5;
  --lp-gray-200: #c8c8d4;
  --lp-gray-300: #9999aa;
  --lp-gray-400: #6b6b7b;
  --lp-border: rgba(255,255,255,0.08);
  --lp-border-gold: rgba(212,168,67,0.3);
  --lp-radius: 16px;
  --lp-radius-sm: 10px;
  --lp-font-heading: 'Space Grotesk', sans-serif;
  --lp-font-body: 'Inter', sans-serif;
  --lp-container: 1200px;
  --lp-glow: 0 0 60px rgba(212,168,67,0.15);
}

.lp-wrapper {
  font-family: var(--lp-font-body);
  background: var(--lp-bg);
  color: var(--lp-white);
  line-height: 1.6;
  overflow-x: hidden;
  -webkit-font-smoothing: antialiased;
}

.lp-wrapper * { box-sizing: border-box; margin: 0; padding: 0; }

.lp-container {
  max-width: var(--lp-container);
  margin: 0 auto;
  padding: 0 24px;
}

/* ===== NAVIGATION ===== */
.lp-nav {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  padding: 16px 0;
  transition: all 0.3s ease;
  background: transparent;
}

.lp-nav.scrolled {
  background: rgba(10,10,15,0.92);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--lp-border);
  padding: 12px 0;
}

.lp-nav__inner {
  max-width: var(--lp-container);
  margin: 0 auto;
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.lp-nav__brand {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  color: var(--lp-white);
  font-family: var(--lp-font-heading);
  font-weight: 700;
  font-size: 1.2rem;
  letter-spacing: -0.02em;
}

.lp-nav__brand img {
  width: 36px;
  height: 36px;
  border-radius: 8px;
}

.lp-nav__links {
  display: flex;
  align-items: center;
  gap: 32px;
  list-style: none;
}

.lp-nav__links a {
  color: var(--lp-gray-200);
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 500;
  transition: color 0.2s;
}

.lp-nav__links a:hover { color: var(--lp-gold); }

.lp-nav__cta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--lp-gold-gradient);
  color: #0a0a0f !important;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
  text-decoration: none;
  transition: transform 0.2s, box-shadow 0.2s;
}

.lp-nav__cta:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 20px rgba(212,168,67,0.4);
}

.lp-nav__burger {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  padding: 8px;
}

.lp-nav__burger span {
  display: block;
  width: 24px;
  height: 2px;
  background: var(--lp-white);
  margin: 5px 0;
  transition: all 0.3s;
}

/* ===== HERO ===== */
.lp-hero {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  padding: 120px 0 80px;
  overflow: hidden;
  background: radial-gradient(ellipse at 30% 50%, rgba(212,168,67,0.08) 0%, transparent 60%),
              radial-gradient(ellipse at 70% 30%, rgba(212,168,67,0.05) 0%, transparent 50%),
              var(--lp-bg);
}

.lp-hero__glow {
  position: absolute;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(212,168,67,0.12) 0%, transparent 70%);
  top: 10%;
  right: 10%;
  pointer-events: none;
  animation: lp-pulse 6s ease-in-out infinite;
}

@keyframes lp-pulse {
  0%, 100% { opacity: 0.6; transform: scale(1); }
  50% { opacity: 1; transform: scale(1.1); }
}

.lp-hero__inner {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}

.lp-hero__badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(212,168,67,0.1);
  border: 1px solid var(--lp-border-gold);
  color: var(--lp-gold);
  padding: 8px 20px;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-bottom: 24px;
}

.lp-hero__title {
  font-family: var(--lp-font-heading);
  font-size: clamp(2.5rem, 5vw, 4.2rem);
  font-weight: 700;
  line-height: 1.08;
  letter-spacing: -0.03em;
  margin-bottom: 8px;
}

.lp-hero__title .gold {
  background: var(--lp-gold-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.lp-hero__subtitle {
  font-family: var(--lp-font-heading);
  font-size: clamp(1.1rem, 2vw, 1.5rem);
  font-weight: 500;
  color: var(--lp-gray-200);
  margin-bottom: 20px;
}

.lp-hero__desc {
  font-size: 1.05rem;
  color: var(--lp-gray-300);
  line-height: 1.7;
  margin-bottom: 36px;
  max-width: 520px;
}

.lp-hero__ctas {
  display: flex;
  gap: 16px;
  margin-bottom: 40px;
  flex-wrap: wrap;
}

.lp-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 16px 36px;
  border-radius: 10px;
  font-family: var(--lp-font-heading);
  font-weight: 700;
  font-size: 0.95rem;
  text-decoration: none;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: all 0.3s ease;
  cursor: pointer;
  border: none;
}

.lp-btn--primary {
  background: var(--lp-gold-gradient);
  color: #0a0a0f;
  box-shadow: 0 4px 30px rgba(212,168,67,0.3);
}

.lp-btn--primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 40px rgba(212,168,67,0.5);
}

.lp-btn--outline {
  background: transparent;
  color: var(--lp-white);
  border: 1px solid var(--lp-border);
}

.lp-btn--outline:hover {
  border-color: var(--lp-gold);
  color: var(--lp-gold);
}

.lp-hero__trust {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.lp-hero__trust-item {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.82rem;
  color: var(--lp-gray-300);
}

.lp-hero__trust-item svg {
  width: 16px;
  height: 16px;
  color: var(--lp-gold);
  flex-shrink: 0;
}

.lp-hero__visual {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}
.lp-hero__visual img {
  width: 100%;
  max-width: 480px;
  border-radius: 20px;
  box-shadow: var(--lp-glow);
}

.lp-hero__floating-card {
  position: absolute;
  background: rgba(20,20,30,0.85);
  backdrop-filter: blur(12px);
  border: 1px solid var(--lp-border);
  border-radius: 12px;
  padding: 12px 18px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 0.82rem;
  font-weight: 500;
  animation: lp-float 4s ease-in-out infinite;
  box-shadow: 0 8px 32px rgba(0,0,0,0.4);
}

.lp-hero__floating-card:nth-child(2) { animation-delay: -1s; }
.lp-hero__floating-card:nth-child(3) { animation-delay: -2s; }

@keyframes lp-float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

/* ===== TRUST BAR ===== */
.lp-trustbar {
  padding: 60px 0;
  border-top: 1px solid var(--lp-border);
  border-bottom: 1px solid var(--lp-border);
  background: var(--lp-bg-alt);
}

.lp-trustbar__label {
  text-align: center;
  font-size: 0.95rem;
  color: var(--lp-gray-300);
  margin-bottom: 32px;
  font-weight: 500;
}

.lp-trustbar__grid {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 40px;
}

.lp-trustbar__item {
  display: flex;
  align-items: center;
  gap: 12px;
}

.lp-trustbar__icon {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  background: rgba(212,168,67,0.1);
  border: 1px solid var(--lp-border-gold);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--lp-gold);
}

.lp-trustbar__icon svg { width: 22px; height: 22px; }

.lp-trustbar__text {
  font-weight: 600;
  font-size: 0.9rem;
  color: var(--lp-white);
}

/* ===== SECTIONS COMMON ===== */
.lp-section {
  padding: 100px 0;
}

.lp-section--alt {
  background: var(--lp-bg-alt);
}

.lp-section__header {
  text-align: center;
  margin-bottom: 60px;
}

.lp-section__tag {
  display: inline-block;
  background: rgba(212,168,67,0.1);
  border: 1px solid var(--lp-border-gold);
  color: var(--lp-gold);
  padding: 6px 16px;
  border-radius: 50px;
  font-size: 0.72rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 16px;
}

.lp-section__title {
  font-family: var(--lp-font-heading);
  font-size: clamp(1.8rem, 3.5vw, 2.8rem);
  font-weight: 700;
  letter-spacing: -0.03em;
  line-height: 1.15;
  margin-bottom: 16px;
}

.lp-section__title .gold {
  background: var(--lp-gold-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.lp-section__desc {
  font-size: 1.05rem;
  color: var(--lp-gray-300);
  max-width: 640px;
  margin: 0 auto;
  line-height: 1.7;
}

/* ===== WHAT'S INSIDE ===== */
.lp-categories {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 24px;
}

.lp-cat-card {
  background: var(--lp-bg-card);
  border: 1px solid var(--lp-border);
  border-radius: var(--lp-radius);
  padding: 36px 30px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.lp-cat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: var(--lp-gold-gradient);
  opacity: 0;
  transition: opacity 0.3s;
}

.lp-cat-card:hover {
  border-color: var(--lp-border-gold);
  background: var(--lp-bg-card-hover);
  transform: translateY(-4px);
  box-shadow: var(--lp-glow);
}

.lp-cat-card:hover::before { opacity: 1; }

.lp-cat-card__icon {
  width: 52px;
  height: 52px;
  border-radius: 12px;
  background: rgba(212,168,67,0.1);
  border: 1px solid var(--lp-border-gold);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  color: var(--lp-gold);
  font-size: 1.5rem;
}

.lp-cat-card__title {
  font-family: var(--lp-font-heading);
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 10px;
  letter-spacing: -0.01em;
}

.lp-cat-card__desc {
  font-size: 0.9rem;
  color: var(--lp-gray-300);
  margin-bottom: 18px;
  line-height: 1.6;
}

.lp-cat-card__tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.lp-tag {
  display: inline-block;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.08);
  padding: 4px 12px;
  border-radius: 6px;
  font-size: 0.75rem;
  color: var(--lp-gray-200);
  font-weight: 500;
}

/* ===== RESOURCE LIBRARY ===== */
.lp-library__tabs {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 40px;
}

.lp-library__tab {
  padding: 10px 22px;
  border-radius: 8px;
  border: 1px solid var(--lp-border);
  background: transparent;
  color: var(--lp-gray-300);
  font-family: var(--lp-font-body);
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.lp-library__tab:hover,
.lp-library__tab.active {
  background: rgba(212,168,67,0.1);
  border-color: var(--lp-border-gold);
  color: var(--lp-gold);
}

.lp-library__grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 20px;
}

.lp-lib-card {
  background: var(--lp-bg-card);
  border: 1px solid var(--lp-border);
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s;
}

.lp-lib-card:hover {
  transform: translateY(-4px);
  border-color: var(--lp-border-gold);
  box-shadow: var(--lp-glow);
}

.lp-lib-card__img {
  width: 100%;
  aspect-ratio: 4/3;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.lp-lib-card__img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.lp-lib-card__body {
  padding: 14px 16px;
}

.lp-lib-card__cat {
  font-size: 0.7rem;
  color: var(--lp-gold);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-weight: 600;
  margin-bottom: 4px;
}

.lp-lib-card__name {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--lp-white);
}

/* ===== COMPARISON ===== */
.lp-compare {
  display: grid;
  grid-template-columns: 1fr 60px 1fr;
  gap: 0;
  align-items: stretch;
  max-width: 900px;
  margin: 0 auto;
}

.lp-compare__col {
  background: var(--lp-bg-card);
  border: 1px solid var(--lp-border);
  border-radius: var(--lp-radius);
  padding: 40px 32px;
}

.lp-compare__col--old { opacity: 0.6; }
.lp-compare__col--new {
  border-color: var(--lp-border-gold);
  background: rgba(212,168,67,0.04);
}

.lp-compare__vs {
  display: flex;
  align-items: center;
  justify-content: center;
}

.lp-compare__vs span {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: var(--lp-gold-gradient);
  color: #0a0a0f;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 0.85rem;
}

.lp-compare__label {
  font-family: var(--lp-font-heading);
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 24px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.lp-compare__list {
  list-style: none;
}

.lp-compare__list li {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 10px 0;
  font-size: 0.9rem;
  color: var(--lp-gray-200);
}

.lp-compare__list li svg {
  width: 18px;
  height: 18px;
  flex-shrink: 0;
  margin-top: 2px;
}

.lp-compare__list--old li svg { color: var(--lp-gray-400); }
.lp-compare__list--new li svg { color: var(--lp-gold); }

/* ===== AUDIENCE ===== */
.lp-audience {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 24px;
}

.lp-aud-card {
  background: var(--lp-bg-card);
  border: 1px solid var(--lp-border);
  border-radius: var(--lp-radius);
  padding: 36px 28px;
  transition: all 0.3s;
}

.lp-aud-card:hover {
  border-color: var(--lp-border-gold);
  transform: translateY(-3px);
}

.lp-aud-card__icon {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  background: rgba(212,168,67,0.1);
  border: 1px solid var(--lp-border-gold);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--lp-gold);
  margin-bottom: 18px;
}

.lp-aud-card__icon svg { width: 24px; height: 24px; }

.lp-aud-card__title {
  font-family: var(--lp-font-heading);
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.lp-aud-card__desc {
  font-size: 0.9rem;
  color: var(--lp-gray-300);
  line-height: 1.6;
}

/* ===== BENEFITS ===== */
.lp-benefits {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 24px;
}

.lp-ben-card {
  display: flex;
  gap: 20px;
  background: var(--lp-bg-card);
  border: 1px solid var(--lp-border);
  border-radius: var(--lp-radius);
  padding: 32px 28px;
  transition: all 0.3s;
}

.lp-ben-card:hover {
  border-color: var(--lp-border-gold);
}

.lp-ben-card__num {
  font-family: var(--lp-font-heading);
  font-size: 2rem;
  font-weight: 700;
  background: var(--lp-gold-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  line-height: 1;
  flex-shrink: 0;
}

.lp-ben-card__title {
  font-family: var(--lp-font-heading);
  font-size: 1.05rem;
  font-weight: 700;
  margin-bottom: 6px;
}

.lp-ben-card__desc {
  font-size: 0.88rem;
  color: var(--lp-gray-300);
  line-height: 1.6;
}

/* ===== HOW IT WORKS ===== */
.lp-steps {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 40px;
  position: relative;
}

.lp-steps::before {
  content: '';
  position: absolute;
  top: 40px;
  left: 15%;
  right: 15%;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--lp-border-gold), transparent);
}

.lp-step {
  text-align: center;
  position: relative;
}

.lp-step__num {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: var(--lp-bg);
  border: 2px solid var(--lp-border-gold);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 24px;
  font-family: var(--lp-font-heading);
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--lp-gold);
  position: relative;
  z-index: 1;
}

.lp-step__title {
  font-family: var(--lp-font-heading);
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}

.lp-step__desc {
  font-size: 0.9rem;
  color: var(--lp-gray-300);
  line-height: 1.6;
}

/* ===== PRODUCT PREVIEW ===== */
.lp-preview {
  position: relative;
  max-width: 1000px;
  margin: 0 auto;
}

.lp-preview__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.lp-preview__card {
  background: var(--lp-bg-card);
  border: 1px solid var(--lp-border);
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.4s;
}

.lp-preview__card:hover {
  transform: translateY(-6px) scale(1.02);
  border-color: var(--lp-border-gold);
  box-shadow: var(--lp-glow);
}

.lp-preview__card--featured {
  grid-column: span 2;
  grid-row: span 2;
}

.lp-preview__img {
  width: 100%;
  aspect-ratio: 16/10;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.lp-preview__img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.lp-preview__card--featured .lp-preview__img {
  aspect-ratio: 16/9;
}

.lp-preview__label {
  padding: 12px 16px;
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--lp-gray-200);
}

.lp-preview__card--featured .lp-preview__label {
  padding: 8px;
  font-size: 1rem;
}

/* ===== VALUE / OFFER ===== */
.lp-offer {
  text-align: center;
  position: relative;
}

.lp-offer__card {
  max-width: 520px;
  margin: 0 auto;
  background: var(--lp-bg-card);
  border: 2px solid var(--lp-border-gold);
  border-radius: 20px;
  padding: 48px 40px;
  position: relative;
  overflow: hidden;
  box-shadow: var(--lp-glow);
}

.lp-offer__card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: var(--lp-gold-gradient);
}

.lp-offer__regular {
  font-size: 0.9rem;
  color: var(--lp-gray-400);
  margin-bottom: 4px;
  text-decoration: line-through;
}

.lp-offer__price {
  font-family: var(--lp-font-heading);
  font-size: 3rem;
  font-weight: 700;
  background: var(--lp-gold-gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 8px;
}

.lp-offer__note {
  font-size: 0.85rem;
  color: var(--lp-gray-300);
  margin-bottom: 32px;
}

.lp-offer__features {
  list-style: none;
  text-align: left;
  margin-bottom: 32px;
}

.lp-offer__features li {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 0;
  font-size: 0.9rem;
  color: var(--lp-gray-200);
}

.lp-offer__features li svg {
  width: 18px;
  height: 18px;
  color: var(--lp-gold);
  flex-shrink: 0;
}

/* ===== BONUS ===== */
.lp-bonus-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 24px;
}

.lp-bonus-card {
  background: var(--lp-bg-card);
  border: 1px solid var(--lp-border);
  border-radius: var(--lp-radius);
  padding: 32px 28px;
  position: relative;
}

.lp-bonus-card__num {
  font-family: var(--lp-font-heading);
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--lp-gold);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  margin-bottom: 12px;
}

.lp-bonus-card__title {
  font-family: var(--lp-font-heading);
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.lp-bonus-card__desc {
  font-size: 0.88rem;
  color: var(--lp-gray-300);
  line-height: 1.6;
}

/* ===== LICENSE ===== */
.lp-license {
  background: rgba(212,168,67,0.04);
  border: 1px solid var(--lp-border-gold);
  border-radius: var(--lp-radius);
  padding: 40px;
  max-width: 800px;
  margin: 0 auto;
}

.lp-license__title {
  font-family: var(--lp-font-heading);
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 12px;
}

.lp-license__text {
  font-size: 0.92rem;
  color: var(--lp-gray-300);
  line-height: 1.7;
  margin-bottom: 20px;
}

/* ===== TESTIMONIALS ===== */
.lp-testimonials {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 24px;
}

.lp-test-card {
  background: var(--lp-bg-card);
  border: 1px solid var(--lp-border);
  border-radius: var(--lp-radius);
  padding: 32px 28px;
}

.lp-test-card__stars {
  display: flex;
  gap: 4px;
  margin-bottom: 16px;
  color: var(--lp-gold);
}

.lp-test-card__stars svg { width: 18px; height: 18px; }

.lp-test-card__text {
  font-size: 0.92rem;
  color: var(--lp-gray-200);
  line-height: 1.7;
  margin-bottom: 20px;
  font-style: italic;
}

.lp-test-card__author {
  display: flex;
  align-items: center;
  gap: 12px;
}

.lp-test-card__avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: rgba(212,168,67,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.85rem;
  color: var(--lp-gold);
}

.lp-test-card__name {
  font-weight: 600;
  font-size: 0.9rem;
}

.lp-test-card__role {
  font-size: 0.78rem;
  color: var(--lp-gray-400);
}

/* ===== FAQ ===== */
.lp-faq {
  max-width: 800px;
  margin: 0 auto;
}

.lp-faq__item {
  border: 1px solid var(--lp-border);
  border-radius: 12px;
  margin-bottom: 12px;
  overflow: hidden;
  transition: border-color 0.3s;
}

.lp-faq__item.active {
  border-color: var(--lp-border-gold);
}

.lp-faq__q {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  background: var(--lp-bg-card);
  border: none;
  color: var(--lp-white);
  font-family: var(--lp-font-heading);
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  text-align: left;
}

.lp-faq__q:hover { background: var(--lp-bg-card-hover); }

.lp-faq__q svg {
  width: 20px;
  height: 20px;
  color: var(--lp-gold);
  transition: transform 0.3s;
  flex-shrink: 0;
}

.lp-faq__item.active .lp-faq__q svg {
  transform: rotate(180deg);
}

.lp-faq__a {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease, padding 0.3s ease;
}

.lp-faq__item.active .lp-faq__a {
  max-height: 300px;
}

.lp-faq__a-inner {
  padding: 0 24px 20px;
  font-size: 0.9rem;
  color: var(--lp-gray-300);
  line-height: 1.7;
}

/* ===== FINAL CTA ===== */
.lp-final-cta {
  position: relative;
  padding: 120px 0;
  text-align: center;
  overflow: hidden;
  background: radial-gradient(ellipse at 50% 50%, rgba(212,168,67,0.1) 0%, transparent 60%),
              var(--lp-bg);
}

.lp-final-cta__glow {
  position: absolute;
  width: 600px;
  height: 400px;
  background: radial-gradient(circle, rgba(212,168,67,0.15) 0%, transparent 70%);
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none;
}

.lp-final-cta__title {
  font-family: var(--lp-font-heading);
  font-size: clamp(2rem, 4vw, 3.2rem);
  font-weight: 700;
  line-height: 1.1;
  letter-spacing: -0.03em;
  margin-bottom: 20px;
  position: relative;
  z-index: 1;
}

.lp-final-cta__desc {
  font-size: 1.1rem;
  color: var(--lp-gray-300);
  max-width: 560px;
  margin: 0 auto 40px;
  position: relative;
  z-index: 1;
}

.lp-final-cta__note {
  font-size: 0.85rem;
  color: var(--lp-gray-400);
  margin-top: 20px;
  position: relative;
  z-index: 1;
}

/* ===== FOOTER (LP) ===== */
.lp-footer {
  padding: 60px 0 30px;
  border-top: 1px solid var(--lp-border);
  background: var(--lp-bg-alt);
}

.lp-footer__grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 40px;
  margin-bottom: 40px;
}

.lp-footer__brand-name {
  font-family: var(--lp-font-heading);
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 12px;
}

.lp-footer__brand-desc {
  font-size: 0.88rem;
  color: var(--lp-gray-400);
  line-height: 1.6;
  max-width: 300px;
}

.lp-footer__col h4 {
  font-family: var(--lp-font-heading);
  font-size: 0.85rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  margin-bottom: 16px;
  color: var(--lp-gray-200);
}

.lp-footer__col a {
  display: block;
  color: var(--lp-gray-400);
  text-decoration: none;
  font-size: 0.85rem;
  padding: 4px 0;
  transition: color 0.2s;
}

.lp-footer__col a:hover { color: var(--lp-gold); }

.lp-footer__bar {
  border-top: 1px solid var(--lp-border);
  padding-top: 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.82rem;
  color: var(--lp-gray-400);
}

.lp-footer__bar-links {
  display: flex;
  gap: 20px;
}

.lp-footer__bar-links a {
  color: var(--lp-gray-400);
  text-decoration: none;
  transition: color 0.2s;
}

.lp-footer__bar-links a:hover { color: var(--lp-gold); }

/* ===== STICKY MOBILE CTA ===== */
.lp-sticky-cta {
  display: none;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 999;
  padding: 12px 20px;
  background: rgba(10,10,15,0.95);
  backdrop-filter: blur(12px);
  border-top: 1px solid var(--lp-border-gold);
}

.lp-sticky-cta .lp-btn {
  width: 100%;
  padding: 14px;
  font-size: 0.9rem;
}

/* ===== SCROLL ANIMATIONS ===== */
.lp-reveal {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.lp-reveal.visible {
  opacity: 1;
  transform: translateY(0);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .lp-hero__inner { grid-template-columns: 1fr; text-align: center; }
  .lp-hero__desc { margin: 0 auto 36px; }
  .lp-hero__ctas { justify-content: center; }
  .lp-hero__trust { justify-content: center; }
  .lp-hero__visual { margin-top: 40px; }
  .lp-compare { grid-template-columns: 1fr; gap: 20px; }
  .lp-compare__vs { display: none; }
  .lp-footer__grid { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 768px) {
  .lp-nav__links { display: none; }
  .lp-nav__burger { display: block; }
  .lp-nav__links.open {
    display: flex;
    flex-direction: column;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: rgba(10,10,15,0.98);
    backdrop-filter: blur(20px);
    padding: 20px 24px;
    gap: 16px;
    border-bottom: 1px solid var(--lp-border);
  }
  .lp-hero { padding: 100px 0 60px; min-height: auto; }
  .lp-hero__floating-card { display: none; }
  .lp-steps { grid-template-columns: 1fr; gap: 30px; }
  .lp-steps::before { display: none; }
  .lp-preview__grid { grid-template-columns: 1fr; }
  .lp-preview__card--featured { grid-column: span 1; grid-row: span 1; }
  .lp-footer__grid { grid-template-columns: 1fr; }
  .lp-footer__bar { flex-direction: column; gap: 12px; text-align: center; }
  .lp-sticky-cta { display: block; }
  .lp-section { padding: 60px 0; }
}

@media (max-width: 480px) {
  .lp-categories { grid-template-columns: 1fr; }
  .lp-audience { grid-template-columns: 1fr; }
  .lp-benefits { grid-template-columns: 1fr; }
  .lp-testimonials { grid-template-columns: 1fr; }
  .lp-bonus-grid { grid-template-columns: 1fr; }
  .lp-offer__card { padding: 32px 24px; }
  .lp-trustbar__grid { gap: 20px; }
}
</style>

<!-- ===== LANDING PAGE CONTENT ===== -->
<div class="lp-wrapper">

  <!-- NAVIGATION -->
  <nav class="lp-nav" id="lpNav">
    <div class="lp-nav__inner">
      <a class="lp-nav__brand" href="<?php echo esc_url(home_url('/')); ?>">
        <img src="<?php echo esc_url($ep_icon); ?>" alt="eProduct Digital" width="36" height="36">
        <span>ePRODUCT DIGITAL</span>
      </a>
      <ul class="lp-nav__links" id="lpNavLinks">
        <li><a href="#home">Home</a></li>
        <li><a href="#whats-included">What's Included</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#categories">Categories</a></li>
        <li><a href="#faq">FAQ</a></li>
        <li><a class="lp-nav__cta" href="#offer">Get Instant Access</a></li>
      </ul>
      <button class="lp-nav__burger" id="lpBurger" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  <!-- HERO -->
  <section class="lp-hero" id="home">
    <div class="lp-hero__glow"></div>
    <div class="lp-container">
      <div class="lp-hero__inner">
        <div class="lp-hero__content">
          <div class="lp-hero__badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            THE ULTIMATE DIGITAL RESOURCE COLLECTION
          </div>
          <h1 class="lp-hero__title">
            <span class="gold">100+ MILLION</span><br>
            DIGITAL RESOURCES
          </h1>
          <p class="lp-hero__subtitle">One Powerful Collection. Endless Possibilities.</p>
          <p class="lp-hero__desc">Get access to a massive collection of digital resources created to help you design, create, market, launch, and grow faster — all in one powerful collection.</p>
          <div class="lp-hero__ctas">
            <a href="#offer" class="lp-btn lp-btn--primary">UNLOCK EVERYTHING</a>
            <a href="#whats-included" class="lp-btn lp-btn--outline">EXPLORE WHAT'S INCLUDED</a>
          </div>
          <div class="lp-hero__trust">
            <span class="lp-hero__trust-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
              Instant Access
            </span>
            <span class="lp-hero__trust-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
              Lifetime Access
            </span>
            <span class="lp-hero__trust-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
              Commercial Flexibility
            </span>
            <span class="lp-hero__trust-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
              Secure Checkout
            </span>
            <span class="lp-hero__trust-item">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
              Premium Quality
            </span>
          </div>
        </div>
        <div class="lp-hero__visual">
          <img src="<?php echo esc_url(lp_find_image('Hero Section: Digital Asset Vault (Right Column)', $lp_media_images)); ?>" alt="100+ Million Digital Resources Collection" style="width:100%;height:100%;border-radius:16px;object-fit:cover;">
          <div class="lp-hero__floating-card" style="top:10%;left:-5%;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#d4a843" stroke-width="2" width="20" height="20"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
            <span>Design Templates</span>
          </div>
          <div class="lp-hero__floating-card" style="bottom:15%;right:-5%;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#d4a843" stroke-width="2" width="20" height="20"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <span>AI Prompts</span>
          </div>
          <div class="lp-hero__floating-card" style="top:60%;left:-8%;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#d4a843" stroke-width="2" width="20" height="20"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
            <span>Video Resources</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- TRUST BAR -->
  <section class="lp-trustbar">
    <div class="lp-container">
      <p class="lp-trustbar__label">Everything you need to create more, faster.</p>
      <div class="lp-trustbar__grid">
        <div class="lp-trustbar__item">
          <div class="lp-trustbar__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          </div>
          <span class="lp-trustbar__text">Instant Access</span>
        </div>
        <div class="lp-trustbar__item">
          <div class="lp-trustbar__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <span class="lp-trustbar__text">Lifetime Access</span>
        </div>
        <div class="lp-trustbar__item">
          <div class="lp-trustbar__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          </div>
          <span class="lp-trustbar__text">Commercial Use</span>
        </div>
        <div class="lp-trustbar__item">
          <div class="lp-trustbar__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          </div>
          <span class="lp-trustbar__text">Premium Collection</span>
        </div>
        <div class="lp-trustbar__item">
          <div class="lp-trustbar__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </div>
          <span class="lp-trustbar__text">Secure Checkout</span>
        </div>
      </div>
    </div>
  </section>

  <!-- WHAT'S INSIDE -->
  <section class="lp-section" id="whats-included">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">WHAT'S INSIDE</span>
        <h2 class="lp-section__title">ONE COLLECTION. <span class="gold">ENDLESS POSSIBILITIES.</span></h2>
        <p class="lp-section__desc">Stop searching across dozens of websites for the resources you need. Bring your creative, business, marketing, and productivity resources together in one powerful collection.</p>
      </div>
      <div class="lp-categories">
        <!-- Design Templates -->
        <div class="lp-cat-card lp-reveal">
          <div class="lp-cat-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
          </div>
          <h3 class="lp-cat-card__title">DESIGN TEMPLATES</h3>
          <p class="lp-cat-card__desc">Ready-to-use templates for creating professional designs faster.</p>
          <div class="lp-cat-card__tags">
            <span class="lp-tag">Canva Templates</span>
            <span class="lp-tag">Social Media</span>
            <span class="lp-tag">Presentations</span>
            <span class="lp-tag">Marketing</span>
            <span class="lp-tag">Business</span>
          </div>
        </div>
        <!-- AI Prompts -->
        <div class="lp-cat-card lp-reveal">
          <div class="lp-cat-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          </div>
          <h3 class="lp-cat-card__title">AI PROMPTS</h3>
          <p class="lp-cat-card__desc">Prompt resources designed to help you get more from modern AI tools.</p>
          <div class="lp-cat-card__tags">
            <span class="lp-tag">ChatGPT Prompts</span>
            <span class="lp-tag">Midjourney</span>
            <span class="lp-tag">Business</span>
            <span class="lp-tag">Marketing</span>
            <span class="lp-tag">Productivity</span>
          </div>
        </div>
        <!-- Graphics -->
        <div class="lp-cat-card lp-reveal">
          <div class="lp-cat-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          </div>
          <h3 class="lp-cat-card__title">GRAPHICS</h3>
          <p class="lp-cat-card__desc">Creative visual assets for websites, social media, branding, and design projects.</p>
          <div class="lp-cat-card__tags">
            <span class="lp-tag">Icons</span>
            <span class="lp-tag">Illustrations</span>
            <span class="lp-tag">Mockups</span>
            <span class="lp-tag">Backgrounds</span>
            <span class="lp-tag">Design Assets</span>
          </div>
        </div>
        <!-- Videos -->
        <div class="lp-cat-card lp-reveal">
          <div class="lp-cat-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
          </div>
          <h3 class="lp-cat-card__title">VIDEOS</h3>
          <p class="lp-cat-card__desc">Video resources for creators, marketers, presentations, and digital projects.</p>
          <div class="lp-cat-card__tags">
            <span class="lp-tag">Stock Videos</span>
            <span class="lp-tag">Video Elements</span>
            <span class="lp-tag">Transitions</span>
            <span class="lp-tag">Overlays</span>
            <span class="lp-tag">Motion Graphics</span>
          </div>
        </div>
        <!-- eBooks -->
        <div class="lp-cat-card lp-reveal">
          <div class="lp-cat-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          </div>
          <h3 class="lp-cat-card__title">EBOOKS & KNOWLEDGE</h3>
          <p class="lp-cat-card__desc">Digital reading and educational resources across multiple categories.</p>
          <div class="lp-cat-card__tags">
            <span class="lp-tag">eBooks</span>
            <span class="lp-tag">Guides</span>
            <span class="lp-tag">Workbooks</span>
            <span class="lp-tag">Stories</span>
            <span class="lp-tag">Reports</span>
          </div>
        </div>
        <!-- Business -->
        <div class="lp-cat-card lp-reveal">
          <div class="lp-cat-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          </div>
          <h3 class="lp-cat-card__title">BUSINESS RESOURCES</h3>
          <p class="lp-cat-card__desc">Useful resources for entrepreneurs, businesses, freelancers, and professionals.</p>
          <div class="lp-cat-card__tags">
            <span class="lp-tag">Templates</span>
            <span class="lp-tag">Plans & Reports</span>
            <span class="lp-tag">Spreadsheets</span>
            <span class="lp-tag">Presentations</span>
            <span class="lp-tag">Documents</span>
          </div>
        </div>
        <!-- Creative -->
        <div class="lp-cat-card lp-reveal" style="grid-column: span 1;">
          <div class="lp-cat-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>
          </div>
          <h3 class="lp-cat-card__title">CREATIVE RESOURCES</h3>
          <p class="lp-cat-card__desc">Fonts, photos, textures, 3D assets, and creative kits for every project.</p>
          <div class="lp-cat-card__tags">
            <span class="lp-tag">Fonts</span>
            <span class="lp-tag">Photos</span>
            <span class="lp-tag">Textures</span>
            <span class="lp-tag">3D Assets</span>
            <span class="lp-tag">Creative Kits</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- RESOURCE LIBRARY SHOWCASE -->
  <section class="lp-section lp-section--alt" id="features">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">RESOURCE LIBRARY</span>
        <h2 class="lp-section__title">YOUR DIGITAL TOOLBOX, <span class="gold">SUPERCHARGED</span></h2>
      </div>
      <div class="lp-library__tabs lp-reveal">
        <button class="lp-library__tab active" data-filter="all">All</button>
        <button class="lp-library__tab" data-filter="templates">Templates</button>
        <button class="lp-library__tab" data-filter="graphics">Graphics</button>
        <button class="lp-library__tab" data-filter="ai">AI Prompts</button>
        <button class="lp-library__tab" data-filter="videos">Videos</button>
        <button class="lp-library__tab" data-filter="ebooks">eBooks</button>
        <button class="lp-library__tab" data-filter="business">Business</button>
        <button class="lp-library__tab" data-filter="creative">Creative</button>
      </div>
      <div class="lp-library__grid lp-reveal">
        <div class="lp-lib-card" data-cat="templates">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('Social Media Pack', $lp_media_images)); ?>" alt="Social Media Pack"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">Templates</div>
            <div class="lp-lib-card__name">Social Media Pack</div>
          </div>
        </div>
        <div class="lp-lib-card" data-cat="ai">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('ChatGPT Bundle', $lp_media_images)); ?>" alt="ChatGPT Bundle"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">AI Prompts</div>
            <div class="lp-lib-card__name">ChatGPT Bundle</div>
          </div>
        </div>
        <div class="lp-lib-card" data-cat="graphics">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('Icon Library', $lp_media_images)); ?>" alt="Icon Library"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">Graphics</div>
            <div class="lp-lib-card__name">Icon Library</div>
          </div>
        </div>
        <div class="lp-lib-card" data-cat="videos">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('Video Resources', $lp_media_images)); ?>" alt="Stock Footage"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">Videos</div>
            <div class="lp-lib-card__name">Stock Footage</div>
          </div>
        </div>
        <div class="lp-lib-card" data-cat="ebooks">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('eBooks & Action Guides', $lp_media_images)); ?>" alt="Business Guide"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">eBooks</div>
            <div class="lp-lib-card__name">Business Guide</div>
          </div>
        </div>
        <div class="lp-lib-card" data-cat="templates">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('Presentation Kit', $lp_media_images)); ?>" alt="Presentation Kit"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">Templates</div>
            <div class="lp-lib-card__name">Presentation Kit</div>
          </div>
        </div>
        <div class="lp-lib-card" data-cat="business">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('Finance Templates', $lp_media_images)); ?>" alt="Finance Templates"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">Business</div>
            <div class="lp-lib-card__name">Finance Templates</div>
          </div>
        </div>
        <div class="lp-lib-card" data-cat="creative">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('Font Collection', $lp_media_images)); ?>" alt="Font Collection"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">Creative</div>
            <div class="lp-lib-card__name">Font Collection</div>
          </div>
        </div>
        <div class="lp-lib-card" data-cat="graphics">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('Mockup Bundle', $lp_media_images)); ?>" alt="Mockup Bundle"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">Graphics</div>
            <div class="lp-lib-card__name">Mockup Bundle</div>
          </div>
        </div>
        <div class="lp-lib-card" data-cat="ai">
          <div class="lp-lib-card__img"><img src="<?php echo esc_url(lp_find_image('Midjourney Pack', $lp_media_images)); ?>" alt="Midjourney Pack"></div>
          <div class="lp-lib-card__body">
            <div class="lp-lib-card__cat">AI Prompts</div>
            <div class="lp-lib-card__name">Midjourney Pack</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- WHY THIS COLLECTION -->
  <section class="lp-section">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">WHY THIS COLLECTION</span>
        <h2 class="lp-section__title">WHY SETTLE FOR <span class="gold">ONE RESOURCE?</span></h2>
      </div>
      <div class="lp-compare lp-reveal">
        <div class="lp-compare__col lp-compare__col--old">
          <div class="lp-compare__label">Traditional Approach</div>
          <ul class="lp-compare__list lp-compare__list--old">
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Search multiple websites</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Buy individual resources</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Waste time finding the right file</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Manage multiple subscriptions</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Limited resource variety</li>
          </ul>
        </div>
        <div class="lp-compare__vs"><span>VS</span></div>
        <div class="lp-compare__col lp-compare__col--new">
          <div class="lp-compare__label" style="color:var(--lp-gold);">ePRODUCT DIGITAL</div>
          <ul class="lp-compare__list lp-compare__list--new">
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> One powerful collection</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Huge variety of digital resources</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Instant access</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Faster workflow</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Multiple use cases</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Better convenience</li>
            <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Commercial flexibility</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- WHO IS IT FOR -->
  <section class="lp-section lp-section--alt" id="categories">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">AUDIENCE</span>
        <h2 class="lp-section__title">BUILT FOR PEOPLE <span class="gold">WHO CREATE</span></h2>
      </div>
      <div class="lp-audience">
        <div class="lp-aud-card lp-reveal">
          <div class="lp-aud-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
          </div>
          <h3 class="lp-aud-card__title">CREATORS</h3>
          <p class="lp-aud-card__desc">Create social content, videos, graphics, and digital projects faster.</p>
        </div>
        <div class="lp-aud-card lp-reveal">
          <div class="lp-aud-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          </div>
          <h3 class="lp-aud-card__title">DESIGNERS</h3>
          <p class="lp-aud-card__desc">Find templates, graphics, mockups, fonts, and visual assets.</p>
        </div>
        <div class="lp-aud-card lp-reveal">
          <div class="lp-aud-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <h3 class="lp-aud-card__title">MARKETERS</h3>
          <p class="lp-aud-card__desc">Access resources for campaigns, content, presentations, and promotion.</p>
        </div>
        <div class="lp-aud-card lp-reveal">
          <div class="lp-aud-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          </div>
          <h3 class="lp-aud-card__title">BUSINESS OWNERS</h3>
          <p class="lp-aud-card__desc">Use professional business resources to organize and grow your operations.</p>
        </div>
        <div class="lp-aud-card lp-reveal">
          <div class="lp-aud-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
          </div>
          <h3 class="lp-aud-card__title">ENTREPRENEURS</h3>
          <p class="lp-aud-card__desc">Speed up planning, branding, content creation, and launching.</p>
        </div>
        <div class="lp-aud-card lp-reveal">
          <div class="lp-aud-card__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
          </div>
          <h3 class="lp-aud-card__title">STUDENTS & PROFESSIONALS</h3>
          <p class="lp-aud-card__desc">Access educational, productivity, presentation, and knowledge resources.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- KEY BENEFITS -->
  <section class="lp-section">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">BENEFITS</span>
        <h2 class="lp-section__title">CREATE <span class="gold">WITHOUT LIMITS</span></h2>
      </div>
      <div class="lp-benefits">
        <div class="lp-ben-card lp-reveal">
          <div class="lp-ben-card__num">01</div>
          <div>
            <h3 class="lp-ben-card__title">SAVE TIME</h3>
            <p class="lp-ben-card__desc">Spend less time searching and more time creating.</p>
          </div>
        </div>
        <div class="lp-ben-card lp-reveal">
          <div class="lp-ben-card__num">02</div>
          <div>
            <h3 class="lp-ben-card__title">HUGE VARIETY</h3>
            <p class="lp-ben-card__desc">Explore resources across creative, business, AI, marketing, and productivity categories.</p>
          </div>
        </div>
        <div class="lp-ben-card lp-reveal">
          <div class="lp-ben-card__num">03</div>
          <div>
            <h3 class="lp-ben-card__title">INSTANT ACCESS</h3>
            <p class="lp-ben-card__desc">Get access to your digital collection immediately after purchase.</p>
          </div>
        </div>
        <div class="lp-ben-card lp-reveal">
          <div class="lp-ben-card__num">04</div>
          <div>
            <h3 class="lp-ben-card__title">LIFETIME ACCESS</h3>
            <p class="lp-ben-card__desc">Access your collection for a lifetime.</p>
          </div>
        </div>
        <div class="lp-ben-card lp-reveal">
          <div class="lp-ben-card__num">05</div>
          <div>
            <h3 class="lp-ben-card__title">COMMERCIAL FLEXIBILITY</h3>
            <p class="lp-ben-card__desc">Usage rights depend on the individual resource/license.</p>
          </div>
        </div>
        <div class="lp-ben-card lp-reveal">
          <div class="lp-ben-card__num">06</div>
          <div>
            <h3 class="lp-ben-card__title">PREMIUM QUALITY</h3>
            <p class="lp-ben-card__desc">Professionally presented and organized digital assets.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="lp-section lp-section--alt">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">HOW IT WORKS</span>
        <h2 class="lp-section__title">GET STARTED IN <span class="gold">3 SIMPLE STEPS</span></h2>
      </div>
      <div class="lp-steps lp-reveal">
        <div class="lp-step">
          <div class="lp-step__num">1</div>
          <h3 class="lp-step__title">GET ACCESS</h3>
          <p class="lp-step__desc">Choose the product and complete checkout.</p>
        </div>
        <div class="lp-step">
          <div class="lp-step__num">2</div>
          <h3 class="lp-step__title">ACCESS YOUR COLLECTION</h3>
          <p class="lp-step__desc">Receive access to your digital resources.</p>
        </div>
        <div class="lp-step">
          <div class="lp-step__num">3</div>
          <h3 class="lp-step__title">CREATE & USE</h3>
          <p class="lp-step__desc">Start using the resources for your creative and business projects according to their applicable licenses.</p>
        </div>
      </div>
      <div style="text-align:center;margin-top:48px;" class="lp-reveal">
        <a href="#offer" class="lp-btn lp-btn--primary">GET INSTANT ACCESS</a>
      </div>
    </div>
  </section>

  <!-- PRODUCT PREVIEW -->
  <section class="lp-section">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">INSIDE THE COLLECTION</span>
        <h2 class="lp-section__title">A PREVIEW OF <span class="gold">WHAT'S INSIDE</span></h2>
      </div>
      <div class="lp-preview lp-reveal">
        <div class="lp-preview__grid">
          <div class="lp-preview__card lp-preview__card--featured">
            <div class="lp-preview__img"><img src="<?php echo esc_url(lp_find_image('Design Templates Collection (Featured Card)', $lp_media_images)); ?>" alt="Design Templates Collection"></div>
            <div class="lp-preview__label">Design Templates Collection</div>
          </div>
          <div class="lp-preview__card">
            <div class="lp-preview__img"><img src="<?php echo esc_url(lp_find_image('AI Prompts Bundle', $lp_media_images)); ?>" alt="AI Prompts Bundle"></div>
            <div class="lp-preview__label">AI Prompts Bundle</div>
          </div>
          <div class="lp-preview__card">
            <div class="lp-preview__img"><img src="<?php echo esc_url(lp_find_image('Graphics & 3D Mockups', $lp_media_images)); ?>" alt="Graphics & Mockups"></div>
            <div class="lp-preview__label">Graphics & Mockups</div>
          </div>
          <div class="lp-preview__card">
            <div class="lp-preview__img"><img src="<?php echo esc_url(lp_find_image('Video Resources', $lp_media_images)); ?>" alt="Video Resources"></div>
            <div class="lp-preview__label">Video Resources</div>
          </div>
          <div class="lp-preview__card">
            <div class="lp-preview__img"><img src="<?php echo esc_url(lp_find_image('eBooks & Action Guides', $lp_media_images)); ?>" alt="eBooks & Guides"></div>
            <div class="lp-preview__label">eBooks & Guides</div>
          </div>
          <div class="lp-preview__card">
            <div class="lp-preview__img"><img src="<?php echo esc_url(lp_find_image('Business Documents & Templates', $lp_media_images)); ?>" alt="Business Documents"></div>
            <div class="lp-preview__label">Business Documents</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- VALUE / OFFER -->
  <section class="lp-section lp-section--alt" id="offer">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">SPECIAL OFFER</span>
        <h2 class="lp-section__title">UNLOCK <span class="gold">THE COMPLETE COLLECTION</span></h2>
        <p class="lp-section__desc">Everything you need to create, design, market, learn, and build — in one powerful digital collection.</p>
      </div>
      <div class="lp-offer__card lp-reveal">
        <div class="lp-offer__regular">REGULAR VALUE: [REGULAR PRICE]</div>
        <div class="lp-offer__price">[SALE PRICE]</div>
        <div class="lp-offer__note">One-time payment. No subscriptions.</div>
        <ul class="lp-offer__features">
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Instant Digital Access</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Secure Checkout</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Lifetime Access</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Commercial Flexibility</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Premium Collection</li>
        </ul>
        <a href="<?php echo esc_url($checkout_url); ?>" class="lp-btn lp-btn--primary" style="width:100%;">UNLOCK EVERYTHING</a>
      </div>
    </div>
  </section>

  <!-- BONUS -->
  <section class="lp-section">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">BONUSES</span>
        <h2 class="lp-section__title">GET EVEN <span class="gold">MORE VALUE</span></h2>
      </div>
      <div class="lp-bonus-grid">
        <div class="lp-bonus-card lp-reveal">
          <div class="lp-bonus-card__num">BONUS #1</div>
          <h3 class="lp-bonus-card__title">[Bonus Name]</h3>
          <p class="lp-bonus-card__desc">[Bonus Description]</p>
        </div>
        <div class="lp-bonus-card lp-reveal">
          <div class="lp-bonus-card__num">BONUS #2</div>
          <h3 class="lp-bonus-card__title">[Bonus Name]</h3>
          <p class="lp-bonus-card__desc">[Bonus Description]</p>
        </div>
        <div class="lp-bonus-card lp-reveal">
          <div class="lp-bonus-card__num">BONUS #3</div>
          <h3 class="lp-bonus-card__title">[Bonus Name]</h3>
          <p class="lp-bonus-card__desc">[Bonus Description]</p>
        </div>
      </div>
    </div>
  </section>

  <!-- LICENSE -->
  <section class="lp-section lp-section--alt">
    <div class="lp-container">
      <div class="lp-license lp-reveal">
        <h3 class="lp-license__title">USE YOUR RESOURCES WITH CONFIDENCE</h3>
        <p class="lp-license__text">Usage rights vary by resource. Always review the applicable license or usage terms before using an asset commercially, redistributing it, reselling it, or incorporating it into products for others.</p>
        <a href="<?php echo esc_url(home_url('/terms/')); ?>" class="lp-btn lp-btn--outline">VIEW LICENSE TERMS</a>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section class="lp-section">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">TESTIMONIALS</span>
        <h2 class="lp-section__title">CREATORS ARE <span class="gold">BUILDING MORE</span></h2>
      </div>
      <div class="lp-testimonials">
        <div class="lp-test-card lp-reveal">
          <div class="lp-test-card__stars">
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
          <p class="lp-test-card__text">"[Customer testimonial placeholder — insert genuine review here]"</p>
          <div class="lp-test-card__author">
            <div class="lp-test-card__avatar">JD</div>
            <div>
              <div class="lp-test-card__name">[Customer Name]</div>
              <div class="lp-test-card__role">[Role / Company]</div>
            </div>
          </div>
        </div>
        <div class="lp-test-card lp-reveal">
          <div class="lp-test-card__stars">
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
          <p class="lp-test-card__text">"[Customer testimonial placeholder — insert genuine review here]"</p>
          <div class="lp-test-card__author">
            <div class="lp-test-card__avatar">SK</div>
            <div>
              <div class="lp-test-card__name">[Customer Name]</div>
              <div class="lp-test-card__role">[Role / Company]</div>
            </div>
          </div>
        </div>
        <div class="lp-test-card lp-reveal">
          <div class="lp-test-card__stars">
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            <svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </div>
          <p class="lp-test-card__text">"[Customer testimonial placeholder — insert genuine review here]"</p>
          <div class="lp-test-card__author">
            <div class="lp-test-card__avatar">AR</div>
            <div>
              <div class="lp-test-card__name">[Customer Name]</div>
              <div class="lp-test-card__role">[Role / Company]</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="lp-section lp-section--alt" id="faq">
    <div class="lp-container">
      <div class="lp-section__header lp-reveal">
        <span class="lp-section__tag">FAQ</span>
        <h2 class="lp-section__title">FREQUENTLY ASKED <span class="gold">QUESTIONS</span></h2>
      </div>
      <div class="lp-faq lp-reveal">
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            What exactly is included?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">The collection includes a massive library of digital resources covering design templates, AI prompts, graphics, videos, eBooks, business resources, and creative assets. [Edit with exact details]</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            Who is this collection for?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">This collection is designed for creators, designers, marketers, business owners, entrepreneurs, students, and professionals who need access to a wide variety of digital resources.</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            How do I receive access?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">After completing your purchase, you will receive instant digital access to the collection. [Edit with exact delivery method]</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            How quickly will I receive access?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">Access is delivered instantly after payment confirmation. [Edit if different]</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            Can I use the resources commercially?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">Usage rights vary by resource. Always review the applicable license or usage terms before using an asset commercially. [Edit with exact commercial terms]</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            Can I resell or redistribute the resources?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">Reselling or redistributing the resources is not permitted unless explicitly stated in the license terms. [Edit with exact policy]</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            Do all resources have the same license?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">No, different resources may have different license terms. Please review the specific license for each resource before use. [Edit as needed]</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            Do I receive lifetime access?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">[Edit with exact lifetime access policy]</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            Is this a physical product?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">No, this is a digital product. All resources are delivered digitally — no physical items will be shipped.</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            What payment methods are supported?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">We support major payment methods including credit/debit cards, PayPal, and other secure payment options. [Edit with exact methods]</div></div>
        </div>
        <div class="lp-faq__item">
          <button class="lp-faq__q">
            How can I contact support?
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="lp-faq__a"><div class="lp-faq__a-inner">You can reach our support team at <a href="mailto:support@eproduct.org" style="color:var(--lp-gold);">support@eproduct.org</a>. We're here to help!</div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="lp-final-cta">
    <div class="lp-final-cta__glow"></div>
    <div class="lp-container lp-reveal">
      <h2 class="lp-final-cta__title">STOP SEARCHING.<br><span class="gold">START CREATING.</span></h2>
      <p class="lp-final-cta__desc">One powerful collection. Thousands of possibilities. Your next project starts here.</p>
      <a href="#offer" class="lp-btn lp-btn--primary">UNLOCK EVERYTHING</a>
      <p class="lp-final-cta__note">Instant digital access &bull; Secure checkout</p>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="lp-footer">
    <div class="lp-container">
      <div class="lp-footer__grid">
        <div>
          <div class="lp-footer__brand-name">ePRODUCT DIGITAL</div>
          <p class="lp-footer__brand-desc">Premium digital resources for creators, professionals, entrepreneurs, and businesses.</p>
        </div>
        <div class="lp-footer__col">
          <h4>Product</h4>
          <a href="#whats-included">What's Included</a>
          <a href="#categories">Categories</a>
          <a href="#features">Features</a>
          <a href="#faq">FAQ</a>
        </div>
        <div class="lp-footer__col">
          <h4>Company</h4>
          <a href="<?php echo esc_url(home_url('/about/')); ?>">About Us</a>
          <a href="mailto:support@eproduct.org">Contact</a>
          <a href="mailto:support@eproduct.org">Support</a>
        </div>
        <div class="lp-footer__col">
          <h4>Legal</h4>
          <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy Policy</a>
          <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms &amp; Conditions</a>
          <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Refund Policy</a>
          <a href="<?php echo esc_url(home_url('/terms/')); ?>">License Terms</a>
        </div>
      </div>
      <div class="lp-footer__bar">
        <span>&copy; 2026 ePRODUCT DIGITAL. All rights reserved.</span>
        <div class="lp-footer__bar-links">
          <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms of Use</a>
          <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- STICKY MOBILE CTA -->
  <div class="lp-sticky-cta">
    <a href="#offer" class="lp-btn lp-btn--primary">UNLOCK EVERYTHING</a>
  </div>

</div><!-- .lp-wrapper -->

<!-- ===== LANDING PAGE JS ===== -->
<script>
(function(){
  /* Sticky nav */
  var nav = document.getElementById('lpNav');
  window.addEventListener('scroll', function(){
    nav.classList.toggle('scrolled', window.scrollY > 60);
  });

  /* Mobile burger */
  var burger = document.getElementById('lpBurger');
  var links = document.getElementById('lpNavLinks');
  burger.addEventListener('click', function(){
    links.classList.toggle('open');
  });

  /* Scroll reveal */
  var reveals = document.querySelectorAll('.lp-reveal');
  var observer = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){
        e.target.classList.add('visible');
        observer.unobserve(e.target);
      }
    });
  }, {threshold: 0.1, rootMargin: '0px 0px -40px 0px'});
  reveals.forEach(function(el){ observer.observe(el); });

  /* Smooth scroll */
  document.querySelectorAll('a[href^="#"]').forEach(function(a){
    a.addEventListener('click', function(e){
      var target = document.querySelector(this.getAttribute('href'));
      if(target){
        e.preventDefault();
        var offset = nav.offsetHeight + 20;
        var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({top: top, behavior: 'smooth'});
        links.classList.remove('open');
      }
    });
  });

  /* FAQ accordion */
  document.querySelectorAll('.lp-faq__q').forEach(function(btn){
    btn.addEventListener('click', function(){
      var item = this.parentElement;
      var isActive = item.classList.contains('active');
      document.querySelectorAll('.lp-faq__item').forEach(function(i){ i.classList.remove('active'); });
      if(!isActive) item.classList.add('active');
    });
  });

  /* Library filter */
  document.querySelectorAll('.lp-library__tab').forEach(function(tab){
    tab.addEventListener('click', function(){
      document.querySelectorAll('.lp-library__tab').forEach(function(t){ t.classList.remove('active'); });
      this.classList.add('active');
      var filter = this.getAttribute('data-filter');
      document.querySelectorAll('.lp-lib-card').forEach(function(card){
        if(filter === 'all' || card.getAttribute('data-cat') === filter){
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  /* prefers-reduced-motion */
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches){
    document.querySelectorAll('.lp-reveal').forEach(function(el){
      el.classList.add('visible');
      el.style.transition = 'none';
    });
    document.querySelectorAll('.lp-hero__floating-card').forEach(function(el){
      el.style.animation = 'none';
    });
  }
})();
</script>

<?php get_footer(); ?>
