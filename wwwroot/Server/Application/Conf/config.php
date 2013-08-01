<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// config.php 2013-03-14

return array(
    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, // 默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 2,    //URL模式
    'URL_HTML_SUFFIX'      => 'html',
    'VAR_URL_PARAMS'       => false,

    /* 项目分组配置 */
    'APP_GROUP_MODE'        => 1, //使用独立分组
    'APP_GROUP_LIST'        => 'Manage,Api', //项目分组设定
    'DEFAULT_GROUP'         => 'Manage', //默认分组
    'APP_SUB_DOMAIN_DEPLOY' => false, // 开启子域名配置
    'APP_SUB_DOMAIN_RULES'  => array(
        
    ),

    /* 数据库配置 */
    'DB_TYPE'   => 'mysqli', // 数据库类型
    'DB_HOST'   => 'aoiujz', // 服务器地址
    'DB_NAME'   => 'think_ucenter', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => '3306', // 端口
    'DB_PREFIX' => 'think_ucenter_', // 数据库表前缀

    /* 其他相关配置 */
    'OUTPUT_ENCODE' => false,
);
