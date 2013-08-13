<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// 文件管理
class FileAction extends CommonAction{

	// 列出当前目录所有的文件
	function index() {
		if(!empty($_GET['f'])) {
			// 获取当前文件名
			$file =  urlsafe_b64decode($_GET['f']);
			if(is_dir($file)) {
				$this->_readDir($file);
			}elseif(is_file($file)){
				$this->_readFile($file);
			}else{
				$this->error('文件或者目录不存在！');
			}
		}else{
			// 默认列出网站根目录
			$this->_readDir(realpath(APP_PATH));
		}
		$this->display();
	}

	// 读取目录
	function _readDir($dirname) {
		import("ORG.Io.Dir");
		if(!empty($_POST['keywords'])) {
			$pattern	 =	 $_POST['keywords'];
			if(false === preg_match('/(*)/i',$pattern)) {
				// 默认在当前目录匹配
				$pattern	 =	 '*'.$pattern.'*';
			}
			$this->assign("search",true);// 处于搜索模式
		}else{
			$pattern	 =	 '*';
		}
		// 读取目录
		$dir	=	new Dir($dirname,$pattern);
		$list	=	array();
		foreach ($dir as $key=>$file){
			$file['id']	=	base64_encode($file['pathname']);
			$list[]	=	$file;
		}
        //排序字段
        if(isset($_REQUEST['order'])) {
            $order = $_REQUEST['order'];
        }else{
            $order = 'isDir';
        }

		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		$sort = $_REQUEST['sort']?'asc':'desc';
		$list	=	sort_by($list,$order,$sort);
		//列表排序显示
		$sortImg    = $sort ;                                   //排序图标
		$sortAlt    = $sort == 'desc'?'升序排列':'倒序排列';    //排序提示
		$sort       = $sort == 'desc'? 1:0;                     //排序方式
		$this->assign('sort',       $sort);
		$this->assign('order',      $order);
		$this->assign('sortImg',    $sortImg);
		$this->assign('sortType',   $sortAlt);

		if(empty($_POST['keywords'])) {
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
		}
		if(substr($dirname, -1) != "/" && substr($dirname, -1) != "\\" )    $dirname .= "/";
		$_SESSION['_currentDir']	=	$dirname;
		if(isset($_GET['showType'])) {
			$showType = $_GET['showType'];
			$this->assign("showType",$showType);
		}
		$this->assign("diskFreeSpace",disk_free_space($dirname));
		$this->assign("diskTotalSpace",disk_total_space($dirname));
		$this->assign("type",'dir');
		$this->assign("filename",base64_encode($dirname));
		$this->assign("truename",auto_charset($dirname,'gbk','utf-8'));
		$this->assign("list",$list);
	}

	// 返回上级目录
    function upDir()
    {
    	$path = $_SESSION['_currentDir'];
		if(!isset($_GET['showType'])) {
			redirect(__URL__.'/index/f/'.base64_encode(dirname($path)));
		}else{
			redirect(__URL__."/index/showType/{$_GET['showType']}/f/".base64_encode(dirname($path)));
		}
    }

	// 读取文件
	function _readFile($filename,$download=false) {
		// 判断文件类型
		$ext = strtolower(substr(strrchr($filename, '.'),1));
        if($download || !preg_match('/(php|asp|htm|js|css|ini|xml|log|as|jsp|txt)/i',$ext)) {
            // 不可直接查看的文件类型直接下载
            import("ORG.Net.Http");
            Http::download($filename);
            exit;
		}else{
            $content	=	file_get_contents($filename);
            if(!seems_utf8($content)) {
                // 自动判断文件编码
                $content	=	auto_charset($content,'gbk','utf-8');
            }
            $this->assign("content",$content);
            $this->assign("filename",base64_encode($filename));
            $this->assign("truename",auto_charset($filename,'gbk','utf-8'));
            $this->assign("type",'file');
            $this->assign("readable",is_readable($filename));
            $this->assign("writable",is_writable($filename));
            $_SESSION['_currentDir']	=	$filename;
        }
	}

	function download() {
		$filename	=	urlsafe_b64decode($_GET['f']);
		$this->_readFile($filename,true);

	}
	// 保存文件
	function saveFile() {
    	$filename  = $_REQUEST['filename'];
        $file  = base64_decode($filename);
        $content  = $_REQUEST['content'];
		if(MAGIC_QUOTES_GPC) {
			$content	 =	 stripslashes($content);
		}
        $result  =  file_put_contents($file,$content);
        if(false !== $result) {
        	$this->success('文件保存成功！');
        }else {
        	$this->error('文件保存失败！');
        }
	}

	// 复制文件或者目录
	function copyFile() {
		$id	=	$_POST['id'];
		$_SESSION['copyFileName']	 =	 explode(',',$id);
		$this->success('文件已经复制，请进入目标目录进行粘贴操作');
	}

