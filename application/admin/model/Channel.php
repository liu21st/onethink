<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;
use think\Model;
/**
 * 导航模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class Channel extends Model {
	//自动写入时间戳字段
	protected $autoWriteTimestamp = true;

	/* 自动完成规则 */
	// protected $auto = [];
	protected $insert = ['status'=>1];
	// protected $update = [];

	/* 自动验证规则 */
	protected $validate = [
		'rule' => [
			'title'  => 'require',
			'url' => 'require',
		],
		'msg' => [
			'title.require' => '标题不能为空',
			'url.require' => 'URL不能为空',
		]
	];

}
