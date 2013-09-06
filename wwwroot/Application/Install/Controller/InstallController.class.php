<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Install\Controller;
use Think\Action;

class InstallController extends Action{
	//安装第一步，检测运行所需的环境设置
	public function step1(){
		//环境检测
		$env = check_env();

		//目录文件读写检测
		$dirfile = check_dirfile();

		//函数检测
		$func = check_func();

		$this->assign('env', $env);
		$this->assign('dirfile', $dirfile);
		$this->assign('func', $func);
		$this->display();
	}

	//安装第二步，设置程序运行配置
	public function step2(){
		if(IS_POST){

		} else {
			$this->display();
		}
	}

	//安装第三步，创建数据库
	public function step3($db = null, $admin = null){
		if(IS_POST){
			// //检测管理员信息
			// if(!is_array($admin) || empty($admin[0]) || empty($admin[1]) || empty($admin[3])){
			// 	$this->error('请填写完整管理员信息');
			// } else if($admin[1] != $admin[2]){
			// 	$this->error('确认密码和密码不一致');
			// } else {
			// 	$info = array();
			// 	list($info['username'], $info['password'], $info['email'], $info['mobile'])
			// 	= $admin;
			// 	//缓存管理员信息
			// 	F('admin_info', $info);
			// }

			// //检测数据库配置
			// if(!is_array($db) || empty($db[0]) ||  empty($db[1]) || empty($db[2]) || empty($db[3])){
			// 	$this->error('请填写完整的数据库配置');
			// } else {
			// 	$DB = array();
			// 	list($DB['DB_TYPE'], $DB['DB_HOST'], $DB['DB_NAME'], $DB['DB_USER'], $DB['DB_PWD'], 
			// 		 $DB['DB_PORT'], $DB['DB_PREFIX']) = $db;
			// 	//缓存数据库配置
			// 	F('db_config', $DB);

			// 	//创建数据库
			// 	$dbname = $DB['DB_NAME'];
			// 	unset($DB['DB_NAME']);
			// 	$db  = \Think\Db::getInstance($DB);
			// 	$sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
			// 	$db->execute($sql) || $this->error($db->getError());
			// }

			// //跳转到数据库安装页面
			$this->redirect('step4');
		} else {
			$this->display();
		}
	}

	//安装第四步，安装数据表，创建配置文件
	public function step4(){
		$this->display();

		//连接数据库
		// $dbconfig = F('db_config');
		// $db = Db::getInstance($dbconfig);

		// //创建数据表
		// create_tables($db, $dbconfig['DB_PREFIX']);

		// //创建配置文件
		// write_config($dbconfig);

		// //创建入口文件
		// write_index();
	}
}
