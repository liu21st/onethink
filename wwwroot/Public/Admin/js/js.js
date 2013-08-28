function prev(step){
	var current = $('.stepstat li.current');
	current.prev().removeClass('unactivated').addClass('current');
	current.removeClass('current').addClass('unactivated');
	$('.header #title'+step).addClass('hidden').prev().removeClass('hidden');
	$('#content'+step).addClass('hidden').prev().removeClass('hidden');
	$('#stepstatbg').attr('class','stepstatbg stepstat'+(step-1));
}

function next(step){
	if(step ==4){
		$('form#create').submit();
		return ;
	}
	var current = $('.stepstat li.current');
	current.next().removeClass('unactivated').addClass('current');
	current.removeClass('current').addClass('unactivated');
	$('.header #title'+step).addClass('hidden').next().removeClass('hidden');
	$('#content'+step).addClass('hidden').next().removeClass('hidden');
	$('#stepstatbg').attr('class','stepstatbg stepstat'+(step+1));
	if(step == 3){
		preview();
	}
}

function bindSelected(data){
	$.each( data, function(i, field){
		if(field.name.indexOf('hooks[') != -1){
			if($('select[name="'+field.name+'"]').length){
				$('select[name="'+field.name+'"]').val(field.value);
			}
		}
	});
}

//验证表单
function checkForm(){
	var form = $('form#create');
	var url = checkUrl;
	$.post(url,form.serialize(),function(data){
		if(data.status){
			$('#content1 label').removeClass('error');
			$('#content1 :text').parent().find('span').remove();
			next(1);
		}else{
			$('#content1 label').removeClass('error');
			$('#content1 :text').parent().find('span').remove();
			$.each(data.data,function(i,v){
				$('#content1 label[for="'+i+'"]').addClass('error');
				$('#content1 :text[name="'+i+'"]').parent().append('<span class="error">'+v+'</span');
			})
		}
	},'json');
}

//预览
function preview(){
	var form = $('form#create');
	var data = form.serializeArray();
	var url = previewUrl;
	$.post(url,form.serialize(),function(data){
		// editor.setOption("theme", 'monokai');
		editor.setValue(data);
	});
	bindSelected(data);
	return data;
}

//动态添加元素，并绑定删除事件
function add_form_btns(name){
	$(name).click(function(){
		var html = $(this).parent().parent();
		var _class = html.attr('class');
		var clone = html.clone();
		//表单清空
		clone.find('input[type="text"]').each(function(){
			$(this).removeAttr('value');
		});
		clone.find('a.add').replaceWith('<a class="del">删除</>');
		var clone_html = clone.html().replace(/\[0\]/g,'['+form_hooks_count+']');
		clone_html += '<td><a class="ico-top" href="javascript:;"></a>';
		clone_html += '<a class="ico-btm" href="javascript:;"></a></td>';
		clone = $('<tr class="group moveable">'+clone_html+'</tr>');
		clone.find('select[name^="hooks["]').val('');
		clone.find('a.del').click(function(){
			$(this).parent().parent().detach();
			preview();
		});
		clone.find('a[class^="ico"]').click(function(){
			var _current_element = $(this).parents('tr');
			if($(this).attr('class') == 'ico-top'){
				if(_current_element.prev('.moveable').length >0){
					_current_element.insertBefore(_current_element.prev('.moveable'));
				}else{
					ui.error('已经是最前面了')
				}
			}else{
				if(_current_element.next('.moveable').length >0){
					_current_element.insertAfter(_current_element.next('.moveable'));
				}else{
					ui.error('已经是最后面了')
				}
			}
		});
		$('tr.'+_class).last().after(clone);
		form_hooks_count++;
	});
}

//动态添加插件方法
function add_form_btns2(name){
	$(name).click(function(){
		var html = $(this).parent().parent();
		var _class = html.attr('class');
		var clone = html.clone();
		//表单清空
		clone.find('input[type="text"]').each(function(){
			$(this).removeAttr('value');
		});
		clone.find('textarea').text('');
		clone.find('a.add').replaceWith('<a class="del">删除</>');
		var clone_html = clone.html().replace(/\[0\]/g,'['+form_hooks_methods_count+']');
		clone_html += '<td><a class="ico-top" href="javascript:;"></a>';
		clone_html += '<a class="ico-btm" href="javascript:;"></a></td>';
		clone = $('<tr class="group2 moveable">'+clone_html+'</tr>');
		clone.find('a.del').click(function(){
			$(this).parent().parent().detach();
			var data= preview();
			generateOptions('table tr.group select.method','table tr.group2 .hook_method[value!=""]');
			bindSelected(data);
		});
		clone.find('a[class^="ico"]').click(function(){
			var _current_element = $(this).parents('tr');
			if($(this).attr('class') == 'ico-top'){
				if(_current_element.prev('.moveable').length >0){
					_current_element.insertBefore(_current_element.prev('.moveable'));
				}else{
					ui.error('已经是最前面了')
				}
			}else{
				if(_current_element.next('.moveable').length >0){
					_current_element.insertAfter(_current_element.next('.moveable'));
				}else{
					ui.error('已经是最后面了')
				}
			}
			generateOptions('table tr.group select.method','table tr.group2 .hook_method[value!=""]');
			bindSelected(data);
		});
		$('tr.'+_class).last().after(clone);
		form_hooks_methods_count++;
	});
}

//修改时的删除元素
function del_form_btns(name){
	$(name).click(function(){
		$(this).parent().parent().detach();
		generateOptions('table tr.group select.method','table tr.group2 .hook_method[value!=""]');
	});
}

function generateOptions(name,source){
	var html = '<option value="">请选择</option>';
	$(source).each(function(i,v){
		html+= '<option value="'+v.value+'">'+v.value+'</option>';
	});
	$(name).html(html);
}

function pop(e){
	tr = $(e).parents('tr');
	temp_textarea = tr.find(':text[name$="[name]"]');
	name = temp_textarea.val();
	temp_textarea = tr.find('textarea[name$="[content]"]');
	$.thinkbox.load(windowUrl,{title:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;function '+ name +' ($param)'});
}

function pop2(e){
	td = $(e).parent();
	temp_textarea = td.find('textarea');
	name = temp_textarea.attr('name');
	$.thinkbox.load(windowUrl,{title:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;function '+ name +' ($param)'});
}

function save(e,item){
	temp_textarea.val(e.getValue());
	cancel(item);
	preview();
}

function cancel(e){
	$.thinkbox.get(e).hide();
}