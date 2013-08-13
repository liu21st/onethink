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
class IndexController extends Action {
    /**
     * 后台首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        if(is_login()){
            $this->display();
        } else {
            $this->redirect('login');
        }
    }

    /**
     * 后台默认页面（欢迎页面）
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function welcome(){
        if(is_login()){
            $this->display();
        } else {
            $this->redirect('login');
        }
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
