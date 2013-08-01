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

// 角色模块
class RoleController extends CommonController {

    // 模型授权
    public function url($groupId=0){
        // 获取所有模型列表
        $urlList =   M('Url')->where('status=1')->field('id,title,url')->select();
        // 系统角色列表
        $groupList =   M('Role')->where('status=1')->getField('id,name');
        // 获取当前用户组的模型权限列表
        $this->selectGroupId    =   $groupId;
        $result =   ThinkAccess::getAccessList($groupId,0);
        $groupUrlList =   array();
        foreach ($result as $val){
            $groupUrlList[]   =   $val['aco_id'];
        }
        $this->groupUrlList   =   $groupUrlList;
        $this->urlList    =   $urlList;
		$this->groupList    =   $groupList;
        $this->display();
    }

    public function setUrl(){
        $access['aro_type']   =   0;
        $access['aro_id'] =   (int)$_POST['groupId'];
        $access['aco_type']   =   0;
        $access['access_level']   =   1;
        // 清除所有权限
        ThinkAccess::clear($access['aro_id'],0);
        foreach ($_POST['groupUrlId'] as $id){
            $access['aco_id']   =   $id;
            $result =   ThinkAccess::reg($access);
        }
        // 注册权限
        if($result){
            $this->success('访问授权成功！');
        }else{
            $this->error('发生错误！');
        }
    }

    public function addUrl(){
        $data['url']    =   strtolower($this->_post('url'));
        $data['title']  =   $this->_post('title');
        $data['create_time']    =   NOW_TIME;
        $data['status'] =   1;
        $id =   M('Url')->add($data);
        $access['aro_type']   =   0;
        $access['aro_id'] =   $this->_post('groupId');
        $access['aco_type']   =   0;
        $access['access_level']   =   1;
        $access['aco_id']   =   $id;
        $result =   ThinkAccess::reg($access);
        // 注册权限
        if($result){
            $this->success('添加授权成功！');
        }else{
            $this->error('发生错误！');
        }
    }
    // 菜单授权
    public function menu($groupId=0){
        // 获取所有模型列表
        $menuList =   M('Menu')->where('status=1')->order('sort,pid')->getField('id,title');
        // 系统角色列表
        $groupList =   M('Role')->where('status=1')->getField('id,name');
        // 获取当前用户组的模型权限列表
        $this->selectGroupId    =   $groupId;
        $result =   ThinkAccess::getAccessList($groupId,4);
        $groupMenuList =   array();
        foreach ($result as $val){
            $groupMenuList[]   =   $val['aco_id'];
        }
        $this->groupMenuList   =   $groupMenuList;
        $this->menuList    =   $menuList;
		$this->groupList    =   $groupList;
        $this->display();
    }

    public function setMenu(){
        $access['aro_type']   =   0;
        $access['aro_id'] =   (int)$_POST['groupId'];
        $access['aco_type']   =   4;
        $access['access_level']   =   1;
        // 清除所有权限
        ThinkAccess::clear($access['aro_id'],4);
        foreach ($_POST['groupMenuId'] as $id){
            $access['aco_id']   =   $id;
            $result =   ThinkAccess::reg($access);
        }
        // 注册权限
        if($result){
            $this->success('菜单授权成功！');
        }else{
            $this->error('发生错误！');
        }
    }

    // 模型授权
    public function model($groupId=0){
        // 获取所有模型列表
        $modelList =   M('Model')->where('status=1')->getField('id,title');
        // 系统角色列表
        $groupList =   M('Role')->where('status=1')->getField('id,name');
        // 获取当前用户组的模型权限列表
        $this->selectGroupId    =   $groupId;
        $result =   ThinkAccess::getAccessList($groupId,2);
        $groupModelList =   array();
        foreach ($result as $val){
            $groupModelList[]   =   $val['aco_id'];
        }
        $this->groupModelList   =   $groupModelList;
        $this->modelList    =   $modelList;
		$this->groupList    =   $groupList;
        $this->display();
    }

    public function setModel(){
        $access['aro_type']   =   0;
        $access['aro_id'] =   (int)$_POST['groupId'];
        $access['aco_type']   =   2;
        $access['access_level']   =   1;
        // 清除所有权限
        ThinkAccess::clear($access['aro_id'],2);
        foreach ($_POST['groupModelId'] as $id){
            $access['aco_id']   =   $id;
            $result =   ThinkAccess::reg($access);
        }
        // 注册权限
        if($result){
            $this->success('模型授权成功！');
        }else{
            $this->error('发生错误！');
        }
    }

