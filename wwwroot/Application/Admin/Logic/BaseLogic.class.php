<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Logic;
use Think\Model;

/**
 * 文档模型逻辑层公共模型
 * 所有逻辑层模型都需要继承此模型
 */
abstract class BaseLogic extends Model{

	/**
	 * 构造函数，用于这是Logic层的表前缀
	 * @param string $name 模型名称
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
	 */
	public function __construct($name = '', $tablePrefix = '', $connection = '') {
		/* 设置默认的表前缀 */
		$this->tablePrefix = C('DB_PREFIX') . 'document_';
		/* 执行构造方法 */
		parent::__construct($name, $tablePrefix, $connection);
	}

	/**
	 * 获取模型详细信息
	 * @param  integer $id 文档ID
	 * @return array       当前模型详细信息
	 */
	public function detail($id){
		$data = $this->field(true)->find($id);
		if(!$data){
			$this->error = '获取详细信息出错！';
			return false;
		}
		return $data;
	}

	/**
	 * 获取段落列表
	 * @param  array $ids 要获取的段落ID列表
	 * @return array      段落数据列表
	 */
	public function lists($ids){
		$map = array();
		if(1 === count($ids)){
			$map['id'] = $ids[0];
		} else {
			$map['id'] = array('in', $ids);
		}

		$data = $this->field(true)->where($map)->select();
		$list = array();
		foreach ($data as $value) {
			$list[$value['id']] = $value;
		}
		return $list;
	}

	/**
	 * 新增或者更新数据
	 * @author huajie <banhuajie@163.com>
	 */
	abstract public function update($id = 0);

	/**
	 * 保存为草稿
	 * @author huajie <banhuajie@163.com>
	 */
	abstract public function autoSave($id = 0);
}
