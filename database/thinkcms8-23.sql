/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.200
Source Server Version : 50520
Source Host           : 192.168.1.200:3306
Source Database       : thinkcms

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2013-08-23 17:49:46
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `think_action`
-- ----------------------------
DROP TABLE IF EXISTS `think_action`;
CREATE TABLE `think_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '用于生成行为日志（%user%替换为执行行为的人，%time%替换为执行行为时间）',
  `rule` text NOT NULL COMMENT '行为规则',
  `status` tinyint(2) NOT NULL COMMENT '状态（-1：已删除，0：禁用，1：正常）',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

-- ----------------------------
-- Records of think_action
-- ----------------------------
INSERT INTO `think_action` VALUES ('1', 'user_login', '用户登录，积分+10，每天一次', '$user在$time登录', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:9-2+3+score*1/1|cycle:24|max:1;', '1', '1377250620');

-- ----------------------------
-- Table structure for `think_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `think_action_log`;
CREATE TABLE `think_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` char(50) NOT NULL COMMENT '触发行为的表',
  `record_id` int(10) NOT NULL COMMENT '触发行为的数据id',
  `create_time` int(10) unsigned NOT NULL COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_id_ix` (`action_id`) USING BTREE,
  KEY `user_id_ix` (`user_id`) USING BTREE,
  KEY `action_ip_ix` (`action_ip`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of think_action_log
-- ----------------------------
INSERT INTO `think_action_log` VALUES ('21', '1', '12', '2130706433', 'user', '12', '1377239687');
INSERT INTO `think_action_log` VALUES ('22', '1', '12', '2130706433', 'user', '12', '1377239691');
INSERT INTO `think_action_log` VALUES ('23', '1', '12', '2130706433', 'user', '12', '1377239695');
INSERT INTO `think_action_log` VALUES ('24', '1', '12', '2130706433', 'user', '12', '1377248456');
INSERT INTO `think_action_log` VALUES ('25', '1', '12', '2130706433', 'user', '12', '1377248479');
INSERT INTO `think_action_log` VALUES ('26', '1', '12', '2130706433', 'user', '12', '1377248524');
INSERT INTO `think_action_log` VALUES ('27', '1', '12', '2130706433', 'user', '12', '1377249565');
INSERT INTO `think_action_log` VALUES ('28', '1', '12', '2130706433', 'user', '12', '1377249606');
INSERT INTO `think_action_log` VALUES ('29', '1', '12', '2130706433', 'user', '12', '1377250236');
INSERT INTO `think_action_log` VALUES ('30', '1', '12', '2130706433', 'user', '12', '1377250252');
INSERT INTO `think_action_log` VALUES ('31', '1', '12', '2130706433', 'user', '12', '1377250352');
INSERT INTO `think_action_log` VALUES ('32', '1', '12', '2130706433', 'user', '12', '1377250375');
INSERT INTO `think_action_log` VALUES ('33', '1', '12', '2130706433', 'user', '12', '1377250436');
INSERT INTO `think_action_log` VALUES ('34', '1', '12', '2130706433', 'user', '12', '1377250455');
INSERT INTO `think_action_log` VALUES ('35', '1', '12', '2130706433', 'user', '12', '1377250466');
INSERT INTO `think_action_log` VALUES ('36', '1', '12', '2130706433', 'user', '12', '1377250504');
INSERT INTO `think_action_log` VALUES ('37', '1', '12', '2130706433', 'user', '12', '1377250512');
INSERT INTO `think_action_log` VALUES ('38', '1', '12', '2130706433', 'user', '12', '1377250598');
INSERT INTO `think_action_log` VALUES ('39', '1', '12', '2130706433', 'user', '12', '1377250601');
INSERT INTO `think_action_log` VALUES ('40', '1', '12', '2130706433', 'user', '12', '1377250623');
INSERT INTO `think_action_log` VALUES ('41', '1', '12', '2130706433', 'user', '12', '1377250671');

-- ----------------------------
-- Table structure for `think_addons`
-- ----------------------------
DROP TABLE IF EXISTS `think_addons`;
CREATE TABLE `think_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识，区分大小写',
  `title` varchar(20) NOT NULL COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1-启用 0-禁用 -1-损坏',
  `config` text COMMENT '配置 序列化存放',
  `author` varchar(40) DEFAULT NULL COMMENT '作者',
  `version` varchar(20) DEFAULT NULL COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL COMMENT '安装时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of think_addons
-- ----------------------------
INSERT INTO `think_addons` VALUES ('28', 'Editor', '编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_height\":\"500px\"}', 'thinkphp', '0.1', '1377162178');
INSERT INTO `think_addons` VALUES ('29', 'Attachment', '附件', '用于文档模型上传附件', '1', null, 'thinkphp', '0.1', '1377162437');

-- ----------------------------
-- Table structure for `think_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `think_attachment`;
CREATE TABLE `think_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL COMMENT '附件显示名',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件类型（0-目录，1-外链，2-文件）',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID（0-目录， 大于0-当资源为文件时其值为file_id,当资源为外链时其值为link_id）',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小（当附件为目录或外链时，该值为0）',
  `dir` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '上级目录ID（0-根目录）',
  `sort` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_record_status` (`record_id`,`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='附件表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- ----------------------------
