<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class UserController extends AdminController {

    static protected $allow = array( 'updatePassword','updateNickname','submitPassword','submitNickname');
	/**
     * 左侧导航节点定义
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    static protected $nodes = array(
        /* 系统设置 */
        array( 'title' => '用户信息', 'url' => 'User/index', 'group' => '用户管理'),
        array( 'title' => '用户行为', 'url' => 'User/action', 'group' => '用户管理',
            'operator'=>array(
                //权限管理页面的五种按钮
                array('title'=>'新增用户行为','url'=>'user/addAction','tip'=>'"用户->用户行为"中的新增'),
                array('title'=>'编辑用户行为','url'=>'user/editAction','tip'=>'"用户->用户行为"点击标题进行编辑'),
                array('title'=>'保存用户行为','url'=>'user/saveAction','tip'=>'"用户->用户行为"保存编辑和新增的用户行为'),
                array('title'=>'变更行为状态','url'=>'user/setStatus','tip'=>'"用户->用户行为"中的启用,禁用和删除权限'),
                array('title'=>'禁用会员','url'=>'user/changeStatus?method=forbidUser','tip'=>'"用户->用户信息"中的禁用'),
                array('title'=>'启用会员','url'=>'user/changeStatus?method=resumeUser','tip'=>'"用户->用户信息"中的启用'),
                array('title'=>'删除会员','url'=>'user/changeStatus?method=deleteUser','tip'=>'"用户->用户信息"中的删除'),
            ),
        ),
    );

    /**
     * 用户管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $Member = M("Member")->field(true)->where(array('status'=>array('egt',0)))->order('uid DESC');
        $list   = $this->lists($Member);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->display();
    }

    /**
     * 修改昵称初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updateNickname(){
        $nickname = M('Member')->getFieldByUid(UID, 'nickname');
        $this->assign('nickname', $nickname);
        $this->meta_title = '修改昵称';
        $this->display();
    }

    /**
     * 修改昵称提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitNickname(){
        //获取参数
        $uid = UID;
        $nickname = I('post.nickname');
        $password = I('post.password');
        empty($nickname) && $this->error('请输入昵称');
        empty($password) && $this->error('请输入密码');

        //密码验证
        $User = new UserApi();
        $uid = $User->login($uid, $password, 4);
        ($uid == -2) && $this->error('密码不正确');

        $Member = D('Member');
        $data = $Member->create(array('nickname'=>$nickname));
        if(!$data){
            $this->error($Member->getError());
        }

        $res = $Member->where(array('uid'=>$uid))->save($data);

        if($res){
        	$user = session('user_auth');
        	$user['username'] = $data['nickname'];
        	session('user_auth', $user);
        	session('user_auth_sign', data_auth_sign($user));
            $this->success('修改昵称成功！');
        }else{
            $this->error('修改昵称失败！');
        }
    }

    /**
     * 修改密码初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updatePassword(){
    	$this->meta_title = '修改密码';
        $this->display();
    }

    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitPassword(){
        //获取参数
        $uid        =   UID;
        $password   =   I('post.old');
        empty($password) && $this->error('请输入原密码');
        $data['password'] = I('post.password');
        empty($data['password']) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');

        if($data['password'] !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }

        $Api = new UserApi();
        $res = $Api->updateInfo($uid, $password, $data);
        if($res['status']){
            $this->success('修改密码成功！');
        }else{
            $this->error($res['info']);
        }
    }

    /**
     * 用户行为列表
     * @author huajie <banhuajie@163.com>
     */
    public function action(){
        //获取列表数据
        $Action = M('Action')->where(array('status'=>array('gt',-1)));
        $list   = $this->lists($Action);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '用户行为';
        $this->display();
    }

    /**
     * 新增行为
     * @author huajie <banhuajie@163.com>
     */
    public function addAction(){
        $this->meta_title = '新增行为';
        $this->display('editaction');
    }

    /**
     * 编辑行为
     * @author huajie <banhuajie@163.com>
     */
    public function editAction(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Action')->field(true)->find($id);

        $this->assign($data);
        $this->meta_title = '编辑行为';
        $this->display();
    }

    /**
     * 更新行为
     * @author huajie <banhuajie@163.com>
     */
    public function saveAction(){
        $res = D('Action')->update();
        if(!$res){
            $this->error(D('Action')->getError());
        }else{
            if($res['id']){
                $this->success('更新行为成功！', U('action'));
            }else{
                $this->success('新增行为成功！', U('action'));
            }
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author huajie <banhuajie@163.com>
     */
    public function setStatus(){
        /*参数过滤*/
        $ids = I('request.ids');
        $status = I('request.status');
        if(empty($ids) || !isset($status)){
            $this->error('请选择要操作的数据');
        }

        /*拼接参数并修改状态*/
        $Model = 'Action';
        $map = array();
        if(is_array($ids)){
            $map['id'] = array('in', implode(',', $ids));
        }elseif (is_numeric($ids)){
            $map['id'] = $ids;
        }
        switch ($status){
            case -1 : $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));break;
            case 0 : $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));break;
            case 1 : $this->resume($Model, $map, array('success'=>'启用成功','error'=>'启用失败'));break;
            default : $this->error('参数错误');break;
        }
    }

    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id    = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id    = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Member', array('uid'=>array('in',$id)) );
                break;
            case 'resumeuser':
                $this->resume('Member', array('uid'=>array('in',$id)) );
                break;
            case 'deleteuser':
                $this->delete('Member', array('uid'=>array('in',$id)) );
                break;
            default:
                $this->error('参数非法');
        }
    }

}
