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

class StorageFile extends ThinkStorage{

    /**
     * 架构函数
     * @access public
     */
    public function __construct() {
    }

    /**
     * 文件内容读取
     * @access public
     */
    public function read($filename){
        return $this->get($filename,'content');
    }

    /**
     * 文件写入
     * @access public
     */
    public function put($filename,$content){
        $dir         =  dirname($filename);
        if(!is_dir($dir))
            mkdir($dir,0755,true);
        if(false === file_put_contents($filename,$content)){
            E(L('_STORAGE_WRITE_ERROR_').':'.$filename);
        }else{
            return true;
        }
    }

    /**
     * 加载文件
     * @access public
     */
    public function load($filename,$vars=null){
        if(!is_null($vars))
            extract($vars, EXTR_OVERWRITE);
        include $filename;
    }

    /**
     * 文件是否存在
     * @access public
     */
    public function has($filename){
        return file_exists($filename);
    }

    /**
     * 文件删除
     * @access public
     */
    public function unlink($filename){
        return unlink($filename);
    }

    /**
     * 读取文件信息
     * @access public
     */
    public function get($filename,$name){
        if(!is_file($filename)) return false;
        $content=   file_get_contents($filename);
        $info   =   array(
            'mtime'     =>  filemtime($filename),
            'content'   =>  $content
        );
        return $info[$name];
    }
}