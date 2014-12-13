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
 * 文档模型子模型 - 文章模型
 */
class ArticleLogic extends BaseLogic{
	/* 自动验证规则 */
	protected $_validate = array(
		array('content', 'require', '内容不能为空！', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
	);

	/* 自动完成规则 */
	protected $_auto = array();

	/**
	 * 新增或添加一条文章详情
	 * @param  number $id 文章ID
	 * @return boolean    true-操作成功，false-操作失败
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function update($id){
		/* 获取文章数据 */ //TODO: 根据不同用户获取允许更改或添加的字段
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
