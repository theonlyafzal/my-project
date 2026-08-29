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
        'public' => true,
        'has_archive' => 'products',
        'rewrite' => array('slug' => 'product'),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-download',
        'show_in_rest' => true,
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
        'show_in_rest' => true,
    ));
}

function eproduct_product_meta_box() {
    add_meta_box('eproduct_meta', 'Product Details', 'eproduct_product_meta_cb', 'eproduct', 'normal', 'high');
}

function eproduct_product_meta_cb($post) {
    wp_nonce_field('eproduct_meta_save', 'eproduct_meta_nonce');
    $price = get_post_meta($post->ID, '_eproduct_price', true);
    $file = get_post_meta($post->ID, '_eproduct_file', true);
    $thumb_id = get_post_thumbnail_id($post->ID);
    echo '<p><label>Price (USD)</label><br>';
    echo '<input type="number" step="0.01" name="eproduct_price" value="' . esc_attr($price) . '" style="width:200px"></p>';
    echo '<p><label>Download File URL</label><br>';
    echo '<input type="url" name="eproduct_file" value="' . esc_url($file) . '" style="width:100%"></p>';
    echo '<p><label><strong>Product Cover Image</strong></label><br>';
    echo '<div id="ep-cover-wrap">';
    if ($thumb_id) {
        echo wp_get_attachment_image($thumb_id, 'medium', false, array('id' => 'ep-cover-prev', 'style' => 'max-width:220px;height:auto;display:block;margin-bottom:8px;border-radius:10px;'));
    }
    echo '<input type="hidden" name="_thumbnail_id" id="ep-thumb-id" value="' . esc_attr($thumb_id) . '">';
    echo '<button type="button" class="button" id="ep-cover-btn">Set cover image</button> ';
    if ($thumb_id) echo '<button type="button" class="button" id="ep-cover-rm">Remove image</button>';
    echo '</div></p>';
}

function eproduct_product_meta_save($post_id) {
    if (!isset($_POST['eproduct_meta_nonce']) || !wp_verify_nonce($_POST['eproduct_meta_nonce'], 'eproduct_meta_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (isset($_POST['eproduct_price'])) {
        update_post_meta($post_id, '_eproduct_price', floatval($_POST['eproduct_price']));
    }
    if (isset($_POST['eproduct_file'])) {
        update_post_meta($post_id, '_eproduct_file', esc_url_raw($_POST['eproduct_file']));
    }
    if (isset($_POST['_thumbnail_id'])) {
        $tid = intval($_POST['_thumbnail_id']);
        if ($tid > 0) set_post_thumbnail($post_id, $tid);
        else delete_post_thumbnail($post_id);
    }
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

add_action('init', 'eproduct_register_product_cpt');
add_action('init', 'eproduct_register_category_tax');
add_action('add_meta_boxes', 'eproduct_product_meta_box');
add_action('save_post_eproduct', 'eproduct_product_meta_save');
