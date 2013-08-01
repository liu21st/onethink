<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2007 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

//import('AdvModel');
class CommonModel extends Model {

	// 获取当前会员的ID
    public function getMemberId() {
        return isset($_SESSION[C('MEMBER_AUTH_KEY')])?$_SESSION[C('MEMBER_AUTH_KEY')]:0;
    }

    // 获取后台用户的ID
    protected function getUserId() {
        return $_SESSION[C('USER_AUTH_KEY')];
    }

    
    // 延迟更新
    protected function lazyWrite($guid,$lazyTime) {
        if(false !== ($value = F($guid))) { // 存在缓存写入数据
            if(time()>F($guid.'_time')+$lazyTime) {
                // 延时更新时间到了，删除缓存数据 并实际写入数据库
                F($guid,NULL);
                F($guid.'_time',NULL);
                return ++$value;
            }else{
                // 追加数据到缓存
                F($guid,++$value);
                return false;
            }
        }else{ // 没有缓存数据
            F($guid,1);
            // 计时开始
            F($guid.'_time',time());
            return false;
        }
    }

   /**
     +----------------------------------------------------------
     * 根据条件禁用表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $where 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function forbid($where,$field='status'){
        if(FALSE === $this->where($where)->setField($field,0)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

    /**
     +----------------------------------------------------------
     * 根据条件恢复表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $where 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function resume($where,$field='status'){
        if(FALSE === $this->where($where)->setField($field,1)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

    /**
     +----------------------------------------------------------
     * 根据条件恢复表数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $where 条件
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function recycle($where,$field='status'){
        if(FALSE === $this->where($where)->setField($field,0)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

    public function recommend($where,$field='is_recommend'){
        if(FALSE === $this->where($where)->setField($field,1)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

    public function unrecommend($where,$field='is_recommend'){
        if(FALSE === $this->where($where)->setField($field,0)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }

    public function checkPass($condition,$field='status'){
        if(FALSE === $this->where($condition)->setField($field,1)){
            $this->error =  L('_OPERATION_WRONG_');
            return false;
        }else {
            return True;
        }
    }
}
?>