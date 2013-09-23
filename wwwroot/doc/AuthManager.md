权限相关功能开发指导
===================


全局权限配置
------------

> 全局权限配置指不分级别对 **除超管外所有管理员用户** 生效的配置

### deny静态属性

> 保存禁止所有管理员访问的public方法,这些公共方法通常供 A 函数从内部调用 

例如: AuthManager控制器中有一个方法updateRules用于更新系统的菜单,不允许通过url访问,这个方法是供System控制器中的缓存管理方法使用的,所以必须设置为 public

    static protected $deny  = array('updateRules');

### allow静态属性

>  保存允许所有管理员访问的公共方法

例如: 登陆、退出、检测系统新版本,获取官方rss新闻等功能,是允许任何管理员登陆后可用的,无需配置权限节点

    static protected $allow = array('login','logout', 'rss','version_check');



节点权限控制
------------

> 节点,即控制器的操作方法对应的url;节点权限即指登陆后台后管理员可访问的后台区域,
> 这些页面系统安装后即始终存在,因此可以直接在控制器中定义$nodes属性.

> 权限系统将根据控制器的 $nodes 属性自动为不同权限的管理员返回对应权限的后台界面菜单,任何非授权的访问将被拒绝.

> 节点在控制器属性中设定，在后台 "访问授权" 中为管理员授权节点。


### 主节点单配置

> 主菜单配置位于 AdminController的 $menus 静态属性

```php
    private $menus = array(
        array( 'title'=>'首页','url'=>'Index/index',  'controllers'=>'Index',),
        array( 'title'=>'内容','url'=>'Article/index','controllers'=>'Article',),
        array( 'title'=>'用户','url'=>'User/index',   'controllers'=>'User,AuthManager'),
        array( 'title'=>'扩展','url'=>'Addons/index', 'controllers'=>'Addons,Model',),
        array( 'title'=>'系统','url'=>'System/index', 'controllers'=>'System,Category',),
        array( 'title'=>'其他','url'=>'other',        'controllers'=>'File','hide'=>true),//专门放置不需要显示在任何菜单中的节点
    );
```

* **title**  必设
* **url**    必设,供U函数使用的合法参数
* **controllers** 必设,将从此处列出的控制器中读取子节点属性$nodes,多个控制器用英文逗号隔开(注意大小写要正确)
* **hide** 可选,隐藏主节点菜单(但其子菜单仍受权限系统控制)


### 子节点配置

> 子节点配置位于 Controller自己的文件中的 $nodes静态属性

示例：

```php
    static protected $nodes= array(

        //一个子节点
        array('title'=>'权限管理','url'=>'AuthManager/index','group'=>'用户管理','hide'=>false,
                  'operator'=>array(
                      //这个子节点的操作子节点
                      array('title'=>'编辑','url'=>'AuthManager/editGroup'),
                      array('title'=>'删除','url'=>'AuthManager/changeStatus?method=deleteGroup'),
                      array('title'=>'禁用','url'=>'AuthManager/changeStatus?method=forbidGroup'),
                      array('title'=>'恢复','url'=>'AuthManager/changeStatus?method=resumeGroup'),
                  ),
        ),
        //另一个子节点
        array(
            ......
        ),
        //更多子节点
        ......
    );
```

子节点配置项：

*   配置项目的key必须小写

*   **title**, **url** 为 **必选配置**, 用于生成菜单和执行验证; url配置值格式:`Moudle/Action?param1=value1&param2=value2`

*   **group** 默认值为"default", 后台左侧菜单把相同group的子节点归类在一起

*   **operator** 为可选,用于页面打开后页面内的操作按钮的权限,元素必须配置title和url

*   **hide** 可选,值为true时,该条目不会显示在菜单中(但受权限系统控制)


动态页面权限控制
----------------

> 动态页面指安装系统后,经过后台添加自动生成的页面,例如栏目分类;

> 动态页面权限不能写死在控制器中,而是需要在自己的action执行时,进行权限验证.

> 因此需要首先建立权限关系记录(例如:使用一个表记录用户组id与有权栏目分类id的对应关系)

### 验证流程

访问页面 `>` 获取用户uid `>` 根据uid获取用户组id `>` 读取用户组拥有的相应权限 `>` 执行判断逻辑

### 栏目分类权限验证

> 系统已经内置了栏目分类相关的权限验证的功能,栏目与用户组对应关系存储在`think_auth_category_access`表,这是一个典型的动态权限验证实例。
> OneThink实现了分类栏目的权限验证，以及分类下的文档的权限验证，每当对一个分类或文档访问和操作时，都会验证其权限，以拒绝非法的访问。

> 系统内置了获取用户拥有访问权限的所有分类id的静态方法:`AuthGroupModel::getAuthCategories($uid)`,
> 该方法返回一个索引数组,包含了用户拥有权限的所有栏目分类的id,你只需要通过ia_array()函数判断当前用户
> 访问的栏目id是否在数组中即可完成权限判断

### 栏目权限设置

> 系统内置了设置栏目所属用户组的静态方法:`AuthGroupModel::addToCategory($gid,$cid)`

* @param intarray $gid   用户组id
* @param string|array $cid   分类id

示例: 为id为123的用户组设置栏目id为(2,3,5,8,9)的访问权限

```
AuthGroupModel::addToCategory( 123, array(2,3,5,8,9) ); 
或
AuthGroupModel::addToCategory( 123, '2,3,5,8,9' ); 
```

动态扩展菜单
----------------------

> 如前所述，系统可以根据$nodes属性自动生成左侧菜单，但缺点是这些菜单节点是定义在控制器中，而不是动态生成的。

> 如果在控制器的输出模板的方法中为模板分配一个菜单扩展变量 `_extra_menu`,即可为左侧的菜单动态增加菜单.

> 例如,如果在index操作中增加以下变量,则在访问index操作是左侧便会在"测试组"插入一条"测试链接"菜单,在"用户管理"组插入一条"测试链接2"菜单

> 你可以把以下代码粘贴到操作中查看效果


```
        //菜单扩展变量名: _extra_menu
        $this->assign('_extra_menu',array(
            // '组名称'=>链接配置数组
            '测试组'=>array(
                array('title'=>'测试链接','url'=>'AuthManager/index2'),
                ......
            ),
            '用户管理'=>array(
                array('title'=>'测试链接','url'=>'AuthManager/index3'),
                ......
            ),
        ));
```


更新数据库
-----------

> 创建一个用户组，然后当您在控制器中更改过节点配置后，只需要进入 “访问授权” 即可自动把您的节点配置更新至数据库。

> 同时，右下角trace菜单中的“开发提示”会随时向您报告控制器中存在的所有没有配置权限的public方法
