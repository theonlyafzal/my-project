<?php
/**
 * eProduct Product Manager
 * 
 * Adds a professional product management system to WordPress admin:
 * - Custom admin list table with filtering, search, sorting
 * - Dashboard summary (total, active, hidden)
 * - Enhanced product editor with visibility, image management
 * - REST API for frontend consumption
 * - Visibility system (active/hidden) — no deletion
 * - Cache invalidation on save
 */
defined('ABSPATH') || exit;

/* ============================================================
   1. VISIBILITY META FIELD — default 'active'
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

/* ============================================================
   2. FILTER HIDDEN PRODUCTS FROM FRONTEND QUERIES
   ============================================================ */

function eproduct_filter_hidden_products($query) {
    if (is_admin()) return;
    if (!$query->is_main_query()) return;

    $post_type = $query->get('post_type');
    if (is_string($post_type) && $post_type === 'eproduct') {
        $query->set('meta_query', array(
            'relation' => 'OR',
            array(
                'key'     => '_eproduct_visibility',
                'value'   => 'active',
                'compare' => '=',
            ),
            array(
                'key'     => '_eproduct_visibility',
                'compare' => 'NOT EXISTS',
            ),
        ));
    } elseif (is_array($post_type) && in_array('eproduct', $post_type)) {
        $existing_meta = $query->get('meta_query');
        if (!is_array($existing_meta)) $existing_meta = array();
        $existing_meta[] = array(
            'relation' => 'OR',
            array(
                'key'     => '_eproduct_visibility',
                'value'   => 'active',
                'compare' => '=',
            ),
            array(
                'key'     => '_eproduct_visibility',
                'compare' => 'NOT EXISTS',
            ),
        );
        $query->set('meta_query', $existing_meta);
    }
}
add_action('pre_get_posts', 'eproduct_filter_hidden_products', 5);

/* ============================================================
   3. CACHE INVALIDATION
   ============================================================ */

function eproduct_invalidate_cache($post_id = 0) {
    if ($post_id > 0) {
        clean_post_cache($post_id);
    }
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
}

/* ============================================================
   4. META BOX — enhanced fields in cpt-products.php
   ============================================================ */

/* ============================================================
   5. MEDIA UPLOADER JS — handled by cpt-products.php
   ============================================================ */

/* ============================================================
   6. ADMIN PRODUCT LIST TABLE
   ============================================================ */

