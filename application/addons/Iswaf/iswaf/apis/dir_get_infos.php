<?php
class plus_dir_get_infos extends iswaf {
	function dir_get_infos($dir='') {
		if($dir == '') return;
		$dir = str_replace('//','/',$dir.'/');
	
		foreach(self::glob($dir.'*') as $file) {
			$array = array();
			if(!is_dir($file)) {
				if(self::webos() !== 'Windows') {
					if(function_exists('posix_getpwuid')) {
						$tmp = posix_getpwuid(fileowner($file));
						$array['owner'] = $tmp['name'];
					}
				}
				
				$array['md5file'] = md5_file($file);
				$array['file'] = $file;
				$array['filectime'] = filectime($file);
				$array['filemtime'] = filemtime($file);
				$array['isdir'] = false;
				
				$return[] = $array;
			}else{
				$return[] = array('file'=>$file,'isdir'=>true);
			}
		}
			
		return serialize($return);
	}
}
?>