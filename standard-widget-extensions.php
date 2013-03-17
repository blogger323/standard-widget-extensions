<?php
  /*
Plugin Name: Standard Widget Extensions
Plugin URI: http://en.hetarena.com/standard-widget-extensions
Description: A plugin to extend widget behavior.
Version: 1.0
Author: Hirokazu Matsui (blogger323)
Author URI: http://en.hetarena.com/
License: GPLv2
  */

class HM_SWE_Plugin_Loader {

  const VERSION        = '1.0';
  const OPTION_VERSION = '1.0';
  const OPTION_KEY     = 'hm_swe_options';
  const I18N_DOMAIN    = 'standard-widget-extensions';
  const PREFIX         = 'hm_swe_';

  public static $default_hm_swe_option = array(
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
    'accordion_widget_areas' => array(''),
  );

  // index for field array
  const I_MAINCOL_ID       = 0;
  const I_SIDEBAR_ID       = 1;
  const I_WIDGET_CLASS     = 2;
  const I_ACCORDION_WIDGET = 3;
  const I_ACCORDION_WIDGET_AREAS = 4;
  const I_HEADING_MARKER   = 5;
  const I_ENABLE_CSS       = 6;
  const I_SCROLL_STOP      = 7;
  const I_DISABLE_IFLT     = 8;

