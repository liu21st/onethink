<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use Admin\Model\AuthGroupModel;

/**
 * 模型管理控制器
 * @author huajie <banhuajie@163.com>
 */
class Model extends Admin  {

    /**
     * 模型管理首页
     * @author huajie <banhuajie@163.com>
     */
    public function index(){
        $map = array('status'=>array('gt',-1));
        $list = $this->lists('Model',$map);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->meta_title = '模型管理';
        return $this->fetch();
    }

    /**
     * 新增页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function add(){
        //获取所有的模型
        $models = db('Model')->where(array('extend'=>0))->field('id,title')->select();

        $this->assign('models', $models);
        $this->meta_title = '新增模型';
        return $this->fetch();
    }

    /**
     * 编辑页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function edit(){
        $id = input('get.id','');
        if(empty($id)){
            $this->error('参数不能为空！');
        }

        /*获取一条记录的详细数据*/
        $Model = db('Model');
        $data = $Model->field(true)->find($id);
        if(!$data){
            $this->error($Model->getError());
        }
        $data['attribute_list'] = empty($data['attribute_list']) ? '' : explode(",", $data['attribute_list']);
        $fields = db('Attribute')->where(array('model_id'=>$data['id']))->getField('id,name,title,is_show',true);
        $fields = empty($fields) ? array() : $fields;
        // 是否继承了其他模型
        if($data['extend'] != 0){
            $extend_fields  = db('Attribute')->where(array('model_id'=>$data['extend']))->getField('id,name,title,is_show',true);
            $fields        += $extend_fields;
        }
        
        // 梳理属性的可见性
        foreach ($fields as $key=>$field){
            if (!empty($data['attribute_list']) && !in_array($field['id'], $data['attribute_list'])) {
                $fields[$key]['is_show'] = 0;
            }
        }
        
        // 获取模型排序字段
        $field_sort = json_decode($data['field_sort'], true);
        if(!empty($field_sort)){
            foreach($field_sort as $group => $ids){
                foreach($ids as $key => $value){
                    $fields[$value]['group']  =  $group;
                    $fields[$value]['sort']   =  $key;
                }
            }
        }
        
        // 模型字段列表排序
        $fields = list_sort_by($fields,"sort");
        
        $this->assign('fields', $fields);
        $this->assign('info', $data);
        $this->meta_title = '编辑模型';
        return $this->fetch();
    }

    /**
     * 删除一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function del(){
        $ids = input('get.ids');
        empty($ids) && $this->error('参数不能为空！');
        $ids = explode(',', $ids);
        foreach ($ids as $value){
            $res = model('Model')->del($value);
            if(!$res){
                break;
            }
        }
        if(!$res){
            $this->error(model('Model')->getError());
        }else{
            $this->success('删除模型成功！');
        }
    }

    /**
     * 更新一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function update(){
        $res = model('Model')->update();

        if(!$res){
            $this->error(model('Model')->getError());
        }else{
            $this->success($res['id']?'更新成功':'新增成功', Cookie('__forward__'));
        }
    }

    /**
     * 生成一个模型
     * @author huajie <banhuajie@163.com>
     */
    public function generate(){
        if(!IS_POST){
            //获取所有的数据表
            $tables = model('Model')->getTables();

            $this->assign('tables', $tables);
            $this->meta_title = '生成模型';
            return $this->fetch();
        }else{
            $table = input('post.table');
            empty($table) && $this->error('请选择要生成的数据表！');
            $res = model('Model')->generate($table,input('post.name'),input('post.title'));
            if($res){
                $this->success('生成模型成功！', url('index'));
            }else{
                $this->error(model('Model')->getError());
            }
        }
    }
}
