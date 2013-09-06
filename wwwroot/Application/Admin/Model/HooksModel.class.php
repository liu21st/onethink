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
 * @date    2013-08-14 11:31:21
 */

class HooksModel extends Model {

	/**
	 * 查找后置操作
	 */
	protected function _after_find(&$result,$options) {

	}

	protected function _after_select(&$result,$options){
        intToString($result, array('type'=>array( 1=>'view', 2=>'controller')));
		foreach($result as &$record){
			$this->_after_find($record,$options);
		}
	}
    /**
	 * 文件模型自动完成
	 * @var array
	 */
    protected $_auto = array(
    	array('update_time', NOW_TIME, self::MODEL_BOTH),
    	);

    /**
     * 更新插件里的所有钩子对应的插件
     */
    public function updateHooks($addons_name){
    	$addons_class = addons($addons_name, 1);//获取插件名
    	$methods = get_class_methods("{$addons_name}Addons");
        $hooks = $this->getField('name', true);
        $common = array_intersect($hooks, $methods);
    	if(!empty($common)){
    		foreach ($common as $hook) {
    			$flag = $this->updateAddons($hook, array($addons_name));
    			if(false === $flag){
    				$this->removeHooks($addons_name);
    				return false;
    			}
    		}
    	} else {
    		$this->error = '插件为实现任何钩子';
    		return false;
    	}
    	return true;
    }

    /**
     * 更新单个钩子处的插件
     */
    public function updateAddons($hook_name, $addons_name){
    	$o_addons = $this->where("name='{$hook_name}'")->getField('addons');
    	if($o_addons)
            $o_addons = str2arr($o_addons);
    	if($o_addons){
    		$addons = array_merge($o_addons, $addons_name);
    		$addons = array_unique($addons);
    	}else{
    		$addons = $addons_name;
    	}
    	$flag = D('Hooks')->where("name='{$hook_name}'")
    	->setField('addons',arr2str($addons));
    	if(false === $flag)
    		D('Hooks')->where("name='{$hook_name}'")
    	->setField('addons',arr2str($o_addons));
    	return $flag;
    }

    /**
     * 去除插件所有钩子里对应的插件数据
     */
    public function removeHooks($addons_name){
    	$methods = get_class_methods("{$addons_name}Addons");
        $hooks = $this->getField('name', true);
        $common = array_intersect($hooks, $methods);
    	if($common){
    		foreach ($common as $hook) {
    			$flag = $this->removeAddons($hook, array($addons_name));
    			if(false === $flag){
    				return false;
    			}
    		}
    	}
    	return true;
    }

    /**
     * 去除单个钩子里对应的插件数据
     */
    public function removeAddons($hook_name, $addons_name){
    	$o_addons = $this->where("name='{$hook_name}'")->getField('addons');
    	$o_addons = str2arr($o_addons);
    	if($o_addons){
    		$addons = array_diff($o_addons, $addons_name);
    	}else{
    		return true;
    	}
    	$flag = D('Hooks')->where("name='{$hook_name}'")
    					  ->setField('addons',arr2str($addons));
		if(false === $flag)
    		D('Hooks')->where("name='{$hook_name}'")
    				  ->setField('addons',arr2str($o_addons));
    	return $flag;
    }
}
