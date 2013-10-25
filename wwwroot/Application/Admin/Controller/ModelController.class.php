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

        //获取模型字段
        if(empty($data['fields'])){
        	$base = array();
			if($data['type'] == 2){
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

        //获取所有的模型
    	$models = M('Model')->where(array('extend'=>0))->field('id,title')->select();

    	$this->assign('models', $models);
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
