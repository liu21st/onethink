<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// 应用引擎模块
class ThinkController extends CommonController {
    protected $think    =   null;

    public function _initialize(){
        parent::_initialize();
        // 检查模型权限
        $this->checkModule($_REQUEST['_module']);
        // 检查分类权限
        $this->checkCate($_REQUEST['cate_id']);
        // 实例化应用引擎
        $this->think    =   D('Think');
    }

    // 模型权限检测
    protected function checkModule($module) {
        if(!empty($module)) {
            if(!ThinkAcl::checkRoleAcl($module,2)) {
                // 提示错误信息
                $this->error(L('_VALID_ACCESS_'));
            }
        }
    }

    // 分类权限检测
    protected function checkCate($cate) {
        if(!empty($cate)) {
            // 查看是否存在映射分类
            $map_id =   M('Cate')->getFieldById($cate,'map_id');
            if($map_id) {
                $cate   =   $map_id;
                $_REQUEST['cate_id']    =   $cate;
            }
            if(!ThinkAcl::checkRoleAcl($cate,3)) {
                // 提示错误信息
                $this->error(L('_VALID_ACCESS_'));
            }
        }
    }

    // 列表过滤
    protected function _filter(&$map) {
        if(!isset($map['status'])) {
            // 默认不显示已经删除的文档
            $map['status'] = array('egt',0);
        }
        if(!empty($map['_module'])) {
            // 搜模块的时候 不限制分类
            unset($map['cate_id']);
        }
    }

    // 获取当前的模型
    protected function getModuleName() {
        if(!empty($_REQUEST['_module'])) {
            return $_REQUEST['_module'];
        }elseif(!empty($_REQUEST['cate_id'])) {
            return getModuleByCate($_REQUEST['cate_id']);
        }
        return '';
    }

