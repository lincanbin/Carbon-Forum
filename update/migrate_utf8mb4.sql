---------------------------------------------------------
-- Created based on /install/database.sql @ version 5.9.0
---------------------------------------------------------

-- Some key lengths need to be lowered
-- because utf8mb4 may have 4-bytes max per character.
-- Max key length is 767 bytes
ALTER TABLE `carbon_dict` DROP KEY `title`;
ALTER TABLE `carbon_dict` ADD KEY `title` (`Title`(190)) USING HASH;
ALTER TABLE `carbon_tags` DROP KEY `TagName`;
ALTER TABLE `carbon_tags` ADD KEY `TagName` (`Name`(190)) USING HASH;

-- Change all utf8 columns to utf8mb4
ALTER TABLE `carbon_app_users`
  MODIFY COLUMN `AppUserName` varchar(50) CHARACTER SET utf8mb4;
ALTER TABLE `carbon_blogs`
  MODIFY COLUMN `Content` longtext CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `UserName` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `Category` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Subject` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `BlogDate` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL;
ALTER TABLE `carbon_blogsettings`
  MODIFY COLUMN `BlogTitle` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `BlogTagline` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `UserName` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `BlogBgcolor` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `BlogPermissions` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `BlogBackground` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `BlogAudio` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `MusicUrl` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `MusicName` longtext CHARACTER SET utf8mb4;
ALTER TABLE `carbon_favorites`
  MODIFY COLUMN `Category` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Title` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Description` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL;
ALTER TABLE `carbon_link`
  MODIFY COLUMN `Name` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `URL` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Logo` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Intro` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL;
ALTER TABLE `carbon_log`
  MODIFY COLUMN `UserName` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `IPAddress` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `UserAgent` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `HttpVerb` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `PathAndQuery` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `Referrer` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `ErrDescription` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `POSTData` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `Notes` longtext CHARACTER SET utf8mb4;
ALTER TABLE `carbon_pictures`
  MODIFY COLUMN `PicUrl` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `UserName` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `PicReadme` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL;
ALTER TABLE `carbon_postrating`
  MODIFY COLUMN `UserName` varchar(50) CHARACTER SET utf8mb4 NOT NULL;
ALTER TABLE `carbon_posts`
  MODIFY COLUMN `UserName` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `Subject` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Content` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `PostIP` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL;
ALTER TABLE `carbon_roles`
  MODIFY COLUMN `Name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `Description` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL;
ALTER TABLE `carbon_tags`
  MODIFY COLUMN `Name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `Description` mediumtext CHARACTER SET utf8mb4;
ALTER TABLE `carbon_topics`
  MODIFY COLUMN `Topic` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `Tags` text CHARACTER SET utf8mb4,
  MODIFY COLUMN `UserName` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `LastName` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `ThreadStyle` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Lists` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `Log` longtext CHARACTER SET utf8mb4;
ALTER TABLE `carbon_upload`
  MODIFY COLUMN `UserName` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `FileName` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `FileType` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `FilePath` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  MODIFY COLUMN `Description` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Category` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Class` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL;
ALTER TABLE `carbon_users`
  MODIFY COLUMN `UserName` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `Password` char(32) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `UserMail` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `UserHomepage` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `PasswordQuestion` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `PasswordAnswer` char(32) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `UserPhoto` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `UserMobile` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `UserLastIP` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  MODIFY COLUMN `BlackLists` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `UserFriend` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `UserInfo` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `UserIntro` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `UserIM` longtext CHARACTER SET utf8mb4;
ALTER TABLE `carbon_vote`
  MODIFY COLUMN `Items` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `Result` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `BallotUserList` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `BallotIPList` longtext CHARACTER SET utf8mb4,
  MODIFY COLUMN `BallotItemsList` longtext CHARACTER SET utf8mb4;

-- Set all tables' default charset to utf8mb4
ALTER TABLE `carbon_app` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_app_users` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_blogs` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_blogsettings` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_config` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_dict` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_favorites` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_link` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_log` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_notifications` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_pictures` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_postrating` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_posts` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_posttags` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_roles` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_statistics` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_tags` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_topics` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_upload` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_users` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_vote` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_messages` DEFAULT CHARSET=utf8mb4;
ALTER TABLE `carbon_inbox` DEFAULT CHARSET=utf8mb4;