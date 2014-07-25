<extend name="Public/base" />

<block name="body">
<script type="text/javascript" src="__STATIC__/uploadify/jquery.uploadify.min.js"></script>
    <div class="main-title cf">
        <h2>{$meta_title}</h2>
    </div>
    <!-- 标签页导航 -->
<div class="tab-wrap">
    <div class="tab-content">
    <!-- 表单 -->
    <form id="form" action="{:U('edit?name='.trim($_GET['name']))}" method="POST" class="form-horizontal">
        <!-- 基础文档模型 -->
		<div id="tab" class="tab-pane in tab1">
            <volist name="fields" id="field">
                <if condition="$field['is_show'] == 1 || ($field['is_show'] == 3 && $data['id']) || ($field['is_show'] == 2 && !$data['id'])">
                <div class="form-item cf">
                    <label class="item-label">{$field['title']}<span class="check-tips"><notempty name="field['remark']">（{$field['remark']}）</notempty></span></label>
                    <div class="controls">
                        <switch name="field.type">
                            <case value="num">
                                <input type="text" class="text input-mid" name="{$field.name}" value="{$data[$field['name']]}">
                            </case>
                            <case value="string">
                                <input type="text" class="text input-large" name="{$field.name}" value="{$data[$field['name']]}">
                            </case>
                            <case value="textarea">
                                <label class="textarea input-large">
                                <textarea name="{$field.name}">{$data[$field['name']]}</textarea>
                                </label>
                            </case>
                            <case value="datetime">
                                <input type="text" name="{$field.name}" class="text input-large time" value="{$data[$field['name']]|date='Y-m-d H:i',###}" placeholder="请选择时间" />
                            </case>
                            <case value="bool">
                                <select name="{$field.name}">
                                    <volist name=":parse_field_attr($field['extra'])" id="vo">
                                        <option value="{$key}" <eq name="data[$field['name']]" value="$key">selected</eq>>{$vo}</option>
                                    </volist>
                                </select>
                            </case>
                            <case value="select">
                                <select name="{$field.name}">
                                    <volist name=":parse_field_attr($field['extra'])" id="vo">
                                        <option value="{$key}" <eq name="data[$field['name']]" value="$key">selected</eq>>{$vo}</option>
                                    </volist>
                                </select>
                            </case>
                            <case value="radio">
                                <volist name=":parse_field_attr($field['extra'])" id="vo">
                                	<label class="radio">
                                    <input type="radio" value="{$key}" name="{$field.name}" <eq name="data[$field['name']]" value="$key">checked="checked"</eq>>{$vo}
                                	</label>
                                </volist>
                            </case>
                            <case value="checkbox">
                                <volist name=":parse_field_attr($field['extra'])" id="vo">
                                	<label class="checkbox">
                                    <input type="checkbox" value="{$key}" name="{$field.name}[]" <in name="key" value="$data[$field['name']]" >checked="checked"</in>>{$vo}
                                	</label>
                                </volist>
                            </case>
                            <case value="editor">
                                <label class="textarea">
                                <textarea name="{$field.name}">{$data[$field['name']]}</textarea>
                                {:hook('adminArticleEdit', array('name'=>$field['name'],'value'=>$data[$field['name']]))}
                                </label>
                            </case>
                            <case value="picture">
                                <div class="controls">
									<input type="file" id="upload_picture_{$field.name}">
									<input type="hidden" name="{$field.name}" id="cover_id_{$field.name}" value="{$data[$field['name']]}"/>
									<div class="upload-img-box">
									<notempty name="data[$field['name']]">
										<div class="upload-pre-item"><img src="{$data[$field['name']]|get_cover='path'}"/></div>
									</notempty>
									</div>
								</div>
								<script type="text/javascript">
								//上传图片
							    /* 初始化上传插件 */
								$("#upload_picture_{$field.name}").uploadify({
							        "height"          : 30,
							        "swf"             : "__STATIC__/uploadify/uploadify.swf",
							        "fileObjName"     : "download",
							        "buttonText"      : "上传图片",
							        "uploader"        : "{:U('File/uploadPicture',array('session_id'=>session_id()))}",
							        "width"           : 120,
							        'removeTimeout'	  : 1,
							        'fileTypeExts'	  : '*.jpg; *.png; *.gif;',
                                    'onFallback' : function() {
                                        alert('未检测到兼容版本的Flash.');
                                    },
							        "onUploadSuccess" : uploadPicture{$field.name}
							    });
								function uploadPicture{$field.name}(file, data){
							    	var data = $.parseJSON(data);
							    	var src = '';
							        if(data.status){
							        	$("#cover_id_{$field.name}").val(data.id);
							        	src = data.url || '__ROOT__' + data.path;
							        	$("#cover_id_{$field.name}").parent().find('.upload-img-box').html(
							        		'<div class="upload-pre-item"><img src="' + src + '"/></div>'
							        	);
							        } else {
							        	updateAlert(data.info);
							        	setTimeout(function(){
							                $('#top-alert').find('button').click();
							                $(that).removeClass('disabled').prop('disabled',false);
							            },1500);
							        }
							    }
								</script>
                            </case>
                            <case value="file">
								<div class="controls">
									<input type="file" id="upload_file_{$field.name}">
									<input type="hidden" name="{$field.name}" value="{$data[$field['name']]}"/>
									<div class="upload-img-box">
										<present name="data[$field['name']]">
											<div class="upload-pre-file"><span class="upload_icon_all"></span>{$data[$field['name']]|get_table_field=###,'id','name','File'}</div>
										</present>
									</div>
								</div>
								<script type="text/javascript">
								//上传图片
							    /* 初始化上传插件 */
								$("#upload_file_{$field.name}").uploadify({
							        "height"          : 30,
							        "swf"             : "__STATIC__/uploadify/uploadify.swf",
							        "fileObjName"     : "download",
							        "buttonText"      : "上传附件",
							        "uploader"        : "{:U('File/upload',array('session_id'=>session_id()))}",
							        "width"           : 120,
							        'removeTimeout'	  : 1,
                                    'onFallback' : function() {
                                        alert('未检测到兼容版本的Flash.');
                                    },
							        "onUploadSuccess" : uploadFile{$field.name}
							    });
								function uploadFile{$field.name}(file, data){
									var data = $.parseJSON(data);
							        if(data.status){
							        	var name = "{$field.name}";
							        	$("input[name="+name+"]").val(data.data);
							        	$("input[name="+name+"]").parent().find('.upload-img-box').html(
							        		"<div class=\"upload-pre-file\"><span class=\"upload_icon_all\"></span>" + data.info + "</div>"
							        	);
							        } else {
							        	updateAlert(data.info);
							        	setTimeout(function(){
							                $('#top-alert').find('button').click();
							                $(that).removeClass('disabled').prop('disabled',false);
							            },1500);
							        }
							    }
								</script>
                            </case>
                            <default/>
                            <input type="text" class="text input-large" name="{$field.name}" value="{$data[$field['name']]}">
                        </switch>
                    </div>
                </div>
                </if>
            </volist>
            <input type="hidden" name="id" value="{$data.id|default=0}">
        </div>
        <div class="form-item cf">
            <button class="btn submit-btn ajax-post hidden" id="submit" type="submit" target-form="form-horizontal">确 定</button>
            <a class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</a>
        </div>
    </form>
    </div>
</div>
</block>

<block name="script">
<link href="__STATIC__/datetimepicker/css/datetimepicker.css" rel="stylesheet" type="text/css">
<php>if(C('COLOR_STYLE')=='blue_color') echo '<link href="__STATIC__/datetimepicker/css/datetimepicker_blue.css" rel="stylesheet" type="text/css">';</php>
<link href="__STATIC__/datetimepicker/css/dropdown.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="__STATIC__/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="__STATIC__/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
<script type="text/javascript">
$('#submit').click(function(){
    $('#form').submit();
});

$(function(){
	$('.time').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language:"zh-CN",
        minView:2,
        autoclose:true
    });
    showTab();
});
</script>
</block>
