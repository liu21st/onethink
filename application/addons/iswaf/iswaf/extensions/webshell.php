<?php
class plus_webshell extends iswaf {
	
	function webshell($conf = array()) {
		
		$key = self::generate_key('lock',$_SERVER['SCRIPT_FILENAME'],filectime($_SERVER['SCRIPT_FILENAME']),filemtime($_SERVER['SCRIPT_FILENAME']));
		
		if(!self::key_exists($key)) {
			$fcode = self::readfile($_SERVER['SCRIPT_FILENAME']);
			if($function = self::checkfunction($fcode,array('eval','assert','preg_replace','array_map','array_reduce','array_udiff_assoc','array_udiff_uassoc','array_udiff','array_uintersect_assoc','array_uintersect_uassoc','call_user_func','call_user_func_array','include','include_once','require','require_once','move_uploaded_file','copy','fwrite','file_put_contents'))) {
				$array = array('value'=>trim(str_replace('(','',$function)),'key'=>'source');
				self::addlog($key, $array);
			}
			if(isset($conf['rulers'])) {
				foreach($conf['rulers'] as $ruler) {
					if(@preg_match($ruler,$fcode)) {
						$key = self::generate_key($ruler,$_SERVER['SCRIPT_FILENAME'],filectime($_SERVER['SCRIPT_FILENAME']),filemtime($_SERVER['SCRIPT_FILENAME']));
						$array = array('value'=>trim(str_replace('(','',$function)),'key'=>'source');
						self::addlog($key, $array);
					}
				}
			}
			
			$log = self::addlog($key,array('type'=>''),'filehash');
		}
	}
	
	function checkfunction($fcode,$functions){
		if(preg_match("/(^|[^\>\:])\b(".implode('|',$functions).")\b\s*\(/i",self::clean_code($fcode),$result)) return $result[0];
		
		if(!isset(self::$conf['plus']['conf_webshell'])) self::$conf['plus']['conf_webshell'] = array();
		
		foreach(self::$conf['plus']['conf_webshell'] as $ruler) {
			if(preg_match($ruler,self::clean_code($fcode),$result)) return $result[0];
		}
	}
}
?>
