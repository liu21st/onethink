<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi.cn@gmail.com>
 */

class MemberModel extends Model {
	
	public function lists($status = 1, $order = 'uid DESC', $field = true){
		$map = array('status' => $status);
		return $this->field($field)->where($map)->order($order)->select();
	}
	
}
