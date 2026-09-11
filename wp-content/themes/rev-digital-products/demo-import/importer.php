<?php
/**
 * @package Demo Import
 * @since 1.0.0
 */

class ThemeWhizzie {

	protected $version = '1.1.0';

	/** @var string Current theme name, used as namespace in actions. */
	protected $theme_name = '';
	protected $theme_title = '';

	/** @var string Demo Import page slug and title. */
	protected $page_slug = '';
	protected $page_title = '';

	/** @var array Demo Import steps set by user. */
	protected $config_steps = array();

	/**
	 * Relative plugin url for this plugin folder
	 * @since 1.0.0
	 * @var string
	*/
	protected $plugin_url = '';
	protected $plugin_path = '';
	public $parent_slug;
	/**
	 * TGMPA instance storage
	 *
	 * @var object
	*/
	protected $tgmpa_instance;

	/**
	 * TGMPA Menu slug
	 *
	 * @var string
	*/
	protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

	/**
	 * TGMPA Menu url
	 *
	 * @var string
	*/
	protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';
	/**
	 * Constructor
	 *
	 * @param $rev_digital_products_config	Our config parameters
	*/
	public function __construct( $rev_digital_products_config ) {
		$this->set_vars( $rev_digital_products_config );
		$this->init();

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	/**
	 * Set some settings
	 * @since 1.0.0
	 * @param $rev_digital_products_config	Our config parameters
	*/
	public function set_vars( $rev_digital_products_config ) {

		require_once trailingslashit( WHIZZIE_DIR ) . 'tgm/tgm.php';

		if( isset( $rev_digital_products_config['page_slug'] ) ) {
			$this->page_slug = esc_attr( $rev_digital_products_config['page_slug'] );
		}
		if( isset( $rev_digital_products_config['page_title'] ) ) {
			$this->page_title = esc_attr( $rev_digital_products_config['page_title'] );
		}
		if( isset( $rev_digital_products_config['steps'] ) ) {
			$this->config_steps = $rev_digital_products_config['steps'];
		}

		$this->plugin_path = trailingslashit( dirname( __FILE__ ) );
		$relative_url = str_replace( get_template_directory(), '', $this->plugin_path );
		$this->plugin_url = trailingslashit( get_template_directory_uri() . $relative_url );
		$current_theme = wp_get_theme();
		$this->theme_title = $current_theme->get( 'Name' );
		$this->theme_name = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
		$this->page_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_page_slug', $this->theme_name . '-demoimport' );
		$this->parent_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_parent_slug', '' );
	}

	/**
	 * Hooks and filters
	 * @since 1.0.0
	*/
	public function init() {

		if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
			add_action( 'init', array( $this, 'get_tgmpa_instance' ), 30 );
			add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_action( 'admin_init', array( $this, 'get_plugins' ), 30 );
		add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
		add_action( 'wp_ajax_setup_plugins', array( $this, 'setup_plugins' ) );
		add_action( 'wp_ajax_setup_widgets', array( $this, 'setup_widgets' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'demo-import-style', get_template_directory_uri() . '/demo-import/assets/css/demo-import-style.css');
		wp_register_script( 'demo-import-script', get_template_directory_uri() . '/demo-import/assets/js/demo-import-script.js', array( 'jquery' ), time() );
		wp_localize_script(
			'demo-import-script',
			'rev_digital_products_whizzie_params',
			array(
				'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
				'wpnonce' 		=> wp_create_nonce( 'whizzie_nonce' ),
				'verify_text'	=> esc_html( 'verifying', 'rev-digital-products' )
			)
		);
		wp_enqueue_script( 'demo-import-script' );
	}

	public function tgmpa_load( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Get configured TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	*/
	public function get_tgmpa_instance() {
		$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	}

	/**
	 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	*/
	public function set_tgmpa_url() {
		$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
		$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );
		$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';
		$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
	}


	/**  Make a modal screen for the wizard **/
	public function menu_page() {
		add_menu_page( esc_html( $this->page_title ), esc_html( $this->page_title ), 'manage_options', $this->page_slug, array( $this, 'rev_digital_products_guide' ) ,'',40);
	}

	/*** Make an interface for the wizard ***/
	public function wizard_page() {

		tgmpa_load_bulk_installer();

		// install plugins with TGM.
		if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
			die( 'Failed to find TGM' );
		}
		$url = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'whizzie-setup' );

		// copied from TGM
		$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.
		$fields = array_keys( $_POST ); // Extra fields to pass to WP_Filesystem.
		if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields ) ) ) {
			return true; // Stop the normal page form from displaying, credential request form will be shown.
		}
		// Now we have some credentials, setup WP_Filesystem.
		if ( ! WP_Filesystem( $creds ) ) {
			// Our credentials were no good, ask the user for them again.
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
			return true;
		}

		/* If we arrive here, we have the filesystem */ ?>
		<div class="wrap">
			<?php echo '<div class="whizzie-wrap">';
				// The wizard is a list with only one item visible at a time
				$steps = $this->get_steps();
				echo '<ul class="whizzie-nav wizard-icon-nav">';?>

				<?php
					$stepI=1;
					foreach( $steps as $step ) {
						$stepAct=($stepI ==1)? 1 : 0;
						if( isset( $step['icon_text'] ) && $step['icon_text'] ) {
							echo '<li class="commom-cls nav-step-' . esc_attr( $step['id'] ) . '" wizard-steps="step-'.esc_attr( $step['id'] ).'" data-enable="'.$stepAct.'">
							<p>'.esc_attr( $step['icon_text'] ).'</p>
							</li>';
						}
					$stepI++;}
			 	echo '</ul>';
				echo '<ul class="whizzie-menu wizard-menu-page">';
				foreach( $steps as $step ) {
					$class = 'step step-' . esc_attr( $step['id'] );
					echo '<li data-step="' . esc_attr( $step['id'] ) . '" class="' . esc_attr( $class ) . '" >';

						$content = call_user_func( array( $this, $step['view'] ) );
						if( isset( $content['summary'] ) ) {
							printf(
								'<div class="summary">%s</div>',
								wp_kses_post( $content['summary'] )
							);
						}
						if( isset( $content['detail'] ) ) {
							// Add a link to see more detail
							printf( '<div class="wz-require-plugins">');
							printf(
								'<div class="detail">%s</div>',
								$content['detail'] // Need to escape this
							);
							printf('</div>');
						}
						printf('<div class="wizard-button-wrapper">');
							// The next button
							if( isset( $step['button_text'] ) && $step['button_text'] ) {
								printf(
									'<div class="button-wrap"><a href="#" class="button button-primary do-it" data-callback="%s" data-step="%s">%s</a></div>',
									esc_attr( $step['callback'] ),
									esc_attr( $step['id'] ),
									esc_html( $step['button_text'] )
								);
							}

							if( isset( $step['button_text_one'] )) {
								printf(
									'<div class="button-wrap button-wrap-one">
										<a href="#" class="button button-primary do-it" data-callback="install_widgets" data-step="widgets"><p class="demo-type-text">%s</p></a>
									</div>',
									esc_html( $step['button_text_one'] )
								);
							}
						printf('</div>');
					echo '</li>';
				}
				echo '</ul>';
				?>
				<div class="step-loading"><span class="spinner">
					<img src="<?php echo esc_url(get_template_directory_uri().'/demo-import/assets/images/Spinner-Animaion.gif'); ?>">
				</span></div>
			<?php echo '</div>';?>
		</div>
	<?php }

	/**
	 * Set options for the steps
	 * @return Array
	*/
	public function get_steps() {
		if ( get_option('rev_digital_products_demo_imported') ) {
			return array(
				'done' => array(
					'id'        => 'done',
					'title'     => __( 'All Done', 'rev-digital-products' ),
					'icon'      => 'yes',
					'view'      => 'get_step_done',
					'callback'  => '',
					'icon_text' => 'View Site'
				)
			);
		}
		$dev_steps = $this->config_steps;
		$steps = array(
			'plugins' => array(
				'id'			=> 'plugins',
				'title'			=> __( 'Plugins', 'rev-digital-products' ),
				'icon'			=> 'admin-plugins',
				'view'			=> 'get_step_plugins',
				'callback'		=> 'install_plugins',
				'button_text'	=> __( 'Install Plugins', 'rev-digital-products' ),
				'can_skip'		=> true,
				'icon_text'      => 'Plugins'
			),
			'widgets' => array(
				'id'			=> 'widgets',
				'title'			=> __( 'Customizer', 'rev-digital-products' ),
				'icon'			=> 'welcome-widgets-menus',
				'view'			=> 'get_step_widgets',
				'callback'		=> 'install_widgets',
				'button_text_one'	=> __( 'Import Demo', 'rev-digital-products' ),

				'can_skip'		=> true,
				'icon_text'      => 'Import Demo'
			),
			'done' => array(
				'id'			=> 'done',
				'title'			=> __( 'All Done', 'rev-digital-products' ),
				'icon'			=> 'yes',
				'view'			=> 'get_step_done',
				'callback'		=> '',
				'icon_text'      => 'Done'
			)
		);

		// Iterate through each step and replace with dev config values
		if( $dev_steps ) {
			// Configurable elements - these are the only ones the dev can update from config.php
			$can_config = array( 'title', 'icon', 'button_text', 'can_skip' );
			foreach( $dev_steps as $dev_step ) {
				// We can only proceed if an ID exists and matches one of our IDs
				if( isset( $dev_step['id'] ) ) {
					$id = $dev_step['id'];
					if( isset( $steps[$id] ) ) {
						foreach( $can_config as $element ) {
							if( isset( $dev_step[$element] ) ) {
								$steps[$id][$element] = $dev_step[$element];
							}
						}
					}
				}
			}
		}
		return $steps;
	}

	/*** Print the content for the intro step ***/
		public function get_step_importer() { ?>
		<div class="summary">
			<p>
				<?php esc_html_e('Thank you for choosing this Rev Digital Products Theme. Using this quick setup wizard, you will be able to configure your new website and get it running in just a few minutes. Just follow these simple steps mentioned in the wizard and get started with your website.','rev-digital-products'); ?>
			</p>
		</div>
	<?php }

	/**
	 * Get the content for the plugins step
	 * @return $content Array
	*/
	public function get_step_plugins() {
		$plugins = $this->get_plugins();
		$content = array(); ?>
			<div class="summary">
				<p>
					<?php esc_html_e('Install Recommended Plugins:	','rev-digital-products') ?>
				</p>
			</div>
		<?php // The detail element is initially hidden from the user
		$content['detail'] = '<span class="wizard-plugin-count">Required Plugins: ' . count($plugins['all']) . '</span><ul class="whizzie-do-plugins">';
		// Add each plugin into a list
		foreach( $plugins['all'] as $slug=>$plugin ) {
			$content['detail'] .= '<li data-slug="' . esc_attr( $slug ) . '">' . esc_html( $plugin['name'] ) . '<div class="wizard-plugin-title">';

			$content['detail'] .= '<span class="wizard-plugin-status">Installation Required</span><i class="spinner"></i></div></li>';
		}
		$content['detail'] .= '</ul>';

		return $content;
	}

	/**    Print the content for the intro step     **/
	public function get_step_widgets() { ?>
		<div class="summary">
			<p>
				<?php esc_html_e('This theme allows you to import demo content and add widgets. Install them using the button below. You can also update or deactivate them using the Customizer.','rev-digital-products'); ?>
			</p>
		</div>
	<?php }

	/***  Print the content for the final step  ***/
	public function get_step_done() { ?>

		<div class="setup-finish">
			<p>
				<?php echo esc_html('Your demo content has been imported successfully. Click the finish button for more information.'); ?>
			</p>
			<div class="finish-buttons">
				<a href="<?php echo esc_url( admin_url( 'themes.php?page=rev-digital-products-getstart-page' ) ); ?>" class="wz-btn-customizer" target="_blank"><?php esc_html_e('About Rev Digital Products','rev-digital-products') ?></a>
				<a href="<?php echo esc_url(admin_url('/customize.php')); ?>" class="wz-btn-customizer" target="_blank"><?php esc_html_e('Customize Your Demo','rev-digital-products') ?></a>
				<a href="<?php echo esc_url(home_url()); ?>" class="wz-btn-visit-site" target="_blank"><?php esc_html_e('Visit Your Site','rev-digital-products'); ?></a>
			</div>
			<div class="finish-buttons">
				<a href="<?php echo esc_url(admin_url()); ?>" class="button button-primary"><?php esc_html_e('Finish','rev-digital-products'); ?></a>
			</div>
		</div>

	<?php }

	/***  Get the plugins registered with TGMPA  ***/
	public function get_plugins() {
		$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$plugins = array(
			'all' 		=> array(),
			'install'	=> array(),
			'update'	=> array(),
			'activate'	=> array()
		);
		foreach( $instance->plugins as $slug=>$plugin ) {
			if( $instance->is_plugin_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
				// Plugin is installed and up to date
				continue;
			} else {
				$plugins['all'][$slug] = $plugin;
				if( ! $instance->is_plugin_installed( $slug ) ) {
					$plugins['install'][$slug] = $plugin;
				} else {
					if( false !== $instance->does_plugin_have_update( $slug ) ) {
						$plugins['update'][$slug] = $plugin;
					}
					if( $instance->can_plugin_activate( $slug ) ) {
						$plugins['activate'][$slug] = $plugin;
					}
				}
			}
		}
		return $plugins;
	}

	public function setup_plugins() {
		if ( ! check_ajax_referer( 'whizzie_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
			wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found','rev-digital-products' ) ) );
		}
		$json = array();
		// send back some json we use to hit up TGM
		$plugins = $this->get_plugins();

		// what are we doing with this plugin?
		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating Plugin','rev-digital-products' ),
				);
				break;
			}
		}
		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating Plugin','rev-digital-products' ),
				);
				break;
			}
		}
		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing Plugin','rev-digital-products' ),
				);
				break;
			}
		}
		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
			wp_send_json( $json );
		} else {
			wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success','rev-digital-products' ) ) );
		}
		exit;
	}

	//................................................. MENUS .................................................//
	
	public function rev_digital_products_customizer_nav_menu() {
		// ------- Create Primary Menu --------
		$rev_digital_products_themename = 'Rev Digital Products'; // Ensure the theme name is set
		$rev_digital_products_menuname = $rev_digital_products_themename . ' Primary Menu';
		$rev_digital_products_menulocation = 'menu-1';
		$rev_digital_products_menu_exists = wp_get_nav_menu_object($rev_digital_products_menuname);

		if (!$rev_digital_products_menu_exists) {
			$rev_digital_products_menu_id = wp_create_nav_menu($rev_digital_products_menuname);

			// Home
			wp_update_nav_menu_item($rev_digital_products_menu_id, 0, array(
				'menu-item-title' => __('Home', 'rev-digital-products'),
				'menu-item-classes' => 'home',
				'menu-item-url' => home_url('/'),
				'menu-item-status' => 'publish'
			));

			// About
			$rev_digital_products_page_about = get_page_by_path('about');
			if($rev_digital_products_page_about){
				wp_update_nav_menu_item($rev_digital_products_menu_id, 0, array(
					'menu-item-title' => __('About', 'rev-digital-products'),
					'menu-item-classes' => 'about',
					'menu-item-url' => get_permalink($rev_digital_products_page_about),
					'menu-item-status' => 'publish'
				));
			}

			// Services
			$rev_digital_products_page_services = get_page_by_path('services');
			if($rev_digital_products_page_services){
				wp_update_nav_menu_item($rev_digital_products_menu_id, 0, array(
					'menu-item-title' => __('Services', 'rev-digital-products'),
					'menu-item-classes' => 'services',
					'menu-item-url' => get_permalink($rev_digital_products_page_services),
					'menu-item-status' => 'publish'
				));
			}

			// Blog
			$rev_digital_products_page_blog = get_page_by_path('blog');
			if($rev_digital_products_page_blog){
				wp_update_nav_menu_item($rev_digital_products_menu_id, 0, array(
					'menu-item-title' => __('Blog', 'rev-digital-products'),
					'menu-item-classes' => 'blog',
					'menu-item-url' => get_permalink($rev_digital_products_page_blog),
					'menu-item-status' => 'publish'
				));
			}

			// Contact Us
			$rev_digital_products_page_contact = get_page_by_path('contact');
			if($rev_digital_products_page_contact){
				wp_update_nav_menu_item($rev_digital_products_menu_id, 0, array(
					'menu-item-title' => __('Contact Us', 'rev-digital-products'),
					'menu-item-classes' => 'contact',
					'menu-item-url' => get_permalink($rev_digital_products_page_contact),
					'menu-item-status' => 'publish'
				));
			}

			// Assign menu to location if not set
			if (!has_nav_menu($rev_digital_products_menulocation)) {
				$rev_digital_products_locations = get_theme_mod('nav_menu_locations');
				$rev_digital_products_locations[$rev_digital_products_menulocation] = $rev_digital_products_menu_id; // Use $rev_digital_products_menu_id here
				set_theme_mod('nav_menu_locations', $rev_digital_products_locations);
			}
		}
	}

	public function rev_digital_products_social_menu() {

		// ------- Create Social Menu --------
		$rev_digital_products_menuname = $rev_digital_products_themename . 'Social Menu';
		$rev_digital_products_menulocation = 'social-menu';
		$rev_digital_products_menu_exists = wp_get_nav_menu_object( $rev_digital_products_menuname );

		if( !$rev_digital_products_menu_exists){
			$rev_digital_products_menu_id = wp_create_nav_menu($rev_digital_products_menuname);

			wp_update_nav_menu_item( $rev_digital_products_menu_id, 0, array(
				'menu-item-title'  => __( 'Facebook', 'rev-digital-products' ),
				'menu-item-url'    => 'https://www.facebook.com',
				'menu-item-status' => 'publish',
			) );

			wp_update_nav_menu_item( $rev_digital_products_menu_id, 0, array(
				'menu-item-title'  => __( 'Pinterest', 'rev-digital-products' ),
				'menu-item-url'    => 'https://www.pinterest.com',
				'menu-item-status' => 'publish',
			) );
	
			wp_update_nav_menu_item( $rev_digital_products_menu_id, 0, array(
				'menu-item-title'  => __( 'Twitter', 'rev-digital-products' ),
				'menu-item-url'    => 'https://www.twitter.com',
				'menu-item-status' => 'publish',
			) );
	
			wp_update_nav_menu_item( $rev_digital_products_menu_id, 0, array(
				'menu-item-title'  => __( 'Youtube', 'rev-digital-products' ),
				'menu-item-url'    => 'https://www.youtube.com',
				'menu-item-status' => 'publish',
			) );

			wp_update_nav_menu_item( $rev_digital_products_menu_id, 0, array(
				'menu-item-title'  => __( 'Instagram', 'rev-digital-products' ),
				'menu-item-url'    => 'https://www.instagram.com',
				'menu-item-status' => 'publish',
			) );

			if( !has_nav_menu( $rev_digital_products_menulocation ) ){
					$locations = get_theme_mod('nav_menu_locations');
					$locations[$rev_digital_products_menulocation] = $rev_digital_products_menu_id;
					set_theme_mod( 'nav_menu_locations', $locations );
			}
		}
	}

	/**
	* Imports the Demo Content
	* @since 1.1.0
	*/
	public function setup_widgets() {

		//................................................. MENU PAGES .................................................//
		
			$rev_digital_products_home_id='';
			$rev_digital_products_home_content = '';

			$rev_digital_products_home_title = 'Home';
			$rev_digital_products_home = array(
					'post_type' => 'page',
					'post_title' => $rev_digital_products_home_title,
					'post_content'  => $rev_digital_products_home_content,
					'post_status' => 'publish',
					'post_author' => 1,
					'post_slug' => 'home'
			);
			$rev_digital_products_home_id = wp_insert_post($rev_digital_products_home);

			//Set the home page template
			add_post_meta( $rev_digital_products_home_id, '_wp_page_template', 'revolution-home.php' );

			//Set the static front page
			$rev_digital_products_home = get_page_by_title( 'Home' );
			update_option( 'page_on_front', $rev_digital_products_home->ID );
			update_option( 'show_on_front', 'page' );


			// Create a posts page and assign the template
			$rev_digital_products_blog_title = 'Blog';
			$rev_digital_products_blog_check = get_page_by_path('blog');
			if (!$rev_digital_products_blog_check) {
				$rev_digital_products_blog = array(
					'post_type'    => 'page',
					'post_title'   => $rev_digital_products_blog_title,
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_name'    => 'blog' // Unique slug for the blog page
				);
				$rev_digital_products_blog_id = wp_insert_post($rev_digital_products_blog);

				// Set the posts page
				if (!is_wp_error($rev_digital_products_blog_id)) {
					update_option('page_for_posts', $rev_digital_products_blog_id);
				}
			}

			// Create a Contact Us page and assign the template
			$rev_digital_products_contact_title = 'Contact Us';
			$rev_digital_products_contact_check = get_page_by_path('contact');
			if (!$rev_digital_products_contact_check) {
				$rev_digital_products_contact = array(
					'post_type'    => 'page',
					'post_title'   => $rev_digital_products_contact_title,
					'post_content'   => '"More About The Free Digital Download WordPress Theme"
										The Free Digital Download WordPress Theme is an invaluable tool for individuals and businesses looking to launch or expand their digital product ventures. As a free offering, it removes financial barriers, making premium-like functionality accessible to everyone. Designed to support diverse needs, it allows entrepreneurs to build platforms for selling freelance digital products such as voice-over services, script templates, and animated explainer videos. Its fully responsive design ensures a seamless browsing and purchasing experience across desktops, tablets, and smartphones, making it ideal for showcasing products like content strategy guides, virtual coaching programs, or e-learning resources. The integration with WooCommerce simplifies managing transactions, enabling you to handle digital downloads and customer orders effortlessly. With its robust features and user-friendly design, this theme empowers creators to establish a professional online presence without additional costs.

										Whether you\'re creating educational resources or managing a library of video course templates, this theme is designed to meet all your needs efficiently. The highly customizable layout ensures your content aligns seamlessly with your brand’s vision, offering a professional and polished presentation. Its support for image and video galleries lets you showcase key offerings such as animated icons, interactive quizzes, and stock audio in an engaging way. Social media integration provides effortless sharing capabilities, helping you expand your reach and attract more visitors to your site. The theme\'s responsiveness guarantees a smooth user experience on any device, while cross-browser compatibility ensures consistency across platforms. Translation readiness makes it ideal for multilingual audiences, and secure coding ensures data safety. With WooCommerce support, you can manage transactions effectively, and features like testimonials add credibility to your business.

										The Free Digital Download WordPress Theme combines elegant design with powerful functionality, offering an intuitive user experience for both site visitors and business owners. Its clean, minimal layout is perfect for showcasing digital products such as digital blueprints, eBooks, or financial templates. With secure coding, business owners can rest assured that their valuable content is well-protected, reducing the risk of data breaches. The theme is fully customizable, allowing you to tailor your website’s look and feel to match your brand’s identity. For business owners, this theme offers seamless integration with WooCommerce for smooth transactions, as well as cross-browser compatibility and responsive design to ensure that your website performs optimally on all devices. Additionally, the social media integration boosts online visibility and traffic, providing entrepreneurs with the tools needed to grow their digital product business and expand their customer base with ease.',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_name'    => 'contact' // Unique slug for the Contact Us page
				);
				wp_insert_post($rev_digital_products_contact);
			}

			// Create a About page and assign the template
			$rev_digital_products_about_title = 'About';
			$rev_digital_products_about_check = get_page_by_path('about');
			if (!$rev_digital_products_about_check) {
				$rev_digital_products_about = array(
					'post_type'    => 'page',
					'post_title'   => $rev_digital_products_about_title,
					'post_content'   => '"More About The Free Digital Download WordPress Theme"
										The Free Digital Download WordPress Theme is an invaluable tool for individuals and businesses looking to launch or expand their digital product ventures. As a free offering, it removes financial barriers, making premium-like functionality accessible to everyone. Designed to support diverse needs, it allows entrepreneurs to build platforms for selling freelance digital products such as voice-over services, script templates, and animated explainer videos. Its fully responsive design ensures a seamless browsing and purchasing experience across desktops, tablets, and smartphones, making it ideal for showcasing products like content strategy guides, virtual coaching programs, or e-learning resources. The integration with WooCommerce simplifies managing transactions, enabling you to handle digital downloads and customer orders effortlessly. With its robust features and user-friendly design, this theme empowers creators to establish a professional online presence without additional costs.

										Whether you\'re creating educational resources or managing a library of video course templates, this theme is designed to meet all your needs efficiently. The highly customizable layout ensures your content aligns seamlessly with your brand’s vision, offering a professional and polished presentation. Its support for image and video galleries lets you showcase key offerings such as animated icons, interactive quizzes, and stock audio in an engaging way. Social media integration provides effortless sharing capabilities, helping you expand your reach and attract more visitors to your site. The theme\'s responsiveness guarantees a smooth user experience on any device, while cross-browser compatibility ensures consistency across platforms. Translation readiness makes it ideal for multilingual audiences, and secure coding ensures data safety. With WooCommerce support, you can manage transactions effectively, and features like testimonials add credibility to your business.

										The Free Digital Download WordPress Theme combines elegant design with powerful functionality, offering an intuitive user experience for both site visitors and business owners. Its clean, minimal layout is perfect for showcasing digital products such as digital blueprints, eBooks, or financial templates. With secure coding, business owners can rest assured that their valuable content is well-protected, reducing the risk of data breaches. The theme is fully customizable, allowing you to tailor your website’s look and feel to match your brand’s identity. For business owners, this theme offers seamless integration with WooCommerce for smooth transactions, as well as cross-browser compatibility and responsive design to ensure that your website performs optimally on all devices. Additionally, the social media integration boosts online visibility and traffic, providing entrepreneurs with the tools needed to grow their digital product business and expand their customer base with ease.',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_name'    => 'about' // Unique slug for the About page
				);
				wp_insert_post($rev_digital_products_about);
			}

			// Create a Services page and assign the template
			$rev_digital_products_services_title = 'Services';
			$rev_digital_products_services_check = get_page_by_path('services');
			if (!$rev_digital_products_services_check) {
				$rev_digital_products_services = array(
					'post_type'    => 'page',
					'post_title'   => $rev_digital_products_services_title,
					'post_content'   => '"More About The Free Digital Download WordPress Theme"
										The Free Digital Download WordPress Theme is an invaluable tool for individuals and businesses looking to launch or expand their digital product ventures. As a free offering, it removes financial barriers, making premium-like functionality accessible to everyone. Designed to support diverse needs, it allows entrepreneurs to build platforms for selling freelance digital products such as voice-over services, script templates, and animated explainer videos. Its fully responsive design ensures a seamless browsing and purchasing experience across desktops, tablets, and smartphones, making it ideal for showcasing products like content strategy guides, virtual coaching programs, or e-learning resources. The integration with WooCommerce simplifies managing transactions, enabling you to handle digital downloads and customer orders effortlessly. With its robust features and user-friendly design, this theme empowers creators to establish a professional online presence without additional costs.

										Whether you\'re creating educational resources or managing a library of video course templates, this theme is designed to meet all your needs efficiently. The highly customizable layout ensures your content aligns seamlessly with your brand’s vision, offering a professional and polished presentation. Its support for image and video galleries lets you showcase key offerings such as animated icons, interactive quizzes, and stock audio in an engaging way. Social media integration provides effortless sharing capabilities, helping you expand your reach and attract more visitors to your site. The theme\'s responsiveness guarantees a smooth user experience on any device, while cross-browser compatibility ensures consistency across platforms. Translation readiness makes it ideal for multilingual audiences, and secure coding ensures data safety. With WooCommerce support, you can manage transactions effectively, and features like testimonials add credibility to your business.

										The Free Digital Download WordPress Theme combines elegant design with powerful functionality, offering an intuitive user experience for both site visitors and business owners. Its clean, minimal layout is perfect for showcasing digital products such as digital blueprints, eBooks, or financial templates. With secure coding, business owners can rest assured that their valuable content is well-protected, reducing the risk of data breaches. The theme is fully customizable, allowing you to tailor your website’s look and feel to match your brand’s identity. For business owners, this theme offers seamless integration with WooCommerce for smooth transactions, as well as cross-browser compatibility and responsive design to ensure that your website performs optimally on all devices. Additionally, the social media integration boosts online visibility and traffic, providing entrepreneurs with the tools needed to grow their digital product business and expand their customer base with ease.',
					'post_status'  => 'publish',
					'post_author'  => 1,
					'post_name'    => 'services' // Unique slug for the Services page
				);
				wp_insert_post($rev_digital_products_services);
			}


		//................................................. HEADER .................................................//

			set_theme_mod('rev_digital_products_header_info_email','digital@example.com');
			set_theme_mod('rev_digital_products_header_button_text','Help');

			set_theme_mod('rev_digital_products_facebook_link_option','#');
			set_theme_mod('rev_digital_products_twitter_link_option','#');
			set_theme_mod('rev_digital_products_pinterest_link_option','#');
			set_theme_mod('rev_digital_products_instagram_link_option','#');
			set_theme_mod('rev_digital_products_youtube_link_option','#');

			set_theme_mod('rev_digital_products_enable_slider',1);
			set_theme_mod('rev_digital_products_projetcs_sec_show',1);

		//................................................. SLIDER SECTION .................................................//

			for($i=1;$i<=3;$i++){
				set_theme_mod( 'rev_digital_products_slider_image'.$i,get_template_directory_uri().'/revolution/assets/images/slider'.$i.'.png' );
				set_theme_mod( 'rev_digital_products_slider_top_text'.$i, 'WELCOME TO DIGITAL PRODUCT' );
				set_theme_mod( 'rev_digital_products_slider_heading'.$i, 'DIGITAL PRODUCTS WITH A CURATED AUDIENCE OF 2M+' );
				set_theme_mod( 'rev_digital_products_slider_text'.$i, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.' );
				set_theme_mod( 'rev_digital_products_slider_button1_text'.$i, 'READ MORE' );
				set_theme_mod( 'rev_digital_products_slider_button1_link'.$i, '#' );
			}
			

		//................................................. PRODUCT SECTION .................................................//

			set_theme_mod('rev_digital_products_projetcs_main_heading','BEST SELLER');

			$rev_digital_products_plan_icon = array('ALL', 'UI/UX DESIGN', 'LOGO', 'MOBILE APP', 'HTML');
			$rev_digital_products_projetcs_category = array('product_cat1', 'product_cat2', 'product_cat1', 'product_cat2', 'product_cat1');
			for ($rev_digital_products_i = 1; $rev_digital_products_i <= 5; $rev_digital_products_i++) {
				set_theme_mod('rev_digital_products_projetcs_text' . $rev_digital_products_i, $rev_digital_products_plan_icon[$rev_digital_products_i - 1]);
				set_theme_mod('rev_digital_products_projetcs_category' . $rev_digital_products_i, $rev_digital_products_projetcs_category[$rev_digital_products_i - 1]);
			}

			$product_category= array(
				'Digital' => array(
							'PRODUCT NAME 1',
							'PRODUCT NAME 2',
							'PRODUCT NAME 3',
							'PRODUCT NAME 4',
				),
				'Prods' => array(
							'PRODUCT NAME 5',
							'PRODUCT NAME 6',
							'PRODUCT NAME 7',
							'PRODUCT NAME 8',
				),
			);
			$k = 1;
			foreach ( $product_category as $product_cats => $products_name ) {

				// Insert porduct cats Start
				$content = 'Lorem Ipsum is simply dummy text of the printing and setting.';
				$parent_category	=	wp_insert_term(
				$product_cats, // the term
				'product_cat', // the taxonomy
				array(
					'description'=> $content,
					'slug' => 'product_cat'.$k
				));

				$image_url = get_template_directory_uri().'/revolution/assets/images/digital.png';

				$image_name= 'img'.$k.'.png';
				$upload_dir       = wp_upload_dir();
				// Set upload folder
				$image_data= file_get_contents($image_url);
				// Get image data
				$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
				// Generate unique name
				$filename= basename( $unique_file_name );
				// Create image file name

				// Check folder permission and define file location
				if( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
				} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
				}

				// Create the image  file on the server
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				
				WP_Filesystem();
				global $wp_filesystem;
				
				if ( ! $wp_filesystem->put_contents( $file, $image_data, FS_CHMOD_FILE ) ) {
					wp_die( 'Error saving file!' );
				}
				
				// Check image file type
				$wp_filetype = wp_check_filetype( $filename, null );

				// Set attachment data
				$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name( $filename ),
				'post_content'   => '',
				'post_type'     => 'product',
				'post_status'    => 'inherit'
				);

				// Create the attachment
				$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

				// Include image.php
				require_once(ABSPATH . 'wp-admin/includes/image.php');

				// Define attachment metadata
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

				// Assign metadata to attachment
				wp_update_attachment_metadata( $attach_id, $attach_data );

				update_woocommerce_term_meta( $parent_category['term_id'], 'thumbnail_id', $attach_id );

				// create Product START
				foreach ( $products_name as $key => $product_title ) {

					$content = 'Lorem Ipsum is simply dummy text of the printing and setting.';
					// Create post object
					$my_post = array(
						'post_title'    => wp_strip_all_tags( $product_title ),
						'post_content'  => $content,
						'post_status'   => 'publish',
						'post_type'     => 'product',
					);

					// Insert the post into the database
					$post_id    = wp_insert_post($my_post);

					wp_set_object_terms( $post_id, 'product_cat' . $k, 'product_cat', true );

					update_post_meta( $post_id, '_price', "$200.00" );

					// Now replace meta w/ new updated value array
					$image_url = get_template_directory_uri().'/revolution/assets/images/'.str_replace( " ", "-", $product_title).'.png';

					echo $image_url . "<br>";

					$image_name       = $product_title.'.png';
					$upload_dir = wp_upload_dir();
					// Set upload folder
					$image_data = file_get_contents(esc_url($image_url));

					// Get image data
					$unique_file_name = wp_unique_filename($upload_dir['path'], $image_name);
					// Generate unique name
					$filename = basename($unique_file_name);
					// Create image file name

					// Check folder permission and define file location
					if (wp_mkdir_p($upload_dir['path'])) {
						$file = $upload_dir['path'].'/'.$filename;
					} else {
						$file = $upload_dir['basedir'].'/'.$filename;
					}

					// Create the image  file on the server
					if ( ! function_exists( 'WP_Filesystem' ) ) {
						require_once( ABSPATH . 'wp-admin/includes/file.php' );
					}
					
					WP_Filesystem();
					global $wp_filesystem;
					
					if ( ! $wp_filesystem->put_contents( $file, $image_data, FS_CHMOD_FILE ) ) {
						wp_die( 'Error saving file!' );
					}

					// Check image file type
					$wp_filetype = wp_check_filetype($filename, null);

					// Set attachment data
					$attachment = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_title'     => sanitize_file_name($filename),
						'post_type'      => 'product',
						'post_status'    => 'inherit',
					);

					// Create the attachment
					$attach_id = wp_insert_attachment($attachment, $file, $post_id);

					// Include image.php
					require_once (ABSPATH.'wp-admin/includes/image.php');

					// Define attachment metadata
					$attach_data = wp_generate_attachment_metadata($attach_id, $file);

					// Assign metadata to attachment
					wp_update_attachment_metadata($attach_id, $attach_data);

					// And finally assign featured image to post
					set_post_thumbnail($post_id, $attach_id);
				}
				// Create product END
				++$k;
			}
	
		$this->rev_digital_products_customizer_nav_menu();
		$this->rev_digital_products_social_menu();
		update_option('rev_digital_products_demo_imported', true);
	}

	//guidline for about theme
	public function rev_digital_products_guide() {
		$display_string = '';
		//custom function about theme customizer
		$return = add_query_arg( array()) ;
		$theme = wp_get_theme( 'rev-digital-products' );
		?>
		<div class="wrapper-info get-stared-page-wrap">
			<div class="wrapper-info-content">
				<div class="buynow__">
					<h2><?php esc_html_e( 'Welcome to Rev Digital Products', 'rev-digital-products' ); ?> <span class="version">Version: <?php echo esc_html($theme['Version']);?></span></h2>
					<p><?php esc_html_e('The quick setup wizard will assist you in configuring your new website. This wizard will import the demo content.', 'rev-digital-products'); ?></p>
				</div>
				<div class="buynow_">
					<a target="_blank" class="buynow_themepage" href="<?php echo esc_url('https://www.revolutionwp.com/products/digital-products-wordpress-theme'); ?>"><?php echo esc_html__('Go Premium Now', 'rev-digital-products'); ?></a>
				</div>
			</div>
			<div class="tab-sec theme-option-tab">
				<div id="demo_offer" class="tabcontent open">
					<?php $this->wizard_page(); ?>
				</div>
			</div>
		</div>
	<?php }
}