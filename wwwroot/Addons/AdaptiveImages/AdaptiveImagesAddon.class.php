<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

/**
 * 响应式图片处理插件
 * @author yangweijie
 */

namespace Addons\AdaptiveImages;
use Common\Controller\Addon;

    class AdaptiveImagesAddon extends Addon{

        public $info = array(
            'name'=>'AdaptiveImages',
            'title'=>'手机端响应式图片处理',
            'description'=>'通过检测手机的宽度，在小设备访问图片时返回合适尺寸的小图片，到小尺寸设备达到图片响应式。',
            'status'=>0,
            'author'=>'thinkphp',
            'version'=>'0.1',
            'url'=> 'http://www.thinkphp.cn'
        );

        public function install(){
            if(file_exists('./.htaccess')){
                $content = file_get_contents('./.htaccess');
                if(stripos($content, 'AdaptiveImages') == false){
                    session('addons_install_error', ',失败原因，站点.htaccess文件里人没有插件相关代码，请手动添加，参见插件目录下的.htaccess文件里的内容');
                    return false;
                }
            }else{
                if(!copy($this->addons_path.'', '.htaccess')){
                    session('addons_install_error', ',失败原因，创建站点.htaccess文件失败，请手动将插件目录下的.htaccess文件里的内容，添加到站点根目录');
                    return false;
                }
            }

            return true;
        }

        public function uninstall(){
            if(file_exists('./.htaccess')){
                $content = file_get_contents('.htaccess');
                if(stripos($content, 'AdaptiveImages') !== false){
                    session('addons_uninstall_error', ',失败原因，站点.htaccess文件里人有插件相关代码，请手动去除，参见插件目录下的.htaccess文件里的内容');
                    return false;
                }
            }
            return true;
        }

        //实现的pageHeader钩子方法
        public function pageHeader($param){
            echo "<script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>".PHP_EOL;
        }
    }