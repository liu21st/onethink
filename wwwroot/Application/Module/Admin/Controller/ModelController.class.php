<?php
// +----------------------------------------------------------------------
// | TOPThink
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// 模型管理
class ModelController extends CommonController {

    public function _initialize() {
        parent::_initialize();
        // 获取数据库对象实例
        $this->db   =  Db::getInstance();
        if($this->_get('app_id')) { // 检查应用权限
            if(!ThinkAcl::checkRoleAcl(I('app_id'),1)) {
                $this->error('没有权限！');
            }
        }elseif($this->_get('id')){// 检查模型权限
            if(!ThinkAcl::checkRoleAcl(I('id'),2)) {
                $this->error('没有权限！');
            }
        }
    }

    public function _filter(&$map){
        if(empty($_SESSION['administrator'])) {
            // 检查应用权限
            $appId = ThinkAcl::getRoleAccessList(1);
            $map['app_id'] = array('IN',$appId?array_keys($appId):'');
            // 检查模型权限
            $modelId = ThinkAcl::getRoleAccessList(2);
            $map['id'] = array('IN',$modelId?array_keys($modelId):'');
        }
    }

    public function edit($id=0) {
        $model  =  M('Model')->find($id);
        if(!$model) {
            $this->error('模型不存在！');
        }
        if(!empty($model['attribute_list'])) {
            $_SESSION['model_'.$id]= explode(',',$model['attribute_list']);
        }
        $this->vo   =  $model;
        // 获取属性列表
        $map['status'] = 1;
        $array['is_common']  =  1;
        if($model['type']==1) {
            $array['model_id'] =  array(0,$model['id'],'or');
        }else{
            $array['model_id'] =  $model['id'];
        }
        $array['_logic'] = 'OR';
        $map['_complex'] = $array;
        $this->attrList = M('Attribute')->where($map)->order('is_common desc,sort')->field('id,name,title,is_common')->select();

        // 映射字段列表
        $map['id']   = array('IN',$model['attribute_list']);
        $this->searchList = M('Attribute')->where($map)->getField('name,title');

        // 映射字段列表
        $map['is_show']   = array('gt',0);
        $this->mapList = M('Attribute')->where($map)->getField('name,title');

        if($model['ext_list']) {
            $this->extList = array_flip(explode(',',$model['ext_list']));
        }
        Cookie('__forward2__',__SELF__);
        $this->display();
    }
    
    public function _before_delete(){
        // 删除数据表
        $id = (int)$_REQUEST['id'];
        $model  =  M('Model')->field('id,name,db_name,table_name,attribute_list,model_type,table_prefix')->find($id);
        if(!$model) {echo m()->_sql();
            $this->error('模型不存在！');
        }
        if(1==$model['model_type']) { // 视图模型 没有实际数据表
            return ;
        }
        $prefix   =  !empty($model['table_prefix'])?$model['table_prefix']:C('DB_PREFIX');
        $tableName = $model['db_name'].'.`'.($model['table_name']?$model['table_name']:$prefix.$model['name']).'`';
        // 删除字段缓存文件
        unlink(DATA_PATH.'_fields/'.$model['db_name'].'.'.strtolower(parse_name($model['name'],1)).'.php');
        $sql   = 'DROP TABLE '.$tableName;
        $result =   model($model['name'])->execute($sql);
        if($result) {
            // 删除属性定义
            $map['id']  =   array('IN',$model['attribute_list']);
            $map['is_common']   =   array('neq',1);
            $result =   M('Attribuate')->where($map)->delete();
        }
        return $result;
    }

