<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// function.php 2013-03-15

/**
 * 判断用户是否登录
 * @return boolean true - 已登录，false - 未登录
 */
function is_login(){
    return session(C('UCENTER_SERVER_AUTH')) ? true : false;
}

/**
 * 解析字符串查询条件
 * @param  string $where 查询条件
 * @return string|array  解析后的查询条件
 */
function parse_where_str($where){
    return false === strpos($where, '%') ? $where : array('like', $where); 
}
