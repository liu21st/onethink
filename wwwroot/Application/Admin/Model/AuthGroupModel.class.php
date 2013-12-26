<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 用户组模型类
 * Class AuthGroupModel 
 * @author 朱亚杰 <zhuyajie@topthink.net>
 */
class AuthGroupModel extends Model {
    const TYPE_ADMIN                = 1;                   // 管理员用户组类型标识
    const MEMBER                    = 'member';
    const UCENTER_MEMBER            = 'ucenter_member';
    const AUTH_GROUP_ACCESS         = 'auth_group_access'; // 关系表表名
    const AUTH_EXTEND               = 'auth_extend';       // 动态权限扩展信息表
    const AUTH_GROUP                = 'auth_group';        // 用户组表名
    const AUTH_EXTEND_CATEGORY_TYPE = 1;              // 分类权限标识
    const AUTH_EXTEND_MODEL_TYPE    = 2; //分类权限标识

    protected $_validate = array(
        array('title','require', '必须设置用户组标题', Model::MUST_VALIDATE ,'regex',Model::MODEL_INSERT),
        //array('title','require', '必须设置用户组标题', Model::EXISTS_VALIDATE  ,'regex',Model::MODEL_INSERT),
        array('description','0,80', '描述最多80字符', Model::VALUE_VALIDATE , 'length'  ,Model::MODEL_BOTH ),
       // array('rules','/^(\d,?)+(?<!,)$/', '规则数据不合法', Model::VALUE_VALIDATE , 'regex'  ,Model::MODEL_BOTH ),
    );

    /**
     * 返回用户组列表
     * 默认返回正常状态的管理员用户组列表
     * @param array $where   查询条件,供where()方法使用
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function getGroups($where=array()){
        $map = array('status'=>1,'type'=>self::TYPE_ADMIN,'module'=>'admin');
        $map = array_merge($map,$where);
        return $this->where($map)->select();
    }

    /**
     * 把用户添加到用户组,支持批量添加用户到用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     * 
     * 示例: 把uid=1的用户添加到group_id为1,2的组 `AuthGroupModel->addToGroup(1,'1,2');`
     */
    public function addToGroup($uid,$gid){
        $uid = is_array($uid)?implode(',',$uid):trim($uid,',');
        $gid = is_array($gid)?$gid:explode( ',',trim($gid,',') );

        $Access = M(self::AUTH_GROUP_ACCESS);
        if( isset($_REQUEST['batch']) ){
            //为单个用户批量添加用户组时,先删除旧数据
            $del = $Access->where( array('uid'=>array('in',$uid)) )->delete();
        }

        $uid_arr = explode(',',$uid);
		$uid_arr = array_diff($uid_arr,array(C('USER_ADMINISTRATOR')));
        $add = array();
        if( $del!==false ){
            foreach ($uid_arr as $u){
                foreach ($gid as $g){
                    if( is_numeric($u) && is_numeric($g) ){
                        $add[] = array('group_id'=>$g,'uid'=>$u);
                    }
                }
            }
            $Access->addAll($add);
        }
        if ($Access->getDbError()) {
            if( count($uid_arr)==1 && count($gid)==1 ){
                //单个添加时定制错误提示
                $this->error = "不能重复添加";
            }
            return false;
        }else{
            return true;
        }
    }

    /**
     * 返回用户所属用户组信息
     * @param  int    $uid 用户id
     * @return array  用户所属的用户组 array(
     *                                         array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *                                         ...)   
     */
    static public function getUserGroup($uid){
        static $groups = array();
        if (isset($groups[$uid]))
            return $groups[$uid];
        $prefix = C('DB_PREFIX');
        $user_groups = M()
            ->field('uid,group_id,title,description,rules')
            ->table($prefix.self::AUTH_GROUP_ACCESS.' a')
            ->join ($prefix.self::AUTH_GROUP." g on a.group_id=g.id")
            ->where("a.uid='$uid' and g.status='1'")
            ->select();
        $groups[$uid]=$user_groups?$user_groups:array();
        return $groups[$uid];
    }
    
