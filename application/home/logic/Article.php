<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\home\logic;

/**
 * 文档模型子模型 - 文章模型
 */
class Article  extends Base{
	/* 自动验证规则 */
// 	protected $_validate = array(
// 		array('content', 'require', '内容不能为空！', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
// 	);

	/* 自动完成规则 */
	protected $_auto = array();

}