-- Records of think_attachment
-- ----------------------------
INSERT INTO `think_attachment` VALUES ('1', '1', 'upyun_api_doc.pdf', '2', '1', '2', '7', '186603', '0', '0', '1373443268', '1373443268', '1');
INSERT INTO `think_attachment` VALUES ('2', '1', '1725084_1.gif', '2', '2', '4', '5', '323063', '0', '0', '1373859340', '1373859340', '1');
INSERT INTO `think_attachment` VALUES ('3', '10', 'adsense广告位代码.txt', '2', '7', '21', '2', '2365', '0', '0', '1374043875', '1374043875', '1');
INSERT INTO `think_attachment` VALUES ('4', '1', '系统说明文档.docx', '2', '8', '29', '1', '19113', '0', '0', '1376037633', '1376037633', '1');
INSERT INTO `think_attachment` VALUES ('5', '1', '测试文档（2013年8月6日）.docx', '2', '9', '31', '1', '195273', '0', '0', '1376040686', '1376040686', '1');
INSERT INTO `think_attachment` VALUES ('6', '1', '麦当苗儿.docx', '2', '15', '42', '1', '124068', '0', '0', '1377164056', '1377164056', '1');

-- ----------------------------
-- Table structure for `think_auth_category_access`
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_category_access`;
CREATE TABLE `think_auth_category_access` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `category_id` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  UNIQUE KEY `uid_group_id` (`group_id`,`category_id`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

-- ----------------------------
-- Records of think_auth_category_access
-- ----------------------------
INSERT INTO `think_auth_category_access` VALUES ('1', '2');
INSERT INTO `think_auth_category_access` VALUES ('1', '3');
INSERT INTO `think_auth_category_access` VALUES ('1', '8');
INSERT INTO `think_auth_category_access` VALUES ('1', '9');
INSERT INTO `think_auth_category_access` VALUES ('1', '10');

-- ----------------------------
-- Table structure for `think_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_group`;
CREATE TABLE `think_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_auth_group
-- ----------------------------
INSERT INTO `think_auth_group` VALUES ('1', 'admin', '1', '管理员', 'id为1的用户组', '1', '1,1,6,7,8,22,3,3,23,4,4,4,14,5,5,15,16,17,18,19,20,21');
INSERT INTO `think_auth_group` VALUES ('5', 'admin', '1', '内容管理员', '111111111111', '1', '1,6,2,7,8,3,13,4,14,5,16,17,18,19,20,21');
INSERT INTO `think_auth_group` VALUES ('6', 'admin', '1', '测试&gt;&lt;&amp;', '测试', '1', '1,6,2,7,8,22,3,23,13,4,14,5,15,16,17,18,19,20,21');

