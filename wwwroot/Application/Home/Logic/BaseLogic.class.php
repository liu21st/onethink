<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Logic;
use Think\Model;

/**
 * 文档模型逻辑层公共模型
 * 所有逻辑层模型都需要继承此模型
 */
class BaseLogic extends Model{

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
     * 新增或添加模型数据
     * @param  number $id 文章ID
     * @return boolean    true-操作成功，false-操作失败
     */
    public function update($id = 0) {
        /* 获取数据 */
        $data = $this->create();
        if ($data === false) {
            return false;
        }

        if (empty($data['id'])) {//新增数据
            $data['id'] = $id;
            $id = $this->add($data);
            if (!$id) {
                $this->error = '新增数据失败！';
                return false;
            }
        } else { //更新数据
            $status = $this->save($data);
            if (false === $status) {
                $this->error = '更新数据失败！';
                return false;
            }
        }
        return true;
    }
}
