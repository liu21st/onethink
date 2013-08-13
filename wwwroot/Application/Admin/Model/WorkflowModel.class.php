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

class WorkflowModel extends CommonModel {
	protected $_validate	 =	 array(

		);

	protected $_auto	 =	 array(
		array('remark','strip_tags',self::MODEL_BOTH,'function'),
        array('op_type','1',self::MODEL_BOTH),
		array('create_time','time',self::MODEL_INSERT,'function'),
		array('update_time','time',self::MODEL_UPDATE,'function'),
        array('from_id','getUserId',Model::MODEL_INSERT,'function'),
        array('send_id','array_to_string',self::MODEL_BOTH,'function'),
		);


}