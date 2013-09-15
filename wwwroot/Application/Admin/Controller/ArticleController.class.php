<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Admin\Model\AuthGroupModel;
use COM\Page;
/**
 * 后台内容控制器
 * @author huajie <banhuajie@163.com>
 */

class ArticleController extends \Admin\Controller\AdminController {

	/* 左侧节点菜单定义 */
	static protected $nodes = array(
		array(
			'title'=>'文档列表', 'url'=>'article/index', 'group'=>'内容','hide'=>true,
			'operator'=>array(
				//权限管理页面的按钮
				array('title'=>'新增','url'=>'article/add'),
				array('title'=>'编辑','url'=>'article/edit'),
				array('title'=>'改变状态','url'=>'article/setStatus'),
				array('title'=>'保存数据','url'=>'article/update'),
				array('title'=>'回收站','url'=>'article/recycle'),
				array('title'=>'还原','url'=>'article/permit'),
				array('title'=>'清空回收站','url'=>'article/clear'),
			),
		),
	);

	private $cate_id = null;	//文档分类id

    /**
     * 控制器初始化方法
     * @see AdminController::_init()
     * @author huajie <banhuajie@163.com>
     */
    protected function _initialize(){
    	//调用父类的初始化方法
    	parent::_initialize();

		//获取左边菜单
		if(ACTION_NAME == 'index' || ACTION_NAME == 'add' || ACTION_NAME == 'edit' || ACTION_NAME == 'recycle'){
			$this->getMenu();
		}

		//获取回收站权限
		$show_recycle = $this->checkRule('Admin/article/recycle');
		$this->assign('show_recycle', is_administrator() || $show_recycle);
    }

    /**
     * 显示左边菜单，进行权限控制
     * @author huajie <banhuajie@163.com>
     */
    protected function getMenu(){
    	//获取动态分类
    	$cate_auth = AuthGroupModel::getAuthCategories(is_login());	//获取当前用户所有的内容权限节点
    	$cate = M('Category')->where(array('display'=>1,'status'=>1))->field('id,title,pid,allow_publish')->order('pid,sort')->select();

    	//没有权限的分类则不显示
    	if(!is_administrator()){
    		foreach ($cate as $key=>$value){
    			if(!in_array($value['id'], $cate_auth)){
    				unset($cate[$key]);
    			}
    		}
    	}

    	//获取默认显示的分类
    	foreach ($cate as $key=>$value){
    		if($value['allow_publish']){
    			$defaule_cate = $value['id'];
    			break;
    		}
    	}

    	$cate = list_to_tree($cate);	//生成分类树

    	//获取分类id
    	$cate_id = I('param.cate_id') == '' ? $defaule_cate : I('param.cate_id');
    	$this->cate_id = $cate_id;

    	//是否展开分类
    	if(ACTION_NAME != 'recycle'){
    		$hide_cate = true;
    	}

    	//生成每个分类的url
    	foreach ($cate as $key=>&$value){
    		$value['url'] = 'Article/index?cate_id='.$value['id'];
    		$value['level'] = 1;
    		if($cate_id == $value['id'] && $hide_cate){
    			$value['current'] = true;
    		}
    		if(!empty($value['_child'])){
	    		foreach ($value['_child'] as $ka=>&$va){
	    			$va['url'] = 'Article/index?cate_id='.$va['id'];
	    			$va['level'] = 2;
	    			if(!empty($va['_child'])){
		    			foreach ($va['_child'] as $k=>&$v){
		    				$v['url'] = 'Article/index?cate_id='.$v['id'];
		    				$v['pid'] = $va['id'];
		    				$v['level'] = 3;
		    				if($v['id'] == $cate_id){
		    					$is_child = true;
		    				}
		    			}
	    			}
	    			//展开子分类的父分类
	    			if($va['id'] == $cate_id || $is_child){
	    				$is_child = false;
	    				if($hide_cate){
		    				$value['current'] = true;
		    				$va['current'] = true;
	    				}
	    			}
	    		}
    		}
    	}
    	$this->assign('nodes', $cate);
    	$this->assign('cate_id', $this->cate_id);

    	//获取面包屑信息
    	$nav = get_parent_category($cate_id);
    	$this->assign('rightNav', $nav);
    }

