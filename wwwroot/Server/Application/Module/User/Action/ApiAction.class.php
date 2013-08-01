<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// ApiAction.class.php 2013-03-18

/**
 * api基类，所有api请求都需要继承次ACTION
 * 获取数据方法：$this->data();
 * 返回数据方法：$this->returnData();
 */
class ApiAction{
	/**
	 * 应用配置信息
	 * @var string
	 */
	private $dataAuthKey;

	/**
	 * 构造方法，用于设置应用信息
	 */
	public function __construct(){
		//获取应用配置信息
		$app_id = intval($_GET['id']);
		$app = M('App')->field('auth_key,status')->find($app_id);
		if(is_array($app) && 1 == $app['status']){
			$this->dataAuthKey = $app['auth_key'];
		} else {
			$this->returnData(-100); //应用配置错误
		}
	}

	/**
	 * 返回加密数据
	 * @param  array $data 需要输出的数据
	 */
	protected function returnData($data){
		header('Content-Type:text/plain; charset=utf-8');
        exit(think_ucenter_encrypt(json_encode($data), $this->dataAuthKey, 10));
	}

	/**
	 * 获取请求数据
	 * @return array 解析后的请求数据
	 */
	protected function data(){
		if(IS_POST){
			// 获取POST数据
			$data = $_POST['data'];
		} else if(IS_GET){
			// 获取GET数据
			$data = $_GET['data'];
		} else {
			$this->returnData(-101); //不支持的请求方式
		}

		/* 解密数据 */
		$data = think_ucenter_decrypt($data, $this->dataAuthKey);
		if('' === $data){
			$this->returnData(-102); //提交数据非法
		}

		//返回数据
		return json_decode($data, true);
	}
}