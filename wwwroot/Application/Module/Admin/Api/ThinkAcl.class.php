<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class ThinkAcl {
    const URL   =   0, APP =    1, MODEL = 2, CATE =    3,MENU = 4;
    // 资源类型对应的数据模型
    static $acl = array(0=>'Url',1=>'App',2=>'Model',3=>'Cate',4=>'Menu');

    // 访问权限检查 包含登录检查
    static public function checkUrl() {
        if(!C('USER_AUTH_ON') || !empty($_SESSION['administrator'])) {
            return true;
        }
        $url    =   '/'.strtolower((C('MULTI_MODULE')?MODULE_NAME.'/':'').CONTROLLER_NAME.'/'.ACTION_NAME);

        $notAuthUrl =   C('NOT_AUTH_URL');
        if(is_string($notAuthUrl)) {
            $notAuthUrl =   explode(',',$notAuthUrl);
        }
        foreach ($notAuthUrl as $checkUrl){
            if(0===strpos($url,$checkUrl)) {
                // 当前地址无需认证
                return true;
            }
        }
        //检查认证识别号
        if(!$_SESSION[C('USER_AUTH_KEY')]) {
            //跳转到认证网关
            cookie('__login__',$_SERVER['REQUEST_URI']);
            redirect(C('USER_AUTH_GATEWAY'));
        }
        // 获取当前用户的角色
        $roles  =   self::getUserRoles();
        foreach ($roles as $role){
            // 读取角色的访问权限
            $accessList =   ThinkAccess::getAccessId($role,0);
            // 获取URL配置
            $map['id']  =   array('IN',$accessList);
            $list   =   M('Url')->where($map)->getField('url',true);
            foreach ($list as $access){
                if(self::parseUrlRole($access,$url)) {
                    return true;
                }
            }
        }
        // 没有权限
        if(C('AUTH_ERROR_URL')) {
            // 定义权限错误页面
            redirect(C('AUTH_ERROR_URL'));
        }else{
            return false;
        }
    }

    // 角色业务权限检查 不包含登录检查
    // id 访问对象标识（id或者name）
    // type 访问对象类型
    // level 访问对象层次
    static public function checkRoleAcl($id,$type=0,$level=null) {
        if(!C('USER_AUTH_ON') || !empty($_SESSION['administrator'])) { // 超级管理员无需检查
            return true;
        }
        // 获取当前用户的角色
        $roles  =   self::getUserRoles();
        if($roles) {// 当前用户有角色定义
            if(is_numeric($id)) {
                $id =   (int)$id;
            }else{
                $id    =   M(self::$acl[$type])->getFieldByName($id,'id');
            }
            foreach ($roles as $roleId){
                // 角色的访问权限
                if(ThinkAccess::check($roleId,array($id,$type),$level)){
                    return true;
                }
            }
        }
        // 没有权限
        if(C('AUTH_ERROR_URL')) {
            // 定义权限错误页面
            redirect(C('AUTH_ERROR_URL'));
        }else{
            return false;
        }
    }

    // 用户业务权限检查 
    // id 访问对象标识（id或者name）
    // type 访问对象类型
    // level 访问对象层次
    static public function checkUserAcl($id,$type=0,$level=null) {
        if(!C('USER_AUTH_ON') || !empty($_SESSION['administrator'])) { // 超级管理员无需检查
            return true;
        }
        // 获取当前用户的角色
        if(is_numeric($id)) {
            $id =   (int)$id;
        }else{
            $id    =   M(self::$acl[$type])->getFieldByName($id,'id');
        }
        $userId =   self::getUserId();
        // 读取用户的访问权限
        if(ThinkAccess::check(array($userId,1),array($id,$type),$level)){
            return true;
        }
        // 没有权限
        if(C('AUTH_ERROR_URL')) {
            // 定义权限错误页面
            redirect(C('AUTH_ERROR_URL'));
        }else{
            return false;
        }
    }

    // 获取当前用户所属角色的权限列表
    static public function getRoleAccessList($type=0,$level='',$fields='id,title',$where=array()){
        // 获取当前用户的角色
        $roles  =   self::getUserRoles();
        $result   =   array();
        if($roles) {// 当前用户有角色定义
            $list   =   array();
            foreach ($roles as $roleId){
                // 角色的访问权限
                $access = ThinkAccess::getAccessId($roleId,$type,$level);
                if($access) {
                    $list   =   array_merge($list,$access);
                }
            }
            $map['id']  =   array('IN',$list);
            if(!empty($where)) {
                $map =  array_merge($map,$where);
            }
            $result =   M(self::$acl[$type])->where($map)->getField($fields);
        }
        return $result;
    }

    // 获取角色的权限列表
    static public function getUserAccessList($type=0,$level='',$fields='id,title'){
        // 获取当前用户的角色
        $userId =   self::getUserId();
        $result   =   array();
        if($userId) {// 当前用户有角色定义
            // 角色的访问权限
            $list   =   ThinkAccess::getAccessId(array($userId,1),$type,$level);
            $map['id']  =   array('IN',$list);
            $result =   M(self::$acl[$type])->where($map)->getField($fields);
        }
        return $result;
    }

    // 解析URL规则
    private static function parseUrlRole($role,$url) {
        $url    =   explode('/',trim($url,'/'));
        $array  =   explode('/',trim($role,'/'));
        foreach ($array as $key=>$val){
            if('*'!=$val && isset($url[$key]) &&$val!=$url[$key] ) {
                return false;
            }
        }
        return true;
    }

    // 获取后台用户的ID
    public static function getUserId() {
        return session(C('USER_AUTH_KEY'));
    }

    // 获取用户的角色列表
    public static function getUserRoles($userId='') {
        $userId =   $userId?$userId:self::getUserId();
        // 获取当前用户的角色
        $map['user_id'] =   $userId;
        $roles  =   M('RoleUser')->where($map)->getField('role_id',true);
        return $roles;
    }
}