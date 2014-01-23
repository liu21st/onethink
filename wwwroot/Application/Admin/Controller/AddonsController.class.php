<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 扩展后台管理页面
 * @author yangweijie <yangweijiester@gmail.com>
 */
class AddonsController extends AdminController {

    public function _initialize(){
        $this->assign('_extra_menu',array(
            '已装插件后台'=> D('Addons')->getAdminList(),
        ));
        parent::_initialize();
    }

    //创建向导首页
    public function create(){
        if(!is_writable(ONETHINK_ADDON_PATH))
            $this->error('您没有创建目录写入权限，无法使用此功能');

        $hooks = M('Hooks')->field('name,description')->select();
        $this->assign('Hooks',$hooks);
        $this->meta_title = '创建向导';
        $this->display('create');
    }

    //预览
    public function preview($output = true){
        $data                   =   $_POST;
        $data['info']['status'] =   (int)$data['info']['status'];
        $extend                 =   array();
        $custom_config          =   trim($data['custom_config']);
        if($data['has_config'] && $custom_config){
            $custom_config = <<<str


        public \$custom_config = '{$custom_config}';
str;
            $extend[] = $custom_config;
        }

        $admin_list = trim($data['admin_list']);
        if($data['has_adminlist'] && $admin_list){
            $admin_list = <<<str


        public \$admin_list = array(
            {$admin_list}
        );
str;
           $extend[] = $admin_list;
        }

        $custom_adminlist = trim($data['custom_adminlist']);
        if($data['has_adminlist'] && $custom_adminlist){
            $custom_adminlist = <<<str


        public \$custom_adminlist = '{$custom_adminlist}';
str;
            $extend[] = $custom_adminlist;
        }

        $extend = implode('', $extend);
        $hook = '';
        foreach ($data['hook'] as $value) {
            $hook .= <<<str
        //实现的{$value}钩子方法
        public function {$value}(\$param){

        }

str;
        }

        $tpl = <<<str
<?php

namespace Addons\\{$data['info']['name']};
use Common\Controller\Addon;

/**
 * {$data['info']['title']}插件
 * @author {$data['info']['author']}
 */

    class {$data['info']['name']}Addon extends Addon{

        public \$info = array(
            'name'=>'{$data['info']['name']}',
            'title'=>'{$data['info']['title']}',
            'description'=>'{$data['info']['description']}',
            'status'=>{$data['info']['status']},
            'author'=>'{$data['info']['author']}',
            'version'=>'{$data['info']['version']}'
        );{$extend}

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

{$hook}
    }
str;
        if($output)
            exit($tpl);
        else
            return $tpl;
    }

    public function checkForm(){
        $data                   =   $_POST;
        $data['info']['name']   =   trim($data['info']['name']);
        if(!$data['info']['name'])
            $this->error('插件标识必须');
        //检测插件名是否合法
        $addons_dir             =   ONETHINK_ADDON_PATH;
        if(file_exists("{$addons_dir}{$data['info']['name']}")){
            $this->error('插件已经存在了');
        }
        $this->success('可以创建');
    }

    public function build(){
        $data                   =   $_POST;
        $data['info']['name']   =   trim($data['info']['name']);
        $addonFile              =   $this->preview(false);
        $addons_dir             =   ONETHINK_ADDON_PATH;
        //创建目录结构
        $files          =   array();
        $addon_dir      =   "$addons_dir{$data['info']['name']}/";
        $files[]        =   $addon_dir;
        $addon_name     =   "{$data['info']['name']}Addon.class.php";
        $files[]        =   "{$addon_dir}{$addon_name}";
        if($data['has_config'] == 1);//如果有配置文件
            $files[]    =   $addon_dir.'config.php';

        if($data['has_outurl']){
            $files[]    =   "{$addon_dir}Controller/";
            $files[]    =   "{$addon_dir}Controller/{$data['info']['name']}Controller.class.php";
            $files[]    =   "{$addon_dir}Model/";
            $files[]    =   "{$addon_dir}Model/{$data['info']['name']}Model.class.php";
        }
        $custom_config  =   trim($data['custom_config']);
        if($custom_config)
            $data[]     =   "{$addon_dir}{$custom_config}";

        $custom_adminlist = trim($data['custom_adminlist']);
        if($custom_adminlist)
            $data[]     =   "{$addon_dir}{$custom_adminlist}";

        create_dir_or_files($files);

        //写文件
        file_put_contents("{$addon_dir}{$addon_name}", $addonFile);
        if($data['has_outurl']){
            $addonController = <<<str
<?php

namespace Addons\\{$data['info']['name']}\Controller;
use Home\Controller\AddonsController;

class {$data['info']['name']}Controller extends AddonsController{

}

str;
            file_put_contents("{$addon_dir}Controller/{$data['info']['name']}Controller.class.php", $addonController);
            $addonModel = <<<str
<?php

namespace Addons\\{$data['info']['name']}\Model;
use Think\Model;

/**
 * {$data['info']['name']}模型
 */
class {$data['info']['name']}Model extends Model{

}

str;
            file_put_contents("{$addon_dir}Model/{$data['info']['name']}Model.class.php", $addonModel);
        }

        if($data['has_config'] == 1)
            file_put_contents("{$addon_dir}config.php", $data['config']);

        $this->success('创建成功',U('index'));
    }

