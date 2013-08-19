<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

/**
 * 插件类
 * @author yangweijie <yangweijiester@gmail.com>
 */
	abstract class Addons extends Action{
		public $addon_path = '';
		public $config_file = '';
		public $access_url = array();

		public function _initialize(){
			// if(!in_array(CONTROLLER_NAME,$this->access_url)){
			// 	$this->error('非插件内部访问');
			// }
			$this->addon_path = C('EXTEND_MODULE.Addons').$this->getName().'/';
			if(is_file($this->addon_path.'config.php')){
				$this->config_file = $this->addon_path.'config.php';
			}
		}

		public function getName(){
			$class = get_class($this);
			return substr($class, 0, -6);//插件类必须为XXAddons为后缀
		}

		//必须实现安装
		abstract protected function install();

		/**
		 * 获取插件的配置数组
		 */
		public function getConfig(){
			$config = D('Addons')->where("name='{$this->getName()}'")->find();
			if($config['config'] && is_string($config['config'])){
				$config['config'] = json_decode($config['config'], 1);
			}
			$config['config']['status'] = $config['status'];
			return $config;
		}

		//显示方法
		public function display($template=''){
			if($template == '')
				$template = CONTROLLER_NAME;
			echo ($this->fetch($template));
		}

		//用于显示模板的方法
		public function fetch($templateFile = CONTROLLER_NAME){
			if(!is_file($templateFile)){
				$templateFile = $this->addon_path.$templateFile.C('TMPL_TEMPLATE_SUFFIX');
				if(!is_file($templateFile)){
					throw new Exception("模板不存在:$templateFile");
				}else{
					return $this->view->fetch($templateFile);;
				}
			}
		}

		//必须卸载插件方法
		abstract protected function uninstall();
	}