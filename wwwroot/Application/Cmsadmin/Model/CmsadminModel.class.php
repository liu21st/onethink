<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 后台公共模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class CmsadminModel extends Model{

    /**
     * 设置数据状态
     * @param  integer status 数据状态
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    final protected function setStatus($status = 0){
        return $this->setField('status', $status);
    }

    /**
     * 删除数据，假删除
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    protected function del(){
        return $this->setStatus(-1);
    }

    /**
     * 禁用数据
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    protected function forbid(){
        return $this->setStatus(0);
    }

    /**
     * 回复数据（标记为正常）
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    protected function resume(){
        return $this->setStatus(1);
    }
}