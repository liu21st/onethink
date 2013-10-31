/*
Navicat MySQL Data Transfer

Source Server         : 200
Source Server Version : 50520
Source Host           : 192.168.1.200:3306
Source Database       : onethink_v01

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2013-10-31 11:49:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for onethink_menu
-- ----------------------------
DROP TABLE IF EXISTS `onethink_menu`;
CREATE TABLE `onethink_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏 1-是0-否',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT NULL COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见 1-开发者模式可见0-开发者模式不可见',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of onethink_menu
-- ----------------------------
INSERT INTO `onethink_menu` VALUES ('1', '首页', '0', '0', 'Index/index', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('2', '内容', '0', '0', 'Article/mydocument', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('3', '文档列表', '2', '0', 'article/index', '0', '', '内容', '0');
INSERT INTO `onethink_menu` VALUES ('4', '新增', '3', '0', 'article/add', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('5', '编辑', '3', '0', 'article/edit', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('6', '改变状态', '3', '0', 'article/setStatus', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('7', '保存', '3', '0', 'article/update', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('8', '保存草稿', '3', '0', 'article/autoSave', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('9', '移动', '3', '0', 'article/move', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('10', '复制', '3', '0', 'article/copy', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('11', '粘贴', '3', '0', 'article/paste', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('12', '导入', '3', '0', 'article/batchOperate', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('13', '回收站', '2', '0', 'article/recycle', '0', '', '内容', '0');
INSERT INTO `onethink_menu` VALUES ('14', '还原', '13', '0', 'article/permit', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('15', '清空', '13', '0', 'article/clear', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('16', '用户', '0', '0', 'User/index', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('17', '用户信息', '16', '0', 'User/index', '0', '', '用户管理', '0');
INSERT INTO `onethink_menu` VALUES ('18', '新增用户', '17', '0', 'User/add', '0', '添加新用户', null, '0');
INSERT INTO `onethink_menu` VALUES ('19', '用户行为', '16', '0', 'User/action', '0', '', '用户管理', '0');
INSERT INTO `onethink_menu` VALUES ('20', '新增用户行为', '19', '0', 'User/addAction', '0', '\"用户->用户行为\"中的新增', null, '0');
INSERT INTO `onethink_menu` VALUES ('21', '编辑用户行为', '19', '0', 'User/editAction', '0', '\"用户->用户行为\"点击标题进行编辑', null, '0');
INSERT INTO `onethink_menu` VALUES ('22', '保存用户行为', '19', '0', 'User/saveAction', '0', '\"用户->用户行为\"保存编辑和新增的用户行为', null, '0');
INSERT INTO `onethink_menu` VALUES ('23', '变更行为状态', '19', '0', 'User/setStatus', '0', '\"用户->用户行为\"中的启用,禁用和删除权限', null, '0');
INSERT INTO `onethink_menu` VALUES ('24', '禁用会员', '19', '0', 'User/changeStatus?method=forbidUser', '0', '\"用户->用户信息\"中的禁用', null, '0');
INSERT INTO `onethink_menu` VALUES ('25', '启用会员', '19', '0', 'User/changeStatus?method=resumeUser', '0', '\"用户->用户信息\"中的启用', null, '0');
INSERT INTO `onethink_menu` VALUES ('26', '删除会员', '19', '0', 'User/changeStatus?method=deleteUser', '0', '\"用户->用户信息\"中的删除', null, '0');
INSERT INTO `onethink_menu` VALUES ('27', '权限管理', '16', '0', 'AuthManager/index', '0', '', '', '0');
INSERT INTO `onethink_menu` VALUES ('28', '删除', '27', '0', 'AuthManager/changeStatus?method=deleteGroup', '0', '删除用户组', null, '0');
INSERT INTO `onethink_menu` VALUES ('29', '禁用', '27', '0', 'AuthManager/changeStatus?method=forbidGroup', '0', '禁用用户组', null, '0');
INSERT INTO `onethink_menu` VALUES ('30', '恢复', '27', '0', 'AuthManager/changeStatus?method=resumeGroup', '0', '恢复已禁用的用户组', null, '0');
INSERT INTO `onethink_menu` VALUES ('31', '新增', '27', '0', 'AuthManager/createGroup', '0', '创建新的用户组', null, '0');
INSERT INTO `onethink_menu` VALUES ('32', '编辑', '27', '0', 'AuthManager/editGroup', '0', '编辑用户组名称和描述', null, '0');
INSERT INTO `onethink_menu` VALUES ('33', '保存用户组', '27', '0', 'AuthManager/writeGroup', '0', '新增和编辑用户组的\"保存\"按钮', null, '0');
INSERT INTO `onethink_menu` VALUES ('34', '授权', '27', '0', 'AuthManager/group', '0', '\"后台 \\ 用户 \\ 用户信息\"列表页的\"授权\"操作按钮,用于设置用户所属用户组', null, '0');
INSERT INTO `onethink_menu` VALUES ('35', '访问授权', '27', '0', 'AuthManager/access', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"访问授权\"操作按钮', null, '0');
INSERT INTO `onethink_menu` VALUES ('36', '成员授权', '27', '0', 'AuthManager/user', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"成员授权\"操作按钮', null, '0');
INSERT INTO `onethink_menu` VALUES ('37', '解除授权', '27', '0', 'AuthManager/removeFromGroup', '0', '\"成员授权\"列表页内的解除授权操作按钮', null, '0');
INSERT INTO `onethink_menu` VALUES ('38', '保存成员授权', '27', '0', 'AuthManager/addToGroup', '0', '\"用户信息\"列表页\"授权\"时的\"保存\"按钮和\"成员授权\"里右上角的\"添加\"按钮)', null, '0');
INSERT INTO `onethink_menu` VALUES ('39', '分类授权', '27', '0', 'AuthManager/category', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"分类授权\"操作按钮', null, '0');
INSERT INTO `onethink_menu` VALUES ('40', '保存分类授权', '27', '0', 'AuthManager/addToCategory', '0', '\"分类授权\"页面的\"保存\"按钮', null, '0');
INSERT INTO `onethink_menu` VALUES ('41', '模型授权', '27', '0', 'AuthManager/modelauth', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"模型授权\"操作按钮', null, '0');
INSERT INTO `onethink_menu` VALUES ('42', '保存模型授权', '27', '0', 'AuthManager/addToModel', '0', '\"分类授权\"页面的\"保存\"按钮', null, '0');
INSERT INTO `onethink_menu` VALUES ('43', '扩展', '0', '5', 'Addons/index', '0', '', '', '0');
INSERT INTO `onethink_menu` VALUES ('44', '插件管理', '43', '0', 'Addons/index', '0', '', '扩展', '0');
INSERT INTO `onethink_menu` VALUES ('45', '创建', '44', '0', 'Addons/create', '0', '服务器上创建插件结构向导', null, '0');
INSERT INTO `onethink_menu` VALUES ('46', '检测创建', '44', '0', 'Addons/checkForm', '0', '检测插件是否可以创建', null, '0');
INSERT INTO `onethink_menu` VALUES ('47', '预览', '44', '0', 'Addons/preview', '0', '预览插件定义类文件', null, '0');
INSERT INTO `onethink_menu` VALUES ('48', '快速生成插件', '44', '0', 'Addons/build', '0', '开始生成插件结构', null, '0');
INSERT INTO `onethink_menu` VALUES ('49', '设置', '44', '0', 'Addons/config', '0', '设置插件配置', null, '0');
INSERT INTO `onethink_menu` VALUES ('50', '禁用', '44', '0', 'Addons/disable', '0', '禁用插件', null, '0');
INSERT INTO `onethink_menu` VALUES ('51', '启用', '44', '0', 'Addons/enable', '0', '启用插件', null, '0');
INSERT INTO `onethink_menu` VALUES ('52', '安装', '44', '0', 'Addons/install', '0', '安装插件', null, '0');
INSERT INTO `onethink_menu` VALUES ('53', '卸载', '44', '0', 'Addons/uninstall', '0', '卸载插件', null, '0');
INSERT INTO `onethink_menu` VALUES ('54', '更新配置', '44', '0', 'Addons/saveconfig', '0', '更新插件配置处理', null, '0');
INSERT INTO `onethink_menu` VALUES ('55', '插件后台列表', '44', '0', 'Addons/adminList', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('56', 'URL方式访问插件', '44', '0', 'Addons/execute', '0', '控制是否有权限通过url访问插件控制器方法', null, '0');
INSERT INTO `onethink_menu` VALUES ('57', '钩子管理', '43', '0', 'Addons/hooks', '0', '', '扩展', '0');
INSERT INTO `onethink_menu` VALUES ('58', '模型管理', '68', '0', 'Model/index', '0', '', '系统设置', '0');
INSERT INTO `onethink_menu` VALUES ('59', '新增', '58', '0', 'model/add', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('60', '编辑', '58', '0', 'model/edit', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('61', '改变状态', '58', '0', 'model/setStatus', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('62', '保存数据', '58', '0', 'model/update', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('63', '属性管理', '68', '0', 'Attribute/index', '1', '', '', '0');
INSERT INTO `onethink_menu` VALUES ('64', '新增', '63', '0', 'Attribute/add', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('65', '编辑', '63', '0', 'Attribute/edit', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('66', '改变状态', '63', '0', 'Attribute/setStatus', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('67', '保存数据', '63', '0', 'Attribute/update', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('68', '系统', '0', '0', 'Config/group', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('69', '网站设置', '68', '0', 'Config/group', '0', '', '系统设置', '0');
INSERT INTO `onethink_menu` VALUES ('70', '配置管理', '68', '0', 'Config/index', '0', '', '系统设置', '0');
INSERT INTO `onethink_menu` VALUES ('71', '编辑', '70', '0', 'Config/edit', '0', '新增编辑和保存配置', null, '0');
INSERT INTO `onethink_menu` VALUES ('72', '删除', '70', '0', 'Config/del', '0', '删除配置', null, '0');
INSERT INTO `onethink_menu` VALUES ('73', '新增', '70', '0', 'Config/add', '0', '新增配置', null, '0');
INSERT INTO `onethink_menu` VALUES ('74', '保存', '70', '0', 'Config/save', '0', '保存配置', null, '0');
INSERT INTO `onethink_menu` VALUES ('75', '菜单管理', '68', '0', 'Menu/index', '0', '', '系统设置', '0');
INSERT INTO `onethink_menu` VALUES ('76', '导航管理', '68', '0', 'Channel/index', '0', '', '系统设置', '0');
INSERT INTO `onethink_menu` VALUES ('77', '新增', '76', '0', 'Channel/add', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('78', '编辑', '76', '0', 'Channel/edit', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('79', '删除', '76', '0', 'Channel/del', '0', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('80', '分类管理', '68', '0', 'Category/index', '0', '', '系统设置', '0');
INSERT INTO `onethink_menu` VALUES ('81', '编辑', '80', '0', 'Category/edit', '0', '编辑和保存栏目分类', null, '0');
INSERT INTO `onethink_menu` VALUES ('82', '新增', '80', '0', 'Category/add', '0', '新增栏目分类', null, '0');
INSERT INTO `onethink_menu` VALUES ('83', '删除', '80', '0', 'Category/remove', '0', '删除栏目分类', null, '0');
INSERT INTO `onethink_menu` VALUES ('84', '移动', '80', '0', 'Category/move', '0', '移动栏目分类', null, '0');
INSERT INTO `onethink_menu` VALUES ('85', '合并', '80', '0', 'Category/merge', '0', '合并栏目分类', null, '0');
INSERT INTO `onethink_menu` VALUES ('86', '备份数据库', '68', '0', 'Database/index?type=export', '0', '', '数据备份', '0');
INSERT INTO `onethink_menu` VALUES ('87', '备份', '86', '0', 'Database/export', '0', '备份数据库', null, '0');
INSERT INTO `onethink_menu` VALUES ('88', '优化表', '86', '0', 'Database/optimize', '0', '优化数据表', null, '0');
INSERT INTO `onethink_menu` VALUES ('89', '修复表', '86', '0', 'Database/repair', '0', '修复数据表', null, '0');
INSERT INTO `onethink_menu` VALUES ('90', '还原数据库', '68', '0', 'Database/index?type=import', '0', '', '数据备份', '0');
INSERT INTO `onethink_menu` VALUES ('91', '恢复', '90', '0', 'Database/import', '0', '数据库恢复', null, '0');
INSERT INTO `onethink_menu` VALUES ('92', '删除', '90', '0', 'Database/del', '0', '删除备份文件', null, '0');
INSERT INTO `onethink_menu` VALUES ('93', '其他', '0', '0', 'other', '1', '', null, '0');
INSERT INTO `onethink_menu` VALUES ('96', '新增', '75', '0', 'Menu/add', '0', '', '系统设置', '0');
INSERT INTO `onethink_menu` VALUES ('98', '编辑', '75', '0', 'Menu/edit', '0', '', '', '0');
INSERT INTO `onethink_menu` VALUES ('102', '应用', '0', '0', 'Think/lists?model=article', '0', '', '', '0');
INSERT INTO `onethink_menu` VALUES ('103', '文章管理', '102', '2', 'Think/lists?model=article', '0', '', '', '0');
INSERT INTO `onethink_menu` VALUES ('104', '下载管理', '102', '0', 'Think/lists?model=download', '0', '', '', '0');
INSERT INTO `onethink_menu` VALUES ('105', '配置管理', '102', '0', 'Think/lists?model=config', '0', '', '', '0');
INSERT INTO `onethink_menu` VALUES ('106', '行为日志', '16', '0', 'Action/actionlog', '0', '', '行为管理', '0');
