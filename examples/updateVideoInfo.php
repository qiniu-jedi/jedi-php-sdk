<?php

include_once 'config.php';

$videoKey = 'qiniu.mp4';

$jediAuth = new Jedi\JediAuth($ak, $sk);
$jediManager = new Jedi\JediManager($jediAuth);

$videoName = 'qiniu promotion video';
$videoTags = array('qiniu','cloud','storage','jedi');
$videoDesc = 'this is a test video';
$result = $jediManager->updateVideoInfo($hub, $videoKey, $videoName, $videoTags, $videoDesc);
print($result['result'] == TRUE);
print_r($result['response']);

