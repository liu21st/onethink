<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 分类模型
 */
class AttachmentModel extends Model{

	/**
	 * 附件模型自动完成
	 * @var array
	 */
	protected $_auto = array(
		array('uid', 'session', self::MODEL_INSERT, 'function', 'user_auth.uid'),
		array('download', 0, self::MODEL_INSERT),
		array('sort', 0, self::MODEL_INSERT),
		array('create_time', NOW_TIME, self::MODEL_INSERT),
		array('update_time', NOW_TIME, self::MODEL_BOTH),
		array('status', 1, self::MODEL_BOTH),
	);

	/**
	 * 保存文件附件到数据库
	 * @param  string  $title  附件标题
	 * @param  array   $file   文件数据
	 * @param  number  $record 关联记录ID
	 * @param  integer $dir    是否为目录
	 * @return boolean         
	 */
	public function saveFile($title, $file, $record, $dir = 0){
		$data = array(
			'title'     => $title,
			'type'      => 2,
			'source'    => $file['id'],
			'record_id' => $record,
			'dir'       => $dir,
			'size'      => $file['size'],
		);

		/* 保存附件 */
		if($this->create($data) && $this->add()){
			return true;
		} else {
			return false;
		}
	}

	public function saveDir(){

	}

	/**
	 * 下载附件
	 * @param  number $id 附件ID
	 * @return boolean    下载失败返回false
	 */
	public function download($id){
		$info = $this->field(true)->find($id);
		if($info && $info['status'] == 1){
			/* 下载附件 */
			$this->downloadId = $id;
			switch($info['type']){
				case 0:
					//TODO: 下载目录？
					break;
				case 1:
					//TODO: 下载外部附件
					break;
				case 2:
					$File = D('File');
					$root = C('ATTACHMENT_UPLOAD.rootPath');
					$call = array($this, 'setDownload');
					if(false === $File->download($root, $info['source'], $call, $id)){
						$this->error = $File->getError();
					}
					break;
				default:
					$this->error = '无效附件类型！';
			}
		} else {
			$this->error = '附件已删除或被禁用！';
		}
		return false;
	}

	/**
	 * 新增下载次数（File模型回调方法）
	 */
	public function setDownload($id){
		$map = array('id' => $id);
		$this->where($map)->setInc('download');
	}

}
