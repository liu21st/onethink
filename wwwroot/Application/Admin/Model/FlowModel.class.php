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

class FlowModel extends CommonModel {
	protected $_validate	 =	 array(

		);

	protected $_auto	 =	 array(
		array('title','strip_tags',self::MODEL_BOTH,'function'),
		array('create_time','time',self::MODEL_INSERT,'function'),
		array('update_time','time',self::MODEL_UPDATE,'function'),
        array('attribute_list','array_to_string',self::MODEL_BOTH,'function'),
        array('role_list','array_to_string',self::MODEL_BOTH,'function'),
		);


}
?>