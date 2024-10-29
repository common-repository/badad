<?php
/**
* @package badAd
*/

// Initiate $wp_filesystem
global $wp_filesystem;
if (empty($wp_filesystem)) {
  WP_Filesystem();
  WP_Filesystem_Direct();
}

// Verify our callback file
if ( ( ! $wp_filesystem->exists($callbackFile)) || ( ($wp_filesystem->exists($callbackFile) ) && ( $badad_plugin == 'set' ) && ( strpos ( $wp_filesystem->get_contents($callbackFile), $my_developer_pub_key ) === false ) ) ) {
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
$partner_app_key = preg_replace( '/[^a-zA-Z0-9]/', '', $_POST['partner_app_key'] );
EOP;

  $callbackPOSTto = admin_url( 'options-general.php?page=badad-settings&callback' );
  $connectionContentsPOST = <<<EOK
// POST to settings page
  echo "
  <form id=\"jsGoForm\" action=\"$callbackPOSTto\" method=\"post\">
    <input type=\"hidden\" name=\"badad_connect_response\" value=\"ture\">
    <input type=\"hidden\" name=\"partner_refcred\" value=\"\$partner_refcred\" /><br />
    <input type=\"hidden\" name=\"partner_call_key\" value=\"\$partner_call_key\">
    <input type=\"hidden\" name=\"partner_app_key\" value=\"\$partner_app_key\">
  </form>
  <script type=\"text/javascript\">
      document.getElementById('jsGoForm').submit();
  </script>";
  exit(); // Quit the script
}
?>

EOK;
  $callbackContentsHTML = <<<EOH
<!DOCTYPE html>
<html>
<head>
<meta name="badad.api.dev.key" content="$my_developer_pub_key" />
</head>
<body>
No script kiddies.
</body>
</html>
EOH;

  $callbackContents = $callbackContentsPHP."\n".$connectionContentsPOST."\n".$callbackContentsHTML;
  $wp_filesystem->put_contents( $callbackFile, $callbackContents, FS_CHMOD_FILE ); // Predefined mode settings for WP files

}
