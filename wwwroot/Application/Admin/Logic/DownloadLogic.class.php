<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------
namespace Admin\Logic;

/**
 * 文档模型子模型 - 下载模型
 */
class DownloadLogic extends BaseLogic{

    /* 自动验证规则 */
    protected $_validate = array(
        array('content', 'require', '详细内容不能为空！', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
        array('download', 'number', '下载次数请输入整数！', self::VALUE_VALIDATE , 'regex', self::MODEL_BOTH),
        array('file_id', 'require', '请上传附件！', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    /**
     * 获取模型详细信息
     * @param  integer $id 文档ID
     * @return array       当前模型详细信息
     * @author huajie <banhuajie@163.com>
     */
    public function detail($id){
        $data = $this->field(true)->find($id);
        if(!$data){
            $this->error = '获取详细信息出错！';
            return false;
        }
        $file = D('File')->field(true)->find($data['file_id']);
        return $data;
    }

    /**
     * 更新数据
     * @param intger $id
     * @author huajie <banhuajie@163.com>
     */
    public function update($id = 0){
        /* 获取下载数据 */ //TODO: 根据不同用户获取允许更改或添加的字段
        $data = $this->create();
        if(!$data){
            return false;
        }

        $file = json_decode(think_decrypt(I('post.file_id')), true);
        if(!empty($file)){
            $data['file_id'] = $file['id'];
            $data['size']    = $file['size'];
        } else {
            $this->error = '获取上传文件信息失败！';
            return false;
        }

        /* 添加或更新数据 */
        if(empty($data['id'])){//新增数据
            $data['id'] = $id;
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增详细内容失败！';
                return false;
            }
        } else { //更新数据
            $status = $this->save($data);
            if(false === $status){
                $this->error = '更新详细内容失败！';
                return false;
            }
        }
        return true;
    }

    /**
     * 下载文件
     * @param  number $id 文档ID
     * @return boolean    下载失败返回false
     */
    public function download($id){
        $info = $this->find($id);
        if(empty($info)){
            $this->error = "不存在的文档ID：{$id}";
            return false;
        }

        $File = D('File');
        $root = C('DOWNLOAD_UPLOAD.rootPath');
        $call = array($this, 'setDownload');
        if(false === $File->download($root, $info['file_id'], $call, $info['id'])){
            $this->error = $File->getError();
        }
    }

    /**
     * 新增下载次数（File模型回调方法）
     */
    public function setDownload($id){
        $map = array('id' => $id);
        $this->where($map)->setInc('download');
    }

    /**
     * 保存为草稿
     * @return true 成功， false 保存出错
     * @author huajie <banhuajie@163.com>
     */
    public function autoSave($id = 0){
        $this->_validate = array();

        /* 获取文章数据 */
        $data = $this->create();
        if(!$data){
            return false;
        }

        $file = json_decode(think_decrypt(I('post.file_id')), true);
        if(!empty($file)){
            $data['file_id'] = $file['id'];
            $data['size']    = $file['size'];
        }

        /* 添加或更新数据 */
        if(empty($data['id'])){//新增数据
            $data['id'] = $id;
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增详细内容失败！';
                return false;
            }
        } else { //更新数据
            $status = $this->save($data);
            if(false === $status){
                $this->error = '更新详细内容失败！';
                return false;
            }
        }
        return true;
    }

}
