<?php
/**
 * @author    WPStore.io <code@wpstore.io>
 * @copyright Copyright (c) 2015, WPStore.io
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPL-2.0+
 * @package   WPStore\GoogleServices
 */

namespace WPStore\GoogleServices;

/**
 * @todo desc
 *
 * @since 0.0.1
 */
class Services extends \PageAPI {

	/**
	 * @todo desc
	 * 
	 * @since 0.0.1
	 */
	public function body() {
		$this->display_services( self::get_services() );
	}

	/**
	 * @todo desc
	 * 
	 * @since 0.0.1
	 * @param array $services
	 */
	public function display_services( $services ) {
		foreach ( $services as $id => $details ) {
			$this->display_service_section( $id, $details );
		}
	} // END display_services()

	/**
	 * @todo desc
	 * 
	 * @since  0.0.1
	 * @param  string $id
	 * @param  array $details
	 * @return string HTML output
	 */
	public function display_service_section( $id, $details ) { ?>
<article id="<?php echo $id; ?>" class="google-service">
	<div class="icon" style="background: url('<?php echo plugin_dir_url( __FILE__ ) . "../assets/img/{$id}-icon.svg"; ?>')"></div>
	<h4><?php echo $details['name']; ?></h4>
	<p><?php echo $details['url']; ?></p>
	<p><?php echo $details['docs']; ?></p>
</article>
	<?php
	}

	/**
	 * @todo desc
	 * 
	 * @since  0.0.1
	 * @return array
	 */
	public static function get_services() {
		
		/**
		 * '+' => array(),
		 * 'google-apps' => array(),
		 * 'youtube' => array(),
		 */
		
		$services = array(
			'analytics'       => array(
				'name' => 'Google Analytics',
				'url'  => 'http://www.google.com/analytics/',
				'docs' => 'https://developers.google.com/analytics/',
				'impl' => array( 'google-analyticator' ),
			),
			'maps'            => array(
				'name' => 'Google Maps',
				'url'  => 'http://www.google.com/maps/',
				'docs' => 'https://developers.google.com/maps/',
				'impl' => array(),
			),
//			'webmaster-tools' => array(
//				'name' => 'Webmaster-Tools',
//				'url'  => 'https://www.google.com/webmasters/tools/',
//				'docs' => 'https://developers.google.com/maps/',
//				'impl' => array(),
//			),
		);
		
		return apply_filters( 'google_services', $services );

	} // END get_services()

} // END class Services
