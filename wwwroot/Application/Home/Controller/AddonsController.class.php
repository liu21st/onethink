<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 扩展控制器
 * 用于调度各个扩展的URL访问需求
 */
class AddonsController extends Action{

	protected $addons = null;

	public function __construct(){
		parent::__construct();
		$class = get_class($this);
		if(substr($class, -10) == 'Controller'){
			$this->addons = substr($class, 0, -10);
		} elseif(substr($class, -6) == 'Widget') {
			$this->addons = substr($class, 0, -6);
		}
	}

	public function execute($_addons = null, $_controller = null, $_action = null){
		if(C('URL_CASE_INSENSITIVE')){
			$_addons = ucfirst(strtolower($_addons));
			$_controller = parse_name($_controller,1);
		}

		if(!empty($_addons) && !empty($_controller) && !empty($_action)){
			$Addons = A("Addons://{$_addons}/{$_controller}")->setName($_addons)->$_action();
		} else {
			$this->error('没有指定插件名称，控制器或操作！');
		}
	}

	protected function display($templateFile='',$charset='',$contentType='',$content='',$prefix='') {
		if(!is_file($templateFile)){
			$templateFile = T("Addons://{$this->addons}@{$templateFile}");
			if(!is_file($templateFile)){
				throw new Exception("模板不存在:$templateFile");
			}
		}

        $this->view->display($templateFile,$charset,$contentType,$content,$prefix);
    }

    /**
     * 设置当前插件名称
     * @param string $name 插件名称
     */
    protected function setName($name){
    	$this->addons = $name;
    	return $this;
    }

    /**
     * 获取所有钩子列表
     */
    static public function getHooks($field='', $order=''){
    	return D('Hooks')->field($field)->order($order)->select();
    }

    /**
     * 读取配置页
     */
    public function config(){
    	$config = D('Addons')->where(array('id'=>I('get.id')))->getField('config');
    	$this->assign('config', $config);
    	$this->display('Config/config');
    }

    /**
     * 保存插件配置
     */
    public function saveConfig(){
    	$id = (int)I('post.id');
    	if(!$id)
    		$this->error('错误的主键');
    	if(!$table)
    		$this->error("写入的配置表{$model},初始化失败");
    	return D('Addons')->where("id={$id}")->setField('config',I('post.addons'));
    }
}
