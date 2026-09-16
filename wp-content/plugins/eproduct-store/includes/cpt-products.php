<?php
defined('ABSPATH') || exit;

function eproduct_register_product_cpt() {
    register_post_type('eproduct', array(
        'labels' => array(
            'name' => 'Products',
            'singular_name' => 'Product',
            'add_new' => 'Add Product',
            'add_new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
        ),
        'public' => false,
        'publicly_queryable' => false,
        'has_archive' => false,
        'rewrite' => false,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-download',
        'show_in_rest' => false,
    ));
}

function eproduct_register_category_tax() {
    register_taxonomy('eproduct_category', 'eproduct', array(
        'labels' => array(
            'name' => 'Product Categories',
            'singular_name' => 'Category',
        ),
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => 'product-category'),
        'show_in_rest' => false,
    ));
}

function eproduct_product_meta_box() {
    remove_meta_box('eproduct_categorydiv', 'eproduct', 'side');
    add_meta_box('eproduct_meta', 'Product Details', 'eproduct_product_meta_cb', 'eproduct', 'normal', 'high');
}

function eproduct_product_meta_cb($post) {
    wp_nonce_field('eproduct_meta_save', 'eproduct_meta_nonce');
    $price = get_post_meta($post->ID, '_eproduct_price', true);
    $sale_price = get_post_meta($post->ID, '_eproduct_sale_price', true);
    $file = get_post_meta($post->ID, '_eproduct_file', true);
    $link = get_post_meta($post->ID, '_eproduct_link', true);
    $visibility = get_post_meta($post->ID, '_eproduct_visibility', true);
    if ($visibility !== 'hidden') $visibility = 'active';
    $short_desc = get_post_meta($post->ID, '_eproduct_short_desc', true);
    $thumb_id = get_post_thumbnail_id($post->ID);
    ?>
    <style>
        .ep-field{margin-bottom:14px}.ep-field label{display:block;font-weight:600;margin-bottom:4px;font-size:13px}
        .ep-field input[type="number"],.ep-field input[type="url"],.ep-field input[type="text"],.ep-field select,.ep-field textarea{width:100%;max-width:400px;padding:6px 10px;border:1px solid #ccc;border-radius:4px}
        .ep-field .desc{color:#666;font-size:12px;margin-top:3px}
        #ep-cover-prev{max-width:220px;height:auto;display:block;margin-bottom:8px;border-radius:10px}
    </style>
    <?php
    echo '<div class="ep-field"><label>Frontend Visibility</label>';
    echo '<select name="eproduct_visibility" style="max-width:400px">';
    echo '<option value="active"' . selected($visibility, 'active', false) . '>Active — Visible on frontend</option>';
    echo '<option value="hidden"' . selected($visibility, 'hidden', false) . '>Hidden — Not visible on frontend</option>';
    echo '</select><div class="desc">Hidden products remain stored but do not appear on the public listing.</div></div>';

    echo '<div class="ep-field"><label>Product Value / Price (₹)</label>';
    echo '<input type="number" step="1" min="0" name="eproduct_price" value="' . esc_attr($price) . '"><div class="desc">Display price on frontend in Indian Rupees.</div></div>';

    echo '<div class="ep-field"><label>Sale Price (₹) <em style="font-weight:normal;color:#888">(optional)</em></label>';
    echo '<input type="number" step="1" min="0" name="eproduct_sale_price" value="' . esc_attr($sale_price) . '"><div class="desc">Leave empty for no sale.</div></div>';

    echo '<div class="ep-field"><label>Landing Page URL <em style="font-weight:normal;color:#888">(optional)</em></label>';
    echo '<input type="url" name="eproduct_link" value="' . esc_url($link) . '" placeholder="https://"><div class="desc">Custom link override. If set, product card links here instead of default page.</div></div>';

    echo '<div class="ep-field"><label>Download File URL <em style="font-weight:normal;color:#888">(optional)</em></label>';
    echo '<input type="url" name="eproduct_file" value="' . esc_url($file) . '" style="max-width:100%" placeholder="https://"></div>';

    echo '<div class="ep-field"><label>Product Card Image</label>';
    echo '<div id="ep-cover-wrap">';
    if ($thumb_id) {
        echo wp_get_attachment_image($thumb_id, 'medium', false, array('id' => 'ep-cover-prev'));
    }
    echo '<input type="hidden" name="_thumbnail_id" id="ep-thumb-id" value="' . esc_attr($thumb_id) . '">';
    echo '<button type="button" class="button" id="ep-cover-btn">Set cover image</button> ';
    if ($thumb_id) echo '<button type="button" class="button" id="ep-cover-rm">Remove image</button>';
    echo '</div></div>';

    $ep_cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => false));
    $ep_assigned = wp_get_post_terms($post->ID, 'eproduct_category', array('fields' => 'ids'));
    if (is_wp_error($ep_assigned)) $ep_assigned = array();
    echo '<div class="ep-field"><label>Product Category</label>';
    if (!empty($ep_cats) && !is_wp_error($ep_cats)) {
        echo '<div style="display:flex;flex-wrap:wrap;gap:8px 18px;margin:6px 0">';
        foreach ($ep_cats as $c) {
            $chk = in_array((int)$c->term_id, $ep_assigned, true) ? 'checked' : '';
            echo '<label style="display:inline-flex;align-items:center;gap:6px;font-weight:400"><input type="checkbox" name="eproduct_category[]" value="' . esc_attr($c->term_id) . '" ' . $chk . '> ' . esc_html($c->name) . '</label>';
        }
        echo '</div>';
    } else {
        echo '<span style="color:#888">No categories yet.</span>';
    }
    echo '<input type="text" name="eproduct_new_category" placeholder="Or add a new category…" style="width:260px;margin-top:8px"></div>';

    echo '<div class="ep-field"><label>Short Description</label>';
    echo '<textarea name="eproduct_short_desc" rows="3" style="max-width:600px;width:100%">' . esc_textarea($short_desc) . '</textarea>';
    echo '<div class="desc">Brief description shown on product cards. Falls back to excerpt if empty.</div></div>';
}

