<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 后台公共文件
 * 主要定义后台公共函数库
 */

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_login(){
	$user = session('user_auth');
    if (empty($user)) {
        return 0;
    } else {
        return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
    }
}

/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_administrator(){
    $uid = is_login();
    return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}

/**
 * 获取对应状态的文字信息
 * @param int $status
 * @return string 状态文字 ，false 未获取到
 * @author huajie <banhuajie@163.com>
 */
function get_status_title($status = null){
	if(!isset($status)){
		return false;
	}
	switch ($status){
		case -1 : return '已删除';break;
		case 0 : return '禁用';break;
		case 1 : return '正常';break;
		case 2 : return '待审核';break;
		default : return false;break;
	}
}

/**
 * 获取文档的类型文字
 * @param string $type
 * @return string 状态文字 ，false 未获取到
 * @author huajie <banhuajie@163.com>
 */
function get_document_type($type = null){
	if(!isset($type)){
		return false;
	}
	switch ($type){
		case 0 : return '专辑';break;
		case 1 : return '目录';break;
		case 2 : return '主题';break;
		case 2 : return '段落';break;
		default : return false;break;
	}
}

/**
 * 检查$pos(推荐位的值)是否包含指定推荐位$contain
 * @param number $pos 推荐位的值
 * @param number $contain 指定推荐位
 * @return boolean true 包含 ， false 不包含
 * @author huajie <banhuajie@163.com>
 */
function check_document_position($pos = 0, $contain = 0){
	if(empty($pos) || empty($contain)){
		return false;
	}

	//将两个参数进行按位与运算，不为0则表示$contain属于$pos
	$res = $pos & $contain;
	if($res !== 0){
		return true;
	}else{
		return false;
	}
}

/**
 * select返回的数组进行整数映射转换
 *
 * @param array $map  映射关系二维数组  array(
 *                                          '字段名1'=>array(映射关系数组),
 *                                          '字段名2'=>array(映射关系数组),
 *                                           ......
 *                                       )
 * @author 朱亚杰 <zhuyajie@topthink.net>
 * @return array
 *
 *  array(
 *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *      ....
 *  )
 *
 */
function intToString(&$data,$map=array('status'=>array(1=>'正常',-1=>'已删除',0=>'已禁用',2=>'审核通过'))) {
    $data = (array)$data;
    foreach ($data as $key => $row){
        foreach ($map as $col=>$pair){
            $data[$key][$col.'_text'] = $pair[$row[$col]];
        }
    }
    return $data;
}

/**
 * 动态扩展左侧菜单,base.html里用到
 * @author 朱亚杰 <zhuyajie@topthink.net>
 */
function extra_menu($extra_menu,&$base_menu){
    foreach ($extra_menu as $key=>$group){
        if( isset($base_menu['child'][$key]) ){
            $base_menu['child'][$key] = array_merge( $base_menu['child'][$key], $group);
        }else{
            $base_menu['child'][$key] = $group;
        }
    }
}

/**
 * 获取参数的所有父级分类
 * @param int $cid 分类id
 * @return array 参数分类和父类的信息集合
 * @author huajie <banhuajie@163.com>
 */
function get_parent_category($cid){
	if(empty($cid)){
		return false;
	}
	$cates = M('Category')->where(array('status'=>1))->field('id,title,pid')->order('sort')->select();
	$child = get_category($cid);	//获取参数分类的信息
	$pid = $child['pid'];
	$temp = array();
	$res[] = $child;
	while(true){
		foreach ($cates as $key=>$cate){
			if($cate['id'] == $pid){
				$pid = $cate['pid'];
				array_unshift($res, $cate);	//将父分类插入到数组第一个元素前
			}
		}
		if($pid == 0){
			break;
		}
	}
	return $res;
}

/**
 * 获取文档封面图片
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 * @author huajie <banhuajie@163.com>
 */
function get_cover($cover_id, $field = null){
	if(empty($cover_id)){
		return false;
	}
	$picture = M('Picture')->where(array('status'=>1))->getById($cover_id);
	return empty($field) ? $picture : $picture[$field];
}

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi.cn@gmail.com>
 */
function check_verify($code, $id = 1){
	$verify = new \COM\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取链接信息
 * @param int $link_id
 * @param string $field
 * @return 完整的链接信息或者某一字段
 * @author huajie <banhuajie@163.com>
 */
function get_link($link_id = null, $field = 'url'){
	$link = '';
	if(empty($link_id)){
		return $link;
	}
	$link = M('Url')->getById($link_id);
	if(empty($field)){
		return $link;
	}else{
		return $link[$field];
	}
}

/**
 * 获取当前分类的文档类型
 * @param int $id
 * @return array 文档类型数组
 * @author huajie <banhuajie@163.com>
 */
function get_type_bycate($id = null){
	if(empty($id)){
		return false;
	}
	$type_list = C('DOCUMENT_MODEL_TYPE');
	$model_type = M('Category')->getFieldById($id, 'type');
	$model_type = explode(',', $model_type);
	foreach ($type_list as $key=>$value){
		if(!in_array($key, $model_type)){
			unset($type_list[$key]);
		}
	}
	return $type_list;
}