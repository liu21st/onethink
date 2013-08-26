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
 * @author yangweijie <yangweijiester@gmail.com>
 */

class AddonsController extends AdminController {
    static protected $nodes = array(
        array( 'title'=>'模型管理', 'url'=>'Addons/index', 'group'=>'扩展'),
        array( 'title'=>'插件管理', 'url'=>'Addons/index', 'group'=>'扩展'),
        array( 'title'=>'钩子管理', 'url'=>'Addons/hooks', 'group'=>'扩展'),
    );

    public function index(){
        $this->assign('list',D('Addons')->getList());
        $this->display();
    }

    /**
     * 启用插件
     */
    public function enable(){
        $id = I('id');
        $msg = array('success'=>'启用成功', 'error'=>'启用失败');
        $this->resume('Addons', "id={$id}", $msg);
    }

    /**
     * 禁用插件
     */
    public function disable(){
        $id = I('id');
        $msg = array('success'=>'禁用成功', 'error'=>'禁用失败');
        $this->forbid('Addons', "id={$id}", $msg);
    }

    /**
     * 设置插件页面
     */
    public function config(){
        $id = (int)I('id');
        $addon = D('Addons')->find($id);
        if(!$addon)
            $this->error('插件未安装');
        $this->assign('data',$addon);
        $this->display();
    }

    /**
     * 保存插件设置
     */
    public function saveConfig(){
        $id = (int)I('id');
        $config = I('config');
        $flag = D('Addons')->where("id={$id}")->setField('config',json_encode($config));
        if($flag !== false){
            $this->success('保存成功');
        }else{
            $this->error('保存失败');
        }
    }

    /**
     * 安装插件
     */
    public function install(){
    	$addons = addons(trim(I('addon_name')));
    	if(!$addons)
    		$this->error('插件不存在');
		$info = $addons->info;
		if(!$info || !$addons->checkInfo())//检测信息的正确性
			$this->error('插件信息缺失');
		$install_flag = $addons->install();
		if(!$install_flag)
			$this->error('执行插件预安装操作失败');

		$addonsModel = D('Addons');
		$data = $addonsModel->create($info);
		if(!$data)
			$this->error($addonsModel->getError());
		if($addonsModel->add()){
            if($hooks_update = D('Hooks')->updateHooks($addons->getName())){
                S('hooks', null);
                $this->success('安装成功');
            }else{
                $this->error('更新钩子处插件失败,请卸载后尝试重新安装');
            }

		}else{
			$this->error('写入插件数据失败');
		}
    }

    /**
     * 卸载插件
     */
    public function uninstall(){
    	$addonsModel = D('Addons');
    	$id = trim(I('id'));
    	$db_addons = $addonsModel->find($id);
    	$addons = addons($db_addons['name']);
        $this->assign('jumpUrl',U('index'));
    	if(!$db_addons || !$addons)
    		$this->error('插件不存在');
    	$uninstall_flag = $addons->uninstall();
		if(!$uninstall_flag)
			$this->error('执行插件预卸载操作失败');
        $hooks_update = D('Hooks')->removeHooks($addons->getName());
        if($hooks_update === false){
            $this->error('卸载插件所挂载的钩子数据失败');
        }
        S('hooks', null);
		$delete = $addonsModel->delete($id);
		if($delete === false){
			$this->error('卸载插件失败');
		}else{
			$this->success('卸载成功');
		}
    }

    /**
     * 钩子列表
     */
    public function hooks(){
        $order = $field = array();
        $this->assign('list', D('Hooks')->field($field)->order($order)->select());
        $this->display();
    }

    public function updateSort(){
        $addons = trim(I('addons'));
        $id = I('id');
        D('Hooks')->where("id={$id}")->setField('addons', $addons);
        S('hooks', null);
        $this->success('更新成功');
    }

    public function execute($_addons = null, $_controller = null, $_action = null){
        if(C('URL_CASE_INSENSITIVE')){
            $_addons = ucfirst(strtolower($_addons));
            $_controller = parse_name($_controller,1);
        }

        if(!empty($_addons) && !empty($_controller) && !empty($_action)){
            $Addons = A("Addons://{$_addons}/{$_controller}")->setName($_addons)->$_action();
        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }

    /**
     * 设置当前插件名称
     * @param string $name 插件名称
     */
    protected function setName($name){
        $this->addons = $name;
        return $this;
    }
}
