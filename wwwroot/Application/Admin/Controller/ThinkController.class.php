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
        $grids  = preg_split('/[;\r\n]+/s', $model['list_grid']);
        foreach ($grids as &$value) {
            $val      = explode(':', $value);
            $val[0]   = explode('|', $val[0]);
            $value    = array('field' => $val[0], 'title' => $val[1]);
            $fields[] = $val[0][0];
        }

        in_array('id', $fields) && array_push($fields, 'id');
        in_array('status', $fields) && array_push($fields, 'status');

        //读取模型数据列表
        $name = parse_name(get_table_name($model['id']), true);
        $row  = empty($model['list_row']) ? 10 : $model['list_row'];
        $map  = array('status' => array('egt', 0));
        $data = M($name)
                /* 查询指定字段，不指定则查询所有字段 */
                ->field(empty($fields) ? true : $fields) 
                ->where($map)
                /* 默认通过id逆序排列 */
                ->order('id DESC')
                /* 数据分页 */
                ->page($page, $row)
                /* 执行查询 */
                ->select(); 

        /* 查询记录总数 */
        $count = M($name)->where($map)->count(); 
        //分页
        
        if($count > $row){
            $page = new \COM\Page($count, $row);
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            $this->assign('_page', $page->show());
        }

        //
        $this->assign('model', $model['id']);
        $this->assign('list_grids', $grids);
        $this->assign('list_data', $data);
        $this->display($model['template_list']);
    }

    public function setStatus($model = null){
        /*参数过滤*/
        $ids    =   I('request.ids');
        $status =   I('request.status');
        if(empty($ids) || !isset($status)){
            $this->error('请选择要操作的数据');
        }

        /*拼接参数并修改状态*/
        $Model  =   get_table_name($model);
        $map    =   array();
        if(is_array($ids)){
            $map['id'] = array('in', implode(',', $ids));
        }elseif (is_numeric($ids)){
            $map['id'] = $ids;
        }
        switch ($status){
            case -1 :
                $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));
                break;
            case 0  :
                $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));
                break;
            case 1  :
                $this->resume($Model, $map, array('success'=>'审核通过','error'=>'审核失败'));
                break;
            default :
                $this->error('参数错误');
                break;
        }
    }

    public function edit($model = null, $id = 0){
        if(IS_POST){

        } else {
            //获取模型信息
            $model = M('Model')->where(array('status' => 1))->find($model);
            $model || $this->error('模型不存在！');
            $fields = get_model_attribute($model['id']);

            //获取数据
            $data = M(get_table_name($model))->find($id);
            $data || $this->error('数据不存在！');

            $this->assign('fields', $fields);
            $this->assign('data', $data);
            $this->display('edit');
        }
    }

    public function add($model = null){
        if(IS_POST){

        } else {
            //获取模型信息
            $model = M('Model')->where(array('status' => 1))->find($model);
            $model || $this->error('模型不存在！');
            $fields = get_model_attribute($model['id']);

            $this->assign('fields', $fields);
            $this->display('edit');
        }
    }

}
