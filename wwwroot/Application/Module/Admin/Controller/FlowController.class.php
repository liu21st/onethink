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

class FlowController extends CommonController {

    public function _before_add(){
        $map['model_id'] =  $_GET['model_id'];
        $map['status'] = 1;
        $this->attrList = M('Attribute')->where($map)->order('sort')->getField('name,title');
        $this->roleList  = M('Role')->where('status=1')->getField('id,name');
    }

    public function _before_edit(){
        $this->roleList  = M('Role')->where('status=1')->getField('id,name');
    }

    public function sort()
    {
		$Flow = M('Flow');
        if(!empty($_GET['sortId'])) {
            $map['id']   = array('in',$_GET['sortId']);
        }else{
            $map['model_id'] =  $_GET['model_id']?$_GET['model_id']:0;
        }
        $map['status'] = 1;
        $sortList   =   $Flow->where($map)->order('sort asc')->select();
        $this->assign("sortList",$sortList);
        $this->display();
        return ;
    }
}
?>