  // field array
  private static $settings_field = 
    array( 
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
	    'id'       => 'accordion_widget',
	    'title'    => 'Accordion Widgets',
	    'callback' => 'settings_field_accordion_widget', 
	    'section'  => 'hm_swe_accordion_widget',
	    'options'  => array(
		  array('id' => 'enable',  'title' => 'Enable',  'value' => 'enabled'), 
		  array('id' => 'disable', 'title' => 'Disable', 'value' => 'disabled'),
				),
	    ),
      array(
	    'id'       => 'accordion_widget_areas',
	    'title'    => 'Widget area IDs in which AW is effective (optional)',
	    'callback' => 'settings_field_accordion_widget_areas',
	    'section'  => 'hm_swe_accordion_widget',
	    ),
      array(
	    'id'       => 'heading_marker',
	    'title'    => 'Icon for Heading',
	    'callback' => 'settings_field_heading_marker',
	    'section'  => 'hm_swe_accordion_widget',
	    'options'  => array(
   		  array('id' => 'none',   'title' => 'None',    'value' => 'none'), 
		  array('id' => 'default','title' => 'Default', 'value' => 'default'),
		  array('id' => 'custom', 'title' => 'Custom',  'value' => 'custom')
				),
	    ),
      array(
	    'id'       => 'enable_css', 
	    'title'    => 'Include Default CSS',
	    'callback' => 'settings_field_enable_css',
	    'section'  => 'hm_swe_accordion_widget',
	    'options'  => array(
		  array('id' => 'enable',  'title' => 'Yes (Recommended)', 'value' => 'enabled'), 
		  array('id' => 'disable', 'title' => 'No',                'value' => 'disabled'),
				),
	    ),
      array(
	    'id'       => 'scroll_stop', 
	    'title'    => 'Sticky Sidebar',
	    'callback' => 'settings_field_scroll_stop',
	    'section'  => 'hm_swe_scroll_stop',
	    'options'  => array(
		  array('id' => 'enable',  'title' => 'Enable',  'value' => 'enabled'), 
		  array('id' => 'disable', 'title' => 'Disable', 'value' => 'disabled'),
				),
	    ),
      array(
	    'id'       => 'disable_iflt',
	    'title'    => 'Disable if the window width is less than',
	    'callback' => 'settings_field_disable_iflt',
	    'section'  => 'hm_swe_scroll_stop',
	    ),
  );

  function __construct() {
    register_activation_hook( __FILE__, array( &$this, 'activate' ) );
    add_action( 'plugins_loaded',       array( &$this, 'plugins_loaded' ) );
    add_action( 'wp_enqueue_scripts',   array( &$this, 'enqueue_scripts' ), 20);
    add_action( 'wp_head',              array( &$this, 'wp_head' ) );
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
      'accordion_widget'       => $options['accordion_widget'] == 'enabled',
      'disable_iflt'           => intval($options['disable_iflt']),
      'accordion_widget_areas' => array_map('esc_attr', $options['accordion_widget_areas']),
    );
    wp_localize_script('standard-widget-extensions', 'swe_params', $params);
  }

  function wp_head() {
    $options = $this->get_hm_swe_option();
    if ($options['heading_marker'] !== 'none' && $options['enable_css'] === 'enabled') {
      $area_array = array_map('esc_attr', $options['accordion_widget_areas']);
      $h3str      = "";
      $areastr = "";
      foreach ($area_array as $i => $area) {
        $h3str .= "#" . $options['sidebar_id'] . ($area ? (" #" . $area) : "") . 
          " ." . $options['widget_class'] . " h3" . ($i+1 == count($area_array) ? "\n" : ",\n");
        $areastr .= "#" . $options['sidebar_id'] . ($area ? (" #" . $area) : "") . 
          ($i+1 == count($area_array) ? "\n" : ",\n");
      } // for 

?>
<style type="text/css">
<?php echo $h3str; ?>
{
  zoom: 1; /* for IE7 to display background-image */
  padding-left: 20px;
  margin-left: -20px;
}

<?php echo $areastr; ?>
{
  overflow: visible;
}
</style>
<?php
    }  // if
  } // wp_head

  function admin_init() {
    add_settings_section( 'hm_swe_main', _x('General', 'title', self::I18N_DOMAIN), 
      array( &$this, 'main_section_text'), 'hm_swe_option_page' );
    add_settings_section( 'hm_swe_accordion_widget', _x('Accordion Widgets', 'title', self::I18N_DOMAIN), 
      array( &$this, 'empty_text' ), 'hm_swe_option_page' );
    add_settings_section( 'hm_swe_scroll_stop', _x('Sticky Sidebar', 'title', self::I18N_DOMAIN), 
      array( &$this, 'empty_text' ), 'hm_swe_option_page' );

    foreach (self::$settings_field as $f) {
      add_settings_field( self::PREFIX . $f['id'], __($f['title'], self::I18N_DOMAIN), array( &$this, $f['callback'] ), 
        'hm_swe_option_page', $f['section'] );
    }

    register_setting( 'hm_swe_option_group', self::OPTION_KEY, array( &$this, 'validate_options' ) );
  }

  function main_section_text() {
    echo __("<p>Use primary/secondary/widget for Twenty Twelve and Twenty Eleven.\nUse container/primary/widget-container for Twenty Ten.</p>", self::I18N_DOMAIN);
  }

  function empty_text() {
  }

  function get_hm_swe_option($key = NULL) {
    // The get_option doesn't seem to merge retrieved values and default values.
    $options = array_merge( self::$default_hm_swe_option, (array)get_option( self::OPTION_KEY, array() ) );
    return $key ? $options[$key] : $options;
  }

  function write_text_option($index, $v = NULL) {
    $id = self::$settings_field[$index]['id'];
    if ($v === NULL) {
      $v  = $this->get_hm_swe_option($id);
    }
    echo "<input class='regular-text' id='$id' name='" . self::OPTION_KEY . "[$id]' type='text' value='" . esc_attr($v) . "' />";
  }

  function write_radio_option($index) {
    $id = self::$settings_field[$index]['id'];
    $v  = $this->get_hm_swe_option($id);
    foreach (self::$settings_field[$index]['options'] as $o) {
      echo "<label title='" . $o['id'] . "'><input type='radio' name='" . self::OPTION_KEY . "[$id]' value='" . $o['value'] . "' " . 
        ($v == $o['value'] ? "checked='checked'" : "") . "/><span>" . __( $o['title'], self::I18N_DOMAIN ) . "</span></label><br />\n";
      
    }
  }

  function settings_field_sidebar_id() {
    $this->write_text_option(self::I_SIDEBAR_ID);
  }

  function settings_field_maincol_id() {
    $this->write_text_option(self::I_MAINCOL_ID);
  }

  function settings_field_widget_class() {
    $this->write_text_option(self::I_WIDGET_CLASS);
  }

  function settings_field_accordion_widget_areas() {
    $this->write_text_option(self::I_ACCORDION_WIDGET_AREAS, 
      implode(",", (array)$this->get_hm_swe_option(self::$settings_field[self::I_ACCORDION_WIDGET_AREAS]['id'])));
  }

  function settings_field_heading_marker() {
    $i = self::I_HEADING_MARKER;
    echo "<fieldset><legend class='screen-reader-text'><span>" . self::$settings_field[$i]['title'] . "</span></legend>\n";

    $this->write_radio_option($i);

    echo "<table><tr><td>" . __("Plus button URL", self::I18N_DOMAIN) . "</td><td><input class='regular-text' id='custom_plus' name='" . 
      self::OPTION_KEY . "[custom_plus]' type='text' value='" .
      esc_attr($this->get_hm_swe_option('custom_plus')) . "' /></td></tr>\n";
    echo "<tr><td>" . __("Minus buttom URL", self::I18N_DOMAIN) . "</td><td><input class='regular-text' id='custom_minus' name='" . 
      self::OPTION_KEY . "[custom_minus]' type='text' value='" . 
      esc_attr($this->get_hm_swe_option('custom_minus')) . "' /></td></tr></table>";

    echo "</fieldset>\n";
  }

  function settings_field_scroll_stop() {
    $i = self::I_SCROLL_STOP;
    echo "<fieldset><legend class='screen-reader-text'><span>" . self::$settings_field[$i]['title'] . "</span></legend>\n";
    $this->write_radio_option($i);
    echo "</fieldset>\n";
  }

  function settings_field_accordion_widget() {
    $i = self::I_ACCORDION_WIDGET;
    echo "<fieldset><legend class='screen-reader-text'><span>" . self::$settings_field[$i]['title'] . "</span></legend>\n";
    $this->write_radio_option($i);
    echo "</fieldset>\n";
  }

  function settings_field_enable_css() {
    $i = self::I_ENABLE_CSS;
    echo "<fieldset><legend class='screen-reader-text'><span>" . self::$settings_field[$i]['title'] . "</span></legend>\n";
    $this->write_radio_option($i);
    echo "</fieldset>\n";
  }

  function settings_field_disable_iflt() {
    $this->write_text_option(self::I_DISABLE_IFLT);
  }

  function validate_options( $input ) {
    $valid = array();
    $prev  =$this->get_hm_swe_option();

    $valid['heading_marker'] = $input['heading_marker'];
    $valid['scroll_stop']    = $input['scroll_stop'];
    $valid['accordion_widget'] = $input['accordion_widget'];
    $valid['enable_css']     = $input['enable_css'];

    if (!filter_var($input['disable_iflt'], FILTER_VALIDATE_INT)) {
      add_settings_error('hm_swe_disable_iflt', 'hm_swe_disable_iflt_error', __('The minimum width has to be a number.', self::I18N_DOMAIN));
      $valid['disable_iflt'] = $prev['disable_iflt'];
    }
    else {
      $valid['disable_iflt'] =  $input['disable_iflt'];
    }

    if ($input['heading_marker'] == 'custom' && 
      !(filter_var($input['custom_plus'], FILTER_VALIDATE_URL) && preg_match('/http/i', $input['custom_plus']))) {
      add_settings_error('hm_swe_custom_plus', 'hm_swe_custom_plus_error', __('Wrong URL for the plus button', self::I18N_DOMAIN));
      $valid['custom_plus'] = $prev['custom_plus'];
      $valid['heading_marker'] = $prev['heading_marker'];
    }
    else {
      $valid['custom_plus']  = $input['custom_plus'];
    }

    if ($input['heading_marker'] == 'custom' && 
	!(filter_var($input['custom_minus'], FILTER_VALIDATE_URL) && preg_match('/http/i', $input['custom_minus']))) {
      add_settings_error('hm_swe_custom_minus', 'hm_swe_custom_minus_error', __('Wrong URL for the minus button', self::I18N_DOMAIN));
      $valid['custom_minus'] = $prev['custom_minus'];
      $valid['heading_marker'] = $prev['heading_marker'];
    }
    else {
      $valid['custom_minus'] = $input['custom_minus'];
    }

    if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $input['maincol_id'])) {
      add_settings_error('hm_swe_maincol_id', 'hm_swe_maincol_id_error', __('Wrong main column ID', self::I18N_DOMAIN));
      $valid['maincol_id'] = $prev['maincol_id'];
    }
    else {
      $valid['maincol_id'] = $input['maincol_id'];
    }

    if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $input['sidebar_id'])) {
      add_settings_error('hm_swe_sidebar_id', 'hm_swe_sidebar_id_error', __('Wrong sidebar ID', self::I18N_DOMAIN));
      $valid['sidebar_id'] = $prev['sidebar_id'];
    }
    else {
      $valid['sidebar_id'] = $input['sidebar_id'];
    }

    if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $input['widget_class'])) {
      add_settings_error('hm_swe_widget_class', 'hm_swe_widget_class_error', __('Wrong widget class', self::I18N_DOMAIN));
      $valid['widget_class'] = $prev['widget_class'];
    }
    else {
      $valid['widget_class'] = $input['widget_class'];
    }

    if (!preg_match('/^[a-zA-Z0-9_\-, ]*$/', $input['accordion_widget_areas'])) {
      add_settings_error('hm_swe_accordion_widget_areas', 'hm_swe_accordion_widget_areas_error', __('Wrong widget areas', self::I18N_DOMAIN));
      $valid['accordion_widget_areas'] = $prev['accordion_widget_areas'];
    }
    else {
      $valid['accordion_widget_areas'] = explode(",", str_replace(" ", "", $input['accordion_widget_areas']));
    }

    $valid['option_version'] = self::OPTION_VERSION;
    return $valid;
  }

  function admin_menu() {
    add_options_page(__('Standard Widget Extensions', self::I18N_DOMAIN), __('Standard WE', self::I18N_DOMAIN), 
        'manage_options', 'hm_swe_option_page', array( &$this, 'admin_page') );
  }

  function admin_page() {
?>
  <div class="wrap">
  <?php screen_icon(); ?>
  <h2><?php echo __("Standard Widget Extensions", self::I18N_DOMAIN); ?></h2>
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

