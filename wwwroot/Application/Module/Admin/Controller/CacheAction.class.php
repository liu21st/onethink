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

// 生成缓存
class CacheAction extends CommonAction{
    // 刷新缓存
    public function index(){
        $this->display();
    }

    public function buildCache(){
        $type = $_POST['type'];
        $array  =   array('config','field','template','html','group','model','cate');
        foreach ($type as $val){
            if(in_array($val,$array)) {
              $this->$val();
            }
        }
        $this->success('缓存生成成功！');
    }

    // 配置缓存
    protected function config(){
        // 生成配置缓存
        $list   =  M("Config")->getField('name,value');
        cache('config',array_change_key_case($list,CASE_UPPER));
    }
    // 字段缓存
    protected function field() {
        import('ORG.Io.Dir');
        Dir::del(RUNTIME_PATH.'Data/_fields/');
    }
    // 模板缓存
    protected function template() {
        import('ORG.Io.Dir');
        if(is_dir(RUNTIME_PATH.'Cache/')) {
            Dir::del(RUNTIME_PATH.'Cache/');
        }
    }

    // 分组缓存
    protected function group() {
		$list   =	M("Group")->where('status=1')->order('sort')->getField('id,title');
        cache('group',$list);
    }

    // 模型缓存
    protected function model(){
        // 生成文档模型缓存
        $list			=	M("Model")->where('status=1')->select();
        $array   =  array();
        foreach ($list as $key=>$val){
            $map['id']   = array('in',$val['attribute_list']);
            $val['attrs']  = M('Attribute')->where($map)->getField('id,name');
            $array[strtolower($val['name'])]  =  $val;
        }
        cache('model',$array);
    }

    // 分类缓存
    protected function cate(){
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
}