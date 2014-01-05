<?php
/*
Plugin Name: Standard Widget Extensions
Plugin URI: http://en.hetarena.com/standard-widget-extensions
Description: Adds Sticky Sidebar and Accordion Widget features to your WordPress sites.
Version: 1.4
Author: Hirokazu Matsui (blogger323)
Text Domain: standard-widget-extensions
Domain Path: /languages
Author URI: http://en.hetarena.com/
License: GPLv2
*/

class HM_SWE_Plugin_Loader {

	const VERSION        = '1.4';
	const OPTION_VERSION = '1.4';
	const OPTION_KEY     = 'hm_swe_options';
	const I18N_DOMAIN    = 'standard-widget-extensions';
	const PREFIX         = 'hm_swe_';

	public static $default_hm_swe_option = array(
		'expert_options'         => 'disabled',
		'maincol_id'             => 'primary',
		'sidebar_id'             => 'secondary',
		'widget_class'           => 'widget',
		'readable_js'            => 'disabled',
		'accordion_widget'       => 'enabled',
		'heading_marker'         => 'default',
		'custom_plus'            => '',
		'custom_minus'           => '',
		'enable_css'             => 'enabled',
		'single_expansion'       => 'disabled',
		'slide_duration'         => 400,
		'heading_string'         => 'h3',
		'accordion_widget_areas' => array( '' ),
		'custom_selectors'       => array( '' ),
		'scroll_stop'            => 'enabled',
		'scroll_mode'            => 1,
		'proportional_sidebar'   => 0,
		'disable_iflt'           => 620,
		'recalc_after'           => 5,
		'ignore_footer'          => 'disabled',

		'sidebar_id2'            => '',
		'proportional_sidebar2'  => 0,
		'disable_iflt2'          => 0,
	);

	// index for field array
	const I_EXPORT_OPTIONS         = 0;
	const I_MAINCOL_ID             = 1;
	const I_SIDEBAR_ID             = 2;
	const I_WIDGET_CLASS           = 3;
	const I_READABLE_JS            = 4;
	const I_ACCORDION_WIDGET       = 5;
	const I_HEADING_MARKER         = 6;
	const I_ENABLE_CSS             = 7;
	const I_SINGLE_EXPANSION       = 8;
	const I_SLIDE_DURATION         = 9;
	const I_HEADING_STRING         = 10;
	const I_ACCORDION_WIDGET_AREAS = 11;
	const I_CUSTOM_SELECTORS       = 12;
	const I_SCROLL_STOP            = 13;

	const I_SCROLL_MODE            = 14;
	const I_RECALC_AFTER           = 15;
	const I_IGNORE_FOOTER          = 16;

	const I_PROPORTIONAL_SIDEBAR   = 17;
	const I_DISABLE_IFLT           = 18;

	// for 2nd sidebar
	const I_SIDEBAR_ID2            = 19;
	const I_PROPORTIONA_SIDEBAR2   = 20;
	const I_DISABLE_IFLT2          = 21;


