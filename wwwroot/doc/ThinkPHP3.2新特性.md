# 目录结构调整
    wwwroot（web访问目录或者某个子目录）
    ├─index.php     应用入口文件
    ├─Public 应用资源文件目录（可选）
    │  ├─Css 应用样式文件目录（可选）
    │  ├─Js 应用JS文件目录（可选）
    │  ├─... 更多资源文件目录
    │  
    ├─Application 应用目录（应用目录名由入口文件中的APP_PATH常量设置决定，可以部署在非web目录下面）
    │  ├─Common 公共模块目录（不能直接访问）
    │  │  ├─Conf 公共配置文件目录
    │  │  ├─Common 公共文件目录
    │  │  ├─Controller 模块访问控制器目录（可选）
    │  │  ├─Model 公共模型目录（可选）
    │  │  ├─Service 公共Service层目录（可选）
    │  │  ├─Widget 公共Widget控制器目录（可选）
    │  │  ├─Behavior 公共行为扩展目录（可选）
    │  │  ├─... 更多分层目录
    │  │  
    │  ├─Home Home模块目录
    │  │  ├─Conf 模块配置文件目录
    │  │  ├─Common 模块公共文件目录
    │  │  ├─Controller 模块访问控制器目录
    │  │  ├─Model 模块模型目录（可选）
    │  │  ├─Service 模块Service层目录（可选）
    │  │  ├─Widget 模块Widget控制器目录（可选）
    │  │  ├─Behavior 模块行为扩展目录（可选）
    │  │  ├─View 模块视图文件目录
    │  │  ├─... 更多分层目录
    │  │
    │  ├─Admin Admin模块目录
    │  │  ├─ ...模块子目录（同Home目录）
    │  │
    │  │...更多模块目录
    │  │
    │  ├─Runtime 默认的应用运行时目录（可写，可定制）



# 取消分组，直接使用多模块（原独立分组）

* 默认URL必须带上模块名
* 可配置禁止访问目录（默认配置为Common,Runtime,自己配置会覆盖，所以需要加上这两个目录）
* 默认控制器层更改为 Controller (IndexController.class.php 但是为了升级方便，默认还是继承Action)


# 函数相关

* 添加T函数，用于定位模板文件
* 添加E函数，用于抛出异常（建议使用E函数替代原来的throw_exception）
* I函数添加全局过滤支持，默认APP类不做全局过滤
* halt函数改为Think::halt静态方法

# 模板引擎

* block标签支持嵌套

# 模型相关

* 字段映射读取数据收会自动处理映射字段
* 自动完成忽略支持指定某个值，不仅仅是空

# 配置相关

* 修正配置参数 MULIT_MODULE  => MUILT_MODULE
* 支持视图目录独立于模块之外（VIEW_PATH）
* READ_DATA_MAP 设置自动映射开关
* 添加MODULE_DENY_LIST 设置静止访问模块列表