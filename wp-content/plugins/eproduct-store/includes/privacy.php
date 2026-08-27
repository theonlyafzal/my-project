<?php
defined('ABSPATH') || exit;

function eproduct_privacy_policy_shortcode() {
    $company = get_option('eproduct_company_name', 'eProduct');
    $email = get_option('eproduct_contact_email', '');
    ob_start();
    ?>
    <section class="eproduct-policy">
        <h1>Privacy Policy</h1>
        <p><?php echo esc_html($company); ?> ("we", "us") respects your privacy. This policy explains what data we collect and why.</p>
        <h2>Data We Collect</h2>
        <ul>
            <li>Account data: name, email, and login credentials (stored securely using WordPress).</li>
            <li>Order data: products purchased, amount, payment status.</li>
            <li>Usage data via cookies and analytics (only with your consent).</li>
        </ul>
        <h2>Cookies &amp; Advertising</h2>
        <p>With your consent we use the Meta (Facebook) Pixel to measure ad performance. You can withdraw consent at any time via the cookie banner.</p>
        <h2>Your Rights</h2>
        <p>You may request access to or deletion of your data by contacting <?php echo esc_html($email); ?>.</p>
        <h2>Contact</h2>
        <p><?php echo esc_html($email); ?></p>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('eproduct_privacy', 'eproduct_privacy_policy_shortcode');

function eproduct_fb_pixel_inject() {
    $pixel = get_option('eproduct_fb_pixel');
    if (!$pixel) return;
    if (!empty($_COOKIE['eproduct_consent']) && $_COOKIE['eproduct_consent'] === 'granted') {
        echo "<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','" . esc_js($pixel) . "');fbq('track','PageView');</script>";
    }
}
add_action('wp_head', 'eproduct_fb_pixel_inject');

function eproduct_consent_banner() {
    if (!empty($_COOKIE['eproduct_consent'])) return;
    ?>
    <div id="eproduct-consent" style="position:fixed;bottom:0;left:0;right:0;background:#111;color:#fff;padding:15px;z-index:9999;display:flex;gap:10px;align-items:center;justify-content:center;flex-wrap:wrap">
        <span>We use cookies for analytics and advertising. Do you accept?</span>
        <button onclick="eproductSetConsent('granted')">Accept</button>
        <button onclick="eproductSetConsent('denied')">Decline</button>
    </div>
    <script>
    function eproductSetConsent(v){
        document.cookie='eproduct_consent='+v+';path=/;max-age='+(60*60*24*365);
        document.getElementById('eproduct-consent').style.display='none';
        if(v==='granted'){location.reload();}
    }
    </script>
    <?php
}
add_action('wp_footer', 'eproduct_consent_banner');
