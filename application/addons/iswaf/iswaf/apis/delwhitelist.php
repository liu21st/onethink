<?php
class plus_delwhitelist extends iswaf {
	
	function delwhitelist($id) {
		$array = self::$conf['plus']['whitelist'];
		if(!is_array($id)) {
			if(isset($array[$id])) unset($array[$id]);
		} else {
			foreach($id as $iid) {
				if(isset($array[$iid]))	unset($array[$iid]);
			}
		}
		self::write_config('whitelist', $array);
	}
	
}
?>