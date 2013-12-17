<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------


namespace Addons\SocialComment;
use Common\Controller\Addon;

/**
 * 通用社交化评论插件
 * @author thinkphp
 */

    class SocialCommentAddon extends Addon{

        public $info = array(
            'name'=>'SocialComment',
            'title'=>'通用社交化评论',
            'description'=>'集成了各种社交化评论插件，轻松集成到系统中。',
            'status'=>1,
            'author'=>'thinkphp',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的pageFooter钩子方法
        public function documentDetailAfter($param){
            $this->assign('addons_config', $this->getConfig());
            $this->display('comment');
        }
    }