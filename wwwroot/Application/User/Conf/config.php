<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.thinkphp.cn>
// +----------------------------------------------------------------------

/**
 * UCenter客户端配置文件
 * 注意：该配置文件请使用常量方式定义
 */

define('UC_APP_ID', 1); //应用ID
define('UC_API_TYPE', 'Model'); //可选值 Model / Service
define('UC_AUTH_KEY', 'XXXIifaljie*#*488f48HF489&45%'); //加密KEY
define('UC_DB_DSN', 'mysqli://root:@192.168.1.200:3306/thinkcms'); // 数据库连接，使用Model方式调用API必须配置此项
define('UC_TABLE_PREFIX', 'think_'); // 数据表前缀，使用Model方式调用API必须配置此项
