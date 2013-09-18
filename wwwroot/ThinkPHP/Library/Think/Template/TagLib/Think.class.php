<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Think\Template\TagLib;
use Think\Template\TagLib;

/**
 * OneThink 系统标签库
 */
class Think extends TagLib{
	/**
	 * 定义标签列表
	 * @var array
	 */
	protected $tags   =  array(
		'nav'  => array('attr' => 'field,name', 'close' => 1), //获取导航
	);

	/* 导航列表 */
	public function _nav($attr, $content){
		$tag    = $this->parseXmlAttr($attr, 'next');
		$filed  = empty($tag['filed']) ? 'true' : $tag['filed'];
		$name   = $tag['name'];
		$parse  = $parse   = '<?php ';
		$parse .= '$__NAV__ = D(\'Channel\')->lists(' . $field . ');';
		$parse .= ' ?>';
		$parse .= '<volist name="__NAV__" id="'. $name .'">';
		$parse .= $content;
		$parse .= '</volist>';
		return $parse;
	}
}