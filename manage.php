<?php
require(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/manage.php');
SetStyle('api', 'API');

$ID     = intval(Request('Post', 'ID', 0));
$Type   = intval(Request('Post', 'Type', 0)); //1:Topic,2:Post,3:User
$Action = Request('Post', 'Action', false);

switch ($Type) {
	//Topic
	case 1:
		$TopicInfo = $DB->row("SELECT * FROM " . $Prefix . "topics force index(PRI) WHERE ID=:ID", array(
			"ID" => $ID
		));
		if (!$TopicInfo) {
			AlertMsg('Topic Not Found', 'Topic Not Found');
		}
		switch ($Action) {
			//将主题移动至回收站
			case 'Delete':
				Auth(4);
				if ($TopicInfo['IsDel'] == 0) {
					$DB->query("UPDATE " . $Prefix . "topics SET IsDel = 1 WHERE ID=:ID", array(
						"ID" => $ID
					));
					//更新全站统计数据
					$NewConfig = array(
						"NumTopics" => $Config["NumTopics"] - 1
					);
					UpdateConfig($NewConfig);
					//更新用户自身统计数据
					$DB->query("UPDATE `" . $Prefix . "users` SET Topics=Topics-1 WHERE `ID`=?", array(
						$TopicInfo['UserID']
					));
					//更新标签统计
					if ($TopicInfo['Tags']) {
						$DB->query("UPDATE `" . $Prefix . "tags` SET TotalPosts=TotalPosts-1 WHERE `Name` in (?)", explode('|', $TopicInfo['Tags']));
					}
					$Message = $Lang['Deleted'];
				} else {
					AlertMsg('Bad Request', $Lang['Deleted']);
				}
				break;
			//从回收站恢复主题
			case 'Recover':
				Auth(4);
				if ($TopicInfo['IsDel'] == 1) {
					$DB->query("UPDATE " . $Prefix . "topics SET IsDel = 0 WHERE ID=:ID", array(
						"ID" => $ID
					));
					//更新全站统计数据
					$NewConfig = array(
						"NumTopics" => $Config["NumTopics"] + 1
					);
					UpdateConfig($NewConfig);
					//更新用户自身统计数据
					$DB->query("UPDATE `" . $Prefix . "users` SET Topics=Topics+1 WHERE `ID`=?", array(
						$TopicInfo['UserID']
					));
					//更新标签统计
					if ($TopicInfo['Tags']) {
						$DB->query("UPDATE `" . $Prefix . "tags` SET TotalPosts=TotalPosts+1 WHERE `Name` in (?)", explode('|', $TopicInfo['Tags']));
					}
					$Message = $Lang['Recovered'];
				} else {
					AlertMsg('Bad Request', $Lang['Failure_Recovery']);
				}
				break;
			//永久删除主题（需要先将主题移动至回收站）
			case 'PermanentlyDelete':
				Auth(5);
				if ($TopicInfo['IsDel'] == 1) {
					$DB->query('DELETE FROM `' . $Prefix . 'posttags` WHERE TopicID=?', array(
						$ID
					));
					$DB->query('DELETE FROM `' . $Prefix . 'posts` WHERE TopicID=?', array(
						$ID
					));
					$DB->query('DELETE FROM `' . $Prefix . 'topics` WHERE ID=?', array(
						$ID
					));
					$DB->query('DELETE FROM `' . $Prefix . 'notifications` WHERE TopicID=?', array(
						$ID
					));
					$Message = $Lang['Permanently_Deleted'];
				} else {
					AlertMsg('Bad Request', $Lang['Failure_Permanent_Deletion']);
				}
				break;
			//主题下沉（LastTime-7*86400）
			case 'Sink':
				Auth(4);
				$DB->query("UPDATE " . $Prefix . "topics SET LastTime = LastTime-604800 WHERE ID=:ID", array(
					"ID" => $ID
				));
				$Message = $Lang['Sunk'];
				break;
			//主题上浮（LastTime+7*86400）
			case 'Rise':
				Auth(4);
				$DB->query("UPDATE " . $Prefix . "topics SET LastTime = LastTime+604800 WHERE ID=:ID", array(
					"ID" => $ID
				));
				$Message = $Lang['Risen'];
				break;
			//主题锁定
			case 'Lock':
				Auth(4);
				$DB->query("UPDATE " . $Prefix . "topics SET IsLocked = :IsLocked WHERE ID=:ID", array(
					"ID" => $ID,
					"IsLocked" => $TopicInfo['IsLocked'] ? 0 : 1
				));
				$Message = $TopicInfo['IsLocked'] ? $Lang['Lock'] : $Lang['Unlock'];
				break;
			//删除标签
			case 'DeleteTag':
				Auth(4, $TopicInfo['UserID'], true);
				$TagName = Request('Post', 'TagName');
				if ((count(explode('|', $TopicInfo['Tags'])) - 1) >= 1 && $DB->query("DELETE FROM `" . $Prefix . "posttags` 
					WHERE TopicID = ? AND TagID = (SELECT ID FROM `" . $Prefix . "tags` WHERE Name = ?)", array(
					$ID,
					$TagName
				))) {
					// 更新标签统计数据
					$DB->query("UPDATE `" . $Prefix . "tags` SET TotalPosts=TotalPosts-1 WHERE `Name`=?", array(
						$TagName
					));
					// 更新Topics表里的Tags缓存
					$DB->query("UPDATE `" . $Prefix . "topics` SET Tags=? WHERE `ID`=?", array(
						implode('|', TagsDiff(explode('|', $TopicInfo['Tags']), array(
							$TagName
						))),
						$ID
					));
					$Message = 'Success';
				} else {
					AlertMsg('Bad Request', 'Bad Request');
				}
				break;
			//添加标签
			case 'AddTag':
				Auth(4, $TopicInfo['UserID'], true);
				$TagName = TagsDiff(array(
					Request('Post', 'TagName')
				), array());
				if ($TagName && !in_array($TagName[0], explode('|', $TopicInfo['Tags'])) && (count(explode('|', $TopicInfo['Tags'])) + 1) <= $Config["MaxTagsNum"]) {
					$TagName   = $TagName[0];
					$TagsExist = $DB->row("SELECT ID,Name FROM `" . $Prefix . "tags` WHERE `Name` = ?", array(
						$TagName
					));
					if (!$TagsExist) {
						$DB->query("INSERT INTO `" . $Prefix . "tags` 
							(`ID`, `Name`,`Followers`,`Icon`,`Description`, `IsEnabled`, `TotalPosts`, `MostRecentPostTime`, `DateCreated`) 
							VALUES (?,?,?,?,?,?,?,?,?)", array(
							null,
							htmlspecialchars(trim($TagName)),
							0,
							0,
							null,
							1,
							1,
							$TimeStamp,
							$TimeStamp
						));
						$TagID = $DB->lastInsertId();
						if ($TagID) {
							$DB->query("INSERT INTO `" . $Prefix . "posttags` 
								(`TagID`, `TopicID`, `PostID`) 
								VALUES (" . $TagID . ", " . $ID . ", (SELECT ID FROM `" . $Prefix . "posts` WHERE TopicID = " . $ID . " AND IsTopic = 1 LIMIT 1))");
							//更新全站统计数据
							$NewConfig = array(
								"NumTags" => $Config["NumTags"] + 1
							);
							UpdateConfig($NewConfig);
						}
					} else {
						if ($DB->query("INSERT INTO `" . $Prefix . "posttags` 
							(`TagID`, `TopicID`, `PostID`) 
							VALUES (" . $TagsExist['ID'] . ", " . $ID . ", (SELECT ID FROM `" . $Prefix . "posts` WHERE TopicID = " . $ID . " AND IsTopic = 1 LIMIT 1))")) {
							// 更新标签统计数据
							$DB->query("UPDATE `" . $Prefix . "tags` SET TotalPosts=TotalPosts+1 WHERE `Name`=?", array(
								$TagName
							));
						}
					}
					$DB->query("UPDATE `" . $Prefix . "topics` SET Tags=? WHERE `ID`=?", array(
						implode('|', $TopicInfo['Tags'] ? array_merge(explode('|', $TopicInfo['Tags']), array(
							$TagName
						)) : array(
							$TagName
						)),
						$ID
					));
					$Message = 'Success';
				} else {
					AlertMsg('Bad Request', 'Bad Request');
				}
				break;
			default:
				AlertMsg('Bad Request', 'Bad Request');
				break;
		}
		if ($MCache) {
			//清理首页内存缓存
			$MCache->delete(MemCachePrefix . 'Homepage');
			//清理主题缓存
			$MCache->delete(MemCachePrefix . 'Topic_' . $ID);
		}
		break;
	
	//Post
	case 2:
		$PostInfo = $DB->row("SELECT * FROM " . $Prefix . "posts force index(PRI) WHERE ID=:ID", array(
			"ID" => $ID
		));
		if (!$PostInfo) {
			AlertMsg('Post Not Found', 'Post Not Found');
		}
		switch ($Action) {
			case 'Delete':
				Auth(4);
				$DB->query('DELETE FROM `' . $Prefix . 'posts` WHERE ID=?', array(
					$ID
				));
				$DB->query('DELETE FROM `' . $Prefix . 'notifications` WHERE PostID=?', array(
					$ID
				));
				//更新全站统计数据
				$NewConfig = array(
					"NumPosts" => $Config["NumPosts"] - 1
				);
				UpdateConfig($NewConfig);
				//更新主题统计数据
				$DB->query("UPDATE `" . $Prefix . "topics` SET Replies=Replies-1 WHERE `ID`=?", array(
					$PostInfo['TopicID']
				));
				//更新用户自身统计数据
				$DB->query("UPDATE `" . $Prefix . "users` SET Replies=Replies-1 WHERE `ID`=?", array(
					$PostInfo['UserID']
				));
				$Message = $Lang['Permanently_Deleted'];
				break;
			//编辑帖子
			case 'Edit':
				//Auth(4, $PostInfo['UserID'], true);
				Auth(4);
				$Content = XssEscape(Request('Post', 'Content', $PostInfo['Content']));
				if ($Content == $PostInfo['Content'])
					AlertMsg($Lang['Do_Not_Modify'], $Lang['Do_Not_Modify']);
				if ($DB->query("UPDATE " . $Prefix . "posts SET Content = :Content WHERE ID=:ID", array(
					'ID' => $ID,
					'Content' => $Content
				))) {
					//标记附件所对应的帖子标签
					$DB->query("UPDATE `" . $Prefix . "upload` SET PostID=? WHERE `PostID`=0 and `UserName`=?", array(
						$ID,
						$CurUserName
					));
					$Message = $Lang['Edited'];
				} else {
					AlertMsg($Lang['Failure_Edit'], $Lang['Failure_Edit']);
				}
				break;
			default:
				AlertMsg('Bad Request', 'Bad Request');
				break;
		}
		break;
	
	//User
	case 3:
		$UserInfo = $DB->row("SELECT * FROM " . $Prefix . "users force index(PRI) WHERE ID=:ID", array(
			"ID" => $ID
		));
		switch ($Action) {
			case 'Delete':
				Auth(4);
				# code...
				break;
			//屏蔽用户
			case 'Block':
				Auth(4);
				$NewUserAccountStatus = $UserInfo['UserAccountStatus'] ? 0 : 1;
				if (UpdateUserInfo(array(
					'UserAccountStatus' => $NewUserAccountStatus
				), $ID)) {
					$Message = $NewUserAccountStatus ? $Lang['Block_User'] : $Lang['Unblock_User'];
				}
				break;
			//重置头像
			case 'ResetAvatar':
				Auth(4, $ID);
				if (extension_loaded('gd')) {
					require(__DIR__ . "/includes/MaterialDesign.Avatars.class.php");
					$Avatar = new MDAvtars(mb_substr($UserInfo['UserName'], 0, 1, "UTF-8"), 256);
					$Avatar->Save('upload/avatar/large/' . $ID . '.png', 256);
					$Avatar->Save('upload/avatar/middle/' . $ID . '.png', 48);
					$Avatar->Save('upload/avatar/small/' . $ID . '.png', 24);
					$Avatar->Free();
					$Message = $Lang['Reset_Avatar_Successfully'];
				} else {
					$Message = $Lang['Reset_Avatar_Successfully']; //Failure
				}
				
				break;
			default:
				AlertMsg('Bad Request', 'Bad Request');
				break;
		}
		break;
	//Follow or Favorite
	case 4:
		Auth(1);
		$Action      = intval($Action);
		//检查主题/标签/用户/帖子是否存在
		$IsFavorite  = $DB->single("SELECT ID FROM " . $Prefix . "favorites WHERE UserID=:UserID and Type=:Type and FavoriteID=:FavoriteID", array(
			'UserID' => $CurUserID,
			'Type' => $Action,
			'FavoriteID' => $ID
		)); //添加索引
		$MessageType = false; //false表示收藏，true表示关注
		$SQLAction   = intval($IsFavorite) ? '-1' : '+1';
		switch ($Action) {
			//1:Topic 2:Tag 3:User 4:Post 5:Blog
			case 1: //Topic
				$Title = $DB->single("SELECT Topic FROM " . $Prefix . "topics WHERE ID=:FavoriteID", array(
					'FavoriteID' => $ID
				));
				break;
			case 2: //Tag
				$Title       = $DB->single("SELECT Name FROM " . $Prefix . "tags WHERE ID=:FavoriteID", array(
					'FavoriteID' => $ID
				));
				$MessageType = true;
				break;
			case 3: //User
				$Title       = $DB->single("SELECT UserName FROM " . $Prefix . "users WHERE ID=:FavoriteID", array(
					'FavoriteID' => $ID
				));
				$MessageType = true;
				break;
			case 4: //Post
				$Title = $DB->single("SELECT Subject FROM " . $Prefix . "posts WHERE ID=:FavoriteID", array(
					'FavoriteID' => $ID
				));
				break;
			case 5: //Blog
				$Title = $DB->single("SELECT Subject FROM " . $Prefix . "blogs WHERE ID=:FavoriteID and ParentID=0", array(
					'FavoriteID' => $ID
				));
				break;
			default:
				AlertMsg('Bad Request', 'Bad Request');
				break;
		}
		if ($Title) {
			if (!$IsFavorite) {
				if (!$DB->query('INSERT INTO `' . $Prefix . 'favorites`(`ID`, `UserID`, `Category`, `Title`, `Type`, `FavoriteID`, `DateCreated`, `Description`) VALUES (?,?,?,?,?,?,?,?)', array(
					null,
					$CurUserID,
					'',
					$Title,
					$Action,
					$ID,
					$TimeStamp,
					''
				)))
					AlertMsg('Unknown Error', 'Unknown Error');
			} else {
				$DB->query('DELETE FROM `' . $Prefix . 'favorites` WHERE `UserID`=? and `Type`=? and `FavoriteID`=?', array(
					$CurUserID,
					$Action,
					$ID
				));
			}
			switch ($Action) {
				//1:Topic 2:Tag 3:User 4:Post 5:Blog
				case 1: //Topic
					$DB->query('UPDATE ' . $Prefix . 'topics SET Favorites = Favorites' . $SQLAction . ' WHERE ID=:FavoriteID', array(
						'FavoriteID' => $ID
					));
					$DB->query('UPDATE `' . $Prefix . 'users` SET NumFavTopics=NumFavTopics' . $SQLAction . ' WHERE `ID`=?', array(
						$CurUserID
					));
					break;
				case 2: //Tag
					$DB->query('UPDATE ' . $Prefix . 'tags SET Followers = Followers' . $SQLAction . ' WHERE ID=:FavoriteID', array(
						'FavoriteID' => $ID
					));
					$DB->query('UPDATE `' . $Prefix . 'users` SET NumFavTags=NumFavTags' . $SQLAction . ' WHERE `ID`=?', array(
						$CurUserID
					));
					break;
				case 3: //User
					$DB->query('UPDATE ' . $Prefix . 'users SET Followers = Followers' . $SQLAction . ' WHERE ID=:FavoriteID', array(
						'FavoriteID' => $ID
					));
					$DB->query('UPDATE `' . $Prefix . 'users` SET NumFavUsers=NumFavUsers' . $SQLAction . ' WHERE `ID`=?', array(
						$CurUserID
					));
					break;
				case 4: //Post
					break;
				case 5: //Blog
					break;
				default:
					AlertMsg('Bad Request', 'Bad Request');
					break;
			}
			//清理内存缓存
			if ($MCache) {
				$MCache->delete(MemCachePrefix . 'UserInfo_' . $CurUserID);
			}
			$Message = $IsFavorite ? ($MessageType ? $Lang['Follow'] : $Lang['Collect']) : ($MessageType ? $Lang['Unfollow'] : $Lang['Unsubscribe']);
			//$FavoriteID = $DB->lastInsertId();
		} else {
			AlertMsg('404 Not Found', '404 Not Found', 404);
		}
		break;
	//Tag
	case 5:
		$TagInfo = $DB->row("SELECT * FROM " . $Prefix . "tags WHERE ID=:ID", array(
			"ID" => $ID
		));
		if (!$TagInfo) {
			AlertMsg('Tag Not Found', 'Tag Not Found');
		}
		switch ($Action) {
			// 修改标签描述
			case 'EditDescription':
				Auth(3);
				$Content = CharCV(Request('Post', 'Content', $TagInfo['Description']));
				if ($Content == $TagInfo['Description'])
					AlertMsg($Lang['Do_Not_Modify'], $Lang['Do_Not_Modify']);
				if ($DB->query('UPDATE ' . $Prefix . 'tags SET Description = :Content WHERE ID=:TagID', array(
					'TagID' => $ID,
					'Content' => $Content
				))) {
					$Message = $Lang['Edited'];
				} else {
					AlertMsg($Lang['Failure_Edit'], $Lang['Failure_Edit']);
				}
				break;
			// 修改标签图标
			case 'UploadIcon':
				Auth(3);
				if ($_FILES['TagIcon']['size'] && $_FILES['TagIcon']['size'] < 1048576) {
					require(__DIR__ . "/includes/ImageResize.class.php");
					$UploadIcon    = new ImageResize('PostField', 'TagIcon');
					$LUploadResult = $UploadIcon->Resize(256, 'upload/tag/large/' . $ID . '.png', 80);
					$MUploadResult = $UploadIcon->Resize(48, 'upload/tag/middle/' . $ID . '.png', 90);
					$SUploadResult = $UploadIcon->Resize(24, 'upload/tag/small/' . $ID . '.png', 90);
					
					if ($LUploadResult && $MUploadResult && $SUploadResult) {
						$SetTagIconStatus = $TagInfo['Icon'] == 0 ? $DB->query('UPDATE ' . $Prefix . 'tags SET Icon = 1 WHERE ID=:TagID', array(
							'TagID' => $ID
						)) : true;
						$Message          = $SetTagIconStatus ? $Lang['Icon_Upload_Success'] : $Lang['Icon_Upload_Failure'];
					} else {
						$Message = $Lang['Icon_Upload_Failure'];
					}
				} else {
					$Message = $Lang['Icon_Is_Oversize'];
				}
				break;
			// 禁用/启用该标签
			case 'SwitchStatus':
				Auth(4);
				if ($DB->query('UPDATE ' . $Prefix . 'tags SET IsEnabled = :IsEnabled WHERE ID=:TagID', array(
					'TagID' => $ID,
					'IsEnabled' => $TagInfo['IsEnabled'] ? 0 : 1 //Bool -> Int
				))) {
					//更新话题统计数据
					$NewConfig = array(
						"NumTags" => $Config["NumTags"] + ($TagInfo['IsEnabled'] ? -1 : 1)
					);
					UpdateConfig($NewConfig);
					$Message = $TagInfo['IsEnabled'] ? $Lang['Enable_Tag'] : $Lang['Disable_Tag'];
				} else {
					AlertMsg('Bad Request', 'Bad Request');
				}
				break;
			default:
				AlertMsg('Bad Request', 'Bad Request');
				break;
				
		}
		break;
	//Error
	default:
		AlertMsg('Bad Request', 'Bad Request');
		break;
}
$PageTitle   = 'Manage';
$ContentFile = $TemplatePath . 'manage.php';
include($TemplatePath . 'layout.php');