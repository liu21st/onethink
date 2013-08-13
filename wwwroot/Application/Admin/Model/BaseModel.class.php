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

// 基础模型抽象类
abstract class BaseModel {
    const INSERT    =   1;  // 新增
    const UPDATE    =   2;  // 更新
    const DELETE    =   3;  // 删除
    const CHECK     =   4;  // 审核
    const RESUME    =   5;  // 还原
    const FORBID    =   6;  // 禁用
    const READ      =   7;  // 读取
    const OTHER     =   0;  // 其他

    protected $data   =  array();
    protected $error  =  '';

    // 属性自动完成定义 如果模型定义如下字段 则会自动完成
    protected $_auto	 =	 array(
        array('status','setStatus',Model::MODEL_INSERT,'function'),
        array('create_time','time',Model::MODEL_INSERT,'function'),
        array('update_time','time',Model::MODEL_BOTH,'function'),
        array('title','strip_tags',Model::MODEL_BOTH,'function'),
        array('user_id','getUserId',Model::MODEL_INSERT,'function'),
//        array('op_id','getUserId',Model::MODEL_INSERT,'function'),
       // array('tags','saveTag',Model::MODEL_BOTH,'function'),
        );

    // 创建数据对象 包含模型的自动验证和自动完成机制
    public function create($data='',$type=''){
        $this->_before_create($data,$type);
        // 获取当前所属模型
        $module = $this->getModuleName($data);
        // 获取当前模型的属性信息
        $attrs = $this->getModelAttrInfo($module);
        foreach ($attrs as $key=>$attr){
            if(isset($attr['complex'])) { // 组合字段
                unset($attrs[$key]);
                $attrs = array_merge($attrs,$attr['complex']);
            }
        }
        // 初始化数据
        $validate = $auto = $date =  $serialize = $image = $file = array();
        // 检测属性类型和参数
        foreach ($attrs as $key=>$attr){
            if($attr['is_must']){// 必填字段
                $validate[]  =  array($attr['name'],'require',$attr['title'].'必须!');
            }
            // 定义自动验证
            if(!empty($attr['validate'])) {
                $item   =  explode(',',$attr['validate']);
                array_unshift($item,$attr['name']);
                $validate[]  =  $item;
            }
            // 定义自动完成
            if(!empty($attr['auto'])) {
                $item   =  explode(',',$attr['auto']);
                array_unshift($item,$attr['name']);
                $auto[]  =  $item;
            }elseif('checkbox'==$attr['type']){
                $auto[] =   array($attr['name'],'array_to_string',Model::MODEL_BOTH,'function');
            }elseif('text'==$attr['type']){
                $auto[] =   array($attr['name'],'ubbfilter',Model::MODEL_BOTH,'function');
            }
            if('verify'==$attr['type']){ // 验证码
                $verify   =  $attr['name'];
            }elseif('date'==$attr['type']){//   日期型
                $date[]  =  $attr['name'];
            }elseif('serialize'==$attr['type']){// 序列化型
                $array   =  explode(',',$attr['extra']);
                foreach ($array as $var){
                    $temp   =  explode(':',$var);
                    $serialize[$attr['name']][] = $temp[0];
                }
            }elseif(0===strpos($attr['type'],'image')){ // 图片
                $image[]   =   $attr['name'];
            }elseif(0===strpos($attr['type'],'file')){ // 附件
                $file[]   =   $attr['name'];
            }elseif('attachment'==$module){
                $file[] =   'attachment';
            }
        }
        // 实例化文档模型
        $Model  =  model($module);
        // 动态设置模型属性
        $Model->setProperty('_validate',$validate);
        $Model->setProperty('_auto',array_merge($auto,$this->_auto));
        $Model->setProperty('serializeField',$serialize);
        // 创建扩展数据对象 完成扩展属性的自动验证和自动完成
        $data = $Model->create($data,$type);
        if(false === $data) {
            $this->error    =  $Model->getError();
            return false;
        }
        // 检测验证码属性
        if(isset($verify)) {
            if($_SESSION['verify'] != md5($data[$verify])) {
                $this->error    =  '验证码错误！';
                return false;
            }
        }
        if(!empty($file)) {
            // 上传附件
            $data   = $this->uploadFile($data,$module);
            if(false === $data) {
                return false;
            }
        }
        if(!empty($image)) {
            // 上传图片
            $data   = $this->uploadFile($data,$module,'image');
            if(false === $data) {
                return false;
            }
        }
        // 日期型转成时间戳
        if(!empty($date)) {
            foreach ($date as $field){
                if($data[$field])
                    $data[$field]  = strtotime($data[$field]);
            }
        }
        // 过滤没有变化的字段 提高更新效率
        if(!empty($data[$Model->getPk()]) && !empty($_SESSION['__data__'])) {
            $id = $data[$Model->getPk()];
            $data = array_diff_assoc($data,$_SESSION['__data__']);
            $data[$Model->getPk()]   = $id;
            unset($_SESSION['__data__']);
        }
        // 写入模型名称
        $data['_module']   =  $module;
        $this->data = $data;
        return $data;
    }
    // 查询回调接口
    protected function _before_create(&$data,$type) {}

