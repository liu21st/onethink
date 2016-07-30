<?php
class plus_delhotfix extends iswaf {
	
	function delhotfix($fixid) {
		$array = self::$conf['plus']['hotfix'];
		if(!is_array($fixid)) {
			if(isset($array[$fixid])) unset($array[$fixid]);
		} else {
			
			foreach($fixid as $iid) {
				if(isset($array[$iid]))	unset($array[$iid]);
			}
		}
		self::write_config('hotfix', $array);
	}
	
}
?>