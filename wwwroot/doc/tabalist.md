表格列表
=============

> 通过配置表头,自动生成需要的 **分页表格数据**


基本流程
--------

1. 查询和处理数据
2. 配置表头
3. 分配变量
4. display 

查询数据
--------

> 任何格式与Model::select()方法返回的结果集结构相同的数据集都可以作为表格列表的数据源

> 即数据源格式为 **二维数组**

> 数据集的字段名(即第二维数组的key名),可以作为变量在表头设置中使用,表格列表会把变量转换为该行数据对应字段的值

系统基类控制器已经内置了一个非常强大的通用分页数据集查询方法:


### lists方法

 `$this->lists($model,$where=array(),$order='',$base = array('status'=>array('egt',0)),$field=true)`


* $model  (字符串或模型对象)支持模型名称或模型实例

* $where  (数组)可选参数:自定义查询条件,数组,与Model::where()的参数格式相同,会与基本条件array('status'=>array('egt',0))做array_merge后作为最终的查询条件

    * lists方法会自动使用url中的参数作为查询条件:例如`aaa.html?title=sometitle`,会被转换为array('title'=>'sometitle')作为查询条件,所以可以直接作为搜索方法使用
    * 例如:`index.html?id[0]=egt&id[1]=5`即表示列出id值大于或等于5的数据列表;
    * 上面的get参数不易理解,但你可以自己设计get传参方式,只需在调用lists()方法之前,把get参数转换成where参数传给lists()方法,同时unset掉不需要的get参数即可.

    * 优先级：$where参数优先级最高，其次是url中的请求参数，优先级最低的是模型实例中的where()方法中的条件

* $order  (字符串)排序条件

    * 字符串，例如：'id desc',默认值'',会尝试根据主键降序排序
    * 如果请求参数中指定了`_order` 和 `_field` 则据此排序(忽略$order参数);例如：`index.html?_order=desc&_field=age`表示根据age字段降序排序
    * 传入 **null** 时强制使用sql默认排序或模型属性(忽略请求参数中的排序参数); (通常用于join模型)

* $base  (数组)基本查询条件，即查询条件中一定会有的条件

* $field (字符串)默认会查询所有字段，如果需要限制，可以在这里定义，参数格式为字段列表，例如：'id,name,age,sex,email' 



### 简单用法

```
$this->lists('modelname');
$this->lists('modelname',null,'age asc');
$this->lists('modelname',null,'age asc',array('status'=>1));
$this->lists('modelname',null,'age asc',array('status'=>1),'id,name,age,sex,email' );
```

### 高级用法

> 有些列表需要join两张或以上表,lists()方法同样支持,见示例:

```php
//构造查询模型
$list     = M()->field( 'm.uid,m.nickname,m.last_login_time,m.last_login_ip,m.status' )
               ->table( $l_table.' m' )
               ->where( array('a.group_id'=>$group_id,'m.status'=>array('egt',0)) )
               ->order( 'm.uid asc')
               ->join ( $r_table.' a ON m.uid=a.uid' );
$_REQUEST = array();//因为要使用模型中的where条件，所以这里清空优先级更高的$_REQUEST
$list = $this->lists($list,null,null,null);
```

### 处理数据集

> 数据集的一些字段通常是有特殊含义的整型值，呈现给用户的应该是他们对应的含义，所以需要对数据集做一些处理

> OneThink中状态字段 status 的值与含义对应关系是：-1:删除，0:禁用，1:正常，2:审核通过，3:草稿; 如果数据集含义status字段，直接使用以下函数处理即可：

```php
int_to_string($list);
```

如果需要转换的字段不止一个，可以这样调用：

```php

int_to_string($list,array(
    array('status'    =>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'审核通过')),
    array('model_type'=>array(1=>'文章',2=>'下载',3=>'图集')),
));
```


配置表头
---------

> 通过配置表头，即可根据表头生成对应的数据表格

