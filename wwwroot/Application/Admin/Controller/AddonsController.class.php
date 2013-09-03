<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
/**
 * 扩展后台管理页面
 * @author yangweijie <yangweijiester@gmail.com>
 */

class AddonsController extends AdminController {

    static protected $nodes = array(
        array(
            'title'=>'插件管理', 'url'=>'Addons/index', 'group'=>'扩展',
            'operator'=>array(
                //权限管理页面的五种按钮
                array('title'=>'创建','url'=>'Addons/create'),
                array('title'=>'检测创建','url'=>'Addons/checkForm'),
                array('title'=>'预览','url'=>'Addons/preview'),
                array('title'=>'快速生成插件','url'=>'Addons/build'),
                array('title'=>'设置','url'=>'Addons/config'),
                array('title'=>'禁用','url'=>'Addons/disable'),
                array('title'=>'启用','url'=>'Addons/enable'),
                array('title'=>'安装','url'=>'Addons/install'),
                array('title'=>'卸载','url'=>'Addons/uninstall'),
                array('title'=>'更新配置','url'=>'Addons/saveconfig'),
                array('title'=>'插件后台列表','url'=>'Addons/adminList')
            ),
        ),
        array( 'title'=>'钩子管理', 'url'=>'Addons/hooks', 'group'=>'扩展',
            'operator'=>array(
            //权限管理页面的五种按钮
                array('title'=>'编辑','url'=>'Addons/updateSort'),
            ),
        ),
    );

    public function _initialize(){
        $this->assign('_extra_menu',array(
            '已装插件后台'=>D('Addons')->getAdminList(),
        ));
        parent::_initialize();
    }

    //创建向导首页
    public function create(){
        $hooks = D('Hooks')->field('name,description')->select();
        $this->assign('Hooks',$hooks);
        $this->display('create');
    }

    //预览
    public function preview($output = true){
        $data = $_POST;
        $data['info']['status'] = (int)$data['info']['status'];
        // exit(var_export($data,1));
        $extend = array();
        $custom_config = trim($data['custom_config']);
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
        $tpl = <<<str
<?php
/**
 * {$data['info']['title']}插件
 * @author {$data['info']['author']}
 */

    class {$data['info']['name']}Addons extends Addons{

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

        //实现的{$data['hook']}钩子方法
        public function {$data['hook']}(\$param){

        }
    }
str;
        if($output)
            exit($tpl);
        else
            return $tpl;
    }

    public function checkForm(){
        $data = $_POST;
        //检测插件名是否合法
        $addons_dir = C('EXTEND_MODULE.Addons');
        if(file_exists("{$addons_dir}{$data['info']['name']}")){
            $this->error('插件已经存在了');
        }
        //检测配置和插件主文件是否合法 TODO: 无法实现正确的检测php代码片段机制
        // if($data['has_config']){
        //     if(!@eval(ltrim($data['config'], '<?php'))){
        //         $this->error('配置有语法错误');
        //     }
        // }
        // $preview = $this->preview(false);
        // $check_preview = ltrim($preview, '<?php');
        // $addon_class = realpath(APP_PATH.'Common/Controller/Addons.class.php');
        // if(!class_exists('Addons'))
        //     $check_preview = "include '{$addon_class}';".$check_preview;
        // if(!@eval($check_preview)){
        //     $this->error('插件定义类有语法错误','', array('error'=>$check_preview));
        // }
        $this->success('可以创建');
    }

    public function build(){
        $addonFile = $this->preview(false);
        $data = $_POST;
        $addons_dir = C('EXTEND_MODULE.Addons');
        //创建目录结构
        $files = array();
        $addon_dir = "$addons_dir{$data['info']['name']}/";
        $files[] = $addon_dir;
        $addon_name = "{$data['info']['name']}Addons.class.php";
        $files[] = "{$addon_dir}{$addon_name}";
        if($data['has_config'] == 1);//如果有配置文件
            $files[] = $addon_dir.'config.php';

        if($data['has_outurl']){
            $files[] = "{$addon_dir}Controller/";
            $files[] = "{$addon_dir}Controller/{$data['info']['name']}Controller.class.php";
            $files[] = "{$addon_dir}Model/";
            $files[] = "{$addon_dir}Model/{$data['info']['name']}Model.class.php";
        }
        $custom_config = trim($data['custom_config']);
        if($custom_config)
            $data[] = "{$addon_dir}{$custom_config}";

        $custom_adminlist = trim($data['custom_adminlist']);
        if($custom_adminlist)
            $data[] = "{$addon_dir}{$custom_adminlist}";

        createDirOrFiles($files);

        //写文件
        file_put_contents("{$addon_dir}{$addon_name}", $addonFile);
        if($data['has_config'] == 1)
            file_put_contents("{$addon_dir}config.php", $data['config']);

        $this->success('创建成功');
    }

    /**
     * 插件列表
     */
    public function index(){
        $this->assign('list',D('Addons')->getList());
        $this->assign('creatable', is_writable(C('EXTEND_MODULE.Addons')));
        $this->display();
    }

    /**
     * 插件后台显示页面
     * @param string $name 插件名
     */
    public function adminList($name){
        $addon = addons($name);
        if(!$addon)
            $this->error('插件不存在');
        $param = $addon->admin_list;
        if(!$param)
            $this->error('插件列表信息不正确');
        extract($param);
        $this->assign('title', $addon->info['title']);
        if($addon->custom_adminlist)
            $this->assign('custom_adminlist', $addon->addon_path.$addon->custom_adminlist);
        $this->assign($param);
        $list = $this->lists(D("Addons://{$model}/{$model}")->field($fields),$map,$order);
        $thead = array(
            //元素value中的变量就是数据集中的字段,value必须使用单引号

            //所有 _ 下划线开头的元素用于使用html代码生成th和td
            '_html'=>array(
                'th'=>'<input class="check-all" type="checkbox"/>',
                'td'=>'<input class="ids" type="checkbox" name="id[]" value="$id" />',
            ),
            //查询出的数据集中的字段=>字段的表头
        );
        if($listKey)
            $thead = array_merge($thead, $listKey);
        $this->assign('_table_class', 'data-table table-striped');
        $this->assign( '_table_list', $this->tableList($list,$thead) );
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
        if($addon['custom_config'])
            $this->assign('custom_config', $addon['addon_path'].$addon['custom_config']);
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
        S('hooks', null);//:TODO S方法更新缓存 前后台不一致，有BUG
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
