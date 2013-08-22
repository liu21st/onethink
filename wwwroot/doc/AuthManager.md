权限相关功能开发指导
===================


全局权限配置
------------

> 全局权限配置指不分级别对所有用户管理员生效的配置

### deny静态属性

> 保存禁止所有管理员访问的公共方法,这些公共方法通常供A函数从内部调用 

例如: AuthManager控制器中有一个方法updateRules用于更新系统的菜单,不允许通过url访问,这个方法是供System控制器中的缓存管理方法使用的,所以必须设置为 public

    static protected $deny  = array('updateRules');

### allow静态属性

>  保存允许所有管理员访问的公共方法

例如: 检测系统新版本,获取官方rss新闻等功能,是允许任何管理员登陆后可用的,无需配置权限节点

    static protected $allow = array('rss','version_check');





菜单权限控制
------------

> 菜单权限指登陆后台后可进入的后台区域,这些页面系统安装后即始终存在,所以可以直接在控制器中定义$nodes属性,权限系统将根据该属性
> 自动为不同权限的管理员返回对应权限的后台界面菜单,任何非授权的访问将被拒绝.

### 配置格式

```php
    static protected $nodes= array(

        array('title'=>'权限管理','url'=>'AuthManager/index','group'=>'用户管理',
              'operator'=>array(
                  array('title'=>'编辑','url'=>'AuthManager/editGroup'),
                  array('title'=>'删除','url'=>'AuthManager/changeStatus?method=deleteGroup'),
                  array('title'=>'禁用','url'=>'AuthManager/changeStatus?method=forbidGroup'),
                  array('title'=>'恢复','url'=>'AuthManager/changeStatus?method=resumeGroup'),
              ),
        ),
    );
```

*   配置项目的键必须小写
*   title,url为必选配置,用于生成菜单和执行验证; url配置值格式:`Moudle/Action?param1=value1&param2=value2`
*   group默认值为"default"
*   operator为可选,用于页面打开后页面内的操作按钮的权限,元素只需配置title和url



动态页面权限控制
----------------

> 动态页面指安装系统后,经过后台添加自动生成的页面,例如栏目分类;
> 动态页面权限不能写死在控制器中,而是需要在自己的action执行时,进行权限验证.
> 因此需要首先建立权限关系记录(例如:使用一个表记录用户组id与有权栏目分类id的对应关系)

### 验证流程

访问页面 `>` 获取用户uid `>` 根据uid获取用户组id `>` 读取用户组拥有的相应权限 `>` 执行判断逻辑

### 栏目分类权限验证

> 系统已经内置了栏目分类权限验证的功能,栏目与用户组对应关系存储在`think_auth_category_access`表

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
AuthGroupModel::addToCategory( 123, '2,3,5,8,9' ); 
```


