<?php 
	$rev_digital_products_custom_css ='';

    /*--------------------------- Menu Text Transform -------------------*/

    $rev_digital_products_footer_attachment = get_theme_mod( 'rev_digital_products_menu_text_transform','uppercase');
    if($rev_digital_products_footer_attachment == 'uppercase'){
        $rev_digital_products_custom_css .='.main-navigation ul#primary-menu>li>a, .main-navigation div#primary-menu>ul>li>a{';
            $rev_digital_products_custom_css .='text-transform: uppercase;';
        $rev_digital_products_custom_css .='}';
    }elseif ($rev_digital_products_footer_attachment == 'capitalize'){
        $rev_digital_products_custom_css .='.main-navigation ul#primary-menu>li>a, .main-navigation div#primary-menu>ul>li>a{';
            $rev_digital_products_custom_css .='text-transform: capitalize;';
        $rev_digital_products_custom_css .='}';
    }elseif ($rev_digital_products_footer_attachment == 'lowercase'){
        $rev_digital_products_custom_css .='.main-navigation ul#primary-menu>li>a, .main-navigation div#primary-menu>ul>li>a{';
            $rev_digital_products_custom_css .='text-transform: lowercase;';
        $rev_digital_products_custom_css .='}';
    }

    /*----------------Related Product show/hide -------------------*/

    $rev_digital_products_enable_related_product = get_theme_mod('rev_digital_products_enable_related_product',1);

    if($rev_digital_products_enable_related_product == 0){
        $rev_digital_products_custom_css .='.related.products{';
            $rev_digital_products_custom_css .='display: none;';
        $rev_digital_products_custom_css .='}';
    }

    /*----------------blog post content alignment -------------------*/

    $rev_digital_products_blog_Post_content_layout = get_theme_mod( 'rev_digital_products_blog_Post_content_layout','Left');
    if($rev_digital_products_blog_Post_content_layout == 'Left'){
        $rev_digital_products_custom_css .='.ct-post-wrapper .card-item {';
            $rev_digital_products_custom_css .='text-align:start;';
        $rev_digital_products_custom_css .='}';
    }else if($rev_digital_products_blog_Post_content_layout == 'Center'){
        $rev_digital_products_custom_css .='.ct-post-wrapper .card-item {';
            $rev_digital_products_custom_css .='text-align:center;';
        $rev_digital_products_custom_css .='}';
    }else if($rev_digital_products_blog_Post_content_layout == 'Right'){
        $rev_digital_products_custom_css .='.ct-post-wrapper .card-item {';
            $rev_digital_products_custom_css .='text-align:end;';
        $rev_digital_products_custom_css .='}';
    }

    /*--------------------------- Footer background image -------------------*/

    $rev_digital_products_footer_bg_image = get_theme_mod('rev_digital_products_footer_bg_image');
    if($rev_digital_products_footer_bg_image != false){
        $rev_digital_products_custom_css .= '.footer-top{';
        $rev_digital_products_custom_css .= 'background-image:url(' . esc_url($rev_digital_products_footer_bg_image) . ');';
        $rev_digital_products_custom_css .= 'background-size: cover;';
        $rev_digital_products_custom_css .= 'background-repeat:no-repeat;';
        $rev_digital_products_custom_css .= '}';
    }

    /*--------------------------- Footer Background Image Attachment -------------------*/

    $rev_digital_products_footer_attachment = get_theme_mod( 'rev_digital_products_background_attachment','scroll');
    if($rev_digital_products_footer_attachment == 'fixed'){
        $rev_digital_products_custom_css .='.footer-top{';
            $rev_digital_products_custom_css .='background-attachment: fixed;';
        $rev_digital_products_custom_css .='}';
    }elseif ($rev_digital_products_footer_attachment == 'scroll'){
        $rev_digital_products_custom_css .='.footer-top{';
            $rev_digital_products_custom_css .='background-attachment: scroll;';
        $rev_digital_products_custom_css .='}';
    }

    /*--------------------------- Go to top positions -------------------*/

    $rev_digital_products_go_to_top_position = get_theme_mod( 'rev_digital_products_go_to_top_position','Right');
    if($rev_digital_products_go_to_top_position == 'Right'){
        $rev_digital_products_custom_css .='.footer-go-to-top{';
            $rev_digital_products_custom_css .='right: 20px;';
        $rev_digital_products_custom_css .='}';
    }else if($rev_digital_products_go_to_top_position == 'Left'){
        $rev_digital_products_custom_css .='.footer-go-to-top{';
            $rev_digital_products_custom_css .='left: 20px;';
        $rev_digital_products_custom_css .='}';
    }else if($rev_digital_products_go_to_top_position == 'Center'){
        $rev_digital_products_custom_css .='.footer-go-to-top{';
            $rev_digital_products_custom_css .='right: 50%;left: 50%;';
        $rev_digital_products_custom_css .='}';
    }

    /*--------------------------- Woocommerce Product Sale Positions -------------------*/

    $rev_digital_products_product_sale = get_theme_mod( 'rev_digital_products_woocommerce_product_sale','Right');
    if($rev_digital_products_product_sale == 'Right'){
        $rev_digital_products_custom_css .='.woocommerce ul.products li.product .onsale{';
            $rev_digital_products_custom_css .='left: auto; ';
        $rev_digital_products_custom_css .='}';
    }else if($rev_digital_products_product_sale == 'Left'){
        $rev_digital_products_custom_css .='.woocommerce ul.products li.product .onsale{';
            $rev_digital_products_custom_css .='right: auto;left:0;';
        $rev_digital_products_custom_css .='}';
    }else if($rev_digital_products_product_sale == 'Center'){
        $rev_digital_products_custom_css .='.woocommerce ul.products li.product .onsale{';
            $rev_digital_products_custom_css .='right: 50%; left: 50%; ';
        $rev_digital_products_custom_css .='}';
    }
 
    /*-------------------- Primary Color -------------------*/

	$rev_digital_products_primary_color = get_theme_mod('rev_digital_products_primary_color', '#1a42fa'); // Add a fallback if the color isn't set

	if ($rev_digital_products_primary_color) {
		$rev_digital_products_custom_css .= ':root {';
		$rev_digital_products_custom_css .= '--primary-color: ' . esc_attr($rev_digital_products_primary_color) . ';';
		$rev_digital_products_custom_css .= '}';
	}

    /*-------------------- Secondary Color -------------------*/

	$rev_digital_products_tertiary_color = get_theme_mod('rev_digital_products_tertiary_color', '#fc3605'); // Add a fallback if the color isn't set

	if ($rev_digital_products_tertiary_color) {
		$rev_digital_products_custom_css .= ':root {';
		$rev_digital_products_custom_css .= '--tertiary-color: ' . esc_attr($rev_digital_products_tertiary_color) . ';';
		$rev_digital_products_custom_css .= '}';
	}

    /*----------------Enable/Disable Breadcrumbs -------------------*/

    $rev_digital_products_enable_breadcrumbs = get_theme_mod('rev_digital_products_enable_breadcrumbs',1);

    if($rev_digital_products_enable_breadcrumbs == 0){
        $rev_digital_products_custom_css .='.rev-digital-products-breadcrumbs, nav.woocommerce-breadcrumb{';
            $rev_digital_products_custom_css .='display: none;';
        $rev_digital_products_custom_css .='}';
    }