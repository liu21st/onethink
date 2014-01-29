<extend name="Public/base" />

<block name="body">
	<div class="main-title cf">
		<h2>新增模型</h2>
	</div>

	<!-- 标签页导航 -->
	<div class="tab-wrap">
		<div class="tab-content">
			<!-- 表单 -->
			<form id="form" action="{:U('update')}" method="post" class="form-horizontal doc-modal-form">
				<!-- 基础 -->
				<div id="tab1" class="tab-pane in tab1">
					<div class="form-item cf">
						<label class="item-label">模型标识<span class="check-tips">（请输入文档模型标识）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="name" value="">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">模型名称<span class="check-tips">（请输入模型的名称）</span></label>
						<div class="controls">
							<input type="text" class="text input-large" name="title" value="">
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">模型类型<span class="check-tips">（目前支持独立模型和文档模型）</span></label>
						<div class="controls">
							<select name="extend">
								<option value="0">独立模型</option>
								<option value="1">文档模型</option>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">引擎类型<span class="check-tips"></span></label>
						<div class="controls">
							<select name="engine_type">
								<option value="MyISAM">MyISAM</option>
								<option value="InnoDB">InnoDB</option>
                                <option value="MEMORY">MEMORY</option>
                                <option value="BLACKHOLE">BLACKHOLE</option>
                                <option value="MRG_MYISAM">MRG_MYISAM</option>
                                <option value="ARCHIVE">ARCHIVE</option>
							</select>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">是否需要主键<span class="check-tips">（选“是”则会新建默认的id字段作为主键）</span></label>
						<div class="controls">
							<select name="need_pk">
								<option value="1" selected="selected">是</option>
								<option value="0">否</option>
							</select>
						</div>
					</div>
				</div>

				<!-- 按钮 -->
				<div class="form-item cf">
					<label class="item-label"></label>
					<div class="controls edit_sort_btn">
						<button class="btn submit-btn ajax-post no-refresh" type="submit" target-form="form-horizontal">确 定</button>
						<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</block>
<block name="script">
<script type="text/javascript" src="__STATIC__/jquery.dragsort-0.5.1.min.js"></script>
<script type="text/javascript" charset="utf-8">
    //导航高亮
    highlight_subnav('{:U('Model/index')}');

    $(function(){
    	showTab();
    })
//拖曳插件初始化
$(function(){
	$(".dragsort").dragsort({
	     dragSelector:'li',
	     placeHolderTemplate: '<li class="draging-place">&nbsp;</li>',
	     dragBetween:true,	//允许拖动到任意地方
	     dragEnd:function(){
	     	updateVal();
	     }
	 });

	$('#sortUl li b').click(function(){
		$(this).parent().remove();
		updateVal();
	});

	// 更新排序后的隐藏域的值
	function updateVal() {
		var sortVal = [];
		var i = 1;
		var val = '';
	   	$('#base li').each(function(){
	   		sortVal[i++] = $(this).find('em').text();
	   	});
	   	i = -1;
	   	$('#extend li').each(function(){
	   		sortVal[i--] = $(this).find('em').text();
	   	});
	   	//将排序数组拼接成json格式字符串
	   	val += "[";
	   	for (k in sortVal){
	   		val += "\"" + sortVal[k] + "\"" + ":" + k + ",";
	   	}
	   	val = val.substr(0,val.length - 1) + "]";
	    $("input[name='fields']").val(val);
	}
})
</script>
</block>

