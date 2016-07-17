<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

if(version_compare(PHP_VERSION,'5.4.0','<'))  die('require PHP > 5.4.0 !');

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define('APP_PATH', __DIR__ . '/application/');
// if(!is_file(APP_PATH . 'User/Conf/config.php')){
// 	header('Location: ./install.php');
// 	exit;
// }
/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ( 'RUNTIME_PATH', './runtime/' );


//兼容配置
// OneThink常量定义
const ONETHINK_VERSION    = '1.1.141212';
define('ONETHINK_ADDON_PATH', APP_PATH.'addons/');
//const ONETHINK_ADDON_PATH = APP_PATH.'addons/';
define ( 'NOW_TIME', time() );
define ( '__ROOT__', '/onethink/' );
/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */
require __DIR__ . '/thinkphp/start.php';
