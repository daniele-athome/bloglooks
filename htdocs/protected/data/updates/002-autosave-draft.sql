ALTER TABLE `posts` ADD `draft` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Draft content' AFTER `content`;
