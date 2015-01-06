<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Basic_Settings' => 'Podstawowe',
	'Page_Settings' => 'Ustawienia',
	'Advanced_Settings' => 'Zaawansowane',
	'Refresh_Cache' => 'Odśwież Cache',

	'Forum_Name' => 'Nazwa Forum',
	'Forum_Descriptions' => 'Opis Forum <br /><br />Pokaże się w opisie w wyszukiwarkach.<br />Mniej niż 150 znaków. ',

	'Page_Show' => 'Pokaż ',
	'Page_Topics' => ' tematów na stronę. ',

	'Topic_Show' => 'Pokaż ',
	'Topic_Posts' => ' postów na stronę. ',

	'Topic_Max' => 'Do ',
	'Topic_Max_Tags' => ' tagów na temat. ',

	'Tag_Max' => 'Do ',
	'Tag_Max_Chars' => ' znaków na tag. ',

	'Post_Max' => 'Do ',
	'Post_Max_Chars' => ' znaków na post.',

	'Save' => 'Zapisz zmiany',

	'Html_Between_Head' => 'Zawrtość pod &lt;Head&gt;',
	'Html_Before_Body' => 'Zawartość przed &lt;/Body&gt;<br />Generalnie tutaj daje się statystyki Google. ',
	'Html_SiderBar' => 'Content of Siderbar',

	'jQuery_CDN' => 'jQuery URL (CDN)',
	'Main_Domainname' => 'Nazwa domeny PC(?)',
	'Mobile_Domainname' => 'Nazwa domeny na telefonie(?)',
	'API_Domainname' => 'Domena API',

	'Refresh_Cache_Notice' => 'Odświeżanie Cache może chwilę zająć, więc bądź cierpliwy. <br />Jeżeli statystyki są poprawne - nie musisz tego robić. ',
	'Refresh_All_Cache' => 'Odśwież Cache',

	'Successfully_Refreshed' => 'Sukces! ',
	'Basic_Settings_Successfully_Saved' => 'Sukces, {{NewConfig}} zostały zapisane. ',
	'Page_Settings_Successfully_Saved' => 'Sukces, {{NewConfig}} zostały zmodyfikowane. ',
	'Advanced_Settings_Successfully_Saved' => 'Sukces, {{NewConfig}} zostały zmodyfikowane. ',
	));