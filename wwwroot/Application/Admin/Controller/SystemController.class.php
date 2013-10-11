<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Db;

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
        /* 其他设置 */
        // array( 'title' => '数据迁移', 'url' => 'System/index5', 'group' => '其他设置'),
        // array( 'title' => '数据备份/恢复', 'url' => 'System/database', 'group' => '其他设置'),
        // array( 'title' => '系统日志', 'url' => 'System/index7', 'group' => '其他设置'),
    );

}
