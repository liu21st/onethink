<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

    public function test(){
        echo T('Addons://Attachment@Article/detail');
    }

	//系统首页
    public function index(){
    	if(IS_CLI){
            $data = M('Content')->field("id,content")->select();
            foreach ($data as $value) {
                $value['content'] = ubb($value['content']);
                M('Content')->save($value);
            }

        } else {
            $this->display();
        }
    }

    public function upload(){
    	if(IS_POST){
    		import('COM.ThinkUpload.ThinkUpload');
            $config = array(
                'host'     => 'http://v0.api.upyun.com', //又拍云服务器
                'username' => 'zuojiazi', //又拍云用户
                'password' => 'thinkphp2013', //又拍云密码
                'bucket'   => 'thinkphp-static', //空间名称
            );
    		$upload = new ThinkUpload(array('rootPath' => 'image/'), 'Upyun', $config);
    		$info   = $upload->upload($_FILES);
    		dump($upload->getError());
    		dump($info);
    	} else {
    		$this->display();
    	}
    }

    public function upyun(){
        $policydoc = array(
            "bucket"             => "thinkphp-static", /// 空间名
            "expiration"         => NOW_TIME + 600, /// 该次授权过期时间
            "save-key"            => "/{year}/{mon}/{random}{.suffix}",
            "allow-file-type"      => "jpg,jpeg,gif,png", /// 仅允许上传图片
            "content-length-range" => "0,102400", /// 文件在 100K 以下
        );

        $policy = base64_encode(json_encode($policydoc));
        $sign = md5($policy.'&'.'56YE3Ne//xc+JQLEAlhQvLjLALM=');

        $this->assign('policy', $policy);
        $this->assign('sign', $sign);
        $this->display();
    }
    
}
