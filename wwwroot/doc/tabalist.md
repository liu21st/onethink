表格列表
=============

> 通过配置表头,自动生成需要的分页表格数据


基本流程
--------

1. 配置表头
2. 查询数据
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
