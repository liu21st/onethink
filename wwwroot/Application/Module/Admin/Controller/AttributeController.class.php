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

class AttributeController extends CommonController {

    public function _filter(&$map){
        if(empty($_GET['model_id'])) {
            $this->error('非法访问！');
        }
        $this->modelType  =  M('Model')->where('id='.$_GET['model_id'])->getField('type');
        $this->models =  M('Model')->field('id,title')->where('status=1')->select();
    }

    public function edit($id){
        $vo =   M('Attribute')->find($id);
        if(!empty($vo['auto'])) {
            $vo['auto'] =   explode(',',$vo['auto']);
        }
        if(!empty($vo['validate'])) {
            $vo['validate']  =   explode(',',$vo['validate']);
        }
        $this->vo   =   $vo;
        $this->display();
    }

    public function sort() {
		$Attribute = M('Attribute');
        if(!empty($_GET['sortId'])) {
            $map['id']   = array('in',$_GET['sortId']);
        }else{
            $map['model_id'] =  $_GET['model_id']?$_GET['model_id']:0;
        }
        $map['status'] = 1;
        $sortList   =   $Attribute->where($map)->order('sort asc')->select();
        $this->assign("sortList",$sortList);
        $this->display();
        return ;
    }
}