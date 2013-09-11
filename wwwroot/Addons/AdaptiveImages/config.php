<?php
return array(
	'resolutions'=>array(//配置在表单中的键名 ,这个会是config[random]
		'title'=>'响应式宽度边界:,分割',//表单的文字
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'1382,992,768,480',	//表单的默认值
	),
	'cache_path'=>array(
		'title'=>'缓存图片的路径：(要有可写权限)',
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'/Public/ai-cache',	//表单的默认值
	),
	'jpg_quality'=>array(
		'title'=>'图像质量',
		'type'=>'text',
		'value'=>'75',
	),
	'sharpen'=>array(
		'title'=>'是否锐化',
		'type'=>'radio',
		'options'=>array(
			'0'=>'否',
			'1'=>'是'
		),
		'value'=>0,
	),
	'watch_cache'=>array(
		'title'=>'是否监视源文件更新，是的话源文件更新后缓存会下次访问时重新生成',
		'type'=>'radio',
		'options'=>array(
			'0'=>'否',
			'1'=>'是'
		),
		'value'=>0
	),
	'browser_cache'=>array(
		'title'=>'浏览器缓存时间(单位:秒)',
		'type'=>'text',
		'value'=>'604800' // 60*60*24*7 7天
	)
);