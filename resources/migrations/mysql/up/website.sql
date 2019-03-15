--
-- Table data for table `admin_resources`
--

INSERT INTO `admin_resources` (`identifier`) VALUES ('blocklayouts'),('pagelayouts'),('pages'),('blocks');

--
-- Table structure and data for table `block_layouts`
--

CREATE TABLE `block_layouts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(160) NOT NULL,
  `body` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_layouts_identifier_uindex` (`identifier`),
  KEY `block_layouts_deleted_index` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `block_layouts` (`identifier`, `body`) VALUES ('index-text-section','                <div><hr class=\"section-heading-spacer\"></div>\r\n                <div class=\"clearfix\"></div>\r\n                <h2 class=\"section-heading\">{{var/title}}</h2>\r\n                <div class=\"lead\">{{var/body}}</div>'),('empty','{{var/body}}');

--
-- Table structure and data for table `blocks`
--

CREATE TABLE `blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(160) NOT NULL,
  `title` varchar(120) NOT NULL,
  `body` mediumtext NOT NULL,
  `layout_id` int(10) unsigned DEFAULT NULL,
  `layout` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `blocks_deleted_index` (`deleted`),
  KEY `block_layouts_id_fk` (`layout_id`),
  CONSTRAINT `blocks_layouts_id_fk` FOREIGN KEY (`layout_id`) REFERENCES `block_layouts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure and data for table `page_layouts`
--

CREATE TABLE `page_layouts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(160) NOT NULL,
  `body` mediumtext NOT NULL,
  `header` mediumtext NOT NULL DEFAULT  '',
  `footer` mediumtext NOT NULL DEFAULT '',
  `css_files` mediumtext NOT NULL DEFAULT  '',
  `js_files` mediumtext NOT NULL DEFAULT  '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_layouts_identifier_uindex` (`identifier`),
  KEY `page_layouts_deleted_index` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `page_layouts` (`identifier`, `body`, `header`, `footer`, `css_files`, `js_files`) VALUES
  ('empty','{{var/body}}','','','','');

--
-- Table structure and data for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(160) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meta_description` mediumtext NOT NULL,
  `meta_robots` varchar(100) NOT NULL,
  `meta_author` varchar(160) NOT NULL,
  `meta_copyright` varchar(160) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `meta_og_title` varchar(255) NOT NULL,
  `meta_og_image` varchar(255) NOT NULL,
  `meta_og_description` mediumtext NOT NULL,
  `body` mediumtext NOT NULL,
  `layout_id` int(10) unsigned DEFAULT NULL,
  `layout` mediumtext NOT NULL,
  `header` mediumtext NOT NULL DEFAULT  '',
  `footer` mediumtext NOT NULL DEFAULT '',
  `css_files` mediumtext NOT NULL DEFAULT '',
  `js_files` mediumtext NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `pages_deleted_index` (`deleted`),
  KEY `page_layouts_id_fk` (`layout_id`),
  CONSTRAINT `pages_layouts_id_fk` FOREIGN KEY (`layout_id`) REFERENCES `page_layouts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `pages` (identifier, title, meta_description, meta_robots, meta_author, meta_copyright, meta_keywords, meta_og_title, meta_og_image, meta_og_description, body, layout_id, layout, header, footer, css_files, js_files) VALUES
  ('index','New AbterCMS installation','AbterCMS is a security first, simple and flexible open source content management system for both educational and commercial usecases.','','','','cms, open source','','','','Hello, World!',NULL,'<div class="container">{{var/body}}</div>','','','', '');

-- Insert all resources for admin
INSERT IGNORE INTO `user_groups_admin_resources` (`user_group_id`, `admin_resource_id`)
SELECT user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups INNER JOIN admin_resources
WHERE user_groups.identifier = 'admin';
