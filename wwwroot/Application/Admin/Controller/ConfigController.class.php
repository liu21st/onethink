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
 * 后台配置控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class ConfigController extends AdminController {

	/**
     * 左侧导航节点定义
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    static protected $nodes = array(
        array( 'title' => '基本设置', 'url' => 'Config/base', 'group' => '系统设置'),
        array( 'title' => '配置管理', 'url' => 'Config/index', 'group' => '系统设置',
            'operator'=>array(
                array('title'=>'编辑','url'=>'Config/edit','tip'=>'新增编辑和保存配置'),
                array('title'=>'编辑','url'=>'Config/del','tip'=>'删除配置'),
            ),
        ),
        // array( 'title' => '静态规则设置', 'url' => 'System/index1', 'group' => '系统设置'),
        // array( 'title' => 'SEO优化设置', 'url' => 'System/index2', 'group' => '系统设置'),
    );

    /**
     * 配置管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $list = D('Config')->where($map)->order('id DESC')->select();
        $this->assign('list', $list);
        $this->display();
    }

    /**
	 * 基本设置
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function base(){
		$this->display();
	}

    /**
     * 编辑配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Config = D('Config');
            $data = $Config->create();
            if($data){
                if($data['id']){
                    $status = $Config->save();
                } else {
                    $status = $Config->add(); 
                }

                if($status){
                    $this->success('操作成功', U('index'));
                } else {
                    $this->error('操作失败');
                }

            } else {
                $this->error($Config->getError());
            }
        } else {
            $info = array();
            if($id){
                /* 获取数据 */
                $info = D('Config')->find($id);

                if(false === $info){
                    $this->error('获取配置信息错误');
                }
            }
            
            $this->assign('info', $info);
            $this->display();
        }
    }

    /**
     * 批量保存配置
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function save($config){
    	if($config && is_array($config)){
    		$Config = D('Config');
    		foreach ($config as $name => $value) {
    			$map = array('name' => $name);
    			$Config->where($map)->setField('value', $value);
    		}
    	}
    	$this->success('保存成功！');
    }
}
