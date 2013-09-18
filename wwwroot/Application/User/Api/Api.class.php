<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace User\Api;
define('UC_CLIENT_PATH', dirname(dirname(__FILE__)));

//载入配置文件
require_cache(UC_CLIENT_PATH . '/Conf/config.php');

//载入函数库文件
require_cache(UC_CLIENT_PATH . '/Common/common.php');

/**
 * UC API调用控制器层
 * 调用方法 A('Uc/User', 'Api')->login($username, $password, $type);
 */
abstract class Api{

	/**
	 * API调用模型实例
	 * @access  protected
	 * @var object
	 */
	protected $model;

	/**
	 * 构造方法，检测相关配置
	 */
	public function __construct(){
		//相关配置检测
		defined('UC_APP_ID') || throw_exception('UC配置错误：缺少UC_APP_ID');
		defined('UC_API_TYPE') || throw_exception('UC配置错误：缺少UC_APP_API_TYPE');
		defined('UC_AUTH_KEY') || throw_exception('UC配置错误：缺少UC_APP_AUTH_KEY');
		defined('UC_DB_DSN') || throw_exception('UC配置错误：缺少UC_DB_DSN');
		defined('UC_TABLE_PREFIX') || throw_exception('UC配置错误：缺少UC_TABLE_PREFIX');
		if(UC_API_TYPE != 'Model' && UC_API_TYPE != 'Service'){
			throw_exception('UC配置错误：UC_API_TYPE只能为 Model 或 Service');
		}
		if(UC_API_TYPE == 'Service' && UC_AUTH_KEY == ''){
			throw_exception('UC配置错误：Service方式调用Api时UC_AUTH_KEY不能为空');
		}
		if(UC_API_TYPE == 'Model' && UC_DB_DSN == ''){
			throw_exception('UC配置错误：Model方式调用Api时UC_DB_DSN不能为空');
		}

		$this->_init();
	}

	/**
	 * 抽象方法，用于设置模型实例
	 */
	abstract protected function _init();

}
