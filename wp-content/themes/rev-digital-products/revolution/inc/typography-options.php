<?php
/**
 * Custom typography options for this theme
 *
 * @package Rev Digital Products
 */

function rev_digital_products_output_custom_font_css() {
    $rev_digital_products_font_choice = get_theme_mod( 'rev_digital_products_font_family', 'default' );

    if ( $rev_digital_products_font_choice === 'default' ) {
        return;
    }

    $rev_digital_products_font_map = array(
        'bad_script'       => '"Bad Script", cursive',
        'roboto'           => '"Roboto", sans-serif',
        'playfair_display' => '"Playfair Display", serif',
        'open_sans'        => '"Open Sans", sans-serif',
        'lobster'          => '"Lobster", cursive',
        'merriweather'     => '"Merriweather", serif',
        'oswald'           => '"Oswald", sans-serif',
        'raleway'          => '"Raleway", sans-serif',
        // Add new fonts here
        'poppins'          => '"Poppins", sans-serif',
        'lato'             => '"Lato", sans-serif',
        'source_sans_pro'  => '"Source Sans Pro", sans-serif',
        'quicksand'        => '"Quicksand", sans-serif',
        'nunito'           => '"Nunito", sans-serif',
        'montserrat'       => '"Montserrat", sans-serif',
        'roboto_condensed' => '"Roboto Condensed", sans-serif',
        'playfair_display_sc' => '"Playfair Display SC", serif',
        'alegreya'         => '"Alegreya", serif',
        'fira_sans'        => '"Fira Sans", sans-serif',
    );

    $rev_digital_products_font_family = isset( $rev_digital_products_font_map[ $rev_digital_products_font_choice ] ) ? $rev_digital_products_font_map[ $rev_digital_products_font_choice ] : $rev_digital_products_font_map['pt_sans'];

    $rev_digital_products_custom_css = "
        body,
        h1, h2, h3, h4, h5, h6,
        p, a, span, div,
        .site, .entry-content, .main-navigation, .widget,
        input, textarea, button, .menu, .site-title, .site-description {
            font-family: {$rev_digital_products_font_family} !important;
        }
    ";

    wp_add_inline_style( 'rev-digital-products-google-fonts', $rev_digital_products_custom_css );
}
add_action( 'wp_enqueue_scripts', 'rev_digital_products_output_custom_font_css', 20 );


function rev_digital_products_sanitize_font_family( $rev_digital_products_input ) {
    $rev_digital_products_valid = array(
        'default', 'bad_script', 'roboto',
        'playfair_display', 'open_sans', 'lobster', 'merriweather', 'oswald', 'raleway',
        // Add new font options here
        'poppins', 'lato', 'source_sans_pro', 'quicksand', 'nunito', 'montserrat',
        'roboto_condensed', 'playfair_display_sc', 'alegreya', 'fira_sans'
    );
    return in_array( $rev_digital_products_input, $rev_digital_products_valid ) ? $rev_digital_products_input : 'default';
}

function rev_digital_products_enqueue_selected_google_font() {
    $rev_digital_products_font_choice = get_theme_mod( 'rev_digital_products_font_family', 'default' );

    $rev_digital_products_font_links = array(
        'bad_script'       => 'https://fonts.googleapis.com/css2?family=Bad+Script&display=swap',
        'roboto'           => 'https://fonts.googleapis.com/css2?family=Roboto&display=swap',
        'playfair_display' => 'https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap',
        'open_sans'        => 'https://fonts.googleapis.com/css2?family=Open+Sans&display=swap',
        'lobster'          => 'https://fonts.googleapis.com/css2?family=Lobster&display=swap',
        'merriweather'     => 'https://fonts.googleapis.com/css2?family=Merriweather&display=swap',
        'oswald'           => 'https://fonts.googleapis.com/css2?family=Oswald&display=swap',
        'raleway'          => 'https://fonts.googleapis.com/css2?family=Raleway&display=swap',
        // Add new font URLs here
        'poppins'          => 'https://fonts.googleapis.com/css2?family=Poppins&display=swap',
        'lato'             => 'https://fonts.googleapis.com/css2?family=Lato&display=swap',
        'source_sans_pro'  => 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap',
        'quicksand'        => 'https://fonts.googleapis.com/css2?family=Quicksand&display=swap',
        'nunito'           => 'https://fonts.googleapis.com/css2?family=Nunito&display=swap',
        'montserrat'       => 'https://fonts.googleapis.com/css2?family=Montserrat&display=swap',
        'roboto_condensed' => 'https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap',
        'playfair_display_sc' => 'https://fonts.googleapis.com/css2?family=Playfair+Display+SC&display=swap',
        'alegreya'         => 'https://fonts.googleapis.com/css2?family=Alegreya&display=swap',
        'fira_sans'        => 'https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap',
    );

    if ( isset( $rev_digital_products_font_links[ $rev_digital_products_font_choice ] ) ) {
        wp_enqueue_style( 'rev-digital-products-dynamic-font', $rev_digital_products_font_links[ $rev_digital_products_font_choice ], array(), null );
    }
}
add_action( 'wp_enqueue_scripts', 'rev_digital_products_enqueue_selected_google_font' );