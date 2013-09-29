<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 文档模型控制器
 * 文档模型列表和详情
 */
class ArticleController extends HomeController {

    /* 文档模型频道页 */
	public function index(){
		/* 分类信息 */
		$category = $this->category();

		//频道页只显示模板，默认不读取任何内容
		//内容可以通过模板标签自行定制

		/* 获取模板 */
		$tmpl = $category['template_index'];

		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		$this->display($tmpl);
	}

	/* 文档模型列表页 */
	public function lists($page = 1){
		/* 分类信息 */
		$category = $this->category();

		/* 获取当前分类列表 */
		$Document = D('Document');
		$list = $Document->page($page, $category['list_row'])->lists($category['id']);
		if(false === $list){
			$this->error('获取列表数据失败！');
		}

		/* 获取模板 */
		$tmpl = $category['template_lists'];

		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		$this->assign('list', $list);
		$this->display($tmpl);
	}

	/* 文档模型详情页 */
	public function detail($id = 0, $page = 1){
		/* 标识正确性检测 */
		if(!($id && is_numeric($id))){
			$this->error('文档ID错误！');
		}

		/* 页码检测 */
		$page = intval($page);
		$page = empty($page) ? 1 : $page;

		/* 获取详细信息 */
		$Document = D('Document');
		$info = $Document->detail($id);
		if(!$info){
			$this->error($Document->getError());
		}

		/* 分类信息 */
		$category = $this->category($info['category_id']);

		/* 获取模板 */
		if(!empty($info['template'])){//已定制模板
			$tmpl = $info['template'];
		} elseif (!empty($category['template_detail'])){ //分类已定制模板
			$tmpl = $category['template_detail'];
		} else { //使用默认模板
			$tmpl = 'Article/'. get_document_model($info['model_id'],'name') .'/detail';
		}

		/* 更新浏览数 */
		$map = array('id' => $id);
		$Document->where($map)->setInc('view');

		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		$this->assign('info', $info);
		$this->assign('page', $page); //页码
		$this->display($tmpl);
	}

	/* 搜索文章 */
	public function search(){

	}

	/* 编辑或新增内容 */
	public function edit($category = null, $model = null, $id = 0){
		$this->login(); //用户登录检测
		//TODO: 管理员权限检测，目前测试阶段不做限制

		/* 参数正确性检测 */
		if((empty($id) || !is_numeric($id)) && (empty($category) || empty($model))){
			$this->error('缺少参数！');
		}

		/* 获取被编辑的数据 */
		$info = array();
		if($id){
			/* 获取详细信息 */
			$Document = D('Document');
			$info = $Document->detail($id);
			if(!$info){
				$this->error($Document->getError());
			}
			$category = $info['category_id'];
			$model    = $info['model'];
		}

		/* 获取当前分类信息 */
		$category = D('Category')->info($category);
		if(0 == $category['allow_publish']){
			$this->error('该分类禁止发布内容！');
		}

		/* 验证绑定模型 */
		if(in_array($model, $category['model'])){
			$info['model_id'] = $model;
		} else {
			$this->error("该分类没有绑定模型：id-{$model}");
		}

		/* 获取模板 */
		if(empty($category['template_edit'])){
			$model = get_document_model($model, 'name');
			$tmpl  = "Article/{$model}/edit";
		} else {
			$tmpl  = $category['template_edit'];
		}

		/* 模板赋值并渲染模板 */
		$this->assign('info', $info);
		$this->assign('category', $category);
		$this->display($tmpl);
	}

	/* 保存文档数据 */
	public function save($category_id = null, $type = 0){
		$this->login(); //用户登录检测
		//TODO: 管理员权限检测，目前测试阶段不做限制

		/* 分类发布验证 */
		if(!empty($category_id) && is_numeric($category_id)){
			$category = D('Category')->info($category_id);
			if(0 == $category['allow_publish']){
				$this->error('该分类禁止发布内容！');
			}
		} elseif(!in_array($type, array(1,3))) {
			$this->error('没有指定分类！');
		}


		/* 保存文档内容 */
		$Document = D('Document');
		$status = $Document->update();

		if($status){
			/* 保存成功，处理插件数据 */
			$param = array($status, $category);
			hook('documentSaveComplete', $param);

			$this->success('发布成功，请等待审核！', U('Article/lists?category='.$category['name']));
		} else {
			$this->error($Document->getError());
		}
	}

	/* 文档分类检测 */
	private function category($id = 0){
		/* 标识正确性检测 */
		$id = $id ? $id : I('get.category', 0);
		if(empty($id)){
			$this->error('请指定文档分类！');
		}

		/* 获取分类信息 */
		$category = D('Category')->info($id);
		if($category && 1 == $category['status']){
			switch ($category['display']) {
				case 0:
					$this->error('该分类禁止显示！');
					break;
				//TODO: 更多分类显示状态判断
				default:
					return $category;
			}
		} else {
			$this->error('该分类不存在或被禁用！');
		}
	}

}
