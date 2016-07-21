<?php
class plus_lshotfixs extends iswaf {
	
	function lshotfixs() {
		return serialize(self::$conf['plus']['hotfix']);
	}
}
?>
