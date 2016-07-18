<?php
class plus_client_log extends iswaf {
	function client_log($type) {
		$tmp = '';
		$return = array();
		
		$return = self::getlog($type);
		self::cleankeys();
		return serialize($return);
	}
}
?>