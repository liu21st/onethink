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