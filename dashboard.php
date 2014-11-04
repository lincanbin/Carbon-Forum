<?php
include(dirname(__FILE__) . '/common.php');
Auth(5);
$BasicMessage = '';
$PageMessage = '';
$AdvancedMessage = '';
$CacheMessage = '';
$Action = Request('POST','Action',false);

switch ($Action) {
	case 'Cache':
		UpdateConfig(array(
			'NumFiles' => $DB->single('SELECT count(ID) FROM '.$Prefix.'upload'),
			'NumTopics' => $DB->single('SELECT count(ID) FROM '.$Prefix.'topics'),
			'NumPosts' => $DB->single('SELECT count(ID) FROM '.$Prefix.'posts')-$Config['NumTopics'],
			'NumUsers' => $DB->single('SELECT count(ID) FROM '.$Prefix.'users'),
			'NumTags' => $DB->single('SELECT count(ID) FROM '.$Prefix.'tags')
			)
		);
		$CacheMessage = '缓存更新成功';
		break;
	
	default:
		$NewConfig = $_POST;
		foreach ($NewConfig as $Key => $Value)
		{
			if(!array_key_exists($Key, $Config) || $Value == $Config[$Key])
			{
				unset($NewConfig[$Key]);
			}else{
				$Config[$Key] = $NewConfig[$Key];
			}
		}
		UpdateConfig($NewConfig);
		switch ($Action) {
			case 'Basic':
				$BasicMessage = '基本设置修改成功，'.count($NewConfig).'项已修改';
				break;
			case 'Page':
				$PageMessage = '页面设置修改成功，'.count($NewConfig).'项已修改';
				break;
			case 'Advanced':
				$AdvancedMessage = '高级设置修改成功，'.count($NewConfig).'项已修改';
				break;
			default:
				break;
		}
		break;
}


$DB->CloseConnection();
// 页面变量
$PageTitle = '系统设置';
$ContentFile = $TemplatePath.'dashboard.php';
include($TemplatePath.'layout.php');
?>