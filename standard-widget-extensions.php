<?php
  /*
Plugin Name: Standard Widget Extensions
Plugin URI: http://en.hetarena.com/standard-widget-extensions
Description: A plugin to extend widget behavior.
Version: 0.8.3
Author: Hirokazu Matsui
Author URI: http://en.hetarena.com/
License: GPLv2
  */

define( 'HM_SWE_VERSION', '0.8.3' );


class HM_SWE_Plugin_Loader {
  function __construct() {
    register_activation_hook( __FILE__, array( &$this, 'activate' ) );
    add_action( 'plugins_loaded',       array( &$this, 'plugins_loaded' ) );
    add_action( 'wp_enqueue_scripts',   array( &$this, 'enqueue_scripts' ), 20);
    add_action( 'admin_init',           array( &$this, 'admin_init' ) );
    add_action( 'admin_menu',           array( &$this, 'admin_menu' ) );
  }

  function plugins_loaded() {
    load_plugin_textdomain( 'standard-widget-extensions', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
  }

  function activate() {
    if ( version_compare( get_bloginfo( 'version' ), '3.2', '<' ) ) {
      deactivate_plugins( basename( __FILE__ ) ); // Deactivate this plugin
    }
  }

  function enqueue_scripts() {
    $options = $this->get_hm_swe_option();
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'jquery-cookie', plugins_url('/js/jquery.cookie.js', __FILE__), array('jquery') );
    wp_enqueue_script( 'standard-widget-extensions', plugins_url('/js/standard-widget-extensions.js', __FILE__ ) );
    if ($options['heading_marker'] !== 'none' && $options['enable_css'] === 'enabled') {
      wp_enqueue_style( 'standard-widget-extensions', 
        plugins_url('/css/standard-widget-extensions.css', __FILE__) );
    }

    $params = array(
      'buttonplusurl'   => $options['heading_marker'] == 'custom' ? "url(" . $options['custom_plus'] . ")" : 
        "url(" . plugins_url('/images/plus.gif',  __FILE__) . ")",
      'buttonminusurl'  => $options['heading_marker'] == 'custom' ? "url(" . $options['custom_minus'] . ")" :
        "url(" . plugins_url('/images/minus.gif', __FILE__) . ")",
      'maincol_id'      => esc_attr($options['maincol_id']),
      'sidebar_id'      => esc_attr($options['sidebar_id']),
      'widget_class'    => esc_attr($options['widget_class']),
      'heading_marker'  => $options['heading_marker'] != 'none',
      'scroll_stop'     => $options['scroll_stop'] == 'enabled',
      'accordion_widget' => $options['accordion_widget'] == 'enabled',
      'disable_iflt'    => intval($options['disable_iflt']),
    );
    wp_localize_script('standard-widget-extensions', 'swe_params', $params);
  }

  function admin_init() {
    add_settings_section( 'hm_swe_main', 'General', array( &$this, 'main_section_text'), 'hm_swe_option_page' );
    add_settings_section( 'hm_swe_accordion_widget', 'Accordion Widgets', array( &$this, 'empty_text' ), 'hm_swe_option_page' );
    add_settings_section( 'hm_swe_scroll_stop', 'Sticky Sidebar', array( &$this, 'empty_text' ), 'hm_swe_option_page' );

    add_settings_field( 'hm_swe_content_id', 'ID of Your Main Column', 
      array( &$this, 'settings_field_maincol_id' ), 'hm_swe_option_page', 'hm_swe_main' );
    add_settings_field( 'hm_swe_sidebar_id', 'ID of Your Sidebar', 
      array( &$this, 'settings_field_sidebar_id' ), 'hm_swe_option_page', 'hm_swe_main' );
    add_settings_field( 'hm_swe_widget_class', 'Class of Widgets',
      array( &$this, 'settings_field_widget_class' ), 'hm_swe_option_page', 'hm_swe_main' );

    add_settings_field( 'hm_swe_accordion_widget', 'Accordion Widgets',
      array( &$this, 'settings_field_accordion_widget' ), 'hm_swe_option_page', 'hm_swe_accordion_widget' );
    add_settings_field( 'hm_swe_heading_marker', 'Icon for Heading', 
      array( &$this, 'settings_field_heading_marker' ), 'hm_swe_option_page', 'hm_swe_accordion_widget' );
    add_settings_field( 'hm_swe_enable_css', 'Include Default CSS',
      array( &$this, 'settings_field_enable_css' ), 'hm_swe_option_page', 'hm_swe_accordion_widget' );

    add_settings_field( 'hm_swe_scroll_stop', 'Sticky Sidebar',
      array( &$this, 'settings_field_scroll_stop' ), 'hm_swe_option_page', 'hm_swe_scroll_stop' );
    add_settings_field( 'hm_swe_disable_iflt', 'Disable if the window width is less than',
      array( &$this, 'settings_field_disable_iflt' ), 'hm_swe_option_page', 'hm_swe_scroll_stop' );
    register_setting( 'hm_swe_option_group', 'hm_swe_options', array( &$this, 'validate_options' ) );
  }

