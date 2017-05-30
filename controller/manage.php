<?php
require(LanguagePath . 'manage.php');
SetStyle('api', 'API');

$ID     = intval(Request('Post', 'ID', 0));
$Type   = intval(Request('Post', 'Type', 0));
$Action = Request('Post', 'Action', false);

$TypeList         = array(
	1 => 'topic',
	2 => 'post',
	3 => 'user',
	4 => 'favorite',
	5 => 'tag'
);
//FavoriteType: 1:Topic 2:Tag 3:User 4:Post 5:Blog
$FavoriteTypeList = array(
	1 => 'Topic',
	2 => 'Tag',
	3 => 'User',
	4 => 'Post',
	5 => 'Blog'
);

$Manage = new Manage($ID, $Action);
if (empty($TypeList[$Type]) || ($Type == 4 && empty($FavoriteTypeList[$Action]))) {
	AlertMsg('Action or Type error. Bad Request', 'Action or Type error. Bad Request');
}
$ManageMethod = $TypeList[$Type] . ($Type == 4 ? $FavoriteTypeList[$Action] : $Action);
if (method_exists($Manage, $ManageMethod)) {
	$ManageInfo = $Manage->getManageInfo($TypeList[$Type]);
	if ($Type != 4 && empty($ManageInfo)) {
		AlertMsg('Resource Not Found', 'Resource Not Found');
	}
	$Manage->$ManageMethod($ManageInfo);
	$Message = $Manage->message;
}
/**
 * @property MemcacheMod mCache
 * @property DB db
 */
class Manage
{
	private $id;
	private $action;
	private $db;
	private $lang;
	private $config;
	private $mCache;
	public $message = '';

	public function __construct($id, $action)
	{
		global $DB, $Lang, $Config, $MCache;
		$this->id     = $id;
		$this->action = $action;
		$this->db     = $DB;
		$this->lang   = $Lang;
		$this->config = $Config;
		$this->mCache = $MCache;
	}

	// 获取要管理的项目的信息
	public function getManageInfo($manageType)
	{
		global $CurUserID;
		switch ($manageType) {
			case 'topic':
				return $this->db->row("SELECT * FROM " . PREFIX . "topics force index(PRI) WHERE ID=:ID", array(
					"ID" => $this->id
				));
				break;
			case 'post':
				return $this->db->row("SELECT * FROM " . PREFIX . "posts force index(PRI) WHERE ID=:ID", array(
					"ID" => $this->id
				));
				break;
			case 'user':
				return $this->db->row("SELECT * FROM " . PREFIX . "users force index(PRI) WHERE ID=:ID", array(
					"ID" => $this->id
				));
				break;
			case 'favorite':
				return $this->db->single("SELECT ID FROM " . PREFIX . "favorites WHERE UserID=:UserID and Type=:Type and FavoriteID=:FavoriteID", array(
					'UserID' => $CurUserID,
					'Type' => $this->action,
					'FavoriteID' => $this->id
				));
				break;
			case 'tag':
				return $this->db->row("SELECT * FROM " . PREFIX . "tags WHERE ID=:ID", array(
					"ID" => $this->id
				));
				break;
			default:
				AlertMsg('Bad Request', 'Bad Request');
				return [];
				break;
		}
	}

	//清理主题缓存
	private function refreshTopicCache()
	{
		if ($this->mCache) {
			//清理首页内存缓存
			$this->mCache->delete(MemCachePrefix . 'Homepage');
			//清理主题缓存
			$this->mCache->delete(MemCachePrefix . 'Topic_' . $this->id);
		}
	}

	// 删帖时清理附件
	private function deleteUpload($uploadRecordList)
	{
		foreach ($uploadRecordList as $uploadRecord) {
			$numberDuplicateFiles = $this->db->single('SELECT count(*) FROM ' . PREFIX . 'upload 
					WHERE 
						FileSize = ? AND
						MD5 = ? AND 
						SHA1 = ?
						', array(
				$uploadRecord['FileSize'],
				$uploadRecord['MD5'],
				$uploadRecord['SHA1']
			));
			$rootPath = $_SERVER['DOCUMENT_ROOT'];
			if (substr($uploadRecord['FilePath'], 0, 1) != '/') {
				$uploadRecord['FilePath'] = '/' . $uploadRecord['FilePath'];
			}
			if ($numberDuplicateFiles <= 1){
				unlink($rootPath . $uploadRecord['FilePath']);
			}
		}
		$UploadIDs = ArrayColumn($uploadRecordList, 'ID');
		if ($UploadIDs) {
			$this->db->query('DELETE FROM ' . PREFIX . 'upload 
				WHERE ID IN (?)', $UploadIDs);
		}
	}

