<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
/**
 * 模型管理控制器
 * @author huajie <banhuajie@163.com>
 */

class ModelController extends AdminController {

	/**
	 * 左侧导航节点定义
	 * @author huajie <banhuajie@163.com>
	 */
    static protected $nodes = array(
        array( 'title'=>'模型管理', 'url'=>'Model/index', 'group'=>'扩展',
        		'operator'=>array(
        				//权限管理页面的五种按钮
        				array('title'=>'新增','url'=>'model/add'),
        				array('title'=>'编辑','url'=>'model/edit'),
        				array('title'=>'改变状态','url'=>'model/setStatus'),
        				array('title'=>'保存数据','url'=>'model/update'),
        		),
    	),
    );

    /**
     * 初始化方法，与AddonsController同步
     * @see AdminController::_initialize()
     * @author huajie <banhuajie@163.com>
     */
    public function _initialize(){
    	$this->assign('_extra_menu',array(
    			'已装插件后台'=>D('Addons')->getAdminList(),
    	));
    	parent::_initialize();
    }

	/**
	 * 模型管理首页
	 * @author huajie <banhuajie@163.com>
	 */
	public function index(){
		$Model = D('DocumentModel');

		/* 查询条件初始化 */
		$map = array('status'=>array('gt',-1));

		/*初始化分页类*/
		import('COM.Page');
		$count = $Model->where($map)->count('id');
		$Page = new Page($count, 10);
		$this->page = $Page->show();

		//列表数据获取
		$list = $Model->where($map)->limit($Page->firstRow. ',' . $Page->listRows)->select();

		$this->assign('list', $list);
		$this->display();
	}

	/**
	 * 设置一条或者多条数据的状态
	 * @author huajie <banhuajie@163.com>
	 */
	public function setStatus(){
		/*参数过滤*/
		$ids = I('param.ids');
		$status = I('param.status');
		if(empty($ids) || !isset($status)){
			$this->error('请选择要操作的数据');
		}

		/*拼接参数并修改状态*/
		$Model = 'DocumentModel';
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
	 * 文档新增页面初始化
	 * @author huajie <banhuajie@163.com>
	 */
	public function add(){
		$this->display();
	}

	/**
	 * 文档编辑页面初始化
	 * @author huajie <banhuajie@163.com>
	 */
	public function edit(){
		$id = I('get.id','');
		if(empty($id)){
			$this->error('参数不能为空！');
		}

		/*获取一条记录的详细数据*/
		$Model = D('DocumentModel');
		$data = $Model->find($id);
		if(!$data){
			$this->error($Model->getError());
		}

		$this->assign($data);
		$this->display();
	}

	/**
	 * 更新一条数据
	 * @author huajie <banhuajie@163.com>
	 */
	public function update(){
		$res = D('DocumentModel')->update();
		if(!$res){
			$this->error(D('DocumentModel')->getError());
		}else{
			if($res['id']){
				$this->success('更新成功');
			}else{
				$this->success('新增成功');
			}
		}
	}

}