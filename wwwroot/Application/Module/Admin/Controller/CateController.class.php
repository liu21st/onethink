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

// 栏目管理
class CateController extends CommonController {

	public function _filter(&$map){
		if(empty($_POST['search']) && !isset($map['pid']) ) {
			$map['pid']	=	0;
		}
		$_SESSION['currentColumnId']	=	$map['pid'];
        /*
        if(isset($_GET['tag'])) {
            $tag = $_GET['tag'];
            cookie('cateTag',$tag);
        }elseif(cookie('cateTag')){
            $tag  =  cookie('cateTag');
        }else{
            $tag  =  1;
        }
        $type = parseAttr(C('CATE_TYPE_LIST'));
		$this->assign("tagName",$type[$tag]);
        $map['tag'] = $tag;*/
		//获取上级节点
		$Column  = M("Cate");
		if($Column->getById($map['pid'])) {
			$this->assign('level',$Column->level+1);
			$this->assign('columnName',$Column->title);
            // 获取上级分类地址
            $this->return_id  =  $Column->where('id='.$map['pid'])->getField('pid');
		}else {
			$this->assign('level',1);
		}
        if(empty($_REQUEST['_order'])) {
            $_REQUEST['_order']    = 'sort';
        }
        if(!isset($_REQUEST['_sort'])) {
            $_REQUEST['_sort'] =  'asc';
        }
	}

	public function add(){
		$Column	=	M("Cate");
		$Column->getById($_SESSION['currentColumnId']);
        $this->assign('pid',$Column->id);
		$this->assign('level',$Column->level+1);
        $cate  =  M('Cate')->where('id='.$_SESSION['currentColumnId'])->field('id,root_id')->find();
        // 获取模型列表
        $map['status'] = 1;
        $map['support_cate'] = 1;
        $this->list   =  M('Model')->field('id,title')->where($map)->select();
        // 获取文档模型列表
        unset($map);
        $map['status'] = 1;
        $map['model_type'] = 2;
        $this->type   =  M('Model')->where($map)->getField('id,title');
        // 获取分类列表
        $list = M('Cate')->where('status=1')->field('id,pid,level,title')->order('level,sort')->select();
        $this->tree   =  toTree($list);
		$this->display();
	}

    public function treeSelect(){
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
        $this->display('treeSelect');
    }

    public function _before_edit() {
        // 获取模型列表
        $cate  =  M('Cate')->where('id='.$_SESSION['currentColumnId'])->field('id,root_id')->find();
        $map['status'] = 1;
        $map['support_cate'] = 1;
        //$map['site'] = array(0,$cate['root_id']?$cate['root_id']:$cate['id'],'or');
        $this->list   =  M('Model')->field('id,title')->where($map)->select();
        // 获取文档模型列表
        unset($map);
        $map['status'] = 1;
        $map['model_type'] = 2;
        $this->type   =  M('Model')->where($map)->getField('id,title');
        // 获取分类列表
        $list = M('Cate')->where('status=1 and id !='.(int)$_GET['id'])->field('id,pid,level,title')->order('level,sort')->select();
        $this->tree   =  toTree($list);
    }

    public function _before_insert(){
        // 上传图片
        $this->uploadFile();
    }
    
    public function _before_update(){
        // 上传图片
        $this->uploadFile();
    }

    // 上传附件
    protected function uploadFile() {
        if(!empty($_FILES['file']['name'])) {
            import("ORG.Net.UploadFile");
            $upload = new UploadFile();
            $file   = $_FILES['file'];
            //设置上传文件大小
            $upload->maxSize  = C('UPLOAD_MAX_SIZE') ;
            $upload->allowExts   =  array('jpg','gif','png','jpeg');
            $upload->savePath =  './Uploads/Cate/';
            $info =  $upload->uploadOne($file);
            if(false === $info) {                
                $this->error($upload->getErrorMsg());
            }
            $_POST['avatar'] = '/Uploads/Cate/'.$info[0]['savename'];
        }
    }

    // 栏目排序
    public function sort() {
		$node = M('Cate');
        if(!empty($_GET['sortId'])) {
            $map = array();
            $map['status'] = 1;
            $map['id']   = array('in',$_GET['sortId']);
            $sortList   =   $node->where($map)->order('sort asc')->select();
        }else{
            if(!empty($_GET['pid'])) {
                $pid  = $_GET['pid'];
            }else {
                $pid  = $_SESSION['currentColumnId'];
            }
            if($node->getById($pid)) {
                $level   =  $node->level+1;
            }else {
                $level   =  1;
            }
            $this->assign('level',$level);
            $sortList   =   $node->where('status=1 and pid='.$pid.' and level='.$level)->order('sort asc')->select();
        }
        $this->assign("sortList",$sortList);
        $this->display();
        return ;
    }

    public function moveNode() {
		$id	=	$_POST['id'];
		$_SESSION['moveNode']	 =	 $id;
		$this->success('请选择要移动到的栏目！');
    }

    public function copyNode() {
		$id	=	$_POST['id'];
		$_SESSION['copyNode']	 =	 $id;
		$this->success('请选择要复制到的栏目！');
    }

    public function pasteNode() {
        if(empty($_SESSION['moveNode']) && empty($_SESSION['copyNode'])) {
            $this->error('没有选择栏目！');
        }
        if(!isset($_POST['pid'])) {
            $this->error('请选择要粘贴到的栏目！');
        }
        $Cate =  M("Cate");
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
                $this->success('栏目复制成功！');
            }else{
                $this->error('栏目复制失败！');
            }
        }
    }

}
?>