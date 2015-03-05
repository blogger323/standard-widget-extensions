<?php
/*
Plugin Name: Standard Widget Extensions
Plugin URI: http://en.hetarena.com/standard-widget-extensions
Description: Adds Sticky Sidebar and Accordion Widget features to your WordPress sites.
Version: 1.7.4
Author: Hirokazu Matsui (blogger323)
Text Domain: standard-widget-extensions
Domain Path: /languages
Author URI: http://en.hetarena.com/
License: GPLv2
*/

class HM_SWE_Plugin_Loader {

	const VERSION        = '1.7.4';
	const OPTION_VERSION = '1.7';
	const OPTION_KEY     = 'hm_swe_options';
	const I18N_DOMAIN    = 'standard-widget-extensions';
	const PREFIX         = 'hm_swe_';

	public static $default_hm_swe_option = array(
		'expert_options'         => 'disabled', // deprecated
		'maincol_id'             => 'primary',
		'sidebar_id'             => 'secondary',
		'widget_class'           => 'widget',
		'readable_js'            => 'disabled',
		'accordion_widget'       => 'enabled',
		'heading_marker'         => 'default',
		'custom_plus'            => '',
		'custom_minus'           => '',
		'enable_css'             => 'enabled', // deprecated
		'single_expansion'       => 'disabled',
        'initially_collapsed'    => 'enabled',
		'slide_duration'         => 400,
		'heading_string'         => 'h3',
		'accordion_widget_areas' => array( '' ),
		'custom_selectors'       => array( '' ),
		'scroll_stop'            => 'enabled',
		'scroll_mode'            => 1, // 1: Normal, 2: Quick Switch Back
		'proportional_sidebar'   => 0,
		'disable_iflt'           => 620,
		'recalc_after'           => 5,
        'recalc_count'           => 2,
		'header_space'           => 0,
		'ignore_footer'          => 'disabled',
        'enable_reload_me'       => 'disabled', // deprecated
        'float_attr_check_mode'  => 'disabled',

		'sidebar_id2'            => '',
		'proportional_sidebar2'  => 0,
		'disable_iflt2'          => 0,
        'float_attr_check_mode2' => 'disabled',
	);

	// index for field array

    const I_MAINCOL_ID             = 0;
    const I_SIDEBAR_ID             = 1;
    const I_WIDGET_CLASS           = 2;
    const I_ACCORDION_WIDGET       = 3;
    const I_HEADING_MARKER         = 4;
    const I_SINGLE_EXPANSION       = 5;
    const I_INITIALLY_COLLAPSED    = 6;
    const I_SLIDE_DURATION         = 7;
    const I_HEADING_STRING         = 8;
    const I_ACCORDION_WIDGET_AREAS = 9;
    const I_CUSTOM_SELECTORS       = 10;
    const I_SCROLL_STOP            = 11;

    const I_SCROLL_MODE            = 12;
    const I_RECALC_AFTER           = 13;  // Now it means the interval.
    const I_RECALC_COUNT           = 14;
    const I_HEADER_SPACE           = 15;
    const I_IGNORE_FOOTER          = 16;

    const I_DISABLE_IFLT           = 17;
    const I_FLOAT_ATTR_CHECK__MODE = 18;

	// for 2nd sidebar
    const I_SIDEBAR_ID2            = 19;
    const I_DISABLE_IFLT2          = 20;
    const I_FLOAT_ATTR_CHECK__MODE2 = 21;

    const I_READABLE_JS            = 22;


