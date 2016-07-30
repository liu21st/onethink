<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------
namespace app\admin\model;
use think\Model;

/**
 * 插件模型
 * @author yangweijie <yangweijiester@gmail.com>
 */

class Menu extends Model {

    /* 自动完成规则 */
	protected $auto = ['title'];
	protected $insert = ['status'=>1];

	protected function setTitleAttr($value) {
		return htmlspecialchars($value);
	}

    /* 自动验证规则 */
	protected $validate = [
		'rule' => [
			'title'  => 'require',
			'url' => 'require',
		],
		'msg' => [
			'title.require' => '标题必须填写',
			'url.require' => '链接必须填写',
		]
	];

}