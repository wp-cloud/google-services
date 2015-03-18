<?php
/**
 * @author    WPStore.io <code@wpstore.io>
 * @copyright Copyright (c) 2015, WPStore.io
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL-2.0+
 * @package   WPStore\GoogleServices
 * @version   0.0.1
 */
/*
Plugin Name: Google Services
Plugin URI:  http://wpstore.io/plugins/wp-google-services/
Description: @todo
Version:     0.0.1
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
	 * @todo DESC
	 *
	 * @since 0.0.1
	 * 
	 * @return void
	 */
	public static function init() {

		self::require_files();

		new \WPStore\GoogleServices\Init( __FILE__ );

		if ( is_network_admin() ) {
			new \WPStore\GoogleServices\Network( __FILE__ );
		} else {
			new \WPStore\GoogleServices\Admin( __FILE__ );
		}

//		register_activation_hook( __FILE__, array( '\\WPStore\\GoogleServices', 'activation' ) );

	} // END init()

	/**
	 * @todo DESC
	 *
	 * @since 0.0.1
	 *
	 * @return void
	 */
	private static function require_files() {

		$path = dirname( __FILE__ );

		require_once $path . '/libs/Google/autoload.php';
		require_once $path . '/../AdminSDK/SettingsAPI.php';
		require_once $path . '/../AdminSDK/PageAPI.php';
		require_once $path . '/GoogleServices/Admin.php';
		require_once $path . '/GoogleServices/Network.php';
		require_once $path . '/GoogleServices/Services.php';
		require_once $path . '/GoogleServices/Init.php';

	} // END require_files()

	public function get_defaults() {

		$defaults = array();

		return apply_filters( 'google_services_defaults', $defaults );

	} // END get_defaults()

	/**
	 * @todo DESC
	 * 
	 * @since 0.0.1
	 *
	 * @return void
	 */
	public static function activation() {

		/**
		 * Check for PHP >= 5.3
		 *
		 * Check for PHP json:
		 *   extension_loaded('json')
		 *
		 * Check WP version
		 */

	} // END activation()

}

/**
 * @todo 'plugins_loaded' vs 'init'? Priority < '10'?
 * @todo compat check?
 */
add_action( 'plugins_loaded', array( 'GoogleServices', 'init' ) );
