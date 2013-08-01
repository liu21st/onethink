// +----------------------------------------------------------------------+
// | ThinkPHP                                                             |
// +----------------------------------------------------------------------+
// | Copyright (c) 2006 liu21st.com All rights reserved.                  |
// +----------------------------------------------------------------------+
// | Licensed under the Apache License, Version 2.0 (the 'License');      |
// | you may not use this file except in compliance with the License.     |
// | You may obtain a copy of the License at                              |
// | http://www.apache.org/licenses/LICENSE-2.0                           |
// | Unless required by applicable law or agreed to in writing, software  |
// | distributed under the License is distributed on an 'AS IS' BASIS,    |
// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      |
// | implied. See the License for the specific language governing         |
// | permissions and limitations under the License.                       |
// +----------------------------------------------------------------------+
// | Author: liu21st <liu21st@gmail.com>                                  |
// +----------------------------------------------------------------------+
// $Id$

// Ajax for ThinkPHP
document.write("<div id='ThinkAjaxResult' class='ThinkAjax' ></div>");
var m = {
    '\b': '\\b',
    '\t': '\\t',
    '\n': '\\n',
    '\f': '\\f',
    '\r': '\\r'
};
var ThinkAjax = {
    method:'POST',			        // 默认发送方
    bComplete:false,			    // 是否完成
    updateTip:'数据处理中...',	    // 后台处理中提示信息
    updateEffect:{'opacity': [0.1,0.85]},			// 更新效果
    image:['','',''],               // 依次是处理中 成功 和错误 显示的图
    tipTarget:'ThinkAjaxResult',	// 提示信息对象
    showTip:true,	                // 是否显示提示信息，默认开
    status:0,                       // 返回状态码
    info:'',	                    // 返回信息
    data:'',	                    // 返回数据
    type:'',                        // JSON EVAL XML ...
    intval:0,
    options:{},
    debug:false,
    activeRequestCount:0,
    tip:function (tips){
        this.options['tip']	=	tips;
        return this;
    },
    effect:function (effect){
        this.options['effect']	=	effect;
        return this;
    },
    target:function (taget){
        this.options['target']	=	target;
        return this;
    },
    response:function (response){
        this.options['response']	=	response;
        return this;
    },
    url:function (url){
        this.options['url']	=	url;
        return this;
    },
    params:function (vars){
        this.options['var']	=	vars;
        return this;
    },
    loading:function (target,tips,effect){
        if ($('#'+target))
        {
            //var arrayPageSize = getPageSize();
            var arrayPageScroll = getPageScroll();
            $('#'+target).css('display', 'block');
            $('#'+target).css('top', arrayPageScroll[1] + 'px');
            $('#'+target).css('right', '5px');

            // 显示正在更新
            if ($('#loader'))
            {
                $('#loader').css('display', 'none');
            }
            if ('' != this.image[0])
            {
                $('#'+target).html('<img src="'+this.image[0]+'" border="0" alt="loading..." align="absmiddle"> '+tips);
            }else{
                $('#'+target).html(tips);
            }
            //使用更新效果
            $('#'+target).fadeIn(1500);
            //var myEffect = $('#'+target).effects();
            //myEffect.custom(effect);
        }
    },
    ajaxResponse:function(request,target,response){
        // 获取ThinkPHP后台返回Ajax信息和数据
        // 此格式为ThinkPHP专用
        //alert(request.responseText);
        var _self = this;
        var str	=	request.responseText;
        str  = str.replace(/([\x00-\x1f\\"])/g, function (a, b) {
        var c = m[b];
        if (c) {
            return c;
        }else{
            return b;
        }
    }) ;
    try{
        $return =  eval('(' + str + ')');
        if (this.debug)
        {
            alert(str);
        }
    }catch(ex){
        if (this.debug)
        {
            alert("服务器返回非JS数据:\n\n"+str.substr(0,100));
        }
        if ($('#'+target) && this.showTip)
        {
            $('#'+target).html("服务器返回数据出错!");
            this.intval = window.setTimeout(function (){
            	$('#'+target).fadeOut(1500);
                //var myFx = new Fx.Style(target, 'opacity',{duration:1000}).custom(1,0);
                //$('#'+target).css('display', 'none');
            }, 3000);
        }
        return ;
    }
    /*
    if (this.debug)
    {
    // 调试模式下面输出eval前的字符串
    alert(str);
    }
    try{
    $return =  eval('(' + str + ')');
    }
    catch(e){alert('返回数据有错误');return;}
    */
    this.status = $return.status;
    this.info	 =	 $return.info;
    this.data = $return.data;
    this.type	=	$return.type;

    if (this.type == 'EVAL' )
    {
        // 直接执行返回的脚本
        eval($this.data);
    }else{
        // 处理返回数据
        // 需要在客户端定义ajaxReturn方
        if (response == undefined)
        {
            try	{(ajaxReturn).apply(this,[this.data,this.status,this.info,this.type]);}
            catch (e){}

        }else {
            try	{ (response).apply(this,[this.data,this.status,this.info,this.type]);}
            catch (e){}
        }
    }

    if ($('#'+target))
    {
        // 显示提示信息
        if (this.showTip && this.info!= undefined && this.info!=''){
            if (this.status==1)
            {
                if ('' != this.image[1])
                {
                    $('#'+target).html('<img src="'+this.image[1]+'" border="0" alt="success..." align="absmiddle"> <span style="color:blue;">'+this.info+'</span>');
                }else{
                    $('#'+target).html('<span style="color:blue;">'+this.info+'</span>');
                }

            }else{
                if ('' != this.image[2])
                {
                    $('#'+target).html('<img src="'+this.image[2]+'" border="0" alt="error..." align="absmiddle"> <span style="color:red;">'+this.info+'</span>');
                }else{
                    $('#'+target).html('<span style="color:red;">'+this.info+'</span>');
                }
            }
        }
        // 提示信息停留5秒
        if (this.showTip)
        this.intval = window.setTimeout(function (){
        	$('#'+target).fadeOut(1500);
            //var myFx = new Fx.Style(target, 'opacity',{duration:1000}).custom(1,0);
            //$('#'+target).css('display', 'none');
        }, 3000);
    }
},
// 发送Ajax请求
send:function(url,pars,response,target,tips,effect)
{
    url = (url == undefined)?this.options['url']:url;
    pars = (pars == undefined)?this.options['var']:pars;
    if (target == undefined)	{
        target = (this.options['target'])?this.options['target']:this.tipTarget;
    }
    if (effect == undefined)	{
        effect = (this.options['effect'])?this.options['effect']:this.updateEffect;
    }
    if (tips == undefined) {
        tips = (this.options['tip'])?this.options['tip']: this.updateTip;
    }
    if (this.showTip)
    {
        this.loading(target,tips,effect);
    }
    if (this.intval)
    {
        window.clearTimeout(this.intval);
    }
    this.activeRequestCount++;
    this.bComplete = false;
    try {
        var _self = this;
        if (this.method == "GET")
        {
            $.get(url, pars, function(data, textStatus, request) {
                _self.bComplete = true;
                _self.activeRequestCount--;
                _self.ajaxResponse(request, target, response);
            });
        }
        else
            {
                $.post(url, pars, function(data, textStatus, request) {
                    _self.bComplete = true;
                    _self.activeRequestCount--;
                    _self.ajaxResponse(request, target, response);
                });
            }
        }
        catch(z) { return false; }
    },
    // 发送表单Ajax操作，暂时不支持附
    sendForm:function(formId,url,response,target,tips,effect)
    {
        vars = $('#'+formId).serialize();
        this.send(url,vars,response,target,tips,effect);
    },
    // 绑定Ajax到HTML元素
    // event 支持根据浏览器的
    // 包括 focus blur mouseover mouseout mousedown mouseup submit click dblclick load change keypress keydown keyup
    bind:function(source,event,url,vars,response,target,tips,effect)
    {
        var _self = this;
        $('#'+source).bind(event,function (){_self.send(url,vars,response,target,tips,effect)});
    },
    // 页面加载完成后执行Ajax操
    load:function(url,vars,response,target,tips,effect)
    {
        var _self = this;
        window.addEvent('load',function (){_self.send(url,vars,response,target,tips,effect)});
    },
    // 延时执行Ajax操
    time:function(url,vars,time,response,target,tips,effect)
    {
        var _self = this;
        myTimer =  window.setTimeout(function (){_self.send(url,vars,response,target,tips,effect)},time);
    },
    // 定制执行Ajax操
    repeat:function(url,vars,intervals,response,target,tips,effect)
    {
        var _self = this;
        _self.send(url,vars,response,target,effect);
        myTimer = window.setInterval(function (){_self.send(url,vars,response,target,tips,effect)},intervals);
    },
    sendFile:function(id,url){
        var frame	=		this.createUploadIframe(id);
        var form		=		this.createUploadForm(id,url);
        if(form.encoding)
        {
            form.encoding = 'multipart/form-data';
        }
        else
            {
                form.enctype = 'multipart/form-data';
            }
            form.submit();
        },
        // 创建上传的IFrame
        createUploadIframe: function(id, uri)
        {
            //create frame
            var frameId = 'ThinkUploadFrame' + id;
            if(window.ActiveXObject) {
                var io = document.createElement('<iframe id="' + frameId + '" name="' + frameId + '" />');
                io.src = 'javascript:false';
            }else {
                var io = document.createElement('iframe');
                io.id = frameId;
                io.name = frameId;
            }
            io.style.position		= 'absolute';
            io.style.top			= '-1000px';
            io.style.left			= '-1000px';
            io.style.display		=	'none';
            document.body.appendChild(io);
            return io;
        },
        // 创建
        createUploadForm: function(id,url)
        {
            //create form
            var formId				=		'ThinkUploadForm' + id;
            var fileId					=		'ThinkUploadFile' + id;
            var form					=		document.createElement('form');
            form.method			=		'POST';
            form.url					=		url;
            form.name				=		formId;
            form.id					=		formId;
            form.enctype			=		"multipart/form-data";
            form.target				=		'ThinkUploadFrame' + id;
            //set attributes
            form.style.position		=		'absolute';
            form.style.top			=		'-1200px';
            form.style.left			=		'-1200px';
            form.style.display		=		'none';
            var fileElement			=		document.createElement('input');
            fileElement.type		=		'file';
            fileElement.
            form.appendChild(fileElement);
            document.body.appendChild(form);
            return form;
        }
    }
