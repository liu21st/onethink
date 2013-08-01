<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// AppAction.class.php 2013-03-18

class AppAction extends BaseAction{
	//应用列表
	public function index(){
		/* 获取列表数据 */
		$list  = M('App')->field(true)->order('id DESC')->select();
		$this->assign('list', $list);
		$this->display();
	}

	//编辑或新增应用
	public function edit($id = 0){
		if($id && is_numeric($id)){
			$app = M('App')->find($id);
			$this->assign('app', $app);
		}

		$this->display();
	}

	//保存应用数据
	public function save(){
		$data = D('App')->create();
		if($data){
			if(D('App')->save()){
				$this->success('保存应用信息成功');
			} else {
				$this->error('保存应用信息失败');
			}
		} else {
			$this->error('保存应用信息出错：' . D('App')->getError());
		}
	}
}
