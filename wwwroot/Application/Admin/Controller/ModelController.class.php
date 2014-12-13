<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Admin\Model\AuthGroupModel;

/**
 * 模型管理控制器
 * @author huajie <banhuajie@163.com>
 */
class ModelController extends AdminController {


    /**
     * 检测是否是需要动态判断的权限
     * @return boolean|null
     *      返回true则表示当前访问有权限
     *      返回false则表示当前访问无权限
     *      返回null，则会进入checkRule根据节点授权判断权限
     *
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    protected function checkDynamic(){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        //模型权限业务检查逻辑
        //
        //提供的工具方法：
        //$AUTH_GROUP = D('AuthGroup');
        // $AUTH_GROUP->checkModelId($mid);      //检查模型id列表是否全部存在
        // AuthGroupModel::getModelOfGroup($gid);//获取某个用户组拥有权限的模型id
        $model_ids = AuthGroupModel::getAuthModels(UID);
        $id        = I('id');
        switch(strtolower(ACTION_NAME)){
            case 'edit':    //编辑
            case 'update':  //更新
                if ( in_array($id,$model_ids) ) {
                    return true;
                }else{
                    return false;
                }
            case 'setstatus': //更改状态
                if ( is_array($id) && array_intersect($id,(array)$model_ids)==$id ) {
                    return true;
                }elseif( in_array($id,$model_ids) ){
                    return true;
                }else{
                    return false;
                }
        }

        return null;//不明,需checkRule
    }

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
        $this->display();
    }

    /**
     * 新增页面初始化
     * @author huajie <banhuajie@163.com>
     */
    public function add(){
        //获取所有的模型
        $models = M('Model')->where(array('extend'=>0))->field('id,title')->select();

        $this->assign('models', $models);
        $this->meta_title = '新增模型';
        $this->display();
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
        $Model = M('Model');
        $data = $Model->field(true)->find($id);
        if(!$data){
            $this->error($Model->getError());
        }

        $fields = M('Attribute')->where(array('model_id'=>$data['id']))->field('id,name,title,is_show')->select();
        //是否继承了其他模型
        if($data['extend'] != 0){
            $extend_fields = M('Attribute')->where(array('model_id'=>$data['extend']))->field('id,name,title,is_show')->select();
            $fields = array_merge($fields, $extend_fields);
        }

        /* 获取模型排序字段 */
        $field_sort = json_decode($data['field_sort'], true);
        if(!empty($field_sort)){
        	/* 对字段数组重新整理 */
        	$fields_f = array();
        	foreach($fields as $v){
        		$fields_f[$v['id']] = $v;
        	}
        	$fields = array();
        	foreach($field_sort as $key => $groups){
        		foreach($groups as $group){
        			$fields[$fields_f[$group]['id']] = array(
        					'id' => $fields_f[$group]['id'],
        					'name' => $fields_f[$group]['name'],
        					'title' => $fields_f[$group]['title'],
        					'is_show' => $fields_f[$group]['is_show'],
        					'group' => $key
        			);
        		}
        	}
        	/* 对新增字段进行处理 */
        	$new_fields = array_diff_key($fields_f,$fields);
        	foreach ($new_fields as $value){
        		if($value['is_show'] == 1){
        			array_unshift($fields, $value);
        		}
        	}
        }

        $this->assign('fields', $fields);
        $this->assign('info', $data);
        $this->meta_title = '编辑模型';
        $this->display();
    }

    /**
     * 删除一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function del(){
        $ids = I('get.ids');
        empty($ids) && $this->error('参数不能为空！');
        $ids = explode(',', $ids);
        foreach ($ids as $value){
            $res = D('Model')->del($value);
            if(!$res){
                break;
            }
        }
        if(!$res){
            $this->error(D('Model')->getError());
        }else{
            $this->success('删除模型成功！');
        }
    }

    /**
     * 更新一条数据
     * @author huajie <banhuajie@163.com>
     */
    public function update(){
        $res = D('Model')->update();

        if(!$res){
            $this->error(D('Model')->getError());
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
            $tables = D('Model')->getTables();

            $this->assign('tables', $tables);
            $this->meta_title = '生成模型';
            $this->display();
        }else{
            $table = I('post.table');
            empty($table) && $this->error('请选择要生成的数据表！');
            $res = D('Model')->generate($table);
            if($res){
                $this->success('生成模型成功！', U('index'));
            }else{
                $this->error(D('Model')->getError());
            }
        }
    }
}