    public function update(){

        $Model  =  D('Model');
        if(!empty($_POST['include_model'])) { // 包含某个模型
            $attrList =  M('Model')->getfieldbyName($_POST['include_model'],'attribute_list');
            $_POST['attribute_list']  =  array_merge($_POST['attribute_list'],explode(',',$attrList));
        }
        if($_POST['app_id']) {// 选择所属应用 则继承应用的数据库信息
            // 读取应用信息
            $app    =   M('App')->field('db_name,table_prefix,connection')->find($_POST['app_id']);
            if(empty($_POST['db_name']) && $app['db_name']) {
                $_POST['db_name']  =   $app['db_name'];
            }
            if(empty($_POST['table_prefix']) && $app['table_prefix']) {
                $_POST['table_prefix']  =   $app['table_prefix'];
            }
            if(empty($_POST['connection']) && $app['connection']) {
                $_POST['connection']  =   $app['connection'];
            }
        }
        if(false === $Model->create()) {
        	$this->error($Model->getError());
        }
		// 更新数据
		if(false === $Model->save()) {
            //错误提示
            $this->error(L('更新失败'));
        }
        if(1==$_POST['model_type']) {// 视图模型
            $this->success(L('更新成功'),Cookie('__forward2__'));
        }
        $id = (int)$_POST['id'];
        $model  =  M('Model')->field('id,name,title,is_show,app_id,db_name,table_name,table_prefix,connection,data_length,table_engine,support_cate,module_list,attribute_list,foreign_key')->find($id);
        if(1==$model['model_type']) {// 视图模型
            $this->success(L('更新成功'),Cookie('__forward2__'));
        }
        // 模型前缀定义
        $prefix   =  !empty($model['table_prefix'])?$model['table_prefix']:C('DB_PREFIX');
        $tableName = '`'.($model['table_name']?$model['table_name']:$prefix.$model['name']).'`';
        if(!empty($model['db_name'])) {
            $tableName = $model['db_name'].'.'.$tableName;
        }
        // 字段缓存文件
        $cacheFile  =  DATA_PATH.'_fields/'.strtolower($model['db_name'].'.'.parse_name($model['name'],1)).'.php';
        // 删除字段缓存文件
        unlink($cacheFile);
        // 生成模型的上传目录
        if(!is_dir('./Uploads/'.$model['name'].'/')) {
            mkdir('./Uploads/'.$model['name'].'/');
        }
        // 列表缓存文件
        array_map("unlink", glob(C('CACHE_PATH').$model['name'].'/*'));
        // 比较字段变化
        // 检查数据表是否已经创建
        $find  =  M()->db(1,$model['connection'])->table($tableName)->find();
        if($find) { // 已经创建
            $del_list =  array_diff($_SESSION['model_'.$id],$_POST['attribute_list']);
            if(!empty($del_list)) {// 删除字段
                $result   =  $this->dropField($tableName,$del_list,$model['name']);
                if(false === $result) {
                    $this->error('发生错误1');
                }
            }
            $add_list = array_diff($_POST['attribute_list'],$_SESSION['model_'.$id]);
            if(!empty($add_list)) { // 新增字段属性
                $result   =  $this->addField($tableName,$add_list,$model['name']);
                if(false === $result) {
                    $this->error('发生错误2');
                }
            }
            if($_POST['support_status']) {
                $this->addStatusField($tableName,$model['name']);
            }       
            if($_POST['support_time']) {
                $this->addTimeField($tableName,$model['name']);
            }                   
            if($_POST['support_cate']) {
                $this->addCateField($tableName,$model['name']);
            }
            if($_POST['support_tags']) {
                $this->addTagsField($tableName,$model['name']);
            }
            if($_POST['support_level']) {
                $this->addLevelField($tableName,$model['name']);
            }
            if($_POST['support_link']) {
                $this->addLinkField($tableName,$model['name']);
            }
            if($_POST['support_sort']) {
                $this->addSortField($tableName,$model['name']);
            }
            if($_POST['allow_as_sub'] && empty($model['foreign_key'])) {
                $this->addRecordField($tableName,$model['name']);
            }
            if($_POST['support_flow']) {
                $this->addFlowField($tableName,$model['name']);
            }
        }elseif(!empty($_POST['attribute_list'])){ // 第一次创建
            $result   =  $this->createTable($tableName,$_POST['attribute_list'],$model);
            if(false === $result) {
                $this->error(L('发生错误3'));
            }
        }
        $this->success(L('更新成功'),Cookie('__forward2__'));
    }

