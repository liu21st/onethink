<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// 定时任务模型
class CronModel extends Think{

    // 清空阅读数
    public function clearReadCount(){
        // 判断时间
        $Article  =  M('Article');
        $time = time();
        if(date('G',$time)>=0 && date('G',$time) <=3 ) {// 凌晨清空日阅读数
            $Article->setField('read_day',0,'status=1');
            if(1 == date('w',$time) ) // 周一清空周阅读数
                $Article->setField('read_week',0,'status=1');
            if(1 == date('j',$time)) // 一号清空月阅读数
                $Article->setField('read_month',0,'status=1');
            // 同步数据
            $this->syncReadCount();
        }
    }

    // 同步阅读数到缓存表
    public function syncReadCount(){
        D('Article')->syncAttrs('read_day,read_week,read_month,read_count,comment_count');
    }

    // 数据清理 对一些不符合要求的数据清理

}
?>