<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

return array(
	'resolutions'=>array(//配置在表单中的键名 ,这个会是config[random]
		'title'=>'响应式宽度边界:',//表单的文字
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'1382,992,768,480',	//表单的默认值
		'tip'=>',分割'
	),
	'cache_path'=>array(
		'title'=>'缓存图片的路径:',
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'./Uploads/ai-cache',	//表单的默认值
		'tip'=>'要有可写权限'
	),
	'jpg_quality'=>array(
		'title'=>'图像质量:',
		'type'=>'text',
		'value'=>'75',
		'tip'=>'1-100'
	),
	'sharpen'=>array(
		'title'=>'是否锐化:',
		'type'=>'radio',
		'options'=>array(
			'0'=>'否',
			'1'=>'是'
		),
		'value'=>0,
	),
	'watch_cache'=>array(
		'title'=>'是否监视源文件更新:',
		'type'=>'radio',
		'options'=>array(
			'0'=>'否',
			'1'=>'是'
		),
		'value'=>0,
		'tip'=>'是的话源文件更新后缓存会下次访问时重新生成'
	),
	'browser_cache'=>array(
		'title'=>'浏览器缓存时间:',
		'type'=>'text',
		'value'=>'604800', // 60*60*24*7 7天
		'tip'=>'单位:秒'
	)
);