    /**
     +----------------------------------------------------------
     * 导入SQL文件
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function importModel() {
        if(!empty($_FILES['modelFile']['name'])) {
            // 判断文件后缀
            $pathinfo = pathinfo($_FILES['modelFile']['name']);
            $ext  =   $pathinfo['extension'];
            if(!in_array($ext,array('sql','zip','rar','gz','txt'))) {
                $this->error('文件格式不符合！');
            }
            // 导入模型定义文件
            if(in_array($ext,array('zip','rar','gz'))) {
                $zip = new ZipArchive();
                $zip->open($_FILES['modelFile']['tmp_name']);
                $file   = $zip->statIndex(0);
                $zip->extractTo(C('TEMP_PATH').'_import');
                $content   = file_get_contents(C('TEMP_PATH').'_import/'.$file['name']);
                unlink(C('TEMP_PATH').'_import/'.$file['name']);
            }else{
                $content   = file_get_contents($_FILES['modelFile']['tmp_name']);
            }
        }elseif(!empty($_POST['modelContent'])){
            $content   = $_POST['modelContent'];
        }else{
            $this->error('选择要导入的文件');
        }
        $content   = str_replace("\r\n", "\n", $content);
        $type   =   $_POST['importType'];
        unlink($_FILES['modelFile']['tmp_name']);
        if(false === $this->executeSql($content)) {
            $this->error('模型导入错误！');
        }else{
            $this->success('模型导入完成！');
        }
    }

    // 创建模型
    protected function buildModel($data) {
        // 读取模型属性
        if(!empty($data['attribute_list'])) {
            // 导入属性
        }
    }

    // 批量执行SQL语句
    protected function executeSql($querySql) {
        $db   =  Db::getInstance();
        if(is_string($querySql)) {
            $querySql   =  explode(";\n", trim($querySql)) ;
        }
        $ret = array();
        $num = 0;
        foreach($querySql as $query) {
            $queries = explode("\n", trim($query));
            foreach($queries as $query) {
                $ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
            }
            $num++;
        }
        foreach($ret as $query) {
            if(!empty($query)) {
                $result   = $db->execute($query);
                if(false === $result) {
                    return false;
                }
            }
        }
        return $result;
    }

    // 创建数据表
    protected function createTable($tableName,$attr_list,$model) {
        // 字段类型
        $type = array('text'=>'text','editor'=>'text','num'=>'int(11) unsigned NOT NULL DEFAULT 0','bool'=>'tinyint(1) unsigned NOT NULL DEFAULT 0','date'=>'int(11) unsigned NOT NULL DEFAULT 0');
        $engine  = $model['table_engine']?$model['table_engine']:'MyISAM';
        // id主键是必须字段
        $data_length =   intval($model['data_length']);
        $sql   = 'CREATE TABLE IF NOT EXISTS '.$tableName.' (`id` '.($data_length>10?'BIGINT':'INT').'( '.$data_length.' ) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'主键\',';
        // 属性列表
        $map['id']   = array('IN',$attr_list);
        $list   =  M("Attribute")->where($map)->field('title,name,field,extra,type')->order('sort')->select();
        unset($map);
        foreach($list as $attr){
            if($attr['type']=='complex') {// 组合字段
                $map2['name']= array('IN',$attr['extra']);
                $list2   =  M("Attribute")->where($map2)->field('title,name,field,extra,type')->select();
                foreach ($list2 as $attr2){
                    $sql   .= '`'.$attr2['name'].'`'.($attr2['field']?$attr2['field']:($type[$attr2['type']]?$type[$attr2['type']]:'varchar(255) DEFAULT NULL')).'COMMENT \''.$attr['title'].'\',';
                }
            }elseif($attr['type'] != 'zone') {
                $sql   .= '`'.$attr['name'].'`'.($attr['field']?$attr['field']:($type[$attr['type']]?$type[$attr['type']]:'varchar(255) DEFAULT NULL')).' COMMENT \''.$attr['title'].'\',';
            }
        }
        if($model['support_cate']) {
            $sql    .=  '`cate_id` mediumint(5) unsigned default 0 COMMENT \'分类ID\',';
        }
        if($model['support_level']) {
            $sql   .=   '`pid` mediumint(6) unsigned default 0 COMMENT \'父ID\',';
            $sql   .=  '`level` smallint(2) unsigned default 0 COMMENT \'层次\',';
        }
        if($model['support_link']) {
            $sql    .=  '`link_id` varchar(25) default NULL COMMENT \'映射ID\',';
        }
        if($model['support_sort']) {
            $sql    .=  '`sort` mediumint(5) unsigned default 0 COMMENT \'排序\',';
        }
        if($model['support_flow']) {
            $sql    .=  '`flow_id` smallint(3) unsigned default 0 COMMENT \'流程ID\',`op_type` tinyint(1) unsigned default 0 COMMENT \'操作员类型\',`op_id` mediumint(6) unsigned default 0 COMMENT \'操作员ID\',';
        }
        if($model['allow_as_sub'] && empty($model['foreign_key'])) {
            $sql    .=  '`record_id` varchar(50) default NULL COMMENT \'所属记录ID\',';
        }
        if($model['support_tags']) {
            $sql    .=  '`tags` varchar(100) default NULL COMMENT \'标签\',';
        }
        if($model['support_status']) {
            $sql    .=  '`status` tinyint(1) unsigned default 0 COMMENT \'状态\',';
        }    
        if($model['support_time']) {
            $sql    .=  '`create_time` int(10) unsigned DEFAULT 0 COMMENT \'创建时间\',
                    `update_time` int(10) unsigned DEFAULT 0 COMMENT \'更新时间\',';
        }
        // 公共部分
        $sql  .=    ' PRIMARY KEY (`id`)';
        if($model['support_status']) {
            $sql .= ',KEY `status` (`status`)';
        }
        $sql  .=    ') ENGINE='.$engine.' DEFAULT CHARSET=utf8;';        
        return  model($model['name'])->execute($sql);
    }

    // 增加数据表字段
    protected function addField($tableName,$attr_list,$modelName) {
        $type = array('text'=>'text','editor'=>'text','num'=>'int(11) unsigned NOT NULL DEFAULT 0','bool'=>'tinyint(1) unsigned NOT NULL DEFAULT 0','date'=>'int(11) unsigned NOT NULL DEFAULT 0');
        $map['id']   = array('IN',$attr_list);
        $list   =  M("Attribute")->where($map)->field('title,name,field,extra,type')->order('sort')->select();
        $sql = 'ALTER TABLE '.$tableName;
        foreach($list as $attr){
            if($attr['type']=='complex') {// 组合字段
                $map2['name']= array('IN',$attr['extra']);
                $list2   =  M("Attribute")->where($map2)->field('title,name,field,extra,type')->select();
                foreach ($list2 as $attr2){
                    $sql   .= ' ADD `'.$attr2['name'].'`'.($attr2['field']?$attr2['field']:($type[$attr2['type']]?$type[$attr2['type']]:' varchar(255) DEFAULT NULL')).' COMMENT \''.$attr2['title'].'\',';
                }
            }elseif($attr['type'] != 'zone') {
                $sql   .= ' ADD `'.$attr['name'].'`'.($attr['field']?$attr['field']:($type[$attr['type']]?$type[$attr['type']]:' varchar(255) DEFAULT NULL')).' COMMENT \''.$attr['title'].'\',';
            }
        }
        $sql   = substr($sql,0,-1).';';
        return  model($modelName)->execute($sql);
    }

    // 添加分类支持字段
    protected function addCateField($tableName,$modelName) {
        // 查询字段是否存在
        $result =   model($modelName)->query('Describe '.$tableName.' cate_id');
        if(empty($result)) { // 不存在则添加字段
            $sql    =   'ALTER TABLE '.$tableName;
            $sql   .=   ' ADD `cate_id` mediumint(5) unsigned default 0 COMMENT \'分类ID\';';
            return  model($modelName)->execute($sql);
        }
    }

    // 添加标签支持字段
    protected function addTagsField($tableName,$modelName) {
        // 查询字段是否存在
        $result =   model($modelName)->query('Describe '.$tableName.' tags');
        if(empty($result)) { // 不存在则添加字段
            $sql    =   'ALTER TABLE '.$tableName;
            $sql   .=   ' ADD `tags` varchar(100) default NULL COMMENT \'标签\';';
            return  model($modelName)->execute($sql);
        }
    }

    // 添加状态支持字段
    protected function addStatusField($tableName,$modelName) {
        // 查询字段是否存在
        $result =   model($modelName)->query('Describe '.$tableName.' status');
        if(empty($result)) { // 不存在则添加字段
            $sql    =   'ALTER TABLE '.$tableName;
            $sql   .=   ' ADD `status` tinyint(1) unsigned default 0 COMMENT \'状态\';';
            return  model($modelName)->execute($sql);
        }
    }

    // 添加层级支持字段
    protected function addLevelField($tableName,$modelName) {
        // 查询字段是否存在
        $result =   model($modelName)->query('Describe '.$tableName.' level');
        if(empty($result)) { // 不存在则添加字段
            $sql    =   'ALTER TABLE '.$tableName;
            $sql   .=   ' ADD `pid` mediumint(6) unsigned default 0 COMMENT \'父ID\',';
            $sql   .=   ' ADD `level` smallint(2) unsigned default 0 COMMENT \'层次\';';
            return  model($modelName)->execute($sql);
        }
    }

    // 添加映射支持字段
    protected function addLinkField($tableName,$modelName) {
        // 查询字段是否存在
        $result =   model($modelName)->query('Describe '.$tableName.' link_id');
        if(empty($result)) { // 不存在则添加字段
            $sql    =   'ALTER TABLE '.$tableName;
            $sql   .=   ' ADD `link_id` varchar(25) default NULL COMMENT \'映射ID\';';
            return  model($modelName)->execute($sql);
        }
    }

    // 添加排序支持字段
    protected function addSortField($tableName,$modelName) {
        // 查询字段是否存在
        $model  =   model($modelName);
        $result =   $model->query('Describe '.$tableName.' sort');
        if(empty($result)) { // 不存在则添加字段
            $sql    =   'ALTER TABLE '.$tableName;
            $sql   .=   ' ADD `sort` mediumint(5) unsigned default 0 COMMENT \'排序\';';
            return  $model->execute($sql);
        }
    }

    // 添加子记录支持字段
    protected function addRecordField($tableName,$modelName) {
        // 查询字段是否存在
        $model  =   model($modelName);
        $result =   $model->query('Describe '.$tableName.' record_id');
        if(empty($result)) { // 不存在则添加字段
            $sql    =   'ALTER TABLE '.$tableName;
            $sql   .=   ' ADD `record_id` varchar(50) default NULL COMMENT \'所属记录ID\';';
            return  $model->execute($sql);
        }
    }

    // 添加子记录支持字段
    protected function addTimeField($tableName,$modelName) {
        // 查询字段是否存在
        $model  =   model($modelName);
        $result =   $model->query('Describe '.$tableName.' create_time');
        if(empty($result)) { // 不存在则添加字段
            $sql    =   'ALTER TABLE '.$tableName;
            $sql   .=   ' ADD `create_time` int(10) unsigned DEFAULT 0 COMMENT \'创建时间\',
                         ADD `update_time` int(10) unsigned DEFAULT 0 COMMENT \'更新时间\';';
            return  $model->execute($sql);
        }
    }

    // 添加排序支持字段
    protected function addFlowField($tableName,$modelName) {
        // 查询字段是否存在
        $model  =   model($modelName);
        $result =   $model->query('Describe '.$tableName.' flow_id');
        if(empty($result)) { // 不存在则添加字段
            $sql    =   'ALTER TABLE '.$tableName;
            $sql   .=   ' ADD `flow_id` smallint(3) unsigned default 0 COMMENT \'排序\',';
            $sql   .=  'ADD `op_type` tinyint(1) unsigned default 0 COMMENT \'操作员类型\',';
            $sql   .=  'ADD `op_id` mediumint(6) unsigned default 0 COMMENT \'操作员ID\';';
            return  $model->execute($sql);
        }
    }

    // 删除数据表字段
    protected function dropField($tableName,$attr_list,$modelName) {
        $map['id']   = array('IN',$attr_list);
        $list   =  M("Attribute")->where($map)->field('name,extra,type')->order('sort')->select();
        $sql = 'ALTER TABLE '.$tableName;
        foreach($list as $attr){
            if($attr['type']=='complex') {// 组合字段
                $map2['name']= array('IN',$attr['extra']);
                $list2   =  M("Attribute")->where($map2)->field('name,extra,type')->select();
                foreach ($list2 as $attr2){
                    $sql   .= ' DROP `'.$attr2['name'].'`,';
                }
            }elseif($attr['type'] != 'zone') {
                $sql   .= ' DROP `'.$attr['name'].'`,';
            }
        }
        $sql   = substr($sql,0,-1).';';
        return  model($modelName)->execute($sql);
    }

    // 创建多个数据表 用于模型对应表被删除的情况
    public function cache(){
        // 获取模型的字段列表
        if(!empty($_GET['id'])) {
            $map['id']   = array('IN',$_GET['id']);
        }
        $map['status'] = 1;
        $models   =  M('Model')->field('id,name,app_id,table_prefix,attribute_list,data_length,support_cate,module_list,table_engine')->where($map)->select();
        unset($map);
        $commond =  array();  // 要执行的SQL指令集
        // 字段类型
        $type = array('text'=>'text','editor'=>'text','num'=>'int(11) unsigned NOT NULL DEFAULT 0','bool'=>'tinyint(1) unsigned NOT NULL DEFAULT 0','date'=>'int(11) unsigned NOT NULL DEFAULT 0');
        $prefix   =  C('DB_PREFIX');
        foreach ($models as $model){
            $engine  = $model['table_engine']?$model['table_engine']:'MyISAM';
            if(!empty($model['table_prefix'])) { // 模型前缀定义
                $prefix   =  $model['table_prefix'];
            }
            if(strpos($model['name'],'.')) { // 指定数据库
                list($dbName,$name) = explode('.',$model['name']);
                $tableName = $dbName.'.`'.$prefix.$name.'`';
                // 删除字段缓存文件
                unlink(DATA_PATH.'_fields/'.strtolower(parse_name($model['name'],1)).'.php');
            }else{
                $tableName = '`'.$prefix.$model['name'].'`';
                // 删除字段缓存文件
                unlink(DATA_PATH.'_fields/'.strtolower(C('DB_NAME').'.'.parse_name($model['name'],1)).'.php');
            }
            // 生成模型的上传目录
            if(!is_dir('./Uploads/'.$model['name'].'/')) {
                mkdir('./Uploads/'.$model['name'].'/');
            }
            //$commond[] = 'DROP TABLE IF EXISTS '.$tableName.';';
            // 必须字段
            $sql   = 'CREATE TABLE '.$tableName.' (`id` INT( '.$model['data_length'].' ) UNSIGNED NOT NULL AUTO_INCREMENT,';
            // 属性列表
            if(!empty($model['attribute_list'])) {
                $map['status'] = 1;
                $map['id']   = array('IN',$model['attribute_list']);
                $list   =  M("Attribute")->where($map)->field('name,title,field,extra,type')->order('sort')->select();
                unset($map);
                foreach($list as $attr){
                    if($attr['type']=='complex') {// 组合字段
                        $map2['name']= array('IN',$attr['extra']);
                        $list2   =  M("Attribute")->where($map2)->field('name,title,field,extra,type')->select();
                        foreach ($list2 as $attr2){
                            $sql   .= '`'.$attr2['name'].'`'.($attr2['field']?$attr2['field']:($type[$attr2['type']]?$type[$attr2['type']]:'varchar(255) DEFAULT NULL')).' COMMENT \''.$attr2['title'].'\',';
                        }
                    }elseif($attr['type'] != 'zone') {
                        $sql   .= '`'.$attr['name'].'`'.($attr['field']?$attr['field']:($type[$attr['type']]?$type[$attr['type']]:'varchar(255) DEFAULT NULL')).' COMMENT \''.$attr['title'].'\',';
                    }
                }
            }
            if($model['support_cate']) {
                $sql    .=  '`cate_id` mediumint(5) unsigned default 0 COMMENT \'分类ID\',';
            }
            if($model['support_level']) {
                $sql   .=   '`pid` mediumint(6) unsigned default 0 COMMENT \'父ID\',';
                $sql   .=  '`level` smallint(2) unsigned default 0 COMMENT \'层次\',';
            }
            if($model['support_link']) {
                $sql    .=  '`link_id` varchar(25) default NULL COMMENT \'映射ID\',';
            }
            if($model['support_sort']) {
                $sql    .=  '`sort` mediumint(5) unsigned default 0 COMMENT \'排序\',';
            }
            if($model['support_flow']) {
                $sql    .=  '`flow_id` smallint(3) unsigned default 0 COMMENT \'流程ID\',';
                $sql    .=  '`op_type` tinyint(1) unsigned default 0 COMMENT \'操作员类型\',';
                $sql    .=  '`op_id` mediumint(6) unsigned default 0 COMMENT \'操作员ID\',';
            }
            if($model['allow_as_sub'] && empty($model['foreign_key'])) {
                $sql    .=  '`record_id` varchar(50) default NULL COMMENT \'所属记录ID\',';
            }
            if($model['support_tags']) {
                $sql    .=  '`tags` varchar(100) default NULL COMMENT \'标签\',';
            }
            if($model['support_status']) {
                $sql    .=  '`status` tinyint(1) unsigned default 0 COMMENT \'状态\',';
            }    
            if($model['support_time']) {
                $sql    .=  '`create_time` int(10) unsigned DEFAULT 0 COMMENT \'创建时间\',
                        `update_time` int(10) unsigned DEFAULT 0 COMMENT \'更新时间\',';
            }
            // 公共部分
            $sql  .=    ' PRIMARY KEY (`id`)';
            if($model['support_status']) {
                $sql .= ',KEY `status` (`status`)';
            }
            $sql  .=    ') ENGINE='.$engine.' DEFAULT CHARSET=utf8;';
            $commond[$model['name']]   = $sql;
        }
        foreach ($commond as $key=>$sql){
            $result   =  model($model['name'])->execute($sql);
            if(false === $result) {
                $this->error('模型表'.$key.'创建失败或者已经创建！');
            }
        }
        $this->success('模型表创建成功！');
    }

    // 根据数据表自动创建模型
    public function autoBuild(){
        // 获取数据库列表
        $this->getDbList();

        // 获取当前数据库
        $dbName   =  $this->getUseDb();

        // 获取当前库的数据表
        $tables   = $this->db->getTables($dbName);
        $this->tables   =  $tables;
        $this->display('autoBuild');
        return ;        
    }

    /**
     +----------------------------------------------------------
     * 获取当前操作的数据库
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    protected function getUseDb() {
        if(isset($_GET['dbName'])){
            $dbName   =  $_GET['dbName'];
            Session('useDb',$dbName);
        }elseif(Session('useDb')) {
            $dbName   =  Session('useDb');
        }else{
            $dbName   =  '';
        }
        return $dbName;
    }

    /**
     +----------------------------------------------------------
     * Ajax方式获取数据库的表列表
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function getTables() {
        if($_POST['app_id']) {
            $app =   M('App')->field('db_name,connection')->find($_POST['app_id']);
            $tables =   $this->db->switchConnect($app['connection'])->getTables($app['db_name']);
        }else{
            $dbName   =  $_POST['db'];
            Session('useDb',$dbName);
            // 获取数据库的表列表
            $tables   = $this->db->getTables($dbName);
        }

        $this->ajaxReturn(array('data'=>$tables,'info'=>'数据表获取完成','status'=>1));
    }

    // 创建模型
    public function createModel(){
        if(empty($_POST['tableName'])) {
            $this->error('没有选择数据表！');
        }
        $tables   =   $_POST['tableName'];
        $dbName    =  $_POST['dbName'];
        $sql  =   'SHOW FULL COLUMNS FROM '.(C('DB_NAME') != $dbName?$dbName.'.':'');
        foreach ($tables as $table) {
            // 生成模型数据
            $pos =   strpos($table,'_');
            $data['name'] = $pos?parse_name(substr($table,$pos+1)):$table;
            $data['title']  =  $data['name'];
            $data['db_name'] =  $dbName;
            $data['table_prefix'] =  $pos?substr($table,0,$pos+1):'';
            $data['create_time'] =  NOW_TIME;
            $data['status'] = 1;
            $data['is_show']  =  1;
            $id = M('Model')->add($data);
            if(!$id) { 
                $this->error('模型创建错误');
            }
            // 读取数据表信息 SELECT * FROM  INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'think_access' and TABLE_SCHEMA='BBS'
            $info =  $this->db->query($sql.$table);
            $data = array();
            $attrId  =   array();
            foreach ($info as $field){
                if(in_array($field['Field'],array('id','status','sort','cate_id','create_time','update_time','record_id','link_id'))) {
                    if('sort'==$field['Field']) {
                        M('Model')->where('id='.$id)->setField('support_sort',1);
                    }elseif('record_id'==$field['Field']){
                        M('Model')->where('id='.$id)->setField('allow_as_sub',1);
                    }elseif('cate_id'==$field['Field']) {
                        M('Model')->where('id='.$id)->setField('support_cate',1);
                    }elseif('link_id'==$field['Field']) {
                        M('Model')->where('id='.$id)->setField('support_link',1);
                    }elseif('status'==$field['Field']){
                        M('Model')->where('id='.$id)->setField('support_status',1);
                    }elseif('create_time'==$field['Field']){
                        M('Model')->where('id='.$id)->setField('support_time',1);
                    }elseif('pid'==$field['Field']){
                        M('Model')->where('id='.$id)->setField('support_level',1);
                    }
                    continue;
                }
                $data['name']  =  $field['Field'];
                $data['title']   =  $field['Comment']?$field['Comment']:$field['Field'];
                $data['type']  =   $this->getFieldType($field['Type']);
                $data['value']   =  'null'!=$field['Default']?$field['Default']:null;
                $data['field']   =  $field['Type'];
                $data['is_common']  =  0;
                $data['status'] = 1;
                if(in_array($field['Field'],array('sort','user_id'))) {
                    $data['is_show']  =  0;
                }else{
                    $data['is_show']  =  1;
                }
                $data['model_id'] =  $id;
                $attrId[] = M('Attribute')->add($data);
            }
            // 模型添加属性
            M('Model')->where('id='.$id)->setField('attribute_list',implode(',',$attrId));
        }
        $this->success('模型创建成功',__URL__);
    }

    // 把数据字段的类型转换成系统的属性类型
    protected function getFieldType($type) {
        if(false !== strpos($type,'int')) {
            $type = 'num';
        }elseif(false !== strpos($type,'longtext')){
            $type = 'editor';
        }elseif(false !== strpos($type,'text')){
            $type = 'text';
        }else{
            $type = 'string';
        }
        return $type;
    }

    /**
     +----------------------------------------------------------
     * 获取数据库列表
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    protected function getDbList() {
        if(!$dbs   =  Session('_databaseList_')) {
            $dbs =$this->db->query('show databases');
            Session('_databaseList_',$dbs);
        }
        $this->dbs  =   $dbs;
    }

    public function treeSelect(){
        C('SHOW_RUN_TIME',false);			// 运行时间显示
        C('SHOW_PAGE_TRACE',false);
        $this->display();
    }
}