  function main_section_text() {
    echo "<p>Use primary/secondary/widget for Twenty Twelve and Twenty Eleven.\n" .
         "Use container/primary/widget-container for Twenty Ten.</p>";
  }

  function empty_text() {
  }

  function get_hm_swe_option($key = NULL) {
    $default_hm_swe_option = array(
      'maincol_id'   => 'primary',
      'sidebar_id'   => 'secondary',
      'widget_class' => 'widget',
      'accordion_widget' => 'enabled',
      'enable_css'   => 'enabled',
      'heading_marker' => 'default',
      'custom_plus'  => '',
      'custom_minus' => '',
      'scroll_stop'  => 'enabled',
      'disable_iflt' => 620,
    );

    // The get_option doesn't seem to merge retrieved values and default values.
    $options = get_option( 'hm_swe_options', $default_hm_swe_option );
    return $key ? $options[$key] : $options;
  }

  function settings_field_sidebar_id() {
    $sidebar_id = $this->get_hm_swe_option('sidebar_id');
    echo "<input class='regular-text' id='sidebar_id' name='hm_swe_options[sidebar_id]' type='text' value='$sidebar_id' />";
  }

  function settings_field_maincol_id() {
    $maincol_id = $this->get_hm_swe_option('maincol_id');
    echo "<input class='regular-text' id='maincol_id' name='hm_swe_options[maincol_id]' type='text' value='$maincol_id' />";
  }

  function settings_field_widget_class() {
    $widget_class = $this->get_hm_swe_option('widget_class');
    echo "<input class='regular-text' id='widget_class' name='hm_swe_options[widget_class]' type='text' value='$widget_class' />";
  }

  function settings_field_heading_marker() {
    $heading_marker = $this->get_hm_swe_option('heading_marker');
    echo "<fieldset><legend class='screen-reader-text'><span>Icon for Heading</span></legend>\n";
    echo "<label title='none'><input type='radio' name='hm_swe_options[heading_marker]' value='none' " . 
      ($heading_marker == "none" ? "checked='checked'" : "") .
      "/><span>None</span></label><br />\n";
    echo "<label title='default'><input type='radio' name='hm_swe_options[heading_marker]' value='default' " .
      ($heading_marker == "default" ? "checked='checked'" : "") .
      "/><span>Default</span></label><br />\n";
    echo "<label title='custom'><input type='radio' name='hm_swe_options[heading_marker]' value='custom' " .
      ($heading_marker == "custom" ? "checked='checked'" : "") .
      "/><span>Custom</span></label><br />\n";
    echo "<table><tr><td>Plus button URL</td><td><input class='regular-text' id='custom_plus' name='hm_swe_options[custom_plus]' type='text' value='" .
      $this->get_hm_swe_option('custom_plus') . "' /></td></tr>\n";
    echo "<tr><td>Minus buttom URL</td><td><input class='regular-text' id='custom_minus' name='hm_swe_options[custom_minus]' type='text' value='" . 
      $this->get_hm_swe_option('custom_minus') . "' /></td></tr></table>";
    echo "</fieldset>\n";
  }

  function settings_field_scroll_stop() {
    $scroll_stop = $this->get_hm_swe_option('scroll_stop');
    echo "<fieldset><legend class='screen-reader-text'><span>Sidebar Scroll Stop</span></legend>\n";
    echo "<label title='enable'><input type='radio' name='hm_swe_options[scroll_stop]' value='enabled' " . 
      ($scroll_stop == "enabled" ? "checked='checked'" : "") .
      "/><span>Enable</span></label><br />\n";
    echo "<label title='disable'><input type='radio' name='hm_swe_options[scroll_stop]' value='disabled' " .
      ($scroll_stop == "disabled" ? "checked='checked'" : "") .
      "/><span>Disable</span></label><br />\n";
    echo "</fieldset>\n";
  }

  function settings_field_accordion_widget() {
    $scroll_stop = $this->get_hm_swe_option('accordion_widget');
    echo "<fieldset><legend class='screen-reader-text'><span>Accordion_widget</span></legend>\n";
    echo "<label title='enable'><input type='radio' name='hm_swe_options[accordion_widget]' value='enabled' " . 
      ($scroll_stop == "enabled" ? "checked='checked'" : "") .
      "/><span>Enable</span></label><br />\n";
    echo "<label title='disable'><input type='radio' name='hm_swe_options[accordion_widget]' value='disabled' " .
      ($scroll_stop == "disabled" ? "checked='checked'" : "") .
      "/><span>Disable</span></label><br />\n";
    echo "</fieldset>\n";
  }

