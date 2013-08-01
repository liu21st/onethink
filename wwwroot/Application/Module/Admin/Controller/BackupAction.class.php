<?php
class BackupAction extends CommonAction{
	//备份确认
	function index() {
		//读取备份文件目录
		$dir = './'.C('BACKUP_FILE_PATH');
		if (!is_dir ( $dir )) {
			mkdir ( $dir );
		}
        import('ORG.Io.Dir');
        $dirs	=	new Dir($dir);
        foreach ($dirs as $key=>$file){
            $file['id'] =  ++$key;
            $list[] = $file;
        }

        import("ORG.Util.Page");
        if(!empty($_REQUEST['listRows'])) {
            $listRows  =  $_REQUEST['listRows'];
        }else {
            $listRows  =  '';
        }
        $p          = new Page(count($list),$listRows);
        $list	=	array_splice($list,$p->firstRow,$p->listRows);
        $page       = $p->show();
        $this->assign("page",$page);

		$this->assign ( "list", $list );
		$this->display ();
	}

	//删除备份文件
	function delete() {
        $dir = './'.C('BACKUP_FILE_PATH');
		$file = $_GET['id'];
        if(strpos($file,',')) {
            $list   = explode(',',$file);
            foreach ($list as $file)
                unlink ( $dir . "/" . $file );
        }else{
    		unlink ( $dir . "/" . $file );
        }
		$this->assign ( "jumpUrl", __URL__  );
		$this->success ( "删除成功！" );
	}

	function backup() {
		import ( "Db" ); //D('');也可以
        $db   =  Db::getInstance();
        if(!C('BACKUP_TABLE_LIST')) {
            // 默认导出所有表
            $tables =$db->getTables(C('DB_NAME'));
        }else{
            // 导出指定表
            $tables  =  explode(',',C('BACKUP_TABLE_LIST'));
        }
        // 组装导出SQL
        $sql  = "-- ThinkPHP SQL Dump\n-- http://www.thinkphp.cn\n\n";
        foreach($tables as $key=>$table) {
            $sql  .= "-- \n-- 表的结构 `$table`\n-- \n";
            $info  = $db->query("SHOW CREATE TABLE  $table");
            $sql   .= $info[0]['Create Table'];
            $sql  .= ";\n-- \n-- 导出表中的数据 `$table`\n--\n";
            $result  =$db->query("SELECT * FROM $table ");
            foreach($result as $key=>$val) {
                foreach ($val as $k=>$field){
                     if(is_string($field)) {
                        $val[$k] = $db->escapeString($field);
                    }elseif(empty($field)){
                        $val[$k] =  'NULL';
                    }
                }
            	$sql  .= "INSERT INTO `$table` VALUES (".implode(',',$val).");\n";
            }
        }
        $dir = './'.C('BACKUP_FILE_PATH');
		if (!is_dir ( $path )) {
			mkdir ( $path );
		}
        $filename   =  date ( 'Ymd_His' );
        $zip = new ZipArchive();
        if ($zip->open($dir . "/" . $filename.'.zip', ZIPARCHIVE::CREATE)!==TRUE) {
            exit("cannot open <$filename>\n");
        }
        $zip->addFromString($filename.'.sql',trim($sql));
        $zip->close();
		$this->assign ( "jumpUrl", __URL__  );
		$this->success ( "备份成功！" );
	}

    public function download() {
        $file   = $_GET['file'];
        import('ORG.Net.Http');
        Http::download('./'.C('BACKUP_FILE_PATH').'/'.$file);
    }
}
?>