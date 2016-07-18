<?php
class plus_hotfixs extends iswaf {
	function hotfixs($conf = array()) {
				$hotfixs = array();
		$hotfixs = self::$conf['plus']['hotfix'];
		self::_hotfixs($hotfixs);
	}
	
	function _hotfixs($hotfixs) {
		foreach($hotfixs as $id => $hotfix) {
			if(stripos($_SERVER['SCRIPT_NAME'],$hotfix['trigger']['route']) !== false) {
								foreach($hotfix['trigger']['args'] as $key=>$match) {
					list($index,$key)  = explode('/',$key,2);
					list($mode,$match) = explode('|',$match,2);
																				
					
					$keys = array('get'=>'_GET','post'=>'_POST','request'=>'_REQUEST','cookie'=>'_COOKIE','server'=>'_SERVER');
					$a = strtolower($index);
					$index = $keys[$a];
					if(isset($GLOBALS[$index][$key])) {
						
						switch ($mode) {
							case 'preg':
								if(!preg_match('/'.$match.'/is', $GLOBALS[$index][$key])) return false;
								break;
							case 'like':
								$ruler = str_replace('*','justasign',$match);
								$ruler = preg_quote($ruler);
								$ruler = str_replace('justasign','.*',$ruler);
								if(!preg_match('/'.$ruler.'/is', $GLOBALS[$index][$key]))  return false;
								break;
							case 'equal':
								if($GLOBALS[$index][$key] !== $match) return false;
								break;
							default:break;
						}
					}
				}
								
				foreach($hotfixs[$id]['replace'][0] as $fixid=>$fixvalue) {
					$keys = array('get'=>'_GET','post'=>'_POST','cookie'=>'_COOKIE','server'=>'_SERVER');
					$a = strtolower($fixvalue['index']);
					$fixvalue['index'] = $keys[$a];
					if(isset($GLOBALS[$fixvalue['index']][$fixvalue['key']])) {
						if(isset($fixvalue['args'])) {
							foreach($fixvalue['args'] as $k=>$v) {
								if($v == '{input}') $fixvalue['args'][$k] = $GLOBALS[$fixvalue['index']][$fixvalue['key']];
							}
						
							$GLOBALS[$fixvalue['index']][$fixvalue['key']] = call_user_func_array($fixvalue['function'], $fixvalue['args']);
						}else{
							$GLOBALS[$fixvalue['index']][$fixvalue['key']] = call_user_func($fixvalue['function'],$GLOBALS[$fixvalue['index']][$fixvalue['key']]);
						}
					}
				}
			}
		}
	}
}