	// 剪切文件或者目录
	function cutFile() {
		$id	=	$_POST['id'];
		$_SESSION['cutFileName']	=		explode(',',$id);
		$this->success('文件已经剪切，请进入目标目录进行粘贴操作');
	}

	function zipFile() {

	}

	// 粘贴文件或者目录
	function pasteFile() {
		if(isset($_SESSION['copyFileName'])) {
			// 复制文件
			$files	=	$_SESSION['copyFileName'];
			$toPath	=	base64_decode($_REQUEST['path']);
			import("ORG.Io.Dir");
			$list	=	array();
			foreach ($files as $key=>$file){
				$filename	=	base64_decode($file);
				$newname	 =	 $toPath.basename($filename);
				if(is_dir($filename)) {
					Dir::copyDir($filename,$newname);
				}else{
					// 拷贝文件
					copy($filename,$newname);
				}
				$list[$key]	=	array();
				$list[$key]['id']	=	base64_encode($newname);
				$list[$key]['name']	=	readFileList($newname);
				$list[$key]['ctime']	=	toDate(filectime($newname),'m-d H:i:s');
				$list[$key]['mtime']	=	toDate(filemtime($newname),'m-d H:i:s');
				$list[$key]['size']	=	byte_format(filesize($newname));
				$list[$key]['extension']	=	showExt(extension($newname),0);
				$list[$key]['isReadable']	=	getRecommend(is_readable($newname));
				$list[$key]['isWritable']	=	getRecommend(is_writable($newname));
			}
			// 复制成功清除复制ID
			unset($_SESSION['copyFileName']);
			clearstatcache();
			$this->ajaxReturn($list,'粘贴成功！',1);
		}
		if (isset($_SESSION['cutFileName'])){
			// 剪切文件
			$files	=	$_SESSION['cutFileName'];
			$toPath	=	base64_decode($_REQUEST['path']);
			import("ORG.Io.Dir");
			$list	=	array();
			foreach ($files as $key=>$file){
				$filename	=	base64_decode($file);
				$newname	 =	 $toPath.basename($filename);
				if(is_dir($filename)) {
					Dir::copyDir($filename,$newname);
					Dir::delDir($filename);
				}else{
					// 拷贝文件
					rename($filename,$newname);
				}
				$list[$key]	=	array();
				$list[$key]['id']	=	base64_encode($newname);
				$list[$key]['name']	=	readFileList($newname);
				$list[$key]['ctime']	=	toDate(filectime($newname),'m-d H:i:s');
				$list[$key]['mtime']	=	toDate(filemtime($newname),'m-d H:i:s');
				$list[$key]['size']	=	byte_format(filesize($newname));
				$list[$key]['extension']	=	showExt(extension($newname),0);
				$list[$key]['isReadable']	=	getRecommend(is_readable($newname));
				$list[$key]['isWritable']	=	getRecommend(is_writable($newname));
			}
			// 剪切成功清除剪切ID
			unset($_SESSION['cutFileName']);
			clearstatcache();
			$this->ajaxReturn($list,'粘贴成功！',1);
		}
		$this->error('没有复制或者剪切操作！');
	}

	// 新建目录或者文件
	function create() {
		$filename = auto_charset($_POST['name'],'utf-8','gbk');
		$path	=	auto_charset($_POST['home'],'utf-8','gbk');
		$type	 =	 $_POST['type'];
		if($type=='dir') {
			$result	=	mkdir($path.$filename);
			$info	 =	 '目录';
		}else{
			$result	=	touch($path.$filename);
			$info	 =	 '文件';
		}
		if($result) {
			$this->success($info.'创建成功！');
		}else{
			$this->error($info.'创建失败！');
		}
	}

	// 重命名目录或者文件
	function edit() {
		$filename = base64_decode($_GET['f']);
		if(is_file($filename)) {
			$this->assign("type",'file');
		}else{
			$this->assign("type",'dir');
		}
		$home	=	base64_encode(dirname($filename));
		$this->assign("filename",$filename);
		$this->assign("home",$home);
		$this->display();
	}

	// 重命名目录或者文件
	function update() {
		$dirname = $_POST['name'];
		$path	=	realpath(base64_decode($_POST['home'])).'/';
		$oldname	=	$_POST['oldname'];
		$result	=	rename($path.$oldname,$path.$dirname);
		if($result) {
			$this->success('重命名成功！');
		}else{
			$this->error('重命名失败！检查是否已经存在相同名称！');
		}
	}

	// 删除文件或者目录
	function delete(){
		$id	 =	 $_POST['id'];
		$files	 =	 explode(',',$id);
		foreach ($files as $file){
			$file	=	base64_decode($file);
			if(is_file($file)) {
				unlink($file);
			}elseif(is_dir($file)){
				import("ORG.Io.Dir");
				Dir::delDir($file);
			}
		}
		$this->success('删除成功！');
	}
}
?>