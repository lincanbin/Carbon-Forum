<?php
error_reporting(0);
require(dirname(__FILE__) . "/config.php");
session_start();
//session_register('VerificationCode');
$_SESSION[$Prefix . 'VerificationCode'] = '';
$width            = '58';//图片宽
$height           = '27';//图片高

$textall = array_merge_recursive(range('0','9'));
for ($i=0; $i<4; $i++) {
    $tmptext=rand(0, 9);
    $randtext = $textall[$tmptext];
    $code .= $randtext;
}

$_SESSION[$Prefix . 'VerificationCode'] = $code;

@header("Expires: -1");
@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
@header("Pragma: no-cache");

if(function_exists('imagecreate') && function_exists('imagecolorset') && function_exists('imagecopyresized') && function_exists('imagecolorallocate') && function_exists('imagesetpixel') && function_exists('imagechar') && function_exists('imagecreatefromgif') && function_exists('imagepng')) {

    $im = imagecreate($width, $height);
    $backgroundcolor = imagecolorallocate ($im, 255, 255, 255);

    $numorder = array(1, 2, 3, 4);
    shuffle($numorder);
    $numorder = array_flip($numorder);

    for($i = 1; $i <= 4; $i++) {
        $x = $numorder[$i] * 13 + mt_rand(0, 4) - 2;
        $y = mt_rand(0, 3);
        $text_color = imagecolorallocate($im, mt_rand(50, 255), mt_rand(50, 128), mt_rand(50, 255));
        imagechar($im, 5, $x + 5, $y + 3, $code[$numorder[$i]], $text_color);
    }
    $linenums = mt_rand(10, 32);
    for($i=0; $i <= $linenums; $i++) {
        $linecolor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
        $linex = mt_rand(0, $width);
        $liney = mt_rand(0, $height);
        imageline($im, $linex, $liney, $linex + mt_rand(0, 4) - 2, $liney + mt_rand(0, 4) - 2, $linecolor);
    }

    for($i=0; $i <= 40; $i++) {
        $pointcolor = imagecolorallocate($im, mt_rand(50, 255), mt_rand(50, 255), mt_rand(50, 255));
        imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pointcolor);
    }

    $bordercolor = imagecolorallocate($im , 150, 150, 150);
    imagerectangle($im, 0, 0, $width-1, $height-1, $bordercolor);

    header('Content-type: image/png');
    imagepng($im);
    imagedestroy($im);

} else {

    $numbers = array
        (
        0 => array('3c','66','66','66','66','66','66','66','66','3c'),
        1 => array('1c','0c','0c','0c','0c','0c','0c','0c','1c','0c'),
        2 => array('7e','60','60','30','18','0c','06','06','66','3c'),
        3 => array('3c','66','06','06','06','1c','06','06','66','3c'),
        4 => array('1e','0c','7e','4c','2c','2c','1c','1c','0c','0c'),
        5 => array('3c','66','06','06','06','7c','60','60','60','7e'),
        6 => array('3c','66','66','66','66','7c','60','60','30','1c'),
        7 => array('30','30','18','18','0c','0c','06','06','66','7e'),
        8 => array('3c','66','66','66','66','3c','66','66','66','3c'),
        9 => array('38','0c','06','06','3e','66','66','66','66','3c')
        );

    for($i = 0; $i < 10; $i++) {
        for($j = 0; $j < 6; $j++) {
            $a1 = substr('012', mt_rand(0, 2), 1).substr('012345', mt_rand(0, 5), 1);
            $a2 = substr('012345', mt_rand(0, 5), 1).substr('0123', mt_rand(0, 3), 1);
            mt_rand(0, 1) == 1 ? array_push($numbers[$i], $a1) : array_unshift($numbers[$i], $a1);
            mt_rand(0, 1) == 0 ? array_push($numbers[$i], $a1) : array_unshift($numbers[$i], $a2);
        }
    }

    $bitmap = array();
    for($i = 0; $i < 20; $i++) {
        for($j = 0; $j < 4; $j++) {
            $n = substr($code, $j, 1);
            $bytes = $numbers[$n][$i];
            $a = mt_rand(0, 14);
            switch($a) {
                case 1: str_replace('9', '8', $bytes); break;
                case 3: str_replace('c', 'e', $bytes); break;
                case 6: str_replace('3', 'b', $bytes); break;
                case 8: str_replace('8', '9', $bytes); break;
                case 0: str_replace('e', 'f', $bytes); break;
            }
            array_push($bitmap, $bytes);
        }
    }

    for($i = 0; $i < 8; $i++) {
        $a = substr('012', mt_rand(0, 2), 1) . substr('012345', mt_rand(0, 5), 1);
        array_unshift($bitmap, $a);
        array_push($bitmap, $a);
    }

    $image = pack('H*', '424d9e000000000000003e000000280000002000000018000000010001000000'.
            '0000600000000000000000000000000000000000000000000000FFFFFF00'.implode('', $bitmap));

    header('Content-Type: image/bmp');
    echo $image;
}