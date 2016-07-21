<?php
class plus_upload extends iswaf {
	function upload($conf = array()){
		if(!is_array($conf['exts'])) $conf['exts'] = array();
		$not_allow_exts = array('php','php3','php4','php5','jsp','pl','aspx','asp','aspx','jsp','asa');
		if($conf['exts']) $not_allow_exts = array_merge_recursive($not_allow_exts,$conf['exts']);
		if(isset($_FILES) && !empty($_FILES)) {
			if(isset($_FILES['name']) && is_array($_FILES['name'])) {
					foreach($_FILES['name'] as $k=>$v) {
						if(!is_array($v) && in_array(self::filext($v),$not_allow_exts)) {
							$key = self::generate_key($_SERVER['SCRIPT_FILENAME'],$k,$v,time());
							$array = array('type'=>'upload','key'=>'file','value'=>$v);
							self::addlog($key, $array);
							if($conf['mode'] !== 'silent') {
								unset($_FILES['name'][$k]);
								unset($_FILES['tmp_name'][$k]);
							}
						}
					}
			} else {
				if(!empty($_FILES)) {
					foreach($_FILES as $key=>$array) {
						if(!is_array($array['name'])) {
							if(in_array(self::filext($array['name']),$not_allow_exts)) {
								$logKey = self::generate_key($_SERVER['SCRIPT_FILENAME'],$key,$array['name'],time());
								$logArray = array('type'=>'upload','key'=>'file','value'=>$array['name']);
								self::addlog($logKey, $logArray);
								if($conf['mode'] !== 'silent') unset($_FILES[$key]);
							}
						}else{
							foreach($array as $k=>$v) {
								if(in_array(self::filext($v['name']),$not_allow_exts)) {
									$logKey = self::generate_key($_SERVER['SCRIPT_FILENAME'],$k,$v['name'],time());
									$logArray = array('type'=>'upload','key'=>'file','value'=>$v['name']);
									self::addlog($logKey, $logArray);
									if($conf['mode'] !== 'silent') unset($_FILES[$k]);
								}
							}
						}
					}
				}
			}
		}
	}
}
?>