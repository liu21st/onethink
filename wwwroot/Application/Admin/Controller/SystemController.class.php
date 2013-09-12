<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Controller;
/**
 * 后台系统控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class SystemController extends AdminController {

    /**
     * 左侧导航节点定义
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    static protected $nodes = array(
    	/* 系统设置 */
        
        
        /* 导航栏目设置 */
        array( 'title' => '导航管理', 'url' => 'System/channel', 'group' => '导航栏目设置'),

        /* 其他设置 */
        // array( 'title' => '数据迁移', 'url' => 'System/index5', 'group' => '其他设置'),
        // array( 'title' => '数据备份/恢复', 'url' => 'System/index6', 'group' => '其他设置'),
        // array( 'title' => '系统日志', 'url' => 'System/index7', 'group' => '其他设置'),
    );
	
	

    /**
     * 频道管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function channel(){
        /* 获取频道列表 */
        $map  = array('status' => 1);
        $list = M('Channel')->where($map)->order('id DESC')->select();

        $this->assign('list', $list);
        $this->display();
    }

    public function channelAdd(){
        if(IS_POST){
            $Channel = D('Channel');
            $data = $Channel->create();
            if($data){

                if($Channel->add()){
                    $this->success('新增成功', U('channel'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Channel->getError());
            }
        } else {
            $this->display('channeledit');
        }
    }

    public function channelEdit($id = 0){
        if(IS_POST){
            $Channel = D('Channel');
            $data = $Channel->create();
            if($data){
                if($Channel->save()){
                    $this->success('编辑成功', U('channel'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error($Channel->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = D('Channel')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }
            
            $this->assign('info', $info);
            $this->display();
        }
    }

}
