CREATE TABLE IF NOT EXISTS `onethink_timeline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `startDate` varchar(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `endDate` varchar(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '媒体图片',
  `author` varchar(40) NOT NULL DEFAULT 'Jay' COMMENT '媒体作者',
  `media_title` char(40) NOT NULL DEFAULT '' COMMENT '媒体标题',
  `text` text COMMENT '事件内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
