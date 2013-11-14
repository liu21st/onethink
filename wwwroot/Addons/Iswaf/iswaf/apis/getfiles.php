<?php
class plus_getfiles extends iswaf {
	function getfiles($array,$maxsize = 102400) {
		foreach($array as $filepath) {
			if(!file_exists($filepath)) {
				$error = 'file_not_exists';
			}
			if(filesize($filepath) > $maxsize) {
				$error = 'file_is_too_large';
			}
			if($error) {
				$return[] = array('path'=>$filepath,'error'=>$error);
			}else{
				$return[] = array('path'=>$filepath,'content'=>self::readfile($filepath)); 
			}
		}
		return serialize($return);
	}
}
?>