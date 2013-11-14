<?php
class plus_filter extends iswaf {
	function filter($conf = array()) {
		if(is_array($conf['findtoreplace'])) {
			foreach(array('_GET','_POST','_COOKIE','_SERVER') as $key) {
				$GLOBALS[$key] = self::set_filter($GLOBALS[$key] ,$conf['findtoreplace']);
			}
		}
	}
	function set_filter($array,$findtoreplace) {
		if(is_array($array)) {
			foreach($array as $k=>$v) {
				self::$key = $k;
				$array[$k] = self::set_filter($v,$findtoreplace);
			}
		}else{
			foreach($findtoreplace as $fr) {
				if(stripos($array,$fr['find'])!==false) {
					$key = self::generate_key($_SERVER['SCRIPT_FILENAME'],self::$key,$v);
					$data = array('key'=>self::$key,'value'=>$array);
					self::addlog($key, $data);
					if(isset($fr['replace']['replace'])) {
						if(isset($fr['replace']['find']) && isset($fr['replace']['replace'])) {
							$array = preg_replace('/'.preg_quote($fr['replace']['find']).'/is',$fr['replace']['replace'],$array);
						}
					}
				}
			}
		}
		return $array;
	}
}