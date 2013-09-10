<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
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
        array( 'title' => '用户行为', 'url' => 'User/action', 'group' => '用户管理',
        		'operator'=>array(
        				//权限管理页面的五种按钮
        				array('title'=>'新增','url'=>'user/addAction'),
        				array('title'=>'编辑','url'=>'user/editAction'),
        				array('title'=>'改变状态','url'=>'user/setStatus'),
        				array('title'=>'保存数据','url'=>'user/saveAction'),
        		),
    	),
    );

	/**
	 * 用户管理首页
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function index(){
		$list = D("Member")->lists();
        intToString($list);
		$this->assign('_list', $list);
		$this->display();
	}

	/**
	 * 用户行为列表
	 * @author huajie <banhuajie@163.com>
	 */
	public function action(){
		//获取列表数据
		$list = M('Action')->where(array('status'=>array('gt',-1)))->select();

		$this->assign('list', $list);
		$this->display();
	}

	/**
	 * 新增行为
	 * @author huajie <banhuajie@163.com>
	 */
	public function addAction(){
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

	/**
	 * 更新行为
	 * @author huajie <banhuajie@163.com>
	 */
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

	/**
	 * 设置一条或者多条数据的状态
	 * @author huajie <banhuajie@163.com>
	 */
	public function setStatus(){
		/*参数过滤*/
		$ids = I('request.ids');
		$status = I('request.status');
		if(empty($ids) || !isset($status)){
			$this->error('请选择要操作的数据');
		}

		/*拼接参数并修改状态*/
		$Model = 'Action';
		$map = array();
		if(is_array($ids)){
			$map['id'] = array('in', implode(',', $ids));
		}elseif (is_numeric($ids)){
			$map['id'] = $ids;
		}
		switch ($status){
			case -1 : $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));break;
			case 0 : $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));break;
			case 1 : $this->resume($Model, $map, array('success'=>'启用成功','error'=>'启用失败'));break;
			default : $this->error('参数错误');break;
		}
	}

    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null)
    {
        $id    = array_unique((array)I('id',0));
        $id    = is_array($id) ? implode(',',$id) : $id;
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Member', array('uid'=>array('in',$id)) );    
                break;
            case 'resumeuser':
                $this->resume('Member', array('uid'=>array('in',$id)) );    
                break;
            case 'deleteuser':
                $this->delete('Member', array('uid'=>array('in',$id)) );    
                break;
            default:
                $this->error('参数非法');
        }
    }

}
