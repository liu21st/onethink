<?php

namespace Addons\AdFloat;
use Common\Controller\Addon;

/**
 * 两侧浮动广告插件
 * @author birdy
 */

    class AdFloatAddon extends Addon{

        public $info = array(
            'name'=>'AdFloat',
            'title'=>'图片漂浮广告',
            'description'=>'需要先通过 http://www.onethink.cn/topic/2133.html 的方法，让插件配置支持图片上传',
            'status'=>1,
            'author'=>'birdy',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的pageFooter钩子方法
        public function pageFooter($param){
            $config = $this->getConfig();
            if($config['image'])
            {
                $this->assign('config', $config);
                $this->display('content');
            }
        }

    }