<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// UserAction.class.php 2013-03-18

class UserAciton extends ApiAction{
	//用户注册
	public function register(){
		/* 获取请求数据 */
		list($user['username'], $user['password'], $user['email'], $user['phone']) = 
		$this->data();

		/* 注册用户 */
		if($user  = D('Member')->create($user)){
			$code = D('Member')->add($user)
		} else {
			$code = D('Member')->getError();
		}

		/* 返回数据 */
		$this->returnData($code);
	}

	//用户登录
	public function login(){
		/* 获取请求数据 */
		list($username, $password, $type) = list($this->data());
		$password = think_ucenter_md5($password);
		empty($type) && $type = 1;
		
		/* 用户登录 */
		$data = D('Member')->login($username, $password, $type);

		/* 返回数据 */
		$this->returnData($data);
	}
}