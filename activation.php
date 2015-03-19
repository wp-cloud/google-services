<?php
/**
 * @author    WPStore.io <code@wpstore.io>
 * @copyright Copyright (c) 2015, WPStore.io
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL-2.0+
 * @package   WPStore\GoogleServices
 */

/**
 * @todo
 *
 * @since 0.0.1
 */
class GoogleServices_Activation {

	/**
	 * @todo desc
	 *
	 * @since 0.0.1
	 * @var   array
	 */
	private $warnings;

	/**
	 * @todo desc
	 * 
	 * @since 0.0.1
	 * @var   array Notices
	 */
	private $notices;

	private $plugin_file;

	/**
	 * @todo desc
	 *
	 * @since 0.0.1
	 * @param string $plugin_file
	 * @param bool $network_wide
	 */
	public function __construct( $plugin_file, $network_wide ) {

		$this->plugin_file = $plugin_file;

	} // END __construct()

	/**
	 * @todo sec
	 * 
	 * @since 0.0.1
	 */
	public function run() {

		// wp_die( $this->warnings ); // wp_die() Alternative?

		$msg_warning = '';
		$msg_notice  = '';

		if ( $this->warnings ) {
			foreach ( $this->warnings as $warning ) {
				$msg_warning .= $warning;
			}
		}

		if ( $this->notices ) {
			foreach ( $this->notices as $notice ) {
				$msg_notice .= $notice;
			}
		}

		if ( '' !== $msg_warning ) {
			// show warnings
			// abort activation
		}

		if ( '' !== $msg_notice ) {
			// save notice(s) to db (transient?)
			// display notice(s)
		}

	} // END run()

	/**
	 * @todo desc
	 * 
	 * @since 0.0.1
	 * @param string $required_version
	 */
	public function check_wp( $required_version = '3.8' ) {
		if ( version_compare( get_bloginfo( 'version' ), $required_version, '<' ) ) {
			// deactivate_plugins( __FILE__ ); // __FILE__ does not work here
			$this->warnings[] = sprintf( __( "WordPress %s and higher required. The plugin has now disabled itself. On a side note why are you running an old version :( Upgrade!", 'google-services' ), $required_version );
		}
	} // END check_wp()

	/**
	 * @todo desc
	 *
	 * @since 0.0.1
	 * @param string $required_version
	 */
	public function check_php( $required_version = '5.3.29' ) {
		if ( version_compare( PHP_VERSION, $required_version, '<' ) ) {
			$this->warnings[] = sprintf( __( "version is lower than %s", 'google-services' ), $required_version );
		}
	} // END check_php()

	/**
	 * @todo desc
	 *
	 * @link   http://php.net/supported-versions.php
	 * @since  0.0.1
	 * @param  string $recommended_version
	 * @return string Notice
	 */
	public function recommend_php( $version = '' ) {

		if ( '' !== $version ) {
			$recommended_version = $version;
		} else {
			/**
			 * PHP 5.3 End of life: 2014-08-14 (5.3.29)
			 * PHP 5.4 End of life: 2015-09-14
			 * PHP 5.5 End of life: 2016-06-20
			 * PHP 5.6 End of life: 2017-08-28
			 */
			$today = time();
			$date  = strtotime("2015-09-14");

			if ( $date > $today ) {
				$recommended_version = '5.5.18'; // current 5.5.x as of 2015-03-18
			} else if ( $date < $today ) {
				$recommended_version = '5.6.6'; // current 5.6.x as of 2015-03-18
			}
		}
		
		if ( version_compare( PHP_VERSION, $recommended_version, '<' ) ) {
			$this->notices[] = sprintf( __( "version is lower than %s", 'google-services' ), "<code>{$recommended_version}</code>", "<code>" . PHP_VERSION . "</code>" );
		}
	} // END recommend_php()

	/**
	 * @todo desc
	 * 
	 * @since  0.0.1
	 * @param  array $required_extensions
	 * @param  bool $output
	 * @return bool|array
	 */
	public function check_php_extension( array $required_extensions, $output = true ) {

		$loaded_extensions = get_loaded_extensions();

		foreach ( $required_extensions as $extension ) {
			if ( in_array( $extension, $loaded_extensions ) ) {
				if ( $output ) {
					// ok - extension loaded
//					$ext = new \ReflectionExtension( $extension );
//					$version = $ext->getVersion();
//					$name    = $ext->getName();
				} else {
					return true;
				}
			} else {
				if ( $output ) {
					$this->warnings[] = sprintf( __( "%s extension is not installed.", 'google-services' ), "<code>{$extension}</code>" );
				} else {
					return false;
				}
			}
		} // END foreach

	} // END check_php_extension()

	/**
	 * @todo desc
	 *
	 * @todo verbose mode <http://www.nextscripts.com/tutorials/how-to-check-if-curlssl-is-working-properly/>
	 *
	 * @link http://curl.haxx.se/docs/security.html cURL security
	 * @since 0.0.1
	 * @param array $requirements
	 * @param bool $verbose
	 */
	public function check_php_curl( array $requirements, $verbose = false ) {
		
		if ( ! self::check_php_extension( array('curl'), false ) ) {
			$this->warnings[] = sprintf( __( "%s extension is not installed.", 'google-services' ), '<code>cURL</code>' );
		} else {
			$versions = curl_version();
			$v        = isset( $requirements['version'] )     ? $requirements['version']     : false;
			$v_ssl    = isset( $requirements['ssl_version'] ) ? $requirements['ssl_version'] : false;

			// version_compare( get_bloginfo( 'version' ), $required_version, '<' );

		}
	} // END check_php_curl()

} // END class GoogleServices_Activation
