<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台配置控制器
 * @author yangweijie <yangweijiester@gmail.com>
 */

class MenuController extends AdminController {

    /**
     * 后台菜单首页
     * @return none
     */
    public function index(){
        $pid  = I('get.pid',0);
        $title = trim(I('get.title'));
        $type = C('CONFIG_GROUP_LIST');
        $all_menu = M('Menu')->getField('id,title');
        $map = array(
            'pid'=>$pid
        );
        if($title)
            $map['title'] = array('like',"%{$title}%");
        $list = M("Menu")->where($map)->field(true)->order('sort asc')->select();
        int_to_string($list,array('hide'=>array(1=>'是',0=>'否'),'is_dev'=>array(1=>'是',0=>'否')));
        if($list) {
            foreach($list as &$key){
                $key['up_title'] = $all_menu[$key['pid']];
            }
            $this->assign('list',$list);
        }
        $this->meta_title = '后台菜单列表';
        $this->display();
    }

    /**
     * 新增彩电
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Menu = D('Menu');
            $data = $Menu->create();
            if($data){
                if($Menu->add()){
                    // S('DB_CONFIG_DATA',null);
                    $this->success('新增成功', U('index?pid='.I('pid')));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Menu->getError());
            }
        } else {
            $this->assign('info',array('pid'=>I('pid')));
            $menus = M('Menu')->field(true)->select();
            $menus = D('Common/Tree')->toFormatTree($menus);
            $this->assign('Menus', $menus);
            $this->meta_title = '新增菜单';
            $this->display('edit');
        }
    }

    /**
     * 编辑配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Menu = D('Menu');
            $data = $Menu->create();
            if($data){
                if($Menu->save()!== false){
                    // S('DB_CONFIG_DATA',null);
                    $this->success('更新成功', U('index?pid='.$data['pid']));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Menu->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Menu')->field(true)->find($id);
            $menus = M('Menu')->field(true)->select();
            $menus = D('Common/Tree')->toFormatTree($menus);
            $this->assign('Menus', $menus);
            if(false === $info){
                $this->error('获取后台菜单信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑后台菜单';
            $this->display();
        }
    }

    /**
     * 删除后台菜单
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Menu')->where($map)->delete()){
            // S('DB_CONFIG_DATA',null);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    public function import($tree = null, $pid=0){
        if($tree == null){
            $file = APP_PATH."Admin/Conf/Menu.php";
            $tree = require_once($file);
        }
        $menuModel = D('Menu');
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
}
