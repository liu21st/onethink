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
 * OneThink 系文档模型标签库
 */
class Article extends TagLib{
	/**
	 * 定义标签列表
	 * @var array
	 */
	protected $tags   =  array(
		'partlist' => array('attr' => 'id,field,page,name', 'close' => 1), //段落列表
		'partpage' => array('attr' => 'id,listrow', 'close' => 0), //段落分页
		'prev'      => array('attr' => 'name,info', 'close' => 1), //获取上一篇文章信息
		'next'      => array('attr' => 'name,info', 'close' => 1), //获取下一篇文章信息
		'page'      => array('attr' => 'cate,listrow', 'close' => 0), //列表分页
		'position'  => array('attr' => 'pos,cate,limit,filed,name', 'close' => 1), //获取推荐位列表
		'list'      => array('attr' => 'name,category,child,page,row,field', 'close' => 1), //获取指定分类列表
	);

	public function _list($attr, $content){
		$tag    = $this->parseXmlAttr($attr, 'next');
		$name   = $tag['name'];
		$cate   = $tag['category'];
		$child  = empty($tag['child']) ? 'false' : $tag['child'];
		$page   = empty($tag['page'])  ? '0' : $tag['page'];
		$row    = empty($tag['row'])   ? '10' : $tag['row'];
		$filed  = empty($tag['filed']) ? 'true' : $tag['filed'];

		$parse  = '<?php ';
		$parse .= '$category=D(\'Category\')->getChildrenId('.$cate.');';
		$parse .= '$__LIST__ = D(\'Document\')->page('.$page.','.$row.')->lists(';
		$parse .= '$category, \'`id` DESC\', 1,';
		$parse .= $filed . ');';
		$parse .= ' ?>';
		$parse .= '<volist name="__LIST__" id="'. $name .'">';
		$parse .= $content;
		$parse .= '</volist>';
		return $parse;
	}

	/* 推荐位列表 */
	public function _position($attr, $content){
		$tag    = $this->parseXmlAttr($attr, 'next');
		$pos    = $tag['pos'];
		$cate   = $tag['cate'];
		$limit  = empty($tag['limit']) ? 'null' : $tag['limit'];
		$filed  = empty($tag['filed']) ? 'true' : $tag['filed'];
		$name   = $tag['name'];
		$parse  = $parse   = '<?php ';
		$parse .= '$__POSLIST__ = D(\'Document\')->position(';
		$parse .= $pos . ',';
		$parse .= $cate . ',';
		$parse .= $limit . ',';
		$parse .= $filed . ');';
		$parse .= ' ?>';
		$parse .= '<volist name="__POSLIST__" id="'. $name .'">';
		$parse .= $content;
		$parse .= '</volist>';
		return $parse;
	}

	/* 列表数据分页 */
	public function _page($attr){
		$tag     = $this->parseXmlAttr($attr, 'next');
		$cate    = $tag['cate'];
		$listrow = $tag['listrow'];
		$parse   = '<?php ';
		$parse  .= '$__PAGE__ = new \COM\Page(get_list_count(' . $cate . '), ' . $listrow . ');';
		$parse  .= 'echo $__PAGE__->show();';
		$parse  .= ' ?>';
		return $parse;
	}

	/* 获取下一篇文章信息 */
	public function _next($attr, $content){
		$tag    = $this->parseXmlAttr($attr, 'next');
		$name   = $tag['name'];
		$info   = $tag['info'];
		$parse  = '<?php ';
		$parse .= '$' . $name . ' = D(\'Document\')->next($' . $info . ');';
		$parse .= ' ?>';
		$parse .= '<notempty name="' . $name . '">';
		$parse .= $content;
		$parse .= '</notempty>';
		return $parse;
	}

	/* 获取上一篇文章信息 */
	public function _prev($attr, $content){
		$tag    = $this->parseXmlAttr($attr, 'prev');
		$name   = $tag['name'];
		$info   = $tag['info'];
		$parse  = '<?php ';
		$parse .= '$' . $name . ' = D(\'Document\')->prev($' . $info . ');';
		$parse .= ' ?>';
		$parse .= '<notempty name="' . $name . '">';
		$parse .= $content;
		$parse .= '</notempty>';
		return $parse;
	}

	/* 子内容数据分页 */
	public function _partpage($attr){
		$tag     = $this->parseXmlAttr($attr, 'next');
		$id      = $tag['id'];
		$listrow = $tag['listrow'];
		$parse   = '<?php ';
		$parse  .= '$__PAGE__ = new \COM\Page(get_part_count(' . $id . '), ' . $listrow . ');';
		$parse  .= 'echo $__PAGE__->show();';
		$parse  .= ' ?>';
		return $parse;
	}

	/* 子内容列表 */
	public function _partlist($attr, $content){
		$tag    = $this->parseXmlAttr($attr, 'partlist');
		$id     = $tag['id'];
		$field  = $tag['field'];
		$name   = $tag['name'];
		$parse  = '<?php ';
		$parse .= '$__PARTLIST__ = D(\'Document\')->part(' . $id . ', $page, \'' . $field . '\');';
		$parse .= ' ?>';
		$parse .= '<volist name="__PARTLIST__" id="'. $name .'">';
		$parse .= $content;
		$parse .= '</volist>';
		return $parse;
	}
}