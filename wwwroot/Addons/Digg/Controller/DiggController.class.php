<?php

namespace Addons\Digg\Controller;
use Home\Controller\AddonsController;

class DiggController extends AddonsController{
	public function vote(){
		$config = get_addon_config('Digg');
		$id = intval(I('id'));
		$type = intval(I('type'));
		$uid = is_login();
		if(!$uid)
			$this->error('请先登录再投票');
		$has_vote = M('Digg')->where("document_id={$id} AND uids like ',{$uid},'")->find();
		if(!$has_vote){
			$field = $type == '1' ? 'good' : 'bad';
			$data = array($field=>array('exp',"$field+1"), "uids"=>array('exp',"concat(uids,'$uid,')"));
			M('Digg')->where("document_id={$id}")->save($data);
			$this->success($config['post_sucess_tip']);
		}else{
			$this->error($config['post_error_tip']);
		}
	}
}
