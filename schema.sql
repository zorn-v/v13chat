SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `abilities`;
CREATE TABLE `abilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `descr` varchar(255) DEFAULT NULL,
  `price` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `abilities` (`id`, `name`, `title`, `descr`, `price`) VALUES
(1, 'ABIL_SILENT', 'Пробка', 'Самая обыкновенная молчанка. Цель лишается способности писать в общий чат на 15 минут, может общаться лишь приватно.', 100),
(2, 'ABIL_AVATAR', 'Другой образ', 'Позволяет установить аватару (20*20 px)', 500),
(3, 'ABIL_ADD_COLORS', 'Другая речь', 'Добавляет в список разрешенных дополнительные цвета, которыми можно писать в общий поток. Цвет не один ;)', 1500),
(4, 'ABIL_COLOR_NAME', 'Другое имя', 'Пример: <font color=''red''>В</font><font color=''green''>а</font><font color=''blue''>с</font><font color=''pink''>я</font>', 1000),
(5, 'ABIL_INVIS', 'Невидимость', 'Ваш ник исчезает из списка посетителей чата, в общий поток и даже в приваты вы пишите от ника НЕВИДИМКА. После активации работает 2 часа.', 300),
(6, 'ABIL_LEVEL_UP', 'Повышение', '+1 уровень', 1000),
(7, 'ABIL_ANGELITE', 'Ангелитет', 'Абсолютная неприкосновенность в чате, скрытые способности.', 3000),
(8, 'ABIL_WEDDING', 'Брачные узы', 'Добавляет иконку в списке пользователей', 10000);

DROP TABLE IF EXISTS `bans`;
CREATE TABLE `bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `reason` tinyint(4) NOT NULL,
  `until` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`reason`),
  KEY `author_id` (`author_id`),
  KEY `until` (`until`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;

INSERT INTO `config` (`name`, `value`, `description`) VALUES
('abils', '1', 'Включение/выключение способностей'),
('open', '0', 'Статус чата'),
('regtype', '2', 'Тип регистрации (2 - авто, 1 - ручная, 0 - отключена)'),
('inactive_timeout', '900', 'Таймаут выкидывания необщительного народа, секунды. 0 - отключено'),
('title', 'v13 chat', 'Название чата (в title страницы)'),
('topic', 'Добро пожаловать в наш чат', 'Топик'),
('topic_active', '1', 'Топик активен?');

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `text` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `user_to` (`recipient_id`)
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
  `ip` varchar(16) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`sess_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `smiles`;
CREATE TABLE `smiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `text` (`text`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `smiles` (`id`, `text`, `img`) VALUES
(1, ':)', 'ab.gif'),
(2, '*WALL*', 'bu.gif'),
(3, 'O:-)', 'aa.gif'),
(4, ':(', 'ac.gif'),
(5, '*WRITE*', 'bv.gif'),
(6, ';)', 'ad.gif'),
(7, '*SCRATCH*', 'bw.gif'),
(8, ':-P', 'ae.gif'),
(9, '8-)', 'af.gif'),
(10, ':-D', 'ag.gif'),
(11, ':-[', 'ah.gif'),
(12, '=-O', 'ai.gif'),
(13, ':-*', 'aj.gif'),
(14, '*STOP*', 'av.gif'),
(15, '*KISSED*', 'as.gif'),
(16, '*CRY*', 'ak.gif'),
(17, ':-X', 'al.gif'),
(18, '>:o', 'am.gif'),
(19, ':-|', 'an.gif'),
(20, ':-/', 'ao.gif'),
(21, '*JOKINGLY*', 'ap.gif'),
(22, ']:-]', 'aq.gif'),
(23, '[:-}', 'ar.gif'),
(24, ':-!', 'at.gif'),
(38, '*TIRED*', 'au.gif'),
(40, '*KISSING*', 'aw.gif'),
(41, '@}-}--', 'ax.gif'),
(42, '*THUMBS UP*', 'ay.gif'),
(43, '*DRINK*', 'az.gif'),
(44, '*IN LOVE*', 'ba.gif'),
(45, '@=', 'bb.gif'),
(46, '*HELP*', 'bc.gif'),
(47, '\\m/', 'bd.gif'),
(48, '%)', 'be.gif'),
(49, '*OK*', 'bf.gif'),
(50, '*WASSUP*', 'bg.gif'),
(51, '*SORRY*', 'bh.gif'),
(52, '*BRAVO*', 'bi.gif'),
(53, '*ROFL*', 'bj.gif'),
(54, '*PARDON*', 'bk.gif'),
(55, '*NO*', 'bl.gif'),
(56, '*CRAZY*', 'bm.gif'),
(57, '*DONT_KNOW*', 'bn.gif'),
(58, '*DANCE*', 'bo.gif'),
(59, '*YAHOO*', 'bp.gif'),
(60, '*HI*', 'bq.gif'),
(61, '*BYE*', 'br.gif'),
(62, '*YES*', 'bs.gif'),
(63, ';D', 'bt.gif');

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
  `points` int(6) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_abilities`;
CREATE TABLE `user_abilities` (
  `user_id` int(11) NOT NULL,
  `ability_id` int(11) NOT NULL,
  `data` text NOT NULL,
  `until` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`,`ability_id`),
  KEY `ability_id` (`ability_id`),
  KEY `until` (`until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `bans`
  ADD CONSTRAINT `bans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bans_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

ALTER TABLE `user_abilities`
  ADD CONSTRAINT `user_abilities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_abilities_ibfk_2` FOREIGN KEY (`ability_id`) REFERENCES `abilities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
