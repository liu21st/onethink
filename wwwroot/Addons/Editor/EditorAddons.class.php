<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

/**
 * 编辑器插件
 * @author yangweijie <yangweijiester@gmail.com>
 */

	class EditorAddons extends Addons{

		public function install(){
			return true;
		}

		public function uninstall(){
			return true;
		}

		/**
		 * 编辑器挂载的文章内容钩子
		 * @param array('name'=>'表单name','value'=>'表单对应的值')
		 */
		static public function documentEditFormContent($data){
			$addons = addons('Editor');
			$addons->assign('data', $data);
			$addons->assign('config', $addons->getConfig());
			$addons->display('content');
		}
	}