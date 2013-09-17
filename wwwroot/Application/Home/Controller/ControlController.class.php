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
 * 控制面板，仅管理员使用
 */
class ControlController extends HomeController {
	/* 控制面板首页 */
	public function index(){
		$this->display();
	}

	/* 站点设置 */
	public function setting(){
		if(IS_POST){ //提交数据
			$setting = I('post.web');
			if(empty($setting) || !is_array($setting)){
				$this->error('提交数据有误！');
			}

			/* 保存数据 */
			foreach ($setting as $name => $value) {
				$map = array('name' => $name);
				M('Setting')->where($map)->setField('value', $value);
			}

			$this->success('保存成功！', U('setting'));
		} else {
			$list = M('Setting')->getField('name,value');
			$this->assign('list', $list);
			$this->display();
		}
	}

	/* 频道管理 */
	public function channel(){
			/* 获取频道列表 */
			$map  = array('status' => 1);
			$list = D('Channel')->where($map)->select();
	
			$this->assign('list', $list);
			$this->display();
		}

	/* 分类列表 */
	public function category(){
		$tree = D('Category')->getTree();
		$this->assign('tree', $tree);
		C('_SYS_GET_CATEGORY_TREE_', true); //标记系统获取分类树模板
		$this->display();
	}

	/* 显示分类树，仅支持内部调 */
	public function categoryTree($tree = null){
		C('_SYS_GET_CATEGORY_TREE_') || $this->_empty();
		$this->assign('tree', $tree);
		$this->display('categorytree');
	}

	/* 编辑分类 */
	public function categoryEdit($id = null, $pid = 0){
		$Category = D('Category');

		if(IS_POST){ //提交表单
			if(false !== $Category->update()){
				$this->success('保存成功！');
			} else {
				$error = $Category->getError();
				$this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$cate = '';
			if($pid){
				/* 获取上级分类信息 */
				$cate = $Category->info($pid, 'id,name,title,status');
				if(!($cate && 1 == $cate['status'])){
					$this->error('指定的上级分类不存在或被禁用！');
				}
			}

			/* 获取分类信息 */
			$info = $id ? $Category->info($id) : '';

			$this->assign('info', $info);
			$this->assign('category', $cate);
			$this->display();
		}
	} 
}