    // field array
	private static $settings_field =
			array(

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

				// Accordion Widgets options
				array(
					'id'       => 'accordion_widget',
					'title'    => 'Accordion Widgets',
					'callback' => 'settings_field_accordion_widget',
					'section'  => 'hm_swe_main',
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
                    'id'       => 'initially_collapsed',
                    'title'    => 'Initial State',
                    'expert'   => 1,
                    'callback' => 'settings_field_initially_collapsed',
                    'section'  => 'hm_swe_accordion_widget',
                    'options'  => array(
                        array( 'id' => 'enable', 'title' => 'Initially Collapsed', 'value' => 'enabled' ),
                        array( 'id' => 'disable', 'title' => 'Leave them as styled', 'value' => 'disabled' ),
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
					'section'  => 'hm_swe_main',
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
                    'id'       => 'recalc_count',
                    'title'    => 'Recalc Count',
                    'expert'   => 1,
                    'callback' => 'settings_field_recalc_count',
                    'section'  => 'hm_swe_scroll_stop',
                ),
                array(
					'id'       => 'header_space',
					'title'    => 'Header Space',
					'expert'   => 1,
					'callback' => 'settings_field_header_space',
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
					'id'       => 'disable_iflt',
					'title'    => 'Disable if the window width is less than',
					'expert'   => 1,
					'callback' => 'settings_field_disable_iflt',
					'section'  => 'hm_swe_scroll_stop',
				),

                // The 'float' attribute Check Mode
                array(
                    'id'       => 'float_attr_check_mode',
                    'title'    => "The 'float' attribute Check Mode",
                    'expert'   => 1,
                    'callback' => 'settings_field_float_attr_check_mode',
                    'section'  => 'hm_swe_scroll_stop',
                    'options'  => array(
                        array( 'id' => 'enable', 'title' => 'Enable', 'value' => 'enabled' ),
                        array( 'id' => 'disable', 'title' => 'Disable', 'value' => 'disabled' ),
                    ),
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
					'id'       => 'disable_iflt2',
					'title'    => '[2nd] Disable the 2nd sidebar if the window width is less than',
					'expert'   => 1,
					'callback' => 'settings_field_disable_iflt2',
					'section'  => 'hm_swe_scroll_stop',
				),
                array(
                    'id'       => 'float_attr_check_mode2',
                    'title'    => "[2nd] The 'float' attribute Check Mode",
                    'expert'   => 1,
                    'callback' => 'settings_field_float_attr_check_mode2',
                    'section'  => 'hm_swe_scroll_stop',
                    'options'  => array(
                        array( 'id' => 'enable', 'title' => 'Enable', 'value' => 'enabled' ),
                        array( 'id' => 'disable', 'title' => 'Disable', 'value' => 'disabled' ),
                    ),
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

			);

	function __construct() {
        register_activation_hook( __FILE__, array( &$this, 'activate' ) );
        add_action( 'plugins_loaded', array( &$this, 'plugins_loaded' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ), 20 );
        add_action( 'wp_head', array( &$this, 'wp_head' ) );
        add_action( 'admin_init', array( &$this, 'admin_init' ) );

        //add_action( 'admin_head', array( &$this, 'admin_head' ) );
        add_action( 'admin_head-settings_page_hm_swe_option_page', array( &$this, 'admin_head' ) );
        add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );

        // TODO: >= 3.9 only (dynamic_sidebar_before is only available since 3.9)
//        add_action( 'dynamic_sidebar_before', array( &$this, 'dynamic_sidebar_before' ), 10, 2 );
//        add_action( 'dynamic_sidebar_after',  array( &$this, 'dynamic_sidebar_after'  ), 10, 2 );
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
//        wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery') );
		wp_enqueue_script( 'jquery-cookie', plugins_url( '/js/jquery.cookie.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'standard-widget-extensions',
			plugins_url( '/js/standard-widget-extensions' . ($this->get_hm_swe_option('readable_js') == 'enabled' ? '.js' : '.min.js'), __FILE__ ), array(), false, true );

//        wp_enqueue_style( 'jquery-ui.css', '//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css');

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
            'initially_collapsed'    => $options['initially_collapsed'] == 'enabled',
			'heading_string'         => esc_attr( $options['heading_string'] ),
			'proportional_sidebar'   => 0, // deprecated.
			'disable_iflt'           => $options['disable_iflt'],
			'accordion_widget_areas' => array_map( 'esc_attr', $options['accordion_widget_areas'] ),
			'scroll_mode'            => ( $options['scroll_mode'] == "2" ? 2 : 1 ),
			'ignore_footer'          => $options['ignore_footer'] == 'enabled',
			'custom_selectors'       => $custom_selectors,
			'slide_duration'         => $options['slide_duration'],
			'recalc_after'           => $options['recalc_after'],
            'recalc_count'           => $options['recalc_count'],
			'header_space'           => $options['header_space'],
			'enable_reload_me'       => 0, // deprecated
            'float_attr_check_mode'  => $options['float_attr_check_mode'] == 'enabled',

			'sidebar_id2'            => esc_attr( $options['sidebar_id2'] ),
			'proportional_sidebar2'  => 0, // deprecated.
			'disable_iflt2'          => $options['disable_iflt2'],
            'float_attr_check_mode2'  => $options['float_attr_check_mode2'] == 'enabled',

			// messages
			'msg_reload_me'          => __( 'To keep layout integrity, please reload me after resizing!', self::I18N_DOMAIN ),
			'msg_reload'             => __( 'Reload', self::I18N_DOMAIN ),
            'msg_continue'           => __( 'Continue', self::I18N_DOMAIN )

		);
		wp_localize_script( 'standard-widget-extensions', 'swe', $params );
	}

	function wp_head() {
		$options = $this->get_hm_swe_option();
        ?>

<style type="text/css">
    <?php
		if ( $options['accordion_widget'] === 'enabled' && $options['heading_marker'] !== 'none'
				&& implode( ',', $options['custom_selectors'] ) === '' ) {
			$area_array = array_map( 'esc_attr', $this->get_widget_selectors( true ) );
			$headstr      = "";
			$areastr    = "";
			$expandstr = "";
			$collapsestr = "";
			foreach ( $area_array as $i => $area ) {
				$headstr .= $area . " ." . $options['widget_class'] . " " . $options['heading_string'] . ( $i + 1 == count( $area_array ) ? "\n" : ",\n" );
				$expandstr .= $area . " ." . $options['widget_class'] . " .hm-swe-expanded " . ( $i + 1 == count( $area_array ) ? "\n" : ",\n" );
				$collapsestr .= $area . " ." . $options['widget_class'] . " .hm-swe-collapsed " . ( $i + 1 == count( $area_array ) ? "\n" : ",\n" );
				$areastr .= $area . ( $i + 1 == count( $area_array ) ? "\n" : ",\n" );
			} // for

	?>

    <?php echo $headstr; ?>
    {
        zoom: 1	; /* for IE7 to display background-image */
        padding-left: 20px;
        margin-left: -20px;
	}

    <?php echo $expandstr; ?>
    {
        background: <?php echo  $options['heading_marker'] == 'custom' ? "url(" . $options['custom_minus'] . ")" :
                "url(" . plugins_url( '/images/minus.gif', __FILE__ ) . ")"; ?> no-repeat left center;
    }

    <?php echo $collapsestr; ?>
    {
        background: <?php echo $options['heading_marker'] == 'custom' ? "url(" . $options['custom_plus'] . ")" :
                "url(" . plugins_url( '/images/plus.gif', __FILE__ ) . ")"; ?> no-repeat left center;
    }

    <?php echo $areastr; ?>
    {
        overflow: visible	;
    }

    <?php
		} // if
    ?>
    .hm-swe-resize-message {
        height: 50%;
        width: 50%;
        margin: auto;
        position: absolute;
        top: 0; left: 0; bottom: 0; right: 0;
        z-index: 99999;

        color: white;
    }

    .hm-swe-modal-background {
        position: fixed;
        top: 0; left: 0; 	bottom: 0; right: 0;
        background: none repeat scroll 0% 0% rgba(0, 0, 0, 0.85);
        z-index: 99998;
        display: none;
    }
</style>
    <?php
	} // wp_head

	function admin_init() {
		add_settings_section( 'hm_swe_main', _x( 'General', 'title', self::I18N_DOMAIN ),
			array( &$this, 'empty_text' ), 'hm_swe_option_page' );
		add_settings_section( 'hm_swe_accordion_widget', _x( 'Accordion Widgets', 'title', self::I18N_DOMAIN ),
			array( &$this, 'empty_text' ), 'hm_swe_option_page' );
		add_settings_section( 'hm_swe_scroll_stop', _x( 'Sticky Sidebar', 'title', self::I18N_DOMAIN ),
			array( &$this, 'empty_text' ), 'hm_swe_option_page' );

		foreach ( self::$settings_field as $f ) {
			$title = __( $f['title'], self::I18N_DOMAIN );
            /*
            if ( isset( $f['expert'] ) ) {
				$title = "<span class='swe-expert-params'>" . $title . "</span>";
			}
            */
			add_settings_field( self::PREFIX . $f['id'], $title, array( &$this, $f['callback'] ),
				'hm_swe_option_page', $f['section'] );
		}

		register_setting( 'hm_swe_option_group', self::OPTION_KEY, array( &$this, 'validate_options' ) );
	}

	function admin_head() {
?>

<style>

    #swe-tabs ul h3 {
        margin: 0;
    }
</style>
<script type="text/javascript">
(function($, window, document) {
    $(document).ready(function(){
        $( '#swe-tabs').tabs();

        // show/hide the accordion widget tab
        if ($('#swe-accordion_widget-disabled').filter(':checked').length) {
            $('#swe-tab-hm_swe_accordion_widget').hide();
        }
        $('#swe-accordion_widget-enabled').click(function() {
            $('#swe-tab-hm_swe_accordion_widget').show();
        })
        $('#swe-accordion_widget-disabled').click(function() {
            $('#swe-tab-hm_swe_accordion_widget').hide();
        });

        // show/hide the sticky sidebar tab
        if ($('#swe-scroll_stop-disabled').filter(':checked').length) {
            $('#swe-tab-hm_swe_scroll_stop').hide();
        }
        $('#swe-scroll_stop-enabled').click(function() {
            $('#swe-tab-hm_swe_scroll_stop').show();
        });
        $('#swe-scroll_stop-disabled').click(function() {
            $('#swe-tab-hm_swe_scroll_stop').hide();
        });

        // show/hide the heading marker option
        /*
        if ($('#swe-enable_css-disabled').filter(':checked').length) {
            $('#swe-heading_marker-none').closest('tr').hide();
        }
        $('#swe-enable_css-enabled').click(function() {
            $('#swe-heading_marker-none').closest('tr').show();
        })
        $('#swe-enable_css-disabled').click(function() {
            $('#swe-heading_marker-none').closest('tr').hide();
        })
        */

    }); // ready
})(jQuery, window, document);
</script>
<?php
	}

