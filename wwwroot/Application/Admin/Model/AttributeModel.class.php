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
        array('name', 'require', '行为标识必须', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '/^[a-zA-Z]\w{0,39}$/', '标识不合法', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '', '标识已经存在', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', '1,80', '标题长度不能超过80个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
        array('remark', 'require', '行为描述不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('remark', '1,140', '行为描述不能超过140个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
    );

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

        /* 添加或新增属性 */
        if(empty($data['id'])){ //新增属性

        	//检查数据表是否存在
        	if(empty($data['model_id'])){
        		$this->error = '未指定模型！';
        		return false;
        	}elseif(!$this->checkTableExist($data['model_id'])){
        		return false;
        	}

            $id = $this->add();
            if(!$id){
                $this->error = '新增属性出错！';
                return false;
            }else{
            	//新增表字段

            }
        } else { //更新数据
            $status = $this->save();
            if(false === $status){
                $this->error = '更新属性出错！';
                return false;
            }
        }

        //内容添加或更新完成
        return $data;

    }

    public function checkTableExist($model_id){

    }

}