    // 分类授权
    public function cate($groupId=0){
        // 获取所有模型列表
        $cateList =   M('Cate')->where('status=1')->getField('id,title');
        // 系统角色列表
        $groupList =   M('Role')->where('status=1')->getField('id,name');
        // 获取当前用户组的模型权限列表
        $this->selectGroupId    =   $groupId;
        $result =   ThinkAccess::getAccessList($groupId,3);
        $groupCateList =   array();
        foreach ($result as $val){
            $groupCateList[]   =   $val['aco_id'];
        }
        $this->groupCateList   =   $groupCateList;
        $this->cateList    =   $cateList;
		$this->groupList    =   $groupList;
        $this->display();
    }

    public function setCate(){
        $access['aro_type']   =   0;
        $access['aro_id'] =   (int)$_POST['groupId'];
        $access['aco_type']   =   3;
        $access['access_level']   =   1;
        // 清除所有权限
        ThinkAccess::clear($access['aro_id'],3);
        foreach ($_POST['groupCateId'] as $id){
            $access['aco_id']   =   $id;
            $result =   ThinkAccess::reg($access);
        }
        // 注册权限
        if($result){
            $this->success('分类授权成功！');
        }else{
            $this->error('发生错误！');
        }
    }

    /**
     +----------------------------------------------------------
     * 组操作权限列表
     *
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function app($groupId=0) {
        // 获取所有模型列表
        $appList =   M('App')->where('status=1')->getField('id,title');
        $appList    =   array_merge(array('0'=>'全局'),$appList);
        // 系统角色列表
        $groupList =   M('Role')->where('status=1')->getField('id,name');
        // 获取当前用户组的模型权限列表
        $this->selectGroupId    =   $groupId;
        $result =   ThinkAccess::getAccessList($groupId,1);
        $groupAppList =   array();
        foreach ($result as $val){
            $groupAppList[]   =   $val['aco_id'];
        }
        $this->groupAppList   =   $groupAppList;
        $this->appList    =   $appList;
		$this->groupList    =   $groupList;
        $this->display();
        return;
    }

    public function setApp() {
        $access['aro_type']   =   0;
        $access['aro_id'] =   (int)$_POST['groupId'];
        $access['aco_type']   =   1;
        $access['access_level']   =   1;
        // 清除所有权限
        ThinkAccess::clear($access['aro_id'],1);
        foreach ($_POST['groupAppId'] as $id){
            $access['aco_id']   =   $id;
            $result =   ThinkAccess::reg($access);
        }
        // 注册权限
        if($result){
            $this->success('分组授权成功！');
        }else{
            $this->error('发生错误！');
        }
    }

    /**
     +----------------------------------------------------------
     * 增加组操作权限
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws FcsException
     +----------------------------------------------------------
     */
    public function setUser() {
        $id     = $_POST['groupUserId'];
		$groupId	=	$_POST['groupId'];
		$group    =   D("Role");
		$group->delGroupUser($groupId);
		$result = $group->setGroupUsers($groupId,$id);
		if($result===false) {
			$this->error('授权失败！');
		}else {
			$this->success('授权成功！');
		}
    }

    /**
     +----------------------------------------------------------
     * 组操作权限列表
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws FcsException
     +----------------------------------------------------------
     */
    public function user() {
        //读取系统的用户列表
        $map['status']  =   1;
        $map['type']    =   1;
		$userList   =  M('Auth')->where($map)->getField('id,account,nickname',' ');

		$group    =   D("Role");
        $groupList   =  $group->where('status=1')->getField('id,name',' ');
		$this->assign("groupList",$groupList);

        //获取当前用户组信息
        $groupId =  isset($_GET['id'])?$_GET['id']:'';
		$groupUserList = array();
		if(!empty($groupId)) {
			$this->assign("selectGroupId",$groupId);
			//获取当前组的用户列表
            $list	=	$group->getGroupUserList($groupId);
			foreach ($list as $vo){
				$groupUserList[$vo['id']]	=	$vo['id'];
			}

		}
		$this->assign('groupUserList',$groupUserList);
        $this->assign('userList',$userList);
        $this->display();

        return;
    }

    public function select($fields='id,name',$title='') {
        //创建数据对象
        $Group = M('Role');
        //查找满足条件的列表数据
        $list     = $Group->where('status=1')->field($field)->select();
        $this->assign('list',$list);
        $this->display();
        return;
    }
}