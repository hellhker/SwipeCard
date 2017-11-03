-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2017 年 11 月 03 日 17:18
-- 服务器版本: 5.0.51
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `swipecard`
--

-- --------------------------------------------------------

--
-- 表的结构 `assistant_data`
--

CREATE TABLE IF NOT EXISTS `assistant_data` (
  `rid` int(11) NOT NULL auto_increment,
  `CostID` varchar(8) NOT NULL,
  `application_person` varchar(12) NOT NULL,
  `application_id` varchar(8) NOT NULL,
  `application_dep` varchar(8) NOT NULL,
  `application_tel` varchar(8) NOT NULL,
  PRIMARY KEY  (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 表的结构 `employee_reason`
--

CREATE TABLE IF NOT EXISTS `employee_reason` (
  `RecordID` int(11) NOT NULL auto_increment,
  `ID` varchar(8) default NULL,
  `Name` varchar(10) default NULL,
  `LineNo` varchar(6) default NULL,
  `RC_NO` varchar(30) default NULL,
  `overtimeDate` date default NULL,
  `overtimeInterval` varchar(20) default NULL,
  `calHours` varchar(6) default NULL,
  `overtimeHours` varchar(6) default NULL,
  `overtimeType` varchar(2) default NULL,
  `Direct` varchar(2) default NULL,
  `Reason` varchar(500) default NULL,
  `State` int(1) default '0',
  PRIMARY KEY  (`RecordID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

-- --------------------------------------------------------

--
-- 表的结构 `interval_setting`
--

CREATE TABLE IF NOT EXISTS `interval_setting` (
  `Record_ID` int(11) NOT NULL auto_increment,
  `Factory` varchar(10) default NULL,
  `WorkshopNo` varchar(10) default NULL,
  `d_interval1` varchar(15) default NULL,
  `d_interval2` varchar(15) default NULL,
  `d_interval3` varchar(15) default NULL,
  `d_interval4` varchar(15) default NULL,
  `d_interval5` varchar(15) default NULL,
  `n_interval1` varchar(15) default NULL,
  `n_interval2` varchar(15) default NULL,
  `n_interval3` varchar(15) default NULL,
  `n_interval4` varchar(15) default NULL,
  `n_interval5` varchar(15) default NULL,
  `weekend` varchar(2) default NULL,
  `Shift` varchar(2) NOT NULL,
  PRIMARY KEY  (`Record_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- 表的结构 `lineno`
--

CREATE TABLE IF NOT EXISTS `lineno` (
  `id` int(4) NOT NULL auto_increment,
  `FactoryCode` varchar(10) default NULL,
  `workshopno` varchar(10) NOT NULL,
  `lineno` varchar(20) NOT NULL,
  `use` int(11) NOT NULL,
  `lockperson` varchar(20) NOT NULL,
  `linesize` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7128 ;

-- --------------------------------------------------------

--
-- 表的结构 `lmt_dept`
--

CREATE TABLE IF NOT EXISTS `lmt_dept` (
  `depid` varchar(6) default NULL COMMENT '部門代碼',
  `deptname` varchar(100) default NULL COMMENT '部門名稱',
  `costid` varchar(6) default NULL COMMENT '費用代碼',
  `deptname2` varchar(100) default NULL COMMENT '費用代碼對應部門名稱',
  KEY `ld_depid_idx` (`depid`),
  KEY `ld_costid_idx` (`costid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `lose_employee`
--

CREATE TABLE IF NOT EXISTS `lose_employee` (
  `Record_ID` int(11) NOT NULL auto_increment,
  `CardID` varchar(10) default NULL,
  `ID` varchar(10) default NULL,
  `Name` varchar(6) default NULL,
  `WorkShopNo` varchar(10) default NULL,
  `SwipeDate` date default NULL,
  `LineNo` varchar(6) default NULL,
  `State` int(1) default '0',
  PRIMARY KEY  (`Record_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=513 ;

-- --------------------------------------------------------

--
-- 表的结构 `notes_overtime_state`
--

CREATE TABLE IF NOT EXISTS `notes_overtime_state` (
  `rid` int(11) NOT NULL auto_increment,
  `id` varchar(7) default NULL,
  `name` varchar(10) default NULL,
  `Depid` varchar(6) default NULL,
  `costID` varchar(6) default NULL,
  `depName` varchar(50) default NULL,
  `Direct` varchar(2) default NULL,
  `WorkshopNo` varchar(20) default NULL,
  `LineNo` varchar(10) default NULL,
  `IS_LMT` varchar(5) default NULL,
  `message` varchar(500) default NULL,
  `RC_NO` varchar(30) default NULL,
  `PRIMARY_ITEM_NO` varchar(25) default NULL,
  `overTimeDate` varchar(50) default NULL,
  `Shift` varchar(4) default NULL,
  `WorkContent` varchar(50) default NULL,
  `Cont_Hours` varchar(6) default NULL,
  `overtimeHours` varchar(6) default NULL,
  `overtimeType` int(2) default NULL,
  `isExcetion` varchar(2) default NULL,
  `overtimeInterval` varchar(20) default NULL,
  `application_person` varchar(20) default NULL,
  `application_id` varchar(7) default NULL,
  `application_dep` varchar(100) default NULL,
  `application_tel` varchar(20) default NULL,
  `notesStates` int(2) NOT NULL default '0',
  `Reason` varchar(100) default NULL,
  `BackTime` datetime default NULL,
  `update_time` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT '審核時間',
  PRIMARY KEY  (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=196176 ;

-- --------------------------------------------------------

--
-- 表的结构 `notes_overtime_state_abnormal`
--

CREATE TABLE IF NOT EXISTS `notes_overtime_state_abnormal` (
  `rid` int(11) NOT NULL,
  `group_sort` varchar(30) default NULL,
  `id` varchar(7) default NULL,
  `name` varchar(10) default NULL,
  `Depid` varchar(6) default NULL,
  `costID` varchar(6) default NULL,
  `depName` varchar(50) default NULL,
  `Direct` varchar(2) default NULL,
  `LineNo` varchar(6) default NULL,
  `RC_NO` varchar(30) default NULL,
  `PRIMARY_ITEM_NO` varchar(25) default NULL,
  `overTimeDate` varchar(50) default NULL,
  `WorkContent` varchar(50) default NULL,
  `overtimeHours` varchar(6) default NULL,
  `overtimeType` int(2) default NULL,
  `overtimeStart` varchar(20) default NULL,
  `overtimeEnd` varchar(20) default NULL,
  `application_person` varchar(20) default NULL,
  `application_id` varchar(7) default NULL,
  `application_dep` varchar(100) default NULL,
  `application_tel` varchar(20) default NULL,
  `notesStates` int(2) default '0',
  `Reason` varchar(100) default NULL,
  `BackTime` datetime default NULL,
  `IS_LMT` varchar(5) default NULL,
  PRIMARY KEY  (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `notes_overtime_state_new`
--

CREATE TABLE IF NOT EXISTS `notes_overtime_state_new` (
  `rid` int(11) NOT NULL,
  `group_sort` varchar(30) default NULL,
  `id` varchar(7) default NULL,
  `name` varchar(10) default NULL,
  `Depid` varchar(6) default NULL,
  `costID` varchar(6) default NULL,
  `depName` varchar(50) default NULL,
  `Direct` varchar(2) default NULL,
  `LineNo` varchar(6) default NULL,
  `RC_NO` varchar(30) default NULL,
  `PRIMARY_ITEM_NO` varchar(25) default NULL,
  `overTimeDate` varchar(50) default NULL,
  `WorkContent` varchar(50) default NULL,
  `overtimeHours` varchar(6) default NULL,
  `overtimeType` int(2) default NULL,
  `overtimeStart` varchar(20) default NULL,
  `overtimeEnd` varchar(20) default NULL,
  `application_person` varchar(20) default NULL,
  `application_id` varchar(7) default NULL,
  `application_dep` varchar(100) default NULL,
  `application_tel` varchar(20) default NULL,
  `notesStates` int(2) default '0',
  `Reason` varchar(100) default NULL,
  `BackTime` datetime default NULL,
  `IS_LMT` varchar(5) default NULL,
  PRIMARY KEY  (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `program_ver`
--

CREATE TABLE IF NOT EXISTS `program_ver` (
  `id` int(11) NOT NULL auto_increment,
  `version` varchar(12) NOT NULL,
  `update_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `raw_record`
--

CREATE TABLE IF NOT EXISTS `raw_record` (
  `id` varchar(10) default NULL COMMENT '工號',
  `cardid` char(10) default NULL COMMENT '卡號',
  `swipecardtime` datetime NOT NULL COMMENT '即時刷卡時間',
  `update_time` timestamp NOT NULL default CURRENT_TIMESTAMP COMMENT '刷卡資料更新時間',
  `record_status` varchar(2) NOT NULL default '0' COMMENT '0.正常 1.無人員資料 2.無班別資料 3.上班提前超15分鐘 4.不符合七休一 5.刷卡重復 6.上下班卡已刷',
  KEY `rr_ididx` (`id`),
  KEY `rr_udtidx` (`update_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `testemployee`
--

CREATE TABLE IF NOT EXISTS `testemployee` (
  `ID` char(10) NOT NULL,
  `name` varchar(20) default NULL COMMENT '姓名',
  `depid` varchar(6) default NULL,
  `depname` varchar(100) default NULL,
  `Direct` varchar(2) default NULL,
  `cardid` char(10) NOT NULL,
  `costID` varchar(6) default NULL,
  `Permission` int(2) NOT NULL default '1',
  `isOnWork` int(2) NOT NULL default '0' COMMENT '0在職，1離職',
  `updateDate` date default NULL,
  PRIMARY KEY  (`ID`),
  KEY `cardididx` (`cardid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `testemployee1`
--

CREATE TABLE IF NOT EXISTS `testemployee1` (
  `ID` char(10) NOT NULL,
  `name` varchar(20) default NULL COMMENT '姓名',
  `depid` varchar(6) default NULL,
  `depname` varchar(100) default NULL,
  `Direct` varchar(2) default NULL,
  `cardid` char(10) NOT NULL,
  `costID` varchar(6) default NULL,
  `Permission` int(2) default NULL,
  `isOnWork` int(2) NOT NULL default '0' COMMENT '0在職，1離職',
  `updateDate` date default NULL,
  PRIMARY KEY  (`ID`),
  KEY `cardididx` (`cardid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `testinfor`
--

CREATE TABLE IF NOT EXISTS `testinfor` (
  `CountID` int(11) NOT NULL auto_increment,
  `RC_NO` varchar(50) NOT NULL,
  `PRIMARY_ITEM_NO` varchar(50) default NULL,
  `PROD_LINE_CODE` varchar(15) default NULL,
  `WorkShopNo` char(20) NOT NULL,
  `STD_MAN_POWER` varchar(10) default NULL,
  `ACTUAL_POWER` varchar(10) default NULL,
  `REMARK` varchar(500) default NULL,
  `CurrentTime` datetime default NULL,
  PRIMARY KEY  (`CountID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `testrcline`
--

CREATE TABLE IF NOT EXISTS `testrcline` (
  `RC_Record_ID` int(11) NOT NULL auto_increment,
  `RC_NO` varchar(100) character set utf8 collate utf8_bin default NULL,
  `PRIMARY_ITEM_NO` varchar(100) default NULL,
  `STD_MAN_POWER` varchar(30) default NULL,
  `PROD_LINE_CODE` varchar(15) default NULL,
  `CUR_DATE` datetime default NULL,
  PRIMARY KEY  (`RC_Record_ID`),
  KEY `RC_NO` (`RC_NO`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=148551422 ;

-- --------------------------------------------------------

--
-- 表的结构 `testrcline1`
--

CREATE TABLE IF NOT EXISTS `testrcline1` (
  `RC_Record_ID` int(11) NOT NULL auto_increment,
  `RC_NO` varchar(100) NOT NULL,
  `PRIMARY_ITEM_NO` varchar(100) default NULL,
  `STD_MAN_POWER` varchar(30) default NULL,
  `PROD_LINE_CODE` varchar(15) default NULL,
  `CUR_DATE` datetime default NULL,
  PRIMARY KEY  (`RC_Record_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=148010067 ;

-- --------------------------------------------------------

--
-- 表的结构 `testswipecardtime`
--

CREATE TABLE IF NOT EXISTS `testswipecardtime` (
  `RecordID` int(11) NOT NULL auto_increment,
  `CardID` char(10) NOT NULL,
  `Name` varchar(10) NOT NULL,
  `SwipeCardTime` datetime default NULL,
  `SwipeCardTime2` datetime default NULL,
  `CheckState` varchar(10) NOT NULL default '0',
  `PROD_LINE_CODE` varchar(20) NOT NULL default 'null',
  `WorkshopNo` char(30) NOT NULL,
  `PRIMARY_ITEM_NO` varchar(80) NOT NULL default 'null',
  `RC_NO` varchar(80) character set utf8 collate utf8_bin default NULL,
  `Shift` varchar(2) default NULL,
  `OvertimeState` int(2) NOT NULL default '0',
  `overtimeType` int(2) NOT NULL default '0',
  `overtimeCal` int(2) NOT NULL default '0',
  PRIMARY KEY  (`RecordID`),
  KEY `CardID` (`CardID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=232490 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_data`
--

CREATE TABLE IF NOT EXISTS `user_data` (
  `ID` int(4) NOT NULL auto_increment,
  `UserID` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `ChineseName` varchar(20) NOT NULL,
  `assistant_id` varchar(10) NOT NULL,
  `DepartmentCode` varchar(6) default NULL,
  `CostID` varchar(50) default NULL,
  `costid_arr` varchar(10) NOT NULL,
  `swipe_system_chief` varchar(2) NOT NULL default '1',
  `EMail` varchar(40) default NULL,
  `Phone_tel` varchar(30) default NULL,
  `Phone_system` varchar(3) default NULL,
  `Phone_sn` varchar(30) default NULL,
  `WeChat_ID` varchar(30) default NULL,
  `WeChat_check` int(1) default '0',
  `WeChat_Update_Date` date default '0000-00-00',
  `modification_prove` int(1) default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `EMail` (`EMail`),
  KEY `UserID` (`UserID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=534 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
