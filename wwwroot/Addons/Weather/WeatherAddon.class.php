<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Author: xiongjun <xiongjunceplj@163.com> 
// +----------------------------------------------------------------------

namespace Addons\Weather;

use Common\Controller\Addon;
/**
 * 系统环境信息插件
 * @author cepljxiongjun
 */
class WeatherAddon extends Addon {

    public $info = array(
        'name' => 'Weather',
        'title' => '天气预报',
        'description' => '天气预报',
        'status' => 1,
        'author' => 'cepljxiongjun hainuo',
        'version' => '0.1.2'
    );
    public function install() {
        return true;
    }
    public function uninstall() {
        return true;
    }
	public function getAddress()
	{
		$config = $this->getConfig();
		$ip = get_client_ip();
		if($ip=='127.0.0.1')
			$ip='123.169.106.156';		
		$address = json_decode(file_get_contents($config['ipUrl']."?ak=".$config['ak']."&ip=".$ip."&coor=bd09ll"));
		trace(json_encode($address));
		return $location = (string)$address->content->address_detail->city;
	}
	public function getWeather(){
		$config = $this->getConfig();
		$url = $config['tianqiUrl']."?location=".urlencode($this->getAddress())."&ak=".$config['ak']."&output=json";
		$result = file_get_contents($url);
		$content = json_decode($result)->results[0];
		$lists=array();
		$lists['city'] = (string)$content->currentCity;
		$lists['showday'] = $config['showday'];
		$lists['data']=array();
		foreach($content->weather_data as $k=> $result){
			$lists['data'][$k]['date'] = (string)$result->date;
			$lists['data'][$k]['weather'] = $result->weather;
			$lists['data'][$k]['wind'] = (string)$result->wind;
			$lists['data'][$k]['temperature'] = (string)$result->temperature;
			$lists['data'][$k]['pictureUrl'] = (string)$result->dayPictureUrl;
			$lists['data'][$k]['nightPictureUrl']=(string)$result->nightPictureUrl;
		}
		if(!empty($lists))
			return $lists;

		
	}
    //实现的AdminIndex钩子方法
    public function AdminIndex() {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
		foreach($config['showplace'] as $k=>$v){
			if($v == '0'&&$config['display'])
				$this->display('widget');
		}            
    }
    //实现的pageTop钩子方法
	public function pageTop(){
		$config = $this->getConfig();
		$this->assign('width', $config['showday']*140);
		//$this->assign('location', $this->getAddress());
		$this->assign('lists', $this->getWeather());
        $this->assign('addons_config', $config);
		foreach($config['showplace'] as $k=>$v){
			if ($v == '1'&&$config['display'])
				$this->display('weather');
		}
	}
}

