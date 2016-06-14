<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 模型数据管理控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class ThinkController extends AdminController {

    /**
     * 显示指定模型列表数据
     * @param  String $model 模型标识
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function lists($model = null, $p = 0){
        $model || $this->error('模型名标识必须！');
        $page = intval($p);
        $page = $page ? $page : 1; //默认显示第一页数据

        //获取模型信息
        $model = M('Model')->getByName($model);
        $model || $this->error('模型不存在！');

        //解析列表规则
        $fields = array();
        $grids  = preg_split('/[;\r\n]+/s', trim($model['list_grid']));
        $foreignFields = array();//foreign_int/foreign_string 2016-06-14
        foreach ($grids as &$value) {
        	if(trim($value) === ''){
        		continue;
        	}
            // 字段:标题:链接
            $val      = explode(':', $value);
            // 支持多个字段显示
            $field   = explode(',', $val[0]);
            
            //foreign_int/foreign_string 2016-06-14 start
            foreach($field as $onefield) {
                if (stripos($onefield, '.foreign') !== false) {
                    $onefield = substr($onefield, 0, stripos($onefield, '.foreign'));
                    array_push($foreignFields, $onefield);
                }
            }
            //foreign_int/foreign_string 2016-06-14 end
            
            $value    = array('field' => $field, 'title' => $val[1]);
            if(isset($val[2])){
                // 链接信息
                $value['href']	=	$val[2];
                // 搜索链接信息中的字段信息
                preg_replace_callback('/\[([a-z_]+)\]/', function($match) use(&$fields){$fields[]=$match[1];}, $value['href']);
            }
            if(strpos($val[1],'|')){
                // 显示格式定义
                list($value['title'],$value['format'])    =   explode('|',$val[1]);
            }
            foreach($field as $val){
                $array	=	explode('|',$val);
                if (stripos($array[0], '.foreign') !== false) {
                    $array[0] = substr($array[0], 0, stripos($array[0], '.foreign'));
                }
                $fields[] = $array[0];
            }
        }
        // 过滤重复字段信息
        $fields =   array_unique($fields);
        // 关键字搜索
        $map	=	array();
        $key	=	$model['search_key']?$model['search_key']:'title';
        if(isset($_REQUEST[$key])){
            $map[$key]	=	array('like','%'.$_GET[$key].'%');
            unset($_REQUEST[$key]);
        }
        // 条件搜索
        foreach($_REQUEST as $name=>$val){
            if(in_array($name,$fields)){
                $map[$name]	=	$val;
            }
        }
        $row    = empty($model['list_row']) ? 10 : $model['list_row'];

        //读取模型数据列表
        if($model['extend']){
            $name   = get_table_name($model['id']);
            $parent = get_table_name($model['extend']);
            $fix    = C("DB_PREFIX");

            $key = array_search('id', $fields);
            if(false === $key){
                array_push($fields, "{$fix}{$parent}.id as id");
            } else {
                $fields[$key] = "{$fix}{$parent}.id as id";
            }

            /* 查询记录数 */
            $count = M($parent)->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")->where($map)->count();

            // 查询数据
            $data   = M($parent)
                ->join("INNER JOIN {$fix}{$name} ON {$fix}{$parent}.id = {$fix}{$name}.id")
                /* 查询指定字段，不指定则查询所有字段 */
                ->field(empty($fields) ? true : $fields)
                // 查询条件
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order("{$fix}{$parent}.id DESC")
                /* 数据分页 */
                ->page($page, $row)
                /* 执行查询 */
                ->select();

        } else {
            if($model['need_pk']){
                in_array('id', $fields) || array_push($fields, 'id');
            }
            $name = parse_name(get_table_name($model['id']), true);
            $data = M($name)
                /* 查询指定字段，不指定则查询所有字段 */
                ->field(empty($fields) ? true : $fields)
                // 查询条件
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order($model['need_pk']?'id DESC':'')
                /* 数据分页 */
                ->page($page, $row)
                /* 执行查询 */
                ->select();

            /* 查询记录总数 */
            $count = M($name)->where($map)->count();
        }

        //分页
        if($count > $row){
            $page = new \Think\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }
        
        //检查field如果是select或foreign_int/foreign_string则显示对应文字 2016-06-14 start
        if (!empty($fields)) {
            $fieldCondition = '';
            foreach ($fields as $key1 => $value1) {
                $fieldCondition .= '"' . $value1 . '",';
            }
            $fieldCondition = substr($fieldCondition, 0, -1);
            $myattributes = D('attribute')->field(array('name', 'title','type','extra'))->where('model_id=' . $model['id'] . ' and name in (' . $fieldCondition . ') ')->select();
        
            if (!empty($myattributes)) {
                foreach ($myattributes as $i => $attr) {
                    if ($attr['type'] == 'select') {
                        $select_extra = parse_field_attr($attr['extra']);
                        foreach ($data as $key => $dataValue) {
                            if (array_key_exists($dataValue[$attr['name']], $select_extra)) {
                                $data[$key][$attr['name']] = $select_extra[$dataValue[$attr['name']]];
                            }
                        }
                    } else if (in_array($attr['name'], $foreignFields)) {
                        $foreignKeyArray = array();
                        foreach ($data as $key => $dataValue) {
                            array_push($foreignKeyArray, $dataValue[$attr['name']]);
                        }
                        $foreignKeyString = implode(',', $foreignKeyArray);
                        $foreign_extra = parse_field_attr($attr['extra']);
                        $foreignTable = $foreign_extra['table'];
                        $foreignKeyField = $foreign_extra['key'];
                        $foreignValueField = $foreign_extra['value'];
                        if(!empty($foreignTable) && !empty($foreignKeyField) && !empty($foreignValueField)) {
                            $foreignValueArray = D($foreignTable)->field(array($foreignKeyField, $foreignValueField))
                                                ->where($foreignKeyField . ' in (' . $foreignKeyString . ')')
                                                ->select();
                            if(!empty($foreignValueArray)) {
                                $foreignMap = array();
                                foreach($foreignValueArray as $foreignValue) {
                                    $foreignMap[$foreignValue[$foreignKeyField]] = $foreignValue[$foreignValueField];
                                }
                                
                                foreach ($data as $key => $dataValue) {
                                    if (array_key_exists($dataValue[$attr['name']], $foreignMap)) {
                                        $data[$key][$attr['name'] . '.foreign'] = $foreignMap[$dataValue[$attr['name']]];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        //检查field如果是select或foreign_int/foreign_string则显示对应文字 2016-06-14 end

        $data   =   $this->parseDocumentList($data,$model['id']);
        $this->assign('model', $model);
        $this->assign('list_grids', $grids);
        $this->assign('list_data', $data);
        $this->meta_title = $model['title'].'列表';
        $this->display($model['template_list']);
    }

    public function del($model = null, $ids=null){
        $model = M('Model')->find($model);
        $model || $this->error('模型不存在！');

        $ids = array_unique((array)I('ids',0));

        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }

        $Model = M(get_table_name($model['id']));
        $map = array('id' => array('in', $ids) );
        if($Model->where($map)->delete()){
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * 设置一条或者多条数据的状态
     * @author huajie <banhuajie@163.com>
     */
    public function setStatus($model='Document'){
        return parent::setStatus($model);
    }
    
    public function edit($model = null, $id = 0){
        //获取模型信息
        $model = M('Model')->find($model);
        $model || $this->error('模型不存在！');

        if(IS_POST){
            $Model  =   D(parse_name(get_table_name($model['id']),1));
            // 获取模型的字段信息
            $Model  =   $this->checkAttr($Model,$model['id']);
            if($Model->create() && $Model->save()){
                $this->success('保存'.$model['title'].'成功！', U('lists?model='.$model['name']));
            } else {
                $this->error($Model->getError());
            }
        } else {
            $fields     = get_model_attribute($model['id']);

            //foreign_int 和 foreign_string. 2016-06-14 start
            if(!empty($fields)) {
                foreach($fields as $key1 => $value1) {
                    if(!empty($value1)) {
                        foreach($value1 as $key2 => $value2) {
                            if($value2['type'] == 'foreign_int' || $value2['type'] == 'foreign_string') {
                                $foreign_extra = parse_field_attr($value2['extra']);
                                $fields[$key1][$key2]['foreign'] = $this->getForeignArray($foreign_extra['table'], $foreign_extra['key'], $foreign_extra['value']);
                            }
                        }
                    }
                }
            }
            //foreign_int 和 foreign_string. 2016-06-14 end
            
            //获取数据
            $data       = M(get_table_name($model['id']))->find($id);
            $data || $this->error('数据不存在！');

            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->assign('data', $data);
            $this->meta_title = '编辑'.$model['title'];
            $this->display($model['template_edit']?$model['template_edit']:'');
        }
    }

    public function add($model = null){
        //获取模型信息
        $model = M('Model')->where(array('status' => 1))->find($model);
        $model || $this->error('模型不存在！');
        if(IS_POST){
            $Model  =   D(parse_name(get_table_name($model['id']),1));
            // 获取模型的字段信息
            $Model  =   $this->checkAttr($Model,$model['id']);
            if($Model->create() && $Model->add()){
                $this->success('添加'.$model['title'].'成功！', U('lists?model='.$model['name']));
            } else {
                $this->error($Model->getError());
            }
        } else {
            
            //接受初始化参数 2016-06-14 start
            $data = array();
            foreach ($_REQUEST as $key => $value) {
                $data[$key] = $value;
            }
            $this->assign('data', $data);
            //接受初始化参数 2016-06-14 end

            $fields = get_model_attribute($model['id']);
            
            //foreign_int 和 foreign_string. 2016-06-14 start
            if(!empty($fields)) {
                foreach($fields as $key1 => $value1) {
                    if(!empty($value1)) {
                        foreach($value1 as $key2 => $value2) {
                            if($value2['type'] == 'foreign_int' || $value2['type'] == 'foreign_string') {
                                $foreign_extra = parse_field_attr($value2['extra']);
                                $fields[$key1][$key2]['foreign'] = $this->getForeignArray($foreign_extra['table'], $foreign_extra['key'], $foreign_extra['value']);
                            }
                        }
                    }
                }
            }
            //foreign_int 和 foreign_string. 2016-06-14 end

            $this->assign('model', $model);
            $this->assign('fields', $fields);
            $this->meta_title = '新增'.$model['title'];
            $this->display($model['template_add']?$model['template_add']:'');
        }
    }

    protected function checkAttr($Model,$model_id){
        $fields     =   get_model_attribute($model_id,false);
        $validate   =   $auto   =   array();
        foreach($fields as $key=>$attr){
            if($attr['is_must']){// 必填字段
                $validate[]  =  array($attr['name'],'require',$attr['title'].'必须!');
            }
            // 自动验证规则
            if(!empty($attr['validate_rule'])) {
                $validate[]  =  array($attr['name'],$attr['validate_rule'],$attr['error_info']?$attr['error_info']:$attr['title'].'验证错误',0,$attr['validate_type'],$attr['validate_time']);
            }
            // 自动完成规则
            if(!empty($attr['auto_rule'])) {
                $auto[]  =  array($attr['name'],$attr['auto_rule'],$attr['auto_time'],$attr['auto_type']);
            }elseif('checkbox'==$attr['type']){ // 多选型
                $auto[] =   array($attr['name'],'arr2str',3,'function');
            }elseif('date' == $attr['type']){ // 日期型
                $auto[] =   array($attr['name'],'strtotime',3,'function');
            }elseif('datetime' == $attr['type']){ // 时间型
                $auto[] =   array($attr['name'],'strtotime',3,'function');
            }
        }
        return $Model->validate($validate)->auto($auto);
    }
    
    /**
     * 读取外键列表
     * @param string $table
     * @param string $keyField
     * @param string $valueField
     * @return multitype:Ambigous <>
     * @author 王洋 <wangyangcn@qq.com>
     */
    private function getForeignArray($table, $keyField, $valueField) {
        if(empty($table) || empty($keyField) || empty($valueField)) {
            return array();
        }
        $projectTypes = D($table)->select();
        $result = array();
        foreach($projectTypes as $i => $item) {
            $result[$item[$keyField]] = $item[$valueField];
        }
        return $result;
    }
}