<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;
/**
 * 后台频道控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class Channel extends Admin  {

    /**
     * 频道列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $pid = input('get.pid', 0);
        /* 获取频道列表 */
        $map  = ['status' => ['gt', -1], 'pid'=>$pid];
        $list = db('Channel')->where($map)->order('sort asc,id asc')->select();

        $this->assign('list', $list);
        $this->assign('pid', $pid);
        $this->meta_title = '导航管理';
        return $this->fetch();
    }

    /**
     * 添加频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if( request()->isPost() ){
            $Channel = model('Channel');
            $data = $Channel->isUpdate(false)->save($_POST);
            if($data){
                //记录行为
                // action_log('update_channel', 'channel', $id, UID);
                $this->success('新增成功', url('index', ['pid'=>input('pid','')]));
            } else {
                $errormsg = $Channel->getError();
                $errormsg = empty($errormsg)?'新增失败':$errormsg;
                $this->error( $errormsg );
            }
        } else {
            $pid = input('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
                $parent = db('Channel')->where('id',$pid)->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info',null);
            $this->meta_title = '新增导航';
            return $this->fetch('edit');
        }
    }

    /**
     * 编辑频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        if( request()->isPost() ){
            $Channel = model('Channel');
            $data = $Channel->isUpdate(true)->save($_POST);
            if($data){
                //记录行为
                // action_log('update_channel', 'channel', $data['id'], UID);
                $this->success('编辑成功', url('index'));
            } else {
                $errormsg = $Channel->getError();
                $errormsg = empty($errormsg)?'编辑失败':$errormsg;
                $this->error( $errormsg );
            }
        } else {
            $info = [];
            /* 获取数据 */
            $info = db('Channel')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $pid = input('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
            	$parent = db('Channel')->where('id',$pid)->field('title')->find();
            	$this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            return $this->fetch();
        }
    }

    /**
     * 删除频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del(){
        $id = array_unique((array)input('id/a',0));

        if( is_array($id) && $id[0]==0 ) {
            $this->error('请选择要操作的数据!');
        }
        $map = ['id' => ['in', $id] ];
        if(db('Channel')->where($map)->delete()){
            //记录行为
            // action_log('update_channel', 'channel', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * 导航排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort(){
        if( request()->isGet() ){
            $ids = input('get.ids');
            $pid = input('get.pid', 0);

            //获取排序的数据
            $map = ['status'=>['gt',-1]];
            if(!empty($ids)){
                $map['id'] = ['in',$ids];
            }else{
                if($pid !== ''){
                    $map['pid'] = $pid;
                }
            }
            $list = db('Channel')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = '导航排序';
            return $this->fetch();
        }elseif ( request()->isPost() ){
            $ids = input('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = db('Channel')->where('id', $value)->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}