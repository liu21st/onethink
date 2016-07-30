<?php
class plus_callback_xss extends iswaf {
	function callback_xss($conf = array()){
		$temp = strtoupper(rawurldecode(rawurldecode($_SERVER['REQUEST_URI'])));
		if(strpos($temp, '>') !== false && strpos($temp, '"') !== false || stripos($temp,'<')!== false || strpos($temp, 'CONTENT-TRANSFER-ENCODING') !== false) {
			$array = array('key'=>'request','value'=>$_SERVER['REQUEST_URI']);
			$key = self::generate_key('getxss'.$_SERVER['REQUEST_URI']);
			if($conf['mode'] !== 'silent') {
				$_GET = self::cleanget($_GET);
				$_REQUEST = self::cleanget($_REQUEST);
			}
			if(isset($conf['rulers'])) {
				foreach($conf['rulers'] as $id=>$ruler) {
					if(@preg_match($ruler,$temp) !==false) {
						$array = array('key'=>'request','value'=>$_SERVER['REQUEST_URI']);
						$key = self::generate_key('getxss'.$_SERVER['REQUEST_URI'].$id);
					}
				}
			}
			self::addlog($key,$array);
		}
	}
	function cleanget($string) {
			if(is_array($string)) {
				foreach($string as $key => $val) {
					$string[$key] = self::cleanget($val);
				}
			} else {
				$string = htmlspecialchars($string);
			}
		return $string;
	}
}
?>