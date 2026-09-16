<?php
defined('ABSPATH') || exit;

/**
 * Product Manager — Admin interface for eProduct Store.
 *
 * Provides a dedicated product-management page under eProduct → Product Manager
 * with dashboard stats, filtering, searching, sorting, quick show/hide,
 * REST API endpoints, and cache invalidation.
 */

/* ============================================================
   1. HELPERS — visibility get / set / label
   ============================================================ */

function eproduct_get_visibility($post_id) {
    $v = get_post_meta($post_id, '_eproduct_visibility', true);
    return ($v === 'hidden') ? 'hidden' : 'active';
}

function eproduct_set_visibility($post_id, $status) {
    $status = ($status === 'hidden') ? 'hidden' : 'active';
    update_post_meta($post_id, '_eproduct_visibility', $status);
    eproduct_invalidate_cache($post_id);
}

function eproduct_visibility_label($status) {
    return ($status === 'hidden') ? 'Hidden' : 'Active';
}

function eproduct_active_meta_query() {
    return array(
        'relation' => 'OR',
        array('key' => '_eproduct_visibility', 'value' => 'active', 'compare' => '='),
        array('key' => '_eproduct_visibility', 'compare' => 'NOT EXISTS'),
    );
}

/* ============================================================
   2. CACHE INVALIDATION
   ============================================================ */

function eproduct_invalidate_cache($post_id = 0) {
    if ($post_id) {
        clean_post_cache($post_id);
    }
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
}

/* ============================================================
   3. FRONTEND VISIBILITY FILTER — exclude hidden products
   ============================================================ */

function eproduct_visibility_filter($query) {
    if (is_admin()) return;
    if (!($query->is_main_query() || $query->get('eproduct_include_all'))) return;

    $post_type = $query->get('post_type');
    if (is_string($post_type)) $post_type = array($post_type);
    if (!is_array($post_type)) return;
    if (!in_array('eproduct', $post_type)) return;

    $meta = $query->get('meta_query');
    if (!is_array($meta)) $meta = array();

    $already_has = false;
    foreach ($meta as $clause) {
        if (isset($clause['key']) && $clause['key'] === '_eproduct_visibility') {
            $already_has = true;
            break;
        }
    }
    if (!$already_has) {
        $meta[] = eproduct_active_meta_query();
        $query->set('meta_query', $meta);
    }
}
add_action('pre_get_posts', 'eproduct_visibility_filter', 5);

/* ============================================================
   4. ADMIN MENU — Product Manager page
   ============================================================ */

function eproduct_register_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=eproduct',
        'Product Manager',
        'Product Manager',
        'manage_options',
        'eproduct-manage',
        'eproduct_manage_products_page'
    );
}
add_action('admin_menu', 'eproduct_register_admin_menu');

/* ============================================================
   5. ADMIN PAGE — dashboard + list table
   ============================================================ */

