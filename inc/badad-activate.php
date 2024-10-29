<?php
/**
* @package badAd
*/

class badAdActivate {
		public static function activate() {
			flush_rewrite_rules();
		}
}
