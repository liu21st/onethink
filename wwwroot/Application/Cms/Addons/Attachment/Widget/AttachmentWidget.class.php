<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 插件widget
 * 用于动态调用各插件相关组件
 */

class AttachmentWidget extends AddonsController{

	/* 页面头部输出CSS文件 */
	public function pageHeader(){
		if(CONTROLLER_NAME == 'Article' && ACTION_NAME == 'edit' && I('get.model') != 2){
			$this->display('Uploadify/style');
		} elseif(CONTROLLER_NAME == 'Article' && ACTION_NAME == 'detail') {
			$this->display('Article/style');
		}
	}

	/* 显示文档模型编辑页插件扩展信息表单 */
	public function documentEditForm($param = array()){
		$this->assign($param);
		$this->display('Article/edit');
	}

	/* 文档末尾显示附件列表 */
	public function documentDetailAfter($info = array()){
		if(empty($info) || empty($info['id'])){ //数据不正确
			return ;
		}

		/* 获取当前文档附件 */
		$Attachment = D('Addons://Attachment/Attachment');
		$map = array('record_id' => $info['id'], 'status' => 1);
		$list = $Attachment->field(true)->where($map)->select();
		if(!$list){ //不存在附件
			return ;
		}

		/* 模板赋值并渲染模板 */
		$this->assign('list', $list);
		$this->display('Article/detail');
	}

	/* 页面结尾输出附件JS */
	public function pageFooter(){
		if(CONTROLLER_NAME == 'Article' && ACTION_NAME == 'edit' && I('get.model') != 2){
			$this->display('Uploadify/script');
		}
	}
	
}
