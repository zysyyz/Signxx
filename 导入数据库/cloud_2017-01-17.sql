# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.1.44)
# Database: cloud
# Generation Time: 2017-01-17 08:33:08 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cloud_advise
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cloud_advise`;

CREATE TABLE `cloud_advise` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `submit_time` datetime DEFAULT NULL,
  `submit_ip` varchar(20) NOT NULL DEFAULT '000.000.000.000',
  `content` varchar(200) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='建议';



# Dump of table cloud_notice
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cloud_notice`;

CREATE TABLE `cloud_notice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `creat_time` varchar(20) NOT NULL DEFAULT '' COMMENT '创建时间',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(200) NOT NULL DEFAULT '' COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告表';



# Dump of table cloud_recommendedrule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cloud_recommendedrule`;

CREATE TABLE `cloud_recommendedrule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `origin_day` int(4) NOT NULL DEFAULT '0' COMMENT '原本时长',
  `give_day` int(4) NOT NULL DEFAULT '0' COMMENT '赠送时长',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='被推荐人规则';



# Dump of table cloud_recommenderrule
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cloud_recommenderrule`;

CREATE TABLE `cloud_recommenderrule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `origin_day` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '原本时长',
  `give_day` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '赠送时长',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推荐人规则';



# Dump of table cloud_regcode
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cloud_regcode`;

CREATE TABLE `cloud_regcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `software_id` int(11) NOT NULL COMMENT '软件id',
  `time_str` varchar(10) NOT NULL DEFAULT '' COMMENT '中文时间',
  `software_name` varchar(20) NOT NULL DEFAULT '' COMMENT '软件名',
  `code` char(32) NOT NULL DEFAULT '' COMMENT '注册码',
  `produce_time` datetime DEFAULT NULL COMMENT '生成时间',
  `all_minutes` int(10) unsigned DEFAULT '0' COMMENT '可用分钟',
  `isonline` mediumint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不在线，1在线',
  `overdue` mediumint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未到期，1到期',
  `computer_uid` char(16) NOT NULL DEFAULT '' COMMENT '机器码',
  `beginuse_time` datetime DEFAULT NULL COMMENT '开始使用时间',
  `expire_time` datetime DEFAULT NULL COMMENT '到期时间',
  `last_time` datetime DEFAULT NULL COMMENT '上次登录时间',
  `last_ip` varchar(20) NOT NULL DEFAULT '000.000.000.000' COMMENT '最后一次登录ip',
  `use_count` int(10) unsigned DEFAULT '0' COMMENT '使用次数',
  `frozen` mediumint(1) NOT NULL DEFAULT '0' COMMENT '0 正常1冻结',
  `token` char(5) DEFAULT NULL COMMENT '限制多开',
  `mark` char(30) NOT NULL DEFAULT '' COMMENT '备注',
  `recommend_code` int(10) DEFAULT NULL COMMENT '推荐码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='注册码表';



# Dump of table cloud_software
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cloud_software`;

CREATE TABLE `cloud_software` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '软件名',
  `version` varchar(10) DEFAULT '' COMMENT '软件版本',
  `info` varchar(45) DEFAULT '' COMMENT '软件公告',
  `update_url` varchar(45) DEFAULT '' COMMENT '更新地址',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `try_minutes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '试用分钟',
  `try_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '试用次数',
  `updatemode` mediumint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0强制更新1不强制',
  `bindmode` mediumint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0绑机单开1绑机多开2不绑多开',
  `unbindmode` mediumint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0允许解绑1不允许',
  `frozen` mediumint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 正常1冻结',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cloud_trial
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cloud_trial`;

CREATE TABLE `cloud_trial` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `computer_uid` char(16) DEFAULT '0' COMMENT '机器码',
  `software_id` int(11) DEFAULT '0' COMMENT '软件id',
  `has_try_count` int(11) DEFAULT '1' COMMENT '已用次数',
  `last_ip` varchar(20) DEFAULT '000.000.000.000' COMMENT 'ip',
  `last_time` datetime DEFAULT NULL COMMENT '最后试用时间',
  `token` char(5) DEFAULT NULL COMMENT '限制多开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table cloud_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cloud_user`;

CREATE TABLE `cloud_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` char(40) NOT NULL DEFAULT '',
  `email` varchar(30) NOT NULL DEFAULT '',
  `reg_time` datetime DEFAULT NULL,
  `lastlogin_time` datetime DEFAULT NULL COMMENT '上一次登录时间',
  `lastlogin_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '上一次登录ip',
  `currentlogin_time` datetime DEFAULT NULL COMMENT '本次登录时间',
  `currentlogin_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '本次登录ip',
  `login_count` int(10) unsigned DEFAULT NULL,
  `forget_time` datetime DEFAULT NULL COMMENT '上次找回密码时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
