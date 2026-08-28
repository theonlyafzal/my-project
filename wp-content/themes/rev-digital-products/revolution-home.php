<?php
/**
 * Template Name: Home Page
 */

get_header();
?>

<main id="primary">

    <?php 
    $rev_digital_products_main_slider_wrap = absint(get_theme_mod('rev_digital_products_enable_slider', 0));
    if($rev_digital_products_main_slider_wrap == 1): 
    ?>
        <section id="main-slider-wrap">
            <div class="owl-carousel">
                <?php for ($rev_digital_products_main_i=1; $rev_digital_products_main_i <= 3; $rev_digital_products_main_i++): ?>
                    <?php if ($rev_digital_products_slider_image = get_theme_mod('rev_digital_products_slider_image'.$rev_digital_products_main_i)): ?>
                        <div class="main-slider-inner-box">
                            <img src="<?php echo esc_url($rev_digital_products_slider_image); ?>" alt="<?php echo esc_attr( get_theme_mod('rev_digital_products_slider_heading'.$rev_digital_products_main_i) ); ?>">
                            <div class="main-slider-content-box wow zoomIn" data-wow-duration="1.5s">
                                <?php if ($rev_digital_products_top_text = get_theme_mod('rev_digital_products_slider_top_text'.$rev_digital_products_main_i)): ?>
                                    <p class="slider-top"><?php echo esc_html($rev_digital_products_top_text); ?></p>
                                <?php endif; ?>
                                <?php if ($rev_digital_products_heading = get_theme_mod('rev_digital_products_slider_heading' . $rev_digital_products_main_i)): ?>
                                    <h1><?php echo esc_html($rev_digital_products_heading); ?></h1>
                                <?php endif; ?>

                                <?php if ($rev_digital_products_text = get_theme_mod('rev_digital_products_slider_text'.$rev_digital_products_main_i)): ?>
                                    <p class="slider-content"><?php echo esc_html($rev_digital_products_text); ?></p>
                                <?php endif; ?>
                                <div class="main-slider-button">
                                    <?php if ( get_theme_mod('rev_digital_products_slider_button1_link'.$rev_digital_products_main_i) ||  get_theme_mod('rev_digital_products_slider_button1_text'.$rev_digital_products_main_i )) : ?><a class="slide-btn-1" href="<?php echo esc_url( get_theme_mod('rev_digital_products_slider_button1_link'.$rev_digital_products_main_i) ); ?>"><?php echo esc_html( get_theme_mod('rev_digital_products_slider_button1_text'.$rev_digital_products_main_i) ); ?></a><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php 
    $rev_digital_products_main_expert_wrap = absint(get_theme_mod('rev_digital_products_projetcs_sec_show', 0));
    if ($rev_digital_products_main_expert_wrap == 1): 
    ?>
        <section id="best-seller">
            <div class="container">
                <div class="flex-row">
                    <div class="product-left">
                        <div class="title-head">
                            <?php 
                            $rev_digital_products_main_heading = get_theme_mod('rev_digital_products_projetcs_main_heading', '');
                            if (!empty($rev_digital_products_main_heading)) : ?>
                                <h2><?php echo esc_html($rev_digital_products_main_heading); ?></h2>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="product-center">
                        <div class="main-tab-col">
                            <div class="tab">
                                <?php 
                                for ($rev_digital_products_j = 1; $rev_digital_products_j <= 5; $rev_digital_products_j++) :
                                    $rev_digital_products_tab_text = get_theme_mod("rev_digital_products_projetcs_text$rev_digital_products_j", '');
                                    if ($rev_digital_products_tab_text) :
                                        $rev_digital_products_tab_id = sanitize_title($rev_digital_products_tab_text);
                                ?>
                                    <button class="tablinks" onclick="rev_digital_products_projetcs_tab(event, '<?php echo esc_attr($rev_digital_products_tab_id); ?>')">
                                        <?php echo esc_html($rev_digital_products_tab_text); ?>
                                    </button>
                                <?php 
                                    endif; 
                                endfor; 
                                ?>
                            </div>
                        </div>
                        <div class="product-btn">
                            <?php
                            $rev_digital_products_btn_text = get_theme_mod('rev_digital_products_product_section_btn_text1', __('VIEW ALL', 'rev-digital-products'));
                            $rev_digital_products_btn_link = get_theme_mod('rev_digital_products_product_section_btn_link1','#');

                            if (!empty($rev_digital_products_btn_text) && !empty($rev_digital_products_btn_link)) : ?>
                                <a class="viewall-btn" href="<?php echo esc_url($rev_digital_products_btn_link); ?>">
                                    <?php echo esc_html($rev_digital_products_btn_text); ?> <i class="fas fa-arrow-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php 
                for ($rev_digital_products_j = 1; $rev_digital_products_j <= 5; $rev_digital_products_j++) :
                    $rev_digital_products_tab_text = get_theme_mod("rev_digital_products_projetcs_text$rev_digital_products_j", '');
                    if ($rev_digital_products_tab_text) :
                        $rev_digital_products_tab_id = sanitize_title($rev_digital_products_tab_text);
                        $rev_digital_products_category_prod = get_theme_mod("rev_digital_products_projetcs_category$rev_digital_products_j");

                        if ($rev_digital_products_category_prod) :
                ?>
                    <div id="<?php echo esc_attr($rev_digital_products_tab_id); ?>" class="tabcontent posts-wrapper" style="display: none;">
                        <?php
                        $rev_digital_products_args = array(
                            'post_type' => 'product',
                            'posts_per_page' => 4,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field'    => 'slug',
                                    'terms'    => sanitize_text_field($rev_digital_products_category_prod),
                                ),
                            ),
                        );
                        $query = new WP_Query($rev_digital_products_args);

                        if ($query->have_posts()) :
                            while ($query->have_posts()) : $query->the_post();
                                $product = wc_get_product(get_the_ID());
                                if ($product) :
                        ?>
                            <div class="product-item wow zoomIn" data-wow-duration="2s">
                                <?php if ($product->is_on_sale()) : ?>
                                    <span class="sale-badge"><?php esc_html_e('SALE', 'rev-digital-products'); ?></span>
                                <?php endif; ?>
                                <div class="box">
                                    <div class="product-img <?php echo $product->is_on_sale() ? 'has-sale-badge' : ''; ?>">

                                        <!-- Product Image -->
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('full', ['alt' => get_the_title()]); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '../revolution/assets/images/woo-placeholder.png'); ?>" alt="<?php esc_attr_e('Product Image', 'rev-digital-products'); ?>">
                                        <?php endif; ?>
                                        <!-- Add to Cart Button -->
                                        <div class="cart-button">
                                            <?php if ($product->is_type('simple')) : ?>
                                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                                                   class="button add_to_cart_button"
                                                   data-quantity="1"
                                                   data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                                                   data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                                                   aria-label="<?php echo esc_attr($product->add_to_cart_text()); ?>"
                                                   rel="nofollow">
                                                    <?php echo esc_html($product->add_to_cart_text()); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Product Price -->
                                        <?php if ( $product->get_price() ) : ?>
                                            <p class="product-price">
                                                <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-content">
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <div class="product-description mb-3">
                                          <?php
                                            $rev_digital_products_content = get_the_content();
                                            $rev_digital_products_excerpt = wp_trim_words($rev_digital_products_content, 12);
                                            echo apply_filters('the_content', $rev_digital_products_excerpt);
                                          ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                                endif;
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                <?php 
                        endif;
                    endif; 
                endfor; 
                ?>
            </div>
        </section>
    <?php endif; ?>



</main>

<?php
get_footer();
?>