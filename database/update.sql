/**
分类表的name字段长度限制改为30
2013-9-25
*/
ALTER TABLE `onethink_category`
MODIFY COLUMN `name`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标识' AFTER `id`;
/**
基础文档表的文档类型字段注释修改
2013-9-26
*/
ALTER TABLE `onethink_document`
MODIFY COLUMN `type`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '内容类型（1-目录，2-主题，3-段落）' AFTER `model_id`;
/**
分类表增加是否需要审核的字段
2013-9-27
*/
ALTER TABLE `think_category`
ADD COLUMN `check`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发布的文章是否需要审核（0：不需要，1：需要）' AFTER `reply`;
/**
分类表删除回复所允许的文档类型字段
2013-9-27
*/
ALTER TABLE `onethink_category`
DROP COLUMN `reply_type`;
/**
基础文档表增加字段“根节点”
2013-10-16
*/
ALTER TABLE `onethink_document`
ADD COLUMN `root`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '根节点' AFTER `description`;