	// field array
	private static $settings_field =
			array(
				// Hidden options
				array(
					'id'       => 'expert_options',
					'title'    => '',
					'callback' => 'settings_field_expert_options',
					'section'  => 'hm_swe_main',
				),

				// General options
				array(
					'id'       => 'maincol_id',
					'title'    => 'ID of Your Main Column',
					'callback' => 'settings_field_maincol_id',
					'section'  => 'hm_swe_main',
				),
				array(
					'id'       => 'sidebar_id',
					'title'    => 'ID of Your Sidebar',
					'callback' => 'settings_field_sidebar_id',
					'section'  => 'hm_swe_main',
				),
				array(
					'id'       => 'widget_class',
					'title'    => 'Class of Widgets',
					'callback' => 'settings_field_widget_class',
					'section'  => 'hm_swe_main',
				),
				array(
					'id'       => 'readable_js',
					'title'    => 'Readable .js File',
					'expert'   => 1,
					'callback' => 'settings_field_readable_js',
					'section'  => 'hm_swe_main',
					'options'  => array(
						array( 'id' => 'enable', 'title' => 'Enable', 'value' => 'enabled' ),
						array( 'id' => 'disable', 'title' => 'Disable (minimized)', 'value' => 'disabled' ),
					),
				),

				// Accordion Widgets options
				array(
					'id'       => 'accordion_widget',
					'title'    => 'Accordion Widgets',
					'callback' => 'settings_field_accordion_widget',
					'section'  => 'hm_swe_accordion_widget',
					'options'  => array(
						array( 'id' => 'enable', 'title' => 'Enable', 'value' => 'enabled' ),
						array( 'id' => 'disable', 'title' => 'Disable', 'value' => 'disabled' ),
					),
				),
				array(
					'id'       => 'heading_marker',
					'title'    => 'Icons for Heading',
					'callback' => 'settings_field_heading_marker',
					'section'  => 'hm_swe_accordion_widget',
					'options'  => array(
						array( 'id' => 'none', 'title' => 'None', 'value' => 'none' ),
						array( 'id' => 'default', 'title' => 'Default', 'value' => 'default' ),
						array( 'id' => 'custom', 'title' => 'Custom', 'value' => 'custom' )
					),
				),
				array(
					'id'       => 'enable_css',
					'title'    => 'Include Default CSS for Icons',
					'expert'   => 1,
					'callback' => 'settings_field_enable_css',
					'section'  => 'hm_swe_accordion_widget',
					'options'  => array(
						array( 'id' => 'enable', 'title' => 'Yes (Recommended)', 'value' => 'enabled' ),
						array( 'id' => 'disable', 'title' => 'No', 'value' => 'disabled' ),
					),
				),
				array(
					'id'       => 'single_expansion',
					'title'    => 'Single Expansion Mode',
					'expert'   => 1,
					'callback' => 'settings_field_single_expansion',
					'section'  => 'hm_swe_accordion_widget',
					'options'  => array(
						array( 'id' => 'enable', 'title' => 'Enable', 'value' => 'enabled' ),
						array( 'id' => 'disable', 'title' => 'Disable', 'value' => 'disabled' ),
					),
				),
				array(
					'id'       => 'slide_duration',
					'title'    => 'Slide Duration (ms)',
					'expert'   => 1,
					'callback' => 'settings_field_slide_duration',
					'section'  => 'hm_swe_accordion_widget',
				),
				array(
					'id'       => 'heading_string',
					'title'    => 'Selector for Headings',
					'expert'   => 1,
					'callback' => 'settings_field_heading_string',
					'section'  => 'hm_swe_accordion_widget',
				),
				array(
					'id'       => 'accordion_widget_areas',
					'title'    => 'Widget area IDs in which AW is effective (optional, comma delimited)',
					'expert'   => 1,
					'callback' => 'settings_field_accordion_widget_areas',
					'section'  => 'hm_swe_accordion_widget',
				),
				array(
					'id'       => 'custom_selectors',
					'title'    => 'Custom Widget Selectors (will override default)',
					'expert'   => 1,
					'callback' => 'settings_field_custom_selectors',
					'section'  => 'hm_swe_accordion_widget',
				),

				// Sticky Sidebar options
				array(
					'id'       => 'scroll_stop',
					'title'    => 'Sticky Sidebar',
					'callback' => 'settings_field_scroll_stop',
					'section'  => 'hm_swe_scroll_stop',
					'options'  => array(
						array( 'id' => 'enable', 'title' => 'Enable', 'value' => 'enabled' ),
						array( 'id' => 'disable', 'title' => 'Disable', 'value' => 'disabled' ),
					),
				),
				array(
					'id'       => 'scroll_mode',
					'title'    => 'Quick Switchback Mode',
					'callback' => 'settings_field_scroll_mode',
					'section'  => 'hm_swe_scroll_stop',
					'options'  => array(
						array( 'id' => 'enable', 'title' => 'Enable', 'value' => '2' ),
						array( 'id' => 'disable', 'title' => 'Disable', 'value' => '1' ),
					),
				),
				array(
					'id'       => 'recalc_after',
					'title'    => 'Recalc Timer (sec, 0=never)',
					'expert'   => 1,
					'callback' => 'settings_field_recalc_after',
					'section'  => 'hm_swe_scroll_stop',
				),
				array(
					'id'       => 'ignore_footer',
					'title'    => 'Ignore Footer (for Infinite Scroll Pages)',
					'expert'   => 1,
					'callback' => 'settings_field_ignore_footer',
					'section'  => 'hm_swe_scroll_stop',
					'options'  => array(
						array( 'id' => 'enable', 'title' => 'Enable', 'value' => 'enabled' ),
						array( 'id' => 'disable', 'title' => 'Disable', 'value' => 'disabled' ),
					),
				),

				array(
					'id'       => 'proportional_sidebar',
					'title'    => 'Proportional Sidebar (width in percent, 0=fixed)',
					'expert'   => 1,
					'callback' => 'settings_field_proportional_sidebar',
					'section'  => 'hm_swe_scroll_stop',
				),
				array(
					'id'       => 'disable_iflt',
					'title'    => 'Disable if the window width is less than',
					'expert'   => 1,
					'callback' => 'settings_field_disable_iflt',
					'section'  => 'hm_swe_scroll_stop',
				),

				// 2nd sidebar
				array(
					'id'       => 'sidebar_id2',
					'title'    => '[2nd] ID of the 2nd Sidebar',
					'expert'   => 1,
					'callback' => 'settings_field_sidebar_id2',
					'section'  => 'hm_swe_scroll_stop',
				),
				array(
					'id'       => 'proportional_sidebar2',
					'title'    => '[2nd] Proportional Sidebar',
					'expert'   => 1,
					'callback' => 'settings_field_proportional_sidebar2',
					'section'  => 'hm_swe_scroll_stop',
				),
				array(
					'id'       => 'disable_iflt2',
					'title'    => '[2nd] Disable the 2nd sidebar if the window width is less than',
					'expert'   => 1,
					'callback' => 'settings_field_disable_iflt2',
					'section'  => 'hm_swe_scroll_stop',
				),
			);

