<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace Addons\Editor;
use Common\Controller\Addon;

/**
 * 编辑器插件
 * @author yangweijie <yangweijiester@gmail.com>
 */

	class EditorAddon extends Addon{

		public $info = array(
				'name'=>'Editor',
				'title'=>'前台编辑器',
				'description'=>'用于增强整站长文本的输入和显示',
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

		/**
		 * 编辑器挂载的文章内容钩子
		 * @param array('name'=>'表单name','value'=>'表单对应的值')
		 */
		public function documentEditFormContent($data){
			$this->assign('addons_data', $data);
			$this->assign('addons_config', $this->getConfig());
			$this->display('content');
		}

		/**
		 * 讨论提交的钩子使用编辑器插件扩展
		 * @param array('name'=>'表单name','value'=>'表单对应的值')
		 */
		public function topicComment ($data){
			$this->assign('addons_data', $data);
			$this->assign('addons_config', $this->getConfig());
			$this->display('content');
		}

	}
