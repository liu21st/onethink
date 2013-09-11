示例
=========

> 为保证操作体验的连贯性和一致性,后台所有操作和按钮应全部使用ajax方式

> 如果一个链接或按钮点击后只有两种状态:成功/失败,那么这个按钮或链接应该ajax化,例如数据列表
中的"禁用","启用","删除",表单页面中的"保存",相应地,如果要执行成功后跳转到其他页面,后台应在success方法里出入调整页面参数



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

> 如果一个链接或按钮定义了 **ajax-post** 类和"target-form"属性 ,那么点击后,将自动以post方式发送请求

> 特别注意:input和button标签都必须明确设定 `type="submit"`

```
<form class="the-from" action="...">
    <input type="submit" class="save-form" target-form="the-form" value="保存" />
    <button type="submit" class="save-form" target-form="the-form" >保存</button>
</form>
```

如果the-form是一个form标签,会启动取form标签的action属性值作为请求目标
否则,你必须在按钮上定义href或url属性明确声明请求目标:

```
<button type="submit" url="..." class="ajax-post" target-form="the-form">批量删除</button>
<div class="the-form">
    <input />
</div>
```

confirm
-------

对于删除这样的操作,通常在执行之前应提示用户确认.只需要在按钮的class中增加一个`confirm`即可


其他
----

ajax-post定义在common.js,如果你提交前需要绑定自己的submit事件,应该从common.js中复制出ajax-post代码到自己的文件中,把自己的逻辑插入到前面;
否则由于ajax-post绑定在先,你后面绑定的逻辑肯定不会生效.(复制过去后,类名也要改一下,否则还是会绑定ajax-post)