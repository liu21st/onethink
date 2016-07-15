<extend name="Public/base" />

<!-- 子导航 -->
<block name="sidebar">
    <include file="sidemenu" />
</block>

<block name="body">
	<div class="main-title cf">
		<h2>
			文档排序 [ <a href="{$Think.cookie.__forward__}">返回列表</a> ]
		</h2>
	</div>
	<div class="sort">
		<form action="{:U('sort')}" method="post">
<!-- 			<div class="sort_top">
				查找：<input type="text"><button class="btn search" type="button">查找</button>
			</div> -->
			<div class="sort_center">
				<div class="sort_option">
					<select value="" size="8">
						<volist name="list" id="vo">
							<option class="ids" title="{$vo.title}" value="{$vo.id}">{$vo.title}</option>
						</volist>
					</select>
				</div>
				<div class="sort_btn">
					<button class="top btn" type="button">第 一</button>
					<button class="up btn" type="button">上 移</button>
					<button class="down btn" type="button">下 移</button>
					<button class="bottom btn" type="button">最 后</button>
				</div>
			</div>
			<div class="sort_bottom">
				<input type="hidden" name="ids">
				<button class="sort_confirm btn submit-btn" type="button">确 定</button>
				<button class="sort_cancel btn btn-return" type="button" url="{$Think.cookie.__forward__}">返 回</button>
			</div>
		</form>
	</div>
</block>

<block name="script">
	<script type="text/javascript">
		$(function(){
			sort();
			$(".top").click(function(){
				rest();
				$("option:selected").prependTo("select");
				sort();
			})
			$(".bottom").click(function(){
				rest();
				$("option:selected").appendTo("select");
				sort();
			})
			$(".up").click(function(){
				rest();
				$("option:selected").after($("option:selected").prev());
				sort();
			})
			$(".down").click(function(){
				rest();
				$("option:selected").before($("option:selected").next());
				sort();
			})
			$(".search").click(function(){
				var v = $("input").val();
				$("option:contains("+v+")").attr('selected','selected');
			})
			function sort(){
				$('option').text(function(){return ($(this).index()+1)+'.'+$(this).text()});
			}

			//重置所有option文字。
			function rest(){
				$('option').text(function(){
					return $(this).text().split('.')[1]
				});
			}

			//获取排序并提交
			$('.sort_confirm').click(function(){
				var arr = new Array();
				$('.ids').each(function(){
					arr.push($(this).val());
				});
				$('input[name=ids]').val(arr.join(','));
				$.post(
					$('form').attr('action'),
					{
					'ids' :  arr.join(',')
					},
					function(data){
						if (data.status) {
	                        updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
	                    }else{
	                        updateAlert(data.info,'alert-success');
	                    }
	                    setTimeout(function(){
	                        if (data.status) {
	                        	$('.sort_cancel').click();
	                        }
	                    },1500);
					},
					'json'
				);
			});

			//点击取消按钮
			$('.sort_cancel').click(function(){
				window.location.href = $(this).attr('url');
			});
		})
	</script>
</block>