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
class Network extends Admin {

	public $settings;

	private $capability, $plugin_file;

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public function __construct( $plugin_file ) {

		$this->plugin_file = $plugin_file;
		$this->capability  = 'manage_network_options'; // split into info/settings

		$args = array(
			'id'      => 'google-services',
			'title'   => __( 'Settings' ),
			'tabbed'  => true,
			'sidebar' => true,
		);
		
		$this->settings = new \SettingsAPI( $args );

		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'network_admin_menu', array( $this, 'admin_menu'          ), 9  );
		add_action( 'network_admin_menu', array( $this, 'admin_menu_settings' ), 99 );

		add_action( 'admin_head', array( $this, 'css' ) );
		add_action( 'ga_sidebar_services', array( $this, 'ga_sidebar_services' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	} // END __construct()

	public function admin_init() {

		$this->settings->set_tabs( $this->get_tabs() );
		$this->settings->set_sections( $this->get_sections() );
		$this->settings->set_fields( $this->get_fields() );

        $this->settings->register_settings();
    }

	function css() {
?>
<style type="text/css">#toplevel_page_google-services img {height: 16px;}</style>
<?php
	}

	public function enqueue_scripts( $hook ) {

		$version = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : $this->plugin_version;
		$min     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? ''     : '.min';

		wp_register_style( 'google-services-admin', plugins_url( "/assets/css/admin{$min}.css", $this->plugin_file ), array(), $version );
		wp_enqueue_style( 'google-services-admin' );

	}

	public function ga_services() {
		$this->get_view('services');
	}

	public function ga_sidebar_services() {
		$this->get_view('sidebar_services');
	}

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

		$this->settings->display();

	} // END

	function get_page( $view, $args = array() ) {

		extract( $args );

		include 'views/_header.php';
//		include "views/{$view}.php";
		?>
		<div id="post-body" class="metabox-holder columns-<?php echo has_action("ga_sidebar_{$view}") ? '2' : '1'; ?>">
			<div id="post-body-content" style="position: relative;">
				<?php
				include "{$view}.php";
//				do_action( "ga_{$view}" ); ?>
			</div><!-- #post-body-content -->
			<div id="postbox-container-1" class="postbox-container">
				<?php do_action( "ga_sidebar_{$view}" ); ?>
			</div><!-- #postbox-container-1 .postbox-container -->
		</div>
		<?php
		include 'views/_footer.php';

	}

	static function get_view( $view ) {
		include "views/{$view}.php";
	}




	public function get_tabs() {

		$tabs = array(
			'general'  => __( 'General', 'google-services' ),
			'advanced' => __( 'Advanced', 'google-services' ),
		);

		return $tabs;

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

} // END class
