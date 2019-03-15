SET FOREIGN_KEY_CHECKS = false;
DELETE FROM `admin_resources` WHERE `identifier` IN ('blocklayouts', 'pagelayouts', 'pages', 'blocks');
DROP TABLE IF EXISTS `block_layouts`;
DROP TABLE IF EXISTS `blocks`;
DROP TABLE IF EXISTS `page_layouts`;
DROP TABLE IF EXISTS `pages`;
SET FOREIGN_KEY_CHECKS = true;
