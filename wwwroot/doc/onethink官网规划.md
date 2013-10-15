onethink官网规划
===============

概要
----

> 大家在这份概要的基础上可以增加自己的想法. 或者在最后补充意见

### 首页

+ OneThink产品介绍,新闻,下载
+ 精华插件展示
+ 精华讨论展示
+ 优秀案例展示
+ 业界资讯

### 讨论区

+ 主题和回复允许使用富文本编辑器
+ 收藏和分享
+ 技术类讨论区 形式上类似oschina讨论贴(最佳答案,支持,反对)
+ 非技术类讨论区 类似目前tp官网讨论区的形式
+ 社会化分享

#### 板块

+ onethink讨论区
+ 站长交流区
+ 站务/新闻(只读板块)
+ 业界资讯

#### 页面

+ 注册/登陆/找回密码
+ 讨论区列表页
+ 主题详情页
+ 发表主题页
+ 用户中心
    - 资料修改
    - 我的主题
    - 我的回复
    - 我的消息
    - 我的收藏
+ 消息中心
+ 推荐位
+ 广告位


### issues管理

> 以更专业的方式管理 bug/issue/建议/开发

### 应用中心

> 提供 插件/工具/模块/模板 的上传下载

+ 荣誉激励机制
    - 荣誉值依靠作品获得,作为用户在官网的身份地位象征. 等级越高越会得到其他用户的信任,在求职、项目等方面占据优势。
    - 开发者制作上传作品可获得一定的荣誉值
    - 作品根据下载量可积累荣誉值
    - 作品根据得到的用户评价积累荣誉值

+ 收费下载模式
    - 只有荣誉值达到一定程度，才允许发布付费作品，以保证作品质量和信誉。
            
### 用户中心

> 需要对前台用户实现全面细致的管理支持
    
### 其他意见

+ 签到
+ 关注
+ @


OneThink.cn前端开发规范
==============

> 官网对IE浏览器支持起点为IE9,部分css降级支持到IE8, 开发阶段完全不需要考虑IE6/7
> 在选择js插件时,优先按以下条件选择:
> 
> * 托管在github上的开源项目
> * 对移动设备支持较好
> * 源码规范,有注释
> * 小巧实用或强大但灵活易用易扩展
> * 依赖较少
> * 有文档和示例
> * 协议为:BSD,MIT,Apache

JS插件
------

插件                      描述                                     浏览器支持                 其他需求
Selectize.js              一个自定义的文本`<select>`实现           IE9+,(IE8需es5-shim),ios5+
iCheck                    一个可高度自定义的radio,checkbox实现     所有桌面和移动浏览器
naeka/jquery-switchbutton on/off按钮                               ie6+                       'jQuery UI Widget'
casperin/nod              表单验证                                 ie6+
bootstrap-daterangepicker 日期范围选择插件(用户体验超棒)           ie8+                       Bootstrap2/3,Moment.js
typeahead.js              输入框自动完成插件                       ie6+                       jQuery1.9+
qTip2                     强大的tip提示插件(可实现Dialog)          ie6+,ios4+
jaz303/tipsy              单纯的tip插件，小巧好用                  ie6+
momentjs                  非常全面的时间展示插件                   all
jquery.cookie             cookie操作插件                           all
underscorejs              提供了大量工具方法                       all
jamesallardice/Placeholders.js  为所有浏览器提供placeholder支持    all
derek-watson/jsUri        uri分析插件                              all
JangoSteve/jQuery-EasyTabs                                         ie7+
zeroclipboard/zeroclipboard 复制到剪贴板                           ie6+
responsive-nav.js         独立的响应式导航插件                     ie6+
DateTimePicker            时间日期选择器                           ie9+
idiot/unslider            一个超小的jQuery轮播插件                 ie9+
makeusabrew/bootbox       bs2/3的对话框插件                        ie8+

ui解决方案
----------

+ 以bootstrap3作为前端前端解决方案(支持至ie8)
+ 以[yui/pure](http://purecss.io/)作为布局向下兼容至ie7解决方案
+ 需要自己写css和js实现的部分,尽量封装成可重用的组件

UI参考
------

> 有优秀的可参考网页,请在下面补充

+ http://www.oschina.net/news/44046/10-super-useful-free-flat-ui-kits
+ http://www.xenserver.org/
+ http://designmodo.com/flat/
+ bootswatch.com


其他资源
--------

+ [每位设计师都应该拥有的50个CSS代码片段](http://www.oschina.net/translate/css-snippets-for-designers)
