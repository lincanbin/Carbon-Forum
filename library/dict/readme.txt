文件说明：

1、base_dic_full.dic
hash索引 -- 字典带有词频和词性标志。

2、words_addons.dic
s 开头的表示停止词 u 后缀词（地名后缀、数学单位等） n 前导词（姓、汉字数词等） a 后导词(地区,部门等)

3、 not-build/base_dic_full.txt
没编译过的词典源码

4、重新编译词典的方法：

<?php

header('Content-Type: text/html; charset=utf-8');

require_once('phpanalysis.class.php');

$pa = new PhpAnalysis('utf-8', 'utf-8', false);
$pa->MakeDict( sourcefile,  16 , 'dict/base_dic_full.dic');

echo "OK";

?>