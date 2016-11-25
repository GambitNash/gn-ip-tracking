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
 * GN_IP_Tracking_Frontend class
 *
 * Front-end code for the IP Tracking integration - Deals with registering and
 * enqueuing front-end scripts.
 */
class GN_IP_Tracking_Frontend {
  private $ipt_core = null;

  public function __construct($ipt_core) {
    $this->ipt_core = $ipt_core;

    // Register Scripts
    add_action('wp_enqueue_scripts', array($this, 'register_scripts'));

    // See if we're frontend & active...
    if ( ! is_admin() && $this->ipt_core->is_configured() !== false ) {
      // We are; Enqueue the scripts (registered above)
      add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
      add_action('wp_footer', array($this, 'output_ip_tracking_footer_scripts'), 30);
    }
  }

  public function register_scripts() {
    $src = 'https://stats.gambitnash.co.uk/stats/pt.js';
    wp_register_script('gn-ip-tracking', $src, array(), null, true);
  }

  public function enqueue_scripts() {
    wp_enqueue_script('gn-ip-tracking');
  }

}