	/**
	 * 内容管理首页
	 * @param $cate_id 分类id
	 * @author huajie <banhuajie@163.com>
	 */
	public function index($cate_id = null, $status = null, $title = null){
        if(is_null($cate_id)){
		    $cate_id = $this->cate_id;
        }

		/* 查询条件初始化 */
		$map = array();
		if(isset($title)){
			$map['title'] = array('like', '%'.$title.'%');
		}
        if ( isset($_GET['time-start']) ) {
            $map['create_time'][] = array('egt',strtotime(I('time-start')));

        }
        if ( isset($_GET['time-end']) ) {
            $map['create_time'][] = array('elt',24*60*60 + strtotime(I('time-end')));

        }
        if ( isset($_GET['nickname']) ) {
            $map['uid'] = M('Member')->where(array('nickname'=>I('nickname')))->getField('uid');
        }

		// 构建列表数据
		if(!empty($cate_id)){			//没有权限则不查询数据
			$Document = D('Document');
			$map['category_id'] = $cate_id;
            $map['pid']         =   I('pid',0);
            if($map['pid']){ // 子文档列表忽略分类
                unset($map['category_id']);
            }
			$list = $this->lists($Document,$map);
			intToString($list);
            if($map['pid']){
                // 获取上级文档
                $article    =   M('Document')->field('id,title,type')->find($map['pid']);
                $this->assign('article',$article);
            }
			//获取对应分类下的模型
			$models = get_category($cate_id, 'model');
            //检查该分类是否允许发布内容
            $allow_publish = get_category($cate_id, 'allow_publish');

            $this->assign('model', $models);
            $this->assign('status', $status);
            $this->assign('list', $list);
            $this->assign('allow', $allow_publish);

            $this->meta_title = '文档列表';
            $this->display();
		}else{
            $this->error('非法的文档分类');
        }
	}

