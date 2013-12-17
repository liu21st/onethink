<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Common\Api;
class UserApi {
    /**
     * 检测用户是否登录
     * @return integer 0-未登录，大于0-当前登录用户ID
     */
    public static function is_login(){
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
     */
    public static function is_administrator($uid = null){
        $uid = is_null($uid) ? is_login() : $uid;
        return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
    }

    /**
     * 根据用户ID获取用户名
     * @param  integer $uid 用户ID
     * @return string       用户名
     */
    public static function get_username($uid = 0){
        static $list;
        if(!($uid && is_numeric($uid))){ //获取当前登录用户名
            return session('user_auth.username');
        }

        /* 获取缓存数据 */
        if(empty($list)){
            $list = S('sys_active_user_list');
        }

        /* 查找用户信息 */
        $key = "u{$uid}";
        if(isset($list[$key])){ //已缓存，直接使用
            $name = $list[$key];
        } else { //调用接口获取用户信息
            $User = new User\Api\UserApi();
            $info = $User->info($uid);
            if($info && isset($info[1])){
                $name = $list[$key] = $info[1];
                /* 缓存用户 */
                $count = count($list);
                $max   = C('USER_MAX_CACHE');
                while ($count-- > $max) {
                    array_shift($list);
                }
                S('sys_active_user_list', $list);
            } else {
                $name = '';
            }
        }
        return $name;
    }

    /**
     * 根据用户ID获取用户昵称
     * @param  integer $uid 用户ID
     * @return string       用户昵称
     */
    public static function get_nickname($uid = 0){
        static $list;
        if(!($uid && is_numeric($uid))){ //获取当前登录用户名
            return session('user_auth.username');
        }

        /* 获取缓存数据 */
        if(empty($list)){
            $list = S('sys_user_nickname_list');
        }

        /* 查找用户信息 */
        $key = "u{$uid}";
        if(isset($list[$key])){ //已缓存，直接使用
            $name = $list[$key];
        } else { //调用接口获取用户信息
            $info = M('Member')->field('nickname')->find($uid);
            if($info !== false && $info['nickname'] ){
                $nickname = $info['nickname'];
                $name = $list[$key] = $nickname;
                /* 缓存用户 */
                $count = count($list);
                $max   = C('USER_MAX_CACHE');
                while ($count-- > $max) {
                    array_shift($list);
                }
                S('sys_user_nickname_list', $list);
            } else {
                $name = '';
            }
        }
        return $name;
    }
}