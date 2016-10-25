<?php

include_once '../Jedi/JediAuth.php';
include_once '../Jedi/JediManager.php';
include_once '../../vendor/qiniu/php-sdk/autoload.php';
include_once 'config.php';

use Qiniu\Jedi;

$videoKey = 'qiniu.mp4';
$videoFilePath = "/Users/jemy/Documents/qiniu.mp4";
$extraParams=array(
    "x:name"=>"qiniu",
    "x:type"=>"mp4",
    "x:year"=>"2016",
);
$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);
$upTokenResult = $jediManager->getUpToken($hub);
if (!empty($upTokenResult['uptoken'])) {
    $upToken = $upTokenResult['uptoken'];
    try {
        $uploadResult = $jediManager->uploadVideoFile($upToken, $videoKey, $videoFilePath,$extraParams);
        print_r($uploadResult[0]);
    } catch (Exception $ex) {
        print("upload file failed");
    }
} else {
    print("get uptoken error");
}
