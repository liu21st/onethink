<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

// OneThink常量定义
const ONETHINK_VERSION      =   '1.0beta';
const ONETHINK_ADDON_PATH   =   './Addons/';

/**
 * 系统配文件
 * 所有系统级别的配置
 */
return array(
    /* 模块相关配置 */
    'AUTOLOAD_NAMESPACE' => array('Addons' => ONETHINK_ADDON_PATH), //扩展模块列表
    'DEFAULT_MODULE'     => 'Admin',
    'MODULE_DENY_LIST'   => array('Common', 'User'),

    /* 调试配置 */
    'SHOW_PAGE_TRACE' => true,

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 1, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符
    
    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //过滤函数

    /* 数据库配置 */
    'DB_TYPE'   => '', // 数据库类型
    'DB_HOST'   => ''/* localhost */, // 服务器地址
    'DB_NAME'   => '', // 数据库名
    'DB_USER'   => '', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => '', // 端口
    'DB_PREFIX' => '', // 数据库表前缀
    'LIST_ROWS' => 15,//列表默认行数

    /* 文档模型配置 (文档模型核心配置，请勿更改) */
    'DOCUMENT_MODEL_TYPE' => array('专辑', '目录', '主题', '段落'),
);
