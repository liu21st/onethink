<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
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
    	/* 系统设置 */
        array( 'title' => '基本设置', 'url' => 'System/index', 'group' => '系统设置'),
        // array( 'title' => '静态规则设置', 'url' => 'System/index1', 'group' => '系统设置'),
        // array( 'title' => 'SEO优化设置', 'url' => 'System/index2', 'group' => '系统设置'),
        
        /* 导航栏目设置 */
        array( 'title' => '导航管理', 'url' => 'System/channel', 'group' => '导航栏目设置'),

        /* 其他设置 */
        // array( 'title' => '数据迁移', 'url' => 'System/index5', 'group' => '其他设置'),
        // array( 'title' => '数据备份/恢复', 'url' => 'System/index6', 'group' => '其他设置'),
        // array( 'title' => '系统日志', 'url' => 'System/index7', 'group' => '其他设置'),
    );
	
	/**
	 * 系统管理首页
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function index(){
		$this->display();
	}

    /* 频道管理 */
    public function channel(){
        /* 获取频道列表 */
        $map  = array('status' => 1);
        $list = M('Channel')->where($map)->select();

        $this->assign('list', $list);
        $this->display();
    }

}
