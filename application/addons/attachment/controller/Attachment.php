<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\addons\attachment\controller;
use app\home\controller\Addons;

class Attachment  extends Addons{
	
	/* 附件下载 */
	public function download(){
		/* 获取附件ID */
		$id = input('get.id');
		if(empty($id) || !is_numeric($id)){
			$this->error('附件ID无效！');
		}

		/* 下载附件 */
		$Attachment = model('Addons://Attachment/Attachment');
		if(false === $Attachment->download($id)){
			$this->error($Attachment->getError());
		}

	}

	/* 上传附件 */
	public function upload(){
		/* 返回标准数据 */
		$return  = array('status' => 1, 'info' => '上传成功', 'data' => '');

		/* 获取当前分类附件配置信息 */
		$default  = config('ATTACHMENT_DEFAULT');
		$category = get_category(input('get.category'));

		/* 分类正确性检测 */
		if(empty($category)){
			$return['status'] = 0;
			$return['info']   = '没有指定分类或分类不正确；';
		} else {
			$config = $category['extend']['attachment'];
			$config = empty($config) ? $default : array_merge($default, $config);

			/* 检测并上传附件 */
			if(in_array('2', str2arr($config['allow_type']))){
				$setting = config('ATTACHMENT_UPLOAD');

				/* 调用文件上传组件上传文件 */
				$File = model('File');
				$info = $File->upload($_FILES, $setting, $config['driver'], $config['driver_config']);
				/* 记录附件信息 */
				if($info){
					$return['data'] = think_encrypt(json_encode($info['attachment']));
				} else {
					$return['status'] = 0;
					$return['info']   = $File->getError();
				}
			} else {
				$return['info']   = '该分类不允许上传文件附件！';
				$return['status'] = 0;
			}
		}

		/* 返回JSON数据 */
		$this->ajaxReturn($return);
	}

}
