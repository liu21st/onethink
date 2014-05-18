<?php

namespace Addons\Timeline;
use Common\Controller\Addon;

/**
 * 工作时间轴插件
 * @author yangweijie
 */

    class TimelineAddon extends Addon{

        public function __construct(){
           parent::__construct();
           include_once $this->addon_path.'function.php';
        }

        public $info = array(
            'name'=>'Timeline',
            'title'=>'工作时间轴',
            'description'=>'个人工作经历时间轴展示',
            'status'=>0,
            'author'=>'yangweijie',
            'version'=>'0.1'
        );

        public $admin_list = array(
            'model'=>'Timeline',		//要查的表
			'fields'=>'*',			//要查的字段
			'map'=>'',				//查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
			'order'=>'id asc',		//排序,
            'list_grid'=>array(//这里定义的是除了id序号外的表格里字段显示的表头名，规则和模型里的规则一样
                'cover_id|preview_pic:媒体',
                'title:事件名',
                'startDate:开始日期',
                'endDate:结束日期',
                'author:媒体作者',
                'media_title:媒体标题',
                'id:操作:[EDIT]|编辑,[DELETE]|删除'
            )
        );

       public function install(){
            $sql = file_get_contents($this->addon_path . 'install.sql');
            $db_prefix = C('DB_PREFIX');
            $sql = str_replace('onethink_', $db_prefix, $sql);
            D()->execute($sql);
            $table_name = $db_prefix.'timeline';
            if(count(M()->query("SHOW TABLES LIKE '{$table_name}'")) != 1){
                session('addons_install_error', ',timeline表未创建成功，请手动检查插件中的sql，修复后重新安装');
                return false;
            }
            return true;
        }

        public function uninstall(){
            $db_prefix = C('DB_PREFIX');
            $sql = "DROP TABLE IF EXISTS `{$db_prefix}timeline`;";
            D()->execute($sql);
            return true;
        }

        //实现的single钩子方法
        public function single($param){
            if($param['name'] == 'Timeline'){
                $this->display('single');
            }
        }

    }
