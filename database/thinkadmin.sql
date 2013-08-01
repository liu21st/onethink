-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 08 月 01 日 07:22
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `thinkadmin`
--

-- --------------------------------------------------------

--
-- 表的结构 `think_action`
--

CREATE TABLE IF NOT EXISTS `think_action` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(50) DEFAULT '' COMMENT '标题',
  `name` varchar(25) DEFAULT '' COMMENT '标识',
  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
  `express` varchar(255) DEFAULT NULL COMMENT '表达式',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_action_log`
--

CREATE TABLE IF NOT EXISTS `think_action_log` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '行为编号',
  `action_ip` varchar(25) DEFAULT NULL COMMENT '行为IP',
  `record_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '记录编号',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `member_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员编号',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_app`
--

CREATE TABLE IF NOT EXISTS `think_app` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `app_key` char(32) DEFAULT NULL,
  `alias_name` varchar(25) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `db_name` varchar(25) DEFAULT NULL COMMENT '数据库名',
  `table_prefix` varchar(25) DEFAULT NULL COMMENT '表前缀',
  `connection` varchar(255) DEFAULT NULL COMMENT '连接信息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `think_app`
--

INSERT INTO `think_app` (`id`, `name`, `title`, `status`, `create_time`, `app_key`, `alias_name`, `remark`, `author`, `update_time`, `db_name`, `table_prefix`, `connection`) VALUES
(1, 'admin', '后台应用', 1, 1373697187, '', '', '', '', 1373697187, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `think_attach`
--

CREATE TABLE IF NOT EXISTS `think_attach` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `size` varchar(20) NOT NULL,
  `extension` varchar(20) NOT NULL,
  `savepath` varchar(255) NOT NULL,
  `savename` varchar(255) NOT NULL,
  `module` varchar(100) NOT NULL,
  `record_id` int(11) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `create_time` int(11) unsigned DEFAULT NULL,
  `download_count` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `hash` varchar(32) NOT NULL,
  `pid` int(12) unsigned NOT NULL,
  `sort` int(8) unsigned NOT NULL,
  `version` smallint(3) unsigned NOT NULL,
  `is_dir` tinyint(1) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `verify` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_top` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `module` (`module`),
  KEY `recordId` (`record_id`),
  KEY `userId` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

--
-- 转存表中的数据 `think_attach`
--

INSERT INTO `think_attach` (`id`, `name`, `type`, `size`, `extension`, `savepath`, `savename`, `module`, `record_id`, `user_id`, `create_time`, `download_count`, `hash`, `pid`, `sort`, `version`, `is_dir`, `remark`, `update_time`, `verify`, `status`, `is_top`) VALUES
(71, 'My97DatePickerBeta.7z', 'application/x-7z-compressed', '21581', '7z', './Uploads/attach/', 'My97DatePickerBeta.7z', 'contract', 5, 1, 1342423584, 2, '045409c4ba82acb289a73fc7c9046229', 0, 0, 0, 0, '', 0, '0', 1, 0),
(70, '彩票网需求分析.doc', 'application/msword', '135680', 'doc', './Uploads/attach/', '彩票网需求分析.doc', 'contract', 5, 1, 1342423514, 0, 'f79a2eb2ae67341711419de889628742', 0, 0, 0, 0, '', 0, '0', 1, 0),
(72, 'img005.jpg', 'image/jpeg', '1756708', 'jpg', './Uploads/attachlib/', '20120805/501e31d654671.jpg', 'attachlib', 0, NULL, 1344156118, 0, 'c1933e3d3bcfdc1f75eced57873d66b6', 0, 0, 0, 0, '', 0, '', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_attachment`
--

CREATE TABLE IF NOT EXISTS `think_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `savename` varchar(50) NOT NULL COMMENT '保存名称',
  `savepath` varchar(50) NOT NULL COMMENT '保存目录',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `ext` char(4) NOT NULL COMMENT '附件后缀',
  `type` varchar(50) NOT NULL COMMENT 'MIME类型',
  `name` varchar(100) NOT NULL COMMENT '源文件名称',
  `md5` char(32) NOT NULL COMMENT 'MD5值',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status_ix` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='附件表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `think_attachment`
--

INSERT INTO `think_attachment` (`id`, `savename`, `savepath`, `size`, `ext`, `type`, `name`, `md5`, `download`, `create_time`, `update_time`, `status`) VALUES
(1, 'error.jpg', 'attachment/20120806/', 68328, 'jpg', 'image/jpeg', 'error.jpg', '', 0, 1344239628, 1344240439, 1);

-- --------------------------------------------------------

--
-- 表的结构 `think_attribute`
--

CREATE TABLE IF NOT EXISTS `think_attribute` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `title` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `module` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` mediumint(6) unsigned DEFAULT '0',
  `update_time` int(11) unsigned DEFAULT '0',
  `create_time` int(11) unsigned DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `extra` varchar(1024) NOT NULL,
  `validate` varchar(255) DEFAULT NULL,
  `length` varchar(25) DEFAULT NULL,
  `auto` varchar(255) DEFAULT NULL,
  `group` varchar(25) DEFAULT NULL,
  `num` tinyint(1) unsigned DEFAULT NULL,
  `is_show` tinyint(1) unsigned DEFAULT '1',
  `filter` varchar(100) DEFAULT NULL,
  `site` int(11) unsigned NOT NULL DEFAULT '0',
  `field` varchar(100) DEFAULT NULL,
  `model_id` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_common` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `think_attribute`
--

INSERT INTO `think_attribute` (`id`, `name`, `title`, `type`, `value`, `module`, `remark`, `sort`, `update_time`, `create_time`, `status`, `extra`, `validate`, `length`, `auto`, `group`, `num`, `is_show`, `filter`, `site`, `field`, `model_id`, `is_must`, `is_common`) VALUES
(1, 'name', '名称', 'string', '', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 1, '', 0, 'varchar(25)', 1, 1, 0),
(2, 'title', '描述', 'string', '', 0, '', 0, 0, 0, 1, '', '', 'large', '', '', NULL, 1, '', 0, 'varchar(50)', 1, 0, 0),
(3, 'url', '地址', 'string', '', 0, '', 0, 0, 0, 1, '', '', 'large', '', '', NULL, 1, '', 0, 'varchar(255)', 1, 0, 0),
(4, 'is_show', '是否显示', 'bool', '1', 0, '', 0, 0, 0, 1, '隐藏,显示', '', '', '', '', NULL, 1, '', 0, 'tinyint(1) unsigned', 1, 0, 0),
(5, 'pid', '父ID', 'num', '0', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 0, '', 0, 'mediumint(6) unsigned', 1, 0, 0),
(6, 'level', '层次', 'num', '0', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 0, '', 0, 'smallint(2) unsigned', 1, 0, 0),
(7, 'account', '账号', 'string', '', 0, '', 0, 0, 0, 1, '', '', '', 'strtolower,3,function', '', NULL, 1, '', 0, 'varchar(100)', 2, 1, 0),
(8, 'password', '密码', 'string', '', 0, '', 0, 0, 0, 1, '', '', '', 'md5,1,function', '', NULL, 2, '', 0, 'varchar(32)', 2, 0, 0),
(9, 'nickname', '昵称', 'string', '', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 1, '', 0, 'varchar(255)', 2, 0, 0),
(10, 'max_login', '最大登录次数', 'num', '0', 0, '0表示不限制次数登录', 0, 0, 0, 1, '', '', '', '', '', NULL, 1, '', 0, 'int(11) unsigned', 2, 0, 0),
(11, 'login_count', '登录次数', 'num', '0', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 0, '', 0, 'int(11) unsigned', 2, 0, 0),
(12, 'last_login_time', '上次登录时间', 'num', '0', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 0, '', 0, 'int(11) unsigned', 2, 0, 0),
(13, 'last_login_ip', '上次登录IP', 'string', '', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 0, '', 0, 'varchar(255)', 2, 0, 0),
(14, 'type', '用户类型', 'select', '0', 0, '', 0, 0, 0, 1, '0:普通,1:后台', '', '', '', '', NULL, 1, '', 0, 'tinyint(1)  unsigned  NOT NULL  DEFAULT 0 ', 2, 0, 0),
(15, 'type', '类型', 'select', '0', 0, '', 0, 0, 0, 1, '1:新增,2:更新,3:删除,4:审核,5:还原,6:禁用,7:读取,0:其他', '', '', '', '', NULL, 1, '', 0, 'tinyint(1) unsigned', 3, 0, 0),
(16, 'user_id', '用户ID', 'num', '0', 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'int(11) unsigned', 3, 0, 0),
(17, 'model', '模型', 'string', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'varchar(25)', 3, 0, 0),
(18, 'ip', 'IP', 'string', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'varchar(25)', 3, 0, 0),
(19, 'name', '标识', 'string', '', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 1, '', 0, 'varchar(25)', 4, 1, 0),
(20, 'title', '名称', 'string', '', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 1, '', 0, 'varchar(100)', 4, 1, 0),
(21, 'app_key', '应用KEY', 'string', '', 0, '', 0, 0, 0, 1, '', '', 'large', '', '', NULL, 1, '', 0, 'char(32)', 4, 0, 0),
(22, 'alias_name', '别名', 'string', '', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 1, '', 0, 'varchar(25)', 4, 0, 0),
(23, 'remark', '描述', 'textarea', '', 0, '', 0, 0, 0, 1, '', '', 'large', '', '', NULL, 1, '', 0, 'varchar(100)', 4, 0, 0),
(24, 'author', '作者', 'string', '', 0, '', 0, 0, 0, 1, '', '', '', '', '', NULL, 1, '', 0, 'varchar(100)', 4, 0, 0),
(25, 'db_name', '数据库名', 'string', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'varchar(25)', 4, 0, 0),
(26, 'table_prefix', '表前缀', 'string', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'varchar(25)', 4, 0, 0),
(27, 'connection', '连接信息', 'textarea', '', 0, '', 0, 0, 0, 1, '', '', 'large', '', '', NULL, 1, '', 0, 'varchar(255)', 4, 0, 0),
(28, 'action_id', '行为编号', 'num', '0', 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'int(11) unsigned', 5, 0, 0),
(29, 'action_ip', '行为IP', 'string', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'varchar(25)', 5, 0, 0),
(30, 'remark', '备注', 'string', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'varchar(255)', 5, 0, 0),
(31, 'member_id', '会员编号', 'num', '0', 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'int(11) unsigned', 5, 0, 0),
(32, 'user_id', 'user_id', 'num', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'int(11) unsigned', 6, 0, 0),
(33, 'in_time', 'in_time', 'string', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'varchar(25)', 6, 0, 0),
(34, 'login_ip', 'login_ip', 'string', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'varchar(50)', 6, 0, 0),
(35, 'type', 'type', 'num', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'tinyint(4) unsigned', 6, 0, 0),
(36, 'out_time', 'out_time', 'string', NULL, 0, NULL, 0, 0, 0, 1, '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 0, 'varchar(25)', 6, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_auth`
--

CREATE TABLE IF NOT EXISTS `think_auth` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `create_time` int(11) unsigned DEFAULT NULL,
  `update_time` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `account` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `max_login` int(11) unsigned NOT NULL DEFAULT '0',
  `login_count` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0',
  `last_login_ip` varchar(255) DEFAULT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户类型',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `think_auth`
--

INSERT INTO `think_auth` (`id`, `create_time`, `update_time`, `status`, `account`, `password`, `nickname`, `max_login`, `login_count`, `last_login_time`, `last_login_ip`, `type`) VALUES
(1, 1222907803, 1373685568, 1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '超级管理员', 0, 1125, 1375340291, '127.0.0.1', 1);

-- --------------------------------------------------------

--
-- 表的结构 `think_cate`
--

CREATE TABLE IF NOT EXISTS `think_cate` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `sort` tinyint(4) unsigned DEFAULT NULL,
  `pid` mediumint(6) unsigned DEFAULT NULL,
  `level` tinyint(3) unsigned DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `model` mediumint(5) unsigned NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `map_id` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `tmpl_home` varchar(100) DEFAULT NULL,
  `tmpl_list` varchar(100) DEFAULT NULL,
  `tmpl_detail` varchar(100) DEFAULT NULL,
  `url_home` varchar(100) DEFAULT NULL,
  `url_list` varchar(100) DEFAULT NULL,
  `url_detail` varchar(100) DEFAULT NULL,
  `build_html` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `root_id` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `require_audit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `tmpl_record` varchar(100) DEFAULT NULL,
  `map_list` varchar(255) DEFAULT NULL,
  `db_name` varchar(25) DEFAULT NULL,
  `table_prefix` varchar(50) DEFAULT NULL,
  `module_list` varchar(255) DEFAULT NULL,
  `type_list` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `level` (`level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='InnoDB free: 9216 kB' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_config`
--

CREATE TABLE IF NOT EXISTS `think_config` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `value` varchar(1024) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `extra` varchar(1024) DEFAULT NULL,
  `tag` smallint(3) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` mediumint(8) unsigned DEFAULT NULL,
  `app_id` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `is_show` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1024 ;

--
-- 转存表中的数据 `think_config`
--

INSERT INTO `think_config` (`id`, `title`, `value`, `create_time`, `remark`, `name`, `type`, `extra`, `tag`, `status`, `sort`, `app_id`, `is_show`) VALUES
(1, '网站名称', 'TOPThink', 1205899396, '', 'WEB_NAME', 0, '', 1, 0, 11, 0, 1),
(2, '网站标题', 'TOPThink -- JUST THINK IT', 1205917701, '', 'WEB_TITLE', 0, '', 1, 0, 12, 0, 1),
(4, '验证码长度', '2,3', 1217511424, '数字表示固定的长度，用 4,6 表示一定范围的长度', 'VERIFY_CODE_LENGTH', 0, '', 1, 0, 14, 0, 1),
(5, '附件上传后缀', 'png,gif,jpg,jpeg,7z,mp3,doc,rar,zip,txt,swf,pdf', 1217511611, '', 'UPLOAD_FILE_EXT', 3, '', 3, 0, 15, 0, 1),
(11, '文件上传的最大限制', '3145728', 1229499278, '使用字节定义', 'UPLOAD_MAX_SIZE', 1, '', 1, 0, 18, 0, 1),
(40, '内容关键字过滤', 'fuck|广州小姐', 1270807019, '', 'CONTENT_LIMIT_KEYWORDS', 3, '', 3, 0, NULL, 0, 1),
(16, '节点分组列表', '0:首页,1:内容,2:扩展,3:会员,4:工具,5:设置,6:系统', 0, '', 'NODE_GROUP_LIST', 3, '', 1, 0, 5, 0, 1),
(17, '用户类型列表', '1:达人,2:专家,3:编辑', 0, '', 'USER_TYPE_LIST', 3, '', 2, 0, 6, 0, 1),
(18, '配置类型', '1:系统配置,2:用户配置,3:内容配置,4:其他配置', 0, '', 'CONF_TYPE_LIST', 3, '', 1, 0, 7, 0, 1),
(22, '头像上传格式', 'jpg,gif,png,jpeg', 0, '', 'AVATAR_UPLOAD_EXTS', 0, '', 2, 0, 8, 0, 1),
(42, '模板文件定制目录', 'Customize/', 1271063098, '', 'CUSTOM_TMPL_PATH', 0, '', 3, 0, NULL, 0, 1),
(37, '频道页URL规则', '{name}/index', 1270279045, '', 'CHANNEL_URL_RULE', 0, '', 3, 0, 1, 0, 1),
(30, '文章推荐位置', '1:首页推荐,2:分类推荐,3:新品上架,5:最新促销,4:热销商品,7:不上推荐', 1269855554, '', 'ARTICLE_RECOMMEND_POS', 3, '', 3, 0, 6, 0, 1),
(1005, '首页静态规则', 'index', 1278124190, '', 'HOME_URL_RULE', 0, '', 3, 0, NULL, 0, 1),
(31, '栏目列表页URL规则', '{channel}/{name}/{id}_{page}', 1269957559, '', 'CATE_LIST_RULE', 0, '', 3, 0, 3, 0, 1),
(32, '内容页URL规则', '{cate}/{year}{month}/{id}_{page}', 1269957640, '', 'PAGE_URL_RULE', 0, '', 3, 0, 4, 0, 1),
(38, '每次最多发布文档数', '5000', 1270364544, '', 'MAX_BUILD_ROWS', 1, '', 3, 0, 5, 0, 1),
(39, '栏目首页URL规则', '{channel}/{name}/index', 1270368444, '', 'CATE_HOME_RULE', 0, '', 3, 0, 2, 0, 1),
(1006, '基本属性列表', 'map:映射,attach:附件,relation:关联,flow:流程', 1278496170, '', 'BASE_ATTR_LIST', 3, '', 1, 0, NULL, 0, 1),
(44, '公共栏目列表', '258', 1271740636, '', 'PUBLIC_CATE_LIST', 0, '', 3, 0, NULL, 0, 1),
(45, '缓存表引擎类型', 'MyISAM', 1271830096, '', 'CACHE_TABLE_ENGINE', 0, '', 1, 0, NULL, 0, 1),
(1000, '子记录URL规则', '{module}/{id}', 1274176935, '', 'RECORD_URL_RULE', 0, '', 3, 0, NULL, 0, 1),
(1004, '频道列表页URL规则', '{name}/{page}', 1274691954, '', 'CHANNEL_LIST_RULE', 0, '', 3, 0, NULL, 0, 1),
(1007, '模型默认图片字段', 'article:avatar,product:pic', 1279335354, '', 'MODEL_PIC_FIELDS', 3, '', 1, 0, NULL, 0, 1),
(1008, '加密钥匙', 'topthink', 1279336237, '', 'THINK_ENCRYPT_KEY', 0, '', 1, 0, NULL, 0, 1),
(1009, '默认文档模型', 'article', 1279437984, '', 'DEFAULT_MODEL', 0, '', 1, 0, NULL, 19, 1),
(1014, '文档模型基础属性列表', 'member_id,map_id,record_id,relation_id,cate_id,sort,url_link,name', 1281349391, '', 'MODEL_BASE_ATTR_LIST', 3, '', 1, 0, NULL, 1, 1),
(1012, '默认文本字段长度', '255', 1280053372, '', 'DEFAULT_TEXT_SIZE', 0, '', 1, 0, NULL, 0, 1),
(1016, '保留模型名称', 'article,auth,cate,access,attribute,config,model,role', 1281350882, '', 'LIMIT_MODEL_NAME', 3, '', 1, 0, NULL, 1, 1),
(1017, '默认列表显示', 'id:编号|8%,title|showTitle:标题:edit,create_time|toDate=''y-m-d H#i'':创建时间,update_time|toDate=''y-m-d H#i'':更新时间,status|getStatus:状态', 1281610185, '', 'DEFAULT_GRID_LIST', 3, '', 1, 0, NULL, 0, 1),
(1018, '默认列表操作定义', 'status|showStatus=$action[''id''],edit:编辑,del:删除:id', 1281626377, '', 'DEFAULT_ACTION_LIST', 3, '', 1, 0, NULL, 0, 1),
(1019, '允许的应用列表', 'home', 1334730953, '', 'APP_ALLOW_LIST', 3, '', 0, 0, NULL, 0, 1),
(1020, '备份文件目录', 'backup', 1336550752, '', 'BACKUP_FILE_PATH', 0, '', 0, 0, NULL, 0, 1),
(1021, 'FTP上传文件保存路径', '/thinkphp/db/Uploads', 1337590861, '', 'FTP_FILE_SAVE_PATH', 0, '', 0, 0, NULL, 0, 1),
(1022, '图片FTP上传保存路径', '/thinkphp/web/Uploads', 1337657940, '', 'FTP_PIC_SAVE_PATH', 0, '', 0, 0, NULL, 0, 1),
(1023, '时间戳字段列表', 'create_time,update_time,last_login_time', 1344427862, '', 'TIMESTAMP_FIELDS', 3, '', 0, 0, NULL, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `think_event`
--

CREATE TABLE IF NOT EXISTS `think_event` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` enum('insert','update','del','get') DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  `extra` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `sort` smallint(3) NOT NULL DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `model_id` mediumint(5) NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_flow`
--

CREATE TABLE IF NOT EXISTS `think_flow` (
  `id` mediumint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `model_id` mediumint(6) unsigned DEFAULT NULL,
  `sort` smallint(3) unsigned DEFAULT NULL,
  `attribute_list` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `role_list` varchar(255) DEFAULT NULL,
  `user_list` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='流程表' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `think_flow`
--

INSERT INTO `think_flow` (`id`, `name`, `title`, `status`, `model_id`, `sort`, `attribute_list`, `remark`, `role_list`, `user_list`) VALUES
(5, NULL, '第一步', 1, 4, NULL, '29,27,59', '', '1', ''),
(6, NULL, '测试流程', 1, 26, NULL, '141,140,142,149,144', '', '1', ''),
(7, NULL, '对方答复', 1, 26, NULL, '144,145,148', '', '1,2,3', '1,7'),
(8, NULL, '测试流程', 1, 31, NULL, 'check_id,title', '', '1', ''),
(9, NULL, '提交申请', 1, 35, NULL, 'type,reason,start_time,end_time', '', '1,2,3,4', NULL),
(10, NULL, '处理申请', 1, 35, NULL, 'apply_status', '', '1', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `think_log`
--

CREATE TABLE IF NOT EXISTS `think_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `record_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '记录ID',
  `model` varchar(25) DEFAULT NULL COMMENT '模型',
  `ip` varchar(25) DEFAULT NULL COMMENT 'IP',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- 转存表中的数据 `think_log`
--

INSERT INTO `think_log` (`id`, `type`, `user_id`, `record_id`, `model`, `ip`, `create_time`, `update_time`, `status`) VALUES
(1, 2, 1, 3, 'menu', '127.0.0.1', 1373699618, 0, 0),
(2, 2, 1, 4, 'menu', '127.0.0.1', 1373699618, 0, 0),
(3, 2, 1, 5, 'menu', '127.0.0.1', 1373699618, 0, 0),
(4, 2, 1, 7, 'menu', '127.0.0.1', 1373699618, 0, 0),
(5, 2, 1, 11, 'menu', '127.0.0.1', 1373699618, 0, 0),
(6, 2, 1, 6, 'menu', '127.0.0.1', 1373699618, 0, 0),
(7, 2, 1, 8, 'menu', '127.0.0.1', 1373699817, 0, 0),
(8, 2, 1, 2, 'menu', '127.0.0.1', 1373699817, 0, 0),
(9, 2, 1, 1, 'menu', '127.0.0.1', 1373699817, 0, 0),
(10, 2, 1, 8, 'menu', '127.0.0.1', 1373700111, 0, 0),
(11, 2, 1, 1, 'menu', '127.0.0.1', 1373700111, 0, 0),
(12, 2, 1, 2, 'menu', '127.0.0.1', 1373700111, 0, 0),
(13, 2, 1, 8, 'menu', '127.0.0.1', 1373700118, 0, 0),
(14, 2, 1, 2, 'menu', '127.0.0.1', 1373700118, 0, 0),
(15, 2, 1, 1, 'menu', '127.0.0.1', 1373700118, 0, 0),
(16, 2, 1, 8, 'menu', '127.0.0.1', 1373700182, 0, 0),
(17, 2, 1, 2, 'menu', '127.0.0.1', 1373700182, 0, 0),
(18, 2, 1, 1, 'menu', '127.0.0.1', 1373700182, 0, 0),
(19, 2, 1, 3, 'menu', '127.0.0.1', 1373700195, 0, 0),
(20, 2, 1, 4, 'menu', '127.0.0.1', 1373700195, 0, 0),
(21, 2, 1, 5, 'menu', '127.0.0.1', 1373700195, 0, 0),
(22, 2, 1, 7, 'menu', '127.0.0.1', 1373700195, 0, 0),
(23, 2, 1, 11, 'menu', '127.0.0.1', 1373700195, 0, 0),
(24, 2, 1, 6, 'menu', '127.0.0.1', 1373700195, 0, 0),
(25, 7, 1, 1, 'app', '127.0.0.1', 1373701972, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_login`
--

CREATE TABLE IF NOT EXISTS `think_login` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `in_time` varchar(25) DEFAULT NULL,
  `login_ip` varchar(50) DEFAULT NULL,
  `type` tinyint(4) unsigned DEFAULT NULL,
  `out_time` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=186 ;

--
-- 转存表中的数据 `think_login`
--

INSERT INTO `think_login` (`id`, `user_id`, `in_time`, `login_ip`, `type`, `out_time`) VALUES
(1, 1, '1337932865', '127.0.0.1', NULL, '1337932877'),
(2, 7, '1337932885', '127.0.0.1', NULL, '1337939408'),
(3, 1, '1337939414', '127.0.0.1', NULL, NULL),
(4, 1, '1338617646', '127.0.0.1', NULL, '1338618616'),
(5, 1, '1338618622', '127.0.0.1', NULL, '1338621614'),
(6, 1, '1338620775', '127.0.0.1', NULL, '1338649781'),
(7, 1, '1338621619', '127.0.0.1', NULL, '1338621622'),
(8, 7, '1338621630', '127.0.0.1', NULL, '1338621654'),
(9, 1, '1338621660', '127.0.0.1', NULL, '1338621808'),
(10, 1, '1338623472', '127.0.0.1', NULL, NULL),
(11, 1, '1338649812', '127.0.0.1', NULL, NULL),
(12, 1, '1338726989', '127.0.0.1', NULL, NULL),
(13, 1, '1338818803', '127.0.0.1', NULL, NULL),
(14, 1, '1338904159', '127.0.0.1', NULL, NULL),
(15, 1, '1338949981', '127.0.0.1', NULL, NULL),
(16, 1, '1339055610', '127.0.0.1', NULL, '1339129335'),
(17, 1, '1339238966', '127.0.0.1', NULL, '1339318296'),
(18, 1, '1339318736', '127.0.0.1', NULL, '1339321107'),
(19, 1, '1339493255', '127.0.0.1', NULL, NULL),
(20, 1, '1339686145', '127.0.0.1', NULL, '1339686191'),
(21, 1, '1339989583', '127.0.0.1', NULL, NULL),
(22, 1, '1340074475', '127.0.0.1', NULL, '1340075626'),
(23, 1, '1340103401', '127.0.0.1', NULL, '1340103525'),
(24, 1, '1340186423', '127.0.0.1', NULL, '1340186877'),
(25, 1, '1340186887', '127.0.0.1', NULL, '1340186975'),
(26, 1, '1340187013', '127.0.0.1', NULL, '1340187129'),
(27, 1, '1340248912', '127.0.0.1', NULL, '1340251154'),
(28, 1, '1340252269', '127.0.0.1', NULL, NULL),
(29, 1, '1340254705', '127.0.0.1', NULL, '1340254955'),
(30, 1, '1340420734', '127.0.0.1', NULL, '1340425573'),
(31, 1, '1340548422', '127.0.0.1', NULL, NULL),
(32, 1, '1340598453', '127.0.0.1', NULL, '1340601117'),
(33, 1, '1340601184', '127.0.0.1', NULL, '1340602084'),
(34, 1, '1340768116', '127.0.0.1', NULL, NULL),
(35, 1, '1340937894', '127.0.0.1', NULL, NULL),
(36, 1, '1341043369', '127.0.0.1', NULL, '1341046533'),
(37, 1, '1341045418', '127.0.0.1', NULL, '1341049603'),
(38, 1, '1341046541', '127.0.0.1', NULL, '1341047751'),
(39, 1, '1341047872', '127.0.0.1', NULL, '1341047909'),
(40, 1, '1341048044', '127.0.0.1', NULL, '1341048196'),
(41, 1, '1341048227', '127.0.0.1', NULL, '1341049025'),
(42, 1, '1341049069', '127.0.0.1', NULL, '1341049382'),
(43, 1, '1341049518', '127.0.0.1', NULL, '1341049620'),
(44, 1, '1341202454', '127.0.0.1', NULL, NULL),
(45, 1, '1341362078', '127.0.0.1', NULL, NULL),
(46, 1, '1341363638', '127.0.0.1', NULL, NULL),
(47, 1, '1341409610', '127.0.0.1', NULL, NULL),
(48, 1, '1341459094', '127.0.0.1', NULL, '1341465308'),
(49, 1, '1341465451', '127.0.0.1', NULL, '1341544207'),
(50, 1, '1341466044', '127.0.0.1', NULL, NULL),
(51, 1, '1341556097', '127.0.0.1', NULL, NULL),
(52, 1, '1341568369', '127.0.0.1', NULL, NULL),
(53, 1, '1341904067', '127.0.0.1', NULL, '1342000652'),
(54, 1, '1341997354', '127.0.0.1', NULL, NULL),
(55, 1, '1342001598', '127.0.0.1', NULL, NULL),
(56, 1, '1342014671', '127.0.0.1', NULL, NULL),
(57, 1, '1342062834', '127.0.0.1', NULL, '1342065777'),
(58, 1, '1342065783', '127.0.0.1', NULL, NULL),
(59, 1, '1342067767', '127.0.0.1', NULL, NULL),
(60, 1, '1342328752', '127.0.0.1', NULL, '1342340344'),
(61, 1, '1342340361', '127.0.0.1', NULL, NULL),
(62, 1, '1342419281', '127.0.0.1', NULL, NULL),
(63, 1, '1342453282', '127.0.0.1', NULL, NULL),
(64, 1, '1342511279', '127.0.0.1', NULL, NULL),
(65, 1, '1342530770', '127.0.0.1', NULL, '1342534398'),
(66, 1, '1342534405', '127.0.0.1', NULL, NULL),
(67, 1, '1342604310', '127.0.0.1', NULL, NULL),
(68, 1, '1342762443', '127.0.0.1', NULL, NULL),
(69, 1, '1342841948', '127.0.0.1', NULL, NULL),
(70, 1, '1342921603', '127.0.0.1', NULL, NULL),
(71, 1, '1342954176', '127.0.0.1', NULL, '1342967941'),
(72, 1, '1342967948', '127.0.0.1', NULL, NULL),
(73, 1, '1343010840', '127.0.0.1', NULL, '1343105215'),
(74, 1, '1343105226', '127.0.0.1', NULL, NULL),
(75, 1, '1343136733', '127.0.0.1', NULL, NULL),
(76, 1, '1343222571', '127.0.0.1', NULL, '1343286050'),
(77, 1, '1343286058', '127.0.0.1', NULL, NULL),
(78, 1, '1343294193', '127.0.0.1', NULL, '1343371283'),
(79, 1, '1343371841', '127.0.0.1', NULL, '1343371884'),
(80, 1, '1343372009', '127.0.0.1', NULL, '1343372245'),
(81, 1, '1343372298', '127.0.0.1', NULL, '1343372928'),
(82, 1, '1343372934', '127.0.0.1', NULL, '1343372937'),
(83, 1, '1343372946', '127.0.0.1', NULL, '1343373288'),
(84, 1, '1343379023', '127.0.0.1', NULL, '1343447751'),
(85, 1, '1343453452', '127.0.0.1', NULL, '1343458624'),
(86, 1, '1343458646', '127.0.0.1', NULL, '1343465747'),
(87, 1, '1343465787', '127.0.0.1', NULL, '1343465824'),
(88, 1, '1343465831', '127.0.0.1', NULL, '1343465971'),
(89, 1, '1343465980', '127.0.0.1', NULL, '1343466282'),
(90, 1, '1343466289', '127.0.0.1', NULL, '1343466343'),
(91, 1, '1343466470', '127.0.0.1', NULL, '1343467522'),
(92, 1, '1343467538', '127.0.0.1', NULL, '1343468377'),
(93, 1, '1343467547', '127.0.0.1', NULL, '1343468010'),
(94, 1, '1343467873', '127.0.0.1', NULL, '1343469368'),
(95, 1, '1343468017', '127.0.0.1', NULL, '1343469273'),
(96, 1, '1343468383', '127.0.0.1', NULL, '1343469363'),
(97, 1, '1343469484', '127.0.0.1', NULL, '1343469522'),
(98, 1, '1343469609', '127.0.0.1', NULL, '1343470208'),
(99, 1, '1343469643', '127.0.0.1', NULL, '1343470181'),
(100, 1, '1343469657', '127.0.0.1', NULL, '1343470681'),
(101, 1, '1343470240', '127.0.0.1', NULL, '1343470691'),
(102, 1, '1343470534', '127.0.0.1', NULL, '1343470767'),
(103, 1, '1343471098', '127.0.0.1', NULL, '1343476527'),
(104, 1, '1343471110', '127.0.0.1', NULL, NULL),
(105, 1, '1343471123', '127.0.0.1', NULL, NULL),
(106, 1, '1343482478', '127.0.0.1', NULL, NULL),
(107, 1, '1343485554', '127.0.0.1', NULL, '1343556018'),
(108, 1, '1343608259', '127.0.0.1', NULL, '1343608356'),
(109, 1, '1343794988', '127.0.0.1', NULL, NULL),
(110, 1, '1343912123', '127.0.0.1', NULL, NULL),
(111, 1, '1343914194', '127.0.0.1', NULL, NULL),
(112, 1, '1343916876', '127.0.0.1', NULL, NULL),
(113, 1, '1344005466', '127.0.0.1', NULL, '1344070857'),
(114, 1, '1344094376', '127.0.0.1', NULL, NULL),
(115, 1, '1344231631', '127.0.0.1', NULL, '1344235208'),
(116, 1, '1344238763', '127.0.0.1', NULL, '1344259674'),
(117, 1, '1344259684', '127.0.0.1', NULL, NULL),
(118, 1, '1344262296', '127.0.0.1', NULL, '1344264879'),
(119, 1, '1344264888', '127.0.0.1', NULL, '1344265194'),
(120, 1, '1344265201', '127.0.0.1', NULL, NULL),
(121, 1, '1344321978', '127.0.0.1', NULL, '1344327863'),
(122, 7, '1344327872', '127.0.0.1', NULL, NULL),
(123, 1, '1344348648', '127.0.0.1', NULL, NULL),
(124, 1, '1344408134', '127.0.0.1', NULL, NULL),
(125, 1, '1344408140', '127.0.0.1', NULL, NULL),
(126, 1, '1344427825', '127.0.0.1', NULL, '1344441371'),
(127, 1, '1344582534', '127.0.0.1', NULL, NULL),
(128, 1, '1344667549', '127.0.0.1', NULL, NULL),
(129, 1, '1344668943', '127.0.0.1', NULL, '1344669019'),
(130, 1, '1344669027', '127.0.0.1', NULL, NULL),
(131, 1, '1344763830', '127.0.0.1', NULL, NULL),
(132, 1, '1344918139', '127.0.0.1', NULL, NULL),
(133, 1, '1344951823', '127.0.0.1', NULL, '1344999479'),
(134, 1, '1345008589', '127.0.0.1', NULL, NULL),
(135, 1, '1345036051', '127.0.0.1', NULL, '1345036404'),
(136, 1, '1345036432', '127.0.0.1', NULL, '1345124826'),
(137, 1, '1345125806', '127.0.0.1', NULL, '1345369463'),
(138, 1, '1345560533', '127.0.0.1', NULL, '1345605302'),
(139, 1, '1345605307', '127.0.0.1', NULL, '1345627359'),
(140, 1, '1345639120', '127.0.0.1', NULL, NULL),
(141, 1, '1345716366', '127.0.0.1', NULL, NULL),
(142, 1, '1345899230', '127.0.0.1', NULL, NULL),
(143, 1, '1346076984', '127.0.0.1', NULL, NULL),
(144, 1, '1346222166', '127.0.0.1', NULL, NULL),
(145, 1, '1346225153', '127.0.0.1', NULL, NULL),
(146, 1, '1346564543', '127.0.0.1', NULL, NULL),
(147, 1, '1346838955', '127.0.0.1', NULL, NULL),
(148, 1, '1367931608', '127.0.0.1', NULL, '1367932198'),
(149, 1, '1367932203', '127.0.0.1', NULL, NULL),
(150, 1, '1367932457', '127.0.0.1', NULL, NULL),
(151, 1, '1367936893', '127.0.0.1', NULL, '1367937093'),
(152, 1, '1367937122', '127.0.0.1', NULL, NULL),
(153, 1, '1367937817', '127.0.0.1', NULL, NULL),
(154, 1, '1368506726', '127.0.0.1', NULL, '1368512466'),
(155, 1, '1368512476', '127.0.0.1', NULL, '1368514456'),
(156, 1, '1368514474', '127.0.0.1', NULL, '1368597183'),
(157, 1, '1368518798', '127.0.0.1', NULL, NULL),
(158, 1, '1368532799', '127.0.0.1', NULL, NULL),
(159, 1, '1368597191', '127.0.0.1', NULL, NULL),
(160, 1, '1368620119', '127.0.0.1', NULL, NULL),
(161, 1, '1368691666', '127.0.0.1', NULL, NULL),
(162, 1, '1369376888', '127.0.0.1', NULL, NULL),
(163, 1, '1369808005', '127.0.0.1', NULL, NULL),
(164, 1, '1369989896', '127.0.0.1', NULL, NULL),
(165, 1, '1372921611', '127.0.0.1', NULL, NULL),
(166, 1, '1373523264', '127.0.0.1', NULL, '1373526830'),
(167, 1, '1373526837', '127.0.0.1', NULL, NULL),
(168, 1, '1373585917', '0.0.0.0', NULL, NULL),
(169, 1, '1373585977', '0.0.0.0', NULL, NULL),
(170, 1, '1373585993', '0.0.0.0', NULL, '1373587717'),
(171, 1, '1373587784', '0.0.0.0', NULL, NULL),
(172, 1, '1373594667', '0.0.0.0', NULL, NULL),
(173, 1, '1373681386', '127.0.0.1', NULL, '1373681390'),
(174, 1, '1373681395', '127.0.0.1', NULL, NULL),
(175, 1, '1373699640', '127.0.0.1', NULL, '1373699794'),
(176, 1, '1373699799', '127.0.0.1', NULL, '1373699823'),
(177, 1, '1373699828', '127.0.0.1', NULL, NULL),
(178, 1, '1373874801', '127.0.0.1', NULL, '1373874811'),
(179, 1, '1373875294', '127.0.0.1', NULL, NULL),
(180, 1, '1374203750', '127.0.0.1', NULL, NULL),
(181, 1, '1374653245', '127.0.0.1', NULL, NULL),
(182, 1, '1374658314', '127.0.0.1', NULL, NULL),
(183, 1, '1374673854', '127.0.0.1', NULL, NULL),
(184, 1, '1375336553', '127.0.0.1', NULL, NULL),
(185, 1, '1375340291', '127.0.0.1', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `think_menu`
--

CREATE TABLE IF NOT EXISTS `think_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(25) DEFAULT NULL COMMENT '名称',
  `title` varchar(50) DEFAULT NULL COMMENT '描述',
  `url` varchar(255) DEFAULT NULL COMMENT '地址',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '状态',
  `pid` mediumint(6) unsigned DEFAULT '0' COMMENT '父ID',
  `level` smallint(2) unsigned DEFAULT '0' COMMENT '层次',
  `sort` mediumint(5) unsigned DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `think_menu`
--

INSERT INTO `think_menu` (`id`, `name`, `title`, `url`, `is_show`, `status`, `pid`, `level`, `sort`, `create_time`, `update_time`) VALUES
(1, 'system', '系统', '', 1, 1, 0, 1, 3, 1373590636, 1373590636),
(2, 'user', '用户', '', 1, 1, 0, 1, 2, 1373591094, 1373591094),
(3, 'model', '模型管理', 'Model', 1, 1, 1, 2, 1, 1373591196, 1373591216),
(4, 'cate', '分类管理', 'Cate', 1, 1, 1, 2, 2, 1373591234, 1373591234),
(5, 'menu', '导航管理', '', 1, 1, 1, 2, 3, 1373591249, 1373591249),
(6, 'db', '数据库管理', 'Db', 1, 1, 1, 2, 6, 1373591305, 1373591305),
(7, 'config', '配置管理', 'Config', 1, 1, 1, 2, 4, 1373594995, 1373594995),
(8, 'content', '内容', 'Cate/treeSelect', 1, 1, 0, 1, 1, 1373681497, 1373681497),
(9, 'role', '角色管理', 'Role', 1, 1, 2, 2, 0, 1373684944, 1373684944),
(10, 'auth', '账号管理', '', 1, 1, 2, 2, 0, 1373684972, 1373684972),
(11, 'app', '应用管理', '', 1, 1, 1, 2, 5, 1373697576, 1373697576);

-- --------------------------------------------------------

--
-- 表的结构 `think_model`
--

CREATE TABLE IF NOT EXISTS `think_model` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) DEFAULT NULL,
  `title` varchar(25) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `create_time` int(10) unsigned DEFAULT NULL,
  `update_time` int(10) unsigned DEFAULT NULL,
  `attribute_list` varchar(255) DEFAULT NULL,
  `module_list` varchar(255) DEFAULT NULL,
  `template_list` varchar(100) DEFAULT NULL,
  `template_add` varchar(100) DEFAULT NULL,
  `template_edit` varchar(100) DEFAULT NULL,
  `ext_list` varchar(100) DEFAULT NULL,
  `build_html` tinyint(1) unsigned DEFAULT '0',
  `map_list` varchar(255) DEFAULT NULL,
  `js_path` varchar(255) DEFAULT NULL,
  `relation_list` varchar(255) DEFAULT NULL,
  `app_id` mediumint(5) unsigned DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `list_grid` varchar(255) DEFAULT NULL,
  `action_list` varchar(255) DEFAULT NULL,
  `table_prefix` varchar(50) DEFAULT NULL,
  `data_length` smallint(2) unsigned DEFAULT NULL,
  `search_list` varchar(255) DEFAULT NULL,
  `search_key` varchar(25) DEFAULT NULL,
  `is_show` tinyint(1) unsigned DEFAULT NULL,
  `belongs_to` varchar(25) DEFAULT NULL,
  `include_model` varchar(25) DEFAULT NULL,
  `action_name` varchar(25) DEFAULT NULL,
  `db_name` varchar(25) NOT NULL,
  `table_engine` char(15) NOT NULL,
  `table_name` varchar(25) DEFAULT NULL,
  `support_cate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `connection` varchar(100) DEFAULT '',
  `support_sort` mediumint(5) unsigned DEFAULT '0',
  `model_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_as_sub` tinyint(1) unsigned DEFAULT '0',
  `upload_path` varchar(255) DEFAULT NULL,
  `has_one` varchar(25) DEFAULT NULL,
  `has_many` varchar(25) DEFAULT NULL,
  `foreign_key` varchar(15) DEFAULT NULL,
  `support_flow` tinyint(1) unsigned DEFAULT NULL,
  `support_attach` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `link_list` varchar(255) DEFAULT NULL,
  `support_link` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `support_level` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `template_search` varchar(100) DEFAULT NULL,
  `support_time` tinyint(1) unsigned DEFAULT '0',
  `support_status` tinyint(1) unsigned DEFAULT '0',
  `status_list` varchar(100) DEFAULT NULL,
  `support_tags` tinyint(1) unsigned DEFAULT '0',
  `auth_key` varchar(25) DEFAULT NULL,
  `sort_key` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文档模型' AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `think_model`
--

INSERT INTO `think_model` (`id`, `name`, `title`, `status`, `create_time`, `update_time`, `attribute_list`, `module_list`, `template_list`, `template_add`, `template_edit`, `ext_list`, `build_html`, `map_list`, `js_path`, `relation_list`, `app_id`, `type`, `list_grid`, `action_list`, `table_prefix`, `data_length`, `search_list`, `search_key`, `is_show`, `belongs_to`, `include_model`, `action_name`, `db_name`, `table_engine`, `table_name`, `support_cate`, `remark`, `connection`, `support_sort`, `model_type`, `allow_as_sub`, `upload_path`, `has_one`, `has_many`, `foreign_key`, `support_flow`, `support_attach`, `link_list`, `support_link`, `support_level`, `template_search`, `support_time`, `support_status`, `status_list`, `support_tags`, `auth_key`, `sort_key`) VALUES
(1, 'menu', '菜单', 1, 1373590538, 1373700151, '1,2,3,4,5,6', '', '', '', '', '', 0, '', '', NULL, 0, 0, 'id:编号,title:描述:child,name:名称,url:地址,status|getStatus:状态,create_time:创建时间', '', 'think_', 10, 'title,url,is_show', 'title', 1, '', '', '', 'thinkadmin', '', '', 0, '', '', 1, 0, 0, '', '', '', '', 0, 0, '', 0, 1, '', 1, 1, '', 0, NULL, NULL),
(2, 'auth', '账号', 1, 1373683121, 1373685556, '7,8,9,10,11,12,13,14', '', '', '', '', '', 0, '', '', NULL, 0, 0, 'id:编号,account:账号:edit,create_time:创建日期,last_login_time:上次登录,max_login:最大登录,status|getStatus:状态', '', 'think_', 10, 'nickname,last_login_ip,type', 'account', 1, '', '', '', 'thinkadmin', '', '', 0, '用于所有用户的登录认证', '', 0, 0, 0, '', '', '', '', 0, 0, '', 0, 0, '', 1, 1, '', 0, NULL, NULL),
(3, 'log', '日志', 1, 1373696316, 1374214855, '15,16,17,18', '', '', '', '', '', 0, '', '', NULL, 0, 0, 'id:编号,user_id|getUserName:用户,create_time|toDate=''y-m-d H#i'':时间,model|getModelName:模型,ip:IP,type:类型', 'delete:删除', 'think_', 10, 'type,user_id', 'model', 1, '', '', '', 'thinkadmin', '', '', 0, '', '', 0, 0, 1, '', '', '', '', 0, 0, '', 0, 0, '', 1, 1, '', 0, NULL, NULL),
(4, 'app', '应用', 1, 1373696435, 1373696454, '19,20,21,22,23,24,25,26,27', '', '', '', '', '', 0, '', '', NULL, 0, 0, '', '', 'think_', 10, '', '', 1, '', '', '', 'thinkadmin', '', '', 0, '', '', 0, 0, 0, '', '', '', '', 0, 0, '', 0, 0, '', 1, 1, '', 0, NULL, NULL),
(5, 'action_log', 'action_log', 1, 1374653265, NULL, '28,29,30,31', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, 0, NULL, NULL, 'think_', NULL, NULL, NULL, 1, NULL, NULL, NULL, '', '', NULL, 0, NULL, '', 0, 0, 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, NULL, 1, 1, NULL, 0, NULL, NULL),
(6, 'login', 'login', 1, 1374653302, 1374653390, '32,33,34,35,36', '', '', '', '', '', 0, '', '', NULL, 0, 0, 'user_id:用户,in_time:登录时间,login_ip:登录IP,out_time:登出时间', '', 'think_', 10, 'user_id,login_ip,type', '', 1, '', '', '', '', '', '', 0, '', '', 0, 0, 0, '', '', '', '', 0, 0, '', 0, 0, '', 0, 0, '', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `think_rbac`
--

CREATE TABLE IF NOT EXISTS `think_rbac` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `aro_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '请求对象',
  `aro_type` varchar(255) DEFAULT NULL COMMENT '请求类型',
  `aco_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '访问对象',
  `aco_level` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '访问对象层次',
  `aco_type` varchar(255) DEFAULT NULL COMMENT '访问对象类型',
  `access_level` varchar(255) DEFAULT NULL COMMENT '访问级别',
  `access_extra` varchar(255) DEFAULT NULL COMMENT '访问扩展',
  `record_id` varchar(50) DEFAULT NULL COMMENT '记录ID',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=79 ;

--
-- 转存表中的数据 `think_rbac`
--

INSERT INTO `think_rbac` (`id`, `aro_id`, `aro_type`, `aco_id`, `aco_level`, `aco_type`, `access_level`, `access_extra`, `record_id`, `create_time`, `update_time`, `status`) VALUES
(54, 1, '0', 6, 0, '0', '1', '', NULL, 0, 0, 0),
(35, 1, '0', 11, 0, '2', '1', '', NULL, 0, 0, 0),
(53, 1, '0', 5, 0, '0', '1', '', NULL, 0, 0, 0),
(52, 1, '0', 4, 0, '0', '1', '', NULL, 0, 0, 0),
(34, 1, '0', 10, 0, '2', '1', '', NULL, 0, 0, 0),
(33, 1, '0', 9, 0, '2', '1', '', NULL, 0, 0, 0),
(32, 1, '0', 8, 0, '2', '1', '', NULL, 0, 0, 0),
(31, 1, '0', 7, 0, '2', '1', '', NULL, 0, 0, 0),
(30, 1, '0', 6, 0, '2', '1', '', NULL, 0, 0, 0),
(29, 1, '0', 5, 0, '2', '1', '', NULL, 0, 0, 0),
(28, 1, '0', 4, 0, '2', '1', '', NULL, 0, 0, 0),
(27, 1, '0', 3, 0, '2', '1', '', NULL, 0, 0, 0),
(51, 1, '0', 2, 0, '0', '1', '', NULL, 0, 0, 0),
(68, 1, '0', 3, 0, '1', '1', '', NULL, 0, 0, 0),
(50, 1, '0', 1, 0, '0', '1', '', NULL, 0, 0, 0),
(55, 1, '0', 7, 0, '0', '1', '', NULL, 0, 0, 0),
(59, 1, '0', 2, 0, '4', '1', '', NULL, 0, 0, 0),
(58, 1, '0', 1, 0, '4', '1', '', NULL, 0, 0, 0),
(60, 1, '0', 3, 0, '4', '1', '', NULL, 0, 0, 0),
(61, 1, '0', 4, 0, '4', '1', '', NULL, 0, 0, 0),
(62, 1, '0', 11, 0, '5', '1', '', NULL, 0, 0, 0),
(63, 1, '0', 6, 0, '5', '1', '', NULL, 0, 0, 0),
(64, 1, '0', 8, 0, '5', '1', '', NULL, 0, 0, 0),
(65, 1, '0', 9, 0, '5', '1', '', NULL, 0, 0, 0),
(66, 1, '0', 8, 0, '0', '1', '', NULL, 0, 0, 0),
(67, 1, '0', 9, 0, '0', '1', '', NULL, 0, 0, 0),
(69, 3, '0', 1, 0, '0', '1', '', NULL, 0, 0, 0),
(70, 3, '0', 2, 0, '0', '1', '', NULL, 0, 0, 0),
(71, 3, '0', 4, 0, '0', '1', '', NULL, 0, 0, 0),
(72, 3, '0', 5, 0, '0', '1', '', NULL, 0, 0, 0),
(73, 3, '0', 6, 0, '0', '1', '', NULL, 0, 0, 0),
(74, 3, '0', 7, 0, '0', '1', '', NULL, 0, 0, 0),
(75, 3, '0', 16, 0, '2', '1', '', NULL, 0, 0, 0),
(76, 3, '0', 20, 0, '2', '1', '', NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_role`
--

CREATE TABLE IF NOT EXISTS `think_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `ename` varchar(5) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `ename` (`ename`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `think_role`
--

INSERT INTO `think_role` (`id`, `name`, `pid`, `status`, `remark`, `ename`, `create_time`, `update_time`) VALUES
(1, '一般管理员', 0, 1, '', NULL, 1208784792, 1215496350),
(2, '公共用户组', 0, 1, '', NULL, 1215496283, 1222872471),
(3, '网站编辑', 0, 1, '', NULL, 1229319925, 1249288389),
(4, '主编组', 0, 1, '', NULL, 1229778408, 1249287673);

-- --------------------------------------------------------

--
-- 表的结构 `think_role_user`
--

CREATE TABLE IF NOT EXISTS `think_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `think_task`
--

CREATE TABLE IF NOT EXISTS `think_task` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(255) DEFAULT NULL COMMENT '任务名称',
  `type` tinyint(2) unsigned DEFAULT '0' COMMENT '任务类型',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `priority` tinyint(1) unsigned DEFAULT '0' COMMENT '优先级',
  `task_status` varchar(255) DEFAULT NULL COMMENT '任务状态',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '指派人ID',
  `executor` varchar(255) DEFAULT NULL COMMENT '执行人ID',
  `remark` text COMMENT '任务描述',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `plan_end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '计划完成时间',
  `record_id` varchar(50) DEFAULT NULL COMMENT '所属记录ID',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `think_url`
--

CREATE TABLE IF NOT EXISTS `think_url` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `url` varchar(255) DEFAULT NULL COMMENT '访问规则',
  `title` varchar(255) DEFAULT NULL COMMENT '访问说明',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `think_url`
--

INSERT INTO `think_url` (`id`, `url`, `title`, `create_time`, `update_time`, `status`) VALUES
(1, 'index/index', '首页', 1336378191, 1336378191, 1),
(2, 'public/*', '公共模块', 1336378229, 1336378229, 1),
(3, '*', '所有地址', 1336379092, 1336379092, 1),
(4, 'model', '模型管理', 1336400183, 1336400183, 1),
(5, '*/edit', '编辑操作', 1336400999, 1336403388, 1),
(6, 'config', '配置管理', 1336402072, 1336402072, 1),
(7, '*/index', '列表操作', 1336402211, 1336402211, 1),
(8, 'cate', '分类', 0, 0, -1),
(9, 'test', '&lt;script&gt;alert(\\''a\\'')&lt;/script&gt;test', 1336802833, 0, -1);

-- --------------------------------------------------------

--
-- 表的结构 `think_workflow`
--

CREATE TABLE IF NOT EXISTS `think_workflow` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(100) DEFAULT '' COMMENT '描述',
  `from_id` mediumint(8) unsigned DEFAULT '0' COMMENT '来源用户ID',
  `op_id` mediumint(8) unsigned DEFAULT '0' COMMENT '操作用户ID',
  `record_id` varchar(50) DEFAULT '' COMMENT '记录ID',
  `flow_id` smallint(3) unsigned DEFAULT '0' COMMENT '流程ID',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注信息',
  `work_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '处理状态',
  `create_time` int(11) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `op_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '操作类型',
  `send_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `think_workflow`
--

INSERT INTO `think_workflow` (`id`, `title`, `from_id`, `op_id`, `record_id`, `flow_id`, `remark`, `work_status`, `create_time`, `update_time`, `status`, `op_type`, `send_id`) VALUES
(1, '', 0, 0, 'project_1', 7, NULL, 0, 0, 0, 0, 0, NULL),
(2, '', 1, 0, 'project_1', 7, NULL, 0, 1342863653, 0, 0, 0, NULL),
(3, '', 1, 0, 'project_1', 7, NULL, 0, 1342863728, 0, 0, 0, NULL),
(4, '', 1, 0, 'project_1', 7, NULL, 0, 1342863875, 0, 0, 0, '1,3'),
(5, '', 1, 0, 'project_1', 7, 'yuoyououo', 0, 1342864050, 0, 0, 1, '1,2,3'),
(6, '', 1, 0, '', 10, '发送备注', 0, 1343297205, 0, 0, 1, '1'),
(7, '', 1, 0, 'apply_2', 10, '12121212', 0, 1343401130, 0, 0, 1, '1'),
(8, '', 1, 0, 'apply_1', 10, '989898', 0, 1343401162, 0, 0, 1, '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
