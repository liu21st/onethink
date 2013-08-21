<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

/**
 * 用户组模型类
 * Class AuthGroupModel 
 * @author 朱亚杰 <zhuyajie@topthink.net>
 */
class AuthGroupModel extends CmsadminModel
{
    const TYPE_ADMIN = 1;

    protected $_validate = array(
        array('title','require', '必须设置用户组标题', Model::MUST_VALIDATE ,'regex',Model::MODEL_BOTH),
        array('description','0,80', '描述最多80字符', Model::VALUE_VALIDATE , 'length'  ,Model::MODEL_BOTH ),
        array('rules','/^(\d,?)+(?<!,)$/', '规则数据不合法', Model::VALUE_VALIDATE , 'regex'  ,Model::MODEL_BOTH ),
    );

    /**
     * 返回用户组列表
     * 默认返回正常状态的管理员用户组列表
     * @param array $where   查询条件,供where()方法使用
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function getGroups($where=array())
    {
        $map = array('status'=>1,'type'=>self::TYPE_ADMIN,'module'=>'admin');
        $map = array_merge($map,$where);
        return $this->where($map)->select();
    }

    /**
     * 把用户添加到用户组,支持批量添加用户到用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function addToGroup($uid,$gid)
    {
        $uid = is_array($uid)?$uid:implode(',',$uid);
        $gid = is_array($gid)?$gid:implode(',',$gid);

        $Member = M('Member');
        foreach ($uid as $u){
            if(is_numeric($u)){
                foreach ($gid as $g){
                    if(is_numeric($g)){
                        $Member->where(array('uid'=>$u))->save(array('group_id'=>$g));
                    }
                }
            }
        }
    }
    
}

