<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 属性控制器
 * @author huajie <banhuajie@163.com>
 */

class AttributeController extends AdminController {


    public function _initialize(){
    	$this->assign('_extra_menu',array(
    			'已装插件后台'=> D('Addons')->getAdminList(),
    	));
    	parent::_initialize();
    }

    /**
     * 属性列表
     * @author huajie <banhuajie@163.com>
     */
    public function index(){
    	$model_id = I('get.model_id');
    	/* 查询条件初始化 */
        $map  = array('model_id' => $model_id);

		$list = $this->lists('Attribute', $map);
		int_to_string($list);

        $this->assign('_list', $list);
        $this->assign('model_id', $model_id);
        $this->meta_title = '属性列表';
        $this->display();
    }

    /**
     * 设置一条或者多条数据的状态
     * @author huajie <banhuajie@163.com>
     */
    public function setStatus(){
        /*参数过滤*/
        $ids = I('request.id');
        $status = I('request.status');
        if(empty($ids) || !isset($status)){
            $this->error('请选择要操作的数据');
        }

        /*拼接参数并修改状态*/
        $Model = 'Attribute';
        $map = array();
        if(is_array($ids)){
            $map['id'] = array('in', implode(',', $ids));
        }elseif (is_numeric($ids)){
            $map['id'] = $ids;
        }
        switch ($status){
            case -1 : $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));break;
            case 0  : $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));break;
            case 1  : $this->resume($Model, $map, array('success'=>'启用成功','error'=>'启用失败'));break;
            default : $this->error('参数错误');break;
        }
    }


    /**
     * 新增页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function add(){
    	$model_id = I('get.model_id');
        $model  =   M('Model')->field('title,name,field_group')->find($data['model_id']);
        $this->assign('model',$model);
    	$this->assign('info', array('model_id'=>$model_id));
        $this->meta_title = '新增属性';
        $this->display('edit');
    }

    /**
     * 编辑页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function edit(){
        $id = I('get.id','');
        if(empty($id)){
            $this->error('参数不能为空！');
        }

        /*获取一条记录的详细数据*/
        $Model = M('Attribute');
        $data = $Model->field(true)->find($id);
        if(!$data){
            $this->error($Model->getError());
        }
        $model  =   M('Model')->field('title,name,field_group')->find($data['model_id']);
        $this->assign('model',$model);
        $this->assign('info', $data);
        $this->meta_title = '编辑属性';
        $this->display();
    }

    /**
     * 更新一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function update(){
        $res = D('Attribute')->update();
        if(!$res){
            $this->error(D('Attribute')->getError());
        }else{
            if($res['id']){
                $this->success('更新成功', U('index','model_id='.$res['model_id']));
            }else{
                $this->success('新增成功', U('index','model_id='.$res['model_id']));
            }
        }
    }

    /**
     * 删除一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function remove(){
    	$id = I('id');
    	empty($id) && $this->error('参数错误！');

    	$Model = D('Attribute');

    	$info = $Model->getById($id);
    	empty($info) && $this->error('该字段不存在！');

    	//删除属性数据
    	$res = $Model->delete($id);

    	//删除表字段
    	$Model->deleteField($info);
    	if(!$res){
    		$this->error(D('Attribute')->getError());
    	}else{
    		//记录行为
    		action_log('update_attribute', 'attribute', $id, UID);
    		$this->success('删除成功', U('index','model_id='.$info['model_id']));
    	}
    }
}
