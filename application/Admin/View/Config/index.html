<extend name="Public/base"/>

<block name="body">
	<div class="main-title">
		<h2>配置管理 [ <present name="Think.get.group">
         <a href="{:U('index')}">全部</a><else/><strong>全部</strong></present>&nbsp;<foreach name="group" item="vo">
		<neq name="group_id" value="$key">
         <a href="{:U('index?group='.$key)}">{$vo}</a><else/><strong>{$vo}</strong></neq>&nbsp;     
        </foreach> ]</h2>
	</div>

	<div class="cf">
		<a class="btn" href="{:U('add')}">新 增</a>
		<a class="btn" href="javascript:;">删 除</a>
		<button class="btn list_sort" url="{:U('sort?group='.I('group'),'','')}">排序</button>
        
		<!-- 高级搜索 -->
		<div class="search-form fr cf">
			<div class="sleft">
				<input type="text" name="name" class="search-input" value="{:I('name')}" placeholder="请输入配置名称">
				<a class="sch-btn" href="javascript:;" id="search" url="{:U('config/index')}"><i class="btn-search"></i></a>
			</div>
		</div>
	</div>

	<div class="data-table table-striped">
		<table>
			<thead>
				<tr>
					<th class="row-selected">
						<input class="checkbox check-all" type="checkbox">
					</th>
					<th>ID</th>
					<th>名称</th>
					<th>标题</th>
					<th>分组</th>
					<th>类型</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<notempty name="list">
				<volist name="list" id="config">
					<tr>
						<td><input class="ids row-selected" type="checkbox" name="id[]" value="{$config.id}"></td>
						<td>{$config.id}</td>
						<td><a href="{:U('edit?id='.$config['id'])}">{$config.name}</a></td>
						<td>{$config.title}</td>
						<td>{$config.group|get_config_group}</td>
						<td>{$config.type|get_config_type}</td>
						<td>
							<a title="编辑" href="{:U('edit?id='.$config['id'])}">编辑</a>
							<a class="confirm ajax-get" title="删除" href="{:U('del?id='.$config['id'])}">删除</a>
						</td>
					</tr>
				</volist>
				<else/>
				<td colspan="6" class="text-center"> aOh! 暂时还没有内容! </td>
				</notempty>
			</tbody>
		</table>
		<!-- 分页 -->
	    <div class="page">
	        {$_page}
	    </div>
	</div>
</block>

<block name="script">
<script type="text/javascript">
$(function(){
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});
	//回车搜索
	$(".search-input").keyup(function(e){
		if(e.keyCode === 13){
			$("#search").click();
			return false;
		}
	});
	//点击排序
	$('.list_sort').click(function(){
		var url = $(this).attr('url');
		var ids = $('.ids:checked');
		var param = '';
		if(ids.length > 0){
			var str = new Array();
			ids.each(function(){
				str.push($(this).val());
			});
			param = str.join(',');
		}

		if(url != undefined && url != ''){
			window.location.href = url + '/ids/' + param;
		}
	});
});
</script>
</block>