	/**
	 * 设置一条或者多条数据的状态
	 * @author huajie <banhuajie@163.com>
	 */
	public function setStatus(){
		/*参数过滤*/
		$ids = I('request.ids');
		$status = I('request.status');
		if(empty($ids) || !isset($status)){
			$this->error('请选择要操作的数据');
		}

		/*拼接参数并修改状态*/
		$Model = 'Document';
		$map = array();
		if(is_array($ids)){
			$map['id'] = array('in', implode(',', $ids));
		}elseif (is_numeric($ids)){
			$map['id'] = $ids;
		}
		switch ($status){
			case -1 : $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));break;
			case 0 : $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));break;
			case 1 : $this->resume($Model, $map, array('success'=>'审核通过','error'=>'审核失败'));break;
			default : $this->error('参数错误');break;
		}
	}


	/**
	 * 文档新增页面初始化
	 * @author huajie <banhuajie@163.com>
	 */
	public function add(){
		$cate_id = I('get.cate_id','');
		$model_id = I('get.model_id','');
		$model_name = get_document_model($model_id, 'title');

		empty($cate_id) && $this->error('参数不能为空！');
		empty($model_id) && $this->error('该分类未绑定模型！');

		//检查该分类是否允许发布
		$allow_publish = D('Document')->checkCategory($cate_id);
		!$allow_publish && $this->error('该分类不允许发布内容！');

		/* 获取要编辑的模型模板 */
		$template = strtolower(get_document_model($model_id, 'name'));
		$extend = $this->fetch($template);
        $info['pid']      = $_GET['pid']?$_GET['pid']:0;
		$info['model_id'] = $model_id;
		$info['category_id'] = $cate_id;
        if($info['pid']){
            // 获取上级文档
            $article    =   M('Document')->field('id,title,type')->find($info['pid']);
            $this->assign('article',$article);
        }
		$this->assign('info', $info);
		$this->assign('template', $template);
		$this->assign('extend', $extend);
		$this->assign('type_list', get_type_bycate($cate_id));

		$this->meta_title = '新增'.$model_name;
		$this->display('edit');
	}

	/**
	 * 文档编辑页面初始化
	 * @author huajie <banhuajie@163.com>
	 */
	public function edit(){
		$id = I('get.id','');
		if(empty($id)){
			$this->error('参数不能为空！');
		}

		/*获取一条记录的详细数据*/
		$Document = D('Document');
		$data = $Document->detail($id);
		if(!$data){
			$this->error($Document->getError());
		}
        $data['create_time'] = empty($data['create_time']) ? '' : date('Y-m-d H:i',$data['create_time']);
        $data['dateline'] = empty($data['dateline']) ? '' : date('Y-m-d H:i',$data['dateline']);
        if($data['pid']){
            // 获取上级文档
            $article    =   M('Document')->field('id,title,type')->find($data['pid']);
            $this->assign('article',$article);
        }
		$this->assign('info', $data);
		$this->assign('model_id', $data['model_id']);
        if($data['type']>1){
            /* 获取要编辑的模型模板 */
            $data['template'] = strtolower(get_document_model($data['model_id'], 'name'));
            //获取扩展模板
            $extend = $this->fetch($data['template']);
            $this->assign('extend', $extend);
        }

		$this->assign('type_list', get_type_bycate($data['category_id']));

		$this->meta_title = '编辑文档';
		$this->display();
	}

	/**
	 * 更新一条数据
	 * @author huajie <banhuajie@163.com>
	 */
	public function update(){
		$res = D('Document')->update();
		if(!$res){
			$this->error(D('Document')->getError());
		}else{
			if($res['id']){
				$this->success('更新成功', '/'.MODULE_NAME.'/article/index/pid/'.$res['pid'].'/cate_id/'.$res['category_id']);
			}else{
				$this->success('新增成功', '/'.MODULE_NAME.'/article/index/pid/'.$res['pid'].'/cate_id/'.$res['category_id']);
			}
		}
	}

	/**
	 * 回收站列表
	 * @author huajie <banhuajie@163.com>
	 */
	public function recycle(){
        if ( is_administrator() ) {
            $map = array('status'=>-1);
        }else{
            $cate_auth = AuthGroupModel::getAuthCategories(is_login());
            if($cate_auth){
                $map = array('status'=>-1,'category_id'=>array('IN',implode(',',$cate_auth)));
            }else{
                $map = array( 'status'=>-1,'category_id'=>-1 );
            }
        }
        $list = D('Document')->where($map)->field('id,title,uid,create_time')->select();
		//处理列表数据
		foreach ($list as $k=>&$v){
			$v['username'] = get_username($v['uid']);
			$v['create_time'] = time_format($v['create_time']);
		}
		$this->assign('list', $list);
        $this->meta_title = '回收站';
        $this->display();
	}

	/**
	 * 还原被删除的数据
	 * @author huajie <banhuajie@163.com>
	 */
	public function permit(){
		/*参数过滤*/
		$ids = I('param.ids');
		if(empty($ids)){
			$this->error('请选择要操作的数据');
		}

		/*拼接参数并修改状态*/
		$Model = 'Document';
		$map = array();
		if(is_array($ids)){
			$map['id'] = array('in', implode(',', $ids));
		}elseif (is_numeric($ids)){
			$map['id'] = $ids;
		}
		$this->restore($Model,$map);
	}

	/**
	 * 清空回收站
	 * @author huajie <banhuajie@163.com>
	 */
	public function clear(){
		$res = D('Document')->remove();
		if($res !== false){
			$this->success('清空回收站成功！');
		}else{
			$this->error('清空回收站失败！');
		}
	}

}
