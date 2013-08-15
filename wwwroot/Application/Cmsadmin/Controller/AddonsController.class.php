<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

/**
 * 扩展后台管理页面
 * @authors yangweijie <yangweijiester@gmail.com>
 * @date    2013-08-14 11:20:04
 */

class AddonsController extends CmsadminController {

    public function index(){
        $this->assign('list',D('Addons')->getList());
        $this->display();
    }

    /**
     * 启用插件
     */
    public function enable(){
        $id = I('id');
        $flag = D('Addons')->where("id={$id}")->setField('status', 1);
        if($flag !== false)
            $this->success('启用成功');
        else
            $this->error('启用失败');
    }

    /**
     * 禁用插件
     */
    public function disable(){
        $id = I('id');
        $flag = D('Addons')->where("id={$id}")->setField('status', 0);
        if($flag !== false)
            $this->success('禁用成功');
        else
            $this->error('禁用失败');
    }

    /**
     * 钩子列表
     */
    public function hooks(){
        $this->assign('list', D('Hooks')->field($field)->order($order)->select());
        $this->display();
    }

    public function updateSort(){
        $addons = trim(I('addons'));
        $id = I('id');
        D('Hooks')->where("id={$id}")->setField('addons', $addons);
        $this->success('更新排序成功');
    }
}
