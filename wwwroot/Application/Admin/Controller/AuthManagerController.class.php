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
        array('title'=>'标题2','url'=>'index','group'=>'分组1'),
        array('title'=>'标题3','url'=>'index','group'=>'分组2'),
        array('title'=>'标题4','url'=>'index','group'=>'分组2'),
    );

    /*
     * 返回节点数据
     */
    protected function returnNodes()
    {
        $iterator = new FilesystemIterator(
                            __DIR__,
                            FilesystemIterator::UNIX_PATHS|FilesystemIterator::CURRENT_AS_PATHNAME|FilesystemIterator::KEY_AS_FILENAME
                        );
        $menu     = $this->getMenus();
        $nodes    = $menu['main']; //主菜单节点

        //所有子菜单接单

        $arr  = array();
        foreach ( $iterator as $filename => $obj ){
            $class = strtr($filename,array('.class.php'=>''));
            if( class_exists($class) && method_exists($class,'getNodes') ){
                $arr[$class] = $class::getNodes($class,false);
            }
        }

        foreach ($nodes as $key => $value){
            $nodes[$key]['child'] = array();
            $controllers = explode(',',$value['controllers']);
            foreach ($controllers as $c){
                $nodes[$key]['child'] = array_merge($nodes[$key]['child'],$arr[$c.'Controller']);
            }
            unset($nodes[$key]['controllers']);
            unset($nodes[$key]['child']['default']);
        }

        return $nodes;
    }
    
    /*
     * 节点配置的url作为规则存入auth_rule
     */
    public function updateRules()
    {
        $nodes = $this->returnNodes();
        var_export($nodes);
        
    }
    

    /*
     * 权限管理首页
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function index()
    {
        $this->assign('auth_node',$this->returnNodes());
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
     * 创建规则
     * 表单字段: 规则标识name,规则名称title
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function createRule()
    {
    }

    public function editRule()
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
