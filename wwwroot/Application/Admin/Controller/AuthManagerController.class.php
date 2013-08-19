<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 朱亚杰 <zhuyajie@topthink.net>
// +----------------------------------------------------------------------

/**
 * 权限管理控制器
 * Class AuthManagerController
 * @author 朱亚杰 <zhuyajie@topthink.net>
 */
class AuthManagerController extends AdminController{

    static protected $deny  = array();

    /* 保存允许所有管理员访问的公共方法 */
    static protected $allow = array();

    static protected $nodes= array(
        array('title'=>'标题5','url'=>'index'),

        array('title'=>'标题1','url'=>'index','group'=>'分组1'),

        array('title'=>'标题4','url'=>'index','group'=>'分组2',
              'operator'=>array(
                  array('title'=>'编辑','url'=>'edit'),
                  array('title'=>'删除','url'=>'delete'),
                  array('title'=>'禁用','url'=>'forbid'),
                  array('title'=>'恢复','url'=>'resume'),
              ),
        ),
    );

    /*
     * 返回后台节点数据
     * @param boolean $tree    是否返回树形结构
     * @retrun array
     * 
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    protected function returnNodes($tree = true)
    {
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
            $nodes[$key]['url'] = $value['url'];
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
            unset($nodes[$key]['controllers']);
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
        return $nodes;
    }
    
    /*
     * 后台节点配置的url作为规则存入auth_rule
     * 执行新节点的插入,已有节点的更新,无效规则的删除三项任务
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function updateRules()
    {
        //需要新增的节点必然位于$nodes
        $nodes    = $this->returnNodes(false);

        $AuthRule = D('AuthRule');
        $map      = array('module'=>'admin','type'=>AuthRuleModel::URL_RULE);//status全部取出,以进行更新
        //需要更新和删除的节点必然位于$rules
        $rules    = $AuthRule->where($map)->order('name')->select();

        //构建insert数据
        $data     = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value){
            $temp['name']   = $value['url'];
            $temp['title']  = $value['title'];
            $temp['module'] = 'admin';
            $temp['type']   = AuthRuleModel::URL_RULE;
            $temp['status'] = 1;
            $data[strtolower($temp['name'].$temp['module'].$temp['type'])] = $temp;//去除重复项
        }

        $update = array();//保存需要更新的节点
        $ids    = array();//保存需要删除的节点的id
        foreach ($rules as $index=>$rule){
            $key = strtolower($rule['name'].$rule['module'].$rule['type']);
            if ( isset($data[$key]) ) {
                $update[] = $data[$key];
                unset($data[$key]); //去除需要更新的节点,只留下需要插入的节点
                unset($rules[$index]);//去除需要更新的节点,只留下需要删除的节点
            }else{
                $ids[] = $rule['id'];
            }
        }
        $AuthRule->startTrans();
        //更新
        if ( count($update) ) {
            foreach ($update as $k=>$row){
                $map['name']   = $row['name'];
                $map['module'] = $row['module'];
                $map['type']   = $row['type'];
                $AuthRule->where($map)->save($update[$k]);
            }
        }
        //删除
        if ( count($ids) ) {
            $AuthRule->where( array( 'id'=>array('IN',implode(',',$ids)) ) )->save(array('status'=>-1));
        }
        //新增
        if( count($data) ){
            $AuthRule->addAll(array_values($data));
        }
        if ( $AuthRule->getDbError() ) {
            $AuthRule->rollback();
            trace(__METHOD__.':'.$AuthRule->getDbError());
            return false;
        }else{
            $AuthRule->commit();
            return true;
        }
    }
    

    /*
     * 权限管理首页
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function index()
    {
        $node_list = $this->returnNodes();
        $this->assign('node_list',$node_list);
        $this->display();
    }

    /*
     * 返回分组列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public static function getGroups()
    {
        
    }
    

    /*
     * 创建用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function createGroup()
    {
        //读取规则节点
        $this->display();
        
    }
    
    /*
     * 编辑用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function editGroup()
    {
        $this->assign('nodes',$nodes);
        $this->display('createGroup');
    }
    
    /*
     * 写入新增用户组
     */
    public function insertGroup()
    {
        //将规则节点写入用户组表
    }

    /*
     * 更新用户组数据
     */
    public function updateGroup()
    {
        
    }
    
    /*
     * 把用户添加到用户组,支持批量用户添加到多个用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function addToGroup()
    {
        $uid      = I('post.uid');
        $group_id = I('post.group_id');
        //检查数据是否存在,是否重复
        // $GroupAccess = M('AuthGroupAccess');
        // $GroupAccess->add();
    }
    
    public function test()
    {
        // dump($method);
        echo PHP_FILE;
    }

    public function changeStatus($method=null)
    {
        switch ( $method ){
            case 'forbidRule':
                $this->forbid('AuthRule');    
                break;
            case 'resumeRule':
                $this->resume('AuthRule');    
                break;
            case 'deleteRule':
                $this->delete('AuthRule');    
                break;
            case 'forbidGroup':
                $this->forbid('AuthGroup');    
                break;
            case 'resumeGroup':
                $this->resume('AuthGroup');    
                break;
            case 'deleteGroup':
                $this->delete('AuthGroup');    
                break;
            default:
                $this->error('参数非法',__APP__);
        }
    }
    
}