    /**
     * 插件列表
     */
    public function index(){
        $this->meta_title = '插件列表';
        $list       =   D('Addons')->getList();
        $request    =   (array)I('request.');
        $total      =   $list? count($list) : 1 ;
        $listRows   =   C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        $page       =   new \Think\Page($total, $listRows, $request);
        $voList     =   array_slice($list, $page->firstRow, $page->listRows);
        $p          =   $page->show();
        $this->assign('_list', $voList);
        $this->assign('_page', $p? $p: '');
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->display();
    }

    /**
     * 插件后台显示页面
     * @param string $name 插件名
     */
    public function adminList($name){
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $class = get_addon_class($name);
        if(!class_exists($class))
            $this->error('插件不存在');
        $addon  =   new $class();
        $this->assign('addon', $addon);
        $param  =   $addon->admin_list;
        if(!$param)
            $this->error('插件列表信息不正确');
        $this->meta_title = $addon->info['title'];
        extract($param);
        $this->assign('title', $addon->info['title']);
        $this->assign($param);
        if(!isset($fields))
            $fields = '*';
        if(!isset($map))
            $map = array();
        if(isset($model))
            $list = $this->lists(D("Addons://{$model}/{$model}")->field($fields),$map);
        $this->assign('_list', $list);
        if($addon->custom_adminlist)
            $this->assign('custom_adminlist', $this->fetch($addon->addon_path.$addon->custom_adminlist));
        $this->display();
    }

    /**
     * 启用插件
     */
    public function enable(){
        $id     =   I('id');
        $msg    =   array('success'=>'启用成功', 'error'=>'启用失败');
        S('hooks', null);
        $this->resume('Addons', "id={$id}", $msg);
    }

    /**
     * 禁用插件
     */
    public function disable(){
        $id     =   I('id');
        $msg    =   array('success'=>'禁用成功', 'error'=>'禁用失败');
        S('hooks', null);
        $this->forbid('Addons', "id={$id}", $msg);
    }

