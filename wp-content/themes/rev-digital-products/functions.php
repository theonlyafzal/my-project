<?php
/**
 * Rev Digital Products functions and definitions
 *
 * @package Rev Digital Products
 */

if ( ! defined( 'REV_DIGITAL_PRODUCTS_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'REV_DIGITAL_PRODUCTS_VERSION', '1.0.0' );
}

function rev_digital_products_setup() {

	load_theme_textdomain( 'rev-digital-products', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( "align-wide" );
	add_theme_support( "responsive-embeds" );

	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'rev-digital-products' ),
			'social-menu' => esc_html__('Social Menu', 'rev-digital-products'),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-background',
		apply_filters(
			'rev_digital_products_custom_background_args',
			array(
				'default-color' => '#fafafa',
				'default-image' => '',
			)
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	add_theme_support( 'post-formats', array(
        'image',
        'video',
        'gallery',
        'audio', 
    ));
	
}
add_action( 'after_setup_theme', 'rev_digital_products_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function rev_digital_products_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'rev_digital_products_content_width', 640 );
}
add_action( 'after_setup_theme', 'rev_digital_products_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function rev_digital_products_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'rev-digital-products' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'rev-digital-products' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'rev-digital-products' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here.', 'rev-digital-products' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'rev-digital-products' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here.', 'rev-digital-products' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'rev-digital-products' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here.', 'rev-digital-products' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'rev_digital_products_widgets_init' );


function rev_digital_products_social_menu()
    {
        if (has_nav_menu('social-menu')) :
            wp_nav_menu(array(
                'theme_location' => 'social-menu',
                'container' => 'ul',
                'menu_class' => 'social-menu menu',
                'menu_id'  => 'menu-social',
            ));
        endif;
    }


/**
 * Enqueue scripts and styles.
 */

function rev_digital_products_scripts() {
    // Google Fonts
    $query_args = array(
        'family' => 'Montserrat:wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900| Libre+Baskerville:ital,wght@0,400;0,700;1,400',
        'display' => 'swap',
    );
    wp_enqueue_style('rev-digital-products-google-fonts', add_query_arg($query_args, 'https://fonts.googleapis.com/css'), array(), null);

    // Font Awesome CSS
    wp_enqueue_style('font-awesome-6', get_template_directory_uri() . '/revolution/assets/vendors/font-awesome-6/css/all.min.css', array(), '6.7.2');

    // Owl Carousel CSS
    wp_enqueue_style('owl-carousel-style', get_template_directory_uri() . '/revolution/assets/css/owl.carousel.css', array(), '2.3.4');
    
    // Main stylesheet
    wp_enqueue_style('rev-digital-products-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

    // Add custom inline styles
	require get_parent_theme_file_path('/custom-style.php');
	wp_add_inline_style('rev-digital-products-style', $rev_digital_products_custom_css);

    // RTL styles if needed
    wp_style_add_data('rev-digital-products-style', 'rtl', 'replace');

    // Navigation script
    wp_enqueue_script('rev-digital-products-navigation', get_template_directory_uri() . '/js/navigation.js', array(), wp_get_theme()->get('Version'), true);

    // Owl Carousel script
    wp_enqueue_script('owl-carousel-jquery', get_template_directory_uri() . '/revolution/assets/js/owl.carousel.js', array('jquery'), '2.3.4', true);

    // Wow script.
	wp_enqueue_script( 'wow-jquery', get_template_directory_uri() . '/revolution/assets/js/wow.js', array('jquery'),'' ,true );
	// Animate CSS
	wp_enqueue_style( 'animate-style', get_template_directory_uri() . '/revolution/assets/css/animate.css' );

    // Custom script
    wp_enqueue_script('rev-digital-products-custom-js', get_template_directory_uri() . '/revolution/assets/js/custom.js', array('jquery'), wp_get_theme()->get('Version'), true);

    // Comments reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
// Disabled: default theme asset stack (Montserrat / Owl / Font Awesome) is replaced by the
// eProduct storefront bundle below so the bundled UI stays consistent.
// add_action('wp_enqueue_scripts', 'rev_digital_products_scripts');

function eproduct_theme_storefront_assets() {
    wp_enqueue_style('eproduct-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null);
    wp_enqueue_style('rev-digital-products-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
    wp_enqueue_script('eproduct-ui', get_template_directory_uri() . '/js/eproduct-ui.js', array('jquery'), wp_get_theme()->get('Version'), true);
    wp_enqueue_script('eproduct-store', get_template_directory_uri() . '/js/store.js', array('jquery'), wp_get_theme()->get('Version'), true);
    wp_localize_script('eproduct-store', 'EP', array('ajax' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'eproduct_theme_storefront_assets');

if (!function_exists('rev_digital_products_related_post')) :
    /**
     * Display related posts from same category
     *
     */

    function rev_digital_products_related_post($post_id){        
        $rev_digital_products_categories = get_the_category($post_id);
        if ($rev_digital_products_categories) {
            $rev_digital_products_category_ids = array();
            $rev_digital_products_category = get_category($rev_digital_products_category_ids);
            $rev_digital_products_categories = get_the_category($post_id);
            foreach ($rev_digital_products_categories as $rev_digital_products_category) {
                $rev_digital_products_category_ids[] = $rev_digital_products_category->term_id;
            }
            $count = $rev_digital_products_category->category_count;
            if ($count > 1) { ?>

         	<?php
		$rev_digital_products_related_post_wrap = absint(get_theme_mod('rev_digital_products_enable_related_post', 1));
		if($rev_digital_products_related_post_wrap == 1){ ?>
                <div class="related-post">
                    
                    <h2 class="post-title"><?php esc_html_e(get_theme_mod('rev_digital_products_related_post_text', __('Related Post', 'rev-digital-products'))); ?></h2>
                    <?php
                    $rev_digital_products_cat_post_args = array(
                        'category__in' => $rev_digital_products_category_ids,
                        'post__not_in' => array($post_id),
                        'post_type' => 'post',
                        'posts_per_page' =>  get_theme_mod( 'rev_digital_products_related_post_count', '3' ),
                        'post_status' => 'publish',
						'orderby'           => 'rand',
                        'ignore_sticky_posts' => true
                    );
                    $rev_digital_products_featured_query = new WP_Query($rev_digital_products_cat_post_args);
                    ?>
                    <div class="rel-post-wrap">
                        <?php
                        if ($rev_digital_products_featured_query->have_posts()) :

                        while ($rev_digital_products_featured_query->have_posts()) : $rev_digital_products_featured_query->the_post();
                            ?>
                            <div class="card-item rel-card-item">
								<div class="card-content">
                                    <?php if ( has_post_thumbnail() ) { ?>
                                        <div class="card-media">
                                            <?php rev_digital_products_post_thumbnail(); ?>
                                        </div>
                                    <?php } else {
                                        // Fallback default image
                                        $rev_digital_products_default_post_thumbnail = get_template_directory_uri() . '/revolution/assets/images/PRODUCT-NAME-4.png';
                                        echo '<img class="default-post-img" src="' . esc_url( $rev_digital_products_default_post_thumbnail ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                                    } ?>
									<div class="entry-title">
										<h3>
											<a href="<?php the_permalink() ?>">
												<?php the_title(); ?>
											</a>
										</h3>
									</div>
									<div class="entry-meta">
                                        <?php
                                        rev_digital_products_posted_on();
                                        rev_digital_products_posted_by();
                                        ?>
                                    </div>
								</div>
							</div>
                        <?php
                        endwhile;
                        ?>
                <?php
                endif;
                wp_reset_postdata();
                ?>
                </div>
                <?php } ?>
                <?php
            }
        }
    }
endif;
add_action('rev_digital_products_related_posts', 'rev_digital_products_related_post', 10, 1);

/**
 * Checkbox sanitization callback example.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 */
function rev_digital_products_sanitize_checkbox($checked)
{
    // Boolean check.
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/revolution/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/revolution/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/revolution/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/revolution/inc/customizer.php';

/**
 * Breadcrumb File.
 */
require get_template_directory() . '/revolution/inc/breadcrumbs.php';

/**
 * Custom typography options for this theme.
 */
require get_template_directory() . '/revolution/inc/typography-options.php';

//////////////////////////////////////////////   Function for Translation Error   //////////////////////////////////////////////////////
function rev_digital_products_enqueue_function() {

    /**
    * GET START.
    */
    require get_template_directory() . '/getstarted/rev_digital_products_about_page.php';

    /**
    * DEMO IMPORT.
    */
    require get_template_directory() . '/demo-import/rev_digital_products_config_file.php';

    
    define('REV_DIGITAL_PRODUCTS_FREE_SUPPORT',__('https://wordpress.org/support/theme/rev-digital-products/','rev-digital-products'));
    define('REV_DIGITAL_PRODUCTS_PRO_SUPPORT',__('https://www.revolutionwp.com/pages/community/','rev-digital-products'));
    define('REV_DIGITAL_PRODUCTS_REVIEW',__('https://wordpress.org/support/theme/rev-digital-products/reviews/#new-post','rev-digital-products'));
    define('REV_DIGITAL_PRODUCTS_BUY_NOW',__('https://www.revolutionwp.com/products/digital-products-wordpress-theme','rev-digital-products'));
    define('REV_DIGITAL_PRODUCTS_LIVE_DEMO',__('https://demo.revolutionwp.com/digital-products-pro/','rev-digital-products'));
    define('REV_DIGITAL_PRODUCTS_PRO_DOC',__('https://demo.revolutionwp.com/wpdocs/digital-products-pro/','rev-digital-products'));
    define('REV_DIGITAL_PRODUCTS_LITE_DOC',__('https://demo.revolutionwp.com/wpdocs/digital-products-free/','rev-digital-products'));
    define('REV_DIGITAL_PRODUCTS_BUNDLE',__('https://www.revolutionwp.com/products/wordpress-theme-bundle','rev-digital-products'));
    
}
add_action( 'after_setup_theme', 'rev_digital_products_enqueue_function' );

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/revolution/inc/jetpack.php';

}

function rev_digital_products_remove_customize_register() {
    global $wp_customize;

    $wp_customize->remove_setting( 'display_header_text' );
    $wp_customize->remove_control( 'display_header_text' );

}

add_action( 'customize_register', 'rev_digital_products_remove_customize_register', 11 );

/************************************************************************************/
// //////////////////////////////////////////////

/**
 * WooCommerce custom filters
 */
add_filter('loop_shop_columns', 'rev_digital_products_loop_columns');

if (!function_exists('rev_digital_products_loop_columns')) {

	function rev_digital_products_loop_columns() {

		$rev_digital_products_columns = get_theme_mod( 'rev_digital_products_per_columns', 3 );

		return $rev_digital_products_columns;
	}
}

/************************************************************************************/

add_filter( 'loop_shop_per_page', 'rev_digital_products_per_page', 20 );

function rev_digital_products_per_page( $rev_digital_products_cols ) {

  	$rev_digital_products_cols = get_theme_mod( 'rev_digital_products_product_per_page', 9 );

	return $rev_digital_products_cols;
}

/************************************************************************************/

add_filter( 'woocommerce_output_related_products_args', 'rev_digital_products_products_args' );

function rev_digital_products_products_args( $rev_digital_products_args ) {

    $rev_digital_products_args['posts_per_page'] = get_theme_mod( 'custom_related_products_number', 6 );

    $rev_digital_products_args['columns'] = get_theme_mod( 'custom_related_products_number_per_row', 3 );

    return $rev_digital_products_args;
}

/************************************************************************************/

/**
 * Custom logo
 */

function rev_digital_products_custom_css() {
?>
	<style type="text/css" id="custom-theme-colors" >
        :root {
           
            --rev_digital_products_logo_width: <?php echo absint(get_theme_mod('rev_digital_products_logo_width')); ?> ;   
        }
        .site-branding img {
            max-width:<?php echo esc_html(get_theme_mod('rev_digital_products_logo_width')); ?>px ;    
        }         
	</style>
<?php
}
add_action( 'wp_head', 'rev_digital_products_custom_css' );

function rev_digital_products_sanitize_choices( $input, $setting ) {
    global $wp_customize; 
    $control = $wp_customize->get_control( $setting->id ); 
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}


//Excerpt 
function rev_digital_products_excerpt_function($rev_digital_products_excerpt_count = 35) {
    $rev_digital_products_excerpt = get_the_excerpt();
    $rev_digital_products_text_excerpt = wp_strip_all_tags($rev_digital_products_excerpt);
    $rev_digital_products_excerpt_limit = (int) get_theme_mod('rev_digital_products_excerpt_limit', $rev_digital_products_excerpt_count);
    $rev_digital_products_words = preg_split('/\s+/', $rev_digital_products_text_excerpt); 
    $rev_digital_products_trimmed_words = array_slice($rev_digital_products_words, 0, $rev_digital_products_excerpt_limit);
    $rev_digital_products_theme_excerpt = implode(' ', $rev_digital_products_trimmed_words);

    return $rev_digital_products_theme_excerpt;
}

// Add admin notice
function rev_digital_products_admin_notice() { 
    global $pagenow;
    $rev_digital_products_theme_args      = wp_get_theme();
    $rev_digital_products_meta            = get_option( 'rev_digital_products_admin_notice' );
    $name            = $rev_digital_products_theme_args->__get( 'Name' );
    $rev_digital_products_current_screen  = get_current_screen();

    if( !$rev_digital_products_meta ){
	    if( is_network_admin() ){
	        return;
	    }

	    if( ! current_user_can( 'manage_options' ) ){
	        return;
	    } 
		
		if( $rev_digital_products_current_screen->base !== 'appearance_page_rev_digital_products_guide' && 
            $rev_digital_products_current_screen->base !== 'toplevel_page_revdigitalproducts-demoimport' ) { ?>

            <div class="notice notice-success rev-digital-products-welcome-notice">
                <p class="rev-digital-products-dismiss-link">
                    <strong>
                        <a href="<?php echo esc_url( add_query_arg( 'rev_digital_products_admin_notice', '1' ) ); ?>">
                            <?php esc_html_e( 'Dismiss', 'rev-digital-products' ); ?>
                        </a>
                    </strong>
                </p>

                <div class="rev-digital-products-welcome-notice-wrap">
                    <h2 class="rev-digital-products-notice-title">
                        <span class="dashicons dashicons-admin-home"></span> 
                        <?php 
                            $rev_digital_products_theme_name = wp_get_theme()->get( 'Name' );
                            /* translators: %s!: Theme Name. */
                            echo esc_html( sprintf( __( 'Welcome to the free theme: %s!', 'rev-digital-products' ), $rev_digital_products_theme_name ) );
                        ?>
                    </h2>
                    <p class="rev-digital-products-notice-desc">
                        <?php esc_html_e( 'Get started by exploring the features of your new theme. Customize your design, add your content, and create a site that fits your vision.', 'rev-digital-products' ); ?>
                    </p>

                    <div class="rev-digital-products-welcome-info">
                        <div class="rev-digital-products-welcome-thumb">
                            <img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/screenshot.png' ); ?>" alt="<?php esc_attr_e( 'Theme Screenshot', 'rev-digital-products' ); ?>">
                        </div>

                        <div class="rev-digital-products-welcome-import">
                            <h3><span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Quick Start: Import Demo', 'rev-digital-products' ); ?></h3>
                            <p><?php esc_html_e( 'Use the Demo Importer to quickly set up your site with a pre-made layout. Get a complete site in minutes.', 'rev-digital-products' ); ?></p>
                            <p><a class="button info-link button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=revdigitalproducts-demoimport' ) ); ?>"><?php esc_html_e( 'Go to Demo Importer', 'rev-digital-products' ); ?></a></p>
                        </div>

                        <div class="rev-digital-products-welcome-getting-started">
                            <h3><span class="dashicons dashicons-art"></span> <?php esc_html_e( 'Customize Your Theme', 'rev-digital-products' ); ?></h3>
                            <p><?php esc_html_e( 'Want to make it truly yours? Explore the Getting Started Guide to personalize your site to suit your needs.', 'rev-digital-products' ); ?></p>
                            <p><a class="info-link button" href="<?php echo esc_url( admin_url( 'themes.php?page=rev-digital-products-getstart-page' ) ); ?>"><?php esc_html_e( 'View Getting Started Guide', 'rev-digital-products' ); ?></a></p>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }

	}
}

add_action( 'admin_notices', 'rev_digital_products_admin_notice' );

if( ! function_exists( 'rev_digital_products_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
*/
function rev_digital_products_update_admin_notice(){
    if ( isset( $_GET['rev_digital_products_admin_notice'] ) && $_GET['rev_digital_products_admin_notice'] = '1' ) {
        update_option( 'rev_digital_products_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'rev_digital_products_update_admin_notice' );


add_action('after_switch_theme', 'rev_digital_products_setup_options');
function rev_digital_products_setup_options () {
    update_option('rev_digital_products_admin_notice', FALSE );
    update_option('rev_digital_products_demo_imported', false);
}

// changelog
function get_changelog_from_readme() {
    $file_path = get_template_directory() . '/readme.txt'; // Adjust path if necessary

    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);

        // Extract changelog section
        $changelog_start = strpos($content, "== Changelog ==");
        $changelog = substr($content, $changelog_start);

        // Split changelog into versions (supports "* X.Y.Z - Date", bare "* X.Y.Z",
        // and "= X.Y.Z =" style entries, with tab, space, or "*" indented details).
        preg_match_all('/^(?:\*[ \t]*([\d.]+)(?:[ \t]*-[ \t]*(.+?))?|=[ \t]*([\d.]+)[ \t]*=)[ \t]*\r?\n((?:(?:\t|[ ]{2,}|\*(?!\s*[\d.]+(?:\s|\r?$)))[ \t]*-?[ \t]*.+?\r?\n)*)/m', $changelog, $raw_matches, PREG_SET_ORDER);

        $matches = [];

        foreach ($raw_matches as $raw_match) {
            $version = '' !== $raw_match[1] ? $raw_match[1] : $raw_match[3];
            $details = preg_replace('/^([ \t]*)\*[ \t]*/m', '$1', $raw_match[4]);

            if ('' !== $version && '' !== trim($details)) {
                $matches[] = array( $raw_match[0], $version, trim($raw_match[2]), $details );
            }
        }

        return $matches;
    }
    return [];
}

add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );