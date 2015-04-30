<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Avatar_Settings' => 'Awatar',
	'Profile_Settings' => 'Profil',
	'Security_Settings' => 'Bezpieczeństwo',
	'Reset_Avatar' => 'Zresetować Avatar',
	'You_Can_Replace_Your_Avatar_Here' => 'Możesz tutaj zmienić awatar<br /><br />Dozwolone formaty jpg/jpeg/png/gif',
	'Max_Avatar_Size_Limit' => 'Awatar nie może ważyć więcej niż 1MB',
	'Upload_Avatar' => 'Wrzuć awatar',

	'Do_Not_Modify' => 'Nie wybrano',
	'User_Sex' => 'Płeć',
	'Sex_Unknown' => 'Nieznane',
	'Sex_Male' => 'Mężczyzna',
	'Sex_Female' => 'Kobieta',
	'Email' => 'E-mail',
	'Ensure_That_Email_Is_Correct' => 'Upewnij się, że e-mail jest poprawny',
	'Homepage' => 'Strona',
	'Introduction' => 'O sobie',
	'Save_Settings' => 'Zapisz zmiany',
	'Current_Password' => 'Obecne hasło',
	'New_Password' => 'Nowe hasło',
	'Confirm_New_Password' => 'Powtórz nowe hasło',
	'Change_Password' => 'Zmień hasło',

	'Avatar_Upload_Success' => 'Sukces',
	'Avatar_Upload_Failure' => 'Błąd',
	'Avatar_Is_Oversize' => 'Awarat jest za duży',

	'Profile_Modified_Successfully' => 'Sukces',
	'Profile_Do_Not_Modify' => 'Nie zmieniono danych',

	'Change_Password_Success' => 'Hasło zostało zmienione. Pamiętaj o nowym haśle.',
	'Change_Password_Failure' => 'Wystąpił błąd',
	'Password_Do_Not_Modify' => 'Nowe hasło nie może być takie same ',
	'Current_Password_Is_Uncorrect' => 'Sktualne hasło jest niepoprawne',
	'Passwords_Inconsistent' => 'Hasła nie są takie same',
	'Forms_Can_Not_Be_Empty' => 'Pola muszą być uzupełnione'
	));