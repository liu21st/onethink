<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Addons\Weather\Controller;
use Home\Controller\AddonsController;

class WeatherController extends AddonsController{

	//获取糗事百科列表
	public function getList(){
		$lists = S('Weather_content');
		var_dump($lists);
		if(!$lists){
			$config = get_addon_config('Weather');
			if($config && $config['city'])
				$city=$config['city'];
			else
				$city=$this->getAddress($config['ipUrl'],$config['ak']);
			$url = $config['tianqiUrl']."?location=".urlencode($city)."&ak=".$config['ak']."&output=json";
/**
**官方返回json数据模板
*
*   "error":0,
*   "status":"success",
*   "date":"2014-05-18",
*   "results":[  //注意此处多了一个[ 表示在results下面是一个数组  然后才是与天气有关的数据
*       {
*           "currentCity":"\u6dc4\u535a\u5e02",
*           "weather_data":[
*               {
*                   "date":"\u5468\u65e5(\u4eca\u5929, \u5b9e\u65f6\uff1a29\u2103)",
*                   "dayPictureUrl":"http:\/\/api.map.baidu.com\/images\/weather\/day\/qing.png",
*                   "nightPictureUrl":"http:\/\/api.map.baidu.com\/images\/weather\/night\/duoyun.png",
*                   "weather":"\u6674\u8f6c\u591a\u4e91",
*                   "wind":"\u5357\u98ce\u5fae\u98ce",
*                   "temperature":"31 ~ 18\u2103"
*               },
*               {
*                   "date":"\u5468\u4e00",
*                   "dayPictureUrl":"http:\/\/api.map.baidu.com\/images\/weather\/day\/duoyun.png",
*                   "nightPictureUrl":"http:\/\/api.map.baidu.com\/images\/weather\/night\/yin.png",
*                   "weather":"\u591a\u4e91\u8f6c\u9634",
*                   "wind":"\u5357\u98ce\u5fae\u98ce",
*                   "temperature":"29 ~ 17\u2103"
*               },
*               {
*                   "date":"\u5468\u4e8c",
*                   "dayPictureUrl":"http:\/\/api.map.baidu.com\/images\/weather\/day\/duoyun.png",
*                   "nightPictureUrl":"http:\/\/api.map.baidu.com\/images\/weather\/night\/qing.png",
*                   "weather":"\u591a\u4e91\u8f6c\u6674",
*                   "wind":"\u5317\u98ce\u5fae\u98ce",
*                   "temperature":"31 ~ 18\u2103"
*               },
*               {
*                   "date":"\u5468\u4e09",
*                   "dayPictureUrl":"http:\/\/api.map.baidu.com\/images\/weather\/day\/qing.png",
*                   "nightPictureUrl":"http:\/\/api.map.baidu.com\/images\/weather\/night\/qing.png",
*                   "weather":"\u6674",
*                   "wind":"\u5357\u98ce\u5fae\u98ce",
*                   "temperature":"34 ~ 20\u2103"
*               }
*           ]
*       }
*   ]
*
**/
			var_dump($url);
			$result = file_get_contents($url);
			$content = json_decode($result)->results[0];
			var_dump($content);
			$lists=array();
			$lists['city'] = (string)$content->currentCity;
			$lists['showday'] = $config['showday'];
			foreach($content->weather_data as $result){
				$lists['date'][] = (string)$result->date;
				$lists['weather'][] = (string)$result->weather;
				$lists['wind'][] = (string)$result->wind;
				$lists['temperature'][] = (string)$result->temperature;
				$lists['pictureUrl'][] = (string)$result->dayPictureUrl;
				$lists['nightPictureUrl'][]=(string)$result->nightPictureUrl;
			}
        }
        if($lists){
        	$this->success('成功', '', array('data'=>$lists));
        	//$this->ajaxReturn( array('data' => $lists , ));
        }else{
        	$this->error('天气列表失败');
        }
	}
	public function getAddress($ipUrl, $ak)
	{
		$ip = get_client_ip();
		if($ip=='127.0.0.1')
			$ip='123.169.106.156';
		$url = $ipUrl ."?ak=".$ak."&ip=".$ip."&coor=bd09ll";
		$address = json_decode(file_get_contents($url));
		return $location = (string)$address->content->address_detail->city;
	}
}
