<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Language' => 'pl',
	'RolesDict' => array('Odwiedzający','Użytkownik','VIP','SVP','Administrator','Administrator Systemu'),
	'Error_Message' => 'Widomość błędu',
	'Error_Unknown_Referer' => 'Unknown referer',
	'Error_Insufficient_Permissions' => 'Ta strona jest widoczna tylko dla {{RoleDict}}. Nie posiadasz odpowiednich uprawnień.',
	'Error_Account_navailable' => 'Twoje konto nie zostało jest zweryfikowane, lub zbanowane. Skontaktuj się z administratorem w celu zatwierdzenia! ',
	'Time_Days_Ago' => 'dni temu',
	'Time_Hours_Ago' => 'godzin temu',
	'Time_Minutes_Ago' => 'minut temu',
	'Time_Seconds_Ago' => 'sekund temu',
	'Time_Just_Now' => 'przed chwilą',
	'Page_Previous' => 'Poprzedni',
	'Page_Next' => 'Następny',
	'New_Message' => '({{NewMessage}} Nowych wiadomości)',
	'UserName' => 'Nazwa Użytkownika',
	'Password' => 'Hasło',
	'Verification_Code' =>'Kod weryfikacyjny',
	'Log_In' => ' Logowanie ',
	'Sign_Up' => 'Rejestracja',
	'User_Panel' => 'Panel Użytkownika',
	'Log_Out' => 'Wyloguj',
	'Information_Bar' =>'Informacje',
	'Home' => 'Strona Główna',
	'Search' => 'Szukaj',
	'Create_New_Topic' => 'Stwórz nowy temat',
	'Tags_Followed' => 'Śledzone tagi',
	'Favorite_Topics' => 'Ulubione',
	'Users_Followed' => 'Śledzący',
	'Notifications' => 'Powiadomienia',
	'Settings' => 'Ustawienia',
	'System_Settings' => 'Ustawienia systemowe',
	'Desktop_Version' => 'Wersja na pulpicie',
	'Mobile_Version' => 'Wersja mobilna'
));