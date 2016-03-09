SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `abilities`;
CREATE TABLE `abilities` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `socr` text CHARACTER SET cp1251 NOT NULL,
  `descr` text CHARACTER SET cp1251,
  `price` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `bans`;
CREATE TABLE `bans` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET cp1251 NOT NULL,
  `author` text CHARACTER SET cp1251 NOT NULL,
  `reason` text CHARACTER SET cp1251 NOT NULL,
  `time` text CHARACTER SET cp1251 NOT NULL,
  `date` text CHARACTER SET cp1251 NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;
INSERT INTO `config` (`key`, `value`, `description`) VALUES
('abils', '1', 'Включение/выключение способностей'),
('open', '0', 'Статус чата'),
('regtype', '2', 'Тип регистрации (2 - авто, 1 - ручная, 0 - отключена)'),
('timeout', '900', 'Таймаут выкидывания необщительного народа, секунды'),
('topic', 'Добро пожаловать в наш чат', 'Топик'),
('topic_act', '1', 'Топик активен?');

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET cp1251 NOT NULL,
  `date` text CHARACTER SET cp1251 NOT NULL,
  `act` text CHARACTER SET cp1251 NOT NULL,
  `admin` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET cp1251 NOT NULL,
  `to` text CHARACTER SET cp1251 NOT NULL,
  `message` text CHARACTER SET cp1251 NOT NULL,
  `date` int(20) NOT NULL DEFAULT '0',
  `ad1` text CHARACTER SET cp1251 NOT NULL,
  `ad2` text CHARACTER SET cp1251 NOT NULL,
  `ad3` text CHARACTER SET cp1251 NOT NULL,
  `ad4` text CHARACTER SET cp1251 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `inherit_roles` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
INSERT INTO `roles` (`id`, `title`, `description`, `inherit_roles`) VALUES
(1, 'ROLE_SILENT_USER', 'Зарегеный юзер', NULL),
(2, 'ROLE_USER', 'Зарегеный, может писать всем', '["ROLE_SILENT_USER"]'),
(3, 'ROLE_MODERATOR', 'Модератор', '["ROLE_USER"]'),
(4, 'ROLE_REGISTRATOR', 'Регистратор', '["ROLE_MODERATOR"]'),
(5, 'ROLE_ADMIN', 'Администратор', '["ROLE_REGISTRATOR"]'),
(6, 'ROLE_SUPER_ADMIN', 'Суперадминистратор', '["ROLE_ADMIN"]');

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `sess_id` varbinary(128) NOT NULL,
  `sess_data` blob NOT NULL,
  `sess_lifetime` mediumint(9) NOT NULL,
  `sess_time` int(10) unsigned NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`sess_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `smiles`;
CREATE TABLE `smiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text CHARACTER SET cp1251 NOT NULL,
  `link` text CHARACTER SET cp1251 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role_id` int(2) NOT NULL,
  `gender` int(1) NOT NULL DEFAULT '0',
  `icq` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `realname` int(30) DEFAULT NULL,
  `birthday` varchar(10) DEFAULT NULL,
  `info` text,
  `site` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `dateofreg` int(20) NOT NULL DEFAULT '0',
  `points` int(6) NOT NULL DEFAULT '0',
  `abil2` text NOT NULL,
  `abil3` text NOT NULL,
  `abil4` text NOT NULL,
  `abil5` text NOT NULL,
  `abil6` text NOT NULL,
  `abil7` text NOT NULL,
  `abil8` text NOT NULL,
  `abil9` text NOT NULL,
  `abil10` text NOT NULL,
  `abil11` text NOT NULL,
  `abil12` text NOT NULL,
  `abil13` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
