<?php
class plus_xssclean extends iswaf {
	function xssclean($conf) {
		$find =  array('onabort','onactivate','onafterprint','onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut','onbeforedeactivate',
						'onbeforeeditfocus','onbeforepaste','onbeforeprint','onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange','onchange',
						'onclick','oncontextmenu','oncontrolselect','oncopy','oncut','ondataavailable','ondatasetchanged','ondatasetcomplete','ondblclick',
						'ondeactivate','ondrag','ondragend','ondragenter','ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
						'onfilterchange','onfinish','onfocus','onfocusin','onfocusout','onhelp','onkeydown','onkeypress','onkeyup','onlayoutcomplete',
						'onload','onlosecapture','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmouseup','onmousewheel',
						'onmove','onmoveend','onmovestart','onpaste','onpropertychange','onreadystatechange','onreset','onresize','onresizeend','onresizestart',
						'onrowenter','onrowexit','onrowsdelete','onrowsinserted','onscroll','onselect','onselectionchange','onselectstart','onstart','onstop',
						'onsubmit','onunload','javascript','script','eval','behaviour','expression','style','class');
		$evilTags=array('script','object','class');

		if(stripos(self::$gpc['post'],'<')!==false){
			$isEvil=false;
			if(preg_match('/<[\/\!\?\s]*?('.implode('|',$evilTags).')/i',self::$gpc['post'])){
				$isEvil=true;
			}
			if(!$isEvil){
				if(preg_match('/<.*?('.implode('|',$find).')/is',self::$gpc['post'])){
					$isEvil=true;
				}
			}
			if($isEvil){
				self::$key.=implode('/',array_keys($_POST));
				$key = self::generate_key(self::$key, self::$conf['remote_ip'],'xssclean');
				$array = array('key'=>'request','value'=>self::$gpc['post']);
				self::addlog($key, $array);
				$_POST = self::do_xss_clean($_POST,$find);
				$_REQUEST = self::do_xss_clean($_REQUEST,$find);
			}
		}
	}
	function do_xss_clean($input,$find) {
		if(is_array($input)) {
			foreach($input as $k=>$v) $input[$k] = self::do_xss_clean($v,$find);
		}else{
			foreach($find as $k=>$v) {
				if(stripos($input,$k) !== false){
					$input = self::clean_evil_html($input);
					break;
				}
			}
		}
		return $input;
	}
	function clean_evil_html($html) {
		$searchs[] = '<';
		$replaces[] = '&lt;';
		$searchs[] = '>';
		$replaces[] = '&gt;';
		$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed';		$skipkeys = array('onabort','onactivate','onafterprint','onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut','onbeforedeactivate',
						'onbeforeeditfocus','onbeforepaste','onbeforeprint','onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange','onchange',
						'onclick','oncontextmenu','oncontrolselect','oncopy','oncut','ondataavailable','ondatasetchanged','ondatasetcomplete','ondblclick',
						'ondeactivate','ondrag','ondragend','ondragenter','ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
						'onfilterchange','onfinish','onfocus','onfocusin','onfocusout','onhelp','onkeydown','onkeypress','onkeyup','onlayoutcomplete',
						'onload','onlosecapture','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmouseup','onmousewheel',
						'onmove','onmoveend','onmovestart','onpaste','onpropertychange','onreadystatechange','onreset','onresize','onresizeend','onresizestart',
						'onrowenter','onrowexit','onrowsdelete','onrowsinserted','onscroll','onselect','onselectionchange','onselectstart','onstart','onstop',
						'onsubmit','onunload','javascript','script','eval','behaviour','expression','style','class');
		
		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);
		if($ms[1]) {
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$searchs[] = "&lt;".$value."&gt;";
				$value = htmlspecialchars($value);
				$value = str_replace(array('\\','/*'), array('.','/.'), $value);
				$skipstr = implode('|', $skipkeys);
				$value = preg_replace(array("/($skipstr)/i"), '.', $value);
				if(!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
					$value = '';
				}
				$replaces[] = empty($value)?'':"<".str_replace('&quot;', '"', $value).">";
			}
		}
		$html = str_replace($searchs, $replaces, $html);
		return $html;
	}
}