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

// 栏目模型
class CateModel extends CommonModel {
	protected $_validate	=	array(
		array('name','require','名称必须'),
		array('name','checkName','名称已经存在',2,'callback',self::MODEL_BOTH),
		);

	protected $_auto		=	array(
		array('create_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_BOTH,'function'),
        array('root_id','getRootId',self::MODEL_INSERT,'callback'),
        array('status','1',self::MODEL_INSERT),
        array('map_list','getMapList',self::MODEL_BOTH,'callback'),
        array('type_list','array_to_string',self::MODEL_BOTH,'function'),
        array('module_list','array_to_string',self::MODEL_BOTH,'function'),
		);

    protected function getMapList() {
        return implode(',',$_POST['map_list']);
    }
    // 获取当前分类的根ID
    protected function getRootId() {
		$pid	=	$_POST['pid'];
        if(!empty($pid)) {
            $cate = $this->where('id='.$pid)->field('pid,root_id')->find();
            return !empty($cate['root_id'])?$cate['root_id']:($cate['pid']?$cate['pid']:$pid);
        }else{
            return 0;
        }
    }

    public function checkName() {
        if(!empty($_POST['id'])) {
            $map['id']   = array('neq',$_POST['id']);
        }
        $map['name']    = $_POST['name'];
        if($this->where($map)->field('id')->find()) {
            return false;
        }
        return true;
    }

	public function checkNode() {
		$map['name']	 =	 $_POST['name'];
		$map['pid']	=	isset($_POST['pid'])?$_POST['pid']:0;
        $map['status'] = 1;
        if(!empty($_POST['id'])) {
			$map['id']	=	array('neq',$_POST['id']);
        }
		$result	=	$this->where($map)->field('id')->find();
        if($result) {
        	return false;
        }else{
			return true;
		}
	}

    // 递归获取子分类信息
    public function getSubCateList($id,$child=false,$showHide=false){
        // 查询子分类
        $map['status'] = 1;
        $map['pid'] = $id;
        if(!$showHide) {
            $map['is_show']   = 1;
        }
        $sub =  $this->where($map)->order('sort')->field('id,name,title,pid,map_id,level,root_id,model,is_show')->select();
        if($sub) {
            foreach ($sub as $key=>$cate){
                $cate['_url'] = Html::getUrl($cate['level']-1,$cate['id']);
                $cate['_child'] = $list;
                if($child) {
                    $list = $this->getSubCateList($cate['id'],true,$showHide);
                    if($list) {
                        $cate['_child'] = $list;
                    }
                }
                $sub[$key]=   $cate;
            }
        }
        return $sub?$sub:array();
    }

    // 递归获取子分类ID
    public function getSubCateId($id,$child=false,$self=false){
        // 查询子分类
        $map['status'] = 1;
        $map['pid'] = $id;
        $sub =  $this->where($map)->getField('id,id');
        if($sub && $child) {
            foreach ($sub as $key=>$cateId){
                $list = $this->getSubCateId($cateId,true);
                if($list) {
                    $sub += $list;
                }
            }
        }
        if($self) $sub[]   =  $id;
        return $sub?$sub:array();
    }

    // 获取子栏目ID 最多三级
    public function getSubCate($id,$level=3){
        // 查询当前分类信息
        $cate = $this->where('id='.$id)->field('level,root_id')->find();
        // 查询子分类
        $map['status'] = 1;
        $map['level']   =  array('gt',$cate['level']);
        $map['root_id'] = $cate['root_id']?$cate['root_id']:$id;
        $ids =  $this->where($map)->field('id,pid')->select();
        $sub =  array();
        $result   = search($ids,'pid='.$id);
        if($result && $level>1) {
            $sub   =  $result;
            foreach ($result as $item){
                $array =  search($ids,'pid='.$item['id']);
                if($array && $level>2) {
                    $sub += $array;
                    foreach ($array as $a){
                        $temp =  search($ids,'pid='.$a['id']);
                        if($temp) {
                            $sub += $temp;
                        }
                    }
                }
            }
        }
        $result   =  array();
        foreach ($sub as $item){
            $result[] = $item['id'];
        }
        return $result;
    }

    // 递归获取父栏目id
    public function getParentCateId($id,$self=false){
        $parent  = array();
        $pid  =  $this->where('id='.$id)->getField('pid');
        if($pid) {
            $parent   +=  $this->getParentCateId($pid);
            $parent[]   =  (int)$pid;
        }
        if($self) $parent[]   =  (int)$id;
        return $parent;
    }

    // 递归获取父栏目信息 用于
    public function getParentCateInfo($id){
        $info  = array();
        $cate  =  $this->where('id='.$id)->field('id,title,name,level,pid')->find();
        if($cate) {
            $info   +=  $this->getParentCateInfo($cate['pid']);
            $url_type = ($cate['level']>2)?2:$cate['level']-1;
            $cate['_url'] = url($cate['id'],$url_type);
            //if(1==$cate['level']) $cate['title']  =  '首页';
            $info[]   =  $cate;
        }
        return $info;
    }

    // 获取分类的映射分类 并且支持返回数组 便于搜索
    public function getMapCateId($id,$returnArray=false){
        // 读取分类映射
        $mapId =  M('Cate')->where('id='.(int)$id)->getField('map_id');
        if($mapId) { // 读取分类映射的文档数据
            return $returnArray?array($id,$mapId):$mapId;
        }
        return $id;
    }

    protected function cacheCate() {
        // 生成分类缓存
        $Cate		=	M("Cate");
        $array   =  array();
        $list			=	$Cate->field('id,name,model,title,tmpl_home,tmpl_list,url,is_show,tmpl_detail')->where('status=1')->order('sort')->select();
        foreach ($list as $key=>$cate){
            $cate['module']   =  M('Model')->where('id='.$cate['model'])->getField('name');
            $array[$cate['id']] = $cate;
        }
        cache('cate',$array);
    }

    protected function _after_insert($data,$options) {
        $this->cacheCate();
    }

    protected function _after_update($data,$options) {
        $this->cacheCate();
    }
}
?>