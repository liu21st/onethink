<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------


namespace app\admin\controller;
use think\Request;

/**
 * 后台配置控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class Config  extends Admin  {

    /**
     * 配置管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map = array();
        $map  = array('status' => 1);
        if(isset($_GET['group'])){
            $map['group']   =   input('group',0);
        }
        if(isset($_GET['name'])){
            $map['name']    =   array('like', '%'.(string)input('name').'%');
        }

        $list = $this->lists('Config', $map,'sort,id');
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('group',config('CONFIG_GROUP_LIST'));
        $this->assign('group_id',input('get.group',0));
        $this->assign('list', $list);
        $this->meta_title = '配置管理';
        return $this->fetch();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Config = model('Config');
            $data = $Config->create();
            if($data){
                if($Config->add()){
                    cache('DB_CONFIG_DATA',null);
                    $this->success('新增成功', url('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $this->meta_title = '新增配置';
            $this->assign('info',null);
            return $this->fetch('edit');
        }
    }

    /**
     * 编辑配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Config = model('Config');
            $data = $Config->create();
            if($data){
                if($Config->save()){
                    cache('DB_CONFIG_DATA',null);
                    //记录行为
                    action_log('update_config','config',$data['id'],UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = db('Config')->field(true)->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑配置';
            return $this->fetch();
        }
    }

    /**
     * 批量保存配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function save($config){
        if($config && is_array($config)){
            $Config = db('Config');
            foreach ($config as $name => $value) {
                $map = array('name' => $name);
                $Config->where($map)->setField('value', $value);
            }
        }
        cache('DB_CONFIG_DATA',null);
        $this->success('保存成功！');
    }

    /**
     * 删除配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del(){
        $id = array_unique((array)input('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(db('Config')->where($map)->delete()){
            cache('DB_CONFIG_DATA',null);
            //记录行为
            action_log('update_config','config',$id,UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    // 获取某个标签的配置参数
    public function group() {
        $id     =   $this->request->get('id',1);
        $type   =   config('CONFIG_GROUP_LIST');
        $list   =   db("Config")->where(array('status'=>1,'group'=>$id))->field('id,name,title,extra,value,remark,type')->order('sort')->select();
        if($list) {
            $this->assign('list',$list);
        }
        $this->assign('id',$id);
        $this->meta_title = $type[$id].'设置';
        return $this->fetch();
    }

    /**
     * 配置排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort(){
        if(IS_GET){
            $ids = input('get.ids');

            //获取排序的数据
            $map = array('status'=>array('gt',-1));
            if(!empty($ids)){
                $map['id'] = array('in',$ids);
            }elseif(input('group')){
                $map['group']	=	input('group');
            }
            $list = db('Config')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = '配置排序';
            return $this->fetch();
        }elseif (IS_POST){
            $ids = input('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = db('Config')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！',Cookie('__forward__'));
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}