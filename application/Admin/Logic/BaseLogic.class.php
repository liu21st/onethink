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
class BaseLogic extends Model {

    /* 自动验证规则 */
    protected $_validate    =   array();

    /* 自动完成规则 */
    protected $_auto        =   array();

    /**
     * 构造函数
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
    public function detail($id) {
        if ($this->getDbFields() == false) {
            $data = array();
        } else {
            $data = $this->field(true)->find($id);
            if (!$data) {
                $this->error = '获取详细信息出错！';
                return false;
            }
        }
        return $data;
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

    /**
     * 模型数据自动保存
     * @return boolean
     */
    public function autoSave($id = 0) {
        $this->_validate = array();
        return $this->update($id);
    }

    /**
     * 检测属性的自动验证和自动完成属性
     * @return boolean
     */
    public function checkModelAttr($model_id){
        $fields     =   get_model_attribute($model_id,false);
        $validate   =   $auto   =   array();
        foreach($fields as $key=>$attr){
            if($attr['is_must']){// 必填字段
                $validate[]  =  array($attr['name'],'require',$attr['title'].'必须!',self::MUST_VALIDATE , 'regex', self::MODEL_BOTH);
            }
            // 自动验证规则
            if(!empty($attr['validate_rule'])) {
                $validate[]  =  array($attr['name'],$attr['validate_rule'],$attr['error_info']?$attr['error_info']:$attr['title'].'验证错误',0,$attr['validate_type'],$attr['validate_time']);
            }
            // 自动完成规则
            if(!empty($attr['auto_rule'])) {
                $auto[]  =  array($attr['name'],$attr['auto_rule'],$attr['auto_time'],$attr['auto_type']);
            }elseif('checkbox'==$attr['type']){ // 多选型
                $auto[] =   array($attr['name'],'arr2str',3,'function');
            }elseif('datetime' == $attr['type'] || 'date' == $attr['type']){ // 日期型
                $auto[] =   array($attr['name'],'strtotime',3,'function');
            }
        }
        $validate   =   array_merge($validate,$this->_validate);
        $auto       =   array_merge($auto,$this->_auto);
        return $this->validate($validate)->auto($auto);
    }
}
