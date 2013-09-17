<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Logic;

/**
 * 文档模型子模型 - 下载模型
 */
class DownloadLogic extends BaseLogic{

	/* 自动验证规则 */
	protected $_validate = array(
		array('content', 'require', '内容不能为空！', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
	);

	/* 自动完成规则 */
	protected $_auto = array();

	public function update($id){
		/* 获取下载数据 */ //TODO: 根据不同用户获取允许更改或添加的字段
		$data = $this->field('download', true)->create();
		if(!$data){
			return false;
		}

		$file = json_decode(think_decrypt(I('post.file')), true);
		if(!empty($file)){
			$data['file_id'] = $file['id'];
			$data['size']    = $file['size'];
		} else {
			$this->error = '获取上传文件信息失败！';
			return false;
		}
		
		/* 添加或更新数据 */
		if(empty($data['id'])){//新增数据
			$data['id'] = $id;
			$id = $this->add($data);
			if(!$id){
				$this->error = '新增详细内容失败！';
				return false;
			}
		} else { //更新数据
			$status = $this->save($data);
			if(false === $status){
				$this->error = '更新详细内容失败！';
				return false;
			}
		}

		return true;
	}

	/**
	 * 下载文件
	 * @param  number $id 文档ID
	 * @return boolean    下载失败返回false
	 */
	public function download($id){
		$info = $this->find($id);
		if(empty($info)){
			$this->error = "不存在的文档ID：{$id}";
			return false;
		}

		$File = D('File');
		$root = C('DOWNLOAD_UPLOAD.rootPath');
		$call = array($this, 'setDownload');
		if(false === $File->download($root, $info['file_id'], $call, $info['id'])){
			$this->error = $File->getError();
		}
	}

	/**
	 * 新增下载次数（File模型回调方法）
	 */
	public function setDownload($id){
		$map = array('id' => $id);
		$this->where($map)->setInc('download');
	}

}