    // 获取当前的模型名称
    protected function getModuleName($data='') {
        if(empty($data)) $data =  $_POST;
        // 查询分类和模型的对应关系
        return !empty($data['_module'])?$data['_module']:strtolower(substr(get_class($this),0,-5));
    }

    // 获取模型的属性列表 type = 0 全部 1 新增 2 编辑 也可以指定属性列表names
    public function getModelAttrInfo($module='',$type=0,$names=''){
        // 获取当前文档模型名
        if(is_int($module)) {
            $attrIds =  M('Model')->getFieldById($module,'attribute_list');
        }else{
            $attrIds =  M('Model')->getFieldByName($module,'attribute_list');
        }
        if($attrIds) {
            $map['status'] = 1;
            $map['id']   = array('IN',$attrIds);
            switch($type) {
            case 1: // 新增
                $map['is_show']   = array(1,2,'or');
                break;
            case 2: // 编辑
                $map['is_show']   = array(1,3,'or');
                break;
            default:// 全部属性
            }
            if(!empty($names)) {
                $map['name'] =  array('IN',$names);
            }
            $attrs   =  M("Attribute")->where($map)->order('sort,id ')->field('name,type,extra,model_id,title,value,validate,length,auto,is_must,is_common,remark')->select();
            $result   =  array();
            foreach ($attrs as $key=>$attr){
                if($attr['type'] =='complex') {// 组合字段则获取组合的字段信息
                    $attr['extra']   =  trim($attr['extra']);
                    if( is_numeric(substr($attr['extra'],0,1))) {
                        // id列表
                        $where["id"] = array("IN",$attr['extra']); 
                        $order   =  'field( id,'.$attr['extra'].' )';
                    }else{ // 字段名列表
                        $where["name"] = array("IN",$attr['extra']); 
                        $where['model_id']   =  $attr['model_id'];
                        $attr['extra']   =  '"'.str_replace(',','","',$attr['extra']).'"';
                        $order   =  'field( name,'.$attr['extra'].' )';
                    }
                    $ext = M("Attribute")->where($where)->order($order)->select();
                    $attr['complex']   =  $ext;
                    $attrs[$key]   =  $attr;
                }
                $result[$attr['name']] = $attr;
            }
            return $result;
        }else{
            return array();
        }
    }

    // 获取某个模型的数据
    public function get($module,$id){
        $data   = model($module)->find($id);
        foreach ($data as $key=>$val){
            if(is_serialized($val)){ // 自动识别序列化字段
                $data[$key]   =  unserialize($val);
            }
        }
        // 事件监听
        event($module,self::READ,$data);
        // 查询回调接口
        $this->_after_find($data,$module);
        return $data;
    }
    // 查询回调接口
    protected function _after_find(&$data,$module) {}

