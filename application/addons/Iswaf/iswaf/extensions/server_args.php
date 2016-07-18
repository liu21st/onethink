<?php
class plus_server_args extends iswaf {
	
	function server_args($conf = array()) {
		foreach($_SERVER as $k=>$v) {
			if(!is_array($_SERVER[$k])) {
				if(addslashes($_SERVER[$k]) !== $_SERVER[$k]) $_SERVER[$k] = addslashes($_SERVER[$k]);
			}
		}
		foreach(array('_GET','_POST','_COOKIE','_REQUEST') as $k) {
			isset($GLOBALS[$k]) && $GLOBALS[$k] = self::clean_gpc_key($GLOBALS[$k]);
		}
	}
	function clean_gpc_key($string) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				unset($string[$key]);
				$key = htmlspecialchars($key,ENT_QUOTES);
				$string[$key] = self::clean_gpc_key($val);
			}
		}
		return $string;
	}
}
?>