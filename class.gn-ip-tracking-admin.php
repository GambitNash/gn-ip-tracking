<?php
/**
 * @package GN_IP_Tracking
 * @version 1.0
 */

// Make sure we're in wordpress (and not being called directly)
if ( !defined( 'ABSPATH' ) || !defined('GN_IP_TRACKING_VERSION') ) {
    exit();
}

/**
 * GN_IP_Tracking_Admin class
 *
 * Admin code for the IP Tracking integration - Deals with admin options pages
 * and menus.
 */
class GN_IP_Tracking_Admin {
  private $ipt_core = null;

  /**
   * GN IP Tracking Admin
   * If the class is loaded within the WP Admin, settings and menus will be
   * registered via the `admin_menu` and `admin_init` actions.
   */
  public function __construct($ipt_core) {
    // If the class has been loaded in admin (normal), register the menu pages
    if ( is_admin() ){
      $this->ipt_core = $ipt_core;
      add_action( 'admin_menu', array( $this, 'admin_add_menu_page' ) );
      add_action( 'admin_init', array( $this, 'register_admin_settings' ) );
    }
  }

  public function admin_add_menu_page() {
    // Register the main options page in the menu
    add_options_page(
      esc_html__( 'Gambit Nash IP Tracking', 'gn-ip-tracking' ),
      esc_html__( 'GN IP Tracking', 'gn-ip-tracking' ),
      'manage_options',
      'gn-ip-tracking',
      array( $this, 'build_admin_options_page' )
    );
  }

  public function register_admin_settings() {
    // Register the settings (with a validation callback)
    register_setting( 'gn-ip-tracking-options', 'gn-ip-tracking-options', array( $this, 'validate_admin_options' ) );

    // Register the settings section (main options)
    add_settings_section( 'gn-ip-tracking-options-main', esc_html__( 'IP Tracking Settngs', 'gn-ip-tracking' ), array( $this, 'admin_options_text'), 'gn-ip-tracking' );

    // Register the settings fields
    add_settings_field( 'gn_ipt_active', esc_html__( 'Activate IP Tracking', 'gn-ip-tracking' ),  array( $this, 'admin_options_field_active'), 'gn-ip-tracking', 'gn-ip-tracking-options-main' );
    add_settings_field( 'gn_ipt_account_id', esc_html__( 'Account ID', 'gn-ip-tracking' ),  array( $this, 'admin_options_field_account_id'), 'gn-ip-tracking', 'gn-ip-tracking-options-main' );
  }

  /**
   * Runs before outputting the contents of an admin page
   * Creates the "wrap" div and page title (with I18n support)
   * Opens the <form> tag for saving options
   */
  public function pre_admin_page() {
    echo '<div class="wrap">';
    printf( '<h1>%s</h1>', esc_html__( 'Gambit Nash IP Tracking', 'gn-ip-tracking' ) );
  }

  /**
   * Runs after outputting the contents of an admin page
   * Closes the "wrap" div, after outputting a "support" footer (with I18n support).
   */
  public function post_admin_page() {
    echo '<hr />';

    $gn_phone = esc_html__( '+44 (0) 156 273 1313', 'gn-ip-tracking' );
    $gn_web = '<a href="https://gambitnash.co.uk/contact/" title="' . esc_html__( 'Contact Gambit Nash', 'gn-ip-tracking' ) . '">' . esc_html__( 'our website', 'gn-ip-tracking' ) . '</a>';
    $gn_footer = esc_html__( 'For IP Tracking Support, Please contact Gambit Nash directly on %s or online via %s', 'gn-ip-tracking' );
    printf( '<p>' . $gn_footer . '</p>', $gn_phone, $gn_web);

    echo '</div>';
  }

  /**
   * Runs before outputting options on admin pages (after `pre_admin_page()`)
   * Creates the "form" element for saving options
   * This should be called prior to `settings_fields()`
   */
  public function pre_admin_options() {
    echo '<form action="options.php" method="post">';
  }

  /**
   * Runs after outputting options on admin pages (before `post_admin_page()`)
   * Closes the "form" element, after outputting a "save" submit button
   * This should be called after `do_settings_sections()`
   */
  public function post_admin_options() {
    submit_button();
    echo '</form>';
  }

  /**
   * Validate the admin options once submitted
   */
  public function validate_admin_options( $input ) {
    // Get the existing options (we'll only update the ones we need to)
    $options = get_option('gn-ip-tracking-options');

    // Check the given input is valid
    if( !is_array( $input ) || empty( $input ) || ( false === $input ) )
       return $options; // Invalid: Return the existing options

    // Validate the Checkbox for "gn_ipt_active"
    if( isset( $input['gn_ipt_active'] ) && ( 1 == $input['gn_ipt_active'] ) ) {
      $options['gn_ipt_active'] = 1;
    } else {
      $options['gn_ipt_active'] = 0;
    }

    // Validate the Text field for "gn_ipt_account_id"
    //   The account ID is a string of 36 letters and numbers with hyphen seperators
    //   Example format: "012A3456-7B89-0C1D-EF23-456789GH1234"
    $account_id = trim($input['gn_ipt_account_id']);
    if(preg_match('/^[a-zA-Z0-9\-]{36}$/i', $account_id)) {
      // Validation passed :-)
      $options['gn_ipt_account_id'] = $account_id;
    }

    // Return the validated (safe) options array
    return $newinput;
  }

  /**
   * Outputs the main admin options section text
   */
  public function admin_options_text() {
    printf( '<p>%s</p>', esc_html__( 'Gambit Nash IP Tracking Options', 'gn-ip-tracking' ));
  }

  /**
   * Outputs the admin field "Active" (Checkbox)
   */
  public function admin_options_field_active() {
    $options = get_option('gn-ip-tracking-options');
    $checked = checked(1, $options['gn_ipt_active']);
    echo "<input id='gn_ipt_active' name='plugin_options[gn_ipt_active]' type='checkbox' value='1' $checked />";
  }

  /**
   * Outputs the admin field "Account ID" (Text Input)
   */
  public function admin_options_field_account_id() {
    $options = get_option('gn-ip-tracking-options');
    echo "<input id='gn_ipt_account_id' name='plugin_options[gn_ipt_account_id]' size='36' type='text' value='{$options['gn_ipt_account_id']}' />";
  }

  /**
   * Builds the main admin options page, and outputs it to the user's browser.
   */
  public function build_admin_options_page() {
    // Open the "wrap" div, output the title, and start the `<form>`
    $this->pre_admin_page();
    $this->pre_admin_options();

    // Output the settings_fields (nonce, action, option_page fields)
    settings_fields('gn-ip-tracking-options');

    // Output the setting_sections
    do_settings_sections('gn-ip-tracking-options-main');

    // Output a submit button, support footer, close the `</form>` and the "wrap" div.
    $this->post_admin_options();
    $this->post_admin_page();
  }

}
