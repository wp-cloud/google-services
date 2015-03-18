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

	public $settings, $plugin_file;

	private $capability;

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 0.0.1
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
		
		$this->settings = new Settings( $args );

		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'network_admin_menu', array( $this, 'admin_menu'          ), 9  );
		add_action( 'network_admin_menu', array( $this, 'admin_menu_settings' ), 99 );

		add_action( 'admin_head', array( $this, 'css' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	} // END __construct()

//	public function admin_init() {
//
//		$this->settings->set_tabs( $this->get_tabs() );
//		$this->settings->set_sections( $this->get_sections() );
//		$this->settings->set_fields( $this->get_fields() );
//
//        $this->settings->register_settings();
//    }

	public function page_services() {

		$args = array(
			'id'      => 'google-services-services',
			'title'   => __( 'Google Services', 'google-services' ),
			'sidebar' => true,
		);

		$services = new Services( $args );

		$services->display();

	} // END

	public function page_settings() {

		$this->settings->display();

	} // END

} // END class Network
