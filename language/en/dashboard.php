<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Basic_Settings' => 'Basic Settings',
	'Page_Settings' => 'Page Settings',
	'Advanced_Settings' => 'Advanced Settings',
	'Refresh_Cache' => 'Refresh Cache',

	'Forum_Name' => 'Forum Name',
	'Forum_Descriptions' => 'Forum Descriptions<br /><br />Which are shown to the Search Engine.<br />Less than 150 characters. ',

	'Page_Show' => 'Show ',
	'Page_Topics' => ' topics per page. ',

	'Topic_Show' => 'Show ',
	'Topic_Posts' => ' posts per topic. ',

	'Topic_Max' => 'Up to ',
	'Topic_Max_Tags' => ' tags per topic. ',

	'Tag_Max' => 'Up to ',
	'Tag_Max_Chars' => ' characters per tag. ',

	'Post_Max' => 'Up to ',
	'Post_Max_Chars' => ' characters per post.',

	'Save' => 'Save settings',

	'Html_Between_Head' => 'Content between &lt;Head&gt;',
	'Html_Before_Body' => 'Content before &lt;/Body&gt;<br />Generally placed statistical code of Google Analytics. ',
	'Html_SiderBar' => 'Content of Siderbar',

	'jQuery_CDN' => 'jQuery URL (CDN)',
	'Main_Domainname' => 'PC Domainname',
	'Mobile_Domainname' => 'Mobile Domainname',
	'API_Domainname' => 'API Domainname',

	'Push_Connection_Timeout_Period' => 'Push Connection Timeout Period<br />(Don\'t modify it if you don\'t know what\'s this.)',
	'SMTP_Host' => 'SMTP Host Name',
	'SMTP_Port' => 'SMTP Port',
	'SMTP_Auth' => 'SMTP Auth',
	'SMTP_Username' => 'Email Address',
	'SMTP_Password' => 'Email Password',

	'Oauth_Settings' => 'oAuth Settings',
	'App_Key' => 'App Key',
	'App_Secret' => 'App Secret',
	'Callback_URL' => 'Callback URL',

	'Refresh_Cache_Notice' => 'Refreshing cache may take a long time, so please be patient. <br />If the statistics are correct, there is no need to refresh the cache. ',
	'Refresh_All_Cache' => 'Refresh Cache',

	'Successfully_Refreshed' => 'Success! ',
	'Basic_Settings_Successfully_Saved' => 'Success, {{NewConfig}} items has been modified. ',
	'Page_Settings_Successfully_Saved' => 'Success, {{NewConfig}} items has been modified. ',
	'Oauth_Settings_Successfully_Saved' => 'Success',
	'Advanced_Settings_Successfully_Saved' => 'Success, {{NewConfig}} items has been modified. ',
	));