	// 将主题移入回收站
	public function topicDelete($TopicInfo)
	{
		Auth(4);
		if ($TopicInfo['IsDel'] == 0) {
			$this->db->query("UPDATE " . PREFIX . "topics SET IsDel = 1 WHERE ID=:ID", array(
				"ID" => $this->id
			));
			//更新全站统计数据
			$NewConfig = array(
				"NumTopics" => $this->config["NumTopics"] - 1
			);
			UpdateConfig($NewConfig);
			//更新用户自身统计数据
			$this->db->query("UPDATE `" . PREFIX . "users` SET Topics=Topics-1 WHERE `ID`=?", array(
				$TopicInfo['UserID']
			));
			//更新标签统计
			if ($TopicInfo['Tags']) {
				$this->db->query("UPDATE `" . PREFIX . "tags` SET TotalPosts=TotalPosts-1 WHERE `Name` in (?)", explode('|', $TopicInfo['Tags']));
			}
			$this->message = $this->lang['Deleted'];
		} else {
			AlertMsg('Bad Request', $this->lang['Deleted']);
		}
		$this->refreshTopicCache();
	}

	// 从回收站恢复主题
	public function topicRecover($TopicInfo)
	{
		Auth(4);
		if ($TopicInfo['IsDel'] == 1) {
			$this->db->query("UPDATE " . PREFIX . "topics SET IsDel = 0 WHERE ID=:ID", array(
				"ID" => $this->id
			));
			//更新全站统计数据
			$NewConfig = array(
				"NumTopics" => $this->config["NumTopics"] + 1
			);
			UpdateConfig($NewConfig);
			//更新用户自身统计数据
			$this->db->query("UPDATE `" . PREFIX . "users` SET Topics=Topics+1 WHERE `ID`=?", array(
				$TopicInfo['UserID']
			));
			//更新标签统计
			if ($TopicInfo['Tags']) {
				$this->db->query("UPDATE `" . PREFIX . "tags` SET TotalPosts=TotalPosts+1 WHERE `Name` in (?)", explode('|', $TopicInfo['Tags']));
			}
			$this->message = $this->lang['Recovered'];
		} else {
			AlertMsg('Bad Request', $this->lang['Failure_Recovery']);
		}
		$this->refreshTopicCache();
	}

	// 永久删除主题
	public function topicPermanentlyDelete($TopicInfo)
	{
		Auth(5);
		if ($TopicInfo['IsDel'] == 1) {
			//先删除附件，不然删了Posts就找不到附件了
			$this->deleteUpload($this->db->query("SELECT * FROM `" . PREFIX . "upload` 
				WHERE `PostID` IN (SELECT ID FROM `" . PREFIX . "posts` WHERE TopicID = ?)",
				array(
					$this->id
				)));
			$this->db->query('DELETE FROM `' . PREFIX . 'posttags` WHERE TopicID = ?', array(
				$this->id
			));
			$this->db->query('DELETE FROM `' . PREFIX . 'posts` WHERE TopicID = ?', array(
				$this->id
			));
			$this->db->query('DELETE FROM `' . PREFIX . 'topics` WHERE ID = ?', array(
				$this->id
			));
			$this->db->query('DELETE FROM `' . PREFIX . 'notifications` WHERE TopicID = ?', array(
				$this->id
			));
			$this->message = $this->lang['Permanently_Deleted'];
		} else {
			AlertMsg('Bad Request', $this->lang['Failure_Permanent_Deletion']);
		}
		$this->refreshTopicCache();
	}

	// 主题下沉
	public function topicSink($TopicInfo)
	{
		Auth(4);
		$this->db->query("UPDATE " . PREFIX . "topics SET LastTime = LastTime-604800 WHERE ID=:ID", array(
			"ID" => $this->id
		));
		$this->message = $this->lang['Sunk'];
		$this->refreshTopicCache();
	}