-- ----------------------------
-- Table structure for `think_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_group_access`;
CREATE TABLE `think_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_auth_group_access
-- ----------------------------
INSERT INTO `think_auth_group_access` VALUES ('1', '1');
INSERT INTO `think_auth_group_access` VALUES ('1', '5');

-- ----------------------------
-- Table structure for `think_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_rule`;
CREATE TABLE `think_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`module`,`name`,`type`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_auth_rule
-- ----------------------------
INSERT INTO `think_auth_rule` VALUES ('1', 'admin', '1', 'Admin/Index/index', '管理首页', '1', '');
INSERT INTO `think_auth_rule` VALUES ('2', 'admin', '1', 'Admin/Article/index', '内容', '1', '');
INSERT INTO `think_auth_rule` VALUES ('3', 'admin', '1', 'Admin/User/index', '用户信息', '1', '');
INSERT INTO `think_auth_rule` VALUES ('4', 'admin', '1', 'Admin/Addons/index', '插件管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('5', 'admin', '1', 'Admin/System/index', '基本设置', '1', '');
INSERT INTO `think_auth_rule` VALUES ('6', 'admin', '1', 'Admin/Index/form', '表单样式', '1', '');
INSERT INTO `think_auth_rule` VALUES ('7', 'admin', '1', 'Admin/Article/index?cate_id=9', '讨论', '1', '');
INSERT INTO `think_auth_rule` VALUES ('8', 'admin', '1', 'Admin/Article/index?cate_id=2', '下载', '1', '');
INSERT INTO `think_auth_rule` VALUES ('9', 'admin', '1', 'Admin/AuthManager/edit', '编辑', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('10', 'admin', '1', 'Admin/AuthManager/delete', '删除', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('11', 'admin', '1', 'Admin/AuthManager/forbid', '禁用', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('12', 'admin', '1', 'Admin/AuthManager/resume', '恢复', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('13', 'admin', '1', 'Admin/AuthManager/index', '权限管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('14', 'admin', '1', 'Admin/Addons/hooks', '钩子管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('15', 'admin', '1', 'Admin/System/index1', '静态规则设置', '1', '');
INSERT INTO `think_auth_rule` VALUES ('16', 'admin', '1', 'Admin/System/index2', 'SEO优化设置', '1', '');
INSERT INTO `think_auth_rule` VALUES ('17', 'admin', '1', 'Admin/System/index3', '导航管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('18', 'admin', '1', 'Admin/System/index4', '分类管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('19', 'admin', '1', 'Admin/System/index5', '数据迁移', '1', '');
INSERT INTO `think_auth_rule` VALUES ('20', 'admin', '1', 'Admin/System/index6', '数据备份/恢复', '1', '');
INSERT INTO `think_auth_rule` VALUES ('21', 'admin', '1', 'Admin/System/index7', '系统日志', '1', '');
INSERT INTO `think_auth_rule` VALUES ('22', 'admin', '1', 'Admin/Article/index?cate_id=10', '框架', '1', '');
INSERT INTO `think_auth_rule` VALUES ('23', 'admin', '1', 'Admin/User/index2', '用户行为', '1', '');
INSERT INTO `think_auth_rule` VALUES ('24', 'admin', '1', 'Admin/User/index1', '权限管理', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('25', 'admin', '1', 'Admin/AuthManager/editGroup', '编辑', '1', '');
INSERT INTO `think_auth_rule` VALUES ('26', 'admin', '1', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '1', '');
INSERT INTO `think_auth_rule` VALUES ('27', 'admin', '1', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '1', '');
INSERT INTO `think_auth_rule` VALUES ('28', 'admin', '1', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '1', '');
INSERT INTO `think_auth_rule` VALUES ('29', 'admin', '1', 'Admin/User/action', '用户行为', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('30', 'admin', '1', 'Admin/User/action1', '用户行为', '-1', '');

-- ----------------------------
-- Table structure for `think_category`
-- ----------------------------
DROP TABLE IF EXISTS `think_category`;
CREATE TABLE `think_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(16) NOT NULL COMMENT '标识',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(3) unsigned NOT NULL COMMENT '列表每页行数',
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `template_index` varchar(100) NOT NULL COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL COMMENT '关联模型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链（0-非外链，大于0-外链ID）',
  `allow_publish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容（0-不允许，1-允许）',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性（0-所有人可见，1-管理员可见，2-不可见）',
  `extend` text NOT NULL COMMENT '扩展设置（JSON数据）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态（-1-删除，0-禁用，1-正常，2-待审核）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='分类表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of think_category
-- ----------------------------
INSERT INTO `think_category` VALUES ('2', 'down', '下载', '0', '10', '10', '', '', '', '', '', '', '2', '0', '0', '1', '', '1372390543', '1372644294', '1');
INSERT INTO `think_category` VALUES ('3', 'extend', '扩展', '0', '20', '10', '', '', '', '', '', '', '1,2', '0', '0', '1', '', '1372390543', '1372408875', '1');
INSERT INTO `think_category` VALUES ('11', 'doc', '文档', '2', '1020', '10', '', '', '', 'Article/Download/lists', '', '', '2', '0', '1', '1', '', '1372390543', '1372644293', '1');
INSERT INTO `think_category` VALUES ('8', 'info', '资讯', '0', '30', '10', '', '', '', '', '', '', '1', '0', '0', '1', '', '1372390543', '1372407973', '1');
INSERT INTO `think_category` VALUES ('9', 'topic', '讨论', '0', '40', '10', '', '', '', '', '', '', '1', '0', '1', '1', '', '1372390543', '1372408840', '1');
INSERT INTO `think_category` VALUES ('10', 'framework', '框架', '2', '1010', '10', '', '', '', 'Article/Download/lists', '', '', '2', '0', '1', '1', '', '1372390543', '1372644293', '1');
INSERT INTO `think_category` VALUES ('12', 'video', '视频', '2', '1030', '10', '', '', '', 'Article/Download/lists', '', '', '2', '0', '1', '1', '', '1372390543', '1372411544', '1');
INSERT INTO `think_category` VALUES ('15', 'ask', '求助交流', '9', '4010', '10', '', '', '', '', 'Article/Article/detail_topic', '', '1', '0', '1', '1', '', '1372390543', '1372408974', '1');
INSERT INTO `think_category` VALUES ('13', 'news', '新闻动态', '8', '3010', '10', '', '', '', '', '', '', '1', '0', '1', '1', '', '1372390543', '1372408893', '1');
INSERT INTO `think_category` VALUES ('14', 'industry', '业界资讯', '8', '3020', '10', '', '', '', '', '', '', '1', '0', '1', '1', '', '1372390543', '1372408898', '1');
INSERT INTO `think_category` VALUES ('16', 'share', '技术分享', '9', '4020', '10', '', '', '', '', 'Article/Article/detail_topic', '', '1', '0', '1', '1', '', '1372390543', '1372408973', '1');
INSERT INTO `think_category` VALUES ('17', 'front', '前端开发', '9', '4030', '10', '', '', '', '', 'Article/Article/detail_topic', '', '1', '0', '1', '1', '', '1372390543', '1372408972', '1');
INSERT INTO `think_category` VALUES ('18', 'engine', '函数', '3', '2010', '10', '', '', '', 'Article/Download/lists', '', '', '1,2', '0', '1', '1', '', '1372390543', '1373860637', '1');
INSERT INTO `think_category` VALUES ('19', 'function', '类库', '3', '2020', '10', '', '', '', 'Article/Download/lists', '', '', '2', '0', '1', '1', '', '1372390543', '1373860638', '1');
INSERT INTO `think_category` VALUES ('20', 'library', '驱动', '3', '2030', '10', '', '', '', 'Article/Download/lists', '', '', '2', '0', '1', '1', '', '1372390543', '1373860639', '1');
INSERT INTO `think_category` VALUES ('22', 'behvior', '行为', '3', '2040', '10', '', '', '', 'Article/Download/lists', '', '', '2', '0', '1', '1', '', '1372412117', '1373860645', '1');

-- ----------------------------
-- Table structure for `think_channel`
-- ----------------------------
DROP TABLE IF EXISTS `think_channel`;
CREATE TABLE `think_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_channel
-- ----------------------------
INSERT INTO `think_channel` VALUES ('1', '0', '首页', 'Index/index', '0', '0', '1');
INSERT INTO `think_channel` VALUES ('2', '0', '下载', 'Article/index?category=down', '0', '0', '1');
INSERT INTO `think_channel` VALUES ('3', '0', '扩展', 'Article/index?category=entend', '0', '0', '1');
INSERT INTO `think_channel` VALUES ('4', '0', '资讯', 'Article/index?category=news', '0', '0', '1');
INSERT INTO `think_channel` VALUES ('5', '0', '讨论', 'Article/index?category=topic', '0', '0', '1');

-- ----------------------------
-- Table structure for `think_document`
-- ----------------------------
DROP TABLE IF EXISTS `think_document`;
CREATE TABLE `think_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` char(40) NOT NULL COMMENT '标识',
  `title` char(80) NOT NULL COMMENT '标题',
  `category_id` int(10) unsigned NOT NULL COMMENT '所属分类',
  `description` char(140) NOT NULL COMMENT '描述',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属ID',
  `model_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容模型ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容类型（0-专辑，1-目录，2-主题，3-段落）',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位(1-列表推荐，2-频道页推荐，4-首页推荐，[同时推荐多个地方相加即可]）',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链（0-非外链，大于0-外链ID）',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面（0-无封面，大于0-封面图片ID）',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性（0-不可见，1-所有人可见）',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截至时间（0-永久有效）',
  `attach` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展统计字段，根据需求自行使用',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态（-1-删除，0-禁用，1-正常，2-待审核）',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`) USING BTREE,
  KEY `idx_category_status` (`category_id`,`status`) USING BTREE,
  KEY `idx_status_type_pid` (`status`,`type`,`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='文档模型基础表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of think_document
-- ----------------------------
INSERT INTO `think_document` VALUES ('1', '1', '11111', 'Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持', '15', 'Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持', '0', '1', '2', '1', '0', '0', '0', '0', '0', '0', '0', '0', '1373443113', '1373443113', '1');
INSERT INTO `think_document` VALUES ('2', '1', 'aaaaabbb', 'ThinkPHP3.1.2核心版', '15', 'ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版', '0', '1', '2', '1', '0', '0', '0', '0', '0', '0', '0', '0', '1373443268', '1373443268', '1');
INSERT INTO `think_document` VALUES ('3', '1', 'aaaaaasdf', 'asdfasdf', '15', 'asdfasdf', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1373859300', '1373859300', '0');
INSERT INTO `think_document` VALUES ('4', '12', 'dsf', '哈哈/index.php/admin/article支持', '9', 'Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及', '0', '1', '2', '6', '0', '0', '1', '0', '0', '0', '0', '0', '1373859340', '1376991939', '0');
INSERT INTO `think_document` VALUES ('5', '1', 'aaaaabbbsss', 'asdfasdfssss', '13', 'Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1373860405', '1373860405', '1');
INSERT INTO `think_document` VALUES ('10', '1', 'asdfasdfasdfddd', '撒旦发射点法', '10', '撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1373881229', '1373881229', '1');
INSERT INTO `think_document` VALUES ('11', '1', 'aaaaaaaaaaaaaaaaaaaaa', '撒旦发射点法', '10', '撒旦发射点法', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1373881685', '1373881685', '0');
INSERT INTO `think_document` VALUES ('12', '1', '', '', '0', '', '4', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1373937066', '1373937066', '1');
INSERT INTO `think_document` VALUES ('13', '1', 'asdfasdfasdfddds', '撒旦发射点法撒旦发射点法撒旦发射点法', '18', '撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1373941059', '1373941059', '1');
INSERT INTO `think_document` VALUES ('14', '1', 'a444444444', '撒旦发射点法撒旦发射点法撒旦发射点法', '22', '撒旦发射点法撒旦发射点法撒旦发射点法', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1373947161', '1373947161', '1');
INSERT INTO `think_document` VALUES ('15', '1', '', '', '0', '', '4', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374024965', '1374024965', '1');
INSERT INTO `think_document` VALUES ('16', '1', 'a444444444sss', '啊撒旦发射点', '16', '啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点', '0', '1', '2', '1', '0', '0', '0', '0', '0', '0', '0', '0', '1374033500', '1374033500', '1');
INSERT INTO `think_document` VALUES ('17', '1', '', '', '0', '', '16', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374033560', '1374033560', '1');
INSERT INTO `think_document` VALUES ('18', '1', '', '', '0', '', '4', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374041538', '1374041538', '1');
INSERT INTO `think_document` VALUES ('19', '1', '', '', '0', '', '4', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374041880', '1374041880', '1');
INSERT INTO `think_document` VALUES ('21', '10', 'test', '测试标题', '15', '测试描述', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374043875', '1374043875', '1');
INSERT INTO `think_document` VALUES ('22', '10', '', '', '0', '', '21', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374043896', '1374043896', '1');
INSERT INTO `think_document` VALUES ('23', '10', '', '', '0', '', '4', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374043927', '1374043927', '1');
INSERT INTO `think_document` VALUES ('24', '10', 'hello', 'alert(\'hello\');', '2', '威威威威alert(\'hello\');', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374044033', '1374044033', '1');
INSERT INTO `think_document` VALUES ('25', '10', 'dddd', 'dfdfalert(\'hello\');', '15', 'testalert(\'hello\');', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374044153', '1374044153', '1');
INSERT INTO `think_document` VALUES ('26', '10', '', '', '0', '', '25', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374044195', '1374044195', '1');
INSERT INTO `think_document` VALUES ('27', '10', '', '', '0', '', '25', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374044210', '1374044210', '1');
INSERT INTO `think_document` VALUES ('28', '10', '', '', '0', '', '21', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1374045133', '1374045133', '1');
INSERT INTO `think_document` VALUES ('29', '1', 'asd', 'asaa', '15', 'asdasd', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1376037633', '1376037633', '1');
INSERT INTO `think_document` VALUES ('31', '1', 'doc', '测试钩子', '15', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1376040686', '1376040686', '1');
INSERT INTO `think_document` VALUES ('32', '11', 'test_markdown', '测试markdown', '15', '测试markdown', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1376901245', '1376901245', '1');
INSERT INTO `think_document` VALUES ('33', '12', 'procoop', '申请认证', '10', 'adfasdfsdfa', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1376968596', '1377065672', '1');
INSERT INTO `think_document` VALUES ('34', '12', 'qiuzhi', 'afasf', '10', '上的风格', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1376970492', '1376970492', '-1');
INSERT INTO `think_document` VALUES ('35', '0', 'asdf', '阿斯蒂芬', '9', '阿斯蒂芬', '0', '1', '2', '6', '0', '0', '1', '0', '0', '0', '0', '0', '1377074318', '1377074318', '1');
INSERT INTO `think_document` VALUES ('38', '0', 'hj', '30.ThinkPHP', '10', '阿萨德发生阿萨德发生阿', '0', '2', '2', '0', '0', '0', '1', '0', '0', '0', '0', '0', '1377075559', '1377075559', '1');
INSERT INTO `think_document` VALUES ('39', '0', 'release', '回复：急急急，', '9', '和法国恢复', '0', '1', '2', '3', '0', '0', '1', '0', '0', '0', '0', '0', '1377150467', '1377150467', '1');
INSERT INTO `think_document` VALUES ('40', '1', 'a444444444sssfff', '     首页     下载     扩展     资讯     讨论  搜索', '15', '\r\n    首页\r\n    下载\r\n    扩展\r\n    资讯\r\n    讨论\r\n\r\n搜索\r\n', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1377163670', '1377163670', '1');
INSERT INTO `think_document` VALUES ('41', '1', 'a444444444ssssssssssss', 'asdfsadfasd', '15', 'asdfsadffasdf', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1377163907', '1377163907', '1');
INSERT INTO `think_document` VALUES ('42', '1', 'asdfsadfsdfsadf', 'asdfasdfasdf', '15', 'asdfasdf', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1377164056', '1377164056', '1');

-- ----------------------------
-- Table structure for `think_document_model`
-- ----------------------------
DROP TABLE IF EXISTS `think_document_model`;
CREATE TABLE `think_document_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(16) NOT NULL COMMENT '模型标识',
  `title` char(16) NOT NULL COMMENT '模型名称',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='文档模型表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- ----------------------------
-- Records of think_document_model
-- ----------------------------
INSERT INTO `think_document_model` VALUES ('1', 'Article', '文章', '0', '0', '1');
INSERT INTO `think_document_model` VALUES ('2', 'Download', '下载', '0', '0', '1');
INSERT INTO `think_document_model` VALUES ('3', 'Application', '应用', '0', '0', '1');

-- ----------------------------
-- Table structure for `think_document_model_application`
-- ----------------------------
DROP TABLE IF EXISTS `think_document_model_application`;
CREATE TABLE `think_document_model_application` (
  `id` int(10) NOT NULL,
  `version` char(10) NOT NULL DEFAULT '' COMMENT 'TP框架版本号',
  `content` text COMMENT '内容',
  `index_url` char(255) NOT NULL DEFAULT '' COMMENT '应用主页',
  `down_url` char(255) NOT NULL DEFAULT '' COMMENT '下载地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_document_model_application
-- ----------------------------

-- ----------------------------
-- Table structure for `think_document_model_application_screenshot`
-- ----------------------------
DROP TABLE IF EXISTS `think_document_model_application_screenshot`;
CREATE TABLE `think_document_model_application_screenshot` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `application_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '对应的应用id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_document_model_application_screenshot
-- ----------------------------

-- ----------------------------
-- Table structure for `think_document_model_article`
-- ----------------------------
DROP TABLE IF EXISTS `think_document_model_article`;
CREATE TABLE `think_document_model_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型（0-html,1-ubb,2-markdown）',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL COMMENT '详情页显示模板',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表\r\n@author   麦当苗儿\r\n@version  2013-05-24';

-- ----------------------------
-- Records of think_document_model_article
-- ----------------------------
INSERT INTO `think_document_model_article` VALUES ('1', '0', 'Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持', '');
INSERT INTO `think_document_model_article` VALUES ('2', '0', 'ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版', '');
INSERT INTO `think_document_model_article` VALUES ('3', '0', 'asdfasdf', '');
INSERT INTO `think_document_model_article` VALUES ('4', '0', 'Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持v', '');
INSERT INTO `think_document_model_article` VALUES ('5', '0', 'Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持v', '');
INSERT INTO `think_document_model_article` VALUES ('12', '0', 'URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持v', '');
INSERT INTO `think_document_model_article` VALUES ('15', '0', 'URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持v', '');
INSERT INTO `think_document_model_article` VALUES ('16', '0', '啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点', '');
INSERT INTO `think_document_model_article` VALUES ('17', '0', '$id[\'category_id\']$id[\'category_id\']$id[\'category_id\']$id[\'category_id\']', '');
INSERT INTO `think_document_model_article` VALUES ('18', '0', '啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点啊撒旦发射点', '');
INSERT INTO `think_document_model_article` VALUES ('19', '0', '阿朵发送到分', '');
INSERT INTO `think_document_model_article` VALUES ('21', '0', '测试内容', '');
INSERT INTO `think_document_model_article` VALUES ('22', '0', '测试回复', '');
INSERT INTO `think_document_model_article` VALUES ('23', '0', '的饭店反对法地方', '');
INSERT INTO `think_document_model_article` VALUES ('24', '0', 'alert(\'hello\');', '');
INSERT INTO `think_document_model_article` VALUES ('25', '0', 'testalert(\'hello\');', '');
INSERT INTO `think_document_model_article` VALUES ('26', '0', 'alert(\'hello\');dfdf', '');
INSERT INTO `think_document_model_article` VALUES ('27', '0', '343434', '');
INSERT INTO `think_document_model_article` VALUES ('28', '0', '77777', '');
INSERT INTO `think_document_model_article` VALUES ('29', '0', 'asdasd', '');
INSERT INTO `think_document_model_article` VALUES ('31', '0', '啊实打实', '');
INSERT INTO `think_document_model_article` VALUES ('32', '2', '**请问请问**\r\n\r\n## **请问**\r\n\r\n1. 王企鹅去\r\n1. 请问\r\n', '');
INSERT INTO `think_document_model_article` VALUES ('35', '0', '阿斯蒂芬', '');
INSERT INTO `think_document_model_article` VALUES ('39', '0', '放到', '');
INSERT INTO `think_document_model_article` VALUES ('40', '0', '&lt;ul class=&quot;main-nav&quot;&gt;\r\n	&lt;li class=&quot;current&quot;&gt;\r\n		&lt;a href=&quot;http://thinkcms.cn/&quot;&gt;首页&lt;/a&gt;\r\n	&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;a href=&quot;http://thinkcms.cn/index.php/home/article/index/category/down.html&quot;&gt;下载&lt;/a&gt;\r\n	&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;a href=&quot;http://thinkcms.cn/index.php/home/article/index/category/extend.html&quot;&gt;扩展&lt;/a&gt;\r\n	&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;a href=&quot;http://thinkcms.cn/index.php/home/article/index/category/info.html&quot;&gt;资讯&lt;/a&gt;\r\n	&lt;/li&gt;\r\n	&lt;li&gt;\r\n		&lt;a href=&quot;http://thinkcms.cn/index.php/home/article/index/category/topic.html&quot;&gt;讨论&lt;/a&gt;\r\n	&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;div class=&quot;header-bar&quot;&gt;\r\n	&lt;div class=&quot;entrance&quot;&gt;\r\n		&lt;a class=&quot;set-ic&quot; href=&quot;http://thinkcms.cn/index.php/home/article/edit/model/1/category/ask.html#&quot;&gt; &lt;img src=&quot;http://thinkcms.cn/Public/Home/images/temp/36_36.png&quot; alt=&quot;&quot; /&gt; &lt;/a&gt; \r\n	&lt;/div&gt;\r\n&lt;a class=&quot;search-btn&quot;&gt;搜索&lt;/a&gt; \r\n&lt;/div&gt;', '');
INSERT INTO `think_document_model_article` VALUES ('41', '0', 'asdfasdf', '');
INSERT INTO `think_document_model_article` VALUES ('42', '0', 'sdf', '');

-- ----------------------------
-- Table structure for `think_document_model_download`
-- ----------------------------
DROP TABLE IF EXISTS `think_document_model_download`;
CREATE TABLE `think_document_model_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型（0-html,1-ubb,2-markdown）',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表\r\n@author   麦当苗儿\r\n@version  2013-05-24';

-- ----------------------------
-- Records of think_document_model_download
-- ----------------------------
INSERT INTO `think_document_model_download` VALUES ('10', '0', '撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法v', '', '3', '3', '405941');
INSERT INTO `think_document_model_download` VALUES ('11', '0', '撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法', '', '5', '4', '2542');
INSERT INTO `think_document_model_download` VALUES ('13', '0', '撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法', '', '6', '1', '483328');
INSERT INTO `think_document_model_download` VALUES ('14', '0', '撒旦发射点法撒旦发射点法撒旦发射点法', '', '3', '1', '405941');
INSERT INTO `think_document_model_download` VALUES ('33', '0', 'asdfasdfasdfsdf', '', '14', '6', '27180');
INSERT INTO `think_document_model_download` VALUES ('38', '0', 'asdfsdf', '', '12', '1', '4536');

-- ----------------------------
-- Table structure for `think_file`
-- ----------------------------
DROP TABLE IF EXISTS `think_file`;
CREATE TABLE `think_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` char(30) NOT NULL COMMENT '原始文件名',
  `savename` char(20) NOT NULL COMMENT '保存名称',
  `savepath` char(30) NOT NULL COMMENT '文件保存路径',
  `ext` char(5) NOT NULL COMMENT '文件后缀',
  `mime` char(40) NOT NULL COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL COMMENT '文件md5',
  `sha1` char(40) NOT NULL COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置（0-本地，1-FTP）',
  `create_time` int(10) unsigned NOT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='文件表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of think_file
-- ----------------------------
INSERT INTO `think_file` VALUES ('1', 'upyun_api_doc.pdf', '51dd1424d10d8.pdf', '2013-07-10/', 'pdf', 'application/octet-stream', '186603', '44385f08f92c3279c04d16d35bc3c95a', 'a65897adf52a3b7284761e288eed67cb8996366d', '0', '1373443108');
INSERT INTO `think_file` VALUES ('2', '1725084_1.gif', '51e36e078dce4.gif', '2013-07-15/', 'gif', 'application/octet-stream', '323063', '18a0f2791c4396e7cfcfe77b6257d2b6', 'e8eb4561ebdaf7bfbd5141add420f4c263602f00', '0', '1373859335');
INSERT INTO `think_file` VALUES ('3', 'jQuery1.8.3_20121215.chm', '51e3b518b86b9.chm', '2013-07-15/', 'chm', 'application/octet-stream', '405941', '070896a55a0f2ffaea2082ec67213362', 'f43142dcef3deba755ab6bd842e884145dace637', '0', '1373877528');
INSERT INTO `think_file` VALUES ('4', 'ThinkPHP.apk', '51e3b577ec75a.apk', '2013-07-15/', 'apk', 'application/octet-stream', '540174', '6be127fce55673ba381687379b3f3d1a', '064ad39eae9f0fc00a0b383f5272ad2afe5996f2', '0', '1373877623');
INSERT INTO `think_file` VALUES ('5', 'myservice', '51e3c54f6d78f.', '2013-07-15/', '', 'application/octet-stream', '2542', '1c7774dc8431f68a1f0d00e9222bf342', '315686ec95849498025e98060299b76c74a6a836', '0', '1373881679');
INSERT INTO `think_file` VALUES ('6', 'putty.exe', '51e4ad3db948d.exe', '2013-07-16/', 'exe', 'application/octet-stream', '483328', 'a3ccfd0aa0b17fd23aa9fd0d84b86c05', '89c19274ad51b6fbd12fb59908316088c1135307', '0', '1373941053');
INSERT INTO `think_file` VALUES ('7', 'adsense广告位代码.txt', '51e63ecccb65e.txt', '2013-07-17/', 'txt', 'application/octet-stream', '2365', '93d6a1c3cfe267b03cd8419f20825e77', '9eed0489b259f562ff40d26a3fc3cda16f1d1052', '0', '1374043852');
INSERT INTO `think_file` VALUES ('8', '系统说明文档.docx', '5204aafd1c41b.docx', '2013-08-09/', 'docx', 'application/octet-stream', '19113', 'aa7a156ca847484a5155fba8cbfc6aaa', 'e2012575ad73f93c15913e13c92c39a32362e86b', '0', '1376037629');
INSERT INTO `think_file` VALUES ('9', '测试文档（2013年8月6日）.docx', '5204b6e36dd5e.docx', '2013-08-09/', 'docx', 'application/octet-stream', '195273', 'af426720fba9ed4f35bb92cbe790d9d5', 'c45c958bcfdef7758e79a5e93ecf64b0999eb08d', '0', '1376040675');
INSERT INTO `think_file` VALUES ('10', '官网日常运营.rar', '5212df8a69e8a.rar', '2013-08-20/', 'rar', 'application/octet-stream', '1354', '0ec5ec0351a5fe15f4998a391d4a2e28', 'c342d2d979dc53c9737699996376a4a53faf46a7', '0', '1376968586');
INSERT INTO `think_file` VALUES ('11', '任务列表表.txt', '5212e69a0c04a.txt', '2013-08-20/', 'txt', 'application/octet-stream', '2424', '1f9175d39788f7c61a78c5a3a8d9601a', '3f6013c2ae454682833c5d3b080f995db36ccb95', '0', '1376970393');
INSERT INTO `think_file` VALUES ('12', '7ee8cbfdbcb0cbfbb5c4c3d4b3c771', '52143693023b9.jpg', '2013-08-21/', 'jpg', 'application/octet-stream', '4536', '0ca9a27f1fc0b2fd8b4f17569010e48a', 'bd4421e88a2601bf6b3878aa8db32127c33ac006', '0', '1377056402');
INSERT INTO `think_file` VALUES ('13', '3c6d55fbb2fb4316e661e3fd20a446', '52143ab3e2296.jpg', '2013-08-21/', 'jpg', 'application/octet-stream', '35512', 'e06956cecda298b75f69387afa4a7483', 'da762fa7e368962232443db907555f5b2d7451f5', '0', '1377057459');
INSERT INTO `think_file` VALUES ('14', '120x120.jpg', '52145ac4779fb.jpg', '2013-08-21/', 'jpg', 'application/octet-stream', '27180', '525c1d1eb84ec6d094cc342717e75605', '62d8da07da8ad0fe6eba00260cc8067310c0bf70', '0', '1377065668');
INSERT INTO `think_file` VALUES ('15', '麦当苗儿.docx', '5215d94ae9357.docx', '2013-08-22/', 'docx', 'application/octet-stream', '124068', '46a59abd10ea29579c42c4eff9a3c369', 'cf30a5483538fd445164660d2c5966e58c1fd7d8', '0', '1377163594');

-- ----------------------------
-- Table structure for `think_hooks`
-- ----------------------------
DROP TABLE IF EXISTS `think_hooks`;
CREATE TABLE `think_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '钩子名称',
  `description` text COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1-Controller 2-Widget',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) DEFAULT NULL COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `搜索索引` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_hooks
-- ----------------------------
INSERT INTO `think_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', '');
INSERT INTO `think_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', '');
INSERT INTO `think_hooks` VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', 'Attachment');
INSERT INTO `think_hooks` VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', 'Attachment');
INSERT INTO `think_hooks` VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', null);
INSERT INTO `think_hooks` VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', 'Attachment');
INSERT INTO `think_hooks` VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', 'Editor');

-- ----------------------------
-- Table structure for `think_image`
-- ----------------------------
DROP TABLE IF EXISTS `think_image`;
CREATE TABLE `think_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_image
-- ----------------------------

-- ----------------------------
-- Table structure for `think_member`
-- ----------------------------
DROP TABLE IF EXISTS `think_member`;
CREATE TABLE `think_member` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别（0-女，1-男）',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL,
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表\r\n@author   麦当苗儿\r\n@version  2013-05-27';

-- ----------------------------
-- Records of think_member
-- ----------------------------
INSERT INTO `think_member` VALUES ('9', '0', '0000-00-00', '', '0', '11', '2130706433', '1369722401', '2130706433', '1371192515', '1');
INSERT INTO `think_member` VALUES ('1', '0', '0000-00-00', '', '0', '15', '2130706433', '1371435498', '2130706433', '1377160863', '1');
INSERT INTO `think_member` VALUES ('10', '0', '0000-00-00', '', '0', '1', '3232235922', '1374043830', '3232235922', '1374043830', '1');
INSERT INTO `think_member` VALUES ('11', '0', '0000-00-00', '', '0', '7', '2130706433', '1376897307', '2130706433', '1377075795', '1');
INSERT INTO `think_member` VALUES ('12', '0', '0000-00-00', '', '211', '2', '2130706433', '1376968536', '2130706433', '1377056390', '1');

-- ----------------------------
-- Table structure for `think_setting`
-- ----------------------------
DROP TABLE IF EXISTS `think_setting`;
CREATE TABLE `think_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `name` varchar(30) NOT NULL COMMENT '设置标识',
  `title` varchar(30) NOT NULL COMMENT '配置名称',
  `value` text NOT NULL COMMENT '设置内容',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='网站设置表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- ----------------------------
-- Records of think_setting
-- ----------------------------
INSERT INTO `think_setting` VALUES ('1', 'title', '网站标题', 'ThinkCMS官方演示网站3', '0');
INSERT INTO `think_setting` VALUES ('2', 'logo', '网站LOGO', '', '0');
INSERT INTO `think_setting` VALUES ('3', 'icp', '网站备案号', '沪ICP备12007941号-2', '0');
INSERT INTO `think_setting` VALUES ('4', 'keywords', '网站SEO关键字', 'ThinkPHP,ThinkCMS\r\nThinkPHP,ThinkCMS', '0');
INSERT INTO `think_setting` VALUES ('5', 'description', '网站搜索引擎描述', 'ThinkCMS是基于ThinkPHP开发框架的网站内容管理系统', '0');

-- ----------------------------
-- Table structure for `think_ucenter_admin`
-- ----------------------------
DROP TABLE IF EXISTS `think_ucenter_admin`;
CREATE TABLE `think_ucenter_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of think_ucenter_admin
-- ----------------------------
INSERT INTO `think_ucenter_admin` VALUES ('1', '1', '1');
INSERT INTO `think_ucenter_admin` VALUES ('2', '3', '1');

-- ----------------------------
-- Table structure for `think_ucenter_app`
-- ----------------------------
DROP TABLE IF EXISTS `think_ucenter_app`;
CREATE TABLE `think_ucenter_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID',
  `title` varchar(30) NOT NULL COMMENT '应用名称',
  `url` varchar(100) NOT NULL COMMENT '应用URL',
  `ip` char(15) NOT NULL COMMENT '应用IP',
  `auth_key` varchar(100) NOT NULL COMMENT '加密KEY',
  `sys_login` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '同步登陆',
  `allow_ip` varchar(255) NOT NULL COMMENT '允许访问的IP',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='应用表';

-- ----------------------------
-- Records of think_ucenter_app
-- ----------------------------
INSERT INTO `think_ucenter_app` VALUES ('1', 'ThinkPHP官网', 'http://www.thinkphp.cn', '', '', '0', '', '0', '0', '1');

-- ----------------------------
-- Table structure for `think_ucenter_member`
-- ----------------------------
DROP TABLE IF EXISTS `think_ucenter_member`;
CREATE TABLE `think_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  KEY `status` (`status`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of think_ucenter_member
-- ----------------------------
INSERT INTO `think_ucenter_member` VALUES ('1', 'administrator', '88caaf09d9c65cafc1191859c17ad36c', 'zuojiazi@vip.qq.com', '', '0', '0', '1377160863', '2130706433', '0', '1');
INSERT INTO `think_ucenter_member` VALUES ('9', '麦当苗儿', '88caaf09d9c65cafc1191859c17ad36c', 'zuojiazi.cn@gmail.com', '', '1369721426', '2130706433', '1371192515', '2130706433', '1369721426', '1');
INSERT INTO `think_ucenter_member` VALUES ('10', 'thinkphp', '525fd9a1ae3a25ec9b2a6650a18a4829', 'thinkphp@qq.com', '', '1374043813', '3232235922', '1374043830', '3232235922', '1374043813', '1');
INSERT INTO `think_ucenter_member` VALUES ('11', 'yangweijie', '7a58d2d679476c86911cfa65882ae430', '917647288@qq.com', '', '1376897291', '2130706433', '1377075795', '2130706433', '1376897291', '1');
INSERT INTO `think_ucenter_member` VALUES ('12', 'thinkphphj', '65d185d7fd782d23dfd06bcc1aa467c8', 'huajie@topthink.net', '', '1376968516', '2130706433', '1377056390', '2130706433', '1376968516', '1');

-- ----------------------------
-- Table structure for `think_ucenter_setting`
-- ----------------------------
DROP TABLE IF EXISTS `think_ucenter_setting`;
CREATE TABLE `think_ucenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `value` text NOT NULL COMMENT '配置数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表';

-- ----------------------------
-- Records of think_ucenter_setting
-- ----------------------------
