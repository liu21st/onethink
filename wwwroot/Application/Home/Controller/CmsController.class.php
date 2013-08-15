<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 前台公共控制器
 * 为防止多分组Action名称冲突，公共Action名称统一使用分组名称
 */
class CmsController extends Action {
	
	/* 空操作，用于输出404页面 */
	// public function _empty(){
	// 	echo 404; //TODO:完成404页面
	// }
	// TODO: 为了调试方便，暂时注释
	
	/* 用户登录检测 */
	protected function login(){
		/* 用户登录检测 */
		is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
	}
	
}
