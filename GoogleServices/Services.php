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
class Services {

	private $args;

	public function __construct( $args = array() ) {
		$this->args = $args;
	}

	public function display() {
		?>
<div id="google-services-services" class="wrap page">
	<div class="page-header">
		<div class="header-right">
			<?php do_action( 'page_header_right' ); ?>
		</div>
		<h2><?php echo $this->args['title']; ?></h2>
	</div>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-<?php echo has_action("ga_sidebar_services") ? '2' : '1'; ?>">
			<div id="post-body-content" style="position: relative;">
				<?php $this->display_services( self::get_services()); ?>
			</div><!-- #post-body-content -->
			<div id="postbox-container-1" class="postbox-container">
				<?php do_action( "ga_sidebar_services" ); ?>
			</div><!-- #postbox-container-1 .postbox-container -->
		</div>
		<div class="clear"></div>
	</div><!-- #poststuff -->
</div><!-- .wrap -->
<?php
	} // END display()

	function display_services( $services ) {
		foreach ( $services as $id => $details ) {
			$this->display_service_section( $id, $details );
		}
	}

	function display_service_section( $id, $details ) { ?>
<section id="<?php echo $id; ?>" class="google-service">
	<div class="icon" style="background: url('<?php echo plugin_dir_url( __FILE__ ) . "../assets/img/{$id}-icon.svg"; ?>')"></div>
	<h4><?php echo $details['name']; ?></h4>
	<p><?php echo $details['url']; ?></p>
	<p><?php echo $details['docs']; ?></p>
</section>
	<?php
	}

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
//			'maps'            => array(
//				'name' => 'Google Maps',
//				'url'  => 'http://www.google.com/maps/',
//				'docs' => 'https://developers.google.com/maps/',
//				'impl' => array(),
//			),
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
