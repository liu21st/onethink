<?php
class plus_aehotfix extends iswaf {
	
	function aehotfix($fixid,$value) {
		$hotfixs = self::$conf['plus']['hotfix'];
		if(!isset($hotfix[$fixid])) {
			$hotfixs[$fixid] = $value;
		}
		self::write_config('hotfix',$hotfixs);
	}
}
?>