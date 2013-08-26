<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class UserController extends AdminController {

	/**
     * 左侧导航节点定义
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    static protected $nodes = array(
    	/* 系统设置 */
        array( 'title' => '用户信息', 'url' => 'User/index', 'group' => '用户管理'),
        array( 'title' => '用户行为', 'url' => 'User/action', 'group' => '用户管理'),
    );

	/**
	 * 用户管理首页
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function index(){
		$list = D("Member")->lists();

		$this->assign('list', $list);
		$this->display();
	}

	/**
	 * 用户行为列表
	 * @author huajie <banhuajie@163.com>
	 */
	public function action(){
		//获取列表数据
		$list = M('Action')->select();

		$this->assign('list', $list);
		$this->display();
	}

	/**
	 * 编辑行为
	 * @author huajie <banhuajie@163.com>
	 */
	public function editAction(){
		$id = I('get.id');
		empty($id) && $this->error('参数不能为空！');
		$data = M('Action')->find($id);

		$this->assign($data);
		$this->display();
	}

	public function saveAction(){
		$res = D('Action')->update();
		if(!$res){
			$this->error(D('Action')->getError());
		}else{
			if($res['id']){
				$this->success('更新行为成功！');
			}else{
				$this->success('新增行为成功！');
			}
		}
	}

}