function eproduct_product_meta_save($post_id) {
    if (!isset($_POST['eproduct_meta_nonce']) || !wp_verify_nonce($_POST['eproduct_meta_nonce'], 'eproduct_meta_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['eproduct_price'])) {
        update_post_meta($post_id, '_eproduct_price', floatval($_POST['eproduct_price']));
    }
    if (isset($_POST['eproduct_sale_price'])) {
        $sp = floatval($_POST['eproduct_sale_price']);
        update_post_meta($post_id, '_eproduct_sale_price', $sp > 0 ? $sp : '');
    }
    if (isset($_POST['eproduct_file'])) {
        update_post_meta($post_id, '_eproduct_file', esc_url_raw($_POST['eproduct_file']));
    }
    if (isset($_POST['eproduct_link'])) {
        update_post_meta($post_id, '_eproduct_link', esc_url_raw($_POST['eproduct_link']));
    }
    if (isset($_POST['eproduct_visibility'])) {
        $v = sanitize_text_field($_POST['eproduct_visibility']);
        update_post_meta($post_id, '_eproduct_visibility', ($v === 'hidden') ? 'hidden' : 'active');
    }
    if (isset($_POST['eproduct_short_desc'])) {
        update_post_meta($post_id, '_eproduct_short_desc', sanitize_textarea_field($_POST['eproduct_short_desc']));
    }
    if (isset($_POST['_thumbnail_id'])) {
        $tid = intval($_POST['_thumbnail_id']);
        if ($tid > 0) set_post_thumbnail($post_id, $tid);
        else delete_post_thumbnail($post_id);
    }
    // Save product categories
    $ep_ids = array();
    if (isset($_POST['eproduct_category'])) {
        foreach ((array)$_POST['eproduct_category'] as $id) {
            $id = intval($id);
            if ($id > 0) $ep_ids[] = $id;
        }
    }
    if (!empty($_POST['eproduct_new_category'])) {
        $ep_new = trim(sanitize_text_field($_POST['eproduct_new_category']));
        if ($ep_new !== '') {
            $ep_term = get_term_by('name', $ep_new, 'eproduct_category');
            if (!$ep_term) {
                $ep_ins = wp_insert_term($ep_new, 'eproduct_category');
                if (!is_wp_error($ep_ins)) $ep_term = get_term($ep_ins['term_id'], 'eproduct_category');
            }
            if (!empty($ep_term) && !is_wp_error($ep_term)) $ep_ids[] = (int)$ep_term->term_id;
        }
    }
    $ep_ids = array_values(array_unique($ep_ids));
    wp_set_object_terms($post_id, $ep_ids, 'eproduct_category', false);

    if (function_exists('eproduct_invalidate_cache')) eproduct_invalidate_cache($post_id);
}

function eproduct_admin_media($hook) {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'eproduct') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'eproduct_admin_media');

function eproduct_admin_cover_js() {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'eproduct') return;
    ?>
    <script>
    jQuery(function ($) {
        var frame;
        $('#ep-cover-btn').on('click', function (e) {
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({ title: 'Product Cover Image', button: { text: 'Use this image' }, multiple: false });
            frame.on('select', function () {
                var att = frame.state().get('selection').first().toJSON();
                $('#ep-thumb-id').val(att.id);
                var url = (att.sizes && att.sizes.medium) ? att.sizes.medium.url : att.url;
                $('#ep-cover-prev').remove();
                $('<img id="ep-cover-prev" src="' + url + '" style="max-width:220px;height:auto;display:block;margin-bottom:8px;border-radius:10px;">').insertBefore('#ep-thumb-id');
                if (!$('#ep-cover-rm').length) $('<button type="button" class="button" id="ep-cover-rm">Remove image</button>').insertAfter('#ep-cover-btn');
            });
            frame.open();
        });
        $('#ep-cover-rm').on('click', function (e) {
            e.preventDefault();
            $('#ep-thumb-id').val('');
            $('#ep-cover-prev').remove();
            $(this).remove();
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'eproduct_admin_cover_js');

function eproduct_hide_from_frontend($query) {
    if (!is_admin() && $query->is_main_query() && !$query->is_singular()) {
        $types = (array) $query->get('post_type');
        if (in_array('eproduct', $types)) {
            $types = array_diff($types, ['eproduct']);
            $query->set('post_type', $types ? $types : ['post']);
        }
    }
}
add_action('pre_get_posts', 'eproduct_hide_from_frontend');

add_action('init', 'eproduct_register_product_cpt');
add_action('init', 'eproduct_register_category_tax');
add_action('add_meta_boxes', 'eproduct_product_meta_box');
add_action('save_post_eproduct', 'eproduct_product_meta_save');
