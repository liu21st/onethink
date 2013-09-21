<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace COM;

class Hook {

    static private $tags =   array();

    /**
     * 动态添加插件到某个标签
     * @param string $tag 标签名称
     * @param mixed $name 插件名称
     * @param string $type 插件的类型       
     * @return void
     */
    static public function add($tag,$name,$type='Addon') {
        if(!isset(self::$tags[$tag])){
            self::$tags[$tag]   =   array();
        }
        if(is_array($name)){
            self::$tags[$tag]   =   array_merge(self::$tags[$tag],$name);
        }else{
            self::$tags[$tag][] =   array($name,$type);
        }
    }

    /**
     * 批量导入插件
     * @param array $tags 插件信息
     * @return void
     */
    static public function import($tag) {
        self::$tags =   array_merge(self::$tags,$tag);
    }

    /**
     * 获取插件信息
     * @param string $tag 插件位置 留空获取全部
     * @return array
     */
    static public function get($tag='') {
        if(empty($tag)){
            // 获取全部的插件信息
            return self::$tags;
        }else{
            return self::$tags[$tag];
        }
    }

    /**
     * 监听标签的插件
     * @param string $tag 标签名称
     * @param mixed $params 传入参数
     * @param string $allowType 允许的插件类型
     * @return void
     */
    static public function listen($tag, &$params=NULL,$allowType='') {
        if(isset(self::$tags[$tag])) {
            if(APP_DEBUG) {
                G($tag.'Start');
                trace('[ '.$tag.' ] --START--','','INFO');
            }
            foreach (self::$tags[$tag] as $addon) {
                if(is_array($addon)){
                    list($name,$type)   =   $addon;
                }else{
                    $name   =   $addon;
                    $type   =   'Addon';
                }
                if(''==$allowType || $allowType==$type){
                    APP_DEBUG && G($type.'_start');
                    $result =   self::exec($name, $tag,$params,$type);
                    if(APP_DEBUG){
                        G($type.'_end');
                        trace('Run '.$name.' '.$type.' [ RunTime:'.G($type.'_start',$type.'_end',6).'s ]','','INFO');
                    }
                    if(false === $result) {
                        // 如果返回false 则中断插件执行
                        return ;
                    }
                }
            }
            if(APP_DEBUG) { // 记录行为的执行日志
                trace('[ '.$tag.' ] --END-- [ RunTime:'.G($tag.'Start',$tag.'End',6).'s ]','','INFO');
            }
        }
        return;
    }

    /**
     * 执行某个插件
     * @param string $name 插件名称
     * @param Mixed $params 传入的参数
     * @param string $type 插件的类型     
     * @return void
     */
    static public function exec($name, $tag,&$params=NULL,$type='Addon') {
        if(false === strpos($name,'\\')) {
            $class      =  $type."s\\{$name}\\{$name}".$type;
        }else{
            $class      =  $name;
        }
        $addon   = new $class();
        return $addon->$tag($params);
    }
}
