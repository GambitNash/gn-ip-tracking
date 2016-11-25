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
 * GN_IP_Tracking class
 *
 * Contains common functions used amongst other IP Tracking classes
 *
 * @abstract
 */
class GN_IP_Tracking {

  /**
   * Checks that both get_ip_tracking_state() is true and get_ip_tracking_account() is not null or empty
   * Used as short-hand to detect if the the IP Tracking plugin is correctly configured and enabled.
   * Returns either boolean false (No IPT) or the Account Number (Has IPT)
   */
  public function is_configured() {
    if ( true !== (bool)$this->get_ip_tracking_state() ) {
      // IPT flagged as disabled
      return (bool)false;
    }

    $ipta = (string)$this->get_ip_tracking_account();
    if ( is_null($ipta) || empty($ipta) || '' === trim($ipta) ) {
      // Blank IPT Account / IPT Account not set
      return (bool)false;
    }

    // Looks good! Return the Account ID
    return (string)$ipta;
  }

  /**
   * Gets the current state of the GN IP Tracking plugin (Enabled/Disabled)
   */
  public function get_ip_tracking_state($opts = null) {
    if ( ! $opts ) {
      $opts = get_option( 'gn-ip-tracking-options' );
    }

    return (bool)$opts['gn_ipt_active'];
  }

  /**
   * Sets the current state of the GN IP Tracking plugin (Enabled/Disabled)
   */
  public function set_ip_tracking_state($active) {
    $opts = get_option( 'gn-ip-tracking-options' );
    $opts['gn_ipt_active'] = (bool)$active;
    return update_option( 'gn-ip-tracking-options', (array)$opts );
  }

  /**
   * Gets the current account ID of the GN IP Tracking plugin
   */
  public function get_ip_tracking_account($opts = null) {
    if ( ! $opts ) {
      $opts = get_option( 'gn-ip-tracking-options' );
    }

    return (string)$opts['gn_ipt_account_id'];
  }

  /**
   * Sets the current account ID of the GN IP Tracking plugin
   */
  public function set_ip_tracking_account($account) {
    $opts = get_option( 'gn-ip-tracking-options' );
    $opts['gn_ipt_account_id'] = (bool)$active;
    return update_option( 'gn-ip-tracking-options', (array)$opts );
  }

  /**
   * Generates the IP Tracking Javascript tags for inclusion in a page.
   */
  protected function _get_ip_tracking_script($account)
  {
    $output = PHP_EOL;
    $output .= '<script type="text/javascript">' . PHP_EOL;
    $output .= '  var ptAccount = "' . (string)$account . '";' . PHP_EOL;
    $output .= '  try { ptInit(ptAccount); } catch (err) { }' . PHP_EOL;
    $output .= '</script>' . PHP_EOL;

    return (string)$output;
  }

  /**
   * Generates the IP Tracking NoScript tags for inclusion in a page.
   */
  protected function _get_ip_tracking_noscript($account)
  {
    $output = PHP_EOL;
    $output .= '<noscript>' . PHP_EOL;
    $output .= '  <img width="1" height="1" src="https://stats.gambitnash.co.uk/stats/stat-nojs.aspx?ac=' . (string)$account . '" alt="" />' . PHP_EOL;
    $output .= '</noscript>' . PHP_EOL;

    return (string)$output;
  }

  /**
   * Generates the IP Tracking NoScript tags for inclusion in a page.
   */
  public function output_ip_tracking_footer_scripts()
  {
    if ( ($account = $this->is_configured()) !== false ) {
      $output  = PHP_EOL;
      $output .= '<!-- Begin Gambit Nash IPT -->' . PHP_EOL;
      $output .= '<!-- Gambit Nash IPT: B2B Lead Tracking by Gambit Nash - https://gambitnash.co.uk/what-we-do/b2b-ip-tracking/ -->' . PHP_EOL;

      $output .= (string)$this->_get_ip_tracking_script($account);

      $output .= (string)$this->_get_ip_tracking_noscript($account);

      $output .= '<!-- End Gambit Nash IPT -->' . PHP_EOL;

      echo (string)$output;
    }
  }
}
