<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// IndexAction.class.php 2013-03-14

class IndexAction extends Action{
	public function index(){
		is_login() || $this->redirect('login');

		$this->display();
	}

	//用户登录
	public function login(){
		/* 已登录，直接跳转到首页 */
		is_login() && $this->redirect('index');

		$this->display();
	}

	//用户登录验证页面
	public function doLogin($username = '', $password = '', $verify = ''){
		/* 已登录，直接跳转到首页 */
		is_login() && $this->success('登录成功', U('index'));

		/* 验证验证码 */
		
		/* 登录后台 */
		if(D('Admin')->login($username, $password)){
			//登录成功，跳转到首页
			$this->success('登录成功', U('index'));
		} else {
			$this->error(D('Admin')->getError());
		}
		
	}
}
