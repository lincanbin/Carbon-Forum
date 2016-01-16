<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Basic_Settings' => 'Основные настройки',
	'Page_Settings' => 'Реклама',
	'Advanced_Settings' => 'Доп. настройки',
	'Refresh_Cache' => 'Обновить кэш',

	'Forum_Name' => 'Название форума',
	'Forum_Descriptions' => 'Описание форума<br /><br />Для поисковых систем.<br />Не менее чем 150 символов. ',

	'Page_Show' => 'Показать ',
	'Page_Topics' => ' тем на странице. ',

	'Topic_Show' => 'Показать ',
	'Topic_Posts' => ' сообщений в теме. ',

	'Topic_Max' => 'До ',
	'Topic_Max_Tags' => ' тэгов на тему. ',

	'Tag_Max' => 'До ',
	'Tag_Max_Chars' => ' символов в теге. ',

	'Post_Max' => 'До ',
	'Post_Max_Chars' => ' символов в сообщении.',

	'Save' => 'Сохранить настройки',

	'Html_Between_Head' => 'Контент перед &lt;Head&gt;',
	'Html_Before_Body' => 'Контент перед &lt;/Body&gt;<br />Как правило размещен статистический код Google Analytics. ',
	'Html_SiderBar' => 'Содержание Siderbar',

	'jQuery_CDN' => 'jQuery URL (CDN)',
	'Main_Domainname' => 'PC Domainname',
	'Mobile_Domainname' => 'Mobile Domainname',
	'API_Domainname' => 'API Domainname',

	'Push_Connection_Timeout_Period' => 'Время ожидания Push подключения<br />(Не изменяйте это, если вы не заете, что это.)',
	'SMTP_Host' => 'SMTP Host Name',
	'SMTP_Port' => 'SMTP Порт',
	'SMTP_Auth' => 'SMTP Auth',
	'SMTP_Username' => 'Email адрес',
	'SMTP_Password' => 'Email пароль',

	'Oauth_Settings' => 'oAuth настройки',
	'App_Key' => 'App ключ',
	'App_Secret' => 'App секрет',
	'Callback_URL' => 'Ссылка обратной связи',

	'Refresh_Cache_Notice' => 'Обновление кэша может занять некоторое время, так что будьте терпеливы. <br />Если статистические данные являются правильными, то не нужно обновлять кэш. ',
	'Refresh_All_Cache' => 'Обновить кэш',

	'Successfully_Refreshed' => 'Успешно! ',
	'Basic_Settings_Successfully_Saved' => 'Успешное изменение настроек {{NewConfig}}. ',
	'Page_Settings_Successfully_Saved' => 'Успешное изменение настроек {{NewConfig}}. ',
	'Oauth_Settings_Successfully_Saved' => 'Успешно',
	'Advanced_Settings_Successfully_Saved' => 'Успешное изменение настроек {{NewConfig}}. ',
	));
