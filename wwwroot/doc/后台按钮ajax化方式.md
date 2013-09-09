示例
=========

> 为保证操作体验的连贯性和一致性,后台所有操作和按钮应全部使用ajax方式

> 如果一个链接或按钮点击后只有两种状态:成功/失败,那么这个按钮或链接应该ajax化,例如数据列表
中的"禁用","启用","删除",表单页面中的"保存",相应地,后台方法必须使用success和error方法返回响应信息



ajax-get链接
--------

> 如果一个链接或按钮定义了 **ajax-get** 类,那么点击后,将自动以get方式发送请求,请求的url地址定义在连接或按钮的href或url属性中

```
<a href="..." class="ajax-get">ajax链接</a>
<a url="..."  class="ajax-get">ajax链接</a>
<button url="..." class="ajax-get">ajax按钮</button>
```

ajax-post按钮
--------

> 如果一个链接或按钮定义了 **ajax-post** 类,那么点击后,将自动以post方式发送请求

```
<form class="the-from" action="...">
    <input type="submit" class="save-form" target-form="the-form" value="保存" />
    <button type="submit" class="save-form" target-form="the-form" >保存</button>
</form>
```

如果the-form是一个form标签,会启动取form标签的action属性值作为请求目标
否则,你必须在按钮上定义href或url属性明确声明请求目标:

```
<button url="..." class="ajax-post" target-form="the-form">批量删除</button>
<div class="the-form">
    <input />
</div>
```

confirm
-------

对于删除这样的操作,通常在执行之前应提示用户确认.只需要在按钮的class中增加一个`confirm`即可
