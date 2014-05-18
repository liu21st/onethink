<extend name="Public/base"/>

<block name="body">
    <!-- 标题栏 -->
    <div class="main-title">
        <h2>[{$model['title']}] 列表</h2>
    </div>
	<div class="cf">
		<div class="fl">
		<empty name="model.extend">
			<div class="tools">
				<a class="btn" href="{:U('add?model='.$model['id'])}">新 增</a>
				<button class="btn ajax-post confirm" target-form="ids" url="{:U('del?model='.$model['id'])}">删 除</button>
			</div>
		</empty>
		</div>
		<!-- 高级搜索 -->
		<div class="search-form fr cf">
			<div class="sleft">
				<input type="text" name="{$model['search_key']|default='title'}" class="search-input" value="{:I('title')}" placeholder="请输入关键字">
				<a class="sch-btn" href="javascript:;" id="search" url="{:U('Think/lists','model='.$model['name'],false)}"><i class="btn-search"></i></a>
			</div>

		</div>
	</div>


    <!-- 数据列表 -->
    <div class="data-table">
        <div class="data-table table-striped">
            <table>
                <!-- 表头 -->
                <thead>
                    <tr>
                        <th class="row-selected row-selected">
                            <input class="check-all" type="checkbox">
                        </th>
                        <volist name="list_grids" id="field">
                            <th>{$field.title}</th>
                        </volist>
                    </tr>
                </thead>

                <!-- 列表 -->
                <tbody>
                    <volist name="list_data" id="data">
                        <tr>
                            <td><input class="ids" type="checkbox" value="{$data['id']}" name="ids[]"></td>
                            <volist name="list_grids" id="grid">
                                <td>{:get_list_field($data,$grid,$model)}</td>
                            </volist>
                        </tr>
                    </volist>
                </tbody>
            </table>
        </div>
    </div>
    <div class="page">
        {$_page|default=''}
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

    //回车自动提交
    $('.search-form').find('input').keyup(function(event){
        if(event.keyCode===13){
            $("#search").click();
        }
    });

})
</script>
</block>
