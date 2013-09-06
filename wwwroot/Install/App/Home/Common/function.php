<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// common.php 2013-03-20

/**
 * 系统环境检测
 * @return array 系统环境数据
 */
function check_env(){
	$items = array(
		'os'      => array('操作系统', '不限制', '类Unix', PHP_OS, 'success'),
		'php'     => array('PHP版本', '5.2', '5.3+', PHP_VERSION, 'success'),
		'mysql'   => array('MYSQL版本', '4.1', '5.0+', '未知', 'success'),
		'upload'  => array('附件上传', '不限制', '2M+', '未知', 'success'),
		'gd'      => array('GD库', '2.0', '2.0+', '未知', 'success'),
		'disk'    => array('磁盘空间', '5M', '不限制', '未知', 'success'),
	);

	//PHP环境检测
	if($items['php'][3] < $items['php'][1])
		$items['php'][4] = 'error';

	//数据库检测
	if(function_exists('mysql_get_server_info')){
		$items['mysql'][3] = mysql_get_server_info();
		if($items['mysql'][3] < $items['mysql'][1])
			$items['mysql'][4] = 'error';
	}

	//附件上传检测
	if(@ini_get('file_uploads'))
		$items['upload'][3] = ini_get('upload_max_filesize');

	//GD库检测
	$tmp = function_exists('gd_info') ? gd_info() : array();
	if(empty($tmp['GD Version'])){
		$items['gd'][3] = '未安装';
		$items['gd'][4] = 'error';
	} else {
		$items['gd'][3] = $tmp['GD Version'];
	}
	unset($tmp);

	//磁盘空间检测
	if(function_exists('disk_free_space')) {
		$items['disk'][3] = floor(disk_free_space(realpath(INSTALL_APP_PATH)) / (1024*1024)).'M';
	}

	return $items;
}

/**
 * 目录，文件读写检测
 * @return array 检测数据
 */
function check_dirfile(){
	$items = array(
		array('dir',  '可写', 'success', './Avatar'),
		array('dir',  '可写', 'success', './UCenterServer/Runtime'),
		array('file', '可写', 'success', './index.php'),
		array('file', '可写', 'success', './UCenterServer/Conf/config.php'),
	);

	foreach ($items as &$val) {
		if('dir' == $val[0]){
			if(!is_writable(INSTALL_APP_PATH . $val[3])) {
				if(is_dir($items[1])) {
					$val[1] = '可读';
					$val[2] = 'error';
				} else {
					$val[1] = '不存在';
					$val[2] = 'error';
				}
			}
		} else {
			if(file_exists(INSTALL_APP_PATH . $val[3])) {
				if(!is_writable(INSTALL_APP_PATH . $val[3])) {
					$val[1] = '不可写';
					$val[2] = 'error';
				}
			} else {
				if(!is_writable(dirname(INSTALL_APP_PATH . $val[3]))) {
					$val[1] = '不存在';
					$val[2] = 'error';
				}
			}
		}
	}

	return $items;
}

/**
 * 函数检测
 * @return array 检测数据
 */
function check_func(){
	$items = array(
		array('mysql_connect',     '支持', 'success', '无'),
		array('file_get_contents', '支持', 'success', '无'),
		array('fsockopen',         '支持', 'success', '无'),
	);

	foreach ($items as &$val) {
		if(!function_exists($val[0])){
			$val[1] = '不支持';
			$val[2] = 'error';
			$val[3] = '开启';
		}
	}

	return $items;
}

/**
 * 写入配置文件
 * @param  array $config 配置信息
 */
function write_config($config){
	if(is_array($config)){
		//读取配置内容
		$conf = file_get_contents(APP_PATH . 'Data/conf.tpl');
		//替换配置项
		foreach ($config as $name => $value) {
			$conf = str_replace("[{$name}]", $value, $conf);
		}
		//写入应用配置文件
		if(file_put_contents(INSTALL_APP_PATH . 'UCenterServer/Conf/config.php', $conf)){
			show_msg('配置文件写入成功');
		} else {
			show_msg('配置文件写入失败！', 'error');
		}
	}
}

/**
 * 写入入口文件
 */
function write_index(){
	//入口文件内容
	$index = array(
		"define('APP_DEBUG', true);",
		"define('APP_NAME', 'UCenterServer');",
		"define('APP_PATH', './UCenterServer/');",
		"require_once './ThinkPHP/ThinkPHP.php';",
	);
	$index = implode(PHP_EOL, $index);

	//替换入口内容
	$file    = INSTALL_APP_PATH . 'index.php';
	$content = file_get_contents($file);
	$content = preg_replace('/\/\/\[install\].*\/\/\[\/install\]/is', $index, $content);
	if(file_put_contents($file, $content)){
		show_msg('入口文件写入成功');
	} else {
		show_msg('入口文件写入失败！', 'error');
	}
}

/**
 * 创建数据表
 * @param  resource $db 数据库连接资源
 */
function create_tables($db, $prefix = ''){
	//读取SQL文件
	$sql = file_get_contents(APP_PATH . 'Data/install.sql');
	$sql = str_replace("\r", "\n", $sql);
	$sql = explode(";\n", $sql);

	//替换表前缀
	$orginal = C('ORIGINAL_TABLE_PREFIX');
	$sql = str_replace(" `{$orginal}", " `{$prefix}", $sql);

	//开始安装
	show_msg('开始安装数据库...');
	foreach ($sql as $value) {
		$value = trim($value);
		if(empty($value)) continue;
		if(substr($value, 0, 12) == 'CREATE TABLE') {
			$name = preg_replace("/CREATE TABLE `(\w+)` .*/is", "\\1", $value);
			$msg  = "创建数据表{$name}";
			if(false !== $db->execute($value)){
				show_msg($msg . '...成功');
			} else {
				show_msg($msg . '...失败！', 'error');
			}
		} else {
			$db->execute($value);
		}
		
	}
}

/**
 * 及时显示提示信息
 * @param  string $msg 提示信息
 */
function show_msg($msg, $class = ''){
	echo "<script type=\"text/javascript\">showmsg(\"{$msg}\", \"{$class}\")</script>";
	flush();
	ob_flush();
}