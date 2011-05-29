# ************************************************************
# Sequel Pro SQL dump
# Version 3311
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.1.44)
# Database: mobile_cartoon
# Generation Time: 2011-05-29 21:51:05 +0800
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table mc_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_log`;

CREATE TABLE `mc_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL,
  `who` varchar(64) DEFAULT NULL,
  `action` varchar(64) DEFAULT NULL,
  `event` varchar(512) DEFAULT NULL,
  `result` mediumint(8) unsigned DEFAULT NULL,
  `createtime` datetime DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
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
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `path` varchar(32) DEFAULT NULL COMMENT '路径',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `title_en` varchar(128) DEFAULT NULL COMMENT '英文标题',
  `title_jp` varchar(128) DEFAULT NULL COMMENT '日文标题',
  `full_titlle` varchar(256) DEFAULT NULL COMMENT '全路径名',
  `resource_type` tinyint(1) DEFAULT NULL COMMENT '资源类型',
  `summary` text COMMENT '简介',
  `cover` varchar(128) DEFAULT NULL COMMENT '封面图片',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='资源表';

LOCK TABLES `mc_resource` WRITE;
/*!40000 ALTER TABLE `mc_resource` DISABLE KEYS */;

INSERT INTO `mc_resource` (`id`, `pid`, `path`, `title`, `title_en`, `title_jp`, `full_titlle`, `resource_type`, `summary`, `cover`, `create_time`, `status`)
VALUES
	(1,0,'','xxx',NULL,NULL,NULL,NULL,'asd','0','2011-05-28 19:17:00',0),
	(2,0,'','yyy',NULL,NULL,NULL,NULL,'yyyyy','0','2011-05-28 19:30:36',0),
	(3,0,'','yyy',NULL,NULL,NULL,NULL,'yyyyy','0','2011-05-28 19:30:55',0),
	(8,1,'1','222',NULL,NULL,NULL,NULL,'22222','0','2011-05-28 22:03:34',0),
	(9,8,'1,8','9999',NULL,NULL,NULL,NULL,'22222','0','2011-05-28 22:03:51',0),
	(11,8,'1,8','999',NULL,NULL,NULL,NULL,'22222','0','0000-00-00 00:00:00',NULL),
	(13,0,'','123','321','111',NULL,NULL,'','/2011/05/upfile1306665734.14.png','2011-05-29 20:05:41',0),
	(14,0,'','123','321','111',NULL,NULL,'<p>\n	dfdfdfdfdfdf</p>\n','/2011/05/upfile1306665734.14.png','2011-05-29 20:17:49',0),
	(15,14,'14','1111','222','333',NULL,NULL,'<p>\n	123123132</p>\n','','2011-05-29 21:31:12',0);

/*!40000 ALTER TABLE `mc_resource` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_resource_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_resource_tag`;

CREATE TABLE `mc_resource_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned DEFAULT NULL,
  `tid` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源标签';



# Dump of table mc_settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_settings`;

CREATE TABLE `mc_settings` (
  `name` varchar(64) NOT NULL COMMENT '键',
  `value` varchar(1024) NOT NULL COMMENT '值',
  `type` tinyint(1) NOT NULL COMMENT '型类',
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `mc_settings` WRITE;
/*!40000 ALTER TABLE `mc_settings` DISABLE KEYS */;

INSERT INTO `mc_settings` (`name`, `value`, `type`)
VALUES
	('core.xx','32',1),
	('core.yy','62',1);

/*!40000 ALTER TABLE `mc_settings` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_tag`;

CREATE TABLE `mc_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL DEFAULT '',
  `create_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='标签表';

LOCK TABLES `mc_tag` WRITE;
/*!40000 ALTER TABLE `mc_tag` DISABLE KEYS */;

INSERT INTO `mc_tag` (`id`, `tag`, `create_time`, `status`)
VALUES
	(3,'123','2011-05-28 12:17:12',0);