	function __construct() {
		register_activation_hook( __FILE__, array( &$this, 'activate' ) );
		add_action( 'plugins_loaded', array( &$this, 'plugins_loaded' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ), 20 );
		add_action( 'wp_head', array( &$this, 'wp_head' ) );
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_head', array( &$this, 'admin_head' ) );
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
	}

	function plugins_loaded() {
		load_plugin_textdomain( 'standard-widget-extensions', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	function activate() {
		if ( version_compare( get_bloginfo( 'version' ), '3.2', '<' ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate this plugin
		}
	}

	function get_widget_selectors($without_widget_class = false) {
		$options = $this->get_hm_swe_option();
		$custom_selectors = $options['custom_selectors'];
		if ( !is_array( $custom_selectors ) || implode(',', $custom_selectors ) === '' ) {
			$custom_selectors = array();
			foreach ( $options['accordion_widget_areas'] as $area ) {
				array_push( $custom_selectors, ( $area ? "#" . $area : "#" . $options['sidebar_id'] ) . ($without_widget_class ? "" : ( " ." . $options['widget_class'] ) ) );
			}
		}
		return $custom_selectors;
	}

	function enqueue_scripts() {
		$options = $this->get_hm_swe_option();
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-cookie', plugins_url( '/js/jquery.cookie.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'standard-widget-extensions',
			plugins_url( '/js/standard-widget-extensions' . ($this->get_hm_swe_option('readable_js') == 'enabled' ? '.js' : '.min.js'), __FILE__ ) );

		$custom_selectors = $this->get_widget_selectors();


		$params = array(
			'buttonplusurl'          => $options['heading_marker'] == 'custom' ? "url(" . $options['custom_plus'] . ")" :
					"url(" . plugins_url( '/images/plus.gif', __FILE__ ) . ")",
			'buttonminusurl'         => $options['heading_marker'] == 'custom' ? "url(" . $options['custom_minus'] . ")" :
					"url(" . plugins_url( '/images/minus.gif', __FILE__ ) . ")",
			'maincol_id'             => esc_attr( $options['maincol_id'] ),
			'sidebar_id'             => esc_attr( $options['sidebar_id'] ),
			'widget_class'           => esc_attr( $options['widget_class'] ),
			'readable_js'            => $options['readable_js'] == 'enabled',
			'heading_marker'         => $options['heading_marker'] != 'none',
			'scroll_stop'            => $options['scroll_stop'] == 'enabled',
			'accordion_widget'       => $options['accordion_widget'] == 'enabled',
			'single_expansion'       => $options['single_expansion'] == 'enabled',
			'heading_string'         => esc_attr( $options['heading_string'] ),
			'proportional_sidebar'   => $options['proportional_sidebar'],
			'disable_iflt'           => $options['disable_iflt'],
			'accordion_widget_areas' => array_map( 'esc_attr', $options['accordion_widget_areas'] ),
			'scroll_mode'            => ( $options['scroll_mode'] == "2" ? 2 : 1 ),
			'ignore_footer'          => $options['ignore_footer'] == 'enabled',
			'custom_selectors'       => $custom_selectors,
			'slide_duration'         => $options['slide_duration'],
			'recalc_after'           => $options['recalc_after'],

			'sidebar_id2'            => esc_attr( $options['sidebar_id2'] ),
			'proportional_sidebar2'  => $options['proportional_sidebar2'],
			'disable_iflt2'          => $options['disable_iflt2'],
		);
		wp_localize_script( 'standard-widget-extensions', 'swe', $params );
	}

	function wp_head() {
		$options = $this->get_hm_swe_option();
		if ( $options['accordion_widget'] === 'enabled' && $options['heading_marker'] !== 'none' && $options['enable_css'] === 'enabled'
				&& implode( ',', $options['custom_selectors'] ) === '' ) {
			$area_array = array_map( 'esc_attr', $this->get_widget_selectors( true ) );
			$headstr      = "";
			$areastr    = "";
			foreach ( $area_array as $i => $area ) {
				$headstr .= $area . " ." . $options['widget_class'] . " " . $options['heading_string'] . ( $i + 1 == count( $area_array ) ? "\n" : ",\n" );
				$areastr .= $area . ( $i + 1 == count( $area_array ) ? "\n" : ",\n" );
			} // for

			?>
			<style type="text/css">
				<?php echo $headstr; ?>
				{
					zoom: 1	; /* for IE7 to display background-image */
					padding-left: 20px;
					margin-left: -20px;
				}

				<?php echo $areastr; ?>
				{
					overflow: visible	;
				}
			</style>
		<?php
		} // if
	} // wp_head

	function admin_init() {
		add_settings_section( 'hm_swe_main', _x( 'General', 'title', self::I18N_DOMAIN ),
			array( &$this, 'main_section_text' ), 'hm_swe_option_page' );
		add_settings_section( 'hm_swe_accordion_widget', _x( 'Accordion Widgets', 'title', self::I18N_DOMAIN ),
			array( &$this, 'empty_text' ), 'hm_swe_option_page' );
		add_settings_section( 'hm_swe_scroll_stop', _x( 'Sticky Sidebar', 'title', self::I18N_DOMAIN ),
			array( &$this, 'empty_text' ), 'hm_swe_option_page' );

		foreach ( self::$settings_field as $f ) {
			$title = __( $f['title'], self::I18N_DOMAIN );
		  if ( isset( $f['expert'] ) ) {
				$title = "<span class='swe-expert-params'>" . $title . "</span>";
			}
			add_settings_field( self::PREFIX . $f['id'], $title, array( &$this, $f['callback'] ),
				'hm_swe_option_page', $f['section'] );
		}

		register_setting( 'hm_swe_option_group', self::OPTION_KEY, array( &$this, 'validate_options' ) );
	}

	function admin_head() {
?>
	<style>
		span.swe-expert-params {
			font-style: italic;
		}
	</style>
		<script type="text/javascript">
			(function($, window, document) {
				$(document).ready(function(){
					<?php
							if ($this->get_hm_swe_option( 'expert_options' ) != 'enabled') {
					?>
					$('span.swe-expert-params').each(function() {
						$(this).parent().parent().hide();
					});
					<?php
							}
							else {
					?>
					$('#swe-expert-button').addClass('swe-show-expert');
					<?php
							}
					?>
					$('#swe-expert-button').click( function() {
						if ($(this).hasClass('swe-show-expert')) {
							$(this).attr('value', '<?php echo __( 'Show Expert Options', self::I18N_DOMAIN ) ?>');
							$(this).removeClass('swe-show-expert');
							$('#expert_options').attr('value', 'disabled');
							$('span.swe-expert-params').each(function() {
								$(this).parent().parent().hide();
							});
						}
						else {
							$(this).attr('value', '<?php echo __( 'Hide Expert Options', self::I18N_DOMAIN ) ?>');
							$(this).addClass('swe-show-expert');
							$('#expert_options').attr('value', 'enabled');
							$('span.swe-expert-params').each(function() {
								$(this).parent().parent().show();
							});
						}
						return false;
					});
				});
			})(jQuery, window, document);
		</script>
	<?php
	}

	function main_section_text() {
		echo __( "<p>Use primary/secondary/widget for Twenty Twelve and Twenty Eleven.\nUse container/primary/widget-container for Twenty Ten.</p>", self::I18N_DOMAIN );
		echo '<input id="swe-expert-button" class="button button-primary" type="submit" value="' .
				( $this->get_hm_swe_option( 'expert_options' ) == 'enabled' ? __( 'Hide Expert Options', self::I18N_DOMAIN ) : __( 'Show Expert Options', self::I18N_DOMAIN ) ) . '" />';
	}

	function empty_text() {
	}

	function get_hm_swe_option( $key = NULL ) {
		// The get_option doesn't seem to merge retrieved values and default values.
		$options = array_merge( self::$default_hm_swe_option, (array) get_option( self::OPTION_KEY, array() ) );
		return $key ? $options[$key] : $options;
	}

	function write_text_option( $index, $v = NULL ) {
		$id = self::$settings_field[$index]['id'];
		if ( $v === NULL ) {
			$v = $this->get_hm_swe_option( $id );
		}
		echo "<input class='regular-text' id='$id' name='" . self::OPTION_KEY . "[$id]' type='text' value='" . esc_attr( $v ) . "' />";
	}

	function write_radio_option( $index ) {
		$id = self::$settings_field[$index]['id'];
		$v  = $this->get_hm_swe_option( $id );
		foreach ( self::$settings_field[$index]['options'] as $o ) {
			echo "<label title='" . $o['id'] . "'><input type='radio' name='" . self::OPTION_KEY . "[$id]' value='" . $o['value'] . "' " .
					( $v == $o['value'] ? "checked='checked'" : "" ) . "/><span>" . __( $o['title'], self::I18N_DOMAIN ) . "</span></label><br />\n";

		}
	}

	function settings_field_sidebar_id() {
		$this->write_text_option( self::I_SIDEBAR_ID );
	}

	function settings_field_maincol_id() {
		$this->write_text_option( self::I_MAINCOL_ID );
	}

	function settings_field_widget_class() {
		$this->write_text_option( self::I_WIDGET_CLASS );
	}

	function settings_field_accordion_widget_areas() {
		$this->write_text_option( self::I_ACCORDION_WIDGET_AREAS,
			implode( ",", (array) $this->get_hm_swe_option( self::$settings_field[self::I_ACCORDION_WIDGET_AREAS]['id'] ) ) );
	}

	function settings_field_custom_selectors() {
		$this->write_text_option( self::I_CUSTOM_SELECTORS,
			implode( ",", (array) $this->get_hm_swe_option( self::$settings_field[self::I_CUSTOM_SELECTORS]['id'] ) ));
	}

	function settings_field_heading_marker() {
		$i = self::I_HEADING_MARKER;
		echo "<fieldset><legend class='screen-reader-text'><span>" . self::$settings_field[$i]['title'] . "</span></legend>\n";

		$this->write_radio_option( $i );

		echo "<table><tr><td>" . __( "Plus button URL", self::I18N_DOMAIN ) . "</td><td><input class='regular-text' id='custom_plus' name='" .
				self::OPTION_KEY . "[custom_plus]' type='text' value='" .
				esc_attr( $this->get_hm_swe_option( 'custom_plus' ) ) . "' /></td></tr>\n";
		echo "<tr><td>" . __( "Minus buttom URL", self::I18N_DOMAIN ) . "</td><td><input class='regular-text' id='custom_minus' name='" .
				self::OPTION_KEY . "[custom_minus]' type='text' value='" .
				esc_attr( $this->get_hm_swe_option( 'custom_minus' ) ) . "' /></td></tr></table>";

		echo "</fieldset>\n";
	}

	function settings_field_simple_radio_option( $i ) {
		echo "<fieldset><legend class='screen-reader-text'><span>" . self::$settings_field[$i]['title'] . "</span></legend>\n";
		$this->write_radio_option( $i );
		echo "</fieldset>\n";
	}

	function settings_field_scroll_stop() {
		$this->settings_field_simple_radio_option( self::I_SCROLL_STOP );
	}

	function settings_field_scroll_mode() {
		$this->settings_field_simple_radio_option( self::I_SCROLL_MODE );
	}

	function settings_field_readable_js() {
		$this->settings_field_simple_radio_option( self::I_READABLE_JS );
	}

	function settings_field_ignore_footer() {
		$this->settings_field_simple_radio_option( self::I_IGNORE_FOOTER );
	}

	function settings_field_accordion_widget() {
		$this->settings_field_simple_radio_option( self::I_ACCORDION_WIDGET );
	}

	function settings_field_enable_css() {
		$this->settings_field_simple_radio_option( self::I_ENABLE_CSS );
	}

	function settings_field_single_expansion() {
		$this->settings_field_simple_radio_option( self::I_SINGLE_EXPANSION );
	}

	function settings_field_heading_string() {
		$this->write_text_option( self::I_HEADING_STRING );
	}

	function settings_field_proportional_sidebar() {
		$this->write_text_option( self::I_PROPORTIONAL_SIDEBAR );
	}

	function settings_field_disable_iflt() {
		$this->write_text_option( self::I_DISABLE_IFLT );
	}

	function settings_field_slide_duration() {
		$this->write_text_option( self::I_SLIDE_DURATION );
	}

	function settings_field_recalc_after() {
		$this->write_text_option( self::I_RECALC_AFTER );
	}

	function settings_field_sidebar_id2() {
		$this->write_text_option( self::I_SIDEBAR_ID2 );
	}

	function settings_field_proportional_sidebar2() {
		$this->write_text_option( self::I_PROPORTIONA_SIDEBAR2) ;
	}

	function settings_field_disable_iflt2() {
		$this->write_text_option( self::I_DISABLE_IFLT2 );
	}

	function settings_field_expert_options() {
		$id = self::$settings_field[ self::I_EXPORT_OPTIONS ]['id'];
		$v = $this->get_hm_swe_option( $id );

		echo "<input id='$id' name='" . self::OPTION_KEY . "[$id]' type='hidden' value='" . esc_attr( $v ) . "' />";

	}

	function validate_options( $input ) {
		$valid = array();
		$prev  = $this->get_hm_swe_option();

		$valid['heading_marker']   = $input['heading_marker'];
		$valid['scroll_stop']      = $input['scroll_stop'];
		$valid['scroll_mode']      = $input['scroll_mode'];
		$valid['accordion_widget'] = $input['accordion_widget'];
		$valid['enable_css']       = $input['enable_css'];
		$valid['single_expansion'] = $input['single_expansion'];
		$valid['readable_js']      = $input['readable_js'];
		$valid['ignore_footer']    = $input['ignore_footer'];
		$valid['expert_options']   = $input['expert_options'];


		// Proportional Sidebar
		if ( filter_var( $input['proportional_sidebar'], FILTER_VALIDATE_FLOAT ) === FALSE ) {
			add_settings_error( 'hm_swe_proportional_sidebar', 'hm_swe_proportional_sidebar_error', __( 'The Proportional Sidebar value has to be a number.', self::I18N_DOMAIN ) );
			$valid['proportional_sidebar'] = $prev['proportional_sidebar'];
		}
		else {
			$valid['proportional_sidebar'] = $input['proportional_sidebar'];
		}

		if ( filter_var( $input['disable_iflt'], FILTER_VALIDATE_INT ) === FALSE ) {
			add_settings_error( 'hm_swe_disable_iflt', 'hm_swe_disable_iflt_error', __( 'The minimum width has to be a number.', self::I18N_DOMAIN ) );
			$valid['disable_iflt'] = $prev['disable_iflt'];
		}
		else {
			$valid['disable_iflt'] = $input['disable_iflt'];
		}

		// the plus icon
		if ( $input['heading_marker'] == 'custom' &&
				! ( filter_var( $input['custom_plus'], FILTER_VALIDATE_URL ) !== FALSE && preg_match( '/http/i', $input['custom_plus'] ) )
		) {
			add_settings_error( 'hm_swe_custom_plus', 'hm_swe_custom_plus_error', __( 'Wrong URL for the plus button', self::I18N_DOMAIN ) );
			$valid['custom_plus']    = $prev['custom_plus'];
			$valid['heading_marker'] = $prev['heading_marker'];
		}
		else {
			$valid['custom_plus'] = $input['custom_plus'];
		}

		// the minus icon
		if ( $input['heading_marker'] == 'custom' &&
				! ( filter_var( $input['custom_minus'], FILTER_VALIDATE_URL ) !== FALSE && preg_match( '/http/i', $input['custom_minus'] ) )
		) {
			add_settings_error( 'hm_swe_custom_minus', 'hm_swe_custom_minus_error', __( 'Wrong URL for the minus button', self::I18N_DOMAIN ) );
			$valid['custom_minus']   = $prev['custom_minus'];
			$valid['heading_marker'] = $prev['heading_marker'];
		}
		else {
			$valid['custom_minus'] = $input['custom_minus'];
		}

		if ( ! preg_match( '/^[a-zA-Z0-9_\-]+$/', $input['maincol_id'] ) ) {
			add_settings_error( 'hm_swe_maincol_id', 'hm_swe_maincol_id_error', __( 'Wrong main column ID', self::I18N_DOMAIN ) );
			$valid['maincol_id'] = $prev['maincol_id'];
		}
		else {
			$valid['maincol_id'] = $input['maincol_id'];
		}

		if ( ! preg_match( '/^[a-zA-Z0-9_\-]+$/', $input['sidebar_id'] ) ) {
			add_settings_error( 'hm_swe_sidebar_id', 'hm_swe_sidebar_id_error', __( 'Wrong sidebar ID', self::I18N_DOMAIN ) );
			$valid['sidebar_id'] = $prev['sidebar_id'];
		}
		else {
			$valid['sidebar_id'] = $input['sidebar_id'];
		}

		if ( ! preg_match( '/^[a-zA-Z0-9_\-]+$/', $input['widget_class'] ) ) {
			add_settings_error( 'hm_swe_widget_class', 'hm_swe_widget_class_error', __( 'Wrong widget class', self::I18N_DOMAIN ) );
			$valid['widget_class'] = $prev['widget_class'];
		}
		else {
			$valid['widget_class'] = $input['widget_class'];
		}

		if ( is_array( $input['accordion_widget_areas'] ) ) { // This function would be called from add_option.
			$input['accordion_widget_areas'] = implode( ',', $input['accordion_widget_areas'] );
		}
		if ( ! preg_match( '/^[a-zA-Z0-9_\-, ]*$/', $input['accordion_widget_areas'] ) ) {
			add_settings_error( 'hm_swe_accordion_widget_areas', 'hm_swe_accordion_widget_areas_error', __( 'Wrong widget areas', self::I18N_DOMAIN ) );
			$valid['accordion_widget_areas'] = $prev['accordion_widget_areas'];
		}
		else {
			$valid['accordion_widget_areas'] = explode( ",", str_replace( " ", "", $input['accordion_widget_areas'] ) );
		}

		if ( is_array( $input['custom_selectors'] ) ) { // This function would be called from add_option.
			$input['custom_selectors'] = implode( ',', $input['custom_selectors'] );
		}
		if ( ! preg_match( '/^[a-zA-Z0-9_\-\.#, ]*$/', $input['custom_selectors'] ) ) {
			add_settings_error( 'hm_swe_custom_selectors', 'hm_swe_custom_selectors_error', __( 'Wrong custom selectors', self::I18N_DOMAIN ) );
			$valid['custom_selectors'] = $prev['custom_selectors'];
		}
		else {
			$valid['custom_selectors'] = explode( ",", $input['custom_selectors'] );
		}

		if ( ! preg_match( '/^[a-zA-Z0-9_\-\.# ]+$/', $input['heading_string'] ) ) {
			add_settings_error( 'hm_swe_heading_string', 'hm_swe_heading_string_error', __( 'Wrong heading selector', self::I18N_DOMAIN ) );
			$valid['heading_string'] = $prev['heading_string'];
		}
		else {
			$valid['heading_string'] = $input['heading_string'];
		}

		if ( filter_var( $input['slide_duration'], FILTER_VALIDATE_INT ) === FALSE ) {
			add_settings_error( 'hm_swe_slide_duration', 'hm_swe_slide_duration_error', __( 'The Slide Duration has to be a number.', self::I18N_DOMAIN ) );
			$valid['slide_duration'] = $prev['slide_duration'];
		}
		else {
			$valid['slide_duration'] = $input['slide_duration'];
		}

		if ( filter_var( $input['recalc_after'], FILTER_VALIDATE_INT ) === FALSE ) {
			add_settings_error( 'hm_swe_recalc_after', 'hm_swe_recalc_after_error', __( 'The Recalc Timer has to be a number.', self::I18N_DOMAIN ) );
			$valid['recalc_after'] = $prev['recalc_after'];
		}
		else {
			$valid['recalc_after'] = $input['recalc_after'];
		}

		if ( $input['sidebar_id2'] !== '' && ! preg_match( '/^[a-zA-Z0-9_\-]+$/', $input['sidebar_id2'] ) ) {
			add_settings_error( 'hm_swe_sidebar_id2', 'hm_swe_sidebar_id2_error', __( 'Wrong 2nd sidebar ID', self::I18N_DOMAIN ) );
			$valid['sidebar_id2'] = $prev['sidebar_id2'];
		}
		else {
			$valid['sidebar_id2'] = $input['sidebar_id2'];
		}

		if ( filter_var( $input['proportional_sidebar2'], FILTER_VALIDATE_FLOAT ) === FALSE ) {
			add_settings_error( 'hm_swe_proportional_sidebar2', 'hm_swe_proportional_sidebar2_error', __( 'The 2nd proportional sidebar value has to be a number.', self::I18N_DOMAIN ) );
			$valid['proportional_sidebar2'] = $prev['proportional_sidebar2'];
		}
		else {
			$valid['proportional_sidebar2'] = $input['proportional_sidebar2'];
		}

		if ( filter_var( $input['disable_iflt2'], FILTER_VALIDATE_INT ) === FALSE ) {
			add_settings_error( 'hm_swe_disable_iflt2', 'hm_swe_disable_iflt2_error', __( 'The minimum width for the 2nd sidebar has to be a number.', self::I18N_DOMAIN ) );
			$valid['disable_iflt2'] = $prev['disable_iflt2'];
		}
		else {
			$valid['disable_iflt2'] = $input['disable_iflt2'];
		}

		$valid['option_version'] = self::OPTION_VERSION;
		return $valid;
	}

	function admin_menu() {
		add_options_page( __( 'Standard Widget Extensions', self::I18N_DOMAIN ), __( 'Standard WE', self::I18N_DOMAIN ),
			'manage_options', 'hm_swe_option_page', array( &$this, 'admin_page' ) );
	}

	function admin_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php echo __( "Standard Widget Extensions", self::I18N_DOMAIN ); ?></h2>

			<form action="options.php" method="post">
				<?php settings_fields( 'hm_swe_option_group' ); ?>
				<?php do_settings_sections( 'hm_swe_option_page' ); ?>
				<p class="submit"><input class="button-primary" name="Submit" type="submit" value="<?php echo __( 'Save Changes' ); ?>" /></p>
			</form>
		</div>
	<?php
	}

} // end of class HM_SWE_Plugin_Loader

$hm_swe_plugin_loader = new HM_SWE_Plugin_Loader();

