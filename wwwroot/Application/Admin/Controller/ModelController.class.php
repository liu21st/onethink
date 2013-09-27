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
 * 模型管理控制器
 * @author huajie <banhuajie@163.com>
 */

class ModelController extends AdminController {

    /**
     * 左侧导航节点定义
     * @author huajie <banhuajie@163.com>
     */
    static protected $nodes = array(
        array(
            'title'     =>  '模型管理',
            'url'       =>  'Model/index',
            'group'     =>  '扩展',
            'operator'  =>  array(
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
        $map = array('status'=>array('gt',-1));
        $list = $this->lists('DocumentModel',$map);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '模型管理';
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
        $Model = 'DocumentModel';
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
        $this->meta_title = '新增文档模型';
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
        $Model = M('DocumentModel');
        $data = $Model->field(true)->find($id);
        if(!$data){
            $this->error($Model->getError());
        }

        //获取模型字段
        if(empty($data['fields'])){

        	/* 获取基础模型字段 */
        	$base = M('Document')->getDbFields();
        	//id字段不需要排序
        	if(in_array('id', $base)){
        		unset($base[array_search('id', $base)]);
        	}
        	$base = array_flip($base);
        	//排序起始值从1开始
        	foreach ($base as $key=>$value){
        		$base[$key] = $value + 1;
        	}

        	/* 获取扩展模型字段 */
        	$extend = D(ucfirst($data['name']), 'Logic')->getDbFields();
        	$extend = empty($extend) ? array() : $extend;
        	//id字段不需要排序
        	if(in_array('id', $extend)){
        		unset($extend[array_search('id', $extend)]);
        	}
        	$extend = array_flip($extend);
        	//扩展里的排序从-1开始
        	foreach ($extend as $key=>$value){
        		$extend[$key] = ($value + 1) * -1;
        	}

        	$data['fields'] = empty($extend) ? array() : array_merge($base, $extend);
        }else{
        	$data['fields'] = json_decode($data['fields'], true);
        }


        $this->assign($data);
        $this->meta_title = '编辑文档模型';
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
                $this->success('更新成功', U('index'));
            }else{
                $this->success('新增成功', U('index'));
            }
        }
    }

}
