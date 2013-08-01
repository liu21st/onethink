<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// BaseAction.class.php 2013-03-15

class BaseAction extends Action{
	/* 初始登录判断 */
	protected function _initialize(){
		is_login() || $this->error('未登录，请先登录', U('Index/login'));
	}

	//删除数据，假删除
	public function del($id = 0){
		empty($id) && $this->error('请指定要删除的ID');
		$this->status($id, -1);
	}

	//禁用数据
	public function forbid($id = 0){
		empty($id) && $this->error('请指定要禁用的ID');
		$this->status($id);
	}

	//改变数据状态
	public function status($id = 0, $status = 0){
		//设置查询条件
		if(is_numeric($id)){
			$map = array('id' => $id);
		} else if(is_array($id)){
			$map = array('id' => array('in', $id));
		} else {
			$this->error('错误的ID');
		}

		//删除数据
		if(false === M(MODULE_NAME)->where($map)->setField('status', $status)){
			$this->error('操作失败');
		} else {
			$this->success('操作成功');
		}
	}
}