<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// BaseMemberModel.class.php 2013-03-15

class BaseMemberModel extends Model{
	/* 用户模型自动验证 */
	protected $_validate = array(
		/* 验证用户名 */
		array('username', '1,16', -1, self::EXISTS_VALIDATE, 'length'), //用户名长度不合法
		array('username', 'checkDenyMember', -2, self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册
		array('username', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用
		
		/* 验证密码 */
		array('password', '6,30', -4, self::EXISTS_VALIDATE, 'length'), //密码长度不合法

		/* 验证邮箱 */
		array('email', 'email', -5, self::EXISTS_VALIDATE), //邮箱格式不正确
		array('email', '1,32', -6, self::EXISTS_VALIDATE, 'length'), //邮箱长度不合法
		array('email', 'checkDenyEmail', -7, self::EXISTS_VALIDATE, 'callback'), //邮箱禁止注册
		array('email', '', -8, self::EXISTS_VALIDATE, 'unique'), //邮箱被占用

		/* 验证手机号码 */
		array('mobile', '//', -9, self::EXISTS_VALIDATE), //手机格式不正确
		array('mobile', 'checkDenyMobile', -10, self::EXISTS_VALIDATE, 'callback'), //手机禁止注册
	);
	
	/* 用户模型自动完成 */
	protected $_auto = array(
		array('password', 'think_ucenter_md5', self::MODEL_INSERT, 'function'),
		array('reg_time', NOW_TIME, self::MODEL_INSERT),
		array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
		array('update_time', NOW_TIME),
		array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
	);

	/**
	 * 检测用户名是不是被禁止注册
	 * @param  string $username 用户名
	 * @return boolean          ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyMember($username){

	}

	/**
	 * 检测邮箱是不是被禁止注册
	 * @param  string $email 邮箱
	 * @return boolean       ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyEmail($email){

	}

	/**
	 * 检测手机是不是被禁止注册
	 * @param  string $mobile 手机
	 * @return boolean        ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyMobile($mobile){
		
	}

	/**
	 * 根据配置指定用户状态
	 * @return integer 用户状态
	 */
	protected function getStatus(){

	}

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