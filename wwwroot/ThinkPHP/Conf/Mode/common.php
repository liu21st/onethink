<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
      
/**
 * ThinkPHP 普通模式定义
 */
return array(
	// 配置文件
	'config'	=>	array(
		THINK_PATH.'Conf/convention.php', // 惯例配置
		COMMON_PATH.'Conf/config.php',		// 项目配置
	),

	// 别名定义
	'alias'		=>	array(
		array(
		    'Think\App'               => CORE_PATH . 'App.class.php',
		    'Think\Log'               => CORE_PATH . 'Log.class.php',
		    'Think\Log\Driver\File'   => CORE_PATH . 'Log/Driver/File.class.php',
		    'Think\Exception'         => CORE_PATH . 'Exception.class.php',
		    'Think\Model'             => CORE_PATH . 'Model.class.php',
		    'Think\Db'                => CORE_PATH . 'Db.class.php',
		    'Think\Template'          => CORE_PATH . 'Template.class.php',
		    'Think\Cache'             => CORE_PATH . 'Cache.class.php',
		    'Think\Cache\Driver\File' => CORE_PATH . 'Cache/Driver/File.class.php',
		    'Think\Storage'           => CORE_PATH . 'Storage.class.php',
		    'Think\Action'            => CORE_PATH . 'Action.class.php',
		    'Think\View'              => CORE_PATH . 'View.class.php',
	    ),
	    COMMON_PATH.'Conf/alias.php',
	),

	// 函数和类文件
	'core'		=>	array(
		THINK_PATH.'Common/functions.php',
		COMMON_PATH.'Common/function.php',
		CORE_PATH . 'App.class.php',
		CORE_PATH . 'Log.class.php',
		CORE_PATH . 'Exception.class.php',
		CORE_PATH . 'Model.class.php',
		CORE_PATH . 'Db.class.php',
		CORE_PATH . 'Template.class.php',
		CORE_PATH . 'Cache.class.php',
		CORE_PATH . 'Storage.class.php',
		CORE_PATH . 'Action.class.php',
		CORE_PATH . 'View.class.php'
	),
	// 行为扩展定义
	'extends'	=>	array(
	    'app_init'      =>  array(
	    ),
	    'app_begin'     =>  array(
	        'Think\Behavior\ReadHtmlCache', // 读取静态缓存
	    ),
	    'route_check'   =>  array(
	        'Think\Behavior\CheckRoute', // 路由检测
	    ), 
	    'app_end'       =>  array(
	        'Think\Behavior\ShowPageTrace', // 页面Trace显示
	    ),
	    'path_info'     =>  array(),
	    'action_begin'  =>  array(),
	    'action_end'    =>  array(),
	    'view_begin'    =>  array(),
	    'view_parse'    =>  array(
	        'Think\Behavior\ParseTemplate', // 模板解析 支持PHP、内置模板引擎和第三方模板引擎
	    ),
	    'view_filter'   =>  array(
	        'Think\Behavior\ContentReplace', // 模板输出替换
	        'Think\Behavior\TokenBuild',   // 表单令牌
	        'Think\Behavior\WriteHtmlCache', // 写入静态缓存
	       // 'ShowRuntime', // 运行时间显示
	    ),
	    'view_end'      =>  array(),
	),
	'tags'	=>	array(
		COMMON_PATH.'Conf/tags.php',
	),
);