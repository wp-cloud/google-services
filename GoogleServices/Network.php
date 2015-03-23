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

	/**
	 * Constructor. Hooks all interactions to initialize the class.
	 *
	 * @since 0.0.1
	 */
	public function __construct( $plugin_file ) {

		$this->plugin_file = $plugin_file;
		$this->capability  = 'manage_network_options'; // split into info/settings

		parent::__construct( $plugin_file );

	} // END __construct()

} // END class Network
