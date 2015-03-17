<?php
/**
 * Settings API Class
 *
 * Create a settings page easily, optionally with tabs and/or sidebar
 *
 * @version 0.0.1
 */
class SettingsAPI {

	/**
	 * settings sections array
	 *
	 * @var array
	 */
	private $settings_sections = array();

	/**
	 * Settings fields array
	 *
	 * @var array
	 */
	private $settings_fields = array();

	/**
	 * Settings tabs array
	 *
	 * @var array
	 */
	private $settings_tabs = array();

	/**
	 * Various information about the current settings page
	 *
	 * @since  0.0.1
	 * @var    array
	 * @access private
	 */
	private $_args;

	/**
	 * The current screen
	 *
	 * @since  0.0.1
	 * @var    object
	 * @access protected
	 */
	protected $screen;

	public function __construct( $instance_args = array() ) {

		$instance_args['id'] = sanitize_key( $instance_args['id'] );

		$args = wp_parse_args(
			$instance_args,
			array(
				// 'id' => $this->screen->id,
				'title'   => __( 'Settings' ),
				'tabbed'  => false,
				'ajax'    => false,
				'sidebar' => false, // @todo sidebar "post-new.php"-style
			)
		);

		$this->_args = $args;

		if ( $args['ajax'] ) {
			add_action( 'admin_footer', array( $this, '_js_vars' ) );
		}

	} // END __construct()

	function register_settings() {

		if ( false == get_option( $this->_args['id'] ) ) {
			add_option( $this->_args['id'] );
		}

		foreach ( $this->get_sections() as $section => $values ) {

			$tab = ( isset( $values['tab'] ) ) ? $values['tab'] : 'nontab';
			$fields = $this->get_fields();

			$this->register_section( $tab, $section, $values );

			//register settings fields
			foreach ( $fields[ $section ] as $section_id => $section_values ) {
				$this->register_field( $tab, $section, $section_id, $section_values );
			}

		} // END foreach sections + fields

		// creates our settings in the options table
		/**
		 * @todo
		foreach ( $this->get_sections() as $section ) {
			register_setting( key( $section ), key( $section ), array( $this, 'sanitize_options' ) );
		}
		*/
	} // END register_settings()

	protected function register_section( $tab, $section, $values ) {

		if ( isset( $values['desc'] ) && !empty( $values['desc'] ) ) {
//			$values['desc']	 = '<div class="inside">' . $values['desc'] . '</div>';
			$callback = create_function( '', 'echo "<p>' . str_replace( '"', '\"', $values['desc'] ) . '</p>";' );
		} else {
			$callback = '__return_false';
		}

		$page = $this->_args['id'] . '_' . $tab;

		add_settings_section( $section, $values['title'], $callback, $page );

	} // END register_section()

	protected function register_field( $tab, $section, $field, $values ) {

		if ( isset( $values['option'] ) ) {

			$option = $values['option'];
			if ( false == get_option( $values['option'] ) ) {
				add_option( $values['option'] );
			}

		} else {
			$option = $this->_args['id'];
		}

		$label = "<label for='{$option}[{$field}]'>" . $values['label'] . '</label>';
		$page = $this->_args['id'] . '_' . $tab;

		$args = array(
			'id'                => $field,
			'desc'              => isset( $values['desc'] ) ? $values['desc'] : '',
			'name'              => $values['label'],
			'section'           => $section,
			'size'              => isset( $values['size'] ) ? $values['size'] : 'regular', // null
			'options'           => isset( $values['options'] ) ? $values['options'] : '',
			'std'               => isset( $values['default'] ) ? $values['default'] : '',
			'sanitize_callback' => isset( $values['sanitize_callback'] ) ? $values['sanitize_callback'] : '',
			'option'            => $option, // (add_)option to be saved to
		);

		if ( isset( $values['type'] ) && method_exists( $this, 'field_' . $values['type'] ) ) {
			$type = $values['type'];
		}
		else {
			$type = 'debug';
			$args['type'] = $values['type'];
		}

		add_settings_field( $field, $label, array( $this, 'field_' . $type ), $page, $section, $args );

	} // END register_field()

