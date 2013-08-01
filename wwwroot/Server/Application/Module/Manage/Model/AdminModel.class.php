<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// AdminModel.class.php 2013-03-15

class AdminModel extends Model{
	/**
	 * 管理员登陆
	 * @param  string $username 用户名
	 * @param  string $password 用户密码
	 * @return boolean          true-登录成功，false-登录失败
	 */
	public function login($username, $password){
		/* 获取用户信息 */
		$member = M('Member')->where("username = '{$username}'")->find();
		if(!is_array($member) || $member['status'] != 1){
			$this->error = '用户不存在或被禁用';
			return false;
		}

		/* 检测是否为管理员 */
		$map = array('member_id' => $member['id']);
		$admin = $this->where($map)->find();
		if(is_array($admin) && 1 == $admin['status']){
			if($member['password'] === think_ucenter_md5($password)){
				/* 登录成功，设置session */
				$auth = array($member['id'], $member['username']);
				session(C('UCENTER_SERVER_AUTH'), $auth);

				//TODO：记录后台登录日志

				return true;
			} else {
				$this->error = '密码错误';
				return false;
			}
		} else {
			$this->error = '非管理员帐号，禁止登录';
			return false;
		}
	}
}