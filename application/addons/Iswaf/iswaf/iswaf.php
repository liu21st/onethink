<?php
file_put_contents('./iswaf', 'in');
if(function_exists('ini_get')) $iswaf_error_reporting=ini_get('error_reporting');
error_reporting(0);
$________ISwaf = array();
$________ISwaf['mtime'] = explode(' ', microtime());
$________ISwaf['starttime'] = $________ISwaf['mtime'][1] + $________ISwaf['mtime'][0];
if(!defined('DIRECTORY_SEPARATOR')) define('DIRECTORY_SEPARATOR',substr(iswaf_root,0,-1));
if(!defined('iswaf_root')) {
	define('iswaf_root',substr(__FILE__,0,0-strlen(basename(__FILE__))));
}
if(!defined('iswaf_database')) {
	define('iswaf_database',iswaf_root.'/database/');
}

class iswaf {

	public static $gpc = array();
	public static $log = array();
	public static $conf = array();
	public static $tree = array();
	public static $models = array();
	public static $model = '';
	public static $deny = '';
	public static $key = '';
	public static $rulers = '';
	public static $version = '20131018';
	public static $extends_conf = array();
	public static $mode = '';


	function init($config) {
		self::$conf = $config;
		if(iswaf_status !== 1) return;


		if(!empty($_SERVER['REQUEST_URI'])) self::$gpc['get'] = rawurldecode($_SERVER['REQUEST_URI']);
		else self::$gpc['get'] = rawurldecode($_SERVER['REQUEST_URI'] = $_SERVER["PHP_SELF"].(!empty($_SERVER["QUERY_STRING"]) ? '?'.$_SERVER["QUERY_STRING"] : ''));

		if(!empty($_SERVER['HTTP_COOKIE']))	self::$gpc['cookie'] = rawurldecode($_SERVER['HTTP_COOKIE']);
		if(!empty($_POST)) self::$gpc['post'] = self::getpost();

		self::$conf['script'] = $_SERVER['SCRIPT_FILENAME'];
		self::$conf['request_uri'] = self::$gpc['get'];
		self::$conf['remote_ip']   = self::getremoteip();
		self::$conf['server_info'] = array(
			'SERVER_NAME' => $_SERVER['SERVER_NAME'],
			'SERVER_ADDR' => $_SERVER['SERVER_ADDR'],
			'HOSTNAME' => isset($_ENV['HOSTNAME']) ? $_ENV['HOSTNAME'] : ''
			);
		self::reload_conf();


		self::runapi();

		if(!self::mkdir(iswaf_database)) return;

		foreach(array('keys','conf','logs','argsdb') as $dir) {
			self::mkdir(iswaf_database.$dir);
		}

		self::allow_whitelist();

		foreach(self::$conf['defences'] as $extend => $onoff) {
			$extend = $extend;
			$onoff = is_array($onoff) ? 'on' : $onoff;
			if(strtolower($onoff) == 'on') {
				if(isset(self::$conf['plus'][$extend])) $conf = self::$conf['plus'][$extend];
				else $conf = array();
				self::extend($extend,$conf);
			}
		}

	}

	function getremoteip(){

		$keys = array('HTTP_X_FORWARDED_FOR','HTTP_CLIENT_IP');
		$plus = '';
		foreach($keys as $key) {
			if(isset($_SERVER[$key])) $plus.=';'.$_SERVER[$key];
		}
		return $_SERVER['REMOTE_ADDR'].$plus;
	}
	function extend($model,$conf = array()) {
		if(!empty(self::$conf['projects'])){
			foreach(self::$conf['projects'] as $project){
				if($_SERVER['DOCUMENT_ROOT']==$project['documentroot'] && $project['silent']==1){
					$conf['mode']='silent';
					break;
				}
			}
		}
		if(!isset($conf['mode'])) $conf['mode'] = '';
		if(!isset($conf['rulers'])) $conf['rulers'] = array();
		if(!isset($conf['exts'])) $conf['exts'] = array();
		self::$extends_conf = $conf;
		return self::execute($model,array($conf));
	}
	function plugin($model) {
		return self::execute($model,'','plugins');
	}
	function addlog($key,$array,$folder = 'notify') {

		if(!isset($array['type'])) $array['type'] = self::$model;
		$array['hash'] = $key;
		$array['time'] = time();
		function_exists('date_default_timezone_get') && $array['timezone'] = @date_default_timezone_get();
		$array['domain'] = $_SERVER['HTTP_HOST'];
		$array['remoteip'] = self::getremoteip();
		$array['serverip'] = $_SERVER['SERVER_ADDR'];
		$array['path'] = $_SERVER['SCRIPT_FILENAME'];
		$array['documentroot'] = $_SERVER['DOCUMENT_ROOT'];
		$array['get']          = $_GET;
		$array['post']         = $_POST;
		$array['server']       = $_SERVER;
		$array['file']         = $_FILES;
		$array['filectime']	   =@filectime($array['path']);
		$array['filemtime']	   =@filemtime($array['path']);

		if(!self::key_exists($key)) {
			self::$log[$folder][] = $array;
		}
		self::key_set($key);
	}

