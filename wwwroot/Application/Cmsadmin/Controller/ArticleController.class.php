<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

/**
 * 后台内容控制器
 * @author huajie <banhuajie@163.com>
 */

class ArticleController extends CmsadminController {
	
	/**
	 * 内容管理首页
	 * @author huajie <banhuajie@163.com>
	 */
	public function index($cate_id = null){
		if(empty($cate_id)){
			$this->error('文章分类不能为空');
		}
		$Document = D('Ducument');
		
		/*初始化分页类*/
		import('COM.Page');
		$count = $Document->listCount($cate_id, array('gt', -1));
		$Page = new Page($count, 20);
		$this->page = $Page->show();
		
		//列表数据获取
		$list = $Document->lists($cate_id, 'id DESC', array('gt', -1), 'id,uid,title,create_time,status', $Page->firstRow. ',' . $Page->listRows);
		
		$this->diaplay();
	}

}