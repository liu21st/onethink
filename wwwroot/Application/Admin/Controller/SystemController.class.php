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
        array( 'title' => '基本设置', 'url' => 'System/index', 'group' => '系统设置'),
        array( 'title' => '静态规则设置', 'url' => 'System/index1', 'group' => '系统设置'),
        array( 'title' => 'SEO优化设置', 'url' => 'System/index2', 'group' => '系统设置'),
        // array( 'title' => '导航栏目设置', 'url' => 'System/index', 'group' => '导航栏目设置'),
        // array( 'title' => '其他设置', 'url' => 'System/index', 'group' => '其他设置'),
    );
	
	/**
	 * 系统管理首页
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function index(){
		$this->display();
	}

}