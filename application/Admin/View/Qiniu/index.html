<extend name="Public/base" />

<block name="style">
	<style>
		.sidebar .right-cnt{
			-webkit-border-radius: 6px;
			-moz-border-radius: 6px;
			border-radius: 6px;
			-webkit-box-shadow: 1px 2px 5px rgba(180,180,180,0.75);
			-moz-box-shadow: 1px 2px 5px rgba(180,180,180,0.75);
			box-shadow: 1px 2px 5px rgba(180,180,180,0.75);
			min-height: 530px;
			display: block;
			border: 1px solid #c9ccd0;
			background-color: #fff;
		}
		.sidebar .right-head{
			overflow: hidden;
			padding: 0 30px;
			height: 50px;
			line-height: 50px;
			text-align: center;
			font-size: 14px;
			border-bottom: 1px solid #c9ccd0;
			background-color: #dee0e6;
			background-image: url(../../image/report/gradient-line.png);
			background-position: top;
			background-repeat: no-repeat;
			-webkit-border-top-right-radius: 5px;
			-moz-border-radius-topright: 5px;
			border-top-right-radius: 5px;
			-webkit-border-top-left-radius: 5px;
			-moz-border-radius-topleft: 5px;
			border-top-left-radius: 5px;
		}
		.sidebar .prev-block{
			width: 100%;
			height: 203px;
			line-height: 203px;
			margin-bottom: 40px;
			text-align: center;
		}
		.sidebar .prev-block .file-prev{
			max-width: 203px;
			max-height: 203px;
			vertical-align: middle;
			background-color: #f0f0f0;
			-webkit-box-shadow: 0 0 2px rgba(120,120,120,0.5);
			-moz-box-shadow: 0 0 2px rgba(120,120,120,0.5);
			box-shadow: 0 0 2px rgba(120,120,120,0.5);
		}
	}
	.sidebar .right-body-block .file-info-item {
		margin-bottom: 8px;
	}
</style>
</block>

<block name="sidebar">
	<div class="right-cnt">
		<!-- ko ifnot: certainFile -->
		<div class="right-head">
			未选择文件
		</div>
		<div class="right-body">
			<div class="right-body-block">
				<div class="prev-block">
					<span>点击左侧文件名以查看信息</span>
				</div>
			</div>
		</div>
		<!-- /ko -->
		<!-- ko with: certainFile --><!-- /ko -->
	</div>
