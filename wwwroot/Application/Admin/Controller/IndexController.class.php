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
        array( 'title' => '基本设置', 'url' => 'System/index', 'group' => '系统设置'),
        array( 'title' => '静态规则设置', 'url' => 'System/index', 'group' => '系统设置'),
        array( 'title' => 'SEO优化设置', 'url' => 'System/index', 'group' => '系统设置'),
        // array( 'title' => '导航栏目设置', 'url' => 'System/index', 'group' => '导航栏目设置'),
        // array( 'title' => '其他设置', 'url' => 'System/index', 'group' => '其他设置'),
    );

    /**
     * 后台首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        if(is_administrator()){
            $this->display();
        } else {
            $this->redirect('login');
        }
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
