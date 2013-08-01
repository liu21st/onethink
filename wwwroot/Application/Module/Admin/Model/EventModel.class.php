<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2008 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class EventModel extends CommonModel {
	protected $_validate	 =	 array(
		//array('name','/^[A-Za-z]\w+$/','变量名错误！'),
		//array('name','checkName','变量已经定义',self::MODEL_BOTH,'callback'),
		);

	public function checkName() {
		$map['name']	 =	 $_POST['name'];
        if(!empty($_POST['id'])) {
			$map['id']	=	array('neq',$_POST['id']);
        }
        $map['model_id']   =  $_POST['model_id'];
		$result	=	$this->where($map)->getField('id');
        if($result) {
        	return false;
        }else{
			return true;
		}
	}
}
?>