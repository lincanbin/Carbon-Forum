<?php
if (PHP_SAPI !== "cli") {
	exit('error: 403 Access Denied');
}

if (PHP_OS === "WINNT") {
	exec('chcp 65001');
}

define('CURRENT_LANGUAGE', 'zh-cn');
require('../config.php');
require(LibraryPath . 'vendor/autoload.php');

use Stichoza\GoogleTranslate\TranslateClient;

$LanguageList = [
	'zh-cn',
	'zh-tw',
	'en',
	'pl',
	'ru'
];

/*
function RefreshLanguageList($input) {
	global $LanguageList;
	$language_name = explode("/", $input)[1];
	if (!empty($language_name) && !in_array($language_name, $LanguageList)) 
		$LanguageList[] = $language_name;
}
*/
function ListDir($dir)
{
	global $LanguageList;
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if ((is_dir($dir . "/" . $file)) && $file != "." && $file != "..") {
					// RefreshLanguageList($dir);
					ListDir($dir . $file . "/");
				} else {
					if ($file != "." && $file != "..") {
						$file_path = $dir . $file;
						$file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
						echo $file_path . "\n\n\n";

						switch ($file_extension) {
							// PHP语言文件翻译
							case "php":
								$template = __DIR__ . '/language_template.php';

								require($file_path);
								$ChineseLang = $Lang;
								unset($Lang);

								foreach ($LanguageList as $language_name) {
									if ($language_name !== CURRENT_LANGUAGE) {
										$language_path = str_ireplace('/' . CURRENT_LANGUAGE . '/', '/' . $language_name . '/', $file_path);
										if ((@include $language_path) != 1) {
											copy($template, $language_path);
											include $language_path;
										}
										$CurrentLang = $Lang;
										unset($Lang);

										$CompareFlag = false;
										translate($ChineseLang, $CurrentLang, $CompareFlag, $language_name);
										if ($CompareFlag) {
											file_put_contents($language_path, str_replace('[]', var_export($CurrentLang, true), file_get_contents($template)));
										}
									}
								}
								break;
							// JS语言文件翻译
							case "js":
								switch ($file) {
									case CURRENT_LANGUAGE . ".js":
										$template = __DIR__ . '/ue_template.js';
										break;
									default:
										$template = __DIR__ . '/language_template.js';
										break;
								}

								preg_match('/\{(?:[^{}]|(?R))*\}/x', file_get_contents($file_path), $matches);
								if (empty($matches)) {
									echo 'Resolve failed.';
									break;
								}
								$ChineseLang = json_decode($matches[0], true);
								if ($ChineseLang === false) {
									echo 'Resolve failed.';
									break;
								}

								foreach ($LanguageList as $language_name) {
									if ($language_name !== CURRENT_LANGUAGE) {
										$language_path = str_ireplace('/' . CURRENT_LANGUAGE . '/', '/' . $language_name . '/', $file_path);
										if (!file_exists($language_path)) {
											copy($template, $language_path);
											$matches2 = [[]];
										} else {
											preg_match('/\{(?:[^{}]|(?R))*\}/x', file_get_contents($language_path), $matches2);
										}
										$CurrentLang = json_decode($matches2[0], true);
										if ($CurrentLang === false) {
											echo 'Resolve failed.';
											break;
										}

										$CompareFlag = false;
										translate($ChineseLang, $CurrentLang, $CompareFlag, $language_name);
										if ($CompareFlag) {
											switch ($file) {
												case "global.js":
													file_put_contents($language_path, str_replace('{}', json_encode($CurrentLang, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), file_get_contents($template)));
													break;
												case CURRENT_LANGUAGE . ".js":
													file_put_contents($language_path, str_replace('{language}', $language_name, str_replace('{}', json_encode($CurrentLang, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), file_get_contents($template))));
													break;
												default:
													break;
											}

										}
									}
								}
								break;
							// 图片等其他语言文件无法翻译，直接复制
							default:
								foreach ($LanguageList as $language_name) {
									if ($language_name !== CURRENT_LANGUAGE) {
										$language_path = str_ireplace('/' . CURRENT_LANGUAGE . '/', '/' . $language_name . '/', $file_path);
										if (!file_exists($language_path)) {
											echo "\n\n\033[32m ---- Copy language file to $language_name ---- \033[0m\n\n";
											copy($file_path, $language_path);
										}
									}
								}
								break;
						}

					}
				}
			}
			closedir($dh);
		}
	}
}

function translate(&$ChineseLang, &$CurrentLang, &$CompareFlag, $language_name)
{
	$diff = array_diff_key($ChineseLang, $CurrentLang);
	if (!empty($diff)) {
		if ($CompareFlag === false) {
			echo "\n\n\033[32m ---- Translating to $language_name ---- \033[0m\n\n";
		}
		$tr = new TranslateClient('zh-cn', $language_name);
		foreach ($diff as $key => $value) {
			if (is_string($value)) {
				$pre_translated_string = $tr->translate($value);
				preg_match_all("/{{[^}]+}}/", $value, $matches);
				preg_match_all("/{{[^}]+}}/", $pre_translated_string, $matches2);
				$translated_string = str_replace($matches2[0], $matches[0], $pre_translated_string);
				echo "\n";
				echo $translated_string;
				echo "\n\n";
				$CurrentLang[$key] = $translated_string;
				$CompareFlag = true; //有差异标志位，要写入文件
			}
		}
	}
	foreach ($ChineseLang as $Key => &$SubLang) {
		if (is_array($SubLang)) {
			if (!isset($CurrentLang[$Key])) {
				$CurrentLang[$Key] = array();
			}
			translate($SubLang, $CurrentLang[$Key], $CompareFlag, $language_name);
		}
	}
	return $CompareFlag;
}

ListDir("./" . CURRENT_LANGUAGE . "/");
var_export($LanguageList);