    // 写入模型数据
    public function add($data='',$module='') {
        $data = $data?$data:$this->data;
        $module = $module?$module:$data['_module'];
        // 实例化文档模型
        $Model  =  model($module);
        // 数据处理
        foreach ($data as $key=>$val){
            if(is_array($val)) {
                // 如果是数组 自动序列化保存
                $data[$key] =  serialize($val);
            }
        }
        if(false === $this->_before_insert($data,$module)) {
            return false;
        }
        $id   =  $Model->add($data);
        if(false === $id) {
            return false;
        }
        // 补充附件表信息
        if(isset($_SESSION['attach_verify'])) {
            $Attach	=	M("Attach");
            $Attach->verify	=	0;
            $Attach->record_id	=	$id;
            $map['verify']  =  $_SESSION['attach_verify'];
            $Attach->where($map)->save();
            unset($_SESSION['attach_verify']);
        }
        // 事件监听
        $data['id']   =  $id;
        // 写入回调接口
        $this->_after_insert($data,$module);
        event($module,self::INSERT,$data);
        return $id;
    }
    // 插入数据前的回调方法
    protected function _before_insert(&$data,$module) {}
    // 插入成功后的回调方法
    protected function _after_insert($data,$module) {}

    // 更新模型数据
    public function save($data='',$module='') {
        $data = $data?$data:$this->data;
        $module = $module?$module:$data['_module'];
        // 实例化文档模型
        $Model  =  model($module);
        // 数据处理
        foreach ($data as $key=>$val){
            if(is_array($val)) {
                // 如果是数组 自动序列化保存
                $data[$key] =  serialize($val);
            }
        }
        $result   =  $Model->save($data);
        if(false === $result) {
            return false;
        }
        // 事件监听
        event($module,self::UPDATE,$data);
        // 更新回调接口
        $this->_after_update($data,$module);
        return true;
    }
    // 插入数据前的回调方法
    protected function _before_update(&$data,$module) {}
    // 插入成功后的回调方法
    protected function _after_update($data,$module) {}

    // 清空模型数据
    public function clear($module,$map){
        // 实例化文档模型
        $Model  =  model($module);
        // 删除附件
        $attrs = $this->getModelAttrInfo($module);
        $attach  =  array();
        foreach ($attrs as $key=>$val){
            if(in_array($val['type'],array('image','file'))) {
                $field =  $val['name'];
                $data = $Model->where($map)->getField('id,'.$field);
                if($data) {
                    $attach  =  $attach+$data;
                }
            }
        }
        foreach ($attach as $file){
            if($file)   unlink('./Uploads/'.$module.'/'.$file);
        }
        // 删除数据
        $result   =  $Model->where($map)->delete();
        // 清空回调接口
        $this->_after_clear($module,$map);
        return true;
    }
    // 清空后的回调方法
    protected function _after_clear($module,$map) {}

    // 删除模型数据
    public function delete($module,$map) {
        // 实例化文档模型
        $Model  =  model($module);
        if(is_numeric($map)) {
            $map['id']   = $map;
        }
        // 删除数据
        $Model->where($map)->setField('status',-1);
        // 事件监听
        event($module,self::DELETE,$map);
        // 删除回调接口
        $this->_after_delete($module,$map);
        return true;
    }
    // 删除后的回调方法
    protected function _after_delete($module,$map) {}

    
    // 获取模型属性值
    public function getValue($module,$id,$name){
        $val = model($module)->getFieldById($id,$name);
        if(is_serialized($val)){ // 自动识别序列化字段
            $val   =  unserialize($val);
        }
        return $val;
    }

    // 同步映射文档
    public function sync($module,$id){
        // 获取当前模型
        $model   = M('Model')->getByName($module);
        if($model['support_link']) {
            $Model  =   model($module);
            $data  =   $Model->find($id);
            if(!$data['link_id']) {
                $link_list  =   explode(',',$model['link_list']);
                $keys   =   array_merge($link_list,explode(',','id,create_time,link_id,update_time,status,cate_id'));
                foreach($data as $key=>$val){
                    if(in_array($key,$keys)) {
                        unset($data[$key]);
                    }
                }
                $map['link_id'] =   $id;
                if($Model->where($map)->save($data)){
                    return true;
                }
            }
        }
        return false;
    }

