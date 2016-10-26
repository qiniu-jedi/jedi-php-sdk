<?php

include_once 'config.php';

use Jedi;

$videoKey = 'qiniu.mp4';
$videoFilePath = "/Users/jemy/Documents/qiniu.mp4";
$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);
$upTokenResult = $jediManager->getUpToken($hub);
if (!empty($upTokenResult['uptoken'])) {
    $upToken = $upTokenResult['uptoken'];
    try {
        $uploadResult = $jediManager->uploadVideoFile($upToken, $videoKey, $videoFilePath);
        print_r($uploadResult[0]);
    } catch (Exception $ex) {
        print("upload file failed");
    }
} else {
    print("get uptoken error");
}
