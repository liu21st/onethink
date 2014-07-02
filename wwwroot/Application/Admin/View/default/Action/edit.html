<extend name="Public/base" />

<block name="body">
	<div class="main-title cf">
		<h2>查看行为日志</h2>
	</div>

	<!-- 标签页导航 -->
	<div class="tab-wrap">
		<div class="tab-content">
			<!-- 表单 -->
			<form id="form" method="post" class="form-horizontal doc-modal-form">
				<!-- 基础 -->
				<div id="tab1" class="tab-pane in tab1">
					<div class="form-item cf">
						<label class="item-label">行为名称</label>
						<div class="controls">
							<span>{:get_action($info['action_id'], "title")}</span>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">执行者</label>
						<div class="controls">
							<span>{:get_nickname($info['user_id'])}</span>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">执行IP</label>
						<div class="controls">
							<span>{:long2ip($info['action_ip'])}</span>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">执行时间</label>
						<div class="controls">
							<span>{:date('Y-m-d H:i:s',$info['create_time'])}</span>
						</div>
					</div>
					<div class="form-item cf">
						<label class="item-label">备注</label>
						<div class="controls">
							<label class="textarea input-large">
								<textarea readonly="readonly">{$info.remark}</textarea>
							</label>
						</div>
					</div>
				</div>

				<!-- 按钮 -->
				<div class="form-item cf">
					<label class="item-label"></label>
					<div class="controls edit_sort_btn">
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
Think.setValue("extend", {$info.extend|default=0});
//导航高亮
highlight_subnav('{:U('Action/actionlog')}');


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
	    	 var self = $(this);
	    	 self.find('input').attr('name', 'field_sort[' + self.closest('ul').data('group') + '][]');
	     	//updateVal();
	     }
	 });

	$('#sortUl li b').click(function(){
		$(this).parent().remove();
		updateVal();
	});

	// 更新排序后的隐藏域的值
	function updateVal() {
		var fields = new Array();
		$('.form_field_sort').each(function(){
			var i = 1;
			var self = this;
			var group = $(self).attr('group');
			$(self).find('li').each(function(){
		   		var id = $(this).find('em').attr('data');
		   		$('#field-' + id).val(id + ':' + group + ':' + i++);
		   	});
		});

	}
})
</script>
</block>

