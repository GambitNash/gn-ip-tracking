<?php
/**
 * @package GN_IP_Tracking
 * @version 1.0
 */
/*
Plugin Name: IP Tracking by Gambit Nash
Plugin URI: https://wordpress.org/plugins/gn-ip-tracking
Description: The IP Tracking plugin from Gambit Nash is an easy way to integrate your existing subscription into your WordPress website. Once configured, the plugin will track B2B leads via your Gambit Nash IP Tracking account.
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

// Load the classes
if ( ! class_exists('GN_IP_Tracking') ) {
  require_once( GN_IP_TRACKING_PLUGIN_DIR . 'class.gn_ip_tracking.php' );
}

if ( ! class_exists('GN_IP_Tracking_Frontend') ) {
  require_once( GN_IP_TRACKING_PLUGIN_DIR . 'class.gn_ip_tracking-frontend.php' );
}

// Load the admin-only classes (but only in admin)
if ( is_admin() && ! class_exists('GN_IP_Tracking_Admin') ) {
	require_once( GN_IP_TRACKING_PLUGIN_DIR . 'class.gn_ip_tracking-admin.php' );
	add_action( 'init', array( 'GN_IP_Tracking_Admin', 'init' ) );
}
