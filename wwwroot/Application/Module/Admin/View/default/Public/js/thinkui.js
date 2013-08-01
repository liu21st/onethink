// +----------------------------------------------------------------------
// | 后台管理专用库
// +----------------------------------------------------------------------
// | Copyright (c) Topthink.com All Rights Reserved.
// +----------------------------------------------------------------------
// | Author: topthink zzguo28 <zzguo28@topthink.com>
// +----------------------------------------------------------------------
// $Id$

(function($){

    var URL,url,app,APP,
        selectRowIndex = [];

    URL = url = consts('url');
    APP = app = consts('app');

/* --------------- 模板应用函数 注意添加时要在大括号后加上分号;------------------- */
    // 新增
    $.add = function(id)
    {
        location.href = id ? URL+"/add/id/"+id : URL+"/add/";
    };

    // 编辑
    $.edit = function(id)
    {
        var keyValue;
        if (id)
        {
            keyValue = id;
        }
        else
        {
            keyValue = getSelectCheckboxValue();
        }
        if (!keyValue)
        {
            alert('请选择编辑项！');
            return false;
        }
        location.href =  URL+"/edit/id/"+keyValue;
    };

    // 排序
    $.sort = function()
    {
        location.href = URL+"/sort/sortId/"+getSelectCheckboxValues();
    };

    // 根据某字段排序浏览
    $.sortBy = function(field,sort)
    {
	    location.href = "?_order="+field+"&_sort="+sort;
    };

    // 删除
    $.del = function(id)
    {
        var keyValue;
        if(id)
        {
            keyValue = id;
        }
        else
        {
           keyValue = getSelectCheckboxValues();
        }

        if (!keyValue)
        {
            alert('请选择删除项！');
            return false;
        }

        if (window.confirm('确实要停用选择项吗？'))
        {
            location.href =  URL+"/delete/id/"+keyValue;
        }
    };

    // 彻底删除
    $.foreverdelete = function(id)
    {
        var keyValue;
        if(id)
        {
            keyValue = id;
        }
        else
        {
           keyValue = getSelectCheckboxValues();
        }

        if (!keyValue)
        {
            alert('请选择删除项！');
            return false;
        }

        if (window.confirm('确实要永久删除选择项吗？'))
        {
            location.href =  URL+"/foreverdelete/id/"+keyValue;
        }
    };

    // 状态禁用
    $.forbid = function(id)
    {
        location.href = URL+"/forbid/id/"+id;
    };

    // 状态恢复
    $.resume = function(id)
    {
	location.href = URL+"/resume/id/"+id;
    };

    // 授权
    $.app = function(id)
    {
        location.href = URL+"/app/groupId/"+id;
    }

    // 用户列表
    $.user = function(id)
    {
        location.href = URL+"/user/id/"+id;
    };

    //子类
    $.child = function(id)
    {
        location.href = URL+"/index/pid/"+id;
    };

    //移动节点
    $.move = function (id)
    {
        var keyValue;
        keyValue = getSelectCheckboxValues();
        location.href =  URL+"/moveNode/id/"+keyValue;
    }

    //拷贝节点
    $.copy = function (id)
    {
        var keyValue= getSelectCheckboxValues();
        location.href =  URL+"/copyNode/id/"+keyValue;
    };

    //访问网站
    $.view = function (url)
    {
        window.open(url);
    };

    //刷新菜单
    $.flesh = function ()
    {
         location.href =URL+'/fleshMenu';
    };

    //缓存
    $.cache = function ()
    {
       var keyValue= getSelectCheckboxValues();
	   window.location.href = URL+'/cache/id/'+keyValue;
    };

/* -------------------------------功能函数--------------------------------------------------- */

    // 全选反选
    $.select_all = function(_this,_name)
    {
        $('input[name="'+_name+'"]').attr('checked',$(_this).attr('checked'));
    };


    function getSelectCheckboxValue(key)
    {
        key = key || 'ids';
        var obj = document.getElementsByName(key);

        for (var i=0;i<obj.length;i++)
        {
            if (obj[i].checked==true)
            {
                return obj[i].value;
            }
        }
        return false;

    }

    function getSelectCheckboxValues(key)
    {
        key = key || 'ids';
        var obj = document.getElementsByName(key);
        var result ='';
        var j= 0;
        for (var i=0;i<obj.length;i++)
        {
            if (obj[i].checked==true){
                selectRowIndex[j] = i+1;
                result += obj[i].value+",";
                j++;
            }
        }
        return result.substring(0, result.length-1);
    }

    /**
     +----------------------------------------------------------
     * 获取选择项 jquery版
     +----------------------------------------------------------
    */
    $.get_select_val = function(_name)
    {
        _name = _name || 'ids';
        var arr = [];
        $('input[name="'+_name+'"]').each(function() {
            this.checked && arr.push(this.value);
        });
        return arr.join(',');
    };

    /**
     +----------------------------------------------------------
     * 插件开发 输出调试
     +----------------------------------------------------------
    */
    $.dump = function(msg)
    {
        if(typeof msg === 'string')
        {
            msg = msg.indexOf('|') != -1 && '<p>'+ msg.split('|').join('</p><p>') ||'<p>' + msg ;
        }

        if(typeof msg === 'object')
        {
            var msgs = [];
            for(var p in msg)
            {
                var _type  = typeof msg[p],
                    _val   = msg[p];
                if('string' == _type)
                {
                    _val = '"'+ _val + '"';
                }
                msgs.push(p + ':&nbsp;' + _val + ' ('+ _type + ')');
            }
            msg = '<p>' + msgs.join('</p><p>');
        }

        if ( !$('#debug_dump').length )
        {
            $('body').prepend('<div id="debug_dump"></div>');
        }

        $('#debug_dump')
            .css({
                position        : 'absolute',
                backgroundColor : '#000',
                opacity         :  '0.65',
                top             :  '0',
                left            :  '0',
                width           :  $('body').width(),
                lineHeight      :  '28px',
                zIndex          :  '99999',
                textIndent      :  '20px',
                color           :  '#fff'
            })
            .html('输出结果是： '+ msg +'</p>')
            .click(function(){
                $(this).mouseout(function(){
                     $('#debug_dump').fadeOut('slow').empty().remove();
                });
            });
    };

    /**
     +----------------------------------------------------------
     * 搜索 焦点控制 增强用户体验
     +----------------------------------------------------------
    */
    $.fn.focus_ctrl = function(onIdName)
    {
          return this.each(function() {
                var defaults = this.value;
                $(this).focus(function(){
                    if(this.value == defaults)
                    {
                        this.value = '';
                    }
                    if(onIdName)
                    {
                        $(onIdName).addClass('btn_on');
                    }
                }).blur(function(){
                    if(this.value == '')
                    {
                        this.value = defaults;
                    }
                    else
                    {
                        defaults = this.value;
                    }
                    if(onIdName)
                    {
                        $(onIdName).removeClass('btn_on');
                    }
                });
          });
    };


    /**
     +----------------------------------------------------------
     * 页面滚动 增强用户体验
     +----------------------------------------------------------
    */
    $.scroll_to = function(_to,diff)
    {
        _to = _to && typeof _to == 'string' && ( $('#'+_to).offset().top - (diff || 15) ) || _to || 0;
        $('html,body').animate({scrollTop : _to},750);
    };

    /**
     +----------------------------------------------------------
     * 全选反选
     +----------------------------------------------------------
    */
    // 全选反选
    $.select_all = function(_this,_name)
    {
        $('input[name="'+_name+'"]').attr('checked',$(_this).attr('checked'));
    };

    // 搜索 普通与高级切换
    $.showHideSearch = function(_this)
    {
       var $adv  = $('#think_advsearch'),
           $ctrl = $(_this).children('em');

       if($adv.is(':visible'))
       {
            $ctrl.html('高级搜索');
            $('#think_search').show();
            $adv.hide();
       }
       else
       {
            $ctrl.html('取消高级');
            $('#think_search').hide();
            $adv.show();
       }
    };

    $.fn.bindFocus = function()
    {
        return this.each(function() {
            $(this).focus(function(){$(this).addClass('onfocus')}).blur(function(){$(this).removeClass('onfocus')});
        });
    };

    // 控制区域相关事件
    function think_ctrl_init()
    {
        $('#think_search,#adv_content').focus_ctrl('#search_btm_on');
        $('.text,.textarea').bindFocus();
    }

    // 分页区域相关事件
    function think_page_init()
    {
        $('#think_page_text').focus_ctrl();
    }

    // 弹出层相关处理
    function think_box_init()
    {
        $('.tbox').thinkbox();
    }


    // 初始化
    function ThinkUiInit()
    {
        think_ctrl_init();// 控制区域
        think_page_init();// 分页区域
        think_box_init();
    }

    $(ThinkUiInit);
// 闭包end
})(jQuery);

