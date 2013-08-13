<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return array(
    'URL_MODEL'            => 2, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
    'TAGLIB_PRE_LOAD'   =>  'html',
    'DEFAULT_THEME'     =>  'default',
    'TAGLIB_BUILD_IN'   =>  'attr,cx',
	'SHOW_PAGE_TRACE'	=>	1,
	'DB_TYPE'           => 'pdo',
    'DB_DSN'    => 'mysql:host=127.0.0.1;dbname=thinkadmin;charset=UTF-8',
	'DB_HOST'           => '127.0.0.1',
	'DB_NAME'           => 'thinkadmin',
	'DB_USER'           => 'root',
	'DB_PWD'            => '',
	'DB_PORT'           => '3306',
	'DB_PREFIX'         => 'think_',
	'DEFAULT_THEME'         => 'default',	// 默认模板主题名称
	'USER_AUTH_ON'      => true,
	'USER_AUTH_TYPE'	=>    1,		// 默认认证类型 1 登录认证 2 实时认证
    'RBAC_ROLE_TABLE'   =>  'think_role',
    'RBAC_USER_TABLE'   =>  'think_role_user',
    'RBAC_ACCESS_TABLE' =>  'think_access',
    'RBAC_NODE_TABLE'   =>  'think_node',
	'USER_AUTH_KEY'		=>    'authId',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'	=> 'administrator',
	'USER_AUTH_MODEL'	=>   'User',	// 默认验证数据表模型
	'AUTH_PWD_ENCODER'	=>  'md5',	// 用户认证密码加密方式
	'USER_AUTH_GATEWAY'	=> __MODULE__.'/Public/login',	// 默认认证网关
	'NOT_AUTH_MODULE'	=>   'Public',		// 默认无需认证模块
    'NOT_AUTH_URL'      =>  array(__MODULE__.'/public',__MODULE__.'/public/logout',__MODULE__.'/public/verify'),
	'REQUIRE_AUTH_MODULE'=>    '',		// 默认需要认证模块
	'NOT_AUTH_ACTION'	=>   '',		// 默认无需认证操作
	'REQUIRE_AUTH_ACTION'=>    '',		// 默认需要认证操作
    'GUEST_AUTH_ON'     =>  false,    // 是否开启游客授权访问
    'GUEST_AUTH_ID'     =>    0,     // 游客的用户ID
    'LIKE_MATCH_FIELDS' =>  'title|remark',
    'TAG_NESTED_LEVEL'  =>  3,
    'TMPL_ACTION_ERROR'  => 'Public:success', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'=> 'Public:success', // 默认成功跳转对应的模板文件
    'TMPL_PARSE_STRING'	=>	array(
    	'__PUBLIC__'       =>  __ROOT__.'/Public/'.MODULE_NAME,// 站点公共目录
    	)
);