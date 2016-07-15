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

    /**
     * 属性列表
     * @author huajie <banhuajie@163.com>
     */
    public function index(){
        $model_id = I('get.model_id');
        /* 查询条件初始化 */
        $map['model_id']    =   $model_id;

        $list = $this->lists('Attribute', $map);
        int_to_string($list);

        // 记录当前列表页的cookie
        Cookie('__forward__',       $_SERVER['REQUEST_URI']);
        $this->assign('_list',      $list);
        $this->assign('model_id',   $model_id);
        $this->meta_title = '属性列表';
        $this->display();
    }

    /**
     * 新增页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function add(){
        $model_id   =   I('get.model_id');
        $model      =   M('Model')->field('title,name,field_group')->find($model_id);
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
            $this->success($res['id']?'更新成功':'新增成功', Cookie('__forward__'));
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
