<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台频道控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class ChannelController extends AdminController {

    /**
     * 频道列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $pid = I('get.pid', 0);
		if($pid){
            $data = M('Channel')->where("id={$pid}")->field(true)->find();
            $this->assign('data',$data);
        }
        /* 获取频道列表 */
        $map  = array('status' => array('gt', -1), 'pid'=>$pid);
		$title =  trim(I('get.title'));
		if($title)
            $map['title'] = array('like',"%{$title}%");
        $list = M('Channel')->where($map)->order('sort asc,id asc')->select();

        $this->assign('list', $list);
        $this->assign('pid', $pid);
		// 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->meta_title = '导航管理';
        $this->display();
    }

    /**
     * 添加频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Channel = D('Channel');
            $data = $Channel->create();
            if($data){
                $id = $Channel->add();
                if($id){
                    $this->success('新增成功', Cookie('__forward__'));
                    //记录行为
                    action_log('update_channel', 'channel', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Channel->getError());
            }
        } else {
            $pid = I('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
                $parent = M('Channel')->where(array('id'=>$pid))->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info',null);
            $this->meta_title = '新增导航';
            $this->display('edit');
        }
    }

    /**
     * 编辑频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Channel = D('Channel');
            $data = $Channel->create();
            if($data){
                if($Channel->save()){
                    //记录行为
                    action_log('update_channel', 'channel', $data['id'], UID);
                    $this->success('编辑成功',Cookie('__forward__'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error($Channel->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Channel')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $pid = I('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
            	$parent = M('Channel')->where(array('id'=>$pid))->field('title')->find();
            	$this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            $this->display();
        }
    }

    /**
     * 删除频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Channel')->where($map)->delete()){
            //记录行为
            action_log('update_channel', 'channel', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
	
	public function import(){
        if(IS_POST){
            $tree = I('post.tree');
            $lists = explode(PHP_EOL, $tree);
            $channelModel = M('Channel');
            if($lists == array()){
                $this->error('请按格式填写批量导入的菜单，至少一个菜单');
            }else{
                $pid = I('post.pid');
                foreach ($lists as $key => $value) {
                    $record = explode('|', $value);
                    if(count($record) == 2){
                        $channelModel->add(array(
                            'title'=>$record[0],
                            'url'=>$record[1],
                            'pid'=>$pid,
                            'sort'=>0,
							'create_time'=>time(),
                            'update_time'=>time(),
                            'status'=>0,
                            'target'=>0,
                        ));
                    }
                }
                $this->success('导入成功',U('index?pid='.$pid));
            }
        }else{
            $this->meta_title = '批量导入前台导航';
            $pid = (int)I('get.pid');
            $this->assign('pid', $pid);
            $data = M('Channel')->where("id={$pid}")->field(true)->find();
            $this->assign('data', $data);
            $this->display();
        }
    }
	
    /**
     * 导航排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort(){
        if(IS_GET){
            $ids = I('get.ids');
            $pid = I('get.pid');

            //获取排序的数据
            $map = array('status'=>array('gt',-1));
            if(!empty($ids)){
                $map['id'] = array('in',$ids);
            }else{
                if($pid !== ''){
                    $map['pid'] = $pid;
                }
            }
            $list = M('Channel')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = '导航排序';
            $this->display();
        }elseif (IS_POST){
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = M('Channel')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}
