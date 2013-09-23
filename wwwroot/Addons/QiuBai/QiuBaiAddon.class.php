<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------


namespace Addons\QiuBai;
use Common\Controller\Addon;

/**
 * 系统环境信息插件
 * @author thinkphp
 */

    class QiuBaiAddon extends Addon{

        public $info = array(
            'name'=>'QiuBai',
            'title'=>'糗事百科',
            'description'=>'读别人的糗事，娱乐自己',
            'status'=>1,
            'author'=>'thinkphp',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的AdminIndex钩子方法
        public function AdminIndex($param){
            $HTTP_Server="www.qiushibaike.com/8hr";

             $HTTP_URL="/";

             $ch = curl_init();

             curl_setopt ($ch,CURLOPT_URL,"http://".$HTTP_Server.$HTTP_URL);

             curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

             curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");

             // curl_setopt($ch,CURLOPT_COOKIE,$HTTP_SESSION);

             $content = curl_exec($ch);
             curl_close ($ch);
             preg_match_all('/<div class="content" title="(.*?)">\s*(.*?)\s*<\/div>/is', $content, $match);
             unset($match[0]);
             $lists = array_map(function($a,$b){
                return array('time'=>$a,'content'=>$b);
             }, $match[1], $match[2]);
             #dom对象方式匹配内容
            // if($content){
            //     $dom = new \DOMDocument();
            //     $dom->loadHTML($content);
            //     $body = $dom->getElementsByTagName('body');
            //     $body = simplexml_import_dom($body->item(0));
            //     $list = $body->xpath("//div[@class='main']/div[@class='content-block']/div[@class='col1']/div/div[@class='content']");
            //     foreach ($list as $key => $value) {
            //         $attr = $value->attributes();
            //         $lists[] = array('time'=>strval($attr['title']),'content'=>trim(strval($value)));
            //     }
            // }
            $this->assign('qiubai_list',$lists);
            $config = $this->getConfig();
            $this->assign('addons_config', $config);
            if($config['display'])
                $this->display('widget');
        }

        private function xml2data($xml,&$data){
            dump(strval($xml));
            foreach ($xml->children() as $items) {
                $key  = $items->getName();
                $attr = $items->attributes();
                if($key == $item && isset($attr[$id])){
                    $key = strval($attr[$id]);
                }

                if($items->count()){
                    $this->xml2data($items, $val);
                } else {
                    $val = strval($items);
                }

                $data[$key] = $val;
            }
        }
    }

