<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class CmsadminController extends Action {
	
    /* 保存禁止通过url访问的公共方法,例如定义在控制器中的工具方法 */
    static private $deny  = array();

    /* 保存允许所有管理员访问的公共方法 */
    static private $allow = array();
    
    /**
     * action访问控制,在登陆成功后执行的第一项权限检测任务
     */
    final protected function accessControl(){
        if ( !is_array(self::$deny)||!is_array(self::$allow) ){
            $this->error('内部错误:deny属性必须为数组,即将返回首页',__APP__,IS_AJAX);
        }
        if ( !empty(self::$deny) && in_array(ACTION_NAME,self::$deny) ) {
            $this->error('禁止访问,即将返回首页',__APP__,IS_AJAX);
        }
    }

    /**
     * 获取模型实例,默认根据MODULE_NAME名称获取
     * 
     * @param string $modelname  模型名称,供D函数使用,如果控制器设置了 public $modelname ,优先使用该属性
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function model($modelname=CONTOLLER_NAME)
    {
        static $models = array();
        if ( $this->modelname ) {
            $name  = $this->modelname;
        } else {
            $name  = $modelname;
        }

        if ( empty($models[$name]) ) {
            $models[$name] = D($name);
        }
        return $models[$name];
    }

    /**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     * @param array $data  修改的数据
     * @param array $where 查询时的where()方法的参数
     * @param array $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                    url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function editRow ($data, $where , $msg )
    {
		$where   = array_merge( array('id' => array('in', $_GET['id'])),$where );
        $msg     = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , $msg );
        if( $this->model()->where($where)->save($data) ) {
            $this->success($msg['success'],$msg['url'],$msg['ajax']);
        }else{
            $this->error($msg['error'],$msg['url'],$msg['ajax']);
        } 
    }
    
    
    /**
     * 禁用条目
     * @param array $where 查询时的where()方法的参数
     * @param array $msg   执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                    url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    public function forbid ( $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！'))
    {
		$data    = array('status' => 0);
		$where   = array_merge(array('status' => 1),$where);
        $this->editRow( $data, $where, $msg);
    }

    /**
     * 恢复条目
     * @param array $where 查询时的where()方法的参数
     * @param array $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                    url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    public function resume ( $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！'))
    {
		$data    = array('status' => 1);
		$where   = array_merge(array('status' => 0),$where);
        $this->editRow( $data, $where, $msg);
    }

    /**
     * 条目假删除
     * @param array $where 查询时的where()方法的参数
     * @param array $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                    url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    public function delete ( $where = array() , $msg = array( 'success'=>'删除成功！', 'error'=>'删除失败！'))
    {
		$data    = array('status' => -1);
        $this->editRow( $data, $where, $msg);
    }

    /**
     * $deny属性的get方法
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final static public function getDeny()
    {
        return self::$deny;
    }

    /**
     * private $deny属性设置方法,在_initialize()中调用
     * @param array $deny  禁止任何人通过url访问的方法,为了方便扩展,强制要求必须为一个数组
     * 示例: array( 'delete','resume' )
     * 
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function setDeny( array $deny )
    {
        foreach ($deny as $key => $value){
           if ( is_numeric($key) ){
               self::$deny[] = $value;
           }else{
               //todo: 功能扩展
           } 
        }
    }
    
    /**
     * 获取控制器中允许所有管理员通过url访问的方法
     * 
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final static public function getAllow()
    {
        return self::$allow;
    }
    
    /**
     * private $allow属性设置方法,在_initialize()中调用
     * @param array $allow  为了方便扩展,强制要求必须为一个数组
     * 示例: array( 'index' )
     * 
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function setAllow( array $allow )
    {
        foreach ( $allow as $key => $value){
           if ( is_numeric($key) ){
               self::$allow[] = $value;
           }else{
               //todo: 功能扩展
           } 
        }
    }

