<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class ModelModel extends CommonModel {
	protected $_validate	=	array(
		array('name','require','模型名称必须'),
		array('name','checkName','模型名称已经存在',2,'callback',self::MODEL_BOTH),
		);

	protected $_auto	 =	 array(
		array('name','strtolower',self::MODEL_BOTH,'function'),
		array('status','1',self::MODEL_INSERT,'string'),
		array('title','strip_tags',self::MODEL_BOTH,'function'),
		array('create_time','time',self::MODEL_INSERT,'function'),
		array('update_time','time',self::MODEL_UPDATE,'function'),
        array('data_length','getDataLength',self::MODEL_UPDATE,'callback'),
        array('attribute_list','array_to_string',self::MODEL_BOTH,'function'),
        array('extend_list','array_to_string',self::MODEL_BOTH,'function'),
        array('module_list','array_to_string',self::MODEL_BOTH,'function'),
        array('ext_list','array_to_string',self::MODEL_BOTH,'function'),
        array('map_list','array_to_string',self::MODEL_BOTH,'function'),
        array('search_list','array_to_string',self::MODEL_BOTH,'function'),
        array('link_list','array_to_string',self::MODEL_BOTH,'function'),
        //array('relation_list','array_to_string',self::MODEL_BOTH,'function'),
       // array('template_list','buildTmpl',self::MODEL_UPDATE,'callback'),
		array('user_id','getUserId',self::MODEL_INSERT,'callback'),
		array('belongs_to','belongs_to',self::MODEL_BOTH,'callback'),
		array('has_one','has_one',self::MODEL_BOTH,'callback'),
		array('has_many','has_many',self::MODEL_BOTH,'callback'),
		);

    public function getDataLength($data){
        return $data?intval($data):10;
    }
    public function belongs_to($name){
        if(!empty($name) && !strpos($name,':')) {
            $name   .= ':'.strtolower($name).'_id';
        }
        return $name;
    }
    public function has_one($name){
        if(!empty($name) && !strpos($name,':')) {
            $name   .= ':'.strtolower($_POST['name']).'_id';
        }
        return $name;
    }
    public function has_many($name){
        if(!empty($name) && !strpos($name,':')) {
            $name   .= ':'.strtolower($_POST['name']).'_id';
        }
        return $name;
    }
    public function checkName($name) {
        // 检查是否内置模型
        if(!empty($_POST['id'])) {
            $map['id']   = array('neq',$_POST['id']);
        }
        $map['name']    = $name;
        if($this->where($map)->field('id')->find()) {
            return false;
        }
        return true;
    }
    
    // 生成新的模板
    public function buildTmpl($template){
        $type = $_POST['type'];
        if(!empty($_POST['list_grid']) || !empty($_POST['action_list'])) {
            $content = file_get_contents(THEME_PATH.'Think/index.html');
            $tmplFile = !empty($_POST['template_list'])?str_replace(':','/',$_POST['template_list']):'Think/'.$_POST['name'].'_list';
            file_put_contents(THEME_PATH.$tmplFile.'.html',$content);
            if(!empty($_POST['template_list'])) {
                $content = file_get_contents(THEME_PATH.'Think/recycleBin.html');
                file_put_contents(THEME_PATH.'Think/'.$_POST['name'].'_recycle.html',$content);
            }
            return str_replace('/',':',$tmplFile);
        }
        return $template;
    }

    protected function cacheModel() {
        // 生成文档模型缓存
        $list			=	M("Model")->where('status=1')->select();
        $array   =  array();
        foreach ($list as $key=>$val){
            $map['id']   = array('in',$val['attribute_list']);
            $val['attrs']  = M('Attribute')->where($map)->getField('id,name');
            $array[strtolower($val['name'])]  =  $val;
        }
        S('model',$array);
    }
    protected function _after_insert($data,$options) {
        $this->cacheModel();
    }

    protected function _after_update($data,$options) {
        $this->cacheModel();
    }

}
?>