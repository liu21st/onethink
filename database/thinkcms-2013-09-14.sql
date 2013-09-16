/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.200
Source Server Version : 50520
Source Host           : 192.168.1.200:3306
Source Database       : thinkcms

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2013-09-14 19:39:51
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
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `status` tinyint(2) NOT NULL COMMENT '状态（-1：已删除，0：禁用，1：正常）',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

-- ----------------------------
-- Records of think_action
-- ----------------------------
INSERT INTO `think_action` VALUES ('1', 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:9-2+3+score*1/1|cycle:24|max:1;', '1', '1377681235');
INSERT INTO `think_action` VALUES ('2', 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max|5', '1', '1377504452');
INSERT INTO `think_action` VALUES ('3', 'review', '评论', '评论积分+2', 'table:member|field:score|condition:uid={$self}|rule:score+1|cycle:24|max|5', '1', '1379150556');

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
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of think_action_log
-- ----------------------------
INSERT INTO `think_action_log` VALUES ('43', '1', '12', '2130706433', 'member', '12', '1377571191');
INSERT INTO `think_action_log` VALUES ('44', '1', '10', '2130706433', 'member', '10', '1377677682');
INSERT INTO `think_action_log` VALUES ('45', '1', '11', '2130706433', 'member', '11', '1378104467');
INSERT INTO `think_action_log` VALUES ('46', '1', '11', '2130706433', 'member', '11', '1378178007');
INSERT INTO `think_action_log` VALUES ('47', '1', '11', '2130706433', 'member', '11', '1378196490');
INSERT INTO `think_action_log` VALUES ('48', '1', '11', '2130706433', 'member', '11', '1378198582');
INSERT INTO `think_action_log` VALUES ('49', '1', '1', '2130706433', 'member', '1', '1378347877');
INSERT INTO `think_action_log` VALUES ('50', '1', '13', '2130706433', 'member', '13', '1378440279');
INSERT INTO `think_action_log` VALUES ('51', '1', '11', '2130706433', 'member', '11', '1378448326');
INSERT INTO `think_action_log` VALUES ('52', '1', '1', '2130706433', 'member', '1', '1378448786');
INSERT INTO `think_action_log` VALUES ('42', '1', '12', '2130706433', 'member', '12', '1377571132');
INSERT INTO `think_action_log` VALUES ('53', '1', '1', '2130706433', 'member', '1', '1378778769');
INSERT INTO `think_action_log` VALUES ('54', '1', '12', '2130706433', 'member', '12', '1378780815');
INSERT INTO `think_action_log` VALUES ('55', '1', '1', '2130706433', 'member', '1', '1378780958');
INSERT INTO `think_action_log` VALUES ('56', '1', '11', '2130706433', 'member', '11', '1378783949');
INSERT INTO `think_action_log` VALUES ('57', '1', '1', '2130706433', 'member', '1', '1378789685');
INSERT INTO `think_action_log` VALUES ('58', '1', '11', '2130706433', 'member', '11', '1378882046');
INSERT INTO `think_action_log` VALUES ('59', '1', '1', '2130706433', 'member', '1', '1378888427');
INSERT INTO `think_action_log` VALUES ('60', '1', '11', '2130706433', 'member', '11', '1378977765');
INSERT INTO `think_action_log` VALUES ('61', '1', '11', '2130706433', 'member', '11', '1379126003');

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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of think_addons
-- ----------------------------
INSERT INTO `think_addons` VALUES ('39', 'AdaptiveImages', '手机端响应式图片处理', '通过检测手机的宽度，在小设备访问图片时返回合适尺寸的小图片，到小尺寸设备达到图片响应式。', '1', '{\"resolutions\":\"1382,992,768,480\",\"cache_path\":\".\\/Public\\/ai-cache\",\"jpg_quality\":\"75\",\"sharpen\":\"0\",\"watch_cache\":\"0\",\"browser_cache\":\"604800\"}', 'thinkphp', '0.1', '1378450898');
INSERT INTO `think_addons` VALUES ('54', 'Editor', '前台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"500px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1378891635');
INSERT INTO `think_addons` VALUES ('55', 'Editor', '前台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"1\",\"editor_wysiwyg\":null,\"editor_height\":\"500px\",\"status\":\"1\"}', 'thinkphp', '0.1', '1378891638');
INSERT INTO `think_addons` VALUES ('56', 'EditorForAdmin', '后台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"220px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1378891657');
INSERT INTO `think_addons` VALUES ('61', 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', '1', '{\"comment_type\":\"1\",\"comment_uid_youyan\":\"900400\",\"comment_short_name_duoshuo\":\"\",\"comment_form_pos_duoshuo\":\"top\",\"comment_data_list_duoshuo\":\"10\",\"comment_data_order_duoshuo\":\"asc\"}', 'thinkphp', '0.1', '1378950537');
INSERT INTO `think_addons` VALUES ('62', 'ReturnTop', '返回顶部', '回到顶部美化，随机或指定显示，100款样式，每天一种换，天天都用新样式', '1', '{\"random\":\"0\",\"current\":\"80\"}', 'thinkphp', '0.1', '1378975837');
INSERT INTO `think_addons` VALUES ('64', 'Attachment', '附件', '用于文档模型上传附件', '1', '\"\"', 'thinkphp', '0.1', '1378979865');
INSERT INTO `think_addons` VALUES ('65', 'SystemInfo', '系统环境信息', '用于显示一些服务器的信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379155235');
INSERT INTO `think_addons` VALUES ('66', 'DevTeam', '开发团队信息', '开发团队成员信息', '1', '{\"title\":\"OneThink\\u5f00\\u53d1\\u56e2\\u961f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379155999');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='附件表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- ----------------------------
-- Records of think_attachment
-- ----------------------------
INSERT INTO `think_attachment` VALUES ('1', '1', 'upyun_api_doc.pdf', '2', '1', '2', '7', '186603', '0', '0', '1373443268', '1373443268', '1');
INSERT INTO `think_attachment` VALUES ('2', '1', '1725084_1.gif', '2', '2', '4', '5', '323063', '0', '0', '1373859340', '1373859340', '1');
INSERT INTO `think_attachment` VALUES ('3', '10', 'adsense广告位代码.txt', '2', '7', '21', '2', '2365', '0', '0', '1374043875', '1374043875', '1');
INSERT INTO `think_attachment` VALUES ('4', '1', '系统说明文档.docx', '2', '8', '29', '1', '19113', '0', '0', '1376037633', '1376037633', '1');
INSERT INTO `think_attachment` VALUES ('5', '1', '测试文档（2013年8月6日）.docx', '2', '9', '31', '1', '195273', '0', '0', '1376040686', '1376040686', '1');
INSERT INTO `think_attachment` VALUES ('6', '1', '麦当苗儿.docx', '2', '15', '42', '1', '124068', '0', '0', '1377164056', '1377164056', '1');
INSERT INTO `think_attachment` VALUES ('7', '10', 'TPM文档.docx', '2', '17', '43', '0', '82883', '0', '0', '1377572673', '1377572673', '1');
INSERT INTO `think_attachment` VALUES ('8', '1', 'ThinkPHP CMS.pdf', '2', '35', '15', '3', '129460', '0', '0', '1378780389', '1378780389', '1');
INSERT INTO `think_attachment` VALUES ('9', '11', 'psb_white.jpg', '2', '29', '75', '0', '7589', '0', '0', '1379158095', '1379158095', '1');
INSERT INTO `think_attachment` VALUES ('10', '11', 'psb.jpg', '2', '31', '76', '0', '7158', '0', '0', '1379158650', '1379158650', '1');

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
INSERT INTO `think_auth_category_access` VALUES ('1', '1');
INSERT INTO `think_auth_category_access` VALUES ('1', '2');
INSERT INTO `think_auth_category_access` VALUES ('1', '3');
INSERT INTO `think_auth_category_access` VALUES ('1', '4');
INSERT INTO `think_auth_category_access` VALUES ('1', '5');
INSERT INTO `think_auth_category_access` VALUES ('1', '6');
INSERT INTO `think_auth_category_access` VALUES ('1', '7');
INSERT INTO `think_auth_category_access` VALUES ('1', '8');
INSERT INTO `think_auth_category_access` VALUES ('1', '9');
INSERT INTO `think_auth_category_access` VALUES ('1', '10');
INSERT INTO `think_auth_category_access` VALUES ('1', '11');
INSERT INTO `think_auth_category_access` VALUES ('1', '12');
INSERT INTO `think_auth_category_access` VALUES ('1', '14');
INSERT INTO `think_auth_category_access` VALUES ('1', '15');
INSERT INTO `think_auth_category_access` VALUES ('1', '17');
INSERT INTO `think_auth_category_access` VALUES ('1', '18');
INSERT INTO `think_auth_category_access` VALUES ('1', '19');
INSERT INTO `think_auth_category_access` VALUES ('5', '1');
INSERT INTO `think_auth_category_access` VALUES ('5', '2');
INSERT INTO `think_auth_category_access` VALUES ('5', '3');
INSERT INTO `think_auth_category_access` VALUES ('5', '4');
INSERT INTO `think_auth_category_access` VALUES ('5', '5');
INSERT INTO `think_auth_category_access` VALUES ('5', '7');
INSERT INTO `think_auth_category_access` VALUES ('5', '9');
INSERT INTO `think_auth_category_access` VALUES ('5', '10');
INSERT INTO `think_auth_category_access` VALUES ('5', '14');
INSERT INTO `think_auth_category_access` VALUES ('5', '15');
INSERT INTO `think_auth_category_access` VALUES ('6', '2');
INSERT INTO `think_auth_category_access` VALUES ('6', '10');
INSERT INTO `think_auth_category_access` VALUES ('6', '11');

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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_auth_group
-- ----------------------------
INSERT INTO `think_auth_group` VALUES ('1', 'admin', '1', '管理员', 'id为1的用户组222aa', '1', '1,2,3,4,13,14,25,26,28,29,31,32,33,34,36,37,38,39,40,41,42,43,44,45,46,47,48,50,52,53,54,55,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,91,92,93,94,95,97');
INSERT INTO `think_auth_group` VALUES ('5', 'admin', '1', '内容管理员', '111111111111', '1', '1,2,31,32,57,58,59,68,69,70,83,87,88,89,90');
INSERT INTO `think_auth_group` VALUES ('6', 'admin', '1', '测试', '测试', '1', '1,2,3,4,13,14,25,26,27,28,29,32,33,36,37,38,39,40,41,42,57,58,59,60,61,62,63,68,72,75');
INSERT INTO `think_auth_group` VALUES ('7', 'admin', '1', 'aaabbb', 'aaaaaa', '1', '13,25,26,27,28,33,36,37,38,39,40,41,42,72,75');

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
INSERT INTO `think_auth_group_access` VALUES ('1', '6');
INSERT INTO `think_auth_group_access` VALUES ('9', '1');
INSERT INTO `think_auth_group_access` VALUES ('9', '6');
INSERT INTO `think_auth_group_access` VALUES ('10', '5');
INSERT INTO `think_auth_group_access` VALUES ('10', '6');
INSERT INTO `think_auth_group_access` VALUES ('11', '5');
INSERT INTO `think_auth_group_access` VALUES ('11', '6');
INSERT INTO `think_auth_group_access` VALUES ('11', '7');
INSERT INTO `think_auth_group_access` VALUES ('12', '1');
INSERT INTO `think_auth_group_access` VALUES ('13', '1');

-- ----------------------------
-- Table structure for `think_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `think_auth_rule`;
CREATE TABLE `think_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`module`,`name`,`type`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_auth_rule
-- ----------------------------
INSERT INTO `think_auth_rule` VALUES ('1', 'admin', '1', 'Admin/Index/index', '管理首页', '1', '');
INSERT INTO `think_auth_rule` VALUES ('2', 'admin', '1', 'Admin/article/index', '文档列表', '1', '');
INSERT INTO `think_auth_rule` VALUES ('3', 'admin', '1', 'Admin/User/index', '用户信息', '1', '');
INSERT INTO `think_auth_rule` VALUES ('4', 'admin', '1', 'Admin/Addons/index', '插件管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('5', 'admin', '1', 'Admin/System/index', '基本设置', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('6', 'admin', '1', 'Admin/Index/form', '表单样式', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('7', 'admin', '1', 'Admin/Article/index?cate_id=9', '讨论', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('8', 'admin', '1', 'Admin/Article/index?cate_id=2', '下载', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('9', 'admin', '1', 'Admin/AuthManager/edit', '编辑', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('10', 'admin', '1', 'Admin/AuthManager/delete', '删除', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('11', 'admin', '1', 'Admin/AuthManager/forbid', '禁用', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('12', 'admin', '1', 'Admin/AuthManager/resume', '恢复', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('13', 'admin', '1', 'Admin/AuthManager/index', '权限管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('14', 'admin', '1', 'Admin/Addons/hooks', '钩子管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('15', 'admin', '1', 'Admin/System/index1', '静态规则设置', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('16', 'admin', '1', 'Admin/System/index2', 'SEO优化设置', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('17', 'admin', '1', 'Admin/System/index3', '导航管理', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('18', 'admin', '1', 'Admin/System/index4', '分类管理', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('19', 'admin', '1', 'Admin/System/index5', '数据迁移', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('20', 'admin', '1', 'Admin/System/index6', '数据备份/恢复', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('21', 'admin', '1', 'Admin/System/index7', '系统日志', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('22', 'admin', '1', 'Admin/Article/index?cate_id=10', '框架', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('23', 'admin', '1', 'Admin/User/index2', '用户行为', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('24', 'admin', '1', 'Admin/User/index1', '权限管理', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('25', 'admin', '1', 'Admin/AuthManager/editGroup', '编辑', '1', '');
INSERT INTO `think_auth_rule` VALUES ('26', 'admin', '1', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '1', '');
INSERT INTO `think_auth_rule` VALUES ('27', 'admin', '1', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '1', '');
INSERT INTO `think_auth_rule` VALUES ('28', 'admin', '1', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '1', '');
INSERT INTO `think_auth_rule` VALUES ('29', 'admin', '1', 'Admin/User/action', '用户行为', '1', '');
INSERT INTO `think_auth_rule` VALUES ('30', 'admin', '1', 'Admin/User/action1', '用户行为', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('31', 'admin', '2', 'Admin/Index/index', '首页', '1', '');
INSERT INTO `think_auth_rule` VALUES ('32', 'admin', '2', 'Admin/Article/index', '内容', '1', '');
INSERT INTO `think_auth_rule` VALUES ('33', 'admin', '2', 'Admin/User/index', '用户', '1', '');
INSERT INTO `think_auth_rule` VALUES ('34', 'admin', '2', 'Admin/Addons/index', '扩展', '1', '');
INSERT INTO `think_auth_rule` VALUES ('35', 'admin', '2', 'Admin/System/index', '系统', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('36', 'admin', '1', 'Admin/AuthManager/createGroup', '新增', '1', '');
INSERT INTO `think_auth_rule` VALUES ('37', 'admin', '1', 'Admin/AuthManager/writeGroup', '保存用户组', '1', '');
INSERT INTO `think_auth_rule` VALUES ('38', 'admin', '1', 'Admin/AuthManager/user', '成员授权', '1', '');
INSERT INTO `think_auth_rule` VALUES ('39', 'admin', '1', 'Admin/AuthManager/removeFromGroup', '解除授权', '1', '');
INSERT INTO `think_auth_rule` VALUES ('40', 'admin', '1', 'Admin/AuthManager/addToGroup', '保存成员授权', '1', '');
INSERT INTO `think_auth_rule` VALUES ('41', 'admin', '1', 'Admin/AuthManager/category', '分类授权', '1', '');
INSERT INTO `think_auth_rule` VALUES ('42', 'admin', '1', 'Admin/AuthManager/group', '授权', '1', '');
INSERT INTO `think_auth_rule` VALUES ('43', 'admin', '1', 'Admin/Model/index', '模型管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('44', 'admin', '1', 'Admin/Addons/config', '设置', '1', '');
INSERT INTO `think_auth_rule` VALUES ('45', 'admin', '1', 'Admin/Addons/disable', '禁用', '1', '');
INSERT INTO `think_auth_rule` VALUES ('46', 'admin', '1', 'Admin/Addons/enable', '启用', '1', '');
INSERT INTO `think_auth_rule` VALUES ('47', 'admin', '1', 'Admin/Addons/install', '安装', '1', '');
INSERT INTO `think_auth_rule` VALUES ('48', 'admin', '1', 'Admin/Addons/uninstall', '卸载', '1', '');
INSERT INTO `think_auth_rule` VALUES ('49', 'admin', '1', 'Admin/Addon/saveconfig', '更新配置', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('50', 'admin', '1', 'Admin/Addons/saveconfig', '更新配置', '1', '');
INSERT INTO `think_auth_rule` VALUES ('51', 'admin', '1', 'Admin/Addons/updateSort', '编辑', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('52', 'admin', '1', 'Admin/Addons/adminList', '插件后台列表', '1', '');
INSERT INTO `think_auth_rule` VALUES ('53', 'admin', '1', 'Admin/Addons/create', '创建', '1', '');
INSERT INTO `think_auth_rule` VALUES ('54', 'admin', '1', 'Admin/Category/index', '分类管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('55', 'admin', '1', 'Admin/Addons/checkForm', '检测创建', '1', '');
INSERT INTO `think_auth_rule` VALUES ('56', 'admin', '1', 'Admin/Addons/window', '弹窗', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('57', 'admin', '1', 'Admin/article/edit', '编辑', '1', '');
INSERT INTO `think_auth_rule` VALUES ('58', 'admin', '1', 'Admin/article/add', '新增', '1', '');
INSERT INTO `think_auth_rule` VALUES ('59', 'admin', '1', 'Admin/article/setStatus', '改变状态', '1', '');
INSERT INTO `think_auth_rule` VALUES ('60', 'admin', '1', 'Admin/user/addAction', '新增用户行为', '1', '');
INSERT INTO `think_auth_rule` VALUES ('61', 'admin', '1', 'Admin/user/editAction', '编辑用户行为', '1', '');
INSERT INTO `think_auth_rule` VALUES ('62', 'admin', '1', 'Admin/user/setStatus', '变更行为状态', '1', '');
INSERT INTO `think_auth_rule` VALUES ('63', 'admin', '1', 'Admin/user/saveAction', '保存用户行为', '1', '');
INSERT INTO `think_auth_rule` VALUES ('64', 'admin', '1', 'Admin/model/add', '新增', '1', '');
INSERT INTO `think_auth_rule` VALUES ('65', 'admin', '1', 'Admin/model/edit', '编辑', '1', '');
INSERT INTO `think_auth_rule` VALUES ('66', 'admin', '1', 'Admin/model/setStatus', '改变状态', '1', '');
INSERT INTO `think_auth_rule` VALUES ('67', 'admin', '1', 'Admin/model/update', '保存数据', '1', '');
INSERT INTO `think_auth_rule` VALUES ('68', 'admin', '1', 'Admin/article/update', '保存数据', '1', '');
INSERT INTO `think_auth_rule` VALUES ('69', 'admin', '1', 'Admin/file/upload', '上传控件', '1', '');
INSERT INTO `think_auth_rule` VALUES ('70', 'admin', '1', 'Admin/file/download', '下载', '1', '');
INSERT INTO `think_auth_rule` VALUES ('71', 'admin', '1', 'Admin/System/channel', '导航管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('72', 'admin', '1', 'Admin/AuthManager/addToCategory', '保存分类授权', '1', '');
INSERT INTO `think_auth_rule` VALUES ('73', 'admin', '1', 'Admin/Addons/preview', '预览', '1', '');
INSERT INTO `think_auth_rule` VALUES ('74', 'admin', '1', 'Admin/Addons/build', '快速生成插件', '1', '');
INSERT INTO `think_auth_rule` VALUES ('75', 'admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');
INSERT INTO `think_auth_rule` VALUES ('76', 'admin', '1', 'Admin/article/recycle', '回收站', '1', '');
INSERT INTO `think_auth_rule` VALUES ('77', 'admin', '1', 'Admin/article/clear', '清空回收站', '1', '');
INSERT INTO `think_auth_rule` VALUES ('78', 'admin', '1', 'Admin/article/permit', '还原', '1', '');
INSERT INTO `think_auth_rule` VALUES ('79', 'admin', '1', 'Admin/user/changeStatus?method=forbidUser', '禁用会员', '1', '');
INSERT INTO `think_auth_rule` VALUES ('80', 'admin', '1', 'Admin/user/changeStatus?method=resumeUser', '启用会员', '1', '');
INSERT INTO `think_auth_rule` VALUES ('81', 'admin', '1', 'Admin/user/changeStatus?method=deleteUser', '删除会员', '1', '');
INSERT INTO `think_auth_rule` VALUES ('83', 'admin', '1', 'Admin/file/uploadPicture', '上传图片', '1', '');
INSERT INTO `think_auth_rule` VALUES ('82', 'admin', '1', 'Admin/Category/edit', '编辑', '1', '');
INSERT INTO `think_auth_rule` VALUES ('84', 'admin', '1', 'Admin/User/editPassword', '修改密码', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('85', 'admin', '1', 'Admin/User/editNickname', '修改昵称', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('86', 'admin', '1', 'Admin/System/config', '配置管理', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('87', 'admin', '1', 'Admin/User/updatePassword', '修改密码', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('88', 'admin', '1', 'Admin/User/updateNickname', '修改昵称', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('89', 'admin', '1', 'Admin/user/submitPassword', '修改密码', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('90', 'admin', '1', 'Admin/user/submitNickname', '修改昵称', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('91', 'admin', '2', 'Admin/Config/base', '系统', '1', '');
INSERT INTO `think_auth_rule` VALUES ('92', 'admin', '1', 'Admin/Config/edit', '编辑', '1', '');
INSERT INTO `think_auth_rule` VALUES ('93', 'admin', '1', 'Admin/Config/del', '编辑', '1', '');
INSERT INTO `think_auth_rule` VALUES ('94', 'admin', '1', 'Admin/Config/base', '基本设置', '1', '');
INSERT INTO `think_auth_rule` VALUES ('95', 'admin', '1', 'Admin/Config/index', '配置管理', '1', '');
INSERT INTO `think_auth_rule` VALUES ('96', 'admin', '1', 'Admin/Addons/edithookaddons', '编辑钩子页面', '-1', '');
INSERT INTO `think_auth_rule` VALUES ('97', 'admin', '1', 'Admin/Addons/execute', 'URL方式访问插件', '1', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='分类表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of think_category
-- ----------------------------
INSERT INTO `think_category` VALUES ('1', 'product', '产品', '0', '1', '10', '', '', '', '', '', '', '1', '2', '0', '0', '1', '0', '', '', '', '1378265725', '1378459277', '1');
INSERT INTO `think_category` VALUES ('2', 'application', '应用', '0', '2', '10', '', '', '', '', '', '', '1', '2', '0', '0', '1', '0', '', '', '', '1378265841', '1378975811', '1');
INSERT INTO `think_category` VALUES ('3', 'bbs', '社区', '0', '3', '10', '', '', '', '', '', '', '1', '2', '0', '0', '1', '0', '', '', '', '1378266283', '1378459274', '1');
INSERT INTO `think_category` VALUES ('4', 'news', '资讯', '0', '4', '10', '', '', '', '', '', '', '1', '2', '0', '0', '1', '0', '', '', '', '1378266306', '1378459262', '1');
INSERT INTO `think_category` VALUES ('5', 'capability', '性能介绍', '1', '1', '10', '', '', '', 'Article/lists_capability', '', '', '1', '2', '0', '1', '1', '0', '', '', '', '1378267351', '1378976032', '1');
INSERT INTO `think_category` VALUES ('6', 'document', '产品文档', '1', '4', '10', '', '', '', 'Article/lists_doc', '', '', '2', '2', '0', '1', '1', '0', '', '', '', '1378267448', '1378954545', '1');
INSERT INTO `think_category` VALUES ('7', 'template_topic', '模板交流', '3', '0', '10', '', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '1', '2', '0', '1', '1', '0', '', '', '', '1378267507', '1378368669', '1');
INSERT INTO `think_category` VALUES ('8', 'addons_topic', '插件交流', '3', '0', '10', '', '', '', 'Article/lists_topic', 'Article/Article/detail_topic', '', '1', '2', '0', '1', '1', '0', '', '', '', '1378267616', '1378361501', '1');
INSERT INTO `think_category` VALUES ('9', 'product_news', '产品新闻', '4', '0', '10', '', '', '', '', '', '', '1,2', '2', '0', '1', '1', '0', '', '', '', '1378267762', '1378717337', '1');
INSERT INTO `think_category` VALUES ('10', 'template', '模板', '2', '0', '10', '', '', '', 'Article/lists_template', '', '', '2', '2', '0', '1', '1', '0', '1', '2', '', '1378368703', '1379152213', '1');
INSERT INTO `think_category` VALUES ('12', 'product_info', '产品动态', '4', '0', '10', '', '', '', '', '', '', '1', '0,1', '0', '1', '1', '0', '', '', '', '1378369726', '1379064311', '1');
INSERT INTO `think_category` VALUES ('14', 'price', '相关服务', '1', '5', '10', '', '', '', 'lists_price', '', '', '1', '2', '0', '1', '1', '0', '', '', '', '1378433321', '1378974565', '1');
INSERT INTO `think_category` VALUES ('15', 'license', '授权协议', '1', '6', '10', '', '', '', 'lists_license', '', '', '1', '2', '0', '1', '1', '0', '', '', '', '1378433444', '1378965623', '1');
INSERT INTO `think_category` VALUES ('17', 'onethink', '产品下载', '1', '2', '10', '', '', '', 'Article/lists_onethink', '', '', '1,2', '2', '0', '1', '1', '0', '', '', '', '1378445623', '1378976103', '1');
INSERT INTO `think_category` VALUES ('18', 'test', '测试分类', '1', '0', '10', '', '', '', '', '', '', '1', '2', '0', '1', '1', '0', '1', '2', '', '1378981042', '1379142843', '1');
INSERT INTO `think_category` VALUES ('19', 'test2', '测试2', '1', '0', '10', '', '', '', '', '', '', '1', '2', '0', '1', '1', '0', '1', '2', '', '1378981128', '1379142832', '1');

-- ----------------------------
-- Table structure for `think_channel`
-- ----------------------------
DROP TABLE IF EXISTS `think_channel`;
CREATE TABLE `think_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_channel
-- ----------------------------
INSERT INTO `think_channel` VALUES ('1', '0', '首页', 'Index/index', '0', '1378977760', '1379149623', '1');
INSERT INTO `think_channel` VALUES ('2', '0', '下载', 'Article/index?category=down', '0', '1378977760', '1378977760', '1');
INSERT INTO `think_channel` VALUES ('3', '0', '扩展', 'Article/index?category=entend', '0', '1378977760', '1378977760', '1');
INSERT INTO `think_channel` VALUES ('4', '0', '资讯', 'Article/index?category=news', '0', '1378977760', '1378977760', '1');
INSERT INTO `think_channel` VALUES ('5', '0', '讨论', 'Article/index?category=topic', '0', '1378977760', '1378983284', '1');
INSERT INTO `think_channel` VALUES ('6', '0', '测试', 'http://www.baidu.com', '0', '1378977760', '1378977760', '1');
INSERT INTO `think_channel` VALUES ('7', '0', '测试s', 'http://thinkphp.cn', '10', '1378977760', '1379128215', '1');

-- ----------------------------
-- Table structure for `think_config`
-- ----------------------------
DROP TABLE IF EXISTS `think_config`;
CREATE TABLE `think_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（0-数字，1-字符，2-文本，3-数组，4-枚举，5-多选）',
  `title` varchar(50) NOT NULL COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组（0-无分组，1-基本设置）',
  `value` text NOT NULL COMMENT '配置值',
  `remark` varchar(100) NOT NULL COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_config
-- ----------------------------
INSERT INTO `think_config` VALUES ('1', 'WEB_SITE_TITLE', '1', '网站标题', '0', 'OneThink内容管理框架', '网站标题前台显示标题', '1378898976', '1378898976', '1');
INSERT INTO `think_config` VALUES ('2', 'WEB_SITE_DESCRIPTION', '1', '网站描述', '0', 'OneThink内容管理框架', '网站搜索引擎描述', '1378898976', '1379038905', '1');
INSERT INTO `think_config` VALUES ('3', 'WEB_SITE_KEYWORD', '1', '网站关键字', '0', 'ThinkPHP,OneThink', '网站搜索引擎关键字', '1378898976', '1378898976', '1');
INSERT INTO `think_config` VALUES ('4', 'WEB_SITE_CLOSE', '0', '关闭站点', '0', '0', '站点关闭后其他用户不能访问，管理员可以正常访问', '1378898976', '1379053644', '1');
INSERT INTO `think_config` VALUES ('9', 'CONFIG_TYPE_LIST', '4', '配置类型列表', '0', '0:数字;1:字符;2:文本;3:数组;4:枚举', '主要用于数据解析和页面表单的生成', '1378898976', '1379055202', '1');
INSERT INTO `think_config` VALUES ('10', 'WEB_SITE_ICP', '1', '网站备案号', '0', '沪ICP备12007941号-21', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', '1378900335', '1378982098', '1');
INSERT INTO `think_config` VALUES ('11', 'DOCUMENT_POSITION', '4', '文档推荐位', '0', '1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐', '文档推荐位，推荐到多个位置KEY值相加即可', '1379053380', '1379055230', '1');
INSERT INTO `think_config` VALUES ('12', 'DOCUMENT_DISPLAY', '4', '文档可见性', '0', '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', '文章可见性仅影响前台显示，后台不收影响', '1379056370', '1379056556', '1');
INSERT INTO `think_config` VALUES ('13', 'COLOR_STYLE', '1', '后台色系', '0', 'blue_color', '后台颜色风格', '1379122533', '1379140237', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COMMENT='文档模型基础表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of think_document
-- ----------------------------
INSERT INTO `think_document` VALUES ('1', '1', 'aaaaa', '撒旦发射点法撒旦发射点法撒旦发射点法', '9', '撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦', '0', '1', '2', '7', '0', '0', '0', '0', '0', '0', '0', '0', '1378273782', '1378273782', '1');
INSERT INTO `think_document` VALUES ('2', '1', 'aaaaaab', '撒旦发射点法撒旦发射点法撒旦发射点法', '9', '撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦', '0', '1', '2', '7', '0', '0', '0', '0', '0', '0', '0', '0', '1378273801', '1378273801', '0');
INSERT INTO `think_document` VALUES ('4', '12', 'qiuzhi', '应届生求职', '9', '应届生求职', '0', '1', '2', '2', '0', '0', '1', '0', '0', '0', '0', '0', '1378344210', '1379041214', '1');
INSERT INTO `think_document` VALUES ('5', '12', 'aaaaaa', '关于模板问题的请教', '7', '关于模板问题的请教关于模板问题的请教', '0', '1', '2', '0', '0', '0', '1', '1383840000', '0', '0', '0', '0', '1378348300', '1378893812', '1');
INSERT INTO `think_document` VALUES ('6', '12', 'fffff', '返回顶部插件怎么用？', '8', '返回顶部插件怎么用？返回顶部插件怎么用？', '0', '1', '2', '1', '0', '0', '1', '0', '0', '0', '0', '0', '1378348583', '1378348583', '1');
INSERT INTO `think_document` VALUES ('7', '12', 'ask', '请问这个模板怎么用？？？？', '7', '请问这个模板怎么用？？？？请问这个模板怎么用？？？？', '0', '1', '2', '0', '0', '0', '1', '0', '0', '0', '0', '0', '1378349736', '1378349736', '1');
INSERT INTO `think_document` VALUES ('11', '1', 'asdfasdfafff', '撒旦发射点法', '6', '撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法', '0', '2', '2', '7', '0', '14', '1', '0', '0', '0', '0', '0', '1379606400', '1379143266', '1');
INSERT INTO `think_document` VALUES ('12', '1', '', '德国主张叙利亚危机交由联合国安理会解决', '12', '新华网柏林9月8日电 德国总理默克尔8日接受德国《图片报》专访时说，德国致力于推动联合国安理会就解决叙利亚危机采取统一行动。', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1378718221', '1378718221', '1');
INSERT INTO `think_document` VALUES ('13', '1', 'aaaaaaa', '图说企业家：家电大鳄 张近东', '12', '福布斯中文版将2012中国年度商业人物的唯一殊荣授予了张近东，49岁的福布斯富豪榜常青树、苏宁集团创始人张近东眼下的处境，代表了中国经济经历从亢奋到常温转变后，一批曾经的明星企业家的命运写照：业务上的直线上升和社会地位上的众星捧月戛然而止', '0', '1', '2', '3', '1', '8', '0', '0', '0', '0', '0', '0', '1378718220', '1379139095', '1');
INSERT INTO `think_document` VALUES ('14', '1', '', '', '0', '', '7', '1', '3', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1378719932', '1378719932', '1');
INSERT INTO `think_document` VALUES ('15', '1', 'asdfasdf', 'OneThink在线版OneThink在线版', '9', 'OneThink在线版OneThink在线版OneThink在线版OneThink在线版', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1378780389', '1378967279', '1');
INSERT INTO `think_document` VALUES ('16', '1', 'eee', '移动服务端整体解决方案', '12', '为移动应用开发者提供稳定可依赖的后端云服务，包括存储、账号管理、社交分享、推送等以及相关的技术支持和服务', '0', '1', '1', '7', '1', '0', '0', '1379649300', '0', '0', '0', '0', '1378784100', '1379139068', '1');
INSERT INTO `think_document` VALUES ('21', '1', 'test', '测试模板', '7', '测试模板', '0', '2', '2', '3', '0', '10', '0', '0', '0', '0', '0', '0', '1378871176', '1378879680', '1');
INSERT INTO `think_document` VALUES ('23', '12', 'receive', ' 乐一下笑话 1.0', '6', '乐一下笑话 1.0\r\n', '0', '2', '2', '0', '0', '0', '1', '1380124800', '0', '0', '0', '0', '1378871571', '1379129450', '1');
INSERT INTO `think_document` VALUES ('36', '12', 'aa34', 'ee442ee', '10', 'afasdf', '0', '2', '2', '0', '0', '0', '1', '1378915200', '0', '0', '0', '0', '1378956793', '1378956875', '1');
INSERT INTO `think_document` VALUES ('38', '12', 'aas', '中国经济进入“第二季”：精彩继续', '10', '　人民网北京9月12日电 （邹光祥）在一些西方人士仍在担忧中国经济会否“硬着陆”时，恰似一叶轻舟，中国经济已经驶出激流与暗礁交织的险滩，进入了高峡出平湖后的宽广。', '0', '2', '2', '0', '0', '14', '0', '1444838400', '0', '0', '0', '0', '1378957447', '1379127513', '1');
INSERT INTO `think_document` VALUES ('39', '10', 'kkkkk', '大幅度反对法', '7', '地方地方', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1378978979', '1379039533', '1');
INSERT INTO `think_document` VALUES ('40', '12', '', '申请人账号：猫没有悲伤', '8', '', '0', '1', '2', '0', '0', '16', '1', '1379057100', '0', '0', '0', '0', '1379040082', '1379043312', '1');
INSERT INTO `think_document` VALUES ('43', '1', '', '我来测试下', '17', '大幅度', '0', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379040163', '1379040233', '1');
INSERT INTO `think_document` VALUES ('44', '12', '', '话说今天是程序员节', '8', '程序员节，每年的第256天，平年的9月13日，闰年的9月12日。', '0', '1', '2', '6', '0', '0', '2', '1378866600', '0', '0', '0', '0', '1379043600', '1379137167', '1');
INSERT INTO `think_document` VALUES ('45', '12', '', 'TPM（ThinkPHP Mobile） 4026 111.24 KB', '9', 'TPM(ThinkPHP Mobile)', '0', '2', '2', '0', '0', '0', '1', '1380340500', '0', '0', '0', '0', '1379044630', '1379044630', '1');
INSERT INTO `think_document` VALUES ('47', '12', '', '12313', '12', '', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379071930', '1379071939', '1');
INSERT INTO `think_document` VALUES ('49', '12', 'ggfff', '积金挤提潮', '10', '积金挤提潮积金挤提潮积金挤提潮积金挤提潮', '0', '2', '2', '6', '0', '18', '1', '1378915200', '0', '0', '0', '0', '1379125362', '1379128251', '1');
INSERT INTO `think_document` VALUES ('51', '1', '', '第一条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129518', '1379129518', '1');
INSERT INTO `think_document` VALUES ('52', '1', '', '第二条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129530', '1379129530', '1');
INSERT INTO `think_document` VALUES ('53', '1', '', '第三条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129542', '1379129542', '1');
INSERT INTO `think_document` VALUES ('54', '1', '', '第三条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129560', '1379129560', '1');
INSERT INTO `think_document` VALUES ('55', '1', '', '第四条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129571', '1379129571', '1');
INSERT INTO `think_document` VALUES ('56', '1', '', '第五条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129803', '1379129803', '1');
INSERT INTO `think_document` VALUES ('57', '1', '', '第五条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129806', '1379129806', '1');
INSERT INTO `think_document` VALUES ('58', '1', '', '第六条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129822', '1379129822', '1');
INSERT INTO `think_document` VALUES ('59', '1', '', '第六条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129823', '1379129823', '1');
INSERT INTO `think_document` VALUES ('60', '1', '', '第七条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129867', '1379129867', '1');
INSERT INTO `think_document` VALUES ('61', '1', '', '第七条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129868', '1379129868', '1');
INSERT INTO `think_document` VALUES ('62', '1', '', '第七条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129868', '1379129868', '1');
INSERT INTO `think_document` VALUES ('63', '1', '', '第七条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129868', '1379129868', '1');
INSERT INTO `think_document` VALUES ('64', '1', '', '第七条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129869', '1379129869', '1');
INSERT INTO `think_document` VALUES ('65', '1', '', '第七条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379129869', '1379130167', '1');
INSERT INTO `think_document` VALUES ('66', '1', '', '第八条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379130311', '1379130311', '1');
INSERT INTO `think_document` VALUES ('67', '1', '', '第八条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379130311', '1379130311', '1');
INSERT INTO `think_document` VALUES ('68', '1', '', '第八条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379130312', '1379130312', '1');
INSERT INTO `think_document` VALUES ('72', '1', 'gff', '1234234', '18', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379157569', '1');
INSERT INTO `think_document` VALUES ('70', '1', '', '第八条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379130312', '1379130312', '1');
INSERT INTO `think_document` VALUES ('71', '1', '', '第八条', '5', '', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1334678400', '1379148111', '0');
INSERT INTO `think_document` VALUES ('74', '1', '', '大幅度反对法', '18', '', '0', '1', '2', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379157519', '1');
INSERT INTO `think_document` VALUES ('75', '11', 'sdf12312', '测试有拍云存', '9', 'asdas', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379158095', '1379158095', '-1');
INSERT INTO `think_document` VALUES ('76', '11', 'f123', '测试附件下载', '7', '请企鹅王', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1379158650', '1379158650', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='文档模型表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- ----------------------------
-- Records of think_document_model
-- ----------------------------
INSERT INTO `think_document_model` VALUES ('1', 'Article', '文章', '0', '1378966643', '1');
INSERT INTO `think_document_model` VALUES ('2', 'Download', '下载', '0', '0', '1');
INSERT INTO `think_document_model` VALUES ('3', 'Application', '应用', '0', '0', '0');
INSERT INTO `think_document_model` VALUES ('4', 'Atlas', '图集', '1377569866', '1378949480', '0');
INSERT INTO `think_document_model` VALUES ('5', 'fdsfsf234', 'sdfsdfsfsd111', '1379151531', '1379152084', '0');

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
INSERT INTO `think_document_model_article` VALUES ('2', '0', '撒旦发射点法撒旦发射点法撒旦发射点法', '');
INSERT INTO `think_document_model_article` VALUES ('4', '0', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应届生求职阿斯蒂芬', '');
INSERT INTO `think_document_model_article` VALUES ('5', '0', '<strong>关于模板问题的请教关于模板问题的请教关于模板问题的请教关于模板问题的请教关于模板问题的请教关于模板问题的请教</strong>', '');
INSERT INTO `think_document_model_article` VALUES ('6', '0', '返回顶部插件怎么用？返回顶部插件怎么用？返回顶部插件怎么用？返回顶部插件怎么用？返回顶部插件怎么用？返回顶部插件怎么用？返回顶部插件怎么用？', '');
INSERT INTO `think_document_model_article` VALUES ('7', '0', '请问这个模板怎么用？？？？请问这个模板怎么用？？？？请问这个模板怎么用？？？？请问这个模板怎么用？？？？请问这个模板怎么用？？？？', '');
INSERT INTO `think_document_model_article` VALUES ('12', '0', '<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 默克尔建议，安理会可通过国际刑事法院对叙政府是否使用化学武器进行裁定。她强调，叙利亚问题必须通过政治途径解决。\r\n</p>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp; 默克尔重申，德国不会参与可能对叙利亚采取的军事行动。\r\n</p>\r\n<div class=\"gg200x300\">\r\n</div>\r\n<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp; 默克尔说，国际社会必须确保不再发生使用大规模杀伤性武器事件。她希望联合国调查叙利亚化学武器问题真相小组尽快提交调查报告，并表示德国将为调查小组检验样本提供帮助。\r\n</p>\r\n<p>\r\n	默克尔说，德国承诺接收5000名叙利亚难民。自2011年起，德国已接收超过1．7万名叙利亚政治避难人员，并向叙提供超过3亿欧元（3．9亿美元）援助资金，用于人道主义救助。\r\n</p>\r\n<p>\r\n	默克尔曾多次强调，希望政治解决叙利亚问题，德国政府对参与可能对叙利亚采取的军事行动持最大克制态度。在6日结束的二十国集团领导人第八次峰会上，默克尔表示应由国际刑事法院对叙危机进行评估。\r\n</p>\r\n<p>\r\n	(原标题：德国主张叙利亚危机交由联合国安理会解决)\r\n</p>', '');
INSERT INTO `think_document_model_article` VALUES ('13', '0', '<p>\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 福布斯中文版将2012中国年度商业人物的唯一殊荣授予了张近东，49岁的福布斯富豪榜常青树、苏宁集团创始人张近东眼下的处境，代表了中国经济经历从亢奋到常温转变后，一批曾经的明星企业家的命运写照：业务上的直线上升和社会地位上的众星捧月戛然而止。\r\n</p>', '');
INSERT INTO `think_document_model_article` VALUES ('14', '0', '请问这个模板怎么用？？？？请问这个模板怎么用？？？？请问这个模板怎么用？？？？请问这个模板怎么用？？？？请问这个模板怎么用？？？？', '');
INSERT INTO `think_document_model_article` VALUES ('15', '2', 'OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版OneThink在线版\r\n\r\n', '');
INSERT INTO `think_document_model_article` VALUES ('16', '0', 'asdfasdf', '');
INSERT INTO `think_document_model_article` VALUES ('62', '0', '地方的', '');
INSERT INTO `think_document_model_article` VALUES ('63', '0', '地方的', '');
INSERT INTO `think_document_model_article` VALUES ('64', '0', '地方的', '');
INSERT INTO `think_document_model_article` VALUES ('65', '0', '地方的', '');
INSERT INTO `think_document_model_article` VALUES ('66', '0', '地方的地方', '');
INSERT INTO `think_document_model_article` VALUES ('67', '0', '地方的地方', '');
INSERT INTO `think_document_model_article` VALUES ('68', '0', '地方的地方', '');
INSERT INTO `think_document_model_article` VALUES ('72', '0', '23rwer', '');
INSERT INTO `think_document_model_article` VALUES ('70', '0', '地方的地方', '');
INSERT INTO `think_document_model_article` VALUES ('71', '0', '地方的地方', '');
INSERT INTO `think_document_model_article` VALUES ('74', '0', '打发打发', '');
INSERT INTO `think_document_model_article` VALUES ('75', '0', 'aa', '');
INSERT INTO `think_document_model_article` VALUES ('76', '0', '请问', '');
INSERT INTO `think_document_model_article` VALUES ('39', '0', '打发打发', '');
INSERT INTO `think_document_model_article` VALUES ('61', '0', '地方的', '');
INSERT INTO `think_document_model_article` VALUES ('40', '0', '申请认证类型：认证用户<br />\r\n<br />\r\n真实姓名：唐子杰<br />\r\n性别：男<br />\r\n年龄：21<br />\r\n联系邮箱：1277783413@qq.com<br />\r\n联系电话：15665725523<br />\r\n所在地区：山东 省 济南市<br />\r\n擅长：PHP，前端，网络安全', '');
INSERT INTO `think_document_model_article` VALUES ('44', '0', '该节日已被众多国际IT公司所认可。<br />\r\n<b>关于256：</b> \r\n<pre class=\"prettyprint lang-html\">     256 = 2^8\r\n    这个数字因为它能表示程序员所熟知的，一个字节是由8位二进制数构成的意义，\r\n    256也是2的幂中最接近但不超过365的数。</pre>\r\n<div class=\"think-copy\">\r\n	<span>复制代码</span> \r\n</div>\r\n程序员节百科：<a href=\"http://baike.baidu.com/view/4367098.htm\" target=\"_blank\">http://baike.baidu.com/view/4367098.htm</a><br />\r\n<br />\r\n==》祝各位码农们节日快乐啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊....', '');
INSERT INTO `think_document_model_article` VALUES ('47', '0', 'sdff', '');
INSERT INTO `think_document_model_article` VALUES ('51', '0', '大幅度', '');
INSERT INTO `think_document_model_article` VALUES ('52', '0', '大幅度', '');
INSERT INTO `think_document_model_article` VALUES ('53', '0', '大幅度发', '');
INSERT INTO `think_document_model_article` VALUES ('54', '0', '大幅度', '');
INSERT INTO `think_document_model_article` VALUES ('55', '0', '地方', '');
INSERT INTO `think_document_model_article` VALUES ('56', '0', '地方地方', '');
INSERT INTO `think_document_model_article` VALUES ('57', '0', '地方地方', '');
INSERT INTO `think_document_model_article` VALUES ('58', '0', '的幅度', '');
INSERT INTO `think_document_model_article` VALUES ('59', '0', '的幅度', '');
INSERT INTO `think_document_model_article` VALUES ('60', '0', '地方的', '');

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
INSERT INTO `think_document_model_download` VALUES ('11', '0', '撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法撒旦发射点法', '', '10', '123', '1354');
INSERT INTO `think_document_model_download` VALUES ('21', '0', '测试内容', '', '39', '10', '72550');
INSERT INTO `think_document_model_download` VALUES ('23', '0', '<p>\r\n	代码灰常的简单，之前打算把后台和会员系统弄好再发的，但是有很多TPER求码，只好先发了，持续更新。。\r\n一个小小的笑话网站，没什么特别的功能，集成了美图秀秀编辑器，有些功能代码已经写好，如上传制作素材在线制作功能。只是模版一直木有时间做。模版有两套，一套网页版，一套手机版，首页自动判断。\r\nTP菜鸟，大神勿喷。。\r\n演示地址 http://www.leyix.com\r\n本人联系QQ：296404875 期待共同开发\r\n</p>', '', '37', '0', '2462');
INSERT INTO `think_document_model_download` VALUES ('36', '2', 'fsgfgdfgsdfg\r\n\r\n', '', '10', '0', '1354');
INSERT INTO `think_document_model_download` VALUES ('38', '0', '9月11日，国务院总理李克强在2013夏季[达沃斯][0]论坛开幕式上发表的特别致辞中表示，中国经济发展的奇迹已经进入提质增效的“第二季”，后面的故事将更精彩。\r\n这是中国向世界传递出的自信。10日，李克强总理为英国《[金融][1]时报》撰文时就曾指出，中国将保持经济长期健康发展，中国将继续走改革开放之路。\r\n信心来源于中国经济整体的基本面：进入下半年以来，在稳增长、调结构、促改革的[宏观][2]调控政策框架下，中国经济逐渐企稳回升，各项经济指标均呈现出良好的态势。国家统计局最新数据显示，8月官方PMI为51.0％，环比上升0.7个百分点。这是PMI连续第二个月出现回升，也创下了自去年5月份以来的新高。8月汇丰PMI数据同样呈现好转迹象。8月汇丰PMI为50.1，环比跳升2.4个百分点，创下4个月新高，并与官方PMI数据趋同。\r\n在美国QE退出未明，全球经济危机阴霾尚未消散，新兴经济体遭遇资金大规模撤离和[金融市场][1]动荡的外部不利的情况下，中国金融和资本市场表现稳健。\r\n回望历史，展望未来。如果说自1979年实施改革开放以来，过去30多年年均9.8%高增长是中国经济的“第一季”的话，那么，“第一季”呈现给世界的是精彩绝伦、跌宕起伏的连续剧，奇迹始终贯穿其间。在中国经济演绎的“奇迹”中，“危机论”与“崩溃论”不绝于耳，各种“唱衰”与“唱空”不时流行，但最后都归于破灭。这不能不说是中国经济的另一个“奇迹”。\r\n自2013年以来，中国经济开始主动减速，由此进入“轻舟已过万重山”的第二季。第二季的主题就是“提质增效”，注重经济增长的质量和效益。在经济学家[林毅夫][3]看来，如果中国能够继续深化改革，消除各种结构性的缺陷，并按比较优势来发展经济以充分利用后发优势，中国有可能将8%的增长潜力变为现实的长期增长率。\r\n英大证券研究所所长叶旭晨表示，中国经济改革“第二季”要实现两个核心转变。其一，中国企业必须从粗放的制造走向创新；其二，经济增长的驱动力，必须从投资转向[消费][4]。\r\n中国经济第二季的精彩故事将是一场改革的大片。要实现企业创新，必须加大知识产权保护，消除寻租的制度空间，减少扭曲的生产要素价格，破除行政垄断、促进公平竞争。政府减少对经济活动的直接干预，放弃做运动员，专注于做裁判员。同时，政府的财政支出，应从经济建设转向社会保障，通过完善医疗、教育、[养老][5]体制，消除百姓消费的后顾之忧。\r\n“把错装在政府身上的手换成市场的手”，且要有“壮士断腕的决心”。李克强总理履新时的讲话掷地有声。由是观之，中国经济的“第二季”，要义之一就是政府自身的改革。实现“自己改革自己”这看似不可能的任务，关键是灵魂深处的革命。\r\n青山遮不住，毕竟东流去。中国经济的第二季：精彩继续，改革继\r\n[0]: http://finance.qq.com/zt2012/davos2012/\r\n[1]: http://finance.qq.com/l/financenews/jinrongshichang/jinrong.htm\r\n[2]: http://finance.qq.com/l/financenews/domestic/index.htm\r\n[3]: http://datalib.finance.qq.com/peoplestar/103/index.shtml\r\n[4]: http://finance.qq.com/l/industry/xiaofeits08/xiaofei.htm\r\n[5]: http://finance.qq.com/l/insurance/xzzq/yanglao/', '', '39', '0', '72550');
INSERT INTO `think_document_model_download` VALUES ('43', '0', '的饭店反对法', '', '39', '0', '72550');
INSERT INTO `think_document_model_download` VALUES ('45', '0', '能快点出ios版本吗，不会变成半成品吧', '', '12', '99999958', '4536');
INSERT INTO `think_document_model_download` VALUES ('49', '0', '<ul class=\"clearfix\" id=\"s_topsearch\">\r\n	<li class=\"s-topsearch-item\">\r\n		<div>\r\n			<a target=\"_blank\" href=\"http://www.baidu.com/baidu?cl=3&tn=baidutop10&fr=top1000&wd=%E8%B1%AA%E5%8D%8E%E6%9C%AC%E7%A7%91%E5%AE%BF%E8%88%8D&rsv_spt=2&issp=2\"><em class=\"word-key\"></em></a> \r\n		</div>\r\n<br />\r\n	</li>\r\n	<li class=\"s-topsearch-item\">\r\n		<div>\r\n			<span class=\"s-topsearch-item-dot\"></span><a target=\"_blank\" href=\"http://www.baidu.com/baidu?cl=3&tn=baidutop10&fr=top1000&wd=%E5%A4%84%E5%A5%B3%E8%A1%80%E5%81%9A%E7%A0%94%E7%A9%B6&rsv_spt=2&issp=2\"><em class=\"word-key\">处女血做研究</em></a> \r\n		</div>\r\n	</li>\r\n	<li class=\"s-topsearch-item\">\r\n		<div>\r\n			<span class=\"s-topsearch-item-dot\"></span><a target=\"_blank\" href=\"http://www.baidu.com/baidu?cl=3&tn=baidutop10&fr=top1000&wd=%E7%A8%8B%E5%BA%8F%E5%91%98%E8%8A%82&rsv_spt=2&issp=2\"><em class=\"word-key\">程序员节</em></a> \r\n		</div>\r\n	</li>\r\n	<li class=\"s-topsearch-item\">\r\n		<div>\r\n			<span class=\"s-topsearch-item-dot\"></span><a target=\"_blank\" href=\"http://www.baidu.com/baidu?cl=3&tn=baidutop10&fr=top1000&wd=%E6%9D%8E%E5%A4%A9%E4%B8%80%E6%A1%885%E4%BA%BA%E6%9B%BE%E4%B8%B2%E4%BE%9B&rsv_spt=2&issp=2\"><em class=\"word-key\">李天一案5人曾串供</em></a> \r\n		</div>\r\n	</li>\r\n	<li class=\"s-topsearch-item\">\r\n		<div>\r\n			<span class=\"s-topsearch-item-dot\"></span><a target=\"_blank\" href=\"http://www.baidu.com/baidu?cl=3&tn=baidutop10&fr=top1000&wd=%E7%8E%8B%E7%A5%96%E8%B4%A4%E8%BD%A6%E7%A5%B8&rsv_spt=2&issp=2\"><em class=\"word-key\">王祖贤车祸</em><em class=\"nlabel\">新!</em></a> \r\n		</div>\r\n	</li>\r\n	<li class=\"s-topsearch-item\">\r\n		<div>\r\n			<span class=\"s-topsearch-item-dot\"></span><a target=\"_blank\" href=\"http://www.baidu.com/baidu?cl=3&tn=baidutop10&fr=top1000&wd=%E5%B0%91%E5%A5%B3%E9%81%AD%E5%BC%BA%E5%A5%B8%E8%87%B4%E5%AD%95%E5%90%8C%E5%B1%85&rsv_spt=2&issp=2\"><em class=\"word-key\">少女遭强奸致孕同居</em></a> \r\n		</div>\r\n	</li>\r\n	<li class=\"s-topsearch-item\">\r\n		<div>\r\n			<span class=\"s-topsearch-item-dot\"></span><a target=\"_blank\" href=\"http://www.baidu.com/baidu?cl=3&tn=baidutop10&fr=top1000&wd=%E6%9D%8E%E4%BA%9A%E9%B9%8F%E7%8E%8B%E8%8F%B2%E7%A6%BB%E5%A9%9A&rsv_spt=2&issp=2\"><em class=\"word-key\">李亚鹏王菲离</em></a> \r\n		</div>\r\n	</li>\r\n</ul>', '', '48', '12333', '106371');

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
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COMMENT='文件表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

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
INSERT INTO `think_file` VALUES ('16', '未命名.jpg', '5218eb66cb937.jpg', '2013-08-25/', 'jpg', 'application/octet-stream', '553536', '31026f1f43ec4e3293c9b64c834e1a01', '2e9e3f8cbbcf5018788f3f427e34d1ef36fbc0bc', '0', '1377364838');
INSERT INTO `think_file` VALUES ('17', 'TPM文档.docx', '521c172aa329b.docx', '2013-08-27/', 'docx', 'application/octet-stream', '82883', 'cb24a13d97bfb4e42bba3c7a9933b514', '9e2d1742a30360dd292fe4777c18aefed3282245', '0', '1377572650');
INSERT INTO `think_file` VALUES ('18', 'ThinkPHP3.1.2完全开发手册CHM[2013-01', '521c235a1566e.chm', '2013-08-27/', 'chm', 'application/octet-stream', '681387', '43fc1aa176c8348b888437464cf78c90', '798937186135795fbb24474794e832f74d23cb8a', '0', '1377575769');
INSERT INTO `think_file` VALUES ('19', '官网日常运营.txt', '521ee9fe2b5f4.txt', '2013-08-29/', 'txt', 'application/octet-stream', '1857', 'd3ccb0643ba6b2aea3d10f261baaf637', 'cfc81fbb386b17c91b0d715a0388c039bc2ea999', '0', '1377757694');
INSERT INTO `think_file` VALUES ('20', 'hosts', '521eedee0e71c.', '2013-08-29/', '', 'application/octet-stream', '999', 'fe638d604d7041eea130c9be90cc8863', '3a511a2f41802a47b7b4253154904eef84e66b93', '0', '1377758701');
INSERT INTO `think_file` VALUES ('21', '7a3f559742d9fc7cfa71fc6e708c61', '52204a64e4fa4.gif', '2013-08-30/', 'gif', 'application/octet-stream', '484038', '8de12e29f5964c48741535525fbc319c', 'be1c7987c5afd50db58acc31390a5ddd027d7128', '0', '1377847908');
INSERT INTO `think_file` VALUES ('22', '08c93133acee899777b266bf9300fc', '52204a81adb46.gif', '2013-08-30/', 'gif', 'application/octet-stream', '438173', '39a17d7f67a8de2e8cc7977488482aee', '2e1139b34439564e2648d8695b0ed43e584e428d', '0', '1377847937');
INSERT INTO `think_file` VALUES ('23', '42a1f42bb4592832c1f6f41566e320', '52204aa5d2f62.gif', '2013-08-30/', 'gif', 'application/octet-stream', '509760', '9825db5ba7b43743e3cd938ed6fb0c3d', 'c87a7a97f91d8e4ea285099476d57b61049441ae', '0', '1377847973');
INSERT INTO `think_file` VALUES ('24', 'IMG_20130825_083921.jpg', '5224361393fe1.jpg', '2013-09-02/', 'jpg', 'application/octet-stream', '121317', 'dba47e649c280cb575d656bfc1b337f0', '942d6cedbc2ab9bc31ee3b9351cb8ea2b369df2b', '0', '1378104851');
INSERT INTO `think_file` VALUES ('25', 'QQ截图20130816133754.png', '522438277438c.png', '2013-09-02/', 'png', 'application/octet-stream', '177662', '3f914622e62879cd9b54e757d3baf4fa', 'bfc84cca804f31c64a24d2ead7fb7e399e6e33cc', '0', '1378105382');
INSERT INTO `think_file` VALUES ('26', 'QQ截图20130731160446.png', '522438edaabe4.png', '2013-09-02/', 'png', 'application/octet-stream', '17023', '5bb9e2e23470342711ddbaf3a1fd53ce', 'd749393084a67d53228d32ddaef844261366fccb', '0', '1378105581');
INSERT INTO `think_file` VALUES ('27', 'QQ截图20130804155639.png', '522439cc92209.png', '2013-09-02/', 'png', 'application/octet-stream', '26705', '30a16dd5940e1243344560fb85a5e77e', 'c19774d5e8abf4a413155c603b416712dcde5763', '0', '1378105804');
INSERT INTO `think_file` VALUES ('28', 'psb_white2.jpg', '5225a68749151.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '7546', '0578abb03d826fc21b05465ee0bf3954', 'b5b8d17dabd46d285c9ea4df9390bc07bc8a189f', '0', '1378199173');
INSERT INTO `think_file` VALUES ('29', 'psb_white.jpg', '5225a75a3dccd.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '7589', 'affa9eb24e7f17082c0a30f31c1455a5', '4d073951bc2bf10db55979a470bfad6945f71083', '0', '1378199382');
INSERT INTO `think_file` VALUES ('30', 'IMG_20130819_130920.jpg', '5225aad51c395.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '994489', '16ea4fa80605ea520e5d009d28e554bc', '3aa2a04c29ac82f811e96974f68069c58dd32bec', '0', '1378200273');
INSERT INTO `think_file` VALUES ('31', 'psb.jpg', '5225ab64004ac.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '7158', 'a941279ed8b5d46f6d860b4c92803add', 'efe6b805e08ca40f4499bce4e8fc1941002d6812', '0', '1378200416');
INSERT INTO `think_file` VALUES ('32', 'QQ截图20130830094219.png', '5225ac1ca6bd4.png', '2013-09-03/', 'png', 'application/octet-stream', '38472', 'dbeef2e5c8e2ef4019ed8c2756087451', 'd1b6295522682993d15423ef7ec69d179e7ce83a', '0', '1378200602');
INSERT INTO `think_file` VALUES ('33', '魁拔之大战元泱界.jpg', '5225acfe7e893.jpg', '2013-09-03/', 'jpg', 'application/octet-stream', '157994', 'ece97d80d1ac12e4f3888f09ed74fa6e', '459535d6465fd9ba956efdc2ade09391f82f5113', '0', '1378200828');
INSERT INTO `think_file` VALUES ('34', '上机题.docx', '52282c4101205.docx', '2013-09-05/', 'docx', 'application/octet-stream', '13405', '8abc6530747bd1d3e22a9191e36a4e27', '41ce40adf1bc1ccab0276b97116039fed59760b4', '0', '1378364480');
INSERT INTO `think_file` VALUES ('35', 'ThinkPHP CMS.pdf', '522e84da834ca.pdf', '2013-09-10/', 'pdf', 'application/octet-stream', '129460', 'c712e6ea9fcf21fd2bafb8db07ee13a5', '23b0806b0824f956abebadd0f05a34820c3f6034', '0', '1378780378');
INSERT INTO `think_file` VALUES ('36', 'thinkcms_20130903.sql', '522e86a658447.sql', '2013-09-10/', 'sql', 'application/octet-stream', '69932', 'a77675379729466f7a5acfe8db40759e', '83eb5303ab0eea95743a384651db32e02a54c5a5', '0', '1378780838');
INSERT INTO `think_file` VALUES ('37', '任务列表表.txt', '522fe8acc0e22.txt', '2013-09-11/', 'txt', 'application/octet-stream', '2462', '3f0c717a4b601af919304648f636673d', 'b181d898da26e7d970099a3e0a58f4dd97abac1e', '0', '1378871468');
INSERT INTO `think_file` VALUES ('38', 'product_video.html', '52300260904da.html', '2013-09-11/', 'html', 'application/octet-stream', '8885', '2b74c5f82b295763a70dc22ab91f2ffe', '6440e1a5a3c0fa71cf9cc18254340372d032a5a2', '0', '1378878048');
INSERT INTO `think_file` VALUES ('39', '51d248f1135a9.jpg', '5230075710ad8.jpg', '2013-09-11/', 'jpg', 'application/octet-stream', '72550', '151178e5513c171c1177e4386a7f9e81', '0dac6e0346138dae5fa013d0f47638041a2fc376', '0', '1378879318');
INSERT INTO `think_file` VALUES ('40', '8ad4b31c8701a18b2eb24c019e2f07', '52300ba1a8878.gif', '2013-09-11/', 'gif', 'application/octet-stream', '991750', '9675d17f0ad8be9e4c50857cf47f89b7', '161637ba3f778cdf12d77319dde2790d1c0ad915', '0', '1378880417');
INSERT INTO `think_file` VALUES ('41', 'RunYNote.exe', '5231386bb32e7.exe', '2013-09-12/', 'exe', 'application/octet-stream', '656016', 'f5ce6d7077a8d7f98a1addb7af3775ab', 'e8a7bfd2c439132a49432b8207f40efcbb6361a0', '0', '1378957419');
INSERT INTO `think_file` VALUES ('42', 'gvim.exe', '5231aa7c2c856.exe', '2013-09-12/', 'exe', 'application/octet-stream', '1994240', 'e1b6be486aaedcbdf20656f3d68b6e23', 'ee7d7bc70346f016f69bd86a4face01d0b9caa90', '0', '1378986619');
INSERT INTO `think_file` VALUES ('43', '元全vps信息 by cooper 20130124(1).', '5232710b2e344.xlsx', '2013-09-13/', 'xlsx', 'application/octet-stream', '18478', '3b7d75acbf5b99e8601def6478baacbe', 'aa69e44e7a306f9ce89771b1ac11e839594bc9d0', '0', '1379037450');
INSERT INTO `think_file` VALUES ('44', 'ajax-loader.gif', '523272256cd07.gif', '2013-09-13/', 'gif', 'application/octet-stream', '10819', '57ca1a2085d82f0574e3ef740b9a5ead', '2974f4bf37231205a256f2648189a461e74869c0', '0', '1379037733');
INSERT INTO `think_file` VALUES ('45', 'Computer_Security_Risk.png', '5232722e36f43.png', '2013-09-13/', 'png', 'application/octet-stream', '787', 'f78b8be142ceb58771f73b703da642c1', 'bec789e85563902149368074f88fc1c28893385e', '0', '1379037741');
INSERT INTO `think_file` VALUES ('46', 'chm_start.html', '5232c3fe06d52.html', '2013-09-13/', 'html', 'application/octet-stream', '1242', '10abb570e3cf6d5909e3373c11219887', '448a1cd25ceee9a4a2ecba6c34b34a831f3a2a6e', '0', '1379058685');
INSERT INTO `think_file` VALUES ('47', 'u16_normal.png', '5232cdd849147.png', '2013-09-13/', 'png', 'application/octet-stream', '8829', '8ea26d7027275fb4f66a2dc1ed54c13b', 'a25b6a5cda546d8febd7a927a4f0fe913503e648', '0', '1379061208');
INSERT INTO `think_file` VALUES ('48', '招聘2.jpg', '5233c86f9add8.jpg', '2013-09-14/', 'jpg', 'application/octet-stream', '106371', '911d4fe765a0f17d254ed51885e2518a', 'f9b07c025bff974f4d73f32f54a2990bcb6863c8', '0', '1379125359');
INSERT INTO `think_file` VALUES ('49', 'Lighthouse.jpg', '52343d858f58b.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '561276', '8969288f4245120e7c3870287cce0ff3', '1b4605b0e20ceccf91aa278d10e81fad64e24e27', '0', '1379155333');
INSERT INTO `think_file` VALUES ('50', 'Penguins.jpg', '52343ec46d0e4.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '777835', '9d377b10ce778c4938b3c7e2c63a229a', 'df7be9dc4f467187783aca68c7ce98e4df2172d0', '0', '1379155652');
INSERT INTO `think_file` VALUES ('51', 'Tulips.jpg', '52344155a89c8.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '620888', 'fafa5efeaf3cbe3b23b2748d13e629a1', '54c2f1a1eb6f12d681a5c7078421a5500cee02ad', '0', '1379156309');
INSERT INTO `think_file` VALUES ('52', 'Desert.jpg', '5234416d1f098.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '845941', 'ba45c8f60456a672e003a875e469d0eb', '30420d1a9afb2bcb60335812569af4435a59ce17', '0', '1379156332');
INSERT INTO `think_file` VALUES ('53', 'Hydrangeas.jpg', '5234418c17e4a.jpg', '2013-09-14/', 'jpg', 'image/jpeg', '595284', 'bdf3bf1da3405725be763540d6601144', 'd997e1c37edc05ad87d03603e32ad495ee2cfce1', '0', '1379156363');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_hooks
-- ----------------------------
INSERT INTO `think_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', 'AdaptiveImages');
INSERT INTO `think_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', 'ReturnTop');
INSERT INTO `think_hooks` VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', 'Attachment');
INSERT INTO `think_hooks` VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', 'SocialComment,Attachment');
INSERT INTO `think_hooks` VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', null);
INSERT INTO `think_hooks` VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', 'Attachment');
INSERT INTO `think_hooks` VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', 'Editor');
INSERT INTO `think_hooks` VALUES ('8', 'adminArticleEdit', '后台内容编辑页编辑器', '1', '1378982734', 'EditorForAdmin');
INSERT INTO `think_hooks` VALUES ('13', 'AdminIndex', '首页小格子个性化显示', '1', '1379153797', 'SystemInfo,DevTeam');

-- ----------------------------
-- Table structure for `think_member`
-- ----------------------------
DROP TABLE IF EXISTS `think_member`;
CREATE TABLE `think_member` (
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
-- Records of think_member
-- ----------------------------
INSERT INTO `think_member` VALUES ('9', '麦当苗儿', '0', '0000-00-00', '', '0', '11', '2130706433', '1369722401', '2130706433', '1371192515', '1');
INSERT INTO `think_member` VALUES ('1', '管理员', '0', '0000-00-00', '', '40', '262', '2130706433', '1371435498', '2130706433', '1379155532', '1');
INSERT INTO `think_member` VALUES ('10', 'thinkphp', '0', '0000-00-00', '', '10', '10', '3232235922', '1374043830', '2130706433', '1379041194', '1');
INSERT INTO `think_member` VALUES ('11', 'yangweijie', '0', '0000-00-00', '', '60', '44', '2130706433', '1376897307', '2130706433', '1379126003', '1');
INSERT INTO `think_member` VALUES ('12', '奥巴马', '0', '0000-00-00', '', '231', '29', '2130706433', '1376968536', '2130706433', '1379122955', '1');
INSERT INTO `think_member` VALUES ('13', 'zhuyajie', '0', '0000-00-00', '', '10', '2', '2130706433', '1378440279', '2130706433', '1378440293', '1');

-- ----------------------------
-- Table structure for `think_picture`
-- ----------------------------
DROP TABLE IF EXISTS `think_picture`;
CREATE TABLE `think_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL COMMENT '文件md5',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of think_picture
-- ----------------------------
INSERT INTO `think_picture` VALUES ('7', '/Uploads/Picture/2013-09-11/522fd436ee35e.jpg', '', 'e06956cecda298b75f69387afa4a7483', '1', '1378866230');
INSERT INTO `think_picture` VALUES ('8', '/Uploads/Picture/2013-09-11/522fd6f1a7a3e.gif', '', 'a9c6422b75fa423e0769fc4c2fee8d4a', '1', '1378866929');
INSERT INTO `think_picture` VALUES ('9', '/Uploads/Picture/2013-09-11/5230087ef00c2.jpg', '', '71168c9f95dbf7afa424c547533b6ded', '1', '1378879614');
INSERT INTO `think_picture` VALUES ('10', '/Uploads/Picture/2013-09-11/523008ba4fde5.png', '', '3b0fd01ef469dad40ba62b53f5edcdaa', '1', '1378879674');
INSERT INTO `think_picture` VALUES ('11', '/Uploads/Picture/2013-09-11/52300b806c249.gif', '', '39a17d7f67a8de2e8cc7977488482aee', '1', '1378880384');
INSERT INTO `think_picture` VALUES ('12', '/Uploads/Picture/2013-09-11/523012e46fca2.jpg', '', 'e47ddd19ab2ef444c9fc1dbde72cec61', '1', '1378882276');
INSERT INTO `think_picture` VALUES ('13', '/Uploads/Picture/2013-09-12/5231af5f583cf.docx', '', 'b7d7a200cffa810a3b1f312aa4f72a3c', '1', '1378987871');
INSERT INTO `think_picture` VALUES ('14', '/Uploads/Picture/2013-09-13/52327959a62d2.jpg', '', '151178e5513c171c1177e4386a7f9e81', '1', '1379039577');
INSERT INTO `think_picture` VALUES ('15', '/Uploads/Picture/2013-09-13/523287bdb9507.jpg', '', 'd2f6700c819306ed896ef2fc0f43c6f6', '1', '1379043261');
INSERT INTO `think_picture` VALUES ('16', '/Uploads/Picture/2013-09-13/523287c988154.gif', '', 'deba4e6b9dc008167006a112f3b94154', '1', '1379043273');
INSERT INTO `think_picture` VALUES ('17', '/Uploads/Picture/2013-09-13/5232a55d3201c.png', '', 'cd99fc1e3274a555755f854240d9e26c', '1', '1379050845');
INSERT INTO `think_picture` VALUES ('18', '/Uploads/Picture/2013-09-13/5232b22beeb5f.jpg', '', 'e2113d4293d779134fa2f71ee0b8bb34', '1', '1379054123');
INSERT INTO `think_picture` VALUES ('19', '/Uploads/Picture/2013-09-13/5232b7c666ced.png', '', 'e298f953fbafadcfb3fcb979fc47da1f', '1', '1379055558');
INSERT INTO `think_picture` VALUES ('20', '/Uploads/Picture/2013-09-13/5232c26e80b8d.png', '', '8ea26d7027275fb4f66a2dc1ed54c13b', '1', '1379058286');
INSERT INTO `think_picture` VALUES ('21', '/Uploads/Picture/2013-09-13/5232c32840f98.png', '', '8615bb31bbf5198f858dd87092c5dde2', '1', '1379058472');
INSERT INTO `think_picture` VALUES ('22', '/Uploads/Picture/2013-09-13/5232c38c4b6dc.png', '', '81a0a3da5b83df6225830d145cd7f51a', '1', '1379058572');
INSERT INTO `think_picture` VALUES ('23', '/Uploads/Picture/2013-09-13/5232ce984b16f.gif', '', '57ca1a2085d82f0574e3ef740b9a5ead', '1', '1379061400');
INSERT INTO `think_picture` VALUES ('24', '/Uploads/Picture/2013-09-13/5232ebba22438.jpg', '', '8969288f4245120e7c3870287cce0ff3', '1', '1379068857');
INSERT INTO `think_picture` VALUES ('25', '/Uploads/Picture/2013-09-13/5232ecdce951f.jpg', '', '9d377b10ce778c4938b3c7e2c63a229a', '1', '1379069148');
INSERT INTO `think_picture` VALUES ('26', '/Uploads/Picture/2013-09-13/5232ed78a586b.jpg', '', '076e3caed758a1c18c91a0e9cae3368f', '1', '1379069304');
INSERT INTO `think_picture` VALUES ('27', '/Uploads/Picture/2013-09-13/5232ee4c4a114.jpg', '', '0578abb03d826fc21b05465ee0bf3954', '1', '1379069516');
INSERT INTO `think_picture` VALUES ('28', '/Uploads/Picture/2013-09-13/5232eedfb32c7.jpg', '', 'ba45c8f60456a672e003a875e469d0eb', '1', '1379069663');
INSERT INTO `think_picture` VALUES ('29', '/Uploads/Picture/2013-09-13/5232f0e2036b7.jpg', '', 'bdf3bf1da3405725be763540d6601144', '1', '1379070177');
INSERT INTO `think_picture` VALUES ('30', '/Uploads/Picture/2013-09-13/5232f3195654a.jpg', '', '5a44c7ba5bbe4ec867233d67e4806848', '1', '1379070745');
INSERT INTO `think_picture` VALUES ('31', '/Uploads/Picture/2013-09-13/5232f338026a3.jpg', '', 'fafa5efeaf3cbe3b23b2748d13e629a1', '1', '1379070775');
INSERT INTO `think_picture` VALUES ('32', '/Uploads/Picture/2013-09-14/5233d1cd1dcf1.jpg', '', '911d4fe765a0f17d254ed51885e2518a', '1', '1379127756');

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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of think_ucenter_member
-- ----------------------------
INSERT INTO `think_ucenter_member` VALUES ('1', 'administrator', '7a58d2d679476c86911cfa65882ae430', 'zuojiazi@vip.qq.com', '', '1378981217', '2130706433', '1379155532', '2130706433', '1378981217', '1');
INSERT INTO `think_ucenter_member` VALUES ('9', '麦当苗儿', '88caaf09d9c65cafc1191859c17ad36c', 'zuojiazi.cn@gmail.com', '', '1369721426', '2130706433', '1371192515', '2130706433', '1369721426', '1');
INSERT INTO `think_ucenter_member` VALUES ('10', 'thinkphp', '525fd9a1ae3a25ec9b2a6650a18a4829', 'thinkphp@qq.com', '', '1374043813', '3232235922', '1379041194', '2130706433', '1374043813', '1');
INSERT INTO `think_ucenter_member` VALUES ('11', 'yangweijie', '7a58d2d679476c86911cfa65882ae430', '917647288@qq.com', '', '1378980320', '2130706433', '1379126003', '2130706433', '1378980320', '1');
INSERT INTO `think_ucenter_member` VALUES ('12', 'thinkphphj', '65d185d7fd782d23dfd06bcc1aa467c8', 'huajie@topthink.net', '', '1378963725', '2130706433', '1379122955', '2130706433', '1378963725', '1');
INSERT INTO `think_ucenter_member` VALUES ('13', 'zhuyajie', 'a2729f2c2d69f110e5eef7f36714c44c', 'zhuyajie@topthink.net', '', '1377484440', '2130706433', '1378440293', '2130706433', '1377484440', '1');

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

-- ----------------------------
-- Table structure for `think_url`
-- ----------------------------
DROP TABLE IF EXISTS `think_url`;
CREATE TABLE `think_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL COMMENT '状态（-1：删除，0：禁用，1：正常，2：未审核）',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_short` (`short`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='链接表';

-- ----------------------------
-- Records of think_url
-- ----------------------------
INSERT INTO `think_url` VALUES ('1', 'http://onethink.cn', '', '1', '1379139055');
