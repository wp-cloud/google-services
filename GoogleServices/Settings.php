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
class Settings extends \SettingsAPI {

	/**
	 * @todo desc
	 *
	 * @since  0.0.1
	 * @param  array $instance_args
	 */
	public function __construct() {

		$args = array(
			'id'      => 'google-services',
			'title'   => __( 'Settings' ),
			'sidebar' => true,
			'tabs'    => array(
				'general'  => __( 'General', 'google-services' ),
				'advanced' => __( 'Advanced', 'google-services' ),
			),
		);

		parent::__construct( $args );

	} // END __construct()

} // END class Settings
