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

// 账号模型
class AuthModel extends CommonModel {
    protected $_validate	=	array(
        array('account','/^[\pL\pN|_^@-]+$/i','用户名格式错误'),
        array('password','require','密码必须'),
        array('repassword','require','确认密码必须'),
        array('repassword','password','确认密码不一致',self::EXISTS_VAILIDATE,'confirm'),
        array('account','','账号已经存在',self::EXISTS_VAILIDATE,'unique'),
        );

    protected $_auto		=	array(
        array('status','1',self::MODEL_INSERT,'string'),
        array('account','strtolower',self::MODEL_INSERT,'function'),
        array('password','pwdHash',self::MODEL_INSERT,'function'),
        array('create_time','time',self::MODEL_INSERT,'function'),
        array('update_time','time',self::MODEL_BOTH,'function'),
        );
}
?>