function eproduct_manage_products_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have permission to access this page.');
    }

    global $wpdb;

    /* --- Handle bulk actions --- */
    if (isset($_POST['ep_bulk_action']) && check_admin_referer('ep_bulk_action')) {
        $action = sanitize_text_field($_POST['ep_bulk_action']);
        $ids = isset($_POST['ep_ids']) ? array_map('intval', $_POST['ep_ids']) : array();
        if (!empty($ids)) {
            foreach ($ids as $id) {
                if ($action === 'bulk_activate') {
                    eproduct_set_visibility($id, 'active');
                } elseif ($action === 'bulk_hide') {
                    eproduct_set_visibility($id, 'hidden');
                } elseif ($action === 'bulk_delete' && current_user_can('delete_posts')) {
                    wp_delete_post($id, true);
                }
            }
        }
        wp_redirect(admin_url('admin.php?page=eproduct-manage&bulk=1'));
        exit;
    }

    /* --- Handle single toggle --- */
    if (isset($_GET['ep_toggle']) && isset($_GET['_wpnonce'])) {
        $id = intval($_GET['ep_toggle']);
        if ($id && wp_verify_nonce($_GET['_wpnonce'], 'ep_toggle_' . $id)) {
            $current = eproduct_get_visibility($id);
            eproduct_set_visibility($id, $current === 'active' ? 'hidden' : 'active');
            wp_redirect(admin_url('admin.php?page=eproduct-manage& toggled=1'));
            exit;
        }
    }

    /* --- Query params --- */
    $status_filter = isset($_GET['ep_status']) ? sanitize_text_field($_GET['ep_status']) : 'all';
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'modified';
    $order = strtoupper(isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC');
    if (!in_array($order, array('ASC', 'DESC'))) $order = 'DESC';

    /* --- Build query --- */
    $args = array(
        'post_type'      => 'eproduct',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => $orderby,
        'order'          => $order,
    );
    if ($search) $args['s'] = $search;

    if ($status_filter === 'active') {
        $args['meta_query'] = array(array('key' => '_eproduct_visibility', 'value' => 'active', 'compare' => '='));
    } elseif ($status_filter === 'hidden') {
        $args['meta_query'] = array(array('key' => '_eproduct_visibility', 'value' => 'hidden', 'compare' => '='));
    } else {
        $args['meta_query'] = array(array('key' => '_eproduct_visibility', 'value' => 'active', 'compare' => '='));
        $args['meta_query']['relation'] = 'OR';
        $args['meta_query'][] = array('key' => '_eproduct_visibility', 'value' => 'hidden', 'compare' => '=');
        $args['meta_query'][] = array('key' => '_eproduct_visibility', 'compare' => 'NOT EXISTS');
    }

    $query = new WP_Query($args);
    $products = $query->posts;

    /* --- Stats --- */
    $counts = array('active' => 0, 'hidden' => 0, 'total' => 0);
    foreach ($products as $p) {
        $v = eproduct_get_visibility($p->ID);
        $counts[$v]++;
        $counts['total']++;
    }

    /* --- Display --- */
    ?>
    <div class="wrap">
        <h1>eProduct Manager</h1>

        <!-- Dashboard Summary -->
        <div style="display:flex;gap:16px;margin:16px 0 24px;flex-wrap:wrap;">
            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:8px;padding:16px 24px;min-width:140px;text-align:center;">
                <div style="font-size:28px;font-weight:700;color:#1d2327;"><?php echo $counts['total']; ?></div>
                <div style="color:#646970;font-size:13px;margin-top:4px;">Total Products</div>
            </div>
            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:8px;padding:16px 24px;min-width:140px;text-align:center;">
                <div style="font-size:28px;font-weight:700;color:#00a32a;"><?php echo $counts['active']; ?></div>
                <div style="color:#646970;font-size:13px;margin-top:4px;">Active</div>
            </div>
            <div style="background:#fff;border:1px solid #c3c4c7;border-radius:8px;padding:16px 24px;min-width:140px;text-align:center;">
                <div style="font-size:28px;font-weight:700;color:#d63638;"><?php echo $counts['hidden']; ?></div>
                <div style="color:#646970;font-size:13px;margin-top:4px;">Hidden</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <?php
        $tabs = array(
            'all'     => 'All (' . $counts['total'] . ')',
            'active'  => 'Active (' . $counts['active'] . ')',
            'hidden'  => 'Hidden (' . $counts['hidden'] . ')',
        );
        ?>
        <h2 class="nav-tab-wrapper" style="margin-bottom:12px;">
            <?php foreach ($tabs as $key => $label):
                $cls = ($status_filter === $key) ? 'nav-tab nav-tab-active' : 'nav-tab';
                $url = admin_url('admin.php?page=eproduct-manage&ep_status=' . $key . ($search ? '&s=' . urlencode($search) : ''));
            ?>
                <a href="<?php echo esc_url($url); ?>" class="<?php echo $cls; ?>"><?php echo $label; ?></a>
            <?php endforeach; ?>
        </h2>

        <!-- Search + Bulk -->
        <form method="get" style="margin-bottom:12px;">
            <input type="hidden" name="page" value="eproduct-manage">
            <input type="hidden" name="ep_status" value="<?php echo esc_attr($status_filter); ?>">
            <p class="search-box" style="display:inline-flex;gap:6px;align-items:center;">
                <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search products...">
                <?php submit_button('Search', 'secondary', '', false); ?>
            </p>
        </form>

        <form method="post" id="ep-bulk-form">
            <?php wp_nonce_field('ep_bulk_action'); ?>

            <div style="margin-bottom:8px;display:flex;gap:8px;align-items:center;">
                <select name="ep_bulk_action" id="ep-bulk-select">
                    <option value="">Bulk Actions</option>
                    <option value="bulk_activate">Activate</option>
                    <option value="bulk_hide">Hide</option>
                    <?php if (current_user_can('delete_posts')): ?>
                        <option value="bulk_delete">Delete</option>
                    <?php endif; ?>
                </select>
                <?php submit_button('Apply', 'secondary', 'ep-bulk-submit', false); ?>
                <span id="ep-bulk-count" style="color:#646970;font-size:13px;"></span>
            </div>

            <table class="wp-list-table widefat fixed striped" id="ep-product-table">
                <thead>
                    <tr>
                        <td class="manage-column column-cb check-column" style="width:30px;">
                            <input type="checkbox" id="ep-select-all">
                        </td>
                        <th style="width:60px;">Image</th>
                        <th>
                            <?php
                            $base_url = admin_url('admin.php?page=eproduct-manage&ep_status=' . $status_filter . ($search ? '&s=' . urlencode($search) : ''));
                            $new_o = ($orderby === 'title' && $order === 'DESC') ? 'ASC' : 'DESC';
                            $arr = ($orderby === 'title') ? ($order === 'DESC' ? ' &#9660;' : ' &#9650;') : '';
                            echo '<a href="' . esc_url(add_query_arg(array('orderby' => 'title', 'order' => $new_o), $base_url)) . '">Product' . $arr . '</a>';
                            ?>
                        </th>
                        <th style="width:100px;">
                            <?php
                            $new_o = ($orderby === 'price' && $order === 'DESC') ? 'ASC' : 'DESC';
                            $arr = ($orderby === 'price') ? ($order === 'DESC' ? ' &#9660;' : ' &#9650;') : '';
                            echo '<a href="' . esc_url(add_query_arg(array('orderby' => 'price', 'order' => $new_o), $base_url)) . '">Price' . $arr . '</a>';
                            ?>
                        </th>
                        <th style="width:90px;">Status</th>
                        <th style="width:140px;">Landing Page</th>
                        <th style="width:120px;">
                            <?php
                            $new_o = ($orderby === 'modified' && $order === 'DESC') ? 'ASC' : 'DESC';
                            $arr = ($orderby === 'modified') ? ($order === 'DESC' ? ' &#9660;' : ' &#9650;') : '';
                            echo '<a href="' . esc_url(add_query_arg(array('orderby' => 'modified', 'order' => $new_o), $base_url)) . '">Updated' . $arr . '</a>';
                            ?>
                        </th>
                        <th style="width:160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr><td colspan="8" style="text-align:center;padding:40px 0;color:#888;">No products found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($products as $p):
                            $v = eproduct_get_visibility($p->ID);
                            $price = floatval(get_post_meta($p->ID, '_eproduct_price', true));
                            $sale = get_post_meta($p->ID, '_eproduct_sale_price', true);
                            $sale = ($sale !== '' && floatval($sale) > 0) ? floatval($sale) : '';
                            $landing = get_post_meta($p->ID, '_eproduct_landing_url', true);
                            $thumb = get_the_post_thumbnail_url($p->ID, 'thumbnail');
                            $edit_url = get_edit_post_link($p->ID, 'raw');
                            $view_url = get_permalink($p->ID);
                            $toggle_url = wp_nonce_url(
                                admin_url('admin.php?page=eproduct-manage&ep_toggle=' . $p->ID . '&ep_status=' . $status_filter),
                                'ep_toggle_' . $p->ID
                            );
                        ?>
                        <tr>
                            <th class="check-column">
                                <input type="checkbox" name="ep_ids[]" value="<?php echo $p->ID; ?>" class="ep-row-cb">
                            </th>
                            <td>
                                <?php if ($thumb): ?>
                                    <img src="<?php echo esc_url($thumb); ?>" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                <?php else: ?>
                                    <div style="width:50px;height:50px;background:#f0f0f1;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#888;font-size:18px;">&#9733;</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><a href="<?php echo esc_url($edit_url); ?>"><?php echo esc_html($p->post_title); ?></a></strong>
                                <div style="color:#888;font-size:12px;margin-top:2px;">ID: <?php echo $p->ID; ?></div>
                            </td>
                            <td>
                                <?php if ($sale !== '' && $sale < $price): ?>
                                    <span style="text-decoration:line-through;color:#888;font-size:12px;">₹<?php echo number_format($price, 0); ?></span>
                                    <br><strong style="color:#00a32a;">₹<?php echo number_format($sale, 0); ?></strong>
                                <?php else: ?>
                                    <strong>₹<?php echo number_format($price, 0); ?></strong>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($v === 'active'): ?>
                                    <span style="background:#d4edda;color:#155724;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;">Active</span>
                                <?php else: ?>
                                    <span style="background:#f8d7da;color:#721c24;padding:3px 10px;border-radius:12px;font-size:12px;font-weight:600;">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($landing)): ?>
                                    <a href="<?php echo esc_url($landing); ?>" target="_blank" style="font-size:12px;word-break:break-all;"><?php echo esc_html(wp_parse_url($landing, PHP_URL_PATH)); ?></a>
                                <?php else: ?>
                                    <span style="color:#888;font-size:12px;">—</span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:12px;color:#646970;">
                                <?php echo human_time_diff(strtotime($p->post_modified), current_time('timestamp')) . ' ago'; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url($edit_url); ?>" class="button button-small">Edit</a>
                                <a href="<?php echo esc_url($toggle_url); ?>" class="button button-small" style="color:<?php echo $v === 'active' ? '#d63638' : '#00a32a'; ?>;">
                                    <?php echo $v === 'active' ? 'Hide' : 'Show'; ?>
                                </a>
                                <?php if (!empty($landing)): ?>
                                    <a href="<?php echo esc_url($landing); ?>" target="_blank" class="button button-small">View</a>
                                <?php elseif ($view_url): ?>
                                    <a href="<?php echo esc_url($view_url); ?>" target="_blank" class="button button-small">View</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>

        <p style="margin-top:12px;color:#646970;font-size:13px;">
            Showing <?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?>.
        </p>
    </div>

    <script>
    jQuery(function($) {
        $('#ep-select-all').on('change', function() {
            $('.ep-row-cb').prop('checked', this.checked);
            updateBulkCount();
        });
        $('.ep-row-cb').on('change', updateBulkCount);
        function updateBulkCount() {
            var c = $('.ep-row-cb:checked').length;
            $('#ep-bulk-count').text(c ? c + ' selected' : '');
        }
        $('#ep-bulk-submit').on('click', function(e) {
            if (!$('#ep-bulk-select').val()) { e.preventDefault(); return; }
            if ($('#ep-bulk-select').val() === 'bulk_delete' && !confirm('Are you sure you want to permanently delete selected products? This cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
    </script>
    <?php
}

/* ============================================================
   6. ENHANCED META BOX — visibility, short desc, landing URL
   ============================================================ */

function eproduct_enhanced_meta_box() {
    add_meta_box(
        'eproduct_details',
        'Product Details',
        'eproduct_enhanced_meta_cb',
        'eproduct',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'eproduct_enhanced_meta_box');

function eproduct_enhanced_meta_cb($post) {
    wp_nonce_field('eproduct_enhanced_save', 'eproduct_enhanced_nonce');

    $visibility = eproduct_get_visibility($post->ID);
    $short_desc = get_post_meta($post->ID, '_eproduct_short_desc', true);
    $landing_url = get_post_meta($post->ID, '_eproduct_landing_url', true);
    $price = get_post_meta($post->ID, '_eproduct_price', true);
    $sale_price = get_post_meta($post->ID, '_eproduct_sale_price', true);
    $thumb_id = get_post_thumbnail_id($post->ID);
    ?>
    <style>.ep-meta-row{margin-bottom:14px;}.ep-meta-row label{display:block;font-weight:600;margin-bottom:4px;}.ep-meta-row input[type="text"],.ep-meta-row input[type="url"],.ep-meta-row input[type="number"],.ep-meta-row textarea,.ep-meta-row select{width:100%;max-width:400px;}.ep-meta-row textarea{min-height:80px;}.ep-meta-hint{color:#888;font-size:12px;margin-top:2px;}</style>

    <div class="ep-meta-row">
        <label>Frontend Visibility</label>
        <select name="eproduct_visibility" style="max-width:200px;">
            <option value="active" <?php selected($visibility, 'active'); ?>>Active — visible on frontend</option>
            <option value="hidden" <?php selected($visibility, 'hidden'); ?>>Hidden — not shown on frontend</option>
        </select>
        <div class="ep-meta-hint">Hidden products remain stored in WordPress but do not appear on the public product listing.</div>
    </div>

    <div class="ep-meta-row">
        <label>Price (₹)</label>
        <input type="number" step="1" min="0" name="eproduct_price" value="<?php echo esc_attr($price); ?>">
    </div>

    <div class="ep-meta-row">
        <label>Sale Price (₹) <em style="font-weight:normal;color:#888;">leave empty for no sale</em></label>
        <input type="number" step="1" min="0" name="eproduct_sale_price" value="<?php echo esc_attr($sale_price); ?>">
    </div>

    <div class="ep-meta-row">
        <label>Short Description</label>
        <textarea name="eproduct_short_desc" rows="3" placeholder="Brief description for product cards and listings..."><?php echo esc_textarea($short_desc); ?></textarea>
        <div class="ep-meta-hint">Shown on product cards and listing pages. Leave empty to use the post excerpt.</div>
    </div>

    <div class="ep-meta-row">
        <label>Landing Page URL</label>
        <input type="url" name="eproduct_landing_url" value="<?php echo esc_url($landing_url); ?>" placeholder="https://store.eproduct.org/store_example.php">
        <div class="ep-meta-hint">URL of the individual product landing page. Used for the "View" link and product card CTA.</div>
    </div>

    <div class="ep-meta-row">
        <label>Product Card Image</label>
        <div id="ep-enhanced-cover-wrap">
            <?php if ($thumb_id): ?>
                <?php echo wp_get_attachment_image($thumb_id, 'medium', false, array('id' => 'ep-enhanced-cover-prev', 'style' => 'max-width:220px;height:auto;display:block;margin-bottom:8px;border-radius:10px;')); ?>
            <?php endif; ?>
            <input type="hidden" name="_thumbnail_id" id="ep-enhanced-thumb-id" value="<?php echo esc_attr($thumb_id); ?>">
            <button type="button" class="button" id="ep-enhanced-cover-btn">Set image</button>
            <?php if ($thumb_id): ?>
                <button type="button" class="button" id="ep-enhanced-cover-rm">Remove image</button>
            <?php endif; ?>
        </div>
    </div>

    <?php
    $ep_cats = get_terms(array('taxonomy' => 'eproduct_category', 'hide_empty' => false));
    $ep_assigned = wp_get_post_terms($post->ID, 'eproduct_category', array('fields' => 'ids'));
    if (is_wp_error($ep_assigned)) $ep_assigned = array();
    ?>
    <div class="ep-meta-row">
        <label>Product Category</label>
        <?php if (!empty($ep_cats) && !is_wp_error($ep_cats)): ?>
            <div style="display:flex;flex-wrap:wrap;gap:10px 18px;margin:6px 0;">
                <?php foreach ($ep_cats as $c):
                    $chk = in_array((int)$c->term_id, $ep_assigned, true) ? 'checked' : '';
                ?>
                    <label style="display:inline-flex;align-items:center;gap:6px;font-weight:400;">
                        <input type="checkbox" name="eproduct_category[]" value="<?php echo esc_attr($c->term_id); ?>" <?php echo $chk; ?>>
                        <?php echo esc_html($c->name); ?>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <span style="color:#888;">No categories yet. Add one below.</span>
        <?php endif; ?>
        <input type="text" name="eproduct_new_category" placeholder="Or add a new category..." style="width:240px;margin-top:8px;">
    </div>
    <?php
}

function eproduct_enhanced_meta_save($post_id) {
    if (!isset($_POST['eproduct_enhanced_nonce']) || !wp_verify_nonce($_POST['eproduct_enhanced_nonce'], 'eproduct_enhanced_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['eproduct_visibility'])) {
        eproduct_set_visibility($post_id, sanitize_text_field($_POST['eproduct_visibility']));
    }
    if (isset($_POST['eproduct_price'])) {
        update_post_meta($post_id, '_eproduct_price', floatval($_POST['eproduct_price']));
    }
    if (isset($_POST['eproduct_sale_price'])) {
        $sp = floatval($_POST['eproduct_sale_price']);
        update_post_meta($post_id, '_eproduct_sale_price', $sp > 0 ? $sp : '');
    }
    if (isset($_POST['eproduct_short_desc'])) {
        update_post_meta($post_id, '_eproduct_short_desc', sanitize_textarea_field($_POST['eproduct_short_desc']));
    }
    if (isset($_POST['eproduct_landing_url'])) {
        update_post_meta($post_id, '_eproduct_landing_url', esc_url_raw($_POST['eproduct_landing_url']));
    }
    if (isset($_POST['_thumbnail_id'])) {
        $tid = intval($_POST['_thumbnail_id']);
        if ($tid > 0) set_post_thumbnail($post_id, $tid);
        else delete_post_thumbnail($post_id);
    }

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

    eproduct_invalidate_cache($post_id);
}
add_action('save_post_eproduct', 'eproduct_enhanced_meta_save');

/* ============================================================
   7. MEDIA UPLOADER JS — for enhanced meta box
   ============================================================ */

function eproduct_enhanced_media_enqueue($hook) {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'eproduct') {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'eproduct_enhanced_media_enqueue');

function eproduct_enhanced_media_js() {
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'eproduct') return;
    ?>
    <script>
    jQuery(function($) {
        var frame;
        $('#ep-enhanced-cover-btn').on('click', function(e) {
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({ title: 'Product Card Image', button: { text: 'Use this image' }, multiple: false });
            frame.on('select', function() {
                var att = frame.state().get('selection').first().toJSON();
                $('#ep-enhanced-thumb-id').val(att.id);
                var url = (att.sizes && att.sizes.medium) ? att.sizes.medium.url : att.url;
                $('#ep-enhanced-cover-prev').remove();
                $('<img id="ep-enhanced-cover-prev" src="' + url + '" style="max-width:220px;height:auto;display:block;margin-bottom:8px;border-radius:10px;">').insertBefore('#ep-enhanced-thumb-id');
                if (!$('#ep-enhanced-cover-rm').length) $('<button type="button" class="button" id="ep-enhanced-cover-rm">Remove image</button>').insertAfter('#ep-enhanced-cover-btn');
            });
            frame.open();
        });
        $('#ep-enhanced-cover-rm').on('click', function(e) {
            e.preventDefault();
            $('#ep-enhanced-thumb-id').val('');
            $('#ep-enhanced-cover-prev').remove();
            $(this).remove();
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'eproduct_enhanced_media_js');

/* ============================================================
   8. REST API — public endpoints for frontend
   ============================================================ */

function eproduct_register_rest_routes() {
    register_rest_route('eproduct/v1', '/products', array(
        'methods'             => 'GET',
        'callback'            => 'eproduct_rest_get_products',
        'permission_callback' => '__return_true',
        'args' => array(
            'per_page' => array('default' => -1, 'type' => 'integer'),
            'category' => array('default' => '', 'type' => 'string'),
            'search'   => array('default' => '', 'type' => 'string'),
        ),
    ));

    register_rest_route('eproduct/v1', '/products/(?P<id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'eproduct_rest_get_product',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('eproduct/v1', '/admin/products/(?P<id>\d+)/visibility', array(
        'methods'             => 'POST',
        'callback'            => 'eproduct_rest_toggle_visibility',
        'permission_callback' => function() {
            return current_user_can('manage_options');
        },
    ));
}
add_action('rest_api_init', 'eproduct_register_rest_routes');

function eproduct_rest_get_products($request) {
    $args = array(
        'post_type'      => 'eproduct',
        'posts_per_page' => intval($request->get_param('per_page')),
        'post_status'    => 'publish',
        'meta_query'     => eproduct_active_meta_query(),
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    );
    $cat = $request->get_param('category');
    if ($cat) {
        $args['tax_query'] = array(array('taxonomy' => 'eproduct_category', 'field' => 'slug', 'terms' => $cat));
    }
    $search = $request->get_param('search');
    if ($search) $args['s'] = $search;

    $query = new WP_Query($args);
    $products = array();

    foreach ($query->posts as $p) {
        $products[] = eproduct_format_product($p);
    }

    return rest_ensure_response($products);
}

function eproduct_rest_get_product($request) {
    $id = intval($request->get_param('id'));
    $post = get_post($id);
    if (!$post || $post->post_type !== 'eproduct') {
        return new WP_Error('not_found', 'Product not found', array('status' => 404));
    }
    return rest_ensure_response(eproduct_format_product($post));
}

function eproduct_format_product($post) {
    $price = floatval(get_post_meta($post->ID, '_eproduct_price', true));
    $sale_price = get_post_meta($post->ID, '_eproduct_sale_price', true);
    $sale_price = ($sale_price !== '' && floatval($sale_price) > 0) ? floatval($sale_price) : null;
    $short_desc = get_post_meta($post->ID, '_eproduct_short_desc', true);
    $landing_url = get_post_meta($post->ID, '_eproduct_landing_url', true);
    $thumb = get_the_post_thumbnail_url($post->ID, 'medium');
    $cats = wp_get_post_terms($post->ID, 'eproduct_category', array('fields' => 'names'));

    return array(
        'id'              => $post->ID,
        'title'           => $post->post_title,
        'slug'            => $post->post_name,
        'price'           => $price,
        'sale_price'      => $sale_price,
        'short_desc'      => $short_desc,
        'excerpt'         => $post->post_excerpt,
        'landing_url'     => $landing_url,
        'permalink'       => get_permalink($post->ID),
        'image'           => $thumb ? $thumb : null,
        'categories'      => is_wp_error($cats) ? array() : $cats,
        'visibility'      => eproduct_get_visibility($post->ID),
        'date_created'    => $post->post_date,
        'date_modified'   => $post->post_modified,
    );
}

function eproduct_rest_toggle_visibility($request) {
    $id = intval($request->get_param('id'));
    $post = get_post($id);
    if (!$post || $post->post_type !== 'eproduct') {
        return new WP_Error('not_found', 'Product not found', array('status' => 404));
    }
    $body = $request->get_json_params();
    $status = isset($body['visibility']) ? sanitize_text_field($body['visibility']) : '';
    if (!in_array($status, array('active', 'hidden'))) {
        return new WP_Error('invalid_status', 'Status must be active or hidden', array('status' => 400));
    }
    eproduct_set_visibility($id, $status);
    return rest_ensure_response(array('id' => $id, 'visibility' => $status));
}

/* ============================================================
   9. DEFAULT WP LIST TABLE — add visibility column
   ============================================================ */

function eproduct_add_visibility_column($columns) {
    $new = array();
    foreach ($columns as $k => $v) {
        $new[$k] = $v;
        if ($k === 'title') {
            $new['ep_visibility'] = 'Visibility';
        }
    }
    return $new;
}
add_filter('manage_eproduct_posts_columns', 'eproduct_add_visibility_column');

function eproduct_render_visibility_column($column, $post_id) {
    if ($column !== 'ep_visibility') return;
    $v = eproduct_get_visibility($post_id);
    $toggle_url = wp_nonce_url(
        admin_url('admin.php?page=eproduct-manage&ep_toggle=' . $post_id),
        'ep_toggle_' . $post_id
    );
    if ($v === 'active') {
        echo '<span style="color:#00a32a;font-weight:600;">Active</span> ';
        echo '<a href="' . esc_url($toggle_url) . '" style="color:#d63638;font-size:12px;">Hide</a>';
    } else {
        echo '<span style="color:#d63638;font-weight:600;">Hidden</span> ';
        echo '<a href="' . esc_url($toggle_url) . '" style="color:#00a32a;font-size:12px;">Show</a>';
    }
}
add_action('manage_eproduct_posts_custom_column', 'eproduct_render_visibility_column', 10, 2);

function eproduct_sortable_visibility($columns) {
    $columns['ep_visibility'] = 'ep_visibility';
    return $columns;
}
add_filter('manage_edit-eproduct_sortable_columns', 'eproduct_sortable_visibility');

function eproduct_sort_by_visibility($query) {
    if (!is_admin() || !$query->is_main_query()) return;
    if ($query->get('orderby') === 'ep_visibility') {
        $query->set('meta_key', '_eproduct_visibility');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'eproduct_sort_by_visibility');

/* ============================================================
   10. ADMIN COLUMN — show price
   ============================================================ */

function eproduct_add_price_column($columns) {
    $new = array();
    foreach ($columns as $k => $v) {
        $new[$k] = $v;
        if ($k === 'ep_visibility') {
            $new['ep_price'] = 'Price';
        }
    }
    return $new;
}
add_filter('manage_eproduct_posts_columns', 'eproduct_add_price_column');

function eproduct_render_price_column($column, $post_id) {
    if ($column !== 'ep_price') return;
    $price = floatval(get_post_meta($post_id, '_eproduct_price', true));
    $sale = get_post_meta($post_id, '_eproduct_sale_price', true);
    $sale = ($sale !== '' && floatval($sale) > 0) ? floatval($sale) : '';
    if ($sale !== '' && $sale < $price) {
        echo '<span style="text-decoration:line-through;color:#888;font-size:12px;">₹' . number_format($price, 0) . '</span> ';
        echo '<strong style="color:#00a32a;">₹' . number_format($sale, 0) . '</strong>';
    } else {
        echo '<strong>₹' . number_format($price, 0) . '</strong>';
    }
}
add_action('manage_eproduct_posts_custom_column', 'eproduct_render_price_column', 10, 2);

/* ============================================================
   11. ADMIN NOTICES
   ============================================================ */

function eproduct_admin_notices() {
    if (isset($_GET['page']) && $_GET['page'] === 'eproduct-manage') {
        if (isset($_GET['toggled'])) {
            echo '<div class="notice notice-success is-dismissible"><p>Product visibility updated.</p></div>';
        }
        if (isset($_GET['bulk'])) {
            echo '<div class="notice notice-success is-dismissible"><p>Bulk action completed.</p></div>';
        }
    }
}
add_action('admin_notices', 'eproduct_admin_notices');

/* ============================================================
   12. HIDE FROM FRONTEND — handled by cpt-products.php + mu-plugin
   ============================================================ */

/* The mu-plugin removes eproduct_hide_from_frontend from cpt-products.php.
   The visibility filter (priority 5) handles filtering hidden products
   from frontend queries. For secondary queries (shortcodes etc.),
   visibility meta_query is added directly in templates.php, ajax.php,
   and single-eproduct.php. */
