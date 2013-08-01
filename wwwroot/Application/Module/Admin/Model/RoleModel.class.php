<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// 角色模型
class RoleModel extends CommonModel {
	protected $_validate = array(
		array('name','require','名称必须'),
		);

	protected $_auto		=	array(
		array('create_time','time','function',self::MODEL_INSERT),
		array('update_time','time','function',self::MODEL_UPDATE),
		);


	function getGroupUserList($groupId)
	{
		$table = $this->tablePrefix.'role_user';
		$rs = $this->db->query('select b.id,b.nickname from '.$table.' as a ,'.$this->tablePrefix.'auth as b where a.user_id=b.id and  a.role_id='.$groupId.' ');
		return $rs;
	}

	function delGroupUser($groupId)
	{
		$table = $this->tablePrefix.'role_user';

		$result = $this->db->execute('delete from '.$table.' where role_id='.$groupId);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}

	function setGroupUser($groupId,$userId) {
		$sql	=	"INSERT INTO ".$this->tablePrefix.'role_user (role_id,user_id) values ('.$groupId.','.$userId.')';
		$result	=	$this->execute($sql);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}

	function setGroupUsers($groupId,$userIdList)
	{
		if(empty($userIdList)) {
			return true;
		}
		if(is_string($userIdList)) {
			$userIdList = explode(',',$userIdList);
		}
		array_walk($userIdList, array($this, 'fieldFormat'));
		$userIdList	 =	 implode(',',$userIdList);
		$where = 'a.id ='.$groupId.' AND b.id in('.$userIdList.')';
		$rs = $this->execute('INSERT INTO '.$this->tablePrefix.'role_user (role_id,user_id) SELECT a.id, b.id FROM '.$this->tablePrefix.'role a, '.$this->tablePrefix.'auth b WHERE '.$where);
		if($result===false) {
			return false;
		}else {
			return true;
		}
	}

    protected function fieldFormat(&$value)
    {
        if(is_int($value)) {
            $value = intval($value);
        } else if(is_float($value)) {
            $value = floatval($value);
        }else if(is_string($value)) {
            $value = '"'.addslashes($value).'"';
        }
        return $value;
    }

}
?>