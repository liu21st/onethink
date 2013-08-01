<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// MemberModel.class.php 2013-03-18

class MemberModel extends BaseMemberModel{
	/**
	 * 模型初始化，用于重置自动完成和自动验证规则
	 * @return [type] [description]
	 */
	protected function _initialize(){
		//删除禁止规则，后台管理员不受此限制
		unset($_validate[1], $_validate[6], $_validate[9]);
		//修改新用户状态，后台管理员添加的用户不需要验证和审核
		$this->_auto[4] = array('status', 1, self::MODEL_BOTH);
	}

	/**
	 * 将错误编号转换成错误信息
	 * @return string 错误信息
	 */
	public function getError(){
		$error = '';
		switch ($this->error) {
			case -1:
				$error = '用户名长度不合法';
				break;
			case -2:
				$error = '用户名禁止注册';
				break;
			case -3:
				$error = '用户名被占用';
				break;
			case -4:
				$error = '密码长度不合法';
				break;
			case -5:
				$error = '邮箱格式不正确';
				break;
			case -6:
				$error = '邮箱长度不合法';
				break;
			case -7:
				$error = '邮箱禁止注册';
				break;
			case -8:
				$error = '邮箱被占用';
				break;
			case -9:
				$error = '手机格式不正确';
				break;
			case -10:
				$error = '手机禁止注册';
				break;
			default:
				$error = '未知错误';
		}
		return $error;
	}
}