<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 朱亚杰 <zhuyajie@topthink.net>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\AuthRule;
use app\admin\model\AuthGroup;

/**
 * 权限管理控制器
 * Class AuthManagerController
 * @author 朱亚杰 <zhuyajie@topthink.net>
 */
class AuthManager extends Admin
{

    /**
     * 后台节点配置的url作为规则存入auth_rule
     * 执行新节点的插入,已有节点的更新,无效规则的删除三项任务
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function updateRules()
    {
        //需要新增的节点必然位于$nodes
        $nodes = $this->returnNodes(false);

        $AuthRule = model('AuthRule');
        $map = array('module' => 'admin', 'type' => array('in', '1,2'));//status全部取出,以进行更新
        //需要更新和删除的节点必然位于$rules
        $rules = $AuthRule->where($map)->order('name')->select();

        //构建insert数据
        $data = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value) {
            $temp['name'] = $value['url'];
            $temp['title'] = $value['title'];
            $temp['module'] = 'admin';
            if ($value['pid'] > 0) {
                $temp['type'] = AuthRule::RULE_URL;
            } else {
                $temp['type'] = AuthRule::RULE_MAIN;
            }
            $temp['status'] = 1;
            $data[strtolower($temp['name'] . $temp['module'] . $temp['type'])] = $temp;//去除重复项
        }

        $update = array();//保存需要更新的节点
        $ids = array();//保存需要删除的节点的id
        foreach ($rules as $index => $rule) {
            $key = strtolower($rule['name'] . $rule['module'] . $rule['type']);
            if (isset($data[$key])) {//如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id'];//为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]);
                unset($rules[$index]);
                unset($rule['condition']);
                $diff[$rule['id']] = $rule;
            } elseif ($rule['status'] == 1) {
                $ids[] = $rule['id'];
            }
        }
        if (count($update)) {
            foreach ($update as $k => $row) {
                if ($row != $diff[$row['id']]) {
                    $AuthRule->where(array('id' => $row['id']))->update($row);
                }
            }
        }
        if (count($ids)) {
            $AuthRule->where(array('id' => array('IN', implode(',', $ids))))->update(array('status' => -1));
            //删除规则是否需要从每个用户组的访问授权表中移除该规则?
        }
        if (count($data)) {
            $AuthRule->saveAll(array_values($data));
        }
        if ($AuthRule->getError()) {
            trace('[' . __METHOD__ . ']:' . $AuthRule->getError());
            return false;
        } else {
            return true;
        }
    }


    /**
     * 权限管理首页
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function index()
    {
        $list = $this->lists('AuthGroup', array('module' => 'admin'), 'id asc');
        $list = int_to_string($list);
        $this->assign('_list', $list);
        $this->assign('_use_tip', true);
        $this->meta_title = '权限管理';
        return $this->fetch();
    }

    /**
     * 创建管理员用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function createGroup()
    {
        if (empty($this->auth_group)) {
            $this->assign('auth_group', array('title' => null, 'id' => null, 'description' => null, 'rules' => null,));//排除notice信息
        }
        $this->meta_title = '新增用户组';
        return $this->fetch('editgroup');
    }

    /**
     * 编辑管理员用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function editGroup()
    {
        $auth_group = db('AuthGroup')->where(array('module' => 'admin', 'type' => AuthGroup::TYPE_ADMIN))
            ->find((int)$_GET['id']);
        $this->assign('auth_group', $auth_group);
        $this->meta_title = '编辑用户组';
        return $this->fetch();
    }


    /**
     * 访问授权页面
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function access()
    {
        $this->updateRules();
        $auth_group = db('AuthGroup')->where(array('status' => array('egt', '0'), 'module' => 'admin', 'type' => AuthGroup::TYPE_ADMIN))
            ->column('id,id,title,rules');
        $node_list = $this->returnNodes();
        $map = array('module' => 'admin', 'type' => AuthRule::RULE_MAIN, 'status' => 1);
        $main_rules = db('AuthRule')->where($map)->column('name,id');
        $map = array('module' => 'admin', 'type' => AuthRule::RULE_URL, 'status' => 1);
        $child_rules = db('AuthRule')->where($map)->column('name,id');
        $this->assign('main_rules', $main_rules);
        $this->assign('auth_rules', $child_rules);
        $this->assign('node_list', $node_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[(int)$_GET['group_id']]);
        $this->meta_title = '访问授权';
        return $this->fetch('managergroup');
    }

    /**
     * 管理员用户组数据写入/更新
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function writeGroup()
    {
        if (isset($_POST['rules'])) {
            sort($_POST['rules']);
            $_POST['rules'] = implode(',', array_unique($_POST['rules']));
        }
        $_POST['module'] = 'admin';
        $_POST['type'] = AuthGroup::TYPE_ADMIN;
        $AuthGroup = model('AuthGroup');

        $validate = validate('AuthGroup');
        if (!$validate->check($_POST)) {
            $this->error($validate->getError());
        } else {
            if (empty($_POST['id'])) {
                $r = $AuthGroup->save($_POST);
            } else {
                $r = $AuthGroup->update($_POST);
            }
            if ($r === false) {
                $this->error('操作失败' . $AuthGroup->getError());
            } else {
                $this->success('操作成功!', url('index'));
            }
        }
//        $data = $AuthGroup->create();
//        if ( $data ) {
//            if ( empty($data['id']) ) {
//                $r = $AuthGroup->add();
//            }else{
//                $r = $AuthGroup->save();
//            }
//            if($r===false){
//                $this->error('操作失败'.$AuthGroup->getError());
//            } else{
//                $this->success('操作成功!',url('index'));
//            }
//        }else{
//            $this->error('操作失败'.$AuthGroup->getError());
//        }
    }

    /**
     * 状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method = null)
    {
        if (empty($_REQUEST['id'])) {
            $this->error('请选择要操作的数据!');
        }
        $where = array();
        $model = 'AuthGroup';
        //TODO::删改直接走admin控制器editRow()方法，不再走中间forbid()、resume()、delete()等
        switch (strtolower($method)) {
            case 'forbidgroup':
                $data = array('status' => 0);
                return $this->editRow($model, $data, $where, $msg = array('success' => '状态禁用成功！', 'error' => '状态禁用失败！'));
//               return $this->forbid('AuthGroup');
                break;
            case 'resumegroup':
                $data = array('status' => 1);
                return $this->editRow($model, $data, $where, $msg = array('success' => '状态恢复成功！', 'error' => '状态恢复失败！'));
//                $this->resume('AuthGroup');
                break;
            case 'deletegroup':
                $data['status'] = -1;
                return $this->editRow($model, $data, $where,  $msg = array('success' => '删除成功！', 'error' => '删除失败！'));
//                $this->delete('AuthGroup');
                break;
            default:
                $this->error($method . '参数非法');
        }
    }

    /**
     * 用户组授权用户列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function user()
    {
        $group_id=$this->request->get('group_id','');
        if (empty($group_id)) {
            $this->error('参数错误');
        }
        $auth_group = db('AuthGroup')->where(array('status' => array('egt', '0'), 'module' => 'admin', 'type' => AuthGroup::TYPE_ADMIN))
            ->column('id,id,title,rules');
        $prefix = config('DB_PREFIX');
        $l_table = $prefix . (AuthGroup::MEMBER);
        $r_table = $prefix . (AuthGroup::AUTH_GROUP_ACCESS);
        $where=array('a.group_id' => $group_id, 'm.status' => array('egt', 0));
        $order='m.uid asc';
        $fields='m.uid,m.nickname,m.last_login_time,m.last_login_ip,m.status';
        $list = Db($l_table)->alias('m')->join($r_table.' a','m.uid=a.uid')->where($where)->order($order)->field($fields)->select();
//        $model = db()->table($l_table . ' m')->join($r_table . ' a ON m.uid=a.uid');
        $_REQUEST = array();
//        $list = $this->lists($model, array('a.group_id' => $group_id, 'm.status' => array('egt', 0)), 'm.uid asc', 'm.uid,m.nickname,m.last_login_time,m.last_login_ip,m.status');
        int_to_string($list);
        $this->assign('_page', '分页');
        $this->assign('_total', 1);//分页暂时不处理
        $this->assign('_list', $list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[(int)$_GET['group_id']]);
        $this->meta_title = '成员授权';
        return $this->fetch();
    }

    /**
     * 将分类添加到用户组的编辑页面
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function category()
    {
        $auth_group = db('AuthGroup')->where(array('status' => array('egt', '0'), 'module' => 'admin', 'type' => AuthGroup::TYPE_ADMIN))
            ->column('id,id,title,rules');
        $group_list = model('Category')->getTree();
        $authed_group = AuthGroup::getCategoryOfGroup(input('group_id'));
        $this->assign('authed_group', implode(',', (array)$authed_group));
        $this->assign('group_list', $group_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[(int)$_GET['group_id']]);
        $this->meta_title = '分类授权';
        return $this->fetch();
    }

    public function tree($tree = null)
    {
        $this->assign('tree', $tree);
        return $this->fetch('tree');
    }

    /**
     * 将用户添加到用户组的编辑页面
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function group()
    {
        $uid = input('uid');
        $auth_groups = model('AuthGroup')->getGroups();
        $user_groups = AuthGroup::getUserGroup($uid);
        $ids = array();
        foreach ($user_groups as $value) {
            $ids[] = $value['group_id'];
        }
        $nickname = model('Member')->getNickName($uid);
        $this->assign('nickname', $nickname);
        $this->assign('auth_groups', $auth_groups);
        $this->assign('user_groups', implode(',', $ids));
        $this->meta_title = '用户组授权';
        return $this->fetch();
    }

    /**
     * 将用户添加到用户组,入参uid,group_id
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function addToGroup()
    {
        $uid = input('uid');
        $gid = input('group_id/a');
        if (empty($uid)) {
            $this->error('参数有误');
        }
        $AuthGroup = model('AuthGroup');
        if (is_numeric($uid)) {
            if (is_administrator($uid)) {
                $this->error('该用户为超级管理员');
            }
            if (!db('Member')->where(array('uid' => $uid))->find()) {
                $this->error('用户不存在');
            }
        }

        if ($gid && !$AuthGroup->checkGroupId($gid)) {
            $this->error($AuthGroup->error);
        }
        if ($AuthGroup->addToGroup($uid, $gid)) {
            $this->success('操作成功');
        } else {
            $this->error($AuthGroup->getError());
        }
    }

    /**
     * 将用户从用户组中移除  入参:uid,group_id
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function removeFromGroup()
    {
        $uid = input('uid');
        $gid = input('group_id');
        if ($uid == UID) {
            $this->error('不允许解除自身授权');
        }
        if (empty($uid) || empty($gid)) {
            $this->error('参数有误');
        }
        $AuthGroup = model('AuthGroup');
        if (!$AuthGroup->find($gid)) {
            $this->error('用户组不存在');
        }
        if ($AuthGroup->removeFromGroup($uid, $gid)) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 将分类添加到用户组  入参:cid,group_id
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function addToCategory()
    {
        $cid = input('cid/a');
        $gid = input('group_id');
        if (empty($gid)) {
            $this->error('参数有误');
        }
        $AuthGroup = model('AuthGroup');
        if (!$AuthGroup->find($gid)) {
            $this->error('用户组不存在');
        }
        if ($cid && !$AuthGroup->checkCategoryId($cid)) {
            $this->error($AuthGroup->error);
        }
        if ($AuthGroup->addToCategory($gid, $cid)) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

    /**
     * 将模型添加到用户组  入参:mid,group_id
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    public function addToModel()
    {
        $mid = input('id');
        $gid = input('get.group_id');
        if (empty($gid)) {
            $this->error('参数有误');
        }
        $AuthGroup = model('AuthGroup');
        if (!$AuthGroup->find($gid)) {
            $this->error('用户组不存在');
        }
        if ($mid && !$AuthGroup->checkModelId($mid)) {
            $this->error($AuthGroup->error);
        }
        if ($AuthGroup->addToModel($gid, $mid)) {
            $this->success('操作成功');
        } else {
            $this->error('操作失败');
        }
    }

}
