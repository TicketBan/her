/*
 Navicat Premium Data Transfer

 Source Server         : LOCAL
 Source Server Type    : MySQL
 Source Server Version : 50723
 Source Host           : 127.0.0.1:3306
 Source Schema         : telegraph

 Target Server Type    : MySQL
 Target Server Version : 50723
 File Encoding         : 65001

 Date: 30/07/2019 19:15:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for content
-- ----------------------------
DROP TABLE IF EXISTS `content`;
CREATE TABLE `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hash` varchar(42) DEFAULT NULL,
  `uid_cookie` text,
  `url` varchar(255) DEFAULT NULL,
  `image` text,
  `title` text,
  `text` text,
  `author` varchar(128) DEFAULT NULL,
  `publish` int(1) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of content
-- ----------------------------
BEGIN;
INSERT INTO `content` VALUES (30, '7b94e144', 'undefined', 'v-chem-otlichie-rossiyskogo-instagram-ot-аmerikаnskogo-i-kаk-nа-nee-zаrаbotаt-thu-07-2019', NULL, 'В чем отличие российского Instagram от американского и как на неё заработать', '<div>Мой первый проект — wellness-добавка Natural shilajit — <b>стал</b> популярен именно благодаря инфлюенсер-маркетингу. Я сделал ставку на рекламу через блогеров в Instagram и Youtube и не прогадал: сейчас мой бизнес приносит несколько десятков тысяч долларов в месяц, что для Калифорнии не бог весть какие деньги, но выше, чем доход среднестатистического американца. Хочу поделиться своим опытом работы с американскими блогерами и рассказать, чем они отличаются от российских.<br></div><div class=\"medium-insert-images medium-insert-images-wide\"><figure contenteditable=\"false\" class=\"\">\n    <img src=\"images/156346633140769.jpg\" alt=\"\" class=\"\">\n        \n</figure></div><p class=\"\">Мой первый проект — wellness-добавка Natural shilajit — стал популярен именно благодаря инфлюенсер-маркетингу. Я сделал ставку на рекламу через блогеров в Instagram и Youtube и не прогадал: сейчас мой бизнес приносит несколько десятков тысяч долларов в месяц, что для Калифорнии не бог весть какие деньги, но выше, чем доход среднестатистического американца. Хочу поделиться своим опытом работы с американскими блогерами и рассказать, чем они отличаются от российских.<br></p><p class=\"\">Мой первый проект — wellness-добавка Natural shilajit — стал популярен именно благодаря инфлюенсер-маркетингу. Я сделал ставку на рекламу через блогеров в Instagram и Youtube и не прогадал: сейчас мой бизнес приносит несколько десятков тысяч долларов в месяц, что для Калифорнии не бог весть какие деньги, но выше, чем доход среднестатистического американца. Хочу поделиться своим опытом работы с американскими блогерами и рассказать, чем они отличаются от российских.<br></p><div class=\"medium-insert-images\"><figure contenteditable=\"false\">\n    <img src=\"images/1563467540301.png\" alt=\"\">\n        \n</figure></div><p class=\"\">Мой первый проект — wellness-добавка Natural shilajit — стал популярен именно благодаря инфлюенсер-маркетингу. Я сделал ставку на рекламу через блогеров в Instagram и Youtube и не прогадал: сейчас мой бизнес приносит несколько десятков тысяч долларов в месяц, что для Калифорнии не бог весть какие деньги, но выше, чем доход среднестатистического американца. Хочу поделиться своим опытом работы с американскими блогерами и рассказать, чем они отличаются от российских.<br></p>', 'Robot', 1, '::1', 1563466348, 1563480510);
INSERT INTO `content` VALUES (40, 'a59e4798', '69460e095a7b8745d681033d13253cb4', 'dawd-tue-07-2019', NULL, 'dawd', '<div class=\"medium-insert-embeds\" contenteditable=\"false\">\n	<figure>\n		<div class=\"medium-insert-embed\">\n			<div class=\"video video-youtube\"><iframe src=\"//www.youtube.com/embed/psXpM6b9Rxc\" frameborder=\"0\" allowfullscreen=\"\" style=\"width: 690px; height: 386.4px;\"></iframe></div>\n		</div>\n	</figure>\n	<div class=\"medium-insert-embeds-overlay\"></div>\n</div>    <div class=\"medium-insert-embeds\" contenteditable=\"false\">\n	<figure>\n		<div class=\"medium-insert-embed\">\n			<div class=\"video video-youtube\"><iframe src=\"//www.youtube.com/embed/_TEqvoO6NDA\" frameborder=\"0\" allowfullscreen=\"\" style=\"width: 690px; height: 386.4px;\"></iframe></div>\n		</div>\n	</figure>\n	<div class=\"medium-insert-embeds-overlay\"></div>\n</div><div class=\"medium-insert-embeds\" contenteditable=\"false\">\n	<figure>\n		<div class=\"medium-insert-embed\">\n			<div class=\"video video-youtube\"><iframe src=\"//www.youtube.com/embed/r-vbh3t7WVI\" frameborder=\"0\" allowfullscreen=\"\" style=\"width: 690px; height: 386.4px;\"></iframe></div>\n		</div>\n	</figure>\n	<div class=\"medium-insert-embeds-overlay\"></div>\n</div><div class=\"medium-insert-embeds\" contenteditable=\"false\">\n	<figure>\n		<div class=\"medium-insert-embed\">\n			<div class=\"video video-youtube\"><iframe src=\"//www.youtube.com/embed/UXVdX5hle3Q\" frameborder=\"0\" allowfullscreen=\"\" style=\"width: 690px; height: 386.4px;\"></iframe></div>\n		</div>\n	</figure>\n	<div class=\"medium-insert-embeds-overlay\"></div>\n</div><p><br></p><p class=\"\" id=\"\"><br></p><p class=\"\" id=\"\"><br></p><p class=\"\" id=\"\"><br></p><p class=\"medium-insert-active\" id=\"\"><br></p>', 'dawd', 0, '::1', 1564501250, 1564501250);
COMMIT;

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of setting
-- ----------------------------
BEGIN;
INSERT INTO `setting` VALUES (1, 'post_limit', '3');
INSERT INTO `setting` VALUES (2, 'post_size', '5');
COMMIT;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `access` int(11) DEFAULT NULL,
  `created_at` int(255) DEFAULT NULL,
  `updated_at` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES (1, 'Administrator', '$2y$13$rN0lCSk6AmkUf8czQlKhaeQO3KLNUOWCSt0S40ZV.T8ee983tZ.p2', 1, 2019, 2019);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