</block>
<block name="body">
	<!-- 标题栏 -->
	<div class="main-title">
		<h2>blackwhite的七牛空间</h2>
	</div>
	<div>
		<form action="__SELF__" method="post">
			<input type="text" class="search-input" name="prefix" placeholder="输入资源名前缀匹配">
			<button class="btn" type="submit">搜索</button>

			<input type="file" name="file" id="upload-file">
			<button class="btn" target-form="ids" id="batchDelBtn" type="button">删 除</button>
			<a href="javascript:location.reload(true);" class="btn">刷新</a>
		</form>
	</div>

	<!-- 数据列表 -->
	<div class="data-table table-striped">
		<form action="{:U('batchDel')}" id="ids">
			<table id="file_list">
				<thead>
					<tr>
						<th></th>
						<th>文件名</th>
						<th>mimeType</th>
						<th >最后更新时间</th>
						<th>文件大小</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<volist name="_list" id="vo">
						<tr>
							<td><input type="checkbox" name="key" value="{$vo.key}"></td>
							<td>{$vo.key}</td>
							<td>{$vo.mimeType} </td>
							<td>{$vo.putTime|strval|substr=###,0,11|bcmul="1000000000"|date="Y-m-d H:i:s",###}</td>
							<td>{$vo.fsize|format_bytes}</td>
							<td>
								<a href="javascript:;" data-href="{:U('rename?file='.$vo['key'])}" class="rename" title="{$vo.key}">重命名</a>
								<a href="{:$qiniu->downLink($vo['key'])}" target="_blank">下载</a>
								<a href="{:U('del?file='.$vo['key'])}">删除</a>
								<a href="javascript:adv('{$vo.mimeType}','{$vo.key}')">高级</a>
							</td>
						</tr>
					</volist>
				</tbody>
			</table>
		</form>
	</div>
	<!-- 分页 -->
	<div class="page">
		{$_page}
	</div>
</block>

<block name="script">
	<script type="text/javascript" src="__STATIC__/uploadify/jquery.uploadify.min.js"></script>
	<script type="text/javascript" src="__STATIC__/thinkbox/jquery.thinkbox.js"></script>

	<script id="hooktpl" type="text/tpl">
		<form action="" method="post" class="form-horizontal hooktpl" id="rename_form">
			<label class="item-label">请输入文件名：</label>
			<div class="controls">
				<label class="textarea">
					<input type="text" name="new_name" class="input-large" />
				</label>
			</div>
		</form>
	</script>

	<script id="imgAdv" type="text/tpl">
		<p>
			<table>
				<tbody>
					<tr>
						<td>
							<input type="radio" class="type" name="type" value=0 checked>
						</td>
						<td>
							查看基本信息
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" class="type" name="type" value=1>
						</td>
						<td>
							查看exif
						</td>
					</tr>
					<tr>
						<td>
							<input type="radio" class="type" name="type" value=2>
						</td>
						<td>
							<form action="{:U('Qiniu/dealImage')}" class="form-horizontal hooktpl" id="resize_form" target="_blank" method="post">
								<label class="item-label">缩放类型：</label>
								<div class="controls">
									<label class="radio">
										<input type="radio" name="mode" value="1">
										非等比缩放
									</label>
									<label class="radio">
										<input type="radio" name="mode" value="2" checked>
										等比缩放
									</label>
								</div>
								<div class="controls">
									<label class="text">
										宽度
										<input type="text" name="w">
									</label>
									<label class="text">
										高度
										<input type="text" name="h">
									</label>
								</div>
								<div class="controls">
									<label class="text">
										质量
										<input type="text" name="q" value="100">(1~100)
									</label>
									<label class="select">
										输出格式：
										<select	name="format">
											<option>jpg</option>
											<option>gif</option>
											<option>png</option>
											<option>webp</option>
										</select>
									</label>
									<input type="hidden" name="imageView" value=1>
									<input type="hidden" name="key">
								</div>
							</form>
						</td>
					</tr>
				</tbody>
			</table>
		</p>
	</script>

	<script id="videoAdv" type="text/tpl">
		<form action="" method="post" class="form-horizontal hooktpl" id="rename_form">
			<label class="item-label">请输入文件名：</label>
			<div class="controls">
				<label class="textarea">
					<input type="text" name="new_name" class="input-large" />
				</label>
			</div>
		</form>
	</script>

	<script id="mdAdv" type="text/tpl">
		<form action="{:U('Qiniu/dealDoc')}" class="form-horizontal hooktpl" id="translate_form" target="_blank" method="post">
			<label class="item-label">请输入远程css路径：</label>
			<div class="controls">
				<label class="textarea">
					<input type="text" name="cssurl" class="input-large" />
				</label>
			</div>
			<label class="item-label">请选择模式：</label>
			<div class="controls">
				<label class="radio">
					<input type="radio" name="mode" value=0 checked/>
					完整的 HTML(head+body) 输出
				</label>
				<label class="radio">
					<input type="radio" name="mode" value=1 />
					只转为HTML Body
				</label>
			</div>
			<input type="hidden" name="key">
		</form>
	</script>

	<script type="text/javascript">
		(function($){
			//上传文件
			/* 初始化上传插件 */
			$("#upload-file").uploadify({
				"height"          : 30,
				"swf"             : "__STATIC__/uploadify/uploadify.swf",
				"fileObjName"     : "qiniu_file",
				"buttonText"      : "上传文件",
				"uploader"        : "{:U('uploadOne',array('session_id'=>session_id(),'ajax'=>1))}",
				"width"           : 120,
				'removeTimeout'   : 1,
				'onInit'		  : init,
				'multi'			  : false,
				"onUploadSuccess" : uploadSuccess,
				'onFallback' : function() {
		            alert('未检测到兼容版本的Flash.');
		        }
			});
			function init(){
				$('#upload-file, #upload-file-queue').css('display','inline-block');
			}

			/* 文件上传成功回调函数 */
			function uploadSuccess(file, data){
				console.log(data);
				var data = $.parseJSON(data);
				if(data.status){
					updateAlert('上传成功', 'alert-success');
					setTimeout(function(){
						location.reload(true);
					},1500);
				} else {
					console.log(data.data);
					updateAlert('上传失败');
				}
			}

			//文件信息预览
			$('#file_list tr').click(function(event){
				$target = $(event.target);
				$tr = $(this);
				if(!$target.is(':checkbox')){
					$('#file_list :checkbox').removeAttr('checked');
					$tr.find(':checkbox').prop('checked',true);
					$.ajax({
						url : '{:U('detail')}',
						data : { key : $('td:eq(1)', $tr).text()},
						success: function(data){
							if(data.status){
								$('.sidebar .right-cnt').html(data.tpl);
							}else{
								updateAlert('获取文件信息失败');
							}
						}
					})
				}
			});

			//批量删除
			$('#batchDelBtn').click(function(){
				var $checked = $('#file_list input[name="key"]:checked');
				if($checked.length != 0){
					if(confirm('您确认删除吗？')){
						$.ajax({
							url : '{:U('batchDel')}',
							data : { key : $checked.serializeArray()},
							success: function(data){
								if(data.status){
									updateAlert('删除成功','alert-success');
									location.reload(true);
								}else{
									updateAlert('批量删除失败');
								}
							}
						});
					}
				}else{
					updateAlert('请先选择一项');
				}
				return false;
			});

			//重命名


			$('.rename').click(function(){
				var action = $.trim($(this).data('href'));
				var html = $($("#hooktpl").html());
				html.find("input[name=new_name]").val(this.title);
				html.find("input[name=new_name]").parents('form').attr('action', action);
				//ajaxForm 公共函数
		        function ajaxForm(element,callback,dataType){
		            var form = $(element).closest('form');
		            var dataType = dataType || 'json';
		            $.ajax({
		                type: "POST",
		                url: form.attr('action'),
		                data: form.serialize(),
		                async: false,
		                dataType:dataType,
		                success: function(data) {
		                    if($.isFunction(callback)){
		                        callback(data,form);
		                    }
		                }
		            });
		        }

				option = {
					title:'文件名更改',
					actions:['close'],
					drag:true,
					tools:true,
					buttons:{"ok":['保存', 'blue',function(){
						var _this = this;
						ajaxForm(this.find('.input-large'),function(data){
							if (data.status){
								_this.hide();
								updateAlert(data.info,'alert-success');
								setTimeout(function(){
		                        	location.reload(true);
		                        },1000);
				            }else{
				            	updateAlert(data.info);
				            }
						})
					}]}
				}
				$.thinkbox(html,option);
			});

		})(jQuery);
		//高级处理
		function adv(mime, key){
			if($.inArray(mime,['image/jpeg','image/png', 'image/gif']) != -1){
				//图片
				var html = $($("#imgAdv").html());
				var option = {
					title:'图片处理',
					actions:['close'],
					drag:true,
					tools:true,
					buttons:{"ok":['提 交', 'blue',function(){
						var _this = this;
						var type = this.find('[name="type"]:checked').val();
						if(type == 2){
							this.find('[name=key]').val(key);
							this.find('#resize_form').submit();
						}else if(type == 0){
							window.open(Think.U('Admin/Qiniu/dealImage','key='+key+'&imageInfo=1'));
						}else{
							window.open(Think.U('Admin/Qiniu/dealImage','key='+key+'&exif=1'))
						}
						this.hide();
					}]}
				}
			}else if(key.slice(-3) == '.md'){
				//markdown
				var html = $($("#mdAdv").html());
				var option = {
					title:'md2html转换',
					actions:['close'],
					drag:true,
					tools:true,
					buttons:{"ok":['提交', 'blue',function(){
						var _this = this;
						this.find('[name=key]').val(key);
						this.find('#translate_form').submit();
					}]}
				}
			}else{
				//视频
				var html = $($("#videoAdv").html());
				var option = {
					title:'视频处理',
					actions:['close'],
					drag:true,
					tools:true,
					buttons:{"ok":['保存', 'blue',function(){
						var _this = this;
						ajaxForm(this.find('.input-large'),function(data){
							if (data.status){
								_this.hide();
								updateAlert(data.info,'alert-success');
								setTimeout(function(){
		                        	location.reload(true);
		                        },1000);
				            }else{
				            	updateAlert(data.info);
				            }
						})
					}]}
				}
			}
			$.thinkbox(html,option);
		}
	</script>
</block>
