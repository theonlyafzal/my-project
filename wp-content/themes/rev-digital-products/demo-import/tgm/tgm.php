<?php
require get_template_directory() . '/demo-import/tgm/class-tgm-plugin-activation.php';
/**
 * Recommended plugins.
 */
function rev_digital_products_register_recommended_plugins_set() {
	$plugins = array(
		array(
			'name'             => __( 'Woocommerce', 'rev-digital-products' ),
			'slug'             => 'woocommerce',
			'source'           => '',
			'required'         => true,
			'force_activation' => false,
		),
	);
	$rev_digital_products_config = array();
	tgmpa( $plugins, $rev_digital_products_config );
}
add_action( 'tgmpa_register', 'rev_digital_products_register_recommended_plugins_set' );
