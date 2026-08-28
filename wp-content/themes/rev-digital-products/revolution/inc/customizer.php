<?php
/**
 * Rev Digital Products Theme Customizer
 *
 * @package Rev Digital Products
 */

function Rev_Digital_Products_Customize_register( $wp_customize ) {

	load_template( trailingslashit( get_template_directory() ) . 'revolution/inc/fontawesome-change.php' );

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'Rev_Digital_Products_Customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'Rev_Digital_Products_Customize_partial_blogdescription',
			)
		);
	}

	/*
    * Theme Options Panel
    */
	$wp_customize->add_panel('rev_digital_products_panel', array(
		'priority' => 25,
		'capability' => 'edit_theme_options',
		'title' => __('Rev Digital Products Theme Options', 'rev-digital-products'),
	));

	/*
	* Customizer main header section
	*/

	$wp_customize->add_setting(
		'rev_digital_products_site_title_text',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_site_title_text',
		array(
			'label'       => __('Enable Title', 'rev-digital-products'),
			'description' => __('Enable or Disable Title from the site', 'rev-digital-products'),
			'section'     => 'title_tagline',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'rev_digital_products_site_tagline_text',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 0,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_site_tagline_text',
		array(
			'label'       => __('Enable Tagline', 'rev-digital-products'),
			'description' => __('Enable or Disable Tagline from the site', 'rev-digital-products'),
			'section'     => 'title_tagline',
			'type'        => 'checkbox',
		)
	);

		$wp_customize->add_setting(
		'rev_digital_products_logo_width',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '150',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_logo_width',
		array(
			'label'       => __('Logo Width in PX', 'rev-digital-products'),
			'section'     => 'title_tagline',
			'type'        => 'number',
			'input_attrs' => array(
	            'min' => 100,
	             'max' => 300,
	             'step' => 1,
	         ),
		)
	);

	/* WooCommerce custom settings */

	$wp_customize->add_section('woocommerce_custom_settings', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('WooCommerce Custom Settings', 'rev-digital-products'),
		'panel'       => 'woocommerce',
	));

	$wp_customize->add_setting(
		'rev_digital_products_per_columns',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '3',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_per_columns',
		array(
			'label'       => __('Product Per Single Row', 'rev-digital-products'),
			'section'     => 'woocommerce_custom_settings',
			'type'        => 'number',
			'input_attrs' => array(
	            'min' => 1,
	             'max' => 4,
	             'step' => 1,
	         ),
		)
	);

	$wp_customize->add_setting(
		'rev_digital_products_product_per_page',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '6',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_product_per_page',
		array(
			'label'       => __('Product Per One Page', 'rev-digital-products'),
			'section'     => 'woocommerce_custom_settings',
			'type'        => 'number',
			'input_attrs' => array(
	            'min' => 1,
	             'max' => 12,
	             'step' => 1,
	         ),
		)
	);

	/*Related Products Enable Option*/
	$wp_customize->add_setting(
		'rev_digital_products_enable_related_product',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_enable_related_product',
		array(
			'label'       => __('Enable Related Product', 'rev-digital-products'),
			'description' => __('Checked to show Related Product', 'rev-digital-products'),
			'section'     => 'woocommerce_custom_settings',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'custom_related_products_number',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '3',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'custom_related_products_number',
		array(
			'label'       => __('Related Product Count', 'rev-digital-products'),
			'section'     => 'woocommerce_custom_settings',
			'type'        => 'number',
			'input_attrs' => array(
	            'min' => 1,
	             'max' => 20,
	             'step' => 1,
	         ),
		)
	);

	$wp_customize->add_setting(
		'custom_related_products_number_per_row',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '3',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'custom_related_products_number_per_row',
		array(
			'label'       => __('Related Product Per Row', 'rev-digital-products'),
			'section'     => 'woocommerce_custom_settings',
			'type'        => 'number',
			'input_attrs' => array(
	            'min' => 1,
	             'max' => 4,
	             'step' => 1,
	         ),
		)
	);

	/*Archive Product layout*/
	$wp_customize->add_setting('rev_digital_products_archive_product_layout',array(
        'default' => 'layout-1',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
	));
	$wp_customize->add_control('rev_digital_products_archive_product_layout',array(
        'type' => 'select',
        'label' => esc_html__('Archive Product Layout','rev-digital-products'),
        'section' => 'woocommerce_custom_settings',
        'choices' => array(
            'layout-1' => esc_html__('Sidebar On Right','rev-digital-products'),
            'layout-2' => esc_html__('Sidebar On Left','rev-digital-products'),
			'layout-3' => esc_html__('Full Width Layout','rev-digital-products')
        ),
	) );

	/*Single Product layout*/
	$wp_customize->add_setting('rev_digital_products_single_product_layout',array(
        'default' => 'layout-1',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
	));
	$wp_customize->add_control('rev_digital_products_single_product_layout',array(
        'type' => 'select',
        'label' => esc_html__('Single Product Layout','rev-digital-products'),
        'section' => 'woocommerce_custom_settings',
        'choices' => array(
            'layout-1' => esc_html__('Sidebar On Right','rev-digital-products'),
            'layout-2' => esc_html__('Sidebar On Left','rev-digital-products'),
			'layout-3' => esc_html__('Full Width Layout','rev-digital-products')
        ),
	) );

	$wp_customize->add_setting('rev_digital_products_woocommerce_product_sale',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
        'default'           => 'Right',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
    ));
    $wp_customize->add_control('rev_digital_products_woocommerce_product_sale',array(
        'label'       => esc_html__( 'Woocommerce Product Sale Positions','rev-digital-products' ),
        'type' => 'select',
        'section' => 'woocommerce_custom_settings',
        'choices' => array(
            'Right' => __('Right','rev-digital-products'),
            'Left' => __('Left','rev-digital-products'),
            'Center' => __('Center','rev-digital-products')
        ),
    ) );


	/*Additional Options*/
	$wp_customize->add_section('rev_digital_products_additional_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Additional Options', 'rev-digital-products'),
		'panel'       => 'rev_digital_products_panel',
	));
	
	/*Main Slider Enable Option*/
	$wp_customize->add_setting(
		'rev_digital_products_enable_sticky_header',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => false,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_enable_sticky_header',
		array(
			'label'       => __('Enable Sticky Header', 'rev-digital-products'),
			'description' => __('Checked to enable sticky header', 'rev-digital-products'),
			'section'     => 'rev_digital_products_additional_section',
			'type'        => 'checkbox',
		)
	);

	/*Main Slider Enable Option*/
	$wp_customize->add_setting(
		'rev_digital_products_enable_preloader',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 0,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_enable_preloader',
		array(
			'label'       => __('Enable Preloader', 'rev-digital-products'),
			'description' => __('Checked to show preloader', 'rev-digital-products'),
			'section'     => 'rev_digital_products_additional_section',
			'type'        => 'checkbox',
		)
	);

	/*Breadcrumbs Enable Option*/
	$wp_customize->add_setting(
		'rev_digital_products_enable_breadcrumbs',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_enable_breadcrumbs',
		array(
			'label'       => __('Enable Breadcrumbs', 'rev-digital-products'),
			'description' => __('Checked to show Breadcrumbs', 'rev-digital-products'),
			'section'     => 'rev_digital_products_additional_section',
			'type'        => 'checkbox',
		)
	);

		/*Pagination Enable Option*/
	$wp_customize->add_setting(
		'rev_digital_products_enable_pagination',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_enable_pagination',
		array(
			'label'       => __('Enable Pagination', 'rev-digital-products'),
			'description' => __('Checked to show Pagination', 'rev-digital-products'),
			'section'     => 'rev_digital_products_additional_section',
			'type'        => 'checkbox',
		)
	);

		/*Pagination Select Type Option*/
	$wp_customize->add_setting('rev_digital_products_pagination_type',array(
        'default' => 'type-1',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
	));
	$wp_customize->add_control('rev_digital_products_pagination_type',array(
        'type' => 'select',
        'label' => esc_html__('Select Pagination Type','rev-digital-products'),
        'section' => 'rev_digital_products_additional_section',
        'choices' => array(
            'type-1' => esc_html__('Type 1','rev-digital-products'),
            'type-2' => esc_html__('Type 2','rev-digital-products'),
        ),
	) );

	/*Post layout*/
	$wp_customize->add_setting('rev_digital_products_archive_layout',array(
        'default' => 'layout-1',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
	));
	$wp_customize->add_control('rev_digital_products_archive_layout',array(
        'type' => 'select',
        'label' => esc_html__('Posts Layout','rev-digital-products'),
        'section' => 'rev_digital_products_additional_section',
        'choices' => array(
            'layout-1' => esc_html__('Sidebar On Right','rev-digital-products'),
            'layout-2' => esc_html__('Sidebar On Left','rev-digital-products'),
			'layout-3' => esc_html__('Full Width Layout','rev-digital-products')
        ),
	) );

	/*single post layout*/
	$wp_customize->add_setting('rev_digital_products_post_layout',array(
        'default' => 'layout-1',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
	));
	$wp_customize->add_control('rev_digital_products_post_layout',array(
        'type' => 'select',
        'label' => esc_html__('Single Post Layout','rev-digital-products'),
        'section' => 'rev_digital_products_additional_section',
        'choices' => array(
            'layout-1' => esc_html__('Sidebar On Right','rev-digital-products'),
            'layout-2' => esc_html__('Sidebar On Left','rev-digital-products'),
			'layout-3' => esc_html__('Full Width Layout','rev-digital-products')
        ),
	) );

	/*single page layout*/
	$wp_customize->add_setting('rev_digital_products_page_layout',array(
        'default' => 'layout-1',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
	));
	$wp_customize->add_control('rev_digital_products_page_layout',array(
        'type' => 'select',
        'label' => esc_html__('Single Page Layout','rev-digital-products'),
        'section' => 'rev_digital_products_additional_section',
        'choices' => array(
            'layout-1' => esc_html__('Sidebar On Right','rev-digital-products'),
            'layout-2' => esc_html__('Sidebar On Left','rev-digital-products'),
			'layout-3' => esc_html__('Full Width Layout','rev-digital-products')
        ),
	) );

	$wp_customize->add_setting( 
		'rev_digital_products_additional_settings_upgraded_features',
		array(
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_additional_settings_upgraded_features', 
		array(
			'type'=> 'hidden',
			'description' => "
				<div class='customizer-upgraded-features'>
					<span class='pro-head'>Want more additional options?</span><br/>
					<span class='pro-text'>						
						<a target='_blank' href='". esc_url(REV_DIGITAL_PRODUCTS_BUY_NOW) ." '>Check Rev Digital Products PRO version.</a>
					</div>
				</div>
			",
			'section' => 'rev_digital_products_additional_section'
		)
	);
	
	/*Archive Post Options*/
	$wp_customize->add_section('rev_digital_products_blog_post_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Blog Page Options', 'rev-digital-products'),
		'panel'       => 'rev_digital_products_panel',
	));

	$wp_customize->add_setting('rev_digital_products_enable_blog_post_title',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_blog_post_title',array(
		'label'       => __('Enable Blog Post Title', 'rev-digital-products'),
		'description' => __('Checked To Show Blog Post Title', 'rev-digital-products'),
		'section'     => 'rev_digital_products_blog_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting('rev_digital_products_enable_blog_post_meta',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_blog_post_meta',array(
		'label'       => __('Enable Blog Post Meta', 'rev-digital-products'),
		'description' => __('Checked To Show Blog Post Meta Feilds', 'rev-digital-products'),
		'section'     => 'rev_digital_products_blog_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting('rev_digital_products_enable_blog_post_tags',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_blog_post_tags',array(
		'label'       => __('Enable Blog Post Tags', 'rev-digital-products'),
		'description' => __('Checked To Show Blog Post Tags', 'rev-digital-products'),
		'section'     => 'rev_digital_products_blog_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting('rev_digital_products_enable_blog_post_image',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_blog_post_image',array(
		'label'       => __('Enable Blog Post Image', 'rev-digital-products'),
		'description' => __('Checked To Show Blog Post Image', 'rev-digital-products'),
		'section'     => 'rev_digital_products_blog_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting('rev_digital_products_enable_blog_post_content',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_blog_post_content',array(
		'label'       => __('Enable Blog Post Content', 'rev-digital-products'),
		'description' => __('Checked To Show Blog Post Content', 'rev-digital-products'),
		'section'     => 'rev_digital_products_blog_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting('rev_digital_products_enable_blog_post_button',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_blog_post_button',array(
		'label'       => __('Enable Blog Post Read More Button', 'rev-digital-products'),
		'description' => __('Checked To Show Blog Post Read More Button', 'rev-digital-products'),
		'section'     => 'rev_digital_products_blog_post_section',
		'type'        => 'checkbox',
	));

	/*Blog post Content layout*/
	$wp_customize->add_setting('rev_digital_products_blog_Post_content_layout',array(
        'default' => 'Left',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
	));
	$wp_customize->add_control('rev_digital_products_blog_Post_content_layout',array(
        'type' => 'select',
        'label' => esc_html__('Blog Post Content Layout','rev-digital-products'),
        'section' => 'rev_digital_products_blog_post_section',
        'choices' => array(
            'Left' => esc_html__('Left','rev-digital-products'),
            'Center' => esc_html__('Center','rev-digital-products'),
            'Right' => esc_html__('Right','rev-digital-products')
        ),
	) );

	/*Excerpt*/
    $wp_customize->add_setting(
		'rev_digital_products_excerpt_limit',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '25',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_excerpt_limit',
		array(
			'label'       => __('Excerpt Limit', 'rev-digital-products'),
			'section'     => 'rev_digital_products_blog_post_section',
			'type'        => 'number',
			'input_attrs' => array(
	            'min' => 2,
	             'max' => 50,
	             'step' => 2,
	         ),
		)
	);

	/*Archive Button Text*/
	$wp_customize->add_setting(
		'rev_digital_products_read_more_text',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 'Continue Reading....',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_read_more_text',
		array(
			'label'       => __('Edit Button Text ', 'rev-digital-products'),
			'section'     => 'rev_digital_products_blog_post_section',
			'type'        => 'text',
		)
	);

	/*Single Post Options*/
	$wp_customize->add_section('rev_digital_products_single_post_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Single Post Options', 'rev-digital-products'),
		'panel'       => 'rev_digital_products_panel',
	));

	$wp_customize->add_setting('rev_digital_products_enable_single_blog_post_title',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_single_blog_post_title',array(
		'label'       => __('Enable Single Post Title', 'rev-digital-products'),
		'description' => __('Checked To Show Single Blog Post Title', 'rev-digital-products'),
		'section'     => 'rev_digital_products_single_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting('rev_digital_products_enable_single_blog_post_meta',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_single_blog_post_meta',array(
		'label'       => __('Enable Single Post Meta', 'rev-digital-products'),
		'description' => __('Checked To Show Single Blog Post Meta Feilds', 'rev-digital-products'),
		'section'     => 'rev_digital_products_single_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting('rev_digital_products_enable_single_blog_post_tags',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_single_blog_post_tags',array(
		'label'       => __('Enable Single Post Tags', 'rev-digital-products'),
		'description' => __('Checked To Show Single Blog Post Tags', 'rev-digital-products'),
		'section'     => 'rev_digital_products_single_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting('rev_digital_products_enable_single_post_image',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_single_post_image',array(
		'label'       => __('Enable Single Post Image', 'rev-digital-products'),
		'description' => __('Checked To Show Single Post Image', 'rev-digital-products'),
		'section'     => 'rev_digital_products_single_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting('rev_digital_products_enable_single_blog_post_content',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 1,
		'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));
	$wp_customize->add_control('rev_digital_products_enable_single_blog_post_content',array(
		'label'       => __('Enable Single Post Content', 'rev-digital-products'),
		'description' => __('Checked To Show Single Blog Post Content', 'rev-digital-products'),
		'section'     => 'rev_digital_products_single_post_section',
		'type'        => 'checkbox',
	));

	$wp_customize->add_setting(
		'rev_digital_products_enable_related_post',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_enable_related_post',
		array(
			'label'       => __('Enable Related Post', 'rev-digital-products'),
			'description' => __('Checked to show Related Post', 'rev-digital-products'),
			'section'     => 'rev_digital_products_single_post_section',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'rev_digital_products_related_post_text',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 'Related Post',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_related_post_text',
		array(
			'label'       => __('Edit Related Post Text ', 'rev-digital-products'),
			'section'     => 'rev_digital_products_single_post_section',
			'type'        => 'text',
		)
	);	

	$wp_customize->add_setting(
		'rev_digital_products_related_post_count',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '3',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_related_post_count',
		array(
			'label'       => __('Related Post Count', 'rev-digital-products'),
			'section'     => 'rev_digital_products_single_post_section',
			'type'        => 'number',
			'input_attrs' => array(
	            'min' => 1,
	             'max' => 9,
	             'step' => 1,
	         ),
		)
	);

	/* Comment Section Enable Option*/
	$wp_customize->add_setting(
		'rev_digital_products_enable_comment_section',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_enable_comment_section',
		array(
			'label'       => __('Enable Comment Section', 'rev-digital-products'),
			'description' => __('Checked to show Comment Section', 'rev-digital-products'),
			'section'     => 'rev_digital_products_single_post_section',
			'type'        => 'checkbox',
		)
	);

	/*
	* Customizer Global COlor
	*/

	/*Global Color Options*/
	$wp_customize->add_section('rev_digital_products_global_color_section', array(
		'priority'       => 1,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Global Color Options', 'rev-digital-products'),
		'panel'       => 'rev_digital_products_panel',
	));

	$wp_customize->add_setting( 'rev_digital_products_primary_color',
		array(
		'default'           => '#1a42fa',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'rev_digital_products_primary_color',
		array(
			'label'      => esc_html__( 'Primary Color', 'rev-digital-products' ),
			'section'    => 'rev_digital_products_global_color_section',
			'settings'   => 'rev_digital_products_primary_color',
		) ) 
	);

	$wp_customize->add_setting( 'rev_digital_products_tertiary_color',
		array(
		'default'           => '#fc3605',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control( 
		new WP_Customize_Color_Control( 
		$wp_customize, 
		'rev_digital_products_tertiary_color',
		array(
			'label'      => esc_html__( 'Tertiary Color', 'rev-digital-products' ),
			'section'    => 'rev_digital_products_global_color_section',
			'settings'   => 'rev_digital_products_tertiary_color',
		) ) 
	);

	/*Typography Options*/
	$wp_customize->add_section( 'rev_digital_products_typography_section', array(
		'panel'       => 'rev_digital_products_panel',
        'title'    => __( 'Typography Options', 'rev-digital-products' ),
        'priority' => 2,
    ) );

    $wp_customize->add_setting( 'rev_digital_products_font_family', array(
		'default'           => 'default',
		'sanitize_callback' => 'rev_digital_products_sanitize_font_family',
	) );
	
	$wp_customize->add_control( 'rev_digital_products_font_family', array(
		'label'    => __( 'Global Font Family', 'rev-digital-products' ),
		'section'  => 'rev_digital_products_typography_section',
		'type'     => 'select',
		'choices'  => array(
			'default'          => __( 'Default (Theme Font)', 'rev-digital-products' ),
			'bad_script'       => 'Bad Script',
			'roboto'           => 'Roboto',
			'playfair_display' => 'Playfair Display',
			'open_sans'        => 'Open Sans',
			'lobster'          => 'Lobster',
			'merriweather'     => 'Merriweather',
			'oswald'           => 'Oswald',
			'raleway'          => 'Raleway',
			// Add new fonts here
			'poppins'            => 'Poppins',
			'lato'               => 'Lato',
			'source_sans_pro'    => 'Source Sans Pro',
			'quicksand'          => 'Quicksand',
			'nunito'             => 'Nunito',
			'montserrat'         => 'Montserrat',
			'roboto_condensed'   => 'Roboto Condensed',
			'playfair_display_sc'=> 'Playfair Display SC',
			'alegreya'           => 'Alegreya',
			'fira_sans'          => 'Fira Sans',
		),
	) );

	/*Main Header Options*/
	$wp_customize->add_section('rev_digital_products_header_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Main Header Options', 'rev-digital-products'),
		'panel'       => 'rev_digital_products_panel',
	));

	/*
	* Customizer main header section
	*/

	/*Main Header Options*/
	$wp_customize->add_section('rev_digital_products_header_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Main Header Options', 'rev-digital-products'),
		'panel'       => 'rev_digital_products_panel',
	));

	// Add Setting for Menu Text Transform
    $wp_customize->add_setting( 'rev_digital_products_menu_text_transform', array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 'uppercase',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
    ) );

    // Add Control for Menu Text Transform
    $wp_customize->add_control( 'rev_digital_products_menu_text_transform', array(
        'label'    => __( 'Menu Text Transform', 'rev-digital-products' ),
        'section'  => 'rev_digital_products_header_section',
        'type'     => 'select',
        'choices'  => array(
            'none'       => __( 'None', 'rev-digital-products' ),
            'capitalize' => __( 'Capitalize', 'rev-digital-products' ),
            'uppercase'  => __( 'Uppercase', 'rev-digital-products' ),
            'lowercase'  => __( 'Lowercase', 'rev-digital-products' ),
        ),
    ) );

	$wp_customize->add_setting(
		'rev_digital_products_header_search',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_header_search',
		array(
			'label'       => __('Enable Disable Search', 'rev-digital-products'),
			'description' => __('Enable or Disable header Search', 'rev-digital-products'),
			'section'     => 'rev_digital_products_header_section',
			'type'        => 'checkbox',
		)
	);

	/*Main Header Button Text*/
	$wp_customize->add_setting(
		'rev_digital_products_header_button_text',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 'Help',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'rev_digital_products_header_button_text',
		array(
			'label'       => __('Edit Help Button Text ', 'rev-digital-products'),
			'section'     => 'rev_digital_products_header_section',
			'type'        => 'text',
		)
	);

	/*Main Header Button Link*/
	$wp_customize->add_setting(
		'rev_digital_products_header_button_link',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'rev_digital_products_header_button_link',
		array(
			'label'       => __('Edit Help Button Link ', 'rev-digital-products'),
			'section'     => 'rev_digital_products_header_section',
			'type'        => 'url',
		)
	);

	/*Main Header email Text*/
	$wp_customize->add_setting(
		'rev_digital_products_header_info_email',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 'digital@example.com',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'rev_digital_products_header_info_email',
		array(
			'label'       => __('Edit Email Address ', 'rev-digital-products'),
			'section'     => 'rev_digital_products_header_section',
			'type'        => 'text',
		)
	);

	/*Facebook Link*/
	$wp_customize->add_setting(
		'rev_digital_products_facebook_link_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_facebook_link_option',
		array(
			'label'       => __('Edit Facebook Link', 'rev-digital-products'),
			'section'     => 'rev_digital_products_header_section',
			'type'        => 'url',
		)
	);

	/*Twitter Link*/
	$wp_customize->add_setting(
		'rev_digital_products_twitter_link_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_twitter_link_option',
		array(
			'label'       => __('Edit Twitter Link', 'rev-digital-products'),
			'section'     => 'rev_digital_products_header_section',
			'type'        => 'url',
		)
	);

	/*Instagram Link*/
	$wp_customize->add_setting(
		'rev_digital_products_instagram_link_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_instagram_link_option',
		array(
			'label'       => __('Edit Instagram Link', 'rev-digital-products'),
			'section'     => 'rev_digital_products_header_section',
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting(
		'rev_digital_products_linked_link_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'rev_digital_products_linked_link_option',
		array(
			'label'       => __('Edit Linkedin Link', 'rev-digital-products'),
			'section'     => 'rev_digital_products_header_section',
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting(
		'rev_digital_products_pinterest_link_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'rev_digital_products_pinterest_link_option',
		array(
			'label'       => __('Edit Pinterest Link', 'rev-digital-products'),
			'section'     => 'rev_digital_products_header_section',
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting( 
		'rev_digital_products_header_settings_upgraded_features',
		array(
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_header_settings_upgraded_features', 
		array(
			'type'=> 'hidden',
			'description' => "
				<div class='customizer-upgraded-features'>
					<span class='pro-head'>Want more header options?</span><br/>
					<span class='pro-text'>						
						<a target='_blank' href='". esc_url(REV_DIGITAL_PRODUCTS_BUY_NOW) ." '>Check Rev Digital Products PRO version.</a>
					</div>
				</div>
			",
			'section' => 'rev_digital_products_header_section'
		)
	);

	/*
	* Customizer main slider section
	*/
	/*Main Slider Options*/
	$wp_customize->add_section('rev_digital_products_slider_section', array(
		'priority'       => 5,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Main Slider Options', 'rev-digital-products'),
		'panel'       => 'rev_digital_products_panel',
	));

	/*Main Slider Enable Option*/
	$wp_customize->add_setting(
		'rev_digital_products_enable_slider',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 0,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_enable_slider',
		array(
			'label'       => __('Enable Main Slider', 'rev-digital-products'),
			'description' => __('Checked to show the main slider', 'rev-digital-products'),
			'section'     => 'rev_digital_products_slider_section',
			'type'        => 'checkbox',
		)
	);

	for ($i=1; $i <= 3; $i++) { 

		/*Main Slider Image*/
		$wp_customize->add_setting(
			'rev_digital_products_slider_image'.$i,
			array(
				'capability'    => 'edit_theme_options',
		        'default'       => '',
		        'transport'     => 'postMessage',
		        'sanitize_callback' => 'esc_url_raw',
	    	)
	    );

		$wp_customize->add_control( 
			new WP_Customize_Image_Control( $wp_customize, 
				'rev_digital_products_slider_image'.$i, 
				array(
			        'label' => __('Edit Slider Image ', 'rev-digital-products') .$i,
			        'description' => __('Edit the slider image.', 'rev-digital-products'),
			        'section' => 'rev_digital_products_slider_section',
				)
			)
		);

		/*Main Slider Heading*/
		$wp_customize->add_setting(
			'rev_digital_products_slider_top_text'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rev_digital_products_slider_top_text'.$i,
			array(
				'label'       => __('Edit Slider Top Text ', 'rev-digital-products') .$i,
				'description' => __('Edit the slider Top text.', 'rev-digital-products'),
				'section'     => 'rev_digital_products_slider_section',
				'type'        => 'text',
			)
		);

		/*Main Slider Heading*/
		$wp_customize->add_setting(
			'rev_digital_products_slider_heading'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rev_digital_products_slider_heading'.$i,
			array(
				'label'       => __('Edit Heading Text ', 'rev-digital-products') .$i,
				'description' => __('Edit the slider heading text.', 'rev-digital-products'),
				'section'     => 'rev_digital_products_slider_section',
				'type'        => 'text',
			)
		);

		/*Main Slider Content*/
		$wp_customize->add_setting(
			'rev_digital_products_slider_text'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'rev_digital_products_slider_text'.$i,
			array(
				'label'       => __('Edit Content Text ', 'rev-digital-products') .$i,
				'description' => __('Edit the slider content text.', 'rev-digital-products'),
				'section'     => 'rev_digital_products_slider_section',
				'type'        => 'text',
			)
		);

		/*Main Slider Button1 Text*/
		$wp_customize->add_setting(
			'rev_digital_products_slider_button1_text'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'rev_digital_products_slider_button1_text'.$i,
			array(
				'label'       => __('Edit Button #1 Text ', 'rev-digital-products') .$i,
				'description' => __('Edit the slider button text.', 'rev-digital-products'),
				'section'     => 'rev_digital_products_slider_section',
				'type'        => 'text',
			)
		);

		/*Main Slider Button1 URL*/
		$wp_customize->add_setting(
			'rev_digital_products_slider_button1_link'.$i,
			array(
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			'rev_digital_products_slider_button1_link'.$i,
			array(
				'label'       => __('Edit Button #1 URL ', 'rev-digital-products') .$i,
				'description' => __('Edit the slider button url.', 'rev-digital-products'),
				'section'     => 'rev_digital_products_slider_section',
				'type'        => 'url',
			)
		);
	}

	$wp_customize->add_setting( 
		'rev_digital_products_slider_settings_upgraded_features',
		array(
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_slider_settings_upgraded_features', 
		array(
			'type'=> 'hidden',
			'description' => "
				<div class='customizer-upgraded-features'>
					<span class='pro-head'>Want more slider options?</span><br/>
					<span class='pro-text'>						
						<a target='_blank' href='". esc_url(REV_DIGITAL_PRODUCTS_BUY_NOW) ." '>Check Rev Digital Products PRO version.</a>
					</div>
				</div>
			",
			'section' => 'rev_digital_products_slider_section'
		)
	);

	// Project section
	$wp_customize->add_section('rev_digital_products_projetcs_sec', array(
	    'priority'       => 5,
	    'capability'     => 'edit_theme_options',
	    'title'          => __('Our Best Seller Section', 'rev-digital-products'),
	    'panel'          => 'rev_digital_products_panel',
	));

	// Enable/disable section
	$wp_customize->add_setting('rev_digital_products_projetcs_sec_show', array(
	    'capability'        => 'edit_theme_options',
	    'default'           => 0,
	    'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
	));

	$wp_customize->add_control('rev_digital_products_projetcs_sec_show', array(
	    'label'    => __('Enable Best Seller Section', 'rev-digital-products'),
	    'section'  => 'rev_digital_products_projetcs_sec',
	    'type'     => 'checkbox',
	));

	// Section heading
	$wp_customize->add_setting('rev_digital_products_projetcs_main_heading', array(
	    'default'           => '',
	    'sanitize_callback' => 'sanitize_text_field',
	));

	$wp_customize->add_control('rev_digital_products_projetcs_main_heading', array(
	    'label'   => esc_html__('Add Section Heading', 'rev-digital-products'),
	    'section' => 'rev_digital_products_projetcs_sec',
	    'type'    => 'text',
	));

	for ( $j = 1; $j <= 5; $j++ ) {

	    $wp_customize->add_setting('rev_digital_products_projetcs_text'.$j,array(
	        'default'=> '',
	        'sanitize_callback' => 'sanitize_text_field'
	    ));
	    $wp_customize->add_control('rev_digital_products_projetcs_text'.$j,array(
	        'label' => esc_html__('Products Category Name ','rev-digital-products').$j,
	        'section'=> 'rev_digital_products_projetcs_sec',
	        'type'=> 'text'
	    ));

	    // Fetch WooCommerce Product Categories
		$product_categories = get_terms(array(
		    'taxonomy' => 'product_cat',
		    'hide_empty' => false,
		));

		$product_cat_options = array();
		$product_cat_options[''] = 'Select';

		if (is_array($product_categories) && !is_wp_error($product_categories)) {
		    foreach ($product_categories as $product_category) {
		        if (isset($product_category->slug) && isset($product_category->name)) {
		            $product_cat_options[$product_category->slug] = $product_category->name;
		        }
		    }
		}

		$wp_customize->add_setting('rev_digital_products_projetcs_category' . $j, array(
		    'sanitize_callback' => 'rev_digital_products_sanitize_choices',
		));

		$wp_customize->add_control('rev_digital_products_projetcs_category' . $j, array(
		    'type' => 'select',
		    'choices' => $product_cat_options,
		    'label' => __('Select Product Category to display products ', 'rev-digital-products') . $j,
		    'section' => 'rev_digital_products_projetcs_sec',
		));

	}

	$wp_customize->add_setting('rev_digital_products_product_section_btn_text1',array(
		'default'=> 'VIEW ALL',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('rev_digital_products_product_section_btn_text1',array(
		'label'	=> esc_html__('Add Button Text','rev-digital-products'),
		'section'=> 'rev_digital_products_projetcs_sec',
		'type'=> 'text'
	));

	$wp_customize->add_setting('rev_digital_products_product_section_btn_link1',array(
		'default'=> '#',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('rev_digital_products_product_section_btn_link1',array(
		'label'	=> esc_html__('Add Button link','rev-digital-products'),
		'section'=> 'rev_digital_products_projetcs_sec',
		'type'=> 'url'
	));

	$wp_customize->add_setting( 
		'rev_digital_products_project_settings_upgraded_features',
		array(
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_project_settings_upgraded_features', 
		array(
			'type'=> 'hidden',
			'description' => "
				<div class='customizer-upgraded-features'>
					<span class='pro-head'>Want more Seller options?</span><br/>
					<span class='pro-text'>						
						<a target='_blank' href='". esc_url(REV_DIGITAL_PRODUCTS_BUY_NOW) ." '>Check Rev Digital Products PRO version.</a>
					</div>
				</div>
			",
			'section' => 'rev_digital_products_projetcs_sec'
		)
	);

	/*
	* Customizer Footer Section
	*/
	/*Footer Options*/
	$wp_customize->add_section('rev_digital_products_footer_section', array(
		'priority'       => 8,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __('Footer Options', 'rev-digital-products'),
		'panel'       => 'rev_digital_products_panel',
	));

	/*Footer Enable Option*/
	$wp_customize->add_setting(
		'rev_digital_products_enable_footer',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'rev_digital_products_enable_footer',
		array(
			'label'       => __('Enable Footer', 'rev-digital-products'),
			'description' => __('Checked to show Footer', 'rev-digital-products'),
			'section'     => 'rev_digital_products_footer_section',
			'type'        => 'checkbox',
		)
	);

	/*Footer bg image Option*/
	$wp_customize->add_setting('rev_digital_products_footer_bg_image',array(
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize,'rev_digital_products_footer_bg_image',array(
        'label' => __('Footer Background Image','rev-digital-products'),
        'section' => 'rev_digital_products_footer_section',
    )));

	/** Footer bg Image Attachment */
    $wp_customize->add_setting('rev_digital_products_background_attachment', array(
        'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 'scroll',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
    ));

    $wp_customize->add_control('rev_digital_products_background_attachment', array(
        'label'    => __('Footer Background Attachment', 'rev-digital-products'),
        'section'  => 'rev_digital_products_footer_section',
        'settings' => 'rev_digital_products_background_attachment',
        'type'     => 'select',
        'choices'  => array(
            'fixed' => __('fixed','rev-digital-products'),
            'scroll' => __('scroll','rev-digital-products'),
        ),
    ));

	/*Footer Social Menu Option*/
	$wp_customize->add_setting(
		'rev_digital_products_footer_social_menu',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_footer_social_menu',
		array(
			'label'       => __('Enable Footer Social Menu', 'rev-digital-products'),
			'description' => __('Checked to show the footer social menu. Go to Dashboard >> Appearance >> Menus >> Create New Menu >> Add Custom Link >> Add Social Menu >> Checked Social Menu >> Save Menu.', 'rev-digital-products'),
			'section'     => 'rev_digital_products_footer_section',
			'type'        => 'checkbox',
		)
	);	

	/*Go To Top Option*/
	$wp_customize->add_setting(
		'rev_digital_products_enable_go_to_top_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => 1,
			'sanitize_callback' => 'rev_digital_products_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_enable_go_to_top_option',
		array(
			'label'       => __('Enable Go To Top', 'rev-digital-products'),
			'description' => __('Checked to enable Go To Top option.', 'rev-digital-products'),
			'section'     => 'rev_digital_products_footer_section',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting('rev_digital_products_go_to_top_position',array(
        'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'default'           => 'Right',
        'sanitize_callback' => 'rev_digital_products_sanitize_choices'
    ));
    $wp_customize->add_control('rev_digital_products_go_to_top_position',array(
        'type' => 'select',
        'section' => 'rev_digital_products_footer_section',
        'label' => esc_html__('Go To Top Positions','rev-digital-products'),
        'choices' => array(
            'Right' => __('Right','rev-digital-products'),
            'Left' => __('Left','rev-digital-products'),
            'Center' => __('Center','rev-digital-products')
        ),
    ) );

	/*Footer Copyright Text Enable*/
	$wp_customize->add_setting(
		'rev_digital_products_copyright_option',
		array(
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_copyright_option',
		array(
			'label'       => __('Edit Copyright Text', 'rev-digital-products'),
			'description' => __('Edit the Footer Copyright Section.', 'rev-digital-products'),
			'section'     => 'rev_digital_products_footer_section',
			'type'        => 'text',
		)
	);

	$wp_customize->add_setting( 
		'rev_digital_products_footer_settings_upgraded_features',
		array(
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		'rev_digital_products_footer_settings_upgraded_features', 
		array(
			'type'=> 'hidden',
			'description' => "
				<div class='customizer-upgraded-features'>
					<span class='pro-head'>Want more footer options?</span><br/>
					<span class='pro-text'>						
						<a target='_blank' href='". esc_url(REV_DIGITAL_PRODUCTS_BUY_NOW) ." '>Check Rev Digital Products PRO version.</a>
					</div>
				</div>
			",
			'section' => 'rev_digital_products_footer_section'
		)
	);

}
add_action( 'customize_register', 'Rev_Digital_Products_Customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function Rev_Digital_Products_Customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function Rev_Digital_Products_Customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function Rev_Digital_Products_Customize_preview_js() {
	wp_enqueue_script( 'rev-digital-products-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), REV_DIGITAL_PRODUCTS_VERSION, true );
}
add_action( 'customize_preview_init', 'Rev_Digital_Products_Customize_preview_js' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Rev_Digital_Products_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $rev_digital_products_instance = null;

		if ( is_null( $rev_digital_products_instance ) ) {
			$rev_digital_products_instance = new self;
			$rev_digital_products_instance->setup_actions();
		}

		return $rev_digital_products_instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $rev_digital_products_manager
	 * @return void
	*/
	public function sections( $rev_digital_products_manager ) {
		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/revolution/inc/section-pro.php' );

		// Register custom section types.
		$rev_digital_products_manager->register_section_type( 'rev_digital_products_Customize_Section_Pro' );

		// Register sections.
		$rev_digital_products_manager->add_section( new Rev_Digital_Products_Customize_Section_Pro( $rev_digital_products_manager,'rev_digital_products_go_pro', array(
			'priority'   => 1,
			'title'    => esc_html__( 'Rev Digital Products Pro', 'rev-digital-products' ),
			'pro_text' => esc_html__( 'Buy Pro', 'rev-digital-products' ),
			'pro_url'    => esc_url( REV_DIGITAL_PRODUCTS_BUY_NOW ),
		) )	);

		// Register sections.
		$rev_digital_products_manager->add_section( new Rev_Digital_Products_Customize_Section_Pro( $rev_digital_products_manager,'rev_digital_products_lite_documentation', array(
			'priority'   => 1,
			'title'    => esc_html__( 'Lite Documentation', 'rev-digital-products' ),
			'pro_text' => esc_html__( 'Instruction', 'rev-digital-products' ),
			'pro_url'    => esc_url( REV_DIGITAL_PRODUCTS_LITE_DOC ),
		) )	);

		$rev_digital_products_manager->add_section( new Rev_Digital_Products_Customize_Section_Pro( $rev_digital_products_manager, 'rev_digital_products_live_demo', array(
		    'priority'   => 1,
		    'title'      => esc_html__( 'Pro Theme Demo', 'rev-digital-products' ),
		    'pro_text'   => esc_html__( 'Live Preview', 'rev-digital-products' ),
		    'pro_url'    => esc_url( REV_DIGITAL_PRODUCTS_LIVE_DEMO ),
		) ) );

		// Register sections.
		$rev_digital_products_manager->add_section( new Rev_Digital_Products_Customize_Section_Pro( $rev_digital_products_manager, 'rev_digital_products_bundle', array(
			'priority'   => 1,
			'title'      => esc_html__( 'Theme Bundle', 'rev-digital-products' ),
			'pro_text'   => esc_html__( 'Get Bundle', 'rev-digital-products' ),
			'pro_url'    => esc_url( REV_DIGITAL_PRODUCTS_BUNDLE ),
		) ) );	
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'rev-digital-products-customize-controls', trailingslashit( get_template_directory_uri() ) . '/revolution/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'rev-digital-products-customize-controls', trailingslashit( get_template_directory_uri() ) . '/revolution/assets/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
Rev_Digital_Products_Customize::get_instance();