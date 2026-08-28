<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( !class_exists( 'rev_digital_products_Welcome' ) ) {

	class rev_digital_products_Welcome {
		public $theme_fields;

		public function __construct( $fields = array() ) {
			$this->theme_fields = $fields;
			add_action ('admin_init' , array( $this, 'admin_scripts' ) );
			add_action('admin_menu', array( $this, 'rev_digital_products_getstart_page_menu' ));
		}

		public function admin_scripts() {
			global $pagenow;
			$file_dir = get_template_directory_uri() . '/getstarted/assets/';

			if ( $pagenow === 'themes.php' && isset($_GET['page']) && $_GET['page'] === 'rev-digital-products-getstart-page' ) {

				wp_enqueue_style (
					'rev-digital-products-getstart-page-style',
					$file_dir . 'css/getstart-page.css',
					array(), '1.0.0'
				);

				wp_enqueue_script (
					'rev-digital-products-getstart-page-functions',
					$file_dir . 'js/getstart-page.js',
					array('jquery'),
					'1.0.0',
					true
				);
			}
		}

        public function theme_info($id, $screenshot = false) {
            $themedata = wp_get_theme();
            return ($screenshot === true) ? esc_url($themedata->get_screenshot()) : esc_html($themedata->get($id));
        }

        public function rev_digital_products_getstart_page_menu() {
            add_theme_page(
                /* translators: 1: Theme Name. */
                sprintf(esc_html__('About %1$s', 'rev-digital-products'), $this->theme_info('Name')),
                sprintf(esc_html__('About %1$s', 'rev-digital-products'), $this->theme_info('Name')),
                'edit_theme_options',
                'rev-digital-products-getstart-page',
                array( $this, 'rev_digital_products_getstart_page' )
            );
		}

        public function rev_digital_products_getstart_page() {
            $tabs = array(
                'rev_digital_products_getting_started' => esc_html__('Getting Started', 'rev-digital-products'),
                'rev_digital_products_free_pro' => esc_html__('Free VS Pro', 'rev-digital-products'),
                'changelog' => esc_html__('Changelog', 'rev-digital-products'),
                'support' => esc_html__('Support', 'rev-digital-products'),
                'review' => esc_html__('Rate & Review', 'rev-digital-products'),
            );
            ?>
                <div class="wrap about-wrap access-wrap">

                    <div class="abt-promo-wrap clearfix">
                        <div class="abt-theme-wrap">
                            <h1>
                                <?php
                                printf(
                                    /* translators: 1: Theme Name. */
                                    esc_html__('Welcome to %1$s - Version %2$s', 'rev-digital-products'),
                                    esc_html($this->theme_info('Name')),
                                    esc_html($this->theme_info('Version'))
                                );
                                ?>
                            </h1>
                            <div class="buttons">
                                <a target="_blank" href="<?php echo esc_url('https://www.revolutionwp.com/products/digital-products-wordpress-theme'); ?>"><?php echo esc_html__('Buy Pro Theme', 'rev-digital-products'); ?></a>
                                <a target="_blank" href="<?php echo esc_url('https://demo.revolutionwp.com/digital-products-pro/'); ?>"><?php echo esc_html__('Preview Pro Version', 'rev-digital-products'); ?></a>
                            </div>
                        </div>
                    </div>

                    <div class="nav-tab-wrapper clearfix">
                        <?php
                            $tabHTML = '';

                            foreach ($tabs as $id => $label) :

                                $target = '';
                                $nav_class = 'nav-tab';
                                $section = isset($_GET['section']) ? sanitize_text_field($_GET['section']) : 'rev_digital_products_getting_started';

                                if ($id === $section) {
                                    $nav_class .= ' nav-tab-active';
                                }

                                if ($id === 'rev_digital_products_free_pro') {
                                    $nav_class .= ' upgrade-button';
                                }

                                switch ($id) {

                                    case 'support':
                                        $target = 'target="_blank"';
                                        $url = esc_url('https://wordpress.org/support/theme/' . esc_html($this->theme_info('TextDomain')));
                                    break;

                                    case 'review':
                                        $target = 'target="_blank"';
                                        $url = esc_url('https://wordpress.org/support/theme/' . esc_html($this->theme_info('TextDomain')) . '/reviews/#new-post');
                                    break;
                                    
                                    case 'rev_digital_products_getting_started':
                                        $url = esc_url(admin_url('themes.php?page=rev-digital-products-getstart-page'));
                                    break;

                                    default:
                                        $url = esc_url(admin_url('themes.php?page=rev-digital-products-getstart-page&section=' . esc_attr($id)));
                                    break;

                                }

                                $tabHTML .= '<a ';
                                $tabHTML .= $target;
                                $tabHTML .= ' href="' . $url . '"';
                                $tabHTML .= ' class="' . esc_attr($nav_class) . '"';
                                $tabHTML .= '>';
                                $tabHTML .= esc_html($label);
                                $tabHTML .= '</a>';

                            endforeach;

                            echo $tabHTML;
                        ?>
                    </div>

                    <div class="getstart-section-wrapper">
                        <div class="getstart-section rev_digital_products_getting_started clearfix">
                            <?php
                                $section = isset($_GET['section']) ? sanitize_text_field($_GET['section']) : 'rev_digital_products_getting_started';
                                switch ($section) {

                                    case 'rev_digital_products_free_pro':
                                        $this->rev_digital_products_free_pro();
                                    break;

                                    case 'changelog':
                                        $this->changelog();
                                    break;

                                    case 'rev_digital_products_getting_started':
                                    default:
                                        $this->rev_digital_products_getting_started();
                                    break;

                                }
                            ?>
                        </div>
                    </div>

                </div>
            <?php
		}

        public function rev_digital_products_getting_started() {
            ?>
            <div class="getting-started-top-wrap clearfix">
                <div class="theme-details">
                    <div class="theme-screenshot">
                        <img src="<?php echo esc_url( $this->theme_info( 'Screenshot', true ) ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'rev-digital-products' ); ?>"/>
                    </div>
                    <div class="about-text"><?php echo esc_html( $this->theme_info( 'Description' ) ); ?></div>
                    <div class="clearfix"></div>
                </div>
                <div class="theme-steps-list">
                    <div class="theme-steps demo-import">
                        <h3><?php echo esc_html__( 'One Click Demo Import', 'rev-digital-products' ); ?></h3>
                        <p><?php echo esc_html__( 'Easily set up your website with our One Click Demo Import feature. This functionality allows you to replicate our demo site with just a single click, ensuring you have a fully functional layout to start from. Whether you’re a beginner or an experienced developer, this tool simplifies the setup process, saving you time and effort.', 'rev-digital-products' ); ?></p>
                        <a target="_blank" class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=revdigitalproducts-demoimport' ) ); ?>"><?php echo esc_html__( 'Click Here For Demo Import', 'rev-digital-products' ); ?></a>
                    </div>
                    <div class="getstart">
                        <div class="theme-steps">
                            <h3><?php echo esc_html__( 'Documentation', 'rev-digital-products' ); ?></h3>
                            <p><?php echo esc_html__( 'Need more details? Check our comprehensive documentation for step-by-step guidance on using the Rev Digital Products Theme.', 'rev-digital-products' ); ?></p>
                            <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://demo.revolutionwp.com/wpdocs/digital-products-free/' ); ?>"><?php echo esc_html__( 'Go to Free Docs', 'rev-digital-products' ); ?></a>
                        </div>

                        <div class="theme-steps">
                            <h3><?php echo esc_html__( 'Preview Pro Theme', 'rev-digital-products' ); ?></h3>
                            <p><?php echo esc_html__( 'Discover the full potential of our Pro Theme! Click the Live Demo button to experience premium features and beautiful designs.', 'rev-digital-products' ); ?></p>
                            <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://demo.revolutionwp.com/digital-products-pro/' ); ?>"><?php echo esc_html__( 'Live Demo', 'rev-digital-products' ); ?></a>
                        </div>

                        <div class="theme-steps highlight">
                            <h3><?php echo esc_html__( 'Buy Rev Digital Products Pro', 'rev-digital-products' ); ?></h3>
                            <p><?php echo esc_html__( 'Unlock unlimited features and enhancements by purchasing the Pro version of Rev Digital Products Theme.', 'rev-digital-products' ); ?></p>
                            <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://www.revolutionwp.com/products/digital-products-wordpress-theme' ); ?>"><?php echo esc_html__( 'Buy Pro Version @$39', 'rev-digital-products' ); ?></a>
                        </div>

                        <div class="theme-steps highlight">
                            <h3><?php echo esc_html__( 'Get the Bundle', 'rev-digital-products' ); ?></h3>
                            <p><?php echo esc_html__( 'The WordPress Theme Bundle is a comprehensive collection of all premium themes, offering everything you need to create stunning, professional websites with ease.', 'rev-digital-products' ); ?></p>
                            <a target="_blank" class="button button-primary" href="<?php echo esc_url( 'https://www.revolutionwp.com/products/wordpress-theme-bundle' ); ?>"><?php echo esc_html__( 'Get Bundle', 'rev-digital-products' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

		public function rev_digital_products_free_pro() {
            ?>
            <table class="card table free-pro" cellspacing="0" cellpadding="0">
                <tbody class="table-body">
                    <tr class="table-head">
                        <th class="large"><?php echo esc_html__( 'Features', 'rev-digital-products' ); ?></th>
                        <th class="indicator"><?php echo esc_html__( 'Free theme', 'rev-digital-products' ); ?></th>
                        <th class="indicator"><?php echo esc_html__( 'Pro Theme', 'rev-digital-products' ); ?></th>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'One Click Demo Import', 'rev-digital-products' ); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
                                        <?php echo esc_html__( 'After the activation of Rev Digital Products theme, all settings will be imported and Data Import.', 'rev-digital-products' ); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Responsive Design', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Site Logo upload', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Footer Copyright text', 'rev-digital-products' ); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
                                        <?php echo esc_html__( 'Remove the copyright text from the Footer.', 'rev-digital-products' ); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Global Color', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Regular Bug Fixes', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Theme Sections', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="abc"><?php echo esc_html__( '2 Sections', 'rev-digital-products' ); ?></span></td>
                        <td class="indicator"><span class="abc"><?php echo esc_html__( '15+ Sections', 'rev-digital-products' ); ?></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Custom colors', 'rev-digital-products' ); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
                                        <?php echo esc_html__( 'Choose a color for links, buttons, icons and so on.', 'rev-digital-products' ); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Google fonts', 'rev-digital-products' ); ?></h4>
                                <div class="feature-inline-row">
                                    <span class="info-icon dashicon dashicons dashicons-info"></span>
                                    <span class="feature-description">
                                        <?php echo esc_html__( 'You can choose and use over 600 different fonts, for the logo, the menu and the titles.', 'rev-digital-products' ); ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Enhanced Plugin Integration', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Fully SEO Optimized', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Premium Support', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Extensive Customization', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'Custom Post Types', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="feature-row">
                        <td class="large">
                            <div class="feature-wrap">
                                <h4><?php echo esc_html__( 'High-Level Compatibility with Modern Browsers', 'rev-digital-products' ); ?></h4>
                            </div>
                        </td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-no-alt" size="30"></span></td>
                        <td class="indicator"><span class="dashicon dashicons dashicons-yes" size="30"></span></td>
                    </tr>

                    <tr class="upsell-row">
                        <td></td>
                        <td><span class="abc"><?php echo esc_html__( 'Try Out Our Premium Version', 'rev-digital-products' ); ?></span></td>
                        <td>
                            <a target="_blank" href="<?php echo esc_url( 'https://www.revolutionwp.com/products/digital-products-wordpress-theme' ); ?>" class="button button-primary"><?php echo esc_html__( 'Buy Pro Theme', 'rev-digital-products' ); ?></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
        }

		public function changelog() {
            if ( is_file( trailingslashit( get_stylesheet_directory() ) . '/getstarted/rev_digital_products_changelog.php' ) ) {
                require_once( trailingslashit( get_stylesheet_directory() ) . '/getstarted/rev_digital_products_changelog.php' );
            } else {
                require_once( trailingslashit( get_template_directory() ) . '/getstarted/rev_digital_products_changelog.php' );
            }
        }
	}

}
new rev_digital_products_Welcome();
?>