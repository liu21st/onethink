/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.200
Source Server Version : 50520
Source Host           : 192.168.1.200:3306
Source Database       : thinkcms

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2013-07-10 17:57:17
*/

SET FOREIGN_KEY_CHECKS=0;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='附件表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- ----------------------------
-- Records of think_attachment
-- ----------------------------
INSERT INTO `think_attachment` VALUES ('1', '1', 'upyun_api_doc.pdf', '2', '1', '2', '0', '186603', '0', '0', '1373443268', '1373443268', '1');

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
INSERT INTO `think_category` VALUES ('11', 'doc', '文档', '2', '1020', '10', '', '', '', '', '', '', '2', '0', '1', '1', '', '1372390543', '1372644293', '1');
INSERT INTO `think_category` VALUES ('8', 'info', '资讯', '0', '30', '10', '', '', '', '', '', '', '1', '0', '0', '1', '', '1372390543', '1372407973', '1');
INSERT INTO `think_category` VALUES ('9', 'topic', '讨论', '0', '40', '10', '', '', '', '', '', '', '1', '0', '0', '1', '', '1372390543', '1372408840', '1');
INSERT INTO `think_category` VALUES ('10', 'framework', '框架', '2', '1010', '10', '', '', '', '', '', '', '2', '0', '1', '1', '', '1372390543', '1372644293', '1');
INSERT INTO `think_category` VALUES ('12', 'video', '视频', '2', '1030', '10', '', '', '', '', '', '', '2', '0', '1', '1', '', '1372390543', '1372411544', '1');
INSERT INTO `think_category` VALUES ('15', 'ask', '求助交流', '9', '4010', '10', '', '', '', '', 'Article/Article/detail_topic', '', '1', '0', '1', '1', '', '1372390543', '1372408974', '1');
INSERT INTO `think_category` VALUES ('13', 'news', '新闻动态', '8', '3010', '10', '', '', '', '', '', '', '1', '0', '1', '1', '', '1372390543', '1372408893', '1');
INSERT INTO `think_category` VALUES ('14', 'industry', '业界资讯', '8', '3020', '10', '', '', '', '', '', '', '1', '0', '1', '1', '', '1372390543', '1372408898', '1');
INSERT INTO `think_category` VALUES ('16', 'share', '技术分享', '9', '4020', '10', '', '', '', '', 'Article/Article/detail_topic', '', '1', '0', '1', '1', '', '1372390543', '1372408973', '1');
INSERT INTO `think_category` VALUES ('17', 'front', '前端开发', '9', '4030', '10', '', '', '', '', 'Article/Article/detail_topic', '', '1', '0', '1', '1', '', '1372390543', '1372408972', '1');
INSERT INTO `think_category` VALUES ('18', 'engine', '函数', '3', '2010', '10', '', '', '', '', '', '', '1,2', '0', '1', '1', '', '1372390543', '1372928187', '1');
INSERT INTO `think_category` VALUES ('19', 'function', '类库', '3', '2020', '10', '', '', '', '', '', '', '2', '0', '1', '1', '', '1372390543', '1372928237', '1');
INSERT INTO `think_category` VALUES ('20', 'library', '驱动', '3', '2030', '10', '', '', '', '', '', '', '2', '0', '1', '1', '', '1372390543', '1372928234', '1');
INSERT INTO `think_category` VALUES ('22', 'behvior', '行为', '3', '2040', '10', '', '', '', '', '', '', '2', '0', '1', '1', '', '1372412117', '1372928235', '1');

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
  `pos` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位(TODO:具体数值代表的位置待定）',
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
  `status` tinyint(4) DEFAULT '0' COMMENT '数据状态（-1-删除，0-禁用，1-正常，2-待审核）',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`) USING BTREE,
  KEY `idx_category_status` (`category_id`,`status`) USING BTREE,
  KEY `idx_status_type_pid` (`status`,`type`,`pid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='文档模型基础表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of think_document
-- ----------------------------
INSERT INTO `think_document` VALUES ('1', '1', '11111', 'Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持', '15', 'Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持Nginx下实现pathinfo及ThinkPHP的URL Rewrite模式支持', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1373443113', '1373443113', '1');
INSERT INTO `think_document` VALUES ('2', '1', 'aaaaabbb', 'ThinkPHP3.1.2核心版', '15', 'ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版ThinkPHP3.1.2核心版', '0', '1', '2', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1373443268', '1373443268', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='文档模型表\r\n@author   麦当苗儿\r\n@version  2013-06-19';

-- ----------------------------
-- Records of think_document_model
-- ----------------------------
INSERT INTO `think_document_model` VALUES ('1', 'Article', '文章', '0', '0', '1');
INSERT INTO `think_document_model` VALUES ('2', 'Download', '下载', '0', '0', '1');

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

-- ----------------------------
-- Table structure for `think_document_model_download`
-- ----------------------------
DROP TABLE IF EXISTS `think_document_model_download`;
CREATE TABLE `think_document_model_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型（0-html,1-ubb,2-markdown）',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL COMMENT '详情页显示模板',
  `system` varchar(255) NOT NULL COMMENT '应用平台',
  `language` varchar(30) NOT NULL COMMENT '软件语言',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` int(10) unsigned NOT NULL COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表\r\n@author   麦当苗儿\r\n@version  2013-05-24';

-- ----------------------------
-- Records of think_document_model_download
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文件表\r\n@author   麦当苗儿\r\n@version  2013-05-21';

-- ----------------------------
-- Records of think_file
-- ----------------------------
INSERT INTO `think_file` VALUES ('1', 'upyun_api_doc.pdf', '51dd1424d10d8.pdf', '2013-07-10/', 'pdf', 'application/octet-stream', '186603', '44385f08f92c3279c04d16d35bc3c95a', 'a65897adf52a3b7284761e288eed67cb8996366d', '0', '1373443108');

-- ----------------------------
-- Table structure for `think_member`
-- ----------------------------
DROP TABLE IF EXISTS `think_member`;
CREATE TABLE `think_member` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别（0-女，1-男）',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL,
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
INSERT INTO `think_member` VALUES ('9', '0', '0000-00-00', '', '11', '2130706433', '1369722401', '2130706433', '1371192515', '1');
INSERT INTO `think_member` VALUES ('1', '0', '0000-00-00', '', '2', '2130706433', '1371435498', '2130706433', '1372905711', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of think_ucenter_member
-- ----------------------------
INSERT INTO `think_ucenter_member` VALUES ('1', 'administrator', '88caaf09d9c65cafc1191859c17ad36c', 'zuojiazi@vip.qq.com', '', '0', '0', '1372905711', '2130706433', '0', '1');
INSERT INTO `think_ucenter_member` VALUES ('9', '麦当苗儿', '88caaf09d9c65cafc1191859c17ad36c', 'zuojiazi.cn@gmail.com', '', '1369721426', '2130706433', '1371192515', '2130706433', '1369721426', '1');

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
