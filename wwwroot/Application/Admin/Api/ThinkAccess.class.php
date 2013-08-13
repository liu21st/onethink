<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// 系统权限接口类
/*
aro_id  请求对象ID
aro_type 请求对象类型 0 角色 1 用户 2 ... 扩展请求对象
aco_id 访问对象ID
aco_level 访问对象层次（用于快速定位资源）
aco_type 访问对象类型 0 URL 1 应用 2 模型 3 分类 4 ... 扩展访问对象
access_level 访问级别 0 拒绝 1允许 2 ... 用于不同等级安全访问或者访问标识 可扩展
access_extra 访问扩展 用于可扩展的访问级别需要
*/
class ThinkAccess {

    static public $name =   'Rbac';

    // 注册权限 aro_id =0 aro_type=0 全局访问 aro_id=0 aro_type=1 游客访问
    static public function reg($data=array()){ 
        $data['aro_type']  =  isset($data['aro_type'])?$data['aro_type']:0;
        $data['aco_type'] = isset($data['aco_type'])?$data['aco_type']:0;
        $data['aco_level'] = isset($data['aco_level'])?$data['aco_level']:0;
        $data['access_level'] = isset($data['access_level'])?$data['access_level']:1;
        $data['access_extra'] = isset($data['access_extra'])?$data['access_extra']:'';
        $Access =  M(self::$name);
        return $Access->add($data);
    }
    
    // 拒绝某个ARO对象访问ACO
    static public function deny($aro,$aco){
        $Access =  M(self::$name);
        $data = self::deal($aro,$aco);
        $data['access_level'] = 0;
        return $Access->add($data);
    }

    // 允许某个ARO对象访问ACO    
    static public function allow($aro,$aco){
        $Access =  M(self::$name);
        $data = self::deal($aro,$aco);
        $data['access_level'] = 1;
        return $Access->add($data);
    }

    // 删除ARO对象的ACO权限
    static public function move($aro,$aco){
        $Access =  M(self::$name);
        $map = self::deal($aro,$aco);
        return $Access->where($map)->delete();
    }

    // 清除ARO对象的所有权限
    static public function clear($aro,$aco_type='',$aco_level=''){
        $Access =  M(self::$name);
        $map = array();
        if(is_array($aro)) {
            list($map['aro_id'],$map['aro_type']) = $aro;
        }else{
            $map['aro_id']  =  $aro;
            $map['aro_type']  =  0; // 默认类型
        }
        if('' !== $aco_type) {
            $map['aco_type']    =   intval($aco_type);
        }
        if('' !== $aco_level) {
            $map['aco_level']   =   intval($aco_level);
        }
        return $Access->where($map)->delete();
    }
    
    // 清除ACO对象的所有请求对象（用于删除某个ACO）
    static public function clearAco($aco){
        $Access =  M(self::$name);
        $map = array();
        if(is_array($aco)) {
            list($map['aco_id'],$map['aco_type']) = $aco;
        }else{
            $map['aco_id']  =  $aco;
            $map['aco_type']  =  0; // 默认类型
        }
        return $Access->where($map)->delete();
    }

    // 检查权限
    // 返回值===false 没有权限; 
    // 返回数字 则为访问等级 0 拒绝 1 允许 2 令牌 3 时间 4 IP
    static public function check($aro,$aco,$aco_level=null){
        $Access =  M(self::$name);
        $map = self::deal($aro,$aco);
        if(!is_null($aco_level)) {
            $map['aco_level']    =   $aco_level;
        }
        // 获取权限
        $result   = $Access->where($map)->field('access_level,access_extra')->find();
        // 检查访问级别
        return is_null($result)?   false  :   self::checkLevel($result);
    }
    
    // 获取某个ARO对象的权限列表
    static public function getAccessList($aro,$aco_type='',$aco_level=''){
        $map = array();
        if(is_array($aro)) {
            list($map['aro_id'],$map['aro_type']) = $aro;
        }else{
            $map['aro_id']  =  $aro;
            $map['aro_type']  =  0; // 默认类型
        }
        if('' !== $aco_type) {
            $map['aco_type']    =   $aco_type;
        }
        if('' !== $aco_level) {
            $map['aco_level']   =   $aco_level;
        }
        $Access =  M(self::$name);
        // 获取权限
        $result   = $Access->where($map)->field('aco_id,aco_type,aco_level,access_level,access_extra')->select();
        return $result;
    }

    static public function getAccessId($aro,$aco_type='',$aco_level=''){
        $map = array();
        if(is_array($aro)) {
            list($map['aro_id'],$map['aro_type']) = $aro;
        }else{
            $map['aro_id']  =  $aro;
            $map['aro_type']  =  0; // 默认类型
        }
        if('' !== $aco_type) {
            $map['aco_type']    =   $aco_type;
        }
        if('' !== $aco_level) {
            $map['aco_level']   =   $aco_level;
        }
        $Access =  M(self::$name);
        // 获取权限
        $result   = $Access->where($map)->getField('aco_id',true);
        return $result;
    }

    // 获取某个ACO对象的请求列表
    static public function getAccessRequest($aco){
        $map = array();
        if(is_array($aco)) {
            list($map['aco_id'],$map['aco_type']) = $aco;
        }else{
            $map['aco_id']  =  $aco;
            $map['aco_type']  =  0; // 默认类型
        }
        $Access =  M(self::$name);
        // 获取权限
        $result   = $Access->where($map)->field('aro_id,aro_type,access_level,access_extra')->select();
        return $result;
    }

    // 检查访问级别
    // 0 拒绝 1 允许 2 令牌 3 时间 4 允许IP 5 禁止IP
    // 返回值 false 没有权限 数字 访问级别
    static private function checkLevel($data) {
        switch($data['access_level']) {
            case 2:// 令牌访问
                return self::checkToken($data['access_extra'],$data['access_level']);
            case 3:// 时间限制
                return self::checkTime($data['access_extra'],$data['access_level']);
            case 4:// IP访问
            case 5:// IP访问
                return self::checkIP($data['access_extra'],$data['access_level']);
            case 0:
            case 1:
            default:
                return $data['access_level'];
        }
    }

    // 检查令牌访问规则
    static private function checkToken($token,$level){
        if($token!=$_REQUEST['__access_token__']) {
            return false;
        }
        return $level;
    }

    // 检查时间访问规则
    static private function checkTime($time,$level){
        list($start,$end)   =  explode(',',$time);
        if(NOW_TIME<$start || NOW_TIME>$end) 
            return false;
        return $level;
    }

    // 检查IP访问规则
    static private function checkIP($ipRule,$level){
        import('ORG.Net.IPFilter');
        $Filer =  new IPFilter(explode(',',$ipRule));
        $Ip   =  get_client_ip();
        if($level==4) { // 允许IP
            if(!$Filter->check($ip)) return false;
        }elseif($level==5) {// 禁止IP
            if($Filter->check($ip)) return false;
        }
        return $level;
    }

    // 对ARO和ACO数据进行预处理
    static private function deal($aro,$aco){
        $data = array();
        if(is_array($aro)) {
            list($data['aro_id'],$data['aro_type']) = $aro;
        }else{
            $data['aro_id']  =  $aro;
            $data['aro_type']  =  0; // 默认类型
        }
        if(is_array($aco)) {
            list($data['aco_id'],$data['aco_type']) = $aco;
        }else{
            $data['aco_id']  =  $aco;
            $data['aco_type']  =  0; // 默认类型
        }
        return $data;
    }
}
?>