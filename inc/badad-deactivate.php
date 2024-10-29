<?php
/**
* @package badAd
*/

class badAdDeactivate {
		public static function deactivate() {
			flush_rewrite_rules();
		}
}
