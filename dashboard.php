<?php
include(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/dashboard.php');
Auth(5);
$BasicMessage    = '';
$PageMessage     = '';
$AdvancedMessage = '';
$CacheMessage    = '';
$Action          = Request('POST', 'Action', false);

switch ($Action) {
	case 'Cache':
		UpdateConfig(array(
			'NumFiles' => $DB->single('SELECT count(ID) FROM ' . $Prefix . 'upload'),
			'NumTopics' => $DB->single('SELECT count(*) FROM ' . $Prefix . 'topics WHERE IsDel=0'),
			'NumPosts' => $DB->single('SELECT sum(Replies) FROM ' . $Prefix . 'topics WHERE IsDel=0'),
			'NumUsers' => $DB->single('SELECT count(ID) FROM ' . $Prefix . 'users'),
			'NumTags' => $DB->single('SELECT count(ID) FROM ' . $Prefix . 'tags')
		));
		$DB->query('UPDATE ' . $Prefix . 'users u SET u.Topics=(SELECT count(*) FROM ' . $Prefix . 'topics t WHERE t.UserName=u.UserName),u.Replies=(SELECT count(*) FROM ' . $Prefix . 'posts p WHERE p.UserName=u.UserName and p.IsTopic=0),u.Followers=(SELECT count(*) FROM ' . $Prefix . 'favorites f WHERE f.FavoriteID=u.ID and Type=3)');
		$DB->query('UPDATE ' . $Prefix . 'topics t SET t.Replies=(SELECT count(*) FROM ' . $Prefix . 'posts p WHERE p.TopicID=t.ID and p.IsTopic=0 and p.IsDel=0),t.Favorites=(SELECT count(*) FROM ' . $Prefix . 'favorites f WHERE f.FavoriteID=t.ID and Type=1)');
		$DB->query('UPDATE ' . $Prefix . 'tags t SET t.TotalPosts=(SELECT count(*) FROM ' . $Prefix . 'posttags p WHERE p.TagID=t.ID),t.Followers=(SELECT count(*) FROM ' . $Prefix . 'favorites f WHERE f.FavoriteID=t.ID and Type=2)');
		if($MCache){
			$MCache -> flush();
		}
		$CacheMessage = $Lang['Successfully_Refreshed'];
		break;
	
	default:
		$NewConfig = $_POST;
		//Fool-proofing
		if($Action == 'Advanced'){
			if($NewConfig['MobileDomainName'] == $_SERVER ['HTTP_HOST']){
				$NewConfig['MobileDomainName'] = $Config['MobileDomainName'];
			}
			if($NewConfig['AppDomainName'] == $_SERVER ['HTTP_HOST']){
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