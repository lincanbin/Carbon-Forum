/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2006-2017 Canbin Lin (lincanbin@hotmail.com)
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for carbon_app
-- ----------------------------
DROP TABLE IF EXISTS `carbon_app`;
CREATE TABLE `carbon_app` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AppKey` varchar(20) NOT NULL,
  `AppName` varchar(32) NOT NULL,
  `AppSecret` varchar(40) NOT NULL,
  `Time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `AppKey` (`AppKey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_app_users
-- ----------------------------
DROP TABLE IF EXISTS `carbon_app_users`;
CREATE TABLE `carbon_app_users` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `AppID` int(10) unsigned NOT NULL,
  `OpenID` varchar(64) NOT NULL,
  `AppUserName` varchar(50),
  `UserID` int(10) unsigned NOT NULL,
  `Time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Index` (`AppID`,`OpenID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_config
-- ----------------------------
DROP TABLE IF EXISTS `carbon_config`;
CREATE TABLE `carbon_config` (
  `ConfigName` varchar(50) NOT NULL DEFAULT '',
  `ConfigValue` text NOT NULL,
  PRIMARY KEY (`ConfigName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_dict
-- ----------------------------
DROP TABLE IF EXISTS `carbon_dict`;
CREATE TABLE `carbon_dict` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(512) NOT NULL,
  `Abstract` mediumtext NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `title` (`Title`(190)) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_favorites
-- ----------------------------
DROP TABLE IF EXISTS `carbon_favorites`;
CREATE TABLE `carbon_favorites` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(10) unsigned NOT NULL,
  `Category` varchar(255) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Type` tinyint(1) unsigned DEFAULT NULL,
  `FavoriteID` int(10) unsigned NOT NULL,
  `DateCreated` int(10) unsigned NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `IsFavorite` (`UserID`,`Type`,`FavoriteID`),
  KEY `UsersFavorites` (`UserID`,`Type`,`DateCreated`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_inbox
-- ----------------------------
DROP TABLE IF EXISTS `carbon_inbox`;
CREATE TABLE `carbon_inbox` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `SenderID` int(10) NOT NULL,
  `SenderName` varchar(50) NOT NULL,
  `ReceiverID` int(10) NOT NULL,
  `ReceiverName` varchar(50) NOT NULL,
  `LastContent` varchar(255) NOT NULL DEFAULT '',
  `LastTime` int(10) NOT NULL DEFAULT '0',
  `IsDel` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `DialogueID` (`LastTime`) USING BTREE,
  KEY `SenderID` (`SenderID`,`ReceiverID`),
  KEY `ReceiverID` (`ReceiverID`,`SenderID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_link
-- ----------------------------
DROP TABLE IF EXISTS `carbon_link`;
CREATE TABLE `carbon_link` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `URL` varchar(255) DEFAULT NULL,
  `Logo` varchar(255) DEFAULT NULL,
  `Intro` varchar(255) DEFAULT NULL,
  `Review` int(11) DEFAULT '0',
  `TopLink` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_messages
-- ----------------------------
DROP TABLE IF EXISTS `carbon_messages`;
CREATE TABLE `carbon_messages` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `InboxID` int(10) NOT NULL DEFAULT '0',
  `UserID` int(10) NOT NULL DEFAULT '0',
  `Content` longtext NOT NULL,
  `Time` int(10) unsigned NOT NULL,
  `IsDel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `Index` (`IsDel`,`InboxID`,`Time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_notifications
-- ----------------------------
DROP TABLE IF EXISTS `carbon_notifications`;
CREATE TABLE `carbon_notifications` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UserID` int(10) unsigned NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Type` tinyint(1) unsigned NOT NULL,
  `TopicID` int(10) unsigned NOT NULL,
  `PostID` int(10) unsigned NOT NULL,
  `Time` int(10) unsigned NOT NULL,
  `IsRead` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `TopicID` (`TopicID`),
  KEY `PostID` (`PostID`),
  KEY `UserID` (`UserID`,`Time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_posts
-- ----------------------------
DROP TABLE IF EXISTS `carbon_posts`;
CREATE TABLE `carbon_posts` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TopicID` int(10) unsigned DEFAULT '0',
  `IsTopic` tinyint(1) unsigned DEFAULT '0',
  `UserID` int(10) unsigned NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `Content` longtext,
  `PostIP` varchar(50) DEFAULT NULL,
  `PostTime` int(10) unsigned NOT NULL,
  `PostTimeIndex` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `TopicID` (`TopicID`,`PostTimeIndex`) USING BTREE,
  KEY `UserPosts` (`UserName`,`PostTimeIndex`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_posts_recycle_bin
-- ----------------------------
DROP TABLE IF EXISTS `carbon_posts_recycle_bin`;
CREATE TABLE `carbon_posts_recycle_bin` (
  `ID` int(10) unsigned NOT NULL,
  `TopicID` int(10) unsigned DEFAULT '0',
  `IsTopic` tinyint(1) unsigned DEFAULT '0',
  `UserID` int(10) unsigned NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Subject` varchar(255) DEFAULT NULL,
  `Content` longtext,
  `PostIP` varchar(50) DEFAULT NULL,
  `PostTime` int(10) unsigned NOT NULL,
  `PostTimeIndex` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `TopicID` (`TopicID`,`PostTimeIndex`) USING BTREE,
  KEY `UserPosts` (`UserName`,`PostTimeIndex`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_posttags
-- ----------------------------
DROP TABLE IF EXISTS `carbon_posttags`;
CREATE TABLE `carbon_posttags` (
  `TagID` int(11) DEFAULT '0',
  `TopicID` int(11) DEFAULT '0',
  `PostID` int(11) DEFAULT '0',
  KEY `TagsIndex` (`TagID`,`TopicID`),
  KEY `TopicID` (`TopicID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_statistics
-- ----------------------------
DROP TABLE IF EXISTS `carbon_statistics`;
CREATE TABLE `carbon_statistics` (
  `DaysUsers` int(10) unsigned NOT NULL DEFAULT '0',
  `DaysPosts` int(10) unsigned NOT NULL DEFAULT '0',
  `DaysTopics` int(10) unsigned NOT NULL DEFAULT '0',
  `TotalUsers` int(10) unsigned NOT NULL DEFAULT '0',
  `TotalPosts` int(10) unsigned NOT NULL DEFAULT '0',
  `TotalTopics` int(10) unsigned NOT NULL DEFAULT '0',
  `DaysDate` date NOT NULL DEFAULT '2014-11-01',
  `DateCreated` int(10) unsigned NOT NULL,
  PRIMARY KEY (`DaysDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_tags
-- ----------------------------
DROP TABLE IF EXISTS `carbon_tags`;
CREATE TABLE `carbon_tags` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `Followers` int(10) unsigned DEFAULT '0',
  `Icon` tinyint(1) unsigned DEFAULT '0',
  `Description` mediumtext,
  `IsEnabled` tinyint(1) unsigned DEFAULT '1',
  `TotalPosts` int(10) unsigned DEFAULT '0',
  `MostRecentPostTime` int(10) unsigned NOT NULL,
  `DateCreated` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `TagName` (`Name`(190)) USING HASH,
  KEY `TotalPosts` (`IsEnabled`, `TotalPosts`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_topics
-- ----------------------------
DROP TABLE IF EXISTS `carbon_topics`;
CREATE TABLE `carbon_topics` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Topic` varchar(255) NOT NULL,
  `Tags` text,
  `UserID` int(10) unsigned NOT NULL DEFAULT '0',
  `UserName` varchar(50) NOT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `PostTime` int(10) unsigned NOT NULL,
  `LastTime` int(10) unsigned NOT NULL,
  `LastTimeIndex` bigint(20) unsigned NOT NULL DEFAULT '0',
  `IsLocked` tinyint(1) unsigned DEFAULT '0',
  `IsDel` tinyint(1) unsigned DEFAULT '0',
  `Views` int(10) unsigned DEFAULT '0',
  `Replies` int(10) unsigned DEFAULT '0',
  `Favorites` int(10) unsigned DEFAULT '0',
  `LastViewedTime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `LastTime` (`LastTimeIndex`,`IsDel`) USING BTREE,
  KEY `UserTopics` (`UserName`,`IsDel`,`LastTimeIndex`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_upload
-- ----------------------------
DROP TABLE IF EXISTS `carbon_upload`;
CREATE TABLE `carbon_upload` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(50) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `FileSize` int(10) unsigned NOT NULL DEFAULT '0',
  `FileType` varchar(255) NOT NULL,
  `SHA1` char(40) DEFAULT NULL,
  `MD5` char(32) DEFAULT NULL,
  `FilePath` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Category` varchar(255) DEFAULT NULL,
  `Class` varchar(255) DEFAULT NULL,
  `PostID` int(10) unsigned DEFAULT NULL,
  `Created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Hash` (`FileSize`,`SHA1`,`MD5`) USING BTREE,
  KEY `UsersName` (`UserName`,`Created`) USING BTREE,
  KEY `PostID` (`PostID`,`UserName`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for carbon_users
-- ----------------------------
DROP TABLE IF EXISTS `carbon_users`;
CREATE TABLE `carbon_users` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `UserName` varchar(50) DEFAULT NULL,
  `Salt` int(6) unsigned DEFAULT NULL,
  `Password` char(32) DEFAULT NULL,
  `UserMail` varchar(255) DEFAULT NULL,
  `UserHomepage` varchar(255) DEFAULT NULL,
  `PasswordQuestion` varchar(255) DEFAULT NULL,
  `PasswordAnswer` char(32) DEFAULT NULL,
  `UserSex` tinyint(1) unsigned DEFAULT NULL,
  `NumFavUsers` int(10) unsigned DEFAULT '0',
  `NumFavTags` int(10) unsigned DEFAULT '0',
  `NumFavTopics` int(10) unsigned DEFAULT '0',
  `NewReply` int(10) unsigned DEFAULT '0',
  `NewMention` int(10) unsigned DEFAULT '0',
  `NewMessage` int(10) unsigned DEFAULT '0',
  `Topics` int(10) unsigned DEFAULT '0',
  `Replies` int(10) unsigned DEFAULT '0',
  `Followers` int(10) unsigned DEFAULT '0',
  `DelTopic` int(10) unsigned DEFAULT '0',
  `GoodTopic` int(10) unsigned DEFAULT '0',
  `UserPhoto` varchar(255) DEFAULT NULL,
  `UserMobile` varchar(255) DEFAULT NULL,
  `UserLastIP` varchar(50) DEFAULT NULL,
  `UserRegTime` int(10) unsigned NOT NULL,
  `LastLoginTime` int(10) unsigned NOT NULL,
  `LastPostTime` int(10) unsigned DEFAULT NULL,
  `BlackLists` longtext,
  `UserFriend` longtext,
  `UserInfo` longtext,
  `UserIntro` longtext,
  `UserIM` longtext,
  `UserRoleID` tinyint(1) unsigned NOT NULL DEFAULT '3',
  `UserAccountStatus` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `Birthday` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UserName` (`UserName`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of carbon_config
-- ----------------------------
INSERT INTO `carbon_config` VALUES ('AllowEditing', 'true');
INSERT INTO `carbon_config` VALUES ('AllowEmptyTags', 'false');
INSERT INTO `carbon_config` VALUES ('AllowNewTopic', 'true');
INSERT INTO `carbon_config` VALUES ('AppDomainName', 'app.94cb.com');
INSERT INTO `carbon_config` VALUES ('CacheAnnouncements', '');
INSERT INTO `carbon_config` VALUES ('CacheHotTags', '');
INSERT INTO `carbon_config` VALUES ('CacheHotTopics', '');
INSERT INTO `carbon_config` VALUES ('CacheLinks', '');
INSERT INTO `carbon_config` VALUES ('CacheOauth', '');
INSERT INTO `carbon_config` VALUES ('CacheRolesDict', '');
INSERT INTO `carbon_config` VALUES ('CacheTime', '');
INSERT INTO `carbon_config` VALUES ('CloseRegistration', 'false');
INSERT INTO `carbon_config` VALUES ('CookiePrefix', 'CarbonBBS_');
INSERT INTO `carbon_config` VALUES ('DaysDate', '2014-11-01');
INSERT INTO `carbon_config` VALUES ('DaysPosts', '0');
INSERT INTO `carbon_config` VALUES ('DaysTopics', '0');
INSERT INTO `carbon_config` VALUES ('DaysUsers', '0');
INSERT INTO `carbon_config` VALUES ('FreezingTime', '0');
INSERT INTO `carbon_config` VALUES ('MainDomainName', '');
INSERT INTO `carbon_config` VALUES ('MaxPostChars', '60000');
INSERT INTO `carbon_config` VALUES ('MaxTagChars', '128');
INSERT INTO `carbon_config` VALUES ('MaxTagsNum', '5');
INSERT INTO `carbon_config` VALUES ('MaxTitleChars', '255');
INSERT INTO `carbon_config` VALUES ('MobileDomainName', '');
INSERT INTO `carbon_config` VALUES ('NumFiles', '0');
INSERT INTO `carbon_config` VALUES ('NumPosts', '0');
INSERT INTO `carbon_config` VALUES ('NumTags', '0');
INSERT INTO `carbon_config` VALUES ('NumTopics', '0');
INSERT INTO `carbon_config` VALUES ('NumUsers', '0');
INSERT INTO `carbon_config` VALUES ('PageBottomContent', '');
INSERT INTO `carbon_config` VALUES ('PageHeadContent', '');
INSERT INTO `carbon_config` VALUES ('PageSiderContent', 'Hello World');
INSERT INTO `carbon_config` VALUES ('PostsPerPage', '25');
INSERT INTO `carbon_config` VALUES ('PostingInterval', '5');
INSERT INTO `carbon_config` VALUES ('SiteDesc', '一个精简、高速的基于话题的新式论坛');
INSERT INTO `carbon_config` VALUES ('SiteName', 'Carbon Forum');
INSERT INTO `carbon_config` VALUES ('TopicsPerPage', '20');
INSERT INTO `carbon_config` VALUES ('Version', '6.0.0');

INSERT INTO `carbon_config` VALUES ('PushConnectionTimeoutPeriod', '22');
INSERT INTO `carbon_config` VALUES ('SMTPHost', 'smtp1.example.com');
INSERT INTO `carbon_config` VALUES ('SMTPPort', '587');
INSERT INTO `carbon_config` VALUES ('SMTPAuth', 'true');
INSERT INTO `carbon_config` VALUES ('SMTPUsername', 'user@example.com');
INSERT INTO `carbon_config` VALUES ('SMTPPassword', 'secret');
