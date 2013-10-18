<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * URL模型
 * @author huajie <banhuajie@163.com>
 */

class UrlModel extends Model {

    /* 自动验证规则 */
    protected $_validate = array(
        array('url', 'url', 'URL格式不正确', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('short', 'url', 'URL格式不正确', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('status', 1, self::MODEL_INSERT, 'string'),
        array('create_time', 'time', self::MODEL_BOTH, 'function'),
    );

    /**
     * 新增或更新一个URL
     * @return boolean fasle 失败 ， 成功 返回完整的数据
     * @author huajie <banhuajie@163.com>
     */
    public function update($data){
        /* 获取数据对象 */
        $data = empty($data) ? $_POST : $data;
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }

        /* 如果链接已存在则直接返回 */
        $info = $this->getByUrl($data['url']);
        if(!empty($info)){
            return $info;
        }

        /* 添加或新增行为 */
        if(empty($data['id'])){ //新增数据
            $id = $this->add();
            $data['id'] = $id;
            if(!$id){
                $this->error = '新增链接出错！';
                return false;
            }
        } else { //更新数据
            $status = $this->save(); //更新基础内容
            if(false === $status){
                $this->error = '更新链接出错！';
                return false;
            }
        }

        //内容添加或更新完成
        return $data;
    }

}
