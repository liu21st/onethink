<?php
class plus_denier extends iswaf {
	function denier($conf) {
		if(empty($conf) && file_exists(iswaf_database.'conf/denier.php')){
    	    $conf=include(iswaf_database.'conf/denier.php');
	    }
		if(isset($conf['ips']) && is_array($conf['ips'])) {
			$keys = array('REMOTE_ADDR','HTTP_X_FORWARDED_FOR','HTTP_CLIENT_IP');
			foreach($keys as $key) {
				if(isset($_SERVER[$key]) && in_array($_SERVER[$key],$conf['ips'])) {
					$key = self::generate_key($_SERVER[$key]);
					$array = array('value'=>$_SERVER[$key]);
					self::addlog($key, $array);
					self::deny('deny.');
				}
			}
		}
		if(isset($conf['filepaths']) && is_array($conf['filepaths'])) {
			if(in_array($_SERVER['SCRIPT_FILENAME'],$conf['filepaths'])) {
				$key = self::generate_key($_SERVER['SCRIPT_FILENAME']);
				$array = array('value'=>$_SERVER['SCRIPT_FILENAME']);
				self::addlog($key, $array);
				self::deny('deny.');
			}
		}
	}
}
?>