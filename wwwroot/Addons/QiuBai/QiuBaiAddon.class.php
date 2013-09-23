<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace Addons\QiuBai;

use Common\Controller\Addon;

/**
 * 系统环境信息插件
 * @author thinkphp
 */
class QiuBaiAddon extends Addon {

    public $info = array(
        'name' => 'QiuBai',
        'title' => '糗事百科',
        'description' => '读别人的糗事，娱乐自己',
        'status' => 1,
        'author' => 'thinkphp',
        'version' => '0.1'
    );

    public function install() {
        if(!extension_loaded('curl')){
            session('addons_install_error', 'PHP的CURL扩展未开启');
            return false;
        }
        return true;
    }

    public function uninstall() {
        return true;
    }

    //实现的AdminIndex钩子方法
    public function AdminIndex($param) {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if ($config['display'])
            $this->display('widget');
    }

}

