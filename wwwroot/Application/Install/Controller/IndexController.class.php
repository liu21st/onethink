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

class IndexController extends Controller{
	//安装首页
	public function index(){ 
		if(is_file(MODULE_PATH . 'Data/install.lock')){
			$this->error('已经成功安装了OneThink，请不要重复安装!', U('Home/Index/index'));
		}
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

		//创建入口文件
		write_index();
		file_put_contents(MODULE_PATH . 'Data/install.lock', '');
		
		session('step', null);
		session('error', null);
		$this->display();
	}
}