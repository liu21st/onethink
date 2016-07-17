<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

return array(
	'comment_type'=>array(//配置在表单中的键名 ,这个会是config[random]
		'title'=>'使用类型:',	 //表单的文字
		'type'=>'select',		 //表单的类型：text、textarea、checkbox、radio、select等
		'options'=>array(		 //select 和radion、checkbox的子选项
			'1'=>'有言',		 //值=>文字
			'2'=>'多说',
		),
		'value'=>'1',			 //表单的默认值
	),
	'group'=>array(
		'type'=>'group',
		'options'=>array(
			'youyan'=>array(
				'title'=>'友言配置',
				'options'=>array(
					'comment_uid_youyan'=>array(
						'title'=>'账号id:',
						'type'=>'text',
						'value'=>'90040',
						'tip'=>'填写自己登录友言后的uid,填写后可进相应官方后台'
					),
				)
			),
			'duoshuo'=>array(
				'title'=>'多说配置',
				'options'=>array(
					'comment_short_name_duoshuo'=>array(
						'title'=>'短域名',
						'type'=>'text',
						'value'=>'',
						'tip'=>'每个站点一个域名'
					),
					'comment_form_pos_duoshuo'=>array(
						'title'=>'表单位置:',
						'type'=>'radio',
						'options'=>array(
							'top'=>'顶部',
							'buttom'=>'底部'
						),
						'value'=>'buttom'
					),
					'comment_data_list_duoshuo'=>array(
						'title'=>'单页显示评论数',
						'type'=>'text',
						'value'=>'10'
					),
					'comment_data_order_duoshuo'=>array(
						'title'=>'评论显示顺序',
						'type'=>'radio',
						'options'=>array(
							'asc'=>'从旧到新',
							'desc'=>'从新到旧'
						),
						'value'=>'asc'
					)
				)
			)
		)
	)
);
