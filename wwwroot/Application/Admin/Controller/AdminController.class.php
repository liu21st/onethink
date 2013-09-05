<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Action;
use Admin\Model\AuthRuleModel;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class AdminController extends Action {

    /* 保存禁止通过url访问的公共方法,例如定义在控制器中的工具方法 ;deny优先级高于allow*/
    static protected $deny  = array('getMenus');

    /* 保存允许所有管理员访问的公共方法 */
    static protected $allow = array( 'login','logout', 'test');

    /**
     * 节点配置
     *   配置项目的键必须小写
     *   菜单节点必须配置title元素和url元素(供U函数作使用的合法字符串,参数必须使用?k=v&k2=v2...格式)
     *   array(
     *       //值的元素  title:节点名字；url:链接; group:链接组; tip:链接提示文字
     *       array( 'title'=>'节点标题','url'=>'Index/action?query=vaule', 'group'=>'扩展','tip'=>''),
     *   )
     */
    static protected $nodes = array();

    /**
     * 主节点配置示例:
     *   配置项目的键必须小写
     *   菜单节点必须配置title元素和url元素(供U函数作使用的合法字符串,参数必须使用?k=v&k2=v2...格式)和controllers元素
     *   array(
     *       //值的元素  title:节点名字；url:链接; controller:从哪些控制器查询节点,多个逗号分隔; tip:链接提示文字
     *       array( 'title'=>'节点标题', 'url'=>'Index/index?param=value','controllers'=>'', 'tip'=>''),
     *        ......
     *     )
     *
     */
    private $menus = array(
        array( 'title'=>'首页','url'=>'Index/index','controllers'=>'Index',),
        array( 'title'=>'内容','url'=>'Article/index','controllers'=>'Article',),
        array( 'title'=>'用户','url'=>'User/index','controllers'=>'User,AuthManager'),
        array( 'title'=>'扩展','url'=>'Addons/index','controllers'=>'Addons,Model',),
        array( 'title'=>'系统','url'=>'System/index','controllers'=>'System,Category',),
        array( 'title'=>'其他','url'=>'other','controllers'=>'File','hide'=>true),//专门放置不需要显示在任何菜单中的节点
    );

    private $uid = null;//保存登陆用户的uid
    private $root_user = null;   //保存超级管理员用户id;

    protected $nav = array();

    protected function _initialize()
    {
        $this->uid = is_login();
        if( !$this->uid ){
            $this->redirect('Admin/Index/login');
        }
        $this->root_user = ((int)($this->uid) === C('USER_ADMINISTRATOR'));
        $ac = $this->accessControl();
        if ( $ac===false ) {
            $this->error('403:禁止访问');
        }elseif( $ac===null ){
            $rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
            if ( !$this->root_user && !$this->checkRule($rule,array('in','1,2')) ){
                $this->error('提示:无权访问,您可能需要联系管理员为您授权!');
            }
        }
        $this->assign('__controller__', $this);
        $this->checkNodes();
        $this->_nav();
    }

    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final protected function checkRule($rule, $type=AuthRuleModel::RULE_URL, $mode='url')
    {
        static $Auth = null;
        if (!$Auth) {
            $Auth  = new \ORG\Util\Auth();
        }
        if(!$Auth->check($rule,$this->uid,$type,$mode)){
            return false;
        }
        return true;
    }


    /**
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     *
     * @return true|false|null  返回值必须使用 `===` 进行判断
     *
     *   返回false,不允许任何人访问
     *   返回true, 允许任何管理员访问,无需执行权限检测
     *   返回null, 需要继续执行权限检测决定是否允许访问
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function accessControl()
    {
        $controller = 'Admin\\Controller\\'.CONTROLLER_NAME.'Controller';
        if ( !is_array($controller::$deny)||!is_array($controller::$allow) ){
            $this->error("内部错误:{$controller}控制器 deny和allow属性必须为数组");
        }
        $deny  = $this->getDeny();
        $allow = $this->getAllow();
        if ( !empty($deny)  && in_array(ACTION_NAME,$deny) ) {
            return false;
        }
        if ( !empty($allow) && in_array(ACTION_NAME,$allow) ) {
            return true;
        }
        return null;
    }

    /**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     *
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $data  修改的数据
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function editRow ( $model ,$data, $where , $msg )
    {
        // if( $_REQUEST['model']||$_REQUEST['where']||$_REQUEST['msg']){
            // $this->error('非法请求'); //安全检测,防止通过参数绑定修改数据
        // }
        $id    = array_unique((array)I('id',0));
        $id    = is_array($id) ? implode(',',$id) : $id;
        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
        if( D($model)->where($where)->save($data)!==false ) {
            $this->success($msg['success'],$msg['url'],$msg['ajax']);
        }else{
            $this->error($msg['error'],$msg['url'],$msg['ajax']);
        }
    }

    /**
     * 禁用条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！'))
    {
        $data    = array('status' => 0);
        $where   = array_merge(array('status' => 1),$where);
        $this->editRow( $model , $data, $where, $msg);
    }

    /**
     * 恢复条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！'))
    {
        $data    = array('status' => 1);
        $where   = array_merge(array('status' => 0),$where);
        $this->editRow(   $model , $data, $where, $msg);
    }

    /**
     * 还原条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     * @author huajie  <banhuajie@163.com>
     */
    protected function restore (  $model , $where = array() , $msg = array( 'success'=>'状态还原成功！', 'error'=>'状态还原失败！'))
    {
    	$data    = array('status' => 1);
    	$where   = array_merge(array('status' => -1),$where);
    	$this->editRow(   $model , $data, $where, $msg);
    }

    /**
     * 条目假删除
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function delete ( $model , $where = array() , $msg = array( 'success'=>'删除成功！', 'error'=>'删除失败！'))
    {
        $data    = array('status' => -1);
        $this->editRow(   $model , $data, $where, $msg);
    }

    /**
     * $deny属性的get方法
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function getDeny()
    {
        $controller = 'Admin\\Controller\\'.CONTROLLER_NAME.'Controller';
        $data = array();
        if ( is_array( $controller::$deny) ) {
            $deny = array_merge( $controller::$deny, self::$deny );
            foreach ( $deny as $key => $value){
                if ( is_numeric($key) ){
                    $data[] = strtolower($value);
                }else{
                    //可扩展
                }
            }
        }
        return $data;
    }

    /**
     * 获取控制器中允许所有管理员通过url访问的方法
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function getAllow()
    {
        $controller = 'Admin\\Controller\\'.CONTROLLER_NAME.'Controller';
        $data = array();
        if ( is_array( $controller::$allow) ) {
            $allow = array_merge( $controller::$allow, self::$allow );
            foreach ( $allow as $key => $value){
                if ( is_numeric($key) ){
                    $data[] = strtolower($value);
                }else{
                    //可扩展
                }
            }
        }
        return $data;
    }

    /**
     * 获取控制器的节点配置
     * @param  string  $controller   控制器类名
     * @param  boolean $group        是否分组
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final static public function getNodes($controller,$group=true)
    {
        if ( !$controller || !is_string($controller) || !is_array($controller::$nodes) ) {
            return false;
        }
        $nodes = array('default'=>array());
        foreach ($controller::$nodes as $value){
            if (!is_array($value) || !isset($value['title'],$value['url'])) {
                $action = A(CONTROLLER_NAME);
				$action->error("内部错误:{$controller}控制器 nodes属性配置有误");
            }
            if( strpos($value['url'],'/')===false ){
                $value['url'] = MODULE_NAME.'/'.strtr($controller,array('Controller'=>'')).'/'.$value['url'];
            }elseif( stripos($value['url'],MODULE_NAME)!==0 ){
                $value['url'] = MODULE_NAME.'/'.$value['url'];
            }

            if ( isset($value['operator']) ) {
                foreach ($value['operator'] as &$v){
                    if( strpos($v['url'],'/')===false ){
                        $v['url'] = MODULE_NAME.'/'.strtr($controller,array('Controller'=>'')).'/'.$v['url'];
                    }elseif( stripos($v['url'],MODULE_NAME)!==0 ){
                        $v['url'] = MODULE_NAME.'/'.$v['url'];
                    }
                }
            }

            if ( $group ) {
                //为节点分组,默认分组为default
                $group_name = empty($value['group']) ?'default': $value['group'];
                unset($value['group']);
                $nodes[$group_name][] = $value;
            }else{
                unset($value['group']);
                $nodes[]=$value;
            }
        }
        return $nodes;
    }

    /**
     * 获取控制器菜单数组
     * 子类中 $this->getMenus() 调用
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final public function getMenus()
    {
//        if ( S('base_menu'.$controller) ) {
//            return S('base_menu'.$controller);
//        }
        $menus['main']  = $this->getVal('menus'); //获取主节点
        $menus['child'] = array(); //设置子节点

        //处理控制器中的节点
        foreach ($menus['main'] as $key=>$item){
            if (!is_array($item) || empty($item['title']) || empty($item['url']) || empty($item['controllers'])) {
                $this->error('控制器基类$menus属性元素配置有误');
            }

            if( stripos($item['url'],MODULE_NAME)!==0 ){
                $item['url'] = MODULE_NAME.'/'.$item['url'];
            }
            //非超级管理员需要判断节点权限
            if (  !$this->root_user &&  !$this->checkRule($item['url'],AuthRuleModel::RULE_MAIN,null) ) {  //检测节点权限
                unset($menus['main'][$key]);
                continue;//继续循环
            }

            if ( !empty($item['hide']) ){
                unset($menus['main'][$key]);
            }

            $other_controller = explode(',',$item['controllers']);
            if ( in_array( CONTROLLER_NAME, $other_controller ) ) {
                $menus['main'][$key]['class']='current';
                foreach ($other_controller as $c){
                    //从控制器中读取节点
                    $child = 'Admin\\Controller\\'.$c.'Controller';
                    $child_nodes = $child::getNodes($child);
                    if ($child_nodes===false) {
                        $this->error("内部错误:请检查{$child}控制器 nodes 属性");
                    }
                    foreach ( $child_nodes as $group => $value ) {
                        //$value  分组数组
                        foreach ($value as $k=>$v){
                            //$v  节点配置
                            if ( !empty($v['hide']) || ( !$this->root_user && !$this->checkRule($v['url'],AuthRuleModel::RULE_URL,null) ) ) {   //检测节点权限
                                unset($value[$k]);
                            }
                        }
                        if ( isset($menus['child'][$group]) ) {
                            //如果分组已存在,合并到分组中
                            $menus['child'][$group] = array_merge( $menus['child'][$group], $value);
                        }else{
                            //否则直接保存
                            $menus['child'][$group] = $value;
                        }
                    }
                }
            }
        }
//        S('base_menu'.CONTROLLER_NAME,$menus);
        // dump($menus);
        return $menus;
    }

    /**
     * 供子类读取基类中的私有属性
     * @param string $val  属性名
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final protected function getVal($val)
    {
        return $this->$val;
    }

    /**
     * 返回后台节点数据
     * @param boolean $tree    是否返回树形结构
     * @retrun array
     *
     * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    final protected function returnNodes($tree = true)
    {
        static $tree_nodes = array();
        if ( $tree && !empty($tree_nodes[(int)$tree]) ) {
            return $tree_nodes[$tree];
        }

        $nodes    = $this->getVal('menus'); //获取主节点
        //所有子菜单接单

        $child = array();//$tree为false时,保存所有控制器中的节点
        foreach ($nodes as $key => $value){
            if( stripos($value['url'],MODULE_NAME)!==0 ){
                $value['url'] = MODULE_NAME.'/'.$value['url'];
            }
            $nodes[$key]['url'] = $value['url'];
            $nodes[$key]['child'] = array();
            if($nodes[$key]['hide'] && !$tree){
                unset($nodes[$key]);//删除隐藏的主节点
            }
            $controllers = explode(',',$value['controllers']);
            foreach ($controllers as $c){
                $class = 'Admin\\Controller\\'.$c.'Controller';
                if( class_exists($class) && method_exists($class,'getNodes') ){
                    $temp = $class::getNodes($class,false);
                }else{
                    continue;
                }
                if($tree){
                    $nodes[$key]['child'] = array_merge($nodes[$key]['child'],$temp);
                }else{
                    foreach ($temp as $k=>$operator){//分离菜单节点下的操作节点
                        if ( isset($operator['operator']) ) {
                            $child = array_merge($child,$operator['operator']);
                            unset($temp[$k]['operator']);
                        }
                    }
                    $child = array_merge($child,$temp);
                }
            }
            if (!$tree) {
                unset($nodes[$key]['child']);
            }else{
                unset($nodes[$key]['child']['default']);
            }
        }

        if (!$tree) {
            $nodes = array_merge($nodes,$child);
            unset($nodes['default']);
        }
        $tree_nodes[(int)$tree]   = $nodes;
        return $nodes;
    }

    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  支持多表join,控制器代码示例如下:
     *
     *  <pre>
     *      $Model = M()
     *               ->table('left_tabel as l')
     *               ->join('right_table as r ON l.id=r.uid')
     *               ->where(array('l.status'=>1));
     *      $list = $this->lists($Model);
     *      $this->assign('data',$list);
     *      $this->dispaly();
     *  </pre>
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件
     * @param array|string $order   排序条件
     * @author 朱亚杰 <zhuyajie@topthink.net>
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(),$order='')
    {
        $options = array();
        $REQUEST = (array)I('get.');
        if(is_string($model)){
            $model = D($model);
        }

        $OPT = new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);

        $pk = $model->getPk();
        if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( empty($order) && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        $options['where'] = array_filter(array_merge( array('status'=>array('egt',0)), $REQUEST,  $where ));
        $options          = array_merge( $options , (array)$OPT->getValue($model) );

		$total = $model->where($options['where'])->count();

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
		$page = new \COM\Page($total, $listRows, $REQUEST);
		$this->assign('_page', $page->show());
        $options['limit'] = $page->firstRow.','.$page->listRows;

        $model->setProperty('options',$options);

		return $model->select();
    }

    /**
     * 通用表格列表
     *
     * @param array $list     select 数据集
     * @param array $thead    表头配置数组
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    protected function tableList($list,$thead)
    {
        $keys = array_keys($thead);
        array_walk($list,function(&$v,$k) use($keys,$thead) {
            $arr = array();
            foreach ($keys as $value){
                if ( isset($v[$value]) ) {
                    $arr[$value] = $v[$value];
                }elseif( strpos($value,'_')===0 ){
                    $arr[$value] = $thead[$value]['td'];
                }
            }
            $v = array_merge($arr,$v);
        });
        $this->assign('_thead',$thead);
        $this->assign('_list',$list);
        return $this->fetch('Public:_list');
    }

    /**
     * debug模式下检查是否存在不受权限系统管理的公共方法
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    protected function checkNodes(){
        if ( APP_DEBUG!=true ){
            return;
        }

        $CReflection = new \ReflectionClass('Admin\\Controller\\'.CONTROLLER_NAME.'Controller');
        $public = $CReflection->getMethods( \ReflectionMethod::IS_PUBLIC );
        $static = $CReflection->getMethods( \ReflectionMethod::IS_STATIC );
        $method = array_diff($public,$static);
        $deny   = $this->getDeny();
        $allow  = $this->getAllow();
        $deny_allow = array_merge($deny,$allow,array('__get','__set','__call','__construct','__destruct','__isset','__sleep','__wakeup','__clone'));

        $nodes  = M('AuthRule')->where(array('module'=>'admin','status'=>1))->getField('name',true);
        foreach ($nodes as $k=>$n){
            if( ($pos = strpos($n,'?'))>0){
                $n= substr($n,0,$pos);
            }
            $nodes[$k] = strtolower($n);
        }

        $collect = array();
        foreach ($method as $value){
            if($value->class=='Think\\Action' || (strpos($value->name,'_')===0) ){
                continue;
            }
            if( in_array( strtolower($value->name),$deny_allow) ){
                continue;
            }else{
                $name = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.$value->name);
                if( in_array($name,$nodes) ){
                    continue;
                }else{
                    $collect[]=$value->name;
                }
            }
        }
        if( count($collect) ){
            C('TRACE_PAGE_TABS', array('BASE'=>'基本','FILE'=>'文件','INFO'=>'流程','ERR|NOTIC'=>'错误','SQL'=>'SQL','DEBUG'=>'调试','DEV'=>'开发提示'));
            foreach ($collect as $value){
                trace(" 公共方法 '{$value}' 尚未进行任何权限配置!",CONTROLLER_NAME,'dev');
            }
        }
    }


    /**
     * 构建nav的1和last元素
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    final protected function _nav()
    {
        if(!isset($_SERVER["HTTP_REFERER"])){
            $_SERVER["HTTP_REFERER"]=U('Admin/Index/index');
        }

        $first = M('AuthRule')->where(array('module'=>'admin','status'=>1, 'type'=>2))->getField('name,title',true);


        $arr = array();
        foreach ($first as $key => $value){
            $arr[U($key,$vars='',$suffix=true,$redirect=false,$domain=true)] = $value;
        }

        $nav  = session('nav')?session('nav'):array();

		$port = $_SERVER['SERVER_PORT']==80?'':':'.$_SERVER['SERVER_PORT'];
		$last = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$port .$_SERVER['REQUEST_URI'];
        if( array_key_exists( $_SERVER["HTTP_REFERER"],$arr ) ){
            $nav = array();//清空
            $nav[1] = array( $_SERVER["HTTP_REFERER"]=>$arr[$_SERVER["HTTP_REFERER"]] );
        }
		if( array_key_exists( $last,$arr ) ){
			$nav = array();//清空
			$nav[1] = array( $last=>$arr[$last]);
			$this->nav = $nav;
			session('nav',$this->nav);
			return;
		}
		$nav['last'] = $last;
        $this->nav = $nav;
    }

    /**
     * 设置nav
     * @param int    $level  菜单层级
     * @param string $title  菜单名称
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    protected function nav($level,$title,$show=false){
        if ( is_numeric($level) ) {
			$this->nav[$level] = array ($this->nav['last']=>$title);
            unset($this->nav['last']);
            ksort($this->nav);
			$this->nav = array_slice($this->nav,0,$level,true);
            $arr = array();
            foreach ($this->nav as $key => $value){
                foreach ($value as $k => $v){
                    $arr[$k]=$v;
                }
            }
			session( 'nav', $this->nav );
            $this->assign('_nav',$arr);
            $this->assign('_show_nav',$show);
        }
    }
}
