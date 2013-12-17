<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

	return array(
		'editor_type'=>array(
			'title'=>'编辑器类型:',
			'type'=>'select',
			'options'=>array(
				'1'=>'普通文本',
				'2'=>'富文本',
				'3'=>'UBB解析',
				'4'=>'Markdown编辑器'
			),
			'value'=>'1',
		),
		'editor_wysiwyg'=>array(
			'title'=>'富文本编辑器:',
			'type'=>'select',
			'options'=>array(
				'1'=>'Kindeditor',
				'2'=>'Ueditor(百度编辑器)',
			),
			'value'=>1
		),
		'editor_height'=>array(
			'title'=>'编辑器高度:',
			'type'=>'text',
			'value'=>'500px'
		),
		'editor_resize_type'=>array(
			'title'=>'是否允许拖拉编辑器',
			'type'=>'radio',
			'options'=>array(
				'0'=>'不允许',
				'1'=>'允许'
			),
			'value'=>'1',
			'tip'=>'ubb和markdown编辑器不支持此功能'
		),
	);