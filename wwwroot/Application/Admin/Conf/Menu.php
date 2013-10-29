<?php

return array(
    array(
        'title' => '首页',
        'url' => 'Index/index',
    ),
    array(
        'title' => '内容',
        'url' => 'Article/mydocument',
        'operator' => array(
            array(
                'title' => '文档列表',
                'url' => 'article/index',
                'group' => '内容',
                'hide' => true,
                'operator' => array(
                    //权限管理页面的按钮
                    array('title' => '新增', 'url' => 'article/add'),
                    array('title' => '编辑', 'url' => 'article/edit'),
                    array('title' => '改变状态', 'url' => 'article/setStatus'),
                    array('title' => '保存', 'url' => 'article/update'),
                    array('title' => '保存草稿', 'url' => 'article/autoSave'),
                    array('title' => '移动', 'url' => 'article/move'),
                    array('title' => '复制', 'url' => 'article/copy'),
                    array('title' => '粘贴', 'url' => 'article/paste'),
                    array('title' => '导入', 'url' => 'article/batchOperate'),
                ),
            ),
            array(
                'title' => '回收站', 'url' => 'article/recycle',
                'group' => '内容',
                'operator' => array(
                    //权限管理页面的按钮
                    array('title' => '还原', 'url' => 'article/permit'),
                    array('title' => '清空', 'url' => 'article/clear'),
                ),
            ),
        ),
    ),
    array(
        'title' => '用户',
        'url' => 'User/index',
        'operator' => array(
            array(
                'title' => '用户信息',
                'url' => 'User/index',
                'operator' => array(
                    //权限管理页面的五种按钮
                    array('title' => '新增用户', 'url' => 'User/add', 'tip' => '添加新用户'),
                ),
                'group' => '用户管理',
            ),
            array(
                'title' => '用户行为',
                'url' => 'User/action',
                'group' => '用户管理',
                'operator' => array(
                    //权限管理页面的五种按钮
                    array('title' => '新增用户行为', 'url' => 'User/addAction', 'tip' => '"用户->用户行为"中的新增'),
                    array('title' => '编辑用户行为', 'url' => 'User/editAction', 'tip' => '"用户->用户行为"点击标题进行编辑'),
                    array('title' => '保存用户行为', 'url' => 'User/saveAction', 'tip' => '"用户->用户行为"保存编辑和新增的用户行为'),
                    array('title' => '变更行为状态', 'url' => 'User/setStatus', 'tip' => '"用户->用户行为"中的启用,禁用和删除权限'),
                    array('title' => '禁用会员', 'url' => 'User/changeStatus?method=forbidUser', 'tip' => '"用户->用户信息"中的禁用'),
                    array('title' => '启用会员', 'url' => 'User/changeStatus?method=resumeUser', 'tip' => '"用户->用户信息"中的启用'),
                    array('title' => '删除会员', 'url' => 'User/changeStatus?method=deleteUser', 'tip' => '"用户->用户信息"中的删除'),
                ),
            ),
            array(
                'title' => '权限管理',
                'url' => 'AuthManager/index',
                'group' => '用户管理',
                'operator' => array(
                    //权限管理页面的五种按钮
                    array('title' => '删除', 'url' => 'AuthManager/changeStatus?method=deleteGroup', 'tip' => '删除用户组'),
                    array('title' => '禁用', 'url' => 'AuthManager/changeStatus?method=forbidGroup', 'tip' => '禁用用户组'),
                    array('title' => '恢复', 'url' => 'AuthManager/changeStatus?method=resumeGroup', 'tip' => '恢复已禁用的用户组'),
                    array('title' => '新增', 'url' => 'AuthManager/createGroup', 'tip' => '创建新的用户组'),
                    array('title' => '编辑', 'url' => 'AuthManager/editGroup', 'tip' => '编辑用户组名称和描述'),
                    array('title' => '保存用户组', 'url' => 'AuthManager/writeGroup', 'tip' => '新增和编辑用户组的"保存"按钮'),
                    array('title' => '授权', 'url' => 'AuthManager/group', 'tip' => '"后台 \ 用户 \ 用户信息"列表页的"授权"操作按钮,用于设置用户所属用户组'),
                    array('title' => '访问授权', 'url' => 'AuthManager/access', 'tip' => '"后台 \ 用户 \ 权限管理"列表页的"访问授权"操作按钮'),
                    array('title' => '成员授权', 'url' => 'AuthManager/user', 'tip' => '"后台 \ 用户 \ 权限管理"列表页的"成员授权"操作按钮'),
                    array('title' => '解除授权', 'url' => 'AuthManager/removeFromGroup', 'tip' => '"成员授权"列表页内的解除授权操作按钮'),
                    array('title' => '保存成员授权', 'url' => 'AuthManager/addToGroup', 'tip' => '"用户信息"列表页"授权"时的"保存"按钮和"成员授权"里右上角的"添加"按钮)'),
                    array('title' => '分类授权', 'url' => 'AuthManager/category', 'tip' => '"后台 \ 用户 \ 权限管理"列表页的"分类授权"操作按钮'),
                    array('title' => '保存分类授权', 'url' => 'AuthManager/addToCategory', 'tip' => '"分类授权"页面的"保存"按钮'),
                    array('title' => '模型授权', 'url' => 'AuthManager/modelauth', 'tip' => '"后台 \ 用户 \ 权限管理"列表页的"模型授权"操作按钮'),
                    array('title' => '保存模型授权', 'url' => 'AuthManager/addToModel', 'tip' => '"分类授权"页面的"保存"按钮'),
                ),
            ),
        )
    ),
    array(
        'title' => '扩展',
        'url' => 'Addons/index',
        'operator' => array(
            array(
                'title' => '插件管理', 'url' => 'Addons/index', 'group' => '扩展',
                'operator' => array(
                    //权限管理页面的五种按钮
                    array('title' => '创建', 'url' => 'Addons/create', 'tip' => '服务器上创建插件结构向导'),
                    array('title' => '检测创建', 'url' => 'Addons/checkForm', 'tip' => '检测插件是否可以创建'),
                    array('title' => '预览', 'url' => 'Addons/preview', 'tip' => '预览插件定义类文件'),
                    array('title' => '快速生成插件', 'url' => 'Addons/build', 'tip' => '开始生成插件结构'),
                    array('title' => '设置', 'url' => 'Addons/config', 'tip' => '设置插件配置'),
                    array('title' => '禁用', 'url' => 'Addons/disable', 'tip' => '禁用插件'),
                    array('title' => '启用', 'url' => 'Addons/enable', 'tip' => '启用插件'),
                    array('title' => '安装', 'url' => 'Addons/install', 'tip' => '安装插件'),
                    array('title' => '卸载', 'url' => 'Addons/uninstall', 'tip' => '卸载插件'),
                    array('title' => '更新配置', 'url' => 'Addons/saveconfig', 'tip' => '更新插件配置处理'),
                    array('title' => '插件后台列表', 'url' => 'Addons/adminList'),
                    array('title' => 'URL方式访问插件', 'url' => 'Addons/execute', 'tip' => '控制是否有权限通过url访问插件控制器方法')
                ),
            ),
            array('title' => '钩子管理', 'url' => 'Addons/hooks', 'group' => '扩展'),
            array(
                'title' => '模型管理',
                'url' => 'Model/index',
                'group' => '扩展',
                'operator' => array(
                    //权限管理页面的五种按钮
                    array('title' => '新增', 'url' => 'model/add'),
                    array('title' => '编辑', 'url' => 'model/edit'),
                    array('title' => '改变状态', 'url' => 'model/setStatus'),
                    array('title' => '保存数据', 'url' => 'model/update'),
                ),
            ),
            array(
                'title' => '属性管理',
                'url' => 'Attribute/index',
                'group' => '扩展',
                'operator' => array(
                    //权限管理页面的五种按钮
                    array('title' => '新增', 'url' => 'Attribute/add'),
                    array('title' => '编辑', 'url' => 'Attribute/edit'),
                    array('title' => '改变状态', 'url' => 'Attribute/setStatus'),
                    array('title' => '保存数据', 'url' => 'Attribute/update'),
                ),
                'hide' => true,
            ),
        )
    ),
    array(
        'title' => '系统',
        'url' => 'Config/group',
        'operator' => array(
            array('title' => '网站设置', 'url' => 'Config/group', 'group' => '系统设置'),
            array('title' => '配置管理', 'url' => 'Config/index', 'group' => '系统设置',
                'operator' => array(
                    array('title' => '编辑', 'url' => 'Config/edit', 'tip' => '新增编辑和保存配置'),
                    array('title' => '删除', 'url' => 'Config/del', 'tip' => '删除配置'),
                    array('title' => '新增', 'url' => 'Config/add', 'tip' => '新增配置'),
                    array('title' => '保存', 'url' => 'Config/save', 'tip' => '保存配置'),
                ),
            ),
            array('title' => '后台菜单管理', 'url' => 'Config/menu', 'group' => '系统设置'),
            array(
                'title' => '导航管理', 'url' => 'Channel/index', 'group' => '导航栏目设置',
                'operator' => array(
                    array('title' => '新增', 'url' => 'Channel/add'),
                    array('title' => '编辑', 'url' => 'Channel/edit'),
                    array('title' => '删除', 'url' => 'Channel/del'),
                )
            ),
            /* 导航栏目设置 */
            array( 'title' => '分类管理', 'url' => 'Category/index', 'group' => '导航栏目设置',
                'operator'=>array(
                    array('title'=>'编辑','url'=>'Category/edit','tip'=>'编辑和保存栏目分类'),
                    array('title'=>'新增','url'=>'Category/add','tip'=>'新增栏目分类'),
                    array('title'=>'删除','url'=>'Category/remove','tip'=>'删除栏目分类'),
                    array('title'=>'移动','url'=>'Category/move','tip'=>'移动栏目分类'),
                    array('title'=>'合并','url'=>'Category/merge','tip'=>'合并栏目分类'),
                ),
            ),
            array(
                'title' => '备份数据库', 'url' => 'Database/index?type=export', 'group' => '数据备份',
                'operator' => array(
                    array('title' => '备份', 'url' => 'Database/export', 'tip' => '备份数据库'),
                    array('title' => '优化表', 'url' => 'Database/optimize', 'tip' => '优化数据表'),
                    array('title' => '修复表', 'url' => 'Database/repair', 'tip' => '修复数据表'),
                )),
            array(
                'title' => '还原数据库', 'url' => 'Database/index?type=import', 'group' => '数据备份',
                'operator' => array(
                    array('title' => '恢复', 'url' => 'Database/import', 'tip' => '数据库恢复'),
                    array('title' => '删除', 'url' => 'Database/del', 'tip' => '删除备份文件'),
                )
            ),
        )
    ),
    array(
        'title' => '其他',
        'url' => 'other',
        'hide' => true
    ), //专门放置不需要显示在任何菜单中的节点
);
?>