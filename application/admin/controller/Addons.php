<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
/**
 * 扩展后台管理页面
 * @author yangweijie <yangweijiester@gmail.com>
 */
class Addons  extends Admin  {

    public function _initialize(){
        $this->assign('_extra_menu',array(
            '已装插件后台'=> model('Addons')->getAdminList(),
        ));
        parent::_initialize();
    }

    //创建向导首页
    public function create(){
        if(!is_writable(ONETHINK_ADDON_PATH))
            $this->error('您没有创建目录写入权限，无法使用此功能');

        $hooks = db('Hooks')->field('name,description')->select();
        $this->assign('Hooks',$hooks);
        $this->meta_title = '创建向导';
        return $this->fetch('create');
    }

    //预览
    public function preview($output = true){
        $status =   $this->request->post('info.status/d');//(int)$data['info']['status'];
        $extend                 =   array();
        $custom_config          =   trim($this->request->post('custom_config'));
        if($this->request->post('has_config') && $custom_config){
            $custom_config = <<<str


        public \$custom_config = '{$custom_config}';
str;
            $extend[] = $custom_config;
        }

        $admin_list = trim($this->request->post('admin_list'));
        if($this->request->post('has_adminlist') && $admin_list){
            $admin_list = <<<str


        public \$admin_list = array(
            {$admin_list}
        );
str;
           $extend[] = $admin_list;
        }

        $custom_adminlist = trim($this->request->post('custom_adminlist'));
        if($this->request->post('has_adminlist') && $custom_adminlist){
            $custom_adminlist = <<<str


        public \$custom_adminlist = '{$custom_adminlist}';
str;
            $extend[] = $custom_adminlist;
        }

        $name=strtolower($this->request->post('info.name'));
        $extend = implode('', $extend);
        $hook = '';
        $hooks=$this->request->post('hook/a');
        if (!empty($hooks)) {
            foreach ($hooks as $value) {
                $hook .= <<<str
        //实现的{$value}钩子方法
        public function {$value}(\$param){
            
        }
            
str;
            }
        }
        
        $tpl = <<<str
<?php

namespace app\addons\\{$name};
use app\common\controller\Addon;

/**
 * {$this->request->post('info.title')}插件
 * @author {$this->request->post('info.author')}
 */

    class {$this->request->post('info.name')} extends Addon{

        public \$info = array(
            'name'=>'{$this->request->post('info.name')}',
            'title'=>'{$this->request->post('info.title')}',
            'description'=>'{$this->request->post('info.description')}',
            'status'=>{$this->request->post('info.status')},
            'author'=>'{$this->request->post('info.author')}',
            'version'=>'{$this->request->post('info.version')}'
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
            return json(['info'=>'插件标识必须','status'=>0]);
//             $this->error('插件标识必须');
        //检测插件名是否合法
        $addons_dir             =   ONETHINK_ADDON_PATH;
        if(file_exists("{$addons_dir}{$data['info']['name']}")){
            return json(['info'=>'插件已经存在了','status'=>0]);
//             $this->error('插件已经存在了');
        }
        return json(['info'=>'可以创建','status'=>1]);
        //$this->success('可以创建');
    }

    public function build(){
        $data                   =   $_POST;
        $data['info']['name']   =   trim($data['info']['name']);
        $name                   =   strtolower($data['info']['name']);
        $addonFile              =   $this->preview(false);
        $addons_dir             =   ONETHINK_ADDON_PATH;
        //创建目录结构
        $files          =   [];
        $addon_dir      =   "$addons_dir{$name}/";
        $files[]        =   $addon_dir;
        $addon_name     =   "{$data['info']['name']}.php";
        $files[]        =   "{$addon_dir}{$addon_name}";
        if(isset($data['has_config'])&&$data['has_config'] == 1);//如果有配置文件
            $files[]    =   $addon_dir.'config.php';

        if(isset($data['has_outurl'])&&$data['has_outurl']){
            $files[]    =   "{$addon_dir}controller/";
            $files[]    =   "{$addon_dir}controller/{$data['info']['name']}.php";
            $files[]    =   "{$addon_dir}model/";
            $files[]    =   "{$addon_dir}model/{$data['info']['name']}.php";
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
        if(isset($data['has_outurl'])&&$data['has_outurl']){
            $addonController = <<<str
<?php

namespace app\addons\\{$data['info']['name']}\controller;
use app\home\controller\Addons;

class {$data['info']['name']} extends Addons{

}

str;
            file_put_contents("{$addon_dir}controller/{$data['info']['name']}.php", $addonController);
            $addonModel = <<<str
<?php

namespace app\addons\\{$data['info']['name']}\model;
use think\Model;

/**
 * {$data['info']['name']}模型
 */
class {$data['info']['name']} extends Model{
    public \$model = [
        'title'=>'',//新增[title]、编辑[title]、删除[title]的提示
        'template_add'=>'',//自定义新增模板自定义html edit.html 会读取插件根目录的模板
        'template_edit'=>'',//自定义编辑模板html
        'search_key'=>'',// 搜索的字段名，默认是title
        'extend'=>1,
    ];

    public \$_fields = array(
        'id'=>[
            'name'=>'id',//字段名
            'title'=>'ID',//显示标题
            'type'=>'num',//字段类型
            'remark'=>'',// 备注，相当于配置里的tip
            'is_show'=>3,// 1-始终显示 2-新增显示 3-编辑显示 0-不显示
            'value'=>0,//默认值
        ],
        'title'=>[
            'name'=>'title',
            'title'=>'书名',
            'type'=>'string',
            'remark'=>'',
            'is_show'=>1,
            'value'=>0,
            'is_must'=>1,
        ],
    );
}

str;
            file_put_contents("{$addon_dir}model/{$data['info']['name']}.php", $addonModel);
        }

        if(isset($data['has_config'])&&$data['has_config'] == 1)
            file_put_contents("{$addon_dir}config.php", $data['config']);

        return json(['info'=>'创建成功','status'=>1,'url'=>url('index')]);
//         $this->success('创建成功',url('index'));
    }

    /**
     * 插件列表
     */
    public function index(){
        $this->meta_title = '插件列表';
        $list       =   model('Addons')->getList();
//         $request    =   input('request./a');
//         $total      =   $list? count($list) : 1 ;
//         $listRows   =   config('LIST_ROWS') > 0 ? config('LIST_ROWS') : 10;
        
//         $page       =   new \Think\Page($total, $listRows, $request);
//         $voList     =   array_slice($list, $page->firstRow, $page->listRows);
//         $p          =   $page->show();
        $this->assign('_list', $list);
//         $this->assign('_page', $p? $p: '');
        // 记录当前列表页的cookie
        cookie('__forward__',$_SERVER['REQUEST_URI']);
        return $this->fetch();
    }

    /**
     * 插件后台显示页面
     * @param string $name 插件名
     */
    public function adminList($name){
        // 记录当前列表页的cookie
        cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('name', $name);
        $class = get_addon_class($name);
        if(!class_exists($class))
            $this->error('插件不存在');
        $addon = new $class();
        $this->assign('addon', $addon);
        $param = $addon->admin_list;
        if(!$param)
            $this->error('插件列表信息不正确');
        $this->meta_title = $addon->info['title'];
        extract($param);
        $this->assign('title', $addon->info['title']);
        $this->assign($param);
        if(!isset($fields))
            $fields = '*';
        if(!isset($search_key))
            $key = 'title';
        else
            $key = $search_key;
        if(isset($_REQUEST[$key])){
            $map[$key] = array('like', '%'.$_GET[$key].'%');
            unset($_REQUEST[$key]);
        }



        if(isset($model)){
            $model  =   model("Addons://{$name}/{$model}");
            // 条件搜索
            $map    =   array();
            foreach($_REQUEST as $name=>$val){
                if($fields == '*'){
                    $fields = $model->getDbFields();
                }
                if(in_array($name, $fields)){
                    $map[$name] = $val;
                }
            }
            if(!isset($order))  $order = '';
            $list = $this->lists($model->field($fields),$map,$order);
            $fields = array();
            foreach ($list_grid as &$value) {
                // 字段:标题:链接
                $val = explode(':', $value);
                // 支持多个字段显示
                $field = explode(',', $val[0]);
                $value = array('field' => $field, 'title' => $val[1]);
                if(isset($val[2])){
                    // 链接信息
                    $value['href'] = $val[2];
                    // 搜索链接信息中的字段信息
                    preg_replace_callback('/\[([a-z_]+)\]/', function($match) use(&$fields){$fields[]=$match[1];}, $value['href']);
                }
                if(strpos($val[1],'|')){
                    // 显示格式定义
                    list($value['title'],$value['format']) = explode('|',$val[1]);
                }
                foreach($field as $val){
                    $array = explode('|',$val);
                    $fields[] = $array[0];
                }
            }
            $this->assign('model', $model->model);
            $this->assign('list_grid', $list_grid);
        }
        $this->assign('_list', $list);
        if($addon->custom_adminlist)
            $this->assign('custom_adminlist', $this->fetch($addon->addon_path.$addon->custom_adminlist));
        return $this->fetch('adminlist');
    }

    /**
     * 启用插件
     */
    public function enable(){
        $id     =   input('id');
        $msg    =   array('success'=>'启用成功', 'error'=>'启用失败');
        cache('hooks', null);
        $this->resume('Addons', "id={$id}", $msg);
    }

    /**
     * 禁用插件
     */
    public function disable(){
        $id     =   input('id');
        $msg    =   array('success'=>'禁用成功', 'error'=>'禁用失败');
        cache('hooks', null);
        $this->forbid('Addons', "id={$id}", $msg);
    }

    /**
     * 设置插件页面
     */
    public function config(){
        $addon  =   db('Addons')->find($this->request->get('id'));
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
                            if (!empty($db_config[$gkey])) {
                                $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                            }else {
                                $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = '';
                            }
                        }
                    }
                }
            }
        }
        $this->assign('data',$addon);
        if($addon['custom_config'])
            $this->assign('custom_config', $this->fetch($addon['addon_path'].$addon['custom_config']));
        return $this->fetch();
    }

    /**
     * 保存插件设置
     */
    public function saveConfig(){
        $id     =   (int)input('id');
        $flag = db('Addons')->where("id={$id}")->setField('config',json_encode($this->request->post('config/a')));
        if($flag !== false){
            return json(['status'=>1,'info'=>'保存成功','url'=>cookie('__forward__')]);
//             $this->success('保存成功', cookie('__forward__'));
        }else{
            return json(['status'=>0,'info'=>'保存失败']);
//             $this->error('保存失败');
        }
    }

    /**
     * 解析数据库语句函数
     * @param string $sql  sql语句   带默认前缀的
     * @param string $tablepre  自己的前缀
     * @return multitype:string 返回最终需要的sql语句
     */
    public function sql_split($sql, $tablepre) {
        if ($tablepre != "onethink_")
            $sql = str_replace("onethink_", $tablepre, $sql);
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);

        if ($r_tablepre != $s_tablepre)
            $sql = str_replace($s_tablepre, $r_tablepre, $sql);
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-')
                    $ret[$num] .= $query;
            }
            $num++;
        }
        return $ret;
    }

    /**
     * 获取插件所需的钩子是否存在，没有则新增
     * @param string $str  钩子名称
     * @param string $addons  插件名称
     * @param string $addons  插件简介
     */
    public function existHook($str, $addons, $msg=''){
        $hook_mod = db('Hooks');
        $where['name'] = $str;
        $gethook = $hook_mod->where($where)->find();
        if(!$gethook || empty($gethook) || !is_array($gethook)){
            $data['name'] = $str;
            $data['description'] = $msg;
            $data['type'] = 1;
            $data['update_time'] = NOW_TIME;
            $data['addons'] = $addons;
            if( false !== $hook_mod->create($data) ){
                $hook_mod->add();
            }
        }
    }

    /**
     * 删除钩子
     * @param string $hook  钩子名称
     */
    public function deleteHook($hook){
        $model = db('hooks');
        $condition = array(
            'name' => $hook,
        );
        $model->where($condition)->delete();
        cache('hooks', null);
    }
    /**
     * 安装插件
     */
    public function install(){
        $addon_name     =   trim(input('addon_name'));
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
        $addonsModel    =   model('Addons');
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
            $hooks_update   =   model('Hooks')->updateHooks($addon_name);
            if($hooks_update){
                cache('hooks', null);
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
        $addonsModel    =   db('Addons');
        $id             =   trim(input('id'));
        $db_addons      =   $addonsModel->find($id);
        $class          =   get_addon_class($db_addons['name']);
        $this->assign('jumpUrl',url('index'));
        if(!$db_addons || !class_exists($class))
            $this->error('插件不存在');
        session('addons_uninstall_error',null);
        $addons =   new $class;
        $uninstall_flag =   $addons->uninstall();
        if(!$uninstall_flag)
            $this->error('执行插件预卸载操作失败'.session('addons_uninstall_error'));
        $hooks_update   =   model('Hooks')->removeHooks($db_addons['name']);
        if($hooks_update === false){
            $this->error('卸载插件所挂载的钩子数据失败');
        }
        cache('hooks', null);
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
//         $list   =   $this->lists(model("Hooks")->field($fields),$map);
        $list   =   db('hooks')->where($map)->paginate();
//         int_to_string($list, array('type'=>config('HOOKS_TYPE')));
        // 记录当前列表页的cookie
        cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('list', $list );
        return $this->fetch();
    }

    public function addhook(){
        $this->assign('data', null);
        $this->meta_title = '新增钩子';
        return $this->fetch('edithook');
    }

    //钩子出编辑挂载插件页面
    public function edithook($id){
        $hook = db('Hooks')->field(true)->find($id);
        $this->assign('data',$hook);
        $this->meta_title = '编辑钩子';
        return $this->fetch('edithook');
    }

    //超级管理员删除钩子
    public function delhook($id){
        if(db('Hooks')->delete($id) !== false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

    public function updateHook(){
        $hookModel  =   model('Hooks');
        $data       =   $hookModel->create();
        if($data){
            if($data['id']){
                $flag = $hookModel->save($data);
                if($flag !== false){
                    cache('hooks', null);
                    $this->success('更新成功', cookie('__forward__'));
                }else{
                    $this->error('更新失败');
                }
            }else{
                $flag = $hookModel->add($data);
                if($flag){
                    cache('hooks', null);
                    $this->success('新增成功', cookie('__forward__'));
                }else{
                    $this->error('新增失败');
                }
            }
        }else{
            $this->error($hookModel->getError());
        }
    }

    public function execute($_addons = null, $_controller = null, $_action = null){
        if(config('URL_CASE_INSENSITIVE')){
            $_addons        =   ucfirst(parse_name($_addons, 1));
            $_controller    =   parse_name($_controller,1);
        }

        $TMPL_PARSE_STRING = config('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__ADDONROOT__'] = __ROOT__ . "/Addons/{$_addons}";
        config('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);

        if(!empty($_addons) && !empty($_controller) && !empty($_action)){
            $Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
        } else {
            $this->error('没有指定插件名称，控制器或操作！');
        }
    }

    public function edit($name, $id = 0){
        $this->assign('name', $name);
        $class = get_addon_class($name);
        if(!class_exists($class))
            $this->error('插件不存在');
        $addon = new $class();
        $this->assign('addon', $addon);
        $param = $addon->admin_list;
        if(!$param)
            $this->error('插件列表信息不正确');
        extract($param);
        $this->assign('title', $addon->info['title']);
        if(isset($model)){
            $addonModel = model("Addons://{$name}/{$model}");
            if(!$addonModel)
                $this->error('模型无法实列化');
            $model = $addonModel->model;
            $this->assign('model', $model);
        }
        if($id){
            $data = $addonModel->find($id);
            $data || $this->error('数据不存在！');
            $this->assign('data', $data);
        }

        if(IS_POST){
            // 获取模型的字段信息
            if(!$addonModel->create())
                $this->error($addonModel->getError());

            if($id){
                $flag = $addonModel->save();
                if($flag !== false)
                    $this->success("编辑{$model['title']}成功！", cookie('__forward__'));
                else
                    $this->error($addonModel->getError());
            }else{
                $flag = $addonModel->add();
                if($flag)
                    $this->success("添加{$model['title']}成功！", cookie('__forward__'));
            }
            $this->error($addonModel->getError());
        } else {
            $fields = $addonModel->_fields;
            $this->assign('fields', $fields);
            $this->meta_title = $id? '编辑'.$model['title']:'新增'.$model['title'];
            if($id)
                $template = $model['template_edit']? $model['template_edit']: '';
            else
                $template = $model['template_add']? $model['template_add']: '';
            if ($template)
                return $this->fetch($addon->addon_path . $template);
            else
                return $this->fetch();
        }
    }

    public function del($id = '', $name){
        $ids = array_unique((array)input('ids',0));

        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }

        $class = get_addon_class($name);
        if(!class_exists($class))
            $this->error('插件不存在');
        $addon = new $class();
        $param = $addon->admin_list;
        if(!$param)
            $this->error('插件列表信息不正确');
        extract($param);
        if(isset($model)){
            $addonModel = model("Addons://{$name}/{$model}");
            if(!$addonModel)
                $this->error('模型无法实列化');
        }

        $map = array('id' => array('in', $ids) );
        if($addonModel->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

}
