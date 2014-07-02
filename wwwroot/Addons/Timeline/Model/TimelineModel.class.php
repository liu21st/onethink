<?php

namespace Addons\Timeline\Model;
use Think\Model;

/**
 * Timeline模型
 */
class TimelineModel extends Model{


	protected $_validate = array(
		array('title','require','事件名'),
		array('title','','事件名！',0,'unique',1),
	);

	public $model = array(
		'title'=>'时间轴事件',
		'template_add'=>'edit.html',
		'template_edit'=>'edit.html',
		'search_key'=>'',
		'extend'=>1,
	);

    /**
     * 获取链接id
     * @return int 链接对应的id
     * @author huajie <banhuajie@163.com>
     */
    protected function getLink(){
        $link = I('post.link_id');
        if(empty($link)){
            return 0;
        } else if(is_numeric($link)){
            return $link;
        }
        $res = D('Url')->update(array('url'=>$link));
        return $res['id'];
    }

	public $_fields = array(
		'id'=>array(
			'name'=>'id',
			'title'=>'ID',
			'type'=>'num',
			'remark'=>'',
			'is_show'=>0,
			'value'=>0,
		),
		'title'=>array(
			'name'=>'title',
			'title'=>'事件名',
			'type'=>'string',
			'remark'=>'',
			'is_show'=>1,
			'is_must'=>1,
		),'startDate'=>array(
			'name'=>'startDate',
			'title'=>'开始日期',
			'type'=>'datetime',
			'remark'=>'',
			'is_show'=>1,
			'value'=>0,
			'is_must'=>1,
		),'endDate'=>array(
			'name'=>'endDate',
			'title'=>'结束日期',
			'type'=>'datetime',
			'remark'=>'',
			'is_show'=>1,
			'value'=>0,
			'is_must'=>0,
		),
		'text'=>array(
			'name'=>'text',
			'title'=>'描述',
			'type'=>'editor',
			'remark'=>'',
			'is_show'=>1,
			'value'=>0,
			'is_must'=>1,
		),
		'cover_id'=>array(
			'name'=>'cover_id',
			'title'=>'媒体',
			'type'=>'picture',
			'remark'=>'',
			'is_show'=>1,
			'value'=>0,
			'is_must'=>0,
		),'author'=>array(
			'name'=>'author',
			'title'=>'媒体作者',
			'type'=>'string',
			'remark'=>'',
			'is_show'=>1,
			'value'=>0,
			'is_must'=>1,
		),
		'media_title'=>array(
			'name'=>'media_title',
			'title'=>'媒体标题',
			'type'=>'string',
			'remark'=>'',
			'is_show'=>1,
			'value'=>0,
			'is_must'=>1,
		),
	);

}
