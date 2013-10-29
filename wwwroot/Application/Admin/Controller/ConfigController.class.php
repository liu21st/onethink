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
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class ConfigController extends AdminController {

    /**
     * 左侧导航节点定义
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    static protected $nodes = array(
        array( 'title' => '网站设置', 'url' => 'Config/group', 'group' => '系统设置'),
        array( 'title' => '配置管理', 'url' => 'Config/index', 'group' => '系统设置',
            'operator'=>array(
                array('title'=>'编辑','url'=>'Config/edit','tip'=>'新增编辑和保存配置'),
                array('title'=>'删除','url'=>'Config/del','tip'=>'删除配置'),
            	array('title'=>'新增','url'=>'Config/add','tip'=>'新增配置'),
            	array('title'=>'保存','url'=>'Config/save','tip'=>'保存配置'),
            ),
        ),
        array( 'title' => '后台菜单管理', 'url' => 'Config/menu', 'group' => '系统设置'),
        // array( 'title' => '静态规则设置', 'url' => 'System/index1', 'group' => '系统设置'),
        // array( 'title' => 'SEO优化设置', 'url' => 'System/index2', 'group' => '系统设置'),
    );

    /**
     * 配置管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
    	/* 查询条件初始化 */
    	$map = array();
        $map  = array('status' => 1);
        if(isset($_GET['name'])){
        	$map['name']  = array('like', '%'.(string)I('name').'%');
        }

		$list = $this->lists('Config', $map);

        $this->assign('list', $list);
        $this->meta_title = '配置管理';
        $this->display();
    }

    /**
     * 新增配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Config = D('Config');
            $data = $Config->create();
            if($data){
                if($Config->add()){
					S('DB_CONFIG_DATA',null);
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $this->meta_title = '新增配置';
            $this->display('edit');
        }
    }

    /**
     * 编辑配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Config = D('Config');
            $data = $Config->create();
            if($data){
                if($Config->save()){
					S('DB_CONFIG_DATA',null);
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Config')->field(true)->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }
            $this->assign('info', $info);
            $this->meta_title = '编辑配置';
            $this->display();
        }
    }

    /**
     * 批量保存配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function save($config){
        if($config && is_array($config)){
            $Config = M('Config');
            foreach ($config as $name => $value) {
                $map = array('name' => $name);
                $Config->where($map)->setField('value', $value);
            }
        }
		S('DB_CONFIG_DATA',null);
        $this->success('保存成功！');
    }

    /**
     * 删除配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Config')->where($map)->delete()){
			S('DB_CONFIG_DATA',null);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    // 获取某个标签的配置参数
    public function group() {
        $id     =   I('get.id',0);
        $type   =   C('CONFIG_GROUP_LIST');
        $list   =   M("Config")->where(array('status'=>1,'group'=>$id))->field('id,name,title,extra,value,remark,type')->order('sort')->select();
        if($list) {
            $this->assign('list',$list);
        }
        $this->meta_title = $type[$id].'设置';
        $this->display();
    }

    /**
     * 后台菜单首页
     * @return none
     */
    public function menu(){
        $pid  = I('get.pid',0);
        $title = trim(I('get.title'));
        $type = C('CONFIG_GROUP_LIST');
        $all_menu = M('Menu')->getField('id,title');
        $map = array(
            'pid'=>$pid
        );
        if($title)
            $map['title'] = array('like',"%{$title}%");
        $list = M("Menu")->where($map)->order('sort')->select();
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
    public function addMenu(){
        if(IS_POST){
            $Menu = D('Menu');
            $data = $Menu->create();
            if($data){
                if($Menu->add()){
                    // S('DB_CONFIG_DATA',null);
                    $this->success('新增成功', U('menu'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Menu->getError());
            }
        } else {
            $menus = M('Menu')->field(true)->select();
            $menus = D('Common/Tree')->toFormatTree($menus);
            $this->assign('Menus', $menus);
            $this->meta_title = '新增菜单';
            $this->display('editMenu');
        }
    }

    /**
     * 编辑配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function editMenu($id = 0){
        if(IS_POST){
            $Menu = D('Menu');
            $data = $Menu->create();
            if($data){
                if($Menu->save()!== false){
                    // S('DB_CONFIG_DATA',null);
                    $this->success('更新成功', U('menu?pid='.$data['pid']));
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
    public function delMenu(){
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
