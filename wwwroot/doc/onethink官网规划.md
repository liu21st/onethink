onethink官网规划
===============

概要
----

> 大家在这份概要的基础上可以增加自己的想法. 或者在最后补充意见

### 首页

+ 导航
    - 首页/讨论区/应用/下载/文档/搜索/消息提醒/登陆/退出
+ OneThink产品介绍/最新版下载链接/更新日志
+ 精华插件展示
+ 精华/最新讨论展示
+ 优秀案例展示
+ 明星用户/排行
+ 业界资讯
+ 投稿

### 讨论区

#### 版块

> 运营初期，版块尽量少，后期人气流量提升后，根据情况进一步细分版块

+ onethink讨论区
+ 站长交流区
+ 站务/新闻
+ 业界资讯

#### 页面

+ 注册/登陆/找回密码
+ 讨论区列表页
    - 模型：不使用article模型,建立独立的bbs模型以方便以后的扩展和升级
    - 排序: 支持发表时间,回复时间,类型(文档模型/精华/热门)
    - 信息: 头像,作者,阅读数,回复数,最后回复/时间
    - 摘要: 如果description有内容,显示摘要按钮
    - 头像: 悬停显示用户名片
    - 图标: 精华/推荐/有图/有附件/热门(收藏数达到一定值)
    - 侧边: 分类话题达人,月热帖/周热帖/热门回复(规则待定)
+ 主题详情页
    - 社会化分享
    - 收藏
    - 顶/踩
    - 最佳答案
    - 加精
    - 上一篇/下一篇
+ 回复列表
+ 发表主题页
+ 用户中心
    - 资料修改
    - 我的主题
    - 我的回复
    - 我的消息
    - 我的收藏
    - 我的应用(插件/主题/工具/应用)
+ 用户主页(显示该用户的部分资料,主题回复应用等数据)
+ 主题推荐位


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
            
### 后台用户中心

> 需要对前台用户实现全面细致的管理支持
    
### 其他意见

+ 签到
+ 关注
+ @

工作计划
================

10.14-10.18
-----------

+ 大致确定官网总体规划
+ 确定前端基础解决方案,收集整理一些有助于提高用户体验和开发效率的插件和方案
+ 分析了解现有home模块
+ 根据官网规划大致确定home模块的控制器/模型
+ 大致确定后续工作计划
+ 首页/讨论区列表/帖子详情页UI设计
+ 确定功能开发分工和推进计划

10.21-10.25
------------

+ 初步完成首页/讨论区列表/详情页模板
+ 完成用户中心/应用中心UI设计

10.28-11.1
----------

+ 初步完成用户中心,应用中心模板

11.4-11.8
---------

+ 前台细节和用户体验方面改进
+ 套模板
+ 冻结功能开发


11.11-11.15
--------------

> 根据正式版开发进度,调整开发

+ 测试/调整
+ 安全检测/调整
+ 正式部署开放


OneThink.cn前端开发规范
==============

> 官网对IE浏览器支持起点为IE9,部分css降级支持到IE8(以ie6环境下的360安全浏览器测试), 开发阶段完全不需要考虑IE6/7
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

> 以下是一些用户体验较高的插件，平时上网遇到好插件及时收集整理，以供不时之需。

插件                      描述                                     浏览器支持                 其他需求
Selectize.js              一个自定义的文本`<select>`实现           IE9+,(IE8需es5-shim),ios5+
patrickkunka/easydropdown 一个select标签样式重载的插件             ie8+
prashantchaudhary/ddslick 一个select增强的插件                     ie8+
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
DateTimePicker            时间日期选择器                           ie8+
makeusabrew/bootbox       bs2/3的对话框插件                        ie8+
kamens/jQuery-menu-aim    一个提升多层下拉菜单用户体验的插件       ie8+ 

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
+ http://bootswatch.com


其他资源
--------

+ [每位设计师都应该拥有的50个CSS代码片段](http://www.oschina.net/translate/css-snippets-for-designers)
