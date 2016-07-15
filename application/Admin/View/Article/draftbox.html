<extend name="Public/base"/>

<!-- 子导航 -->
<block name="sidebar">
    <include file="sidemenu" />
</block>


<block name="body">
	<!-- 标题 -->
	<div class="main-title">
		<h2>
		草稿箱({$_total})
		</h2>
	</div>

	<!-- 按钮工具栏 -->
	<div class="cf">
		<div class="fl">
			<button class="btn ajax-post confirm" target-form="ids" url="{:U("Article/setStatus",array("status"=>-1))}">删 除</button>
		</div>

		<!-- 高级搜索 -->
		<div class="search-form fr cf">
		</div>
	</div>


	<!-- 数据表格 -->
    <div class="data-table">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">标题</th>
		<th class="">类型</th>
		<th class="">分类</th>
		<th class="">最后更新</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<volist name="list" id="vo">
		<tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="{$vo.id}" /></td>
			<td>{$vo.id} </td>
			<td><a data-id="{$vo.id}" href="{:U('Article/edit?cate_id='.$vo['category_id'].'&id='.$vo['id'])}">{$vo.title}</a></td>
			<td>{:get_document_type($vo['type'])}</td>
			<td><span>{:get_cate($vo['category_id'])}</span></td>
			<td><span>{$vo.update_time|time_format}</span></td>
			<td><a href="{:U('Article/edit?cate_id='.$vo['category_id'].'&id='.$vo['id'])}">编辑</a>
				<a href="{:U('Article/setstatus?ids='.$vo['id'].'&status='.abs(1-$vo['status']))}" class="ajax-get">{$vo.status|show_status_op}</a>
				<a href="{:U('Article/setStatus?status=-1&ids='.$vo['id'])}" class="confirm ajax-get">删除</a>
                </td>
		</tr>
		</volist>
	</tbody>
    </table> 
        	</div>

	<!-- 分页 -->
    <div class="page">
        {$_page}
    </div>
</div>

</block>
<block name="script">
<link href="__STATIC__/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
<link href="__STATIC__/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="__STATIC__/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="__STATIC__/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">

</script>
</block>
