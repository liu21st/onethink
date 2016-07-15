<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace app\common\behavior;
use think\Hook;
defined('THINK_PATH') or exit();

// 初始化钩子信息
class InitHook  {

    // 行为扩展的执行入口必须是run
    public function run(&$content){
        return true;
        
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;
        
        $data = cache('hooks');
        if(!$data){
            $hooks = M('Hooks')->getField('name,addons');
            foreach ($hooks as $key => $value) {
                if($value){
                    $map['status']  =   1;
                    $names          =   explode(',',$value);
                    $map['name']    =   array('IN',$names);
                    $data = M('Addons')->where($map)->getField('id,name');
                    if($data){
                        $addons = array_intersect($names, $data);
                        Hook::add($key,array_map('get_addon_class',$addons));
                    }
                }
            }
            cache('hooks',Hook::get());
        }else{
            Hook::import($data,false);
        }
    }
}