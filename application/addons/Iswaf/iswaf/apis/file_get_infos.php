<?php
class plus_file_get_infos extends iswaf {
	function file_get_infos($file) {
		$return = array('file_content'=>self::readfile($file));
		if(self::webos() !== 'Windows') {
			if(function_exists('posix_getpwuid')) {
				$tmp = posix_getpwuid(fileowner($file));
				$return['owner'] = $tmp['name'];
			}
		}
		$return['filename'] = $file;
		$return['filectime'] = filectime($file);
		$return['filemtime'] = filemtime($file);
		$return['md5file'] = md5_file($file);
		
		return serialize($return);
	}
}
?>