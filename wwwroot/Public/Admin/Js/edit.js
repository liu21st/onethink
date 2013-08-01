$(function(){
	var __GET_CONTENT__ = '/Edit/Index/getContent';
	var __UPDATE__      = '/Edit/Index/update';
	var __IMAGE__       = '/Edit/Upload/editorUploadImage';
	var __ATTACH__      = '/Edit/Upload/editorUploadAttach';
	var isEditor = null, isAdd = null;
	//编辑段落
	$('.book-content')
		.hover(
			function() {$(this).addClass('book-focus')},
			function() {$(this).removeClass('book-focus')}
		)
		.live('dblclick', function(){
			var self = $(this);
			var id   = self.attr('id');
			
			if(isEditor){
				if(isEditor.attr('id') != id){
					isEditor.find('textarea').RemoveThinkEditor();
					isEditor.removeClass('book-edit').html(isEditor.data('html'));
				}else return;
			} 
			if(isAdd){
				isAdd.find('textarea').RemoveThinkEditor();
				isAdd.removeClass('book-add-content').addClass('book-add').html('双击添加段落');
			}

			isEditor = self;

			//缓存原HTML数据
			self.data('html', self.html());
			//获取UBB代码
			$.post(__GET_CONTENT__, {
				'id'   : id,
				'menu' : $('.book-title[menu]').attr('menu'),
				'book' : $('.book-logo a[book]').attr('book')
				}, function(data){
				if(data.status){
					var ubb = data.data
					//缓存UBB代码
					self.data('ubb', ubb);
					//清空容器内容
					var height = self.height(),width = self.width();
					self.html('').addClass('book-edit');
					//创建编辑框
					var editor = $('<textarea/>').val(ubb)
						.css({'width':width,'height':height > 600 ? 600 : height})
						.addClass('book-editor')
						.appendTo(self)
						.ThinkEditor({'image' : __IMAGE__, 'attach' : __ATTACH__});
				} else {
					alert(data.info);
				}
			}, 'json');
			return false;
		});
	
	//编辑框快捷键支持
	$('.book-editor').live('keydown', function(e){
		var _this = $(this);
		var self = _this.parents('.book-edit');
		e.stopPropagation();
		if(e.ctrlKey){
			switch(e.keyCode){
				case 13:
				case 83:
					$.post(__UPDATE__, {
						'id'      : self.attr('id')||0,
						'menu'    : $('.book-title[menu]').attr('menu'),
						'book'    : $('.book-logo a[book]').attr('book'),
						'content' : _this.val()}, function(data){
							if(data.status == 1){
								isEditor.find('textarea').RemoveThinkEditor();
								_this.remove();
								self.removeClass('book-edit').html(data.data);
								isEditor = null;
							} else if(data.status == 2){
								isEditor.find('textarea').RemoveThinkEditor();
								_this.remove();
								var html = '';
								$.each(data.data, function(i, v){
									html += '<div id="' + v.id + '" class="book-content">' + v.content + '</div>';
								})
								self.replaceWith(html);
								isEditor = null;
							} else if(data.status == 3){
								isAdd && isAdd.find('textarea').RemoveThinkEditor();
								isEditor && isEditor.find('textarea').RemoveThinkEditor();
								_this.remove();
								var html = '';
								$.each(data.data, function(i, v){
									html += '<div id="' + v.id + '" class="book-content">' + v.content + '</div>';
								});
								self.after('<div class="book-add book-edit">双击添加段落</div>');
								self.replaceWith(html);
								isEditor = isAdd = null;
							} else if(data.status == 4){
								isAdd && isAdd.find('textarea').RemoveThinkEditor();
								isEditor && isEditor.find('textarea').RemoveThinkEditor();
								_this.remove();
								self.prev().html(data.data);
								self.remove();
								isEditor = isAdd = null;
							} else if(data.status == 5){
								isAdd && isAdd.find('textarea').RemoveThinkEditor();
								isEditor && isEditor.find('textarea').RemoveThinkEditor();
								_this.remove();
								self.next().html(data.data);
								self.remove();
								isEditor = isAdd = null;
							}else {
								alert(data.info);
								return false;
							}
							//代码高亮
							$('pre.code').each(function(){
								var self = $(this).addClass('prettycode').removeClass('code');
								self.html(prettyPrintOne(self.html(), self.attr('lang'), true));
							});
					}, 'json');
					return false;
					break;
				case 66:
					_this.insertContent('[b]'+ _this.selectionRange() +'[/b]');
					return false;
					break;
				case 49:
					_this.insertContent('[h1]'+ _this.selectionRange() +'[/h1]');
					return false;
					break;
				case 50:
					_this.insertContent('[h2]'+ _this.selectionRange() +'[/h2]');
					return false;
					break;
				case 51:
					_this.insertContent('[h3]'+ _this.selectionRange() +'[/h3]');
					return false;
					break;
				case 52:
					_this.insertContent('[h4]'+ _this.selectionRange() +'[/h4]');
					return false;
					break;
				case 53:
					_this.insertContent('[h5]'+ _this.selectionRange() +'[/h5]');
					return false;
					break;
				case 54:
					_this.insertContent('[h6]'+ _this.selectionRange() +'[/h6]');
					return false;
					break;
			}
		}
		if(e.altKey){
			switch(e.keyCode){
				case 67:
					_this.insertContent('[code]'+ _this.selectionRange() +'[/code]');
					return false;
					break;
				case 76:
					_this.insertContent('[li]'+ _this.selectionRange() +'[/li]');
					return false;
					break;
				case 80:
					_this.insertContent('[p]'+ _this.selectionRange() +'[/p]');
					return false;
					break;
				case 85:
					_this.insertContent('[url]'+ _this.selectionRange() +'[/url]');
					return false;
					break;
			}
			
		}
	});
	
	//添加段落
	$('.book-add').live('dblclick', function(){
		if(isEditor){
			isEditor.find('textarea').RemoveThinkEditor();
			isEditor.removeClass('book-edit').html(isEditor.data('html'));
			isEditor = null;
		}
		if(isAdd) return;

		var self = $(this);
		isAdd = self;
		self.html('').removeClass('book-add').addClass('book-add-content');
		var editor = $('<textarea/>')
			.addClass('book-editor')
			.css({'height': 100, 'width':self.width()})
			.appendTo(self)
			.ThinkEditor({'image' : __IMAGE__, 'attach' : __ATTACH__});
	});
	
	//编辑目录
	$('.edit-menu-list .child')
		.hover(
			function(){$(this).addClass('hover')},
			function(){$(this).removeClass('hover')}
		);
	$('.edit-menu-list .save a').live('click', function(){
		var data = {}, list=$(this).parent().parent();
		data.id    = list.find('.save input').val();
		data.sort  = list.find('.sort input').val();
		data.name  = list.find('.name input').val();
		data.title = list.find('.title input').val();
		$.post(__SAVEMENU__, data, function(data){
			alert(data.info);
		}, 'json');
	});
	
	$('.ThinkBox').ThinkBox({'style':'bordernone'});
	
});

