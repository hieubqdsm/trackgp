<?php
/**
 * Created by PhpStorm.
 * User: hieubq
 * Date: 17/03/2017
 * Time: 13:07
 */
$myFile = fopen("ajax.txt", "w") or die("Unable to open file!");
$now = strtotime('NOW');
$txt = "$now \n";
fwrite($myFile, $txt);
$txt = "$now \n";
fwrite($myFile, $txt);
fclose($myFile);