<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com> <http://www.thinkphp.cn>
// +----------------------------------------------------------------------

namespace Home\Logic;

/**
 * 文档模型子模型 - 应用模型
 */
class ArticleLogic extends BaseLogic{
	/* 自动验证规则 */
	protected $_validate = array(
		array('content', 'require', '内容不能为空！', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('version', 'require', '版本号不能为空！', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('index_url', 'require', '应用主页不能为空！', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('down_url', 'require', '下载地址不能为空！', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
		array('index_url', 'url', 'URL格式不正确！', self::MUST_VALIDATE , '', self::MODEL_BOTH),
		array('down_url', 'url', 'URL格式不正确！', self::MUST_VALIDATE , '', self::MODEL_BOTH),
	);

	/* 自动完成规则 */
	protected $_auto = array();

	/**
	 * 新增或添加一条应用详情
	 * @param  number $id 应用ID
	 * @return boolean    true-操作成功，false-操作失败
	 */
	public function update($id){
		/* 获取文章数据 */
		$data = $this->create();
		if(!$data){
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

}
