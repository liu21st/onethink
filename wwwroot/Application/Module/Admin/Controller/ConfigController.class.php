<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// 配置模块
class ConfigController extends CommonController {

	// 获取某个标签的配置参数
	public function tag() {
		$id	=	(int)$_GET['id'];
        if(empty($id)) {
            $id = 1;
        }
        $type = parseAttr(C('CONF_TYPE_LIST'));
		$this->assign("tagName",$type[$id]);
		$model	=	M("Config");
		$list	=	$model->where('is_show=1 AND tag='.$id)->order('sort')->select();
        if($list) {
       		$this->assign('list',$list);
        }
		$this->display();
	}

	// 批量修改配置参数
    public function saveConfig() {
        $Config = M("Config");
    	foreach($_POST as $key=>$val) {
            $config    = Array();
            $config['value']  =  $val;
            $where =  "name='".$key."'";
    		$Config->where($where)->save($config);
    	}
		$list			=	$Config->getField('name,value');
        $result   =  cache('config',array_change_key_case($list,CASE_UPPER));
        $this->success('配置修改成功！');
    }
    
    // 配置缓存
    public function cache(){
        $Config = M("Config");
		$list			=	$Config->getField('name,value');
        $result   =  cache('config',array_change_key_case($list,CASE_UPPER));
        $this->success('配置修改成功！');
    }

    // 配置排序
    public function sort() {
		$config = M('Config');
        $map = array();
        if(!empty($_GET['sortId'])) {
            $map['id']   = array('in',$_GET['sortId']);
        }
        $sortList   =   $config->where($map)->order('sort asc')->select();
        $this->assign("sortList",$sortList);
        $this->display();
        return ;
    }

}