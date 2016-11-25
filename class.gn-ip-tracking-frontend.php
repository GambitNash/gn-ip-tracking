<?php
/**
 * @package GN_IP_Tracking
 * @version 1.0
 * @copyright Copyright (C) 2016 Gambit Nash Limited.
 * @license GNU GPL v3 or later
 * @author Daniel Wilson
 */

// Make sure we're in wordpress (not being called directly) and the plugin is loaded.
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

    // See if we're frontend, configured and active...
    if ( ! is_admin() && $this->ipt_core->is_configured() !== false ) {
      // We are active and front-end; Enqueue the registered scripts
      add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

      // Also hook the tracking trigger to the 'wp_footer' action - this outputs the tracking ID and trigger.
      add_action('wp_footer', array($this, 'output_ip_tracking_footer_scripts'), 30);
    }
  }

  /**
   * Register GN IP Tracking Scripts.
   *
   * Hooked to the 'wp_enqueue_scripts' action.
   */
  public function register_scripts() {
    // pt.js is the external tracker and responsible for tracking the visitors
    $src = 'https://stats.gambitnash.co.uk/stats/pt.js';

    // Register pt.js for use in the footer of the site with no dependencies.
    wp_register_script('gn-ip-tracking', $src, array(), null, true);
  }

  /**
   * Enqueue GN IP Tracking Scripts.
   *
   * Hooked to the 'wp_enqueue_scripts' action but only when the request is
   * front-end and the site has IP Tracking enabled and configured.
   *
   * The 'gn-ip-tracking' script (pt.js) has already been registered by this
   * class at this point.
   *
   * @see GN_IP_Tracking_Frontend::register_scripts
   */
  public function enqueue_scripts() {
    wp_enqueue_script('gn-ip-tracking');
  }

}
