<?php
namespace app\common\behavior;
// 模块初始化
class ModuleInit  {

    // 行为扩展的执行入口必须是run
    public function run(&$request){
        // 当前模块路径
        define('MODULE_PATH', APP_PATH . ($request->module() ? $request->module() . DS : ''));
    }
    
}