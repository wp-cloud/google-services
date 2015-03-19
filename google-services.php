<?php
/**
 * @author    WPStore.io <code@wpstore.io>
 * @copyright Copyright (c) 2015, WPStore.io
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL-2.0+
 * @package   WPStore\GoogleServices
 * @version   0.0.2-dev
 */
/*
Plugin Name: Google Services
Plugin URI:  http://wpstore.io/plugins/wp-google-services/
Description: @todo
Version:     0.0.2-dev
Author:      WPStore.io
Author URI:  http://wpstore.io
License:     GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wp-google-services
Domain Path: /languages
Network:     True

    Google Services
    Copyright (C) 2015 WPStore.io (http://wpstore.io)

    This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @todo DESC
 *
 * @since 0.0.1
 */
class GoogleServices {

	/**
	 * @since 0.0.1
	 */
	const SDK_VERSION = 'master';

	/**
	 * @todo DESC
	 *
	 * @since 0.0.1
	 */
	public static function init() {

		self::require_files();

		new \WPStore\GoogleServices\Init( __FILE__ );

		if ( is_network_admin() ) {
			new \WPStore\GoogleServices\Network( __FILE__ );
		} else {
			new \WPStore\GoogleServices\Admin( __FILE__ );
		}

	} // END init()

	/**
	 * @todo DESC
	 *
	 * @since 0.0.1
	 */
	private static function require_files() {

		$path = dirname( __FILE__ );

		require_once $path . '/libs/autoload.php';
		
	} // END require_files()

	/**
	 * @todo desc
	 * 
	 * @since  0.0.1
	 * @return array
	 */
	public static function get_defaults() {

		$defaults = array(
			'setting' => '',
		);

		return apply_filters( 'google_services_defaults', $defaults );

	} // END get_defaults()
	
	/**
	 * @todo DESC
	 *
	 * @todo Check for PHP >= 5.3
	 * @todo Check for PHP json: extension_loaded('json')
	 * @todo Check WP version >= 3.8
	 * @todo redirect to welcome/auth/plugin page
	 *
	 * @since  0.0.1
	 * @param  bool $network_wide
	 * @return void
	 */
	public static function activation( $network_wide ) {
		
		require dirname( __FILE__ ) . '/activation.php';

		$activation = new GoogleServices_Activation( $network_wide );

		$activation->check_wp( '3.8' );
		$activation->check_php( '5.3' );
		$activation->recommend_php();
		$activation->check_php_extension( array( 'json' ) );
		// $activation->check_php_curl( array( 'version' => '7.35.0', 'ssl_version' => 'OpenSSL/1.0.1f' ) );

		$activation->run();

		add_action( 'activated_plugin', array( 'GoogleServices', 'welcome_redirect' ) );

	} // END activation()

	public static function welcome_redirect( $plugin ) {
		if ( $plugin == plugin_basename( __FILE__ ) ) { // $this->plugin_file
			exit( wp_redirect( self_admin_url( 'admin.php?page=google-services' ) ) );
		}
	}

} // END class GoogleServices

/** (De-)Activation */
register_activation_hook( __FILE__, array( 'GoogleServices', 'activation' ) );
// register_deactivation_hook( __FILE__, array( 'GoogleServices', 'activation' ) );

/**
 * @todo 'plugins_loaded' vs 'init'? Priority < '10'?
 * @todo compat check?
 */
/** Start the plugin */
add_action( 'plugins_loaded', array( 'GoogleServices', 'init' ) );
