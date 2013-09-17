<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
/**
 * 插件模型
 * @author yangweijie <yangweijiester@gmail.com>
 */

class AddonsModel extends Model {

    /**
     * 查找后置操作
     */
    protected function _after_find(&$result,$options) {
        $addons = addons($result['name']);
        if($addons){
            $result['addon_path'] = $addons->addon_path;
            $result['custom_config'] = $addons->custom_config;
        }
    }

    protected function _after_select(&$result,$options){
        intToString($result, array('status'=>array(-1=>'损坏', 0=>'禁用', 1=>'启用')));
        foreach($result as &$record){
            $this->_after_find($record,$options);
        }
    }
    /**
     * 文件模型自动完成
     * @var array
     */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    /**
     * 获取插件列表
     * @param string $addon_dir
     */
    public function getList($addon_dir = ''){
        if(!$addon_dir)
            $addon_dir = ONETHINK_ADDON_PATH;
        $dirs = array_map('basename',glob($addon_dir.'*', GLOB_ONLYDIR));
        if($dirs === FALSE || !file_exists($addon_dir)){
            $this->error = '插件目录不可读或者不存在';
            return FALSE;
        }
		$addons			=	array();
		$where['name']	=	array('in',$dirs);
		$list			=	$this->where($where)->field(true)->select();
		foreach($list as $addon){
			$addon['uninstall']		=	0;
			$addons[$addon['name']]	=	$addon;
		}
        foreach ($dirs as $value) {
            if(!isset($addons[$value])){
				$obj			=	addons($value);
				if(!$obj){ // 实例化插件失败忽略执行
					\Think\Log::record('插件'.$value.'的入口文件不存在！');
					continue;
				}
				$addons[$value]	=	$obj->info;
				if($addons[$value]){
					$addons[$value]['uninstall']	=	1;
				}
			}
        }
        intToString($addons, array('status'=>array(-1=>'损坏', 0=>'禁用', 1=>'启用')));
        $addons = list_sort_by($addons,'uninstall','desc');
        return $addons;
    }

    /**
     * 获取插件的后台列表
     */
    public function getAdminList(){
        $db_addons = $this->where("status=1 AND has_adminlist=1")->field('title,name')->select();
        foreach ($db_addons as $value) {
            $admin[] = array('title'=>$value['title'],'url'=>"Addons/adminList?name={$value['name']}");
        }
        return $admin;
    }
}