	public function display() {

		add_action( 'admin_footer', array( $this, 'js_footer' ) );
		?>
		<div id="<?php echo esc_attr( $this->_args['id'] ); ?>" class="wrap page settings-page">
			<div class="page-header">
				<div class="header-right">
					<?php do_action( 'page_header_right' ); ?>
				</div>
				<h2><?php echo esc_html( $this->_args['title'] ); ?></h2>
				<div class="clear"></div>
			</div>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-<?php echo $this->_args['sidebar'] ? '2' : '1'; ?>">
					<div id="post-body-content" style="position: relative;">
					<?php
					if ( $this->_args['tabbed'] ) {
						$this->tab_nav();
					}
					?>
					<form action="" method="post">
						<?php
						wp_nonce_field( "{$this->_args['id']}-settings-update", "{$this->_args['id']}-settings-nonce" ); // generate ids

						if ( $this->_args['tabbed'] ) {
							$this->tabs();
						} else {
							$this->print_table( $this->_args['id'], true );
						}

						$this->print_submit();
						?>
					</form>
					<?php do_action( "{$this->_args['id']}_post_form" ); // @todo rename ?>
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

	public function get_sections() {
		return apply_filters( "sections_{$this->_args['id']}", $this->settings_sections );
	} // END get_sections()

	/**
	 * Set settings sections
	 *
	 * @param array   $sections setting sections array
	 */
	public function set_sections( $sections ) {

		$this->settings_sections = $sections;

		return $this;

	} // END set_sections()

	public function get_fields() {
		return apply_filters( "fields_{$this->_args['id']}", $this->settings_fields );
	} // END get_fields()

	/**
	 * Set settings fields
	 *
	 * @param array   $fields settings fields array
	 */
	public function set_fields( $fields ) {

		$this->settings_fields = $fields;

		return $this;

	} // END set_fields()

	public function get_tabs() {
		return apply_filters( "tabs_{$this->_args['id']}", $this->settings_tabs );
	} // END get_tabs()

	public function set_tabs( $tabs = array() ) {

		// return array( 'tab' => 'nontab' );
		$this->settings_tabs = $tabs;

		return $this;

	} // END set_tabs()

	public function get_active_tab( $tabs ) {

		$first_tab = key( $tabs );
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $first_tab ;

		return $active_tab;

	} // END get_active_tab()

	protected function tab_nav() {

		$page = $_GET['page']; // @todo secure?
		$tabs = $this->get_tabs();
		$active_tab = $this->get_active_tab( $tabs );

		echo '<h2 class="nav-tab-wrapper" id="' . esc_attr( $this->_args['id'] ) . '" >';

			foreach ( (array) $tabs as $id => $title  ) {
				$active_class = ( $active_tab == $id ? ' nav-tab-active' : '' );
				echo "<a id='{$id}' class='nav-tab{$active_class}' href='?page={$page}&tab={$id}'>{$title}</a>"; // class='{$this->_args['id']}-tab
			} // END foreach

		echo '</h2><!-- .nav-tab-wrapper -->';

	} // END tab_nav()

	protected function tabs() {

		$tabs = $this->get_tabs();
		$active_tab = $this->get_active_tab( $tabs );

		foreach ( (array) $tabs as $tab_id => $title ) {
			$active = ( $active_tab == $tab_id ? true : false );
			$this->print_table( $tab_id, $active );
		} // END foreach

	} // END tabs()

	protected function print_table( $id, $active = false ) { // $id = general|advanced

		$active_tab = $active ? 'display: block;' : 'display: none;';

		echo "<div id='section-{$id}' class='settings-section' style='{$active_tab}'>"; // @todo ESC attributes required?

		$page = $this->_args['id'] . '_' . $id;
		do_settings_sections( $page );

		echo 'do_settings_sections: ' . $page; // TEMP debug

		echo "</div>";

	} // END print_table()

	protected function print_submit() {
		submit_button();
	}

	/** Field Types ******************************************************/

	/**
	 * @todo COPIED + modified
	 * @param type $args
	 */
	public function field_text( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

		// <input id="blogdescription" class="regular-text" type="text" value="Just another WP Trunk Sites site" name="blogdescription">

		$html = sprintf( '<input type="text" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['option'], $args['id'], $value );
		$html .= sprintf( '<p class="description"> %s</p>', $args['desc'] );

		echo $html;

	} // END field_text()

	/**
	 * @todo COPIED + non-modified
	 * @param type $args
	 */
	public function field_checkbox( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['option'], $args['std'] ) );

		$html = sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] );
		$html .= sprintf( '<input type="checkbox" class="checkbox" id="%1$s[%2$s]" name="%1$s[%2$s]" value="on"%4$s />', $args['option'], $args['id'], $value, checked( $value, 'on', false ) );
		$html .= sprintf( '<label for="%1$s[%2$s]"> %3$s</label>', $args['section'], $args['id'], $args['desc'] );

		echo $html;

	} // END field_checkbox()

	/**
	 * @todo COPIED + non-modified
	 * @param type $args
	 */
	public function field_radio( $args ) {

		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );

		$html = '';
		foreach ( $args['options'] as $key => $label ) {
			$html .= sprintf( '<input type="radio" class="radio" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s"%4$s />', $args['option'], $args['id'], $key, checked( $value, $key, false ) );
			$html .= sprintf( '<label for="%1$s[%2$s][%4$s]"> %3$s</label><br>', $args['section'], $args['id'], $label, $key );
		}
		$html .= sprintf( '<span class="description"> %s</label>', $args['desc'] );

		echo $html;

	} // END field_radio()

	/**
	 * @todo COPIED + non-modified
	 * @param type $args
	 */
	public function field_textarea( $args ) {

		$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

		$html = sprintf( '<textarea rows="5" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>', $size, $args['option'], $args['id'], $value );
		$html .= sprintf( '<br><span class="description"> %s</span>', $args['desc'] );

		echo $html;

	}

	/**
	 * @todo COPIED + non-modified
	 * @param type $args
	 */
	public function field_select( $args ) {

		// multiselect option vs different callback??

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';

		$html = sprintf( '<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['option'], $args['id'] );
		foreach ( $args['options'] as $key => $label ) {
			$html .= sprintf( '<option value="%s"%s>%s</option>', $key, selected( $value, $key, false ), $label );
		}
		$html .= sprintf( '</select>' );
		$html .= sprintf( '<p class="description"> %s</p>', $args['desc'] );

		echo $html;

	}

	public function field_debug( $args ) {

		var_dump( $args );

	} // END field_debug()


	/**
	 * Get the value of a settings field
	 *
	 * @todo account for option != section
	 *
	 * @param string  $option  settings field name
	 * @param string  $section the section name this field belongs to
	 * @param string  $default default text if it's not found
	 * @return string
	 */
	public function get_option( $option, $section, $default = '' ) {

		$options = get_option( $section );

		if ( isset( $options[$option] ) ) {
			return $options[$option];
		}

		return $default;
	}

	public function js_footer() { ?>
		<script type="text/javascript">
		jQuery(document).ready(function($){
			$('.nav-tab-wrapper').on('click','a.nav-tab', function(e){
				e.preventDefault();
				if ( ! $(this).hasClass('nav-tab-active') ) {
					$('.settings-section').hide();
					$('.nav-tab').removeClass('nav-tab-active');
					$(this).addClass('nav-tab-active');
					$('#section-' + $(this).attr('id')).show();
				}
			});
		});
		jQuery(document).ready(function($) {
			// Switches option sections
			$('.group').hide();
			var activetab = '';
			if (typeof(localStorage) !== 'undefined' ) {
				activetab = localStorage.getItem("activetab");
			}
			if (activetab !== '' && $(activetab).length ) {
				$(activetab).fadeIn();
			} else {
				$('.group:first').fadeIn();
			}
			$('.group .collapsed').each(function(){
				$(this).find('input:checked').parent().parent().parent().nextAll().each(
				function(){
					if ($(this).hasClass('last')) {
						$(this).removeClass('hidden');
						return false;
					}
					$(this).filter('.hidden').removeClass('hidden');
				});
			});

			if (activetab !== '' && $(activetab + '-tab').length ) {
				$(activetab + '-tab').addClass('nav-tab-active');
			}
			else {
				$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
			}
			$('.nav-tab-wrapper a').click(function(evt) {
				$('.nav-tab-wrapper a').removeClass('nav-tab-active');
				$(this).addClass('nav-tab-active').blur();
				var clicked_group = $(this).attr('href');
				if (typeof(localStorage) != 'undefined' ) {
					localStorage.setItem("activetab", $(this).attr('href'));
				}
				$('.group').hide();
				$(clicked_group).fadeIn();
				evt.preventDefault();
			});
		});
		</script>
		<?php
	} // END js_footer()

	/**
	 * Send required variables to JavaScript land
	 *
	 * @access public
	 */
	public function _js_vars() {
		/**

		$args = array(
			'class'  => get_class( $this ),
			'screen' => array(
				'id'   => $this->screen->id,
				'base' => $this->screen->base,
			)
		);

		printf( "<script type='text/javascript'>settings_args = %s;</script>\n", json_encode( $args ) );

		 */

	} // END _js_vars()

} // END class SettingsAPI