/*!40000 ALTER TABLE `mc_tag` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_token`;

CREATE TABLE `mc_token` (
  `sn` char(16) NOT NULL COMMENT 'token编号',
  `uid` int(11) unsigned DEFAULT NULL COMMENT '用户id',
  `uname` varchar(64) DEFAULT NULL COMMENT '用户名',
  `nickname` varchar(64) DEFAULT NULL,
  `login_time` int(11) unsigned DEFAULT NULL COMMENT '登录时间',
  `sync_time` int(11) unsigned DEFAULT NULL COMMENT '同步时间',
  `login_ip` int(11) unsigned DEFAULT NULL COMMENT '登录ip',
  UNIQUE KEY `sn` (`sn`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='登录令牌';

LOCK TABLES `mc_token` WRITE;
/*!40000 ALTER TABLE `mc_token` DISABLE KEYS */;

INSERT INTO `mc_token` (`sn`, `uid`, `uname`, `nickname`, `login_time`, `sync_time`, `login_ip`)
VALUES
	('dd669bdfe',1,'admin','admin',1306632647,1306676777,2130706433);

/*!40000 ALTER TABLE `mc_token` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mc_uri
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_uri`;

CREATE TABLE `mc_uri` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vid` int(11) unsigned DEFAULT NULL COMMENT '所属卷id',
  `file_size` int(11) unsigned DEFAULT NULL,
  `res_type` tinyint(1) DEFAULT NULL,
  `mime_type` varchar(32) DEFAULT NULL,
  `download_count` int(11) unsigned DEFAULT NULL,
  `view_count` int(11) unsigned DEFAULT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

LOCK TABLES `mc_user` WRITE;
/*!40000 ALTER TABLE `mc_user` DISABLE KEYS */;

INSERT INTO `mc_user` (`id`, `username`, `nickname`, `passwd`, `status`, `avator`, `reg_ip`, `reg_time`, `last_login_ip`, `last_login_time`)
VALUES
	(1,'admin','admin','202cb962ac59075b964b07152d234b70',0,NULL,NULL,'2011-05-25 14:13:16',NULL,'2011-05-27 14:13:22');

/*!40000 ALTER TABLE `mc_user` ENABLE KEYS */;
UNLOCK TABLES;


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



# Dump of table mc_volume
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mc_volume`;

CREATE TABLE `mc_volume` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '资源id',
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `title_en` varchar(128) DEFAULT NULL,
  `title_jp` varchar(128) DEFAULT NULL,
  `summary` text COMMENT '简介',
  `cover` varchar(128) DEFAULT NULL COMMENT '封面图片',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

LOCK TABLES `mc_volume` WRITE;
/*!40000 ALTER TABLE `mc_volume` DISABLE KEYS */;

INSERT INTO `mc_volume` (`id`, `rid`, `title`, `title_en`, `title_jp`, `summary`, `cover`, `create_time`, `status`)
VALUES
	(1,9,'ww',NULL,NULL,'wwwww','0','2011-05-29 00:52:03',0),
	(2,9,'vvv',NULL,NULL,'','0','2011-05-29 01:15:26',0),
	(3,9,'ww',NULL,NULL,'','0','2011-05-29 01:35:39',0),
	(4,9,'',NULL,NULL,'','0','2011-05-29 01:36:02',0),
	(5,9,'',NULL,NULL,'','0','2011-05-29 01:47:44',0),
	(6,11,'vvvv',NULL,NULL,'','0','2011-05-29 09:35:39',0),
	(7,11,'666',NULL,NULL,'','upfile1306636476.92.png','2011-05-29 10:35:20',0),
	(8,11,'6667',NULL,NULL,'','upfile1306639384.41.png','2011-05-29 11:23:06',0),
	(9,11,'6667',NULL,NULL,'','/2011/05/upfile1306639495.55.png','2011-05-29 11:24:57',0);

/*!40000 ALTER TABLE `mc_volume` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
