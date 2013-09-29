<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
use Admin\Model\AuthRuleModel;
use Admin\Model\AuthGroupModel;
/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class AdminController extends Controller {

    /* 保存禁止通过url访问的公共方法,例如定义在控制器中的工具方法 ;deny优先级高于allow*/
    static protected $deny  = array('getMenus','tableList','recordList');

    /* 保存允许访问的公共方法 */
    static protected $allow = array( 'login','logout','get');

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
    private $menus      =   array(
        array( 'title'=>'首页','url'=>'Index/index',        'controllers'=>'Index',),
        array( 'title'=>'内容','url'=>'Article/mydocument', 'controllers'=>'Article',),
        array( 'title'=>'用户','url'=>'User/index',         'controllers'=>'User,AuthManager'),
        array( 'title'=>'扩展','url'=>'Addons/index',       'controllers'=>'Addons,Model',),
        array( 'title'=>'系统','url'=>'Config/group',       'controllers'=>'Config,Channel,System,Category',),
        array( 'title'=>'其他','url'=>'other',              'controllers'=>'File','hide'=>true),//专门放置不需要显示在任何菜单中的节点
    );

    protected $nav      =   array();

    /**
     * 后台控制器初始化
     */
    protected function _initialize(){
        // 获取当前用户ID
        define('UID',is_login());
        if( !UID ){// 还没登录 跳转到登录页面
            $this->redirect('Public/login');
        }
        /* 读取数据库中的配置 */
		$config	=	S('DB_CONFIG_DATA');
		if(!$config){
			$config	=	D('Config')->lists();
			S('DB_CONFIG_DATA',$config);
		}
        C($config); //添加配置
        
        // 初始化钩子
        init_hooks();

        // 是否是超级管理员
        define('IS_ROOT',   is_administrator());
        $access =   $this->accessControl();
        if ( $access === false ) {
            $this->error('403:禁止访问');
        }elseif( $access === null ){
            $dynamic        =   $this->checkDynamic();//检测分类栏目有关的各项动态权限
            if( $dynamic === null ){
                //检测非动态权限
                $rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
                if ( !$this->checkRule($rule,array('in','1,2')) ){
                    $this->error('提示:无权访问,您可能需要联系管理员为您授权!');
                }
            }elseif( $dynamic === false ){
                $this->error('提示:无权访问,您可能需要联系管理员为您授权!');
            }
        }
        $this->assign('__controller__', $this);
        $this->checkNodes();
    }

    /**
     * 权限检测
     * @param string  $rule    检测的规则
     * @param string  $mode    check模式
     * @return boolean
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final protected function checkRule($rule, $type=AuthRuleModel::RULE_URL, $mode='url'){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        static $Auth    =   null;
        if (!$Auth) {
            $Auth       =   new \ORG\Util\Auth();
        }
        if(!$Auth->check($rule,UID,$type,$mode)){
            return false;
        }
        return true;
    }

    /**
     * 检测是否是需要动态判断的权限
     * @return boolean|null  
     *      返回true则表示当前访问有权限
     *      返回false则表示当前访问无权限
     *      返回null，则会进入checkRule根据节点授权判断权限 
     *      
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    protected function checkDynamic(){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        return null;//不明,需checkRule
    }


    /**
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     *
     * @return boolean|null  返回值必须使用 `===` 进行判断
     *
     *   返回 **false**, 不允许任何人访问(超管除外)
     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final protected function accessControl(){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        $controller = 'Admin\\Controller\\'.CONTROLLER_NAME.'Controller';
        if ( !is_array($controller::$deny)||!is_array($controller::$allow) ){
            $this->error("内部错误:{$controller}控制器 deny和allow属性必须为数组");
        }
        $deny  = $this->getDeny();
        $allow = $this->getAllow();
        if ( !empty($deny)  && in_array(ACTION_NAME,$deny) ) {
            return false;//非超管禁止访问deny中的方法
        }
        if ( !empty($allow) && in_array(ACTION_NAME,$allow) ) {
            return true;
        }
        return null;//需要检测节点权限
    }

    /**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     *
     * @param string $model 模型名称,供M函数使用的参数
     * @param array  $data  修改的数据
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    final protected function editRow ( $model ,$data, $where , $msg ){
        $id    = array_unique((array)I('id',0));
        $id    = is_array($id) ? implode(',',$id) : $id;
        $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
        if( M($model)->where($where)->save($data)!==false ) {
            $this->success($msg['success'],$msg['url'],$msg['ajax']);
        }else{
            $this->error($msg['error'],$msg['url'],$msg['ajax']);
        }
    }

    /**
     * 禁用条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的 where()方法的参数
     * @param array  $msg   执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     * @author 朱亚杰  <zhuyajie@topthink.net>
     */
    protected function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！')){
        $data    =  array('status' => 0);
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
    protected function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！')){
        $data    =  array('status' => 1);
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
    protected function restore (  $model , $where = array() , $msg = array( 'success'=>'状态还原成功！', 'error'=>'状态还原失败！')){
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
    protected function delete ( $model , $where = array() , $msg = array( 'success'=>'删除成功！', 'error'=>'删除失败！')) {
        $data['status']         =   -1;
        $data['update_time']    =   NOW_TIME;
        $this->editRow(   $model , $data, $where, $msg);
    }

    /**
     * 获取控制器中允许禁止任何人(超管除外)通过url访问的方法
     * @param  string  $controller   控制器类名(不含命名空间)
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final static protected function getDeny($controller=CONTROLLER_NAME){
        $controller =   'Admin\\Controller\\'.$controller.'Controller';
        $data       =   array();
        if ( is_array( $controller::$deny) ) {
            $deny   =   array_merge( $controller::$deny, self::$deny );
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
     * @param  string  $controller   控制器类名(不含命名空间)
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final static protected function getAllow($controller=CONTROLLER_NAME){
        $controller =   'Admin\\Controller\\'.$controller.'Controller';
        $data       =   array();
        if ( is_array( $controller::$allow) ) {
            $allow  =   array_merge( $controller::$allow, self::$allow );
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
     * 获取控制器的节点配置$nodes
     * @param  string  $controller   控制器类名(不含命名空间)
     * @param  boolean $group        是否分组(按配置中的group合并分组)
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final static public function getNodes($controller,$group=true){
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
     * 获取控制器菜单数组,二级菜单元素位于一级菜单的'_child'元素中
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final public function getMenus($controller=CONTROLLER_NAME){
		$menus	=	session('ADMIN_MENU_LIST'.$controller);
		if(!$menus){
			$menus['main']  = $this->getVal('menus'); //获取主节点
			$menus['child'] = array(); //设置子节点

			//处理控制器中的节点
			foreach ($menus['main'] as $key=>$item){
				if (!is_array($item) || empty($item['title']) || empty($item['url']) ) {
					$this->error('控制器基类$menus属性元素配置有误');
				}

				if( stripos($item['url'],MODULE_NAME)!==0 ){
					$item['url'] = MODULE_NAME.'/'.$item['url'];
				}
				//判断节点权限
				if ( !$this->checkRule($item['url'],AuthRuleModel::RULE_MAIN,null) ) {  //检测节点权限
					unset($menus['main'][$key]);
					continue;//继续循环
				}

				if ( !empty($item['hide']) ){
					unset($menus['main'][$key]);
				}

                if (empty($item['controllers'])) { continue; }
				$other_controller = explode(',',$item['controllers']);
				if ( in_array( $controller, $other_controller ) ) {
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
								if ( !empty($v['hide']) || !$this->checkRule($v['url'],AuthRuleModel::RULE_URL,null ) ) {   //检测节点权限
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
			session('ADMIN_MENU_LIST'.$controller,$menus);
		}
        return $menus;
    }

    /**
     * 供子类读取基类中的私有属性
     * @param string $val  属性名
     * @author 朱亚杰  <xcoolcc@gmail.com>
     */
    final protected function getVal($val){
        return $this->$val;
    }

    /**
     * 返回后台节点数据
     * @param boolean $tree    是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
     * @retrun array
     *
     * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     *
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    final protected function returnNodes($tree = true){
        static $tree_nodes = array();
        if ( $tree && !empty($tree_nodes[(int)$tree]) ) {
            return $tree_nodes[$tree];
        }

        $nodes  =   $this->getVal('menus'); //获取主节点
        //所有子菜单接单

        $child  =   array();//$tree为false时,保存所有控制器中的节点
        foreach ($nodes as $key => $value){
            if( stripos($value['url'],MODULE_NAME)!==0 ){
                $value['url']       =   MODULE_NAME.'/'.$value['url'];
            }
            $nodes[$key]['url']     =   $value['url'];
            $nodes[$key]['child']   =   array();
            if($nodes[$key]['hide'] && !$tree){
                unset($nodes[$key]);//删除隐藏的主节点
            }

            if (empty($value['controllers'])) { continue; }
            $controllers    =   explode(',',$value['controllers']);
            foreach ($controllers as $c){
                $class      =   'Admin\\Controller\\'.$c.'Controller';
                if( class_exists($class) && method_exists($class,'getNodes') ){
                    $temp   =   $class::getNodes($class,false);
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
     * 通用分页列表数据集获取方法,获取的数据集主要供tableList()方法用来生成表格列表
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param array        $base    基本的查询条件
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author 朱亚杰 <xcoolcc@gmail.com>
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(),$order='',$base = array('status'=>array('egt',0)),$field=true){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        if(is_string($model)){
            $model  =   M($model);
        }

        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);

        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        $options['where'] = array_filter(array_merge( (array)$base, $REQUEST, (array)$where ),function($val){
            if($val===''||$val===null){
                return false;
            }else{
                return true;
            }
        });
        if( empty($options['where'])){
            unset($options['where']);
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        $total        =   $model->where($options['where'])->count();

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $page = new \COM\Page($total, $listRows, $REQUEST);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $options['limit'] = $page->firstRow.','.$page->listRows;

        $model->setProperty('options',$options);

        return $model->field($field)->select();
    }

    /**
     * 数据集分页
     * @param array $records 传入的数据集
     */
    public function recordList($records){
        $request    =   (array)I('request.');
        $total      =   $records? count($records) : 1 ;
        if( isset($request['r']) ){
            $listRows = (int)$request['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $page       =   new \COM\Page($total, $listRows, $request);
        $voList     =   array_slice($records, $page->firstRow, $page->listRows);
        $p			=	$page->show();
        $this->assign('_list', $voList);
        $this->assign('_page', $p? $p: '');
    }

    /**
     * 通用表格列表
     *
     * @param array $list     select 数据集
     * @param array $thead    表头配置数组
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function tableList( $list, $thead ){
        $list = (array)$list;
        if(APP_DEBUG){
            //debug模式检测数据
            $List  = new \RecursiveArrayIterator($list);
            $RList = new \RecursiveIteratorIterator($List,\RecursiveIteratorIterator::CHILD_FIRST);
            foreach($RList as $v){
                if($RList->getDepth()==2){
                    //数据集不是二维数组
                    die('<h1>'.'严重问题：表格列表数据集参数不是二维数组'.'</h1>');
                    break;
                }
            }

            $keys   =   array_keys( (array)reset($list) );
            foreach($list as $row){
                $keys = array_intersect( $keys, array_keys($row) );
            }
            $s_thead =  serialize($thead);
            if(!empty($list)){
                preg_replace_callback('/\$([a-zA-Z_]+)/',function($matches) use($keys){
                    if( !in_array($matches[1],$keys) ){
                        die('<h1>'.'严重问题：数据列表表头定义使用了数据集中不存在的字段:$'.$matches[1].', 请检查表头和数据集.</h1>');
                    }
                },$s_thead);
            }
        }
        $keys       =   array_keys($thead);//表头所有的key
        array_walk($list,function(&$v,$k) use($keys,$thead) {
            $arr    =   array();//保存数据集字段的值
            foreach ($keys as $value){
                //判断表头key是否在数据集中存在对应字段
                if ( isset($v[$value]) ) {
                    $arr[$value] = $v[$value];
                }elseif( strpos($value,'_')===0 ){
                    $arr[$value] = @$thead[$value]['td'];
                }elseif( isset($thead[$value]['_title']) ){
                    $arr[$value] = '';
                }
            }
            $v      =   array_merge($arr,$v);//根据$arr的顺序更新数据集字段顺序
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
        $controllers    =   array();
        foreach ($this->menus as $value){
           $con         =   explode(',',$value['controllers']);
           $controllers =   array_merge($controllers,$con);
        }

        $nodes          =   M('AuthRule')->where(array('module'=>'admin','status'=>1))->getField('name',true);
        if($nodes===null){
            return;
        }
        foreach ((array)$nodes as $k=>$n){
            if( ($pos = strpos($n,'?'))>0){
                $n      =   substr($n,0,$pos);
            }
            $nodes[$k]  =   strtolower($n);
        }

        foreach ($controllers as $controller){
            if (empty($controller)) {continue;}
            $CReflection = new \ReflectionClass('Admin\\Controller\\'.$controller.'Controller');
            $public = $CReflection->getMethods( \ReflectionMethod::IS_PUBLIC );
            $static = $CReflection->getMethods( \ReflectionMethod::IS_STATIC );
            $method = array_diff($public,$static);
            $class  = 'Admin\\Controller\\'.$controller.'Controller';

            $deny   = $class::getDeny($controller);
            $allow  = $class::getAllow($controller);
            $deny_allow = array_merge($deny,$allow,array('__get','__set','__call','__construct','__destruct','__isset','__sleep','__wakeup','__clone'));

            $collect = array();
            foreach ($method as $value){
                if($value->class=='Think\\Action' || (strpos($value->name,'_')===0) ){
                    continue;
                }
                if( in_array( strtolower($value->name),$deny_allow) ){
                    continue;
                }else{
                    $name = strtolower(MODULE_NAME.'/'.$controller.'/'.$value->name);
                    if( in_array($name,(array)$nodes) ){
                        continue;
                    }else{
                        $collect[]=$value->name;
                    }
                }
            }
            if( count($collect) ){
                C('TRACE_PAGE_TABS', array('BASE'=>'基本','FILE'=>'文件','INFO'=>'流程','ERR|NOTIC'=>'错误','SQL'=>'SQL','DEBUG'=>'调试','DEV'=>'开发提示'));
                foreach ($collect as $value){
                    trace(" 公共方法 '{$value}' 尚未进行任何权限配置!",$controller.'Controller','dev');
                }
            }
        }
    }


    /**
     * 构建nav的1和last元素
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    final protected function _nav(){
        if(!isset($_SERVER["HTTP_REFERER"])){
            $_SERVER["HTTP_REFERER"]=U('Admin/Index/index');
        }

        $first  =   M('AuthRule')->where(array('module'=>'admin','status'=>1, 'type'=>2))->getField('name,title',true);


        $arr    =   array();
        foreach ($first as $key => $value){
            $arr[U($key,$vars='',$suffix=true,$redirect=false,$domain=true)] = $value;
        }

        $nav    =   session('nav')?session('nav'):array();

        $port = $_SERVER['SERVER_PORT']==80?'':':'.$_SERVER['SERVER_PORT'];
        $last = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$port .$_SERVER['REQUEST_URI'];
        if( array_key_exists( $_SERVER["HTTP_REFERER"],$arr ) ){
            $nav    =   array();//清空
            $nav[1] =   array( $_SERVER["HTTP_REFERER"]=>$arr[$_SERVER["HTTP_REFERER"]] );
        }
        if( array_key_exists( $last,$arr ) ){
            $nav         =   array();//清空
            $nav[1]      =   array( $last=>$arr[$last]);
            $this->nav   =   $nav;
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
            $this->nav  =   array_slice($this->nav,0,$level,true);
            $arr        =   array();
            foreach ($this->nav as $key => $value){
                foreach ($value as $k => $v){
                    $arr[$k] =  $v;
                }
            }
            session( 'nav', $this->nav );
            $this->assign('_nav',$arr);
            $this->assign('_show_nav',$show);
        }
    }
}
