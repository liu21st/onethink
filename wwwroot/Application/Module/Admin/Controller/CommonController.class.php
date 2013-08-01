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

class CommonController extends Action {

   /**
     * 获取当前Action名称
     * @access protected
     */
    protected function getActionName() {
        if(empty($this->name)) {
            // 获取Action名称
            $this->name     =   substr(get_class($this),0,-10);
        }
        return $this->name;
    }

    // 初始化
    public function _initialize() {
        
        // 用户访问权限检查
        if(!ThinkAcl::checkUrl()) {
            // 提示错误信息
            $this->error(L('_VALID_ACCESS_'));
        }
        // 后台初始化标签
        tag('admin_init');
        $config =   S('config');
        if(!$config) {
            // 生成配置缓存
            $list   =  M("Config")->getField('name,value');
            $config =   array_change_key_case($list,CASE_UPPER);
            S('config',$config);
        }
        C($config);
        if(!empty($_GET['forward'])) {
            // 设置返回URL
            Cookie('__forward__',base64_decode($_GET['forward']));
        }
        layout(true);
    }

    // 默认列表页
	public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if(method_exists($this,'_filter')) {
            $this->_filter($map);
        }
		$model        = M($this->getActionName());
        if(!empty($model)) {
        	$this->_list($model,$map);
        }
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
		$this->display();
        return;
    }

     // 根据表单生成查询条件和过滤
    protected function _search($name='') {
        //生成查询条件
        $name   = $name?$name:$this->getActionName();
		$model	=	M($name);
		$map	=	array();
        $fields =   $model->getDbFields();
        if($fields) {
            foreach($model->getDbFields() as $key=>$val) {
                if(substr($key,0,1)=='_') continue;
                if(isset($_REQUEST[$val]) && $_REQUEST[$val]!='') {
                    $map[$val]	=	$_REQUEST[$val];
                }
            }
        }
        return $map;
    }

    // 列表数据
    protected function _list($model,$map=array(),$sortBy='',$asc=false) {
        //排序字段 默认为主键名
        if(isset($_REQUEST['_order'])) {
            $order = $_REQUEST['_order'];
        }else {
            $order = !empty($sortBy)? $sortBy: $model->getPk();
        }
        //排序方式默认按照倒序排列
        //接受 sost参数 0 表示倒序 非0都 表示正序
        if(isset($_REQUEST['_sort'])) {
            $sort = $_REQUEST['_sort']?'asc':'desc';
        }else {
            $sort = $asc?'asc':'desc';
        }
        //取得满足条件的记录数
        $count      = $model->where($map)->count($model->getPk());
        import("ORG.Util.Page");
        //创建分页对象
        if(!empty($_REQUEST['listRows'])) {
            $listRows  =  $_REQUEST['listRows'];
        }else {
            $listRows  =  '';
        }
        $p          = new Page($count,$listRows);
        //分页查询数据
        $voList     = $model->where($map)->order($order.' '.$sort)->limit($p->firstRow.','.$p->listRows)->select();
        //分页跳转的时候保证查询条件
        foreach($map as $key=>$val) {
            if(!is_array($val)) {
                $p->parameter   .=   "$key=".urlencode($val)."&";
            }
        }

        //分页显示
        $page       = $p->show();
        //列表排序显示
        $sortImg    = $sort ;                                   //排序图标
        $sortAlt    = $sort == 'desc'?'升序排列':'倒序排列';    //排序提示
        $sort       = $sort == 'desc'? 1:0;                     //排序方式
        //模板赋值显示
        $this->count   =  $count;
        $this->assign('list',       $voList);
        $this->assign('sort',       $sort);
        $this->assign('order',      $order);
        $this->assign('sortImg',    $sortImg);
        $this->assign('sortType',   $sortAlt);
        $this->assign("page",       $page);
        Cookie('_currentUrl_',__SELF__);
        return ;
    }

    // 默认写入操作
    function insert() {
		$model	=	D($this->getActionName());
        if(false === $model->create()) {
        	$this->error($model->getError());
        }
        //保存当前数据对象
        if($result = $model->add()) { //保存成功
            //成功提示
            $this->success(L('新增成功'),Cookie('__forward__'));
        }else {
            //失败提示
            $this->error(L('新增失败'));
        }
    }

    // 默认新增操作
	public function add() {
		$this->display();
	}
    // 默认查看操作
	function read($id=0) {
		$this->edit($id);
	}

    // 默认编辑操作
	function edit($id=0) {
		$model	=	M($this->getActionName());
		$vo	=	$model->getById($id);
		$this->assign('vo',$vo);
		$this->display();
	}

    // 默认更新操作
	function update() {
		$model	=	D($this->getActionName());
        if(false === $model->create()) {
        	$this->error($model->getError());
        }
		// 更新数据
		if(false !== $model->save()) {
            //成功提示
            $this->success(L('更新成功'),Cookie('__forward__'));
        }else {
            //错误提示
            $this->error(L('更新失败'));
        }
	}

    // 默认列表选择操作
    protected function select($fields='id,name',$title='') {
        $map = $this->_search();
        //创建数据对象
        $Model = M($this->getActionName());
        //查找满足条件的列表数据
        $list     = $Model->where($map)->getField($fields);
		$this->assign('selectName',$title);
        $this->assign('list',$list);
        $this->display();
        return;
    }

    // 默认删除操作
    public function delete($id=0) {
        //删除指定记录
        $model        = M($this->getActionName());
        if(!empty($model)) {
            if(!empty($id)) {
                $condition = array('id'=>array('in',explode(',',$id)));
                if(false !== $model->where($condition)->delete()){
                    $this->success(L('删除成功'),$_SERVER['HTTP_REFERER']);
                }else {
                    $this->error(L('删除失败'));
                }
            }else {
                $this->error('非法操作');
            }
        }
    }

    // 通过审核
    public function pass() {
        //删除指定记录
        $model        = D($this->getActionName());
        if(!empty($model)) {
			$pk	=	$model->getPk();
            if(isset($_REQUEST[$pk])) {
                $id         = $_REQUEST[$pk];
                $condition = array($pk=>array('in',explode(',',$id)));
                if(false !== $model->where($condition)->setField('status',1)){
                    $this->success('审核通过！',$_SERVER['HTTP_REFERER']);
                }else {
                    $this->error('审核失败！');
                }
            }else {
                $this->error('非法操作');
            }
        }
    }

    // 默认禁用操作
    public function forbid() {
		$model	=	D($this->getActionName());
		$pk	=	$model->getPk();
        $id         = $_GET[$pk];
        $condition = array($pk=>array('in',$id));
        if($model->forbid($condition)){
            $this->success('状态禁用成功！',$_SERVER['HTTP_REFERER']);
        }else {
            $this->error('状态禁用失败！');
        }
    }

    // 默认还原操作
    public function recycle($id) {
		$model	=	D($this->getActionName());
        $condition = array('id'=>array('in',$id));
        if($model->recycle($condition)){
            $this->success('状态还原成功！',__URL__.'/recycleBin/');
        }else {
            $this->error('状态还原失败！');
        }
    }

    public function recycleBin() {
        $map = $this->_search();
        $map['status'] = -1;
		$model        = D($this->getActionName());
        if(!empty($model)) {
        	$this->_list($model,$map);
        }
		$this->display();
    }

    // 默认恢复操作
    function resume($id=0) {
        //恢复指定记录
		$model	=	D($this->getActionName());
        $condition = array('id'=>array('in',$id));
        if($model->resume($condition)){
            $this->success('状态恢复成功！',$_SERVER['HTTP_REFERER']);
        }else {
            $this->error('状态恢复失败！');
        }
    }
    // 默认推荐操作
    function recommend($id=0) {
		$model	=	D($this->getActionName());
        $condition = array('id'=>array('in',$id));
        if($model->recommend($condition)){
            $this->success('推荐成功！',$_SERVER['HTTP_REFERER']);
        }else {
            $this->error('推荐失败！');
        }
    }

    function getReturnUrl() {
        return __URL__.'?'.C('VAR_MODULE').'='.MODULE_NAME.'&'.C('VAR_ACTION').'='.C('DEFAULT_ACTION');
    }

    // 默认上传操作
	public function upload() {DUMP($_POST);DUMP($_FILES);EXIT;
		if(!empty($_FILES)) {//如果有文件上传
			$this->_upload();
		}
	}

    // 文件上传功能，支持多文件上传、保存数据库、自动缩略图
    protected function _upload() {
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
            if(isset($_POST['_uploadReplace']) && 1==$_POST['_uploadReplace']) {
                //设置附件是否覆盖
                $upload->uploadReplace =  true;
            }
        }
		$uploadFileVersion = false;
        if(isset($_POST['_uploadFileVersion']) && 1==$_POST['_uploadFileVersion']) {
            //设置是否记录附件版本
            $uploadFileVersion =  true;
        }
        $uploadRecord  =  true;
        if(isset($_POST['_uploadRecord']) && 0==$_POST['_uploadRecord']) {
            //设置附件数据是否保存到数据库
            $uploadRecord =  false;
        }
        // 记录上传成功ID
        $uploadId =  array();
        $savename = array();
        //执行上传操作
        if(!$upload->upload()) {dump($upload);exit;
            if($this->isAjax() && isset($_POST['_uploadFileResult'])) {
                $uploadSuccess =  false;
                $ajaxMsg  =  $upload->getErrorMsg();
            }else {
                //捕获上传异常
                $this->error($upload->getErrorMsg());
            }
        }else {
			 //取得成功上传的文件信息
			$uploadList = $upload->getUploadFileInfo();dump($uploadList);exit;
            $remark	 =	 $_POST['remark'];
			//保存附件信息到数据库
			if($uploadRecord) {
				$Attach    = M('Attach');
				//启动事务
				$Attach->startTrans();
			}
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
				$savename[] =  $file['savepath'].$file['savename'];
                $sourcename[]    = $file['name'];
				if($uploadRecord) {
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
					if($upload->uploadReplace ) {
						if(!empty($id)) {
							$vo  =  $Attach->getById($id);
						}else{
							$vo  =  $Attach->find(array('where'=>"module='".$module."' and record_id=".$recordId));
						}
						if($vo) {
							// 如果附件为覆盖方式 且已经存在记录，则进行替换
							$id	=	$vo[$Attach->getPk()];
							if($uploadFileVersion) {
								// 记录版本号
								$file['version']	 =	 $vo['version']+1;
								// 备份旧版本文件
								$oldfile	=	$vo['savepath'].$vo['savename'];
								if(is_file($oldfile)) {
									if(!is_dir(dirname($oldfile).'/_version/')) {
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
			}
			if($uploadRecord) {
				//提交事务
				$Attach->commit();
			}
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
            $info['name']   = implode(',',$sourcename);
            $this->ajaxUploadResult($info);
        }
        return ;
    }

    /**
     +----------------------------------------------------------
     * Ajax上传页面返回信息
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param array $info 附件信息
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    protected function ajaxUploadResult($info)
    {
        // Ajax方式附件上传提示信息设置
        // 默认使用mootools opacity效果
        $show   = '<script language="JavaScript" src="'.__ROOT__.'/Public/Js/mootools.js"></script><script language="JavaScript" type="text/javascript">'."\n";
        $show  .= ' var parDoc = window.parent.document;';
        $show  .= ' var result = parDoc.getElementById("'.$info['uploadResult'].'");';
        if(isset($info['uploadFormId'])) {
   	        $show  .= ' parDoc.getElementById("'.$info['uploadFormId'].'").reset();';
        }
        $show  .= ' result.style.display = "block";';
        $show .= " var myFx = new Fx.Style(result, 'opacity',{duration:600}).custom(0.1,1);";
        if($info['success']) {
            // 提示上传成功
            $show .=  'result.innerHTML = "<div style=\"color:#3333FF\"><IMG SRC=\"__PUBLIC__/images/ok.gif\" align=\"absmiddle\" BORDER=\"0\"> 文件上传成功！</div>";';
            // 如果定义了成功响应方法，执行客户端方法
            // 参数为上传的附件id，多个以逗号分割
            if(isset($info['uploadResponse'])) {
                $show  .= 'window.parent.'.$info['uploadResponse'].'("'.$info['uploadId'].'","'.$info['name'].'","'.$info['savename'].'");';
            }
        }else {
            // 上传失败
            // 提示上传失败
            $show .=  'result.innerHTML = "<div style=\"color:#FF0000\"><IMG SRC=\"__PUBLIC__/images/update.gif\" align=\"absmiddle\" BORDER=\"0\"> 上传失败：'.$info['message'].'</div>";';
        }
        $show .= "\n".'</script>';
        header("Content-Type:text/html; charset=utf-8");
        exit($show);
        return ;
   	}

    // 下载附件
    public function download($id=0) {
        $Attach        =   M("Attach");
        if($Attach->getById($id)) {
            $filename   =   $Attach->savepath.$Attach->savename;
            if(is_file($filename)) {
                $showname = auto_charset($Attach->name,'utf-8','gbk');
				$Attach->where('id='.$id)->setInc('download_count');
		        import("ORG.Net.Http");
                Http::download($filename,$showname);
            }
        }
    }

    // 默认删除附件操作
    public function delAttach($id=0) {
        //删除指定记录
        $attach        = M("Attach");
	    $condition = array('id'=>array('in',$id));
        if($attach->where($condition)->delete()){
            $this->ajaxReturn(array('data'=>$id,'info'=>L('_DELETE_SUCCESS_'),'status'=>1));
        }else {
            $this->error(L('_DELETE_FAIL_'));
        }
    }

    // 生成树型列表XML文件
	public function tree() {
		$Model	=	M($this->getActionName());
		$title		=	$_REQUEST['title']?		$_REQUEST['title']		:'选择';
		$caption	=	$_REQUEST['caption']?	$_REQUEST['caption']	:'name';
		$list   =  $Model->where('status=1')->order('sort')->select();
		$tree		=	toTree($list);
		header("Content-Type:text/xml; charset=utf-8");
		$xml	 =  '<?xml version="1.0" encoding="utf-8" ?>'."\n";
		$xml	.=  '<tree caption="'.$title.'" >'."\n";
		$xml  .= $this->_toTree($tree,$caption);
		$xml	.= '</tree>';
		exit($xml);
	}

    // 把树型列表数据转换为XML节点
	protected function _toTree($list,$caption) {
		foreach ($list as $key=>$val){
			$tab	=	str_repeat("\t",$val['level']);
			if(isset($val['_child'])) {
				// 有子节点
				$xml	.= $tab.'<level'.$val['level'].' id="'.$val['id'].'" level="'.$val['level'].'" parentId="'.$val['pid'].'" caption="'.$val[$caption].'" >'."\n";
				$xml  .= $this->_toTree($val['_child'],$caption);
				$xml  .= $tab.'</level'.$val['level'].'>'."\n";
			}else{
				$xml	.= $tab.'<level'.$val['level'].' id="'.$val['id'].'" level="'.$val['level'].'" parentId="'.$val['pid'].'" caption="'.$val[$caption].'" />'."\n";
			}
		}
		return $xml;
	}

    // 默认排序操作
    public function sort() {
		$model = M($this->getActionName());
        $map = array();
        $map['status'] = 1;
        if(!empty($_GET['sortId'])) {
            $map['id']   = array('in',$_GET['sortId']);
        }elseif(isset($_GET['pid'])){
            $map['pid'] =  $_GET['pid'];
        }
        $sortList   =   $model->where($map)->order('sort asc')->select();
        $this->assign("sortList",$sortList);
        $this->display();
        return ;
    }

    // 默认排序保存操作
    public function saveSort() {
        $seqNoList  =   $_POST['seqNoList'];
        if(!empty($seqNoList)) {
            //更新数据对象
            $model    = M($this->getActionName());
            $col    =   explode(',',$seqNoList);
            //启动事务
            $model->startTrans();
            foreach($col as $val) {
                $val    =   explode(':',$val);
                $model->id	=	$val[0];
                $model->sort	=	$val[1];
                $result =   $model->save();
                if(false === $result) {
                    break;
                }
            }
            //提交事务
            $model->commit();
            if(false !== $result) {
                //采用普通方式跳转刷新页面
                $this->success('更新成功');
            }else {
                $this->error($model->getError());
            }
        }
    }

	protected function getAttach($id,$module='') {
        $module = empty($module)?$this->getActionName():$module;
        //读取附件信息
        $Attach = M('Attach');
        return $Attach->where("module='".$module."' and record_id=$id")->select();
	}

    // 同步数据缓存
    public function syncData(){
        G('1');
        import('ORG.Util.Page');
        $map['status'] = array(1,2,'or');
        if($_POST['model']) {
            $models =  $_POST['model'];
        }else{
            $models =  explode(',',$_GET['models']);
        }
        $map['module']   =  array('IN',$models);
        // 同步之前清空缓存表
        //M('Cache'.ucfirst($model))->where(1)->delete();
        // 同步数据
        $count   =  M('Article')->where($map)->count();
        $this->page =   $Page->show();
        $maxRow   =  1000;//C('MAX_BUILD_ROWS');
        $times =  ceil($count/$maxRow);
        if(empty($_GET['t'])) {
            $_GET['t']=1;
        }elseif($_GET['t']>$times){
            // 清空栏目和频道缓存
            Build::deleteCate(2);
            $this->success('数据同步完成',__URL__);
        }
        $Article  =  D('Article');
        $list   =  $Article->where($map)->page($_GET['t'].','.$maxRow)->getField('id,id');
        $result   =  $Article->syncList($list,true);
        $url   =  __URL__.'/syncData/?models='.implode(',',$models).'&t='.($_GET['t']+1);
        $this->success('数据同步进行中~完成'.$_GET['t'].'/'.$times.'！耗时：'.G("1",'2').'s',$url);

    }
}