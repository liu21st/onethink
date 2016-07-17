<extend name="Public/base"/>

<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>行为日志</h2>
	</div>

    <div>
        <button class="btn ajax-get confirm" url="{:U('clear')}">清 空</button>
		<button class="btn ajax-post confirm" target-form="ids" url="{:U('remove')}">删 除</button>
    </div>
	<!-- 数据列表 -->
	<div class="data-table">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">行为名称</th>
		<th class="">执行者</th>
		<th class="">执行时间</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<notempty name="_list">
		<volist name="_list" id="vo">
		<tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="{$vo.id}" /></td>
			<td>{$vo.id} </td>
			<td>{:get_action($vo['action_id'],'title')}</td>
			<td>{:get_nickname($vo['user_id'])}</td>
			<td><span>{$vo.create_time|time_format}</span></td>
			<td><a href="{:U('Action/edit?id='.$vo['id'])}">详细</a>
				<a class="confirm ajax-get" href="{:U('Action/remove?ids='.$vo['id'])}">删除</a>
                </td>
		</tr>
		</volist>
		<else/>
		<td colspan="6" class="text-center"> aOh! 暂时还没有内容! </td>
		</notempty>
	</tbody>
    </table>
	</div>
	<!-- 分页 -->
	<div class="page">{$_page}</div>
	<!-- /分页 -->

</block>
<block name="script">
<script type="text/javascript">
$(function(){
	$("#action_add").click(function(){
		window.location.href = $(this).attr('url');
	})
})
</script>
</block>
