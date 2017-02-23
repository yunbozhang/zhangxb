-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 04 月 20 日 15:57
-- 服务器版本: 5.5.47
-- PHP 版本: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `borrow`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `last_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '登录ip',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `borrow`
--

CREATE TABLE IF NOT EXISTS `borrow` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `borrow_number` varchar(50) NOT NULL DEFAULT '' COMMENT '借款序号',
  `contract_number` varchar(50) NOT NULL DEFAULT '' COMMENT '合同号',
  `borrow_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `borrow_duration` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '借款期限',
  `borrow_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '借款金额',
  `borrow_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '借款状态 0,未还 1,已还完 2,续借',
  `renew_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '续借的借款id',
  `borrow_interest` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '利息',
  `borrow_interest_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '利率',
  `borrow_procedures` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `borrow_procedures_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费率',
  `is_procedures` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已支付手续费',
  `procedures_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '还手续费时间',
  `borrow_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '借款时间',
  `repayment_type` varchar(255) NOT NULL DEFAULT '' COMMENT '还款方式',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `borrow_remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '借款备注',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='借款' AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- 表的结构 `borrow_repayment`
--

CREATE TABLE IF NOT EXISTS `borrow_repayment` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `borrow_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '借款编号',
  `borrow_uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '借款人',
  `repayment_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '还款金额',
  `repayment_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '还款时间',
  `real_repayment_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '实际还款时间',
  `is_repayment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已还款',
  `is_late` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否逾期',
  `late_penalty_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '逾期罚息',
  `late_interest_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '逾期利息',
  `is_late_money` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否收逾期',
  `is_repayment_late_money` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已收逾期费',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `repayment_remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '还款备注',
  `late_repayment_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '逾期收取时间',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='还款记录' AUTO_INCREMENT=218 ;

-- --------------------------------------------------------

--
-- 表的结构 `borrow_user_relation`
--

CREATE TABLE IF NOT EXISTS `borrow_user_relation` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `borrow_uid` int(11) NOT NULL DEFAULT '0' COMMENT '借款人编号',
  `borrow_id` int(11) NOT NULL DEFAULT '0' COMMENT '借款编号',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='借款用户关联' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '借款人姓名',
  `phone` varchar(50) NOT NULL DEFAULT '' COMMENT '借款人联系方式',
  `add_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`Id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='投资人' AUTO_INCREMENT=17 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
