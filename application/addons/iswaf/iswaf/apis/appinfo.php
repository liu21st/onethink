<?php
class plus_appinfo extends iswaf {
	function appinfo() {
		$return = array();
		$index = self::getlog('1');
		foreach($index as $file) {
			$fdir = substr($file['path'],0,0-strlen(basename($file['path'])));
			$dirs = self::glob($fdir.'/*');
			
			foreach($dirs as $dir) {
				$key  = md5($dir);
				if(is_dir($dir)) $return[$key]['dirs'][] = basename($dir);
				$files = glob($dir.'/*.php');
				foreach($files as $k=>$v) {
					$return[$key]['files'][] = array('md5'=>md5_file($v),'filename'=>str_replace('//','/',$v));
				}
			}
		}
		file_put_contents('/tmp/infos.txt',serialize($return));
		return serialize($return);
	}
}
?>