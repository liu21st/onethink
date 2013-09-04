表格列表
=============

> 通过配置表头,自动生成需要的 **分页表格数据**


基本流程
--------

1. 配置表头
2. 查询和处理数据
3. 分配变量
4. display 

配置表头
---------

> 可以在配置值中直接使用该行数据集中的字段

示例:

```php
$thead = array(
    //元素value中的变量就是数据集中的字段,value必须使用单引号
    
    //所有 _ 下划线开头的元素用于使用html代码生成th和td中的内容
    '_html'=>array(
        'th'=>'<input class="check-all" type="checkbox"/>',
        'td'=>'<input class="ids" type="checkbox" name="id[]" value="$id" />',
    ),

    //"数据集中的字段"=>"字段的表头"
    'title'           =>'用户组',
    'description'     =>array( //高级玩法
        'title'=>'描述',
        'tag'  =>'a',//标签名
        'class'=>'my_class',//可以配置任意标签属性
        'href' =>'供U函数使用的参数',
        'func' =>'mb_substr($description,0,20,"utf-8")',
    ),
    'status_text'     =>'状态',

    //操作配置
    '操作'=>array(
        //操作按钮 =>'按钮链接'
        '编辑'     =>'AuthManager/editgroup?id=$id',
        '禁用'     =>array(
            'tag'  =>'a',//按钮的包裹元素,默认为 a 标签,可以不设置
            // 标签上的attr,需要什么设置什么,此处设置了a标签的href属性
            'href' =>'AuthManager/changeStatus?method=forbidGroup&id=$id',
            // 按钮显示的条件,支持 == != > < 比较运算
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

查询数据
--------

> 任何格式与Model::select()方法返回的结果集结构相同的数据集都可以作为表格列表的数据源

> 数据集的字段名,可以作为变量在表头设置中使用,表格列表会把变量转换为该行数据对应字段的值

系统基类控制器已经内置了一个非常强大的通用分页数据集查询方法 `$this->lists($model,$where,$order)`

### 简单用法

* $model  支持模型名称或模型实例

* $where  可选参数:自定义查询条件,数组,与Model::where()的参数格式相同,会与基本条件array('status'=>array('egt',0))做array_merge后作为最终的查询条件
    * lists方法会自动使用url中的参数作为查询条件:例如`aaa.html?title=sometitle`,会被转换为array('title'=>'sometitle')作为查询条件,所以可以直接作为搜索方法使用

* $order  可选参数:排序条件,默认为:'id desc'. 
    * lists方法支持通过url控制表格排序,通过`_field`参数指定排序的依据字段,通过`_order`指定排序的方式asc或desc;例如`aaa.html?_field=id&_order=asc`即表示根据id字段进行升序排列

### 高级用法

> 有些列表需要join两张或以上表,lists()方法同样支持,见示例:

```php
  $Model = M()
           ->table('left_tabel as l')
           ->join('right_table as r ON l.id=r.uid')
           ->where(array('l.status'=>1));//可以使用其他任何返回模型自身的方法,但最后不要调用select()等查询方法!
  $list = $this->lists($Model);//把构造好的模型传入,返回查询数据集
```

### 处理数据集

```php
$list = intToString($list);
```

分配模板变量
----------

> 现在,表头和数据集都有了,你可以调用`$this->tableList()`方法得到表格,第一个参数是数据集,第二个参数是表头

```php
$table = $this->tableList($list,$thead);
//$this->assign( '_table_class', 'table table-bordered');//如果你要给表格的table标签加class,可以分配一个_table_class模板变量
$this->assign( '_table_list', $table );
$this->dispaly();
```

修改模板
---------

> 现在,模板变量已经有了,你只需要在模板文件需要显示表格的地方`{$_table_list}`

> 在需要显示分页的地方`{$_page}`


控制每页数据行数
---------------

> 通过url参数`r`可以控制每页显示的行数,例如:`aaa.html?r=20`表示每页显示20行数据

