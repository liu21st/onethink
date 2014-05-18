<extend name="Public/base"/>

<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>[{:get_model_by_id($model_id)}] 属性列表(不含继承属性)</h2>

	</div>
    <div class="tools">
        <a class="btn" href="{:U('Attribute/add?model_id='.$model_id)}">新 增</a>
    </div>

	<!-- 数据列表 -->
	<div class="data-table">
        <div class="data-table table-striped">
	<table class="">
    <thead>
        <tr>
		<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
		<th class="">编号</th>
		<th class="">字段</th>
		<th class="">名称</th>
		<th class="">数据类型</th>
		<th class="">操作</th>
		</tr>
    </thead>
    <tbody>
		<notempty name="_list">
		<volist name="_list" id="vo">
		<tr>
            <td><input class="ids" type="checkbox" name="id[]" value="{$vo.id}" /></td>
			<td>{$vo.id} </td>
			<td>{$vo.name}</td>
			<td><a data-id="{$vo.id}" href="{:U('Attribute/edit?id='.$vo['id'])}">{$vo.title}</a></td>
			<td><span>{:get_attribute_type($vo['type'])}</span></td>
			<td><a href="{:U('Attribute/edit?id='.$vo['id'])}">编辑</a>
				<a class="confirm ajax-get" href="{:U('Attribute/remove?id='.$vo['id'])}">删除</a>
                </td>
		</tr>
		</volist>
		<else/>
		<td colspan="6" class="text-center"> aOh! 暂时还没有内容! </td>
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
  	//导航高亮
    highlight_subnav('{:U('Model/index')}');
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