    /**
     * 设置插件页面
     */
    public function config(){
        $id     =   (int)I('id');
        $addon  =   M('Addons')->find($id);
        if(!$addon)
            $this->error('插件未安装');
        $addon_class = get_addon_class($addon['name']);
        if(!class_exists($addon_class))
            trace("插件{$addon['name']}无法实例化,",'ADDONS','ERR');
        $data  =   new $addon_class;
        $addon['addon_path'] = $data->addon_path;
        $addon['custom_config'] = $data->custom_config;
        $this->meta_title   =   '设置插件-'.$data->info['title'];
        $db_config = $addon['config'];
        $addon['config'] = include $data->config_file;
        if($db_config){
            $db_config = json_decode($db_config, true);
            foreach ($addon['config'] as $key => $value) {
                if($value['type'] != 'group'){
                    $addon['config'][$key]['value'] = $db_config[$key];
                }else{
                    foreach ($value['options'] as $gourp => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                        }
                    }
                }
            }
        }
        $this->assign('data',$addon);
        if($addon['custom_config'])
            $this->assign('custom_config', $this->fetch($addon['addon_path'].$addon['custom_config']));
        $this->display();
    }

    /**
     * 保存插件设置
     */
    public function saveConfig(){
        $id     =   (int)I('id');
        $config =   I('config');
        $flag = M('Addons')->where("id={$id}")->setField('config',json_encode($config));
        if($flag !== false){
            $this->success('保存成功', Cookie('__forward__'));
        }else{
            $this->error('保存失败');
        }
    }

    /**
     * 安装插件
     */
    public function install(){
        $addon_name     =   trim(I('addon_name'));
        $class          =   get_addon_class($addon_name);
        if(!class_exists($class))
            $this->error('插件不存在');
        $addons  =   new $class;
        $info = $addons->info;
        if(!$info || !$addons->checkInfo())//检测信息的正确性
            $this->error('插件信息缺失');
        session('addons_install_error',null);
        $install_flag   =   $addons->install();
        if(!$install_flag){
            $this->error('执行插件预安装操作失败'.session('addons_install_error'));
        }
        $addonsModel    =   D('Addons');
        $data           =   $addonsModel->create($info);
        if(is_array($addons->admin_list) && $addons->admin_list !== array()){
            $data['has_adminlist'] = 1;
        }else{
            $data['has_adminlist'] = 0;
        }
        if(!$data)
            $this->error($addonsModel->getError());
        if($addonsModel->add($data)){
            $config         =   array('config'=>json_encode($addons->getConfig()));
            $addonsModel->where("name='{$addon_name}'")->save($config);
            $hooks_update   =   D('Hooks')->updateHooks($addon_name);
            if($hooks_update){
                S('hooks', null);
                $this->success('安装成功');
            }else{
                $addonsModel->where("name='{$addon_name}'")->delete();
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
        $addonsModel    =   M('Addons');
        $id             =   trim(I('id'));
        $db_addons      =   $addonsModel->find($id);
        $class          =   get_addon_class($db_addons['name']);
        $this->assign('jumpUrl',U('index'));
        if(!$db_addons || !class_exists($class))
            $this->error('插件不存在');
        session('addons_uninstall_error',null);
        $addons =   new $class;
        $uninstall_flag =   $addons->uninstall();
        if(!$uninstall_flag)
            $this->error('执行插件预卸载操作失败'.session('addons_uninstall_error'));
        $hooks_update   =   D('Hooks')->removeHooks($db_addons['name']);
        if($hooks_update === false){
            $this->error('卸载插件所挂载的钩子数据失败');
        }
        S('hooks', null);
        $delete = $addonsModel->where("name='{$db_addons['name']}'")->delete();
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
        $this->meta_title   =   '钩子列表';
        $map    =   $fields =   array();
        $list   =   $this->lists(D("Hooks")->field($fields),$map);
        int_to_string($list, array('type'=>C('HOOKS_TYPE')));
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('list', $list );
        $this->display();
    }

    public function addhook(){
        $this->assign('data', null);
        $this->meta_title = '新增钩子';
        $this->display('edithook');
    }

    //钩子出编辑挂载插件页面
    public function edithook($id){
        $hook = M('Hooks')->field(true)->find($id);
        $this->assign('data',$hook);
        $this->meta_title = '编辑钩子';
        $this->display('edithook');
    }

    //超级管理员删除钩子
    public function delhook($id){
        if(M('Hooks')->delete($id) !== false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    public function updateHook(){
        $hookModel  =   D('Hooks');
        $data       =   $hookModel->create();
        if($data){
            if($data['id']){
                $flag = $hookModel->save($data);
                if($flag !== false)
                    $this->success('更新成功', Cookie('__forward__'));
                else
                    $this->error('更新失败');
            }else{
                $flag = $hookModel->add($data);
                if($flag)
                    $this->success('新增成功', Cookie('__forward__'));
                else
                    $this->error('新增失败');
            }
        }else{
            $this->error($hookModel->getError());
        }
    }

    public function execute($_addons = null, $_controller = null, $_action = null){
        if(C('URL_CASE_INSENSITIVE')){
            $_addons        =   ucfirst(parse_name($_addons, 1));
            $_controller    =   parse_name($_controller,1);
        }

        if(!empty($_addons) && !empty($_controller) && !empty($_action)){
            $Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }

}