    // 同步映射文档状态
    public function syncStatus($module,$id,$status){
        // 获取当前模型
        $model   = M('Model')->getByName($module);
        if($model['support_link']) {
            $Model  =   model($module);
            $map['link_id'] =   array('IN',$id);
            $map['status']  =   $status;
            if($Model->where($map)->save($data)){
                return true;
            }
        }
        return false;
    }

    // 设置模型属性值
    public function setValue($module,$id,$name,$value){
        $map['id'] = array('in',$id);
        return model($module)->where($map)->setField($name,$value);
    }

    // 设置模型状态
    public function setStatus($status,$module,$map){
        $result = model($module)->where($map)->setField('status',$status);
        // 事件监听
        switch($status) {
        case 0:event($module,self::FORBID,$map);break;
        case 1:event($module,self::RESUME,$map);break;
        case -1:event($module,self::DELETE,$map);break;
        }
        return $result;
    }

    // 上传附件
    protected function uploadFile($data,$module,$type='file') {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        // 读取module的保存路径
        $savePath  =   M('Model')->getFieldByName($module,'upload_path');
        if(!$savePath) {
            $savePath   =   './Uploads/'.$module.'/';
        }
        foreach ($_FILES as $key=>$file){
            if(!empty($file['name'])) {
                //设置上传文件大小
                $upload->maxSize  = C('UPLOAD_MAX_SIZE') ;
                // 获取上传参数
                if(!empty($_POST['__upload_'.$key])) {
                    parse_str(base64_decode($_POST['__upload_'.$key]),$options);
                    foreach ($options as $name=>$val)
                        $upload->$name =  $val;
                }
                //设置附件上传目录和子目录保存规则 不允许设定
                if($upload->isFtp) {
                    $upload->savePath   =   ($type=='image'?C('FTP_PIC_SAVE_PATH'):C('FTP_FILE_SAVE_PATH'));
                }else{
                    $upload->savePath =  $savePath;
                }
                $upload->autoSub = true;
                $upload->subType   =  'date';
                $infos =  $upload->uploadOne($file);
                if(false === $infos) {                
                    $this->error = $upload->getErrorMsg();
                    return false;
                }else{
                    if('attachment'==$module) { 
                        foreach ($infos as $info){
                            unset($info['key']);
                            $data['name']   =   $info['name'];
                            $data['size']   =   $info['size'];
                            $data['type']   =   $info['type'];
                            $data['savepath']   =   basename($info['savepath']).'/'.dirname($info['savename']).'/';
                            $data['savename']   =   basename($info['savename']);
                            $data['ext']    =   $info['extension'];
                            $data['md5']    =   md5_file($_FILES[$key]['tmp_name']);
                            $data[$key] =   '';
                        }
                    }elseif($upload->uploadRecord) {
                        // 附件数据需要保存到数据库
                        $Attach    = M('Attachment');
                        // 附件数据需要保存到数据库
                        foreach ($infos as $file){
                            unset($file['key']);
                            $attach =   $file['savepath'].$file['savename'];
                            $file['savepath']   =   basename($file['savepath']).'/'.dirname($file['savename']).'/';
                            $file['savename']   =   basename($file['savename']);
                            $file['ext']    =   $file['extension'];
                            $file['md5']    =   md5_file($_FILES[$key]['tmp_name']);
                            if($data['attach_id']) { // 更新
                                $file['id'] =   $data['attach_id'];
                                $file['update_time']    =   NOW_TIME;
                                $Attach->save($file);
                            }else{// 新增
                                $file['status']		=	1;
                                $file['create_time'] =   time();
                                $attachId   =   $Attach->add($file);
                                $data['attach_id'] =   $attachId;
                            }
                            $data[$key] =   $file['name'];
                        }
                    }
                    // 上传成功 删除临时文件
                    unset($_FILES[$key]);
                }
                if(!isset($data[$key])) {
                    if(count($infos)>1) {// 多附件上传
                        foreach ($infos as $val){
                            $_temp[]  =  $module.'/'.$val['savename'];
                        }
                        $data[$key]  = implode(',',$_temp);
                    }else{
                        $data[$key]  = $module.'/'.$infos[0]['savename'];
                    }
                }
            }
        }
        return $data;
    }


    // 获取错误信息
    public function getError(){
        return $this->error;
    }
}