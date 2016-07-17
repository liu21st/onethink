<?php
namespace app\common\behavior;
use think\Request;
// 模块初始化
class ModuleInit  {

    // 行为扩展的执行入口必须是run
    public function run(&$request){
        // 当前模块路径
        $request=Request::instance();
        define('MODULE_PATH', APP_PATH . ($request->module() ? $request->module() . DS : ''));
        define('ACTION_NAME', $request->action());
        define('MODULE_NAME', $request->module());
        define('CONTROLLER_NAME', $request->controller());
        
//         define('MODULE_PATH', APP_PATH . ($request->module() ? $request->module() . DS : ''));
    }
    
}