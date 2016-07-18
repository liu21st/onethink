<?php
class plus_dir_grep extends iswaf {
	function dir_grep($path='',$type='*.php',$ruler='') {
		if(!$path) return;
		$return = array();
		$path = str_replace('//','/',$path.'/');
		$tmp  = self::glob($path.$type);
		foreach($tmp as $filename) {
			$fcode = self::readfile($filename);
			if(preg_match_all($ruler,self::clean_code($fcode),$a)) {
				$return[] = array('filename'=>$filename,'point'=>count($a[0]),'codes'=>$a[0]);
			}
		}
		return serialize($return);
	}
}
?>