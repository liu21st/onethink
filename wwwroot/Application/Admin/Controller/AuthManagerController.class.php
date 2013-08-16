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

    static protected $deny  = array('test');

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
     * 更新节点信息
     */
    public function updateNode()
    {
        $iterator = new FilesystemIterator(
                            __DIR__,
                            FilesystemIterator::UNIX_PATHS|FilesystemIterator::CURRENT_AS_PATHNAME|FilesystemIterator::KEY_AS_FILENAME
                        );
        $base     = get_parent_class(__CLASS__);
        $menu     = $base::getMenus();
        $nodes     = $menu['main']; //主菜单节点

        //所有子菜单接单

        foreach ( $iterator as $filename => $obj ){
            $class = strtr($filename,array('.class.php'=>''));
            if( class_exists($class) && method_exists($class,'getNodes') ){
                $node = $class::getNodes($class);
                foreach ($nodes as $key => $value){
                    $controllers = explode(',',$value['controllers']);
                    if ( in_array( strtr($class,array('Controller'=>'')),$controllers) ) {
                        $nodes[$key]['child'] = array_merge((array)$nodes[$key]['child'],$node);
                    }
                }
            }
        }

        dump($nodes);
    }
    

    /*
     * 权限管理首页
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function index()
    {
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
        $this->display();
    }

    public function __call($method,$args)
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
        }
        parent::__call($method,$args);
    }
    
}
