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

/**
 +------------------------------------------------------------------------------
 * 数据库管理
 +------------------------------------------------------------------------------
 * @package   core
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Ver$
 +------------------------------------------------------------------------------
 */
class DbController extends CommonController {//类定义开始

    protected $db =  NULL;

    /**
     +----------------------------------------------------------
     * 初始化操作
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function _initialize() {
        parent::_initialize();
        // 获取数据库对象实例
        $this->db   =  Db::getInstance();
    }

    /**
     +----------------------------------------------------------
     * 数据库管理首页
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function index() {
        // 获取数据库列表
        $this->getDbList();

        // 获取当前数据库
        $dbName   =  $this->getUseDb();

        // 获取当前库的数据表
        $tables   = $this->db->getTables($dbName);
        $this->tables   =  $tables;
        $this->display();
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
            session('useDb',$dbName);
            // 获取数据库的表列表
            $tables   = $this->db->getTables($dbName);
        }
        $data['data']   =   $tables;
        $data['info']   =   '数据表获取完成';
        $data['status'] =   1;
        $this->ajaxReturn($data);
    }

    /**
     +----------------------------------------------------------
     * 复制数据表
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function copyTable() {
        // 获取数据库列表
        $this->getDbList();
        // 获取当前数据库
        $dbName   =  $this->getUseDb();
        $this->display();
    }

    /**
     +----------------------------------------------------------
     * 创建新的数据表
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function createTable() {
        $tableName = $_POST['tableName'];
        $dbName    = $_POST['dbName'];
        $sourceTable = $_POST['sourceTable'];
        $sourceDb  =  Session('useDb');
        $info  = $this->db->query("SHOW CREATE TABLE {$sourceDb}.`$sourceTable`");
        $sql   = $info[0]['Create Table'];
        $sql   = preg_replace('/CREATE TABLE\s`'.$sourceTable.'`/is','CREATE TABLE `'.$tableName.'`',$sql);
        // 开始复制
       $this->db->execute('USE '.$dbName);
        $result   = $this->db->execute($sql);
        if(false !== $result) {
            if(1 == $_POST['option']) {
                // 复制表数据
                $sql   = "INSERT INTO `{$dbName}`.`{$tableName}` SELECT * FROM `{$sourceDb}`.`{$sourceTable}` ;";
                $result   = $this->db->execute($sql);
                if(false === $result) {
                    $this->error('数据复制错误！');
                }
            }
            $this->success('数据表复制成功！');
        }else{
            $this->error('复制错误！');
        }
    }

    /**
     +----------------------------------------------------------
     * 移动数据表
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function moveTable() {
        // 获取数据库列表
        $this->getDbList();
        $this->display();
    }

    /**
     +----------------------------------------------------------
     * 移动数据表
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function transfTable() {
        $tableName = $_POST['tableName'];
        $dbName    = $_POST['dbName'];
        $sourceTable = $_POST['sourceTable'];
        $sourceDb  =  Session('useDb');
        $info  = $this->db->query("SHOW CREATE TABLE {$sourceDb}.`$sourceTable`");
        $sql   = $info[0]['Create Table'];
        $sql   = preg_replace('/CREATE TABLE\s`'.$sourceTable.'`/is','CREATE TABLE `'.$tableName.'`',$sql);
        // 创建新表
       $this->db->execute('USE '.$dbName);
        $result   = $this->db->execute($sql);
        if(false !== $result) {
            $sql   = "INSERT INTO `{$dbName}`.`{$tableName}` SELECT * FROM `{$sourceDb}`.`{$sourceTable}` ;";
            $result   = $this->db->execute($sql);
            // 删除原来表
           $this->db->execute('USE '.Session('useDb'));
            $result   = $this->db->execute('DROP TABLE `'.$sourceTable.'`');
            if(false === $result) {
                $this->error('当前表删除失败！');
            }
            $this->success('数据表移动成功！');
        }else{
            $this->error('移动错误！');
        }
    }

    /**
     +----------------------------------------------------------
     * 显示数据表结构
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function showTable() {
        $table = $_GET['table'];
        $this->db->execute('USE '.Session('useDb'));
        $list   = $this->db->query("SHOW FULL COLUMNS FROM $table");
        $json = array();
        foreach ($list as $key=>$val){
            unset($val['Privileges']);
            $attribute   =  explode(' ',$val['Type']);
            $type = explode('(',$attribute[0]);
            $val['Type'] = strtoupper($type[0]);
            if(isset($type[1])) {
                $val['Length']  =  substr($type[1],0,-1);
            }else{
                $val['Length']  =  '';
            }
            $val['Sign']  = isset($attribute[1])?strtoupper($attribute[1]):'';
            if(is_null($val['Default'])) {
                $val['Default']  =  'NULL';
            }
            $list[$key]  =  $val;
            $json[$val['Field']] = $val;
        }
        $this->assign('json',json_encode($json));
        $this->assign('list',$list);
        // 获取索引列表
        $list   =  $this->db->query('SHOW INDEX FROM '.$table);
        foreach ($list as $key=>$val){
            if($val['Seq_in_index']==1) { // 记录索引位
                $i  = $key;
                $val['Non_unique']  = $val['Non_unique']==0?'是':'否';
                $val['Packed']  = is_null($val['Packed'])?'否':$val['Packed'];
                $val['Cardinality']  =  is_null($val['Cardinality'])?0:$val['Cardinality'];
                $list[$key]  =  $val;
            }else{
                $list[$i]['Column_name']  =  $list[$i]['Column_name'].','.$val['Column_name'];
                unset($list[$key]);
            }
        }
        $this->indexList   =  $list;
        $this->display('showTable');
    }

    public function dealField(){
        $fields =  explode(',',$_POST['field']);
        $sql   = 'ALTER TABLE `'.$_POST['table'].'` ';
        $temp   =  array();
        switch($_POST['type']) {
        case 'del': // 删除字段
            foreach($fields as $field){
                $temp[] = 'DROP `'.$field.'`';
            }
            $sql   .= implode(',',$temp);
            break;
        case 'unique':// 唯一
            foreach($fields as $field){
                $temp[] = '`'.$field.'`';
            }
            $sql   .= 'ADD UNIQUE ('.implode(',',$temp).')';
            break;
        case 'index':// 索引
            foreach($fields as $field){
                $temp[] = '`'.$field.'`';
            }
            $sql   .= 'ADD INDEX ('.implode(',',$temp).')';
            break;
        case 'fultext':// 全文索引
            foreach($fields as $field){
                $temp[] = '`'.$field.'`';
            }
            $sql   .= 'ADD FULLTEXT ('.implode(',',$temp).')';
            break;
        case 'drop':// 删除索引和主键
            if($fields[0]=='PRIMARY') {
                $sql   .= 'DROP PRIMARY KEY ';
            }else{
                $sql   .= 'DROP INDEX '.$fields[0];
            }
            break;            
        default:
            $this->error('非法操作');
        }
        $sql   .= ';';
        $this->db->execute('USE '.Session('useDb'));
        $result   =  $this->db->execute($sql);
        if(false !== $result) {
            $this->success('操作成功！');
        }else{
            $this->error('操作失败！'.$sql);
        }
    }

    public function addField() {
        $table = $_POST['table'];
        $len	=	count($_POST['name']);
        $sql   = "ALTER TABLE `{$table}` ";
        for($i=0;$i<$len;$i++) {
            if(!empty($_POST['name'][$i])) {
                $field	=	$_POST['name'][$i];
                $type	 =	 $_POST['type'][$i];
                $length	=	$_POST['length'][$i];
                $attribute	=	$_POST['attribute'][$i];
                $null		=	$_POST['null'][$i];
                $default	=	$_POST['default'][$i];
                $autoinc	=	$_POST['autoinc'][$i];
                $comment = $_POST['comment'][$i];
                $after = $_POST['after'][$i];
                $sql	.= " ADD `{$field}` {$type}";
                if(!empty($length)) {
                    $sql	.= "( {$length} )";
                }
                if(!empty($attribute)) {
                    $sql	.= " {$attribute} ";
                }
                $sql	.= " {$null} ";
                if('NULL' == $default) {
                    $sql	.=	 " DEFAULT NULL ";
                }elseif('' != $default) {
                    $sql	.=	 " DEFAULT '{$default}' ";
                }
                if(!empty($autoinc)) {
                    $sql	.= " {$autoinc} ";
                }
                if(!empty($comment)) {
                    $sql	.= " COMMENT '{$comment}'";
                }
                if(!empty($after)) {
                    $sql   .= "AFTER `{$after}` ";
                }
                $sql	.= ',';
            }
        }
        $sql	=	substr($sql,0,-1);
        $this->db->execute('USE '.Session('useDb'));
        $result   =  $this->db->execute($sql);
        if(false !== $result) {
            $this->success('字段增加成功！');
        }else{
            $this->error('字段添加失败！');
        }
    }

    public function updateField() {
        $table = $_POST['table'];
        $sql   = "ALTER TABLE `{$table}` ";
        $len	=	count($_POST['name']);
        for($i=0;$i<$len;$i++) {
            if(!empty($_POST['name'][$i])) {
                $name	=	$_POST['change'][$i];
                $field	=	$_POST['name'][$i];
                $type	 =	 $_POST['type'][$i];
                $length	=	$_POST['length'][$i];
                $attribute	=	$_POST['attribute'][$i];
                $null		=	$_POST['null'][$i];
                $default	=	$_POST['default'][$i];
                $autoinc	=	$_POST['autoinc'][$i];
                $comment = $_POST['comment'][$i];
                $sql	.= " CHANGE `{$name}` `{$field}` {$type}";
                if(!empty($length)) {
                    $sql	.= "( {$length} )";
                }
                if(!empty($attribute)) {
                    $sql	.= " {$attribute} ";
                }
                $sql	.= " {$null} ";
                if('NULL' == $default) {
                    $sql	.=	 " DEFAULT NULL ";
                }elseif('' != $default) {
                    $sql	.=	 " DEFAULT '{$default}' ";
                }
                if(!empty($autoinc)) {
                    $sql	.= " {$autoinc} ";
                }
                if(!empty($comment)) {
                    $sql	.= " COMMENT '{$comment}'";
                }
                $sql	.= ',';
            }
        }
        $sql	=	substr($sql,0,-1);
        $this->db->execute('USE '.Session('useDb'));
        $result   =  $this->db->execute($sql);F('SQL',$sql);
        if(false !== $result) {
            $this->success('字段修改成功！');
        }else{
            $this->error('字段修改失败！');
        }
    }

    // 删除字段
    public function dropField() {
        $table = $_POST['table'];
        $name   = $_POST['name'];
        $sql   = "ALTER TABLE `{$table}` DROP `{$name}` ";
        $this->db->execute('USE '.Session('useDb'));
        $result   =  $this->db->execute($sql);
        if(false !== $result) {
            $this->success('字段删除成功！');
        }else{
            $this->error('字段删除失败！');
        }
    }

    // 添加主键字段
    public function addPrimary() {
        $table = $_POST['table'];
        $name   = $_POST['name'];
        $sql   = "ALTER TABLE `{$table}` DROP PRIMARY KEY,ADD PRIMARY KEY (`{$name}` )";
        $this->db->execute('USE '.Session('useDb'));
        $result   =  $this->db->execute($sql);
        if(false !== $result) {
            $this->success('主键设置成功！');
        }else{
            $this->error('主键设置失败！');
        }
    }

    // 添加唯一字段
    public function addUnique() {
        $table = $_POST['table'];
        $name   = $_POST['name'];
        $sql   = "ALTER TABLE `{$table}` ADD UNIQUE (`{$name}` )";
        $this->db->execute('USE '.Session('useDb'));
        $result   =  $this->db->execute($sql);
        if(false !== $result) {
            $this->success('设置成功！');
        }else{
            $this->error('设置失败！');
        }
    }
    // 添加字段索引
    public function addIndex() {
        $table = $_POST['table'];
        $name   = $_POST['name'];
        $sql   = "ALTER TABLE `{$table}` ADD INDEX (`{$name}` )";
        $this->db->execute('USE '.Session('useDb'));
        $result   =  $this->db->execute($sql);
        if(false !== $result) {
            $this->success('设置成功！');
        }else{
            $this->error('设置失败！');
        }
    }

    // 添加字段全文搜索
    public function addFulltext() {
        $table = $_POST['table'];
        $name   = $_POST['name'];
        $sql   = "ALTER TABLE `{$table}` ADD FULLTEXT (`{$name}` )";
        $this->db->execute('USE '.Session('useDb'));
        $result   =  $this->db->execute($sql);
        if(false !== $result) {
            $this->success('设置成功！');
        }else{
            $this->error('设置失败！');
        }
    }

    /**
     +----------------------------------------------------------
     * 修改数据表
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function editTable() {
        $table = $_GET['table'];
        $dbName   =  Session('useDb');
        $result   = $this->db->query('SHOW TABLE STATUS FROM '.$dbName.' WHERE Name="'.$table.'"');
        $vo   =  $result[0];
        $this->assign('vo',$vo);
        $this->display('editTable');
    }

    /**
     +----------------------------------------------------------
     * 更新数据表
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function updateTable() {
        $oldName   = $_POST['old_name'];
        $Name   = $_POST['Name'];
        if($oldName != $Name) { // 表名更改
            $result   = $this->db->execute("RENAME TABLE `$oldName` TO  $Name");
        }
        $Engine    =  $_POST['Engine'];
        $Comment  = $_POST['Comment'];
        $Charset = $_POST['Charset'];
        $Collation    = $_POST['Collation'];
        $result   = $this->db->execute("ALTER TABLE `$Name` COMMENT = '$Comment' ENGINE = $Engine DEFAULT CHARACTER SET $Charset COLLATE $Collation");
        if(false === $result) {
            $this->error('更新错误！');
        }else{
            $this->success('更新表成功！');
        }
    }

    /**
     +----------------------------------------------------------
     * 高级模式数据库管理
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function adv() {
        // 获取数据库列表
        $this->getDbList();
        // 获取当前数据库
        $dbName   =  $this->getUseDb();
        $result = $this->db->query('SHOW TABLE STATUS FROM '.$dbName);
        $this->assign('list',$result);
        $this->display();
    }

    /**
     +----------------------------------------------------------
     * 浏览数据表的数据 支持分页
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function selectTable() {
        $table = Session('useDb').'.'.$_GET['table'];
        $where  =  array();
        if($_REQUEST['map']) {
            $where['_string']  =  base64_decode($_REQUEST['map']);
        }
       //$this->db->execute('USE '.Session('useDb'));
        $fields =  $this->db->getFields($table);
        $field =  array_keys($fields);
        $field =  $field[0];
        $Model  =  new Model();
        $count      = $Model->table($table)->where($where)->count('*');
        if($_GET['bench']) {
           $this->db->execute('SET PROFILING=1;');
        }
        import("ORG.Util.Page");
        //创建分页对象
        if(!empty($_REQUEST['listRows'])) {
            $listRows  =  $_REQUEST['listRows'];
        }else {
            $listRows  =  '';
        }
        //排序字段 默认为主键名
        if(isset($_REQUEST['_order'])) {
            $order = $_REQUEST['_order'];
        }else {
            $order = !empty($sortBy)? $sortBy: $field;
        }
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        if(isset($_REQUEST['_sort'])) {
            $sort = $_REQUEST['_sort']?'asc':'desc';
        }else {
            $sort = $asc?'asc':'desc';
        }
        $p          = new Page($count,$listRows);
        //分页查询数据
        $voList     = $Model->table($table)->where($where)->field(!empty($_REQUEST['field'])?$_REQUEST['field']:'')->order($order.' '.$sort)->limit($p->firstRow.','.$p->listRows)->select();

        $fields = array_keys($voList[0]);
        $this->assign('fieldCount',count($fields)+2);
        $list[] = $fields;
        if($_GET['bench']) {
            $data   = $this->db->query('SHOW PROFILE');
            $fields = array_keys($data[0]);
            $a[] = $fields;
            foreach($data as $key=>$val) {
                $val  = array_values($val);
                $a[] = $val;
            }
            $this->assign('bench',$a);
        }
        //分页显示
        $page       = $p->show();
        //列表排序显示
        $sortImg    = $sort ;                                   //排序图标
        $sortAlt    = $sort == 'desc'?'升序排列':'倒序排列';    //排序提示
        $sort       = $sort == 'desc'? 1:0;                     //排序方式
        //模板赋值显示
        $this->assign('list',       array_merge($list,$voList));
        $this->assign('sort',       $sort);
        $this->assign('order',      $order);
        $this->assign('sortImg',    $sortImg);
        $this->assign('sortType',   $sortAlt);
        $this->assign("page",       $page);
        $this->display('table');
    }

    /**
     +----------------------------------------------------------
     * 导入文件
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function import() {
        // 获取数据库列表
        $this->getDbList();
        $this->assign('useDb',Session('useDb'));
        $this->display();
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
    public function importSql() {
        if(!empty($_FILES['sqlFile']['name'])) {
            // 判断文件后缀
            $pathinfo = pathinfo($_FILES['sqlFile']['name']);
            $ext  =   $pathinfo['extension'];
            if(!in_array($ext,array('sql','zip','rar','gz','txt'))) {
                $this->error('文件格式不符合！');
            }
            // 导入SQL文件
            if(in_array($ext,array('zip','rar','gz'))) {
                $zip = new ZipArchive();
                $zip->open($_FILES['sqlFile']['tmp_name']);
                $file   = $zip->statIndex(0);
                $zip->extractTo(C('TEMP_PATH').'_import');
                $sql   = file_get_contents(C('TEMP_PATH').'_import/'.$file['name']);
                unlink(C('TEMP_PATH').'_import/'.$file['name']);
            }else{
                $sql   = file_get_contents($_FILES['sqlFile']['tmp_name']);
            }
        }elseif(!empty($_POST['sql'])){
            $sql   = $_POST['sql'];
        }else{
            $this->error('选择要导入的文件');
        }
        $sql   = str_replace("\r\n", "\n", $sql);
        $sql   = auto_charset($sql,$_POST['charset'],'utf-8');
        unlink($_FILES['sqlFile']['tmp_name']);
        if(false === $this->patchExecute($sql)) {
            $this->error('导入错误！');
        }else{
            $this->success('导入完成！');
        }
    }

    // 批量执行SQL语句
    protected function patchExecute($querySql) {
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
        if(isset($_POST['dbName'])) {
            $dbName   =  $_POST['dbName'];
        }else{
            $dbName   =  Session('useDb');
        }
        $this->db->execute('USE '.$dbName);
        foreach($ret as $query) {
            if(!empty($query)) {
                $result   = $this->db->execute($query);
                if(false === $result) {
                    return false;
                }
            }
        }
        return $result;
    }

    public function output() {
        $tables =$this->db->getTables(Session('useDb'));
        $this->assign('tables',$tables);
        $this->display();
    }
    /**
     +----------------------------------------------------------
     * 导出SQL文件
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function outputData() {
        if(empty($_POST['table'])) {
            // 默认导出所有表
            $tables =$this->db->getTables(Session('useDb'));
        }else{
            // 导出指定表
            $tables  =  explode(',',$_POST['table']);
        }
       $this->db->execute('USE '.Session('useDb'));
        // 组装导出SQL
        $sql  = "-- ThinkPHP SQL Dump\n-- http://www.thinkphp.cn\n\n";
        foreach($tables as $key=>$table) {
            $sql  .= "-- \n-- 表的结构 `$table`\n-- \n";
            $info  = $this->db->query("SHOW CREATE TABLE  $table");
            $sql   .= $info[0]['Create Table'];
            $sql  .= ";\n-- \n-- 导出表中的数据 `$table`\n--\n";
            $result  =$this->db->query("SELECT * FROM $table ");
            foreach($result as $key=>$val) {
                foreach ($val as $k=>$field){
                     if(is_string($field)) {
                        $val[$k] = '\''.$this->db->escapeString($field).'\'';
                    }elseif(empty($field)){
                        $val[$k] =  'NULL';
                    }
                }
            	$sql  .= "INSERT INTO `$table` VALUES (".implode(',',$val).");\n";
            }
        }
        $filename   =  empty($_POST['table'])?Session('useDb'):$_POST['table'];
        import("ORG.Net.Http");
        if(empty($_POST['zip'])) {
            file_put_contents(C('TEMP_PATH').$filename.'.sql',trim($sql));
            Http::download (C('TEMP_PATH').$filename.'.sql');
        }else{
            $zip = new ZipArchive();
            if ($zip->open(C('TEMP_PATH').$filename.'.zip', ZIPARCHIVE::CREATE)!==TRUE) {
                exit("cannot open <$filename>\n");
            }
            $zip->addFromString($filename.'.sql',trim($sql));
            //$zip->addFile(TEMP_PATH.'thinkcms.sql',"ddd/test.sql");
            $zip->close();
            Http::download (C('TEMP_PATH').$filename.'.zip');
        }
        /*
        if(empty($_POST['zip'])) {
            import("ORG.Net.Http");
            Http::download (TEMP_PATH.$filename.'.sql');
        }else{
            import('Think.Util.Archive');
            $archive = new Archive($_POST['zip']);
            switch(strtolower($_POST['zip'])) {
                case 'gzip':$ext    = '.tar.gz';break;
                case 'bzip':$ext    = '.tar.bz2';break;
                case 'tar':$ext =  '.tar';break;
                case 'zip':
                default:$ext =  '.zip';
            }
            $archive->add(TEMP_PATH.$filename.'.sql',$filename.'.sql',TRUE);
            $archive->download($filename.$ext);
        }*/
    }

    /**
     +----------------------------------------------------------
     * 生成数据表
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function buildTable() {
        // 组装SQL
        $tableName			=	 $_POST['tableName'];
        $tableComment	=	$_POST['tableComment'];
        $tableType			=	$_POST['tableType'];
        $tableCharset		=	$_POST['tableCharset'];
        if(empty($tableName)) {
            $this->error('数据表名称必须！');
        }
        $sql			=	"CREATE TABLE `$tableName` (";
        $len					=	count($_POST['name']);
        for($i=0;$i<$len;$i++) {
            if(!empty($_POST['name'][$i])) {
                $field	=	$_POST['name'][$i];
                $type	 =	 $_POST['type'][$i];
                $length	=	$_POST['length'][$i];
                $attribute	=	$_POST['attribute'][$i];
                $null		=	$_POST['null'][$i];
                $default	=	$_POST['default'][$i];
                $autoinc	=	$_POST['autoinc'][$i];
                $comment = $_POST['comment'][$i];
                $sql	.= "`{$field}` {$type}";
                if(!empty($length)) {
                    $sql	.= "( {$length} )";
                }
                if(!empty($attribute)) {
                    $sql	.= " {$attribute} ";
                }
                $sql	.= " {$null} ";
                if('NULL' == $default) {
                    $sql	.=	 " DEFAULT NULL ";
                }elseif('' != $default) {
                    $sql	.=	 " DEFAULT '{$default}' ";
                }
                if(!empty($autoinc)) {
                    $sql	.= " {$autoinc} ";
                }
                if(!empty($comment)) {
                    $sql	.= " COMMENT '{$comment}'";
                }
                $sql	.= ',';
                $valid	 =	 true;
            }
        }
        if(empty($valid)) {
            $this->error('没有定义任何字段！');
        }
        for($i=0;$i<$len;$i++) {
            if(!empty($_POST['extra'][$i])) {
                $sql	.= "{$_POST['extra'][$i]} ( `{$_POST['name'][$i]}`) ,";
            }
        }
        $sql	=	substr($sql,0,-1);
        $sql	.= ") ENGINE = {$tableType} CHARACTER SET {$tableCharset}  COMMENT = '{$tableComment}' ";
       $this->db->execute('USE '.Session('useDb'));
        if(false !==$this->db->execute($sql)){
            $this->success('表创建成功');
        }else{
            $this->error('表创建错误！'.$this->db->getlastsql());
        }
    }

    /**
     +----------------------------------------------------------
     * 创建数据库
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function createDb() {
        $dbName   =  $_POST['dbName'];
        $charset    =  $_POST['charset'];
        $collation    =  $_POST['db_collation'];
        $result   = $this->db->execute('CREATE DATABASE `'.$dbName.'` DEFAULT CHARACTER SET '.$charset.' COLLATE '.$collation.';');
        if(false === $result ) {
            $this->error('创建失败！');
        }else{
            $this->success('创建成功！');
        }
    }

    // 编辑某条记录
    public function editData(){
        $id         = $_REQUEST['id'];
        $table     = Session('useDb').'.'.$_REQUEST['table'];
        $data = M()->table($table)->find($id);
        $_SESSION[$table.'_data_'.$id]   =  $data;
        // 查询字段类型
        $result   = M()->query('SHOW COLUMNS FROM '.$table);
        $list   =  array();
        foreach ($result as $val){
            if(strtolower($val['Key'])=='pri') { // 主键
                $this->pk   =  $val['Field'];
            }
            $vo['name'] =  $val['Field'];
            $vo['type']   =  $val['Type'];
            $vo['value']  =  $data[$vo['name']];
            if(false !== strpos($vo['type'],'int')) {
                list($vo['_type'],) = explode('(',$vo['type']);
            }elseif(false !== strpos($vo['type'],'char')){
                list($vo['_type'],) = explode('(',$vo['type']);
            }
            $list[] = $vo;
        }
        $this->list = $list;
        $this->display('editData');
    }

    // 更新某条记录
    public function updateData(){
        $id = $_POST['__pk__'];
        $table     = Session('useDb').'.'.$_POST['__table__'];
        unset($_POST['__pk__'],$_POST['__table__']);
        $data = array_diff_assoc($_POST,$_SESSION[$table.'_data_'.$id]);
        if(!empty($data)) {
            $result   =  M()->table($table)->where('id='.(int)$id)->save($data);
            if(false !== $result) {
                unset($_SESSION[$table.'_data_'.$id]);
                $this->success('更新成功');
            }else{
                $this->error('更新失败'.M()->getDbError());
            }
        }else{
            $this->error('无数据更新');
        }
    }

    /**
     +----------------------------------------------------------
     * 删除数据表中的某个记录
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function deleteData()
    {
        //删除指定记录
        $id         = $_REQUEST['id'];
        $table     = $_REQUEST['table'];
        if(isset($id)) {
            $condition['id'] = array('IN',$id);
            if(M()->table($table)->where($condition)->delete()){
                $this->success(L('删除成功'));
            }else {
                $this->error(L('删除失败'));
            }
        }else {
            $this->error('非法操作');
        }
    }

    /**
     +----------------------------------------------------------
     * 执行SQL语句
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function execute()
    {
        $sql  = trim($_REQUEST['sql']);
        if(empty($sql)) {
            $this->error('SQL不能为空！');
        }
        if(MAGIC_QUOTES_GPC) {
            $sql   = stripslashes($sql);
        }
       $this->db->execute('USE '.Session('useDb'));
        if(!empty($_POST['bench'])) {
           $this->db->execute('SET PROFILING=1;');
        }
        $startTime	=	microtime(TRUE);
        $queryIps = 'INSERT|UPDATE|DELETE|REPLACE|'
                . 'CREATE|DROP|'
                . 'LOAD DATA|SELECT .* INTO|COPY|'
                . 'ALTER|GRANT|TRUNCATE|REVOKE|'
                . 'LOCK|UNLOCK';
        if (preg_match('/^\s*"?(' . $queryIps . ')\s+/i', $sql)) {
            //$result=   $this->db->execute($sql);
            $result   =  $this->patchExecute($sql);
            $type = 'execute';
        }else {
            $result=   $this->db->query($sql);
            $type = 'query';
        }
        $runtime	 =	 number_format((microtime(TRUE) - $startTime), 6);
        if(false !== $result) {
            $array[] =  $runtime.'s';
            if(!empty($_POST['bench'])) {
                $data   = $this->db->query('SHOW PROFILE');
                $fields = array_keys($data[0]);
                $a[] = $fields;
                foreach($data as $key=>$val) {
                    $val  = array_values($val);
                    $a[] = $val;
                }
                $array[] =  $a;
            }else{
                $array[]  = '';
            }
            if($type == 'query') {
                if(empty($result)) {
                    $this->ajaxReturn(array('data'=>$array,'info'=>'SQL执行成功！','status'=>1));
                }
                $fields = array_keys($result[0]);
                $array[] = $fields;
                foreach($result as $key=>$val) {
                    $val  = array_values($val);
                    $array[] = $val;
                }
                if(count($array)>12) {
                    $array[] = $fields;
                }
                $this->ajaxReturn(array('data'=>$array,'info'=>'SQL执行成功！','status'=>1));
            }else {
                $this->ajaxReturn(array('data'=>$array,'info'=>'SQL执行成功！','status'=>1));
            }
        }else {
            $this->ajaxReturn(array('data'=>$this->db->getError(),'info'=>'SQL执行错误！','status'=>0));
        }
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
        $this->assign('dbs',$dbs);
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
        }elseif(Session('?useDb')) {
            $dbName   =  Session('useDb');
        }else{
            $dbName   =  C('DB_NAME');
            Session('useDb',$dbName);
        }
        $this->assign('useDb',$dbName);
        return $dbName;
    }

}//类定义结束
?>