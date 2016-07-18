<?php
class plus_safemode extends iswaf {
	function safemode($conf = array()) {
		$_GET = self::_safemode($_GET);
		$_POST = self::_safemode($_POST);
		$_COOKIE = self::_safemode($_COOKIE);
		$_SERVER = self::_safemode($_SERVER);
	}
	function _safemode($array) {
		$logarray = array('<?','?>','../','..\\','${','file_get_contents','/*');
		$find     = array('<','>','${','*/');
		$replace  = array('&lt;','&gt;','|{','*|');
		
		if(!is_array($array)) {
			foreach($logarray as $v) {
				if(stripos($array,$v)!==false) return $array = str_replace($find,$replace,$array);
			}
		} else {
			foreach($array as $k=>$v) {
				$array[$k] = self::_safemode($v);
			}
		}
		return $array;
	}
}
?>