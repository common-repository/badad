<?php
/**
* @package badAd
*/
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
   $badad_plugin = 'notset';
 } else {
   $badad_plugin = 'set';
 }
if (($badad_call_key == 'delete') && ($badad_siteslug == 'delete')) {
  // Delete connection.php
  unlink(plugin_dir_path( __FILE__ ).'connection.php');
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

if ($badad_status == 'live') {
  $write_dev_pub_key = $badad_live_pub;
  $write_dev_sec_key = $badad_live_sec;
} elseif ($badad_status == 'test') {
  $write_dev_pub_key = $badad_test_pub;
  $write_dev_sec_key = $badad_test_sec;
}
// Access
$badad_access = get_option('badad_access');
if ( $badad_access == 'admin' ) {
  $badAd_drole = 'administrator';
  $badAd_arole = 'administrator';
} elseif ( $badad_access == 'admineditor' ) {
  $badAd_drole = 'administrator';
  $badAd_arole = 'editor';
} elseif ( $badad_access == 'editor' ) {
  $badAd_drole = 'editor';
  $badAd_arole = 'editor';
}
// Set these per use (not standard style, but poetically brief)
if ($badAd_drole == 'administrator') {$badAd_dlevel = 'activate_plugins';}
elseif ($badAd_drole == 'editor') {$badAd_dlevel = 'edit_others_posts';}
if ($badAd_arole == 'administrator') {$badAd_alevel = 'activate_plugins';}
elseif ($badAd_arole == 'editor') {$badAd_alevel = 'edit_others_posts';}

/* Note to developers and WordPress.org reviewers

- For speed, keys for regular calls to the badAd API should utilize include(), rather than SQL queries
- The variable values for these files are stored in wp_options via the WordPress API; upon viewing the plugin dashboard, the plugin renders these files if the files are missing
- These four files are created when adding keys:
  - callback.php (created automatically by the badAd settings dashboard [this file, settings.php] after adding Dev Keys, used to talk to our API)
  - devkeys.php  (created automatically by the badAd settings dashboard from settings stored using the WP native settings-database calls)
  - connection.php (created when a user authorizes an API connection, used to store related connection "call" keys, these keys are added to the database from the file the first time it is created upon auto-redirect to the badAd settings dashboard)
- Only devkeys.php and connection.php serve as our framework, having variables developers need to build on for plugins and themes dependent on this plugin:
- What the framework files look like:
  - devkeys.php:
    ```
    <?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $my_developer_pub_key = 'some_pub_0123456789abcdfghijklmnopqruvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0abcd';
    $my_developer_sec_key = 'some_sec_0123456789abcdfghijklmnopqruvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0abcd';
    ```
  - connection.php:
    ```
    <?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    $partner_call_key = 'some_pub_0123456789abcdfghijklmnopqruvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0abcd';
    $partner_resiteSLUG = '0123456789abcdfghijklmnopqruvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdfghijklmnopqruvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdfghijklmnopqruvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdfghijklmnopqruvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghij';
    ```

*/

// Write our include files //
// Initiate $wp_filesystem
global $wp_filesystem;
if (empty($wp_filesystem)) {
  require_once (ABSPATH . '/wp-admin/includes/file.php');
  WP_Filesystem();
  WP_Filesystem_Direct();
}
// Write callback.php
$callbackFile = plugin_dir_path( __FILE__ ).'callback.php';
$connectionKeyFile = plugin_dir_path( __FILE__ ).'connection.php';
$connectionDelFile = plugin_dir_path( __FILE__ ).'disconnect.php';
$badadSettingsPage = admin_url( 'options-general.php?page=badad-settings' );
if (( ! $wp_filesystem->exists($connectionKeyFile) )
  || (( $badad_connection == 'set' ) && ( ! strpos ( $wp_filesystem->get_contents($connectionKeyFile), $badad_call_key ) === true ))
  || (( $badad_connection == 'set' ) && ( ! strpos ( $wp_filesystem->get_contents($connectionKeyFile), $badad_siteslug ) === true ))) {
  $badad_connection_file = false;
} else {
  $badad_connection_file = true;
}
if ( ( ! $wp_filesystem->exists($callbackFile)) || ( ($wp_filesystem->exists($callbackFile) ) && ( $badad_plugin == 'set' ) && ( strpos ( $wp_filesystem->get_contents($callbackFile), $write_dev_pub_key ) === false ) ) ) {
  $callbackContentsPHP = <<<'EOP'
<?php
if ((isset($_POST['badad_connect_response']))
&& (isset($_POST['partner_app_key']))
&& (isset($_POST['partner_call_key']))
&& (isset($_POST['partner_refcred']))
&& (preg_match ('/[a-zA-Z0-9_]$/i', $_POST['partner_app_key']))
&& (preg_match ('/[a-zA-Z0-9_]$/i', $_POST['partner_call_key']))
&& (preg_match ('/^call_key_(.*)/i', $_POST['partner_call_key']))
&& (preg_match ('/[a-zA-Z0-9]$/i', $_POST['partner_refcred']))) { // _POST all present and mild regex check
$partner_call_key = preg_replace( '/[^a-zA-Z0-9_]/', '', $_POST['partner_call_key'] ); // Starts with: "call_key_" Keep this in your database for future API calls with this connected partner, it starts with: "call_key_"
$partner_refcred = preg_replace( '/[^a-zA-Z0-9]/', '', $_POST['partner_refcred'] ); // The "resite.html" URL, acting as BOTH a badAd click for Partner shares AND as a referral link for ad credits uppon purchase of a new customer
$connectionKeyContents = <<<EKK
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
\$partner_call_key = '$partner_call_key';
\$partner_resiteSLUG = '$partner_refcred';
EKK;
EOP;
  $connectionKeyFileContents = <<<EOK
file_put_contents('$connectionKeyFile', \$connectionKeyContents);
header("Location: $badadSettingsPage");
exit();
}
?>
EOK;
  $callbackContentsHTML = <<<EOH
<!DOCTYPE html>
<html>
<head>
<meta name="badad.api.dev.key" content="$write_dev_pub_key" />
</head>
<body>
No script kiddies.
</body>
</html>
EOH;

  $callbackContents = $callbackContentsPHP."\n".$connectionKeyFileContents."\n".$callbackContentsHTML;
  $wp_filesystem->put_contents( $callbackFile, $callbackContents, FS_CHMOD_FILE ); // predefined mode settings for WP files
}
// end callback.php

// Check connection.php
if ((( ! $wp_filesystem->exists($connectionKeyFile) ) && ( $badad_connection == 'set' ))
   || (( $wp_filesystem->exists($connectionKeyFile) ) && ( $badad_connection == 'set' )
      && (( strpos ( $wp_filesystem->get_contents($connectionKeyFile), $badad_call_key ) === false )
       || ( strpos ( $wp_filesystem->get_contents($connectionKeyFile), $badad_siteslug ) === false )))) {

  // Write connection.php
  $connectionKeys = <<<CONN
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
\$partner_call_key = '$badad_call_key';
\$partner_resiteSLUG = '$badad_siteslug';
CONN;
  $wp_filesystem->put_contents( $connectionKeyFile, $connectionKeys, FS_CHMOD_FILE ); // predefined mode settings for WP files
  include $connectionKeyFile; // Make sure we get our variable one way or another

} elseif (( $wp_filesystem->exists($connectionKeyFile) ) && ( $badad_connection == 'notset' )) {

  // Enter the call_key into the WP settings database
  include $connectionKeyFile; // Make sure we get our variable one way or another
  update_option('badad_call_key', $partner_call_key);
  update_option('badad_siteslug', $partner_resiteSLUG);
}

// Write devkeys.php
if ( $badad_status == 'live' ) {
  $devKeysContents = <<< EDK
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
\$my_developer_pub_key = '$badad_live_pub';
\$my_developer_sec_key = '$badad_live_sec';
EDK;
} elseif ( $badad_status == 'test' ) {
  $devKeysContents = <<< EDK
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
\$my_developer_pub_key = '$badad_test_pub';
\$my_developer_sec_key = '$badad_test_sec';
EDK;
}
$devKeysFile = plugin_dir_path( __FILE__ ).'devkeys.php'; // a better way
$wp_filesystem->put_contents( $devKeysFile, $devKeysContents, FS_CHMOD_FILE ); // predefined mode settings for WP files
// end devkeys.php
