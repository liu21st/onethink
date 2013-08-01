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

// 文档管理
class DocumentAction extends CommonAction{
    // 列出所有的文件
    public function index() {
        C('LIKE_MATCH_FIELDS','name|content');
        $Document	=	M("Document");
        // 当前文档目录
        if(empty($_GET['pid'])) {
            $savepath = ROOT_PATH.'Uploads';
            $truepath	=	'根目录';
        }else{
            $Document->getById($_GET['pid']);
            $savepath = $Document->savepath.$_GET['pid'];
            $truepath	=	$Document->name;
        }
        if(!empty($_GET['up'])) {
            $savepath = dirname($savepath).'/';
            $Document->getBy('savepath',dirname($savepath).'/');
            if(!isset($_GET['showType'])) {
                $this->redirect('index',array('pid'=>$Document->id));
            }else{
                $this->redirect("index",array('showType'=>$_GET['showType'],'pid'=>$Document->id));
            }
        }
        if(substr($savepath, -1) != "/")    $savepath .= "/";
        if(is_dir($savepath)) {
            // 缓存当前位置
            $_SESSION['currPath']	=	$savepath;
        }
        if(isset($_GET['showType'])) {
            // 列表方式
            $showType = $_GET['showType'];
            $this->assign("showType",$showType);
        }
        $_SESSION['currPathName']	=	$truepath;
        $this->assign("truepath",$truepath);
        //$map = $this->_search('Document');
        $map['module']	=	'Document';
        if(empty($_POST['search'])) {
            $map['savepath']	=	$savepath;
        }
        unset($map['pid']);
        if(empty($_GET['pid']) && !isset($_SESSION['administrator'])) {
            // 查看授权的目录
            $allowId = RBAC::getRecordAccessList();
            $map["id"]		=	array('in',$allowId);
        }
        $this->_list($Document,$map,'sort');
        $this->display();
    }

    protected function _upload_init($upload) {
        $upload->maxSize  = 6290220 ;
        $upload->allowExts  = array('png','gif','jpg','doc','txt','zip','rar','gz','mp3','wav');//explode(',',strtolower(C('TOPIC_UPLOAD_FILE_EXT')));//array('png','gif','jpg','doc','txt','zip','rar','gz','mp3','wav');
        $upload->savePath =  base64_encode($_SESSION['currPath']);
        return $upload;
    }

    // 查看文档
    public function read() {
        $id = $_GET['id'];
        $Document = M("Document");
        $Document->getById($id);
        if(file_exists($Document->savepath.$Document->savename)) {
            $filename	=	$Document->savepath.$Document->savename;
        }else{
            $filename	=	$_SESSION['currPath'].$id;
        }
        $content	=	file_get_contents($filename);
        $this->assign('title',$Document->name);
        $this->assign('content',$content);
        $this->display();
    }

    // 设置目录权限
    public function access() {
        // 读取用户组列表
        $Role	=	M("Role");
        $list	=	$Role->where("status=1")->select();
        foreach ($list as $vo){
            $groupList[$vo['id']] = $vo['name'];
        }
        $this->assign("groupList",$groupList);
        // 当前操作组
        $groupId =  $_GET['groupId'];
        if(!empty($groupId)) {
            $this->assign("selectGroupId",$groupId);
            //读取系统组的授权列表
            $access	=	M("Access");
            $list       =  $access->where("role_id=".$groupId." and module=File")->field('node_id')->select();
            foreach ($list as $vo){
                $selectList[$vo['node_id']] = $vo['node_id'];
            }
        }
        $Document	=	M("Document");
        $map['module,savepath'] = array('File',$_SESSION['currPath']);
        $list	=	$Document->where($map)->field("id,name")->select();
        foreach ($list as $vo){
            $fileList[$vo['id']] = $vo['name'];
        }
        $this->assign("fileList",$fileList);
        $this->assign("selectList",$selectList);
        $this->display();
    }

    // 保存记录的权限
    public function saveAccess() {
        $id     = $_POST['saveId'];
        $groupId	=	intval($_POST['groupId']);
        if(empty($groupId)) {
            $this->error('没有选择组！');
        }
        $access	=	D("Access");
        $access->where("module=File and role_id={$groupId}")->delete();
        $result	=	$access->setModuleAccessList('File',$groupId,$id);
        if($result) {
            $this->success('授权成功！');
        }else{
            $this->error('授权失败！');
        }
    }