    // 可以发起ajax请求 对模型的某些字段做更改
    public function setField($id=0,$field='',$value='',$success='',$error=''){
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }
        // 对删除文档设置标记位
        if(empty($id) || empty($field)) {
            $this->error(L('非法操作'));
        }
        $result =   $this->think->setValue($module,$id,$field,$value);
        if($result) {
            $this->success($success?$success:'操作成功！');
        }else{
            $this->error($error?$error:'操作失败！');
        }
    }

    // 列表页
    public function index(){
        // 获取模型名称
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }else{
            C('COOKIE_PREFIX',$module.':');
        }
        // 获取模型数据
        $model   = M('Model')->getByName($module);
        if(!$model) {
            $this->error('未定义的模型');
        }
        // 实例化模型
        $Model        = model($module);
        if($model['support_sort']) {// 支持排序
            $order  =   'sort';
            $asc    =   true;
        }elseif($model['sort_key']){
            $order  =   $model['sort_key'];
            $asc    =   false;
        }elseif($model['support_status']){
            $order  =   'status';
            $asc    =   false;
        }else{
            $order  =   'id';
            $asc    =   false;
        }
        // 设置模糊查询字段
        C('DB_LIKE_FIELDS',$model['search_key']);
        // 获取模型的属性列表（新增显示）
        $attrList   =  $this->think->getModelAttrInfo($module,0,$model['search_list']);
        // 去掉隐藏字段
        foreach ($attrList as $key=>$val){
            if($val['type']=='hidden') {
                unset($attrList[$key]);
            }
        }
        $this->attrList     =   $attrList;
        // 获取当前模型的外键关联字段
        $this->foreign_key  =   $model['foreign_key']?$model['foreign_key']:'record_id';
        // 输出模型变量到模板
        $this->model        =   $model;
        // 自定义列表字段
        $listGrid           =   !empty($model['list_grid'])?$model['list_grid']:C('DEFAULT_GRID_LIST');
        $statusList         =   !empty($model['status_list'])?$model['status_list']:C('DEFAULT_STATUS_LIST');
        $this->list_grid    =   $listGrid;
        // 自定义操作
        if(1 != $model['model_type']){
            $this->action_list  =   !empty($model['action_list'])?$model['action_list']:C('DEFAULT_ACTION_LIST');
        }else{
            $this->action_list  =   '';
        }
        $this->search_key       =   !empty($model['search_key'])?$model['search_key']:'title';
        $this->template_search  =   !empty($model['template_search'])?$model['template_search']:'Think:search';

        // 获取列表要查询的字段信息
        $grid       =   explode(',',$listGrid);
        $field      =   array();
        if($model['support_status']){
            $field[]    =   'status';
        }
        
        foreach ($grid as $key=>$val){
            $array  =   explode(':',$val);
            $array  =   explode('|',$array[0]);
            $field[]=   $array[0];
        }
        // 列表过滤器，生成查询Map对象
        if(1 != $model['model_type']) {
            $map    =   $this->_search($module);
            $this->_filter($map);
        }else{
            $map    =   $this->_search('',$field);
        }
        //$map['_module'] =   $module;
        if($model['support_level']) {// 使用层级显示列表
            if(empty($map['pid'])) {
                $map['pid'] =   0;
            }else{
                $vo             =   $Model->find($map['pid']);
                $this->returnId =   $vo['pid'];
                array_shift($vo);
                $this->_title   =   array_shift($vo);
            }
            session('pid',$map['pid']);
        }
        // 获取列表数据
        if(1 != $model['model_type']) {// 普通模型
            $list   =   $this->_list($Model,$map,$order,$asc,$field);
            // 对列表数据进行显示处理
            foreach ($list as $key=>$val){
                foreach ($val as $k=>$data){
                    if(in_array($k,$field)) {
                        $options    =   null;
                        $extra      =   $attrList[$k]['extra'];
                        $type       =   $attrList[$k]['type'];
                        if('select'== $type) { // 枚举型
                            if(0===strpos($extra,'@')) {
                                $options    =   parseT(substr($extra,1),true);
                            }elseif(0===strpos($extra,':')){
                                $fun        =   substr($extra,1);
                                $options    =   $fun();
                            }else{
                                $options    =   express_to_array($extra);
                            }
                        }elseif('bool'==$type){// 布尔型
                            $options    =   string_to_array($extra);
                        }elseif('date'==$type){ // 日期型
                            $val[$k]    =   todate($data,'Y-m-d');
                        }elseif(in_array($k,explode(',',C('TIMESTAMP_FIELDS')))){
                            $val[$k]    =   todate($data,'y-m-d H:i:s');
                        }elseif('status'==$k){
                            $options    =   express_to_array($statusList);
                        }
                        if($options && array_key_exists($data,$options)) {
                            $val[$k]    =   $options[$data];
                        }
                    }
                }
                $list[$key]   =   $val;
            }
        }else{ // 视图模型
            $field1 =   array();
            foreach ($attrList as $key=>$val){
                if($val['type']=='hidden') {
                    unset($attrList[$key]);
                }else{
                    $field1[$val['extra']]   =   $val['name'];
                }
            }
            $join   =   explode(',',$model['relation_list']);
            $count  =   $Model->alias($model['name'])->where($map)->field($field1)->join($join)->count();
            import("ORG.Util.Page");
            //创建分页对象
            if(!empty($_REQUEST['listRows'])) {
                $listRows  =  $_REQUEST['listRows'];
            }else {
                $listRows  =  '';
            }
            $p          =   new Page($count,$listRows);
            $list       =   $Model->alias($model['name'])->field($field1)->where($map)->join($join)->limit($p->firstRow.','.$p->listRows)->select();
            $page       =   $p->show();
            $this->count=   $count;
            $this->page =   $page;
        }
        $this->list     =   $list;
        // 记录当前列表页的cookie
        Cookie('__forward1__',$_SERVER['REQUEST_URI']);
        C('TMPL_CACHE_PREFIX',$module.'/');
        $this->display($model['template_list']?$model['template_list']:'');
        return;
    }

     // 根据表单生成查询条件和过滤
    protected function _search($name='',$fields=array()) {
		$map	=	array();
        if(empty($fields)) { 
            //列表过滤器，生成查询Map对象
    		$model	=	Model($name?$name:$this->getModuleName());
            $fields =   $model->getDbFields();
        }
        if($fields) {
            foreach($fields as $key=>$val) {
                if(substr($key,0,1)=='_') continue;
                if(isset($_REQUEST[$val]) && $_REQUEST[$val]!='') {
                    $value  =   $_REQUEST[$val];
                    if(is_array($value)) {
                        if(preg_match('/^(EQ|NEQ|GT|EGT|LT|ELT|LIKE|LIKE|IN|EXP)$/i',$value[0])) {
                            $map[$val]  =   $value;
                        }elseif(!empty($value[0])){
                            $map[$val]  =   array('between',$value);
                        }
                    }else{
                        $map[$val]	=	$value;
                    }
                }
            }
        }
        return $map;
    }

    // 列表数据
    protected function _list($model,$map=array(),$sortBy='',$asc=false,$field='') {
        //排序字段 默认为主键名
        if(isset($_REQUEST['_order'])) {
            $order = $_REQUEST['_order'];
        }else {
            $order = !empty($sortBy)? $sortBy: 'status';
        }
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        if(isset($_REQUEST['_sort'])) {
            $sort = $_REQUEST['_sort']?'asc':'desc';
        }else {
            $sort = $asc?'asc':'desc';
        }
        //取得满足条件的记录数
        $count      = $model->where($map)->count('id');
        import("ORG.Util.Page");
        //创建分页对象
        if(!empty($_REQUEST['listRows'])) {
            $listRows  =  $_REQUEST['listRows'];
        }else {
            $listRows  =  '';
        }
        $p          = new Page($count,$listRows);
        //分页查询数据
        $voList     = $model->field($field)->where($map)->order($order.' '.$sort.',id desc')->limit($p->firstRow.','.$p->listRows)->select();

        //分页跳转的时候保证查询条件
        foreach($map as $key=>$val) {
            if(!is_array($val)) {
                $p->parameter   .=   "$key=".urlencode($val)."&";
            }
        }
        //分页显示
        $page           =   $p->show();
        //列表排序显示
        $sortImg        =   $sort ;                                   //排序图标
        $sortAlt        =   $sort == 'desc'?'升序排列':'倒序排列';    //排序提示
        $sort           =   $sort == 'desc'? 1:0;                     //排序方式
        //模板赋值显示
        $this->count    =   $count;
        $this->sort     =   $sort;
        $this->order    =   $order;
        $this->sortImg  =   $sortImg;
        $this->sortType =   $sortAlt;
        $this->page     =   $page;
        return $voList;
    }

    // 新增文档
    public function add() {
        if(isset($_GET['preview'])) { // 预览模式
            $this->preview = true;
        }
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }else{
            C('COOKIE_PREFIX',$module.':');
        }
        $model   = M('Model')->getByName($module);
        if(!$model) {
            $this->error('未定义的模型');
        }
        // 实例化模型
        $Model  =  $this->think;
        // 获取模型的属性列表（新增显示）
        $attrList   =  $Model->getModelAttrInfo($module,1);
        if($model['support_flow']) {// 使用工作流
            // 获取当前模型的工作流定义
            // 获取当前模型的流程列表
            $flow_list = M('Flow')->where('model_id='.$model['id'])->order('sort')->getField('id',2);
            // 获取当前流程ID
            $this->curFlowId  =  $flow_list[0];
            // 检测用户权限
            if(!$this->preview && !checkUserFlow($this->curFlowId)) {
                //$this->error('没有操作权限！');
            }
            // 获取下一流程ID
            $this->nextFlowId =  $flow_list[1];
            // 获取当前流程的表单属性
            $flow =  M('Flow')->find($this->curFlowId);
            //$attribute_list  =  $flow['attribute_list'];
            //$map['id']   = array('IN',$attribute_list);
            //$attrList =  M("Attribute")->where($map)->order('sort')->select();
            $linkId =   explode(',',$flow['attribute_list']);
            $keys   =   array_keys($attrList);
            $keys   =   array_diff($keys,$linkId);
            foreach ($keys as $key){
                $attrList[$key]['readonly']    =   'readonly';
            }
        }else{
            if(!empty($model['belongs_to'])) { // 存在上级模型
                // 合并属性列表
                list($parentModel,)   =  explode(':',$model['belongs_to']);
                $parentAttrList = $Model->getModelAttrInfo($parentModel,1);
                $attrList   =  array_merge($parentAttrList,$attrList);
            }
            if(!empty($model['has_one'])) { // 存在上级模型
                // 合并属性列表
                list($subModel,)   =  explode(':',$model['has_one']);
                $subAttrList = $Model->getModelAttrInfo($subModel,1);
                $attrList   =  array_merge($attrList,$subAttrList);
            }
        }
        // 提取隐藏字段
        $hidden_list  = array();
        foreach ($attrList as $key=>$val){
            if($val['type']=='hidden') {
                $hidden_list[]   =  $val;
                unset($attrList[$key]);
            }
        }
        if(!empty($hidden_list)) {
            $this->hidden_list =  $hidden_list;
        }

        $this->attr_list =  $attrList;
        $this->foreign_key  =   $model['foreign_key']?$model['foreign_key']:'record_id';
        // 获取模型的特殊属性
        if($model['support_attach']) {
            // 生成附件的随机验证码
            $_SESSION['attach_verify'] = time();
            $this->verify   =  $_SESSION['attach_verify'];
        }
        if(!empty($model['support_level'])) {
            // 获取父节点
            $pid    =   session('pid');
            if(!empty($pid)) {
                $vo  =   $Model->get($module,$pid);
                $level  =   $vo['level']+1;
            }else{
                $level  =   1;
            }
            $this->pid  =   $pid;
            $this->level    =   $level;
        }
        $this->model   = $model;
        // 读取模型对应的列表模板
        $template   = $model['template_add'];
        $this->display($template?$template:'');
    }
    
    // 写入
    public function insert() {
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }else{
            C('COOKIE_PREFIX',$module.':');
        }
        $model   = M('Model')->getByName($module);
        if(!$model) {
            $this->error('未定义的模型');
        }
        // 实例化模型
        $Model	=	$this->think;
        if(!empty($model['belongs_to'])) { // 存在上级模型
            list($parentModule,$parentId)   =  explode(':',$model['belongs_to']);
            if(strpos($parentModule,'}')) { // 动态绑定
                $parentModule    =   $_POST[substr($parentModule,1,-1)];
            }
            $_POST['_module'] = $parentModule;
            // 创建父模型对象
            $parentData = $Model->create();
            if(!$parentData) { 
                $this->error($Model->getError());
            }else{
                C('TOKEN_ON',false); // 关闭令牌
                $_POST['_module'] = $module;
            }
        }
        if(!empty($model['has_one'])) { // 存在下级模型
            list($subModel,$subId)   =  explode(':',$model['has_one']);
            if(strpos($subModel,'}')) { // 动态绑定
                $subModel    =   $_POST[substr($subModel,1,-1)];
            }
            $_POST['_module'] = $subModule;
            // 创建父模型对象
            $subData = $Model->create();
            if(!$subData) { 
                $this->error($Model->getError());
            }else{
                C('TOKEN_ON',false); // 关闭令牌
                $_POST['_module'] = $module;
            }
        }
         // 创建数据对象
        $data = $Model->create();
        if(!$data) {
            $this->error($Model->getError());
        }elseif(isset($parentData)){
            // 写入上级模型数据
            $data[$parentId]  =  $Model->add($parentData);
        }
        //保存当前数据对象
        if($result = $Model->add($data)) { // 写入成功
            // 写入下级模型
            if(isset($subData)) {
                $subData[$subId]    =   $result;
                $result =   $Model->add($subData);
                if(!$result) {
                    $this->error('子模型写入失败！');
                }
            }
            //成功提示
            $this->success(L('新增成功'),Cookie('__forward1__'));
        }else {
            //失败提示
            $this->error(L('新增失败'));
        }
    }

    public function read($id=0){
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }
        $model   = M('Model')->getByName($module);
        if(!$model) {
            $this->error('未定义的模型');
        }
        // 实例化文档模型
        $Article        = $this->think;
        $vo	=	$Article->get($module,$id);
        if(!$vo) {
            $this->error('文档不存在');
        }
        // 记录当前编辑的数据
        $vo['_module']  =  $module;
        $this->module =  $module;
        // 获取文档对应的属性列表
        $attr_list   =  $this->think->getModelAttrInfo($module,0);
        if(!empty($model['belongs_to'])) { // 存在上级模型
            list($parentModel,$parentId)   =  explode(':',$model['belongs_to']);
            $parentAttrList = $Article->getModelAttrInfo($parentModel,0);
            $attr_list   =  array_merge($parentAttrList,$attr_list);
            // 获取父模型数据并合并
            $parentVo  =  $Article->get($parentModel,$vo[$parentId]);
            $vo   =  array_merge($parentVo,$vo);
        }
        foreach ($attr_list as $key=>$val){
            // 属性赋值
            if(isset($vo[$val['name']])) {
                $val['value'] = $vo[$val['name']];
                $attr_list[$key] = $val;
            }
            // 处理复合类型
            if(isset($val['complex'])) { 
                // 组合字段中的各个字段赋值
                foreach ($val['complex'] as $k=>$v){
                    if(isset($vo[$v['name']])) {
                        $v['value'] = $vo[$v['name']];
                        $val['complex'][$k] = $v;
                    }
                }
                $attr_list[$key] = $val;
            }
        }
        // 模型的特殊属性
        if($model['support_attach']) {
            // 开启附件功能
            // 获取附件列表
            $this->article_attach  =  1;
            $map['module']   =  $module;
            $map['record_id'] =  $vo['id'];
            $this->attachs =  M('Attach')->where($map)->select();
        }
        $this->vo   =  $vo;  // 当前文档数据
        $this->model   = $model;  // 当前模型数据
        $this->attr_list  = $attr_list; // 当前属性列表
        $this->display();
    }

    // 编辑
    public function edit($id=0) {
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }else{
            C('COOKIE_PREFIX',$module.':');
        }
        $model   = M('Model')->getByName($module);
        if(!$model) {
            $this->error('未定义的模型');
        }
        // 实例化文档模型
        $Article        = $this->think;
        $vo	=	$Article->get($module,$id);
        if(!$vo) {
            $this->error('文档不存在');
        }
        // 记录当前编辑的数据
        $_SESSION['__data__']   =  $vo;
        $vo['_module']  =  $module;
        $this->module =  $module;
        // 获取文档对应的属性列表
        $attr_list   =  $Article->getModelAttrInfo($module,2);
        if($model['support_flow']) {// 使用工作流
            // 获取当前模型的流程列表
            $flow_list = M('Flow')->where('model_id='.$model['id'])->order('sort')->getField('id',true);
            $flow_id    =   $vo['flow_id']?$vo['flow_id']:$flow_list[0];
            // 检测用户权限
            if(!$this->preview && !checkUserFlow($flow_id)) {
                //$this->error('没有操作权限！');
            }
            // 获取下一流程ID
            $key =  array_search($flow_id,$flow_list);
            $this->nextFlowId =  $flow_list[$key+1];
            // 获取当前流程的表单属性
            $flow =  M('Flow')->find($flow_id);
            //$attribute_list  =  $flow['attribute_list'];
            //$map['id']   = array('IN',$attribute_list);
            //$attr_list =  M("Attribute")->where($map)->order('sort')->select();
            $linkId =   explode(',',$flow['attribute_list']);
            $keys   =   array_keys($attr_list);
            $keys   =   array_diff($keys,$linkId);
            foreach ($keys as $key){
                $attr_list[$key]['readonly']    =   'readonly';
            }
        }else{
            if(!empty($model['belongs_to'])) { // 存在上级模型
                list($parentModel,$parentId)   =  explode(':',$model['belongs_to']);
                if(strpos($parentModel,'}')) { // 动态绑定模型
                    $parentModel    =   $vo[substr($parentModel,1,-1)];
                }
                $parentAttrList = $Article->getModelAttrInfo($parentModel,2);
                $attr_list   =  array_merge($parentAttrList,$attr_list);
                // 获取父模型数据并合并
                $parentVo  =  $Article->get($parentModel,$vo[$parentId]);
                if($parsentVo) {
                    $vo   =  array_merge($parentVo,$vo);
                }
            }
            if(!empty($model['has_one'])) { // 存在下级模型
                list($subModel,$subId)   =  explode(':',$model['has_one']);
                if(strpos($subModel,'}')) { // 动态绑定
                    $subModel    =   $vo[substr($subModel,1,-1)];
                }
                $subAttrList = $Article->getModelAttrInfo($subModel,2);
                $attr_list   =  array_merge($attr_list,$subAttrList);
                // 获取父模型数据并合并
                $subVo  =  model($subModel)->where($subId.'='.$vo['id'])->find();
                if($subVo) {
                    $vo   =  array_merge($subVo,$vo);
                }

            }
        }
        if($model['support_link'] && $vo['link_id']) { // 存在映射
            // 对非映射字段 设置为不可用
            $linkId =   explode(',',$model['link_list']);
            $keys   =   array_keys($attr_list);
            $keys   =   array_diff($keys,$linkId);
            foreach ($keys as $key){
                //unset($attr_list[$key]);
                $attr_list[$key]['readonly']    =   'readonly';
            }
        }
        $hidden_list  = array();
        foreach ($attr_list as $key=>$val){
            // 属性赋值
            if(isset($vo[$val['name']])) {
                $val['value'] = $vo[$val['name']];
                $attr_list[$key] = $val;
            }
            // 提取隐藏字段
            if($val['type']=='hidden') {
                $hidden_list[]   =  $val;
                unset($attr_list[$key]);
            }
            // 处理复合类型
            if(isset($val['complex'])) { 
                // 组合字段中的各个字段赋值
                foreach ($val['complex'] as $k=>$v){
                    if(isset($vo[$v['name']])) {
                        $v['value'] = $vo[$v['name']];
                        $val['complex'][$k] = $v;
                    }
                }
                $attr_list[$key] = $val;
            }
        }
        if(!empty($hidden_list)) { // 提取隐藏字段
            $this->hidden_list =  $hidden_list;
        }
        $moduleList =   $model['module_list']?explode(',',$model['module_list']):array();
        if($vo['cate_id']) {// 指定分类
            $module_list    =   M('cate')->getFieldById($vo['cate_id'],'module_list');
            if($module_list) {
                $moduleList   =   $moduleList+explode(',',$module_list);
            }
        }
        $this->foreign_key  =   $model['foreign_key']?$model['foreign_key']:'record_id';
        if(!empty($moduleList)) { // 子模型
            //$moduleList = explode(',',$model['module_list']);
            if(empty($_SESSION['administrator'])) {
                $list = ThinkAcl::getRoleAccessList(2,'','id,name');
                $moduleList =   array_intersect($moduleList,$list);
            }
            $where['name'] = array('IN',$moduleList);
            $models = M('Model')->where($where)->field('name,title,list_grid,foreign_key,status_list')->select();
            foreach ($models as $_k=>$m){
                $foreign_key  =   $m['foreign_key']?$m['foreign_key']:'record_id';
                $attrList   =  $Article->getModelAttrInfo($m['name'],0);
                // 对列表数据处理
                $listGrid   =   $m['list_grid']?$m['list_grid']:C('DEFAULT_GRID_LIST');
                $statusList =   $m['status_list']?$m['status_list']:C('DEFAULT_STATUS_LIST');
                $grid   =   explode(',',$listGrid);
                $field  =   $title  =   array();
                foreach ($grid as $key=>$val){
                    $array    =   explode(':',$val);
                    $array1  =   explode('|',$array[1]);
                    $title[]    =   $array1[0];
                    $array1  =   explode('|',$array[0]);
                    $field[]    =   $array1[0];
                    $fun[]  =   isset($array1[1])?$array1[1]:'';
                }
                $m['field'] =   $title;
                $map1   =   array();
                $map1['status']  =   1;
                $map1[$foreign_key]   =   $m['foreign_key']?$vo['id']:$model['name'].'_'.$vo['id'];
                $list   =   model($m['name'])->field($field)->order('id desc')->where($map1)->limit(30)->select();
                foreach ($list as $key=>$val){
                    foreach ($val as $k=>$data){
                        if(in_array($k,$field)) {
                            $options    =   null;
                            $extra  =   $attrList[$k]['extra'];
                            $type   =   $attrList[$k]['type'];
                            if('select'== $type) { // 枚举型
                                if(0===strpos($extra,'@')) {
                                    $options    =   parseT(substr($extra,1),true);
                                }elseif(0===strpos($extra,':')){
                                    $fun    =   substr($extra,1);
                                    $options    =   $fun();
                                }else{
                                    $options    =   express_to_array($extra);
                                }
                            }elseif('bool'==$type){// 布尔型
                                $options    =   string_to_array($extra);
                            }elseif('date'==$type){
                                $val[$k]    =   todate($data,'y-m-d');
                            }elseif(in_array($k,explode(',',C('TIMESTAMP_FIELDS')))){
                                $val[$k]    =   todate($data,'y-m-d H:i:s');
                            }elseif('status'==$k){
                                $options    =   express_to_array($statusList);
                            }
                            if($options && array_key_exists($data,$options)) {
                                $val[$k]    =   $options[$data];
                            }
                        }
                    }
                    $list[$key]   =   $val;
                }
                $m['data']  =   $list;
                $models[$_k] =   $m;
            }
            $this->models   =   $models;
        }
        /*
        if(!empty($model['has_many'])) {// 存在子数据集
            list($name,$subKey)   =  explode(':',$model['has_many']);
            $this->subModel =   $name;
            $this->relationKey  =   $subKey;
            $where['name'] = $name;
            $subModel = M('Model')->where($where)->field('name,title,list_grid')->select();
            $attrList   =  $Article->getModelAttrInfo($name,0);
            // 对列表数据处理
            $listGrid   =   $subModel['list_grid']?$subModel['list_grid']:C('DEFAULT_GRID_LIST');
            $grid   =   explode(',',$listGrid);
            $field  =   $title  =   array();
            foreach ($grid as $key=>$val){
                $array    =   explode(':',$val);
                $array1  =   explode('|',$array[1]);
                $title[]    =   $array1[0];
                $array1  =   explode('|',$array[0]);
                $field[]    =   $array1[0];
            }
            $this->field    =   $title;
            $map1[$subKey]   =   $vo['id'];
            $list   =   model($name)->field($field)->order('id desc')->where($map1)->limit(30)->select();
            foreach ($list as $key=>$val){
                foreach ($val as $k=>$data){
                    if(in_array($k,$field)) {
                        $options    =   null;
                        $extra  =   $attrList[$k]['extra'];
                        $type   =   $attrList[$k]['type'];
                        if('select'== $type) { // 枚举型
                            if(0===strpos($extra,'@')) {
                                $options    =   parseT(substr($extra,1),true);
                            }elseif(0===strpos($extra,':')){
                                $fun    =   substr($extra,1);
                                $options    =   $fun();
                            }else{
                                $options    =   express_to_array($extra);
                            }
                        }elseif('bool'==$type){// 布尔型
                            $options    =   string_to_array($extra);
                        }elseif('date'==$type){
                            $val[$k]    =   todate($data,'y-m-d');
                        }elseif(in_array($k,explode(',',C('TIMESTAMP_FIELDS')))){
                            $val[$k]    =   todate($data,'y-m-d H:i:s');
                        }
                        if($options && array_key_exists($data,$options)) {
                            $val[$k]    =   $options[$data];
                        }
                    }
                }
                $list[$key]   =   $val;
            }
            $this->list  =   $list;
        }*/

        // 模型的特殊属性
        if($model['support_attach']) {
            // 开启附件功能
            // 获取附件列表
            $this->article_attach   =  1;
            $map['module']          =  $module;
            $map['record_id']       =  $vo['id'];
            $this->attachs          =  M('Attach')->where($map)->select();
            unset($map);
        }

        $this->vo           =   $vo;  // 当前文档数据
        $this->model        =   $model;  // 当前模型数据
        $this->attr_list    =   $attr_list; // 当前属性列表
        Cookie('__forward2__',__SELF__);
        // 读取模型对应的列表模板
        $template   = $model['template_edit'];
        $this->display($template?$template:'');
    }
    
    // 更新文档
    public function update() {
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }else{
            C('COOKIE_PREFIX',$module.':');
        }
        $model   = M('Model')->getByName($module);
        if(!$model) {
            $this->error('未定义的模型');
        }
        $Model	=	$this->think;
        if(!empty($model['belongs_to'])) { // 存在父模型
            list($parentModel,$parentId)   =  explode(':',$model['belongs_to']);
            if(strpos($parentModel,'}')) { // 动态绑定模型
                $parentModel    =   $_POST[substr($parentModel,1,-1)];
            }
            $_POST['_module'] = $parentModel;
            $parentData   =  $Model->create($_POST,2);
            if(!$parentData) { // 创建数据对象
                $this->error($Model->getError());
            }else{
                if(isset($_POST[$parentId])) {
                    $parentData['id'] =  $_POST[$parentId];
                }else{
                    $parentData['id']  =  model($module)->where('id='.$_POST['id'])->getField($parentId);
                }
                $_POST['_module'] = $module;
                C('TOKEN_ON',false); // 关闭令牌
            }
        }
        if(!empty($model['has_one'])) { // 存在父模型
            list($subModel,$subId)   =  explode(':',$model['has_one']);
            if(strpos($subModel,'}')) { // 动态绑定
                $subModel    =   $_POST[substr($subModel,1,-1)];
            }
            $_POST['_module'] = $subModel;
            $subData   =  $Model->create($_POST,2);
            if(!$subData) { // 创建数据对象
                $this->error($Model->getError());
            }else{
                $subData['id']  =  model($subModule)->where($subId.'='.$_POST['id'])->getField('id');
                $_POST['_module'] = $module;
                C('TOKEN_ON',false); // 关闭令牌
            }
        }
        $data = $Model->create();
        if(!$data) { // 创建数据对象
            $this->error($Model->getError());
        }elseif(isset($parentData)){
            // 首先更新上级模型
            $Model->save($parentData);
        }
        // 更新数据
        if(false !== $Model->save($data)) {
            // 更新下级模型
            if(isset($subData)) {
                $Model->save($subData);
            }
            // 同步映射文档
            $Model->sync($module,$data['id']);
            //成功提示
            $this->success(L('更新成功'),Cookie('__forward1__'));
        }else {
            //错误提示
            $this->error(L('更新失败'));
        }
    }

    // 默认删除附件操作
    public function delAttach($id) {
        //删除指定记录
        $attach    =   M("Attach");
	    $map['id'] =   array('in',$id);
        if($attach->where($map)->delete()){
            $this->ajaxReturn(array('data'=>$id,'info'=>'附件删除成功！','status'=>1));
        }else {
            $this->error('附件删除出错！'.$id);
        }
    }

    // 回收站
    public function recycleBin() {
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }
        $model   = M('Model')->where('name="'.$module.'"')->find();
        if($model) {
            $map = $this->_search();
            $map['status'] = -1;
            if(!empty($_GET['cate_id'])) {
                $map['cate_id']   =  (int)$_GET['cate_id'];
            }else{
            }
            $Model        = model($module);
            $this->model   = $model;
            if(!empty($Model)) {
                $list =   $this->_list($Model,$map);
            }
        }
        $attrList   =  $this->think->getModelAttrInfo($module,0);
        $list_grid =  !empty($model['list_grid'])?$model['list_grid']:C('DEFAULT_GRID_LIST');
        // 对列表数据处理
        $grid   =   explode(',',$list_grid);
        $field  =   array();
        foreach ($grid as $key=>$val){
            $array    =   explode(':',$val);
            //$array  =   explode('|',$array[0]);
            $field[]    =   $array[0];
        }
        foreach ($list as $key=>$val){
            foreach ($val as $k=>$data){
                if(in_array($k,$field)) {
                    $options    =   null;
                    $extra  =   $attrList[$k]['extra'];
                    $type   =   $attrList[$k]['type'];
                    if('select'== $type) { // 枚举型
                        if(0===strpos($extra,'@')) {
                            $options    =   parseT(substr($extra,1),true);
                        }elseif(0===strpos($extra,':')){
                            $fun    =   substr($extra,1);
                            $options    =   $fun();
                        }else{
                            $options    =   express_to_array($extra);
                        }
                    }elseif('bool'==$type){// 布尔型
                        $options    =   string_to_array($extra);
                    }elseif('date'==$type){ // 日期型
                        $val[$k]    =   todate($data,'Y-m-d');
                    }elseif(in_array($k,explode(',',C('TIMESTAMP_FIELDS')))){
                        $val[$k]    =   todate($data,'y-m-d H:i:s');
                    }
                    if($options && array_key_exists($data,$options)) {
                        $val[$k]    =   $options[$data];
                    }
                }
            }
            $list[$key]     =   $val;
        }
        $this->list         =   $list;
        $this->list_grid    =   $list_grid;
        Cookie('__forward1__',__SELF__);
        C('TMPL_CACHE_PREFIX',$module.'/');
		$this->display('recycleBin');
    }
    
    // 还原
    public function recycle($id=0) {
        $module     =   $_GET['_module'];
        $map['id']  =   array('IN',$id);
        $result     =   $this->think->setStatus(0,$module,$map);
        if($result){
            $this->success('状态还原成功！',Cookie('__forward1__'));
        }else {
            $this->error('状态还原失败！');
        }
    }

    // 文档排序
    public function sort(){
        $map = array();
        $map['status'] = 1;
        if(!empty($_GET['cate_id'])) {
            $map['cate_id']   =  $_GET['cate_id'];
            $module =   getModuleByCate($_GET['cate_id']);
        }else{
            $module = $_REQUEST['_module'];
        }
        // 检查模型是否支持排序
        $model  =   M('Model')->getByName($module);
        if(!$model['support_sort']) {
            $this->error('当前模型不支持排序');
        }
        if($model['support_level']) {
            $map['pid'] =   $_GET['pid'];
        }
        if(!empty($_GET['sortId'])) {
            $map['id']   = array('in',$_GET['sortId']);
            Cookie('sortId',$_GET['sortId']);
        }
        $foreign_key = $model['foreign_key']?$model['foreign_key']:'record_id';
        if(!empty($_GET[$foreign_key])) {
            $map[$foreign_key] =  $_GET[$foreign_key];
        }
        $this->module   =   $module;
        $this->sortList =  model($module)->where($map)->order('sort')->select();
        $this->display();
    }
    
    // 排序保存操作
    public function saveSort() {
        $seqNoList  =   $_POST['seqNoList'];
        $module = $_POST['module'];
        if(!empty($seqNoList)) {
            //更新数据对象
            $Article    = $this->think;
            $col    =   explode(',',$seqNoList);
            //启动事务
            //$Article->startTrans();
            foreach($col as $val) {
                $val    =   explode(':',$val);
                $data['id']	=	$val[0];
                $data['sort']	=	$val[1];
                $result =   $Article->save($data,$module);
                if(false === $result) {
                    break;
                }
            }
            //提交事务
            //$Article->commit();
            if(false !== $result) {
                //采用普通方式跳转刷新页面
                $this->success('更新成功');
            }else {
                $this->error('更新错误:'.$Article->getError());
            }
        }
    }

    // 删除文档
    public function delete($id=0) {
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }
        // 对删除文档设置标记位
        if(empty($id)) {
            $this->error(L('非法操作'));
        }
        $map['id'] = array('in',$id);
        $result   = $this->think->delete($module,$map);
        if(false !== $result){
            // 删除映射文档
            $this->think->syncStatus($module,$id,-1);
            $this->success(L('删除成功'));
        }else {
            $this->error(L('删除失败'));
        }
    }

    // 永久删除文档
    public function foreverdelete($id=0){
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }
        //删除指定记录
        if(empty($id)) {
            $this->error(L('非法操作'));
        }
        $map['id'] = array('in',$id);
        $result   =  $this->think->clear($module,$map);
        if(false !== $result){
            $this->success(L('删除成功'));
        }else {
            $this->error(L('删除失败'));
        }
    }
    
    // 清空回收站
    public function clear(){
        $map['status'] = -1;
        $module = $_REQUEST['_module'];
        if(!empty($_REQUEST['cate_id'])) {
            $map['cate_id']   =  (int)$_REQUEST['cate_id'];
        }
        $result   =  $this->think->clear($module,$map);
        if(false !== $result){
            $this->success(L('清空成功'));
        }else {
            $this->error(L('清空失败'));
        }
    }

    protected function _upload_init($upload) {
        $upload->maxSize  = -1;//C('UPLOAD_MAX_SIZE') ;
        $upload->allowExts  = explode(',',strtolower(C('UPLOAD_FILE_EXT')));
        $upload->savePath =  './Uploads/attach/';
        return $upload;
    }

    // 删除图片
    public function delPic(){
        $result   =  $this->think->setValue($_GET['_module'],$_GET['id'],$_GET['name'],'');
        if(false !== $result) {
            $this->success('删除成功！');
        }else{
            $this->error('删除错误或已经删除！');
        }
    }

    // 禁用操作
    public function forbid($id=0) {
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }
        $map['id']   = array('in',$id);
        if($this->think->setStatus(0,$module,$map)){
            $this->think->syncStatus($module,$id,0);
            $this->success('状态禁用成功！',$_SERVER['HTTP_REFERER']);//Cookie('__forward__'));
        }else {
            $this->error('状态禁用失败！');
        }
    }

    // 恢复操作
    public function resume($id=0) {
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }
        //恢复指定记录
        $map['id'] = array('in',$id);
        if($this->think->setStatus(1,$module,$map)){
            $this->think->syncStatus($module,$id,1);
            $this->success('状态恢复成功！',$_SERVER['HTTP_REFERER']);
        }else {
            $this->error('状态恢复失败！');
        }
    }

    // 移动文档
    public function moveArticle() {
        if(empty($_POST['id'])) {
            $this->error('请选择要移动的文档！');
        }
		$_SESSION['moveArticle']	 =	 $_POST['id'];
        $_SESSION['moveModule'] =   $_POST['module'];
		$this->success('请选择要移动到的分类！');
    }
    // 拷贝文档
    public function copyArticle() {
        if(empty($_POST['id'])) {
            $this->error('请选择要复制的文档！');
        }
		$_SESSION['copyArticle']	 =	 $_POST['id'];
        $_SESSION['copyModule'] =   $_POST['module'];
		$this->success('请选择要复制到的分类！');
    }
    // 映射文档
    public function linkArticle(){
        if(empty($_POST['id'])) {
            $this->error('请选择要映射的文档！');
        }
		$_SESSION['linkArticle']	 =	 $_POST['id'];
        $_SESSION['linkModule'] =   $_POST['module'];
		$this->success('请选择要导入到的分类！');
    }
    // 导入文档
    public function importArticle(){
        if(empty($_SESSION['linkArticle']) ) {
            $this->error('首先选择要映射的文档！');
        }
        if(!isset($_POST['cate_id'])) {
            $this->error('请选择要导入到的分类！');
        }
        $cate_id = (int)$_POST['cate_id'];
        $module =   $this->getModuleName();
        if($_SESSION['linkModule'] != $module) {
            $this->error('模型不同不能映射！');
        }
        $Model  =   model($_SESSION['linkModule']);
        $ids   = explode(',',$_SESSION['linkArticle']);
        $dataList = array();
        foreach($ids as $id){
            $data = $Model->find($id);
            if($data && empty($data['link_id'])) {
                $data['cate_id']   =  $cate_id;
                $data['link_id'] =   $data['id'];
                $data['create_time']    =   NOW_TIME;
                $data['update_time']    =   NOW_TIME;
                unset($data['id']);
                $dataList[]  = $data;
            }
        }
        $result   =  $Model->addAll($dataList);
        if($result){
            unset($_SESSION['linkArticle'],$_SESSION['copyModule']);
            $this->success('文档映射成功！');
        }else{
            $this->error('文档映射失败！');
        }
    }
    // 粘贴文档
    public function pasteArticle() {
        if(empty($_SESSION['moveArticle']) && empty($_SESSION['copyArticle'])) {
            $this->error('没有选择文章！');
        }
        if(!isset($_POST['cate_id'])) {
            $this->error('请选择要粘贴到的分类！');
        }
        $cate_id = (int)$_POST['cate_id'];
        if(!empty($_SESSION['moveArticle'])) {// 移动
            $module =   $this->getModuleName();
            if($_SESSION['moveModule'] != $module) {
                $this->error('模型不同不能移动！');
            }
            $Model  =   model($_SESSION['moveModule']);
            $map['id']   = array('IN',$_SESSION['moveArticle']);
            $data['cate_id']   =  $cate_id;
            if($Model->where($map)->save($data)){
                unset($_SESSION['moveArticle'],$_SESSION['moveModule']);
                $this->success('文章移动成功！');
            }else{
                $this->error('文章移动失败！');
            }
        }elseif(!empty($_SESSION['copyArticle'])){ // 复制
            $module =   $this->getModuleName();
            if($_SESSION['copyModule'] != $module) {
                $this->error('模型不同不能复制！');
            }
            $Model  =   model($_SESSION['copyModule']);
            $ids   = explode(',',$_SESSION['copyArticle']);
            $dataList = array();
            foreach($ids as $id){
                $data = $Model->find($id);
                unset($data['id']);
                $data['cate_id']        =  $cate_id;
                $data['create_time']    =   NOW_TIME;
                $data['update_time']    =   NOW_TIME;
                $dataList[]  = $data;
            }
            $result   =  $Model->addAll($dataList);
            if($result){
                unset($_SESSION['copyArticle'],$_SESSION['copyModule']);
                $this->success('文章复制成功！');
            }else{
                $this->error('文章复制失败！');
            }
        }
    }

    // 审核通过文档
	public function checkPass() {
        //恢复指定记录
        $condition = array('id'=>array('in',$_GET['id']));
        if($this->think->setStatus(1,$_GET['_module'],$condition)){
            $this->success('文档审核通过！',Cookie('__forward1__'));
        }else {
            $this->error('文档审核出错或者无需审核！');
        }
	}

    public function sendFlow($flow_id=0,$record_id=''){
        $where['record_id'] =   $record_id;
        $where['flow_id']   =   $flow_id;
        if(M('Workflow')->where($where)->find()){
            $this->error('不能重复发送！');
        }
        $flow = M('Flow')->find($flow_id);
        $this->flow =   $flow;
        $map['status']  =   1;
        $map['id']  =   array('IN',$flow['role_list']);
        $this->roleList  = M('Role')->where($map)->getField('id,name');
        $this->display('sendFlow');
    }

    public function saveFlow(){
        // 实例化模型
        $Model	=	D('Workflow');
         // 创建数据对象
        $data = $Model->create();
        if(!$data) {
            $this->error($Model->getError());
        }
        //保存当前数据对象
        if($result = $Model->add($data)) { // 写入成功
            //成功提示
            $this->success(L('发送成功'),Cookie('__forward1__'));
        }else {
            //失败提示
            $this->error(L('发送失败'));
        }
    }

    public function moveNode() {
		$id	=	$_POST['id'];
		$_SESSION['moveNode']	 =	 $id;
		$this->success('请选择要移动到的节点！');
    }

    public function copyNode() {
		$id	=	$_POST['id'];
		$_SESSION['copyNode']	 =	 $id;
		$this->success('请选择要复制到的节点！');
    }

    public function pasteNode() {
        if(empty($_SESSION['moveNode']) && empty($_SESSION['copyNode'])) {
            $this->error('没有选择节点！');
        }
        if(!isset($_POST['pid'])) {
            $this->error('请选择要粘贴到的节点！');
        }
        $module = $this->getModuleName();
        $Cate =  model($module);
        // 查询父栏目
        $pid = (int)$_POST['pid'];
        $level = $Cate->where('id='.$pid)->getField('level');
        if(!empty($_SESSION['moveNode'])) {// 移动
            $map['id']   = array('IN',$_SESSION['moveNode']);
            $data['pid'] =  $pid;
            $data['level']   =  $level+1;
            if($Cate->where($map)->save($data)){
                unset($_SESSION['moveNode']);
                $this->success('栏目移动成功！');
            }else{
                $this->error('栏目移动失败！');
            }
        }elseif(!empty($_SESSION['copyNode'])){ // 复制
            $ids   = explode(',',$_SESSION['copyNode']);
            $dataList = array();
            foreach($ids as $cateId){
                $cate = $Cate->find($cateId);
                unset($cate['id']);
                $cate['level']   =  $level+1;
                $cate['pid'] =  $pid;
                $cate['name'] =  $cate['name'].'_1_';
                $dataList[]  = $cate;
            }
            $result   =  $Cate->addAll($dataList);
            if($result){
                unset($_SESSION['copyNode']);
                $this->success('节点复制成功！');
            }else{
                $this->error('节点复制失败！');
            }
        }
    }

    // 报表
    public function vstat($type='d'){
        // 获取当前模型
        $module =   $this->getModuleName();
        if(empty($module)) {
            $this->error('未指定模型！');
        }
        $model   = M('Model')->getByName($module);
        $this->model    =   $model;
        if(!$model) {
            $this->error('未定义的模型');
        }
        $model  =   model($module);
        $d = intval($_REQUEST['t']?($_REQUEST['t']-1):6);
        switch(strtolower($type)) {
            case 'm': // 月报表
                $end  =  strtotime(date('Y-m-01'));
                $begin   =  strtotime('-'.$d.' months',$end);
                $list = array();
                $time    = $begin;
                $xml = "<chart caption='按月统计(".date('Y-m',$begin)."~".date('Y-m',$end).")' showValues='1' decimals='0' formatNumberScale='0' baseFontSize='14'>";
                while($time<=$end) {
                    $count  =  $model->where('create_time>='.$time.' and create_time<='.strtotime('+1 month',$time))->count();
                    $xml .= "<set label='".date('Y-m',$time)."' value='$count' />";
                    $time    = strtotime('+1 month',$time);
                }
                break;
            case 'y': // 年报表
                $end  =  strtotime(date('Y-01-01'));
                $begin   =  strtotime('-'.$d.' years',$end);
                $time    = $begin;
                $xml = "<chart caption='按年统计(".date('Y',$begin)."~".date('Y',$end).")' showchatPoints='1' decimals='0' formatNumberScale='0' baseFontSize='14'>";
                while($time<=$end) {
                    $count  =  $model->where('create_time>='.$time.' and create_time<='.strtotime('+1 year',$time))->count();
                    $xml .= "<set label='".date('Y',$time)."' value='$count' />";
                    $time    = strtotime('+1 year',$time);
                }
                break;
            case 'w': // 周报表
                $end  =  strtotime(date('Y-m-d'));
                $begin   =  strtotime('-'.$d.' weeks',$end);
                $time    = $begin;
                $xml = "<chart caption='按周统计(".date('Y',$begin)."~".date('Y',$end).")' showchatPoints='1' decimals='0' formatNumberScale='0' baseFontSize='14'>";
                while($time<=$end) {
                    $count  =  $model->where('create_time>='.$time.' and create_time<='.strtotime('+1 week',$time))->count();
                    $xml .= "<set label='".date('W',$time)."' value='$count' />";
                    $time    = strtotime('+1 week',$time);
                }
                break;
            case 'h':
            default: // 小时报表
                $end  =  strtotime(date('Y-m-d H:0:0'));
                $begin   =  strtotime('-'.$d.' hours',$end);
                $time    = $begin;
                $xml = "<chart caption='按小时统计(".date('H',$begin).":00~".date('H',$end).":00)' showchatPoints='1' decimals='0' formatNumberScale='0' baseFontSize='14'>";
                while($time<=$end) {
                    $count  =  $model->where('create_time>='.$time.' and create_time<='.strtotime('+1 hour',$time))->count();
                    $xml .= "<set label='".date('H',$time)."' value='$count' />";
                    $time    = strtotime('+1 hour',$time);
                }
                break;
            case 'd':
            default: // 日报表
                $end  =  strtotime(date('Y-m-d'));
                $begin   =  strtotime('-'.$d.' days',$end);
                $time    = $begin;
                $xml = "<chart caption='按日统计(".date('Y',$begin)."~".date('Y',$end).")' showchatPoints='1' decimals='0' formatNumberScale='0' baseFontSize='14' >";
                while($time<=$end) {
                    $count  =  $model->where('create_time>='.$time.' and create_time<='.strtotime('+1 day',$time))->count();
                    $xml .= "<set label='".date('Y-m-d',$time)."' value='$count' />";
                    $time    = strtotime('+1 day',$time);
                }
                break;
        }
        $xml .="</chart>"; 
        $chartWidth   = 650;
        $chartHeight  = 300;
        $chartShow   =  true;
        $this->assign('chartShow',$chartShow);
        $this->assign('chartWidth',$chartWidth);
        $this->assign('chartHeight',$chartHeight);
        $this->assign('chartXML',$xml);    	
        $this->display();
    }

    //重置密码
    public function resetPwd() {
    	$id  =  $_POST['id'];
        $password = $_POST['password'];
        if(''== trim($password)) {
        	$this->error('密码不能为空！');
        }
        $User = M('Auth');
		$User->password	=	md5($password);
		$User->id			=	$id;
		$result	=	$User->save();
        if(false !== $result) {
            $this->success("密码修改为$password");
        }else {
        	$this->error('重置密码失败！');
        }
    }

}