<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

/**
 * 插件模型
 * @author yangweijie <yangweijiester@gmail.com>
 * @date    2013-08-14 11:31:21
 */

class AddonsModel extends Model {

	/**
	 * 查找后置操作
	 */
	protected function _after_find(&$result,$options) {
		$result['status_text_arr'] = array(-1=>'损坏', 0=>'禁用', 1=>'启用');
		$result['status_text'] = $result['status_text_arr'][$result['status']];
	}

	protected function _after_select(&$result,$options){
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
			$addon_dir = C('EXTEND_MODULE.Addons');
		$dir = getcwd();
		chdir($addon_dir);
		$addons_names = glob('*', GLOB_ONLYDIR);
		$addons = array();
		foreach ($addons_names as $value) {
			$addons[] = $this->getAddonsInfo($value);
		}
		chdir($dir);
		return $addons;
	}

	/**
	 * 获取插件信息
	 */
	public function getAddonsInfo($name){
		$info = $this->where("name='{$name}'")->find();
		return $info;
	}
}