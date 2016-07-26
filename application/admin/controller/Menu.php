<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

/**
 * 后台配置控制器
 * @author yangweijie <yangweijiester@gmail.com>
 */
class Menu extends Admin {

    /**
     * 后台菜单首页
     * @return none
     */
    public function index(){
        $pid  = input('get.pid',0);
        if($pid){
            $data = db('Menu')->where('id',$pid)->field(true)->find();
            $this->assign('data',$data);
        }
        $title      =   trim(input('get.title'));
        $type       =   config('CONFIG_GROUP_LIST');
        $all_menu   =   db('Menu')->column('id,title');
        $map['pid'] =   $pid;
        if($title)
            $map['title'] = array('like',"%{$title}%");
        $list       =   db("Menu")->where($map)->field(true)->order('sort asc,id asc')->select();
        int_to_string($list,array('hide'=>array(1=>'是',0=>'否'),'is_dev'=>array(1=>'是',0=>'否')));
        if($list) {
            foreach($list as &$key){
                if($key['pid']){
                    $key['up_title'] = $all_menu[$key['pid']];
                }
            }
            $this->assign('list',$list);
        }
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->meta_title = '菜单列表';
        return $this->fetch();
    }

    /**
     * 新增菜单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function add(){
        if( request()->isPost() ){
            $Menu = model('Menu');
            $data = $Menu->isUpdate(false)->save($_POST);
            if( $data ){
                session('ADMIN_MENU_LIST',null);
                //记录行为
                // action_log('update_menu', 'Menu', $id, UID);
                return $this->success('新增成功', Cookie('__forward__'));
            } else {
                $errormsg = $Menu->getError();
                $errormsg = empty($errormsg)?'新增失败':$errormsg;
                return $this->error( $errormsg );
            }
        } else {
            $this->assign('info',array('pid'=>input('pid')));
            $menus = db('Menu')->field(true)->select();
            $menus = model('Common/Tree')->toFormatTree($menus);
            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            $this->assign('Menus', $menus);
            $this->meta_title = '新增菜单';
            return $this->fetch('edit');
        }
    }

    /**
     * 编辑配置
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function edit($id = 0){
        if( request()->isPost() ){
            $Menu = model('Menu');
            $data = $Menu->isUpdate(true)->save($_POST);
            if($data){
                session('ADMIN_MENU_LIST',null);
                //记录行为
                // action_log('update_menu', 'Menu', $data['id'], UID);
                return $this->success('更新成功', Cookie('__forward__'));
            } else {
                $errormsg = $Menu->getError();
                $errormsg = empty($errormsg)?'更新失败':$errormsg;
                return $this->error( $errormsg );
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = db('Menu')->field(true)->find($id);
            $menus = db('Menu')->field(true)->select();
            $menus = model('Common/Tree')->toFormatTree($menus);

            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            $this->assign('Menus', $menus);
            if(false === $info){
                return $this->error('获取后台菜单信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑后台菜单';
            return $this->fetch();
        }
    }

    /**
     * 删除后台菜单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function del(){
        $id = array_unique((array)input('id/a',0));

        $map = array('id' => array('in', $id) );
        if(db('Menu')->where($map)->delete()){
            session('ADMIN_MENU_LIST',null);
            //记录行为
            // action_log('update_menu', 'Menu', $id, UID);
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败！');
        }
    }

    public function toogleHide($id,$value = 1){
        session('ADMIN_MENU_LIST',null);
        return $this->editRow('Menu', array('hide'=>$value), array('id'=>$id), '');
    }

    public function toogleDev($id,$value = 1){
        session('ADMIN_MENU_LIST',null);
        return $this->editRow('Menu', array('is_dev'=>$value), array('id'=>$id), '');
    }

    public function importFile($tree = null, $pid=0){
        if($tree == null){
            $file = APP_PATH."Admin/Conf/Menu.php";
            $tree = require_once($file);
        }
        $menuModel = model('Menu');
        foreach ($tree as $value) {
            $add_pid = $menuModel->add(
                array(
                    'title'=>$value['title'],
                    'url'=>$value['url'],
                    'pid'=>$pid,
                    'hide'=>isset($value['hide'])? (int)$value['hide'] : 0,
                    'tip'=>isset($value['tip'])? $value['tip'] : '',
                    'group'=>$value['group'],
                )
            );
            if($value['operator']){
                $this->import($value['operator'], $add_pid);
            }
        }
    }

    public function import(){
        if( request()->isPost() ){
            $tree = input('post.tree');
            $lists = explode(PHP_EOL, $tree);
            $menuModel = db('Menu');
            if($lists == array()){
                $this->error('请按格式填写批量导入的菜单，至少一个菜单');
            }else{
                $pid = input('post.pid');
                foreach ($lists as $key => $value) {
                    $record = explode('|', $value);
                    if(count($record) == 2){
                        $menuModel->add(array(
                            'title'=>$record[0],
                            'url'=>$record[1],
                            'pid'=>$pid,
                            'sort'=>0,
                            'hide'=>0,
                            'tip'=>'',
                            'is_dev'=>0,
                            'group'=>'',
                        ));
                    }
                }
                session('ADMIN_MENU_LIST',null);
                $this->success('导入成功',url('index?pid='.$pid));
            }
        }else{
            $this->meta_title = '批量导入后台菜单';
            $pid = (int)input('get.pid');
            $this->assign('pid', $pid);
            $data = db('Menu')->where("id={$pid}")->field(true)->find();
            $this->assign('data', $data);
            return $this->fetch();
        }
    }

    /**
     * 菜单排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort(){
        if( request()->isGet() ){
            $ids = input('get.ids');
            $pid = input('get.pid');

            //获取排序的数据
            $map = array('status'=>array('gt',-1));
            if(!empty($ids)){
                $map['id'] = array('in',$ids);
            }else{
                if($pid !== ''){
                    $map['pid'] = $pid;
                }
            }
            $list = db('Menu')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = '菜单排序';
            return $this->fetch();
        }elseif ( request()->isPost() ){
            $ids = input('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = db('Menu')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                session('ADMIN_MENU_LIST',null);
                return $this->success('排序成功！');
            }else{
                return $this->error('排序失败！');
            }
        }else{
            return $this->error('非法请求！');
        }
    }
}