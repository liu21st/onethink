<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// MemberAction.class.php 2013-03-15

class MemberAction extends BaseAction{
	//用户列表
	public function index($p = 1, $id = '' ,$username = '', $email = '', $mobile = '',
		$reg_time = '', $reg_ip = '', $last_login_time = '', $last_login_ip = ''){
		/* 设置页码 */
		$p = intval($p);
		0 == $p && $p = 1;

		/* 配置查询条件 */
		$map = array();
		empty($id)       || $map['id']       = parse_where_str($id); //按id查询
		empty($username) || $map['username'] = parse_where_str($username); //按username查询
		empty($email)    || $map['email']    = parse_where_str($email); //按email查询
		empty($mobile)   || $map['mobile']   = parse_where_str($mobile); //按mobile查询

		//TODO：通过时间和IP搜索用户
		
		/* 查询数据并分页 */
		$list  = M('Member')->field(true)->where($map)->order('id DESC')->page($p, 10)->select();
		$count = M('Member')->where($map)->count();
		import('COM.Page');
		$Page = new Page($count, 10);
		$show = $Page->show();

		$this->assign('list', $list);
		$this->assign('show', $show);
		$this->display();
	}

	//添加新用户
	public function add(){
		/* 添加用户 */
		if($user  = D('Member')->create()){
			if(D('Member')->add()){
				$this->success('添加用户成功');
			} else {
				$this->error('添加用户失败');
			}
		} else {
			$this->error(D('Member')->getError());
		}
	}
}