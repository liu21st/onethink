<?php
return array(
    'url'=>array(
        'title'=>'广告链接:',
        'type'=>'text',
        'value'=>'http://www.hangmou.com'
    ),
    'speed'=>array(
        'title'=>'漂浮速度:（单位：毫秒），默认为10',
        'type'=>'text',
        'value'=>'10'
    ),
    'target'=>array(
		'title'=>'链接打开方式:',
		'type'=>'radio',
		'options'=>array(
			'0'=>'当前页面',
			'1'=>'新窗口打开',
		),
		'value'=>'1',
	),
    'images'=>array(
        'title' => '图片上传',
        'type'  => 'picture',
        'value' => ''
    )，
);
					