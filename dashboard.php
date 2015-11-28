<?php
error_reporting(0);
include(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/dashboard.php');
if(!array_key_exists('HTTP_MOD_REWRITE', $_SERVER)) die ("Please Enable Mod Rewrite before passing"); //Check for Mod Rewrite
if (file_exists("install")) die ("Please delete the install folder before passing."); //Make sure install does not exist for safety
Auth(5);
$BasicMessage    = '';
$PageMessage     = '';
$AdvancedMessage = '';
$OauthMessage = '';
$CacheMessage    = '';
$Action          = Request('Post', 'Action', false);

$OauthData = json_decode($Config['CacheOauth'], true);
$OauthData = $OauthData?$OauthData:array();
$OauthConfig = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("includes/Oauth.config.json")), true);

switch ($Action) {
	case 'Cache':
		set_time_limit(0);
		UpdateConfig(array(
			'NumFiles' => intval($DB->single('SELECT count(ID) FROM ' . $Prefix . 'upload')),
			'NumTopics' => intval($DB->single('SELECT count(*) FROM ' . $Prefix . 'topics WHERE IsDel=0')),
			'NumPosts' => intval($DB->single('SELECT sum(Replies) FROM ' . $Prefix . 'topics WHERE IsDel=0')),
			'NumUsers' => intval($DB->single('SELECT count(ID) FROM ' . $Prefix . 'users')),
			'NumTags' => intval($DB->single('SELECT count(ID) FROM ' . $Prefix . 'tags')),
			'CacheHotTags' => json_encode($DB->query('SELECT ID,Name,Icon,TotalPosts,Followers FROM ' . $Prefix . 'tags 
				WHERE IsEnabled=1 
				ORDER BY TotalPosts DESC 
				LIMIT ' . $Config['TopicsPerPage']))
		));
		$DB->query('UPDATE ' . $Prefix . 'users u 
			SET u.Topics=(SELECT count(*) FROM ' . $Prefix . 'topics t 
				WHERE t.UserName=u.UserName AND IsDel=0),
			u.Replies=(SELECT count(*) FROM ' . $Prefix . 'posts p 
				WHERE p.UserName=u.UserName AND p.IsTopic=0),
			u.Followers=(SELECT count(*) FROM ' . $Prefix . 'favorites f 
				WHERE f.FavoriteID=u.ID AND Type=3)
		');
		$DB->query('UPDATE ' . $Prefix . 'topics t 
			SET t.Replies=(SELECT count(*) FROM ' . $Prefix . 'posts p 
				WHERE p.TopicID=t.ID AND p.IsTopic=0 AND p.IsDel=0),
			t.Favorites=(SELECT count(*) FROM ' . $Prefix . 'favorites f 
				WHERE f.FavoriteID=t.ID AND Type=1)
		');
		$DB->query('UPDATE ' . $Prefix . 'tags t 
			SET t.TotalPosts=(SELECT count(*) FROM ' . $Prefix . 'topics topic 
				WHERE topic.ID in (
					SELECT TopicID FROM ' . $Prefix . 'posttags p 
					WHERE p.TagID=t.ID) 
				AND topic.IsDel=0),
			t.Followers=(SELECT count(*) FROM ' . $Prefix . 'favorites f 
				WHERE f.FavoriteID=t.ID AND f.Type=2)
		');


		if ($MCache) {
			if (extension_loaded('memcached') || extension_loaded('memcache') || extension_loaded('xcache')) {
				//MemCached or MemCache
				$MCache->flush();
			} elseif (extension_loaded('redis')) {
				//Redis
				//https://github.com/phpredis/phpredis
				$MCache->flushAll();
			}
		}

		$CacheMessage = $Lang['Successfully_Refreshed'];
		break;

	case 'Statistics':
		set_time_limit(0);
		$DB->query('DELETE FROM ' . $Prefix . 'statistics');
		$StatisticsTime = strtotime(date('Y-m-d', $DB->single('SELECT UserRegTime FROM ' . $Prefix . 'users ORDER BY ID ASC LIMIT 1')));
		while ($StatisticsTime < ($TimeStamp - 86400)) {
			$StatisticsTimeAddOneDay = $StatisticsTime + 86400;
			//echo date('Y-m-d', $StatisticsTime);
			//echo '<br />';
			$DB->query('INSERT INTO `' . $Prefix . 'statistics` 
				(
					`DaysUsers`, 
					`DaysPosts`, 
					`DaysTopics`, 
					`TotalUsers`, 
					`TotalPosts`, 
					`TotalTopics`, 
					`DaysDate`, 
					`DateCreated`
				) 
				SELECT 
					(SELECT count(*) FROM ' . $Prefix . 'users u 
						WHERE u.UserRegTime >= ' . $StatisticsTime . ' 
							AND u.UserRegTime < ' . $StatisticsTimeAddOneDay . ' ), 
					(SELECT count(*) FROM ' . $Prefix . 'posts p 
						WHERE p.PostTime >= ' . $StatisticsTime . ' 
							AND p.PostTime < ' . $StatisticsTimeAddOneDay . ' 
							AND p.IsTopic = 0), 
					(SELECT count(*) FROM ' . $Prefix . 'topics t 
						WHERE t.PostTime >= ' . $StatisticsTime . ' 
							AND t.PostTime < ' . $StatisticsTimeAddOneDay . '  
							AND t.IsDel = 0), 
					(SELECT count(*) FROM ' . $Prefix . 'users u 
						WHERE u.UserRegTime < ' . $StatisticsTimeAddOneDay . ' ), 
					 (SELECT count(*) FROM ' . $Prefix . 'posts p 
						WHERE p.TopicID NOT IN (SELECT ID FROM ' . $Prefix . 'topics t 
							WHERE t.PostTime < ' . $StatisticsTimeAddOneDay . ' 
								AND t.IsDel = 1)
							AND p.PostTime < ' . $StatisticsTimeAddOneDay . ' 
							AND p.IsTopic = 0 ), 
					(SELECT count(*) FROM ' . $Prefix . 'topics t 
						WHERE t.PostTime < ' . $StatisticsTimeAddOneDay . ' 
							AND t.IsDel = 0), 
					:DaysDate,
					:DateCreated 
					FROM dual  
					WHERE NOT EXISTS(  
						SELECT *  FROM `' . $Prefix . 'statistics`  
						WHERE DaysDate = :DaysDate2
					)
				', array(
				'DaysDate' => date('Y-m-d', $StatisticsTime),
				'DaysDate2' => date('Y-m-d', $StatisticsTime),
				'DateCreated' => $StatisticsTimeAddOneDay - 1
			));
			$StatisticsTime = $StatisticsTimeAddOneDay;
		}

		$CacheMessage = $Lang['Successfully_Refreshed'];
		break;

	case 'AddOauth':
		$AppName          = $_POST['AppName'];
		$AppKey             = $_POST['AppKey'];	
		$AppSecret         = $_POST['AppSecret'];
		foreach ($AppName as $Key => $Value) {
			if($AppName[$Key] && $AppKey[$Key] && $AppSecret[$Key]){
				if(isset($OauthData[$Value])){
					$DB->query('UPDATE `' . $Prefix . 'app` 
						SET 
							`AppKey` = ?, 
							`AppSecret` = ?, 
							`Time` = ?
						WHERE `AppName` = ?',
						array(
							$AppKey[$Key],
							$AppSecret[$Key],
							$TimeStamp,
							$AppName[$Key]
							)
					);
				}else{
					$DB->query('INSERT INTO `' . $Prefix . 'app`(ID, AppName, AppKey, AppSecret, Time)
						SELECT ?,?,?,?,?
						FROM dual
						WHERE NOT EXISTS(
							SELECT * 
							FROM `' . $Prefix . 'app`
							WHERE AppName = ?
						);', array(
							null,
							$AppName[$Key],
							$AppKey[$Key],
							$AppSecret[$Key],
							$TimeStamp,
							$AppName[$Key]
						)
					);
				}
			}else{
				if(isset($OauthData[$Value])){
					//$DB->query('DELETE FROM `' . $Prefix . 'app` WHERE AppName = ?;', array($AppName[$Key]));
				}
			}
		}
		$OauthData = array();
		foreach ($DB->query('SELECT * FROM `' . $Prefix . 'app`') as $Value) {
			$OauthData[$Value['AppName']] = $Value;
			$OauthData[$Value['AppName']]['Alias'] = $OauthConfig[$Value['AppName']]['Alias'];
			$OauthData[$Value['AppName']]['LogoUrl'] = $OauthConfig[$Value['AppName']]['LogoUrl'];
			$OauthData[$Value['AppName']]['ButtonImageUrl'] = $OauthConfig[$Value['AppName']]['ButtonImageUrl'];
		}
		UpdateConfig(array('CacheOauth' => json_encode($OauthData)));
		$OauthMessage = $Lang['Oauth_Settings_Successfully_Saved'];
		break;
	default:
		$NewConfig = $_POST;
		//Fool-proofing
		if ($Action == 'Basic') {
			$NewConfig['TopicsPerPage'] = intval(Request('Post', 'TopicsPerPage', 20));
			$NewConfig['PostsPerPage']  = intval(Request('Post', 'PostsPerPage', 20));
			$NewConfig['MaxTagsNum']    = intval(Request('Post', 'MaxTagsNum', 5));
			$NewConfig['MaxTagChars']   = intval(Request('Post', 'MaxTagChars', 128));
			$NewConfig['MaxPostChars']  = intval(Request('Post', 'MaxPostChars', 65536));
		}
		//Fool-proofing
		if ($Action == 'Advanced') {
			if ($NewConfig['MobileDomainName'] == $_SERVER['HTTP_HOST']) {
				$NewConfig['MobileDomainName'] = $Config['MobileDomainName'];
			}
			if ($NewConfig['AppDomainName'] == $_SERVER['HTTP_HOST']) {
				$NewConfig['AppDomainName'] = $Config['AppDomainName'];
			}
		}
		foreach ($NewConfig as $Key => $Value) {
			if (!array_key_exists($Key, $Config) || $Value == $Config[$Key]) {
				unset($NewConfig[$Key]);
			} else {
				$Config[$Key] = $NewConfig[$Key];
			}
		}
		UpdateConfig($NewConfig);
		switch ($Action) {
			case 'Basic':
				$BasicMessage = str_replace('{{NewConfig}}', count($NewConfig), $Lang['Basic_Settings_Successfully_Saved']);
				break;
			case 'Page':
				$PageMessage = str_replace('{{NewConfig}}', count($NewConfig), $Lang['Page_Settings_Successfully_Saved']);
				break;
			case 'Advanced':
				$AdvancedMessage = str_replace('{{NewConfig}}', count($NewConfig), $Lang['Advanced_Settings_Successfully_Saved']);
				break;
			default:
				break;
		}
		break;
}

$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['System_Settings'];
$ContentFile = $TemplatePath . 'dashboard.php';
include($TemplatePath . 'layout.php');
