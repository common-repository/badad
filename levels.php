<?php
/**
* @package badAd
*/

// Required access user levels
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
// Set these per use (not standard PHP style, but poetically brief)
if ($badAd_drole == 'administrator') {$badAd_dlevel = 'activate_plugins';}
elseif ($badAd_drole == 'editor') {$badAd_dlevel = 'edit_others_posts';}
if ($badAd_arole == 'administrator') {$badAd_alevel = 'activate_plugins';}
elseif ($badAd_arole == 'editor') {$badAd_alevel = 'edit_others_posts';}
