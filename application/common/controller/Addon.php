<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace app\common\controller;

/**
 * 插件类
 * @author yangweijie <yangweijiester@gmail.com>
 */
abstract class Addon{
    /**
     * 视图实例对象
     * @var view
     * @access protected
     */
    protected $view = null;

    /**
     * $info = array(
     *  'name'=>'Editor',
     *  'title'=>'编辑器',
     *  'description'=>'用于增强整站长文本的输入和显示',
     *  'status'=>1,
     *  'author'=>'thinkphp',
     *  'version'=>'0.1'
     *  )
     */
    public $info                =   array();
    public $addon_path          =   '';
    public $config_file         =   '';
    public $custom_config       =   '';
    public $admin_list          =   array();
    public $custom_adminlist    =   '';
    public $access_url          =   array();

    public function __construct(){
        $this->view         =   \think\View::instance(\think\Config::get('template'));
        $this->addon_path   =   ONETHINK_ADDON_PATH.$this->getName().'/';
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
    /**
     * 模板变量赋值
     *
     * @access protected
     * @param mixed $name要显示的模板变量
     * @param mixed $value变量的值
     * @return Action
     */
    final protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);
        return $this;
    }
    //用于显示模板的方法
    final protected function fetch($templateFile = null){
        $templateFile=empty($templateFile)?$this->request->controller():$templateFile;
        if(!is_file($templateFile)){
            $templateFile = $this->addon_path.$templateFile.'.'.\think\Config::get('template.view_suffix');
            if(!is_file($templateFile)){
                throw new \Exception("模板不存在:$templateFile");
            }
        }
        echo  $this->view->fetch($templateFile);
    }
    
    //显示方法
    final protected function display($template=''){
        if($template == '')
            $template = CONTROLLER_NAME;
        echo ($this->fetch($template));
    }


    final public function getName(){
        $class = get_class($this);
        return strtolower(substr($class,strrpos($class, '\\')+1));
//         return substr($class,strrpos($class, '\\')+1, -5);
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
    final public function getConfig($name=''){
        static $_config = array();
        if(empty($name)){
            $name = $this->getName();
        }
        if(isset($_config[$name])){
            return $_config[$name];
        }
        $config =   array();
        $map['name']    =   $name;
        $map['status']  =   1;
        $config  =   db('Addons')->where($map)->value('config');
        if($config){
            $config   =   json_decode($config, true);
        }else{
            $temp_arr = include $this->config_file;
            foreach ($temp_arr as $key => $value) {
                if($value['type'] == 'group'){
                    foreach ($value['options'] as $gkey => $gvalue) {
                        foreach ($gvalue['options'] as $ikey => $ivalue) {
                            $config[$ikey] = $ivalue['value'];
                        }
                    }
                }else{
                    $config[$key] = $temp_arr[$key]['value'];
                }
            }
        }
        $_config[$name]     =   $config;
        return $config;
    }

    //必须实现安装
    abstract public function install();

    //必须卸载插件方法
    abstract public function uninstall();
}
