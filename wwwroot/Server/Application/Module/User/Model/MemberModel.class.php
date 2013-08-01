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
	 * 用户登录认证
	 * @param  string  $username 用户名
	 * @param  string  $password 用户密码
	 * @param  integer $type     用户名类型
	 * @return integer           登录成功-用户信息，登录失败-错误编号
	 */
	public function login($username, $password, $type = 1){
		$map = array();
		if(1 == $type){
			$map['username'] = $username;
		} else if(2 == $type){
			$map['email'] = $username;
		} else {
			$map['id'] = $username;
		}

		//获取用户数据
		$user = $this->field(true)->where($map)->find();
		if(is_array($user) && $user['status']){
			/* 验证用户密码 */
			if($password === $user['password']){
				return array($user['id'], $user['username'], $user['email'], $user['mobile']);
			} else {
				return -2;
			}
		} else {
			return -1; //用户不存在或被禁用
		}
	}
}