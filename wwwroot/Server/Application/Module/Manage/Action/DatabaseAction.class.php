<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// DatabaseAction.class.php 2013-03-22

class DatabaseAction extends Action{
	/* 初始登录判断 */
	protected function _initialize(){
		is_login() || $this->error('未登录，请先登录', U('Index/login'));
	}

	/* 数据库备份首页，列出数据库列表 */
	public function index(){
		$Db = Db::getInstance();
		$list = $Db->query('SHOW TABLE STATUS');
		$this->assign('list', array_map('array_change_key_case', $list));
		$this->display();
	}

	/* 备份数据库 */
	public function export($tables = null, $id = null, $start = null){
		if(IS_POST && !empty($tables) && is_array($tables)){ //初始化
			//缓存要备份的表
			session('backup_tables', $tables);
			//生成备份文件信息
			$file = array(
				'name' => date('Ymd-His', NOW_TIME),
				'part' => 1,
			);
			session('backup_file', $file);
			//创建备份文件
			if(false !== D('Export')->create()){
				$tab = array('id' => 0, 'start' => 0);
				$this->success('初始化成功！', '', array('tables' => $tables, 'tab' => $tab));
			} else {
				$this->error('初始化失败，备份文件创建失败！');
			}
		} elseif (IS_GET && is_numeric($id) && is_numeric($start)) { //备份数据
			$file   = session('backup_file');
			$tables = session('backup_tables');
			//备份指定表
			$start  = D('Export')->backup($tables[$id], $start);
			if(false === $start){ //出错
				$this->error('备份出错！');
			} elseif (0 === $start) { //下一表
				if(isset($tables[++$id])){
					$tab = array('id' => $id, 'start' => 0);
					$this->success('备份完成！', '', array('tab' => $tab));
				} else {
					$this->success('备份完成！');
				}
			} else {
				$tab  = array('id' => $id, 'start' => $start[0]);
				$rate = floor(100*($start[0]/$start[1]));
				$this->success("正在备份...({$rate}%)", '', array('tab' => $tab));
			}

		} else { //出错
			$this->error('请指定要备份的表！');
		}
	}
}