    // 收藏列表
    public function favorite() {
        // 我的收藏
        $favorite	=	M("Favorite");
        if(!empty($_GET['pid'])) {
            $this->redirect('index',array('pid'=>$_GET['pid']));
        }
        $Document	=	M("Document");
        $map = $this->_search('Document');
        $recordId	=	$favorite->where("module=File and user_id=".$_SESSION[C('USER_AUTH_KEY')])->getField('record_id');
        if($recordId) {
            $map['id']   = array('in',$recordId);
        }else{
            $this->error('还没有任何收藏！');
        }
        $this->_list($Document,$map,'is_dir');
        $this->display();
    }

    // 回收站
    public function recycle() {
        import("ORG.Io.Dir");
        $dirname	=	ROOT_PATH.'Uploads/_del';
        $dir	=	new Dir($dirname);
        $list	=	array();
        foreach ($dir as $key=>$file){
            $file['id']	=	base64_encode($file['pathname']);
            $list[]	=	$file;
        }
        //排序字段 默认为主键名
        if(isset($_REQUEST['order'])) {
            $order = $_REQUEST['order'];
        }
        if(!empty($order)) {
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
        $this->assign("diskFreeSpace",disk_free_space($dirname));
        $this->assign("diskTotalSpace",disk_total_space($dirname));
        $this->assign("type",'dir');
        $this->assign("filename",base64_encode($dirname));
        $this->assign("truename",auto_charset($dirname,'gbk','utf-8'));
        $this->assign("list",$list);
        $this->display();

    }
    // 删除回收站的文档
    public function deleteRecycle() {
        $files =  explode(',',$_POST['id']);
        foreach ($files as $file){
            unlink(base64_decode($file));
        }
        $this->success('删除成功！');
    }

    // 下载查看回收站的文件
    public function readRecycleFile() {
        $file   = base64_decode($_GET['id']);
        import('ORG.Net.Http');
        Http::download($file);
    }

    // 清空回收站目录
    public function clear() {
        $filepath	=	ROOT_PATH.'Uploads/_del';
        import("ORG.Io.Dir");
        Dir::del($filepath);
        $this->success('回收站已经清空');
    }

    // 列表过滤
    public function _filter($map) {
        // 根据权限过滤显示
    }

    public function addFavorite() {
        $id	=	$_POST['id'];
        if(empty($id)) {
            $this->error('收藏失败！');
        }
        $Favorite	=	M("Favorite");
        $userId	=	$_SESSION[C('USER_AUTH_KEY')];
        $map['module']   =  'File';
        $map['user_id']   =  $userId;
        $vo	=	$Favorite->where($map)->field('id,record_id')->find();
        if($vo) {
            $Favorite->record_id	=	implode(',',array_unique(explode(',',$Favorite->record_id.','.$id)));
            $result	=	$Favorite->save();
        }else{
            $vo['user_id']	=	$userId;
            $vo['record_id']	=	$id;
            $vo['module']	 =	 'File';
            $result	=	$Favorite->add($vo);
        }
        if($result) {
            $this->success("收藏成功！");
        }else{
            $this->error("收藏失败！");
        }
    }

    // 拷贝文件
    public function copyFile() {
        $id	=	$_POST['id'];
        $_SESSION['copyFileId']		=	$id;
        $this->success('文档节点已经复制，请进入目标目录进行粘贴操作');
    }

    // 剪切文件
    public function cutFile() {
        $id	=	$_POST['id'];
        $_SESSION['cutFileId']	=	$id;
        $this->success('文档节点已经剪切，请进入目标目录进行粘贴操作');
    }

    // 粘贴文件
    public function pasteFile() {
        if(isset($_SESSION['copyFileId'])) {
            // 复制文件
            $id	=	$_SESSION['copyFileId'];
            $Document	=	M("Document");
            $map['module']   =  'File';
            $map['id']       = array('IN',$id);
            $list	=	$Document->field('savepath,extension,type,name,module,record_id,version,remark,size,create_time,savename')->where($map)->select();
            if($list) {
                foreach ($list as $key=>$attach){
                    if($attach['extension'] != 'dir') {
                        // 拷贝文件
                        $toPath = $_SESSION['currPath'];
                        if(copy($attach['savepath'].$attach['savename'],$toPath.$attach['savename'])) {
                            // 增加附件信息
                            $attach['savepath']	=	$toPath;
                            $attach['create_time'] = time();
                            $attach['version']	=	0;
                            $attach['user_id']	=	$_SESSION[C('USER_AUTH_KEY')];
                            $id = $Document->add($attach);
                            $attach['name']	=	showFileName($attach['name'],$id);
                            $attach['size']	=	byte_format($attach['size']);
                            $attach['create_time'] = toDate($attach['create_time'],'y-m-d H:i:s');
                            $attach['update_time']	=	'';
                            $attach['download_count'] = 0;
                            $attach['user_id'] = ($attach['user_id']);
                            $list[$key]	=	$attach;
                        }else{
                            $this->error('文件拷贝错误！');
                        }
                    }else{
                        // 拷贝目录
                    }
                }
                // 复制成功清除复制ID
                unset($_SESSION['copyFileId']);
                $this->ajaxReturn($list,'粘贴成功！',1);
            }else{
                $this->error('粘贴出错！文档没有找到');
            }
        }elseif (isset($_SESSION['cutFileId'])){
            // 剪切文件
            $id	=	$_SESSION['cutFileId'];
            $Document	=	M("Document");
            $map['module']   =  'File';
            $map['id']       = array('IN',$id);
            $list	=	$Document->where($map)->select();
            if($list) {
                foreach ($list as $key=>$attach){
                    if($file['extension'] != 'dir') {
                        // 拷贝文件
                        $toPath = $_SESSION['currPath'];
                        if(rename($attach['savepath'].$attach['savename'],$toPath.$attach['savename'])) {
                            // 更新附件信息
                            $Document->where("id=".$attach['id'])->setField('savepath',$toPath);
                            $attach['name']	=	showFileName($attach['name'],$attach['id']);
                            $attach['size']	=	byte_format($attach['size']);
                            $attach['create_time'] = toDate($attach['create_time'],'y-m-d H:i:s');
                            $attach['update_time'] = toDate($attach['update_time'],'y-m-d H:i:s');
                            $attach['user_id'] = ($attach['user_id']);
                            $list[$key]	=	$attach;
                        }else{
                            $this->error('文件已经存在或者移动错误！');
                        }
                    }else{
                        // 拷贝目录
                    }
                }
                // 剪切成功清除剪切ID
                unset($_SESSION['cutFileId']);
                $this->ajaxReturn($list,'粘贴成功！',1);
            }else{
                $this->error('粘贴出错！文档没有找到');
            }
        }else{
            $this->error('没有复制或者剪切操作！');
        }
    }

    // 上传附件
    public function add() {
        $savepath	=	$_SESSION['currPath'];
        $Document	=	M("Document");
        $path	=	$Document->where("savepath='".dirname($savepath)."/'")->find();
        $this->assign("pid",$path['id']);
        $this->display();
    }

    // 更新文件
    public function edit() {
        $id = $_GET['id'];
        $Document = M("Document");
        $vo	=	$Document->getById($id);
        $this->assign("vo",$vo);
        if($Document->extension != 'dir') {
            // 读取历史版本
            $list	=	glob($Document->savepath.'_version/'.$Document->id.'*');
            $this->assign("history",$list);
            $dir	=	$Document->where("savepath='".dirname($Document->savepath)."/'")->find();
            $this->assign("pid",$dir['id']);
        }
        $this->display();
    }

    // 查看文档历史
    public function history() {
        $id = $_GET['id'];
        $Document = M("Document");
        $vo = $Document->getById($id);
        $this->assign("vo",$vo);
        if($Document->extension != 'dir') {
            // 读取历史版本
            $list	=	glob($Document->savepath.'_version/'.$Document->id.'*');
            $this->assign("history",$list);
        }else{
            $this->error('无法查看目录的版本！');
        }
        $this->display();
    }

    // 文档排序
    public function sort()
    {
        $Document = M("Document");
        $sortList   =   $Document->where('savepath='.$_SESSION['currPath'])->order('sort asc')->select();
        $this->assign("sortList",$sortList);
        $this->display();
        return ;
    }

    public function saveSort()
    {
        $seqNoList  =   $_POST['seqNoList'];
        if(!empty($seqNoList)) {
            //更新数据对象
            $Document    = M("Document");;
            $col    =   explode(',',$seqNoList);
            //启动事务
            $Document->startTrans();
            foreach($col as $val) {
                $val    =   explode(':',$val);
                $Document->id	=	$val[0];
                $Document->sort	=	$val[1];
                $result =   $Document->save();
                if(!$result) {
                    break;
                }
            }
            //提交事务
            $Document->commit();
            if($result) {
                //采用普通方式跳转刷新页面
                $this->assign("jumpUrl",$this->getReturnUrl());
                $this->success('更新成功');
            }else {
                $this->error($Document->getError());
            }
        }
    }

    // 创建文档目录节点
    public function addDir() {
        $path = $_POST['name'];
        // 写入附件数据表
        $Document	=	M("Document");
        $Document->name	=	$path;
        $Document->extension	 =	 'dir';
        $Document->type     = 'dir';
        $Document->create_time	=	time();
        $Document->module	=	'Document';
        $Document->savepath	=	$_POST['pos'];
        $Document->savename	 =	 rawurlencode($path);
        $Document->remark	 =	 $_POST['remark'];
        $Document->user_id	=	$_SESSION[C('USER_AUTH_KEY')];
        $Document->is_dir	=	1;
        $Document->pid	=	basename($_SESSION['currPath']);
        $id	=	$Document->add();
        if(!$id) {
            $this->error('数据写入失败！');
        }
        $result	=	mkdir($_SESSION['currPath'].$id);
        if($result) {
            // 写入目录安全文件
            file_put_contents($_SESSION['currPath'].$id.'/index.htm','');
            $this->success('目录创建成功！');
        }else{
            $this->error('目录创建失败！');
        }
    }

    public function updateDir() {
        $Document	=	M("Document");
        $Document->create();
        $Document->update_time = time();
        $Document->version++;
        $result	=	$Document->save();
        if($result) {
            $this->success('目录更新成功！');
        }else{
            $this->error('目录更新失败！');
        }
    }

    // 新增文件
    public function addFile() {
        // 保存附件数据
        $Document = M("Document");
        $Document->name	=	$title;
        $Document->extension	 =	 'html';
        $Document->upload_time	=	time();
        $Document->module	=	'File';
        $Document->savepath	=	$_SESSION['currPath'];
        $Document->savename	 =	 rawurlencode($title);
        $Document->remark	 =	 $_POST['remark'];
        $Document->user_id	=	$_SESSION[C('USER_AUTH_KEY')];
        $Document->pid	=	basename($_SESSION['currPath']);
        if($Document->add()) {
            $id	=	$Document->getLastInsID();
            // 保存文本文件
            $filename	=	$_SESSION['currPath'].$id;
            file_put_contents($filename,$content);
            $this->success('新增成功！');
        }else{
            $this->error('新增失败！');
        }

    }
    // 删除附件
    public function delete()
    {
        //删除指定记录
        $Document        = M("Document");
        $pk	=	$Document->getPk();
        $id         = $_REQUEST[$pk];
        $list	=	$Document->where(array($pk=>array('in',$id)))->field('id,savename,savepath,extension')->select();
        if(!$list) {
            $this->error('删除失败，文件不存在！');
        }
        // 删除附件
        foreach ($list as $file){
            if(is_file($file['savepath'].$file['savename'])) {
                // 删除附件数据
                if($Document->deleteById($file['id'])) {
                    //删除的文件进入回收站
                    if(!file_exists(ROOT_PATH.'Uploads/_del')) {
                        mkdir(ROOT_PATH.'Uploads/_del');
                    }
                    $result	=	rename($file['savepath'].$file['savename'],ROOT_PATH.'Uploads/_del/'.md5($file['id'].$file['savepath'].$file['savename']).'.'.$file['extension']);
                    if(!$result) {
                        $this->error('文件删除失败！');
                    }
                }else{
                    $this->error('文件删除失败！');
                }
            }elseif('dir'==$file['extension']){
                // 搜索目录下面的文件
                $sublist	=	$Document->where("savepath=".$file['savepath'].$file['id']."/")->select();
                if($sublist) {
                    foreach ($sublist as $sub){
                        if($sub['extension'] != 'dir') {
                            if($Document->deleteById($sub['id'])) {
                                //删除的文件进入回收站
                                $result	=	rename($sub['savepath'].$sub['savename'],ROOT_PATH.'Uploads/_del/'.md5($sub['id'].$sub['savepath'].$sub['savename']).'.'.$sub['extension']);
                            }
                        }else{
                            $this->error('包含子目录，无法删除！');
                        }
                    }
                }
                if($Document->deleteById($file['id'])) {
                    // 删除目录安全文件
                    unlink($file['savepath'].$file['id'].'/index.htm');
                    // 最后删除空目录
                    rmdir($file['savepath'].$file['id']);
                }else{
                    $this->error('目录删除失败！');
                }
            }elseif('dir' !=$file['extension']){
                if(!$Document->deleteById($file['id'])) {
                    $this->error('文件删除失败！');
                }
            }
        }
        $this->success('文件删除成功！');
    }

    // 下载前置操作
    public function _before_download() {
        $Document	=	M("Document");
        $Document->setInc('download_count',"id=".$_GET['id']);
    }

    /**
     +----------------------------------------------------------
     * 文件上传功能，支持多文件上传、保存数据库、自动缩略图
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param string $module 附件保存的模块名称
     * @param integer $id 附件保存的模块记录号
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    protected function _upload($module='',$recordId='')
    {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        if(method_exists($this,'_upload_init')) {
            // 自定义上传规则
            $upload  = $this->_upload_init($upload);
        }else{
            // 采用默认的规则
            // 检查客户端上传文件参数设置
            if(isset($_POST['_uploadFileSize']) && is_numeric($_POST['_uploadFileSize'])) {
                //设置上传文件大小
                $upload->maxSize  = $_POST['_uploadFileSize'] ;
            }
            if(!empty($_POST['_uploadFileType'])) {
                //设置上传文件类型
                $upload->allowExts  = explode(',',strtolower($_POST['_uploadFileType']));
            }
            if(!empty($_POST['_uploadSavePath'])) {
                //设置附件上传目录
                $upload->savePath =  $_POST['_uploadSavePath'];
            }
            if(isset($_POST['_uploadSaveRule'])) {
                //设置附件命名规则
                $upload->saveRule =  $_POST['_uploadSaveRule'];
            }
            if(!empty($_POST['_uploadImgThumb'])) {
                //设置需要生成缩略图，仅对图像文件有效
                $upload->thumb =  $_POST['_uploadImgThumb'];
            }
            if(!empty($_POST['_uploadThumbPrefix'])) {
                //设置需要生成缩略图的文件前缀
                $upload->thumbPrefix =  $_POST['_uploadThumbPrefix'];
            }
            if(!empty($_POST['_uploadThumbFile'])) {
                //设置需要生成缩略图的文件名
                $upload->thumbFile =  $_POST['_uploadThumbFile'];
            }
            if(!empty($_POST['_uploadThumbMaxWidth'])) {
                //设置缩略图最大宽度
                $upload->thumbMaxWidth =  $_POST['_uploadThumbMaxWidth'];
            }
            if(!empty($_POST['_uploadThumbMaxHeight'])) {
                //设置缩略图最大高度
                $upload->thumbMaxHeight =  $_POST['_uploadThumbMaxHeight'];
            }
            if(!empty($_POST['_uploadThumbRemoveOrigin'])) {
                //设置是否需要删除原图
                $upload->thumbRemoveOrigin =  $_POST['_uploadThumbRemoveOrigin'];
            }
        }
        $uploadReplace =  false;
        if(isset($_POST['_uploadReplace']) && 1==$_POST['_uploadReplace']) {
            //设置附件是否覆盖
            $upload->uploadReplace =  true;
			$uploadReplace = true;
        }
		$uploadFileVersion = false;
        if(isset($_POST['_uploadFileVersion']) && 1==$_POST['_uploadFileVersion']) {
            //设置是否记录附件版本
            $uploadFileVersion =  true;
        }
        // 记录上传成功ID
        $uploadId =  array();
        $savename = array();
        //执行上传操作
        if(!$upload->upload()) {
            if($this->isAjax() && isset($_POST['_uploadFileResult'])) {
                $uploadSuccess =  false;
                $ajaxMsg  =  $upload->getErrorMsg();
            }else {
                //捕获上传异常
                $this->error($upload->getErrorMsg());
            }
        }else {
			 //取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();
            $remark	 =	 $_POST['remark'];
			//保存附件信息到数据库
            $Attach    = M('Document');
            //启动事务
            $Attach->startTrans();
            if(!empty($_POST['_uploadFileTable'])) {
                //设置附件关联数据表
                $module =  $_POST['_uploadFileTable'];
            }
            if(!empty($_POST['_uploadRecordId'])) {
                //设置附件关联记录ID
                $recordId =  $_POST['_uploadRecordId'];
            }
            if(!empty($_POST['_uploadFileId'])) {
                //设置附件记录ID
                $id =  $_POST['_uploadFileId'];
            }
            if(!empty($_POST['_uploadFileVerify'])) {
                //设置附件验证码
                $verify =  $_POST['_uploadFileVerify'];
            }
            if(!empty($_POST['_uploadUserId'])) {
                //设置附件上传用户ID
                $userId =  $_POST['_uploadUserId'];
            }else {
                $userId = isset($_SESSION[C('USER_AUTH_KEY')])?$_SESSION[C('USER_AUTH_KEY')]:0;
            }
			foreach($uploadList as $key=>$file) {
				$savename[] =  $file['savename'];
					// 附件数据需要保存到数据库
					//记录模块信息
                    unset($file['key']);
					$file['module']		=   $module;
					$file['record_id']	=   $recordId?$recordId:0;
					$file['user_id']		=   $userId;
					$file['verify']			=	$verify?$verify:'0';
					$file['remark']		=	 $remark[$key]?$remark[$key]:($remark?$remark:'');
					$file['status']		=	1;
					$file['create_time'] =   time();
                    if(empty($file['hash'])) {
                        unset($file['hash']);
                    }
					//保存附件信息到数据库
					if($uploadReplace ) {
						if(!empty($id)) {
							$vo  =  $Attach->getById($id);
						}else{
							$vo  =  $Attach->find(array('condition'=>"module=".$module."&record_id=".$recordId));
						}
						if(is_object($vo)) {
							$vo	=	get_object_vars($vo);
						}
						if(false !== $vo) {
							// 如果附件为覆盖方式 且已经存在记录，则进行替换
							$id	=	$vo[$Attach->getPk()];
							if($uploadFileVersion) {
								// 记录版本号
								$file['version']	 =	 $vo['version']+1;
								// 备份旧版本文件
								$oldfile	=	$vo['savepath'].$vo['savename'];
								if(is_file($oldfile)) {
									if(!file_exists(dirname($oldfile).'/_version/')) {
										mkdir(dirname($oldfile).'/_version/');
									}
									$bakfile	=	dirname($oldfile).'/_version/'.$id.'_'.$vo['version'].'_'.$vo['savename'];
									$result = rename($oldfile,$bakfile);
								}
							}
							// 覆盖模式
							$Attach->where("id=".$id)->save($file);
							$uploadId[]   = $id;

						}else {
							$uploadId[] = $Attach->add($file);
						}
					}else {
						//保存附件信息到数据库
						$uploadId[] =  $Attach->add($file);
					}
			}
            //提交事务
            $Attach->commit();
            $uploadSuccess =  true;
            $ajaxMsg  =  '';
        }

        // 判断是否有Ajax方式上传附件
        // 并且设置了结果显示Html元素
        if($this->isAjax() && isset($_POST['_uploadFileResult']) ) {
            // Ajax方式上传参数信息
            $info = Array();
            $info['success']  =  $uploadSuccess;
            $info['message']   = $ajaxMsg;
            //设置Ajax上传返回元素Id
            $info['uploadResult'] =  $_POST['_uploadFileResult'];
            if(isset($_POST['_uploadFormId'])) {
                //设置Ajax上传表单Id
                $info['uploadFormId'] =  $_POST['_uploadFormId'];
            }
            if(isset($_POST['_uploadResponse'])) {
                //设置Ajax上传响应方法名称
                $info['uploadResponse'] =  $_POST['_uploadResponse'];
            }
            if(!empty($uploadId)) {
                $info['uploadId'] = implode(',',$uploadId);
            }
            $info['savename']   = implode(',',$savename);
            $this->ajaxUploadResult($info);
        }
        return ;

    }

}
?>