    function admin_enqueue_scripts( $hook ) {
        if ( 'settings_page_hm_swe_option_page' == $hook ) { // != 'options-general.php'
            wp_enqueue_script( 'jquery-ui-tabs', false, array( 'jquery' ) );
//            wp_enqueue_style( 'jquery-ui.css', '//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css');
            wp_enqueue_style( 'jquery-ui.css', plugins_url( '/css/jquery-ui.css', __FILE__ ));

        }
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
			echo "<label title='" . $o['id'] . "'><input id='swe-" . $id . '-' . $o['value'] . "' type='radio' name='" . self::OPTION_KEY . "[$id]' value='" . $o['value'] . "' " .
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

	function settings_field_single_expansion() {
		$this->settings_field_simple_radio_option( self::I_SINGLE_EXPANSION );
	}

    function settings_field_initially_collapsed() {
        $this->settings_field_simple_radio_option( self::I_INITIALLY_COLLAPSED );
    }

	function settings_field_heading_string() {
		$this->write_text_option( self::I_HEADING_STRING );
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

    function settings_field_recalc_count() {
        $this->write_text_option( self::I_RECALC_COUNT );
    }

	function settings_field_header_space() {
		$this->write_text_option( self::I_HEADER_SPACE );
	}

	function settings_field_sidebar_id2() {
		$this->write_text_option( self::I_SIDEBAR_ID2 );
	}

    function settings_field_float_attr_check_mode() {
        $this->settings_field_simple_radio_option( self::I_FLOAT_ATTR_CHECK__MODE );
    }

	function settings_field_disable_iflt2() {
		$this->write_text_option( self::I_DISABLE_IFLT2 );
	}

    function settings_field_float_attr_check_mode2() {
        $this->settings_field_simple_radio_option( self::I_FLOAT_ATTR_CHECK__MODE2 );
    }

	function validate_options( $input ) {
		$valid = array();
		$prev  = $this->get_hm_swe_option();

		$valid['heading_marker']   = $input['heading_marker'];
		$valid['scroll_stop']      = $input['scroll_stop'];
		$valid['scroll_mode']      = $input['scroll_mode'];
		$valid['accordion_widget'] = $input['accordion_widget'];
		$valid['single_expansion'] = $input['single_expansion'];
        $valid['initially_collapsed'] = $input['initially_collapsed'];
		$valid['readable_js']      = $input['readable_js'];
		$valid['ignore_footer']    = $input['ignore_footer'];
		$valid['expert_options']   = $input['expert_options'];
        $valid['float_attr_check_mode']  = $input['float_attr_check_mode'];
        $valid['float_attr_check_mode2'] = $input['float_attr_check_mode2'];

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

        if ( filter_var( $input['recalc_count'], FILTER_VALIDATE_INT ) === FALSE ) {
            add_settings_error( 'hm_swe_recalc_count', 'hm_swe_recalc_count_error', __( 'The Recalc Count has to be a number.', self::I18N_DOMAIN ) );
            $valid['recalc_count'] = $prev['recalc_count'];
        }
        else {
            $valid['recalc_count'] = $input['recalc_count'];
        }

		if ( filter_var( $input['header_space'], FILTER_VALIDATE_INT ) === FALSE ) {
			add_settings_error( 'hm_swe_header_space', 'hm_swe_header_space', __( 'The Header Space has to be a number.', self::I18N_DOMAIN ) );
			$valid['header_space'] = $prev['header_space'];
		}
		else {
			$valid['header_space'] = $input['header_space'];
		}

		if ( $input['sidebar_id2'] !== '' && ! preg_match( '/^[a-zA-Z0-9_\-]+$/', $input['sidebar_id2'] ) ) {
			add_settings_error( 'hm_swe_sidebar_id2', 'hm_swe_sidebar_id2_error', __( 'Wrong 2nd sidebar ID', self::I18N_DOMAIN ) );
			$valid['sidebar_id2'] = $prev['sidebar_id2'];
		}
		else {
			$valid['sidebar_id2'] = $input['sidebar_id2'];
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

    // Substitution function for do_settings_sections() in template.php
    function do_my_settings_sections( $page ) {
        global $wp_settings_sections, $wp_settings_fields;

        if ( ! isset( $wp_settings_sections[$page] ) )
            return;

        echo "\n<ul>\n";
        foreach ( (array) $wp_settings_sections[$page] as $section ) {
            echo "<li id='swe-tab-{$section['id']}'><a href='#{$section['id']}'><h3>{$section['title']}</h3></a></li>\n";
        }
        echo "</ul>\n";

        foreach ( (array) $wp_settings_sections[$page] as $section ) {
            echo "<div id='{$section['id']}'>\n";
            if ( $section['callback'] )
                call_user_func( $section['callback'], $section );

            if ( ! isset( $wp_settings_fields ) || !isset( $wp_settings_fields[$page] ) || !isset( $wp_settings_fields[$page][$section['id']] ) )
                continue;
            echo '<table class="form-table">';
            do_settings_fields( $page, $section['id'] );
            echo '</table>';
            echo "</div>\n";
        }
    }

	function admin_page() {
		?>
		<div class="wrap">
			<h2><?php echo __( "Standard Widget Extensions", self::I18N_DOMAIN ); ?></h2>
            <?php echo __( '<p>Check <a href="http://en.hetarena.com/standard-widget-extensions" target="_blank">the plugin home page</a> for help.</p>', self::I18N_DOMAIN ); ?>

			<form action="options.php" method="post">
                <div id="swe-tabs">
				<?php
                settings_fields( 'hm_swe_option_group' );
                $this->do_my_settings_sections( 'hm_swe_option_page' );
                submit_button();
                ?>
                </div>
			</form>
		</div>
	<?php
	}


    // experimental function for Tabs
    // not used in production release
    function dynamic_sidebar_before($index, $has_widgets) {
        global $wp_registered_widgets;
        if (! $has_widgets) {
            return;
        }

        // temporarily
        if ( $index != "sidebar-3" ) {
            return;
        }

        ?>
        <div id='tabs-<?php echo $index;  ?>'><ul>
        <?php
        $sidebars_widgets = wp_get_sidebars_widgets();
        foreach ( (array) $sidebars_widgets[$index] as $id ) {
            if ( !isset($wp_registered_widgets[$id]) ) continue;

?>
            <li><a href="#<?php echo $id; ?>"><?php echo $wp_registered_widgets[$id][name]; ?></a></li>
<?php
        }
        echo "</ul>\n";
    }

    // experimental function for Tabs
    // not used in production release
    function dynamic_sidebar_after($index, $has_widgets) {
        if (! $has_widgets) {
            return;
        }

        // temporarily
        if ( $index != "sidebar-3" ) {
            return;
        }
        echo "</div>\n";
    }

} // end of class HM_SWE_Plugin_Loader

$hm_swe_plugin_loader = new HM_SWE_Plugin_Loader();

