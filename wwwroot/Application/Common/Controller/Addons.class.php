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
	abstract class Addons{
 		/**
	     * 视图实例对象
	     * @var view
	     * @access protected
	     */
	    protected $view = null;

	    public $info = array();
		public $addon_path = '';
		public $config_file = '';
		public $access_url = array();

		public function __construct(){
			// if(!in_array(CONTROLLER_NAME,$this->access_url)){
			// 	$this->error('非插件内部访问');
			// }
			$this->view = Think::instance('View');
			$this->addon_path = C('EXTEND_MODULE.Addons').$this->getName().'/';
			if(is_file($this->addon_path.'config.php')){
				$this->config_file = $this->addon_path.'config.php';
			}
		}

	    /**
	     * 模板主题设置
	     * @access protected
	     * @param string $theme 模版主题
	     * @return Action
	     */
	    final protected function theme($theme){
	        $this->view->theme($theme);
	        return $this;
	    }

		//显示方法
		final protected function display($template=''){
			if($template == '')
				$template = CONTROLLER_NAME;
			echo ($this->fetch($template));
		}

	    /**
	     * 模板变量赋值
	     * @access protected
	     * @param mixed $name 要显示的模板变量
	     * @param mixed $value 变量的值
	     * @return Action
	     */
	    final protected function assign($name,$value='') {
	        $this->view->assign($name,$value);
	        return $this;
	    }


		//用于显示模板的方法
		final protected function fetch($templateFile = CONTROLLER_NAME){
			if(!is_file($templateFile)){
				$templateFile = $this->addon_path.$templateFile.C('TMPL_TEMPLATE_SUFFIX');
				if(!is_file($templateFile)){
					throw new Exception("模板不存在:$templateFile");
				}else{
					return $this->view->fetch($templateFile);;
				}
			}
		}

		final public function getName(){
			$class = get_class($this);
			return substr($class, 0, -6);//插件类必须为XXAddons为后缀
		}

		final public function checkInfo(){
			$info_check_keys = array('name','title','description','status','author','version');
			foreach ($info_check_keys as $value) {
				if(!array_key_exists($value, $this->info))
					return FALSE;
			}
			return TRUE;
		}

		/**
		 * 获取插件的配置数组
		 */
		final public function getConfig(){
			$config = D('Addons')->where("name='{$this->getName()}'")->find();
			if($config['config'] && is_string($config['config'])){
				$config['config'] = json_decode($config['config'], 1);
			}else{
				$config['config'] = include $this->config_file;
			}
			$config['config']['status'] = $config['status'];
			return $config['config'];
		}

		//必须实现安装
		abstract public function install();

		//必须卸载插件方法
		abstract public function uninstall();
	}