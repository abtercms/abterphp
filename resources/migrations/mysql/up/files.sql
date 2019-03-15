--
-- Table data for table `admin_resources`
--

INSERT INTO `admin_resources` (`identifier`) VALUES ('files'), ('filecategories'), ('filedownloads'), ('usergroups_filecategories');

--
-- Table structure and data for table `file_categories`
--

CREATE TABLE `file_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `identifier` varchar(160) NOT NULL,
  `is_public` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier` (`identifier`),
  KEY `file_categories_deleted_index` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `file_categories` (`name`, `identifier`, `is_public`) VALUES ('publikus','publikus',1),('privat','privat',0);

--
-- Table structure and data for table `files`
--

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_category_id` int(10) unsigned NOT NULL,
  `filesystem_name` varchar(100) NOT NULL,
  `public_name` varchar(255) NOT NULL DEFAULT '',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `file_category_id` (`file_category_id`),
  KEY `filesystem_name` (`filesystem_name`),
  KEY `files_deleted_index` (`deleted`),
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`file_category_id`) REFERENCES `file_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure and data for table `file_downloads`
--

CREATE TABLE `file_downloads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `downloaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `file_id` (`file_id`),
  KEY `user_id` (`user_id`),
  KEY `file_downloads_deleted_index` (`deleted`),
  CONSTRAINT `file_downloads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `file_downloads_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure and data for table `user_groups_file_categories`
--

CREATE TABLE `user_groups_file_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_group_id` int(10) unsigned NOT NULL DEFAULT 0,
  `file_category_id` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_group_id` (`user_group_id`),
  KEY `file_category_id` (`file_category_id`),
  CONSTRAINT `ugfc_ibfk_1` FOREIGN KEY (`file_category_id`) REFERENCES `file_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ugfc_ibfk_2` FOREIGN KEY (`user_group_id`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Provide admins access to all file categories
INSERT INTO `user_groups_file_categories` (`user_group_id`, `file_category_id`)
SELECT `user_groups`.`id` AS `user_group_id`, `file_categories`.`id` AS `file_category_id`
FROM `user_groups` INNER JOIN `file_categories`
WHERE `user_groups`.`identifier` = 'admin';

-- Provide admins access to all admin resources
INSERT IGNORE INTO `user_groups_admin_resources` (`user_group_id`, `admin_resource_id`)
SELECT user_groups.id AS user_group_id, admin_resources.id AS admin_resource_id
FROM user_groups INNER JOIN admin_resources
WHERE user_groups.identifier = 'admin';
