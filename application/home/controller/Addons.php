<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\home\controller;

use think\Controller;
/**
 * 扩展控制器
 * 用于调度各个扩展的URL访问需求
 */
class Addons  extends Controller{

	public function _initialize(){
		/* 读取数据库中的配置 */
        $config = cache('DB_CONFIG_DATA');
        if(!$config){
            $config = api('Config/lists');
            cache('DB_CONFIG_DATA',$config);
        }
        config($config); //添加配置
	}

	protected $addons = null;

	public function execute($_addons = null, $_controller = null, $_action = null){
		if(config('URL_CASE_INSENSITIVE')){
			$_addons = ucfirst(parse_name($_addons, 1));
			$_controller = parse_name($_controller,1);
		}

	 	$TMPL_PARSE_STRING = config('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__ADDONROOT__'] = __ROOT__ . "/Addons/{$_addons}";
        config('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);

		if(!empty($_addons) && !empty($_controller) && !empty($_action)){
			$Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
		} else {
			$this->error('没有指定插件名称，控制器或操作！');
		}
	}

}
