<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class IndexController extends AdminController {

    /**
     * 左侧导航节点定义
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    static protected $nodes = array(
        array( 'title' => '管理首页', 'url' => 'Index/index', 'group' => '常用菜单'),
        array( 'title' => '表单样式', 'url' => 'Index/form', 'group' => '常用菜单'),
    );

    /**
     * 登录页面不检查权限
     */
    protected function _initialize(){
        if(ACTION_NAME !== 'login'){
            parent::_initialize();
        }
    }

    /**
     * 后台首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $this->display();
    }

    //表单样式查看页面 TODO: 完成后会删除
    public function form(){
        $this->display("static_form_tpl");
    }

    /**
     * 后台用户登录
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function login(){
        if(IS_POST){
            //TODO: 后台用户登录
        } else {
            $this->display();
        }
    }
}