    /**
     * 返回用户拥有管理权限的扩展数据id列表
     * 
     * @param int     $uid  用户id
     * @param int     $type 扩展数据标识
     * @param int     $session  结果缓存标识
     * @return array
     *  
     *  array(2,4,8,13) 
     *
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    static public function getAuthExtend($uid,$type,$session){
        if ( !$type ) {
            return false;
        }
        if ( $session ) {
            $result = session($session);
        }
        if ( $uid == UID && !empty($result) ) {
            return $result;
        }
        $prefix = C('DB_PREFIX');
        $result = M()
            ->table($prefix.self::AUTH_GROUP_ACCESS.' g')
            ->join($prefix.self::AUTH_EXTEND.' c on g.group_id=c.group_id')
            ->where("g.uid='$uid' and c.type='$type' and !isnull(extend_id)")
            ->getfield('extend_id',true);
        if ( $uid == UID && $session ) {
            session($session,$result);
        }
        return $result;
    }

    /**
     * 返回用户拥有管理权限的分类id列表
     * 
     * @param int     $uid  用户id
     * @return array
     *  
     *  array(2,4,8,13) 
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    static public function getAuthCategories($uid){
        return self::getAuthExtend($uid,self::AUTH_EXTEND_CATEGORY_TYPE,'AUTH_CATEGORY');
    }



    /**
     * 获取用户组授权的扩展信息数据
     * 
     * @param int     $gid  用户组id
     * @return array
     *  
     *  array(2,4,8,13) 
     *
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    static public function getExtendOfGroup($gid,$type){
        if ( !is_numeric($type) ) {
            return false;
        }
        return M(self::AUTH_EXTEND)->where( array('group_id'=>$gid,'type'=>$type) )->getfield('extend_id',true);
    }

    /**
     * 获取用户组授权的分类id列表
     * 
     * @param int     $gid  用户组id
     * @return array
     *  
     *  array(2,4,8,13) 
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    static public function getCategoryOfGroup($gid){
        return self::getExtendOfGroup($gid,self::AUTH_EXTEND_CATEGORY_TYPE);
    }
    

    /**
     * 批量设置用户组可管理的扩展权限数据
     *
     * @param int|string|array $gid   用户组id
     * @param int|string|array $cid   分类id
     * 
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    static public function addToExtend($gid,$cid,$type){
        $gid = is_array($gid)?implode(',',$gid):trim($gid,',');
        $cid = is_array($cid)?$cid:explode( ',',trim($cid,',') );

        $Access = M(self::AUTH_EXTEND);
        $del = $Access->where( array('group_id'=>array('in',$gid),'type'=>$type) )->delete();

        $gid = explode(',',$gid);
        $add = array();
        if( $del!==false ){
            foreach ($gid as $g){
                foreach ($cid as $c){
                    if( is_numeric($g) && is_numeric($c) ){
                        $add[] = array('group_id'=>$g,'extend_id'=>$c,'type'=>$type);
                    }
                }
            }
            $Access->addAll($add);
        }
        if ($Access->getDbError()) {
            return false;
        }else{
            return true;
        }
    }

    /**
     * 批量设置用户组可管理的分类
     *
     * @param int|string|array $gid   用户组id
     * @param int|string|array $cid   分类id
     * 
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    static public function addToCategory($gid,$cid){
        return self::addToExtend($gid,$cid,self::AUTH_EXTEND_CATEGORY_TYPE);
    }


    /**
     * 将用户从用户组中移除
     * @param int|string|array $gid   用户组id
     * @param int|string|array $cid   分类id
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    public function removeFromGroup($uid,$gid){
        return M(self::AUTH_GROUP_ACCESS)->where( array( 'uid'=>$uid,'group_id'=>$gid) )->delete();
    }

    /**
     * 获取某个用户组的用户列表
     *
     * @param int $group_id   用户组id
     * 
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    static public function memberInGroup($group_id){
        $prefix   = C('DB_PREFIX');
        $l_table  = $prefix.self::MEMBER;
        $r_table  = $prefix.self::AUTH_GROUP_ACCESS;
        $r_table2 = $prefix.self::UCENTER_MEMBER;
        $list     = M() ->field('m.uid,u.username,m.last_login_time,m.last_login_ip,m.status')
                       ->table($l_table.' m')
                       ->join($r_table.' a ON m.uid=a.uid')
                       ->join($r_table2.' u ON m.uid=u.id')
                       ->where(array('a.group_id'=>$group_id))
                       ->select();
        return $list;
    }

    /**
     * 检查id是否全部存在
     * @param array|string $gid  用户组id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkId($modelname,$mid,$msg = '以下id不存在:'){
        if(is_array($mid)){
            $count = count($mid);
            $ids   = implode(',',$mid);
        }else{
            $mid   = explode(',',$mid);
            $count = count($mid);
            $ids   = $mid;
        }

        $s = M($modelname)->where(array('id'=>array('IN',$ids)))->getField('id',true);
        if(count($s)===$count){
            return true;
        }else{
            $diff = implode(',',array_diff($mid,$s));
            $this->error = $msg.$diff;
            return false;
        }
    }

    /**
     * 检查用户组是否全部存在
     * @param array|string $gid  用户组id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkGroupId($gid){
        return $this->checkId('AuthGroup',$gid, '以下用户组id不存在:');
    }
    
    /**
     * 检查分类是否全部存在
     * @param array|string $cid  栏目分类id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkCategoryId($cid){
        return $this->checkId('Category',$cid, '以下分类id不存在:');
    }


}

