<extend name="Public/base" />

<!-- 子导航 -->
<block name="sidebar">
    <include file="sidemenu" />
</block>

<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>待审核({:count($list)})</h2>
	</div>

    <div class="tools auth-botton">
        <button class="btn ajax-post confirm" target-form="ids" url="{:U("Article/setStatus",array("status"=>-1))}">删 除</button>
        <button url="{:U('article/setStatus?status=1')}" class="btn ajax-post" target-form="ids">审 核</button>
    </div>

	<!-- 数据列表 -->
	<div class="data-table table-striped">
			<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">标题</th>
		<th class="">创建者</th>
		<th class="">类型</th>
		<th class="">分类</th>
		<th class="">发布时间</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<volist name="list" id="vo">
		<tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="{$vo.id}" /></td>
			<td>{$vo.id} </td>
			<td><a href="{:U('Article/edit?cate_id='.$vo['category_id'].'&id='.$vo['id'])}">{$vo.title}</a></td>
			<td>{$vo.username} </td>
			<td><span>{:get_document_type($vo['type'])}</span></td>
			<td><span>{:get_cate($vo['category_id'])}</span></td>
			<td><span>{$vo.create_time|time_format}</span></td>
			<td><a href="{:U('Article/edit?cate_id='.$vo['category_id'].'&id='.$vo['id'])}">编辑</a>
				<a href="{:U('Article/setStatus?ids='.$vo['id'].'&status=1')}" class="ajax-get">审核</a>
				<a href="{:U('Article/setStatus?status=-1&ids='.$vo['id'])}" class="confirm ajax-get">删除</a>
                </td>
		</tr>
		</volist>
	</tbody>
    </table> 
        
	</div>
    <div class="page">
        {$_page}
    </div>
</block>
