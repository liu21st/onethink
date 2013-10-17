+function($){

/**
 * 定义基础内部全局变量
 */
var
    /**
     * ThinkEditor 对象
     * @type {Object}
     */
    ThinkEditor,

    /**
     * 封装过的textarea Range对象
     * @type {Object}
     */
    Range,

    /**
     * ThinkEditor插件对象，所有的操作都是通过该对象实现
     * @type {Object}
     */
    Plugin = {},

    /**
     * ThinkEditor语言包对象
     */
    Language = {},

    /**
     * 键盘按键对应数值表
     * @type {Object}
     */
    KeyCode = {
        "BACKSPACE" : 8,
        "TAB"       : 9,
        "ENTER"     : 13,
        "ESC"       : 27,
        "SPACE"     : 32,
        "F1"        : 112,
        "F2"        : 113,
        "F3"        : 114,
        "F4"        : 115,
        "F5"        : 116,
        "F6"        : 117,
        "F7"        : 118,
        "F8"        : 119,
        "F9"        : 120,
        "F10"       : 121,
        "F11"       : 122,
        "F12"       : 123
    },

    /**
     * 默认配置项，创建Tree时传入的配置项会和该配置合并
     * @type {Object}
     */
    Defaults = {
        "style"  : "default", //显示风格
        "items"  : "h1,h2,h3,h4,h5,h6,-,link,image,-,bold,italic,code,-,ul,ol,blockquote,hr,-,fullscreen,save",
        "width"  : "100%", //宽度
        "height" : "100%", //高度
        "lang"   : "zh-cn", //语言
        "tab"    : "    ", //Tab键插入的字符， 默认为四个空格
        "save"   : $.noop //保存按钮回调接口
    };

/**
 * Range构造器，用于创建一个新的Range对象
 * @param {Object} textarea 一个textarea对象，用于创建Range
 */
Range = function(textarea){
    /* 绑定Range对象的textarea */
    this.textarea = textarea;
}

/**
 * 扩展Range原型，主要添加了get,set,insert三个方法
 * @type {Object}
 */
Range.prototype = {
    /**
     * 获取当前range
     * @return {Object} 当前range对象
     */
    "get" : function(){
        var textarea = this.textarea, data = {"start" : 0, "end" : 0, "text" : ""},
            range, dupRange, rangeNl, dupRangeNl;

        textarea.focus();
        if (textarea.setSelectionRange) { // W3C  
            data.start = textarea.selectionStart;
            data.end   = textarea.selectionEnd;
            data.text  = (data.start != data.end) ? textarea.value.substring(data.start, data.end) : "";
        } else if (document.selection) { // For IE
            range    = document.selection.createRange(),
            dupRange = range.duplicate();
            dupRange.moveToElementText(textarea);
            dupRange.setEndPoint("EndToEnd", range );

            data.text  = range.text; //选中的文本内容
            rangeNl    = range.text.split("\n").length - 1; //选中文本换行数
            dupRangeNl = dupRange.text.split("\n").length - 1; //选中之前换行数
            data.start = dupRange.text.length - range.text.length - dupRangeNl + rangeNl;
            data.end   = data.text.length + data.start - rangeNl;
        }

        return data;
    },

    /**
     * 设置当前range的位置
     * @param  {Integer} start 起始位置
     * @param  {Integer} end   结束位置
     * @return {Object}        当前Range对象
     */
    "set" : function (start, end) {
        var range, textarea = this.textarea;

        textarea.focus();
        if (textarea.setSelectionRange) { // W3C
            textarea.setSelectionRange(start, end);
        } else if (textarea.createTextRange) { // For IE
            range = textarea.createTextRange();
            range.collapse(true);
            range.moveStart("character", start);
            range.moveEnd("character", end - start);
            range.select();
        }

        return this;
    },

    /**
     * 在当前Range处插入文本
     * @param  {String} text 文本内容
     * @return {Object}      当前Range对象
     */
    "insert" : function (text) {
        var textarea = this.textarea, data = this.get(),
            oValue, nValue, range, scroll;
        
        if (textarea.setSelectionRange) { // W3C
            oValue         = textarea.value;
            nValue         = oValue.substring(0, data.start) + text + oValue.substring(data.end);
            scroll         = textarea.scrollTop;
            data.end       = data.start + text.length;
            textarea.value = nValue;
            
            /**
             * Fixbug:
             * After textarea.values = nValue, scrollTop value to 0
             */
            if(textarea.scrollTop != scroll) {
                textarea.scrollTop = scroll;
            }

            textarea.setSelectionRange(data.start, data.end);
        } else if (textarea.createTextRange) { //For IE
            range      = document.selection.createRange();
            range.text = text;
            range.setEndPoint("StartToEnd", range);
            range.select();
        }

        return this;
    }
};

/**
 * 创建编辑器工具栏
 * @param  {Object} $editor 编辑器对象
 * @param  {Object} options 配置项
 */
function create_editor_tools($editor, options){
    var self = this, items, groups = options.items.split(",-,"), $group, $tools = $("<div/>");
    
    /* 创建按钮组 */
    for(i in groups){
        items  = groups[i].split(",");
        $group = $("<div/>").addClass("thinkeditor-tools-group").appendTo($tools);
        for(j in items){
            $("<a/>").addClass("thinkeditor-tools-" + items[j])
                .attr({"title" : this.lang(items[j]), "href" : "javascript:;"})
                .data("name", items[j])
                .appendTo($group);
        }
    }

    /* 工具栏放入editor */
    $tools.addClass("thinkeditor-tools").prependTo($editor);

    /* 绑定操作事件 */
    $tools.on("click", ".thinkeditor-tools-group a", function(event){
        event.stopPropagation();
        self.plugin($(this).data("name"), options);
    });
}

/**
 * 执行快捷键
 * @param  {event} event 事件对象
 */
function keyboard(event){
    var keyboard = Array(4), self = event.data.self, options = event.data.options;

    /* 当前按键 */
    keyboard[0] = event.ctrlKey  ? "ctrl"  : "";
    keyboard[1] = event.shiftKey ? "shift" : "";
    keyboard[2] = event.altKey   ? "alt"   : "";
    keyboard[3] = event.which;
    keyboard    = keyboard.join("");

    /* 执行快捷键 */
    if(self.keyboards[keyboard]){
        if($.isFunction(self.keyboards[keyboard])){
            self.keyboards[keyboard].call(self);
        } else {
            self.plugin(self.keyboards[keyboard], options);
        }
        return false;
    }
}

/**
 * 编辑器构造器，用于创建一个新的编辑器对象
 * @param {Object} textarea 被创建编辑器的textarea对象
 * @param {Object} options  编辑器初始化选项
 */
ThinkEditor = function(textarea, options){
    var options, self = this, $textarea = $(textarea), $editor;

    /* 合并配置项 */
    options = $.extend({}, Defaults, options || {});
    options.width  = options.width  ? options.width  : $textarea.width();
    options.height = options.height ? options.height : $textarea.height();

    /* 创建Range对象 */
    this.range = new Range(textarea);
    this.language = Language[options.lang] ? options.lang : "en-us";

    /* 创建编辑器 */
    $textarea.wrap("<div></div>").parent().wrap("<div></div>");
    $editor = $textarea.parent().parent();
    $editor.addClass("thinkeditor thinkeditor-" + options.style);
    $editor.children("div").addClass("thinkeditor-textarea");

    /* 设置editor尺寸 */
    $editor.css({"width" : options.width, "height" : options.height});

    /* 创建工具栏 */
    create_editor_tools.call(this, $editor, options);

    /* 绑定快捷键事件 */
    $textarea.keydown({"self" : this, "options" : options}, keyboard);

    /* 绑定插件的快捷键 */
    for(name in Plugin){
        Plugin[name].keyboard && this.keyboard(Plugin[name].keyboard, name);
    }
}

/**
 * 编辑器原型，用于扩展编辑器的外部调用接口
 * @type {Object}
 */
ThinkEditor.prototype = {
    /**
     * 获取语言变量
     * @param  {String} name     变量名
     * @param  {String} language 语言，默认去当前options.lang
     * @return {String}          指定语言的值
     */
    "lang" : function(name, language){
        return Language[language || this.language][name] || name;
    },

    /**
     * 执行某个插件插件
     * @param {String} name    插件名称
     * @param {Object} options 编辑器配置项
     */
    "plugin" : function(name, options){
        Plugin[name].markdown.call(this, options);
    },

    /**
     * 插入数据到编辑器光标处
     * @param  {String} text 要插入的数据
     * @return {Object}      ThinkEditor对象
     */
    "insert" : function(text){
        var range = this.range.get(), start, _start, end, length, line = 0;
        if(arguments.length > 1){ //首尾添加文本
            start = arguments[0];
            end   = arguments[1];

            if(arguments[2]){ //按行添加
                text   = range.text.split("\n");
                length = range.text.length;

                /* 逐行添加 */
                for(i in text){
                    if(!length || $.trim(text[i])){
                        _start  = start.replace("{$line}", ++line).replace("{$i}", i);
                        text[i] = _start + text[i] + end;
                    }
                }

                /* 插入数据 */
                this.range.insert(text.join("\n"));

                /* 没有选中文本时设置光标位置 */
                if(!length){
                    start = range.start + _start.length;
                    this.range.set(start, start);
                }
            } else {
                this.range.insert(start + range.text + end);
            }
        } else { //插入文本
            this.range.insert(text);
        }
        return this;
    },

    /**
     * 设置或获取编辑器的值
     * @param  {String} text 要设置的值，不传递此参数则获取编辑器的值
     * @return {String}      设置值 - 返回ThinkEditor对象， 获取值 - 返回当前值
     */
    "value" : function(text){
        if(text === undefined){
            return this.range.textarea.value;
        } else {
            this.range.textarea.value = text;
            return this;
        }
    },

    /**
     * 给编辑器绑定快捷键
     * @param  {String}   keys     快捷键名称
     * @param  {Function} callback 触发快捷键时执行额函数或插件名称
     * @return {Object}            当前编辑器对象
     */
    "keyboard" : function(keys, callback){
        var keyboard = Array(4); //[ctrl, shift, alt, code]

        //初始化快捷键
        if(!this.keyboards) { 
            this.keyboards = {};
        }

        keys = keys.toUpperCase().split("+");
        for(i in keys){
            switch(keys[i]){
                case "CTRL" :
                    keyboard[0] = "ctrl";
                    break;
                case "SHIFT" :
                    keyboard[1] = "shift";
                    break;
                case "ALT" :
                    keyboard[2] = "alt";
                    break;
                default:
                    keyboard[3] = KeyCode[keys[i]] || keys[i].charCodeAt();
                    break;
            }
        }
        this.keyboards[keyboard.join("")] = callback;
        return this;
    }
}

/**
 * 通过textarea获取通过当前textarea创建的ThinkEditor对象
 * @param  {elements} textarea textarea对象或jquery textarea选择器
 * @return {Object}            ThinkEditor对象
 */
$.thinkeditor = function(textarea){
    return $(textarea).data("ThinkEditor");
};

/**
 * 添加ThinkEditor全局扩展
 * 提供对编辑器的语言包，插件，全局设置等功能
 */
$.extend($.thinkeditor, {
    /**
     * 扩展语言包
     * @param  {Object} language 语言包
     */
    "language" : function(language){
        $.extend(Language, $.isPlainObject(language) ? language : {});
    },

    /**
     * 扩展插件
     * @param  {Object} plugin 一个或多个插件
     */
    "plugin" : function(plugin){
        $.extend(Plugin, $.isPlainObject(plugin) ? plugin : {});
    },

    /**
     * 全局设置ThinkEditor
     * @param  {Object} options 要改变的编辑器默认设置项
     */
    "defaults" : function(options){
        $.extend(Defaults, $.isPlainObject(options) ? options : {});
    }
});

/**
 * jQuery.fn对象，用于创建ThinkEditor插件
 * @param  {Object} options ThinkEditor初始化参数
 */
$.fn.thinkeditor = function(options){
    return this.each(function(){
        $(this).data("ThinkEditor", new ThinkEditor(this, options));
    });
}

/**
 * ThinkEditor编辑器插件，工具按显示的每一个按钮代表一个插件
 * 一个合法的插件必须包含markdown方法，用于内部调用
 * 如果需要给插件设置快捷键，则可以通过 keyboard 属性来设置
 * 插件的this指针指向当前ThinkEditor对象
 */
$.thinkeditor.plugin({
    /* 标题一插件 */
    "h1" : {
        /* 标题一快捷键 */
        "keyboard" : "ctrl+1",

        /* 执行标题一 */
        "markdown" : function(options){
            this.insert("# ", "", true);
        }
    },

    /* 标题二插件 */
    "h2" : {
        /* 标题二快捷键 */
        "keyboard" : "ctrl+2",

        /* 执行标题二 */
        "markdown" : function(options){
            this.insert("## ", "", true);
        }
    },

    /* 标题三插件 */
    "h3" : {
        /* 标题三快捷键 */
        "keyboard" : "ctrl+3",

        /* 执行标题三 */
        "markdown" : function(options){
            this.insert("### ", "", true);
        }
    },

    /* 标题四插件 */
    "h4" : {
        /* 标题四快捷键 */
        "keyboard" : "ctrl+4",

        /* 执行标题四 */
        "markdown" : function(options){
            this.insert("#### ", "", true);
        }
    },

    /* 标题五插件 */
    "h5" : {
        /* 标题五快捷键 */
        "keyboard" : "ctrl+5",

        /* 执行标题五 */
        "markdown" : function(options){
            this.insert("##### ", "", true);
        }
    },

    /* 标题六插件 */
    "h6" : {
        /* 标题六快捷键 */
        "keyboard" : "ctrl+6",

        /* 执行标题六 */
        "markdown" : function(options){
            this.insert("###### ", "", true);
        }
    },

    /* 添加链接 */
    "link" : {
        /* 连接快捷键 */
        "keyboard" : "ctrl+l",

        /* 插入连接 */
        "markdown" : function(options){
            var range = this.range.get(), start;

            if(range.text.length){
                if(range.text.match(/^http:\/\/.*/i)){
                    this.insert("[" + range.text + "](" + range.text + ")");
                } else {
                    this.insert("[" + range.text + "]()");
                    start = range.start + range.text.length + 3;
                    this.range.set(start, start);
                }
            } else {
                this.insert("[]()");
                start = range.start + 1;
                this.range.set(start, start);
            }
        }
    },

    /* 添加图片 */
    "image" : {
        /* 图片快捷键 */
        "keyboard" : "ctrl+p",

        /* 插入图片 */
        "markdown" : function(options){
            var range = this.range.get(), start;

            if(range.text.length){
                if(range.text.match(/^http:\/\/.*/i)){
                    this.insert("![alt](" + range.text + ")");
                    start = range.start + 2;
                    this.range.set(start, start + 3);
                } else {
                    this.insert("![" + range.text + "](img)");
                    start = range.start + range.text.length + 4;
                    this.range.set(start, start + 3);
                }
            } else {
                this.insert("![](img)");
                start = range.start + 4;
                this.range.set(start, start + 3);
            }
        }
    },

    /* 文本加粗插件 */
    "bold" : {
        /* 文本加粗快捷键 */
        "keyboard" : "ctrl+b",

        /* 执行文本加粗 */
        "markdown" : function(options){
            this.insert("**", "**", true);
        }
    },

    /* 文字倾斜插件 */
    "italic" : {
        /* 文字倾斜快捷键 */
        "keyboard" : "ctrl+i",

        /* 执行文字倾斜 */
        "markdown" : function(options){
            this.insert("_", "_", true);
        }
    },

    /* 插入代码 */
    "code" : {
        /* 代码快捷键 */
        "keyboard" : "ctrl+d",

        /* 插入代码 */
        "markdown" : function(options){
            var range = this.range.get(), start;

            if(range.text.length){
                if(range.text.split("\n").length > 1){
                    this.insert("~~~\n"+ range.text +"\n~~~");
                    start = range.start + 3;
                    this.range.set(start, start);
                } else {
                    this.insert("`"+ range.text +"`");
                }
            } else {
                this.insert("``");
                start = range.start + 1;
                this.range.set(start, start);
            }
        }
    },

    /* 无序列表插件 */
    "ul" : {
        /* 代码快捷键 */
        "keyboard" : "ctrl+u",

        /* 插入代码 */
        "markdown" : function(options){
            this.insert("* ", "", true);
        }
    },

    /* 插入有序列表 */
    "ol" : {
        /* 有序列表捷键 */
        "keyboard" : "ctrl+o",

        /* 插入有序列表 */
        "markdown" : function(options){
            this.insert("{$line}. ", "", true);
        }
    },

    /* 引用文本 */
    "blockquote" : {
        /* 引用快捷键 */
        "keyboard" : "ctrl+q",

        /* 插入代码 */
        "markdown" : function(options){
            this.insert("> ", "", true);
        }
    },

    /* 插入水平分割线 */
    "hr" : {
        /* 分割线快捷键 */
        "keyboard" : "ctrl+h",

        /* 插入分割线 */
        "markdown" : function(options){
            var range = this.range.get(), start = range.start + range.text.length + 11;
            this.insert(range.text + "\n* * * * *\n");
            this.range.set(start, start);
        }
    },

    /* 全屏编辑 */
    "fullscreen" : {
        /* 全屏编辑快捷键 */
        "keyboard" : "ctrl+f",

        /* 执行全屏编辑 */
        "markdown" : function(options){
            var $body = $("body"), $editor = $(this.range.textarea).closest(".thinkeditor");

            if($editor.hasClass("thinkeditor-fullscreen")){
                $body.css("overflow", "");
                $editor.removeClass("thinkeditor-fullscreen");
            } else {
                $body.css("overflow", "hidden");
                $editor.addClass("thinkeditor-fullscreen");
            }
        }
    },

    /* 保存数据 */
    "save" : {
        /* 保存数据快捷键 */
        "keyboard" : "ctrl+s",

        /* 执行全屏编辑 */
        "markdown" : function(options){
            if($.isFunction(options.save)){
                options.save.call(this.range.textarea);
            }
        }
    },

    /* 文本缩进 */
    "indent" : {
        /* 缩进快捷键 */
        "keyboard" : "tab",

        /* 插入缩进 */
        "markdown" : function(options){
            var range = this.range.get(), text, start;
            if(range.start){
                text  = this.range.textarea.value.substring(0, range.start);
                start = text.lastIndexOf("\n") + 1;
                if(range.text.length && start != text.length){
                    this.range.set(start, range.end);
                }
            }
            this.insert(options.tab, "", true);
        }
    },

    /* 减少缩进 */
    "outdent" : {
        /* 减少缩进快捷键 */
        "keyboard" : "shift+tab",

        /* 插入代码 */
        "markdown" : function(options){
            var range = this.range.get(), text, start;
            if(range.start){
                text  = this.range.textarea.value.substring(0, range.start);
                start = text.lastIndexOf("\n") + 1;
                if(start != text.length){
                    range = this.range.set(start, range.end).get();
                }
            }

            if(range.text.length){
                text = range.text.split("\n");
                for(i in text){
                    text[i] = text[i].replace(/^((\t)|( {1,4}))/, "");
                }
                this.insert(text.join("\n"));
            }
        }
    }
});

/**
 * ThinkEditor编辑器默认语言包
 * 默认仅支持英文和简体中文语言包
 * 其他语言包可以通过 $.thinkeditor.laguage() 方法扩展
 */
$.thinkeditor.language({
    /* 英文语言包 */
    "en-us" : {
        "h1"         : "H1 (Ctrl+1)",
        "h2"         : "H2 (Ctrl+2)",
        "h3"         : "H3 (Ctrl+3)",
        "h4"         : "H4 (Ctrl+4)",
        "h5"         : "H5 (Ctrl+5)",
        "h6"         : "H6 (Ctrl+6)",
        "link"       : "Link (Ctrl+L)",
        "image"      : "Image (Ctrl+P)",
        "bold"       : "Blod (Ctrl+B)",
        "italic"     : "Italic (Ctrl+I)",
        "code"       : "Code (Ctrl+D)",
        "ul"         : "Unordered List (Ctrl+U)",
        "ol"         : "Ordered List (Ctrl+O)",
        "blockquote" : "Blockquote (Ctrl+Q)",
        "hr"         : "Horizontal Rule (Ctrl+H)",
        "fullscreen" : "Full Screen (Ctrl+F)",
        "save"       : "Save (Ctrl+S)"
    },

    /* 简体中文语言包 */
    "zh-cn" : {
        "h1"         : "标题一 (Ctrl+1)",
        "h2"         : "标题二 (Ctrl+2)",
        "h3"         : "标题三 (Ctrl+3)",
        "h4"         : "标题四 (Ctrl+4)",
        "h5"         : "标题五 (Ctrl+5)",
        "h6"         : "标题六 (Ctrl+6)",
        "link"       : "链接 (Ctrl+L)",
        "image"      : "图片 (Ctrl+P)",
        "bold"       : "加粗 (Ctrl+B)",
        "italic"     : "斜体 (Ctrl+I)",
        "code"       : "代码 (Ctrl+D)",
        "ul"         : "无序列表 (Ctrl+U)",
        "ol"         : "有序列表 (Ctrl+O)",
        "blockquote" : "引用 (Ctrl+Q)",
        "hr"         : "分割线 (Ctrl+H)",
        "fullscreen" : "全屏编辑 (Ctrl+F)",
        "save"       : "保存 (Ctrl+S)"
    }
});

}(jQuery);