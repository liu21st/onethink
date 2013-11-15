<?php
class plus_inject extends iswaf {
	public static $gpcpath = '';
	function inject($conf = array()) {
		$key_array = array('get'=>'_GET','post'=>'_POST','cookie'=>'_COOKIE');
		foreach(self::$gpc as $k=>$v) {
			self::$gpcpath = $k;
			if(self::is_inject($v,$conf)){
				$GLOBALS[$key_array[$k]] = self::safecover($GLOBALS[$key_array[$k]],$conf);
				$_REQUEST = self::safecover($_REQUEST,$conf,false);
			}
		}
	}
	function stripos($subject,$keys = array()) {
		$count = count($keys);
		$i = 0;
		foreach($keys as $key) {
			if(stripos($subject,$key) !== false) $i++;
		}
		return $i == $count ? true : false;
	}
	
	function safecover($string,$conf=array(),$recordlog=true) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				self::$key .= '/'.$key;
				if(self::is_inject($key,$conf)) {
					if($conf['mode'] !=='silent') {
						unset($string[$key]);
						$key = self::safecover($key,array(),$recordlog);
					}
				}
				$string[$key] = self::safecover($val,$conf,$recordlog);
			}
		} else {
			if(self::is_inject($string,$conf)) {
				$key = self::generate_key(self::$key, self::$conf['remote_ip'],'inject');
				$array = array('key'=>'request','value'=>$string);
				if($recordlog) self::addlog($key, $array);
				if($conf['mode'] !=='silent') {
					$string = str_replace('s','&#115;',$string);
					$string = str_replace('S','&#83;',$string);
					$string = str_replace(array('=','<','>','('),array('&#61;','&#60;','&#62;','&#40;'),$string);
				}
			}
		}
		return $string;
	}
	function safe_replace($var) {
		if(strtolower(substr(trim($var),0,6)) == 'select') {
			$tmp = strtolower(trim($var));
			$a = explode('select',$var);
			$count = count($a);
			if($count < 2 && trim($a[0]) == '') return $var;
						
		} else {
									$tmp = preg_replace('/[\'\"]+select/','',$var);
			if(!self::is_inject($tmp,self::$extends_conf)) {
				return $var;
			} else {
				$clean = preg_replace("/[^a-z0-9_\-\(\)#\*\/]+/is", "", strtolower($tmp));
				if(preg_match('/(\(|union)[a-z0-9_\-\#\*\/]*select[a-z0-9_\-\(\)#\*\/]*from/',$clean)) {
					$var = str_replace('s','&#115;',$var);
					$var = str_replace('S','&#83;',$var);
				}
			}	
		}
		return $var;
	}
	function is_inject($input,$conf = array()) {
		
		$input = str_replace('+',' ',$input);
		$keys = array();
		foreach(array('and','or','&&','||') as $k) {
			if(stripos($input,$k)!==false) {
				$keys[] = preg_quote($k);
			}
		}
		
		if($keys && stripos($input,'(')!==false) {
			if(preg_match('/('.implode('|',$keys).')\b.*\(.*select\b/is',$input)) return true;
			
		}
		$keys = array();
		if(stripos($input,'union')) {
			if(preg_match('/union\b.*select\b/is',$input)) {		
				return 1;
			}
					
		}
		if(stripos($input,'select') !== false) {
			if(preg_match('/select\b.*from\b/is',$input)) {		
				return 2;
			}
			
		}
		if(stripos($input,'into') !== false && (stripos($input,'outfile') !== false || stripos($input,'dumpfile') !== false)) {
			if(preg_match('/into\b.*(out|dump)file\b/is',$input)) {		
				return 3;
			}	
		}
		$keys = array('database','user','sleep','load_file','benchmark');
		
		foreach($keys as $k){
			if(stripos($input,$k)!==false) {
				if(strtolower(substr($input,0,6)!=='select')) {
					if(preg_match('/('.implode('|',$keys).')\b(\s|(\/\*.*?\*\/))*?\(/is',$input)) {
						return 4;
					}
				}
			}
		}
		if($conf['rulers']) {
			foreach($conf['rulers'] as $ruler) {
				if(@preg_match($ruler,$input) !==false) {
					return true;
				}
			}
		}
	}
}
?>