<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Model;

use Think\Model;

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class MemberModel extends Model{

    protected $_validate = array(
        array(
            'nickname',
            '1,16',
            '昵称长度为1-16个字符',
            self::EXISTS_VALIDATE,
            'length'
        ),
        array('nickname', '', '昵称被占用', self::EXISTS_VALIDATE, 'unique'),
        //用户名被占用
    );

    public function lists($status = 1, $order = 'uid DESC', $field = TRUE) {
        $map = array('status' => $status);
        return $this->field($field)->where($map)->order($order)->select();
    }

    /**
     * 登录指定用户
     * @param  integer $ucId 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($ucId) {
        /* 检测是否在当前应用注册 */
        $user = $this->field(TRUE)->where(array('uc_id' => $ucId))->find();
        if (!$user || 1 != $user['status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return FALSE;
        }

        //记录行为
        action_log('user_login', 'member', $user['userid'], $ucId);

        /* 登录用户 */
        $this->autoLogin($user);
        return TRUE;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout() {
        session('user_auth', NULL);
        session('user_auth_sign', NULL);
    }

   static public function get_uc_id() {
        $user = session('user_auth');
        if (empty($user)) {
            return 0;
        } else {
            return session('user_auth_sign') == data_auth_sign($user) ? $user['uc_id'] : 0;
        }
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user) {
        /* 更新登录信息 */
        $data = array(
            'userid' => $user['userid'],
            'login' => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip' => get_client_ip(1),
        );
        $this->save($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid' => $user['userid'],
            'uc_id' => $user['uc_id'],
            'username' => $user['nickname'],
            'last_login_time' => $user['last_login_time'],
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));

    }
}
