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
        array('title'=>'标题1','url'=>'index','group'=>'分组1'),
        array('title'=>'标题2','url'=>'index','group'=>'分组1'),
        array('title'=>'标题3','url'=>'index','group'=>'分组2'),
        array('title'=>'标题4','url'=>'index','group'=>'分组2'),
        array('title'=>'标题5','url'=>'index'),
    );

    /*
     * 更新节点信息
     */
    public function updateNode()
    {
        $iterator = new RecursiveDirectoryIterator(
            __DIR__, 
            FilesystemIterator::UNIX_PATHS|FilesystemIterator::CURRENT_AS_SELF|FilesystemIterator::KEY_AS_PATHNAME
        );
        $nodes = array();
        //todo:
        foreach ( $iterator as $pathname => $obj ){
            
        }

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
        
    }
    
    /*
     * 编辑用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function editGroup()
    {
        
    }
    
    /*
     * 删除用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function deleteGroup()
    {
        $this->delete('AuthGroup');    
    }

    /*
     * 恢复用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function resumeGroup()
    {
        $this->resume('AuthGroup');    
    }
    
    /*
     * 禁用用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function forbidGroup()
    {
        $this->forbid('AuthGroup');    
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
     * 删除规则
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function deleteRule()
    {
        $this->modelname='AuthRule';
        $this->delete();    
    }

    /*
     * 恢复规则
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function resumeRule()
    {
        $this->modelname='AuthRule';
        $this->resume();    
    }
    
    /*
     * 禁用规则
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function forbidRule()
    {
        $this->modelname='AuthRule';
        $this->forbid();    
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

}
