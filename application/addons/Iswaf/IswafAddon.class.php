<?php

namespace Addons\Iswaf;
use Common\Controller\Addon;

/**
 * 防护云插件
 * @author thinkphp
 */

    class IswafAddon extends Addon{

        public $info = array(
            'name'=>'Iswaf',
            'title'=>'防护云',
            'description'=>'仅需一次简单设置，即可全面开启安全云防护，让你的网站和业务不再遭受各种漏洞和攻击带来的困扰！',
            'status'=>1,
            'author'=>'thinkphp',
            'version'=>'0.1'
        );

        public $admin_list = array(
            'model'=>'Hooks',		//要查的表
			'fields'=>'*',			//要查的字段
			'map'=>'',				//查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
			'order'=>'id desc',		//排序,
			'listKey'=>array( 		//这里定义的是除了id序号外的表格里字段显示的表头名
				'字段名'=>'表头显示名'
			),
        );

        public $custom_adminlist = 'admin.html';

        public function install(){
            $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
            $key = md5('&*<1%1>qw</1%1>'. $host.time());
            $conf_file = <<<str
<?php
 define('iswaf_connenct_key','{$key}');
 define('iswaf_connenct_api','http://www.fanghuyun.com/client.php');
 ?>
str;
            # 更新配置文件
            file_put_contents(__DIR__.'/iswaf/conf/conf.php', $conf_file);
            # 验证
            $index_url = U('/','','','',true);
            $document_path = realpath('.');
            $valid_url = "http://www.fanghuyun.com/api.php?do=onethinkreg&IDKey={$key}&url={$index_url}&documentroot={$document_path}";
            $res = file_get_contents($valid_url);
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的pageHeader钩子方法
        public function app_begin($param){
            define('iswaf_database', RUNTIME_PATH.'iswaf_database/');
            include __DIR__.'/iswaf/iswaf.php';
        }
    }