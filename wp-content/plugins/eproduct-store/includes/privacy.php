<?php
defined('ABSPATH') || exit;

function eproduct_privacy_policy_shortcode() {
    $company = get_option('eproduct_company_name', 'eProduct');
    $email = get_option('eproduct_contact_email', '');
    if (!$email) $email = 'privacy@eproduct.org';
    $site = home_url('/');
    ob_start();
    ?>
    <section class="eproduct-policy">
        <h1>Privacy Policy</h1>
        <p class="ep-policy-updated"><strong>Last Updated:</strong> 29 August 2026</p>

        <p>This Privacy Policy describes how <?php echo esc_html($company); ?> ("we", "us", "our", or "<?php echo esc_html($company); ?>") collects, uses, discloses, stores, and protects your personal information when you visit our website at <?php echo esc_url($site); ?> (the "Site"), create an account, purchase digital products, download files, or otherwise interact with our services (collectively, the "Services").</p>

        <p>We operate an online store that sells digital products such as templates, graphics, presets, courses, software, plugins, and themes (the "Products"). Because our Products are delivered electronically, this policy explains in detail how purchased files are delivered, how payment and account data are handled, and what rights you have over your information.</p>

        <p>By using the Site or purchasing a Product, you acknowledge that you have read and understood this Privacy Policy. If you do not agree with this policy, please do not use the Site or our Services.</p>

        <h2>1. Information We Collect</h2>
        <p>We collect information that you provide to us directly, information we collect automatically when you use the Site, and information we receive from third parties such as payment processors. The categories of personal information we may collect include:</p>
        <ul>
            <li><strong>Identity and contact information:</strong> your full name, email address, and any other information you submit when creating an account, placing an order, or contacting support.</li>
            <li><strong>Billing information:</strong> billing name, billing email, billing address, country, postal code, and tax or VAT identifiers where required for invoicing.</li>
            <li><strong>Payment information:</strong> payment method details, transaction identifiers, and amount paid. Please see Section 5 (Payment Information) for important limits on what we store.</li>
            <li><strong>Phone number:</strong> only if you choose to provide it through a contact, support, or account form.</li>
            <li><strong>Account and login information:</strong> your chosen username, password (stored in hashed form), password-reset tokens, and authentication/session identifiers.</li>
            <li><strong>IP address and device/browser information:</strong> IP address, browser type and version, operating system, device type, screen resolution, referring URLs, and approximate location derived from your IP address.</li>
            <li><strong>Usage and analytics data:</strong> pages viewed, products browsed, links clicked, time spent on the Site, and interactions with features, collected through cookies and similar technologies (only where permitted or with your consent where required).</li>
            <li><strong>Purchase and download history:</strong> the Products you buy, order identifiers, dates of purchase, license keys or activation records (where applicable), and the dates and times you download files.</li>
            <li><strong>Communications:</strong> information you submit through contact forms, support requests, and survey responses, as well as the content of messages you send us.</li>
        </ul>

        <h2>2. How We Use Your Information</h2>
        <p>We use the information described above for the following purposes:</p>
        <ul>
            <li><strong>Process orders and payments:</strong> to verify your purchase, calculate totals and applicable taxes, and complete the transaction.</li>
            <li><strong>Deliver purchased digital products:</strong> to grant access to downloads, generate download links, and make files available in your account.</li>
            <li><strong>Create and manage user accounts:</strong> to register your account, authenticate you, and provide order and license history.</li>
            <li><strong>Provide customer support:</strong> to respond to questions, troubleshoot issues, and investigate suspicious activity.</li>
            <li><strong>Send transactional communications:</strong> order confirmations, download links, license or account notifications, and security alerts.</li>
            <li><strong>Improve our Products and website:</strong> to understand usage trends, fix bugs, test new features, and enhance the user experience.</li>
            <li><strong>Prevent fraud, abuse, and unauthorized access:</strong> to detect, investigate, and prevent fraudulent transactions, account takeover, and violations of our terms.</li>
            <li><strong>Comply with legal obligations:</strong> to meet tax, accounting, and regulatory requirements, and to respond to lawful requests from authorities.</li>
            <li><strong>Marketing (with consent or as permitted):</strong> to send newsletters, product updates, and promotional offers where you have opted in or where otherwise permitted by law. You can opt out at any time.</li>
        </ul>

        <h2>3. Digital Product Delivery</h2>
        <p>Because all of our Products are digital, delivery works as follows:</p>
        <ul>
            <li><strong>How products are delivered:</strong> after a successful payment, your Product is made available for download. In most cases the file is accessible immediately from your account's order history and a download link is also emailed to the address you provided at checkout.</li>
            <li><strong>Download links by email:</strong> where applicable, we send download links to your billing or account email. We recommend retaining that email so you can re-download your purchase.</li>
            <li><strong>Link validity:</strong> download links and account access remain available while your account is active and for a reasonable period thereafter so you can re-download your purchases. We may limit the number of simultaneous downloads or impose link expiry to protect against abuse, but we will not remove your ability to access Products you have legitimately purchased without notice.</li>
            <li><strong>Account association:</strong> downloads are associated with the customer account used to make the purchase. We track which account downloaded a file and when, in order to provide re-downloads and to prevent unauthorized redistribution.</li>
            <li><strong>Unauthorized sharing or misuse:</strong> our Products are licensed, not sold outright (see Section 9). Sharing, reselling, or redistributing downloaded files, or circumventing access controls, is prohibited. We may suspend or terminate accounts and revoke access where we detect misuse, fraud, or violation of our license terms, subject to applicable law.</li>
        </ul>

        <h2>4. Payment Information</h2>
        <p>We use third-party payment providers to process payments securely. Depending on your location and chosen method, payments may be handled by providers such as Stripe, PayPal, and Razorpay.</p>
        <ul>
            <li>We do <strong>not</strong> directly store complete credit or debit card numbers, CVV codes, or full payment instrument details on our servers. Those details are collected and processed by the payment processor you select at checkout.</li>
            <li>Payment processors handle your payment information according to their own privacy policies and security standards (for example PCI-DSS). We encourage you to review the privacy policy of the processor you use.</li>
            <li>We receive from payment processors a limited set of information needed to fulfil your order, such as a transaction identifier, the amount, currency, and payment status. We do not receive your full card number.</li>
        </ul>

        <h2>5. Cookies and Tracking Technologies</h2>
        <p>We use cookies and similar technologies (such as local storage and pixels) to operate and improve the Site. Categories include:</p>
        <ul>
            <li><strong>Essential cookies:</strong> required for core functionality such as security, load balancing, and session integrity. These cannot be disabled through our cookie controls.</li>
            <li><strong>Login and session cookies:</strong> used to keep you signed in, remember your cart, and maintain your preferences across pages.</li>
            <li><strong>Analytics cookies:</strong> used to understand how visitors use the Site (for example, which pages are popular) so we can improve it. Where required by law, these load only after you grant consent.</li>
            <li><strong>Preference cookies:</strong> remember choices such as currency, language, or previously viewed items.</li>
            <li><strong>Marketing cookies:</strong> used with your consent to measure the performance of advertising and to show relevant offers. For example, with your consent we use the Meta (Facebook) Pixel to understand how our ads perform. You can withdraw consent at any time through our cookie banner or your browser settings.</li>
        </ul>
        <p>You can manage or delete cookies through your browser settings. Most browsers let you block or delete cookies; however, disabling essential or functional cookies may affect how the Site works (for example, you may be signed out or lose cart contents). We also honor opt-out preference signals such as Global Privacy Control (GPC) where required.</p>

        <h2>6. Third-Party Services</h2>
        <p>We rely on trusted service providers to operate the Site and deliver the Services. Categories of third parties with whom information may be shared include:</p>
        <ul>
            <li><strong>Payment processors:</strong> Stripe, PayPal, Razorpay, and any other processor we enable.</li>
            <li><strong>Website hosting and infrastructure providers:</strong> the companies that host the Site and store its data.</li>
            <li><strong>Email services:</strong> providers that send transactional and marketing email on our behalf.</li>
            <li><strong>Analytics providers:</strong> privacy-respecting analytics tools used to measure Site performance.</li>
            <li><strong>Customer-support platforms:</strong> tools used to manage support tickets and communications.</li>
            <li><strong>Fraud and security services:</strong> systems that help detect and prevent fraudulent or abusive activity.</li>
            <li><strong>Cloud storage and delivery providers:</strong> services that store Product files and serve downloads securely.</li>
        </ul>
        <p>Each provider processes personal information only as needed to perform its function for us and under appropriate agreements. We recommend reviewing the privacy policies of these providers for details about their own practices.</p>

        <h2>7. Account Information</h2>
        <p>If you create an account, we store the information described in Sections 1 and 2 so we can provide the Services. You can:</p>
        <ul>
            <li><strong>Update your information:</strong> log in to your account to correct or update your name, email, password, and billing details at any time.</li>
            <li><strong>Request account deletion:</strong> contact us using the details in Section 20. We will delete or anonymize your account and personal data, except for information we must retain for legal, accounting, or fraud-prevention reasons (see Section 13).</li>
            <li><strong>Retention of limited data:</strong> even after deletion, certain records (such as completed orders and tax documents) may be retained as required by law for a defined period and will be handled in accordance with this policy.</li>
        </ul>

        <h2>8. Product Licensing and Usage Data</h2>
        <p>Our Products are provided under a license for your personal or business use, subject to the specific license terms presented at purchase. For Products that require activation (such as software, plugins, or themes), we may collect:</p>
        <ul>
            <li><strong>License keys:</strong> a unique key that confirms your valid purchase and unlocks the Product.</li>
            <li><strong>Domain or website information:</strong> the domain or URL on which the Product is activated, where activation is domain-limited.</li>
            <li><strong>Activation information:</strong> when and how many times a license was activated, and from which account.</li>
            <li><strong>Product version:</strong> the version of the Product you downloaded or activated, to provide updates and support.</li>
            <li><strong>Usage or activation status:</strong> whether a license is active, expired, or revoked, used to enforce license limits and prevent abuse.</li>
        </ul>
        <p>For non-activated Products (such as templates, graphics, and presets), we generally do not collect domain or activation data; your purchase and download history are sufficient to provide re-downloads and support.</p>

        <h2>9. Email Communications</h2>
        <p>We send two broad types of email:</p>
        <ul>
            <li><strong>Transactional emails (required):</strong> order confirmations, download links, license or account notifications, security alerts, and responses to support requests. These are sent because they are necessary to provide the Services you requested, and you cannot opt out of them while using the account.</li>
            <li><strong>Optional marketing emails:</strong> newsletters, product announcements, and promotional offers. These are sent only where you have subscribed or where otherwise permitted. You can unsubscribe at any time by clicking the link in the email or by contacting us; this does not affect transactional emails about your purchases.</li>
        </ul>

        <h2>10. Data Sharing</h2>
        <p>We do <strong>not sell</strong> your personal information. We share personal information only when necessary and as described in this policy:</p>
        <ul>
            <li>With <strong>payment processors</strong> to complete transactions.</li>
            <li>With <strong>service providers</strong> (hosting, email, analytics, support, storage, security) who process data on our behalf.</li>
            <li>With <strong>hosting and infrastructure providers</strong> that operate the Site.</li>
            <li>With <strong>legal authorities</strong> when required by law, legal process, or to protect our rights, users, or the public.</li>
            <li>With <strong>business successors</strong> in connection with a merger, acquisition, reorganization, or sale of assets, subject to this policy continuing to apply to your information.</li>
        </ul>

        <h2>11. Data Security</h2>
        <p>We maintain reasonable technical and organizational measures designed to protect your personal information, including:</p>
        <ul>
            <li><strong>Encryption in transit:</strong> the Site uses HTTPS/SSL to encrypt data sent between your browser and our servers.</li>
            <li><strong>Access controls:</strong> accounts are protected by passwords and role-based permissions; only authorized personnel can access personal data.</li>
            <li><strong>Secure payment processing:</strong> payments are handled by compliant third-party processors rather than stored on our servers.</li>
            <li><strong>Password protection:</strong> passwords are stored using strong, salted hashes; we never store them in plain text.</li>
            <li><strong>Security monitoring:</strong> we monitor for suspicious activity and review access logs.</li>
            <li><strong>Regular updates:</strong> we keep software, plugins, and dependencies current with security patches.</li>
        </ul>
        <p>No method of transmission or storage is completely secure, and we cannot guarantee that your information is "100% secure." We will, however, promptly investigate and notify you of any known breach affecting your personal data where required by law.</p>

        <h2>12. Data Retention</h2>
        <p>We keep personal information only as long as necessary for the purposes described in this policy:</p>
        <ul>
            <li><strong>Account information:</strong> retained while your account is active and for a limited period afterward to allow reactivation and to meet legal obligations.</li>
            <li><strong>Purchase and transaction records:</strong> retained for accounting, tax, and legal requirements (commonly several years, depending on jurisdiction).</li>
            <li><strong>Support communications:</strong> retained for a reasonable period to provide continuity of support.</li>
            <li><strong>Marketing information:</strong> retained until you unsubscribe or request deletion.</li>
            <li><strong>Analytics and cookie data:</strong> retained according to the settings of our analytics providers and your consent choices.</li>
        </ul>

        <h2>13. Refunds and Transaction Records</h2>
        <p>Because our Products are digital and delivered immediately, they are generally <strong>non-refundable</strong> once downloaded or accessed, except where required by law. Regardless of refund outcome, we retain certain transaction information in order to:</p>
        <ul>
            <li>Resolve payment disputes and chargebacks with payment processors.</li>
            <li>Maintain accurate accounting and tax records.</li>
            <li>Prevent fraudulent transactions and abuse of download access.</li>
            <li>Comply with legal and regulatory obligations.</li>
        </ul>

        <h2>14. Children's Privacy</h2>
        <p>The Site and our Products are <strong>not directed to children</strong> and are intended for users who are at least 18 years old (or the age of majority in their jurisdiction). We do not knowingly collect personal information from children. If you believe a minor has provided us personal information, contact us using the details in Section 20 and we will delete it, unless we are required by law to retain it for protective purposes.</p>

        <h2>15. International Users</h2>
        <p>We serve customers worldwide. As a result, your personal information may be processed or stored in countries other than your own, including India, the United States, the European Union, and other jurisdictions where our service providers operate. Where we transfer personal data across borders, we rely on appropriate safeguards such as standard contractual clauses or the legal mechanisms recognized under applicable law, and we require our processors to maintain reasonable protections.</p>

        <h2>16. Your Privacy Rights</h2>
        <p>Depending on where you live, you may have rights under laws such as the GDPR/UK GDPR, the CCPA/CPRA, and India's Digital Personal Data Protection Act (DPDP Act). These may include the right to:</p>
        <ul>
            <li>Access the personal information we hold about you.</li>
            <li>Correct inaccurate or incomplete information.</li>
            <li>Request deletion of your personal information (subject to retention exceptions).</li>
            <li>Obtain a copy of your information in a portable format.</li>
            <li>Object to or restrict certain processing.</li>
            <li>Withdraw consent where processing is based on consent (for example, analytics or marketing cookies).</li>
            <li>Opt out of marketing communications at any time.</li>
        </ul>
        <p>To exercise any of these rights, contact us using the details in Section 20. We will verify your request and respond within the timeframes required by applicable law. You also have the right to lodge a complaint with a supervisory authority in your jurisdiction.</p>

        <h2>17. Third-Party Links</h2>
        <p>The Site may contain links to external websites, including payment processors, analytics tools, and social platforms. This Privacy Policy does not apply to those websites. We are not responsible for the privacy practices, content, or security of third-party sites, and we encourage you to read their policies before providing personal information.</p>

        <h2>18. Changes to This Privacy Policy</h2>
        <p>We may update this Privacy Policy from time to time to reflect changes in our practices, technology, or the law. When we make changes, we will update the "Last Updated" date at the top of this policy. For significant changes, we may notify you through the Site or by email where required. We encourage you to review this policy periodically.</p>

        <h2>19. Contact Information</h2>
        <p>If you have questions about this policy, your personal information, or your privacy rights, or if you wish to exercise any right described above, contact us:</p>
        <ul>
            <li><strong>Business:</strong> <?php echo esc_html($company); ?></li>
            <li><strong>Privacy contact email:</strong> <?php echo esc_html($email); ?></li>
            <li><strong>Data Protection / Grievance contact:</strong> <?php echo esc_html($email); ?> (for GDPR, UK GDPR, and India DPDP Act grievances)</li>
            <li><strong>Postal address:</strong> provided upon request; we will share our registered business address where legally required.</li>
        </ul>

        <h2>20. Legal Compliance</h2>
        <p>We strive to comply with applicable privacy and data-protection laws, including:</p>
        <ul>
            <li><strong>GDPR / UK GDPR</strong> (European Union and United Kingdom) for users in those regions.</li>
            <li><strong>CCPA / CPRA</strong> for applicable California residents.</li>
            <li><strong>India's Digital Personal Data Protection Act (DPDP Act) 2023</strong> and applicable rules, including appointment of a Grievance Officer reachable at the contact email above.</li>
            <li>Other local privacy, consumer-protection, and data-security laws that apply based on where you are located.</li>
        </ul>
        <p>This policy is provided for transparency and does not constitute legal advice. If you are unsure how a specific law applies to you, please consult a qualified legal professional.</p>
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
