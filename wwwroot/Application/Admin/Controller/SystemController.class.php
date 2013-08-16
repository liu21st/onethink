<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 后台系统控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class SystemController extends AdminController {

	/**
     * 左侧导航节点定义
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    static protected $nodes = array(
        array( 'title' => '管理首页', 'url' => 'Index/index', 'group' => '常用菜单'),
        array( 'title' => '表单样式', 'url' => 'Index/form', 'group' => '常用菜单'),
    );
	
	/**
	 * 系统管理首页
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function index(){
		$this->display();
	}

}