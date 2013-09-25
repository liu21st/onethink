/**
分类表的name字段长度限制改为30
2013-9-25
*/
ALTER TABLE `onethink_category`
MODIFY COLUMN `name`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '标识' AFTER `id`;