/**
 +----------------------------------------------------------
 * Thinkbox
 +----------------------------------------------------------
*/
(function($){

    var box = {},
        opt = {};

    $.thinkbox = function(settings)
    {

    };

    $.thinkbox.version = '1.0.0 (2010-11-28)';

    // 默认设置 可使用url方式或传参方式
    // url?thinkbox=true&参数1=设置值&参数2=设置值
    $.thinkbox.defaults = {
        resize      : 1,          // 标题栏是否允许使用大小切换按钮
        close       : 1,          // 标题栏是否允许使用关闭按钮
        forbid      : 0,          // 是否禁止使用点击背景层进行关闭和键盘上esc关闭
        drag        : 1,          // 是否允许拖动
        width       : 500,        // 黙认的内容宽度
		height      : 350,        // 黙认的内容高度
        opacity     : 35,         // 背景透明程度
        bgtime      : 500,        // 背景动画时间
        bgcolor     : '000',      // 遮罩层背景颜色
        background  : 'f0f0f0',   // 内容层的16进制背景颜色
        overflow    : 'auto',     // 内容层的overflow值
        title       : '',         // 支持直接设置标题
        type        : '',         // 弹出层类型
        /* 以下参数如果要指定，只能在调用参数中设置 */
        closeid     : '.close',// 默认自动绑定关闭的ID或 CLASS名称
        openid      : '.tbox',// 默认自动绑定使用thinkbox的元素
        resizeid    : '.resize',
        content     : '',         // 直接指定显示内容
        ajax        : {},         // ajax方式中要额外传入的变量
        _this       : null,       // 要操作的对象
        url         : null        // 支持直接指定url
    };


    // 绑定执行
    $.fn.thinkbox = function(settings)
    {
        return this.each(function()
        {
            var settings = settings || {},
                events = 'click.thinkbox',
                $this  = $(this);

            if($.nodeName(this,'form'))
            {
                events = 'submit.thinkbox';

                if($this.data('thinkboxpass'))
                {
                    return true;
                }

                if('multipart/form-data' == this.enctype)
                {
                    settings._this = this;
                    box_play( settings );
                    return true;
                }
            }

            $this.bind(events,function(e)
            {
                e.preventDefault();// 取消默认事件
                settings._this = this;
                box_play( settings );
                this.blur();
                return false;
            });
        });
    };

    // 弹出层处理进程
    function box_play(settings)
    {
        // 构建弹出层dom
        if(!box.outer)
        {
            box_create();
        }

        // 确认框处理
        if(!settings._this)
        {
            return show_dialog(settings);
        }

        var _this = settings._this,
            $this = $(_this);

        opt = $this.data('config');

        if(!opt)
        {
            opt = set_config(_this, settings);
        }

        box_init();

        switch(opt.type)
        {
            case 'div':// 同一页面内的div
                    box.temp.html(opt.content);
                    var $div = $(opt.selector);
                    if($div.length)
                    {
                        box.temp.append($div.contents().clone());
                    }
                    if(box.temp.html())
                    {
                        box_show();
                    }
                    else
                    {
                        show_error();
                    }
                    break;
            case 'ajax':// 指定某个页面或页面中某个DIV
                    var data = opt.ajax.data || {};
                    if(opt.selector)
                    {
                        if(typeof data == 'string')
                        {
                            data += '&tboxidname='+opt.selector.substring(1);
                        }
                        else
                        {
                            data['tboxidname'] = opt.selector.substring(1);
                        }
                    }
                    $.ajax($.extend(true, opt.ajax, {
                        url: opt.url,
                        success: show_ajax,
                        error:show_error,
                        data: data
                    }));
                    break;
            case 'form':// form类型，并且允许指定提交到某个div
                    var data = $this.serializeArray();
                    data.push({name: 'thinkboxform', value: 1});
                    if (opt.selector)
                    {
                        data.push({name: 'idname', value: opt.selector.substring(1)});
                    }
                    $.ajax($.extend({}, opt.ajax, {
                            url     : opt.url,
                            data    : data,
                            type    : $this.attr('method') || 'get',
                            success : show_ajax,
                            error   : show_error
                        }));
                    break;
            default:
                //
        }

    }

    // 弹出层显示
    function box_show()
    {
        box.content.empty().html(box.temp.contents());
        box.temp.empty();
        box.title.html(opt.title);
        if(box.scripts.length)
        {
            box.content.append(box.scripts);
            box.scripts = [];
        }
        set_box_event();
        box.loading.hide();
        box.wrap.show();
        box.is_show = 1;
        $(opt.closeid).bind('click.thinkbox', box_remove);
        $(opt.resizeid).bind('click.thinkbox', box_resize);
        $(opt.openid).thinkbox(set_new_opt());
    }

    // 设置新的弹出层
    function set_new_opt()
    {
		var newOpt  = $.extend(true, {}, opt);
        newOpt.url  = '';
        newOpt.type = '';
        newOpt.ajax = '';
		return newOpt;
    }
    // 弹出层相关事件绑定
    function set_box_event()
    {
        if(!opt.forbid)
        {
            box.overbg.click(box_remove);
            $(document).bind('keydown.thinkbox',box_key);
        }

        if(opt.close)
        {
            box.close.bind('click.thinkbox',box_remove);
        }
        else
        {
            box.close.unbind('click.thinkbox').addClass('tbox_closegray').attr({title:'关闭-当前不可用',allow:'false'});
        }

        if(opt.drag)
        {
            // 对title进行绑定
            box.title.bind('mousedown.thinkbox', function(e)
            {
                if(box.wrap.attr('state') == 'normal')
                {
                    var e = e || window.event;
                    box.lastMouseX = e.clientX;
                    box.lastMouseY = e.clientY;

                    // 鼠标移动事件
                    $(document).bind('mousemove.thinkbox', function(e)
                    {
                        box_drag(e);
                    });

                    $(document).bind('mouseup.thinkbox', function(e)
                    {
                        $(document).unbind('mousemove.thinkbox');
                        $(document).unbind('mouseup.thinkbox');
                    });
                }
            });
        }
        else
        {
            box.title.css('cursor','default');
        }

        // 最大化 最小化
        if(opt.resize)
        {
            box.resize.bind('click.thinkbox',box_resize);
            box.top.bind('dblclick.thinkbox',box_resize);
        }
        else
        {
            box.resize.addClass('tbox_fullgray').attr({title:'最大化-当前不可用',allow:'false'});
        }
        $('.text,.textarea').bindFocus();
    }

    // 大小切换
    function box_resize()
    {
        box.wrap.hide();
        box.loading.show();
        opt.isMax = !opt.isMax;
        set_box_style();
        if(opt.isMax)
        {
            box.resize.addClass('tbox_tosmall').attr('title','最小化');
            box.content.addClass('tbox_max');
        }
        else
        {
            box.resize.removeClass('tbox_tosmall').attr('title','最大化');
            box.content.removeClass('tbox_max');
        }
        box.loading.hide();
        box.wrap.show();
    }

    // 键盘事件，只支持esc
    function box_key(e)
    {
		var e = e || window.event;
        if(e.keyCode == 27 && !opt.forbid)
        {
		    box_remove(e);
		}
	}

    // 移除元素
    function box_remove(e)
    {
        if(e)
        {
            e.preventDefault();
        }
        box.loading.hide();
        box.wrap.hide();
        box.overbg.fadeOut(300);
        box.outer.hide();
        box.content.empty();

        box.is_start = 0;
        box.is_show  = 0;

        if(opt.after_remove && $.isFunction(opt.after_remove))
        {
            opt.after_remove(box, opt);
        }

        $(document).unbind('keydown.thinkbox');

        if(e)
        {
            return false;
        }
    }

    // ajax处理
    function show_ajax(data)
    {
        var tpl = opt.selector ? filter_script($('<div>'+data+'</div>').find(opt.selector).contents()):filter_script(data);
        box.temp.html(tpl);
        if(box.temp.html())
        {
            box_show();
        }
        else
        {
            show_error();
        }
    }

    // 过滤不必要的html元素
    function filter_script(data)
    {
        box.scripts     = [];
        if(typeof data == 'string')
        {
            data = data.replace(/<\/?(\!DOCTYPE|html|head|meta|body)[^><]*>/gi, '');
        }
        var temp = [];
        $.each($.clean({0:data}, this.ownerDocument), function()
        {
            if($.nodeName(this, 'script'))
            {
			    if(!this.src || $(this).attr('addons'))// 是否强制加载
                {
                    box.scripts.push(this);
                }
			}
            else
            {
                temp.push(this);
            }
		});
		return temp;
	}

    // 出错提示 未处理
    function show_error()
    {
        alert ('出错');
    }

    // 初始化弹出层
    function box_init()
    {
        box.outer.show();
        box.overbg.css({backgroundColor:'#'+opt.bgcolor,opacity:0}).fadeTo(opt.bgtime, opt.opacity/100);
        box.is_start = true;
        if(!box.is_show)
        {
            box.loading.show();
        }
        box.lastMouseX = 0;
        box.lastMouseY = 0;
        set_box_style();
    }

    // 弹出层设置
    function set_box_style()
    {
        var boxH = opt.height,
            boxW = opt.width;

        opt.winW = box.win.width();
        opt.winH = box.win.height();
        opt.maxW = opt.winW - 45;
        opt.maxH = opt.winH - 67;

        // 修正弹出层大小
        if( boxH > opt.maxH || boxW > opt.maxW )
        {
            boxH = Math.min(boxH, opt.maxH);
            boxW = Math.min(boxW, opt.maxW);
        }

        if(opt.isMax)
        {
            boxH = opt.maxH;
            boxW = opt.maxW;
        }

        box.wrap.css({
            width:boxW+45,
            height:boxH+67,
            top: (opt.maxH - boxH)/2,
            left:(opt.maxW - boxW)/2
        }).attr('state','normal');

        box.content.css({
            width:boxW,
            height:boxH,
            backgroundColor:'#'+opt.background,
            overflow:opt.overflow
        });

        box.top.width(boxW);
        box.left.height(boxH);
        box.right.height(boxH);
        box.btm.width(boxW);
    }

    // 移动处理
    function box_move(x, y)
    {
        box.wrap.attr({
            lastX   :   parseInt(x),
            lastY   :   parseInt(y)
        });
        x = x+'px';
		y = y+'px';

        box.wrap.css({left:x,top:y});
    }

    // 拖动处理
    function box_drag(e)
    {
        var e = e || window.event;
        var newX = parseInt(box.wrap.css('left')) + (e.clientX  - box.lastMouseX);
        var newY = parseInt(box.wrap.css('top')) + (e.clientY   - box.lastMouseY);
        box.lastMouseX = e.clientX;
        box.lastMouseY = e.clientY;
        box_move(newX, newY);
    }

    // 获取配置设定
    function set_config(_this, conf)
    {
        var $this = $(_this);

        // 对当前对象进行分析
        conf.url     = conf.url    || $this.attr('href')  || $this.attr('action') || null;
        conf.title   = conf.title  || $this.attr('title') || $this.attr('alt') || null;
        conf.forbid  = conf.forbid || $this.attr('rev') == 'forbid';
        conf.iframe  = conf.iframe || _this.target.toLowerCase() == '_blank';

        // 整合url中thinkbox相关参数
        if(conf.url.indexOf('?') != -1 && conf.url.indexOf('thinkbox=1') != -1)
        {
            conf = $.extend(true,{}, conf, get_url_params(conf.url));
        }

        // 设置selector
        conf.selector = '';
        if(conf.url !== '#' && conf.url.indexOf('#') != -1)
        {
            var win_href      = window.location.href,
                win_hash      = get_hash(win_href),
                win_true_href = win_href.substring(0, win_href.length - win_hash.length),
                url_hash      = get_hash(conf.url),
                true_url_href = conf.url.substring(0, conf.url.length  - url_hash.length );

            conf.url = !(true_url_href == win_true_href) && true_url_href || null;
            conf.selector = url_hash;
        }

        // 判断弹出层类型
        conf.type = conf.type ||
                    $.nodeName(_this,'form') && ( (conf.iframe && 'iframeform') || ((_this.enctype == 'multipart/form-data') && 'uploadform') || 'form' ) ||
                    conf.iframe && 'iframe' ||
                    conf.url && 'ajax' ||
                    conf.selector && 'div' || conf.content && 'tpl' || null;

        // 整合默认值
        var format = ['resize','close','forbid','drag', 'width','height', 'padding', 'opacity', 'bgtime'];
        for(var i in format)
        {
            if(conf[format[i]])
            {
                conf[format[i]] = parseInt(conf[format[i]]);
            }
        }
        opt = $.extend(true, {}, $.thinkbox.defaults, conf);
        $this.data('config',opt);
        return opt;
    }

    // 获取URL参数设置
    function get_url_params(url)
    {
        var query   = url.split('?'),
            nowUrl  = query[0] ,
            pairs   = query[1].split(/[;&]/);

        // 获取参数
        if(pairs[0] != 'thinkbox=1')
        {
            pairs  = query[1].split('&thinkbox=1&');
            nowUrl = nowUrl + '?' + pairs[0];
            pairs  = pairs[1].split(/[;&]/);
        }
        else
        {
            pairs.shift();
        }

        // 遍历获取url参数
        var params = {},arr;
        for( var i=0; i<pairs.length; i++ )
        {
            arr = pairs[i].split('=');
            if(arr[1])
            {
                params[ arr[0] ] = arr[1];
            }
        }
        // 将url进行还原
        params.url = nowUrl;
        return params;
    }

    // 获取描点
    function get_hash(_url)
    {
		if(typeof _url == 'string')
        {
			var hashPos = _url.indexOf('#');
			if(hashPos > -1)
            {
                return _url.substring(hashPos);
            }
		}
		return '';
    }

    // 信息弹出框 确认框
    function show_dialog(settings)
    {
        alert (settings);
    }

    // 构建弹出层
    function box_create()
    {
        if(!$('#thinkbox').length)
        {
            $('<div class="thinkbox" id="thinkbox"><div class="tbox_overbg"></div><div class="tbox_wrap"><div class="tbox_top"><div class="tbox_top_left"></div><div class="tbox_top_content"><h2>添加数据</h2><div><a href="javascript:void(0);" title="最大化" class="tbox_tofull" id="tbox_resize">最大化</a><a href="javascript:void(0);" title="关闭" class="tbox_close">关闭</a></div></div><div class="tbox_top_right"></div></div><div class="tbox_body"><div class="tbox_body_left"></div><div class="tbox_body_content"></div><div class="tbox_body_right"></div></div><div class="tbox_btm"><div class="tbox_btm_left"></div><div class="tbox_btm_content"></div><div class="tbox_btm_right"></div></div></div><div class="tbox_loading"></div><div class="tbox_temp"></div></div>').appendTo('body');
        }
        box.win     = $(window);
        box.outer   = $('#thinkbox');
        box.overbg  = $('.tbox_overbg', box.outer);
        box.wrap    = $('.tbox_wrap', box.outer);
        box.top     = $('.tbox_top_content',box.outer);
        box.title   = $('h2', box.top);
        box.resize  = $('#tbox_resize');
        box.close   = $('.tbox_close', box.top);
        box.left    = $('.tbox_body_left', box.outer);
        box.right   = $('.tbox_body_right', box.outer);
        box.content = $('.tbox_body_content', box.outer);
        box.btm     = $('.tbox_btm_content', box.outer);
        box.temp    = $('.tbox_temp', box.outer);
        box.loading = $('.tbox_loading', box.outer);
    }

})(jQuery);