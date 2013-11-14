<?php
class plus_aewhitelist extends iswaf {
	
	function aewhitelist($id,$value) {
		
		$array = self::$conf['plus']['whitelist'];
		if(!isset($array[$id])) {
			$array[$id] = $value;
		}
		self::write_config('whitelist',$array);
	}
	
}
?>