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
class Settings {

	private $args;

	public function __construct( $args = array() ) {
		$this->args = $args;
	}

	public function display() {
		?>
<div id="google-services-services" class="wrap">
	<header class="page-header">
		<h2><?php echo $this->args['title']; ?></h2>
		<div class="new_right social" id="social-wrapper">
		  <span class="signed-in">
			<div>
			  <a href="https://plus.google.com/me" class"email"="">cfoellmann@googlemail.com</a>
			  <a href="https://www.google.com/accounts/Logout?continue=https://developers.google.com/&amp;service=ahsid">Abmelden</a>
			</div>
			<img src="https://lh5.googleusercontent.com/-ym9oUIXhG7c/AAAAAAAAAAI/AAAAAAAAADQ/xNpU6LyeqVA/photo.jpg?sz=40" class="avatar">
		  </span>
		</div>
	</header>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-<?php echo has_action("ga_sidebar_services") ? '2' : '1'; ?>">
			<div id="post-body-content" style="position: relative;">
				<?php $this->display_services( self::get_services()); ?>
			</div><!-- #post-body-content -->
			<div id="postbox-container-1" class="postbox-container">
				<?php do_action( "ga_sidebar_services" ); ?>
			</div><!-- #postbox-container-1 .postbox-container -->
		</div>
	</div><!-- #poststuff -->
</div><!-- .wrap -->
<?php
	}

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


} // END class Settings
