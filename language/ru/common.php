<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Language' => 'ru',
	'RolesDict' => array('Гость','Участник','VIP','SVP','Вице-Администратор','Системный Администратор'),
	'Error_Message' => 'Информационное сообщение',
	'Error_Unknown_Referer' => 'Неизвестный реферер',
	'Error_Insufficient_Permissions' => 'Эта страница видна только для {{RoleDict}}, у Вас недостаточно разрешений.',
	'Error_Account_navailable' => 'Ваш аккаунт находится на рассмотрении или запрещен, обратитесь к администратору для подтверждения! ',
	'Time_Days_Ago' => 'дней назад',
	'Time_Hours_Ago' => 'часов назад',
	'Time_Minutes_Ago' => 'минут назад',
	'Time_Seconds_Ago' => 'секунд назад',
	'Time_Just_Now' => 'топ',
	'Page_Previous' => 'Назад',
	'Page_Next' => 'Вперед',
	'New_Message' => '({{NewMessage}} новые сообщения)',
	'UserName' => 'Имя пользователя',
	'Password' => 'Пароль',
	'Verification_Code' =>'Код проверки',
	'Log_In' => ' Войти ',
	'Sign_Up' => 'Регистрация',
	'Forgot_Password' => 'Забыли пароль',
	'User_Panel' => 'Профиль',
	'Log_Out' => 'Выйти',
	'Information_Bar' =>'Информационная панель',
	'Hot_Tags'=>'Популярные тэги',
	'Show_More'=>'Показать все',
	'Home' => 'Главная',
	'Search' => 'Поиск',
	'Create_New_Topic' => 'Новая тема',
	'Tags_Followed' => 'Теги',
	'Favorite_Topics' => 'Подписки',
	'Users_Followed' => 'Подписчики',
	'Notifications' => 'Уведомления',
	'Settings' => 'Настройки',
	'System_Settings' => 'Настройки системы',
	'Desktop_Version' => 'Настольная Версия',
	'Mobile_Version' => 'Мобильная Версия',
	'Statistics' => 'Статистика'
));
