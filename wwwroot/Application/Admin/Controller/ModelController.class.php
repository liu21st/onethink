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
        $Model = 'Model';
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

        /* 获取模型排序字段 */
        $fields = json_decode($data['field_sort'], true);

        if(empty($fields)){		//未排序
        	$base_fields = M('Attribute')->where(array('model_id'=>$data['id'],'is_show'=>1))->field('id,name,title')->select();
        	//是否继承了其他模型
        	$extend_fields = array();
        	if($data['extend'] != 0){
        		$extend_fields = M('Attribute')->where(array('model_id'=>$data['extend'],'is_show'=>1))->field('id,name,title')->select();
        	}
        	$fields = array_merge($base_fields, $extend_fields);
        	//默认分组设为1
        	foreach ($fields as $key=>$value){
				$fields[$key]['group'] = 1;
        	}
        }else{						//已排序
        	//查询字段数据
			$fields_list = array();
        	foreach ($fields as $key=>$value){
        		foreach ($value as $k=>$v){
        			$info = M('Attribute')->where(array('id'=>$v))->field('id,name,title,is_show')->find();
        			if(!empty($info)){
        				$info['group'] = $key;
        				$fields_list[] = $info;
        			}
        		}
        	}
        	//检查字段分组规则是否被修改
        	$keys = array_keys($fields);
        	$group = array_keys(parse_field_attr($data['field_group']));
        	foreach ($keys as $value){
        		if(!in_array($value, $group)){
					//重置字段分组
        			foreach ($fields_list as $k=>$v){
        				$fields_list[$k]['group'] = 1;
        			}
        		}
        	}
        	$fields = $fields_list;
        }

        //获取所有的模型
    	$models = M('Model')->where(array('extend'=>0))->field('id,title')->select();

    	$this->assign('models', $models);
    	$this->assign('fields', $fields);
        $this->assign('info', $data);
        $this->meta_title = '编辑模型';
        $this->display();
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
            if($res['id']){
                $this->success('更新成功', U('index'));
            }else{
                $this->success('新增成功', U('index'));
            }
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
