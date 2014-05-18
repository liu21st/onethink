<?php
return array(
	'title'=>array(
		'title'=>'显示标题:',
		'type'=>'text',
		'value'=>'天气预报',
	),
	'city'=>array(
		'title'=>'显示城市',
		'type'=>'text',
		'tip'=>'前台显示可不填'
	),
	'showplace'=>array(
		'title'=>'显示位置',
		'type'=>'checkbox',
		'options'=>array(
			'1'=>'前台',
			'0'=>'后台'
		),
		'value'=>'1'
	),
	'showday'=>array(
		'title'=>'显示天数',
		'type'=>'select',
		'options'=>array(
			'1'=>'1天',
			'2'=>'2天',
			'3'=>'3天',
			'4'=>'4天'
		),
		'value'=>'1',
	),
	'ak'=>array(
		'title'=>'百度密钥',
		'type'=>'text',
		'tip' => '天气链接的查询次数为5000次每天，地址查询为100万次每天，后续将支持多个key查询，或者你自己发邮件给百度，自行提升访问次数',
		'value'=>'DEcad2d987274f741bd96dbae14a4759',
	),
	'tianqiUrl'=> array(
		'title' => '天气查询地址',
		'type' =>'text',
		'tip'=>'因百度会不定时升级接口，所以将接口地址放进配置文件中，方便管理',
		'value'=>'http://api.map.baidu.com/telematics/v3/weather',
	),
	'ipUrl'=> array(
		'title' => 'ip自动获取地址',
		'type' =>'text',
		'tip'=>'因百度会不定时升级接口，所以将接口地址放进配置文件中，方便管理',
		'value'=>'http://api.map.baidu.com/location/ip',
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
