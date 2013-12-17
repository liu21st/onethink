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
 * 文档基础模型
 */
class ModelModel extends Model{

    /* 自动验证规则 */
    protected $_validate = array(
        array('name', 'require', '标识不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_INSERT),
        array('name', '/^[a-zA-Z]\w{0,39}$/', '文档标识不合法', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '', '标识已经存在', self::VALUE_VALIDATE, 'unique', self::MODEL_BOTH),
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,30', '标题长度不能超过30个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    	array('list_grid', 'require', '列表定义不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_UPDATE),
    );

    /* 自动完成规则 */
    protected $_auto = array(
    	array('name', 'strtolower', self::MODEL_INSERT, 'function'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_INSERT, 'string'),
    	array('field_sort', 'getFields', self::MODEL_BOTH, 'callback'),
    );

    /**
     * 新增或更新一个文档
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     * @author huajie <banhuajie@163.com>
     */
    public function update(){
        /* 获取数据对象 */
        $data = $this->create();
        if(empty($data)){
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $id = $this->add(); //添加基础内容
            if(!$id){
                $this->error = '新增模型出错！';
                return false;
            }
        } else { //更新数据
            $status = $this->save(); //更新基础内容
            if(false === $status){
                $this->error = '更新模型出错！';
                return false;
            }
        }
		// 清除模型缓存数据
		S('DOCUMENT_MODEL_LIST', null);

		//记录行为
		action_log('update_model','model',$data['id'] ? $data['id'] : $id,UID);

        //内容添加或更新完成
        return $data;
    }

    /**
     * 处理字段排序数据
     * @author huajie <banhuajie@163.com>
     */
    protected function getFields($fields){
    	return empty($fields) ? '' : json_encode($fields);
    }

    /**
     * 获取指定数据库的所有表名
     * @author huajie <banhuajie@163.com>
     */
    public function getTables($connection = null){
    	$tables = M()->query('SHOW TABLES;');
    	foreach ($tables as $key=>$value){
    		$tables[$key] = $value['Tables_in_'.C('DB_NAME')];
    	}
    	return $tables;
    }

    /**
     * 根据数据表生成模型及其属性数据
     * @author huajie <banhuajie@163.com>
     */
    public function generate($table){
    	//新增模型数据
    	$name = substr($table, strlen(C('DB_PREFIX')));
    	$data = array('name'=>$name, 'title'=>$name);
    	$data = $this->create($data);
    	$res = $this->add($data);
    	if(!$res){
    		return false;
    	}

    	//新增属性
		$fields = M()->query('SHOW FULL COLUMNS FROM '.$table);
		foreach ($fields as $key=>$value){
			//不新增id字段
			if(strcmp($value['Field'], 'id') == 0){
				continue;
			}

			//生成属性数据
			$data = array();
			$data['name'] = $value['Field'];
			$data['title'] = $value['Comment'];
			$data['type'] = 'string';	//TODO:根据字段定义生成合适的数据类型
			//获取字段定义
			$is_null = strcmp($value['Null'], 'NO') == 0 ? ' NOT NULL ' : ' NULL ';
			$data['field'] = $value['Type'].$is_null;
			$data['value'] = $value['Default'] == null ? '' : $value['Default'];
			$data['model_id'] = $res;
			$_POST = $data;		//便于自动验证
			D('Attribute')->update($data, false);
		}
    	return $res;
    }

    /**
     * 删除一个模型
     * @param integer $id 模型id
     * @author huajie <banhuajie@163.com>
     */
    public function del($id){
    	//获取表名
    	$model = $this->field('name')->find($id);
    	$table_name = C('DB_PREFIX').strtolower($model['name']);
    	//删除属性数据
    	M('Attribute')->where(array('model_id'=>$id))->delete();
    	//删除模型数据
    	$this->delete($id);
    	//删除该表
    	$sql = <<<sql
				DROP TABLE {$table_name};
sql;
    	$res = M()->execute($sql);
    	return $res !== false;
    }
}
