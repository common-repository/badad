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

// Keys & Files
include (plugin_dir_path( __FILE__ ) . 'checks.php');

// User access levels
include (plugin_dir_path( __FILE__ ) . 'levels.php');

?>

<div class="wrap">
  <h1>badAd</h1>

<?php

// Check for a writable plugin directory
$path = plugin_dir_path( __FILE__ );
if (!wp_is_writable($path)) {
  echo "<h2>Your 'badad' plugin folder is not writable on the server!</h2>
  <p>If you are using Apache, you might need to run:</p>
  <code>sudo chown -R www-data:www-data $path</code>
  <p>We can't do anymore until this gets fixed.</p>";
  exit();
}

// Callback URL
$callbackURL = plugin_dir_url('badad') . 'badad/' . $siteFilePrefix . 'callback.php';

// Check keys
if ( ( current_user_can($badAd_dlevel) ) && ( $badad_plugin == 'notset' ) ) {
  // add Dev keys
  echo '<h2>Add your badAd Developer API keys to get started!</h2>
  <p>These keys can be found or created in your badAd.one <i>Partner Center > Developer Center</i>. For help or to create an account, see the <a target="_blank" href="https://badad.one/444/site.html">help videos here</a>.</p>
  <p>Dev Callback URL: <code><b>'.$callbackURL.'</b></code> <i>(for badAd Developer Center: Dev App settings)</i></p>
  <form method="post" action="options.php">';
    settings_fields( 'devkeys' );
    echo '<h4>Keys</h4>
    <label for="badad_live_pub">Live Public Key:</label>
    <input name="badad_live_pub" type="text" style="width: 100%" ><br>
    <label for="badad_live_sec">Live Secret Key:</label>
    <input name="badad_live_sec" type="text" style="width: 100%" ><br>
    <label for="badad_test_pub">Test Public Key:</label>
    <input name="badad_test_pub" type="text" style="width: 100%" ><br>
    <label for="test_sec_key">Test Secret Key:</label>
    <input name="badad_test_sec" type="text" style="width: 100%" ><br>
    <br>
    <input type="checkbox" name="double_check_key_update" value="certain" required>
    <label for="double_check_delete"> I am sure I want to update the keys.</label>
    <input class="button button-secondary" type="submit" value="Update all keys as shown">
  </form>
  <br><hr>
  <h2>Need help?</h2>
  <p><a target="_blank" href="https://badad.one/help_videos.php">Learn more</a> or sign up to start monetizing today!</p>
  <p>You must be registered, have purchased one (ridiculously cheap) ad, and confirmed your email to be a <a target="_blank" href="https://badad.one/444/site.html">badAd.one</a> Dev Partner. It could take as little as $1 and 10 minutes to be up and running! <a target="_blank" href="https://badad.one/444/site.html">Learn more</a>.</p>
  <p><iframe width="640" height="360" scrolling="no" frameborder="0" style="border: none;" src="https://www.bitchute.com/embed/gW3C4CtlzrWw/"></iframe></p>';

} elseif ( ( current_user_can($badAd_alevel) ) && ( $badad_connection == 'notset' ) ) {

  // Callback process?
  if (isset($_GET['callback'])) {

    // Did the API send us here?
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

      // Make the changes
      update_option('badad_call_key', $partner_call_key);
      update_option('badad_siteslug', $partner_refcred);

      // Reload the page using JavaScript, using header("Location: ") doesn't work
      $badadSettingsPage = admin_url( 'options-general.php?page=badad-settings' );
      echo '<script>window.location.href = "'.$badadSettingsPage.'";</script>';
      exit();

    } else { // API didn't send us here
      // Reload the page using JavaScript, using header("Location: ") doesn't work
      $badadSettingsPage = admin_url( 'options-general.php?page=badad-settings' );
      echo '<script>window.location.href = "'.$badadSettingsPage.'";</script>';
      exit();
    }
  }

  // Forms to connect

  // User app_key
  echo '
  <form id="connect_partner_app_id" class="connect_partner" action="https://badad.one/connect_app.php" method="post" accept-charset="utf-8">
  <p><b>Connect with a Partner App Key</b></p>

  <!-- DEV NEEDS THIS -->
  <input type="hidden" name="dev_key" value="' . $my_developer_sec_key . '" />

  <label for="partner_app_key">Your Partner App Key:</label>
  <br /><br />

  <!-- DEV NEEDS THIS: name="partner_app_key" -->
  <input type="text" name="partner_app_key" id="partner_app_key" size="32" required />

  <input class="button button-primary" type="submit" value="Connect" class="formbutton" />
  <br />
  </form>';

  // Be pretty
  echo "<br /><hr /><br />";

  // User login
  echo '
  <form id="connect_partner_app_id" class="connect_partner" action="https://badad.one/connect_app.php" method="post" accept-charset="utf-8">
  <p><b>Connect by login</b></p>

  <!-- DEV NEEDS THIS -->
  <input type="hidden" name="dev_key" value="' . $my_developer_sec_key . '" />

  <input class="button button-primary" type="submit" value="Login to Connect..." class="formbutton" />
  <br />
  </form>
  <br><hr>
  <h2>Need help?</h2>
  <p><a target="_blank" href="https://badad.one/help_videos.php">Learn more</a> or sign up to start monetizing today!</p>
  <p>You must be registered, have purchased one (ridiculously cheap) ad, and confirmed your email to be a <a target="_blank" href="https://badad.one/444/site.html">badAd.one</a> Partner. It could take as little as $1 and 10 minutes to be up and running! <a target="_blank" href="https://badad.one/444/site.html">Learn more</a>.</p>
  <p><iframe width="640" height="360" scrolling="no" frameborder="0" style="border: none;" src="https://www.bitchute.com/embed/mZSpkFWnCbxo/"></iframe></p>';

  // Be pretty
    echo "<br /><hr /><br />";

} elseif ( current_user_can('edit_posts') ) {
  // All Contributors
  // Shortcode help
  echo "<h2>Shortcodes:</h2>";
  echo "<h3><code>[badad]</code></h3>";
  echo "<p><i>Retrieve ads from badAd, share count</i></p>";
  echo "<p><code><b>[badad num=10 valign=yes balink=yes hit=no]</b></code> <i>(<b>Default</b>: ten ads, vertically aligned, shows badad.one link, no hit count; same as using </i><code><b>[badad]</b></code><i>)</i></p>";
  echo "<p><code><b>[badad num=8 valign=no balink=no hit=yes]</b></code> <i>(eight ads, side-by-side, no badad.one link, hit counted)</i></p>";
  echo "<p>&nbsp;&nbsp;<code><b>num=</b> Number 1-20:</code> <i>how many ads to show (1 share per ad)</i></p>";
  echo "<p>&nbsp;&nbsp;<code><b>balink=</b> yes/no:</code> <i>Count-shares-if-clicked referral link, text only (share count of 1 ad)</i></p>";
  echo "<p>&nbsp;&nbsp;<code><b>valign=</b> yes/no:</code> <i>Align ads vertically? (no effect on share count)</i></p>";
  echo "<p>&nbsp;&nbsp;<code><b>hit=</b> yes/no:</code> <i>Count as \"hit\" in Project Stats? (no effect on share count)</i></p>";
  echo "<p>&nbsp;&nbsp;<i>Tip: Set exactly ONE <code>[badad hit=yes]</code> (any settings with <code>hit=yes</code>) per page for accurate Stats</i></p>";
  echo "<br>";
  echo "<h3><code>[badadrefer]</code></h3>";
  echo "<p><i>Count-shares-if-clicked referral link, no view share or hit count (loads fast)</i></p>";
  echo "<p><code><b>[badadrefer type=domain]</b></code> <i>Text: \"<b>badAd.one</b>\" (<b>Default</b>, same as using </i><code><b>[badadrefer]</b></code><i>)</i></p>";
  echo "<p><code><b>[badadrefer type=claim]</b></code> <i>Text: \"<b>Claim your ad credit...</b>\"</i></p>";
  echo "<p><code><b>[badadrefer type=pic]</b></code> <i>Shows badAd logo-slogan cycling GIF image instead of text (may change when plugin is updated)</i></p>";
  echo "<p>&nbsp;&nbsp;<i>Note: If placed in the same \"Text\" widget as a </i><code><b>[badad]</b></code><i> shortcode, this may appear at the bottom of the widget; solution is to place this in a unique \"Text\" widget</i></p>";
  echo '<br><p><i>Watch the <a target="_blank" href="https://www.bitchute.com/video/BkIMAjWX4jii/">help video on badAd-WordPress shortcodes</a></i></p>';
  echo "<hr>";

}