  function settings_field_enable_css() {
    $enable_css = $this->get_hm_swe_option('enable_css');
    echo "<fieldset><legend class='screen-reader-text'><span>Include Default CSS</span></legend>\n";
    echo "<label title='enable'><input type='radio' name='hm_swe_options[enable_css]' value='enabled' " . 
      ($enable_css == "enabled" ? "checked='checked'" : "") .
      "/><span>Yes (Recommended)</span></label><br />\n";
    echo "<label title='disable'><input type='radio' name='hm_swe_options[enable_css]' value='disabled' " .
      ($enable_css == "disabled" ? "checked='checked'" : "") .
      "/><span>No</span></label><br />\n";
    echo "</fieldset>\n";
  }

  function settings_field_disable_iflt() {
    $disable_iflt = $this->get_hm_swe_option('disable_iflt');
    echo "<input class='regular-text' id='disable_iflt' name='hm_swe_options[disable_iflt]' type='text' value='$disable_iflt' />";
  }

  function validate_options( $input ) {
    $valid = array();
    $prev  =$this->get_hm_swe_option();

    $valid['heading_marker'] = $input['heading_marker'];
    $valid['scroll_stop']    = $input['scroll_stop'];
    $valid['accordion_widget'] = $input['accordion_widget'];
    $valid['enable_css']     = $input['enable_css'];

    if (!filter_var($input['disable_iflt'], FILTER_VALIDATE_INT)) {
      add_settings_error('hm_swe_disable_iflt', 'hm_swe_disable_iflt_error', 'The minimum width has to be a number.');
      $valid['disable_iflt'] = $prev['disable_iflt'];
    }
    else {
      $valid['disable_iflt'] =  $input['disable_iflt'];
    }

    if ($input['heading_marker'] == 'custom' && 
      !(filter_var($input['custom_plus'], FILTER_VALIDATE_URL) && preg_match('/http/i', $input['custom_plus']))) {
      add_settings_error('hm_swe_custom_plus', 'hm_swe_custom_plus_error', 'Wrong URL for the plus button');
      $valid['custom_plus'] = $prev['custom_plus'];
      $valid['heading_marker'] = $prev['heading_marker'];
    }
    else {
      $valid['custom_plus']  = $input['custom_plus'];
    }

    if ($input['heading_marker'] == 'custom' && 
	!(filter_var($input['custom_minus'], FILTER_VALIDATE_URL) && preg_match('/http/i', $input['custom_minus']))) {
      add_settings_error('hm_swe_custom_minus', 'hm_swe_custom_minus_error', 'Wrong URL for the minus button');
      $valid['custom_minus'] = $prev['custom_minus'];
      $valid['heading_marker'] = $prev['heading_marker'];
    }
    else {
      $valid['custom_minus'] = $input['custom_minus'];
    }

    if (preg_match('/[^a-zA-Z0-9_-]/', $input['maincol_id'])) {
      add_settings_error('hm_swe_maincol_id', 'hm_swe_maincol_id_error', 'Wrong main column ID');
      $valid['maincol_id'] = $prev['maincol_id'];
    }
    else {
      $valid['maincol_id'] = $input['maincol_id'];
    }

    if (preg_match('/[^a-zA-Z0-9_-]/', $input['sidebar_id'])) {
      add_settings_error('hm_swe_sidebar_id', 'hm_swe_sidebar_id_error', 'Wrong sidebar ID');
      $valid['sidebar_id'] = $prev['sidebar_id'];
    }
    else {
      $valid['sidebar_id'] = $input['sidebar_id'];
    }

    if (preg_match('/[^a-zA-Z0-9_-]/', $input['widget_class'])) {
      add_settings_error('hm_swe_widget_class', 'hm_swe_widget_class_error', 'Wrong widget class');
      $valid['widget_class'] = $prev['widget_class'];
    }
    else {
      $valid['widget_class'] = $input['widget_class'];
    }

    return $valid;
  }

  function admin_menu() {
    add_options_page('Standard Widget Extensions', 'Standard WE', 'manage_options', 
	'hm_swe_option_page', array( &$this, 'admin_page') );
  }

  function admin_page() {
?>
  <div class="wrap">
  <?php screen_icon(); ?>
  <h2>Standard Widget Extensions</h2>
  <form action="options.php" method="post">
  <?php settings_fields('hm_swe_option_group'); ?>
  <?php do_settings_sections('hm_swe_option_page'); ?>
  <p class="submit"><input class="button-primary" name="Submit" type="submit" value="Save Changes" /></p>
  </form>
  </div>
<?php
  }
  
} // end of class HM_SWE_Plugin_Loader

$hm_swe_plugin_loader = new HM_SWE_Plugin_Loader();

