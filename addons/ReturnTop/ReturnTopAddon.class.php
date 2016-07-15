<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace Addons\ReturnTop;
use Common\Controller\Addon;

/**
 * 编辑器插件
 * @author yangweijie <yangweijiester@gmail.com>
 */

	class ReturnTopAddon extends Addon{

		public $custom_config = 'config.html';

		public $info = array(
				'name'=>'ReturnTop',
				'title'=>'返回顶部',
				'description'=>'回到顶部美化，随机或指定显示，100款样式，每天一种换，天天都用新样式',
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
		public function pageFooter($data){
			$this->assign('addons_data', $data);
			$config = $this->getConfig();
			if($config['random'])
				$config['current'] = rand(1,99);
			$this->assign('addons_config', $config);
			$this->display('content');
		}
	}