//jquery 文本域插件
(function($) {
    $.fn.extend({
        insertContent: function(myValue, t) {
            var $t = $(this)[0];
            if (document.selection) { //ie
                this.focus();
                var sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
                sel.moveStart('character', -l);
                var wee = sel.text.length;
                if (arguments.length == 2) {
                    var l = $t.value.length;
                    sel.moveEnd("character", wee + t);
                    t <= 0 ? sel.moveStart("character", wee - 2 * t - myValue.length) : sel.moveStart("character", wee - t - myValue.length);

                    sel.select();
                }
            } else if ($t.selectionStart || $t.selectionStart == '0') {
                var startPos = $t.selectionStart;
                var endPos = $t.selectionEnd;
                var scrollTop = $t.scrollTop;
                $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                this.focus();
                $t.selectionStart = startPos + myValue.length;
                $t.selectionEnd = startPos + myValue.length;
                $t.scrollTop = scrollTop;
                if (arguments.length == 2) {
                    $t.setSelectionRange(startPos - t, $t.selectionEnd + t);
                    this.focus();
                }
            }
            else {
                this.value += myValue;
                this.focus();
            }
        },
        selectionRange : function(start, end) {
        	var str = "";
        	var thisSrc = this[0];
        	if(start === undefined) {
          	//获取当前选中文字内容，接受各种元素的选中文字
          		if(/input|textarea/i.test(thisSrc.tagName) && /firefox/i.test(navigator.userAgent))
            		//文本框情况在Firefox下的特殊情况
            		str = thisSrc.value.substring(thisSrc.selectionStart, thisSrc.selectionEnd);
          		else if(document.selection)
            		//非文本框情况
            		str = document.selection.createRange().text;
          		else
            		str = document.getSelection().toString();
        		} else {
          			//设置文本输入控件的光标位置
          			if(!/input|textarea/.test(thisSrc.tagName.toLowerCase()))
            			//非文本输入控件，无效
            			return false;

          			//假如不传第二个参数则默认将end设为start
          			(end === undefined) && (end = start);

    	      		//控制光标位置
    		      	if(thisSrc.setSelectionRange) {
    		        	thisSrc.setSelectionRange(start, end);
    		        	this.focus();
    		      	} else {
    		        	var range = thisSrc.createTextRange();
    		        	range.move('character', start);
    		        	range.moveEnd('character', end - start);
    		        	range.select();
    		      	}
        		}
    	    if(start === undefined)
    	      return str;
    	    else
    	      return this;
      	},
      	autoTextarea : function(options) {
    		var defaults={
    			maxHeight:null,//文本框是否自动撑高，默认：null，不自动撑高；如果自动撑高必须输入数值，该值作为文本框自动撑高的最大高度
    			minHeight:$(this).height() //默认最小高度，也就是文本框最初的高度，当内容高度小于这个高度的时候，文本以这个高度显示
    		};
    		var opts = $.extend({},defaults,options);
    		return $(this).each(function() { 
    			$(this).bind("paste cut keydown focus blur",function(){
    				var height,style = this.style;
    				this.style.height =  opts.minHeight + 'px';
    				if (this.scrollHeight > opts.minHeight) {
    					if (opts.maxHeight && this.scrollHeight > opts.maxHeight) {
    						height = opts.maxHeight;
    						style.overflowY = 'scroll';
    					} else {
    						height = this.scrollHeight;
    						style.overflowY = 'hidden';
    					}
    					style.height = height  + 'px';
    				}
    			});
				$(this).focus();
    		});
    	}
    })
})(jQuery);