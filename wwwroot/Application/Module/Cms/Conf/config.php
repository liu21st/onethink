<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.thinkphp.cn>
// +----------------------------------------------------------------------

/**
 * 前台配置文件
 * 所有除开系统级别的前台配置
 */
return array(
    /* 模块相关配置 */
    'EXTEND_MODULE'  => array('Addons' => MODULE_PATH . 'Addons/'), //扩展模块列表
    
	/* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
		'__IMG__' => __ROOT__ . '/Public/Home/images',
		'__CSS__' => __ROOT__ . '/Public/Home/css',
		'__JS__'  => __ROOT__ . '/Public/Home/js',
	),

	/* SESSION配置 */
    'SESSION_PREFIX' => 'thinkcms_home', //session前缀

    /* 默认插件定义 */
    'ADDONS_HOOKS_WIDGET' => array(
        'page_header'            => array('Attachment'),
    	'document_edit_form'     => array('Attachment'),
        'document_detail_before' => array(),
        'document_detail_after'  => array('Attachment'),
        'page_footer'            => array('Attachment'),
    ),
    'ADDONS_HOOKS_CONTROLLER' => array(
    	'document_save_complete' => array('Attachment'),
    ),

);
