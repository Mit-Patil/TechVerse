<?php

session_start();

$image=imagecreatetruecolor(450,50);

$bgcolor=imagecolorallocate($image,255,255,255);

imagefill($image,0,0,$bgcolor);

$textcolor=imagecolorallocate($image,0,0,0);

$captchaString=substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'),0,6);

$_SESSION['captcha']=$captchaString;

imagestring($image,100,30,5,$captchaString,$textcolor);

header('content-type:image/png');
imagepng($image);
imagedestroy($image);
?>
