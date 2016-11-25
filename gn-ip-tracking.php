<?php
/**
 * @package GN_IP_Tracking
 * @version 1.0
 * @copyright Copyright (C) 2016 Gambit Nash Limited.
 * @license GNU GPL v3 or later
 * @author Daniel Wilson
 */

/*
Plugin Name: IP Tracking by Gambit Nash
Plugin URI: https://wordpress.org/plugins/gn-ip-tracking
Description: The IP Tracking plugin from Gambit Nash is an easy way to integrate your existing IP Tracking subscription into your WordPress website. Once configured, the plugin will track B2B leads via your Gambit Nash IP Tracking account.
Version: 1.0
Author: Daniel Wilson
Author URI: https://gambitnash.co.uk
License: GPLv3 or later
Text Domain: gn-ip-tracking
*/

// Make sure we're in wordpress (and not being called directly)
if ( !defined( 'ABSPATH' ) ) {
    exit();
}

// Define some constants to be used in other classes
define( 'GN_IP_TRACKING_VERSION', '1.0' );
define( 'GN_IP_TRACKING_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Load the classes (and instantise them - the class constructors will do the rest)
if ( ! class_exists('GN_IP_Tracking') ) {
  // GN_IP_Tracking - Common functions used by other IP Tracking classes
  require_once( GN_IP_TRACKING_PLUGIN_DIR . 'class.gn-ip-tracking.php' );
}
$ipt_core = new GN_IP_Tracking();

if ( ! class_exists('GN_IP_Tracking_Frontend') ) {
  // GN_IP_Tracking_Frontend - Script enqueue & footer trigger for front-end requests
  require_once( GN_IP_TRACKING_PLUGIN_DIR . 'class.gn-ip-tracking-frontend.php' );
}
new GN_IP_Tracking_Frontend($ipt_core);

// Load the admin-only classes (but only in admin)
if ( is_admin() ) {

  if ( ! class_exists('GN_IP_Tracking_Admin') ) {
     // GN_IP_Tracking_Admin - Admin Options page, settings validation functions.
	   require_once( GN_IP_TRACKING_PLUGIN_DIR . 'class.gn-ip-tracking-admin.php' );
  }

  new GN_IP_Tracking_Admin($ipt_core);
}
