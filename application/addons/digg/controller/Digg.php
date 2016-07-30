<?php

namespace app\addons\digg\controller;

use app\home\controller\Addons;
class Digg  extends Addons{
	public function vote(){
		$config = get_addon_config('Digg');
		$id = intval(input('id'));
		$type = intval(input('type'));
		$uid = is_login();
		if(!$uid)
			$this->error('请先登录再投票');
		$has_vote = db('Digg')->where("document_id={$id} AND uids like '%,{$uid},%'")->find();
		if(!$has_vote){
			$field = $type == '1' ? 'good' : 'bad';
			$data = array($field=>array('exp',"$field+1"), "uids"=>array('exp',"concat(uids,'$uid,')"));
			db('Digg')->where("document_id={$id}")->save($data);
			$this->success($config['post_sucess_tip']);
		}else{
			$this->error($config['post_error_tip']);
		}
	}
}
