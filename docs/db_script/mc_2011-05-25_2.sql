# ************************************************************
# Sequel Pro SQL dump
# Version 3305
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.1.44)
# Database: mobile_cartoon
# Generation Time: 2011-05-25 23:02:36 +0800
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table mc_animate
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_animate`;

CREATE TABLE `mc_animate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned DEFAULT NULL COMMENT '资源id',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  `view_count` int(11) unsigned DEFAULT NULL COMMENT '浏览次数',
  `cover_sn` char(16) DEFAULT NULL COMMENT '封面sn',
  `summary` text COMMENT '简介',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table mc_comic
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_comic`;

CREATE TABLE `mc_comic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned DEFAULT NULL COMMENT '资源id',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  `view_count` int(11) unsigned DEFAULT NULL COMMENT '浏览次数',
  `cover_sn` char(16) DEFAULT NULL COMMENT '封面sn',
  `summary` text COMMENT '简介',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table mc_ranking
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_ranking`;

CREATE TABLE `mc_ranking` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='热门排行';



# Dump of table mc_resource
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_resource`;

CREATE TABLE `mc_resource` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) DEFAULT NULL COMMENT '标题',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `resource_type` tinyint(1) DEFAULT NULL COMMENT '资源类型',
  `summary` text COMMENT '简介',
  `is_package` tinyint(1) DEFAULT NULL COMMENT '是否为集合',
  `cover_sn` char(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源表';



# Dump of table mc_resource_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_resource_tag`;

CREATE TABLE `mc_resource_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned DEFAULT NULL,
  `tid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源标签';



# Dump of table mc_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_tag`;

CREATE TABLE `mc_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签表';



# Dump of table mc_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_token`;

CREATE TABLE `mc_token` (
  `sn` char(16) NOT NULL DEFAULT '' COMMENT 'token编号',
  `uid` int(11) unsigned DEFAULT NULL COMMENT '用户id',
  `usn` char(16) DEFAULT NULL COMMENT '用户编号',
  `uname` varchar(64) DEFAULT NULL COMMENT '用户名',
  `login_time` int(11) unsigned DEFAULT NULL COMMENT '登录时间',
  `sync_time` int(11) DEFAULT NULL COMMENT '同步时间',
  `login_ip` int(11) unsigned DEFAULT NULL COMMENT '登录ip'
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='登录令牌';



# Dump of table mc_uri
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_uri`;

CREATE TABLE `mc_uri` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned DEFAULT NULL,
  `file_size` int(11) unsigned DEFAULT NULL,
  `res_type` tinyint(1) DEFAULT NULL,
  `mime_type` varchar(32) DEFAULT NULL,
  `download_count` int(11) unsigned DEFAULT NULL,
  `cover_sn` char(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table mc_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_user`;

CREATE TABLE `mc_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) DEFAULT NULL,
  `nickname` varchar(64) DEFAULT NULL,
  `passwd` char(32) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `avator` varchar(64) DEFAULT NULL,
  `reg_ip` int(11) unsigned DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `last_login_ip` int(11) unsigned DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';



# Dump of table mc_user_account
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_user_account`;

CREATE TABLE `mc_user_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL,
  `mb` int(11) DEFAULT NULL,
  `sorce` int(11) DEFAULT NULL,
  `status` tinyint(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消费账户信息';



# Dump of table mc_user_feed
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_user_feed`;

CREATE TABLE `mc_user_feed` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL,
  `rid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推送至用户的资源';



# Dump of table mc_user_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_user_tag`;

CREATE TABLE `mc_user_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL,
  `tid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户标签';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
