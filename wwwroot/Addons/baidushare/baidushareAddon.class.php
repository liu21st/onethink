<?php

namespace Addons\baidushare;
use Common\Controller\Addon;

/**
 * 百度分享插件
 * @author 啊不名字
 */

    class baidushareAddon extends Addon{

        public $custom_config = 'config.html';

        public $info = array(
            'name'=>'baidushare',
            'title'=>'百度分享',
            'description'=>'用户将网站内容分享到第三方网站，第三方网站的用户点击专有的分享链接，从第三方网站带来社会化流量。',
            'status'=>1,
            'author'=>'jesuspan',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的documentDetailAfter钩子方法
        public function documentDetailAfter($param){
            $this->assign('addons_config', $this->getConfig());
            $this->display('share');
        }

    }