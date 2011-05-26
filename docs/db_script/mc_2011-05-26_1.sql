/*
MySQL Data Transfer
Source Host: localhost
Source Database: mobile_cartoon
Target Host: localhost
Target Database: mobile_cartoon
Date: 2011-5-26 ���� 03:51:25
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for mc_animate
-- ----------------------------
DROP TABLE IF EXISTS `mc_animate`;
CREATE TABLE `mc_animate` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `rid` int(11) unsigned default NULL COMMENT '资源id',
  `status` tinyint(1) default NULL COMMENT '状态',
  `view_count` int(11) unsigned default NULL COMMENT '浏览次数',
  `cover_sn` char(16) default NULL COMMENT '封面sn',
  `summary` text COMMENT '简介',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mc_comic
-- ----------------------------
DROP TABLE IF EXISTS `mc_comic`;
CREATE TABLE `mc_comic` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `rid` int(11) unsigned default NULL COMMENT '资源id',
  `status` tinyint(1) default NULL COMMENT '状态',
  `view_count` int(11) unsigned default NULL COMMENT '浏览次数',
  `cover_sn` char(16) default NULL COMMENT '封面sn',
  `summary` text COMMENT '简介',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mc_log
-- ----------------------------
DROP TABLE IF EXISTS `mc_log`;
CREATE TABLE `mc_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uid` int(11) unsigned default NULL,
  `who` varchar(64) default NULL,
  `action` varchar(64) default NULL,
  `event` varchar(512) default NULL,
  `result` mediumint(8) unsigned default NULL,
  `createtime` datetime default NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mc_ranking
-- ----------------------------
DROP TABLE IF EXISTS `mc_ranking`;
CREATE TABLE `mc_ranking` (
  `id` int(11) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='热门排行';

-- ----------------------------
-- Table structure for mc_resource
-- ----------------------------
DROP TABLE IF EXISTS `mc_resource`;
CREATE TABLE `mc_resource` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(128) default NULL COMMENT '标题',
  `create_time` datetime default NULL COMMENT '创建时间',
  `resource_type` tinyint(1) default NULL COMMENT '资源类型',
  `summary` text COMMENT '简介',
  `is_package` tinyint(1) default NULL COMMENT '是否为集合',
  `cover_sn` char(16) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源表';

-- ----------------------------
-- Table structure for mc_resource_tag
-- ----------------------------
DROP TABLE IF EXISTS `mc_resource_tag`;
CREATE TABLE `mc_resource_tag` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `rid` int(11) unsigned default NULL,
  `tid` int(11) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资源标签';

-- ----------------------------
-- Table structure for mc_settings
-- ----------------------------
DROP TABLE IF EXISTS `mc_settings`;
CREATE TABLE `mc_settings` (
  `name` varchar(64) NOT NULL COMMENT '键',
  `value` varchar(1024) NOT NULL COMMENT '值',
  `type` tinyint(1) NOT NULL COMMENT '型类',
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mc_tag
-- ----------------------------
DROP TABLE IF EXISTS `mc_tag`;
CREATE TABLE `mc_tag` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `tag` varchar(64) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签表';

-- ----------------------------
-- Table structure for mc_token
-- ----------------------------
DROP TABLE IF EXISTS `mc_token`;
CREATE TABLE `mc_token` (
  `sn` char(16) NOT NULL default '' COMMENT 'token编号',
  `uid` int(11) unsigned default NULL COMMENT '用户id',
  `usn` char(16) default NULL COMMENT '用户编号',
  `uname` varchar(64) default NULL COMMENT '用户名',
  `login_time` int(11) unsigned default NULL COMMENT '登录时间',
  `sync_time` int(11) default NULL COMMENT '同步时间',
  `login_ip` int(11) unsigned default NULL COMMENT '登录ip'
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='登录令牌';

-- ----------------------------
-- Table structure for mc_uri
-- ----------------------------
DROP TABLE IF EXISTS `mc_uri`;
CREATE TABLE `mc_uri` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `rid` int(11) unsigned default NULL,
  `file_size` int(11) unsigned default NULL,
  `res_type` tinyint(1) default NULL,
  `mime_type` varchar(32) default NULL,
  `download_count` int(11) unsigned default NULL,
  `cover_sn` char(16) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mc_user
-- ----------------------------
DROP TABLE IF EXISTS `mc_user`;
CREATE TABLE `mc_user` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(64) default NULL,
  `nickname` varchar(64) default NULL,
  `passwd` char(32) default NULL,
  `status` tinyint(1) default NULL,
  `avator` varchar(64) default NULL,
  `reg_ip` int(11) unsigned default NULL,
  `reg_time` datetime default NULL,
  `last_login_ip` int(11) unsigned default NULL,
  `last_login_time` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Table structure for mc_user_account
-- ----------------------------
DROP TABLE IF EXISTS `mc_user_account`;
CREATE TABLE `mc_user_account` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uid` int(11) unsigned default NULL,
  `mb` int(11) default NULL,
  `sorce` int(11) default NULL,
  `status` tinyint(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消费账户信息';

-- ----------------------------
-- Table structure for mc_user_feed
-- ----------------------------
DROP TABLE IF EXISTS `mc_user_feed`;
CREATE TABLE `mc_user_feed` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uid` int(11) unsigned default NULL,
  `rid` int(11) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推送至用户的资源';

-- ----------------------------
-- Table structure for mc_user_tag
-- ----------------------------
DROP TABLE IF EXISTS `mc_user_tag`;
CREATE TABLE `mc_user_tag` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uid` int(11) unsigned default NULL,
  `tid` int(11) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户标签';

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `mc_settings` VALUES ('core.xx', '32', '1');
INSERT INTO `mc_settings` VALUES ('core.yy', '62', '1');
