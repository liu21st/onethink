<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 属性模型
 * @author huajie <banhuajie@163.com>
 */

class AttributeModel extends Model {

    /* 自动验证规则 */
    protected $_validate = array(
        array('name', 'require', '字段名必须', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '/^[a-zA-Z]{1,30}$/', '字段名不合法', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    	array('name', '', '字段名已存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
    	array('field', 'require', '字段定义必须', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    	array('field', '1,100', '注释长度不能超过100个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
    	array('type', 'require', '数据类型必须', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,100', '注释长度不能超过100个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
        array('remark', '1,100', '备注不能超过100个字符', self::VALUE_VALIDATE, 'length', self::MODEL_BOTH),
    	array('model_id', 'require', '未选择操作属性的模型', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
    	array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
    );

    /* 操作的表名 */
    protected $table_name = null;

    /**
     * 新增或更新一个属性
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     * @author huajie <banhuajie@163.com>
     */
    public function update(){
        /* 获取数据对象 */
        $data = $this->create($_POST);
        if(empty($data)){
            return false;
        }

        //检查数据表是否存在
        if(empty($data['model_id'])){
        	$this->error = '未指定模型！';
        	return false;
        }
//         $this->checkTableExist($data['model_id']);

        /* 添加或新增属性 */
        if(empty($data['id'])){ //新增属性

            $id = $this->add();
            if(!$id){
                $this->error = '新增属性出错！';
                return false;
            }
            //新增表字段
			$res = $this->addField($data);
			if(!$res){
				$this->error = '新建字段出错！';
				return false;
			}
        } else { //更新数据
            $status = $this->save();
            if(false === $status){
                $this->error = '更新属性出错！';
                return false;
            }
            //更新表字段
            $this->addField($data);
        }

        //内容添加或更新完成
        return $data;

    }

    /**
     * 检查当前表是否存在，没有则新建
     * @param intger $model_id 模型id
     * @return boolean true 新建成功
     * @author huajie <banhuajie@163.com>
     */
    public function checkTableExist($model_id){
    	$Model = M('Model');
    	//当前操作的表
		$model = $Model->where(array('id'=>$model_id))->field('name,extend')->find();

		if($model['extend'] == 0){	//独立模型表名
			$table_name = $this->table_name = C('DB_PREFIX').strtolower($model['name']);
		}else{						//继承模型表名
			$extend_model = $Model->where(array('id'=>$model['extend']))->field('name,extend')->find();
			$table_name = $this->table_name = C('DB_PREFIX').strtolower($extend_model['name']).'_'.strtolower($model['name']);
		}
		$sql = <<<sql
				SHOW TABLES LIKE '{$table_name}';
sql;
		$res = M()->query($sql);
		return count($res);
    }

    public function createTable(){

    }

    protected function addField($field){
    	//检查表是否存在
    	$table_exist = $this->checkTableExist($field['model_id']);

    	if($table_exist){
    		$fields = M()->query('SHOW COLUMNS FROM '.$this->table_name);
    		$last_field = end($fields);
    		$sql = <<<sql
				ALTER TABLE `{$this->table_name}`
ADD COLUMN `{$field['name']}`  {$field['field']} DEFAULT '{$field['value']}' COMMENT '{$field['title']}';
sql;
    	}else{		//新建表时默认新增“id主键”字段
    		$sql = <<<sql
				CREATE TABLE IF NOT EXISTS `{$this->table_name}` (
				`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键' ,
				`{$field['name']}`  {$field['field']} DEFAULT '{$field['value']}' COMMENT '{$field['title']}' ,
				PRIMARY KEY (`id`)
				)
				ENGINE=MyISAM
				DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
				CHECKSUM=0
				ROW_FORMAT=DYNAMIC
				DELAY_KEY_WRITE=0
				;
sql;
    	}

    	$res = M()->execute($sql);
    	return $res !== false;
    }

}
