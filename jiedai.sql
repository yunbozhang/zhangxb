-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-02-23 07:46:33
-- 服务器版本： 10.1.13-MariaDB
-- PHP Version: 5.5.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jiedai`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `Id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `last_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '登录ip'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员';

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`Id`, `name`, `password`, `add_time`, `last_time`, `ip`) VALUES
(3, 'Administrator', '646045fc4a584cd664815bf167a81368', 1487820340, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `borrow`
--

CREATE TABLE `borrow` (
  `Id` int(11) NOT NULL,
  `borrow_number` varchar(50) NOT NULL DEFAULT '' COMMENT '借款序号',
  `contract_number` varchar(50) NOT NULL DEFAULT '' COMMENT '合同号',
  `borrow_uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户',
  `borrow_duration` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '借款期限',
  `borrow_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '借款金额',
  `borrow_status` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '借款状态 0,未还 1,已还完 2,续借',
  `renew_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '续借的借款id',
  `borrow_interest` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '利息',
  `borrow_interest_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '利率',
  `borrow_procedures` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `borrow_procedures_rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费率',
  `is_procedures` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否已支付手续费',
  `procedures_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '还手续费时间',
  `borrow_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '借款时间',
  `repayment_type` varchar(255) NOT NULL DEFAULT '' COMMENT '还款方式',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `borrow_remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '借款备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='借款';

-- --------------------------------------------------------

--
-- 表的结构 `borrow_repayment`
--

CREATE TABLE `borrow_repayment` (
  `Id` int(11) NOT NULL,
  `borrow_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '借款编号',
  `borrow_uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '借款人',
  `repayment_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '还款金额',
  `repayment_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '还款时间',
  `real_repayment_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '实际还款时间',
  `is_repayment` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否已还款',
  `is_late` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否逾期',
  `late_penalty_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '逾期罚息',
  `late_interest_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '逾期利息',
  `is_late_money` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否收逾期',
  `is_repayment_late_money` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否已收逾期费',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间',
  `repayment_remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '还款备注',
  `late_repayment_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '逾期收取时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='还款记录';

-- --------------------------------------------------------

--
-- 表的结构 `borrow_user_relation`
--

CREATE TABLE `borrow_user_relation` (
  `Id` int(11) NOT NULL,
  `borrow_uid` int(11) NOT NULL DEFAULT '0' COMMENT '借款人编号',
  `borrow_id` int(11) NOT NULL DEFAULT '0' COMMENT '借款编号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='借款用户关联';

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `Id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '借款人姓名',
  `phone` varchar(50) NOT NULL DEFAULT '' COMMENT '借款人联系方式',
  `add_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='投资人';

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`Id`, `name`, `phone`, `add_time`) VALUES
(17, '张云波', '18910403461', 1487820485),
(18, '张云波', '18910403461', 1487820505),
(19, '张云波', '18910403461', 1487820524);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `borrow_repayment`
--
ALTER TABLE `borrow_repayment`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `borrow_user_relation`
--
ALTER TABLE `borrow_user_relation`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `name` (`name`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `borrow`
--
ALTER TABLE `borrow`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- 使用表AUTO_INCREMENT `borrow_repayment`
--
ALTER TABLE `borrow_repayment`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;
--
-- 使用表AUTO_INCREMENT `borrow_user_relation`
--
ALTER TABLE `borrow_user_relation`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
