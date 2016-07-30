<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

	$returntop = array(
		'0.png',
		'1.png','2.png','3.gif','4.png','5.png','6.png','7.gif','8.gif','9.gif','10.png',
		'11.jpg','12.gif','13.png','14.jpg','15.png','16.png','17.png','18.png','19.gif','20.png',
		'21.png','22.png','23.gif','24.png','25.gif','26.png','27.gif','28.gif','29.png','30.png',
		'31.png','32.png','33.png','34.png','35.png','36.gif','37.png','38.gif','39.png','40.png',
		'41.png','42.png','43.png','44.png','45.png','46.gif','47.png','48.gif','49.gif','50.gif',
		'51.gif','52.gif','53.gif','54.gif','55.gif','56.gif','57.gif','58.gif','59.gif','60.gif',
		'61.gif','62.gif','63.gif','64.gif','65.png','66.png','67.gif','68.png','69.png','70.png',
		'71.gif','72.gif','73.gif','74.png','75.jpg','76.png','77.gif','78.gif','79.gif','80.gif',
		'81.jpg','82.png','83.gif','84.jpg','85.png','86.gif','87.png','88.gif','89.gif','90.png',
		'91.png','92.gif','93.gif','94.gif','95.gif','96.gif','97.gif','98.png','99.png',
	);
	$returntop = array_slice($returntop, 1 ,null ,true);

	foreach ($returntop as $key=>$value) {
		$returntop[$key] = '<img src="/Addons/ReturnTop/images/0'.$value.'"/>';
	}

	return array(
		'random'=>array(
			'title'=>'是否开启随机:',
			'type'=>'radio',
			'options'=>array(
				'1'=>'开启',
				'0'=>'关闭',
			),
			'value'=>'0',
		),
		'current'=>array(
			'title'=>'指定样式:',
			'type'=>'radio',
			'options'=>$returntop,
			'value'=>'1'
		)
	);