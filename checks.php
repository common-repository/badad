<?php
/**
* @package badAd
*/

// File locations
$siteFilePrefix = get_current_blog_id() ? 'callback/' . get_current_blog_id() . '-' : 'callback/';
$callbackFile = plugin_dir_path( __FILE__ ) . $siteFilePrefix . 'callback.php';
global $wp_filesystem;
if (empty($wp_filesystem)) {
  WP_Filesystem();
  WP_Filesystem_Direct();
}

// Keys
$badad_status = get_option('badad_testlive');
$badad_live_pub = get_option('badad_live_pub');
$badad_live_sec = get_option('badad_live_sec');
$badad_test_pub = get_option('badad_test_pub');
$badad_test_sec = get_option('badad_test_sec');
$badad_call_key = get_option('badad_call_key');
$badad_siteslug = get_option('badad_siteslug');
if (($badad_live_pub == '') || ($badad_live_pub == null) || (!isset($badad_live_pub)) || (strpos($badad_live_pub, 'live_pub_') === false)
 || ($badad_live_sec == '') || ($badad_live_sec == null) || (!isset($badad_live_sec)) || (strpos($badad_live_sec, 'live_sec_') === false)
 || ($badad_test_pub == '') || ($badad_test_pub == null) || (!isset($badad_test_pub)) || (strpos($badad_test_pub, 'test_pub_') === false)
 || ($badad_test_sec == '') || ($badad_test_sec == null) || (!isset($badad_test_sec)) || (strpos($badad_test_sec, 'test_sec_') === false)) {

   $badad_plugin = 'notset'; // Plugin keys not setup

 } else {

   $badad_plugin = 'set'; // Plugin keys setup

 }

// Clear out any to-be-deleted key settings
if (($badad_call_key == 'delete') && ($badad_siteslug == 'delete')) {
  // Reset the options
  update_option('badad_call_key', '');
  update_option('badad_siteslug', '');
  // Set our variables
  $badad_call_key = null;
  $badad_siteslug = null;
  $badad_connection = 'notset';
} elseif (($badad_call_key == '') || ($badad_call_key == null) || (!isset($badad_call_key)) || (strpos($badad_call_key, 'call_key_') === false)
 || ($badad_siteslug == '') || ($badad_siteslug == null) || (!isset($badad_siteslug)) || (!preg_match('/[A-Za-z]/', $badad_siteslug))) {
  $badad_connection = 'notset';
} else {
  $badad_connection = 'set';
}

// Dev keys, test or live?
if ( $badad_status == 'live' ) {
  $my_developer_pub_key = $badad_live_pub;
  $my_developer_sec_key = $badad_live_sec;
} elseif ( $badad_status == 'test' ) {
  $my_developer_pub_key = $badad_test_pub;
  $my_developer_sec_key = $badad_test_sec;
}

// Connection
if ($badad_connection == 'set') {
  $partner_call_key = $badad_call_key;
  $partner_resiteSLUG = $badad_siteslug;
  $partner_resiteURL = "https://badad.one/$partner_resiteSLUG/site.html";
} else {
  $partner_call_key = '';
  $partner_resiteSLUG = '444';
  $partner_resiteURL = "https://badad.one/$partner_resiteSLUG/site.html";
}

// Confirm proper callback file
if ( $badad_plugin == 'set' ) { // Create the callback file
  include (plugin_dir_path( __FILE__ ) . 'callback.php');

} elseif ( $badad_plugin == 'notset' ) { // Delete any unnecessary callback file
  if ( $wp_filesystem->exists($callbackFile) ) {
    wp_delete_file( $callbackFile );
  }
}