function eproduct_manage_products_page() {
    if (!current_user_can('manage_options')) return;

    // Handle bulk actions
    if (isset($_POST['ep_action']) && check_admin_referer('ep_bulk_products')) {
        $action = sanitize_text_field($_POST['ep_action']);
        $ids = array_map('intval', $_POST['product_ids']);
        if (!empty($ids)) {
            foreach ($ids as $id) {
                if (!current_user_can('edit_post', $id)) continue;
                if ($action === 'show') {
                    eproduct_set_visibility($id, 'active');
                } elseif ($action === 'hide') {
                    eproduct_set_visibility($id, 'hidden');
                } elseif ($action === 'delete') {
                    if (current_user_can('delete_post', $id)) {
                        wp_delete_post($id, true);
                    }
                }
            }
            eproduct_invalidate_cache();
        }
    }

    // Handle single product actions
    if (isset($_GET['ep_toggle'])) {
        if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'ep_toggle_' . $_GET['ep_toggle'])) {
            wp_die('Security check failed. Please go back and try again.');
        }
        $toggle_id = intval($_GET['ep_toggle']);
        if (current_user_can('edit_post', $toggle_id)) {
            $current = eproduct_get_visibility($toggle_id);
            $new = ($current === 'active') ? 'hidden' : 'active';
            eproduct_set_visibility($toggle_id, $new);
            eproduct_invalidate_cache();
        }
        wp_redirect(admin_url('admin.php?page=eproduct-manage'));
        exit;
    }

    // Handle permanent delete
    if (isset($_GET['ep_delete'])) {
        if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'ep_delete_' . $_GET['ep_delete'])) {
            wp_die('Security check failed. Please go back and try again.');
        }
        $del_id = intval($_GET['ep_delete']);
        if (current_user_can('delete_post', $del_id)) {
            wp_delete_post($del_id, true);
            eproduct_invalidate_cache();
        }
        wp_redirect(admin_url('admin.php?page=eproduct-manage'));
        exit;
    }

    // Query parameters
    $status_filter = isset($_GET['ep_status']) ? sanitize_text_field($_GET['ep_status']) : 'all';
    $search        = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $orderby       = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date';
    $order         = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';
    $paged         = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $per_page      = 20;

    // Build query
    $args = array(
        'post_type'      => 'eproduct',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
    );

    // Search
    if ($search) {
        $args['s'] = $search;
    }

    // Status filter
    $meta_query = array();
    if ($status_filter === 'active') {
        $meta_query[] = array(
            'key'     => '_eproduct_visibility',
            'value'   => 'active',
            'compare' => '=',
        );
    } elseif ($status_filter === 'hidden') {
        $meta_query[] = array(
            'key'     => '_eproduct_visibility',
            'value'   => 'hidden',
            'compare' => '=',
        );
    } else {
        $meta_query[] = array(
            'relation' => 'OR',
            array(
                'key'     => '_eproduct_visibility',
                'value'   => 'active',
                'compare' => '=',
            ),
            array(
                'key'     => '_eproduct_visibility',
                'compare' => 'NOT EXISTS',
            ),
        );
    }
    $args['meta_query'] = $meta_query;

    // Orderby
    $valid_orderby = array('title', 'date', 'modified', 'price');
    if (in_array($orderby, $valid_orderby)) {
        if ($orderby === 'price') {
            $args['meta_key'] = '_eproduct_price';
            $args['orderby']  = 'meta_value_num';
        } else {
            $args['orderby'] = $orderby;
        }
    } else {
        $args['orderby'] = 'date';
    }
    $args['order'] = ($order === 'ASC') ? 'ASC' : 'DESC';

    $query = new WP_Query($args);
    $total_products = $query->found_posts;
    $total_pages = $query->max_num_pages;

    // Counts for dashboard
    $all_count = count(get_posts(array('post_type' => 'eproduct', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids')));
    $active_count = count(get_posts(array(
        'post_type' => 'eproduct', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids',
        'meta_query' => array(array('key' => '_eproduct_visibility', 'value' => 'active', 'compare' => '=')),
    )));
    // Products with no visibility set are treated as active
    $no_vis_count = count(get_posts(array(
        'post_type' => 'eproduct', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids',
        'meta_query' => array(array('key' => '_eproduct_visibility', 'compare' => 'NOT EXISTS')),
    )));
    $active_count = $active_count + $no_vis_count;
    $hidden_count = $all_count - $active_count;
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Product Manager</h1>
        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=eproduct')); ?>" class="page-title-action">Add New</a>

        <!-- Dashboard Summary -->
        <div style="display:flex;gap:16px;margin:16px 0;flex-wrap:wrap;">
            <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:16px 24px;min-width:160px;">
                <div style="font-size:28px;font-weight:700;color:#1d2327;"><?php echo $all_count; ?></div>
                <div style="color:#646970;font-size:13px;">Total Products</div>
            </div>
            <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:16px 24px;min-width:160px;">
                <div style="font-size:28px;font-weight:700;color:#00a88a;"><?php echo $active_count; ?></div>
                <div style="color:#646970;font-size:13px;">Active (Visible)</div>
            </div>
            <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:16px 24px;min-width:160px;">
                <div style="font-size:28px;font-weight:700;color:#d63638;"><?php echo $hidden_count; ?></div>
                <div style="color:#646970;font-size:13px;">Hidden</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <ul class="subsubsub">
            <li><a href="<?php echo esc_url(admin_url('admin.php?page=eproduct-manage&ep_status=all' . ($search ? '&s=' . urlencode($search) : ''))); ?>" class="<?php echo $status_filter === 'all' ? 'current' : ''; ?>">All <span class="count">(<?php echo $all_count; ?>)</span></a> |</li>
            <li><a href="<?php echo esc_url(admin_url('admin.php?page=eproduct-manage&ep_status=active' . ($search ? '&s=' . urlencode($search) : ''))); ?>" class="<?php echo $status_filter === 'active' ? 'current' : ''; ?>">Active <span class="count">(<?php echo $active_count; ?>)</span></a> |</li>
            <li><a href="<?php echo esc_url(admin_url('admin.php?page=eproduct-manage&ep_status=hidden' . ($search ? '&s=' . urlencode($search) : ''))); ?>" class="<?php echo $status_filter === 'hidden' ? 'current' : ''; ?>">Hidden <span class="count">(<?php echo $hidden_count; ?>)</span></a></li>
        </ul>

        <!-- Search -->
        <form method="get" style="float:right;">
            <input type="hidden" name="page" value="eproduct-manage">
            <input type="hidden" name="ep_status" value="<?php echo esc_attr($status_filter); ?>">
            <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search products…" style="width:250px;">
            <button type="submit" class="button">Search</button>
        </form>

        <br class="clear">

        <!-- Bulk Actions Form -->
        <form method="post" id="ep-products-form">
            <?php wp_nonce_field('ep_bulk_products'); ?>

            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
                    <select name="ep_bulk_action">
                        <option value="">Bulk Actions</option>
                        <option value="show">Show (Make Active)</option>
                        <option value="hide">Hide</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="button action" id="ep-bulk-apply">Apply</button>
                </div>

                <!-- Sort Links -->
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo $total_products; ?> items</span>
                    <?php
                    if ($total_pages > 1) {
                        echo '<span class="pagination-links">';
                        echo paginate_links(array(
                            'base'    => add_query_arg('paged', '%#%'),
                            'format'  => '',
                            'current' => $paged,
                            'total'   => $total_pages,
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'echo'    => false,
                        ));
                        echo '</span>';
                    }
                    ?>
                </div>
            </div>

            <!-- Sort Headers -->
            <?php
            $base_url = admin_url('admin.php?page=eproduct-manage&ep_status=' . $status_filter . ($search ? '&s=' . urlencode($search) : ''));
            ?>

            <table class="wp-list-table widefat fixed striped" style="table-layout:fixed;">
                <thead>
                    <tr>
                        <td style="width:30px;" class="manage-column column-cb check-column"><input type="checkbox" id="cb-select-all-1"></td>
                        <th style="width:60px;"><?php
                            $new_o = ($orderby === 'date' && $order === 'DESC') ? 'ASC' : 'DESC';
                            $arr = ($orderby === 'date') ? ($order === 'DESC' ? ' &#9660;' : ' &#9650;') : '';
                            echo '<a href="' . esc_url(add_query_arg(array('orderby' => 'date', 'order' => $new_o), $base_url)) . '">Image' . $arr . '</a>';
                        ?></th>
                        <th><?php
                            $new_o = ($orderby === 'title' && $order === 'DESC') ? 'ASC' : 'DESC';
                            $arr = ($orderby === 'title') ? ($order === 'DESC' ? ' &#9660;' : ' &#9650;') : '';
                            echo '<a href="' . esc_url(add_query_arg(array('orderby' => 'title', 'order' => $new_o), $base_url)) . '">Product' . $arr . '</a>';
                        ?></th>
                        <th style="width:100px;"><?php
                            $new_o = ($orderby === 'price' && $order === 'DESC') ? 'ASC' : 'DESC';
                            $arr = ($orderby === 'price') ? ($order === 'DESC' ? ' &#9660;' : ' &#9650;') : '';
                            echo '<a href="' . esc_url(add_query_arg(array('orderby' => 'price', 'order' => $new_o), $base_url)) . '">Price' . $arr . '</a>';
                        ?></th>
                        <th style="width:100px;">Status</th>
                        <th style="width:150px;">Landing Page</th>
                        <th style="width:120px;"><?php
                            $new_o = ($orderby === 'modified' && $order === 'DESC') ? 'ASC' : 'DESC';
                            $arr = ($orderby === 'modified') ? ($order === 'DESC' ? ' &#9660;' : ' &#9650;') : '';
                            echo '<a href="' . esc_url(add_query_arg(array('orderby' => 'modified', 'order' => $new_o), $base_url)) . '">Updated' . $arr . '</a>';
                        ?></th>
                        <th style="width:200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($query->have_posts()): while ($query->have_posts()): $query->the_post();
                        $pid = get_the_ID();
                        $vis = eproduct_get_visibility($pid);
                        $price = get_post_meta($pid, '_eproduct_price', true);
                        $sale = get_post_meta($pid, '_eproduct_sale_price', true);
                        $link = get_post_meta($pid, '_eproduct_link', true);
                        $thumb = get_the_post_thumbnail_url($pid, 'thumbnail');
                        $cats = wp_get_post_terms($pid, 'eproduct_category', array('fields' => 'names'));
                        $landing_url = !empty($link) ? $link : get_permalink($pid);
                    ?>
                    <tr>
                        <th class="check-column"><input type="checkbox" name="product_ids[]" value="<?php echo $pid; ?>"></th>
                        <td>
                            <?php if ($thumb): ?>
                                <img src="<?php echo esc_url($thumb); ?>" alt="" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                            <?php else: ?>
                                <div style="width:50px;height:50px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#999;font-size:18px;">—</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><a href="<?php echo esc_url(get_edit_post_link($pid)); ?>"><?php echo esc_html(get_the_title()); ?></a></strong>
                            <?php if (!empty($cats)): ?>
                                <div style="color:#666;font-size:12px;margin-top:2px;"><?php echo esc_html(implode(', ', array_slice($cats, 0, 3))); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($sale && floatval($sale) > 0 && floatval($sale) < floatval($price)): ?>
                                <span style="text-decoration:line-through;color:#999;">₹<?php echo number_format($price, 0); ?></span>
                                <span style="color:#d63638;font-weight:600;">₹<?php echo number_format($sale, 0); ?></span>
                            <?php else: ?>
                                <strong>₹<?php echo number_format(floatval($price), 0); ?></strong>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($vis === 'active'): ?>
                                <span style="color:#00a88a;font-weight:600;">● Active</span>
                            <?php else: ?>
                                <span style="color:#d63638;font-weight:600;">● Hidden</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url($landing_url); ?>" target="_blank" style="color:#2271b1;text-decoration:none;font-size:12px;">
                                <?php echo esc_html(wp_parse_url($landing_url, PHP_URL_PATH)); ?>
                            </a>
                        </td>
                        <td><?php echo get_the_modified_date('M j, Y'); ?></td>
                        <td>
                            <a href="<?php echo esc_url(get_edit_post_link($pid)); ?>" class="button button-small">Edit</a>
                            <a href="<?php echo esc_url($landing_url); ?>" target="_blank" class="button button-small">View</a>
                            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=eproduct-manage&ep_toggle=' . $pid), 'ep_toggle_' . $pid)); ?>" class="button button-small" style="color:<?php echo $vis === 'active' ? '#d63638' : '#00a88a'; ?>;">
                                <?php echo $vis === 'active' ? 'Hide' : 'Show'; ?>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="8" style="text-align:center;padding:40px;color:#666;">
                        <?php echo $search ? 'No products found matching "' . esc_html($search) . '"' : 'No products yet. <a href="' . esc_url(admin_url('post-new.php?post_type=eproduct')) . '">Create your first product</a>.'; ?>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
    </div>

    <script>
    jQuery(function($) {
        // Bulk action submit
        $('#ep-bulk-apply').on('click', function(e) {
            e.preventDefault();
            var action = $('select[name="ep_bulk_action"]').val();
            if (!action) { alert('Please select a bulk action.'); return; }
            if (action === 'delete' && !confirm('Are you sure you want to permanently delete the selected products? This action cannot be undone.')) return;
            // Rename select to match PHP expectation
            $('select[name="ep_bulk_action"]').attr('name', 'ep_action');
            $('#ep-products-form').submit();
        });
        // Select all checkbox
        $('#cb-select-all-1').on('change', function() {
            $('input[name="product_ids[]"]').prop('checked', this.checked);
        });
    });
    </script>
    <?php
}

/* ============================================================
   8. ADMIN MENU ITEMS
   ============================================================ */

function eproduct_product_manager_menu() {
    add_submenu_page(
        'eproduct-settings',
        'Product Manager',
        'Product Manager',
        'manage_options',
        'eproduct-manage',
        'eproduct_manage_products_page'
    );
}
add_action('admin_menu', 'eproduct_product_manager_menu');

/* ============================================================
   9. REST API — public endpoints for frontend
   ============================================================ */

function eproduct_register_rest_routes() {
    register_rest_route('eproduct/v1', '/products', array(
        'methods'             => 'GET',
        'callback'            => 'eproduct_rest_get_products',
        'permission_callback' => '__return_true',
        'args' => array(
            'status' => array(
                'default'           => 'active',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'per_page' => array(
                'default'           => 20,
                'sanitize_callback' => 'absint',
            ),
            'page' => array(
                'default'           => 1,
                'sanitize_callback' => 'absint',
            ),
            'category' => array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'search' => array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'orderby' => array(
                'default'           => 'date',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'order' => array(
                'default'           => 'DESC',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
    ));

    register_rest_route('eproduct/v1', '/products/(?P<id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'eproduct_rest_get_product',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'sanitize_callback' => 'absint',
            ),
        ),
    ));
}
add_action('rest_api_init', 'eproduct_register_rest_routes');

function eproduct_rest_get_products($request) {
    $status   = $request->get_param('status');
    $per_page = $request->get_param('per_page');
    $page     = $request->get_param('page');
    $category = $request->get_param('category');
    $search   = $request->get_param('search');
    $orderby  = $request->get_param('orderby');
    $order    = $request->get_param('order');

    $args = array(
        'post_type'      => 'eproduct',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
    );

    // Visibility filter
    if ($status === 'active') {
        $args['meta_query'] = array(
            'relation' => 'OR',
            array('key' => '_eproduct_visibility', 'value' => 'active', 'compare' => '='),
            array('key' => '_eproduct_visibility', 'compare' => 'NOT EXISTS'),
        );
    } elseif ($status === 'hidden') {
        $args['meta_query'] = array(
            array('key' => '_eproduct_visibility', 'value' => 'hidden', 'compare' => '='),
        );
    }

    // Category filter
    if ($category) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'eproduct_category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }

    // Search
    if ($search) {
        $args['s'] = $search;
    }

    // Orderby
    if ($orderby === 'price') {
        $args['meta_key'] = '_eproduct_price';
        $args['orderby']  = 'meta_value_num';
    } elseif (in_array($orderby, array('title', 'date', 'modified'))) {
        $args['orderby'] = $orderby;
    }
    $args['order'] = ($order === 'ASC') ? 'ASC' : 'DESC';

    $query = new WP_Query($args);
    $products = array();

    while ($query->have_posts()) {
        $query->the_post();
        $pid = get_the_ID();
        $products[] = eproduct_format_product_data($pid);
    }
    wp_reset_postdata();

    return rest_ensure_response(array(
        'products' => $products,
        'total'    => $query->found_posts,
        'pages'    => $query->max_num_pages,
    ));
}

function eproduct_rest_get_product($request) {
    $id = $request->get_param('id');
    $post = get_post($id);

    if (!$post || $post->post_type !== 'eproduct') {
        return new WP_Error('product_not_found', 'Product not found.', array('status' => 404));
    }

    return rest_ensure_response(eproduct_format_product_data($id));
}

function eproduct_format_product_data($post_id) {
    $price      = floatval(get_post_meta($post_id, '_eproduct_price', true));
    $sale_price = get_post_meta($post_id, '_eproduct_sale_price', true);
    $sale_price = ($sale_price !== '' && floatval($sale_price) > 0) ? floatval($sale_price) : null;
    $file       = get_post_meta($post_id, '_eproduct_file', true);
    $link       = get_post_meta($post_id, '_eproduct_link', true);
    $short_desc = get_post_meta($post_id, '_eproduct_short_desc', true);
    $visibility = eproduct_get_visibility($post_id);
    $thumb_id   = get_post_thumbnail_id($post_id);
    $cats       = wp_get_post_terms($post_id, 'eproduct_category', array('fields' => 'all'));

    $cat_data = array();
    if (!is_wp_error($cats)) {
        foreach ($cats as $c) {
            $cat_data[] = array(
                'id'   => $c->term_id,
                'name' => $c->name,
                'slug' => $c->slug,
            );
        }
    }

    return array(
        'id'              => $post_id,
        'title'           => get_the_title($post_id),
        'slug'            => get_post_field('post_name', $post_id),
        'description'     => wpautop(get_the_content($post_id)),
        'excerpt'         => $short_desc ? $short_desc : get_the_excerpt($post_id),
        'price'           => $price,
        'sale_price'      => $sale_price,
        'price_display'   => $sale_price && $sale_price < $price ? $sale_price : $price,
        'on_sale'         => $sale_price !== null && $sale_price < $price,
        'currency'        => '₹',
        'image'           => array(
            'id'     => $thumb_id,
            'url'    => get_the_post_thumbnail_url($post_id, 'full'),
            'medium' => get_the_post_thumbnail_url($post_id, 'medium'),
            'thumb'  => get_the_post_thumbnail_url($post_id, 'thumbnail'),
        ),
        'categories'      => $cat_data,
        'landing_page'    => !empty($link) ? $link : get_permalink($post_id),
        'custom_link'     => $link,
        'download_file'   => $file,
        'visibility'      => $visibility,
        'date_created'    => get_the_date('c', $post_id),
        'date_modified'   => get_the_modified_date('c', $post_id),
        'permalink'       => get_permalink($post_id),
    );
}

/* ============================================================
   10. REST API — admin endpoints (protected)
   ============================================================ */

function eproduct_register_admin_rest_routes() {
    register_rest_route('eproduct/v1', '/admin/products/(?P<id>\d+)/visibility', array(
        'methods'             => 'POST',
        'callback'            => 'eproduct_rest_update_visibility',
        'permission_callback' => function() {
            return current_user_can('manage_options');
        },
    ));
}
add_action('rest_api_init', 'eproduct_register_admin_rest_routes');

function eproduct_rest_update_visibility($request) {
    $id = $request->get_param('id');
    $status = $request->get_param('status');

    if (!in_array($status, array('active', 'hidden'))) {
        return new WP_Error('invalid_status', 'Status must be "active" or "hidden".', array('status' => 400));
    }

    $post = get_post($id);
    if (!$post || $post->post_type !== 'eproduct') {
        return new WP_Error('product_not_found', 'Product not found.', array('status' => 404));
    }

    eproduct_set_visibility($id, $status);

    return rest_ensure_response(array(
        'success'    => true,
        'visibility' => $status,
        'message'    => 'Product visibility updated to ' . $status . '.',
    ));
}

/* ============================================================
   11. ADD VISIBILITY COLUMN TO DEFAULT LIST TABLE
   ============================================================ */

function eproduct_manage_columns($columns) {
    $new = array();
    foreach ($columns as $key => $val) {
        $new[$key] = $val;
        if ($key === 'title') {
            $new['ep_visibility'] = 'Visibility';
        }
    }
    if (!isset($new['ep_visibility'])) {
        $new['ep_visibility'] = 'Visibility';
    }
    return $new;
}
add_filter('manage_eproduct_posts_columns', 'eproduct_manage_columns');

function eproduct_manage_column_content($column, $post_id) {
    if ($column === 'ep_visibility') {
        $vis = eproduct_get_visibility($post_id);
        if ($vis === 'active') {
            echo '<span style="color:#00a88a;font-weight:600;">● Active</span>';
        } else {
            echo '<span style="color:#d63638;font-weight:600;">● Hidden</span>';
        }
    }
}
add_action('manage_eproduct_posts_custom_column', 'eproduct_manage_column_content', 10, 2);

function eproduct_manage_sortable($columns) {
    $columns['ep_visibility'] = 'ep_visibility';
    return $columns;
}
add_filter('manage_edit-eproduct_sortable_columns', 'eproduct_manage_sortable');

function eproduct_visibility_sort($query) {
    if (!is_admin() || !$query->is_main_query()) return;
    if ($query->get('orderby') === 'ep_visibility') {
        $query->set('meta_key', '_eproduct_visibility');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'eproduct_visibility_sort');

/* ============================================================
   12. QUICK VISIBILITY TOGGLE LINK IN DEFAULT LIST
   ============================================================ */

function eproduct_quick_toggle_link($actions, $post) {
    if ($post->post_type !== 'eproduct') return $actions;

    $vis = eproduct_get_visibility($post->ID);
    $toggle_url = wp_nonce_url(
        admin_url('admin.php?page=eproduct-manage&ep_toggle=' . $post->ID),
        'ep_toggle_' . $post->ID
    );

    if ($vis === 'active') {
        $actions['ep_hide'] = '<a href="' . esc_url($toggle_url) . '" style="color:#d63638;">Hide</a>';
    } else {
        $actions['ep_show'] = '<a href="' . esc_url($toggle_url) . '" style="color:#00a88a;">Show</a>';
    }

    return $actions;
}
add_filter('post_row_actions', 'eproduct_quick_toggle_link', 10, 2);
