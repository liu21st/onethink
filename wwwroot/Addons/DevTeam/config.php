<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

return array(
	'title'=>array(//配置在表单中的键名 ,这个会是config[title]
		'title'=>'显示标题:',//表单的文字
		'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
		'value'=>'OneThink开发团队',			 //表单的默认值
	),
	'width'=>array(
		'title'=>'显示宽度:',
		'type'=>'select',
		'options'=>array(
			'1'=>'1格',
			'2'=>'2格',
			'4'=>'4格'
		),
		'value'=>'2'
	),
	'display'=>array(
		'title'=>'是否显示:',
		'type'=>'radio',
		'options'=>array(
			'1'=>'显示',
			'0'=>'不显示'
		),
		'value'=>'1'
	)
);