* 可以在配置值中直接使用 **数据集中存在的字段**,如果使用了数据集中不存在的字段，会报错．

* 所使用的数据集字段，必须是 **标量**

* 表头配置使用 **单引号**

* 表头的配置顺序与实际生成的表格表头顺序一致

示例:

```php
$thead = array(
    //元素value中的变量就是数据集中的字段,value必须使用单引号
    
    //所有 _ 下划线开头的元素用于使用html代码生成th和td中的内容
    '_html'=>array(
        'th'=>'<input class="check-all" type="checkbox"/>',
        'td'=>'<input class="ids" type="checkbox" name="id[]" value="$id" />',
    ),

    //简单方式："数据集中的字段"=>"字段的表头"
    'title'           =>'用户组',

    //高级方式："数据集中的字段"=>"字段配置数组"
    'description'     =>array( 
        '_title'=>'描述',      //表头名称
        'tag'  =>'a',          //内容标签名，td内容被此标签包裹
        'func' =>'mb_substr($description,0,20,"utf-8")', //表格数据处理，直接使用函数

        //包裹元素的html属性
        'class'=>'my_class',          //可以配置任意标签属性
        'href' =>'供U函数使用的参数', //href和url属性必须使用标准的U函数参数，例如：`Index/index?a=1&b=2`
    ),

    'status_text'     =>'状态',

    //操作配置
    '操作'=>array(
        //简单方式：操作名称 =>'链接'
        '编辑'     =>'AuthManager/editgroup?id=$id',

        //高级方式：操作名称 =>数组
        '禁用'     =>array(
            'tag'  =>'a',//按钮的包裹元素,默认为 a 标签,可以不设置
            // 标签上的属性,需要什么设置什么,此处设置了a标签的href属性
            'href' =>'AuthManager/changeStatus?method=forbidGroup&id=$id',
            // 操作显示的条件,支持 == != > < 比较运算,以及一些可以返回布尔值的函数
            'condition'=>'$status==1',
        ), 
        '启用' => array(
            'href'     =>'AuthManager/changeStatus?method=resumeGroup&id=$id',
            'condition'=>'$status==0',
        ), 
        '删除' =>'AuthManager/changeStatus?method=deleteGroup&id=$id',
    ),

    //另一列操作配置
    '授权'=>array(
        '成员'=>'AuthManager/user?group_name=$title&group_id=$id',
        '栏目'=>'AuthManager/category?group_name=$title&group_id=$id',
    ),
);
```

分配模板变量
----------

> 现在,表头和数据集都有了,你可以调用`$this->tableList()`方法得到表格,第一个参数是数据集,第二个参数是表头

```php
$table = $this->tableList($list,$thead);
$this->assign( '_table_list', $table );
$this->dispaly();
```

在模板文件中，你只需要`{$_table_list}`即可输出表格


如果你们是前后端分工明确，那么把表头定义在控制器中可能不利于前端工作，
那么你可以把数据集和控制器对象赋值到一个模板变量中(OneThink已经把控制器对象赋值到了模板的`__controller__`变量)，
在模板中定义表头和输出表格

```
<!-- 模板文件　-->
<php>
$thead = array(
        ......
);
echo $__controller__->tableList($_list,$thead);
</php>
```


修改模板
---------

> 在模板文件中，你只需要`{$_table_list}`即可输出表格


如果你们是前后端分工明确，那么把表头定义在控制器中可能不利于前端工作，
那么你可以把数据集和控制器对象赋值到一个模板变量中(OneThink已经把控制器对象赋值到了模板的`__controller__`变量)，
在模板中定义表头和输出表格

```
<!-- 模板文件　-->
<php>
$thead = array(
        ......
);
echo $__controller__->tableList($_list,$thead);
</php>
```

> 在需要显示分页的地方:

```
    <div class="page">
        {$_page}
    </div>
```


控制每页数据行数
---------------

> 通过url参数`r`可以控制每页显示的行数,例如:`aaa.html?r=20`表示每页显示20行数据