	// 主题上浮
	public function topicRise($TopicInfo)
	{
		Auth(4);
		$this->db->query("UPDATE " . PREFIX . "topics SET LastTime = LastTime+604800 WHERE ID=:ID", array(
			"ID" => $this->id
		));
		$this->message = $this->lang['Risen'];
		$this->refreshTopicCache();
	}

	//锁定 / 解锁主题
	public function topicLock($TopicInfo)
	{
		Auth(4);
		$this->db->query("UPDATE " . PREFIX . "topics SET IsLocked = :IsLocked WHERE ID=:ID", array(
			"ID" => $this->id,
			"IsLocked" => $TopicInfo['IsLocked'] ? 0 : 1
		));
		$this->message = $TopicInfo['IsLocked'] ? $this->lang['Lock'] : $this->lang['Unlock'];
		$this->refreshTopicCache();
	}

	//给帖子删除话题
	public function topicDeleteTag($TopicInfo)
	{
		Auth(4, $TopicInfo['UserID'], true);
		$TagName = Request('Post', 'TagName');
		if ((count(explode('|', $TopicInfo['Tags'])) - 1) >= 1 && $this->db->query("DELETE FROM `" . PREFIX . "posttags` 
					WHERE TopicID = ? AND TagID = (SELECT ID FROM `" . PREFIX . "tags` WHERE Name = ?)", array(
				$this->id,
				$TagName
			))) {
			// 更新标签统计数据
			$this->db->query("UPDATE `" . PREFIX . "tags` SET TotalPosts=TotalPosts-1 WHERE `Name`=?", array(
				$TagName
			));
			// 更新Topics表里的Tags缓存
			$this->db->query("UPDATE `" . PREFIX . "topics` SET Tags=? WHERE `ID`=?", array(
				implode('|', TagsDiff(explode('|', $TopicInfo['Tags']), array(
					$TagName
				))),
				$this->id
			));
			$this->message = 'Success';
		} else {
			AlertMsg('Bad Request', 'Bad Request');
		}
		$this->refreshTopicCache();
	}

	// 给主题添加话题
	public function topicAddTag($TopicInfo)
	{
		global $TimeStamp;
		Auth(4, $TopicInfo['UserID'], true);
		$TagName = TagsDiff(array(
			Request('Post', 'TagName')
		), array());
		if ($TagName && !in_array($TagName[0], explode('|', $TopicInfo['Tags'])) && (count(explode('|', $TopicInfo['Tags'])) + 1) <= $this->config["MaxTagsNum"]) {
			$TagName   = $TagName[0];
			$TagsExist = $this->db->row("SELECT ID,Name FROM `" . PREFIX . "tags` WHERE `Name` = ?", array(
				$TagName
			));
			if (!$TagsExist) {
				$this->db->query("INSERT INTO `" . PREFIX . "tags` 
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
				$TagID = $this->db->lastInsertId();
				if ($TagID) {
					$this->db->query("INSERT INTO `" . PREFIX . "posttags` 
								(`TagID`, `TopicID`, `PostID`) 
								VALUES (" . $TagID . ", " . $this->id . ", (SELECT ID FROM `" . PREFIX . "posts` WHERE TopicID = " . $this->id . " AND IsTopic = 1 LIMIT 1))");
					//更新全站统计数据
					$NewConfig = array(
						"NumTags" => $this->config["NumTags"] + 1
					);
					UpdateConfig($NewConfig);
				}
			} else {
				if ($this->db->query("INSERT INTO `" . PREFIX . "posttags` 
							(`TagID`, `TopicID`, `PostID`) 
							VALUES (" . $TagsExist['ID'] . ", " . $this->id . ", (SELECT ID FROM `" . PREFIX . "posts` WHERE TopicID = " . $this->id . " AND IsTopic = 1 LIMIT 1))")) {
					// 更新标签统计数据
					$this->db->query("UPDATE `" . PREFIX . "tags` SET TotalPosts=TotalPosts+1 WHERE `Name`=?", array(
						$TagName
					));
				}
			}
			$this->db->query("UPDATE `" . PREFIX . "topics` SET Tags=? WHERE `ID`=?", array(
				implode('|', $TopicInfo['Tags'] ? array_merge(explode('|', $TopicInfo['Tags']), array(
					$TagName
				)) : array(
					$TagName
				)),
				$this->id
			));
			$this->message = 'Success';
		} else {
			AlertMsg('Bad Request', 'Bad Request');
		}
		$this->refreshTopicCache();
	}

	// 删除帖子
	public function postDelete($PostInfo)
	{
		Auth(4);
		$this->db->query('DELETE FROM `' . PREFIX . 'posts` WHERE ID=?', array(
			$this->id
		));
		$this->db->query('DELETE FROM `' . PREFIX . 'notifications` WHERE PostID=?', array(
			$this->id
		));
		//更新全站统计数据
		$NewConfig = array(
			"NumPosts" => $this->config["NumPosts"] - 1
		);
		UpdateConfig($NewConfig);
		//更新主题统计数据
		$this->db->query("UPDATE `" . PREFIX . "topics` SET Replies=Replies-1 WHERE `ID`=?", array(
			$PostInfo['TopicID']
		));
		//更新用户自身统计数据
		$this->db->query("UPDATE `" . PREFIX . "users` SET Replies=Replies-1 WHERE `ID`=?", array(
			$PostInfo['UserID']
		));
		//删除附件
		$this->deleteUpload($this->db->query("SELECT * FROM `" . PREFIX . "upload` WHERE `PostID` = ?", array(
			$PostInfo['ID']
		)));
		$this->message = $this->lang['Permanently_Deleted'];
	}

	// 编辑帖子
	public function postEdit($PostInfo)
	{
		global $CurUserRole, $CurUserName, $TimeStamp;
		if ($this->config['AllowEditing'] === 'true') {
			Auth(4, $PostInfo['UserID'], true);
		} else {
			Auth(4);
		}
		$Content             = XssEscape(Request('Post', 'Content', $PostInfo['Content']));
		// 内容过滤系统
		$ContentFilterResult = Filter($Content);
		$GagTime             = $CurUserRole < 3 ? $ContentFilterResult['GagTime'] : 0;
		$Prohibited          = $ContentFilterResult['Prohibited'];
		if ($Prohibited) {
			if ($GagTime) {
				//禁言用户 $GagTime 秒
				UpdateUserInfo(array(
					"LastPostTime" => $TimeStamp + $GagTime
				));
			}
			AlertMsg($this->lang['Prohibited_Content'], $this->lang['Prohibited_Content']);
			return;
		}
		$Content = $ContentFilterResult['Content'];

		if ($Content == $PostInfo['Content'])
			AlertMsg($this->lang['Do_Not_Modify'], $this->lang['Do_Not_Modify']);
		if ($this->db->query("UPDATE " . PREFIX . "posts SET Content = :Content WHERE ID=:ID", array(
			'ID' => $this->id,
			'Content' => $Content
		))) {
			//标记附件所对应的帖子标签
			$this->db->query("UPDATE `" . PREFIX . "upload` SET PostID=? WHERE `PostID`=0 and `UserName`=?", array(
				$this->id,
				$CurUserName
			));
			$this->message = $this->lang['Edited'];
		} else {
			AlertMsg($this->lang['Failure_Edit'], $this->lang['Failure_Edit']);
		}
	}

	//TODO: 删除用户功能
	public function userDelete($UserInfo)
	{
		Auth(4);
		return;
	}

	// 禁言/解禁用户
	public function userBlock($UserInfo)
	{
		Auth(4);
		$NewUserAccountStatus = $UserInfo['UserAccountStatus'] ? 0 : 1;
		if (UpdateUserInfo(array(
			'UserAccountStatus' => $NewUserAccountStatus
		), $this->id)) {
			$this->message = $NewUserAccountStatus ? $this->lang['Block_User'] : $this->lang['Unblock_User'];
		}
	}

	// 重置用户头像
	public function userResetAvatar($UserInfo)
	{
		Auth(4, $this->id);
		if (extension_loaded('gd')) {
			require(LibraryPath . "MaterialDesign.Avatars.class.php");
			$Avatar = new MDAvtars(mb_substr($UserInfo['UserName'], 0, 1, "UTF-8"), 256);
			$Avatar->Save(__DIR__ . '/../upload/avatar/large/' . $this->id . '.png', 256);
			$Avatar->Save(__DIR__ . '/../upload/avatar/middle/' . $this->id . '.png', 48);
			$Avatar->Save(__DIR__ . '/../upload/avatar/small/' . $this->id . '.png', 24);
			$Avatar->Free();
			$this->message = $this->lang['Reset_Avatar_Successfully'];
		} else {
			$this->message = $this->lang['Reset_Avatar_Successfully']; //Failure
		}
	}


	public function favoriteTopic($IsFavorite)
	{
		$this->favorite($IsFavorite, 'Topic');
	}

	public function favoriteTag($IsFavorite)
	{
		$this->favorite($IsFavorite, 'Tag');
	}

	public function favoriteUser($IsFavorite)
	{
		$this->favorite($IsFavorite, 'User');
	}

	public function favoritePost($IsFavorite)
	{
		$this->favorite($IsFavorite, 'Post');
	}

	public function favoriteBlog($IsFavorite)
	{
		$this->favorite($IsFavorite, 'Blog');
	}

	//关注 / 收藏功能
	private function favorite($IsFavorite, $FavoriteType)
	{
		global $TimeStamp, $CurUserID;
		Auth(1);
		//$IsFavorite: 检查主题/标签/用户/帖子是否存在
		$MessageType = false; //false表示收藏，true表示关注
		$SQLAction   = intval($IsFavorite) ? '-1' : '+1';
		switch ($FavoriteType) {
			//1:Topic 2:Tag 3:User 4:Post 5:Blog
			case 'Topic': //Topic
				$Title = $this->db->single("SELECT Topic FROM " . PREFIX . "topics WHERE ID=:FavoriteID", array(
					'FavoriteID' => $this->id
				));
				break;
			case 'Tag': //Tag
				$Title       = $this->db->single("SELECT Name FROM " . PREFIX . "tags WHERE ID=:FavoriteID", array(
					'FavoriteID' => $this->id
				));
				$MessageType = true;
				break;
			case 'User': //User
				$Title       = $this->db->single("SELECT UserName FROM " . PREFIX . "users WHERE ID=:FavoriteID", array(
					'FavoriteID' => $this->id
				));
				$MessageType = true;
				break;
			case 'Post': //Post
				$Title = $this->db->single("SELECT Subject FROM " . PREFIX . "posts WHERE ID=:FavoriteID", array(
					'FavoriteID' => $this->id
				));
				break;
			case 'Blog': //Blog
				$Title = $this->db->single("SELECT Subject FROM " . PREFIX . "blogs WHERE ID=:FavoriteID and ParentID=0", array(
					'FavoriteID' => $this->id
				));
				break;
			default:
				AlertMsg('Bad Request', 'Bad Request');
				$Title = '';
				break;
		}
		if ($Title) {
			if (!$IsFavorite) {
				if (!$this->db->query('INSERT INTO `' . PREFIX . 'favorites`(`ID`, `UserID`, `Category`, `Title`, `Type`, `FavoriteID`, `DateCreated`, `Description`) VALUES (?,?,?,?,?,?,?,?)', array(
					null,
					$CurUserID,
					'',
					$Title,
					$this->action,
					$this->id,
					$TimeStamp,
					''
				)))
					AlertMsg('Unknown Error', 'Unknown Error');
			} else {
				$this->db->query('DELETE FROM `' . PREFIX . 'favorites` WHERE `UserID`=? and `Type`=? and `FavoriteID`=?', array(
					$CurUserID,
					$this->action,
					$this->id
				));
			}
			switch ($FavoriteType) {
				//1:Topic 2:Tag 3:User 4:Post 5:Blog
				case 'Topic': //Topic
					$this->db->query('UPDATE ' . PREFIX . 'topics SET Favorites = Favorites' . $SQLAction . ' WHERE ID=:FavoriteID', array(
						'FavoriteID' => $this->id
					));
					$this->db->query('UPDATE `' . PREFIX . 'users` SET NumFavTopics=NumFavTopics' . $SQLAction . ' WHERE `ID`=?', array(
						$CurUserID
					));
					break;
				case 'Tag': //Tag
					$this->db->query('UPDATE ' . PREFIX . 'tags SET Followers = Followers' . $SQLAction . ' WHERE ID=:FavoriteID', array(
						'FavoriteID' => $this->id
					));
					$this->db->query('UPDATE `' . PREFIX . 'users` SET NumFavTags=NumFavTags' . $SQLAction . ' WHERE `ID`=?', array(
						$CurUserID
					));
					break;
				case 'User': //User
					$this->db->query('UPDATE ' . PREFIX . 'users SET Followers = Followers' . $SQLAction . ' WHERE ID=:FavoriteID', array(
						'FavoriteID' => $this->id
					));
					$this->db->query('UPDATE `' . PREFIX . 'users` SET NumFavUsers=NumFavUsers' . $SQLAction . ' WHERE `ID`=?', array(
						$CurUserID
					));
					break;
				case 'Post': //Post
					break;
				case 'Blog': //Blog
					break;
				default:
					AlertMsg('Bad Request', 'Bad Request');
					break;
			}
			//清理内存缓存
			if ($this->mCache) {
				$this->mCache->delete(MemCachePrefix . 'UserInfo_' . $CurUserID);
			}
			$this->message = $IsFavorite ? ($MessageType ? $this->lang['Follow'] : $this->lang['Collect']) : ($MessageType ? $this->lang['Unfollow'] : $this->lang['Unsubscribe']);
			//$FavoriteID = $this->db->lastInsertId();
		} else {
			AlertMsg('404 Not Found', '404 Not Found', 404);
		}
	}

	// 修改话题描述
	public function tagEditDescription($TagInfo)
	{
		Auth(3);
		$Content = CharCV(Request('Post', 'Content', $TagInfo['Description']));
		if ($Content == $TagInfo['Description'])
			AlertMsg($this->lang['Do_Not_Modify'], $this->lang['Do_Not_Modify']);
		if ($this->db->query('UPDATE ' . PREFIX . 'tags SET Description = :Content WHERE ID=:TagID', array(
			'TagID' => $this->id,
			'Content' => $Content
		))) {
			$this->message = $this->lang['Edited'];
		} else {
			AlertMsg($this->lang['Failure_Edit'], $this->lang['Failure_Edit']);
		}
	}

	// 上传话题图标
	public function tagUploadIcon($TagInfo)
	{
		Auth(3);
		if ($_FILES['TagIcon']['size'] && $_FILES['TagIcon']['size'] < 1048576) {
			require(LibraryPath . "ImageResize.class.php");
			$UploadIcon    = new ImageResize('PostField', 'TagIcon');
			$LUploadResult = $UploadIcon->Resize(256, 'upload/tag/large/' . $this->id . '.png', 80);
			$MUploadResult = $UploadIcon->Resize(48, 'upload/tag/middle/' . $this->id . '.png', 90);
			$SUploadResult = $UploadIcon->Resize(24, 'upload/tag/small/' . $this->id . '.png', 90);

			if ($LUploadResult && $MUploadResult && $SUploadResult) {
				$SetTagIconStatus = $TagInfo['Icon'] == 0 ? $this->db->query('UPDATE ' . PREFIX . 'tags SET Icon = 1 WHERE ID=:TagID', array(
					'TagID' => $this->id
				)) : true;
				$this->message    = $SetTagIconStatus ? $this->lang['Icon_Upload_Success'] : $this->lang['Icon_Upload_Failure'];
			} else {
				$this->message = $this->lang['Icon_Upload_Failure'];
			}
		} else {
			$this->message = $this->lang['Icon_Is_Oversize'];
		}
	}

	// 禁用/启用该标签
	public function tagSwitchStatus($TagInfo)
	{
		Auth(4);
		if ($this->db->query('UPDATE ' . PREFIX . 'tags SET IsEnabled = :IsEnabled WHERE ID=:TagID', array(
			'TagID' => $this->id,
			'IsEnabled' => $TagInfo['IsEnabled'] ? 0 : 1 //Bool -> Int
		))) {
			//更新话题统计数据
			$NewConfig = array(
				"NumTags" => $this->config["NumTags"] + ($TagInfo['IsEnabled'] ? -1 : 1)
			);
			UpdateConfig($NewConfig);
			$this->message = $TagInfo['IsEnabled'] ? $this->lang['Enable_Tag'] : $this->lang['Disable_Tag'];
		} else {
			AlertMsg('Bad Request', 'Bad Request');
		}
	}
}

$PageTitle   = 'Manage';
$ContentFile = $TemplatePath . 'manage.php';
include($TemplatePath . 'layout.php');