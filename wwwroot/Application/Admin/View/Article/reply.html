<extend name="Public/base"/>

<!-- 子导航 -->
<block name="sidebar">
    <include file="sidemenu" />
</block>

<block name="body">
	<!-- 标题 -->
	<div class="main-title">
		<h2>
		子文档列表({$_total}) [
		<volist name="rightNav" id="nav">
		<a href="{:U('article/index','cate_id='.$nav['id'])}">{$nav.title}</a>
			<if condition="count($rightNav) gt $i"><i class="ca"></i></if>
		</volist>
		<present name="article">：<a href="{:U('article/index','cate_id='.$cate_id.'&pid='.$article['id'])}">{$article.title}</a></present>
		]
		<eq name="allow" value="0">（该分类不允许发布内容）</eq>
		</h2>
	</div>

	<!-- 按钮工具栏 -->
	<div class="cf">
		<div class="fl">
			<div class="btn-group">
				<eq name="allow" value="1">
					<button class="btn" id="document_add" <if condition="count($model) eq 1">url="{:U('article/add',array('cate_id'=>$cate_id,'pid'=>I('pid',0),'model_id'=>$model[0]))}"</if>>新 增
						<if condition="count($model) gt 1"><i class="btn-arrowdown"></i></if>
					</button>
					<if condition="count($model) gt 1">
					<ul class="dropdown nav-list">
						<volist name="model" id="vo">
						<li><a href="{:U('article/add',array('cate_id'=>$cate_id,'model_id'=>$vo,'pid'=>I('pid',0)))}">{$vo|get_document_model='title'}</a></li>
						</volist>
					</ul>
					</if>
				<else/>
					<button class="btn disabled" >新 增
						<if condition="count($model) gt 1"><i class="btn-arrowdown"></i></if>
					</button>
				</eq>
			</div>
            <button class="btn ajax-post" target-form="ids" url="{:U("Article/setStatus",array("status"=>1))}">启 用</button>
			<button class="btn ajax-post" target-form="ids" url="{:U("Article/setStatus",array("status"=>0))}">禁 用</button>
			<input type="hidden" class="hide-data" name="cate_id" value="{$cate_id}"/>
			<input type="hidden" class="hide-data" name="pid" value="{$pid}"/>
			<button class="btn ajax-post confirm" target-form="ids" url="{:U("Article/setStatus",array("status"=>-1))}">删 除</button>
		</div>

		<!-- 高级搜索 -->
		<div class="search-form fr cf">
			<div class="sleft">
				<div class="drop-down">
					<span id="sch-sort-txt" class="sort-txt" data="{$status}"><if condition="get_status_title($status) eq ''">所有<else/>{:get_status_title($status)}</if></span>
					<i class="arrow arrow-down"></i>
					<ul id="sub-sch-menu" class="nav-list hidden">
						<li><a href="javascript:;" value="">所有</a></li>
						<li><a href="javascript:;" value="1">正常</a></li>
						<li><a href="javascript:;" value="0">禁用</a></li>
						<li><a href="javascript:;" value="2">待审核</a></li>
					</ul>
				</div>
				<input type="text" name="content" class="search-input" value="{:I('content')}" placeholder="请输入内容关键字">
				<a class="sch-btn" href="javascript:;" id="search" url="{:U('article/index','pid='.I('pid',0).'&cate_id='.$cate_id,false)}"><i class="btn-search"></i></a>
			</div>
            <div class="btn-group-click adv-sch-pannel fl">
                <button class="btn">高 级<i class="btn-arrowdown"></i></button>
                <div class="dropdown cf">
                	<div class="row">
                		<label>更新时间：</label>
                		<input type="text" id="time-start" name="time-start" class="text input-2x" value="" placeholder="起始时间" /> -
                        <div class="input-append date" id="datetimepicker" style="display:inline-block">
                            <input type="text" id="time-end" name="time-end" class="text input-2x" value="" placeholder="结束时间"/>
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                	</div>
                    <!-- <div class="row"> -->
                        <!-- <label>创建者：</label> -->
                        <!-- <input type="text" name="username" class="text input-2x" value="" placeholder="请输入用户名"> -->
                    <!-- </div> -->
                </div>
            </div>
		</div>
	</div>

	<!-- 数据表格 -->
    <div class="data-table">
		<table class="">
			<thead>
				<tr>
				<th class="row-selected row-selected"><input class="check-all" type="checkbox"/></th>
				<th class="">编号</th>
				<th class="">内容</th>
				<th class="">创建者</th>
				<th class="">最后更新</th>
				<th class="">状态</th>
				<th class="">操作</th>
				</tr>
			</thead>
			<tbody>
				<volist name="list" id="vo">
				<tr>
					<td><input class="ids" type="checkbox" name="ids[]" value="{$vo.id}" /></td>
					<td>{$vo.id} </td>
					<td><a href="{:U('Article/index?cate_id='.$vo['category_id'].'&pid='.$vo['id'])}">{:mb_strimwidth($vo['content'],0,50,"...","utf-8")}</a></td>
					<td><span>{:get_username($vo['uid'])}</span></td>
					<td><span>{$vo.update_time|time_format}</span></td>
					<td>{$vo.status_text}</td>
					<td><a href="{:U('Article/edit?cate_id='.$vo['category_id'].'&id='.$vo['id'])}">编辑</a>
						<a href="{:U('Article/setStatus?ids='.$vo['id'].'&status='.abs(1-$vo['status']))}" class="ajax-get">{$vo.status|show_status_op}</a>
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

</block>
<block name="script">
<link href="__STATIC__/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
<php>if(C('COLOR_STYLE')=='blue_color') echo '<link href="__STATIC__/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">';</php>
<link href="__STATIC__/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="__STATIC__/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="__STATIC__/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
$(function(){
	//搜索功能
	$("#search").click(function(){
		var url = $(this).attr('url');
		var status = $("#sch-sort-txt").attr("data");
        var query  = $('.search-form').find('input').serialize();
        query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
        query = query.replace(/^&/g,'');
		if(status != ''){
			query += 'status=' + status + "&" + query;
        }
        if( url.indexOf('?')>0 ){
            url += '&' + query;
        }else{
            url += '?' + query;
        }
		window.location.href = url;
	});

	/* 状态搜索子菜单 */
	$(".search-form").find(".drop-down").hover(function(){
		$("#sub-sch-menu").removeClass("hidden");
	},function(){
		$("#sub-sch-menu").addClass("hidden");
	});
	$("#sub-sch-menu li").find("a").each(function(){
		$(this).click(function(){
			var text = $(this).text();
			$("#sch-sort-txt").text(text).attr("data",$(this).attr("value"));
			$("#sub-sch-menu").addClass("hidden");
		})
	});

	//只有一个模型时，点击新增
	$('#document_add').click(function(){
		var url = $(this).attr('url');
		if(url != undefined && url != ''){
			window.location.href = url;
		}
	});

    //回车自动提交
    $('.search-form').find('input').keyup(function(event){
        if(event.keyCode===13){
            $("#search").click();
        }
    });

    $('#time-start').datetimepicker({
        format: 'yyyy-mm-dd',
        language:"zh-CN",
	    minView:2,
	    autoclose:true
    });

    $('#datetimepicker').datetimepicker({
       format: 'yyyy-mm-dd',
        language:"zh-CN",
        minView:2,
        autoclose:true,
        pickerPosition:'bottom-left'
    })
})
</script>
</block>
