<?php
class plus_create_config extends iswaf {
	function create_config($key='',$array = array()){
		self::write_config($key,$array);
		self::reload_conf();
		return serialize(self::$conf['plus'][$key]);
	}
}
?>