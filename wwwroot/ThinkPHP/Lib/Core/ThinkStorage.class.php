<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 分布式文件存储类
class ThinkStorage {

    /**
     * 连接分布式文件系统
     * @access public
     * @param string $type 文件类型
     * @param array $options  配置数组
     * @return object
     */
    public function connect($type='',$options=array()) {
        $class = 'Storage'.ucwords($type);
        require THINK_PATH.'Lib/Driver/Storage/'.$class.'.class.php';
        if(class_exists($class)){
            $cache = new $class($options);
        }else{
            function_exists('E')?E(L('_STORAGE_TYPE_INVALID_').':'.$type):exit('_STORAGE_TYPE_INVALID_:'.$type);
        }
        return $cache;
    }

    /**
     * 取得分布式文件存储类实例
     * @static
     * @access public
     * @return mixed
     */
    static function getInstance($type='',$options=array()) {
        static $_instance = null;
        if(!is_null($_instance)){
            return $_instance;
        }else{
            $class  =   new ThinkStorage();
            $obj    =   $class->connect($type,$options);
            $_instance   =   $obj;
            return $obj;
        }
    }

}