/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.200
Source Server Version : 50520
Source Host           : 192.168.1.200:3306
Source Database       : onethink

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2013-09-18 21:15:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `onethink_action`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_action`;
CREATE TABLE `onethink_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `status` tinyint(2) NOT NULL COMMENT '状态（-1：已删除，0：禁用，1：正常）',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

-- ----------------------------
-- Records of onethink_action
-- ----------------------------
INSERT INTO `onethink_action` VALUES ('1', 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:9-2+3+score*1/1|cycle:24|max:1;', '1', '1377681235');
INSERT INTO `onethink_action` VALUES ('2', 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max|5', '1', '1377504452');
INSERT INTO `onethink_action` VALUES ('3', 'review', '评论', '评论积分+2', 'table:member|field:score|condition:uid={$self}|rule:score+1|cycle:24|max|5', '1', '1379150556');

-- ----------------------------
-- Table structure for `onethink_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_action_log`;
CREATE TABLE `onethink_action_log` (
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
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of onethink_action_log
-- ----------------------------
INSERT INTO `onethink_action_log` VALUES ('43', '1', '12', '2130706433', 'member', '12', '1377571191');
INSERT INTO `onethink_action_log` VALUES ('44', '1', '10', '2130706433', 'member', '10', '1377677682');
INSERT INTO `onethink_action_log` VALUES ('45', '1', '11', '2130706433', 'member', '11', '1378104467');
INSERT INTO `onethink_action_log` VALUES ('46', '1', '11', '2130706433', 'member', '11', '1378178007');
INSERT INTO `onethink_action_log` VALUES ('47', '1', '11', '2130706433', 'member', '11', '1378196490');
INSERT INTO `onethink_action_log` VALUES ('48', '1', '11', '2130706433', 'member', '11', '1378198582');
INSERT INTO `onethink_action_log` VALUES ('49', '1', '1', '2130706433', 'member', '1', '1378347877');
INSERT INTO `onethink_action_log` VALUES ('50', '1', '13', '2130706433', 'member', '13', '1378440279');
INSERT INTO `onethink_action_log` VALUES ('51', '1', '11', '2130706433', 'member', '11', '1378448326');
INSERT INTO `onethink_action_log` VALUES ('52', '1', '1', '2130706433', 'member', '1', '1378448786');
INSERT INTO `onethink_action_log` VALUES ('42', '1', '12', '2130706433', 'member', '12', '1377571132');
INSERT INTO `onethink_action_log` VALUES ('53', '1', '1', '2130706433', 'member', '1', '1378778769');
INSERT INTO `onethink_action_log` VALUES ('54', '1', '12', '2130706433', 'member', '12', '1378780815');
INSERT INTO `onethink_action_log` VALUES ('55', '1', '1', '2130706433', 'member', '1', '1378780958');
INSERT INTO `onethink_action_log` VALUES ('56', '1', '11', '2130706433', 'member', '11', '1378783949');
INSERT INTO `onethink_action_log` VALUES ('57', '1', '1', '2130706433', 'member', '1', '1378789685');
INSERT INTO `onethink_action_log` VALUES ('58', '1', '11', '2130706433', 'member', '11', '1378882046');
INSERT INTO `onethink_action_log` VALUES ('59', '1', '1', '2130706433', 'member', '1', '1378888427');
INSERT INTO `onethink_action_log` VALUES ('60', '1', '11', '2130706433', 'member', '11', '1378977765');
INSERT INTO `onethink_action_log` VALUES ('61', '1', '11', '2130706433', 'member', '11', '1379126003');
INSERT INTO `onethink_action_log` VALUES ('62', '1', '1', '2130706433', 'member', '1', '1379297819');
INSERT INTO `onethink_action_log` VALUES ('63', '1', '11', '2130706433', 'member', '11', '1379301611');
INSERT INTO `onethink_action_log` VALUES ('64', '1', '11', '2130706433', 'member', '11', '1379315962');
INSERT INTO `onethink_action_log` VALUES ('65', '1', '11', '2130706433', 'member', '11', '1379322408');
INSERT INTO `onethink_action_log` VALUES ('66', '1', '13', '2130706433', 'member', '13', '1379407403');
INSERT INTO `onethink_action_log` VALUES ('67', '1', '13', '2130706433', 'member', '13', '1379407474');
INSERT INTO `onethink_action_log` VALUES ('68', '1', '13', '2130706433', 'member', '13', '1379407502');
INSERT INTO `onethink_action_log` VALUES ('69', '1', '13', '2130706433', 'member', '13', '1379407553');
INSERT INTO `onethink_action_log` VALUES ('70', '1', '1', '2130706433', 'member', '1', '1379483494');
INSERT INTO `onethink_action_log` VALUES ('71', '1', '15', '2130706433', 'member', '15', '1379484969');
INSERT INTO `onethink_action_log` VALUES ('72', '1', '15', '2130706433', 'member', '15', '1379485018');
INSERT INTO `onethink_action_log` VALUES ('73', '1', '1', '2130706433', 'member', '1', '1379493142');
INSERT INTO `onethink_action_log` VALUES ('74', '1', '1', '2130706433', 'member', '1', '1379496969');
INSERT INTO `onethink_action_log` VALUES ('75', '1', '1', '2130706433', 'member', '1', '1379506189');
INSERT INTO `onethink_action_log` VALUES ('76', '1', '1', '2130706433', 'member', '1', '1379506491');
INSERT INTO `onethink_action_log` VALUES ('77', '1', '1', '2130706433', 'member', '1', '1379506574');
INSERT INTO `onethink_action_log` VALUES ('78', '1', '15', '2130706433', 'member', '15', '1379506974');
INSERT INTO `onethink_action_log` VALUES ('79', '1', '1', '2130706433', 'member', '1', '1379507698');

-- ----------------------------
-- Table structure for `onethink_addons`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_addons`;
CREATE TABLE `onethink_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识，区分大小写',
  `title` varchar(20) NOT NULL COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1-启用 0-禁用 -1-损坏',
  `config` text COMMENT '配置 序列化存放',
  `author` varchar(40) DEFAULT NULL COMMENT '作者',
  `version` varchar(20) DEFAULT NULL COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1-有后台列表 0-无后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of onethink_addons
-- ----------------------------
INSERT INTO `onethink_addons` VALUES ('39', 'AdaptiveImages', '手机端响应式图片处理', '通过检测手机的宽度，在小设备访问图片时返回合适尺寸的小图片，到小尺寸设备达到图片响应式。', '1', '{\"resolutions\":\"1382,992,768,480\",\"cache_path\":\".\\/Uploads\\/ai-cache\",\"jpg_quality\":\"75\",\"sharpen\":\"0\",\"watch_cache\":\"0\",\"browser_cache\":\"604800\"}', 'thinkphp', '0.1', '1378450898', '0');
INSERT INTO `onethink_addons` VALUES ('56', 'EditorForAdmin', '后台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"2\",\"editor_height\":\"220px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1378891657', '0');
INSERT INTO `onethink_addons` VALUES ('61', 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', '1', '{\"comment_type\":\"1\",\"comment_uid_youyan\":\"900400\",\"comment_short_name_duoshuo\":\"\",\"comment_form_pos_duoshuo\":\"top\",\"comment_data_list_duoshuo\":\"10\",\"comment_data_order_duoshuo\":\"asc\"}', 'thinkphp', '0.1', '1378950537', '0');
INSERT INTO `onethink_addons` VALUES ('103', 'Editor', '前台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"300px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1379496393', '0');
INSERT INTO `onethink_addons` VALUES ('92', 'DevTeam', '开发团队信息', '开发团队成员信息', '1', '{\"title\":\"OneThink\\u5f00\\u53d1\\u56e2\\u961f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379392499', '0');
INSERT INTO `onethink_addons` VALUES ('93', 'SystemInfo', '系统环境信息', '用于显示一些服务器的信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379392602', '0');
INSERT INTO `onethink_addons` VALUES ('97', 'SiteStat', '站点统计信息', '统计站点的基础信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"1\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379401954', '0');
INSERT INTO `onethink_addons` VALUES ('101', 'Attachment', '附件', '用于文档模型上传附件', '1', '{\"status\":\"1\"}', 'thinkphp', '0.1', '1379404518', '1');
INSERT INTO `onethink_addons` VALUES ('102', 'ReturnTop', '返回顶部', '回到顶部美化，随机或指定显示，100款样式，每天一种换，天天都用新样式', '1', '{\"random\":\"0\",\"current\":\"82\"}', 'thinkphp', '0.1', '1379409912', '0');

-- ----------------------------
-- Table structure for `onethink_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_attachment`;
CREATE TABLE `onethink_attachment` (
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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='附件表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- ----------------------------
-- Records of onethink_attachment
-- ----------------------------
INSERT INTO `onethink_attachment` VALUES ('1', '1', 'upyun_api_doc.pdf', '2', '1', '2', '7', '186603', '0', '0', '1373443268', '1373443268', '1');
INSERT INTO `onethink_attachment` VALUES ('2', '1', '1725084_1.gif', '2', '2', '4', '5', '323063', '0', '0', '1373859340', '1373859340', '1');
INSERT INTO `onethink_attachment` VALUES ('3', '10', 'adsense广告位代码.txt', '2', '7', '21', '2', '2365', '0', '0', '1374043875', '1374043875', '1');
INSERT INTO `onethink_attachment` VALUES ('4', '1', '系统说明文档.docx', '2', '8', '29', '1', '19113', '0', '0', '1376037633', '1376037633', '1');
INSERT INTO `onethink_attachment` VALUES ('5', '1', '测试文档（2013年8月6日）.docx', '2', '9', '31', '1', '195273', '0', '0', '1376040686', '1376040686', '1');
INSERT INTO `onethink_attachment` VALUES ('6', '1', '麦当苗儿.docx', '2', '15', '42', '1', '124068', '0', '0', '1377164056', '1377164056', '1');
INSERT INTO `onethink_attachment` VALUES ('7', '10', 'TPM文档.docx', '2', '17', '43', '0', '82883', '0', '0', '1377572673', '1377572673', '1');
INSERT INTO `onethink_attachment` VALUES ('8', '1', 'ThinkPHP CMS.pdf', '2', '35', '15', '3', '129460', '0', '0', '1378780389', '1378780389', '1');
INSERT INTO `onethink_attachment` VALUES ('9', '11', 'psb_white.jpg', '2', '29', '75', '0', '7589', '0', '0', '1379158095', '1379158095', '1');
INSERT INTO `onethink_attachment` VALUES ('10', '11', 'psb.jpg', '2', '31', '76', '0', '7158', '0', '0', '1379158650', '1379158650', '1');

-- ----------------------------
-- Table structure for `onethink_auth_category_access`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_auth_category_access`;
CREATE TABLE `onethink_auth_category_access` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `category_id` mediumint(8) unsigned NOT NULL COMMENT '栏目id',
  UNIQUE KEY `uid_group_id` (`group_id`,`category_id`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

-- ----------------------------
-- Records of onethink_auth_category_access
-- ----------------------------

-- ----------------------------
-- Table structure for `onethink_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_auth_group`;
CREATE TABLE `onethink_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of onethink_auth_group
-- ----------------------------
INSERT INTO `onethink_auth_group` VALUES ('1', 'admin', '1', '管理员', 'id为1的用户组222aa', '1', '1,2,3,4,13,14,25,26,27,28,29,31,33,34,36,37,38,39,40,41,42,43,44,45,46,47,48,50,52,53,54,55,57,58,59,60,61,62,63,64,65,66,67,68,69,70,72,73,74,75,76,77,78,79,80,81,82,83,92,93,95,97,98,99,100,101,103,104,105,106,107,108,109,110,111,112,113,114');
INSERT INTO `onethink_auth_group` VALUES ('5', 'admin', '1', '内容管理员', '111111111111', '1', '1,2,31,32,57,58,59,68,69,70,83,87,88,89,90');
INSERT INTO `onethink_auth_group` VALUES ('6', 'admin', '1', '测试', '测试', '1', '1,2,3,4,13,14,25,26,27,28,29,32,33,36,37,38,39,40,41,42,57,58,59,60,61,62,63,68,72,75');
INSERT INTO `onethink_auth_group` VALUES ('7', 'admin', '1', 'aaabbb', 'aaaaaa', '1', '13,25,26,27,28,33,36,37,38,39,40,41,42,72,75');

-- ----------------------------
-- Table structure for `onethink_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_auth_group_access`;
CREATE TABLE `onethink_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of onethink_auth_group_access
-- ----------------------------
INSERT INTO `onethink_auth_group_access` VALUES ('1', '6');
INSERT INTO `onethink_auth_group_access` VALUES ('9', '1');
INSERT INTO `onethink_auth_group_access` VALUES ('9', '6');
INSERT INTO `onethink_auth_group_access` VALUES ('10', '5');
INSERT INTO `onethink_auth_group_access` VALUES ('10', '6');
INSERT INTO `onethink_auth_group_access` VALUES ('11', '5');
INSERT INTO `onethink_auth_group_access` VALUES ('11', '6');
INSERT INTO `onethink_auth_group_access` VALUES ('11', '7');
INSERT INTO `onethink_auth_group_access` VALUES ('12', '1');
INSERT INTO `onethink_auth_group_access` VALUES ('13', '1');
INSERT INTO `onethink_auth_group_access` VALUES ('13', '6');

-- ----------------------------
-- Table structure for `onethink_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_auth_rule`;
CREATE TABLE `onethink_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`module`,`name`,`type`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of onethink_auth_rule
-- ----------------------------
INSERT INTO `onethink_auth_rule` VALUES ('1', 'admin', '1', 'Admin/Index/index', '管理首页', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('2', 'admin', '1', 'Admin/article/index', '文档列表', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('3', 'admin', '1', 'Admin/User/index', '用户信息', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('4', 'admin', '1', 'Admin/Addons/index', '插件管理', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('5', 'admin', '1', 'Admin/System/index', '基本设置', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('6', 'admin', '1', 'Admin/Index/form', '表单样式', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('7', 'admin', '1', 'Admin/Article/index?cate_id=9', '讨论', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('8', 'admin', '1', 'Admin/Article/index?cate_id=2', '下载', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('9', 'admin', '1', 'Admin/AuthManager/edit', '编辑', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('10', 'admin', '1', 'Admin/AuthManager/delete', '删除', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('11', 'admin', '1', 'Admin/AuthManager/forbid', '禁用', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('12', 'admin', '1', 'Admin/AuthManager/resume', '恢复', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('13', 'admin', '1', 'Admin/AuthManager/index', '权限管理', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('14', 'admin', '1', 'Admin/Addons/hooks', '钩子管理', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('15', 'admin', '1', 'Admin/System/index1', '静态规则设置', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('16', 'admin', '1', 'Admin/System/index2', 'SEO优化设置', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('17', 'admin', '1', 'Admin/System/index3', '导航管理', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('18', 'admin', '1', 'Admin/System/index4', '分类管理', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('19', 'admin', '1', 'Admin/System/index5', '数据迁移', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('20', 'admin', '1', 'Admin/System/index6', '数据备份/恢复', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('21', 'admin', '1', 'Admin/System/index7', '系统日志', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('22', 'admin', '1', 'Admin/Article/index?cate_id=10', '框架', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('23', 'admin', '1', 'Admin/User/index2', '用户行为', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('24', 'admin', '1', 'Admin/User/index1', '权限管理', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('25', 'admin', '1', 'Admin/AuthManager/editGroup', '编辑', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('26', 'admin', '1', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('27', 'admin', '1', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('28', 'admin', '1', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('29', 'admin', '1', 'Admin/User/action', '用户行为', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('30', 'admin', '1', 'Admin/User/action1', '用户行为', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('31', 'admin', '2', 'Admin/Index/index', '首页', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('32', 'admin', '2', 'Admin/Article/index', '内容', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('33', 'admin', '2', 'Admin/User/index', '用户', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('34', 'admin', '2', 'Admin/Addons/index', '扩展', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('35', 'admin', '2', 'Admin/System/index', '系统', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('36', 'admin', '1', 'Admin/AuthManager/createGroup', '新增', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('37', 'admin', '1', 'Admin/AuthManager/writeGroup', '保存用户组', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('38', 'admin', '1', 'Admin/AuthManager/user', '成员授权', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('39', 'admin', '1', 'Admin/AuthManager/removeFromGroup', '解除授权', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('40', 'admin', '1', 'Admin/AuthManager/addToGroup', '保存成员授权', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('41', 'admin', '1', 'Admin/AuthManager/category', '分类授权', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('42', 'admin', '1', 'Admin/AuthManager/group', '授权', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('43', 'admin', '1', 'Admin/Model/index', '模型管理', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('44', 'admin', '1', 'Admin/Addons/config', '设置', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('45', 'admin', '1', 'Admin/Addons/disable', '禁用', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('46', 'admin', '1', 'Admin/Addons/enable', '启用', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('47', 'admin', '1', 'Admin/Addons/install', '安装', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('48', 'admin', '1', 'Admin/Addons/uninstall', '卸载', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('49', 'admin', '1', 'Admin/Addon/saveconfig', '更新配置', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('50', 'admin', '1', 'Admin/Addons/saveconfig', '更新配置', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('51', 'admin', '1', 'Admin/Addons/updateSort', '编辑', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('52', 'admin', '1', 'Admin/Addons/adminList', '插件后台列表', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('53', 'admin', '1', 'Admin/Addons/create', '创建', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('54', 'admin', '1', 'Admin/Category/index', '分类管理', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('55', 'admin', '1', 'Admin/Addons/checkForm', '检测创建', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('56', 'admin', '1', 'Admin/Addons/window', '弹窗', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('57', 'admin', '1', 'Admin/article/edit', '编辑', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('58', 'admin', '1', 'Admin/article/add', '新增', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('59', 'admin', '1', 'Admin/article/setStatus', '改变状态', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('60', 'admin', '1', 'Admin/user/addAction', '新增用户行为', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('61', 'admin', '1', 'Admin/user/editAction', '编辑用户行为', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('62', 'admin', '1', 'Admin/user/setStatus', '变更行为状态', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('63', 'admin', '1', 'Admin/user/saveAction', '保存用户行为', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('64', 'admin', '1', 'Admin/model/add', '新增', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('65', 'admin', '1', 'Admin/model/edit', '编辑', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('66', 'admin', '1', 'Admin/model/setStatus', '改变状态', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('67', 'admin', '1', 'Admin/model/update', '保存数据', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('68', 'admin', '1', 'Admin/article/update', '保存数据', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('69', 'admin', '1', 'Admin/file/upload', '上传控件', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('70', 'admin', '1', 'Admin/file/download', '下载', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('71', 'admin', '1', 'Admin/System/channel', '导航管理', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('72', 'admin', '1', 'Admin/AuthManager/addToCategory', '保存分类授权', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('73', 'admin', '1', 'Admin/Addons/preview', '预览', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('74', 'admin', '1', 'Admin/Addons/build', '快速生成插件', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('75', 'admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('76', 'admin', '1', 'Admin/article/recycle', '回收站', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('77', 'admin', '1', 'Admin/article/clear', '清空回收站', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('78', 'admin', '1', 'Admin/article/permit', '还原', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('79', 'admin', '1', 'Admin/user/changeStatus?method=forbidUser', '禁用会员', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('80', 'admin', '1', 'Admin/user/changeStatus?method=resumeUser', '启用会员', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('81', 'admin', '1', 'Admin/user/changeStatus?method=deleteUser', '删除会员', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('83', 'admin', '1', 'Admin/file/uploadPicture', '上传图片', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('82', 'admin', '1', 'Admin/Category/edit', '编辑', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('84', 'admin', '1', 'Admin/User/editPassword', '修改密码', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('85', 'admin', '1', 'Admin/User/editNickname', '修改昵称', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('86', 'admin', '1', 'Admin/System/config', '配置管理', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('87', 'admin', '1', 'Admin/User/updatePassword', '修改密码', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('88', 'admin', '1', 'Admin/User/updateNickname', '修改昵称', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('89', 'admin', '1', 'Admin/user/submitPassword', '修改密码', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('90', 'admin', '1', 'Admin/user/submitNickname', '修改昵称', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('91', 'admin', '2', 'Admin/Config/base', '系统', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('92', 'admin', '1', 'Admin/Config/edit', '编辑', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('93', 'admin', '1', 'Admin/Config/del', '删除', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('94', 'admin', '1', 'Admin/Config/base', '基本设置', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('95', 'admin', '1', 'Admin/Config/index', '配置管理', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('96', 'admin', '1', 'Admin/Addons/edithookaddons', '编辑钩子页面', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('97', 'admin', '1', 'Admin/Addons/execute', 'URL方式访问插件', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('98', 'admin', '2', 'Admin/Config/group', '系统', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('99', 'admin', '1', 'Admin/Config/group', '网站设置', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('100', 'admin', '2', 'Admin/Article/mydocument', '内容', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('101', 'admin', '1', 'Admin/Channel/index', '导航管理', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('102', 'admin', '1', 'Admin/article/mydocument', '我的文档', '-1', '');
INSERT INTO `onethink_auth_rule` VALUES ('103', 'admin', '1', 'Admin/article/draftbox', '草稿箱', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('104', 'admin', '1', 'Admin/article/autoSave', '自动保存为草稿', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('105', 'admin', '1', 'Admin/article/move', '移动文章', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('106', 'admin', '1', 'Admin/article/copy', '复制文章', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('107', 'admin', '1', 'Admin/article/paste', '粘贴文章', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('108', 'admin', '1', 'Admin/Config/add', '新增', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('109', 'admin', '1', 'Admin/Config/save', '保存', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('110', 'admin', '1', 'Admin/Channel/add', '新增', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('111', 'admin', '1', 'Admin/Channel/edit', '编辑', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('112', 'admin', '1', 'Admin/Channel/del', '删除', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('113', 'admin', '1', 'Admin/Category/add', '新增', '1', '');
INSERT INTO `onethink_auth_rule` VALUES ('114', 'admin', '1', 'Admin/Category/remove', '删除', '1', '');

-- ----------------------------
-- Table structure for `onethink_category`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_category`;
CREATE TABLE `onethink_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(16) NOT NULL COMMENT '标识',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(3) unsigned NOT NULL COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `template_index` varchar(100) NOT NULL COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL COMMENT '关联模型',
  `type` varchar(100) NOT NULL COMMENT '允许发布的内容类型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链（0-非外链，大于0-外链ID）',
  `allow_publish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容（0-不允许，1-允许）',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性（0-所有人可见，1-管理员可见，2-不可见）',
  `reply` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `reply_model` varchar(100) NOT NULL,
  `reply_type` varchar(100) NOT NULL,
  `extend` text NOT NULL COMMENT '扩展设置（JSON数据）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态（-1-删除，0-禁用，1-正常，2-待审核）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='分类表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of onethink_category
-- ----------------------------
INSERT INTO `onethink_category` VALUES ('1', 'blog', '博客', '0', '0', '10', '', '', '', '', '', '', '', '1,2', '2', '0', '0', '1', '0', '1', '2', '', '1379474947', '1379502263', '1');
INSERT INTO `onethink_category` VALUES ('2', 'default_blog', '默认分类', '1', '0', '10', '', '', '', '', '', '', '', '1,2', '2', '0', '1', '1', '0', '1', '2', '', '1379475028', '1379486406', '1');
INSERT INTO `onethink_category` VALUES ('3', 'topic', '讨论', '0', '0', '10', '', '', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '1,2', '2', '0', '0', '1', '0', '1', '2', '', '1379475049', '1379483786', '1');
INSERT INTO `onethink_category` VALUES ('4', 'default_topic', '默认分类', '3', '0', '10', 'asdfsadfasdfsfdsafasasdfsadfasdfsfdsafasasdfsa', 'dfbdfbgdfbgdfbdfbggfdfbdfbdfbdfbgdfbgdfbdfbggfdfbdfb', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '1,2', '2', '0', '1', '1', '0', '1', '2', '', '1379475068', '1379491975', '1');
INSERT INTO `onethink_category` VALUES ('5', 'product', '程序发布', '3', '0', '10', '', '', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '2', '2', '0', '1', '1', '1', '1', '2', '', '1379475697', '1379475697', '1');
INSERT INTO `onethink_category` VALUES ('6', 'discuss', '求助交流', '3', '0', '10', '', '', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '1', '2', '0', '1', '1', '1', '1', '2', '', '1379475735', '1379475735', '1');
INSERT INTO `onethink_category` VALUES ('7', 'plugins', '插件开发', '3', '0', '10', '', '', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '1', '2', '0', '1', '1', '0', '1', '2', '', '1379475780', '1379475780', '1');
INSERT INTO `onethink_category` VALUES ('8', 'advise', '产品建议', '3', '0', '10', '', '', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '1', '2', '0', '1', '1', '1', '1', '2', '', '1379475841', '1379475841', '1');
INSERT INTO `onethink_category` VALUES ('9', 'bugs', 'BUG反馈', '3', '0', '10', '', '', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '1', '2', '0', '1', '1', '0', '1', '2', '', '1379475871', '1379475871', '1');

-- ----------------------------
-- Table structure for `onethink_channel`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_channel`;
CREATE TABLE `onethink_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of onethink_channel
-- ----------------------------
INSERT INTO `onethink_channel` VALUES ('1', '0', '首页', 'Index/index', '0', '1379475111', '1379475111', '1');
INSERT INTO `onethink_channel` VALUES ('2', '0', '博客', 'Article/index?category=blog', '0', '1379475131', '1379483713', '1');
INSERT INTO `onethink_channel` VALUES ('3', '0', '讨论', 'Article/index?category=topic', '0', '1379475154', '1379483726', '1');
INSERT INTO `onethink_channel` VALUES ('4', '0', '下载', '#', '0', '1379476014', '1379476014', '1');
INSERT INTO `onethink_channel` VALUES ('5', '0', '文档', '#', '0', '1379476035', '1379476035', '1');
INSERT INTO `onethink_channel` VALUES ('6', '0', '案例', '#', '0', '1379476056', '1379476056', '1');
INSERT INTO `onethink_channel` VALUES ('7', '0', '资讯', '#', '0', '1379476105', '1379476105', '1');
INSERT INTO `onethink_channel` VALUES ('8', '0', '应用', '#', '0', '1379476145', '1379476145', '1');

-- ----------------------------
-- Table structure for `onethink_config`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_config`;
CREATE TABLE `onethink_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（0-数字，1-字符，2-文本，3-数组，4-枚举，5-多选）',
  `title` varchar(50) NOT NULL COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组（0-无分组，1-基本设置）',
  `extra` varchar(255) NOT NULL COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL,
  `sort` smallint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of onethink_config
-- ----------------------------
INSERT INTO `onethink_config` VALUES ('1', 'WEB_SITE_TITLE', '1', '网站标题', '0', '', '网站标题前台显示标题', '1378898976', '1379235274', '1', 'OneThink内容管理框架', '0');
INSERT INTO `onethink_config` VALUES ('2', 'WEB_SITE_DESCRIPTION', '2', '网站描述', '0', '', '网站搜索引擎描述', '1378898976', '1379235841', '1', 'OneThink内容管理框架', '1');
INSERT INTO `onethink_config` VALUES ('3', 'WEB_SITE_KEYWORD', '2', '网站关键字', '0', '', '网站搜索引擎关键字', '1378898976', '1379235848', '1', 'ThinkPHP,OneThink', '3');
INSERT INTO `onethink_config` VALUES ('4', 'WEB_SITE_CLOSE', '4', '关闭站点', '0', '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', '1378898976', '1379235296', '1', '1', '0');
INSERT INTO `onethink_config` VALUES ('9', 'CONFIG_TYPE_LIST', '3', '配置类型列表', '3', '', '主要用于数据解析和页面表单的生成', '1378898976', '1379235348', '1', '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', '0');
INSERT INTO `onethink_config` VALUES ('10', 'WEB_SITE_ICP', '1', '网站备案号', '0', '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', '1378900335', '1379235859', '1', '', '4');
INSERT INTO `onethink_config` VALUES ('11', 'DOCUMENT_POSITION', '3', '文档推荐位', '1', '', '文档推荐位，推荐到多个位置KEY值相加即可', '1379053380', '1379235329', '1', '1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐', '0');
INSERT INTO `onethink_config` VALUES ('12', 'DOCUMENT_DISPLAY', '3', '文档可见性', '1', '', '文章可见性仅影响前台显示，后台不收影响', '1379056370', '1379235322', '1', '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', '0');
INSERT INTO `onethink_config` VALUES ('13', 'COLOR_STYLE', '4', '后台色系', '0', 'default_color:默认\r\nblue_color:紫罗兰', '后台颜色风格', '1379122533', '1379235904', '1', 'default_color', '5');
INSERT INTO `onethink_config` VALUES ('20', 'CONFIG_GROUP_LIST', '3', '配置分组', '3', '', '配置分组', '1379228036', '1379312862', '1', '0:基本设置\r\n1:内容设置\r\n2:用户设置\r\n3:系统配置', '0');
INSERT INTO `onethink_config` VALUES ('21', 'HOOKS_TYPE', '3', '钩子的类型', '3', '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', '1379313397', '1379313407', '1', '1:视图\r\n2:控制器', '0');
INSERT INTO `onethink_config` VALUES ('22', 'AUTH_CONFIG', '3', 'Auth配置', '3', '', '自定义Auth.class.php类配置', '1379409310', '1379409564', '1', 'AUTH_ON:1\r\nAUTH_TYPE:1', '0');
INSERT INTO `onethink_config` VALUES ('23', 'OPEN_DRAFTBOX', '4', '是否开启草稿功能', '1', '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', '1379484332', '1379484591', '1', '0', '0');
INSERT INTO `onethink_config` VALUES ('24', 'AOTUSAVE_DRAFT', '0', '自动保存草稿时间', '1', '', '自动保存草稿的时间间隔，单位：秒', '1379484574', '1379484574', '1', '60', '0');
INSERT INTO `onethink_config` VALUES ('25', 'LIST_ROWS', '0', '后台每页记录数', '1', '', '后台数据每页显示记录数', '1379503896', '1379503896', '1', '15', '5');
INSERT INTO `onethink_config` VALUES ('26', 'USER_ALLOW_REGISTER', '4', '是否允许用户注册', '2', '0:关闭注册\r\n1:允许注册', '是否开放用户注册', '1379504487', '1379504580', '1', '1', '0');

-- ----------------------------
-- Table structure for `onethink_document`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_document`;
CREATE TABLE `onethink_document` (
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
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优先级（越高排序越靠前）',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态（-1-删除，0-禁用，1-正常，2-待审核，3-草稿）',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`) USING BTREE,
  KEY `idx_category_status` (`category_id`,`status`) USING BTREE,
  KEY `idx_status_type_pid` (`status`,`type`,`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COMMENT='文档模型基础表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of onethink_document
-- ----------------------------
INSERT INTO `onethink_document` VALUES ('2', '1', 'hello', 'OneThink欢迎您', '2', 'OneThink欢迎您。', '0', '1', '2', '0', '0', '43', '0', '0', '0', '0', '0', '0', '0', '1379480640', '1379480766', '1');
INSERT INTO `onethink_document` VALUES ('3', '1', 'baofu', '前首富宗庆后被砍 疑因报复', '6', '据《香港商报》报道，有网友在杭州本土论坛口水楼市爆料，经证实，中国前首富，杭州娃哈哈集团董事长宗庆后上周五（9月13日）清晨在家附近，被人砍断左手四个手指的肌腱，原因据称是报复。', '0', '1', '2', '1', '3', '18', '0', '0', '0', '0', '0', '0', '2147483647', '1379482980', '1379484956', '1');
INSERT INTO `onethink_document` VALUES ('4', '1', '', '娃哈哈相关人士：宗庆后手指被砍“纯属谣言”', '4', '据中国之声《央广新闻》报道，昨天《香港商报》报道，杭州网友在杭州本土论坛“口水楼市”爆料称，中国前首富杭州娃哈哈集团董事长宗庆后，在上周五清晨在他家附近被人砍断左手四个手指的肌腱，并称被袭或因打击报复。为此，中国之声记者求证了娃哈哈的相关人士。', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379483259', '1379483259', '1');
INSERT INTO `onethink_document` VALUES ('5', '1', '', '京津新城成“空城” 工作人员比常住业主多', '4', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379483496', '1379483496', '1');
INSERT INTO `onethink_document` VALUES ('6', '1', '', '123123', '5', '', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379488080', '1379488432', '-1');
INSERT INTO `onethink_document` VALUES ('7', '1', '', '从“超女挨批”说“媒介批评的威权批评”', '2', '引子提要：最近文化部原部长全国政协常委兼教科文卫体主任刘忠德批评“超级女生”引人关注。[1]本文不就“超级女生”现象和其具体批评是否正确做出评论。政协常委批评“超级女生”其实是典型的“媒介批评”，而且本文认为这属于典型的“媒介批评的威权批评”。', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379491963', '1379491963', '1');
INSERT INTO `onethink_document` VALUES ('9', '1', '', '重庆一聋哑学校4名女生失踪 校方疑有人操纵', '2', '昨(1日，下同)晚7时，垫江县聋哑学校校长肖建国，仍率队辗转在南岸、沙坪坝等地，火急火燎地寻找着失踪已达6天的4名聋哑女学生——10月25日晚，该校老师好心允许两外来聋哑人留宿后，次日4名在校女生便悄悄跟她俩走了。', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379492032', '1379492032', '1');
INSERT INTO `onethink_document` VALUES ('10', '1', '', '“临时性强奸”，祝贺又一新名词诞生了(原创首发)', '2', '', '0', '1', '2', '7', '0', '0', '0', '0', '0', '0', '0', '0', '50', '1381997700', '1379492149', '1');
INSERT INTO `onethink_document` VALUES ('23', '1', '', 'RE:前首富宗庆后被砍 疑因报复', '4', '', '3', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379497737', '1379497737', '1');
INSERT INTO `onethink_document` VALUES ('25', '1', '', '', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379498804', '1379498804', '1');
INSERT INTO `onethink_document` VALUES ('26', '1', '', '测试1', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499656', '1379499656', '1');
INSERT INTO `onethink_document` VALUES ('27', '1', '', '测试2', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499670', '1379499670', '1');
INSERT INTO `onethink_document` VALUES ('28', '1', '', '测试3', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499680', '1379499680', '1');
INSERT INTO `onethink_document` VALUES ('29', '1', '', '测试4', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499688', '1379499688', '1');
INSERT INTO `onethink_document` VALUES ('30', '1', '', '测试5', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499702', '1379499702', '1');
INSERT INTO `onethink_document` VALUES ('31', '1', '', '测试6', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499712', '1379499712', '1');
INSERT INTO `onethink_document` VALUES ('32', '1', '', '测试7', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499721', '1379499721', '1');
INSERT INTO `onethink_document` VALUES ('33', '1', '', '测试8', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499730', '1379499730', '1');
INSERT INTO `onethink_document` VALUES ('35', '1', '', '测试9', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499741', '1379499741', '1');
INSERT INTO `onethink_document` VALUES ('36', '1', '', '测试10', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499765', '1379499765', '1');
INSERT INTO `onethink_document` VALUES ('37', '1', '', '测试11', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499776', '1379499776', '1');
INSERT INTO `onethink_document` VALUES ('38', '1', '', '后台发的回复', '4', '哈哈', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499771', '1379499771', '1');
INSERT INTO `onethink_document` VALUES ('39', '1', '', '打发打发的', '4', '地方', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379499799', '1379499799', '1');
INSERT INTO `onethink_document` VALUES ('40', '1', '', '234234', '2', '', '10', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379500243', '1379500243', '1');
INSERT INTO `onethink_document` VALUES ('42', '1', '', '23232323', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379501710', '1379501710', '1');
INSERT INTO `onethink_document` VALUES ('43', '1', '', '下载', '5', '', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379502190', '1379502190', '1');
INSERT INTO `onethink_document` VALUES ('44', '1', '', '下载下载下载下载下载下载下载下载下载下载', '2', '', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379502234', '1379502234', '1');
INSERT INTO `onethink_document` VALUES ('45', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379503424', '1379503424', '1');
INSERT INTO `onethink_document` VALUES ('46', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379503430', '1379503430', '1');
INSERT INTO `onethink_document` VALUES ('47', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379503435', '1379503435', '1');
INSERT INTO `onethink_document` VALUES ('48', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379503440', '1379503440', '1');
INSERT INTO `onethink_document` VALUES ('49', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379503445', '1379503445', '1');
INSERT INTO `onethink_document` VALUES ('50', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379503462', '1379503462', '1');
INSERT INTO `onethink_document` VALUES ('51', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379503469', '1379503469', '1');
INSERT INTO `onethink_document` VALUES ('52', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379503480', '1379503480', '1');
INSERT INTO `onethink_document` VALUES ('53', '1', '', '发表话题', '4', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379505130', '1379505130', '1');
INSERT INTO `onethink_document` VALUES ('54', '1', '', '发表话题', '4', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379505174', '1379505174', '1');
INSERT INTO `onethink_document` VALUES ('55', '1', '', '发表话题发表话题发表话题发表话题发表话题发表话题发表话题发表话题发表话题发表话题', '4', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379505271', '1379505271', '1');
INSERT INTO `onethink_document` VALUES ('56', '1', '', 'RE:发表话题', '4', '', '53', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379506831', '1379506831', '1');
INSERT INTO `onethink_document` VALUES ('57', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379506888', '1379506888', '1');
INSERT INTO `onethink_document` VALUES ('58', '1', '', 'RE:发表话题', '4', '', '54', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379506986', '1379506986', '1');
INSERT INTO `onethink_document` VALUES ('59', '1', '', '图片的', '4', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507136', '1379507136', '1');
INSERT INTO `onethink_document` VALUES ('60', '1', '', 'RE:图片的', '4', '', '59', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507209', '1379507209', '1');
INSERT INTO `onethink_document` VALUES ('61', '1', '', 'RE:娃哈哈相关人士：宗庆后手指被砍“纯属谣言”', '4', '', '4', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507276', '1379507276', '1');
INSERT INTO `onethink_document` VALUES ('62', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507305', '1379507305', '1');
INSERT INTO `onethink_document` VALUES ('63', '1', '', 'RE:京津新城成“空城” 工作人员比常住业主多', '4', '', '5', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507307', '1379507307', '1');
INSERT INTO `onethink_document` VALUES ('64', '1', '', 'RE:图片的', '4', '', '59', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507357', '1379507357', '1');
INSERT INTO `onethink_document` VALUES ('65', '1', '', 'RE:图片的', '4', '', '59', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507715', '1379507715', '1');
INSERT INTO `onethink_document` VALUES ('66', '1', '', '博客', '4', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507731', '1379507731', '1');
INSERT INTO `onethink_document` VALUES ('67', '1', '', 'RE:博客', '4', '', '66', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507773', '1379507773', '1');
INSERT INTO `onethink_document` VALUES ('68', '1', '', 'RE:博客', '4', '', '66', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379507778', '1379507778', '1');

-- ----------------------------
-- Table structure for `onethink_document_model`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_document_model`;
CREATE TABLE `onethink_document_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(16) NOT NULL COMMENT '模型标识',
  `title` char(16) NOT NULL COMMENT '模型名称',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='文档模型表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- ----------------------------
-- Records of onethink_document_model
-- ----------------------------
INSERT INTO `onethink_document_model` VALUES ('1', 'Article', '文章', '0', '1378966643', '1');
INSERT INTO `onethink_document_model` VALUES ('2', 'Download', '下载', '0', '0', '1');
INSERT INTO `onethink_document_model` VALUES ('3', 'Application', '应用', '0', '0', '0');
INSERT INTO `onethink_document_model` VALUES ('4', 'Atlas', '图集', '1377569866', '1378949480', '0');
INSERT INTO `onethink_document_model` VALUES ('5', 'fdsfsf234', 'sdfsdfsfsd111', '1379151531', '1379152084', '0');

-- ----------------------------
-- Table structure for `onethink_document_model_article`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_document_model_article`;
CREATE TABLE `onethink_document_model_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型（0-html,1-ubb,2-markdown）',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL COMMENT '详情页显示模板',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表\r\n@author   麦当苗儿\r\n@version  2013-05-24';

-- ----------------------------
-- Records of onethink_document_model_article
-- ----------------------------
INSERT INTO `onethink_document_model_article` VALUES ('2', '0', '<p>OneThink欢迎您！</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('3', '0', '<p style=\"TEXT-INDENT: 2em\"><strong>被袭或因打击报复</strong></p><p style=\"TEXT-INDENT: 2em\">宗\r\n庆后家住杭州解放路某老小区，目前被砍的具体原因还在调查中。据有人爆料说，此次的被袭击，可能不是打劫，而是报复。因为前不久有媒体报道独立负责商业运\r\n作的娃哈哈商业股份有限公司(下称“娃哈哈商业公司”)出现高管集体停职待岗的现象，该公司运作实质上已陷入停滞状态。</p><p style=\"TEXT-INDENT: 2em\">“娃\r\n哈哈商业公司正进行重大内部调整，整个公司除了总经理之外，副总等领导层人员均被免职，原来的团队成员也面临着解散。”一位接近娃哈哈集团的知情人士透\r\n露，具体原因并不清楚，但与目前娃哈哈商业公司步履维艰的运营状况密切相关，宗庆后也一直对目前的商业运作班底并不满意。</p><p style=\"TEXT-INDENT: 2em\">而前不久彭博亿万富翁排行榜数据显示，万达集团股份有限公司董事长王健林已经超越宗庆后成为新的中国首富。王健林以估测资产净值142亿<a class=\"a-tips-Article-QQ\" target=\"_blank\" href=\"http://finance.qq.com/money/forex/index.htm\">美元</a>跃居中国首富之位，王健林亚太区排名第六(第一名为<a class=\"a-tips-Article-QQ\" target=\"_blank\" href=\"http://datalib.finance.qq.com/peoplestar/72/index.shtml\">李嘉诚</a>)。杭州娃哈哈集团宗庆后，估测资产净值110亿美元，退居中国第二富豪。</p><p style=\"TEXT-INDENT: 2em\"><strong>女儿：企业考虑搬出中国</strong></p><p style=\"TEXT-INDENT: 2em\">8\r\n月26日，宗庆后的女儿宗馥莉表示，娃哈哈已经到了一个“危险时期”，引以为豪的经销商体系实际已成为娃哈哈的弱势、长线产品缺失、多元化业务泛滥。同时\r\n她还给出让人诧异的回答，作为企业主，她藐视与政府打交道，她不赞同中国女性约定俗成的相夫教子。宗馥莉表示让其倍感头疼的是要花费太多精力去“跟政府打\r\n交道”，“我觉得政府需要面对我们这一代，我们这一代永远不可能像我老爸那一代一样”。</p><p style=\"TEXT-INDENT: 2em\">当媒体问及“难道要把企业整个搬到国外去吗？”宗馥莉反问，“真是有可能，你知道李嘉诚都已经搬出去了，为什么我以后不可能搬出去呢？”</p><p>据浙江大学医学院附属第二医院的一位护士透露，宗庆后在该院接受治疗，医院麻醉科主任站台负责麻醉，医院骨科主任亲自接肌腱。宗手术后住在该院国际保健中心VIP病房。</p><p><br/></p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('4', '0', '<p>娃哈哈相关人士：等一下市委市政府有一个公告，昨天这个香港商曝露的帖子全都是无中生有，根本不是，这个事是《香港商报》乱扯的，所根本不是这么一回事，省委省政府很高度重视，会有一个正面回应，到时候会以他们的名义来发。</p><p>　　昨天晚上，当记者通过微信向这位娃哈哈相关人士求证时，他非常肯定的回复：是网络谣言。</p><p>　\r\n　报道还说，医学方面人士称，肌腱断裂可以通过手术修补，后期经过康复性锻炼可以恢复手指功能，不会致残。媒体还说，宗庆后家住在杭州解放路某小区中，目\r\n前被砍的具体原因还在调查当中。根据爆料人称，这次被袭击很可能不是打击，而是报复，因为前不久有媒体报道说，独立负责商业运作的娃哈哈商业股份有限公司\r\n出现了高管集体停职待岗的现象，公司的运作实际上已经陷入停滞状态。这个报道还说，一位接近娃哈哈集团的知情人士透露说，娃哈哈商业公司正在进行重大的内\r\n部调整，公司里面除了总经理之外，副总等等领导层面人员都已经被免职，原来的团队成员也面临着解散。报料人说原因还不是很清楚，但是和目前娃哈哈商业公司\r\n艰难的运营状况密切相关，宗庆后也一直对目前商业运作班底并不很满意。</p><p>　　以上是香港媒体的报道和网友爆料的说法，具体原因现在娃哈哈方面还没有给出一个详尽的解释。另外，还有一条消息称，前不久彭博亿万富翁排行榜的数据显示，杭州娃哈哈集团的宗庆后的估测资产净值是110亿<a class=\"a-tips-Article-QQ\" target=\"_blank\" href=\"http://finance.qq.com/money/forex/index.htm\">美元</a>，已经退居到中国第二富豪的位置上。</p><p><br/></p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('5', '0', '<p style=\"TEXT-INDENT: 2em\"><span style=\"FONT-FAMILY: 楷体_GB2312\">都定位旅游度假 当年天津6个新城一起上马 </span></p><p style=\"TEXT-INDENT: 2em\"><span style=\"FONT-FAMILY: 楷体_GB2312\">被“新城”的260平方公里</span></p><p style=\"TEXT-INDENT: 2em\">恢弘的“凯旋门”、夸张的金色马车——当你站在京津新城面前，才能深切体会到中国式造城运动，原来距离我们的生活如此之近。</p><p style=\"TEXT-INDENT: 2em\">在\r\n全国范围内高密度的造城风潮中，它曾拥有诸多响亮的称号，“中国第一个由开发商打造的新城”、“亚洲最大的别墅区”等等。但开发10年来，却屡屡被形容为\r\n“亚洲第一空城”。上周末，记者赶到位于天津市宝坻区周良庄乡附近的新城。在本应人流最密集的周末，实地感受了一座空城带给人的震撼。</p><p style=\"TEXT-INDENT: 2em\"><strong>工作人员比业主多</strong></p><p style=\"TEXT-INDENT: 2em\">新城很好找。这里距离北京的天安门广场约120公里，从东四环上G1京哈高速，然后转向津蓟高速，从京津新城出口下高速，出了收费站，标志性的“凯旋门”近在眼前。</p><p style=\"TEXT-INDENT: 2em\">整个新城的规划始于10年前的2003年，开发商和宝坻区合作，做出的远景规划总面积达到260平方公里。这相当于8个澳门的面积。</p><p style=\"TEXT-INDENT: 2em\">穿\r\n过“凯旋门”，进入新城内，因为车少，这里的交通状况非常理想，即便当天有一个大型论坛在城内的酒店举行，也没出现拥堵。而且，新城内的大小道路，都没有\r\n红绿灯，有限的机动车自由行驶。距离“凯旋门”不远，就是新城的售楼处。在售楼处，来访者可以直接走上沙盘，站在项目模型中间，桃园、顺园、康园、雍\r\n园……一目了然。</p><p style=\"TEXT-INDENT: 2em\">销售小戴是位天津小伙，他热情地向记者推荐现在项目主推的“康园”片\r\n区——独栋别墅的均价在13000元/平方米。小戴说，新城的规划一共包括8000多套别墅，用了10年时间，现在建成的约3000套，建成部分的销售率\r\n很不错，至于入住率则是另一回事。记者问小戴，是否听说了新城被形容为“空城”、“鬼城”，小戴显得很有准备：“我们新城倡导的是一种‘5+2’和\r\n‘11+1’的生活理念，别墅主要是被用来当第二居所的，周末和节假日人比较多”。小戴所说的“5+2”是指5天工作、2天休闲，而“11+1”则是11\r\n个月工作、1个月度假。</p><p style=\"TEXT-INDENT: 2em\">而上周五和周六两天，记者发现，城里的客流量变化并不明显。城里最忙活的一个饭店是职工食堂，密度最大的居住区是职工宿舍区。一位清扫道路的老者告诉记者：“这里的工作人员比常住业主多得多。”</p><p style=\"TEXT-INDENT: 2em\"><strong>别墅水系忙钓鱼</strong></p><p style=\"TEXT-INDENT: 2em\">新城的户型以欧式风格的独栋别墅为主，面积均在300至400平方米左右。有些院落较大的别墅还配备游泳池。但绝大多数别墅，看上去已经很久没人打理。游泳池干涸见底，杂草爬上窗台，屋顶的油漆也不同程度地脱落。</p><p style=\"TEXT-INDENT: 2em\">虽然入住率不高，但新城的管理依旧严格，陌生人几乎不可能进入别墅区内部。新城的每个片区，都有水系与周边道路相隔，每个片区的出入口都是唯一的。一名保安告诉记者，因为常住的业主不多，所以“每辆车，我几乎都认得”。</p><p style=\"TEXT-INDENT: 2em\">在保安看来，水系像院墙一样，是保障安全的有效措施。而在其他工作人员看来，水系却成为垂钓的好去处。记者发现，新城的不少工作人员，都在下班后扛着鱼竿、拎着水桶到水系边钓鱼——这里人少、污染少、水质干净、鱼也好吃。</p><p style=\"TEXT-INDENT: 2em\">珠江北环西路，是新城内最“繁华”的商业街，当然，是相对而言。这里的门面房基本都曾经被租用过。为什么说是曾经？因为很多门面房顶着掉色的招牌，大门上锁，贴着招租或转让的告示。在白天，珠江北环西路还有些车辆来来往往，但到了夜晚就是另一番景象。</p><p style=\"TEXT-INDENT: 2em\">夜幕降临、华灯初上，新城内的主要道路被路灯照得宛如白昼，但别墅区却是漆黑一片。无论是开发较早、入住率相对最高的桃园，还是开发较晚、正在销售的康园，在夜晚都静得吓人，记者驱车在新城内转了几圈，发现亮灯的别墅屈指可数。</p><p style=\"TEXT-INDENT: 2em\"><strong>生意最好是装修公司</strong></p><p style=\"TEXT-INDENT: 2em\">在\r\n新城恢弘的造城计划中，有超过100亿元的配套设施。截至目前，酒店、高尔夫球场、温泉度假中心、大学城等已经落成。记者发现，这些落成的配套设施的利用\r\n率并不高。凯悦酒店外墙斑驳，即便有会议，也显得冷清。高尔夫球场的练习场，即使在周末也几乎没有一个人。体育俱乐部的网球场、排球场破败不堪，灯架倒\r\n塌、杂草遍地、蛛网乱挂。游乐场里，工作人员趴在售票厅里睡觉。</p><p style=\"TEXT-INDENT: 2em\">新城内有限的居民，主要依靠零散的小饭店、小超市满足生活需要。在珠江南环路，在工作时间，相邻的国家电网人工售电网点和天津<a class=\"a-tips-Article-QQ\" href=\"http://stockhtm.finance.qq.com/sstock/ggcx/600831.shtml\" target=\"_blank\">广电网络</a>(7.90,-0.23,-2.83%)营业厅都关门上锁，自2013年6月15日起暂停营业。在新城内，生意最好的是装修公司。浙江人老白的装修门面在新城已经开了5年，他自己也在新城里买了房。</p><p style=\"TEXT-INDENT: 2em\"><strong>北京嫌远天津嫌贵</strong></p><p style=\"TEXT-INDENT: 2em\">在新城的官方网站上，新城被定义为“打造京津唐地区重要的休闲旅游服务中心，以温泉疗养、会议会展、文化教育、商务<a class=\"a-tips-Article-QQ\" href=\"http://finance.qq.com/l/financenews/jinrongshichang/jinrong.htm\" target=\"_blank\">金融</a>为特色的现代服务业基地”。而且官方认为，新城的区位优势明显，“位于北京、天津、唐山三大城市的核心区域，处在环渤海经济圈发展的腹地”。</p><p style=\"TEXT-INDENT: 2em\">新城13000元/平方米的均价，比宝坻区中心新房6000元/平方米的均价高很多。老白说，“说起来，这里离北京并不近，开车少说也要1个半小时。而且，这里是天津市，有限购政策，北京人过来买房还得有纳税证明。”</p><p style=\"TEXT-INDENT: 2em\">老\r\n白买的是桃园的二手别墅，均价7500元/平方米，比正在销售的康园13000元/平方米的均价低很多。“要是真想买，肯定买二手的啊，反正也没人住过，\r\n新房太贵，天津市区的房子才多少钱啊？”记者查询到，天津市范围内，均价13000元/平方米以下的普通住宅超过200个。</p><p style=\"TEXT-INDENT: 2em\"><strong>“孪生兄弟”还有5个</strong></p><p style=\"TEXT-INDENT: 2em\">2003\r\n年，开发商拿下了这个当时叫“宝邸温泉度假村”的新城项目，计划投入120亿元资金，在这片盐碱地上建设一个可居住50万人的新城。要知道，宝坻全区现在\r\n的常住人口才79.9万。据报道，当初拿地时只付出了每平方米78元的代价。但是新城，只是天津市11个规划新城中的一个。其中，在2007年天津市将土\r\n地自主权从区县收回前，大量定位重复的新城项目上马。和新城同样涉及旅游、度假定位的，还有5个。</p><p>2006年天津<a class=\"a-tips-Article-QQ\" href=\"http://stockhtm.finance.qq.com/hcenter/index.htm?page=1020178\" target=\"_blank\">滨海新区</a>的规划出炉，天津城市发展的重点东移。2008年通车的京津高铁，又“甩开”了宝坻。</p><p><br/></p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('7', '0', '<p><span style=\"\">香港是一个国际性城市，也是社会开放的城市，受到了世界和社会方方面面的关注。香港高度发达的新闻媒介更是风尖浪口。许多香港以外的社会团体，政府部门和社会权威人士都对香港的媒介做出了自己独特而值得研究的媒介批评。<br/><br/>　\r\n　因而，香港媒介批评受到了更多的两岸三地的影响，来自内地和中国台湾的批评之声，构成了香港媒介批评的重要组成部分。李金铨提出的香港新闻业的“香港性\r\n与大陆性”。在香港媒介批评中，香港媒介批评中也表现出这种“香港性与大陆性”，这一香港媒介批评的特点，不同于内地，也不同于台湾。深刻地根植于香港独\r\n特的本土政治经济文化制度之上，其中的表现极具参考价值和研究意义。无论此种意义是理论的，还是现实的，都表现了香港媒介批评开放性的另一面。<br/><br/>　　所以，香港媒介的批评主体各有不同，但是有些批评者却特别引人注目，而他们发出的批评效果也是较为显著的。这些批评主体，一方面是因为他们位高权重，另一方面是他们背后代表了一种社会批评媒介的批评立场和批评倾向。<br/><br/>　　具体到分析来，就是：<br/><br/>　　1，&nbsp;&nbsp;社会权威人士，政府高官或者行政领导人；<br/><br/>　　2，&nbsp;&nbsp;媒体的老板，甚至就是“媒体大亨”。<br/><br/>　　<strong>一、社会权威人士，政府高官或者行政领导人<br/></strong><br/>　　其中，一些中央政府领导人的谈话，是对香港媒体的直接批评，这些批评话语直接带有着强烈的“新闻政策”的意味。比如：<br/><br/>▲&nbsp;邓小平在香港回归前谈到香港的未来时曾说：“共产党人是骂不倒的”，表示了中央政府在香港的传媒政策：中央努力保障香港的新闻自由。<br/><br/>▲&nbsp;周恩来总理五十年代批评香港的中方报纸时说：“不要你们在香港办党报！”<br/><br/>▲&nbsp;陈毅副总理说：“若要你们在香港办党报，倒不如叫《人民日报》去搞个分社，干脆把《人民日报》拿到香港去印发。”<br/><br/>▲&nbsp;廖承志曾对原新华社香港分社黄作梅社长说：“我们在香港办报，决不能脱离香港这个商业社会的实际，决不能脱离香港同胞的思想意识和政治水平，以及他们的风俗习惯和喜恶爱憎，特别是要适合所谓‘中间落后大多数’人的需要。”<br/><br/>▲&nbsp;在\r\n一次中央外办港澳组会议上，廖承志曾提出：“我们在香港的报纸要走社会化的道路，不要搞党报左报，老摆一张红面孔、一副‘爱国主义’的架子。在香港要内外\r\n有别，不能照搬国内三百六十天突出政治，板起面孔教训人，把报纸办成教科书，把你们自己扮成训导主任。香港读者不是小孩子、小学生了，他们怎么愿意来亲近\r\n你们、来上你们的课、听你们的教训呢？所以，我们香港的报纸一定要办成香港化、社会化，要办成香港人喜闻乐见，做他们的良伴益友，请各位注意，我是说良伴\r\n益友，不是‘良师益友’。”<br/><br/>▲&nbsp;而与会的香港老报人李子诵建议说：“社会化就是今后要为香港人办报纸。一个‘为’字，一个‘对’字，立场和方法就完全不同了，‘对’字站在对面，‘为’字进入香港人社会，报纸就变成香港人的报纸了。”<br/><br/>　\r\n　以上这些，中央政府领导人和中国共产党的老报人对香港左派报纸的直接批评，这些批评无疑具有行政指导和办报政策的意味，直接对香港的左派报纸形成了强大\r\n的批评效果。这样的批评都根植在其特殊的批评主体和特殊的批评对象的属性之中的，表现了香港媒介批评的重要一面：政治左右媒介批评话语，而香港媒介批评现\r\n象和活动中都包含了强烈的政治性因素。<br/><br/>　　1965年香港爆发了左派报纸与《明报》关于“要核子，还是要裤子“的争论，这一批评特点在这一次论战中表现的分明。<br/><br/>　　1963年10月，时任国务院副总理兼外交部长的陈毅元帅在北京对日本记者团发表了著名的“核裤论”，驳斥苏联的嘲弄，并郑重声明，不管中国有多穷，“我当了裤子也要造核子！”&nbsp;<br/><br/>　　对此，《明报》老板金庸在《明报》发表《要裤子不要核子》的社评，从经济角度，反对在贫穷情况下造原子弹。<br/><br/>　\r\n　此论一出，引起了一场轩然大波。香港《大公报》、《文汇报》、《新晚报》、《香港商报》、《晶报》等左翼报纸批评《明报》，并对金庸进行大肆的人身攻\r\n击，骂他是“汉奸”、“走狗”、“卖国贼”，骂《明报》“造谣生事”、“反共反华”、“亲英崇美”、“背叛民族”等等，一时“红云压报”。[2]《明报》\r\n与左派报纸发生了轰动一时的论战，但“奇怪的是，之后，左翼报纸猛烈的炮火突然停止了。”[3]<br/><br/>　　原因是，陈毅元帅发表了一次谈话，对香港左派报纸，做出了一次特殊的“威权批评”。<br/><br/>　　陈毅说：“《明报》那个社论，要中国人有裤子穿，那还是爱中国人的嘛！”。<br/><br/>　\r\n　在国共两党冷战的政治压倒性话语下，中央政府的领导人对香港媒介，特别是左派报纸有着行政命令式的批评。陈毅的这番话，看是针对《明报》社论的评价，也\r\n代表了对《明报》老板查良镛的批评，更是直接对左派报纸攻击《明报》这一媒体行为的批评。媒介批评威权的威权批评达到了立竿见影的效果，陈毅的谈话发表\r\n后，左派报纸停止了对《明报》的攻击。<br/><br/>&nbsp;　　同样，还有一类批评是针对非左派传媒，也较为引人注目。<br/><br/>　　2000年10月27日，江泽民在对香港记者的新闻发布会上，直接批评香港记者太幼稚。“对香港记者的新闻发布会上，江泽民发火了。当时，一位香港记者问江，他支持董建华连任香港特首是否就等于‘钦点’。”[4]<br/><br/>　　据当时提问的香港有线电视女记者张宝华事后回忆：<br/><br/>　\r\n　“但当记者问到欧盟报告说，北京有渠道干预特区法治时，江泽民便严肃起来，他说没有听过，马上有记者补充说是前港督彭定康说的，此时，江泽民已提高声\r\n调，指传媒不要‘看见风就是雨，收到消息，媒体本身也要有判断，不要的东西把它再说一遍’这时，江泽民已开始动气了，会堂上刚巧有半秒钟的寂静，我马上抓\r\n住机会问他：‘中央这么早说支持董先生连任，会否给外界认为是钦定了董先生呢？’”[5]<br/><br/>　　江泽民马上否认了这种说法，并批评香港传媒道：<br/><br/>　　“我感到你们还要学习，你们非常熟悉西方的那一套，但毕竟不一样，你们毕竟too&nbsp;young&nbsp;(太年轻)，…其实媒体还要提高自己知识水平。”<br/><br/>　　“唉，我真为你们着急，真的，你们有个好的，你们跑到世界各地（报道新闻），你们比西方记者跑得还快。但问来问去的问题都too&nbsp;simple,&nbsp;sometimes&nbsp;na?ve(太简单，有时很幼稚)!你们理解不?”[6]<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;此种政策性行政批评带有强烈威权个体性格，这些批评的发出都激起了香港传媒的纷纷议论和对香港新闻自由的忧虑。由于这些批评是作为政府行政官员的个体发出的，所以带着个人色彩，更有着不同一般“政策性行政批评”的批评形态。<br/><br/>　\r\n　而在此之前，香港回归之际，中国国务院港澳办公室主任鲁平批评香港传媒，“在&nbsp;九七年后的舆论，不可以引起推翻中国政府的行动”，“鲁平接受香港电子传\r\n媒访问时表示，香港传媒可以批评北京政府，也可以反对中方政策，“说甚么都可以，但如果诱发行动，就得小心……如果传媒真的想推翻中国政府，那就另当别\r\n论。”[7]<br/><br/>　　而在另一次事件中，台湾驻港代表中华旅行社总经理郑安国上月在“香港电台”节目中谈论“特殊国与国关系论”后，引起香港社会极大争议。<br/><br/>　　港府新闻统筹专员林瑞麟公开指摘郑安国在港台节目发表的言论，与其身分不符。钱其琛今日在北京接见香港特区律政司司长梁爱诗时首次明确表示，在香港不能公开宣传“两国论”，发表支持“两国论”的言论将违反“一个中国”原则。&nbsp;<br/><br/>　　中共国务院副总理钱其琛表示，在香港公开宣传“两国论”将违反“一个中国”原则及中央有关政策，香港民主派人士及媒体都担心这番讲话会导致特区政府收紧言论和新闻自由的尺度，官方“香港电台”更发表声明指出，在不抵触法律的前提下，任何人有权发表言论。<br/><br/>　　不但是中央政府的官员，而香港的官员也有此类表现。<br/><br/>　　2000年4月，两会召开期间，香港的全国政治协商会委员徐四民及王凤超两位先生职责香港新闻媒介报道吕秀莲有违“一国两制”言论，而引起的指责。由于两位先生身分特殊，立刻引起香港人对中国落实“两制”的关注。<br/><br/>　　香港中文大学新闻传播学院院长李少南在《苹果日报》网络版评论道：<br/><br/>　\r\n　“香港长久以来实行的新闻制度是以西方模式为蓝本，新闻人员的自主权不单受到尊重，还受到法例保障。政府官员像其它人一样，可以批评媒介，但不能指令媒\r\n介报道什幺、不报道什幺或怎样报道。日前两位先生虽不至于“指令”本港媒介报道什幺，但由于他们身分特别，因此令人忧虑香港新闻自由及“两制”的前景。徐\r\n王二人希望传媒作打手，事实上，细看两位先生的言论，他们并非说不能报道吕秀莲的言论，而是要把她的言论与“一般的不同声音”区分开来。怎样区分开来？从\r\n两位的意思看，就是要像中国官方的《人民日报》那样，大肆鞭挞这种言论。换言之，两位先生真正希望的是香港媒介充当中国政府的‘啦啦队’，甚或‘打手’角\r\n色。但是，做政府‘打手’并非香港媒介的一贯角色。香港媒介一向的角色是作为各方传递信息的中间人。它们当然也有评论，但评论与新闻消息报道，一般分清界\r\n线。”[8]<br/><br/>　　可见，一些“威权批评”并不得到另一些媒介批评主体的认可，在香港这个开放的社会，不同媒介批评的主体的“协商规范”也体现了媒介批评主体的个性和立场。<br/><br/>　　<strong>二、媒体内部行政“威权批评”</strong><br/><br/>　　香港的传媒，98%是私营的。作为私营媒体，其商品性特征非常明显，有时为争取读者、听众、观众不惜降低品位迎合读者。由于主办单位是团体或私人投资，各个单位的背景不同，立场有异，因此也或多或少会影响到作风不同，表达的言论观点取向也不同。&nbsp;<br/><br/>&nbsp;　\r\n　所以，尽管倡导新闻自由，作为私营企业，媒体的运作还是在老板的意愿下进行的。一位香港资深新闻工作者结合行业的角色与组织两个层面考察以后，认为，香\r\n港中文报纸运作的常态典范，是“一人报纸”，报纸为一个人服务。报纸管理层是要贯彻老板的意愿，整个报社组织，都是环绕着老板的意愿来运作的。&nbsp;<br/><br/>　　但是，也有人对这种看法大摇其头。毕竟，传媒是一项影响大众的社会舆论工具，即使为某一老板私有，要在倡导新闻自由和客观公正的新闻界立足，是要尊重行规的。<br/><br/>　　可是有一点却是毋庸质疑的，“新闻机构的方针政策，却完全由机构的主持人决定，谁出钱，谁话事，资本主义社会都是如此，新闻工作者，并与例外。”[9]<br/><br/>　　结合行业的角色与组织两个层面来考察，也可以得出一个香港中文报纸运作的常态典范，即“一人报纸”。<br/><br/>　\r\n　报纸为一个人而服务。报纸管理层是要贯彻老板的意愿；报纸内容的转变，反映出老板对社会转变之评估。整个报社组织都是围绕老板的意愿来运作。说来讽刺的\r\n很，香港报业在19世纪诞生以来，报社的规模只有几个人，故此报纸明显的带有办报人的好恶，当时的报纸被视为“一人报纸”，然而，经历了一百五十多年，尽\r\n管报社的规模扩大数百倍，报纸的本质，仍然是“一人报纸”。<br/><br/>　　金庸创办的《明报》一直被视为“知识分子报纸”，但谈到，对新闻媒体的控\r\n制，金庸曾表示“报纸是老板的私器”，“新闻自由其实是新闻事业老板所享受的自由，一般新闻工作者非听命于老板不可。&quot;所以在他看来，&quot;新闻自由，是报社\r\n员工向外争取的，而不是向报社內争取的。报社內只有雇主与雇员的关系，并没有谁向谁争取自由的关系。”<br/><br/>　　“他平时不大喜欢说话，在《明报》常常以字条、写信发号施令，以笔代口表达他的声音是他的拿手戏。”&nbsp;<br/><br/>　\r\n　此外,在广东省内大亚湾兴建核电厂也引起社会及中港关系强烈分化。其时,《明报》及查良镛均积极参与其内。曾任《明报》督印人的吴霭仪有这样体会：“社\r\n评涉及中英，中港的事情，永远不是一个社评主笔的事情，而是查良镛许可的立场。如香港的直选，民主建设的速度等问题，都要与他讨论后才执笔，要经过潘粤生\r\n（总编辑）修改。或是经徐东滨（主&nbsp;笔）修改，才能见刊。”&nbsp;<br/><br/>　　与文人气的金庸相比，星系报纸（比如著名的《星岛日报》）创办人，著名实业家胡文虎表现更是有江湖气的。<br/><br/>　\r\n　“胡文虎是讲义气的，星系报时常刊登揭露国民党政治黑暗面，抨击当局时政的文章，胡文虎有时候还亲笔撰文，直言不讳地指出时弊。但他却对星岛报业的编辑\r\n记者说：“蒋介石称我为朋友的，请我吃过饭，照过相，我的报纸骂他不好，不够朋友。蒋介石不好骂，其他人可以骂。”[10]<br/><br/>　　<strong>三、社会威权批评<br/></strong><br/>&nbsp;&nbsp;&nbsp;&nbsp;一\r\n些香港社会的贤达人士也是香港媒介批评的威权批评的主体。香港长期以来就有社会领袖和士绅阶层，港英政府也在扶植作为“社会粘合剂”的华人领袖，给予他们\r\n社会地位和社会头衔，比如授予爵士，太平绅士之类的。香港社会领袖的威权得以建构，加上他们社会团体的地位和行业地位，由此，他们发出的批评也具有了非同\r\n一般民众的“威权效果”。<br/><br/>　　在报导新闻上经常引起争议的香港苹果日报，2002年就遭到著名艺人成龙公开抨击其新闻道德操守。<br/><br/>　　事缘《苹果日报》上星期在报导一宗补习社老师涉嫌性侵犯小童案时，刊登了怀疑受淫辱小朋友的照片，令其家人十分不满；而香港一些媒体及社会团体对该报的报导手法也不敢苟同。<br/><br/>　　“2002年年底挺身支持影星刘嘉玲而攻击东周刊的成龙，这次也毫不保留地带头公开批评苹果日报。根据星岛日报今天刊登他的一则专访，成龙竟然激烈地形容苹果日报“为了利润而不顾廉耻”，又呼吁公众人士站出来声讨其劣行，并要求该集团作公开道歉。”<br/><br/>　　“据引述，成龙甚至用“淫媒”来形容该报。”<br/><br/>　\r\n　“他声称，‘他们(苹果日报和姊妹壹周刊)已经天不怕、地不怕，为了利润不顾廉耻刊登这些照片，罚款数千元对他们已经没有作用，他们也懂得走法律空\r\n隙……希望政府订立一些法律，专门针对那些传媒刊登伤害小孩的照片、造新闻、作大、虚报失实等恶行，施加很重的惩罚’。”[11]<br/><br/>　　成龙又表示，“令人感到愤怒的是，刊出照片可能祸及受害小朋友的一生，那些令人恶心的照片给注销来，那些小朋友以后如何生活下去？长大后怎样面对朋友？就这么一刊登，人家可能给害得跑去自杀，他们怎样担当得起这些严重后果”！<br/><br/>　　近年成龙已多次公开抨击该报及壹周刊，但以这次最为激烈。而在2002年“《东周刊》事件”中多位社会名流如著名导演吴思远，香港立法会八大党派和许多社会团体领袖也随即发表声明，对《东周刊》的行径表示不齿和谴责。这些社会威权批评更多具有“舆论领袖”的意味。<br/><br/>　　<strong>四、解析香港媒介“威权批评”</strong><br/><br/>　　这种政府行政，媒体内部行政和社会领袖而派生出来的有权威个人所主宰的批评范式，本文称之为“威权批评”。<br/><br/>&nbsp;　\r\n　“威权”[12]（authority）有别于一般的我们日常生活的“权威”[13]之说，（当然有也相当程度的联系），接近于台湾学者的“东方威权社\r\n会”概念。[14]“威权”的语意重点落在权上，更多是指政体和政制的制度性权力，而此中“权力”包含了政治权力和商业权力。<br/><br/>　　媒介一方面受媒介外部政治威权的批评，另一方面受到来自媒介内部的商业权力的威权批评，而这两种威权批评构成了对媒介批评范式的重要方面，东西方媒介皆然如此。<br/><br/>　　运用这个概念不仅可解释在单一政治意识形态主宰下的改革开放前的中国内地社会，也可以解释在报禁前的中国台湾社会，更能解释在政治权力和商业权力双重压力下的香港媒介。<br/><br/>　　“威权批评”多起到政治学上意味的CHILL&nbsp;EFFEC，寒颤效应，禁声效应。<br/><br/>　　媒介批评学者刘建明认为，“这种权威主宰的批评范式，导致这样一种结果：把批评当成一种既定的事实，由特殊人物确定它和生活和报道的关系，很少分析从事件到新闻作品的复杂演进过程，以及在这个过程中记者和受众的思维特性。”[15]<br/><br/>　　“在权威主宰的范式里，媒介批评缺少记者和受众的地位，被广大受众欢迎的新闻很可能被视为有害的消息而受到非议。由于没有正确的批评观，媒介批评的权利被权威独占，批评的标准可能被少数人的非理性的解读所左右。”[16]<br/><br/>　　而在计划经济体制下，这个表现的更为强烈，“在计划经济体制下，批评是领导思想的代言工具和指挥棒，批评家往往也是各级主管领导或与新闻媒体有着千丝万缕联系的各级人员，难免形成舆论一律。”[17]<br/><br/>　\r\n　其实不仅是本身的权威性构成了“威权批评”的效果，而且在批评过程中也建构了自身的权威。比如美国学者詹森认为媒介批评也具有神话，传说和民间故事的特\r\n性，就是因为他把媒介批评理解为更为一般性的话语，认为媒介批评动员并再生产了有关历史，文化，社会和技术的预设。[18]<br/><br/>　　对于社会威权批评，可从叙事学的角度做出了分析。<br/><br/>　\r\n　“利奥塔认为，在讲述者，聆听者和指涉物之间存在一种特定的关系：讲述者处于‘知者’或者‘智者’的地位，因而具有相对的权威性；聆听者的角色定位只是\r\n表示赞同或者反对，当讲述者的权威性不容质疑时，聆听者无需参与讨论或证实；而指涉物则以独特的定义方式来回应。比如，当有人批评说：这一新闻报道是不客\r\n观的，批评者显示了自己对这一报道和报道的客观性要求的知识，他对指涉物的性质——新闻报道——的影响力即体现在他对指涉物的界定和判断上。事实上，他已\r\n经宣布了指涉物的性质：不客观。在如此宣布的同时，他被赋予了一种权威。”[19]<br/><br/>　　媒介批评学者谢静还认为“甚至讲述规范者本身也不\r\n是目的，而是批评者借此树立自己的权威：对聆听者和指涉物（批评对象）的权威，从而实现自己的权威。曾专门研究美国新闻媒介批评的布朗认为，批评具有社会\r\n控制和合法化的功能。”比如1969年副总统阿格纽对“东部权势集团的偏见”的批评。<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;威权批评由于自身的权威性和专业性构成了强大的批评效果，而且进一步，是否自身的权威性是否就构成了媒介批评的正确和积极后果，值得人们认真思考。<br/><br/>　\r\n　雷蒙.威廉斯认为：“目前尚未被质疑的就是‘权威式的评论（authoritative&nbsp;judgment）’的假说。问题的症结不仅在于批评与挑剔两\r\n者之间的关系，而且在于‘批评’与权威式的(authoritative)评论两者之间存在着更基本的相关性；二者皆被视为普遍的，自然的过程。作为表示\r\n社会的或专业的普遍化过程的一个词汇，criticism是带有意识形态的，不只是因为它具有消费者的立场，而且是因为它通过一系列抽象词等来隐藏这种身\r\n份立场。”[20]<br/><br/>　　“然而事实上，criticism需要被解释为一种具特殊性的反应，而不是被视为一个抽象的‘判断’。在复杂而活跃的关系与整个情境，脉络里，这种反应——不管它是正面或负面——是一个明确的实践(practice)。”[21]<br/><br/>　　由此，媒介的威权批评不仅是媒介问题，更涉及到了更广层面的社会问题。谢静指出“不过，在对媒介的公开批评中，无论持何种立场的讲讲述者都不可能垄断话语权力，在批评的过程中讲述者通过具体事件来协商规范，从而协商话语权。”[22]<br/><br/>　　但现实生活的事实总有点不遂人类美好愿望。通过分析香港媒介批评威权批评，本文发现，这一批评范式完全表现了香港媒介批评被香港特殊的政治经济压力下，扭曲常态，左右摇摆的个性。<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;<strong>后&nbsp;&nbsp;续：</strong>回\r\n到“超级女生”话题上，本人认为，“超级女生”本身就是精英文化控制大众文化的典型个案，而不是什么“大众文化对精英文化的反动”[23]。这种精英更具\r\n有了商业文化消费的意味。“超级女生”挨批是媒介批评的热潮的一个表现；而如何去理解这些越来越多的复杂批评现象，对于媒介批评的批评性研究才刚刚开始。</span></p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('10', '0', '<p>浙江南浔2协警在宾馆趁女子醉酒不省人事之时实施强奸，南浔法院根据犯罪事实，考虑到两人属临时性的即意犯罪，事前并无商谋，且事后主动自首，并取得被害人谅解，给予酌情从轻处罚，判决两被告各入狱三年。（中国新闻网）<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/> &nbsp; &nbsp; &nbsp;这些年，“临时”一词很是流行，这个“临时”、那个“临时”的，在各个部门“开花结果”，其效果特别的如领导之意，让一些单位屡用屡爽，甜头如美酒一般，成了推却责任的挡箭牌。<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/>\r\n &nbsp; 南浔法院根据犯罪事实，给两个强奸犯定的属“临时性的即意犯罪”，令辽河鱼迷糊了，这是个啥概念？搜遍了网络，也没找到“临时性的即意犯罪”的来源和条款\r\n依据。“临时”既是非正式的和短时间的行为，难道强奸犯罪还有“非正式”和时间的长短之分？这个“临时性的即意犯罪”应是个新名词，可以为我国的司法界又\r\n填补了一项创造性的空白，可喜可贺。<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/>\r\n &nbsp; &nbsp;搞不明白，犯罪还有临时、固定、长期之分？在被害人不清醒的情况下，两人轮番实施强奸，为什么不是轮奸？难道就是因为得到了被害人的谅解？这个谅解是怎么\r\n得到的？相信“金钱封嘴”的肯定的。如果这个判决能成为一个新的榜样，那以后再出现这样的“临时性的即意犯罪”，是否也要得到“从轻处罚”呢？要是在这\r\n样，可以再全国推而广之，以便广大犯罪分子都去进行“临时性的即意犯罪”。<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/> &nbsp; 将女灌醉，然后带她去开房，强奸了她，明显就是有预谋的犯罪，什么是“即时性”？就是即时就发生性关系。临时性的犯罪就可以“从轻判决”？那哪个犯罪不是“临时性”的？是否都要可以轻判？<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/> &nbsp; 其犯罪算是“临时性的即意犯罪”，那判了3年，笔者想也应该是“临时性的即意判决”。<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/> &nbsp; 协警，就是协助警察执法，可以叫做“临时性”协助执法，但犯了罪咋还能变成“临时性”犯法呢。<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/>\r\n &nbsp; &nbsp;“事前并无商谋，且事后主动自首”。真是一个好榜样的强奸犯，不愧是协警出身，被害人不报案不追究，自已就去主动投案自首，这样的强奸犯真的天上难找、地\r\n下难寻。辽河鱼看对他们根本都不用判刑，理应树为投案自首的楷模进行宣传，还要发点奖金为好，以资鼓励。要是判刑，也应该是判三缓二，这样才显出我们的人\r\n性化判决。<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/> &nbsp;联想到那个因爬树偷看女人而被判强奸的男人，要是在“临时性的即意犯罪”创造的法院，怎能会被判为有罪之人？可怜你生错了地方，这不怨你。<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/> &nbsp;看看女人都能判罪，这个两人同时强奸一个女人的案件，却判了一个“临时性的即意犯罪”，实在是高。不服法官的创造性是不行的。<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/> 绝地相信这“临时性的即意犯罪”很快就能与“躲猫猫”、“俯卧撑”“70码”等等吧，成为网络流行词语。<br/><span class=\"hidden_white\" style=\"color:#FFFFFF;\">人民网强国社区(http://bbs.people.com.cn)</span><br/> &nbsp;按此“临时性”，以后也可以出现“临时性打死人”、“临时性踢死人”、“临时性被自杀”、“临时性行贿受贿”、“临时性醉驾”、“临时性抢劫”、“临时性盗窃”，一切犯罪行为都用“临时性”为借口来减轻刑罚，可以想象，我们一个“临时性”的时代就要到来了。</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('11', '0', ' 2003 年，开发商拿下了这个当时叫“宝邸温泉度假村”的新城项目，计划投入120亿元资金，在这片盐碱地上建设一个可居住50万人的新城。要知道，宝坻全区现在 的常住人口才79.9万。据报道，当初拿地时只付出了每平方米78元的代价。但是新城，只是天津市11个规划新城中的一个。其中，在2007年天津市将土 地自主权从区县收回前，大量定位重复的新城项目上马。和新城同样涉及旅游、度假定位的，还有5个。', '');
INSERT INTO `onethink_document_model_article` VALUES ('12', '0', ' 2003 年，开发商拿下了这个当时叫“宝邸温泉度假村”的新城项目，计划投入120亿元资金，在这片盐碱地上建设一个可居住50万人的新城。要知道，宝坻全区现在 的常住人口才79.9万。据报道，当初拿地时只付出了每平方米78元的代价。但是新城，只是天津市11个规划新城中的一个。其中，在2007年天津市将土 地自主权从区县收回前，大量定位重复的新城项目上马。和新城同样涉及旅游、度假定位的，还有5个。', '');
INSERT INTO `onethink_document_model_article` VALUES ('13', '0', ' 2003 年，开发商拿下了这个当时叫“宝邸温泉度假村”的新城项目，计划投入120亿元资金，在这片盐碱地上建设一个可居住50万人的新城。要知道，宝坻全区现在 的常住人口才79.9万。据报道，当初拿地时只付出了每平方米78元的代价。但是新城，只是天津市11个规划新城中的一个。其中，在2007年天津市将土 地自主权从区县收回前，大量定位重复的新城项目上马。和新城同样涉及旅游、度假定位的，还有5个。', '');
INSERT INTO `onethink_document_model_article` VALUES ('14', '0', ' 据浙江大学医学院附属第二医院的一位护士透露，宗庆后在该院接受治疗，医院麻醉科主任站台负责麻醉，医院骨科主任亲自接肌腱。宗手术后住在该院国际保健中心VIP病房。\r\n\r\n\r\n', '');
INSERT INTO `onethink_document_model_article` VALUES ('15', '0', ' 据浙江大学医学院附属第二医院的一位护士透露，宗庆后在该院接受治疗，医院麻醉科主任站台负责麻醉，医院骨科主任亲自接肌腱。宗手术后住在该院国际保健中心VIP病房。\r\n\r\n\r\n', '');
INSERT INTO `onethink_document_model_article` VALUES ('16', '0', '  据浙江大学医学院附属第二医院的一位护士透露，宗庆后在该院接受治疗，医院麻醉科主任站台负责麻醉，医院骨科主任亲自接肌腱。宗手术后住在该院国际保健中心VIP病房。 ', '');
INSERT INTO `onethink_document_model_article` VALUES ('17', '0', ' 2003 年，开发商拿下了这个当时叫“宝邸温泉度假村”的新城项目，计划投入120亿元资金，在这片盐碱地上建设一个可居住50万人的新城。要知道，宝坻全区现在 的常住人口才79.9万。据报道，当初拿地时只付出了每平方米78元的代价。但是新城，只是天津市11个规划新城中的一个。其中，在2007年天津市将土 地自主权从区县收回前，大量定位重复的新城项目上马。和新城同样涉及旅游、度假定位的，还有5个。', '');
INSERT INTO `onethink_document_model_article` VALUES ('18', '0', '报道还说，医学方面人士称，肌腱断裂可以通过手术修补，后期经过康复性锻炼可以恢复手指功能，不会致残。媒体还说，宗庆后家住在杭州解放路某小区中，目\r\n前被砍的具体原因还在调查当中。根据爆料人称，这次被袭击很可能不是打击，而是报复，因为前不久有媒体报道说，独立负责商业运作的娃哈哈商业股份有限公司\r\n出现了高管集体停职待岗的现象，公司的运作实际上已经陷入停滞状态。这个报道还说，一位接近娃哈哈集团的知情人士透露说，娃哈哈商业公司正在进行重大的内\r\n部调整，公司里面除了总经理之外，副总等等领导层面人员都已经被免职，原来的团队成员也面临着解散。报料人说原因还不是很清楚，但是和目前娃哈哈商业公司\r\n艰难的运营状况密切相关，宗庆后也一直对目前商业运作班底并不很满意。', '');
INSERT INTO `onethink_document_model_article` VALUES ('19', '0', '<strong><em>报道还说，医学方面人士称，肌腱断裂可以通过手术修补，后期经过康复性锻炼可以恢复手指功能，不会致残。媒体还说，宗庆后家住在杭州解放路某小区中，目\r\n前被砍的具体原因还在调查当中。根据爆料人称，这次被袭击很可能不是打击，而是报复，因为前不久有媒体报道说，独立负责商业运作的娃哈哈商业股份有限公司\r\n出现了高管集体停职待岗的现象，公司的运作实际上已经陷入停滞状态。这个报道还说，一位接近娃哈哈集团的知情人士透露说，娃哈哈商业公司正在进行重大的内\r\n部调整，公司里面除了总经理之外，副总等等领导层面人员都已经被免职，原来的团队成员也面临着解散。报料人说原因还不是很清楚，但是和目前娃哈哈商业公司\r\n艰难的运营状况密切相关，宗庆后也一直对目前商业运作班底并不很满意。</em></strong>', '');
INSERT INTO `onethink_document_model_article` VALUES ('20', '0', '<img src=\"/Uploads/Editor/2013-09-18/523973506b1b7.jpg\" alt=\"\" />', '');
INSERT INTO `onethink_document_model_article` VALUES ('21', '0', 'dfdfdfdf', '');
INSERT INTO `onethink_document_model_article` VALUES ('22', '0', ' adsfasdfasdf', '');
INSERT INTO `onethink_document_model_article` VALUES ('23', '0', ' 据浙江大学医学院附属第二医院的一位护士透露，宗庆后在该院接受治疗，医院麻醉科主任站台负责麻醉，医院骨科主任亲自接肌腱。宗手术后住在该院国际保健中心VIP病房。', '');
INSERT INTO `onethink_document_model_article` VALUES ('25', '0', '一楼 哈哈', '');
INSERT INTO `onethink_document_model_article` VALUES ('26', '0', '<p>测试1</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('27', '0', '<p>测试2</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('28', '0', '<p>测试3</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('29', '0', '<p>测试4</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('30', '0', '<p>测试5</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('31', '0', '<p>测试6</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('32', '0', '<p>测试7</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('33', '0', '<p>测试8</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('35', '0', '<p>测试9</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('36', '0', '<p>测试10</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('37', '0', '<p>测试11</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('38', '0', '<p>这是在后台的回复哦</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('39', '0', '<p>大幅度f</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('40', '0', '<p>234234<br/></p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('42', '0', '<p>dfdfdfdf</p>', '');
INSERT INTO `onethink_document_model_article` VALUES ('45', '0', '大幅度f大幅度f大幅度f大幅度f', '');
INSERT INTO `onethink_document_model_article` VALUES ('46', '0', '大幅度', '');
INSERT INTO `onethink_document_model_article` VALUES ('47', '0', '大幅度', '');
INSERT INTO `onethink_document_model_article` VALUES ('48', '0', '大幅度', '');
INSERT INTO `onethink_document_model_article` VALUES ('49', '0', '大幅度', '');
INSERT INTO `onethink_document_model_article` VALUES ('50', '0', '大幅度', '');
INSERT INTO `onethink_document_model_article` VALUES ('51', '0', '大幅度', '');
INSERT INTO `onethink_document_model_article` VALUES ('52', '0', '大幅度', '');
INSERT INTO `onethink_document_model_article` VALUES ('53', '0', '发表话题发表话题发表话题发表话题发表话题发表话题发表话题发表话题发表话题', '');
INSERT INTO `onethink_document_model_article` VALUES ('54', '0', '发表话题发表话题发表话题发表话题发表话题发表话题发表话题发表话题发表话题发表话题', '');
INSERT INTO `onethink_document_model_article` VALUES ('55', '0', '<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>\r\n<h2>\r\n	发表话题\r\n</h2>', '');
INSERT INTO `onethink_document_model_article` VALUES ('56', '0', '大幅度反对法', '');
INSERT INTO `onethink_document_model_article` VALUES ('57', '0', '454545', '');
INSERT INTO `onethink_document_model_article` VALUES ('58', '0', '11111111111', '');
INSERT INTO `onethink_document_model_article` VALUES ('59', '0', '<img src=\"/Uploads/Editor/2013-09-18/52399bb3d8853.png\" alt=\"\" />', '');
INSERT INTO `onethink_document_model_article` VALUES ('60', '0', '图片挺不错', '');
INSERT INTO `onethink_document_model_article` VALUES ('61', '0', '&lt;script&gt;alert(\'ddd\')&lt;/script&gt;', '');
INSERT INTO `onethink_document_model_article` VALUES ('62', '0', '&lt;script&gt;alert(\'ddd\')&lt;/script&gt; teest', '');
INSERT INTO `onethink_document_model_article` VALUES ('63', '0', '&lt;script&gt;alert(\'ddd\')&lt;/script&gt; teest', '');
INSERT INTO `onethink_document_model_article` VALUES ('64', '0', '&lt;script&gt;alert(\'dd\');&lt;/script&gt; dfdfdf大\'幅度\'\"', '');
INSERT INTO `onethink_document_model_article` VALUES ('65', '0', '图片挺不错', '');
INSERT INTO `onethink_document_model_article` VALUES ('66', '0', '图片挺不错', '');
INSERT INTO `onethink_document_model_article` VALUES ('67', '0', '图片挺不错', '');
INSERT INTO `onethink_document_model_article` VALUES ('68', '0', '图片挺不错', '');

-- ----------------------------
-- Table structure for `onethink_document_model_download`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_document_model_download`;
CREATE TABLE `onethink_document_model_download` (
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
-- Records of onethink_document_model_download
-- ----------------------------
INSERT INTO `onethink_document_model_download` VALUES ('6', '0', '', '', '0', '0', '0');
INSERT INTO `onethink_document_model_download` VALUES ('9', '0', '<p><strong>留宿两人牵走4名学生</strong></p><p>　　“我这是好心办了坏事。”昨日，负责管理学生宿舍的徐文秀老师说。25日下午6时，两名16岁左右的聋哑少女来到学生宿舍外，隔着围墙打手势找人。曾在邻水一瓷砖厂打过工的女学生陶林、袁应出来，说两人是她们以前的工友。</p><p>　　按规定，除家长外，陌生人原则上不准进宿舍楼，更不允许留宿。徐文秀说，当时陶、袁二人紧抱她双臂，苦苦哀求，自己一心软，便把门开了。晚8时，她去催两人离校，陶林又求情：朋友俩远道而来，晚上没地方住。见对方也是聋哑人，她只得勉强答应。</p><p>　　26日晨，两少女依约离校。下午，袁应、陶林等4人向班主任老师写了假条，称约出去“买卫生纸”。这是正当要求，徐文秀没理由不答应。吃晚饭，点名差4人。徐文秀预感出事了，赶紧上报。校长肖建国连夜带队找人，未果。4人中，最大的17岁，最小的仅有15岁。</p><p>　　27日，校方报警，曾派全体老师到垫江三大车站找人。车站一擦鞋女工证实：26日下午4时左右，她曾见6名聋哑女生，分乘两辆出租车驶往长寿方向。</p><p>　　<strong>三天前曾现身长生桥</strong></p><p>　　据该校教导主任徐文武介绍，他们随即跑到垫江4家出租车公司，借助GSP定位系统查找该时段出租车行踪。最终锁定渝G36021和渝G35050两辆车，载着6人去了长寿汽车站，下车地点是个偏僻角落，像是有意隐藏行踪。</p><p>　　校方与几名家长，手执袁应等4人的照片，跑到重庆各大车站找人。在汽车北站，一商贩证实看见了4人。29日上午，有市民在南岸区长生桥一彩印厂外也见到4名聋哑人，蹲在厂房外说话，听旁边人称“是来找工作的”。该市民在照片中认出了袁应等两人。</p><p>　　<strong>校方怀疑有人在操纵</strong></p><p>　\r\n　肖建国昨晚说，他们近日得到多名聋哑学生报告，同为垫江聋哑人，长期在重庆“操社会”的闲杂男子陈某，曾于26日当天出现在垫江县城，之后便不见踪影。\r\n同时来县城的还有聋哑学生高某，据称与陶林等人有过接触，而陈高二人关系很好。肖建国怀疑，陈某是幕后策划者，留宿两名聋哑少女也是其一伙的。目前，他正\r\n带队赶往沙坪坝，寻找陈某。</p><p>　　肖建国介绍，当地派出所认为，此事可能就是学生逃学打工，目前尚未介入调查。肖建国称，事发后，家长们都很着急，都在全力以赴找人，目前尚未有人找校方问责。教导主任徐文武默认，学校管理上存在疏漏，目前也加大管理力度，比如点名由每日2次增加为3次。</p><p>　　(因涉及特殊人群，本文人名为化名) (记者 朱亮)</p><p>　　<strong>拐骗聋哑学生惯用伎俩</strong></p><p>　　相关聋哑教育专家介绍，拐骗聋哑学生常见有两种手段：</p><p>　　1 通过聋哑同学、校友串联，以“找工作，多赚钱”为由哄骗。聋哑人接触社会不多，朋友少，对为数有限的同学、校友一般很信任。加上双方都是聋哑人，彼此间有共同语言，一旦对方以“某城市很好耍，找工作容易，挣钱又多”引诱，很容易被说动。</p><p>　　2 通过QQ、手机短信等工具，加聋哑学生为好友，先感情投资，骗取信任，再动员说服同学，达到目的。</p><p>　　<strong>聋哑女生可能的遭遇</strong></p><p>　　1 的确在找工作。徐文武称，袁英等人出走时，曾用笔纸告诉其他同学，她要出去一两年，“挣点钱孝敬父母。”市民在南岸长生桥看见4人时，4人尚有人身自由。</p><p>　　2 胁迫进扒窃团伙。由于聋哑少年易控制，即便被逮住警方也难查，所以偷窃团伙很“垂青”聋哑人。聋哑教育干了11年的肖建国称，若4女生被诱骗，最可能被胁迫进扒窃团伙。</p><p>　　3 被拐卖至偏远农村，或被胁迫当“小姐”，因聋哑人目标太大易暴露，可能性不大。　　</p><p><br/></p>', '', '48', '0', '106371');
INSERT INTO `onethink_document_model_download` VALUES ('43', '0', '<p>下载<br/></p>', '', '48', '1234', '106371');
INSERT INTO `onethink_document_model_download` VALUES ('44', '0', '<p>下载下载下载下载下载下载下载下载下载下载</p>', '', '48', '123123', '106371');

-- ----------------------------
-- Table structure for `onethink_file`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_file`;
CREATE TABLE `onethink_file` (
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
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COMMENT='文件表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of onethink_file
-- ----------------------------
INSERT INTO `onethink_file` VALUES ('1', 'upyun_api_doc.pdf', '51dd1424d10d8.pdf', '2013-07-10/', 'pdf', 'application/octet-stream', '186603', '44385f08f92c3279c04d16d35bc3c95a', 'a65897adf52a3b7284761e288eed67cb8996366d', '0', '1373443108');
INSERT INTO `onethink_file` VALUES ('2', '1725084_1.gif', '51e36e078dce4.gif', '2013-07-15/', 'gif', 'application/octet-stream', '323063', '18a0f2791c4396e7cfcfe77b6257d2b6', 'e8eb4561ebdaf7bfbd5141add420f4c263602f00', '0', '1373859335');
INSERT INTO `onethink_file` VALUES ('3', 'jQuery1.8.3_20121215.chm', '51e3b518b86b9.chm', '2013-07-15/', 'chm', 'application/octet-stream', '405941', '070896a55a0f2ffaea2082ec67213362', 'f43142dcef3deba755ab6bd842e884145dace637', '0', '1373877528');
INSERT INTO `onethink_file` VALUES ('4', 'ThinkPHP.apk', '51e3b577ec75a.apk', '2013-07-15/', 'apk', 'application/octet-stream', '540174', '6be127fce55673ba381687379b3f3d1a', '064ad39eae9f0fc00a0b383f5272ad2afe5996f2', '0', '1373877623');
INSERT INTO `onethink_file` VALUES ('5', 'myservice', '51e3c54f6d78f.', '2013-07-15/', '', 'application/octet-stream', '2542', '1c7774dc8431f68a1f0d00e9222bf342', '315686ec95849498025e98060299b76c74a6a836', '0', '1373881679');
INSERT INTO `onethink_file` VALUES ('6', 'putty.exe', '51e4ad3db948d.exe', '2013-07-16/', 'exe', 'application/octet-stream', '483328', 'a3ccfd0aa0b17fd23aa9fd0d84b86c05', '89c19274ad51b6fbd12fb59908316088c1135307', '0', '1373941053');
INSERT INTO `onethink_file` VALUES ('7', 'adsense广告位代码.txt', '51e63ecccb65e.txt', '2013-07-17/', 'txt', 'application/octet-stream', '2365', '93d6a1c3cfe267b03cd8419f20825e77', '9eed0489b259f562ff40d26a3fc3cda16f1d1052', '0', '1374043852');
INSERT INTO `onethink_file` VALUES ('8', '系统说明文档.docx', '5204aafd1c41b.docx', '2013-08-09/', 'docx', 'application/octet-stream', '19113', 'aa7a156ca847484a5155fba8cbfc6aaa', 'e2012575ad73f93c15913e13c92c39a32362e86b', '0', '1376037629');
INSERT INTO `onethink_file` VALUES ('9', '测试文档（2013年8月6日）.docx', '5204b6e36dd5e.docx', '2013-08-09/', 'docx', 'application/octet-stream', '195273', 'af426720fba9ed4f35bb92cbe790d9d5', 'c45c958bcfdef7758e79a5e93ecf64b0999eb08d', '0', '1376040675');
INSERT INTO `onethink_file` VALUES ('10', '官网日常运营.rar', '5212df8a69e8a.rar', '2013-08-20/', 'rar', 'application/octet-stream', '1354', '0ec5ec0351a5fe15f4998a391d4a2e28', 'c342d2d979dc53c9737699996376a4a53faf46a7', '0', '1376968586');
INSERT INTO `onethink_file` VALUES ('11', '任务列表表.txt', '5212e69a0c04a.txt', '2013-08-20/', 'txt', 'application/octet-stream', '2424', '1f9175d39788f7c61a78c5a3a8d9601a', '3f6013c2ae454682833c5d3b080f995db36ccb95', '0', '1376970393');
INSERT INTO `onethink_file` VALUES ('12', '7ee8cbfdbcb0cbfbb5c4c3d4b3c771', '52143693023b9.jpg', '2013-08-21/', 'jpg', 'application/octet-stream', '4536', '0ca9a27f1fc0b2fd8b4f17569010e48a', 'bd4421e88a2601bf6b3878aa8db32127c33ac006', '0', '1377056402');
INSERT INTO `onethink_file` VALUES ('13', '3c6d55fbb2fb4316e661e3fd20a446', '52143ab3e2296.jpg', '2013-08-21/', 'jpg', 'application/octet-stream', '35512', 'e06956cecda298b75f69387afa4a7483', 'da762fa7e368962232443db907555f5b2d7451f5', '0', '1377057459');
INSERT INTO `onethink_file` VALUES ('14', '120x120.jpg', '52145ac4779fb.jpg', '2013-08-21/', 'jpg', 'application/octet-stream', '27180', '525c1d1eb84ec6d094cc342717e75605', '62d8da07da8ad0fe6eba00260cc8067310c0bf70', '0', '1377065668');
INSERT INTO `onethink_file` VALUES ('15', '麦当苗儿.docx', '5215d94ae9357.docx', '2013-08-22/', 'docx', 'application/octet-stream', '124068', '46a59abd10ea29579c42c4eff9a3c369', 'cf30a5483538fd445164660d2c5966e58c1fd7d8', '0', '1377163594');
INSERT INTO `onethink_file` VALUES ('16', '未命名.jpg', '5218eb66cb937.jpg', '2013-08-25/', 'jpg', 'application/octet-stream', '553536', '31026f1f43ec4e3293c9b64c834e1a01', '2e9e3f8cbbcf5018788f3f427e34d1ef36fbc0bc', '0', '1377364838');
INSERT INTO `onethink_file` VALUES ('17', 'TPM文档.docx', '521c172aa329b.docx', '2013-08-27/', 'docx', 'application/octet-stream', '82883', 'cb24a13d97bfb4e42bba3c7a9933b514', '9e2d1742a30360dd292fe4777c18aefed3282245', '0', '1377572650');
INSERT INTO `onethink_file` VALUES ('18', 'ThinkPHP3.1.2完全开发手册CHM[2013-01', '521c235a1566e.chm', '2013-08-27/', 'chm', 'application/octet-stream', '681387', '43fc1aa176c8348b888437464cf78c90', '798937186135795fbb24474794e832f74d23cb8a', '0', '1377575769');
INSERT INTO `onethink_file` VALUES ('19', '官网日常运营.txt', '521ee9fe2b5f4.txt', '2013-08-29/', 'txt', 'application/octet-stream', '1857', 'd3ccb0643ba6b2aea3d10f261baaf637', 'cfc81fbb386b17c91b0d715a0388c039bc2ea999', '0', '1377757694');
INSERT INTO `onethink_file` VALUES ('20', 'hosts', '521eedee0e71c.', '2013-08-29/', '', 'application/octet-stream', '999', 'fe638d604d7041eea130c9be90cc8863', '3a511a2f41802a47b7b4253154904eef84e66b93', '0', '1377758701');
INSERT INTO `onethink_file` VALUES ('21', '7a3f559742d9fc7cfa71fc6e708c61', '52204a64e4fa4.gif', '2013-08-30/', 'gif', 'application/octet-stream', '484038', '8de12e29f5964c48741535525fbc319c', 'be1c7987c5afd50db58acc31390a5ddd027d7128', '0', '1377847908');
INSERT INTO `onethink_file` VALUES ('22', '08c93133acee899777b266bf9300fc', '52204a81adb46.gif', '2013-08-30/', 'gif', 'application/octet-stream', '438173', '39a17d7f67a8de2e8cc7977488482aee', '2e1139b34439564e2648d8695b0ed43e584e428d', '0', '1377847937');
INSERT INTO `onethink_file` VALUES ('23', '42a1f42bb4592832c1f6f41566e320', '52204aa5d2f62.gif', '2013-08-30/', 'gif', 'application/octet-stream', '509760', '9825db5ba7b43743e3cd938ed6fb0c3d', 'c87a7a97f91d8e4ea285099476d57b61049441ae', '0', '1377847973');
INSERT INTO `onethink_file` VALUES ('24', 'IMG_20130825_083921.jpg', '5224361393fe1.jpg', '2013-09-02/', 'jpg', 'application/octet-stream', '121317', 'dba47e649c280cb575d656bfc1b337f0', '942d6cedbc2ab9bc31ee3b9351cb8ea2b369df2b', '0', '1378104851');
INSERT INTO `onethink_file` VALUES ('25', 'QQ截图20130816133754.png', '522438277438c.png', '2013-09-02/', 'png', 'application/octet-stream', '177662', '3f914622e62879cd9b54e757d3baf4fa', 'bfc84cca804f31c64a24d2ead7fb7e399e6e33cc', '0', '1378105382');
INSERT INTO `onethink_file` VALUES ('26', 'QQ截图20130731160446.png', '522438edaabe4.png', '2013-09-02/', 'png', 'application/octet-stream', '17023', '5bb9e2e23470342711ddbaf3a1fd53ce', 'd749393084a67d53228d32ddaef844261366fccb', '0', '1378105581');
INSERT INTO `onethink_file` VALUES ('27', 'QQ截图20130804155639.png', '522439cc92209.png', '2013-09-02/', 'png', 'application/octet-stream', '26705', '30a16dd5940e1243344560fb85a5e77e', 'c19774d5e8abf4a413155c603b416712dcde5763', '0', '1378105804');
INSERT INTO `onethink_file` VALUES ('28', 'psb_white2.jpg', '5225a68749151.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '7546', '0578abb03d826fc21b05465ee0bf3954', 'b5b8d17dabd46d285c9ea4df9390bc07bc8a189f', '0', '1378199173');
INSERT INTO `onethink_file` VALUES ('29', 'psb_white.jpg', '5225a75a3dccd.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '7589', 'affa9eb24e7f17082c0a30f31c1455a5', '4d073951bc2bf10db55979a470bfad6945f71083', '0', '1378199382');
INSERT INTO `onethink_file` VALUES ('30', 'IMG_20130819_130920.jpg', '5225aad51c395.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '994489', '16ea4fa80605ea520e5d009d28e554bc', '3aa2a04c29ac82f811e96974f68069c58dd32bec', '0', '1378200273');
INSERT INTO `onethink_file` VALUES ('31', 'psb.jpg', '5225ab64004ac.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '7158', 'a941279ed8b5d46f6d860b4c92803add', 'efe6b805e08ca40f4499bce4e8fc1941002d6812', '0', '1378200416');
INSERT INTO `onethink_file` VALUES ('32', 'QQ截图20130830094219.png', '5225ac1ca6bd4.png', '2013-09-03/', 'png', 'application/octet-stream', '38472', 'dbeef2e5c8e2ef4019ed8c2756087451', 'd1b6295522682993d15423ef7ec69d179e7ce83a', '0', '1378200602');
INSERT INTO `onethink_file` VALUES ('33', '魁拔之大战元泱界.jpg', '5225acfe7e893.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '157994', 'ece97d80d1ac12e4f3888f09ed74fa6e', '459535d6465fd9ba956efdc2ade09391f82f5113', '0', '1378200828');
INSERT INTO `onethink_file` VALUES ('34', '上机题.docx', '52282c4101205.docx', '2013-09-05/', 'docx', 'application/octet-stream', '13405', '8abc6530747bd1d3e22a9191e36a4e27', '41ce40adf1bc1ccab0276b97116039fed59760b4', '0', '1378364480');
INSERT INTO `onethink_file` VALUES ('35', 'ThinkPHP CMS.pdf', '522e84da834ca.pdf', '2013-09-10/', 'pdf', 'application/octet-stream', '129460', 'c712e6ea9fcf21fd2bafb8db07ee13a5', '23b0806b0824f956abebadd0f05a34820c3f6034', '0', '1378780378');
INSERT INTO `onethink_file` VALUES ('36', 'thinkcms_20130903.sql', '522e86a658447.sql', '2013-09-10/', 'sql', 'application/octet-stream', '69932', 'a77675379729466f7a5acfe8db40759e', '83eb5303ab0eea95743a384651db32e02a54c5a5', '0', '1378780838');
INSERT INTO `onethink_file` VALUES ('37', '任务列表表.txt', '522fe8acc0e22.txt', '2013-09-11/', 'txt', 'application/octet-stream', '2462', '3f0c717a4b601af919304648f636673d', 'b181d898da26e7d970099a3e0a58f4dd97abac1e', '0', '1378871468');
INSERT INTO `onethink_file` VALUES ('38', 'product_video.html', '52300260904da.html', '2013-09-11/', 'html', 'application/octet-stream', '8885', '2b74c5f82b295763a70dc22ab91f2ffe', '6440e1a5a3c0fa71cf9cc18254340372d032a5a2', '0', '1378878048');
INSERT INTO `onethink_file` VALUES ('39', '51d248f1135a9.jpg', '5230075710ad8.jpg', '2013-09-11/', 'jpg', 'application/octet-stream', '72550', '151178e5513c171c1177e4386a7f9e81', '0dac6e0346138dae5fa013d0f47638041a2fc376', '0', '1378879318');
INSERT INTO `onethink_file` VALUES ('40', '8ad4b31c8701a18b2eb24c019e2f07', '52300ba1a8878.gif', '2013-09-11/', 'gif', 'application/octet-stream', '991750', '9675d17f0ad8be9e4c50857cf47f89b7', '161637ba3f778cdf12d77319dde2790d1c0ad915', '0', '1378880417');
INSERT INTO `onethink_file` VALUES ('41', 'RunYNote.exe', '5231386bb32e7.exe', '2013-09-12/', 'exe', 'application/octet-stream', '656016', 'f5ce6d7077a8d7f98a1addb7af3775ab', 'e8a7bfd2c439132a49432b8207f40efcbb6361a0', '0', '1378957419');
INSERT INTO `onethink_file` VALUES ('42', 'gvim.exe', '5231aa7c2c856.exe', '2013-09-12/', 'exe', 'application/octet-stream', '1994240', 'e1b6be486aaedcbdf20656f3d68b6e23', 'ee7d7bc70346f016f69bd86a4face01d0b9caa90', '0', '1378986619');
INSERT INTO `onethink_file` VALUES ('43', '元全vps信息 by cooper 20130124(1).', '5232710b2e344.xlsx', '2013-09-13/', 'xlsx', 'application/octet-stream', '18478', '3b7d75acbf5b99e8601def6478baacbe', 'aa69e44e7a306f9ce89771b1ac11e839594bc9d0', '0', '1379037450');
INSERT INTO `onethink_file` VALUES ('44', 'ajax-loader.gif', '523272256cd07.gif', '2013-09-13/', 'gif', 'application/octet-stream', '10819', '57ca1a2085d82f0574e3ef740b9a5ead', '2974f4bf37231205a256f2648189a461e74869c0', '0', '1379037733');
INSERT INTO `onethink_file` VALUES ('45', 'Computer_Security_Risk.png', '5232722e36f43.png', '2013-09-13/', 'png', 'application/octet-stream', '787', 'f78b8be142ceb58771f73b703da642c1', 'bec789e85563902149368074f88fc1c28893385e', '0', '1379037741');
INSERT INTO `onethink_file` VALUES ('46', 'chm_start.html', '5232c3fe06d52.html', '2013-09-13/', 'html', 'application/octet-stream', '1242', '10abb570e3cf6d5909e3373c11219887', '448a1cd25ceee9a4a2ecba6c34b34a831f3a2a6e', '0', '1379058685');
INSERT INTO `onethink_file` VALUES ('47', 'u16_normal.png', '5232cdd849147.png', '2013-09-13/', 'png', 'application/octet-stream', '8829', '8ea26d7027275fb4f66a2dc1ed54c13b', 'a25b6a5cda546d8febd7a927a4f0fe913503e648', '0', '1379061208');
INSERT INTO `onethink_file` VALUES ('48', '招聘2.jpg', '5233c86f9add8.jpg', '2013-09-14/', 'jpg', 'application/octet-stream', '106371', '911d4fe765a0f17d254ed51885e2518a', 'f9b07c025bff974f4d73f32f54a2990bcb6863c8', '0', '1379125359');
INSERT INTO `onethink_file` VALUES ('49', 'Lighthouse.jpg', '52343d858f58b.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '561276', '8969288f4245120e7c3870287cce0ff3', '1b4605b0e20ceccf91aa278d10e81fad64e24e27', '0', '1379155333');
INSERT INTO `onethink_file` VALUES ('50', 'Penguins.jpg', '52343ec46d0e4.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '777835', '9d377b10ce778c4938b3c7e2c63a229a', 'df7be9dc4f467187783aca68c7ce98e4df2172d0', '0', '1379155652');
INSERT INTO `onethink_file` VALUES ('51', 'Tulips.jpg', '52344155a89c8.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '620888', 'fafa5efeaf3cbe3b23b2748d13e629a1', '54c2f1a1eb6f12d681a5c7078421a5500cee02ad', '0', '1379156309');
INSERT INTO `onethink_file` VALUES ('52', 'Desert.jpg', '5234416d1f098.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '845941', 'ba45c8f60456a672e003a875e469d0eb', '30420d1a9afb2bcb60335812569af4435a59ce17', '0', '1379156332');
INSERT INTO `onethink_file` VALUES ('53', 'Hydrangeas.jpg', '5234418c17e4a.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '595284', 'bdf3bf1da3405725be763540d6601144', 'd997e1c37edc05ad87d03603e32ad495ee2cfce1', '0', '1379156363');
INSERT INTO `onethink_file` VALUES ('54', '太极侠.JPEG', '52344e47bf20e.JPEG', '2013-09-14/', 'JPEG', 'image/jpeg', '170183', '51459df7fe30a6c098891e69fe7224b4', '9ad80833b9df8d05f811a44afe71ca729e3b8139', '0', '1379159622');

-- ----------------------------
-- Table structure for `onethink_hooks`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_hooks`;
CREATE TABLE `onethink_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '钩子名称',
  `description` text COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1-Controller 2-Widget',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) DEFAULT NULL COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `搜索索引` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of onethink_hooks
-- ----------------------------
INSERT INTO `onethink_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', 'AdaptiveImages');
INSERT INTO `onethink_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', 'ReturnTop');
INSERT INTO `onethink_hooks` VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', 'Attachment');
INSERT INTO `onethink_hooks` VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', 'SocialComment,Attachment');
INSERT INTO `onethink_hooks` VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', null);
INSERT INTO `onethink_hooks` VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', 'Attachment');
INSERT INTO `onethink_hooks` VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', 'Editor');
INSERT INTO `onethink_hooks` VALUES ('8', 'adminArticleEdit', '后台内容编辑页编辑器', '1', '1378982734', 'EditorForAdmin');
INSERT INTO `onethink_hooks` VALUES ('13', 'AdminIndex', '首页小格子个性化显示', '1', '1379402135', 'SiteStat,DevTeam,SystemInfo');
INSERT INTO `onethink_hooks` VALUES ('14', 'topicComment', '评论提交方式扩展钩子。', '1', '1379496194', 'Editor');

-- ----------------------------
-- Table structure for `onethink_member`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_member`;
CREATE TABLE `onethink_member` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `nickname` char(16) NOT NULL COMMENT '昵称',
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
-- Records of onethink_member
-- ----------------------------
INSERT INTO `onethink_member` VALUES ('9', '麦当苗儿', '0', '0000-00-00', '', '0', '11', '2130706433', '1369722401', '2130706433', '1371192515', '1');
INSERT INTO `onethink_member` VALUES ('1', '超级管理员', '0', '0000-00-00', '', '60', '332', '2130706433', '1371435498', '2130706433', '1379507698', '1');
INSERT INTO `onethink_member` VALUES ('10', 'thinkphp', '0', '0000-00-00', '', '10', '10', '3232235922', '1374043830', '2130706433', '1379041194', '1');
INSERT INTO `onethink_member` VALUES ('11', 'yangweijie', '0', '0000-00-00', '', '70', '51', '2130706433', '1376897307', '2130706433', '1379404404', '1');
INSERT INTO `onethink_member` VALUES ('12', '奥巴马', '0', '0000-00-00', '', '231', '30', '2130706433', '1376968536', '2130706433', '1379405701', '1');
INSERT INTO `onethink_member` VALUES ('13', 'zhuyajie', '0', '0000-00-00', '', '20', '8', '2130706433', '1378440279', '2130706433', '1379408895', '1');
INSERT INTO `onethink_member` VALUES ('15', '', '0', '0000-00-00', '', '10', '3', '2130706433', '1379484969', '2130706433', '1379506974', '1');

-- ----------------------------
-- Table structure for `onethink_picture`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_picture`;
CREATE TABLE `onethink_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL COMMENT '文件md5',
  `sha1` char(40) NOT NULL COMMENT '文件 sha1编码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of onethink_picture
-- ----------------------------
INSERT INTO `onethink_picture` VALUES ('7', '/Uploads/Picture/2013-09-11/522fd436ee35e.jpg', '', 'e06956cecda298b75f69387afa4a7483', '', '1', '1378866230');
INSERT INTO `onethink_picture` VALUES ('8', '/Uploads/Picture/2013-09-11/522fd6f1a7a3e.gif', '', 'a9c6422b75fa423e0769fc4c2fee8d4a', '', '1', '1378866929');
INSERT INTO `onethink_picture` VALUES ('9', '/Uploads/Picture/2013-09-11/5230087ef00c2.jpg', '', '71168c9f95dbf7afa424c547533b6ded', '', '1', '1378879614');
INSERT INTO `onethink_picture` VALUES ('10', '/Uploads/Picture/2013-09-11/523008ba4fde5.png', '', '3b0fd01ef469dad40ba62b53f5edcdaa', '', '1', '1378879674');
INSERT INTO `onethink_picture` VALUES ('11', '/Uploads/Picture/2013-09-11/52300b806c249.gif', '', '39a17d7f67a8de2e8cc7977488482aee', '', '1', '1378880384');
INSERT INTO `onethink_picture` VALUES ('12', '/Uploads/Picture/2013-09-11/523012e46fca2.jpg', '', 'e47ddd19ab2ef444c9fc1dbde72cec61', '', '1', '1378882276');
INSERT INTO `onethink_picture` VALUES ('13', '/Uploads/Picture/2013-09-12/5231af5f583cf.docx', '', 'b7d7a200cffa810a3b1f312aa4f72a3c', '', '1', '1378987871');
INSERT INTO `onethink_picture` VALUES ('14', '/Uploads/Picture/2013-09-13/52327959a62d2.jpg', '', '151178e5513c171c1177e4386a7f9e81', '', '1', '1379039577');
INSERT INTO `onethink_picture` VALUES ('15', '/Uploads/Picture/2013-09-13/523287bdb9507.jpg', '', 'd2f6700c819306ed896ef2fc0f43c6f6', '', '1', '1379043261');
INSERT INTO `onethink_picture` VALUES ('16', '/Uploads/Picture/2013-09-13/523287c988154.gif', '', 'deba4e6b9dc008167006a112f3b94154', '', '1', '1379043273');
INSERT INTO `onethink_picture` VALUES ('17', '/Uploads/Picture/2013-09-13/5232a55d3201c.png', '', 'cd99fc1e3274a555755f854240d9e26c', '', '1', '1379050845');
INSERT INTO `onethink_picture` VALUES ('18', '/Uploads/Picture/2013-09-13/5232b22beeb5f.jpg', '', 'e2113d4293d779134fa2f71ee0b8bb34', '', '1', '1379054123');
INSERT INTO `onethink_picture` VALUES ('19', '/Uploads/Picture/2013-09-13/5232b7c666ced.png', '', 'e298f953fbafadcfb3fcb979fc47da1f', '', '1', '1379055558');
INSERT INTO `onethink_picture` VALUES ('20', '/Uploads/Picture/2013-09-13/5232c26e80b8d.png', '', '8ea26d7027275fb4f66a2dc1ed54c13b', '', '1', '1379058286');
INSERT INTO `onethink_picture` VALUES ('21', '/Uploads/Picture/2013-09-13/5232c32840f98.png', '', '8615bb31bbf5198f858dd87092c5dde2', '', '1', '1379058472');
INSERT INTO `onethink_picture` VALUES ('22', '/Uploads/Picture/2013-09-13/5232c38c4b6dc.png', '', '81a0a3da5b83df6225830d145cd7f51a', '', '1', '1379058572');
INSERT INTO `onethink_picture` VALUES ('23', '/Uploads/Picture/2013-09-13/5232ce984b16f.gif', '', '57ca1a2085d82f0574e3ef740b9a5ead', '', '1', '1379061400');
INSERT INTO `onethink_picture` VALUES ('24', '/Uploads/Picture/2013-09-13/5232ebba22438.jpg', '', '8969288f4245120e7c3870287cce0ff3', '', '1', '1379068857');
INSERT INTO `onethink_picture` VALUES ('25', '/Uploads/Picture/2013-09-13/5232ecdce951f.jpg', '', '9d377b10ce778c4938b3c7e2c63a229a', '', '1', '1379069148');
INSERT INTO `onethink_picture` VALUES ('26', '/Uploads/Picture/2013-09-13/5232ed78a586b.jpg', '', '076e3caed758a1c18c91a0e9cae3368f', '', '1', '1379069304');
INSERT INTO `onethink_picture` VALUES ('27', '/Uploads/Picture/2013-09-13/5232ee4c4a114.jpg', '', '0578abb03d826fc21b05465ee0bf3954', '', '1', '1379069516');
INSERT INTO `onethink_picture` VALUES ('28', '/Uploads/Picture/2013-09-13/5232eedfb32c7.jpg', '', 'ba45c8f60456a672e003a875e469d0eb', '', '1', '1379069663');
INSERT INTO `onethink_picture` VALUES ('29', '/Uploads/Picture/2013-09-13/5232f0e2036b7.jpg', '', 'bdf3bf1da3405725be763540d6601144', '', '1', '1379070177');
INSERT INTO `onethink_picture` VALUES ('30', '/Uploads/Picture/2013-09-13/5232f3195654a.jpg', '', '5a44c7ba5bbe4ec867233d67e4806848', '', '1', '1379070745');
INSERT INTO `onethink_picture` VALUES ('31', '/Uploads/Picture/2013-09-13/5232f338026a3.jpg', '', 'fafa5efeaf3cbe3b23b2748d13e629a1', '', '1', '1379070775');
INSERT INTO `onethink_picture` VALUES ('32', '/Uploads/Picture/2013-09-14/5233d1cd1dcf1.jpg', '', '911d4fe765a0f17d254ed51885e2518a', '', '1', '1379127756');
INSERT INTO `onethink_picture` VALUES ('33', '/Uploads/Picture/2013-09-16/523664f6ae557.jpg', '', '72d7c71fc9c294e8e6c333b6153b8348', '', '1', '1379296502');
INSERT INTO `onethink_picture` VALUES ('34', '/Uploads/Picture/2013-09-16/5236663d45b60.jpg', '', '5d0ee4241191d8f26ddd8e76b01cd2fe', '', '1', '1379296829');
INSERT INTO `onethink_picture` VALUES ('35', '/Uploads/Picture/2013-09-16/52369b7589a92.jpg', '', '6c1a82458091c4b35eeb85747e7fa358', '', '1', '1379310453');
INSERT INTO `onethink_picture` VALUES ('36', '/Uploads/Picture/2013-09-16/5236a54141526.png', '', '8a2487cd30c29966447fabd4a837ddc9', '', '1', '1379312961');
INSERT INTO `onethink_picture` VALUES ('37', '/Uploads/Picture/2013-09-16/5236a990b7cf8.jpg', '', '6bb6da064284aec91855ed8b7e6626ac', '', '1', '1379314064');
INSERT INTO `onethink_picture` VALUES ('38', '/Uploads/Picture/2013-09-16/5236ade90c5d1.jpg', '', '4a383f8838107bc27b89090de5644e34', '', '1', '1379315176');
INSERT INTO `onethink_picture` VALUES ('39', '/Uploads/Picture/2013-09-16/5236ae9f76c21.png', '', '20b3ca080df7ea67d079001f8c4d5ce6', '', '1', '1379315359');
INSERT INTO `onethink_picture` VALUES ('40', '/Uploads/Picture/2013-09-16/5236aeb267b80.jpg', '', 'c3f484d4bb0e775018abe5716c56bdf9', '', '1', '1379315378');
INSERT INTO `onethink_picture` VALUES ('41', '/Uploads/Picture/2013-09-16/5236af5121573.jpg', '', '0066c235f9618e8dfca7ca85e5ecb8a8', '', '1', '1379315536');
INSERT INTO `onethink_picture` VALUES ('42', '/Uploads/Picture/2013-09-18/523934af5cd74.png', '', 'e42874d9b30407027b3cd1da656368d9', '', '1', '1379480751');
INSERT INTO `onethink_picture` VALUES ('43', '/Uploads/Picture/2013-09-18/523934bca96cd.png', '', '4b946d6c99e0b90a93487539dcff1ccb', '', '1', '1379480764');

-- ----------------------------
-- Table structure for `onethink_ucenter_admin`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_ucenter_admin`;
CREATE TABLE `onethink_ucenter_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of onethink_ucenter_admin
-- ----------------------------
INSERT INTO `onethink_ucenter_admin` VALUES ('1', '1', '1');
INSERT INTO `onethink_ucenter_admin` VALUES ('2', '3', '1');

-- ----------------------------
-- Table structure for `onethink_ucenter_app`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_ucenter_app`;
CREATE TABLE `onethink_ucenter_app` (
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
-- Records of onethink_ucenter_app
-- ----------------------------
INSERT INTO `onethink_ucenter_app` VALUES ('1', 'ThinkPHP官网', 'http://www.thinkphp.cn', '', '', '0', '', '0', '0', '1');

-- ----------------------------
-- Table structure for `onethink_ucenter_member`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_ucenter_member`;
CREATE TABLE `onethink_ucenter_member` (
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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Table structure for `onethink_ucenter_setting`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_ucenter_setting`;
CREATE TABLE `onethink_ucenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `value` text NOT NULL COMMENT '配置数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表';

-- ----------------------------
-- Records of onethink_ucenter_setting
-- ----------------------------

-- ----------------------------
-- Table structure for `onethink_url`
-- ----------------------------
DROP TABLE IF EXISTS `onethink_url`;
CREATE TABLE `onethink_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL COMMENT '状态（-1：删除，0：禁用，1：正常，2：未审核）',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='链接表';

-- ----------------------------
-- Records of onethink_url
-- ----------------------------
INSERT INTO `onethink_url` VALUES ('1', 'http://onethink.cn', '', '1', '1379139055');
INSERT INTO `onethink_url` VALUES ('2', 'https://onethink.cn', '', '1', '1379470673');
INSERT INTO `onethink_url` VALUES ('3', 'http://finance.qq.com/a/20130918/005078.htm?pgv_ref=aio2012&ptlang=2052', '', '1', '1379482959');
