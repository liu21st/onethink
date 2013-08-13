<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

class AttributeModel extends CommonModel {
	protected $_validate	 =	 array(
		array('name','/^[A-Za-z]\w+$/','变量名错误！'),
		array('name','checkName','变量已经定义',self::MODEL_BOTH,'callback'),
        array('create_time','time',Model::MODEL_INSERT,'function'),
        array('update_time','time',Model::MODEL_BOTH,'function'),
		);

    protected $_auto    =   array(
        array('auto','writeValue',Model::MODEL_BOTH,'callback'),
        array('validate','writeValue',Model::MODEL_BOTH,'callback'),
        array('field','buildField',Model::MODEL_BOTH,'callback'),
        );

    public function writeValue($value){
        if(empty($value[0])) {
            return '';
        }
        $value  =   implode(',',$value);
        return $value;
    }

    public function buildField($value){
        if(empty($value) && !empty($_POST['field_type'])) {
            $value  =   $_POST['field_type'];
            if(!empty($_POST['field_length'])) {
                $value .= '('.$_POST['field_length'].') ';
            }
            if(!empty($_POST['field_attribute'])) {
                $value .= ' '.$_POST['field_attribute'].' ';
            }
            $value .= ' '.$_POST['field_null'].' ';
            if('NULL'==$_POST['field_default'] || '' == $_POST['field_default']) {
                $value .= strpos($_POST['field_type'],'int')?" DEFAULT 0 ":" DEFAULT NULL ";
            }else{
                $value .= strpos($_POST['field_type'],'int')?" DEFAULT {$_POST['field_default']} ":" DEFAULT '{$_POST['field_default']}' ";
            }
        }
        return $value;
    }
	public function checkName($name) {
        // 检查是否内置属性
        if(strtolower($name) =='id') {
            return false;
        }
        // 检查是否已经定义
		$map['name']	 =	 $_POST['name'];
        if(!empty($_POST['id'])) {
			$map['id']	=	array('neq',$_POST['id']);
        }
        $array['model_id']   =  $_POST['model_id'];
        $array['is_common'] =  1;
        $map['_complex'] = $array;
		$result	=	$this->where($map)->getField('id');
        if($result) {
        	return false;
        }else{
			return true;
		}
	}
}
?>