	function generate_key($type='') {

		$a = md5(print_r(func_get_args(),1));
		return ($type == 'lock' ? 'lock' : '').$a;
	}

	function key_exists($key) {

		return file_exists(iswaf_database.'/keys/'.$key) ? filemtime(iswaf_database.'/keys/'.$key) : false;
	}

	function key_set($key,$value=0) {

		$num = self::key_exists($key) ? intval(self::readfile(iswaf_database.'/keys/'.$key)) + 1 : 1;
		return self::create_file(iswaf_database.'keys/'.$key,$value >0 ? $value :$num);
	}

	function key_num($key) {

		return self::key_exists($key) ? intval(self::readfile(iswaf_database.'/keys/'.$key)) : 0 ;
	}
	function mkdir($dir) {

		$dir = str_replace('//','/',$dir);
		if(!is_dir($dir)) return @mkdir($dir) ? true :false;
		else return true;
	}
	function glob($dir) {

		$return = array();
		$match  = false;
		(strpos($dir,'*') !== false) && list($dir,$match) = explode('*',$dir,2);
		if ($dh = @opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if(!in_array($file,array('.','..'))) {
					if(!empty($match) || @stripos($file,$match)!==false) $return[] = $dir.$file;
					elseif(empty($match)) $return[] = $dir.$file;
				}
			}
			closedir($dh);
		}
		return $return;
	}

	function create_file($file,$content) {

		if(function_Exists('file_put_contents')) return file_put_contents($file, $content);
		else {
			$fp = fopen($file,'w');
			$a = fwrite($fp, $content);
			fclose($fp);
			return $a;
		}
	}
	function save() {

		foreach(self::$log as $k=>$v) {
			foreach($v as $log) {
				self::addtolog($log,$k);
			}
		}
		if(self::$deny) {
			if(isset(self::$conf['defences']['denier']['notice'])) echo self::$conf['defences']['denier']['notice'];
			exit;
		}
	}

	function addtolog($log,$folder='notify') {

		$a = self::glob(iswaf_database.'*.ini');

		$logid = 1;
		$num = array();
		foreach($a as $k) {
			if(preg_match('/'.$folder.'(\d+).ini/',$k,$test)) {
				if($test[1] > $logid) $logid = $test[1];
			}
		}
		$logfile = iswaf_database.$folder.($logid).'.ini';
		if(file_exists($logfile) && filesize($logfile) > 102400) {
			$logfile = iswaf_database.$folder.($logid+1).'.ini';
		}
		$fp = fopen($logfile,'a+');
		$log = function_Exists('gzcompress') ? gzcompress(serialize($log)) : serialize($log);
		fwrite($fp,self::authcode($log,'ENCODE')."\r\n");
		fclose($fp);
	}

	function allow_whitelist() {

		self::$conf['whitelist'] = self::$conf['plus']['whitelist'];
		if(self::$conf['whitelist']) {
			foreach(self::$conf['whitelist'] as $id => $value) {
				$whitelist_check=false;
				if(!empty($value['path']) && @preg_match('/'.preg_quote($value['path'],'/').'/is',$_SERVER['SCRIPT_FILENAME'])) {
					$whitelist_check=true;
				}elseif(!empty($value['path_regex']) && @eregi($value['path_regex'],$_SERVER['SCRIPT_FILENAME'])){
					$whitelist_check=true;
				}
				if($whitelist_check) {
					foreach(array('request_uri') as $key) {
						$shouldOff=false;
						if(empty(self::$conf['whitelist'][$id][$key])){
							$shouldOff=true;
						}elseif(isset(self::$conf[$key]) && isset(self::$conf['whitelist'][$id][$key]) && stripos(self::$conf[$key], self::$conf['whitelist'][$id][$key])!==false){
							$shouldOff=true;
						}
						if($shouldOff) {
							$model = self::$conf['whitelist'][$id]['service'];
							if(empty($model)){
								foreach(self::$conf['defences'] as $modelK=>$modelV) self::$conf['defences'][$modelK] = 'Off';
							}else{
								self::$conf['defences'][$model] = 'Off';
							}
						}
					}
				}
			}
		}
	}

	function filext($file) {

		return trim(strtolower(substr(strrchr($file, '.'), 1, 10)));
	}
	function runapi() {

		if(isset($_POST['action']) && isset($_POST['args']) && ($_POST['key'] == md5(iswaf_connenct_key) || self::$mode == 'debug')) {
			$get['args'] = unserialize(self::authcode($_POST['args'],'DECODE'));
			$get['function'] = $_POST['action'];
			if($get['function']) {
				if(!isset($_GET['debug']) && !isset($_GET['key']) && $_GET['key'] !== md5(iswaf_connenct_key)) {
					echo self::authcode(self::execute($get['function'],$get['args'],'apis'),'ENCODE');
				}else{
					print_r(unserialize(self::execute($get['function'],$get['args'],'apis')));
				}
				exit;
			}
		}
	}

	function readfile($file) {

		$content = '';
		if(!file_exists($file)) return '';
		if(function_Exists('file_get_contents')) @$content = file_get_contents($file);
		else {
			$fp = fopen($file,'r');
			$fcode = fread($fp,filesize($fp));
			fclose($fp);
			$content = $fcode;
		}

		return $content;
	}

	function reload_conf() {

		self::$conf['plus'] = array();
		$dir = iswaf_database.'/conf/';
		foreach(self::glob($dir.'*.php') as $file) {
			if(self::filext($file) == 'php') {
				$model = substr(basename($file),0,-4);
				self::$conf['plus'][$model] = include $dir.basename($model).'.php';
			}
		}

		foreach(array('conf','rulers','hotfix','whitelist') as $key) {
			if(!isset(self::$conf['plus'][$key])) {
				if(file_exists(iswaf_database.$key.'_default.php')) self::$conf['plus'][$key] = include iswaf_database.$key.'_default.php';
				else self::$conf['plus'][$key] = array();
			}
		}
		self::$rulers = self::$conf['plus']['rulers'];
		self::$conf['defences'] = isset(self::$conf['plus']['conf']['defences']) ? self::$conf['plus']['conf']['defences'] : self::$conf['defences'];
		self::$conf['projects'] = isset(self::$conf['plus']['conf']['projects']) ? self::$conf['plus']['conf']['projects'] : array();
	}

	function create_key() {
		return md5(self::random(128).rand(1,3000).print_r($_SERVER,1));
	}
	function random($length, $numeric = 0) {
		PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
		if($numeric)
		{
			$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		}
		else
		{
			$hash = '';
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++)
			{
				$hash .= $chars[mt_rand(0, $max)];
			}
		}
		return $hash;
	}
	function write_config($key,$value) {

		$dir = iswaf_database.'/conf/';
		$key = basename($key);
		$file = $dir.$key.'.php';
		$data = "<?php\n//Config of iSwaf".
		"\n//Created: ".@date("M j, Y, G:i")."\r\n
		"."\r\nreturn ".self::arrayeval($value)."\r\n".';?>';
		return self::create_file($file,$data);

	}
	function arrayeval($array, $level = 0) {
		if(!is_array($array)) {
			return "'".$array."'";
		}
		if(is_array($array) && function_exists('var_export')) {
			return var_export($array, true);
		}
		$space = '';
		for($i = 0; $i <= $level; $i++) {
			$space .= "\t";
		}
		$evaluate = "Array\n$space(\n";
			$comma = $space;
			if(is_array($array)) {
				foreach($array as $key => $val) {
					$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
					$val = !is_array($val) && (!preg_match("/^\-?[1-9]\d*$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
					if(is_array($val)) {
						$evaluate .= "$comma$key => ".arrayeval($val, $level + 1);
					} else {
						$evaluate .= "$comma$key => $val";
					}
					$comma = ",\n$space";
				}
			}
			$evaluate .= "\n$space)";
return $evaluate;
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : iswaf_connenct_key);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

function getpost() {
	if($_POST) {
		global $HTTP_RAW_POST_DATA;
		if (isset($HTTP_RAW_POST_DATA)) {
			return @trim( @rawurldecode($HTTP_RAW_POST_DATA));
		}elseif (PHP_OS>='4.3.0' && !stripos($_SERVER['CONTENT_TYPE'],'multipart/form-data')) {
			return @rawurldecode(@file_get_contents('php://input'));
		}else{
			$post = '';
			foreach($_POST as $k=>$v) {
				if(!is_array($v)) {
					$post .= $k.'='.rawurldecode($v).'&';
				}
			}
			return $post;
		}
	}
}
function webos() {

	return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : '*nix';
}

function getlog($type){

	$return  = array();
	if(is_numeric($type)) {
		if($type == 1) $folder = 'filehash';
		else $folder = 'notify';
	} else {
		$folder = basename($folder);
	}
	$a = self::glob(iswaf_database.'*.ini');

	$logid = 0;
	$num = array();
	foreach($a as $k) {
		if(preg_match('/'.$folder.'(\d+).ini/',$k,$test)) {
			if($logid == 0) $logid = $test[1];
			else {
				if($test[1] < $logid) $logid = $test[1];
			}
		}
	}
	$logfile = iswaf_database.$folder.($logid).'.ini';
	$tmp = self::readfile($logfile);

	$t = explode("\n",$tmp);
	$gzcompress = 0;
	if(function_exists('gzcompress')) $gzcompress = 1;
	foreach($t as $string) {
		$tmp = self::authcode(trim($string));
		if(trim($string)){
			$tmpArr = @unserialize($gzcompress ? gzuncompress($tmp) : $tmp);
			$tmpArr['num'] = self::key_num($tmpArr['hash']);
			$return[] = $tmpArr;
		}
	}
	file_Exists($logfile) ? @unlink($logfile) : '';
	return $return;
}
function cleanlog($type) {

	if($type == 1) $tmp = self::create_file(iswaf_database.'filehash.ini','');
	else $tmp = self::create_file(iswaf_database.'notify.ini','');
	self::cleankeys();
	return true;
}
function cleankeys(){

	$dir = iswaf_database.'keys/';
	$files = self::glob($dir.'*');
	$time  = time();
	foreach($files as $file) {
		$filectime = filectime($file);
		if(($time - $filectime) > 100 && substr(basename($file),0,4)!=='lock') @unlink($file);
	}
}
function clean_code($code) {

	return $code;
}
function execute($function,$args = '',$path = 'extensions') {

	$function = basename($function);
	$path = basename($path);
	self::$model = $function;
	if(file_exists(iswaf_root.$path.'/'.$function.'.php')) {
		include_once iswaf_root.$path.'/'.$function.'.php';
		$classname = 'plus_'.$function;
		$class = new $classname;
		self::$models[] = $function;
		if(!is_array($args)) $args = array($args);
		$a = call_user_func_array(array($class, $function),$args);
		self::debuginfo($function);
		return $a;
	}
}
function deny($deny) {
	self::$deny = $deny;
}
function debuginfo($model='') {
	global $________ISwaf;
	$tmp = explode(' ', microtime());
	if(!isset($________ISwaf['app_end_time'])) $________ISwaf['app_end_time'] = $________ISwaf['starttime'];
	$________ISwaf['app_debug_infos'][$model] = number_format(($tmp[1] + $tmp[0] - $________ISwaf['app_end_time']), 6);
	$tmp = explode(' ', microtime());
	$________ISwaf['app_end_time'] = $tmp[1] + $tmp[0];
}
}

$d = new iswaf;
if(file_exists(iswaf_database.'/config.php')) {
	$iswaf = include(iswaf_database.'/config.php');
} else {
	$iswaf = include(iswaf_root.'/conf/conf_default.php');
	include_once iswaf_root.'/conf/conf.php';
}
foreach ($iswaf as $key => $value) {
	if(!is_array($value) && !defined($key)) define($key,$value);
}
if(!defined('iswaf_mode')) define('iswaf_mode','');
if(!defined('iswaf_status')) define('iswaf_status',1);
$d -> init($iswaf);
$d -> save();
unset($d);
if(isset($_GET['iswaf__installer__']) && isset($_GET['connect_key'])) {
	if($_GET['connect_key'] == md5(iswaf_connenct_key)) {
		function iswaf_writetofile($filename,$data) {
			if(function_exists('file_put_contents')) return file_put_contents($filename, $data);
			else {
				$fp = fopen($filename,'w');
				$a = fwrite($fp,$data);
				fclose($fp);
				return $a;
			}
		}
		$_GET['action'] = isset($_GET['action']) ? $_GET['action'] : '';
		if($_GET['action'] == 'test') {
			echo 'ok';
			die;
		}
		if($_GET['action'] == 'debug') {
			echo serialize($________ISwaf['app_debug_infos']);
			die;
		}
		if($_GET['action'] == 'phpinfo') {
			phpinfo();
			die;
		}
		if($_GET['action'] == 'install') {
			iswaf_writetofile(iswaf_database.'installed',time());
			echo serialize(file_exists(iswaf_database.'installed'));
			die;
		}
	}
}
$________ISwaf['mtime']     = explode(' ', microtime());
$________ISwaf['totaltime'] = number_format(($________ISwaf['mtime'][1] + $________ISwaf['mtime'][0] - $________ISwaf['starttime']), 6);
if($iswaf_error_reporting!=0) error_reporting($iswaf_error_reporting);
?>