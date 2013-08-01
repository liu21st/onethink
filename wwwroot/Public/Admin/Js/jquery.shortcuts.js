//jquery 文本域插件
(function($) {
    $.fn.extend({
		shortcuts : function(){
			this.keydown(function(e){
				var _this = $(this);
				e.stopPropagation();
				if(e.ctrlKey){
					switch(e.keyCode){
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
				}else if(e.altKey){
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
		},
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
      	}
    })
})(jQuery);