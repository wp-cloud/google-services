<?php
/**
 * @author    WPStore.io <code@wpstore.io>
 * @copyright Copyright (c) 2015, WPStore.io
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL-2.0+
 * @package   WPStore\GoogleServices
 */

namespace WPStore\GoogleServices;

/**
 * @todo DESC
 *
 * @since 0.0.1
 */
class Admin {

	public $plugin_file;

	private $capability;

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 0.0.1
	 */
	public function __construct( $plugin_file ) {

		$this->plugin_file = $plugin_file;
		$this->capability  = 'manage_options'; // @todo filter

		$this->menus();

		add_action( 'admin_head', array( $this, 'css' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'page_footer', array( $this, 'page_footer' ), 9 );

	} // END __construct()

	/**
	 * @todo desc
	 *
	 * @since  0.0.1
	 * @return string HTML output
	 */
	public function css() {
?>
<style type="text/css">#toplevel_page_google-services img {height: 16px;}</style>
<?php
	} // END css()

	public function enqueue_scripts( $hook ) {

		$admin_pages = apply_filters( 'google_analyticator_scripts', array( '@todo' ) );

		if ( true ) { // in_array( $hook, $admin_pages )

			$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : $this->plugin_version;
			$min     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? ''     : '.min';

			wp_register_style( 'google-services-admin', plugins_url( "/assets/css/admin{$min}.css", $this->plugin_file ), array(), $version );
			wp_enqueue_style( 'google-services-admin' );

		}

	} // END enqueue_scripts()

	protected function menus() {

		$network = is_network_admin() ? 'network_' : '';

		add_action( "{$network}admin_menu", array( $this, 'admin_menu'          ), 9  );
		add_action( "{$network}admin_menu", array( $this, 'admin_menu_settings' ), 99 );

	} // END menus()

	public function admin_menu() {

		add_menu_page(
			__( 'Google Services', 'google-services' ),
			__( 'Google Services', 'google-services' ),
			$this->capability,
			'google-services',
			array( $this, 'page_services' ),
			plugin_dir_url( __FILE__ ) . '../assets/img/developers-icon.svg'
		);

		add_submenu_page(
			'google-services',
			__( 'Google Services', 'google-services' ) . ' &rsaquo; ' . __( 'Services', 'google-services' ),
			__( 'Services', 'google-services' ),
			$this->capability,
			'google-services',
			array( $this, 'page_services' )
		);

	} // END admin_menu()

	public function admin_menu_settings() {

		add_submenu_page(
			'google-services',
			__( 'Google Services', 'google-services' ) . ' &rsaquo; ' . __( 'Settings', 'google-services' ),
			__( 'Settings', 'google-services' ),
			$this->capability,
			'google-services-settings',
			array( $this, 'page_settings' )
		);

	}

	public function page_services() {

		$args = array(
			'id' => 'google-services-services',
			'title' => __( 'Google Services', 'google-services' ),
			'sidebar' => true,
		);

		$services = new Services( $args );

		$services->display();

	} // END

	public function page_settings() {

		$settings = new Settings();
		$settings->display();

	} // END

	public function page_footer( $page ) {

		if ( in_array( $page, array( 'google-services', 'google-services-services' ) ) ) {
			$v    = \GoogleServices::SDK_VERSION;
			$link = "<a href='https://github.com/google/google-api-php-client/releases/tag/{$v}' target='_blank'>v{$v}</a>";
			?>
	<p style="border-top:1px solid lightgray;padding-top:10px;color:grey;text-align:right;">Google APIs Client Library - <?php echo $link; ?></p>
		<?php
		}

	}

	function get_view( $view ) {

		$path = dirname( $this->plugin_file );
		include_once $path . "/GoogleServices/views/{$view}.php";

	}

	public function get_sections() {

		$sections = array(
			'main'		 => array(
				'tab'	 => 'general',
				'title'	 => __( 'Main Options', 'google-services' ), // null -- hide the text
				'desc'	 => __( 'This is a short description for a settings SECTION', 'google-services' ),
			),
			'security'	 => array(
				'tab'	 => 'general',
				'title'	 => __( 'Security', 'google-services' ),
//				'desc'	 => __( 'SEC This is a short description for a settings SECTION', 'google-services' ),
			),
			'privacy'	 => array(
				'tab'	 => 'advanced',
				'title'	 => __( 'Privacy', 'google-services' ),
				'desc'	 => __( 'Privacy! hort description for a settings SECTION', 'google-services' ),
			),
		);

		return $sections;

	}

	public function get_fields() {

		$fields = array(
			'main'		 => array(
				'google_ua'	 => array(
					'label'	 => __( 'Google Analytics UA', 'google-services' ),
					'desc'	 => __( 'Set your UA', 'google-services' ),
					'type'	 => 'google_ua',
//					'option' => 'analytics_2nd', // save into add_option('analytics_2nd') || if (!isset 'option') add_option( $this->_args['id'] )
				),
				'textarea'	 => array(
					'label'	 => __( 'Textarea Input', 'wedevs' ),
					'desc'	 => __( 'Textarea description', 'wedevs' ),
					'type'	 => 'text'
				),
			),
			'security'	 => array(
				'google_ua'	 => array(
					'label'	 => __( 'Text Input (integer validation)', 'google-services' ),
					'desc'	 => __( 'Text input description', 'google-services' ),
					'type'	 => 'google_ua',
				),
				'textarea'	 => array(
					'label'	 => __( 'Textarea Input', 'wedevs' ),
					'desc'	 => __( 'Textarea description', 'wedevs' ),
					'type'	 => 'text'
				),
				'checkbox'	 => array(
					'label'	 => __( 'Checkbox', 'wedevs' ),
					'desc'	 => __( 'Checkbox Label', 'wedevs' ),
					'type'	 => 'text'
				),
			),
			'privacy'	 => array(
				'google_ua'	 => array(
					'label'	 => __( 'Text Input (integer validation)', 'google-services' ),
					'desc'	 => __( 'Text input description', 'google-services' ),
					'type'	 => 'google_ua',
				),
				'textarea'	 => array(
					'label'	 => __( 'Textarea Input', 'wedevs' ),
					'desc'	 => __( 'Textarea description', 'wedevs' ),
					'type'	 => 'textarea'
				),
				'checkbox'	 => array(
					'label'	 => __( 'Checkbox', 'wedevs' ),
					'desc'	 => __( 'Checkbox Label', 'wedevs' ),
					'type'	 => 'checkbox'
				),
				'radio'		 => array(
					'label'		 => __( 'Radio Button', 'wedevs' ),
					'desc'		 => __( 'A radio button', 'wedevs' ),
					'type'		 => 'radio',
					'options'	 => array(
						'yes'	 => 'Yes',
						'no'	 => 'No'
					)
				),
			),
		);

		return $fields;

	}

} // END class Admin
