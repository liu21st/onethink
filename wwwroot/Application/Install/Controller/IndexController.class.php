<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Install\Controller;
use Think\Controller;
use Think\Storage;

class IndexController extends Controller{
	//安装首页
	public function index(){
		if(Storage::has(MODULE_PATH . 'Data/install.lock')){
			$this->error('已经成功安装了OneThink，请不要重复安装!');
		}
		session_destroy();
		session_start();
		session('step', 0);
		session('error', false);
		$this->display();
	}

	//安装完成
	public function complete(){
		$step = session('step');

		if(!$step){
			$this->redirect('index');
		} elseif($step != 3) {
			$this->redirect("Install/step{$step}");
		}

		Storage::put(MODULE_PATH . 'Data/install.lock', 'lock');
		//创建配置文件
		$this->assign('info',session('config_file'));

		session('step', null);
		session('error', null);
		$this->display();
	}
}