// Plugin Settings
if ( current_user_can($badAd_alevel) ) {

  echo "<h2>Connection Status:</h2>";

  // App Connection
  if ( $badad_connection == 'set' ) {
    // Display information
    echo "<p><i><b>Connected to App Project:</b></i></p>";
    extract(badad_meta()); // Use extract because we will use the response variable later

  } elseif ( $badad_connection == 'notset' ) {

    // Form to connect
    echo "<p><b>Use the form above to connect.</b></p>";
  }

  echo "<hr>";

}

// Display current status, dev keys & callback
if ( ( current_user_can($badAd_dlevel) ) && ( $badad_plugin == 'set' ) ) {
  // Important info
  echo "<h2>Reference:</h2>";

  // Are there plugin settings to show?
  echo "<p>WP Plugin Status: <code><b>$badad_status</b></code></p>";
  echo "<p>Current Public Key: <code><b>$my_developer_pub_key</b></code></p>";
  echo "<p>Dev Callback URL: <code><b>$callbackURL</b></code></p>";

  echo "<hr>" ;
}

// Settings
if ( current_user_can($badAd_alevel) ) {
  echo "<h3>Danger Zone: Make changes</h3>";

  // Change Dev keys/status
  if ( current_user_can($badAd_dlevel) ) {
    echo '
    <button class="button button-primary" onclick="showDevKeysStatus()">Dev keys & status <b>&darr;&darr;&darr;</b></button>
    <div id="devKeysStatus" style="display:none">

    <!-- Status radio form -->
    <form method="post" action="options.php">';
      settings_fields( 'status' );
      echo '<h4>Status</h4>
      <input type="radio" name="badad_testlive" value="live"';
      checked('live', $badad_status, true);
      echo '> Live<br>

      <input type="radio" name="badad_testlive" value="test"';
      checked('test', $badad_status, true);
      echo '> Test<br>
      <br>

      <input class="button button-secondary" type="submit" value="Save status">
    </form>
    <br><br>

    <!-- Update keys form -->
    <form method="post" action="options.php">';
      settings_fields( 'devkeys' );
      echo '<h4>Keys</h4>
      <label for="badad_live_pub">Live Public Key:</label>
      <input name="badad_live_pub" type="text" style="width: 100%" value="';
      echo $badad_live_pub;
      echo '" ><br>
      <label for="badad_live_sec">Live Secret Key:</label>
      <input name="badad_live_sec" type="text" style="width: 100%" value="';
      echo $badad_live_sec;
      echo '" ><br>
      <label for="badad_test_pub">Test Public Key:</label>
      <input name="badad_test_pub" type="text" style="width: 100%" value="';
      echo $badad_test_pub;
      echo '" ><br>
      <label for="test_sec_key">Test Secret Key:</label>
      <input name="badad_test_sec" type="text" style="width: 100%" value="';
      echo $badad_test_sec;
      echo '" ><br>
      <br>
      <input type="checkbox" name="double_check_key_update" value="certain" required>
      <label for="double_check_delete"> I am sure I want to update the keys.</label>
      <input class="button button-secondary" type="submit" value="Update all keys as shown">
    </form>
    <p>You can update these keys from the same Dev App and it will not disconnect your ads.</p>
    <hr>
    </div>
    <script>
    function showDevKeysStatus() {
      var x = document.getElementById("devKeysStatus");
      if (x.style.display === "block") {
        x.style.display = "none";
      } else {
        x.style.display = "block";
      }
    }
    </script>
    <br><br>
    ';
  }

  // Delete App Call keys
  if (( current_user_can($badAd_alevel) ) && ( isset($connection_meta_response) )) {
    echo '
    <button class="button button-primary" onclick="showAppConnection()">App connection <b>&darr;&darr;&darr;</b></button>
    <div id="appConnection" style="display:none">
    <h4>Delete current App connection?</h4>
    <p><i>Connected to App Project:<br>'.$connection_meta_response.'</i></p>
    <form method="post" action="options.php">';
    settings_fields( 'connection' );
    echo '<input type="hidden" name="badad_call_key" value="delete">
    <input type="hidden" name="badad_siteslug" value="delete">
    <input type="checkbox" name="double_check_delete" value="certain" required>
    <label for="double_check_delete"> I am sure I want to delete this connection.</label>
    <input class="button button-secondary" type="submit" value="Disconnect from this badAd App Project forever!">
    </form>
    <br>
    <hr>
    </div>
    <script>
    function showAppConnection() {
      var x = document.getElementById("appConnection");
      if (x.style.display === "block") {
        x.style.display = "none";
      } else {
        x.style.display = "block";
      }
    }
    </script>
    <br><br>
    ';
  }
}
// Who can change plugin keys and connection
if ( current_user_can('update_plugins') ) { // Only admins or super admins
  // js button "User level settings..."
  // Radio options: Administrator for all; Administrator for Dev keys, Editor for App connection; Editor for all
  echo '
  <button class="button button-primary" onclick="showPluginAccess()">Plugin access <b>&darr;&darr;&darr;</b></button>
  <div id="pluginAccess" style="display:none">
    <form method="post" action="options.php">';
      settings_fields( 'access' );
      echo '<h4>Who can change Dev keys and App connection?</h4>
      <input type="radio" name="badad_access" value="admin"';
      checked('admin', $badad_access, true);
      echo '> Administrator for all<br>
      <input type="radio" name="badad_access" value="admineditor"';
      checked('admineditor', $badad_access, true);
      echo '> Administrator for Dev keys, Editor for App connection
      <br>
      <input type="radio" name="badad_access" value="editor"';
      checked('editor', $badad_access, true);
      echo '> Editor for all<br>
      <br>
      <br><br>
      <input class="button button-secondary" type="submit" value="Save">
    </form>
    <br><hr>
  </div>
  <script>
  function showPluginAccess() {
    var x = document.getElementById("pluginAccess");
    if (x.style.display === "block") {
      x.style.display = "none";
    } else {
      x.style.display = "block";
    }
  }
  </script>
  ';
}

?>
</div>
