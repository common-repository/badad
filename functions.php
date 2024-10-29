<?php
/**
* @package badAd
*/

// Site ID -based connection.php location
$siteFilePrefix = get_current_blog_id() ? 'callback/' . get_current_blog_id() . '-' : 'callback/';
$callbackFile = plugin_dir_path( __FILE__ ) . $siteFilePrefix . 'callback.php';

// Toolbox for callback files
function badad_files() {
  global $siteFilePrefix;
  global $callbackFile;

  // Initiate $wp_filesystem now so we can call WP_Filesystem_Direct(); later
  global $wp_filesystem;
  if (empty($wp_filesystem)) {
    require_once (ABSPATH . 'wp-admin/includes/file.php');
    WP_Filesystem();
  }
}
badad_files();

// Keys & Files
include (plugin_dir_path( __FILE__ ) . 'checks.php');

// Pic Credit-referral
function badad_refer( $atts = array() ) {
  global $partner_resiteURL;

  // Defaults
    extract(shortcode_atts(array(
      'type' => 'domain'
    ), $atts));

    if (isset($type)) {
      if ($type == 'claim') {
        $content = '<hr class="badad_shortcode badad_txt badad_hr_top"><p style="text-align: center;"><a id="baVrtLnk1" title="Claim your ad credit at badAd.one with this referral link..." rel="nofollow" href="' . $partner_resiteURL . '"><b>Claim your ad credit...</b></a></p><hr class="badad_shortcode badad_txt badad_hr_bot">';
      } elseif ($type == 'pic') {
        $content = '<p style="text-align: center;"><a class="badad_shortcode badad_gif" id="baVrtLnk1" title="Unannoying advertising" rel="nofollow" href="' . $partner_resiteURL . '"><img class="aligncenter" id="baVrtImg1" alt="badAad.one" src="' . plugins_url() . '/badad/art/badadcred.gif" /></a></p>';
      } elseif ($type == 'domain') {
        $content = '<hr class="badad_shortcode badad_txt badad_hr_top"><p style="text-align: center;"><a id="baVrtLnk1" title="Unannoying advertising" rel="nofollow" href="' . $partner_resiteURL . '"><b>badAd.one</b></a></p><hr class="badad_shortcode badad_txt badad_hr_bot">';
      }
    }
    $content = '<div class="badad_ad badad_container">'.$content.'</div>';
  return $content;
}
add_shortcode('badadrefer', 'badad_refer');

// Embedded ads via API
function badad_ads( $atts = array() ) {
  global $my_developer_sec_key;
  global $partner_call_key;

  // Defaults
    extract(shortcode_atts(array(
      'num' => 10,
      'balink' => 'yes',
      'valign' => 'yes',
      'hit' => 'no'
    ), $atts));

  // Regex tests
    // $num
    if (filter_var($num, FILTER_VALIDATE_INT, array("options"=>array('min_range'=>0, 'max_range'=>20)))) {
      $num = $num;
    } else {
      $num = 2;
    }
    // $balink
    if ((isset($balink)) && ($balink == 'yes')) {
      $balink = true;
    } else {
      $balink = false;
    }
    // $valign
    if ((isset($valign)) && ($valign == 'yes')) { // Human setting is reverse from the api (true = horizantal)
      $valign = false;
    } else {
      $valign = true;
    }
    // $hit
    if ((isset($hit)) && ($hit == 'yes')) { // Human setting is reverse from the api (true = hide)
      $hit = false;
    } else {
      $hit = true;
    }

  // Build the _POST the WordPress way: wp_remote_post()
  $body = array(
    'num_ads' => $num, // Optional, 1-20, default 1
    'show_badad_link' => $balink, // Optional, default false
    'inline_div' => $valign, // Optional, default false
    'no_hit' => $hit, // Optional, default false; if TRUE this counts the same shares, but not as a "hit" in stats, use in sequential calls to avoid triggering multiple "hits" in Partner stats when making more than one call on a single page

    'dev_key' => $my_developer_sec_key,
    'call_key' => $partner_call_key
  );

  // _POST envelope the WordPress way: wp_remote_post()
  $args = array(
    'body' => $body,
    'timeout' => '5',
    'redirection' => '5',
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(),
    'cookies' => array()
  );

  // Give the _POST a hearty sendoff the WordPress way: wp_remote_post()
  $response = wp_remote_post('https://api.badad.one/render.php', $args);
  if ((isset($response)) && ($response != '')) {
    // Filter this glob we got back through the API
    $clean_response = $response['body'];
    echo $clean_response; // This $response is the HTML payload fetched from our Dev API
  }

}
add_shortcode('badad', 'badad_ads');

// Fetch Partner meta
function badad_meta() {

  global $my_developer_sec_key;
  global $partner_call_key;

  // Build the _POST the WordPress way: wp_remote_post()
  $body = array(
    'dev_key' => $my_developer_sec_key,
    'call_key' => $partner_call_key,
  );

  // _POST envelope the WordPress way: wp_remote_post()
  $args = array(
    'body' => $body,
    'timeout' => '15',
    'redirection' => '15',
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(),
    'cookies' => array()
  );

  // Give the _POST a hearty sendoff the WordPress way: wp_remote_post()
  $response = wp_remote_post('https://api.badad.one/fetchmeta.php', $args);
  if ((!isset($response)) || ($response == '')) {
    echo "<div class=\"connected\"><p>Connection not working! Is this plugin set to the same <b>test/live</b> status as your Dev App in the badAd Developer Center?</p></div>";
  } else {
    // Filter this glob we got back through the API
    $clean_response = $response['body'];
    echo "<div class=\"connected\"><p></p>$clean_response<p></p></div>"; // This $response is the HTML payload fetched from our Dev API
  }

  // We need our variables
  $connection_meta_response = $clean_response;
  return compact(
    'connection_meta_response'
  );
}
