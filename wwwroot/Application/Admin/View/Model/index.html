<extend name="Public/base"/>

<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>模型列表</h2>

	</div>
    <div class="tools">
        <a class="btn" href="{:U('Model/add')}">新 增</a>
        <button class="btn ajax-post" target-form="ids" url="{:U('Model/setStatus',array('status'=>1))}">启 用</button>
        <button class="btn ajax-post" target-form="ids" url="{:U('Model/setStatus',array('status'=>0))}">禁 用</button>
        <a class="btn" href="{:U('Model/generate')}">生 成</a>
    </div>

	<!-- 数据列表 -->
	<div class="data-table">
        <div class="data-table table-striped">
<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">标识</th>
		<th class="">名称</th>
		<th class="">创建时间</th>
		<th class="">状态</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
	<notempty name="_list">
		<volist name="_list" id="vo">
		<tr>
            <td><input class="ids" type="checkbox" name="ids[]" value="{$vo.id}" /></td>
			<td>{$vo.id} </td>
			<td>{$vo.name}</td>
			<td><a data-id="{$vo.id}" href="{:U('model/edit?id='.$vo['id'])}">{$vo.title}</a></td>
			<td><span>{$vo.create_time|time_format}</span></td>
			<td>{$vo.status_text}</td>
			<td>
				<a href="{:U('think/lists?model='.$vo['name'])}">数据</a>
				<a href="{:U('model/setstatus?ids='.$vo['id'].'&status='.abs(1-$vo['status']))}" class="ajax-get">{$vo.status|show_status_op}</a>
				<a href="{:U('model/edit?id='.$vo['id'])}">编辑</a>
				<a href="{:U('model/del?ids='.$vo['id'])}" class="confirm ajax-get">删除</a>
            </td>
		</tr>
		</volist>
		<else/>
		<td colspan="7" class="text-center"> aOh! 暂时还没有内容! </td>
		</notempty>
	</tbody>
    </table>

        </div>
    </div>
    <div class="page">
        {$_page}
    </div>
</block>

<block name="script">
    <script src="__STATIC__/thinkbox/jquery.thinkbox.js"></script>
    <script type="text/javascript">
    $(function(){
    	$("#search").click(function(){
    		var url = $(this).attr('url');
    		var status = $('select[name=status]').val();
    		var search = $('input[name=search]').val();
    		if(status != ''){
    			url += '/status/' + status;
    		}
    		if(search != ''){
    			url += '/search/' + search;
    		}
    		window.location.href = url;
    	});
})
</script>
</block>
