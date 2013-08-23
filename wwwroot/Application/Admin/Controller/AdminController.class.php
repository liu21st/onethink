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
class AdminController extends Action {

    /* 保存禁止通过url访问的公共方法,例如定义在控制器中的工具方法 ;deny优先级高于allow*/
    static protected $deny  = array('getMenus');

    /* 保存允许所有管理员访问的公共方法 */
    static protected $allow = array();

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
        array( 'title'=>'首页','controllers'=>'Index',),
        array( 'title'=>'内容','controllers'=>'Article',),
        array( 'title'=>'用户','controllers'=>'User,AuthManager'),
        array( 'title'=>'扩展','controllers'=>'Addons',),
        array( 'title'=>'系统','controllers'=>'System',),
    );

    final protected function _initialize()
    {
        if( !is_administrator() ){
            $this->error('您的身份不是管理员!');
        }
        $this->uid = 1;//保存用户id
        $ac = $this->accessControl();
        if ( $ac===false ) {
            $this->error('403:禁止访问');
        }elseif( $ac===null ){
            $rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
            $nodes = $this->returnNodes(false);
            $i = 0;
            foreach ($nodes as $value){
                $value['url'] = strtolower($value['url']);
                if( in_array($rule,$value) ){
                    $i = 1; //当前访问的节点存在于需要执行权限验证的节点中
                    break;
                }
            }
            if ( $i==1 ){
                if ( !$this->checkRule($rule,array('in','1,2')) ){
                    $this->error('无权访问');
                }
            } 
        }
        $this->assign('__controller__', $this);
        $this->_init();
    }

    protected function _init()
    {
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
        import('ORG.Util.Auth');
        if (!$Auth) {
            $Auth  = new Auth();
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
    final protected function accessControl(){
        // if($this->uid==1){
            // return true;//超级管理员
        // }
        $controller = CONTROLLER_NAME.'Controller';
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
        if( $_REQUEST['model']||$_REQUEST['where']||$_REQUEST['msg']){
            $this->error('非法请求'); //安全检测,防止通过参数绑定修改数据
        }
        $id    = array_unique((array)I('id',0));
        $id    = is_array($id) ? implode(',',$id) : $id;
        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
        if( D($model)->where($where)->save($data) ) {
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
    public function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！'))
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
    public function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！'))
    {
        $data    = array('status' => 1);
        $where   = array_merge(array('status' => 0),$where);
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
    public function delete ( $model , $where = array() , $msg = array( 'success'=>'删除成功！', 'error'=>'删除失败！'))
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
        $controller = CONTROLLER_NAME.'Controller';
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
        $controller = CONTROLLER_NAME.'Controller';
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
    final static public function getNodes($controller,$group=true){
        $temp = $controller::$nodes;
        if ( !$controller || !is_string($controller) || !is_array($temp) || !count($temp)) {
            return false;
        }
        $nodes = array('default'=>array());
        foreach ($temp as $value){
            if (!is_array($value) || !isset($value['title'],$value['url'])) {
                $this->error("内部错误:{$controller}控制器 nodes属性配置有误");
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
    final public function getMenus(){
//        if ( S('base_menu'.$controller) ) {
//            return S('base_menu'.$controller);
//        }
        $menus['main']  = $this->getVal('menus'); //获取主节点
        $menus['child'] = array(); //设置子节点

        //处理控制器中的节点
        foreach ($menus['main'] as $key=>$item){
            if (!is_array($item) || empty($item['title']) ||  empty($item['controllers'])) {
                $this->error('控制器基类$menus属性元素配置有误');
            }

            if( stripos($item['url'],MODULE_NAME)!==0 ){
                $item['url'] = MODULE_NAME.'/'.$item['url'];
            }
            //非超级管理员需要判断节点权限
            if (  /* $this->uid!=1 && */  !$this->checkRule($item['url'],AuthRuleModel::RULE_MAIN,null)) {  //检测节点权限
                unset($menus['main'][$key]);
                continue;//继续循环
            }
            $other_controller = explode(',',$item['controllers']);
            if ( in_array( CONTROLLER_NAME, $other_controller ) ) {
                $menus['main'][$key]['class']='current';
                foreach ($other_controller as $c){
                    //从控制器中读取节点
                    $child = $c.'Controller';
                    $child_nodes = $child::getNodes($child);
                    if ($child_nodes===false) {
                        $this->error("内部错误:请检查{$child}控制器 nodes 属性");
                    }
                    foreach ( $child_nodes as $group => $value ) {
                        //$value  分组数组
                        foreach ($value as $k=>$v){
                            //$v  节点配置
                            if ( /* $this->uid!=1 && */ !$this->checkRule($v['url'],AuthRuleModel::RULE_URL,null) ) {   //检测节点权限
                                unset($value[$k]);
                            }
                            if( $k==0 && !isset($menus['main'][$key]['url']) ){
                                $menus['main'][$key]['url']=$v['url'];//初始化主菜单url
                            } elseif( isset($v['default']) ){
                                $menus['main'][$key]['url']=$v['url'];//如果配置了default,采用该url
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
        dump($menus);
        return $menus;
    }

    /**
     * 读取基类中的私有属性
     * @param string $val  属性名
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final protected function getVal($val){
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
        static $notree_nodes = array();
        if ( $tree && !empty($tree_nodes) ) {
            return $tree_nodes;
        }
        if ( !$tree && !empty($notree_nodes) ) {
            return $notree_nodes;
        }

        $iterator = new FilesystemIterator(
                            __DIR__,
                            FilesystemIterator::UNIX_PATHS|FilesystemIterator::CURRENT_AS_PATHNAME|FilesystemIterator::KEY_AS_FILENAME
                        );
        $nodes    = $this->getVal('menus'); //获取主节点
        //所有子菜单接单

        $arr  = array(); //保存每个控制器中的节点
        foreach ( $iterator as $filename => $obj ){
            $class = strtr($filename,array('.class.php'=>''));
            if( class_exists($class) && method_exists($class,'getNodes') ){
                $arr[$class] = $class::getNodes($class,false);
            }
        }

        $child = array();//$tree为false时,保存所有控制器中的节点
        foreach ($nodes as $key => $value){
            $nodes[$key]['url'] = $value['title'];//使用title作为规则name
            $nodes[$key]['child'] = array();
            $controllers = explode(',',$value['controllers']);
            foreach ($controllers as $c){
                if($tree){
                    $nodes[$key]['child'] = array_merge($nodes[$key]['child'],$arr[$c.'Controller']);
                }else{
                    $temp = $arr[$c.'Controller'];
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
            $notree_nodes = $nodes;
        }else{
            $tree_nodes   = $nodes;
        }
        return $nodes;
    }
    
}
