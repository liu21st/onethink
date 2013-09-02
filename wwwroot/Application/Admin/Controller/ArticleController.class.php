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

	/* 左侧节点菜单定义 */
	static protected $nodes = array(
			array(
					'title'=>'文档列表', 'url'=>'article/index', 'group'=>'内容','hide'=>true,
					'operator'=>array(
							//权限管理页面的五种按钮
							array('title'=>'新增','url'=>'article/add'),
							array('title'=>'编辑','url'=>'article/edit'),
							array('title'=>'改变状态','url'=>'article/setStatus'),
							array('title'=>'保存数据','url'=>'article/update'),
					),
			),
	);

	private $cate_id = null;	//文档分类id

    /**
     * 控制器初始化方法
     * @see AdminController::_init()
     * @author huajie <banhuajie@163.com>
     */
    protected function _initialize(){
    	//调用父类的初始化方法
    	parent::_initialize();

    	//获取动态节点
    	$cate = M('Category')->where(array('display'=>1,'status'=>1))->field('id,title,pid')->order('sort')->select();
		$cate = list_to_tree($cate);

		//获取分类id
		$cate_id = I('param.cate_id') == '' ? $cate[0]['id'] : I('param.cate_id');
		$this->cate_id = $cate_id;

		//单独处理2级以下的分类
		$child_cates = array();

		//生成每个分类的url
		foreach ($cate as $key=>&$value){
			$value['url'] = 'Article/index?cate_id='.$value['id'];
			$value['level'] = 1;
			if($cate_id == $value['id']){
				$value['current'] = true;
			}
			foreach ($value['_child'] as $ka=>&$va){
				$va['url'] = 'Article/index?cate_id='.$va['id'];
				$va['level'] = 2;
				foreach ($va['_child'] as $k=>&$v){
					$v['url'] = 'Article/index?cate_id='.$v['id'];
					$v['pid'] = $va['id'];
					$v['level'] = 3;
					if($v['id'] == $cate_id){
						$is_child = true;
					}
				}
				//展开子分类的父分类
				if($va['id'] == $cate_id || $is_child){
					$child_cates = $va['_child'];
					$is_child = false;
					$value['current'] = true;
					$va['current'] = true;
				}
			}
		}
		$this->assign('nodes', $cate);
		$this->assign('child_cates', $child_cates);
		$this->assign('cate_id', $this->cate_id);

		//权限判断
		$cate_auth = AuthGroupModel::getAuthCategories(is_login());	//获取当前用户所有的内容权限节点
		if(!in_array($cate_id, $cate_auth)){
			$this->error('没有权限！');
		}
    }

	/**
	 * 内容管理首页
	 * @param $cate_id 分类id
	 * @author huajie <banhuajie@163.com>
	 */
	public function index($cate_id = null, $status = null, $search = null){
		$cate_id = $this->cate_id;
		$Document = D('Document');

		/* 查询条件初始化 */
		$map = array();
		if(isset($status)){
			$map['status'] = $status;
		}
		if(isset($search)){
			$map['title'] = array('like', '%'.$search.'%');
		}
		/*初始化分页类*/
		import('COM.Page');
		$count = $Document->listCount($cate_id, array('gt', -1), $map);
		$Page = new Page($count, 10);
		$this->page = $Page->show();

		//列表数据获取
		$list = $Document->lists($cate_id, 'id DESC', array('gt', -1), 'id,uid,title,create_time,status', $Page->firstRow. ',' . $Page->listRows, $map);

		//获取对应分类下的模型
		$models = get_category($cate_id, 'model');

		$this->assign('model', implode(',', $models));
		$this->assign('status', $status);
		$this->assign('search', $search);
		$this->assign('list', $list);
		$this->display();
	}

	/**
	 * 设置一条或者多条数据的状态
	 * @author huajie <banhuajie@163.com>
	 */
	public function setStatus(){
		/*参数过滤*/
		$ids = I('param.ids');
		$status = I('param.status');
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
			case 1 : $this->resume($Model, $map, array('success'=>'审核通过','error'=>'审核失败'));break;
			default : $this->error('参数错误');break;
		}
	}


	/**
	 * 文档新增页面初始化
	 * @author huajie <banhuajie@163.com>
	 */
	public function add(){
		$cate_id = I('get.cate_id','');
		$model_id = I('get.model_id','');
		if(empty($cate_id) || empty($model_id)){
			$this->error('参数不能为空！');
		}

		/* 获取要编辑的模型模板 */
		$template = strtolower(get_document_model($model_id, 'name'));

		$this->assign('model_id', $model_id);
		$this->assign('template', $template);
		$this->display();
	}

	/**
	 * 文档编辑页面初始化
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

		/* 获取要编辑的模型模板 */
		$data['template'] = strtolower(get_document_model($data['model_id'], 'name'));

		$this->assign($data);
		$this->display();
	}

	/**
	 * 更新一条数据
	 * @author huajie <banhuajie@163.com>
	 */
	public function update(){
		$res = D('Document')->update();
		if(!$res){
			$this->error(D('Document')->getError());
		}else{
			if($res['id']){
				$this->success('更新成功');
			}else{
				$this->success('新增成功');
			}
		}
	}

}