-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2013 年 09 月 15 日 09:12
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `thinkcms`
--

-- --------------------------------------------------------

--
-- 表的结构 `think_config`
--

CREATE TABLE IF NOT EXISTS `think_config` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `think_config`
--

INSERT INTO `think_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES
(1, 'WEB_SITE_TITLE', 1, '网站标题', 0, '', '网站标题前台显示标题', 1378898976, 1379235274, 1, 'OneThink内容管理框架2', 0),
(2, 'WEB_SITE_DESCRIPTION', 2, '网站描述', 0, '', '网站搜索引擎描述', 1378898976, 1379235841, 1, 'OneThink内容管理框架', 1),
(3, 'WEB_SITE_KEYWORD', 2, '网站关键字', 0, '', '网站搜索引擎关键字', 1378898976, 1379235848, 1, 'ThinkPHP,OneThink,test', 3),
(4, 'WEB_SITE_CLOSE', 4, '关闭站点', 0, '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', 1378898976, 1379235296, 1, '1', 0),
(9, 'CONFIG_TYPE_LIST', 3, '配置类型列表', 3, '', '主要用于数据解析和页面表单的生成', 1378898976, 1379235348, 1, '0:数字;1:字符;2:文本;3:数组;4:枚举', 0),
(10, 'WEB_SITE_ICP', 1, '网站备案号', 0, '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', 1378900335, 1379235859, 1, '沪ICP备12007941号-2', 4),
(11, 'DOCUMENT_POSITION', 3, '文档推荐位', 1, '', '文档推荐位，推荐到多个位置KEY值相加即可', 1379053380, 1379235329, 1, '1:列表页推荐\r\n2:频道页推荐\r\n4:网站首页推荐', 0),
(12, 'DOCUMENT_DISPLAY', 3, '文档可见性', 1, '', '文章可见性仅影响前台显示，后台不收影响', 1379056370, 1379235322, 1, '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', 0),
(13, 'COLOR_STYLE', 4, '后台色系', 0, 'default_color:默认\r\nblue_color:紫罗兰', '后台颜色风格', 1379122533, 1379235904, 1, 'default_color', 5),
(20, 'CONFIG_GROUP_LIST', 3, '配置分组', 3, '', '配置分组', 1379228036, 1379235355, 1, '0:基本设置\r\n1:内容设置\r\n2:用户设置\r\n3:系统配置', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
