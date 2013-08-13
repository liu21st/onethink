<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 插件controller
 * 用于动态调用各插件相关控制器
 */

class AttachmentController {
	/**
	 * 文档保存成功后执行行为
	 * @param  array  $data     文档数据
	 * @param  array  $catecory 分类数据
	 */
	public function documentSaveComplete($param){
		list($data, $catecory) = $param;
		/* 附件默认配置项 */
		$default  = C('ATTACHMENT_DEFAULT');

		/* 合并当前配置 */
		$config = $category['extend']['attachment'];
		$config = empty($config) ? $default : array_merge($default, $config);
		$attach = I('post.attachment');

		/* 该分类不允许上传附件 */
		if(!$config['is_upload'] || !in_array($attach['type'], str2arr($config['allow_type']))){
			return ;
		}

		switch ($attach['type']) {
			case 1: //外链
				# code...
				break;
			case 2: //文件
				$info = json_decode(think_decrypt($attach['info']), true);
				if(!empty($info)){
					$Attachment = D('Addons://Attachment/Attachment');
					$Attachment->saveFile($info['name'], $info, $data['id']);
				} else {
					return; //TODO:非法附件上传，可记录日志
				}
				break;
		}

	}
}
