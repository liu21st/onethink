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
namespace Behavior;
use Think\Behavior;
use Think\Storage;
defined('THINK_PATH') or exit();
/**
 * 创建 ThinkPHP Lite版本文件
 */
class BuildLiteBehavior extends Behavior {

    // 行为扩展的执行入口必须是run
    public function run(&$content){
        if(!APP_DEBUG && C('BUILD_LITE_RUNTIME')) {
            $litefile     = RUNTIME_PATH.APP_MODE.'~lite.php';
            $runtimefile  = RUNTIME_PATH.APP_MODE.'~runtime.php';
            if( !Storage::has($litefile)){
                $defs       =   get_defined_constants(TRUE);
                $content    =   Storage::read($runtimefile);
               // $content   .=   'namespace { $GLOBALS[\'_beginTime\'] = microtime(TRUE);';
               $content .=  compile(CORE_PATH.'Think'.EXT);
                $content   .=   'namespace {'.$this->array_define($defs['user']).'Think\Think::start();}';
                
                Storage::put($litefile , $content);
            }
        }
    }

    protected function array_define($array) {
        $content = "\n";
        foreach ($array as $key => $val) {
            $key = strtoupper($key);
            if($check)   $content .= 'defined(\'' . $key . '\') or ';
            if (is_int($val) || is_float($val)) {
                $content .= "define('" . $key . "'," . $val . ');';
            } elseif (is_bool($val)) {
                $val = ($val) ? 'true' : 'false';
                $content .= "define('" . $key . "'," . $val . ');';
            } elseif (is_string($val)) {
                $content .= "define('" . $key . "','" . addslashes($val) . "');";
            }
            $content    .= "\n";
        }
        return $content;
    }
}