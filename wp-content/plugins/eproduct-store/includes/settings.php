<?php
defined('ABSPATH') || exit;

function eproduct_register_settings() {
    $fields = array(
        'eproduct_stripe_publishable',
        'eproduct_stripe_secret',
        'eproduct_paypal_client',
        'eproduct_paypal_secret',
        'eproduct_razorpay_key',
        'eproduct_razorpay_secret',
        'eproduct_fb_pixel',
        'eproduct_company_name',
        'eproduct_contact_email',
    );
    foreach ($fields as $f) {
        register_setting('eproduct_settings', $f);
    }
}

function eproduct_settings_page() {
    if (!current_user_can('manage_options')) return;
    ?>
    <div class="wrap">
        <h1>eProduct Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('eproduct_settings'); ?>
            <table class="form-table">
                <tr><th>Company Name</th><td><input type="text" name="eproduct_company_name" value="<?php echo esc_attr(get_option('eproduct_company_name')); ?>" class="regular-text"></td></tr>
                <tr><th>Contact Email</th><td><input type="email" name="eproduct_contact_email" value="<?php echo esc_attr(get_option('eproduct_contact_email')); ?>" class="regular-text"></td></tr>
                <tr><th>Stripe Publishable Key</th><td><input type="text" name="eproduct_stripe_publishable" value="<?php echo esc_attr(get_option('eproduct_stripe_publishable')); ?>" class="regular-text"></td></tr>
                <tr><th>Stripe Secret Key</th><td><input type="password" name="eproduct_stripe_secret" value="<?php echo esc_attr(get_option('eproduct_stripe_secret')); ?>" class="regular-text"></td></tr>
                <tr><th>PayPal Client ID</th><td><input type="text" name="eproduct_paypal_client" value="<?php echo esc_attr(get_option('eproduct_paypal_client')); ?>" class="regular-text"></td></tr>
                <tr><th>PayPal Secret</th><td><input type="password" name="eproduct_paypal_secret" value="<?php echo esc_attr(get_option('eproduct_paypal_secret')); ?>" class="regular-text"></td></tr>
                <tr><th>Razorpay Key ID</th><td><input type="text" name="eproduct_razorpay_key" value="<?php echo esc_attr(get_option('eproduct_razorpay_key')); ?>" class="regular-text"></td></tr>
                <tr><th>Razorpay Key Secret</th><td><input type="password" name="eproduct_razorpay_secret" value="<?php echo esc_attr(get_option('eproduct_razorpay_secret')); ?>" class="regular-text"></td></tr>
                <tr><th>Facebook Pixel ID</th><td><input type="text" name="eproduct_fb_pixel" value="<?php echo esc_attr(get_option('eproduct_fb_pixel')); ?>" class="regular-text"></td></tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function eproduct_settings_menu() {
    add_menu_page('eProduct', 'eProduct', 'manage_options', 'eproduct-settings', 'eproduct_settings_page', 'dashicons-cart');
    add_submenu_page('eproduct-settings', 'Add Product', 'Add Product', 'manage_options', 'eproduct-add-product', 'eproduct_add_product_page');
}

add_action('admin_init', 'eproduct_register_settings');
add_action('admin_menu', 'eproduct_settings_menu');

/* ── Add Product admin page ── */
function eproduct_add_product_page() {
    $saved = false;
    $errors = array();

    if (isset($_POST['ep_add_product']) && check_admin_referer('ep_add_product')) {
        $title   = sanitize_text_field($_POST['title']);
        $content = wp_kses_post($_POST['content']);
        $price   = floatval($_POST['price']);
        $sale    = $_POST['sale_price'] !== '' ? floatval($_POST['sale_price']) : '';
        $file    = esc_url_raw($_POST['file_url']);
        $thumb   = intval($_POST['thumb_id']);

        if (empty($title)) $errors[] = 'Product name is required.';
        if ($price <= 0) $errors[] = 'Price must be greater than 0.';

        if (empty($errors)) {
            $post_id = wp_insert_post(array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'eproduct',
            ));

            if ($post_id && !is_wp_error($post_id)) {
                update_post_meta($post_id, '_eproduct_price', $price);
                update_post_meta($post_id, '_eproduct_sale_price', $sale !== '' && $sale > 0 ? $sale : '');
                update_post_meta($post_id, '_eproduct_file', $file);

                if ($thumb > 0) set_post_thumbnail($post_id, $thumb);

                // Categories
                $cat_ids = array();
                if (!empty($_POST['eproduct_category'])) {
                    foreach ((array)$_POST['eproduct_category'] as $cid) {
                        $cid = intval($cid);
                        if ($cid > 0) $cat_ids[] = $cid;
                    }
                }
                if (!empty($_POST['new_category'])) {
                    $new_cat = trim(sanitize_text_field($_POST['new_category']));
                    if ($new_cat !== '') {
                        $existing = get_term_by('name', $new_cat, 'eproduct_category');
                        if ($existing) {
                            $cat_ids[] = $existing->term_id;
                        } else {
                            $ins = wp_insert_term($new_cat, 'eproduct_category');
                            if (!is_wp_error($ins)) $cat_ids[] = $ins['term_id'];
                        }
                    }
                }
                if (!empty($cat_ids)) {
                    wp_set_object_terms($post_id, array_unique($cat_ids), 'eproduct_category');
                }

                $saved = true;
            } else {
                $errors[] = 'Failed to create product.';
            }
        }
    }

    $cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => false));
    ?>
    <div class="wrap">
        <h1>Add New Product</h1>

        <?php if ($saved): ?>
            <div class="notice notice-success is-dismissible"><p>Product created successfully! <a href="<?php echo esc_url(get_edit_post_link(get_the_ID())); ?>">Edit it</a> or <a href="<?php echo esc_url(admin_url('edit.php?post_type=eproduct')); ?>">view all products</a>.</p></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="notice notice-error"><p><?php echo implode('<br>', $errors); ?></p></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('ep_add_product'); ?>

            <table class="form-table">
                <tr>
                    <th><label for="title">Product Name *</label></th>
                    <td><input type="text" id="title" name="title" value="<?php echo esc_attr(isset($_POST['title']) ? $_POST['title'] : ''); ?>" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label for="content">Description</label></th>
                    <td><?php
                        wp_editor(isset($_POST['content']) ? $_POST['content'] : '', 'content', array(
                            'textarea_rows' => 8,
                            'media_buttons' => false,
                            'teeny'         => true,
                        ));
                    ?></td>
                </tr>
                <tr>
                    <th><label for="price">Price (USD) *</label></th>
                    <td><input type="number" step="0.01" min="0.01" id="price" name="price" value="<?php echo esc_attr(isset($_POST['price']) ? $_POST['price'] : ''); ?>" style="width:120px" required></td>
                </tr>
                <tr>
                    <th><label for="sale_price">Sale Price (USD)</label></th>
                    <td><input type="number" step="0.01" min="0" id="sale_price" name="sale_price" value="<?php echo esc_attr(isset($_POST['sale_price']) ? $_POST['sale_price'] : ''); ?>" style="width:120px"><br><span class="description">Leave empty for no sale.</span></td>
                </tr>
                <tr>
                    <th><label for="file_url">Download File URL</label></th>
                    <td><input type="url" id="file_url" name="file_url" value="<?php echo esc_url(isset($_POST['file_url']) ? $_POST['file_url'] : ''); ?>" class="regular-text" placeholder="https://"></td>
                </tr>
                <tr>
                    <th>Product Cover Image</th>
                    <td>
                        <div id="ep-admin-cover-wrap">
                            <input type="hidden" name="thumb_id" id="ep-admin-thumb-id" value="<?php echo esc_attr(isset($_POST['thumb_id']) ? $_POST['thumb_id'] : ''); ?>">
                            <div id="ep-admin-cover-preview"></div>
                            <button type="button" class="button" id="ep-admin-cover-btn">Set cover image</button>
                            <button type="button" class="button" id="ep-admin-cover-rm" style="display:none;">Remove</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Product Category</th>
                    <td>
                        <?php if (!empty($cats) && !is_wp_error($cats)): ?>
                            <div style="display:flex;flex-wrap:wrap;gap:8px 18px;margin-bottom:10px;">
                            <?php foreach ($cats as $c): ?>
                                <label style="display:inline-flex;align-items:center;gap:5px;font-weight:400;">
                                    <input type="checkbox" name="eproduct_category[]" value="<?php echo esc_attr($c->term_id); ?>">
                                    <?php echo esc_html($c->name); ?>
                                </label>
                            <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="description">No categories yet.</p>
                        <?php endif; ?>
                        <input type="text" name="new_category" placeholder="Or add a new category…" style="width:260px">
                    </td>
                </tr>
            </table>

            <?php submit_button('Create Product', 'primary', 'ep_add_product'); ?>
        </form>
    </div>

    <script>
    jQuery(function ($) {
        var frame;
        $('#ep-admin-cover-btn').on('click', function (e) {
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({ title: 'Product Cover Image', button: { text: 'Use this image' }, multiple: false });
            frame.on('select', function () {
                var att = frame.state().get('selection').first().toJSON();
                $('#ep-admin-thumb-id').val(att.id);
                var url = (att.sizes && att.sizes.medium) ? att.sizes.medium.url : att.url;
                $('#ep-admin-cover-preview').html('<img src="' + url + '" style="max-width:220px;height:auto;display:block;margin-bottom:8px;border-radius:10px;">');
                $('#ep-admin-cover-rm').show();
            });
            frame.open();
        });
        $('#ep-admin-cover-rm').on('click', function (e) {
            e.preventDefault();
            $('#ep-admin-thumb-id').val('');
            $('#ep-admin-cover-preview').empty();
            $(this).hide();
        });
    });
    </script>
    <?php
}
