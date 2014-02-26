<extend name="Public/base" />

<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>{$title}内容列表</h2>
	</div>

	<div class="cf">
		<div class="fl">
		<eq name="model.extend" value="1">
			<div class="tools">
				<a class="btn" href="{:U('edit',array('name'=>$name))}">新 增</a>
				<button class="btn ajax-post confirm" target-form="ids" url="{:U('del?name='.$name)}">删 除</button>
			</div>
		</eq>
		</div>
		<!-- 高级搜索 -->
		<div class="search-form fr cf">
			<div class="sleft">
				<input type="text" name="{$model['search_key']|default='title'}" class="search-input" value="{:I('title')}" placeholder="请输入关键字">
				<a class="sch-btn" href="javascript:;" id="search" url="{:U('adminList','name='.$name,false)}"><i class="btn-search"></i></a>
			</div>
		</div>
	</div>

	<!-- 数据列表 -->
	<div class="data-table table-striped">
		<empty name="custom_adminlist">
			<!-- 数据列表 -->
	        <table>
				<thead>
					<tr>
						<th width="50">
						 	<input class="check-all" type="checkbox">序号
	                    </th>
						<volist name="list_grid" id="vo">
							<th>{$vo.title}</th>
						</volist>
					</tr>
				</thead>
				<tbody>
					<volist name="_list" id="lv" key="vo">
					<tr>
						<td><input class="ids" type="checkbox" value="{$lv.id}" name="ids[]">{$lv.id}</td>
						<volist name="list_grid" id="lk">
							<td>{:get_addonlist_field($lv, $lk, $name)}</td>
						</volist>
					</tr>
					</volist>
				</tbody>
			</table>
		<else />
			<present name="custom_adminlist">
				{$custom_adminlist}
			</present>
		</empty>
	</div>
	<!-- 分页 -->
    <div class="page">
        {$_page}
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
			$('.data-table th:last').attr('width',70);
		    //回车自动提交
		    $('.search-form').find('input').keyup(function(event){
		        if(event.keyCode===13){
		            $("#search").click();
		        }
    		});
		})
	</script>
</block>
