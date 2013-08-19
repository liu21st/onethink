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

class ArticleController extends AdminController {
	
	/**
     * 节点配置  
     *   菜单节点必须配置title元素和url元素(供U函数作使用)
     *   array(
     *       //值的元素  title:节点名字；url:链接; group:链接组; tip:链接提示文字
     *       array( 'title'=>'节点标题','url'=>'action?query=vaule', 'group'=>'扩展','tip'=>''),
     *   )
     */ 
    static protected $nodes = array(
    	array( 'title' => '讨论', 'url' => 'Article/index?cate_id=9', 'group' => '文档分类'),
    	array( 'title' => '下载', 'url' => 'Article/index?cate_id=2', 'group' => '文档分类'),
    );
    
	/**
	 * 内容管理首页
	 * @param $cate_id 分类id
	 * @author huajie <banhuajie@163.com>
	 */
	public function index($cate_id = null){
		if(empty($cate_id)){
			$cate_id = 9;	//TODO:动态获取第一个分类
		}
		$Document = D('Document');
		
		/*初始化分页类*/
		import('COM.Page');
		$count = $Document->listCount($cate_id, array('gt', -1));
		$Page = new Page($count, 20);
		$this->page = $Page->show();
		
		//列表数据获取
		$list = $Document->lists($cate_id, 'id DESC', array('gt', -1), 'id,uid,title,create_time,status', $Page->firstRow. ',' . $Page->listRows);
		
		$this->assign('list', $list);
		$this->display();
	}
	
	/**
	 * @Description 设置一条或者多条数据的状态
	 * @author huajie <banhuajie@163.com>
	 */
	public function setStatus(){
		/*参数过滤*/
		$ids = I('get.ids');
		$status = I('get.status');
		if(empty($ids) || !isset($status)){
			$this->error('请选择要操作的数据');
		}
		
		/*拼接参数并修改状态*/
		$Model = 'Document';
		$map = array();
		if(is_array($ids)){
			$map['id'] = array('in', implode(',', $ids));
		}elseif (is_numeric($ids)){
			$map['id'] = $ids;
		}
		switch ($status){
			case -1 : $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));break;
			case 0 : $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));break;
			case -1 : $this->resume($Model, $map, array('success'=>'审核通过','error'=>'审核失败'));break;
			default : $this->error('参数错误');break;
		}
	}
	
	
	/**
	 * @Description 文档新增页面初始化
	 * @author huajie <banhuajie@163.com>
	 */
	public function add(){
		$cate_id = I('get.cate_id','');
		if(empty($cate_id)){
			$this->error('分类参数错误！');
		}
		
		/*获取该分类下的文档模型*/
		$model = D('Category')->getFieldById($cate_id, 'model');
		$model = explode(',', $model);
		
		$this->assign('models', $model);
		$this->display();
	}
	
	/**
	 * @Description 文档编辑页面初始化
	 * @author huajie <banhuajie@163.com>
	 */
	public function edit(){
		$id = I('get.id','');
		if(empty($id)){
			$this->error('参数不能为空！');
		}
		
		/*获取一条记录的详细数据*/
		$Document = D('Document');
		$data = $Document->detail($id);
		if(!$data){
			$this->error($Document->getError());
		}
		
		$this->assign($data);
		$this->display();
	}
}