<?php
class plus_filemode extends iswaf {
	
	function filemode($conf = array()) {
		
		foreach(self::$gpc as $k=>$v) {
			if(strpos($v,'../') || stripos($v,'..\\') && ($k!=='server')) {
				$key = '_'.strtoupper($k);
				if($conf['mode'] !=='silent'){
					$GLOBALS[$key] = self::remove_evilcode($GLOBALS[$key]);
					$_REQUEST = self::remove_evilcode($_REQUEST);
				}
				$key = self::generate_key($k,$v);
				$array = array('value'=>$v,'key'=>'request','pos'=>$k);
				self::addlog($key, $array);
			}
			if(isset($conf['rulers'])) {
				foreach($conf['rulers'] as $id=>$ruler) {
					if(@preg_match($ruler,$temp) !==false) {
						$key = self::generate_key($k,$v,$ruler);
						$array = array('value'=>$v,'key'=>'request','pos'=>$k);
						self::addlog($key, $array);
					}
				}
			}
		}
	}
	function remove_evilcode($string) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				self::$key = $key;
				$string[$key] = self::remove_evilcode($val);
			}
		} else {
			if(stripos($string,'..')!==false) {
				$string = str_replace(chr(0),'',$string);
				$t = str_replace('\\','/',$string);
				if(preg_match_all("/\.\.\//s",$t,$c)) {
					if(count($c['0']) > 2) $string = str_replace(array('../','..\\'),'..&#47;',$string);
				}
			}
		}
		return $string;
	}
}