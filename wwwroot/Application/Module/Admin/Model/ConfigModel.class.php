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

// 配置模型
class ConfigModel extends CommonModel {

	protected $_validate = array(
		array('name','require','参数名称必须'),
		array('name','checkName','参数已经定义',3,'callback'),
		);

	protected $_auto		=	array(
		array('create_time','time',self::MODEL_INSERT,'function'),
		);

	public function checkName() {
		$map['name']	 =	 $_POST['name'];
        if(!empty($_POST['id'])) {
			$map['id']	=	array('neq',$_POST['id']);
        }
        $map['site'] = array(0,$_POST['site'],'or');
		$result	=	$this->where($map)->getField('id');
        if($result) {
        	return false;
        }else{
			return true;
		}
	}
}
?>