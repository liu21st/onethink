<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace app\addons\devteam;

use app\common\controller\Addon;

/**
 * 开发团队信息插件
 * 
 * @author thinkphp
 */
class DevTeam extends Addon
{

    public $info = array(
        'name' => 'DevTeam',
        'title' => '开发团队信息',
        'description' => '开发团队成员信息',
        'status' => 1,
        'author' => 'thinkphp',
        'version' => '0.1'
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }
    
    // 实现的AdminIndex钩子方法
    public function AdminIndex($param)
    {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if ($config['display'])
            return $this->fetch('widget');
    }
}